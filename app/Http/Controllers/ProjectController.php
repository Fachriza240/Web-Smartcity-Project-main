<?php

namespace App\Http\Controllers;

use App\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::published()
            ->orderByDesc('tahun')
            ->latest()
            ->get();

        return view('halaman-user.project-user', compact('projects'));
    }

    public function dosenIndex()
    {
        $projects = Project::published()
            ->orderByDesc('tahun')
            ->latest()
            ->get();

        return view('halaman-dosen.project-dosen', compact('projects'));
    }
}
