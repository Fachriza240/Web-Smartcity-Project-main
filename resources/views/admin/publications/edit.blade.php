<x-layout.admin>
    
            <div class="content-header">
                <div>
                    <h2 class="mb-1">Edit Publication</h2>
                    <p class="mb-0">Perbarui data publikasi ilmiah COE Smart City.</p>
                </div>
            </div>

            <div class="card-admin">
                <div class="card-body">
                    <form action="{{ route('admin.publications.update', $publication) }}" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                        @include('admin.publications._form')
                    </form>
                </div>
            </div>
        </x-layout.admin>
