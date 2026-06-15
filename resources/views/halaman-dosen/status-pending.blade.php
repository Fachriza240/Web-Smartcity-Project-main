<x-layout.link>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h4>Status Registrasi Anda</h4>

                        @php $status = auth()->user()->registration_status ?? null; @endphp

                        @if($status === \App\Models\User::STATUS_PENDING)
                            <p class="lead text-warning">Akun Anda sedang menunggu validasi dari Admin.</p>
                        @elseif($status === \App\Models\User::STATUS_REJECTED)
                            <p class="lead text-danger">Registrasi Anda ditolak. Silakan hubungi Admin untuk informasi lebih lanjut.</p>
                            <a href="{{ route('registrasi') }}" class="btn btn-primary">Daftar Ulang</a>
                        @else
                            <p class="lead text-success">Status Anda: {{ $status }}</p>
                            <a href="/beranda-dosen" class="btn btn-primary">Masuk Dashboard</a>
                        @endif

                        <hr>
                        <p class="small text-muted mb-0">Jika perlu bantuan, hubungi admin melalui halaman kontak.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout.link>
