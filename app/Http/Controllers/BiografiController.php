<?php

namespace App\Http\Controllers;

use App\Models\Hki;
use App\Models\Publication;
use App\Models\User;

class BiografiController extends Controller
{
    public function user(?User $user = null)
    {
        return view('halaman-user.biografi-user', $this->profile($user));
    }

    public function dosen(?User $user = null)
    {
        return view('halaman-dosen.biografi-dosen', $this->profile($user));
    }

    private function profile(?User $user): array
    {
        if ($user && ($user->role !== 'dosen' || $user->registration_status !== User::STATUS_APPROVED)) {
            abort(404);
        }

        if (! $user) {
            return ['lecturer' => null, 'publications' => collect(), 'hkis' => collect()];
        }

        return [
            'lecturer' => $user,
            'publications' => Publication::published()
                ->forDosen($user)
                ->orderByDesc('tahun')
                ->get(),
            'hkis' => Hki::published()
                ->forDosen($user)
                ->orderByDesc('tgl_terbit')
                ->get(),
        ];
    }
}
