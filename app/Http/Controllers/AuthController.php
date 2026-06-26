<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showRegister(){
        return view('authorized.registrasi');
    }

    public function register(Request $request){
        $role = $request->input('role', 'dosen');

        // Validasi berbeda berdasarkan role
        $rules = [
            'fullname' => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'role'     => 'required|in:dosen,content_creator',
            'foto'     => 'nullable|image|max:2048',
        ];

        // NIP hanya wajib untuk dosen
        if ($role === 'dosen') {
            $rules['nip'] = 'required|numeric|unique:users,nip';
        } else {
            $rules['nip'] = 'nullable|numeric|unique:users,nip';
        }

        $data = $request->validate($rules);

        $data['role'] = $role;

        // Dosen butuh validasi admin, content_creator langsung approved
        $data['registration_status'] = ($role === 'dosen')
            ? User::STATUS_PENDING
            : User::STATUS_APPROVED;

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto-users', 'public');
        }

        User::create($data);

        $message = ($role === 'dosen')
            ? 'Registrasi berhasil. Akun dosen Anda menunggu validasi admin.'
            : 'Registrasi berhasil. Silakan login dengan akun Anda.';

        return redirect('/login')->with('success', $message);
    }

    public function showLogin(){
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

            // Dosen belum approved → redirect ke halaman status
            if ($user->role === 'dosen' && $user->registration_status !== User::STATUS_APPROVED) {
                return redirect()->route('dosen.status');
            }

            // Redirect berdasarkan role
            return match ($user->role) {
                'admin'           => redirect('/beranda-admin'),
                'dosen'           => redirect('/beranda-dosen'),
                'content_creator' => redirect('/beranda-creator'),
                default           => redirect('/'),
            };
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->withInput($request->only('email'));
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    // public function showProfile()
    // {
    //     $user = Auth::user(); // ambil user yang sedang login
    //     return view('profil', compact('user'));
    // }
}
