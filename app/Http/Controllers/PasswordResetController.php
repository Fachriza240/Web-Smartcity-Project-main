<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class PasswordResetController extends Controller
{
    public function request()
    {
        return view('authorized.lupa-password');
    }

    public function email(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'string', 'email:rfc', 'min:6', 'max:254'],
        ]);

        try {
            $status = Password::sendResetLink([User::emailCredential($data['email'])]);
        } catch (TransportExceptionInterface $e) {
            report($e);

            return back()->withInput()->withErrors([
                'email' => 'Email reset password gagal dikirim karena gangguan server email. Silakan coba beberapa saat lagi atau hubungi Admin.',
            ]);
        }

        if ($status === Password::RESET_THROTTLED) {
            return back()->withInput()->withErrors(['email' => __($status)]);
        }

        return back()->with('success', 'Jika email tersebut terdaftar, tautan reset password telah dikirim. Silakan periksa kotak masuk email Anda.');
    }

    public function edit(Request $request, string $token)
    {
        return view('authorized.reset-password', [
            'token' => $token,
            'email' => (string) $request->query('email', ''),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'string', 'email:rfc', 'min:6', 'max:254'],
            'password' => ['required', 'string', 'min:8', 'max:64', 'confirmed'],
        ]);

        $status = Password::reset(
            [
                User::emailCredential($data['email']),
                'password' => $data['password'],
                'token' => $data['token'],
            ],
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => $password,
                    'remember_token' => Str::random(60),
                    'locked_until' => null,
                ])->save();
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
        }

        return redirect()->route('login')
            ->with('success', 'Password berhasil diubah. Silakan login dengan password baru Anda.');
    }
}
