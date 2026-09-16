function $id(id) {
    return document.getElementById(id);
}

function setDisplay(id, value) {
    const el = $id(id);
    if (el) {
        el.style.display = value;
    }
}

window.addEventListener("scroll", function () {
    const navbar = document.querySelector(".navbar");
    if (!navbar) return; 

    if (window.scrollY > 50) {
        navbar.classList.add("scrolled");
    } else {
        navbar.classList.remove("scrolled");
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const galleryEl = $id("galleryCarousel");

    if (!galleryEl || typeof bootstrap === "undefined") return;

    new bootstrap.Carousel(galleryEl, {
        interval: 3000, 
        wrap: true, 
        pause: "hover", 
    });
});


document.addEventListener("DOMContentLoaded", function () {
    const scrollTop = document.querySelector(".scroll-top");

    function toggleScrollTop() {
        if (!scrollTop) return;

        window.scrollY > 100
            ? scrollTop.classList.add("active")
            : scrollTop.classList.remove("active");
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

document.addEventListener("DOMContentLoaded", function () {
    const currentPath = window.location.pathname; 
    const menuItems = document.querySelectorAll(".navbar-nav .nav-item > a"); 

    if (!menuItems || menuItems.length === 0) return; 

    menuItems.forEach((menuItem) => {
        menuItem.classList.remove("active");
        if (menuItem.parentElement) {
            menuItem.parentElement.classList.remove("active");
        }
    });

    menuItems.forEach((menuItem) => {
        const menuHref = menuItem.getAttribute("href");

        if (!menuHref) return; 
        if (menuHref.startsWith("#") && window.location.pathname === "/") {
            menuItem.classList.add("active");
            menuItem.parentElement?.classList.add("active");
        }
        else if (
            currentPath === menuHref ||
            (menuHref !== "/" && currentPath.startsWith(menuHref)) ||
            (menuHref === "/" && currentPath === "/")
        ) {
            menuItem.classList.add("active");
            menuItem.parentElement?.classList.add("active");
        }
    });

    menuItems.forEach((menuItem) => {
        menuItem.addEventListener("click", function (e) {
            menuItems.forEach((item) => {
                item.classList.remove("active");
                item.parentElement?.classList.remove("active");
            });

            this.classList.add("active");
            this.parentElement?.classList.add("active");
        });
    });
});


function isProfileOwner() {
    return true; 
}

document.addEventListener("DOMContentLoaded", function () {
    if (isProfileOwner()) {
        setDisplay("edit-controls", "block");
    }

    const editBtn   = $id("edit-profile-btn");
    const saveBtn   = $id("save-profile-btn");
    const cancelBtn = $id("cancel-edit-btn");
    const addEduBtn = $id("add-education");
    const addPubBtn = $id("add-publication");

    if (editBtn) {
        editBtn.addEventListener("click", function () {
            this.style.display = "none";
            setDisplay("save-profile-btn", "inline-block");
            setDisplay("cancel-edit-btn", "inline-block");

            toggleEditMode(true);
        });
    }

    if (saveBtn) {
        saveBtn.addEventListener("click", function () {
            saveProfileChanges();

            this.style.display = "none";
            setDisplay("cancel-edit-btn", "none");
            setDisplay("edit-profile-btn", "inline-block");

            toggleEditMode(false);
        });
    }

    if (cancelBtn) {
        cancelBtn.addEventListener("click", function () {
            this.style.display = "none";
            setDisplay("save-profile-btn", "none");
            setDisplay("edit-profile-btn", "inline-block");

            toggleEditMode(false);
        });
    }

    if (addEduBtn) {
        addEduBtn.addEventListener("click", function () {
            addEducationItem();
        });
    }

    if (addPubBtn) {
        addPubBtn.addEventListener("click", function () {
            addPublicationItem();
        });
    }

    document.addEventListener("click", function (event) {
        const target = event.target;
        if (!target) return;

        const removeEduBtn = target.closest?.(".remove-education");
        if (removeEduBtn) {
            removeEducationItem(removeEduBtn.closest(".education-edit-item"));
        }

        const removePubBtn = target.closest?.(".remove-publication");
        if (removePubBtn) {
            removePublicationItem(removePubBtn.closest(".publication-edit-item"));
        }
    });
});

function toggleEditMode(isEdit) {
    setDisplay("display-header-info", isEdit ? "none" : "block");
    setDisplay("edit-header-info", isEdit ? "block" : "none");
    setDisplay("edit-photo-btn", isEdit ? "block" : "none");

    setDisplay("display-about", isEdit ? "none" : "block");
    setDisplay("edit-about", isEdit ? "block" : "none");

    setDisplay("display-education", isEdit ? "none" : "block");
    setDisplay("edit-education", isEdit ? "block" : "none");

    setDisplay("display-research", isEdit ? "none" : "block");
    setDisplay("edit-research", isEdit ? "block" : "none");

    setDisplay("display-publications", isEdit ? "none" : "block");
    setDisplay("edit-publications", isEdit ? "block" : "none");
}

function saveProfileChanges() {
    console.log("Menyimpan perubahan profil...");

    alert("Perubahan profil berhasil disimpan!");
}

function addEducationItem() {
    const container = $id("education-items");
    if (!container) return; 

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

function removeEducationItem(item) {
    if (!item) return; 

    if (confirm("Apakah Anda yakin ingin menghapus riwayat pendidikan ini?")) {
        item.remove();
    }
}

function addPublicationItem() {
    const container = $id("publication-items");
    if (!container) return; 

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

function removePublicationItem(item) {
    if (!item) return; 

    if (confirm("Apakah Anda yakin ingin menghapus publikasi ini?")) {
        item.remove();
    }
}