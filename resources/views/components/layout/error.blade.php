@props(['code', 'title', 'message', 'redirect' => null, 'icon' => 'bi-exclamation-triangle'])
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <meta name="theme-color" content="#3a7ab3">
    @if ($redirect)
        <meta http-equiv="refresh" content="3;url={{ $redirect }}">
    @endif
    <title>{{ $title }} | {{ config('smartcity.name') }}</title>
    <link rel="icon" href="{{ asset('img/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Spline+Sans:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --err-blue: #4c8dc9;
            --err-blue-dark: #3a7ab3;
            --err-deep: #1b3f72;
            --err-text: #2c3e55;
            --err-muted: #6b7a8c;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            font-family: "Lato", "Segoe UI", Roboto, Arial, sans-serif;
            background: linear-gradient(160deg, #f4f8fd 0%, #e4edf8 100%);
            color: var(--err-text);
        }

        .err-card {
            width: 100%;
            max-width: 520px;
            padding: 40px 32px;
            border-radius: 20px;
            background: #fff;
            box-shadow: 0 20px 50px rgba(27, 63, 114, 0.12);
            text-align: center;
        }

        .err-logo {
            height: 52px;
            width: auto;
            margin-bottom: 24px;
        }

        .err-icon {
            width: 72px;
            height: 72px;
            margin: 0 auto 16px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: var(--err-blue);
            background: rgba(76, 141, 201, 0.12);
        }

        .err-code {
            margin: 0 0 6px;
            font-family: "Spline Sans", "Segoe UI", sans-serif;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.12em;
            color: var(--err-blue);
        }

        .err-title {
            margin: 0 0 10px;
            font-family: "Spline Sans", "Segoe UI", sans-serif;
            font-size: 24px;
            font-weight: 700;
            color: var(--err-deep);
        }

        .err-message {
            margin: 0 0 24px;
            font-size: 15px;
            line-height: 1.6;
            color: var(--err-muted);
        }

        .err-note {
            margin: -12px 0 20px;
            font-size: 13px;
            color: var(--err-muted);
        }

        .err-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
        }

        .err-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 44px;
            padding: 0 20px;
            border: 1.5px solid var(--err-blue);
            border-radius: 999px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            transition: background 0.2s, color 0.2s;
        }

        .err-btn--primary {
            background: var(--err-blue);
            color: #fff;
        }

        .err-btn--primary:hover {
            background: var(--err-blue-dark);
            border-color: var(--err-blue-dark);
            color: #fff;
        }

        .err-btn--ghost {
            background: #fff;
            color: var(--err-blue);
        }

        .err-btn--ghost:hover {
            background: #f4f8fd;
        }

        .err-btn:focus-visible {
            outline: 3px solid rgba(76, 141, 201, 0.45);
            outline-offset: 2px;
        }

        @media (max-width: 480px) {
            .err-card {
                padding: 32px 20px;
            }

            .err-title {
                font-size: 20px;
            }

            .err-btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <main class="err-card">
        <img src="{{ asset('img/logosc.png') }}" alt="CoE Smart City Telkom University" class="err-logo">
        <div class="err-icon"><i class="bi {{ $icon }}" aria-hidden="true"></i></div>
        <p class="err-code">KODE {{ $code }}</p>
        <h1 class="err-title">{{ $title }}</h1>
        <p class="err-message">{{ $message }}</p>
        @if ($redirect)
            <p class="err-note">Anda akan diarahkan kembali secara otomatis dalam 3 detik.</p>
        @endif
        <div class="err-actions">
            {{ $slot }}
        </div>
    </main>
</body>

</html>
