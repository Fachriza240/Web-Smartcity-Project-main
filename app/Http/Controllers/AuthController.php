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
        $request->validate([
            'fullname'=>'required|string|max:255',
            'email'=>'required|email|unique:users,email',
            'nip'=>'required|numeric|unique:users,nip',
            'password'=>'required|confirmed|min:6',
            'foto'=>'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if($request->hasFile('foto')){
            $data['foto'] = $request->file('foto')->store('foto-users', 'public');
        }

        $user = User::create($data);

        Auth::login($user);

        return $user->role === 'admin' ? redirect('/login') : redirect('/login');
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
            return Auth::user()->role==='admin'? redirect('/beranda-admin') : redirect('/beranda-dosen');
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
