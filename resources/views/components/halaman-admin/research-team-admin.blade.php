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
                <a href="/research-team-admin" class="nav-link active">
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
        <h2 class="mb-4">Pengelolaan Data Team - SmartCity</h2>

        <!-- Form Input Data -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Form Input Data Team</div>
            <div class="card-body">
                <form action="proses_team.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Nama Depan</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Belakang</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="Staff">Staff</option>
                            <option value="Intern">Intern</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jabatan / Posisi</label>
                        <input type="text" name="posisi" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bidang Keahlian</label>
                        <textarea name="bidang_keahlian" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lokasi Kerja</label>
                        <input type="text" name="lokasi" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto</label>
                        <input type="file" name="foto" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">LinkedIn</label>
                        <input type="url" name="linkedin" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Google Scholar</label>
                        <input type="url" name="google_scholar" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ResearchGate</label>
                        <input type="url" name="researchgate" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tugas dan Tanggung Jawab</label>
                        <textarea name="responsibilities" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Materi yang Diajarkan</label>
                        <textarea name="student_education" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kelompok Riset</label>
                        <textarea name="research_groups" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bidang Penelitian</label>
                        <textarea name="bidang_penelitian" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Publikasi Penelitian</label>
                        <textarea name="publikasi_penelitian" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Publikasi Pengabdian</label>
                        <textarea name="publikasi_pengabdian" class="form-control" rows="3"></textarea>
                    </div>

                    <button type="submit" class="btn btn-success">Simpan Data</button>
                </form>
            </div>
        </div>

        <!-- Tabel Data -->
        <div class="card">
            <div class="card-header bg-secondary text-white">Daftar Data Team</div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Posisi</th>
                            <th>Bidang Keahlian</th>
                            <th>Email</th>
                            <th>Lokasi</th>
                            <th>LinkedIn</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Contoh Data -->
                        <tr>
                            <td>John Doe</td>
                            <td>Peneliti Utama</td>
                            <td>AI & IoT</td>
                            <td>john@example.com</td>
                            <td>Bandung</td>
                            <td><a href="#">Profil</a></td>
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
