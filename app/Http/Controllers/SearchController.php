<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Program;
use App\Models\Project;
use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $keyword = Str::limit(trim((string) $request->query('q', '')), 100, '');
        $results = collect();

        if (mb_strlen($keyword) >= 2) {
            $like = "%{$keyword}%";

            $results = collect()
                ->concat(News::published()->where('judul', 'like', $like)->orderByDesc('published_at')->limit(10)->get()
                    ->map(fn ($item) => [
                        'type' => 'Berita',
                        'icon' => 'bi-newspaper',
                        'title' => $item->judul,
                        'excerpt' => Str::limit(strip_tags((string) $item->konten), 140),
                        'url' => route('news.show', $item),
                    ]))
                ->concat(Publication::published()->where(fn ($q) => $q->where('judul', 'like', $like)->orWhere('penulis', 'like', $like))->orderByDesc('tahun')->limit(10)->get()
                    ->map(fn ($item) => [
                        'type' => 'Publikasi',
                        'icon' => 'bi-journal-text',
                        'title' => $item->judul,
                        'excerpt' => trim($item->penulis.' ('.$item->tahun.')'),
                        'url' => route('publications.show', $item),
                    ]))
                ->concat(Project::published()->where('judul', 'like', $like)->orderByDesc('tahun')->limit(10)->get()
                    ->map(fn ($item) => [
                        'type' => 'Proyek',
                        'icon' => 'bi-kanban',
                        'title' => $item->judul,
                        'excerpt' => Str::limit((string) $item->deskripsi, 140),
                        'url' => url('/project-user').'#proyek-'.$item->id,
                    ]))
                ->concat(Program::published()->where('judul', 'like', $like)->orderBy('urutan')->limit(10)->get()
                    ->map(fn ($item) => [
                        'type' => 'Program',
                        'icon' => 'bi-layers',
                        'title' => $item->judul,
                        'excerpt' => Str::limit((string) $item->deskripsi, 140),
                        'url' => url('/program-user').'#program-'.$item->id,
                    ]));
        }

        return view('halaman-user.search-user', [
            'keyword' => $keyword,
            'results' => $results,
        ]);
    }
}
