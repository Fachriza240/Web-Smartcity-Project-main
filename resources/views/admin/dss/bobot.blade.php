<x-layout.admin title="Pembobotan AHP" subtitle="Tentukan bobot kriteria penilaian potensi dosen dengan metode AHP.">

    <div class="content-header">
        <div>
            <h2 class="mb-1">Pembobotan AHP</h2>
            <p class="mb-0">Bandingkan tingkat kepentingan antar kriteria. Sistem menghitung bobot dan memeriksa konsistensi penilaian (Consistency Ratio).</p>
        </div>
        @if ($kriteria->count() >= 2)
            <div class="header-actions">
                <a href="{{ route('admin.dss.bobot.index', ['contoh' => 1]) }}" class="btn btn-light">
                    <i class="bi bi-magic me-2"></i>Isi dengan contoh
                </a>
            </div>
        @endif
    </div>

    @error('perbandingan')
        <div class="alert alert-danger" role="alert">{{ $message }}</div>
    @enderror

    @if ($errors->any() && ! $errors->has('perbandingan'))
        <div class="alert alert-danger" role="alert">Periksa kembali pilihan perbandingan dan catatan yang Anda isi.</div>
    @endif

    @if ($kriteria->count() < 2)
        <div class="alert alert-warning" role="alert">
            Data kriteria belum tersedia. Jalankan <code>php artisan db:seed --class=DssKriteriaSeeder</code>, lalu muat ulang halaman ini.
        </div>
    @else

        <div class="card-admin mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                    <div>
                        <h5 class="card-title mb-1">Bobot Saat Ini</h5>
                        <div class="small text-muted">
                            @if ($aktif)
                                Sumber: hasil AHP #{{ $aktif->id }}, disimpan {{ $aktif->created_at?->translatedFormat('d M Y H:i') }},
                                CR {{ number_format($aktif->cr, 4, ',', '.') }}.
                            @else
                                Sumber: bobot awal dari konfigurasi. Belum ada hasil AHP yang disimpan.
                            @endif
                        </div>
                    </div>
                    <form action="{{ route('admin.dss.bobot.reset') }}" method="POST"
                          data-confirm="Kembalikan bobot ke nilai awal? Hasil AHP yang aktif akan dinonaktifkan.">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>Kembalikan ke bobot awal
                        </button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 80px;">Kode</th>
                                <th>Kriteria</th>
                                <th style="width: 140px;">Bobot</th>
                                <th style="width: 140px;">Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kriteria as $item)
                                <tr>
                                    <td><strong>{{ $item->kode }}</strong></td>
                                    <td>
                                        {{ $item->nama }}
                                        @if ($item->deskripsi)
                                            <div class="small text-muted">{{ $item->deskripsi }}</div>
                                        @endif
                                    </td>
                                    <td>{{ number_format($item->bobot, 4, ',', '.') }}</td>
                                    <td>{{ number_format($item->bobot * 100, 2, ',', '.') }}%</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="2" class="text-end"><strong>Total</strong></td>
                                <td><strong>{{ number_format($kriteria->sum('bobot'), 4, ',', '.') }}</strong></td>
                                <td><strong>{{ number_format($kriteria->sum('bobot') * 100, 2, ',', '.') }}%</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card-admin mb-4">
            <div class="card-body">
                <h5 class="card-title mb-1">Perbandingan Berpasangan</h5>
                <p class="small text-muted mb-3">
                    Untuk setiap pasangan, pilih kriteria mana yang lebih penting dan seberapa besar.
                    Skala: 1 = sama penting, 3 = sedikit lebih penting, 5 = lebih penting, 7 = sangat lebih penting, 9 = mutlak lebih penting
                    (nilai 2, 4, 6, 8 adalah nilai antara). Hasil sebaiknya diisi oleh pengambil keputusan di CoE.
                </p>

                <form id="formAhp" method="POST" action="{{ route('admin.dss.bobot.hitung') }}">
                    @csrf

                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Kriteria A</th>
                                    <th style="min-width: 340px;">Tingkat kepentingan</th>
                                    <th>Kriteria B</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pasangan as [$a, $b])
                                    @php $terpilih = (string) old("perbandingan.{$a->kode}.{$b->kode}", $nilai[$a->kode][$b->kode] ?? '1'); @endphp
                                    <tr>
                                        <td><strong>{{ $a->kode }}</strong> {{ $a->nama }}</td>
                                        <td>
                                            <select name="perbandingan[{{ $a->kode }}][{{ $b->kode }}]"
                                                    class="form-select pasangan-select"
                                                    aria-label="Perbandingan {{ $a->kode }} dengan {{ $b->kode }}">
                                                @foreach ($opsi as $o)
                                                    <option value="{{ $o['nilai'] }}" @selected($terpilih === $o['nilai'])>
                                                        @if ($o['arah'] === 'tengah')
                                                            1 - Sama penting
                                                        @elseif ($o['arah'] === 'kiri')
                                                            {{ $a->kode }} lebih penting, skala {{ $o['angka'] }} ({{ $skalaLabel[$o['angka']] }})
                                                        @else
                                                            {{ $b->kode }} lebih penting, skala {{ $o['angka'] }} ({{ $skalaLabel[$o['angka']] }})
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><strong>{{ $b->kode }}</strong> {{ $b->nama }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row g-3 mt-1">
                        <div class="col-md-8">
                            <label for="catatan" class="form-label">Catatan (opsional)</label>
                            <input type="text" id="catatan" name="catatan" maxlength="255"
                                   class="form-control @error('catatan') is-invalid @enderror"
                                   value="{{ old('catatan', $catatan) }}"
                                   placeholder="Contoh: Penilaian Ketua CoE, Oktober 2026">
                            @error('catatan')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="d-flex flex-wrap align-items-center gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-calculator me-1" aria-hidden="true"></i> Hitung
                        </button>
                        <button type="submit" id="btnSimpan" class="btn btn-success"
                                formaction="{{ route('admin.dss.bobot.simpan') }}"
                                @disabled(! ($hasil && $hasil['konsisten']))>
                            <i class="bi bi-save me-1" aria-hidden="true"></i> Simpan sebagai bobot aktif
                        </button>
                        <span id="hintHitung" class="small text-muted" @if ($hasil) hidden @endif>
                            @if ($hasil && ! $hasil['konsisten'])
                                CR melebihi batas, ubah nilai perbandingan lalu hitung ulang.
                            @else
                                Klik Hitung terlebih dahulu. Tombol Simpan aktif jika CR tidak melebihi batas.
                            @endif
                        </span>
                    </div>
                </form>
            </div>
        </div>

        @if ($hasil)
            @php
                $n = $hasil['n'];
                $kodeKolom = $kriteria->pluck('kode')->values();
                $fmt = fn ($v, $d = 4) => number_format($v, $d, ',', '.');
            @endphp

            <div class="card-admin mb-4" id="hasil-ahp">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                        <h5 class="card-title mb-0">Hasil Perhitungan</h5>
                        <span class="badge {{ $hasil['konsisten'] ? 'bg-success' : 'bg-danger' }} fs-6">
                            {{ $hasil['konsisten'] ? 'Konsisten' : 'Tidak konsisten' }}
                        </span>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-6 col-md-2"><div class="small text-muted">λ maks</div><strong>{{ $fmt($hasil['lambda_max']) }}</strong></div>
                        <div class="col-6 col-md-2"><div class="small text-muted">CI</div><strong>{{ $fmt($hasil['ci']) }}</strong></div>
                        <div class="col-6 col-md-2"><div class="small text-muted">RI (n = {{ $n }})</div><strong>{{ $fmt($hasil['ri'], 2) }}</strong></div>
                        <div class="col-6 col-md-3"><div class="small text-muted">CR = CI / RI</div><strong>{{ $fmt($hasil['cr']) }}</strong></div>
                        <div class="col-12 col-md-3"><div class="small text-muted">Batas CR</div><strong>≤ {{ $fmt($hasil['ambang_cr'], 2) }}</strong></div>
                    </div>

                    <h6 class="mb-2">Bobot Prioritas</h6>
                    <div class="table-responsive mb-4">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th style="width: 80px;">Kode</th>
                                    <th>Kriteria</th>
                                    <th style="width: 140px;">Bobot</th>
                                    <th style="width: 140px;">Persentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kriteria->values() as $i => $item)
                                    <tr>
                                        <td><strong>{{ $item->kode }}</strong></td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $fmt($hasil['bobot'][$i]) }}</td>
                                        <td>{{ $fmt($hasil['bobot'][$i] * 100, 2) }}%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <h6 class="mb-2">Matriks Perbandingan Berpasangan</h6>
                    <div class="table-responsive mb-4">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th></th>
                                    @foreach ($kodeKolom as $kode)<th>{{ $kode }}</th>@endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kodeKolom as $i => $kode)
                                    <tr>
                                        <td><strong>{{ $kode }}</strong></td>
                                        @for ($j = 0; $j < $n; $j++)
                                            <td>{{ $fmt($hasil['matriks'][$i][$j], 3) }}</td>
                                        @endfor
                                    </tr>
                                @endforeach
                                <tr>
                                    <td><strong>Jumlah</strong></td>
                                    @for ($j = 0; $j < $n; $j++)
                                        <td><strong>{{ $fmt($hasil['jumlah_kolom'][$j], 3) }}</strong></td>
                                    @endfor
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h6 class="mb-2">Matriks Ternormalisasi</h6>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th></th>
                                    @foreach ($kodeKolom as $kode)<th>{{ $kode }}</th>@endforeach
                                    <th>Rata-rata (bobot)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kodeKolom as $i => $kode)
                                    <tr>
                                        <td><strong>{{ $kode }}</strong></td>
                                        @for ($j = 0; $j < $n; $j++)
                                            <td>{{ $fmt($hasil['ternormalisasi'][$i][$j]) }}</td>
                                        @endfor
                                        <td><strong>{{ $fmt($hasil['bobot'][$i]) }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        @if ($riwayat->isNotEmpty())
            <div class="card-admin">
                <div class="card-body">
                    <h5 class="card-title mb-3">Riwayat Pembobotan</h5>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>CR</th>
                                    <th>Dibuat oleh</th>
                                    <th>Catatan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($riwayat as $row)
                                    <tr>
                                        <td class="small">{{ $row->created_at?->translatedFormat('d M Y H:i') }}</td>
                                        <td class="small">{{ number_format($row->cr, 4, ',', '.') }}</td>
                                        <td class="small">{{ $row->pembuat?->fullname ?? '-' }}</td>
                                        <td class="small">{{ $row->catatan ?? '-' }}</td>
                                        <td>
                                            <span class="badge {{ $row->aktif ? 'bg-success' : 'bg-secondary' }}">{{ $row->aktif ? 'Aktif' : 'Arsip' }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <script>
            (function () {
                var form = document.getElementById('formAhp');
                if (!form) { return; }

                var simpan = document.getElementById('btnSimpan');
                var hint = document.getElementById('hintHitung');

                form.querySelectorAll('.pasangan-select').forEach(function (pilihan) {
                    pilihan.addEventListener('change', function () {
                        if (simpan) { simpan.disabled = true; }
                        if (hint) {
                            hint.hidden = false;
                            hint.textContent = 'Nilai berubah. Klik Hitung ulang sebelum menyimpan.';
                        }
                    });
                });

                if (simpan) {
                    simpan.addEventListener('click', function (event) {
                        if (!window.confirm('Simpan sebagai bobot aktif? Bobot kriteria saat ini akan diganti dengan hasil perhitungan ini.')) {
                            event.preventDefault();
                        }
                    });
                }
            })();
        </script>
    @endif

</x-layout.admin>