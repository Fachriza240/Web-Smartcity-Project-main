<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PublicationController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeContentManager();

        $query = Publication::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($query) use ($search) {
                    $query->where('judul', 'like', "%{$search}%")
                        ->orWhere('penulis', 'like', "%{$search}%")
                        ->orWhere('penerbit', 'like', "%{$search}%")
                        ->orWhere('doi', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('kategori'), fn ($query) => $query->where('kategori', $request->kategori))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->latest();

        $publications = $query->paginate(10)->withQueryString();

        return view('admin.publications.index', [
            'publications' => $publications,
            'categories' => Publication::categories(),
            'statuses' => Publication::statuses(),
        ]);
    }

    public function create()
    {
        $this->authorizeContentManager();

        return view('admin.publications.create', [
            'publication' => new Publication(['status' => Publication::STATUS_DRAFT]),
            'categories' => Publication::categories(),
            'statuses' => Publication::statuses(),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizeContentManager();

        $data = $this->validatedData($request);
        $data['pdf_path'] = $request->file('pdf')->store('publications/pdf', 'public');
        unset($data['pdf']);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail_path'] = $request->file('thumbnail')->store('publications/thumbnails', 'public');
        }
        unset($data['thumbnail']);

        Publication::create($data);

        return redirect()->route('admin.publications.index')->with('success', 'Publication berhasil ditambahkan.');
    }

    public function edit(Publication $publication)
    {
        $this->authorizeContentManager();

        return view('admin.publications.edit', [
            'publication' => $publication,
            'categories' => Publication::categories(),
            'statuses' => Publication::statuses(),
        ]);
    }

    public function update(Request $request, Publication $publication)
    {
        $this->authorizeContentManager();

        $data = $this->validatedData($request, $publication);

        if ($request->hasFile('pdf')) {
            $this->deleteFile($publication->pdf_path);
            $data['pdf_path'] = $request->file('pdf')->store('publications/pdf', 'public');
        }
        unset($data['pdf']);

        if ($request->hasFile('thumbnail')) {
            $this->deleteFile($publication->thumbnail_path);
            $data['thumbnail_path'] = $request->file('thumbnail')->store('publications/thumbnails', 'public');
        }
        unset($data['thumbnail']);

        $publication->update($data);

        return redirect()->route('admin.publications.index')->with('success', 'Publication berhasil diperbarui.');
    }

    public function destroy(Publication $publication)
    {
        $this->authorizeContentManager();

        $this->deleteFile($publication->pdf_path);
        $this->deleteFile($publication->thumbnail_path);
        $publication->delete();

        return redirect()->route('admin.publications.index')->with('success', 'Publication berhasil dihapus.');
    }

    private function validatedData(Request $request, ?Publication $publication = null): array
    {
        return $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'penulis' => ['required', 'string', 'max:255'],
            'tahun' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'abstrak' => ['required', 'string'],
            'kategori' => ['required', Rule::in(Publication::categories())],
            'penerbit' => ['nullable', 'string', 'max:255'],
            'doi' => ['nullable', 'string', 'max:255'],
            'pdf' => [$publication ? 'nullable' : 'required', 'file', 'mimes:pdf', 'max:20480'],
            'thumbnail' => ['nullable', 'image', 'max:4096'],
            'status' => ['required', Rule::in(Publication::statuses())],
        ]);
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
