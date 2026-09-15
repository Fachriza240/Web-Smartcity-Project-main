<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showRegister(){
        if (Auth::check()) {
            return redirect(Auth::user()->dashboardUrl());
        }

        return view('authorized.registrasi');
    }

    public function register(Request $request){
        $role = $request->input('role', 'dosen');

        $rules = [
            'fullname' => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'role'     => 'required|in:dosen,content_creator',
            'foto'     => 'nullable|image|max:2048',
        ];

        if ($role === 'dosen') {
            $rules['nip']      = 'required|numeric|unique:users,nip';
            $rules['prodi']    = 'nullable|string|max:255';
            $rules['fakultas'] = 'nullable|string|max:255';
        } else {
            $rules['nip'] = 'nullable|numeric|unique:users,nip';
        }

        $data = $request->validate($rules);

        $data['role'] = $role;

        $data['registration_status'] = User::STATUS_PENDING;

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto-users', 'public');
        }

        User::create($data);

        $message = ($role === 'dosen')
            ? 'Registrasi berhasil. Akun dosen Anda menunggu validasi admin.'
            : 'Registrasi berhasil. Akun Anda menunggu persetujuan admin sebelum bisa digunakan.';

        return redirect('/login')->with('success', $message);
    }

    public function showLogin(){
        if (Auth::check()) {
            return redirect(Auth::user()->dashboardUrl());
        }

        return view('authorized.login');
    }

    public function login(Request $request){
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            return redirect($user->dashboardUrl());
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->withInput($request->only('email'));
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // public function showProfile()
    // {
    //     $user = Auth::user(); // ambil user yang sedang login
    //     return view('profil', compact('user'));
    // }
}