<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hki;
use App\Models\User;
use App\Notifications\HkiAddedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class HkiController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeAdmin();

        $hkis = Hki::query()
            ->when($request->filled('search'), fn ($q) => $q->where(function ($q) use ($request) {
                $q->where('judul_sertifikat', 'like', "%{$request->search}%")
                  ->orWhere('nomor_sertifikat', 'like', "%{$request->search}%")
                  ->orWhere('pencipta', 'like', "%{$request->search}%");
            }))
            ->when($request->filled('jenis'),  fn ($q) => $q->where('jenis_sertifikat', $request->jenis))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $dosens = User::where('role', 'dosen')
            ->where('registration_status', 'approved')
            ->orderBy('fullname')
            ->get(['id', 'fullname', 'nip', 'prodi', 'fakultas']);

        return view('admin.hki.index', [
            'hkis'     => $hkis,
            'statuses' => Hki::statuses(),
            'jenis'    => Hki::JENIS,
            'dosens'   => $dosens,
        ]);
    }

    public function create()
    {
        $this->authorizeAdmin();

        $dosens = User::where('role', 'dosen')
            ->where('registration_status', 'approved')
            ->orderBy('fullname')
            ->get(['id', 'fullname', 'nip', 'prodi', 'fakultas']);

        return view('admin.hki.create', [
            'hki'      => new Hki(['status' => Hki::STATUS_DRAFT, 'submission_type' => 'non_member']),
            'statuses' => Hki::statuses(),
            'jenis'    => Hki::JENIS,
            'dosens'   => $dosens,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $data = $this->validatedData($request);

        if ($request->hasFile('file_sertifikat')) {
            $data['file_sertifikat'] = $request->file('file_sertifikat')->store('hki/sertifikat', 'public');
        }
        unset($data['file_sertifikat_upload']);

        $hki = Hki::create($data);
        $this->sendHkiNotifications($hki);

        return redirect()->route('admin.hki.index')->with('success', 'HKI berhasil ditambahkan.');
    }

    public function edit(Hki $hki)
    {
        $this->authorizeAdmin();

        $dosens = User::where('role', 'dosen')
            ->where('registration_status', 'approved')
            ->orderBy('fullname')
            ->get(['id', 'fullname', 'nip', 'prodi', 'fakultas']);

        return view('admin.hki.edit', [
            'hki'      => $hki,
            'statuses' => Hki::statuses(),
            'jenis'    => Hki::JENIS,
            'dosens'   => $dosens,
        ]);
    }

    public function update(Request $request, Hki $hki)
    {
        $this->authorizeAdmin();

        $data = $this->validatedData($request, $hki);

        if ($request->hasFile('file_sertifikat')) {
            $this->deleteFile($hki->file_sertifikat);
            $data['file_sertifikat'] = $request->file('file_sertifikat')->store('hki/sertifikat', 'public');
        }
        unset($data['file_sertifikat_upload']);

        $hki->update($data);
        $this->sendHkiNotifications($hki);

        return redirect()->route('admin.hki.index')->with('success', 'HKI berhasil diperbarui.');
    }

    public function destroy(Hki $hki)
    {
        $this->authorizeAdmin();

        $this->deleteFile($hki->file_sertifikat);
        $hki->delete();

        return redirect()->route('admin.hki.index')->with('success', 'HKI berhasil dihapus.');
    }

    private function validatedData(Request $request, ?Hki $hki = null): array
    {
        $data = $request->validate([
            'nomor_sertifikat'  => ['required', 'string', 'max:255', Rule::unique('hkis', 'nomor_sertifikat')->ignore($hki?->id)],
            'tgl_terbit'        => ['required', 'date'],
            'judul_sertifikat'  => ['required', 'string', 'max:255'],
            'jenis_sertifikat'  => ['required', Rule::in(Hki::JENIS)],
            'pencipta'          => ['required', 'string', 'max:255'],
            'submission_type'   => ['required', 'in:member,non_member'],
            'user_id'           => ['nullable', 'exists:users,id'],
            'recommended_by'    => ['nullable', 'string', 'max:255'],
            'file_sertifikat'   => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'status'            => ['required', Rule::in(Hki::statuses())],
        ]);

        // Non-member: hapus user_id
        if ($data['submission_type'] === 'non_member') {
            $data['user_id'] = null;
        } else {
            $data['recommended_by'] = null;
        }

        return $data;
    }

    private function authorizeAdmin(): void
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403);
        }
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
                                 ->get();

            foreach ($usersToNotify as $user) {
                // Admin can notify everyone, so no Auth::id() exclusion needed
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
}
