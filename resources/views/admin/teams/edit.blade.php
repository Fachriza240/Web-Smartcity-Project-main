<x-layout.admin>
    
            <div class="content-header">
                <div><h2 class="mb-1">Edit Anggota Tim</h2><p class="mb-0">Perbarui data anggota tim COE Smart City.</p></div>
            </div>
            <div class="card-admin">
                <div class="card-body">
                    <form action="{{ route('admin.teams.update', $team) }}" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                        @include('admin.teams._form')
                    </form>
                </div>
            </div>
        </x-layout.admin>
