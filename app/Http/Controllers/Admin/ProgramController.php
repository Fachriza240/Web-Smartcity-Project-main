<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
            'program'  => new Program(['status' => Program::STATUS_DRAFT]),
            'statuses' => Program::statuses(),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizeContentManager();

        $data = $this->validatedData($request);
        $data = $this->storeFiles($request, $data);

        Program::create($data);

        return redirect()->route('admin.programs.index')->with('success', 'Program berhasil ditambahkan.');
    }

    public function edit(Program $program)
    {
        $this->authorizeContentManager();

        return view('admin.programs.edit', [
            'program'  => $program,
            'statuses' => Program::statuses(),
        ]);
    }

    public function update(Request $request, Program $program)
    {
        $this->authorizeContentManager();

        $data = $this->validatedData($request, $program);
        $data = $this->storeFiles($request, $data, $program);

        $program->update($data);

        return redirect()->route('admin.programs.index')->with('success', 'Program berhasil diperbarui.');
    }

    public function destroy(Program $program)
    {
        $this->authorizeContentManager();

        $this->deleteFile($program->thumbnail_path);
        $program->delete();

        return redirect()->route('admin.programs.index')->with('success', 'Program berhasil dihapus.');
    }

    private function validatedData(Request $request, ?Program $program = null): array
    {
        return $request->validate([
            'judul'     => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
            'thumbnail' => [$program ? 'nullable' : 'required', 'image', 'max:4096'],
            'urutan'    => ['nullable', 'integer'],
            'status'    => ['required', Rule::in(Program::statuses())],
        ]);
    }

    private function storeFiles(Request $request, array $data, ?Program $program = null): array
    {
        unset($data['thumbnail']);

        if ($request->hasFile('thumbnail')) {
            $this->deleteFile($program?->thumbnail_path);
            $data['thumbnail_path'] = $request->file('thumbnail')->store('programs/thumbnails', 'public');
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
