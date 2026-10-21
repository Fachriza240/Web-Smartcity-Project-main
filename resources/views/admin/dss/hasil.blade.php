<x-layout.admin title="Peringkat Dosen" subtitle="Hasil penilaian potensi dosen dengan metode AHP dan SAW.">

    <div class="content-header">
        <div>
            <h2 class="mb-1">Peringkat Potensi Dosen</h2>
            <p class="mb-0">Ranking, kategori potensi, kekuatan utama, dan rekomendasi peran dosen berdasarkan data penelitian dan abdimas.</p>
        </div>
        <div class="header-actions d-flex gap-2 flex-wrap">
            @if ($ada)
                <a href="{{ route('admin.dss.hasil.ekspor', array_filter(['prodi' => $prodi, 'kategori' => $kategori])) }}" class="btn btn-light">
                    <i class="bi bi-filetype-csv me-2" aria-hidden="true"></i>Ekspor CSV
                </a>
            @endif
            <form action="{{ route('admin.dss.hasil.hitung') }}" method="POST"
                  data-confirm="Hitung ulang potensi seluruh dosen? Hasil perhitungan sebelumnya akan diganti.">
                @csrf
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-calculator me-2" aria-hidden="true"></i>Hitung Ulang
                </button>
            </form>
        </div>
    </div>

    @include('admin.dss._nav')
    @include('admin.dss._status')

    @if ($ada)
        <div class="dss-summary">
            <div class="dss-box">
                <div class="dss-box__label">Dosen dinilai</div>
                <div class="dss-box__value">{{ $kalibrasi['jumlah'] }}</div>
            </div>
            <div class="dss-box">
                <div class="dss-box__label">Skor rata-rata</div>
                <div class="dss-box__value">{{ number_format($kalibrasi['rata'], 2, ',', '.') }}</div>
                <div class="dss-box__sub">Median {{ number_format($kalibrasi['median'], 2, ',', '.') }}</div>
            </div>
            <div class="dss-box">
                <div class="dss-box__label">Skor tertinggi</div>
                <div class="dss-box__value">{{ number_format($kalibrasi['tertinggi'], 2, ',', '.') }}</div>
                <div class="dss-box__sub">{{ $teratas?->user?->fullname ?? '-' }}</div>
            </div>
            <div class="dss-box">
                <div class="dss-box__label">Skor terendah</div>
                <div class="dss-box__value">{{ number_format($kalibrasi['terendah'], 2, ',', '.') }}</div>
            </div>
        </div>

        <details class="dss-note mb-4">
            <summary>Cara membaca skor: skala relatif antar dosen</summary>
            <div class="mt-3">
                <p class="mb-2">
                    Poin mentah tiap kriteria dinormalisasi terhadap dosen dengan poin tertinggi pada kriteria itu (poin tertinggi = 100).
                    Skor akhir adalah jumlah nilai ternormalisasi dikalikan bobot AHP. Karena itu skor dan kategori menunjukkan
                    <strong>posisi dosen di antara dosen yang dinilai</strong>, bukan standar kompetensi mutlak.
                    Jika data dosen berubah atau ada dosen baru, skor dosen lain ikut bergeser setelah Hitung Ulang.
                </p>

                <div class="table-responsive mb-3">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Kriteria</th>
                                <th>Bobot</th>
                                <th>Poin acuan (= 100)</th>
                                <th>Rata-rata poin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kalibrasi['kriteria'] as $kode => $item)
                                <tr>
                                    <td><strong>{{ $kode }}</strong> {{ $item['nama'] }}</td>
                                    <td>{{ number_format($item['bobot'] * 100, 2, ',', '.') }}%</td>
                                    <td>{{ number_format($item['maks'], 2, ',', '.') }}</td>
                                    <td>{{ number_format($item['rata'], 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mb-1"><strong>Sebaran kategori</strong> (ambang tetap dari konfigurasi)</div>
                <div>
                    @foreach ($kalibrasi['sebaran'] as $item)
                        <span class="me-3 d-inline-block">
                            @include('admin.dss._kategori', ['label' => $item['label'], 'warna' => $item['warna']])
                            skor &ge; {{ number_format($item['min'], 0, ',', '.') }}: <strong>{{ $item['jumlah'] }}</strong> dosen
                        </span>
                    @endforeach
                </div>
            </div>
        </details>
    @endif

    <div class="card-admin">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.dss.hasil.index') }}" class="row g-3 mb-4">
                <div class="col-12 col-md-4">
                    <select name="prodi" class="form-select" aria-label="Filter program studi">
                        <option value="">Semua Program Studi</option>
                        @foreach ($daftarProdi as $item)
                            <option value="{{ $item }}" @selected($prodi === $item)>{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-4">
                    <select name="kategori" class="form-select" aria-label="Filter kategori potensi">
                        <option value="">Semua Kategori</option>
                        @foreach ($daftarKategori as $item)
                            <option value="{{ $item }}" @selected($kategori === $item)>{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel me-1" aria-hidden="true"></i>Terapkan</button>
                    <a href="{{ route('admin.dss.hasil.index') }}" class="btn btn-light" aria-label="Reset filter"><i class="bi bi-arrow-clockwise" aria-hidden="true"></i></a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 70px;">Peringkat</th>
                            <th>Dosen</th>
                            <th style="min-width: 150px;">Skor Akhir</th>
                            <th>Kategori</th>
                            <th>Kekuatan Utama</th>
                            <th>Rekomendasi Peran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($hasil as $item)
                            <tr>
                                <td><strong>#{{ $item->peringkat }}</strong></td>
                                <td>
                                    <strong>{{ $item->user?->fullname ?? '-' }}</strong>
                                    @if ($item->user?->prodi)
                                        <div class="small text-muted">{{ $item->user->prodi }}</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="dss-score">{{ number_format($item->skor_akhir, 2, ',', '.') }}</div>
                                    <div class="dss-bar" role="img" aria-label="Skor {{ number_format($item->skor_akhir, 2, ',', '.') }} dari 100">
                                        <span style="width: {{ max(0, min(100, $item->skor_akhir)) }}%"></span>
                                    </div>
                                </td>
                                <td>@include('admin.dss._kategori', ['label' => $item->kategori, 'warna' => $warna[$item->kategori] ?? 'secondary'])</td>
                                <td class="small">
                                    {{ $item->kekuatan_utama ?? '-' }}
                                    @if ($item->cluster)
                                        <div class="text-muted">{{ $item->cluster }}</div>
                                    @endif
                                </td>
                                <td class="small">{{ $item->rekomendasi_peran ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.dss.hasil.show', $item->user_id) }}" class="btn btn-sm btn-light">
                                        <i class="bi bi-eye me-1" aria-hidden="true"></i>Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    @if ($ada)
                                        Tidak ada dosen yang cocok dengan filter.
                                    @else
                                        Belum ada hasil perhitungan. Klik Hitung Ulang untuk memulai.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $hasil->links() }}</div>
        </div>
    </div>

</x-layout.admin>