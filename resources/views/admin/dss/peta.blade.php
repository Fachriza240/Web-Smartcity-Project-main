<x-layout.admin title="Peta Kompetensi Smart City" subtitle="Sebaran dosen pada enam dimensi Smart City.">

    <div class="content-header">
        <div>
            <h2 class="mb-1">Peta Kompetensi Smart City</h2>
            <p class="mb-0">Jumlah dosen pada setiap dimensi Smart City, berdasarkan kata kunci pada publikasi, HKI, aktivitas, dan bidang penelitian.</p>
        </div>
    </div>

    @include('admin.dss._nav')
    @include('admin.dss._status')

    @if ($totalDosen > 0)
        <div class="card-admin mb-4">
            <div class="card-body">
                <h5 class="card-title mb-3">Jumlah Dosen per Dimensi</h5>
                <div class="dss-chart">
                    <canvas id="dssPeta" role="img" aria-label="Grafik batang jumlah dosen per dimensi Smart City"></canvas>
                </div>
                <div class="small text-muted mt-2">
                    Satu dosen dapat masuk ke lebih dari satu dimensi. Total dosen dinilai: {{ $totalDosen }}.
                </div>
            </div>
        </div>

        <div class="card-admin">
            <div class="card-body">
                <h5 class="card-title mb-3">Daftar Dosen per Dimensi</h5>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Dimensi</th>
                                <th>Jumlah Dosen</th>
                                <th>Jumlah Dokumen</th>
                                <th>Dosen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($peta as $item)
                                <tr>
                                    <td><strong>{{ $item['label'] }}</strong></td>
                                    <td>{{ $item['jumlah_dosen'] }}</td>
                                    <td>{{ $item['jumlah_dokumen'] }}</td>
                                    <td>
                                        @forelse ($item['dosen'] as $orang)
                                            <a href="{{ route('admin.dss.hasil.show', $orang['id']) }}" class="dss-chip">{{ $orang['nama'] }}</a>
                                        @empty
                                            <span class="text-muted">-</span>
                                        @endforelse
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
        <script>
            (function () {
                if (!window.Chart) { return; }
                var data = {{ \Illuminate\Support\Js::from($grafik) }};
                var css = getComputedStyle(document.documentElement);
                var rgb = (css.getPropertyValue('--adm-primary-rgb') || '76, 141, 201').trim();
                var teks = (css.getPropertyValue('--adm-text-2') || '#475569').trim();
                var garis = (css.getPropertyValue('--adm-border') || '#e2e8f0').trim();
                new Chart(document.getElementById('dssPeta'), {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Jumlah dosen',
                            data: data.nilai,
                            backgroundColor: 'rgba(' + rgb + ', 0.75)',
                            borderColor: 'rgb(' + rgb + ')',
                            borderWidth: 1,
                            borderRadius: 6
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { beginAtZero: true, ticks: { precision: 0, color: teks }, grid: { color: garis } },
                            y: { ticks: { color: teks }, grid: { display: false } }
                        }
                    }
                });
            })();
        </script>
    @endif

</x-layout.admin>