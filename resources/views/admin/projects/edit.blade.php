<x-layout.admin>
    
            <div class="content-header">
                <div>
                    <h2 class="mb-1">Edit Project</h2>
                    <p class="mb-0">Perbarui data proyek COE Smart City.</p>
                </div>
            </div>

            <div class="card-admin">
                <div class="card-body">
                    <form action="{{ route('admin.projects.update', $project) }}" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                        @include('admin.projects._form')
                    </form>
                </div>
            </div>
        </x-layout.admin>
