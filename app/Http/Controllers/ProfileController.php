<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Storage;

// class ProfileController extends Controller
// {
//     public function showProfile()
//     {
//         $user = Auth::user(); // ambil user yang sedang login
//         return view('halaman-dosen.profil-dosen', compact('user'));
//     }

//     public function updateProfile(Request $request)
//     {
//         $user = Auth::user();
        
//         $request->validate([
//             'fullname' => 'required|string|max:255',
//             'email' => 'required|email|unique:users,email,' . $user->id,
//             'nip' => 'required|numeric|unique:users,nip,' . $user->id,
//             'foto' => 'nullable|image|max:2048',
//         ]);

//         $data = $request->only(['fullname', 'email', 'nip']);

//         // Handle foto upload
//         if ($request->hasFile('foto')) {
//             // Delete old photo if exists
//             if ($user->foto && Storage::disk('public')->exists($user->foto)) {
//                 Storage::disk('public')->delete($user->foto);
//             }
            
//             $data['foto'] = $request->file('foto')->store('foto-users', 'public');
//         }

//         $user->update($data);

//         return redirect()->back()->with('success', 'Profil berhasil diperbarui');
//     }
// }