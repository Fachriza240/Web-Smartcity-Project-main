<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            'fullname'          => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email,' . $user->id,
            'nip'               => 'nullable|string|max:50|unique:users,nip,' . $user->id,
            'prodi'             => 'nullable|string|max:255',
            'fakultas'          => 'nullable|string|max:255',
            'bio'               => 'nullable|string',
            'bidang_penelitian' => 'nullable|string',
            'foto'              => 'nullable|image|max:4096',
        ]);

        $data = $request->only([
            'fullname', 'email', 'nip',
            'prodi', 'fakultas',
            'bio', 'bidang_penelitian',
        ]);

        // Jangan bcrypt — password tidak diubah di sini
        if ($request->hasFile('foto')) {
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }
            $data['foto'] = $request->file('foto')->store('foto-users', 'public');
        }

        // Update tanpa trigger password mutator
        $user->forceFill($data)->save();

        return redirect()->route('profil.dosen')->with('success', 'Profil berhasil diperbarui.');
    }
}
