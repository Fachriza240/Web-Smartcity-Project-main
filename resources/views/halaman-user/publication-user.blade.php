<x-layout.link title="Publikasi">
    <x-layout.navbar />
    <main id="konten-utama">
        <x-halaman-user.publication-user :publications="$publications" :categories="$categories" :years="$years" />
    </main>
    <x-layout.footer />
</x-layout.link>
