<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\ValidatesAktivitasDosen;
use App\Http\Controllers\Controller;
use App\Models\AktivitasDosen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AktivitasDosenController extends Controller
{
    use ValidatesAktivitasDosen;

    public function index(Request $request)
    {
        $this->authorizeAdmin();

        $kata = $request->string('search')->trim()->toString();
        $jenis = $request->string('jenis')->toString();
        $status = $request->string('status')->toString();
        $userId = $request->integer('user_id');
        $tahun = $request->integer('tahun');

        $aktivitas = AktivitasDosen::query()
            ->with('user:id,fullname,prodi')
            ->when($kata !== '', function ($q) use ($kata) {
                $q->where(function ($q) use ($kata) {
                    $q->where('judul', 'like', "%{$kata}%")
                        ->orWhere('mitra', 'like', "%{$kata}%")
                        ->orWhereHas('user', fn ($u) => $u->where('fullname', 'like', "%{$kata}%"));
                });
            })
            ->when(in_array($jenis, AktivitasDosen::jenisKeys(), true), fn ($q) => $q->where('jenis', $jenis))
            ->when(in_array($status, AktivitasDosen::statuses(), true), fn ($q) => $q->where('status', $status))
            ->when($userId > 0, fn ($q) => $q->where('user_id', $userId))
            ->when($tahun > 0, fn ($q) => $q->where('tahun', $tahun))
            ->orderByDesc('tahun')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.aktivitas-dosen.index', [
            'aktivitas' => $aktivitas,
            'jenisList' => AktivitasDosen::jenisList(),
            'statuses' => AktivitasDosen::statuses(),
            'dosens' => $this->dosenTerdaftar(),
        ]);
    }

    public function create()
    {
        $this->authorizeAdmin();

        return view('admin.aktivitas-dosen.create', [
            'aktivitas' => new AktivitasDosen([
                'tahun' => (int) date('Y'),
                'peran' => 'anggota',
                'status' => AktivitasDosen::STATUS_PUBLISH,
            ]),
            'dosens' => $this->dosenTerdaftar(),
            'statuses' => AktivitasDosen::statuses(),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $data = $this->validasiAktivitas(
            $request,
            null,
            $request->integer('user_id'),
            $this->aturanAdmin(),
            ['user_id' => 'Dosen', 'status' => 'Status']
        );

        AktivitasDosen::create($data);

        return redirect()->route('admin.aktivitas-dosen.index')
            ->with('success', 'Aktivitas dosen berhasil ditambahkan.');
    }

    public function edit(AktivitasDosen $aktivitas)
    {
        $this->authorizeAdmin();

        return view('admin.aktivitas-dosen.edit', [
            'aktivitas' => $aktivitas,
            'dosens' => $this->dosenTerdaftar(),
            'statuses' => AktivitasDosen::statuses(),
        ]);
    }

    public function update(Request $request, AktivitasDosen $aktivitas)
    {
        $this->authorizeAdmin();

        $data = $this->validasiAktivitas(
            $request,
            $aktivitas,
            $request->integer('user_id'),
            $this->aturanAdmin(),
            ['user_id' => 'Dosen', 'status' => 'Status']
        );

        $aktivitas->update($data);

        return redirect()->route('admin.aktivitas-dosen.index')
            ->with('success', 'Aktivitas dosen berhasil diperbarui.');
    }

    public function destroy(AktivitasDosen $aktivitas)
    {
        $this->authorizeAdmin();

        $aktivitas->delete();

        return redirect()->route('admin.aktivitas-dosen.index')
            ->with('success', 'Aktivitas dosen berhasil dihapus.');
    }

    private function aturanAdmin(): array
    {
        return [
            'user_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')
                    ->where('role', 'dosen')
                    ->where('registration_status', User::STATUS_APPROVED),
            ],
            'status' => ['required', Rule::in(AktivitasDosen::statuses())],
        ];
    }

    private function dosenTerdaftar()
    {
        return User::where('role', 'dosen')
            ->where('registration_status', User::STATUS_APPROVED)
            ->orderBy('fullname')
            ->get(['id', 'fullname', 'nip', 'prodi']);
    }

    private function authorizeAdmin(): void
    {
        abort_unless(Auth::check() && Auth::user()->role === 'admin', 403);
    }
}