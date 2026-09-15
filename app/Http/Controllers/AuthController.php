<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showRegister(){
        $user = Auth::user();

        if ($user && !$user->isRejected()) {
            return redirect($user->dashboardUrl());
        }

        return view('authorized.registrasi');
    }

    public function register(Request $request){
        $role = $request->input('role', 'dosen');

        $rules = [
            'fullname' => 'required|string|max:255',
            'email'    => 'required|email',
            'password' => 'required|confirmed|min:6',
            'role'     => 'required|in:dosen,content_creator',
            'foto'     => 'nullable|image|max:2048',
        ];

        if ($role === 'dosen') {
            $rules['nip']      = 'required|numeric';
            $rules['prodi']    = 'nullable|string|max:255';
            $rules['fakultas'] = 'nullable|string|max:255';
        } else {
            $rules['nip'] = 'nullable|numeric';
        }

        $data = $request->validate($rules);

        $existingByEmail = User::where('email', $data['email'])->first();

        if ($existingByEmail && !$existingByEmail->isRejected()) {
            return back()
                ->withErrors(['email' => 'Email sudah terdaftar.'])
                ->withInput($request->except('password', 'password_confirmation'));
        }

        if (!empty($data['nip'])) {
            $existingByNip = User::where('nip', $data['nip'])
                ->when($existingByEmail, fn ($q) => $q->where('id', '!=', $existingByEmail->id))
                ->first();

            if ($existingByNip && !$existingByNip->isRejected()) {
                return back()
                    ->withErrors(['nip' => 'NIP sudah terdaftar.'])
                    ->withInput($request->except('password', 'password_confirmation'));
            }
        }

        $data['role'] = $role;

        $data['registration_status'] = User::STATUS_PENDING;

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto-users', 'public');
        }

        if ($existingByEmail && $existingByEmail->isRejected()) {
            $existingByEmail->fill($data);
            $existingByEmail->save();

            if (Auth::check() && Auth::id() === $existingByEmail->id) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }
        } else {
            User::create($data);
        }

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
    //     $user = Auth::user(); 
    //     return view('profil', compact('user'));
    // }
}