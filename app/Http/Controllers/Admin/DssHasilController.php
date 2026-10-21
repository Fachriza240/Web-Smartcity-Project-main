<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DssHasil;
use App\Models\DssKriteria;
use App\Models\User;
use App\Services\DssHitungService;
use App\Services\DssLaporanService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;
use PDOException;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DssHasilController extends Controller
{
    public function __construct(
        private DssLaporanService $laporan,
        private DssHitungService $hitung,
    ) {
    }

    public function index(Request $request)
    {
        $this->authorizeAdmin();

        $semua = DssHasil::query()->with('user:id,fullname,prodi,fakultas')->get();

        $daftarProdi = $semua
            ->map(fn ($hasil) => trim((string) $hasil->user?->prodi))
            ->filter()
            ->unique()
            ->sort()
            ->values();

        ['prodi' => $prodi, 'kategori' => $kategori] = $this->filter($request, $daftarProdi->all());

        $hasil = $this->queryTerfilter($prodi, $kategori)->paginate(20)->withQueryString();
        $kalibrasi = $this->laporan->kalibrasi($semua);

        $teratas = $semua->sortBy('peringkat')->first();

        return view('admin.dss.hasil', [
            'hasil' => $hasil,
            'ada' => $semua->isNotEmpty(),
            'daftarProdi' => $daftarProdi,
            'daftarKategori' => collect((array) config('dss.kategori', []))->pluck('label')->all(),
            'prodi' => $prodi,
            'kategori' => $kategori,
            'kalibrasi' => $kalibrasi,
            'teratas' => $teratas,
            'warna' => $this->laporan->warnaKategori(),
            'terakhir' => $this->laporan->terakhirDihitung(),
            'kedaluwarsa' => $this->laporan->kedaluwarsaGlobal(),
        ]);
    }

    public function show(User $dosen)
    {
        $this->authorizeAdmin();

        abort_unless($dosen->role === 'dosen', 404);

        $hasil = DssHasil::query()->where('user_id', $dosen->id)->first();

        if (! $hasil) {
            return redirect()->route('admin.dss.hasil.index')
                ->with('warning', 'Dosen ini belum memiliki hasil perhitungan. Klik Hitung Ulang terlebih dahulu.');
        }

        $semua = DssHasil::query()->get();
        $kalibrasi = $this->laporan->kalibrasi($semua);
        $maks = collect($kalibrasi['kriteria'])->map(fn ($item) => $item['maks'])->all();
        $baris = $this->laporan->barisKriteria($hasil, $maks);

        return view('admin.dss.hasil-detail', [
            'dosen' => $dosen,
            'hasil' => $hasil,
            'baris' => $baris,
            'rincian' => $this->laporan->rincianPoin($hasil),
            'dimensi' => (array) ($hasil->kompetensi['dimensi'] ?? []),
            'kategori' => $this->laporan->infoKategori((string) $hasil->kategori),
            'totalDosen' => $semua->count(),
            'radar' => [
                'labels' => array_column($baris, 'nama'),
                'nilai' => array_map(fn ($item) => round($item['normal'], 2), $baris),
            ],
            'terakhir' => $this->laporan->terakhirDihitung(),
            'kedaluwarsa' => $this->laporan->kedaluwarsaGlobal(),
        ]);
    }

    public function peta()
    {
        $this->authorizeAdmin();

        $semua = DssHasil::query()->with('user:id,fullname')->get();
        $peta = $this->laporan->petaDimensi($semua);

        return view('admin.dss.peta', [
            'peta' => $peta,
            'totalDosen' => $semua->count(),
            'grafik' => [
                'labels' => array_values(array_map(fn ($item) => $item['label'], $peta)),
                'nilai' => array_values(array_map(fn ($item) => $item['jumlah_dosen'], $peta)),
            ],
            'terakhir' => $this->laporan->terakhirDihitung(),
            'kedaluwarsa' => $this->laporan->kedaluwarsaGlobal(),
        ]);
    }

    public function hitung()
    {
        $this->authorizeAdmin();

        try {
            $ringkasan = $this->hitung->hitungSemua();
        } catch (PDOException $e) {
            // QueryException turunan RuntimeException. Dilempar ulang agar ditangani handler
            // di bootstrap/app.php dan pesan SQL tidak tampil ke pengguna.
            throw $e;
        } catch (RuntimeException|InvalidArgumentException $e) {
            return redirect()->route('admin.dss.hasil.index')->with('error', $e->getMessage());
        }

        return redirect()->route('admin.dss.hasil.index')
            ->with('success', "Perhitungan ulang selesai. {$ringkasan['jumlah']} dosen dinilai pada "
                . $ringkasan['dihitung_pada']->translatedFormat('d M Y H:i') . '.');
    }

    public function ekspor(Request $request): StreamedResponse
    {
        $this->authorizeAdmin();

        $semua = DssHasil::query()->with('user:id,fullname,prodi,fakultas')->get();
        $daftarProdi = $semua->map(fn ($hasil) => trim((string) $hasil->user?->prodi))->filter()->unique()->values()->all();

        ['prodi' => $prodi, 'kategori' => $kategori] = $this->filter($request, $daftarProdi);

        $baris = $this->queryTerfilter($prodi, $kategori)->get();
        $kriteria = DssKriteria::daftar();
        $nama = 'dss-peringkat-dosen-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($baris, $kriteria) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");

            $judul = ['Peringkat', 'Nama Dosen', 'Program Studi', 'Fakultas'];
            foreach ($kriteria as $item) {
                $judul[] = "Poin {$item->kode} {$item->nama}";
            }
            foreach ($kriteria as $item) {
                $judul[] = "Skor {$item->kode} (0-100)";
            }
            array_push($judul, 'Skor Akhir', 'Kategori', 'Kekuatan Utama', 'Cluster', 'Rekomendasi Peran', 'Dimensi Smart City Dominan', 'Gap Analysis', 'Dihitung Pada');
            fputcsv($out, $judul, ';', '"', '');

            foreach ($baris as $hasil) {
                $isi = [
                    $hasil->peringkat,
                    $hasil->user ? $this->aman((string) $hasil->user->fullname) : '-',
                    $this->aman((string) ($hasil->user?->prodi ?? '')),
                    $this->aman((string) ($hasil->user?->fakultas ?? '')),
                ];
                foreach ($kriteria as $item) {
                    $isi[] = $this->desimal($hasil->poin_mentah[$item->kode] ?? 0);
                }
                foreach ($kriteria as $item) {
                    $isi[] = $this->desimal($hasil->skor_normal[$item->kode] ?? 0);
                }
                array_push(
                    $isi,
                    $this->desimal($hasil->skor_akhir),
                    (string) $hasil->kategori,
                    (string) ($hasil->kekuatan_utama ?? '-'),
                    (string) ($hasil->cluster ?? '-'),
                    (string) ($hasil->rekomendasi_peran ?? '-'),
                    (string) ($hasil->kompetensi['dominan_label'] ?? '-'),
                    collect($hasil->gap ?? [])
                        ->map(fn ($gap) => ($gap['kriteria'] ?? '') . ' (' . $this->desimal($gap['skor'] ?? 0) . ')')
                        ->implode(' | '),
                    $hasil->dihitung_pada?->format('d-m-Y H:i') ?? '-'
                );

                fputcsv($out, $isi, ';', '"', '');
            }

            fclose($out);
        }, $nama, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * @param  array<int, string>  $daftarProdi
     * @return array{prodi: string, kategori: string}
     */
    private function filter(Request $request, array $daftarProdi): array
    {
        $prodi = $request->string('prodi')->trim()->toString();
        $kategori = $request->string('kategori')->trim()->toString();
        $labelKategori = collect((array) config('dss.kategori', []))->pluck('label')->all();

        return [
            'prodi' => in_array($prodi, $daftarProdi, true) ? $prodi : '',
            'kategori' => in_array($kategori, $labelKategori, true) ? $kategori : '',
        ];
    }

    private function queryTerfilter(string $prodi, string $kategori): Builder
    {
        return DssHasil::query()
            ->with('user:id,fullname,prodi,fakultas')
            ->when($prodi !== '', fn ($q) => $q->whereHas('user', fn ($u) => $u->where('prodi', $prodi)))
            ->when($kategori !== '', fn ($q) => $q->where('kategori', $kategori))
            ->orderBy('peringkat')
            ->orderBy('user_id');
    }

    private function desimal(float|int|string|null $nilai): string
    {
        return number_format((float) $nilai, 2, ',', '');
    }

    /**
     * Mencegah CSV injection: sel teks yang diawali = + - @ dibuat menjadi teks biasa di Excel.
     */
    private function aman(string $nilai): string
    {
        return preg_match('/^[=+\-@\t\r]/', $nilai) === 1 ? "'" . $nilai : $nilai;
    }

    private function authorizeAdmin(): void
    {
        abort_unless(Auth::check() && Auth::user()->role === 'admin', 403);
    }
}