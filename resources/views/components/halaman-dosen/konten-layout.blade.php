@props(['title' => 'Konten Saya', 'active' => ''])

<x-layout.link :title="$title">
    <x-layout.navbar />
    <main id="konten-utama" class="dsn-area">
        <div class="container">
            <header class="dsn-area__head">
                <div>
                    <h1 class="dsn-area__title">Konten Saya</h1>
                    <p class="dsn-area__lead">Kelola publikasi dan HKI atas nama Anda.</p>
                </div>
                <a href="{{ route('profil.dosen') }}" class="sc-btn sc-btn--ghost sc-btn--sm">
                    <i class="bi bi-person-circle" aria-hidden="true"></i> Lihat Profil
                </a>
            </header>

            <nav class="dsn-tabs" aria-label="Jenis konten">
                <a href="{{ route('dosen.publikasi.index') }}" class="dsn-tabs__item {{ $active === 'publikasi' ? 'active' : '' }}" @if ($active === 'publikasi') aria-current="page" @endif>
                    <i class="bi bi-journal-text" aria-hidden="true"></i> Publikasi
                </a>
                <a href="{{ route('dosen.hki.index') }}" class="dsn-tabs__item {{ $active === 'hki' ? 'active' : '' }}" @if ($active === 'hki') aria-current="page" @endif>
                    <i class="bi bi-award" aria-hidden="true"></i> HKI
                </a>
            </nav>

            {{ $slot }}
        </div>
    </main>
    <x-layout.footer />
</x-layout.link>
