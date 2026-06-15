<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeContentManager();

        $news = News::query()
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('judul', 'like', "%{$request->search}%")
                      ->orWhere('konten', 'like', "%{$request->search}%")
                      ->orWhere('kategori', 'like', "%{$request->search}%");
                });
            })
            ->when($request->filled('kategori'), fn ($q) => $q->where('kategori', $request->kategori))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.news.index', [
            'news'       => $news,
            'statuses'   => News::statuses(),
            'categories' => News::categories(),
        ]);
    }

    public function create()
    {
        $this->authorizeContentManager();

        return view('admin.news.create', [
            'news'       => new News(['status' => News::STATUS_DRAFT]),
            'statuses'   => News::statuses(),
            'categories' => News::categories(),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizeContentManager();

        $data = $this->validatedData($request);
        $data = $this->storeFiles($request, $data);

        News::create($data);

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil ditambahkan.');
    }

    public function edit(News $news)
    {
        $this->authorizeContentManager();

        return view('admin.news.edit', [
            'news'       => $news,
            'statuses'   => News::statuses(),
            'categories' => News::categories(),
        ]);
    }

    public function update(Request $request, News $news)
    {
        $this->authorizeContentManager();

        $data = $this->validatedData($request, $news);
        $data = $this->storeFiles($request, $data, $news);

        $news->update($data);

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(News $news)
    {
        $this->authorizeContentManager();

        $this->deleteFile($news->thumbnail_path);
        $news->delete();

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil dihapus.');
    }

    private function validatedData(Request $request, ?News $news = null): array
    {
        return $request->validate([
            'judul'     => ['required', 'string', 'max:255'],
            'kategori'  => ['nullable', 'string', 'max:255'],
            'konten'    => ['required', 'string'],
            'thumbnail' => [$news ? 'nullable' : 'required', 'image', 'max:4096'],
            'status'    => ['required', Rule::in(News::statuses())],
        ]);
    }

    private function storeFiles(Request $request, array $data, ?News $news = null): array
    {
        unset($data['thumbnail']);

        if ($request->hasFile('thumbnail')) {
            $this->deleteFile($news?->thumbnail_path);
            $data['thumbnail_path'] = $request->file('thumbnail')->store('news/thumbnails', 'public');
        }

        return $data;
    }

    private function authorizeContentManager(): void
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['admin', 'content_creator'], true)) {
            abort(403);
        }
    }

    private function deleteFile(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
