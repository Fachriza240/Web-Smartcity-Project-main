<x-layout.admin title="Edit HKI">
    <div class="content-header">
        <div><h2 class="mb-1">Edit HKI</h2><p class="mb-0">Perbarui data Hak Kekayaan Intelektual.</p></div>
    </div>
    <div class="card-admin">
        <div class="card-body">
            <form action="{{ route('admin.hki.update', $hki) }}" method="POST" enctype="multipart/form-data">
                @method('PUT')
                @include('admin.hki._form')
            </form>
        </div>
    </div>
</x-layout.admin>
