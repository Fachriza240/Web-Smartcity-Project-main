<x-mail::message>
**Halo, {{ $nama }}!**

Sistem mencatat aktivitas login mencurigakan. Pemilik akun memilih **Bukan Saya** pada email konfirmasi login, sehingga proses login dibatalkan dan akun dikunci sementara.

<x-mail::panel>
**Waktu:** {{ $waktu }} WIB<br>
**Email:** {{ $email }}<br>
**Perangkat:** {{ $perangkat }}<br>
**Alamat IP:** {{ $ip }}<br>
**Dikunci sampai:** {{ $terkunci }} WIB
</x-mail::panel>

Mohon periksa aktivitas akun tersebut dan hubungi pemilik akun bila diperlukan.

<x-mail::button :url="$url">
Buka Dashboard Admin
</x-mail::button>

Salam,<br>
Sistem {{ config('smartcity.name') }}
</x-mail::message>
