<x-layout.error code="413" title="Ukuran file terlalu besar" message="Ukuran file yang diunggah melebihi batas maksimum yang diizinkan. Silakan pilih file yang lebih kecil lalu coba lagi." icon="bi-file-earmark-x">
    <a href="{{ url()->previous() }}" class="err-btn err-btn--primary">
        <i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali
    </a>
</x-layout.error>
