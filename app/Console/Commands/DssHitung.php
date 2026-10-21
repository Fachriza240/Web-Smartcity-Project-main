<?php

namespace App\Console\Commands;

use App\Models\DssHasil;
use App\Services\DssHitungService;
use Illuminate\Console\Command;
use RuntimeException;

class DssHitung extends Command
{
    protected $signature = 'dss:hitung {--tampilkan : Tampilkan tabel peringkat setelah perhitungan}';

    protected $description = 'Menghitung potensi dosen dengan AHP dan SAW lalu menyimpan hasilnya.';

    public function handle(DssHitungService $service): int
    {
        try {
            $ringkasan = $service->hitungSemua();
        } catch (RuntimeException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->info("Selesai. {$ringkasan['jumlah']} dosen dinilai pada {$ringkasan['dihitung_pada']->format('d-m-Y H:i:s')}.");

        if ($this->option('tampilkan')) {
            $baris = DssHasil::with('user:id,fullname')
                ->orderBy('peringkat')
                ->orderBy('user_id')
                ->get()
                ->map(fn ($hasil) => [
                    $hasil->peringkat,
                    $hasil->user?->fullname ?? '-',
                    number_format($hasil->skor_akhir, 2, ',', '.'),
                    $hasil->kategori,
                    $hasil->rekomendasi_peran,
                ])->all();

            $this->table(['Peringkat', 'Dosen', 'Skor', 'Kategori', 'Rekomendasi peran'], $baris);
        }

        return self::SUCCESS;
    }
}