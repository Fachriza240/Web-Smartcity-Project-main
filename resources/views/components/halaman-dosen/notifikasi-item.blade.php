@props(['notif'])

@php
    $data = $notif->data ?? [];
    $judul = $data['judul'] ?? \Illuminate\Support\Str::after($data['message'] ?? '', ': ');
    $oleh = $data['oleh'] ?? null;
    $unread = $notif->unread();
@endphp

<a href="{{ route('dosen.notifications.read', $notif->id) }}" {{ $attributes->class(['sc-notif__item', 'is-unread' => $unread]) }}>
    <span class="sc-notif__icon"><i class="bi bi-award" aria-hidden="true"></i></span>
    <span class="sc-notif__body">
        <span class="sc-notif__text">{{ $oleh ? $oleh.' menambahkan Anda' : 'Anda ditambahkan' }} sebagai pencipta HKI:</span>
        <strong class="sc-notif__title">{{ $judul }}</strong>
        <small class="sc-notif__time"><i class="bi bi-clock" aria-hidden="true"></i> {{ $notif->created_at->diffForHumans() }}</small>
    </span>
    @if ($unread)
        <span class="sc-notif__dot"><span class="visually-hidden">Belum dibaca</span></span>
    @endif
</a>