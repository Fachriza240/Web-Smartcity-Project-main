@props(['notif'])

@php
    $data = $notif->data ?? [];
    $unread = $notif->unread();
    $isRegistrasi = ($data['jenis'] ?? null) === 'registrasi';
    $approved = ($data['status'] ?? null) === \App\Models\User::STATUS_APPROVED;
    $judul = $data['judul'] ?? \Illuminate\Support\Str::after($data['message'] ?? '', ': ');
    $oleh = $data['oleh'] ?? null;
@endphp

<a href="{{ route('dosen.notifications.read', $notif->id) }}" {{ $attributes->class(['sc-notif__item', 'is-unread' => $unread]) }}>
    @if ($isRegistrasi)
        <span class="sc-notif__icon"><i class="bi {{ $approved ? 'bi-person-check' : 'bi-person-x' }}" aria-hidden="true"></i></span>
        <span class="sc-notif__body">
            <span class="sc-notif__text">Status registrasi akun:</span>
            <strong class="sc-notif__title">{{ $data['message'] ?? ($approved ? 'Registrasi akun Anda telah disetujui oleh Admin.' : 'Registrasi akun Anda ditolak oleh Admin.') }}</strong>
            @if (! $approved && ! empty($data['alasan']))
                <span class="sc-notif__text">Alasan: {{ $data['alasan'] }}</span>
            @endif
            <small class="sc-notif__time"><i class="bi bi-clock" aria-hidden="true"></i> {{ $notif->created_at->diffForHumans() }}</small>
        </span>
    @else
        <span class="sc-notif__icon"><i class="bi bi-award" aria-hidden="true"></i></span>
        <span class="sc-notif__body">
            <span class="sc-notif__text">{{ $oleh ? $oleh.' menambahkan Anda' : 'Anda ditambahkan' }} sebagai pencipta HKI:</span>
            <strong class="sc-notif__title">{{ $judul }}</strong>
            <small class="sc-notif__time"><i class="bi bi-clock" aria-hidden="true"></i> {{ $notif->created_at->diffForHumans() }}</small>
        </span>
    @endif
    @if ($unread)
        <span class="sc-notif__dot"><span class="visually-hidden">Belum dibaca</span></span>
    @endif
</a>
