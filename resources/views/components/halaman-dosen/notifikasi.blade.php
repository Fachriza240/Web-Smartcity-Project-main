@props(['notifications'])

@php
    $unreadCount = auth()->user()->unreadNotifications()->count();
@endphp

<section class="dsn-area">
    <div class="container sc-notif-page">
        <header class="dsn-area__head">
            <div>
                <h1 class="dsn-area__title">Notifikasi</h1>
                <p class="dsn-area__lead">
                    @if ($unreadCount > 0)
                        Ada <strong>{{ $unreadCount }}</strong> notifikasi yang belum dibaca.
                    @else
                        Semua notifikasi sudah dibaca.
                    @endif
                </p>
            </div>
            @if ($unreadCount > 0)
                <form action="{{ route('dosen.notifications.read-all') }}" method="POST">
                    @csrf
                    <button type="submit" class="sc-btn sc-btn--ghost sc-btn--sm">
                        <i class="bi bi-check2-all" aria-hidden="true"></i> Tandai semua dibaca
                    </button>
                </form>
            @endif
        </header>

        <div class="dsn-card">
            @forelse ($notifications as $notif)
                <x-halaman-dosen.notifikasi-item :notif="$notif" />
            @empty
                <div class="dsn-empty">
                    <i class="bi bi-bell-slash" aria-hidden="true"></i>
                    <h3>Belum ada notifikasi</h3>
                    <p>Notifikasi muncul ketika dosen lain atau admin mencantumkan nama Anda sebagai pencipta HKI.</p>
                </div>
            @endforelse
        </div>

        <div class="dsn-pagination">{{ $notifications->links() }}</div>
    </div>
</section>