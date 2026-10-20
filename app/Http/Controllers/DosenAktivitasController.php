<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ValidatesAktivitasDosen;
use App\Models\AktivitasDosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DosenAktivitasController extends Controller
{
    use ValidatesAktivitasDosen;

    public function index(Request $request)
    {
        $jenis = (string) $request->query('jenis', '');
        $jenisDipilih = in_array($jenis, AktivitasDosen::jenisKeys(), true) ? $jenis : '';

        $aktivitas = AktivitasDosen::milik(Auth::user())
            ->when($jenisDipilih !== '', fn ($q) => $q->where('jenis', $jenisDipilih))
            ->orderByDesc('tahun')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('halaman-dosen.konten.aktivitas-index', [
            'aktivitas' => $aktivitas,
            'jenisList' => AktivitasDosen::jenisList(),
            'jenisDipilih' => $jenisDipilih,
            'tahunMulai' => AktivitasDosen::tahunMulai(),
        ]);
    }

    public function create(Request $request)
    {
        $jenis = (string) $request->query('jenis', '');

        return view('halaman-dosen.konten.aktivitas-form', [
            'aktivitas' => new AktivitasDosen([
                'jenis' => in_array($jenis, AktivitasDosen::jenisKeys(), true) ? $jenis : null,
                'tahun' => (int) date('Y'),
                'peran' => 'anggota',
                'status' => AktivitasDosen::STATUS_PUBLISH,
            ]),
            'mode' => 'create',
            'tahunMulai' => AktivitasDosen::tahunMulai(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validasiAktivitas($request, null, Auth::id());
        $data['user_id'] = Auth::id();
        $data['status'] = AktivitasDosen::STATUS_PUBLISH;

        AktivitasDosen::create($data);

        return redirect()->route('dosen.aktivitas.index')
            ->with('success', 'Aktivitas berhasil ditambahkan.');
    }

    public function edit(AktivitasDosen $aktivitas)
    {
        $this->authorizeOwner($aktivitas);

        return view('halaman-dosen.konten.aktivitas-form', [
            'aktivitas' => $aktivitas,
            'mode' => 'edit',
            'tahunMulai' => AktivitasDosen::tahunMulai(),
        ]);
    }

    public function update(Request $request, AktivitasDosen $aktivitas)
    {
        $this->authorizeOwner($aktivitas);

        $data = $this->validasiAktivitas($request, $aktivitas, Auth::id());
        $data['status'] = AktivitasDosen::STATUS_PUBLISH;

        $aktivitas->update($data);

        return redirect()->route('dosen.aktivitas.index')
            ->with('success', 'Aktivitas berhasil diperbarui.');
    }

    public function destroy(AktivitasDosen $aktivitas)
    {
        $this->authorizeOwner($aktivitas);

        $aktivitas->delete();

        return redirect()->route('dosen.aktivitas.index')
            ->with('success', 'Aktivitas berhasil dihapus.');
    }

    private function authorizeOwner(AktivitasDosen $aktivitas): void
    {
        abort_unless($aktivitas->isOwnedBy(Auth::user()), 403);
    }
}