<x-halaman-dosen.konten-layout active="publikasi" title="Publikasi Saya">
    <div class="dsn-toolbar">
        <h2 class="dsn-toolbar__title">Daftar Publikasi Saya</h2>
        <a href="{{ route('dosen.publikasi.create') }}" class="sc-btn sc-btn--primary">
            <i class="bi bi-plus-lg" aria-hidden="true"></i> Tambah Publikasi
        </a>
    </div>

    <div class="dsn-card">
        @if ($publikasi->isEmpty())
            <div class="dsn-empty">
                <i class="bi bi-journal-text" aria-hidden="true"></i>
                <h3>Belum ada publikasi</h3>
                <p>Klik <strong>Tambah Publikasi</strong> untuk menambahkan publikasi pertama Anda.</p>
            </div>
        @else
            <table class="dsn-table">
                <thead>
                    <tr>
                        <th scope="col">Judul</th>
                        <th scope="col">Tahun</th>
                        <th scope="col">Kategori</th>
                        <th scope="col">Status</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($publikasi as $p)
                        <tr>
                            <td data-label="Judul">
                                <strong class="dsn-table__title">{{ $p->judul }}</strong>
                                <span class="dsn-table__sub">{{ $p->penulis }}{{ $p->doi ? ', DOI '.$p->doi : '' }}</span>
                                @unless ($p->isOwnedBy(auth()->user()))
                                    <span class="dsn-coowner"><i class="bi bi-people" aria-hidden="true"></i> Anda tercantum sebagai penulis. Diinput oleh {{ $p->recommender_name }}</span>
                                @endunless
                            </td>
                            <td data-label="Tahun">{{ $p->tahun }}</td>
                            <td data-label="Kategori"><span class="dsn-pill">{{ $p->kategori }}</span></td>
                            <td data-label="Status">
                                <span class="dsn-status {{ $p->status === 'Publish' ? 'is-publish' : '' }}">{{ $p->status }}</span>
                            </td>
                            <td data-label="Aksi">
                                <div class="dsn-actions">
                                    @if ($p->status === 'Publish')
                                        <a href="{{ route('publications.show', $p) }}" target="_blank" rel="noopener" class="dsn-icon-btn" title="Lihat" aria-label="Lihat publikasi">
                                            <i class="bi bi-eye" aria-hidden="true"></i>
                                        </a>
                                    @endif
                                    @if ($p->isOwnedBy(auth()->user()))
                                        <a href="{{ route('dosen.publikasi.edit', $p) }}" class="dsn-icon-btn" title="Edit" aria-label="Edit publikasi">
                                            <i class="bi bi-pencil" aria-hidden="true"></i>
                                        </a>
                                        <form action="{{ route('dosen.publikasi.destroy', $p) }}" method="POST" data-confirm="Hapus publikasi ini?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dsn-icon-btn dsn-icon-btn--danger" title="Hapus" aria-label="Hapus publikasi">
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

    <div class="dsn-pagination">{{ $publikasi->links() }}</div>
</x-halaman-dosen.konten-layout>
