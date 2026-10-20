<x-halaman-dosen.konten-layout active="aktivitas" title="Aktivitas Saya">
    <div class="dsn-toolbar">
        <h2 class="dsn-toolbar__title">Aktivitas Riset dan Pengabdian Saya</h2>
        <a href="{{ route('dosen.aktivitas.create') }}" class="sc-btn sc-btn--primary">
            <i class="bi bi-plus-lg" aria-hidden="true"></i> Tambah Aktivitas
        </a>
    </div>

    <div class="dsn-note">
        <i class="bi bi-info-circle-fill" aria-hidden="true"></i>
        <span>
            Data aktivitas dipakai pada penilaian potensi dosen (DSS) CoE Smart City.
            Hanya aktivitas tahun <strong>{{ $tahunMulai }}</strong> sampai <strong>{{ date('Y') }}</strong> yang dihitung.
            Publikasi dan HKI diambil otomatis dari menu masing-masing, jadi tidak perlu diinput ulang di sini.
        </span>
    </div>

    <form method="GET" action="{{ route('dosen.aktivitas.index') }}" class="d-flex flex-wrap gap-2 mb-3">
        <select name="jenis" class="form-select" style="max-width: 260px;" aria-label="Filter jenis aktivitas">
            <option value="">Semua jenis</option>
            @foreach ($jenisList as $key => $label)
                <option value="{{ $key }}" @selected($jenisDipilih === $key)>{{ $label }}</option>
            @endforeach
        </select>
        <button type="submit" class="sc-btn sc-btn--ghost sc-btn--sm">
            <i class="bi bi-funnel" aria-hidden="true"></i> Terapkan
        </button>
        @if ($jenisDipilih !== '')
            <a href="{{ route('dosen.aktivitas.index') }}" class="sc-btn sc-btn--ghost sc-btn--sm">Reset</a>
        @endif
    </form>

    <div class="dsn-card">
        @if ($aktivitas->isEmpty())
            <div class="dsn-empty">
                <i class="bi bi-clipboard-data" aria-hidden="true"></i>
                <h3>Belum ada aktivitas</h3>
                <p>Klik <strong>Tambah Aktivitas</strong> untuk mencatat penelitian, abdimas, produk, kolaborasi, atau kepemimpinan program Anda.</p>
            </div>
        @else
            <table class="dsn-table">
                <thead>
                    <tr>
                        <th scope="col">Judul</th>
                        <th scope="col">Jenis</th>
                        <th scope="col">Detail</th>
                        <th scope="col">Tahun</th>
                        <th scope="col">Bidang Smart City</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($aktivitas as $item)
                        <tr>
                            <td data-label="Judul">
                                <strong class="dsn-table__title">{{ $item->judul }}</strong>
                                @if ($item->mitra)
                                    <span class="dsn-table__sub">Mitra: {{ $item->mitra }}</span>
                                @endif
                                @if ($item->tahun < $tahunMulai)
                                    <span class="dsn-table__sub">Di luar jendela penilaian, tidak dihitung.</span>
                                @endif
                            </td>
                            <td data-label="Jenis"><span class="dsn-pill">{{ $item->label_jenis }}</span></td>
                            <td data-label="Detail">{{ $item->ringkasan }}</td>
                            <td data-label="Tahun">{{ $item->tahun }}</td>
                            <td data-label="Bidang">{{ $item->label_bidang }}</td>
                            <td data-label="Aksi">
                                <div class="dsn-actions">
                                    <a href="{{ route('dosen.aktivitas.edit', $item) }}" class="dsn-icon-btn" title="Edit" aria-label="Edit aktivitas">
                                        <i class="bi bi-pencil" aria-hidden="true"></i>
                                    </a>
                                    <form action="{{ route('dosen.aktivitas.destroy', $item) }}" method="POST" data-confirm="Hapus aktivitas ini?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dsn-icon-btn dsn-icon-btn--danger" title="Hapus" aria-label="Hapus aktivitas">
                                            <i class="bi bi-trash" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="dsn-pagination">{{ $aktivitas->links() }}</div>
</x-halaman-dosen.konten-layout>