<div class="dashboard-container">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-logo">A</div>
            <h4 class="mb-0">Admin Panel</h4>
        </div>

        <ul class="nav-menu">
            <li class="menu-category">Main Menu</li>
            <li class="nav-item">
                <a href="/beranda-admin" class="nav-link active">
                    <i class="bi bi-speedometer2"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-layout-text-window-reverse"></i>
                    Konten Website
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-people"></i>
                    Manajemen User
                </a>
            </li>

            <li class="menu-category">Content Management</li>
            <li class="nav-item">
                <a href="/program-admin" class="nav-link">
                    <i class="bi bi-layers"></i> <!-- Program -->
                    Program
                </a>
            </li>
            <li class="nav-item">
                <a href="/research-team-admin" class="nav-link">
                    <i class="bi bi-people-fill"></i> <!-- Research Team -->
                    Research Team
                </a>
            </li>
            <li class="nav-item">
                <a href="/project-admin" class="nav-link">
                    <i class="bi bi-kanban"></i> <!-- Project -->
                    Project
                </a>
            </li>
            <li class="nav-item">
                <a href="/news-admin" class="nav-link">
                    <i class="bi bi-newspaper"></i> <!-- News -->
                    News
                </a>
            </li>

            <li class="menu-category">Settings</li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-gear"></i>
                    Pengaturan
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link" 
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-right"></i>
                    Logout
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="content-header">
            <h2 class="mb-0">Content Management</h2>
            <div class="header-actions">
                <button class="btn btn-primary">
                    <i class="bi bi-plus-lg me-2"></i>
                    Tambah Konten Baru
                </button>
            </div>
        </div>

        <div class="content-editor mb-4">
            <h4>Edit Konten</h4>
            <div class="editor-toolbar">
                <button class="btn btn-light"><i class="bi bi-type-bold"></i></button>
                <button class="btn btn-light"><i class="bi bi-type-italic"></i></button>
                <button class="btn btn-light"><i class="bi bi-list"></i></button>
                <button class="btn btn-light"><i class="bi bi-link"></i></button>
                <button class="btn btn-light"><i class="bi bi-image"></i></button>
            </div>
            <div class="mb-3">
                <input type="text" class="form-control mb-3" placeholder="Judul Konten">
                <textarea class="form-control" rows="5" placeholder="Isi konten..."></textarea>
            </div>
            <button class="btn btn-primary">Simpan Perubahan</button>
        </div>

        <div class="card-admin">
            <div class="card-body">
                <h5 class="card-title mb-4">Daftar Konten</h5>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Kategori</th>
                                <th>Status</th>
                                <th>Terakhir Diubah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Halaman Utama</td>
                                <td>Website</td>
                                <td><span class="badge bg-success">Published</span></td>
                                <td>2024-02-17</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-icon" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn-icon" title="Preview">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn-icon" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>About Us</td>
                                <td>Website</td>
                                <td><span class="badge bg-warning">Draft</span></td>
                                <td>2024-02-16</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-icon" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn-icon" title="Preview">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn-icon" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>