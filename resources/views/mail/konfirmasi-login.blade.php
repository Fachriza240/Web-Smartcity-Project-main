<x-mail::message>
**Halo, {{ $nama }}!**

Ada permintaan login ke akun {{ config('smartcity.short_name') }} dengan email **{{ $email }}**.

<x-mail::panel>
**Waktu:** {{ $waktu }} WIB<br>
**Perangkat:** {{ $perangkat }}<br>
**Alamat IP:** {{ $ip }}
</x-mail::panel>

Jika benar Anda yang sedang login, pilih **Ya, Ini Saya** untuk melanjutkan.

<x-mail::button :url="$urlIniSaya" color="success">
Ya, Ini Saya
</x-mail::button>

Jika Anda tidak merasa melakukan login ini, pilih **Bukan Saya** agar login tersebut ditolak, lalu segera ganti password akun Anda.

<x-mail::button :url="$urlBukanSaya" color="error">
Bukan Saya
</x-mail::button>

Tautan konfirmasi ini berlaku selama {{ $menit }} menit.

Salam,<br>
Tim {{ config('smartcity.name') }}
</x-mail::message>
