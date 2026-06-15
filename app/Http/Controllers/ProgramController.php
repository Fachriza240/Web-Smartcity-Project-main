<?php

namespace App\Http\Controllers;

use App\Models\Program;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = Program::published()->orderBy('urutan')->get();

        return view('halaman-user.program-user', compact('programs'));
    }

    public function dosenIndex()
    {
        $programs = Program::published()->orderBy('urutan')->get();

        return view('halaman-dosen.program-dosen', compact('programs'));
    }
}
