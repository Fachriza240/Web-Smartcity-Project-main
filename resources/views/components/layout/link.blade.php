<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart City - Center of Excellence</title>
    <link href="{{ asset('img/favicon.png') }}" rel="icon">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <link
        href="https://fonts.googleapis.com/css2?family=Spline+Sans:wght@400;500;600;700&family=Lato:wght@300;400;700&display=swap"
        rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet" />

    <link href="{{ asset('css/main.css') }}" rel="stylesheet">

</head>

<body>

    @if(session('error'))
        <div class="alert alert-danger text-center mb-0 rounded-0" style="z-index: 2000; position: relative;">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success text-center mb-0 rounded-0" style="z-index: 2000; position: relative;">{{ session('success') }}</div>
    @endif

    {{ $slot }}

    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>

    <script src="{{ asset('js/main.js') }}"></script>

    <script>
        AOS.init();

        document.addEventListener('DOMContentLoaded', function () {
            var navbarCollapseEl = document.getElementById('navbarNav');
            if (!navbarCollapseEl) return;

            var navLinks = navbarCollapseEl.querySelectorAll('.nav-link:not(.dropdown-toggle)');
            navLinks.forEach(function (link) {
                link.addEventListener('click', function () {
                    if (!navbarCollapseEl.classList.contains('show')) return;

                    if (window.bootstrap && window.bootstrap.Collapse) {
                        var instance = window.bootstrap.Collapse.getOrCreateInstance(navbarCollapseEl, { toggle: false });
                        instance.hide();
                    } else {
                        navbarCollapseEl.classList.remove('show');
                    }
                });
            });
        });
    </script>

</body>

</html>