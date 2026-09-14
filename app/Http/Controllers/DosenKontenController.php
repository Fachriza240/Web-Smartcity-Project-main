<?php

namespace App\Http\Controllers;

use App\Models\Hki;
use App\Models\Publication;
use App\Models\User;
use App\Notifications\HkiAddedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class DosenKontenController extends Controller
{
    public function publikasiIndex()
    {
        $user = Auth::user();

        $publikasi = Publication::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('halaman-dosen.konten.publikasi-index', compact('publikasi'));
    }

    public function publikasiCreate()
    {
        return view('halaman-dosen.konten.publikasi-form', [
            'publication' => new Publication([
                'status'          => Publication::STATUS_DRAFT,
                'submission_type' => 'member',
                'user_id'         => Auth::id(),
            ]),
            'categories' => Publication::categories(),
            'statuses'   => Publication::statuses(),
            'mode'       => 'create',
        ]);
    }

    public function publikasiStore(Request $request)
    {
        $data = $this->validatePublication($request);
        $data['user_id']         = Auth::id();
        $data['submission_type'] = 'member';
        $data['recommended_by']  = null;
        $data['status'] = Publication::STATUS_DRAFT;

        if (!$request->hasFile('pdf')) {
            return back()->withErrors(['pdf' => 'File PDF wajib diupload.'])->withInput();
        }

        $data['pdf_path'] = $request->file('pdf')->store('publications/pdf', 'public');
        unset($data['pdf']);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail_path'] = $request->file('thumbnail')
                ->store('publications/thumbnails', 'public');
        }
        unset($data['thumbnail']);

        Publication::create($data);

        return redirect()->route('dosen.publikasi.index')
            ->with('success', 'Publikasi berhasil ditambahkan. Menunggu review admin untuk dipublikasikan.');
    }

    public function publikasiEdit(Publication $p)
    {
        $this->authorizeOwnerPublikasi($p);

        return view('halaman-dosen.konten.publikasi-form', [
            'publication' => $p,
            'categories'  => Publication::categories(),
            'statuses'    => Publication::statuses(),
            'mode'        => 'edit',
        ]);
    }

    public function publikasiUpdate(Request $request, Publication $p)
    {
        $this->authorizeOwnerPublikasi($p);

        $data = $this->validatePublication($request, $p);

        if ($request->hasFile('pdf')) {
            $this->deleteFile($p->pdf_path);
            $data['pdf_path'] = $request->file('pdf')->store('publications/pdf', 'public');
        }
        unset($data['pdf']);

        if ($request->hasFile('thumbnail')) {
            $this->deleteFile($p->thumbnail_path);
            $data['thumbnail_path'] = $request->file('thumbnail')
                ->store('publications/thumbnails', 'public');
        }
        unset($data['thumbnail']);

        $data['status'] = Publication::STATUS_DRAFT;

        $p->update($data);

        return redirect()->route('dosen.publikasi.index')
            ->with('success', 'Publikasi berhasil diperbarui dan menunggu review admin kembali.');
    }

    public function publikasiDestroy(Publication $p)
    {
        $this->authorizeOwnerPublikasi($p);

        $this->deleteFile($p->pdf_path);
        $this->deleteFile($p->thumbnail_path);
        $p->delete();

        return redirect()->route('dosen.publikasi.index')
            ->with('success', 'Publikasi berhasil dihapus.');
    }

    public function hkiIndex()
    {
        $hkis = Hki::where('user_id', Auth::id())->latest()->paginate(10);

        return view('halaman-dosen.konten.hki-index', compact('hkis'));
    }

    public function hkiCreate()
    {
        $dosens = User::where('role', 'dosen')
            ->where('registration_status', 'approved')
            ->orderBy('fullname')
            ->get(['id', 'fullname', 'nip', 'prodi', 'fakultas']);

        return view('halaman-dosen.konten.hki-form', [
            'hki'      => new Hki([
                'status'          => Hki::STATUS_DRAFT,
                'submission_type' => 'member',
                'user_id'         => Auth::id(),
            ]),
            'jenis'    => Hki::JENIS,
            'statuses' => Hki::statuses(),
            'mode'     => 'create',
            'dosens'   => $dosens,
        ]);
    }

    public function hkiStore(Request $request)
    {
        $data = $this->validateHki($request);
        $data['user_id']         = Auth::id();
        $data['submission_type'] = 'member';
        $data['recommended_by']  = null;

        if ($request->hasFile('file_sertifikat')) {
            $data['file_sertifikat'] = $request->file('file_sertifikat')
                ->store('hki/sertifikat', 'public');
        }

        $hki = Hki::create($data);
        $this->sendHkiNotifications($hki);

        return redirect()->route('dosen.hki.index')
            ->with('success', 'HKI berhasil ditambahkan. Menunggu review admin untuk dipublikasikan.');
    }

    public function hkiEdit(Hki $h)
    {
        $this->authorizeOwnerHki($h);

        $dosens = User::where('role', 'dosen')
            ->where('registration_status', 'approved')
            ->orderBy('fullname')
            ->get(['id', 'fullname', 'nip', 'prodi', 'fakultas']);

        return view('halaman-dosen.konten.hki-form', [
            'hki'      => $h,
            'jenis'    => Hki::JENIS,
            'statuses' => Hki::statuses(),
            'mode'     => 'edit',
            'dosens'   => $dosens,
        ]);
    }

    public function hkiUpdate(Request $request, Hki $h)
    {
        $this->authorizeOwnerHki($h);

        $data = $this->validateHki($request, $h);

        if ($request->hasFile('file_sertifikat')) {
            $this->deleteFile($h->file_sertifikat);
            $data['file_sertifikat'] = $request->file('file_sertifikat')
                ->store('hki/sertifikat', 'public');
        }

        $h->update($data);
        $this->sendHkiNotifications($h);

        return redirect()->route('dosen.hki.index')
            ->with('success', 'HKI berhasil diperbarui.');
    }

    public function hkiDestroy(Hki $h)
    {
        $this->authorizeOwnerHki($h);

        $this->deleteFile($h->file_sertifikat);
        $h->delete();

        return redirect()->route('dosen.hki.index')
            ->with('success', 'HKI berhasil dihapus.');
    }

    private function validatePublication(Request $request, ?Publication $pub = null): array
    {
        return $request->validate([
            'judul'     => ['required', 'string', 'max:255'],
            'penulis'   => ['required', 'string', 'max:255'],
            'tahun'     => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'abstrak'   => ['required', 'string'],
            'kategori'  => ['required', Rule::in(Publication::categories())],
            'penerbit'  => ['nullable', 'string', 'max:255'],
            'doi'       => ['nullable', 'string', 'max:255'],
            'pdf'       => [$pub ? 'nullable' : 'required', 'file', 'mimes:pdf', 'max:20480'],
            'thumbnail' => ['nullable', 'image', 'max:4096'],
        ]);
    }

    private function validateHki(Request $request, ?Hki $hki = null): array
    {
        return $request->validate([
            'nomor_sertifikat' => [
                'required', 'string', 'max:255',
                Rule::unique('hkis', 'nomor_sertifikat')->ignore($hki?->id),
            ],
            'tgl_terbit'       => ['required', 'date'],
            'judul_sertifikat' => ['required', 'string', 'max:255'],
            'jenis_sertifikat' => ['required', Rule::in(Hki::JENIS)],
            'pencipta'         => ['required', 'string', 'max:255'],
            'file_sertifikat'  => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'status'           => ['required', Rule::in(Hki::statuses())],
        ]);
    }

    private function authorizeOwnerPublikasi(Publication $p): void
    {
        if ($p->user_id !== Auth::id()) abort(403);
    }

    private function authorizeOwnerHki(Hki $h): void
    {
        if ($h->user_id !== Auth::id()) abort(403);
    }

    private function deleteFile(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    private function sendHkiNotifications(Hki $hki): void
    {
        if (empty($hki->pencipta)) {
            return;
        }

        $names = array_map('trim', explode(',', $hki->pencipta));
        
        if (count($names) > 0) {
            $usersToNotify = User::whereIn('fullname', $names)
                                 ->where('role', 'dosen')
                                 ->where('id', '!=', Auth::id())
                                 ->get();

            foreach ($usersToNotify as $user) {
                $alreadyNotified = $user->notifications()
                                        ->where('type', HkiAddedNotification::class)
                                        ->where('data->hki_id', $hki->id)
                                        ->exists();
                
                if (!$alreadyNotified) {
                    $user->notify(new HkiAddedNotification($hki));
                }
            }
        }
    }

    public function markNotificationAsRead($id)
    {
        $notification = Auth::user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
            
            return redirect()->route('dosen.hki.index');
        }
        return back();
    }
}