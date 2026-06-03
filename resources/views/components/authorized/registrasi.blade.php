<div class="container-fluid p-0">
    <div class="row g-0 login-container" style="min-height:100vh;">
        <!-- Left side - Background -->
        <div class="col-md-6 login-bg">
            <div class="circuit-overlay"></div>
        </div>

        <!-- Right side - Form -->
        <div class="col-md-6 login-form-container d-flex align-items-center justify-content-center">
            <div class="login-form w-75">
                <!-- Back Button -->
                <a href="/login" class="back-btn d-inline-block mb-3 text-decoration-none text-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Back to Login
                </a>

                <!-- Logo -->
                <div class="text-center mb-4">
                    <img src="img/logosc.png" alt="Smart City Logo" style="max-width:200px;">
                </div>

                <!-- Header -->
                <h2 class="text-center mb-4">Create Account</h2>

                <!-- Form -->
                <form action="/registrasi" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <input type="text" name="fullname" class="form-control" placeholder="Fullname" required>
                    </div>
                    <div class="mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                    </div>
                    <div class="mb-3">
                        <input type="number" name="nip" class="form-control" placeholder="NIP" required>
                    </div>
                    <div class="mb-3 position-relative password-field">
                        <input type="password" name="password" class="form-control" placeholder="Password" required id="password">
                        <span class="password-toggle position-absolute" style="top:50%; right:10px; transform:translateY(-50%); cursor:pointer;">
                            <i class="far fa-eye-slash"></i>
                        </span>
                    </div>
                    <div class="mb-3 position-relative password-field">
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
                        <span class="password-toggle position-absolute" style="top:50%; right:10px; transform:translateY(-50%); cursor:pointer;">
                            <i class="far fa-eye-slash"></i>
                        </span>
                    </div>
                    <div class="mb-3">
                        <input type="file" name="foto" class="form-control">
                    </div>
                    <div class="d-grid">
                        <button type="submit"  class="btn btn-primary">Register</button>
                    </div>
                </form>

                <!-- Partner Logos -->
                <div class="text-center mt-4 partner-logos">
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

<script>
document.querySelectorAll('.password-toggle').forEach(toggle => {
    toggle.addEventListener('click', function () {
        const input = this.previousElementSibling; 
        const icon = this.querySelector('i');

        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        } else {
            input.type = "password";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        }
    });
});
</script>
