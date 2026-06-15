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
        $data = $request->validate([
            'fullname'=>'required|string|max:255',
            'email'=>'required|email|unique:users,email',
            'nip'=>'required|numeric|unique:users,nip',
            'password'=>'required|confirmed|min:6',
            'foto'=>'nullable|image|max:2048',
        ]);

        $data['role'] = 'dosen';
        $data['registration_status'] = User::STATUS_PENDING;

        if($request->hasFile('foto')){
            $data['foto'] = $request->file('foto')->store('foto-users', 'public');
        }

        User::create($data);

        return redirect('/login')->with('success', 'Registrasi berhasil. Akun dosen Anda menunggu validasi admin.');
    }

    public function showLogin(){
        return view('authorized.login');
    }

    public function login(Request $request){
        $credentials = $request->validate([
            'email'=>'required|email',
            'password'=>'required',
        ]);

        if(Auth::attempt($credentials)){
            $request->session()->regenerate();

            if (Auth::user()->role === 'dosen' && Auth::user()->registration_status !== User::STATUS_APPROVED) {
                $status = Auth::user()->registration_status;

                Auth::logout();
                $request->session()->regenerateToken();

                $message = $status === User::STATUS_REJECTED
                    ? 'Registrasi Anda ditolak. Silakan hubungi admin.'
                    : 'Akun Anda masih menunggu validasi admin.';

                return back()->withErrors(['email' => $message]);
            }

            $role = Auth::user()->role;
            if($role === 'admin') return redirect('/beranda-admin');
            if($role === 'dosen') return redirect('/beranda-dosen');
            if($role === 'content_creator') return redirect('/beranda-creator');
            return redirect('/');
        }

        return back()->withErrors(['email'=>'Email atau password salah']);
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
