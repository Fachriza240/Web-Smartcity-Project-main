<x-layout.admin title="Detail Potensi Dosen" subtitle="Rincian skor, grafik radar, dan gap analysis.">

    <div class="content-header">
        <div>
            <h2 class="mb-1">{{ $dosen->fullname }}</h2>
            <p class="mb-0">
                {{ $dosen->prodi ?: 'Program studi belum diisi' }}@if ($dosen->fakultas), {{ $dosen->fakultas }}@endif
            </p>
        </div>
        <div class="header-actions d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.aktivitas-dosen.index', ['user_id' => $dosen->id]) }}" class="btn btn-light">
                <i class="bi bi-clipboard-data me-2" aria-hidden="true"></i>Aktivitas Dosen
            </a>
            <a href="{{ route('admin.dss.hasil.index') }}" class="btn btn-primary">
                <i class="bi bi-arrow-left me-2" aria-hidden="true"></i>Kembali ke Peringkat
            </a>
        </div>
    </div>

    @include('admin.dss._nav')
    @include('admin.dss._status')

    <div class="dss-summary">
        <div class="dss-box">
            <div class="dss-box__label">Skor akhir</div>
            <div class="dss-box__value">{{ number_format($hasil->skor_akhir, 2, ',', '.') }}</div>
            <div class="dss-box__sub">dari 100 (skala relatif)</div>
        </div>
        <div class="dss-box">
            <div class="dss-box__label">Peringkat</div>
            <div class="dss-box__value">#{{ $hasil->peringkat }}</div>
            <div class="dss-box__sub">dari {{ $totalDosen }} dosen</div>
        </div>
        <div class="dss-box">
            <div class="dss-box__label">Kategori</div>
            <div class="mt-1 mb-1">@include('admin.dss._kategori', ['label' => $kategori['label'], 'warna' => $kategori['warna']])</div>
            <div class="dss-box__sub">{{ $kategori['makna'] }}</div>
        </div>
        <div class="dss-box">
            <div class="dss-box__label">Cluster</div>
            <div class="dss-box__value" style="font-size: 18px;">{{ $hasil->cluster ?? '-' }}</div>
            <div class="dss-box__sub">Kekuatan utama: {{ $hasil->kekuatan_utama ?? '-' }}</div>
        </div>
    </div>

    <div class="card-admin mb-4">
        <div class="card-body">
            <h5 class="card-title mb-1">Rekomendasi Peran</h5>
            <p class="mb-0">{{ $hasil->rekomendasi_peran ?? '-' }}</p>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-lg-7">
            <div class="card-admin h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">Rincian Skor per Kriteria</h5>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Kriteria</th>
                                    <th>Poin mentah</th>
                                    <th>Poin acuan</th>
                                    <th>Nilai (0-100)</th>
                                    <th>Bobot</th>
                                    <th>Kontribusi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($baris as $item)
                                    <tr>
                                        <td><strong>{{ $item['kode'] }}</strong> {{ $item['nama'] }}</td>
                                        <td>{{ number_format($item['poin'], 2, ',', '.') }}</td>
                                        <td>{{ number_format($item['maks'] ?? 0, 2, ',', '.') }}</td>
                                        <td>{{ number_format($item['normal'], 2, ',', '.') }}</td>
                                        <td>{{ number_format($item['bobot'] * 100, 2, ',', '.') }}%</td>
                                        <td><strong>{{ number_format($item['kontribusi'], 2, ',', '.') }}</strong></td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="5" class="text-end"><strong>Skor akhir</strong></td>
                                    <td><strong>{{ number_format($hasil->skor_akhir, 2, ',', '.') }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="small text-muted mt-2">
                        Nilai = poin mentah / poin acuan x 100. Kontribusi = nilai x bobot. Poin acuan adalah poin tertinggi di antara seluruh dosen pada kriteria tersebut.
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-5">
            <div class="card-admin h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">Grafik Radar</h5>
                    <div class="dss-chart">
                        <canvas id="dssRadar" role="img" aria-label="Grafik radar nilai lima kriteria {{ $dosen->fullname }}"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-lg-6">
            <div class="card-admin h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">Gap Analysis</h5>
                    @if (! empty($hasil->gap))
                        <p class="small text-muted">Kriteria dengan nilai di bawah {{ config('dss.ambang_gap', 50) }}, dari yang paling rendah.</p>
                        <ul class="list-unstyled mb-0">
                            @foreach ($hasil->gap as $gap)
                                <li class="mb-3">
                                    <div class="d-flex justify-content-between gap-2">
                                        <strong>{{ $gap['kode'] }} {{ $gap['kriteria'] }}</strong>
                                        <span class="dss-score">{{ number_format($gap['skor'], 2, ',', '.') }}</span>
                                    </div>
                                    <div class="small text-muted">{{ $gap['saran'] }}</div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="mb-0 text-muted">Tidak ada kriteria di bawah ambang. Semua kriteria sudah memadai.</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="card-admin h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">Kompetensi Smart City</h5>
                    <div class="mb-3">
                        @php $adaDimensi = false; @endphp
                        @foreach ($dimensi as $kunci => $info)
                            @if (($info['jumlah'] ?? 0) > 0 || ! empty($info['dari_profil']))
                                @php $adaDimensi = true; @endphp
                                <span class="dss-chip">
                                    {{ $info['label'] ?? $kunci }}
                                    @if (($info['jumlah'] ?? 0) > 0)
                                        ({{ $info['jumlah'] }})
                                    @else
                                        (profil)
                                    @endif
                                </span>
                            @endif
                        @endforeach
                        @unless ($adaDimensi)
                            <span class="text-muted">Belum ada dokumen yang cocok dengan dimensi Smart City.</span>
                        @endunless
                    </div>
                    @if (! empty($hasil->kompetensi['dominan_label']))
                        <div class="small">Dimensi dominan: <strong>{{ $hasil->kompetensi['dominan_label'] }}</strong></div>
                    @endif
                    <div class="small text-muted mt-1">Angka dalam kurung adalah jumlah dokumen yang cocok. Label (profil) berasal dari bidang penelitian di profil dosen.</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-admin mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Rincian Poin</h5>
            <div class="row g-3">
                @foreach ($rincian as $kode => $daftar)
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="dss-box h-100">
                            <div class="dss-box__label">{{ $kode }} {{ collect($baris)->firstWhere('kode', $kode)['nama'] ?? '' }}</div>
                            @foreach ($daftar as $item)
                                <div class="d-flex justify-content-between gap-2 small mt-1">
                                    <span>{{ $item['label'] }}</span>
                                    <strong>{{ number_format($item['nilai'], 2, ',', '.') }}</strong>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="small text-muted mt-3">Dihitung dari data berstatus Publish dalam jendela {{ config('dss.jendela_tahun', 5) }} tahun terakhir.</div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script>
        (function () {
            if (!window.Chart) { return; }
            var data = {{ \Illuminate\Support\Js::from($radar) }};
            var css = getComputedStyle(document.documentElement);
            var rgb = (css.getPropertyValue('--adm-primary-rgb') || '76, 141, 201').trim();
            var teks = (css.getPropertyValue('--adm-text-2') || '#475569').trim();
            var garis = (css.getPropertyValue('--adm-border') || '#e2e8f0').trim();
            new Chart(document.getElementById('dssRadar'), {
                type: 'radar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Nilai (0-100)',
                        data: data.nilai,
                        backgroundColor: 'rgba(' + rgb + ', 0.2)',
                        borderColor: 'rgb(' + rgb + ')',
                        pointBackgroundColor: 'rgb(' + rgb + ')',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        r: {
                            min: 0,
                            max: 100,
                            ticks: { stepSize: 25, color: teks, backdropColor: 'transparent' },
                            grid: { color: garis },
                            angleLines: { color: garis },
                            pointLabels: { color: teks, font: { size: 11 } }
                        }
                    }
                }
            });
        })();
    </script>

</x-layout.admin>