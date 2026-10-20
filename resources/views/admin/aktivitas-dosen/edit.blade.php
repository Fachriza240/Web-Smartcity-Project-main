<x-layout.admin title="Edit Aktivitas Dosen">
    <div class="content-header">
        <div>
            <h2 class="mb-1">Edit Aktivitas Dosen</h2>
            <p class="mb-0">Perbarui data aktivitas dosen.</p>
        </div>
    </div>
    <div class="card-admin">
        <div class="card-body">
            <form action="{{ route('admin.aktivitas-dosen.update', $aktivitas) }}" method="POST" novalidate data-validate data-aktivitas-form>
                @method('PUT')
                @include('admin.aktivitas-dosen._form')
            </form>
        </div>
    </div>
</x-layout.admin>