<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        return view('halaman-user.news-user', $this->listing($request));
    }

    public function dosenIndex(Request $request)
    {
        return view('halaman-dosen.news-dosen', $this->listing($request));
    }

    public function show(News $news)
    {
        abort_unless($news->status === News::STATUS_PUBLISH, 404);

        $related = News::published()
            ->whereKeyNot($news->getKey())
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        return view('news.show', compact('news', 'related'));
    }

    private function listing(Request $request): array
    {
        $search = Str::limit(trim((string) $request->query('search', '')), 100, '');

        $news = News::published()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('judul', 'like', "%{$search}%")
                        ->orWhere('konten', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('kategori'), fn ($query) => $query->where('kategori', $request->query('kategori')))
            ->when($request->filled('tahun'), fn ($query) => $query->whereYear('published_at', (int) $request->query('tahun')))
            ->orderByDesc('published_at')
            ->paginate(9)
            ->withQueryString();

        $years = News::published()
            ->whereNotNull('published_at')
            ->selectRaw('YEAR(published_at) as tahun')
            ->groupBy('tahun')
            ->orderByDesc('tahun')
            ->pluck('tahun');

        return [
            'news' => $news,
            'categories' => News::categories(),
            'years' => $years,
        ];
    }
}
