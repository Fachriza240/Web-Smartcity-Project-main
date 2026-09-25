@props(['items' => []])

<div class="row g-3 mb-4">
    @foreach ($items as $item)
        <div class="col-6 col-lg-3">
            <div class="adm-stat">
                <div class="adm-stat__top">
                    <div>
                        <div class="adm-stat__label">{{ $item['label'] }}</div>
                        <div class="adm-stat__num">{{ $item['value'] }}</div>
                    </div>
                    <div class="adm-stat__icon {{ $item['color'] }}"><i class="bi {{ $item['icon'] }}" aria-hidden="true"></i></div>
                </div>
                <a href="{{ $item['url'] }}" class="adm-stat__link">{{ $item['action'] ?? 'Kelola' }} <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
            </div>
        </div>
    @endforeach
</div>
