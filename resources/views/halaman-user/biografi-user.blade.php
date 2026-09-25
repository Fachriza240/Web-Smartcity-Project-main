<x-layout.link title="Biografi Dosen">
    <x-layout.navbar />
    <main id="konten-utama">
        <x-halaman-user.biografi-user :lecturer="$lecturer" :publications="$publications" :hkis="$hkis" />
    </main>
    <x-layout.footer />
</x-layout.link>
