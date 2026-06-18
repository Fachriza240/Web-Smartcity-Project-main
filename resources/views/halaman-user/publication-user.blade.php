<x-layout.link>
    @if ($isDosen)
        <x-layout.navbar-dosen></x-layout.navbar-dosen>
    @else
        <x-layout.navbar></x-layout.navbar>
    @endif
    <x-halaman-user.publication-user :publications="$publications" :categories="$categories"
        :years="$years"></x-halaman-user.publication-user>
    <x-layout.footer></x-layout.footer>
</x-layout.link>
