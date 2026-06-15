<?php

namespace App\Http\Controllers;

use App\Models\News;

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
        $years = News::published()
            ->selectRaw('YEAR(published_at) as tahun')
            ->groupBy('tahun')
            ->orderByDesc('tahun')
            ->pluck('tahun');

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
        $years = News::published()
            ->selectRaw('YEAR(published_at) as tahun')
            ->groupBy('tahun')
            ->orderByDesc('tahun')
            ->pluck('tahun');

        return view('halaman-dosen.news-dosen', compact('news', 'categories', 'years'));
    }
}
