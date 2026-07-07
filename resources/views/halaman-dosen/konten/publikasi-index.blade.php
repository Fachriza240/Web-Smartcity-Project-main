<x-halaman-dosen.konten-layout active="publikasi" title="Publikasi Saya">

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
        <h3 style="font-size:17px;font-weight:700;color:#0f172a;margin:0;">
            Daftar Publikasi Saya
        </h3>
        <a href="{{ route('dosen.publikasi.create') }}"
           style="display:inline-flex;align-items:center;gap:6px;
                  background:#4c8dc9;color:#fff;border-radius:10px;
                  padding:9px 18px;font-size:13px;font-weight:700;text-decoration:none;
                  box-shadow:0 3px 10px rgba(76,141,201,.3);transition:background .2s;">
            <i class="bi bi-plus-lg"></i> Tambah Publikasi
        </a>
    </div>

    <div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;
                box-shadow:0 2px 8px rgba(0,0,0,.05);overflow:hidden;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#f8fafc;border-bottom:2px solid #e2e8f0;">
                    <th style="padding:12px 16px;font-size:11px;font-weight:700;
                               text-transform:uppercase;letter-spacing:.8px;color:#94a3b8;text-align:left;">
                        Judul
                    </th>
                    <th style="padding:12px 16px;font-size:11px;font-weight:700;
                               text-transform:uppercase;letter-spacing:.8px;color:#94a3b8;white-space:nowrap;">
                        Tahun
                    </th>
                    <th style="padding:12px 16px;font-size:11px;font-weight:700;
                               text-transform:uppercase;letter-spacing:.8px;color:#94a3b8;">
                        Kategori
                    </th>
                    <th style="padding:12px 16px;font-size:11px;font-weight:700;
                               text-transform:uppercase;letter-spacing:.8px;color:#94a3b8;">
                        Status
                    </th>
                    <th style="padding:12px 16px;font-size:11px;font-weight:700;
                               text-transform:uppercase;letter-spacing:.8px;color:#94a3b8;">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($publikasi as $p)
                    <tr style="border-bottom:1px solid #f1f5f9;transition:background .15s;"
                        onmouseover="this.style.background='#f8fafc'"
                        onmouseout="this.style.background='#fff'">
                        <td style="padding:14px 16px;">
                            <div style="font-size:14px;font-weight:600;color:#0f172a;">
                                {{ $p->judul }}
                            </div>
                            <div style="font-size:12px;color:#94a3b8;margin-top:2px;">
                                {{ $p->penulis }}
                                @if($p->doi) · <span style="font-family:monospace;">{{ $p->doi }}</span> @endif
                            </div>
                        </td>
                        <td style="padding:14px 16px;font-size:14px;color:#475569;white-space:nowrap;">
                            {{ $p->tahun }}
                        </td>
                        <td style="padding:14px 16px;">
                            <span style="background:#dbeafe;color:#1d4ed8;font-size:12px;
                                         font-weight:700;padding:3px 10px;border-radius:20px;">
                                {{ $p->kategori }}
                            </span>
                        </td>
                        <td style="padding:14px 16px;">
                            <span style="font-size:12px;font-weight:700;padding:3px 10px;border-radius:20px;
                                {{ $p->status === 'Publish'
                                    ? 'background:#dcfce7;color:#16a34a;'
                                    : 'background:#f1f5f9;color:#64748b;' }}">
                                {{ $p->status }}
                            </span>
                        </td>
                        <td style="padding:14px 16px;">
                            <div style="display:flex;gap:6px;align-items:center;">
                                @if($p->status === 'Publish')
                                    <a href="{{ route('publications.show', $p) }}" target="_blank"
                                       title="Lihat"
                                       style="width:32px;height:32px;border-radius:8px;border:1px solid #e2e8f0;
                                              background:#f8fafc;color:#64748b;display:flex;align-items:center;
                                              justify-content:center;text-decoration:none;font-size:14px;transition:all .2s;"
                                       onmouseover="this.style.background='#dbeafe';this.style.color='#1d4ed8'"
                                       onmouseout="this.style.background='#f8fafc';this.style.color='#64748b'">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                @endif
                                <a href="{{ route('dosen.publikasi.edit', $p) }}"
                                   title="Edit"
                                   style="width:32px;height:32px;border-radius:8px;border:1px solid #e2e8f0;
                                          background:#f8fafc;color:#64748b;display:flex;align-items:center;
                                          justify-content:center;text-decoration:none;font-size:14px;transition:all .2s;"
                                   onmouseover="this.style.background='#4c8dc9';this.style.color='#fff'"
                                   onmouseout="this.style.background='#f8fafc';this.style.color='#64748b'">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('dosen.publikasi.destroy', $p) }}" method="POST"
                                      onsubmit="return confirm('Hapus publikasi ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" title="Hapus"
                                            style="width:32px;height:32px;border-radius:8px;border:1px solid #e2e8f0;
                                                   background:#f8fafc;color:#64748b;display:flex;align-items:center;
                                                   justify-content:center;cursor:pointer;font-size:14px;transition:all .2s;"
                                            onmouseover="this.style.background='#fef2f2';this.style.color='#dc2626'"
                                            onmouseout="this.style.background='#f8fafc';this.style.color='#64748b'">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding:48px;text-align:center;color:#94a3b8;">
                            <i class="bi bi-journal-text" style="font-size:2.5rem;display:block;margin-bottom:12px;"></i>
                            <div style="font-size:15px;font-weight:600;color:#64748b;margin-bottom:6px;">
                                Belum ada publikasi
                            </div>
                            <div style="font-size:13px;">
                                Klik <strong>Tambah Publikasi</strong> untuk menambahkan publikasi pertama Anda.
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">{{ $publikasi->links() }}</div>

</x-halaman-dosen.konten-layout>
