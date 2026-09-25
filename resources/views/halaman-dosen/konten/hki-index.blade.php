<x-halaman-dosen.konten-layout active="hki" title="HKI Saya">
    <div class="dsn-toolbar">
        <h2 class="dsn-toolbar__title">Daftar HKI Saya</h2>
        <a href="{{ route('dosen.hki.create') }}" class="sc-btn sc-btn--primary">
            <i class="bi bi-plus-lg" aria-hidden="true"></i> Tambah HKI
        </a>
    </div>

    <div class="dsn-card">
        @if ($hkis->isEmpty())
            <div class="dsn-empty">
                <i class="bi bi-award" aria-hidden="true"></i>
                <h3>Belum ada HKI</h3>
                <p>Klik <strong>Tambah HKI</strong> untuk menambahkan data HKI pertama Anda.</p>
            </div>
        @else
            <table class="dsn-table">
                <thead>
                    <tr>
                        <th scope="col">Judul Sertifikat</th>
                        <th scope="col">Jenis</th>
                        <th scope="col">Tanggal Terbit</th>
                        <th scope="col">Status</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($hkis as $h)
                        <tr>
                            <td data-label="Judul">
                                <strong class="dsn-table__title">{{ $h->judul_sertifikat }}</strong>
                                <span class="dsn-table__sub">No. {{ $h->nomor_sertifikat }}</span>
                                @unless ($h->isOwnedBy(auth()->user()))
                                    <span class="dsn-coowner"><i class="bi bi-people" aria-hidden="true"></i> Anda tercantum sebagai pencipta. Diinput oleh {{ $h->recommender_name }}</span>
                                @endunless
                            </td>
                            <td data-label="Jenis"><span class="dsn-pill">{{ $h->jenis_sertifikat }}</span></td>
                            <td data-label="Terbit">{{ $h->tgl_terbit?->translatedFormat('d M Y') }}</td>
                            <td data-label="Status">
                                <span class="dsn-status {{ $h->status === 'Publish' ? 'is-publish' : '' }}">{{ $h->status }}</span>
                            </td>
                            <td data-label="Aksi">
                                <div class="dsn-actions">
                                    @if ($h->file_sertifikat)
                                        <a href="{{ asset('storage/'.$h->file_sertifikat) }}" target="_blank" rel="noopener" class="dsn-icon-btn" title="Lihat sertifikat" aria-label="Lihat sertifikat">
                                            <i class="bi bi-file-earmark-text" aria-hidden="true"></i>
                                        </a>
                                    @endif
                                    @if ($h->isOwnedBy(auth()->user()))
                                        <a href="{{ route('dosen.hki.edit', $h) }}" class="dsn-icon-btn" title="Edit" aria-label="Edit HKI">
                                            <i class="bi bi-pencil" aria-hidden="true"></i>
                                        </a>
                                        <form action="{{ route('dosen.hki.destroy', $h) }}" method="POST" data-confirm="Hapus HKI ini?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dsn-icon-btn dsn-icon-btn--danger" title="Hapus" aria-label="Hapus HKI">
                                                <i class="bi bi-trash" aria-hidden="true"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="dsn-readonly">Hanya lihat</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="dsn-pagination">{{ $hkis->links() }}</div>
</x-halaman-dosen.konten-layout>
