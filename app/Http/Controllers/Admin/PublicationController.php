<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\AuthorizesRoles;
use App\Http\Controllers\Controller;
use App\Models\Publication;
use App\Models\User;
use App\Rules\SafeName;
use App\Rules\SafeText;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PublicationController extends Controller
{
    use AuthorizesRoles;

    public function index(Request $request)
    {
        $this->authorizeAdmin();

        $publications = Publication::query()
            ->with('recommender')
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                      ->orWhere('penulis', 'like', "%{$search}%")
                      ->orWhere('penerbit', 'like', "%{$search}%")
                      ->orWhere('doi', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('kategori'), fn ($q) => $q->where('kategori', $request->kategori))
            ->when($request->filled('status'),   fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $dosens = User::where('role', 'dosen')
            ->where('registration_status', 'approved')
            ->orderBy('fullname')->get(['id', 'fullname', 'nip']);

        return view('admin.publications.index', [
            'publications' => $publications,
            'categories'   => Publication::categories(),
            'statuses'     => Publication::statuses(),
            'dosens'       => $dosens,
        ]);
    }

    public function create()
    {
        $this->authorizeAdmin();

        $dosens = User::where('role', 'dosen')
            ->where('registration_status', 'approved')
            ->orderBy('fullname')->get(['id', 'fullname', 'nip']);

        return view('admin.publications.create', [
            'publication'   => new Publication(['status' => Publication::STATUS_DRAFT, 'submission_type' => 'non_member']),
            'categories'    => Publication::categories(),
            'statuses'      => Publication::statuses(),
            'dosens'        => $dosens,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $data = $this->validatedData($request);

        if (!$request->hasFile('pdf')) {
            return back()->withErrors(['pdf' => 'File PDF wajib diupload.'])->withInput();
        }

        $data['pdf_path'] = $request->file('pdf')->store('publications/pdf', 'local');
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
        $this->authorizeAdmin();

        $dosens = User::where('role', 'dosen')
            ->where('registration_status', 'approved')
            ->orderBy('fullname')->get(['id', 'fullname', 'nip']);

        return view('admin.publications.edit', [
            'publication' => $publication,
            'categories'  => Publication::categories(),
            'statuses'    => Publication::statuses(),
            'dosens'      => $dosens,
        ]);
    }

    public function update(Request $request, Publication $publication)
    {
        $this->authorizeAdmin();

        $data = $this->validatedData($request, $publication);

        if ($request->hasFile('pdf')) {
            $newPdfPath = $request->file('pdf')->store('publications/pdf', 'local');
            $this->deleteFile($publication->pdf_path, 'local');
            $data['pdf_path'] = $newPdfPath;
        }
        unset($data['pdf']);

        if ($request->hasFile('thumbnail')) {
            $newThumbPath = $request->file('thumbnail')->store('publications/thumbnails', 'public');
            $this->deleteFile($publication->thumbnail_path);
            $data['thumbnail_path'] = $newThumbPath;
        }
        unset($data['thumbnail']);

        $publication->update($data);

        return redirect()->route('admin.publications.index')->with('success', 'Publication berhasil diperbarui.');
    }

    public function file(Publication $publication)
    {
        $this->authorizeAdmin();

        abort_unless($publication->pdf_path && Storage::disk('local')->exists($publication->pdf_path), 404);

        $filename = str($publication->judul)->slug()->append('.pdf')->toString();

        return Storage::disk('local')->download($publication->pdf_path, $filename);
    }

    public function destroy(Publication $publication)
    {
        $this->authorizeAdmin();

        $this->deleteFile($publication->pdf_path, 'local');
        $this->deleteFile($publication->thumbnail_path);
        $publication->delete();

        return redirect()->route('admin.publications.index')->with('success', 'Publication berhasil dihapus.');
    }

    private function validatedData(Request $request, ?Publication $publication = null): array
    {
        $data = $request->validate([
            'submission_type' => ['required', 'in:member,non_member'],
            'user_id'         => [
                Rule::requiredIf(fn () => $request->input('submission_type') === 'member'),
                'nullable', 'exists:users,id',
            ],
            'recommended_by'  => [
                Rule::requiredIf(fn () => $request->input('submission_type') === 'non_member'),
                'nullable', 'string', 'max:255', new SafeName(),
            ],
            'judul'           => ['required', 'string', 'min:5', 'max:255', new SafeText()],
            'penulis'         => ['required', 'string', 'max:255', new SafeName()],
            'tahun'           => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'abstrak'         => ['required', 'string', new SafeText()],
            'kategori'        => ['required', Rule::in(Publication::categories())],
            'penerbit'        => ['nullable', 'string', 'max:255', new SafeText()],
            'doi'             => [
                'nullable', 'string', 'max:255',
                'regex:/^10\.\d{4,9}\/\S+$/i',
                Rule::unique('publications', 'doi')->ignore($publication?->id),
            ],
            'pdf'             => [$publication ? 'nullable' : 'required', 'file', 'mimes:pdf', 'max:20480'],
            'thumbnail'       => ['nullable', 'image', 'max:4096'],
            'status'          => ['required', Rule::in(Publication::statuses())],
        ]);

        if ($data['submission_type'] === 'non_member') {
            $data['user_id'] = null;
        } else {
            $data['recommended_by'] = null;
        }

        return $data;
    }


    private function deleteFile(?string $path, string $disk = 'public'): void
    {
        if ($path && Storage::disk($disk)->exists($path)) {
            Storage::disk($disk)->delete($path);
        }
    }
}