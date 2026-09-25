<x-layout.link title="Tentang Kami">
    <x-layout.navbar />
    <main id="konten-utama">
        <x-halaman-user.about-user />
        <x-halaman-user.partner-section :partners="$partners" />
    </main>
    <x-layout.footer />
</x-layout.link>
