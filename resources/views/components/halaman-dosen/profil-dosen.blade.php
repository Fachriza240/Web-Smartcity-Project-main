<!-- Tombol Back -->
<div class="back-button-container" style="position: absolute; top: 20px; left: 20px; z-index: 100;">
    <a href="javascript:history.back()" class="btn btn-light rounded-circle">
        <i class="fas fa-arrow-left"></i>
    </a>
</div>

<!-- Profile Header -->
<div class="profile-header">
    <div class="container profile-header-content">
        <div class="row align-items-center">
            <div class="col-md-3 text-center">
                <img src="img/rayyan.jpg" alt="Foto Profil" class="rounded-circle img-fluid profile-image">
                <!-- Tombol edit foto hanya muncul dalam mode edit -->
                <div id="edit-photo-btn" class="mt-2" style="display: none;">
                    <button class="btn btn-sm btn-light"><i class="fas fa-camera"></i> Ubah Foto</button>
                </div>
            </div>
            <div class="col-md-9">
                <!-- Versi tampilan normal -->
                <div id="display-header-info">
                    <h1 style="color: white;">Dr. Budi Santoso, M.Sc.</h1>
                    <h4 class="mb-3" style="color: white;">Profesor Ilmu Komputer</h4>
                    <p class="lead mb-0" style="color: white;">Fakultas Ilmu Komputer - Universitas Contoh</p>

                    <div class="mt-4 social-icons-profile">
                        <a href="#" target="_blank"><i class="fab fa-instagram"></i></a>
                        <a href="#" target="_blank"><i class="fas fa-envelope"></i></a>
                        <a href="#" target="_blank"><i class="fas fa-blog"></i></a>
                        <a href="#" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" target="_blank"><i class="fas fa-globe"></i></a>
                    </div>
                </div>

                <!-- Versi edit (tersembunyi secara default) -->
                <div id="edit-header-info" style="display: none;">
                    <div class="form-group">
                        <input type="text" class="form-control mb-2" id="edit-name" value="Dr. Budi Santoso, M.Sc."
                            style="color: black;">
                        <input type="text" class="form-control mb-2" id="edit-position" value="Profesor Ilmu Komputer"
                            style="color: black;">
                        <input type="text" class="form-control mb-2" id="edit-faculty"
                            value="Fakultas Ilmu Komputer - Universitas Contoh" style="color: black;">
                    </div>

                    <div class="mt-2">
                        <label style="color: white;">Social Media Links:</label>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fab fa-instagram"></i></span>
                            </div>
                            <input type="url" class="form-control" placeholder="Instagram URL">
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            </div>
                            <input type="email" class="form-control" placeholder="Email">
                        </div>
                        <!-- Tambahan field untuk media sosial lainnya -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Tombol Edit/Save hanya tampil untuk pemilik profil -->
        <div id="edit-controls" class="text-right mt-3" style="display: none;">
            <button id="edit-profile-btn" class="btn btn-primary"><i class="fas fa-edit"></i> Edit Profil</button>
            <button id="save-profile-btn" class="btn btn-success" style="display: none;"><i class="fas fa-save"></i>
                Simpan Perubahan</button>
            <button id="cancel-edit-btn" class="btn btn-secondary" style="display: none;"><i class="fas fa-times"></i>
                Batal</button>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="container">
    <div class="row">
        <!-- Left Column: About and Education -->
        <div class="col-lg-4 mb-4">
            <!-- About Me Section -->
            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="section-title-profile">Tentang Saya</h3>

                    <!-- Versi tampilan -->
                    <div id="display-about">
                        <p class="about-me">
                            Saya telah menekuni dunia pendidikan dan penelitian selama lebih dari 15 tahun.
                            Perjalanan akademik saya dimulai setelah lulus S1, ketika saya mendapat kesempatan
                            untuk melanjutkan studi di luar negeri.
                        </p>
                        <p class="about-me">
                            Selama karir sebagai dosen, saya telah membimbing lebih dari 50 mahasiswa dalam
                            menyelesaikan tugas akhir mereka. Saya percaya bahwa pendidikan adalah kunci untuk
                            membuka potensi seseorang dan berkontribusi pada kemajuan masyarakat.
                        </p>
                    </div>

                    <!-- Versi edit (tersembunyi secara default) -->
                    <div id="edit-about" style="display: none;">
                        <div class="form-group">
                            <textarea class="form-control" id="about-text"
                                rows="6">Saya telah menekuni dunia pendidikan dan penelitian selama lebih dari 15 tahun. Perjalanan akademik saya dimulai setelah lulus S1, ketika saya mendapat kesempatan untuk melanjutkan studi di luar negeri.

