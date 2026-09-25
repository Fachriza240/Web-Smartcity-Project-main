@php
    $user = auth()->user();
    $area = $user && $user->role === 'dosen' && $user->registration_status === \App\Models\User::STATUS_APPROVED ? 'dosen' : 'user';
    $socials = collect(config('smartcity.socials'))->filter(fn ($item) => ! empty($item['url']));
@endphp

<footer class="sc-footer">
    <div class="container">
        <div class="sc-footer__grid">
            <div class="sc-footer__brand">
                <img src="{{ asset('img/logosc.png') }}" alt="CoE Smart City Telkom University" class="sc-footer__logo" width="180" height="68" loading="lazy">
                <p>
                    Center of Excellence Smart City Telkom University mempertemukan akademisi, industri, dan pemerintah
                    untuk merancang solusi kota cerdas yang berdampak nyata bagi warga.
                </p>
                <div class="sc-footer__social">
                    @foreach ($socials as $social)
                        <a href="{{ $social['url'] }}" target="_blank" rel="noopener" aria-label="{{ $social['label'] }}">
                            <i class="bi {{ $social['icon'] }}" aria-hidden="true"></i>
                        </a>
                    @endforeach
                    <a href="https://wa.me/{{ config('smartcity.whatsapp') }}" target="_blank" rel="noopener" aria-label="WhatsApp">
                        <i class="bi bi-whatsapp" aria-hidden="true"></i>
                    </a>
                    <a href="mailto:{{ config('smartcity.email') }}" aria-label="Email">
                        <i class="bi bi-envelope" aria-hidden="true"></i>
                    </a>
                </div>
            </div>

            <nav class="sc-footer__col" aria-label="Tautan halaman">
                <h2 class="sc-footer__title">Jelajahi</h2>
                <ul>
                    <li><a href="{{ url("/about-{$area}") }}">Tentang Kami</a></li>
                    <li><a href="{{ url("/program-{$area}") }}">Program</a></li>
                    <li><a href="{{ url("/project-{$area}") }}">Proyek</a></li>
                    <li><a href="{{ url("/news-{$area}") }}">Berita</a></li>
                    <li><a href="{{ url('/publication-user') }}">Publikasi</a></li>
                    <li><a href="{{ url("/team-{$area}") }}">Tim</a></li>
                    <li><a href="{{ url("/mitra-{$area}") }}">Mitra</a></li>
                </ul>
            </nav>

            <div class="sc-footer__col sc-footer__contact">
                <h2 class="sc-footer__title">Hubungi Kami</h2>
                <ul>
                    <li>
                        <i class="bi bi-geo-alt" aria-hidden="true"></i>
                        <a href="{{ config('smartcity.maps_link') }}" target="_blank" rel="noopener">{{ config('smartcity.address') }}</a>
                    </li>
                    <li>
                        <i class="bi bi-envelope" aria-hidden="true"></i>
                        <a href="mailto:{{ config('smartcity.email') }}">{{ config('smartcity.email') }}</a>
                    </li>
                    <li>
                        <i class="bi bi-whatsapp" aria-hidden="true"></i>
                        <a href="https://wa.me/{{ config('smartcity.whatsapp') }}" target="_blank" rel="noopener">
                            {{ config('smartcity.phone_display') }} ({{ config('smartcity.whatsapp_label') }})
                        </a>
                    </li>
                </ul>
                <a href="{{ route('contact') }}" class="sc-footer__cta">
                    Lihat lokasi di peta <i class="bi bi-arrow-right-short" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <div class="sc-footer__bottom">
            <span>&copy; {{ date('Y') }} CoE Smart City Telkom University. Seluruh hak cipta dilindungi.</span>
            <span>Bandung, Jawa Barat</span>
        </div>
    </div>
</footer>
