<?php

namespace App\Http\Controllers;

use App\Models\Team;

class TeamController extends Controller
{
    public function index()
    {
        $staff   = Team::published()->where('tipe', Team::TIPE_STAFF)->orderBy('urutan')->get();
        $interns = Team::published()->where('tipe', Team::TIPE_INTERN)->orderBy('urutan')->get();

        return view('halaman-user.team-user', compact('staff', 'interns'));
    }

    public function dosenIndex()
    {
        $staff   = Team::published()->where('tipe', Team::TIPE_STAFF)->orderBy('urutan')->get();
        $interns = Team::published()->where('tipe', Team::TIPE_INTERN)->orderBy('urutan')->get();

        return view('halaman-dosen.team-dosen', compact('staff', 'interns'));
    }
}
