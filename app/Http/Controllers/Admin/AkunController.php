<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrustedDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AkunController extends Controller
{
    public function index()
    {
        $this->authorizeStaff();

        return view('admin.akun.index', ['user' => Auth::user()]);
    }

    public function updateEmail(Request $request)
    {
        $this->authorizeStaff();

        $user = Auth::user();

        $request->merge(['email' => mb_strtolower(trim((string) $request->input('email')))]);

        $data = $request->validateWithBag('akunEmail', [
            'email' => [
                'required',
                'string',
                'email:rfc',
                'min:6',
                'max:254',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'current_password' => ['required', 'string', 'current_password'],
        ], [
            'email.unique' => 'Email tersebut sudah digunakan oleh akun lain.',
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
        ], [
            'email' => 'Email',
            'current_password' => 'Password saat ini',
        ]);

        if ($data['email'] === mb_strtolower((string) $user->email)) {
            return back()->with('info', 'Email baru sama dengan email Anda saat ini, tidak ada yang diubah.');
        }

        $user->forceFill(['email' => $data['email']])->save();

        return redirect()->route('admin.akun')->with('success', 'Email berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $this->authorizeStaff();

        $user = Auth::user();

        $data = $request->validateWithBag('akunPassword', [
            'current_password' => ['required', 'string', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'max:64', 'confirmed', 'different:current_password'],
        ], [
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'password.different' => 'Password baru harus berbeda dari password saat ini.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ], [
            'current_password' => 'Password saat ini',
            'password' => 'Password baru',
        ]);

        $user->forceFill([
            'password' => $data['password'],
            'remember_token' => Str::random(60),
        ])->save();

        TrustedDevice::forgetFor($user);

        $request->session()->regenerate();

        return redirect()->route('admin.akun')->with('success', 'Password berhasil diperbarui.');
    }

    private function authorizeStaff(): void
    {
        if (! Auth::check() || ! in_array(Auth::user()->role, ['admin', 'content_creator'], true)) {
            abort(403);
        }
    }
}