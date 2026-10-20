<x-layout.admin title="Tambah Aktivitas Dosen">
    <div class="content-header">
        <div>
            <h2 class="mb-1">Tambah Aktivitas Dosen</h2>
            <p class="mb-0">Catat aktivitas penelitian, abdimas, produk, kolaborasi, atau kepemimpinan atas nama dosen.</p>
        </div>
    </div>
    <div class="card-admin">
        <div class="card-body">
            <form action="{{ route('admin.aktivitas-dosen.store') }}" method="POST" novalidate data-validate data-aktivitas-form>
                @include('admin.aktivitas-dosen._form')
            </form>
        </div>
    </div>
</x-layout.admin>