<x-layout.admin>
    
            <div class="content-header">
                <div><h2 class="mb-1">Tambah Berita</h2><p class="mb-0">Buat berita baru COE Smart City.</p></div>
            </div>
            <div class="card-admin">
                <div class="card-body">
                    <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
                        @include('admin.news._form')
                    </form>
                </div>
            </div>
        </x-layout.admin>
