<x-layout.admin title="Edit Program">

            <div class="content-header">
                <div><h2 class="mb-1">Edit Program</h2><p class="mb-0">Perbarui program CoE Smart City.</p></div>
            </div>
            <div class="card-admin">
                <div class="card-body">
                    <form action="{{ route('admin.programs.update', $program) }}" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                        @include('admin.programs._form')
                    </form>
                </div>
            </div>
        </x-layout.admin>
