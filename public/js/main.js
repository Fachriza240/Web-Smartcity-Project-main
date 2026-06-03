// Main JS

window.addEventListener("scroll", function () {
    if (window.scrollY > 50) {
        document.querySelector(".navbar").classList.add("scrolled");
    } else {
        document.querySelector(".navbar").classList.remove("scrolled");
    }
});

document.addEventListener("DOMContentLoaded", function () {
    // Initialize the carousel with custom options
    const galleryCarousel = new bootstrap.Carousel(
        document.getElementById("galleryCarousel"),
        {
            interval: 3000, // Change slides every 3 seconds
            wrap: true, // Continuous loop
            pause: "hover", // Pause on mouse hover
        }
    );
});

/**
 * Scroll top button
 */
document.addEventListener("DOMContentLoaded", function () {
    let scrollTop = document.querySelector(".scroll-top");

    function toggleScrollTop() {
        if (scrollTop) {
            window.scrollY > 100
                ? scrollTop.classList.add("active")
                : scrollTop.classList.remove("active");
        }
    }

    if (scrollTop) {
        scrollTop.addEventListener("click", (e) => {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: "smooth",
            });
        });
    }

    window.addEventListener("load", toggleScrollTop);
    document.addEventListener("scroll", toggleScrollTop);
});

// Navbar Active
document.addEventListener("DOMContentLoaded", function () {
    const currentPath = window.location.pathname; // Mendapatkan URL path saat ini
    const menuItems = document.querySelectorAll(".navbar-nav .nav-item > a"); // Menu utama (navbar)

    // Hapus kelas active dari semua item menu
    menuItems.forEach((menuItem) => {
        menuItem.classList.remove("active");
        // Pastikan parent element (nav-item) juga tidak memiliki class active
        menuItem.parentElement.classList.remove("active");
    });

    // Cek menu utama apakah cocok dengan currentPath
    menuItems.forEach((menuItem) => {
        const menuHref = menuItem.getAttribute("href");

        // Untuk link dengan anchor (#) seperti #about, #program
        if (menuHref.startsWith("#") && window.location.pathname === "/") {
            // Jika ini adalah halaman home dan link memiliki anchor
            menuItem.classList.add("active");
            menuItem.parentElement.classList.add("active");
        }
        // Untuk path penuh seperti /people, /indexuser
        else if (
            currentPath === menuHref ||
            (menuHref !== "/" && currentPath.startsWith(menuHref)) ||
            (menuHref === "/" && currentPath === "/")
        ) {
            menuItem.classList.add("active");
            menuItem.parentElement.classList.add("active");
        }
    });

    // Menangani klik pada link navbar untuk menandai yang aktif
    menuItems.forEach((menuItem) => {
        menuItem.addEventListener("click", function (e) {
            // Hapus active dari semua item
            menuItems.forEach((item) => {
                item.classList.remove("active");
                item.parentElement.classList.remove("active");
            });

            // Tambahkan active ke item yang diklik
            this.classList.add("active");
            this.parentElement.classList.add("active");
        });
    });
});

// Cek apakah pengguna adalah pemilik profil (diimplementasikan berdasarkan sistem autentikasi Anda)
function isProfileOwner() {
    // Contoh implementasi sederhana
    // Di implementasi sebenarnya, Anda akan memeriksa session atau token autentikasi
    // Misalnya: return currentUserId === profileOwnerId;
    return true; // Untuk demo, kita anggap pengunjung adalah pemilik profil
}

// Tampilkan kontrol edit jika pengguna adalah pemilik profil
document.addEventListener("DOMContentLoaded", function () {
    if (isProfileOwner()) {
        document.getElementById("edit-controls").style.display = "block";
    }

    // Event listener untuk tombol edit
    document
        .getElementById("edit-profile-btn")
        .addEventListener("click", function () {
            // Sembunyikan tombol edit dan tampilkan tombol simpan/batal
            this.style.display = "none";
            document.getElementById("save-profile-btn").style.display =
                "inline-block";
            document.getElementById("cancel-edit-btn").style.display =
                "inline-block";

            // Beralih ke mode edit
            toggleEditMode(true);
        });

    // Event listener untuk tombol simpan
    document
        .getElementById("save-profile-btn")
        .addEventListener("click", function () {
            // Di sini seharusnya ada kode untuk menyimpan perubahan ke server
            // Untuk contoh ini, kita hanya beralih kembali ke mode tampilan
            saveProfileChanges();

            // Sembunyikan tombol simpan/batal dan tampilkan tombol edit
            this.style.display = "none";
            document.getElementById("cancel-edit-btn").style.display = "none";
            document.getElementById("edit-profile-btn").style.display =
                "inline-block";

            // Beralih kembali ke mode tampilan
            toggleEditMode(false);
        });

    // Event listener untuk tombol batal
    document
        .getElementById("cancel-edit-btn")
        .addEventListener("click", function () {
            // Sembunyikan tombol simpan/batal dan tampilkan tombol edit
            this.style.display = "none";
            document.getElementById("save-profile-btn").style.display = "none";
            document.getElementById("edit-profile-btn").style.display =
                "inline-block";

            // Beralih kembali ke mode tampilan tanpa menyimpan perubahan
            toggleEditMode(false);
        });

    // Event listener untuk tombol tambah pendidikan
    document
        .getElementById("add-education")
        .addEventListener("click", function () {
            addEducationItem();
        });

    // Event listener untuk tombol tambah publikasi
    document
        .getElementById("add-publication")
        .addEventListener("click", function () {
            addPublicationItem();
        });

    // Event delegation untuk tombol hapus pendidikan dan publikasi
    document.addEventListener("click", function (event) {
        if (
            event.target.classList.contains("remove-education") ||
            event.target.parentElement.classList.contains("remove-education")
        ) {
            removeEducationItem(event.target.closest(".education-edit-item"));
        }

        if (
            event.target.classList.contains("remove-publication") ||
            event.target.parentElement.classList.contains("remove-publication")
        ) {
            removePublicationItem(
                event.target.closest(".publication-edit-item")
            );
        }
    });
});

