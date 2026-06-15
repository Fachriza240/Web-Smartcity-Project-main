<x-layout.admin>
    
            <div class="content-header">
                <div>
                    <h2 class="mb-1">Tambah Publication</h2>
                    <p class="mb-0">Tambahkan publikasi ilmiah COE Smart City.</p>
                </div>
            </div>

            <div class="card-admin">
                <div class="card-body">
                    <form action="{{ route('admin.publications.store') }}" method="POST" enctype="multipart/form-data">
                        @include('admin.publications._form')
                    </form>
                </div>
            </div>
        </x-layout.admin>