Selama karir sebagai dosen, saya telah membimbing lebih dari 50 mahasiswa dalam menyelesaikan tugas akhir mereka. Saya percaya bahwa pendidikan adalah kunci untuk membuka potensi seseorang dan berkontribusi pada kemajuan masyarakat.</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Education Section -->
            <div class="card">
                <div class="card-body">
                    <h3 class="section-title-profile">Riwayat Pendidikan</h3>

                    <!-- Versi tampilan -->
                    <div id="display-education">
                        <div class="education-item">
                            <div class="education-year">2010 - 2014</div>
                            <div class="university-name">Massachusetts Institute of Technology (MIT)</div>
                            <div class="degree">Doctor of Philosophy (Ph.D), Computer Science</div>
                            <small>Disertasi: "Optimasi Algoritma Machine Learning untuk Deteksi Pola"</small>
                        </div>

                        <div class="education-item">
                            <div class="education-year">2007 - 2009</div>
                            <div class="university-name">Stanford University</div>
                            <div class="degree">Master of Science (M.Sc), Computer Science</div>
                            <small>Fokus: Artificial Intelligence dan Data Mining</small>
                        </div>

                        <div class="education-item">
                            <div class="education-year">2003 - 2007</div>
                            <div class="university-name">Universitas Indonesia</div>
                            <div class="degree">Sarjana Komputer (S.Kom), Ilmu Komputer</div>
                            <small>Lulus dengan predikat Cum Laude</small>
                        </div>
                    </div>

                    <!-- Versi edit (tersembunyi secara default) -->
                    <div id="edit-education" style="display: none;">
                        <div id="education-items">
                            <!-- Template item pendidikan untuk mode edit -->
                            <div class="education-edit-item mb-3 border p-3 rounded">
                                <div class="form-row">
                                    <div class="col-md-6 mb-2">
                                        <label>Tahun</label>
                                        <input type="text" class="form-control" value="2010 - 2014">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>Universitas</label>
                                        <input type="text" class="form-control"
                                            value="Massachusetts Institute of Technology (MIT)">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-12 mb-2">
                                        <label>Gelar</label>
                                        <input type="text" class="form-control"
                                            value="Doctor of Philosophy (Ph.D), Computer Science">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-12 mb-2">
                                        <label>Catatan (opsional)</label>
                                        <input type="text" class="form-control"
                                            value="Disertasi: 'Optimasi Algoritma Machine Learning untuk Deteksi Pola'">
                                    </div>
                                </div>
                                <button class="btn btn-sm btn-danger remove-education"><i class="fas fa-trash"></i>
                                    Hapus</button>
                            </div>

                            <!-- Item pendidikan lainnya bisa ditambahkan disini dengan JS -->
                        </div>

                        <button id="add-education" class="btn btn-sm btn-info mt-2"><i class="fas fa-plus"></i> Tambah
                            Pendidikan</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Research and Publications -->
        <div class="col-lg-8">
            <!-- Research Interests -->
            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="section-title-profile">Bidang Penelitian</h3>

                    <!-- Versi tampilan -->
                    <div id="display-research">
                        <p class="research-interests mb-3">
                            Fokus penelitian saya berpusat pada penerapan teknologi AI dan machine learning
                            untuk menyelesaikan masalah dunia nyata. Saya tertarik pada pengembangan algoritma
                            yang dapat meningkatkan kinerja sistem informasi dan pengambilan keputusan.
                        </p>

                        <div class="interest-tags">
                            <span class="interest-tag">Machine Learning</span>
                            <span class="interest-tag">Artificial Intelligence</span>
                            <span class="interest-tag">Data Mining</span>
                            <span class="interest-tag">Natural Language Processing</span>
                            <span class="interest-tag">Computer Vision</span>
                            <span class="interest-tag">Big Data Analytics</span>
                            <span class="interest-tag">Algoritma Optimasi</span>
                        </div>
                    </div>

                    <!-- Versi edit (tersembunyi secara default) -->
                    <div id="edit-research" style="display: none;">
                        <div class="form-group">
                            <label>Deskripsi Penelitian</label>
                            <textarea class="form-control mb-3"
                                rows="4">Fokus penelitian saya berpusat pada penerapan teknologi AI dan machine learning untuk menyelesaikan masalah dunia nyata. Saya tertarik pada pengembangan algoritma yang dapat meningkatkan kinerja sistem informasi dan pengambilan keputusan.</textarea>

                            <label>Bidang Minat (pisahkan dengan Enter)</label>
                            <textarea class="form-control" rows="4">Machine Learning
