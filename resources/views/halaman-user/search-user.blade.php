<x-layout.link title="Pencarian">
    <x-layout.navbar />
    <main id="konten-utama">
        <x-halaman-user.search-user :keyword="$keyword" :results="$results" />
    </main>
    <x-layout.footer />
</x-layout.link>
