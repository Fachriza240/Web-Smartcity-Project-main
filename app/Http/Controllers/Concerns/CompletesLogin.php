<?php

namespace App\Http\Controllers\Concerns;

use App\Models\LoginConfirmation;
use App\Models\User;
use App\Notifications\LoginConfirmationNotification;
use App\Notifications\RegistrationStatusNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

trait CompletesLogin
{
    protected function proceedLogin(Request $request, User $user, bool $remember = false)
    {
        if (! config('login.email_confirmation')) {
            return $this->completeLogin($request, $user, $remember);
        }

        $previous = $request->session()->pull(LoginConfirmation::SESSION_KEY);

        if ($previous) {
            LoginConfirmation::whereKey($previous)
                ->whereIn('status', [LoginConfirmation::STATUS_PENDING, LoginConfirmation::STATUS_APPROVED])
                ->update(['status' => LoginConfirmation::STATUS_CANCELLED]);
        }

        [$confirmation, $token] = LoginConfirmation::issue($user, $request, $remember);

        try {
            $user->notify(new LoginConfirmationNotification($confirmation, $token));
        } catch (TransportExceptionInterface $e) {
            report($e);
            $confirmation->delete();

            return redirect()->route('login')
                ->withErrors(['email' => 'Email konfirmasi login gagal dikirim karena gangguan server email. Silakan coba beberapa saat lagi atau hubungi Admin.'])
                ->withInput(['email' => $user->email]);
        }

        $request->session()->put(LoginConfirmation::SESSION_KEY, $confirmation->id);

        return redirect()->route('login.konfirmasi.menunggu');
    }

    protected function completeLogin(Request $request, User $user, bool $remember = false)
    {
        Auth::login($user, $remember);
        $request->session()->regenerate();
        $user->forceFill(['last_login_at' => now()])->save();

        return $this->redirectByRole($user);
    }

    protected function redirectByRole(User $user)
    {
        if (in_array($user->role, ['dosen', 'content_creator'], true)
            && $user->registration_status !== User::STATUS_APPROVED) {
            return redirect()->route('dosen.status');
        }

        $response = match ($user->role) {
            'admin' => redirect('/beranda-admin'),
            'dosen' => redirect('/beranda-dosen'),
            'content_creator' => redirect('/beranda-creator'),
            default => redirect('/'),
        };

        $approval = $user->unreadNotifications()
            ->where('type', RegistrationStatusNotification::class)
            ->get();

        if ($approval->isNotEmpty()) {
            $approval->markAsRead();
            $response->with('success', 'Selamat, registrasi akun Anda telah disetujui oleh Admin.');
        }

        return $response;
    }
}
