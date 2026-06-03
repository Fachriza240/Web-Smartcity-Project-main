<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="img/logosc.png" alt="Smart City Logo">
        </a>

        <!-- User avatar for mobile (visible only on small screens) -->
        <div class="d-lg-none mobile-user-dropdown dropdown">
            <img src="img/rayyan.jpg" class="user-avatar dropdown-toggle" id="mobileUserDropdown"
                data-bs-toggle="dropdown" aria-expanded="false">
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="mobileUserDropdown">
                <li><a class="dropdown-item" href="/editprofile">Edit Profile</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="#"
                       onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
                        Logout
                    </a>
                    <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <div class="nav-and-search">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="/beranda-dosen">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="/about-dosen">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="/program-dosen">Program</a></li>
                    <li class="nav-item"><a class="nav-link" href="/project-dosen">Project</a></li>
                    <li class="nav-item"><a class="nav-link" href="/news-dosen">News</a></li>
                    <li class="nav-item"><a class="nav-link" href="/team-dosen">Team</a></li>
                </ul>
                <div class="d-flex align-items-center">
                    <div class="search-box">
                        <input type="text" placeholder="Search...">
                        <i class="fas fa-search"></i>
                    </div>
                    <!-- User profile for desktop (visible only on large screens) -->
                    <div class="d-none d-lg-block dropdown user-dropdown">
                        <img src="img/rayyan.jpg" class="user-avatar dropdown-toggle" id="desktopUserDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="desktopUserDropdown">
                            <li><a class="dropdown-item" href="/profil-dosen">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="#"
                                   onclick="event.preventDefault(); document.getElementById('logout-form-desktop').submit();">
                                    Logout
                                </a>
                                <form id="logout-form-desktop" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>