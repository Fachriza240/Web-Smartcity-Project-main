@php
    $socials = collect(config('smartcity.socials'))->filter(fn ($item) => ! empty($item['url']));
@endphp

<section class="sc-page-hero">
    <div class="container">
        <span class="sc-page-hero__eyebrow"><span class="sc-page-hero__dot" aria-hidden="true"></span> Kontak Kami</span>
        <h1 class="sc-page-hero__title">Mari berkolaborasi dengan<br><span class="text-blue">CoE Smart City</span></h1>
        <p class="sc-page-hero__lead">
            Punya ide riset, kebutuhan pendampingan, atau rencana kerja sama? Tim kami siap mendengarkan
            dan membantu menemukan solusi kota cerdas yang paling sesuai.
        </p>
    </div>
    <div class="sc-page-hero__wave" aria-hidden="true">
        <svg viewBox="0 0 1440 100" preserveAspectRatio="none">
            <path fill="#ffffff" d="M0,60 C360,110 720,10 1080,60 C1260,85 1380,50 1440,40 L1440,100 L0,100Z" />
        </svg>
    </div>
</section>

<section class="sc-section">
    <div class="container">
        <div class="sc-contact__grid">
            <div class="sc-contact__cards">
                <div class="sc-contact-card" data-aos="fade-up">
                    <span class="sc-contact-card__icon"><i class="bi bi-geo-alt-fill" aria-hidden="true"></i></span>
                    <div>
                        <h2>Alamat</h2>
                        <p>{{ config('smartcity.address') }}</p>
                        <a href="{{ config('smartcity.maps_link') }}" target="_blank" rel="noopener">Buka di Google Maps</a>
                    </div>
                </div>

                <div class="sc-contact-card" data-aos="fade-up" data-aos-delay="80">
                    <span class="sc-contact-card__icon"><i class="bi bi-envelope-fill" aria-hidden="true"></i></span>
                    <div>
                        <h2>Email</h2>
                        <p>Kirim proposal kerja sama atau pertanyaan umum melalui email resmi kami.</p>
                        <a href="mailto:{{ config('smartcity.email') }}">{{ config('smartcity.email') }}</a>
                    </div>
                </div>

                <div class="sc-contact-card" data-aos="fade-up" data-aos-delay="160">
                    <span class="sc-contact-card__icon sc-contact-card__icon--wa"><i class="bi bi-whatsapp" aria-hidden="true"></i></span>
                    <div>
                        <h2>WhatsApp {{ config('smartcity.whatsapp_label') }}</h2>
                        <p>Untuk koordinasi cepat pada hari dan jam kerja.</p>
                        <a href="https://wa.me/{{ config('smartcity.whatsapp') }}" target="_blank" rel="noopener">{{ config('smartcity.phone_display') }}</a>
                    </div>
                </div>

                @if ($socials->isNotEmpty())
                    <div class="sc-contact-card" data-aos="fade-up" data-aos-delay="240">
                        <span class="sc-contact-card__icon"><i class="bi bi-share-fill" aria-hidden="true"></i></span>
                        <div>
                            <h2>Media Sosial</h2>
                            <p>Ikuti kegiatan dan kabar terbaru kami.</p>
                            <div class="sc-contact__socials">
                                @foreach ($socials as $social)
                                    <a href="{{ $social['url'] }}" target="_blank" rel="noopener">
                                        <i class="bi {{ $social['icon'] }}" aria-hidden="true"></i> {{ $social['handle'] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="sc-contact__map" data-aos="fade-up">
                <iframe src="{{ config('smartcity.maps_embed') }}" title="Peta lokasi CoE Smart City Telkom University"
                    loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>

        <div class="sc-contact__cta" data-aos="fade-up">
            <div>
                <h2>Siap memulai kolaborasi?</h2>
                <p>Ceritakan kebutuhan Anda, kami akan menghubungi kembali secepatnya.</p>
            </div>
            <div class="sc-contact__cta-actions">
                <a href="https://wa.me/{{ config('smartcity.whatsapp') }}" class="sc-btn sc-btn--wa" target="_blank" rel="noopener">
                    <i class="bi bi-whatsapp" aria-hidden="true"></i> Chat WhatsApp
                </a>
                <a href="mailto:{{ config('smartcity.email') }}" class="sc-btn sc-btn--light">
                    <i class="bi bi-envelope" aria-hidden="true"></i> Kirim Email
                </a>
            </div>
        </div>
    </div>
</section>
