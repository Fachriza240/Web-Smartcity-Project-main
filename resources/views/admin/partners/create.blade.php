<x-layout.admin>
    
            <div class="content-header">
                <div><h2 class="mb-1">Tambah Mitra</h2><p class="mb-0">Tambahkan mitra baru COE Smart City.</p></div>
            </div>
            <div class="card-admin">
                <div class="card-body">
                    <form action="{{ route('admin.partners.store') }}" method="POST" enctype="multipart/form-data">
                        @include('admin.partners._form')
                    </form>
                </div>
            </div>
        </x-layout.admin>
