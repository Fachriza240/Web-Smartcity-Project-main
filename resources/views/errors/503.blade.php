<x-layout.error code="503" title="Sedang dalam pemeliharaan" message="Website sedang dalam pemeliharaan atau gangguan sementara. Silakan coba beberapa saat lagi." icon="bi-tools">
    <a href="{{ request()->fullUrl() }}" class="err-btn err-btn--primary">
        <i class="bi bi-arrow-clockwise" aria-hidden="true"></i> Muat Ulang
    </a>
</x-layout.error>
