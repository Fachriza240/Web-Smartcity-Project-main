@php
    $user = auth()->user();
    $dashboard = match ($user?->role) {
        'admin' => url('/beranda-admin'),
        'content_creator' => $user->registration_status === \App\Models\User::STATUS_APPROVED ? url('/beranda-creator') : route('dosen.status'),
        'dosen' => $user->registration_status === \App\Models\User::STATUS_APPROVED ? url('/beranda-dosen') : route('dosen.status'),
        default => url('/'),
    };
    $message = $user?->role === 'content_creator'
        ? 'Anda tidak memiliki hak akses untuk membuka halaman ini.'
        : 'Anda tidak memiliki hak akses ke halaman ini.';
@endphp

<x-layout.error code="403" title="Akses Ditolak" :message="$message" :redirect="$dashboard" icon="bi-shield-lock">
    <a href="{{ $dashboard }}" class="err-btn err-btn--primary">
        <i class="bi bi-speedometer2" aria-hidden="true"></i> Kembali ke Dashboard
    </a>
</x-layout.error>
