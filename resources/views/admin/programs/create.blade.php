<x-layout.admin>
    
            <div class="content-header">
                <div><h2 class="mb-1">Tambah Program</h2><p class="mb-0">Tambahkan program baru COE Smart City.</p></div>
            </div>
            <div class="card-admin">
                <div class="card-body">
                    <form action="{{ route('admin.programs.store') }}" method="POST" enctype="multipart/form-data">
                        @include('admin.programs._form')
                    </form>
                </div>
            </div>
        </x-layout.admin>
