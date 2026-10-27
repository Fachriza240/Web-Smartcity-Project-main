<?php

return [

    'jendela_tahun' => 5,

    'ambang_cr' => 0.1,

    'ri' => [
        1 => 0.00,
        2 => 0.00,
        3 => 0.58,
        4 => 0.90,
        5 => 1.12,
        6 => 1.24,
        7 => 1.32,
        8 => 1.41,
        9 => 1.45,
        10 => 1.49,
    ],

    'skala_saaty' => [1, 2, 3, 4, 5, 6, 7, 8, 9],

    'ambang_gap' => 50,

    'ambang_cluster' => 60,

    'ambang_peran_utama' => 30,

    'kriteria' => [
        'C1' => [
            'nama' => 'Penelitian',
            'deskripsi' => 'Publikasi ilmiah, hibah penelitian, dan keterlibatan dalam riset.',
            'jenis' => 'benefit',
            'bobot_awal' => 0.30,
            'cluster' => 'Kuat Riset',
            'peran_utama' => 'Ketua Riset',
            'peran_dasar' => 'Anggota Riset',
            'saran_gap' => 'Perlu didorong menambah publikasi dan hibah penelitian.',
        ],
        'C2' => [
            'nama' => 'Abdimas',
            'deskripsi' => 'Kegiatan pengabdian kepada masyarakat, keberlanjutan program, dan mitra masyarakat.',
            'jenis' => 'benefit',
            'bobot_awal' => 0.25,
            'cluster' => 'Kuat Abdimas',
            'peran_utama' => 'PIC Abdimas',
            'peran_dasar' => 'Anggota Tim Abdimas',
            'saran_gap' => 'Perlu didorong menambah kegiatan abdimas dan mitra masyarakat.',
        ],
        'C3' => [
            'nama' => 'Inovasi dan Produk',
            'deskripsi' => 'HKI, prototipe, aplikasi, dashboard, sistem informasi, dan produk teknologi IoT/AI.',
            'jenis' => 'benefit',
            'bobot_awal' => 0.20,
            'cluster' => 'Kuat Inovasi',
            'peran_utama' => 'PIC Prototipe dan Pengembang Produk',
            'peran_dasar' => 'Anggota Tim Pengembang',
            'saran_gap' => 'Perlu didorong menghasilkan HKI, prototipe, atau produk teknologi.',
        ],
        'C4' => [
            'nama' => 'Kesesuaian Smart City',
            'deskripsi' => 'Kesesuaian topik penelitian dan kegiatan dengan enam dimensi Smart City.',
            'jenis' => 'benefit',
            'bobot_awal' => 0.15,
            'cluster' => 'Selaras Smart City',
            'peran_utama' => 'Pakar dan Reviewer Bidang Smart City',
            'peran_dasar' => 'Anggota Tim Kajian',
            'saran_gap' => 'Perlu diarahkan agar topik riset dan kegiatan selaras dengan bidang Smart City.',
        ],
        'C5' => [
            'nama' => 'Kolaborasi dan Kepemimpinan',
            'deskripsi' => 'Kerja sama dengan mitra, lintas prodi, dan pengalaman memimpin program atau proyek.',
            'jenis' => 'benefit',
            'bobot_awal' => 0.10,
            'cluster' => 'Kuat Jejaring',
            'peran_utama' => 'PIC Partnership dan Hilirisasi',
            'peran_dasar' => 'Anggota Tim Kemitraan',
            'saran_gap' => 'Perlu didorong membangun kolaborasi dan mengambil peran kepemimpinan program.',
        ],
    ],

    'kategori' => [
        ['min' => 45, 'label' => 'Sangat Potensial', 'warna' => 'success', 'makna' => 'Cocok menjadi ketua program atau ketua riset unggulan CoE.'],
        ['min' => 30, 'label' => 'Potensial', 'warna' => 'primary', 'makna' => 'Cocok menjadi anggota inti atau PIC kegiatan tertentu.'],
        ['min' => 15, 'label' => 'Cukup Potensial', 'warna' => 'warning', 'makna' => 'Perlu penguatan publikasi, abdimas, atau kolaborasi.'],
        ['min' => 0, 'label' => 'Perlu Pengembangan', 'warna' => 'secondary', 'makna' => 'Perlu pendampingan dan integrasi ke program CoE.'],
    ],

    'rubrik' => [

        'peran' => [
            'ketua' => 1.0,
            'anggota' => 0.6,
        ],

        'publikasi' => [
            'Jurnal' => 10,
            'Buku' => 8,
            'Conference' => 6,
            'Laporan Penelitian' => 3,
            'Paten' => 0,
        ],

        'penelitian' => [
            'institusi' => 4,
            'nasional' => 8,
            'internasional' => 12,
        ],

        'abdimas' => [
            'institusi' => 3,
            'nasional' => 6,
            'internasional' => 10,
        ],

        'bonus_berkelanjutan' => 2,

        'hki' => [
            'Paten' => 12,
            'Paten Sederhana' => 8,
            'Hak Cipta' => 5,
            'Desain Industri' => 5,
            'Desain Tata Letak Sirkuit Terpadu' => 5,
            'Merek' => 3,
            'Rahasia Dagang' => 3,
            'Varietas Tanaman' => 3,
        ],

        'produk' => [
            'prototipe' => 6,
            'aplikasi' => 8,
            'dashboard' => 8,
            'sistem_informasi' => 8,
            'iot_ai' => 10,
            'produk_teknologi' => 10,
        ],

        'kolaborasi' => [
            'industri' => 5,
            'pemerintah' => 5,
            'komunitas' => 3,
            'luar_negeri' => 8,
            'lintas_prodi' => 3,
        ],

        'kepemimpinan' => [
            'ketua' => 6,
            'pic' => 4,
            'koordinator' => 4,
            'pembimbing_mahasiswa' => 2,
            'pengelola_proyek' => 4,
        ],

        'smart_city' => [
            'poin_per_dokumen' => 2,
            'bonus_per_dimensi' => 3,
        ],
    ],

    'label_sub_jenis' => [
        'produk' => [
            'prototipe' => 'Prototipe',
            'aplikasi' => 'Aplikasi',
            'dashboard' => 'Dashboard',
            'sistem_informasi' => 'Sistem Informasi',
            'iot_ai' => 'Integrasi IoT/AI',
            'produk_teknologi' => 'Produk Teknologi',
        ],
        'kolaborasi' => [
            'industri' => 'Mitra Industri',
            'pemerintah' => 'Pemerintah',
            'komunitas' => 'Komunitas',
            'luar_negeri' => 'Kampus/Institusi Luar Negeri',
            'lintas_prodi' => 'Lintas Program Studi',
        ],
        'kepemimpinan' => [
            'ketua' => 'Ketua Program/Proyek',
            'pic' => 'PIC',
            'koordinator' => 'Koordinator',
            'pembimbing_mahasiswa' => 'Pembimbing Mahasiswa',
            'pengelola_proyek' => 'Pengelola Proyek',
        ],
    ],

    'bidang_smart_city' => [
        'smart_governance' => [
            'label' => 'Smart Governance',
            'kata_kunci' => ['pemerintah', 'tata kelola', 'e-government', 'egovernment', 'layanan publik', 'kebijakan', 'birokrasi', 'open data', 'governance', 'administrasi'],
        ],
        'smart_environment' => [
            'label' => 'Smart Environment',
            'kata_kunci' => ['lingkungan', 'energi', 'sampah', 'polusi', 'kualitas udara', 'kualitas air', 'banjir', 'iklim', 'hijau', 'pertanian', 'agriculture', 'environment'],
        ],
        'smart_mobility' => [
            'label' => 'Smart Mobility',
            'kata_kunci' => ['transportasi', 'lalu lintas', 'mobilitas', 'kendaraan', 'kereta', 'parkir', 'traffic', 'transport', 'logistik', 'jalan raya'],
        ],
        'smart_economy' => [
            'label' => 'Smart Economy',
            'kata_kunci' => ['ekonomi', 'umkm', 'bisnis', 'industri', 'e-commerce', 'keuangan', 'fintech', 'startup', 'pariwisata', 'wirausaha', 'perdagangan'],
        ],
        'smart_living' => [
            'label' => 'Smart Living',
            'kata_kunci' => ['kesehatan', 'keamanan', 'keselamatan', 'bencana', 'perumahan', 'rumah pintar', 'smart home', 'cctv', 'kualitas hidup', 'telemedicine'],
        ],
        'smart_people' => [
            'label' => 'Smart People',
            'kata_kunci' => ['pendidikan', 'pembelajaran', 'literasi', 'sdm', 'pelatihan', 'pemberdayaan', 'kompetensi digital', 'e-learning', 'siswa', 'mahasiswa'],
        ],
    ],

];