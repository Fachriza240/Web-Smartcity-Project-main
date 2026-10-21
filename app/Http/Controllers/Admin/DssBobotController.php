<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DssAhp;
use App\Models\DssKriteria;
use App\Rules\SafeText;
use App\Services\AhpService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;

class DssBobotController extends Controller
{

    private const CONTOH = [
        'C1-C2' => '2', 'C1-C3' => '2', 'C1-C4' => '3', 'C1-C5' => '4',
        'C2-C3' => '2', 'C2-C4' => '2', 'C2-C5' => '3',
        'C3-C4' => '2', 'C3-C5' => '2',
        'C4-C5' => '2',
    ];

    public function __construct(private AhpService $ahp)
    {
    }

    public function index(Request $request)
    {
        $this->authorizeAdmin();

        $kriteria = DssKriteria::daftar();

        return $this->tampilkan($kriteria, $this->nilaiAwal($request, $kriteria), null);
    }

    public function hitung(Request $request)
    {
        $this->authorizeAdmin();

        $kriteria = $this->kriteriaSiap();
        if (! $kriteria) {
            return $this->kriteriaBelumAda();
        }

        ['nilai' => $nilai, 'catatan' => $catatan] = $this->validasiPerbandingan($request, $kriteria);

        return $this->tampilkan($kriteria, $nilai, $this->hitungDariNilai($kriteria, $nilai), $catatan);
    }

    public function simpan(Request $request)
    {
        $this->authorizeAdmin();

        $kriteria = $this->kriteriaSiap();
        if (! $kriteria) {
            return $this->kriteriaBelumAda();
        }

        ['nilai' => $nilai, 'catatan' => $catatan] = $this->validasiPerbandingan($request, $kriteria);
        $hasil = $this->hitungDariNilai($kriteria, $nilai);

        if (! $hasil['konsisten']) {
            return redirect()->route('admin.dss.bobot.index')
                ->withInput()
                ->withErrors(['perbandingan' => 'Consistency Ratio ' . $this->angka($hasil['cr'], 4)
                    . ' melebihi batas ' . $this->angka($hasil['ambang_cr'], 2)
                    . '. Perbaiki nilai perbandingan agar lebih konsisten, lalu hitung ulang.']);
        }

        $kodeList = $kriteria->pluck('kode')->all();
        $bobot = $this->ahp->bulatkanBobot(array_combine($kodeList, $hasil['bobot']));

        DB::transaction(function () use ($kriteria, $hasil, $kodeList, $bobot, $catatan) {
            $ahp = DssAhp::create([
                'matriks' => ['kriteria' => $kodeList, 'nilai' => $hasil['matriks']],
                'bobot' => $bobot,
                'lambda_max' => round($hasil['lambda_max'], 6),
                'ci' => round($hasil['ci'], 6),
                'cr' => round($hasil['cr'], 6),
                'konsisten' => true,
                'aktif' => false,
                'catatan' => $catatan,
                'dibuat_oleh' => Auth::id(),
            ]);

            $ahp->jadikanAktif();

            foreach ($kriteria as $item) {
                $item->update(['bobot' => $bobot[$item->kode]]);
            }
        });

        return redirect()->route('admin.dss.bobot.index')
            ->with('success', 'Bobot AHP berhasil disimpan dan kini dipakai untuk penilaian. CR = ' . $this->angka($hasil['cr'], 4) . '.');
    }

    public function reset()
    {
        $this->authorizeAdmin();

        DB::transaction(function () {
            foreach (DssKriteria::daftar() as $item) {
                $awal = config('dss.kriteria.' . $item->kode . '.bobot_awal');

                if ($awal !== null) {
                    $item->update(['bobot' => (float) $awal]);
                }
            }

            DssAhp::where('aktif', true)->update(['aktif' => false]);
        });

        return redirect()->route('admin.dss.bobot.index')
            ->with('success', 'Bobot dikembalikan ke nilai awal.');
    }

