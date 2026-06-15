<!DOCTYPE html>
<html lang="id" data-theme="">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Panel' }} — COE Smart City</title>
    <link href="{{ asset('img/favicon.png') }}" rel="icon">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Admin CSS -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">

    <script>
        // Terapkan tema sebelum render agar tidak flicker
        (function () {
            var t = localStorage.getItem('adm-theme') || 'light';
            document.documentElement.setAttribute('data-theme', t === 'dark' ? 'dark' : '');
        })();
    </script>
</head>

<body class="adm-body">

    <div class="dashboard-container">

        {{-- Sidebar --}}
        <x-admin.sidebar />

        {{-- Main wrapper --}}
        <div style="flex:1; min-width:0;">

            {{-- Topbar --}}
            <x-admin.topbar :title="$title ?? 'Dashboard'" :subtitle="$subtitle ?? ''" />

            {{-- Page content --}}
            <main class="main-content">
                {{ $slot }}
            </main>

        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Admin JS -->
    <script src="{{ asset('js/admin.js') }}"></script>

</body>
</html>
