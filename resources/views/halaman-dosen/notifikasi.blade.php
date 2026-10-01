<x-layout.link title="Notifikasi">
    <x-layout.navbar />
    <main id="konten-utama">
        <x-halaman-dosen.notifikasi :notifications="$notifications" />
    </main>
    <x-layout.footer />
</x-layout.link>