<?php

namespace App\Http\Controllers;

use App\Models\Hki;
use App\Models\Publication;
use App\Models\User;
use App\Rules\PersonName;
use App\Rules\DssClassification;
use App\Rules\SafeText;
use App\Services\HkiNotifier;
use App\Services\PublicFileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DosenKontenController extends Controller
{
    public function publikasiIndex()
    {
        $user = Auth::user();

        $publikasi = Publication::forDosen($user)
            ->with('recommender:id,fullname')
            ->latest()
            ->paginate(10);

        return view('halaman-dosen.konten.publikasi-index', compact('publikasi'));
    }

    public function publikasiCreate()
    {
        return view('halaman-dosen.konten.publikasi-form', [
            'publication' => new Publication([
                'status' => Publication::STATUS_PUBLISH,
                'submission_type' => 'member',
                'user_id' => Auth::id(),
            ]),
            'categories' => Publication::categories(),
            'mode' => 'create',
        ]);
    }

    public function publikasiStore(Request $request)
    {
        $data = $this->validatePublication($request);
        $data['user_id'] = Auth::id();
        $data['recommended_by'] = null;
        $data['submission_type'] = 'member';
        $data['status'] = Publication::STATUS_PUBLISH;
        unset($data['pdf'], $data['thumbnail']);

        app(PublicFileService::class)->save(new Publication, $data, [
            'pdf_path' => [$request->file('pdf'), 'publications/pdf'],
            'thumbnail_path' => [$request->file('thumbnail'), 'publications/thumbnails'],
        ]);

        return redirect()->route('dosen.publikasi.index')
            ->with('success', 'Publikasi berhasil ditambahkan. Publikasi langsung dipublikasikan dan tampil di halaman publik.');
    }

    public function publikasiEdit(Publication $p)
    {
        $this->authorizeOwnerPublikasi($p);

        return view('halaman-dosen.konten.publikasi-form', [
            'publication' => $p,
            'categories' => Publication::categories(),
            'mode' => 'edit',
        ]);
    }

    public function publikasiUpdate(Request $request, Publication $p)
    {
        $this->authorizeOwnerPublikasi($p);

        $data = $this->validatePublication($request, $p);
        $data['submission_type'] = 'member';
        $data['status'] = Publication::STATUS_PUBLISH;
        unset($data['pdf'], $data['thumbnail']);

        app(PublicFileService::class)->save($p, $data, [
            'pdf_path' => [$request->file('pdf'), 'publications/pdf'],
            'thumbnail_path' => [$request->file('thumbnail'), 'publications/thumbnails'],
        ]);

        return redirect()->route('dosen.publikasi.index')
            ->with('success', 'Publikasi berhasil diperbarui.');
    }

    public function publikasiDestroy(Publication $p)
    {
        $this->authorizeOwnerPublikasi($p);

        app(PublicFileService::class)->delete($p, ['pdf_path', 'thumbnail_path']);

        return redirect()->route('dosen.publikasi.index')
            ->with('success', 'Publikasi berhasil dihapus.');
    }

    public function hkiIndex()
    {
        $hkis = Hki::forDosen(Auth::user())
            ->with('recommender:id,fullname')
            ->latest()
            ->paginate(10);

        return view('halaman-dosen.konten.hki-index', compact('hkis'));
    }

    public function hkiCreate()
    {
        return view('halaman-dosen.konten.hki-form', [
            'hki' => new Hki([
                'status' => Hki::STATUS_PUBLISH,
                'submission_type' => 'member',
                'user_id' => Auth::id(),
            ]),
            'jenis' => Hki::JENIS,
            'mode' => 'create',
            'dosens' => $this->approvedDosens(),
        ]);
    }

    public function hkiStore(Request $request, HkiNotifier $notifier)
    {
        $data = $this->validateHki($request);
        $data['user_id'] = Auth::id();
        $data['recommended_by'] = null;
        $data['submission_type'] = 'member';
        $data['status'] = Hki::STATUS_PUBLISH;
        unset($data['file_sertifikat']);

        app(PublicFileService::class)->save(new Hki, $data, [
            'file_sertifikat' => [$request->file('file_sertifikat'), 'hki/sertifikat'],
        ], function (Hki $saved, array $previous) use ($notifier) {
            $notifier->sync($saved, Auth::user(), $previous['pencipta'] ?? null);
        });

        return redirect()->route('dosen.hki.index')
            ->with('success', 'HKI berhasil ditambahkan. HKI langsung dipublikasikan dan tampil di halaman publik.');
    }

    public function hkiEdit(Hki $h)
    {
        $this->authorizeOwnerHki($h);

        return view('halaman-dosen.konten.hki-form', [
            'hki' => $h,
            'jenis' => Hki::JENIS,
            'mode' => 'edit',
            'dosens' => $this->approvedDosens(),
        ]);
    }

    public function hkiUpdate(Request $request, Hki $h, HkiNotifier $notifier)
    {
        $this->authorizeOwnerHki($h);

        $data = $this->validateHki($request, $h);
        $data['submission_type'] = 'member';
        $data['status'] = Hki::STATUS_PUBLISH;
        unset($data['file_sertifikat']);

        app(PublicFileService::class)->save($h, $data, [
            'file_sertifikat' => [$request->file('file_sertifikat'), 'hki/sertifikat'],
        ], function (Hki $saved, array $previous) use ($notifier) {
            $notifier->sync($saved, Auth::user(), $previous['pencipta'] ?? null);
        });

        return redirect()->route('dosen.hki.index')
            ->with('success', 'HKI berhasil diperbarui.');
    }

    public function hkiDestroy(Hki $h, HkiNotifier $notifier)
    {
        $this->authorizeOwnerHki($h);

        app(PublicFileService::class)->delete(
            $h,
            ['file_sertifikat'],
            fn (Hki $saved) => $notifier->forget($saved)
        );

        return redirect()->route('dosen.hki.index')
            ->with('success', 'HKI berhasil dihapus.');
    }

    private function validatePublication(Request $request, ?Publication $pub = null): array
    {
        DssClassification::prepare($request);
        return $request->validate([
            ...DssClassification::rules(),
            'judul' => ['required', 'string', 'min:5', 'max:200', new SafeText],
            'penulis' => ['required', 'string', 'min:3', 'max:255', new PersonName],
            'tahun' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'abstrak' => ['required', 'string', 'min:20', 'max:5000', new SafeText],
            'kategori' => ['required', Rule::in(Publication::categories())],
            'penerbit' => ['nullable', 'string', 'min:2', 'max:200', new SafeText],
            'doi' => ['nullable', 'string', 'max:255', 'regex:/^[A-Za-z0-9.\/:_()\-]+$/'],
            'pdf' => [$pub ? 'nullable' : 'required', 'file', 'mimes:pdf', 'max:20480'],
            'thumbnail' => ['nullable', 'image', 'max:4096'],
        ]);
    }

    private function validateHki(Request $request, ?Hki $hki = null): array
    {
        DssClassification::prepare($request);
        return $request->validate([
            ...DssClassification::rules(),
            'nomor_sertifikat' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/^[A-Za-z0-9.\/\-\s]+$/',
                Rule::unique('hkis', 'nomor_sertifikat')->ignore($hki?->id),
            ],
            'tgl_terbit' => ['required', 'date'],
            'judul_sertifikat' => ['required', 'string', 'min:5', 'max:200', new SafeText],
            'jenis_sertifikat' => ['required', Rule::in(Hki::JENIS)],
            'pencipta' => ['required', 'string', 'min:3', 'max:255', new PersonName],
            'file_sertifikat' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
        ]);
    }

    private function approvedDosens()
    {
        return User::where('role', 'dosen')
            ->where('registration_status', User::STATUS_APPROVED)
            ->orderBy('fullname')
            ->get(['id', 'fullname', 'nip', 'prodi', 'fakultas']);
    }

    private function authorizeOwnerPublikasi(Publication $p): void
    {
        if ((int) $p->user_id !== (int) Auth::id()) {
            abort(403);
        }
    }

    private function authorizeOwnerHki(Hki $h): void
    {
        if ((int) $h->user_id !== (int) Auth::id()) {
            abort(403);
        }
    }
}