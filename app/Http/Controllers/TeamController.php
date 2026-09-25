<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;

class TeamController extends Controller
{
    public function index()
    {
        return view('halaman-user.team-user', $this->members());
    }

    public function dosenIndex()
    {
        return view('halaman-dosen.team-dosen', $this->members());
    }

    private function members(): array
    {
        return [
            'lecturers' => User::where('role', 'dosen')
                ->where('registration_status', User::STATUS_APPROVED)
                ->orderBy('fullname')
                ->get(['id', 'fullname', 'email', 'prodi', 'fakultas', 'bidang_penelitian', 'foto']),
            'staff' => Team::published()->where('tipe', Team::TIPE_STAFF)->orderBy('urutan')->get(),
            'interns' => Team::published()->where('tipe', Team::TIPE_INTERN)->orderBy('urutan')->get(),
        ];
    }
}
