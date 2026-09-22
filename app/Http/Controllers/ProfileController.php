<?php

namespace App\Http\Controllers;

use App\Rules\SafeName;
use App\Rules\SafeText;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('halaman-dosen.profil-dosen', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'fullname' => ['required', 'string', 'min:3', 'max:255', new SafeName()],
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'nip'      => 'nullable|numeric|digits_between:5,20|unique:users,nip,' . $user->id,
            'prodi'    => ['nullable', 'string', 'max:255', new SafeText()],
            'fakultas' => ['nullable', 'string', 'max:255', new SafeText()],
            'bio'                => ['nullable', 'string', 'max:2000', new SafeText()],
            'bidang_penelitian'  => ['nullable', 'string', 'max:500', new SafeText()],
            'foto'               => 'nullable|image|max:4096',
        ]);

        $data = $request->only([
            'fullname', 'email', 'nip',
            'prodi', 'fakultas',
            'bio', 'bidang_penelitian',
        ]);

        if ($request->hasFile('foto')) {
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }
            $data['foto'] = $request->file('foto')->store('foto-users', 'public');
        }

        $user->forceFill($data)->save();

        return redirect()->route('profil.dosen')->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'string', 'min:6', 'max:64', 'confirmed'],
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'password.required'         => 'Password baru wajib diisi.',
            'password.min'              => 'Password baru minimal 6 karakter.',
            'password.max'              => 'Password baru maksimal 64 karakter.',
            'password.confirmed'        => 'Konfirmasi password baru tidak cocok.',
        ]);

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Password lama yang Anda masukkan salah.',
            ])->withInput($request->except(['current_password', 'password', 'password_confirmation']));
        }

        $user->forceFill([
            'password' => Hash::make($request->password),
        ])->save();

        return redirect()->route('profil.dosen')->with('success', 'Password berhasil diperbarui.');
    }
}