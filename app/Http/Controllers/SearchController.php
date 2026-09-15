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

        $news = collect();
        $publications = collect();
        $programs = collect();
        $projects = collect();
        $teams = collect();
        $partners = collect();

        if ($keyword !== '') {
            $news = News::published()
                ->where(function ($q) use ($keyword) {
                    $q->where('judul', 'like', "%{$keyword}%")
                      ->orWhere('konten', 'like', "%{$keyword}%")
                      ->orWhere('kategori', 'like', "%{$keyword}%");
                })
                ->orderByDesc('published_at')
                ->limit(12)
                ->get();

            $publications = Publication::where('status', Publication::STATUS_PUBLISH)
                ->where(function ($q) use ($keyword) {
                    $q->where('judul', 'like', "%{$keyword}%")
                      ->orWhere('penulis', 'like', "%{$keyword}%")
                      ->orWhere('abstrak', 'like', "%{$keyword}%")
                      ->orWhere('penerbit', 'like', "%{$keyword}%")
                      ->orWhere('doi', 'like', "%{$keyword}%");
                })
                ->orderByDesc('tahun')
                ->limit(12)
                ->get();

            $programs = Program::published()
                ->where(function ($q) use ($keyword) {
                    $q->where('judul', 'like', "%{$keyword}%")
                      ->orWhere('deskripsi', 'like', "%{$keyword}%");
                })
                ->orderBy('urutan')
                ->limit(12)
                ->get();

            $projects = Project::published()
                ->where(function ($q) use ($keyword) {
                    $q->where('judul', 'like', "%{$keyword}%")
                      ->orWhere('deskripsi', 'like', "%{$keyword}%")
                      ->orWhere('kategori', 'like', "%{$keyword}%")
                      ->orWhere('partner', 'like', "%{$keyword}%");
                })
                ->orderByDesc('tahun')
                ->limit(12)
                ->get();

            $teams = Team::published()
                ->where(function ($q) use ($keyword) {
                    $q->where('nama', 'like', "%{$keyword}%")
                      ->orWhere('jabatan', 'like', "%{$keyword}%")
                      ->orWhere('bidang', 'like', "%{$keyword}%");
                })
                ->orderBy('urutan')
                ->limit(12)
                ->get();

            $partners = Partner::published()
                ->where(function ($q) use ($keyword) {
                    $q->where('nama', 'like', "%{$keyword}%")
                      ->orWhere('deskripsi', 'like', "%{$keyword}%");
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