Artificial Intelligence
Data Mining
Natural Language Processing
Computer Vision
Big Data Analytics
Algoritma Optimasi</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Publications -->
            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="section-title-profile">Publikasi Penelitian</h3>

                    <!-- Versi tampilan -->
                    <div id="display-publications">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="card card-publication">
                                    <div class="card-body">
                                        <span class="publication-year">2023</span>
                                        <h5 class="publication-title">Peningkatan Akurasi Deep Learning Menggunakan
                                            Metode Hybrid</h5>
                                        <p class="journal-name">Journal of Computer Science Research, Vol. 25, Issue 3
                                        </p>
                                        <a href="#" class="btn btn-sm btn-view">Lihat Publikasi</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <div class="card card-publication">
                                    <div class="card-body">
                                        <span class="publication-year">2022</span>
                                        <h5 class="publication-title">Implementasi NLP pada Analisis Sentimen Media
                                            Sosial</h5>
                                        <p class="journal-name">International Journal of AI Applications, Vol. 12, Issue
                                            2</p>
                                        <a href="#" class="btn btn-sm btn-view">Lihat Publikasi</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <div class="card card-publication">
                                    <div class="card-body">
                                        <span class="publication-year">2021</span>
                                        <h5 class="publication-title">Optimasi Algoritma untuk Big Data Analytics</h5>
                                        <p class="journal-name">Data Science Journal, Vol. 8, Issue 4</p>
                                        <a href="#" class="btn btn-sm btn-view">Lihat Publikasi</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <div class="card card-publication">
                                    <div class="card-body">
                                        <span class="publication-year">2020</span>
                                        <h5 class="publication-title">Deteksi Objek Realtime dengan Convolutional Neural
                                            Network</h5>
                                        <p class="journal-name">IEEE Transactions on Pattern Analysis, Vol. 18, Issue 2
                                        </p>
                                        <a href="#" class="btn btn-sm btn-view">Lihat Publikasi</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-3">
                            <a href="#" class="btn btn-view">Lihat Semua Publikasi</a>
                        </div>
                    </div>

                    <!-- Versi edit (tersembunyi secara default) -->
                    <div id="edit-publications" style="display: none;">
                        <div id="publication-items">
                            <!-- Template item publikasi untuk mode edit -->
                            <div class="publication-edit-item mb-3 border p-3 rounded">
                                <div class="form-row">
                                    <div class="col-md-4 mb-2">
                                        <label>Tahun</label>
                                        <input type="text" class="form-control" value="2023">
                                    </div>
                                    <div class="col-md-8 mb-2">
                                        <label>Judul Publikasi</label>
                                        <input type="text" class="form-control"
                                            value="Peningkatan Akurasi Deep Learning Menggunakan Metode Hybrid">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-8 mb-2">
                                        <label>Jurnal/Publikasi</label>
                                        <input type="text" class="form-control"
                                            value="Journal of Computer Science Research, Vol. 25, Issue 3">
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label>URL</label>
                                        <input type="url" class="form-control" placeholder="https://...">
                                    </div>
                                </div>
                                <button class="btn btn-sm btn-danger remove-publication"><i class="fas fa-trash"></i>
                                    Hapus</button>
                            </div>

                            <!-- Item publikasi lainnya bisa ditambahkan disini dengan JS -->
                        </div>

                        <button id="add-publication" class="btn btn-sm btn-info mt-2"><i class="fas fa-plus"></i> Tambah
                            Publikasi</button>
                    </div>
                </div>

                <!-- Publications -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h3 class="section-title-profile">Publikasi Pengabdian Kepada Masyarakat
                        </h3>

                        <!-- Versi tampilan -->
                        <div id="display-publications">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="card card-publication">
                                        <div class="card-body">
                                            <span class="publication-year">2023</span>
                                            <h5 class="publication-title">Peningkatan Akurasi Deep Learning Menggunakan
                                                Metode Hybrid</h5>
                                            <p class="journal-name">Journal of Computer Science Research, Vol. 25, Issue
                                                3
                                            </p>
                                            <a href="#" class="btn btn-sm btn-view">Lihat Publikasi</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <div class="card card-publication">
                                        <div class="card-body">
                                            <span class="publication-year">2022</span>
                                            <h5 class="publication-title">Implementasi NLP pada Analisis Sentimen Media
                                                Sosial</h5>
                                            <p class="journal-name">International Journal of AI Applications, Vol. 12,
                                                Issue
                                                2</p>
                                            <a href="#" class="btn btn-sm btn-view">Lihat Publikasi</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <div class="card card-publication">
                                        <div class="card-body">
                                            <span class="publication-year">2021</span>
                                            <h5 class="publication-title">Optimasi Algoritma untuk Big Data Analytics
                                            </h5>
                                            <p class="journal-name">Data Science Journal, Vol. 8, Issue 4</p>
                                            <a href="#" class="btn btn-sm btn-view">Lihat Publikasi</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <div class="card card-publication">
                                        <div class="card-body">
                                            <span class="publication-year">2020</span>
                                            <h5 class="publication-title">Deteksi Objek Realtime dengan Convolutional
                                                Neural
                                                Network</h5>
                                            <p class="journal-name">IEEE Transactions on Pattern Analysis, Vol. 18,
                                                Issue 2
                                            </p>
                                            <a href="#" class="btn btn-sm btn-view">Lihat Publikasi</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-3">
                                <a href="#" class="btn btn-view">Lihat Semua Publikasi</a>
                            </div>
                        </div>

                        <!-- Versi edit (tersembunyi secara default) -->
                        <div id="edit-publications" style="display: none;">
                            <div id="publication-items">
                                <!-- Template item publikasi untuk mode edit -->
                                <div class="publication-edit-item mb-3 border p-3 rounded">
                                    <div class="form-row">
                                        <div class="col-md-4 mb-2">
                                            <label>Tahun</label>
                                            <input type="text" class="form-control" value="2023">
                                        </div>
                                        <div class="col-md-8 mb-2">
                                            <label>Judul Publikasi</label>
                                            <input type="text" class="form-control"
                                                value="Peningkatan Akurasi Deep Learning Menggunakan Metode Hybrid">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-8 mb-2">
                                            <label>Jurnal/Publikasi</label>
                                            <input type="text" class="form-control"
                                                value="Journal of Computer Science Research, Vol. 25, Issue 3">
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label>URL</label>
                                            <input type="url" class="form-control" placeholder="https://...">
                                        </div>
                                    </div>
                                    <button class="btn btn-sm btn-danger remove-publication"><i
                                            class="fas fa-trash"></i>
                                        Hapus</button>
                                </div>

                                <!-- Item publikasi lainnya bisa ditambahkan disini dengan JS -->
                            </div>

                            <button id="add-publication" class="btn btn-sm btn-info mt-2"><i class="fas fa-plus"></i>
                                Tambah
                                Publikasi</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>