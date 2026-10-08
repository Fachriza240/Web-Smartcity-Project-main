@php
    $path = trim(request()->path(), '/');
    $title = match (true) {
        $path === 'beranda-dosen' => 'Data dashboard gagal dimuat',
        in_array($path, ['beranda-admin', 'beranda-creator'], true) => 'Dashboard gagal dimuat',
        default => 'Halaman gagal dimuat',
    };
    $reload = request()->isMethod('GET') ? request()->fullUrl() : url()->previous();
@endphp

<x-layout.error code="500" :title="$title" message="Terjadi gangguan pada sistem atau koneksi sehingga data gagal dimuat. Silakan muat ulang halaman." icon="bi-cloud-slash">
    <a href="{{ $reload }}" class="err-btn err-btn--primary">
        <i class="bi bi-arrow-clockwise" aria-hidden="true"></i> Muat Ulang
    </a>
    <a href="{{ url('/') }}" class="err-btn err-btn--ghost">Kembali ke Beranda</a>
</x-layout.error>
