<x-halaman-dosen.konten-layout active="hki" title="HKI Saya">

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
        <h3 style="font-size:17px;font-weight:700;color:#0f172a;margin:0;">
            Daftar HKI Saya
        </h3>
        <a href="{{ route('dosen.hki.create') }}"
           style="display:inline-flex;align-items:center;gap:6px;
                  background:#4c8dc9;color:#fff;border-radius:10px;
                  padding:9px 18px;font-size:13px;font-weight:700;text-decoration:none;
                  box-shadow:0 3px 10px rgba(76,141,201,.3);">
            <i class="bi bi-plus-lg"></i> Tambah HKI
        </a>
    </div>

    <div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;
                box-shadow:0 2px 8px rgba(0,0,0,.05);overflow:hidden;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#f8fafc;border-bottom:2px solid #e2e8f0;">
                    <th style="padding:12px 16px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#94a3b8;text-align:left;">Judul Sertifikat</th>
                    <th style="padding:12px 16px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#94a3b8;">Jenis</th>
                    <th style="padding:12px 16px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#94a3b8;white-space:nowrap;">Tgl Terbit</th>
                    <th style="padding:12px 16px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#94a3b8;">Status</th>
                    <th style="padding:12px 16px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#94a3b8;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($hkis as $h)
                    <tr style="border-bottom:1px solid #f1f5f9;"
                        onmouseover="this.style.background='#f8fafc'"
                        onmouseout="this.style.background='#fff'">
                        <td style="padding:14px 16px;">
                            <div style="font-size:14px;font-weight:600;color:#0f172a;">
                                {{ $h->judul_sertifikat }}
                            </div>
                            <div style="font-size:12px;color:#94a3b8;margin-top:2px;font-family:monospace;">
                                {{ $h->nomor_sertifikat }}
                            </div>
                        </td>
                        <td style="padding:14px 16px;">
                            <span style="background:#fef9c3;color:#ca8a04;font-size:12px;font-weight:700;padding:3px 10px;border-radius:20px;">
                                {{ $h->jenis_sertifikat }}
                            </span>
                        </td>
                        <td style="padding:14px 16px;font-size:13px;color:#475569;white-space:nowrap;">
                            {{ $h->tgl_terbit?->format('d M Y') }}
                        </td>
                        <td style="padding:14px 16px;">
                            <span style="font-size:12px;font-weight:700;padding:3px 10px;border-radius:20px;
                                {{ $h->status === 'Publish' ? 'background:#dcfce7;color:#16a34a;' : 'background:#f1f5f9;color:#64748b;' }}">
                                {{ $h->status }}
                            </span>
                        </td>
                        <td style="padding:14px 16px;">
                            <div style="display:flex;gap:6px;align-items:center;">
                                @if($h->file_sertifikat)
                                    <a href="{{ asset('storage/'.$h->file_sertifikat) }}" target="_blank"
                                       title="Unduh" style="width:32px;height:32px;border-radius:8px;border:1px solid #e2e8f0;background:#f8fafc;color:#64748b;display:flex;align-items:center;justify-content:center;text-decoration:none;font-size:14px;"
                                       onmouseover="this.style.background='#dbeafe';this.style.color='#1d4ed8'"
                                       onmouseout="this.style.background='#f8fafc';this.style.color='#64748b'">
                                        <i class="bi bi-file-earmark-text"></i>
                                    </a>
                                @endif
                                <a href="{{ route('dosen.hki.edit', $h) }}" title="Edit"
                                   style="width:32px;height:32px;border-radius:8px;border:1px solid #e2e8f0;background:#f8fafc;color:#64748b;display:flex;align-items:center;justify-content:center;text-decoration:none;font-size:14px;"
                                   onmouseover="this.style.background='#4c8dc9';this.style.color='#fff'"
                                   onmouseout="this.style.background='#f8fafc';this.style.color='#64748b'">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('dosen.hki.destroy', $h) }}" method="POST"
                                      onsubmit="return confirm('Hapus HKI ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" title="Hapus"
                                            style="width:32px;height:32px;border-radius:8px;border:1px solid #e2e8f0;background:#f8fafc;color:#64748b;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:14px;"
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
                            <i class="bi bi-award" style="font-size:2.5rem;display:block;margin-bottom:12px;"></i>
                            <div style="font-size:15px;font-weight:600;color:#64748b;margin-bottom:6px;">Belum ada HKI</div>
                            <div style="font-size:13px;">Klik <strong>Tambah HKI</strong> untuk menambahkan data HKI pertama Anda.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">{{ $hkis->links() }}</div>

</x-halaman-dosen.konten-layout>
