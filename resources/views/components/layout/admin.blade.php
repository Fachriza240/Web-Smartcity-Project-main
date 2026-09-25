@props(['title' => 'Dashboard', 'subtitle' => ''])
<!DOCTYPE html>
<html lang="id" data-theme="">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0f172a">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} | Panel {{ config('smartcity.short_name') }}</title>
    <link rel="icon" href="{{ asset('img/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    <script>
        (function () {
                    try {
                        var theme = localStorage.getItem('adm-theme');
                        document.documentElement.setAttribute('data-theme', theme === 'dark' ? 'dark' : '');
                    } catch (e) {
                    }
                })();
    </script>
</head>

<body class="adm-body">
    <a href="#adm-main" class="adm-skip-link">Lewati ke konten utama</a>

    <div class="dashboard-container">
        <x-admin.sidebar />
        <div class="adm-backdrop" id="admBackdrop" hidden></div>

        <div class="adm-wrapper">
            <x-admin.topbar :title="$title" :subtitle="$subtitle" />

            <main class="main-content" id="adm-main">
                <x-layout.flash class="adm-flash" />
                {{ $slot }}
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/validation.js') }}"></script>
    <script src="{{ asset('js/admin.js') }}"></script>
</body>

</html>
