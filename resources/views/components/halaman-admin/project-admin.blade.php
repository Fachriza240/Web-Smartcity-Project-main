
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
                <a href="/project-admin" class="nav-link active">
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
        <h2 class="mb-4">Pengelolaan Project - SmartCity</h2>

        <!-- Form Input Project -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Form Input Project</div>
            <div class="card-body">
                <form action="proses_project.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Foto Project</label>
                        <input type="file" name="foto" class="form-control" accept="image/*" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Judul Project</label>
                        <input type="text" name="judul_proyek" class="form-control" placeholder="Masukkan Judul Project" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="4" placeholder="Masukkan Deskripsi Project" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <input type="text" name="kategori" class="form-control" placeholder="Masukkan Kategori Project">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">-- Pilih Status --</option>
                            <option value="Perencanaan">Perencanaan</option>
                            <option value="Berjalan">Berjalan</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lokasi</label>
                        <input type="text" name="lokasi" class="form-control" placeholder="Masukkan Lokasi">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tim Penanggung Jawab</label>
                        <input type="text" name="tim_penanggungjawab" class="form-control" placeholder="Nama Tim / Penanggung Jawab">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Link Dokumen</label>
                        <input type="url" name="link_dokumen" class="form-control" placeholder="URL Dokumen (PDF/Proposal)">
                    </div>
                    <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Simpan Project</button>
                </form>
            </div>
        </div>

        <!-- Tabel Data Project -->
        <div class="card">
            <div class="card-header bg-secondary text-white">Daftar Project</div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Lokasi</th>
                            <th>Tim</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Contoh Data -->
                        <tr>
                            <td><img src="uploads/project1.jpg" alt="Foto Project" width="80"></td>
                            <td>Smart Lighting</td>
                            <td>Infrastruktur</td>
                            <td>Berjalan</td>
                            <td>Jakarta</td>
                            <td>Tim Energi</td>
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