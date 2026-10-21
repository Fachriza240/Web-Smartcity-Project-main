@props(['user'])

@php
    $pemilik = auth()->check()
        && (int) auth()->id() === (int) $user->id
        && $user->role === 'dosen';

    $analisis = $pemilik ? app(\App\Services\DssLaporanService::class)->analisisPribadi($user) : null;
@endphp

@if ($pemilik)
    <div class="sc-panel" id="analisis-kompetensi">
        <div class="prf-panel-head">
            <h2 class="sc-panel__title">Analisis Kompetensi</h2>
            <a href="{{ route('dosen.aktivitas.index') }}" class="prf-link">Kelola Aktivitas <i class="bi bi-arrow-right-short" aria-hidden="true"></i></a>
        </div>

        @if (! $analisis)
            <p class="sc-panel__muted">
                Analisis kompetensi Anda belum tersedia. Hasil muncul setelah admin menjalankan perhitungan.
                Lengkapi publikasi, HKI, dan aktivitas Anda agar penilaian lebih akurat.
            </p>
        @else
            @php
                $hasil = $analisis['hasil'];
                $kategori = $analisis['kategori'];
                $kelasKategori = $kategori['warna'] === 'warning' ? 'bg-warning text-dark' : 'bg-' . $kategori['warna'];
            @endphp

            @if ($analisis['kedaluwarsa'])
                <div class="alert alert-warning small" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-1" aria-hidden="true"></i>
                    <strong>Hasil kedaluwarsa.</strong> Data Anda berubah setelah perhitungan terakhir
                    ({{ $hasil->dihitung_pada?->translatedFormat('d M Y H:i') }} WIB). Skor akan diperbarui saat admin menghitung ulang.
                </div>
            @endif

            <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
                <div>
                    <div class="small text-muted">Skor akhir</div>
                    <div class="fs-2 fw-bold lh-1">{{ number_format($hasil->skor_akhir, 2, ',', '.') }}<span class="fs-6 fw-normal text-muted"> / 100</span></div>
                </div>
                <div>
                    <span class="badge {{ $kelasKategori }} fs-6">{{ $kategori['label'] }}</span>
                    @if ($kategori['makna'])
                        <div class="small text-muted mt-1">{{ $kategori['makna'] }}</div>
                    @endif
                </div>
            </div>

            <dl class="row small mb-3">
                <dt class="col-sm-4">Kekuatan utama</dt>
                <dd class="col-sm-8">{{ $hasil->kekuatan_utama ?? '-' }}</dd>
                <dt class="col-sm-4">Cluster</dt>
                <dd class="col-sm-8">{{ $hasil->cluster ?? '-' }}</dd>
                <dt class="col-sm-4">Rekomendasi peran</dt>
                <dd class="col-sm-8">{{ $hasil->rekomendasi_peran ?? '-' }}</dd>
            </dl>

            <h3 class="h6 fw-bold">Rincian per kriteria</h3>
            <div class="table-responsive mb-3">
                <table class="table table-sm align-middle mb-1">
                    <thead>
                        <tr>
                            <th>Kriteria</th>
                            <th class="text-end">Poin</th>
                            <th style="min-width: 130px;">Nilai (0-100)</th>
                            <th class="text-end">Bobot</th>
                            <th class="text-end">Kontribusi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($analisis['baris'] as $baris)
                            <tr>
                                <td>{{ $baris['nama'] }}</td>
                                <td class="text-end">{{ number_format($baris['poin'], 2, ',', '.') }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height: 6px;" role="progressbar"
                                             aria-label="Nilai {{ $baris['nama'] }}" aria-valuenow="{{ round($baris['normal']) }}" aria-valuemin="0" aria-valuemax="100">
                                            <div class="progress-bar" style="width: {{ max(0, min(100, $baris['normal'])) }}%"></div>
                                        </div>
                                        <span class="small">{{ number_format($baris['normal'], 1, ',', '.') }}</span>
                                    </div>
                                </td>
                                <td class="text-end">{{ number_format($baris['bobot'] * 100, 0, ',', '.') }}%</td>
                                <td class="text-end fw-semibold">{{ number_format($baris['kontribusi'], 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="4" class="text-end fw-bold">Skor akhir</td>
                            <td class="text-end fw-bold">{{ number_format($hasil->skor_akhir, 2, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="small text-muted">
                    Nilai dibandingkan dengan dosen lain yang dinilai (skala relatif), sehingga dapat bergeser saat data dosen lain berubah.
                </div>
            </div>

            <h3 class="h6 fw-bold">Peta kompetensi Smart City</h3>
            @if (! empty($analisis['dimensi']))
                <ul class="sc-chips mb-3">
                    @foreach ($analisis['dimensi'] as $label)
                        <li>{{ $label }}</li>
                    @endforeach
                </ul>
            @else
                <p class="sc-panel__muted mb-3">Belum ada kegiatan atau dokumen yang cocok dengan dimensi Smart City.</p>
            @endif

            <h3 class="h6 fw-bold">Gap analysis</h3>
            @if (! empty($hasil->gap))
                <ul class="list-unstyled small mb-2">
                    @foreach ($hasil->gap as $gap)
                        <li class="mb-2">
                            <strong>{{ $gap['kriteria'] }}</strong> ({{ number_format($gap['skor'], 1, ',', '.') }})
                            <div class="text-muted">{{ $gap['saran'] }}</div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="sc-panel__muted mb-2">Semua kriteria sudah memadai. Pertahankan kinerja Anda.</p>
            @endif

            <div class="small text-muted">
                Dihitung pada {{ $hasil->dihitung_pada?->translatedFormat('d M Y H:i') }} WIB dari data berstatus Publish
                dalam {{ config('dss.jendela_tahun', 5) }} tahun terakhir.
            </div>
        @endif
    </div>
@endif