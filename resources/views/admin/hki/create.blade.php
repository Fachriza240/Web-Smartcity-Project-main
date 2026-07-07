<x-layout.admin title="Tambah HKI">
    <div class="content-header">
        <div><h2 class="mb-1">Tambah HKI</h2><p class="mb-0">Tambahkan data Hak Kekayaan Intelektual baru.</p></div>
    </div>
    <div class="card-admin">
        <div class="card-body">
            <form action="{{ route('admin.hki.store') }}" method="POST" enctype="multipart/form-data">
                @include('admin.hki._form')
            </form>
        </div>
    </div>
</x-layout.admin>
