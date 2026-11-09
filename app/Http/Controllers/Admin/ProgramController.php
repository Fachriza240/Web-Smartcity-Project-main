<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Rules\SafeText;
use App\Services\PublicFileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeContentManager();

        $programs = Program::query()
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('judul', 'like', "%{$request->search}%")
                        ->orWhere('deskripsi', 'like', "%{$request->search}%");
                });
            })
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->orderBy('urutan')
            ->paginate(10)
            ->withQueryString();

        return view('admin.programs.index', [
            'programs' => $programs,
            'statuses' => Program::statuses(),
        ]);
    }

    public function create()
    {
        $this->authorizeContentManager();

        return view('admin.programs.create', [
            'program' => new Program(['status' => Program::STATUS_DRAFT]),
            'statuses' => Program::statuses(),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizeContentManager();

        $data = $this->validatedData($request);
        unset($data['thumbnail']);

        app(PublicFileService::class)->save(new Program, $data, [
            'thumbnail_path' => [$request->file('thumbnail'), 'programs/thumbnails'],
        ]);

        return redirect()->route('admin.programs.index')
            ->with('success', 'Program berhasil ditambahkan.');
    }

    public function edit(Program $program)
    {
        $this->authorizeContentManager();

        return view('admin.programs.edit', [
            'program' => $program,
            'statuses' => Program::statuses(),
        ]);
    }

    public function update(Request $request, Program $program)
    {
        $this->authorizeContentManager();

        $data = $this->validatedData($request, $program);
        unset($data['thumbnail']);

        app(PublicFileService::class)->save($program, $data, [
            'thumbnail_path' => [$request->file('thumbnail'), 'programs/thumbnails'],
        ]);

        return redirect()->route('admin.programs.index')
            ->with('success', 'Program berhasil diperbarui.');
    }

    public function destroy(Program $program)
    {
        $this->authorizeContentManager();

        app(PublicFileService::class)->delete($program, ['thumbnail_path']);

        return redirect()->route('admin.programs.index')
            ->with('success', 'Program berhasil dihapus.');
    }

    private function validatedData(Request $request, ?Program $program = null): array
    {
        return $request->validate([
            'judul' => ['required', 'string', 'min:3', 'max:100', new SafeText],
            'deskripsi' => ['required', 'string', 'min:20', 'max:5000', new SafeText(false)],
            'thumbnail' => [$program ? 'nullable' : 'required', 'image', 'max:4096'],
            'urutan' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'status' => ['required', Rule::in(Program::statuses())],
        ]);
    }

    private function authorizeContentManager(): void
    {
        if (! Auth::check() || ! in_array(Auth::user()->role, ['admin', 'content_creator'], true)) {
            abort(403);
        }
    }
}