<x-layout.link title="Proyek">
    <x-layout.navbar />
    <main id="konten-utama">
        <x-halaman-user.project-user :projects="$projects" />
    </main>
    <x-layout.footer />
</x-layout.link>
