<x-layout.link title="Profil Saya">
    <x-layout.navbar />
    <main id="konten-utama">
        <x-halaman-dosen.profil-dosen :user="$user" :publications="$publications" :hkis="$hkis" />
    </main>
    <x-layout.footer />
</x-layout.link>
