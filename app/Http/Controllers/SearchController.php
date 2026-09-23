<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Partner;
use App\Models\Program;
use App\Models\Project;
use App\Models\Publication;
use App\Models\Team;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim((string) $request->get('q'));

        $escaped = $keyword === '' ? '' : str_replace(
            ['\\', '%', '_'],
            ['\\\\', '\\%', '\\_'],
            $keyword
        );

        $news = collect();
        $publications = collect();
        $programs = collect();
        $projects = collect();
        $teams = collect();
        $partners = collect();

        if ($keyword !== '') {
            $news = News::published()
                ->where(function ($q) use ($escaped) {
                    $q->where('judul', 'like', "%{$escaped}%")
                      ->orWhere('konten', 'like', "%{$escaped}%")
                      ->orWhere('kategori', 'like', "%{$escaped}%");
                })
                ->orderByDesc('published_at')
                ->limit(12)
                ->get();

            $publications = Publication::where('status', Publication::STATUS_PUBLISH)
                ->where(function ($q) use ($escaped) {
                    $q->where('judul', 'like', "%{$escaped}%")
                      ->orWhere('penulis', 'like', "%{$escaped}%")
                      ->orWhere('abstrak', 'like', "%{$escaped}%")
                      ->orWhere('penerbit', 'like', "%{$escaped}%")
                      ->orWhere('doi', 'like', "%{$escaped}%");
                })
                ->orderByDesc('tahun')
                ->limit(12)
                ->get();

            $programs = Program::published()
                ->where(function ($q) use ($escaped) {
                    $q->where('judul', 'like', "%{$escaped}%")
                      ->orWhere('deskripsi', 'like', "%{$escaped}%");
                })
                ->orderBy('urutan')
                ->limit(12)
                ->get();

            $projects = Project::published()
                ->where(function ($q) use ($escaped) {
                    $q->where('judul', 'like', "%{$escaped}%")
                      ->orWhere('deskripsi', 'like', "%{$escaped}%")
                      ->orWhere('kategori', 'like', "%{$escaped}%")
                      ->orWhere('partner', 'like', "%{$escaped}%");
                })
                ->orderByDesc('tahun')
                ->limit(12)
                ->get();

            $teams = Team::published()
                ->where(function ($q) use ($escaped) {
                    $q->where('nama', 'like', "%{$escaped}%")
                      ->orWhere('jabatan', 'like', "%{$escaped}%")
                      ->orWhere('bidang', 'like', "%{$escaped}%");
                })
                ->orderBy('urutan')
                ->limit(12)
                ->get();

            $partners = Partner::published()
                ->where(function ($q) use ($escaped) {
                    $q->where('nama', 'like', "%{$escaped}%")
                      ->orWhere('deskripsi', 'like', "%{$escaped}%");
                })
                ->orderBy('urutan')
                ->limit(12)
                ->get();
        }

        $totalResults = $news->count() + $publications->count() + $programs->count()
            + $projects->count() + $teams->count() + $partners->count();

        return view('halaman-user.search-user', compact(
            'keyword',
            'news',
            'publications',
            'programs',
            'projects',
            'teams',
            'partners',
            'totalResults'
        ));
    }
}