    private function tampilkan(Collection $kriteria, array $nilai, ?array $hasil, ?string $catatan = null)
    {
        $daftar = $kriteria->values();
        $pasangan = [];

        for ($i = 0; $i < $daftar->count(); $i++) {
            for ($j = $i + 1; $j < $daftar->count(); $j++) {
                $pasangan[] = [$daftar[$i], $daftar[$j]];
            }
        }

        $opsi = [];
        for ($k = 9; $k >= 2; $k--) {
            $opsi[] = ['nilai' => (string) $k, 'arah' => 'kiri', 'angka' => $k];
        }
        $opsi[] = ['nilai' => '1', 'arah' => 'tengah', 'angka' => 1];
        for ($k = 2; $k <= 9; $k++) {
            $opsi[] = ['nilai' => '1/' . $k, 'arah' => 'kanan', 'angka' => $k];
        }

        return view('admin.dss.bobot', [
            'kriteria' => $kriteria,
            'pasangan' => $pasangan,
            'nilai' => $nilai,
            'opsi' => $opsi,
            'skalaLabel' => AhpService::SKALA_LABEL,
            'aktif' => DssAhp::terpakai(),
            'riwayat' => DssAhp::with('pembuat:id,fullname')->latest('id')->limit(5)->get(),
            'hasil' => $hasil,
            'catatan' => $catatan,
        ]);
    }

    private function nilaiAwal(Request $request, Collection $kriteria): array
    {
        $kode = $kriteria->pluck('kode')->values()->all();
        $nilai = [];

        for ($i = 0; $i < count($kode); $i++) {
            for ($j = $i + 1; $j < count($kode); $j++) {
                $nilai[$kode[$i]][$kode[$j]] = '1';
            }
        }

        if ($request->boolean('contoh')) {
            foreach (self::CONTOH as $kunci => $isi) {
                [$a, $b] = explode('-', $kunci);

                if (isset($nilai[$a][$b])) {
                    $nilai[$a][$b] = $isi;
                }
            }

            return $nilai;
        }

        $aktif = DssAhp::terpakai();

        if ($aktif && ($aktif->matriks['kriteria'] ?? null) === $kode && isset($aktif->matriks['nilai'])) {
            for ($i = 0; $i < count($kode); $i++) {
                for ($j = $i + 1; $j < count($kode); $j++) {
                    $nilai[$kode[$i]][$kode[$j]] = AhpService::formatNilai((float) ($aktif->matriks['nilai'][$i][$j] ?? 1));
                }
            }
        }

        return $nilai;
    }

    private function validasiPerbandingan(Request $request, Collection $kriteria): array
    {
        $kode = $kriteria->pluck('kode')->values()->all();
        $diizinkan = AhpService::nilaiDiizinkan();

        $aturan = ['catatan' => ['nullable', 'string', 'max:255', new SafeText]];
        $atribut = ['catatan' => 'Catatan'];

        for ($i = 0; $i < count($kode); $i++) {
            for ($j = $i + 1; $j < count($kode); $j++) {
                $kunci = "perbandingan.{$kode[$i]}.{$kode[$j]}";
                $aturan[$kunci] = ['required', 'string', Rule::in($diizinkan)];
                $atribut[$kunci] = "Perbandingan {$kode[$i]} dengan {$kode[$j]}";
            }
        }

        try {
            $data = $request->validate($aturan, [], $atribut);
        } catch (ValidationException $e) {
            throw $e->redirectTo(route('admin.dss.bobot.index'));
        }

        $nilai = [];
        for ($i = 0; $i < count($kode); $i++) {
            for ($j = $i + 1; $j < count($kode); $j++) {
                $nilai[$kode[$i]][$kode[$j]] = $data['perbandingan'][$kode[$i]][$kode[$j]];
            }
        }

        return ['nilai' => $nilai, 'catatan' => $data['catatan'] ?? null];
    }

    private function hitungDariNilai(Collection $kriteria, array $nilai): array
    {
        $kode = $kriteria->pluck('kode')->values()->all();
        $atas = [];

        for ($i = 0; $i < count($kode); $i++) {
            for ($j = $i + 1; $j < count($kode); $j++) {
                $atas[$i][$j] = AhpService::parseNilai($nilai[$kode[$i]][$kode[$j]]);
            }
        }

        try {
            return $this->ahp->hitung(AhpService::buatMatriks(count($kode), $atas));
        } catch (InvalidArgumentException $e) {
            throw ValidationException::withMessages(['perbandingan' => $e->getMessage()])
                ->redirectTo(route('admin.dss.bobot.index'));
        }
    }

    private function kriteriaSiap(): ?Collection
    {
        $kriteria = DssKriteria::daftar();

        return $kriteria->count() >= 2 ? $kriteria : null;
    }

    private function kriteriaBelumAda()
    {
        return redirect()->route('admin.dss.bobot.index')
            ->with('error', 'Data kriteria belum tersedia. Jalankan: php artisan db:seed --class=DssKriteriaSeeder');
    }

    private function angka(float $nilai, int $desimal): string
    {
        return number_format($nilai, $desimal, ',', '.');
    }

    private function authorizeAdmin(): void
    {
        abort_unless(Auth::check() && Auth::user()->role === 'admin', 403);
    }
}