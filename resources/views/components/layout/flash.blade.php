@props(['floating' => false])

@php
    $messages = collect([
        'success' => session('success'),
        'danger' => session('error'),
        'warning' => session('warning'),
        'info' => session('info'),
    ])->filter();
    $icons = [
        'success' => 'bi-check-circle-fill',
        'danger' => 'bi-exclamation-octagon-fill',
        'warning' => 'bi-exclamation-triangle-fill',
        'info' => 'bi-info-circle-fill',
    ];
@endphp

@if ($messages->isNotEmpty())
    <div {{ $attributes->class(['flash-stack', 'flash-stack--floating' => $floating]) }}>
        @foreach ($messages as $type => $message)
            <div class="alert alert-{{ $type }} alert-flash alert-flash--{{ $type }} alert-dismissible fade show" role="alert" data-flash="{{ $type }}">
                <i class="bi {{ $icons[$type] }}" aria-hidden="true"></i>
                <span class="alert-flash__text">{{ $message }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endforeach
    </div>
@endif
