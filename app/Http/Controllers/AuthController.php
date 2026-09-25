<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\PersonName;
use App\Rules\SafeText;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('authorized.registrasi');
    }

    public function register(Request $request)
    {
        $role = $request->input('role') === 'content_creator' ? 'content_creator' : 'dosen';

        $rules = [
            'fullname' => ['required', 'string', 'min:3', 'max:100', new PersonName],
            'email' => ['required', 'string', 'email:rfc', 'max:100', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'max:64', 'confirmed'],
            'role' => ['required', 'in:dosen,content_creator'],
            'foto' => ['nullable', 'image', 'max:2048'],
        ];

        if ($role === 'dosen') {
            $rules['nip'] = ['required', 'regex:/^[0-9]+$/', 'digits_between:4,30', 'unique:users,nip'];
            $rules['prodi'] = ['nullable', 'string', 'min:2', 'max:100', new SafeText];
            $rules['fakultas'] = ['nullable', 'string', 'min:2', 'max:100', new SafeText];
        } else {
            $rules['nip'] = ['nullable', 'regex:/^[0-9]+$/', 'digits_between:4,30', 'unique:users,nip'];
        }

        $data = $request->validate($rules);

        $data['fullname'] = preg_replace('/\s+/u', ' ', trim($data['fullname']));
        $data['email'] = strtolower($data['email']);
        $data['role'] = $role;
        $data['registration_status'] = User::STATUS_PENDING;

        if ($role !== 'dosen') {
            unset($data['prodi'], $data['fakultas']);
        }

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto-users', 'public');
        }

        User::create($data);

        $message = $role === 'dosen'
            ? 'Registrasi berhasil. Akun dosen Anda menunggu validasi admin.'
            : 'Registrasi berhasil. Akun Anda menunggu persetujuan admin sebelum bisa digunakan.';

        return redirect()->route('login')->with('success', $message);
    }

    public function showLogin()
    {
        return view('authorized.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email', 'max:100'],
            'password' => ['required', 'string', 'max:64'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Email atau password salah.'])
                ->withInput($request->only('email'));
        }

        $request->session()->regenerate();

        return $this->redirectByRole(Auth::user());
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda berhasil keluar dari akun.');
    }

    private function redirectByRole(User $user)
    {
        if (in_array($user->role, ['dosen', 'content_creator'], true)
            && $user->registration_status !== User::STATUS_APPROVED) {
            return redirect()->route('dosen.status');
        }

        return match ($user->role) {
            'admin' => redirect('/beranda-admin'),
            'dosen' => redirect('/beranda-dosen'),
            'content_creator' => redirect('/beranda-creator'),
            default => redirect('/'),
        };
    }
}
