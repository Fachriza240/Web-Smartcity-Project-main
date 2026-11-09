<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Rules\SafeText;
use App\Services\PublicFileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        unset($data['thumbnail'], $data['dokumen'], $data['gallery']);

        app(PublicFileService::class)->save(new Project, $data, [
            'thumbnail_path' => [$request->file('thumbnail'), 'projects/thumbnails'],
            'dokumen_path' => [$request->file('dokumen'), 'projects/documents'],
            'gallery_paths' => [$request->file('gallery'), 'projects/gallery'],
        ]);

        return redirect()->route('admin.projects.index')
            ->with('success', 'Proyek berhasil ditambahkan.');
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
        unset($data['thumbnail'], $data['dokumen'], $data['gallery']);

        app(PublicFileService::class)->save($project, $data, [
            'thumbnail_path' => [$request->file('thumbnail'), 'projects/thumbnails'],
            'dokumen_path' => [$request->file('dokumen'), 'projects/documents'],
            'gallery_paths' => [$request->file('gallery'), 'projects/gallery'],
        ]);

        return redirect()->route('admin.projects.index')
            ->with('success', 'Proyek berhasil diperbarui.');
    }

    public function destroy(Project $project)
    {
        $this->authorizeContentManager();

        app(PublicFileService::class)->delete($project, [
            'thumbnail_path',
            'dokumen_path',
            'gallery_paths',
        ]);

        return redirect()->route('admin.projects.index')
            ->with('success', 'Proyek berhasil dihapus.');
    }

    private function validatedData(Request $request, ?Project $project = null): array
    {
        return $request->validate([
            'judul' => ['required', 'string', 'min:3', 'max:100', new SafeText],
            'thumbnail' => [$project ? 'nullable' : 'required', 'image', 'max:4096'],
            'deskripsi' => ['required', 'string', 'min:20', 'max:5000', new SafeText(false)],
            'kategori' => ['nullable', 'string', 'min:2', 'max:100', new SafeText],
            'partner' => ['nullable', 'string', 'min:2', 'max:200', new SafeText],
            'tahun' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['image', 'max:4096'],
            'dokumen' => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar', 'max:20480'],
            'status' => ['required', Rule::in(Project::statuses())],
        ]);
    }

    private function authorizeContentManager(): void
    {
        if (! Auth::check() || ! in_array(Auth::user()->role, ['admin', 'content_creator'], true)) {
            abort(403);
        }
    }
}