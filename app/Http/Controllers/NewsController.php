<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Support\Carbon;

class NewsController extends Controller
{
    public function index()
    {
        $query = News::published()
            ->when(request('search'), function ($q) {
                $q->where(function ($q) {
                    $q->where('judul', 'like', '%' . request('search') . '%')
                      ->orWhere('konten', 'like', '%' . request('search') . '%');
                });
            })
            ->when(request('kategori'), fn ($q) => $q->where('kategori', request('kategori')))
            ->when(request('tahun'), fn ($q) => $q->whereYear('published_at', request('tahun')));

        $news = $query->orderByDesc('published_at')->paginate(9)->withQueryString();

        $categories = News::categories();
        $years = $this->publishedYears();

        return view('halaman-user.news-user', compact('news', 'categories', 'years'));
    }

    public function show(News $news)
    {
        if ($news->status !== News::STATUS_PUBLISH) {
            abort(404);
        }

        return view('news.show', compact('news'));
    }

    public function dosenIndex()
    {
        $query = News::published()
            ->when(request('search'), function ($q) {
                $q->where(function ($q) {
                    $q->where('judul', 'like', '%' . request('search') . '%')
                      ->orWhere('konten', 'like', '%' . request('search') . '%');
                });
            })
            ->when(request('kategori'), fn ($q) => $q->where('kategori', request('kategori')))
            ->when(request('tahun'), fn ($q) => $q->whereYear('published_at', request('tahun')));

        $news = $query->orderByDesc('published_at')->paginate(9)->withQueryString();

        $categories = News::categories();
        $years = $this->publishedYears();

        return view('halaman-dosen.news-dosen', compact('news', 'categories', 'years'));
    }

    private function publishedYears()
    {
        return News::published()
            ->whereNotNull('published_at')
            ->pluck('published_at')
            ->map(fn ($date) => Carbon::parse($date)->year)
            ->unique()
            ->sortDesc()
            ->values();
    }
}