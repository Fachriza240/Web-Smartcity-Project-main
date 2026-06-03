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
                <a href="/beranda-admin" class="nav-link">
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
                <a href="/program-admin" class="nav-link active">
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
    <main class="main-content p-4">
        <h2 class="mb-4">Pengelolaan Program - SmartCity</h2>

        <!-- Form Input Program -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Form Input Program</div>
            <div class="card-body">
                <form action="proses_program.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Foto Program</label>
                        <input type="file" name="foto" class="form-control" accept="image/*" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Judul Program</label>
                        <input type="text" name="judul" class="form-control" placeholder="Masukkan Judul" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="4" placeholder="Masukkan Deskripsi" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Simpan Program
                    </button>
                </form>
            </div>
        </div>

        <!-- Tabel Data Program -->
        <div class="card">
            <div class="card-header bg-secondary text-white">Daftar Program</div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Judul</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Contoh Data -->
                        <tr>
                            <td><img src="uploads/contoh.jpg" alt="Foto Program" width="80"></td>
                            <td>Smart Traffic System</td>
                            <td>Sistem pengaturan lalu lintas berbasis IoT</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-warning">Edit</a>
                                <a href="#" class="btn btn-sm btn-danger">Hapus</a>
                            </td>
                        </tr>
                        <!-- End Contoh -->
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
