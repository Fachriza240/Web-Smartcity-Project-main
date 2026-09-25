<x-layout.link title="Berita">
    <x-layout.navbar />
    <main id="konten-utama">
        <x-halaman-user.news-user :news="$news" :categories="$categories" :years="$years" />
    </main>
    <x-layout.footer />
</x-layout.link>
