<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

    public function document(Project $project)
    {
        $user = Auth::user();
        $isManager = $user && in_array($user->role, ['admin', 'content_creator'], true);

        if ($project->status !== Project::STATUS_PUBLISH && ! $isManager) {
            abort(404);
        }

        $disk = $project->dokumen_disk ?? 'public';

        abort_unless($project->dokumen_path && Storage::disk($disk)->exists($project->dokumen_path), 404);

        $extension = pathinfo($project->dokumen_path, PATHINFO_EXTENSION);
        $filename = str($project->judul)->slug()->append('-dokumen')
            ->append($extension ? ".{$extension}" : '')->toString();

        return Storage::disk($disk)->download($project->dokumen_path, $filename);
    }
}