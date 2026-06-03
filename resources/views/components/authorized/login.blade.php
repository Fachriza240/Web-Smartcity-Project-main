<div class="container-fluid p-0">
    <div class="row g-0 login-container">
        <!-- Left side - City Background with Circuit Pattern Overlay -->
        <div class="col-md-6 login-bg">
            <div class="circuit-overlay"></div>
        </div>

        <!-- Right side - Login Form -->
        <div class="col-md-6 login-form-container">
            <!-- Back Button -->
            <a href="/" class="back-btn">
                <i class="fas fa-arrow-left me-2"></i> Back
            </a>

            <div class="login-form">
                <!-- Logo -->
                <div class="text-center mb-4">
                    <img src="img/logosc.png" alt="Smart City Logo" style="max-width: 200px;">
                </div>

                <!-- Login Header -->
                <h2 class="text-center login-header">Login</h2>

                <!-- Login Form -->
                <form action="{{ route('login.masuk') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <input type="email" id="email" name="email" class="form-control" placeholder="Email" required>
                    </div>
                    <div class="mb-3 position-relative password-field">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                        <span class="password-toggle position-absolute" style="top:50%; right:10px; transform:translateY(-50%); cursor:pointer;">
                            <i class="far fa-eye-slash"></i>
                        </span>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                    <!-- Register link -->
                    <div class="text-center mt-3">
                        <p class="mb-0">
                            Don't have an account? 
                            <a href="/registrasi" class="text-decoration-none fw-medium" style="color: #0d6efd;">
                                Register
                            </a>
                        </p>
                    </div>
                </form>


                <!-- Partner Logos -->
                <div class="text-center partner-logos">
                    <div class="row">
                        <div class="col-6">
                            <img src="img/logotelkom2.png" alt="Telkom University" class="img-fluid">
                        </div>
                        <div class="col-6">
                            <img src="img/logofit.png" alt="Fakultas Ilmu Terapan" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for password visibility toggle -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const togglePassword = document.querySelector('.password-toggle');
        const password = document.querySelector('#password');

        // Toggle password visibility
        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });

        // Validasi login
        const loginForm = document.getElementById('loginForm');
        loginForm.addEventListener('submit', function (e) {
            e.preventDefault(); // stop submit langsung

            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value.trim();

            if (username === "" || password === "") {
                alert("Harap isi Username dan Password sebelum login!");
                return;
            }

            // Kalau valid → redirect ke halaman admin
            window.location.href = "/beranda-admin";
        });
    });
</script>
