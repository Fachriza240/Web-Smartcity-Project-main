<x-layout.admin>
    
            <div class="content-header">
                <div><h2 class="mb-1">Edit Mitra</h2><p class="mb-0">Perbarui data mitra COE Smart City.</p></div>
            </div>
            <div class="card-admin">
                <div class="card-body">
                    <form action="{{ route('admin.partners.update', $partner) }}" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                        @include('admin.partners._form')
                    </form>
                </div>
            </div>
        </x-layout.admin>
