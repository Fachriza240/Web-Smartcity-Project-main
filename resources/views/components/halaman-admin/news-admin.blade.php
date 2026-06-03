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
                <a href="/project-admin" class="nav-link">
                    <i class="bi bi-kanban"></i> <!-- Project -->
                    Project
                </a>
            </li>
            <li class="nav-item">
                <a href="/news-admin" class="nav-link active">
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
        <h2 class="mb-4">Pengelolaan Berita - SmartCity</h2>

        <!-- Form Input News -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Form Input Berita</div>
            <div class="card-body">
                <form action="proses_news.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Foto Utama</label>
                        <input type="file" name="image" class="form-control" accept="image/*" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Judul Berita</label>
                        <input type="text" name="title" class="form-control" placeholder="Masukkan Judul Berita" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ringkasan</label>
                        <textarea name="excerpt" class="form-control" rows="3" placeholder="Masukkan ringkasan berita"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Isi Berita</label>
                        <textarea name="content" class="form-control" rows="6" placeholder="Masukkan isi berita lengkap" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <input type="text" name="category" class="form-control" placeholder="Masukkan kategori">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tags</label>
                        <input type="text" name="tags" class="form-control" placeholder="Contoh: teknologi, smartcity, iot">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">-- Pilih Status --</option>
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Publikasi</label>
                        <input type="datetime-local" name="published_at" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Simpan Berita</button>
                </form>
            </div>
        </div>

        <!-- Tabel Data News -->
        <div class="card">
            <div class="card-header bg-secondary text-white">Daftar Berita</div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Tanggal Publikasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Contoh Data -->
                        <tr>
                            <td><img src="uploads/news1.jpg" alt="Foto Berita" width="80"></td>
                            <td>Smart City Akan Diterapkan di Seluruh Kota</td>
                            <td>Teknologi</td>
                            <td>Published</td>
                            <td>2025-08-12 10:00</td>
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