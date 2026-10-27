<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'dss.kategori' => [
                ['min' => 85, 'label' => 'Sangat Potensial', 'warna' => 'success', 'makna' => 'Cocok menjadi ketua program atau ketua riset unggulan CoE.'],
                ['min' => 70, 'label' => 'Potensial', 'warna' => 'primary', 'makna' => 'Cocok menjadi anggota inti atau PIC kegiatan tertentu.'],
                ['min' => 55, 'label' => 'Cukup Potensial', 'warna' => 'warning', 'makna' => 'Perlu penguatan publikasi, abdimas, atau kolaborasi.'],
                ['min' => 0, 'label' => 'Perlu Pengembangan', 'warna' => 'secondary', 'makna' => 'Perlu pendampingan dan integrasi ke program CoE.'],
            ],
            'dss.ambang_peran_utama' => 70,
            'dss.ambang_cluster' => 60,
            'dss.ambang_gap' => 50,
        ]);
    }
}