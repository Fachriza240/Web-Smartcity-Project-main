<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class DssConfigTest extends TestCase
{
    private array $konfigurasi;

    protected function setUp(): void
    {
        $this->konfigurasi = require __DIR__ . '/../../config/dss.php';
    }

    public function test_ambang_kategori_menurun_dan_diakhiri_nol(): void
    {
        $kategori = $this->konfigurasi['kategori'];
        $this->assertTrue(count($kategori) >= 2, 'minimal dua kategori');

        for ($i = 1; $i < count($kategori); $i++) {
            $this->assertTrue(
                $kategori[$i - 1]['min'] > $kategori[$i]['min'],
                'ambang kategori harus menurun dari atas ke bawah'
            );
        }

        $this->assertSame(0, $kategori[count($kategori) - 1]['min']);
    }

    public function test_setiap_kategori_lengkap_dan_labelnya_unik(): void
    {
        $label = [];

        foreach ($this->konfigurasi['kategori'] as $item) {
            foreach (['min', 'label', 'warna', 'makna'] as $kunci) {
                $this->assertTrue(isset($item[$kunci]), "kategori harus memiliki '{$kunci}'");
            }

            $this->assertTrue(in_array($item['warna'], ['success', 'primary', 'warning', 'secondary', 'danger', 'info', 'dark'], true));
            $this->assertTrue($item['min'] >= 0 && $item['min'] <= 100);
            $label[] = $item['label'];
        }

        $this->assertCount(count($label), array_unique($label));
    }

    public function test_ambang_lain_berada_di_rentang_nol_sampai_seratus(): void
    {
        foreach (['ambang_gap', 'ambang_cluster', 'ambang_peran_utama'] as $kunci) {
            $this->assertTrue($this->konfigurasi[$kunci] >= 0 && $this->konfigurasi[$kunci] <= 100, "{$kunci} di luar 0 sampai 100");
        }
    }

    public function test_peran_utama_dapat_dicapai_oleh_kategori_tertinggi(): void
    {
        $this->assertTrue(
            $this->konfigurasi['ambang_peran_utama'] <= $this->konfigurasi['kategori'][0]['min'],
            'ambang_peran_utama tidak boleh melebihi ambang kategori tertinggi'
        );
    }

    public function test_jumlah_bobot_awal_adalah_satu(): void
    {
        $total = array_sum(array_column($this->konfigurasi['kriteria'], 'bobot_awal'));

        $this->assertEqualsWithDelta(1.0, $total, 1e-9);
        $this->assertSame(['C1', 'C2', 'C3', 'C4', 'C5'], array_keys($this->konfigurasi['kriteria']));
    }

    public function test_rubrik_mencakup_seluruh_kategori_publikasi_dan_jenis_hki(): void
    {
        $rubrik = $this->konfigurasi['rubrik'];

        foreach (['Jurnal', 'Conference', 'Buku', 'Paten', 'Laporan Penelitian'] as $kategori) {
            $this->assertTrue(isset($rubrik['publikasi'][$kategori]), "rubrik publikasi belum memuat {$kategori}");
        }

        foreach (['Paten', 'Paten Sederhana', 'Hak Cipta', 'Desain Industri', 'Desain Tata Letak Sirkuit Terpadu', 'Merek', 'Rahasia Dagang', 'Varietas Tanaman'] as $jenis) {
            $this->assertTrue(isset($rubrik['hki'][$jenis]), "rubrik HKI belum memuat {$jenis}");
        }
    }

    public function test_rubrik_dan_label_sub_jenis_konsisten(): void
    {
        $rubrik = $this->konfigurasi['rubrik'];
        $label = $this->konfigurasi['label_sub_jenis'];

        foreach (['produk', 'kolaborasi', 'kepemimpinan'] as $jenis) {
            $kunciRubrik = array_keys($rubrik[$jenis]);
            $kunciLabel = array_keys($label[$jenis]);
            sort($kunciRubrik);
            sort($kunciLabel);

            $this->assertSame($kunciRubrik, $kunciLabel, "sub jenis {$jenis} pada rubrik dan label harus sama");
        }

        foreach (['penelitian', 'abdimas'] as $jenis) {
            $this->assertSame(['institusi', 'nasional', 'internasional'], array_keys($rubrik[$jenis]));
        }

        $this->assertTrue(isset($rubrik['peran']['ketua'], $rubrik['peran']['anggota']));
    }

    public function test_enam_dimensi_smart_city_memiliki_kata_kunci(): void
    {
        $dimensi = $this->konfigurasi['bidang_smart_city'];

        $this->assertCount(6, $dimensi);

        foreach ($dimensi as $kunci => $item) {
            $this->assertTrue($item['label'] !== '', "label dimensi {$kunci} kosong");
            $this->assertTrue(count($item['kata_kunci']) > 0, "dimensi {$kunci} belum memiliki kata kunci");
        }
    }

    public function test_tabel_random_index_lengkap(): void
    {
        for ($n = 1; $n <= 10; $n++) {
            $this->assertTrue(isset($this->konfigurasi['ri'][$n]), "RI untuk n = {$n} belum ada");
        }

        $this->assertEqualsWithDelta(1.12, $this->konfigurasi['ri'][5], 1e-9);
    }
}