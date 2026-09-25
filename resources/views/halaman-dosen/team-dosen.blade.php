<x-layout.link title="Tim">
    <x-layout.navbar />
    <main id="konten-utama">
        <x-halaman-user.team-user :lecturers="$lecturers" :staff="$staff" :interns="$interns" />
    </main>
    <x-layout.footer />
</x-layout.link>
