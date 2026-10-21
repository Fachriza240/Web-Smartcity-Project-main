@php
    $warnaBadge = $warna ?? 'secondary';
    $kelasBadge = $warnaBadge === 'warning' ? 'bg-warning text-dark' : 'bg-' . $warnaBadge;
@endphp
<span class="badge {{ $kelasBadge }}">{{ $label }}</span>