@if (! $terakhir)
    <div class="alert alert-info" role="alert">
        Belum ada hasil perhitungan. Klik <strong>Hitung Ulang</strong> di halaman Peringkat Dosen untuk menjalankan AHP dan SAW.
    </div>
@else
    <div class="d-flex align-items-center flex-wrap gap-2 mb-3 small dss-muted">
        <i class="bi bi-clock-history" aria-hidden="true"></i>
        Perhitungan terakhir: <strong>{{ $terakhir->translatedFormat('d M Y H:i') }} WIB</strong>
    </div>
    @if ($kedaluwarsa)
        <div class="alert alert-warning" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-1" aria-hidden="true"></i>
            <strong>Hasil kedaluwarsa.</strong> Ada data publikasi, HKI, aktivitas, bobot, atau daftar dosen yang berubah setelah perhitungan terakhir.
            Klik <strong>Hitung Ulang</strong> agar skor dan peringkat sesuai data terbaru.
        </div>
    @endif
@endif