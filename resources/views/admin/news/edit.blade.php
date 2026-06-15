<x-layout.admin>
    
            <div class="content-header">
                <div><h2 class="mb-1">Edit Berita</h2><p class="mb-0">Perbarui berita COE Smart City.</p></div>
            </div>
            <div class="card-admin">
                <div class="card-body">
                    <form action="{{ route('admin.news.update', $news) }}" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                        @include('admin.news._form')
                    </form>
                </div>
            </div>
        </x-layout.admin>