// Fungsi untuk beralih antara mode tampilan dan mode edit
function toggleEditMode(isEdit) {
    // Toggle elemen header
    document.getElementById("display-header-info").style.display = isEdit
        ? "none"
        : "block";
    document.getElementById("edit-header-info").style.display = isEdit
        ? "block"
        : "none";
    document.getElementById("edit-photo-btn").style.display = isEdit
        ? "block"
        : "none";

    // Toggle bagian About
    document.getElementById("display-about").style.display = isEdit
        ? "none"
        : "block";
    document.getElementById("edit-about").style.display = isEdit
        ? "block"
        : "none";

    // Toggle bagian Education
    document.getElementById("display-education").style.display = isEdit
        ? "none"
        : "block";
    document.getElementById("edit-education").style.display = isEdit
        ? "block"
        : "none";

    // Toggle bagian Research
    document.getElementById("display-research").style.display = isEdit
        ? "none"
        : "block";
    document.getElementById("edit-research").style.display = isEdit
        ? "block"
        : "none";

    // Toggle bagian Publications
    document.getElementById("display-publications").style.display = isEdit
        ? "none"
        : "block";
    document.getElementById("edit-publications").style.display = isEdit
        ? "block"
        : "none";
}

// Fungsi untuk menyimpan perubahan (sebenarnya akan melibatkan AJAX/fetch ke server)
function saveProfileChanges() {
    // Di implementasi sebenarnya, kode ini akan mengumpulkan semua data dari form
    // dan mengirimkannya ke server untuk disimpan ke database
    console.log("Menyimpan perubahan profil...");

    // Ini hanya contoh visualisasi perubahan tanpa benar-benar menyimpannya
    // Di aplikasi sebenarnya, ini akan diimplementasikan dengan AJAX/fetch
    alert("Perubahan profil berhasil disimpan!");
}

// Fungsi untuk menambahkan item pendidikan baru
function addEducationItem() {
    const container = document.getElementById("education-items");
    const newItem = document.createElement("div");
    newItem.className = "education-edit-item mb-3 border p-3 rounded";
    newItem.innerHTML = `
        <div class="form-row">
            <div class="col-md-6 mb-2">
                <label>Tahun</label>
                <input type="text" class="form-control" placeholder="contoh: 2010 - 2014">
            </div>
            <div class="col-md-6 mb-2">
                <label>Universitas</label>
                <input type="text" class="form-control" placeholder="Nama Universitas">
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-12 mb-2">
                <label>Gelar</label>
                <input type="text" class="form-control" placeholder="contoh: Sarjana Komputer (S.Kom), Ilmu Komputer">
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-12 mb-2">
                <label>Catatan (opsional)</label>
                <input type="text" class="form-control" placeholder="contoh: Lulus dengan predikat Cum Laude">
            </div>
        </div>
        <button class="btn btn-sm btn-danger remove-education"><i class="fas fa-trash"></i> Hapus</button>
    `;
    container.appendChild(newItem);
}

// Fungsi untuk menghapus item pendidikan
function removeEducationItem(item) {
    if (confirm("Apakah Anda yakin ingin menghapus riwayat pendidikan ini?")) {
        item.remove();
    }
}

// Fungsi untuk menambahkan item publikasi baru
function addPublicationItem() {
    const container = document.getElementById("publication-items");
    const newItem = document.createElement("div");
    newItem.className = "publication-edit-item mb-3 border p-3 rounded";
    newItem.innerHTML = `
        <div class="form-row">
            <div class="col-md-4 mb-2">
                <label>Tahun</label>
                <input type="text" class="form-control" placeholder="contoh: 2023">
            </div>
            <div class="col-md-8 mb-2">
                <label>Judul Publikasi</label>
                <input type="text" class="form-control" placeholder="Judul publikasi ilmiah">
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-8 mb-2">
                <label>Jurnal/Publikasi</label>
                <input type="text" class="form-control" placeholder="contoh: Journal of Computer Science Research, Vol. 25, Issue 3">
            </div>
            <div class="col-md-4 mb-2">
                <label>URL</label>
                <input type="url" class="form-control" placeholder="https://...">
            </div>
        </div>
        <button class="btn btn-sm btn-danger remove-publication"><i class="fas fa-trash"></i> Hapus</button>
    `;
    container.appendChild(newItem);
}

// Fungsi untuk menghapus item publikasi
function removePublicationItem(item) {
    if (confirm("Apakah Anda yakin ingin menghapus publikasi ini?")) {
        item.remove();
    }
}
