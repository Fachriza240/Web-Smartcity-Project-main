<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeContentManager();

        $query = Project::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($query) use ($search) {
                    $query->where('judul', 'like', "%{$search}%")
                        ->orWhere('deskripsi', 'like', "%{$search}%")
                        ->orWhere('kategori', 'like', "%{$search}%")
                        ->orWhere('partner', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->latest();

        $projects = $query->paginate(10)->withQueryString();

        return view('admin.projects.index', [
            'projects' => $projects,
            'statuses' => Project::statuses(),
        ]);
    }

    public function create()
    {
        $this->authorizeContentManager();

        return view('admin.projects.create', [
            'project' => new Project(['status' => Project::STATUS_DRAFT]),
            'statuses' => Project::statuses(),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizeContentManager();

        $data = $this->validatedData($request);
        $data = $this->storeFiles($request, $data);

        Project::create($data);

        return redirect()->route('admin.projects.index')->with('success', 'Project berhasil ditambahkan.');
    }

    public function edit(Project $project)
    {
        $this->authorizeContentManager();

        return view('admin.projects.edit', [
            'project' => $project,
            'statuses' => Project::statuses(),
        ]);
    }

    public function update(Request $request, Project $project)
    {
        $this->authorizeContentManager();

        $data = $this->validatedData($request, $project);
        $data = $this->storeFiles($request, $data, $project);

        $project->update($data);

        return redirect()->route('admin.projects.index')->with('success', 'Project berhasil diperbarui.');
    }

    public function destroy(Project $project)
    {
        $this->authorizeContentManager();

        $this->deleteFile($project->thumbnail_path);
        $this->deleteFile($project->dokumen_path);
        foreach ($project->gallery_paths ?? [] as $path) {
            $this->deleteFile($path);
        }

        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', 'Project berhasil dihapus.');
    }

    private function validatedData(Request $request, ?Project $project = null): array
    {
        return $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'thumbnail' => [$project ? 'nullable' : 'required', 'image', 'max:4096'],
            'deskripsi' => ['required', 'string'],
            'kategori' => ['nullable', 'string', 'max:255'],
            'partner' => ['nullable', 'string', 'max:255'],
            'tahun' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['image', 'max:4096'],
            'dokumen' => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar', 'max:20480'],
            'status' => ['required', Rule::in(Project::statuses())],
        ]);
    }

    private function storeFiles(Request $request, array $data, ?Project $project = null): array
    {
        unset($data['thumbnail'], $data['gallery'], $data['dokumen']);

        if ($request->hasFile('thumbnail')) {
            $this->deleteFile($project?->thumbnail_path);
            $data['thumbnail_path'] = $request->file('thumbnail')->store('projects/thumbnails', 'public');
        }

        if ($request->hasFile('dokumen')) {
            $this->deleteFile($project?->dokumen_path);
            $data['dokumen_path'] = $request->file('dokumen')->store('projects/documents', 'public');
        }

        if ($request->hasFile('gallery')) {
            foreach ($project?->gallery_paths ?? [] as $path) {
                $this->deleteFile($path);
            }

            $data['gallery_paths'] = collect($request->file('gallery'))
                ->map(fn ($file) => $file->store('projects/gallery', 'public'))
                ->values()
                ->all();
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
