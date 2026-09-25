<?php

namespace App\Http\Controllers;

use App\Models\Hki;
use App\Models\Publication;
use App\Rules\PersonName;
use App\Rules\SafeText;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        $publications = Publication::published()
            ->forDosen($user)
            ->orderByDesc('tahun')
            ->limit(6)
            ->get();

        $hkis = Hki::published()
            ->forDosen($user)
            ->orderByDesc('tgl_terbit')
            ->limit(6)
            ->get();

        return view('halaman-dosen.profil-dosen', compact('user', 'publications', 'hkis'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'fullname' => ['required', 'string', 'min:3', 'max:100', new PersonName],
            'email' => ['required', 'string', 'email:rfc', 'max:100', 'unique:users,email,'.$user->id],
            'nip' => ['nullable', 'regex:/^[0-9]+$/', 'digits_between:4,30', 'unique:users,nip,'.$user->id],
            'prodi' => ['nullable', 'string', 'min:2', 'max:100', new SafeText],
            'fakultas' => ['nullable', 'string', 'min:2', 'max:100', new SafeText],
            'bio' => ['nullable', 'string', 'max:2000', new SafeText],
            'bidang_penelitian' => ['nullable', 'string', 'max:500', new SafeText],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $data['fullname'] = preg_replace('/\s+/u', ' ', trim($data['fullname']));
        $data['email'] = strtolower($data['email']);
        unset($data['foto']);

        if ($request->hasFile('foto')) {
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }
            $data['foto'] = $request->file('foto')->store('foto-users', 'public');
        }

        $user->forceFill($data)->save();

        return redirect()->route('profil.dosen')->with('success', 'Profil berhasil diperbarui.');
    }
}
