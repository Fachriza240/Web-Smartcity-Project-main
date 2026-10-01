<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hki;
use App\Models\User;
use App\Rules\PersonName;
use App\Rules\SafeText;
use App\Services\HkiNotifier;
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

        return view('admin.hki.index', [
            'hkis'     => $hkis,
            'statuses' => Hki::statuses(),
            'jenis'    => Hki::JENIS,
            'dosens'   => $this->approvedDosens(),
        ]);
    }

    public function create()
    {
        $this->authorizeAdmin();

        return view('admin.hki.create', [
            'hki'      => new Hki(['status' => Hki::STATUS_DRAFT, 'submission_type' => 'non_member']),
            'statuses' => Hki::statuses(),
            'jenis'    => Hki::JENIS,
            'dosens'   => $this->approvedDosens(),
        ]);
    }

    public function store(Request $request, HkiNotifier $notifier)
    {
        $this->authorizeAdmin();

        $data = $this->validatedData($request);

        if ($request->hasFile('file_sertifikat')) {
            $data['file_sertifikat'] = $request->file('file_sertifikat')->store('hki/sertifikat', 'public');
        }

        $hki = Hki::create($data);
        $notifier->sync($hki, Auth::user());

        return redirect()->route('admin.hki.index')->with('success', 'HKI berhasil ditambahkan.');
    }

    public function edit(Hki $hki)
    {
        $this->authorizeAdmin();

        return view('admin.hki.edit', [
            'hki'      => $hki,
            'statuses' => Hki::statuses(),
            'jenis'    => Hki::JENIS,
            'dosens'   => $this->approvedDosens(),
        ]);
    }

    public function update(Request $request, Hki $hki, HkiNotifier $notifier)
    {
        $this->authorizeAdmin();

        $data = $this->validatedData($request, $hki);
        $penciptaLama = $hki->pencipta;

        if ($request->hasFile('file_sertifikat')) {
            $this->deleteFile($hki->file_sertifikat);
            $data['file_sertifikat'] = $request->file('file_sertifikat')->store('hki/sertifikat', 'public');
        }

        $hki->update($data);
        $notifier->sync($hki, Auth::user(), $penciptaLama);

        return redirect()->route('admin.hki.index')->with('success', 'HKI berhasil diperbarui.');
    }

    public function destroy(Hki $hki, HkiNotifier $notifier)
    {
        $this->authorizeAdmin();

        $notifier->forget($hki);
        $this->deleteFile($hki->file_sertifikat);
        $hki->delete();

        return redirect()->route('admin.hki.index')->with('success', 'HKI berhasil dihapus.');
    }

    private function validatedData(Request $request, ?Hki $hki = null): array
    {
        $data = $request->validate([
            'nomor_sertifikat'  => ['required', 'string', 'min:3', 'max:100', 'regex:/^[A-Za-z0-9.\/\-\s]+$/', Rule::unique('hkis', 'nomor_sertifikat')->ignore($hki?->id)],
            'tgl_terbit'        => ['required', 'date'],
            'judul_sertifikat'  => ['required', 'string', 'min:5', 'max:200', new SafeText],
            'jenis_sertifikat'  => ['required', Rule::in(Hki::JENIS)],
            'pencipta'          => ['required', 'string', 'min:3', 'max:255', new PersonName],
            'submission_type'   => ['required', 'in:member,non_member'],
            'user_id'           => ['nullable', 'exists:users,id'],
            'recommended_by'    => ['nullable', 'string', 'min:3', 'max:100', new PersonName],
            'file_sertifikat'   => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'status'            => ['required', Rule::in(Hki::statuses())],
        ]);

        if ($data['submission_type'] === 'non_member') {
            $data['user_id'] = null;
        } else {
            $data['recommended_by'] = null;
        }

        return $data;
    }

    private function approvedDosens()
    {
        return User::where('role', 'dosen')
            ->where('registration_status', User::STATUS_APPROVED)
            ->orderBy('fullname')
            ->get(['id', 'fullname', 'nip', 'prodi', 'fakultas']);
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
}