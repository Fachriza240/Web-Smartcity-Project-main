<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\CompletesLogin;
use App\Models\LoginConfirmation;
use App\Models\SuspiciousLoginReport;
use App\Models\User;
use App\Notifications\LoginConfirmationNotification;
use App\Notifications\SuspiciousLoginNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class LoginConfirmationController extends Controller
{
    use CompletesLogin;

    public function show(Request $request)
    {
        $confirmation = $this->current($request);

        if (! $confirmation) {
            return Auth::check() ? $this->redirectByRole(Auth::user()) : redirect()->route('login');
        }

        return match ($confirmation->state()) {
            LoginConfirmation::STATUS_PENDING, LoginConfirmation::STATUS_APPROVED => view('authorized.konfirmasi-login.menunggu', [
                'confirmation' => $confirmation,
                'email' => $this->maskEmail($confirmation->user->email),
                'approved' => $confirmation->state() === LoginConfirmation::STATUS_APPROVED,
            ]),
            LoginConfirmation::STATUS_REJECTED => $this->leave($request, 'error', 'Proses login dibatalkan karena pemilik akun memilih "Bukan Saya" pada email konfirmasi login. Akun dikunci sementara demi keamanan.'),
            LoginConfirmation::STATUS_EXPIRED => $this->leave($request, 'warning', 'Waktu konfirmasi login telah habis. Silakan login kembali.'),
            default => $this->leave($request, 'info', 'Permintaan login sudah tidak berlaku. Silakan login kembali.'),
        };
    }

    public function status(Request $request): JsonResponse
    {
        return response()->json([
            'status' => $this->current($request)?->state() ?? 'missing',
        ]);
    }

    public function continue(Request $request)
    {
        $confirmation = $this->current($request);

        if (! $confirmation) {
            return redirect()->route('login');
        }

        $state = $confirmation->state();

        if ($state === LoginConfirmation::STATUS_APPROVED) {
            return $this->finish($request, $confirmation);
        }

        if ($state === LoginConfirmation::STATUS_PENDING) {
            return redirect()->route('login.konfirmasi.menunggu')
                ->withErrors(['konfirmasi' => 'Login belum dikonfirmasi. Buka email Anda lalu pilih "Ya, Ini Saya".']);
        }

        return redirect()->route('login.konfirmasi.menunggu');
    }

    public function resend(Request $request)
    {
        $confirmation = $this->current($request);

        if (! $confirmation || $confirmation->state() !== LoginConfirmation::STATUS_PENDING) {
            return redirect()->route('login.konfirmasi.menunggu');
        }

        $key = 'login-confirmation-resend:'.$confirmation->id;

        if (RateLimiter::tooManyAttempts($key, 1)) {
            $detik = RateLimiter::availableIn($key);

            return redirect()->route('login.konfirmasi.menunggu')
                ->withErrors(['konfirmasi' => "Tunggu {$detik} detik sebelum mengirim ulang email konfirmasi."]);
        }

        $token = $confirmation->refreshToken();

        try {
            $confirmation->user->notify(new LoginConfirmationNotification($confirmation, $token));
        } catch (TransportExceptionInterface $e) {
            report($e);

            return redirect()->route('login.konfirmasi.menunggu')
                ->withErrors(['konfirmasi' => 'Email konfirmasi gagal dikirim ulang karena gangguan server email. Silakan coba beberapa saat lagi.']);
        }

        RateLimiter::hit($key, 60);

        return redirect()->route('login.konfirmasi.menunggu')
            ->with('success', 'Email konfirmasi login telah dikirim ulang. Tautan pada email sebelumnya sudah tidak berlaku.');
    }

    public function cancel(Request $request)
    {
        $confirmation = $this->current($request);

        if ($confirmation && in_array($confirmation->status, [LoginConfirmation::STATUS_PENDING, LoginConfirmation::STATUS_APPROVED], true)) {
            $confirmation->forceFill(['status' => LoginConfirmation::STATUS_CANCELLED])->save();
        }

        $request->session()->forget(LoginConfirmation::SESSION_KEY);

        return redirect()->route('login')->with('info', 'Login dibatalkan.');
    }

    public function review(string $token, string $aksi)
    {
        $confirmation = LoginConfirmation::findByToken($token);

        if (! $confirmation || $confirmation->state() !== LoginConfirmation::STATUS_PENDING) {
            return $this->outcome($confirmation);
        }

        return view('authorized.konfirmasi-login.tinjau', [
            'confirmation' => $confirmation,
            'token' => $token,
            'aksi' => $aksi,
        ]);
    }

    public function approve(Request $request, string $token)
    {
        $confirmation = LoginConfirmation::findByToken($token);

        if (! $confirmation || $confirmation->state() !== LoginConfirmation::STATUS_PENDING) {
            return $this->outcome($confirmation);
        }

        $user = $confirmation->user;

        if (! $user || ! $user->isActive() || $user->isLocked()) {
            $confirmation->forceFill([
                'status' => LoginConfirmation::STATUS_CANCELLED,
                'responded_at' => now(),
            ])->save();

            return view('authorized.konfirmasi-login.hasil', [
                'jenis' => $user && $user->isLocked() ? 'terkunci' : 'nonaktif',
                'pesan' => $user && $user->isLocked() ? $user->lockMessage() : null,
            ]);
        }

        $confirmation->forceFill([
            'status' => LoginConfirmation::STATUS_APPROVED,
            'responded_at' => now(),
        ])->save();

        if ($this->ownsSession($request, $confirmation)) {
            return $this->finish($request, $confirmation);
        }

        return view('authorized.konfirmasi-login.hasil', ['jenis' => 'disetujui']);
    }

    public function reject(Request $request, string $token)
    {
        $confirmation = LoginConfirmation::findByToken($token);

        if (! $confirmation || $confirmation->state() !== LoginConfirmation::STATUS_PENDING) {
            return $this->outcome($confirmation);
        }

        $confirmation->forceFill([
            'status' => LoginConfirmation::STATUS_REJECTED,
            'responded_at' => now(),
        ])->save();

        $user = $confirmation->user;

        if ($user) {
            $user->forceFill([
                'locked_until' => now()->addMinutes(max(1, (int) config('login.lock_minutes', 30))),
                'remember_token' => Str::random(60),
            ])->save();

            LoginConfirmation::cancelPendingFor($user);

            if (config('session.driver') === 'database') {
                DB::table(config('session.table', 'sessions'))->where('user_id', $user->id)->delete();
            }

            $this->reportToAdmins(SuspiciousLoginReport::fromConfirmation($confirmation->fresh('user')));
        }

        if ($this->ownsSession($request, $confirmation)) {
            $request->session()->forget(LoginConfirmation::SESSION_KEY);
        }

        return view('authorized.konfirmasi-login.hasil', ['jenis' => 'ditolak']);
    }

    private function reportToAdmins(SuspiciousLoginReport $report): void
    {
        $admins = User::where('role', 'admin')->get()->filter(fn (User $admin) => $admin->isActive());

        if ($admins->isEmpty()) {
            return;
        }

        try {
            Notification::send($admins, new SuspiciousLoginNotification($report));
        } catch (TransportExceptionInterface $e) {
            report($e);
        }
    }

    private function finish(Request $request, LoginConfirmation $confirmation)
    {
        $user = $confirmation->user;
        $request->session()->forget(LoginConfirmation::SESSION_KEY);

        if (! $user || ! $user->isActive() || $user->isLocked()) {
            $confirmation->forceFill(['status' => LoginConfirmation::STATUS_CANCELLED])->save();

            return redirect()->route('login')->withErrors([
                'email' => $user && $user->isLocked() ? $user->lockMessage() : User::INACTIVE_MESSAGE,
            ]);
        }

        $confirmation->forceFill(['status' => LoginConfirmation::STATUS_USED])->save();

        return $this->completeLogin($request, $user, $confirmation->remember);
    }

    private function current(Request $request): ?LoginConfirmation
    {
        $id = $request->session()->get(LoginConfirmation::SESSION_KEY);

        return $id ? LoginConfirmation::with('user')->find($id) : null;
    }

    private function ownsSession(Request $request, LoginConfirmation $confirmation): bool
    {
        return (int) $request->session()->get(LoginConfirmation::SESSION_KEY) === (int) $confirmation->id;
    }

    private function leave(Request $request, string $type, string $message)
    {
        $request->session()->forget(LoginConfirmation::SESSION_KEY);

        return redirect()->route('login')->with($type, $message);
    }

    private function outcome(?LoginConfirmation $confirmation)
    {
        $jenis = match ($confirmation?->state()) {
            null => 'tidak-valid',
            LoginConfirmation::STATUS_APPROVED, LoginConfirmation::STATUS_USED => 'sudah-disetujui',
            LoginConfirmation::STATUS_REJECTED => 'sudah-ditolak',
            LoginConfirmation::STATUS_EXPIRED => 'kedaluwarsa',
            default => 'dibatalkan',
        };

        return response()->view('authorized.konfirmasi-login.hasil', ['jenis' => $jenis], $confirmation ? 200 : 404);
    }

    private function maskEmail(string $email): string
    {
        [$name, $domain] = array_pad(explode('@', $email, 2), 2, '');
        $visible = mb_substr($name, 0, min(2, mb_strlen($name)));

        return $visible.str_repeat('*', max(3, mb_strlen($name) - mb_strlen($visible))).'@'.$domain;
    }
}
