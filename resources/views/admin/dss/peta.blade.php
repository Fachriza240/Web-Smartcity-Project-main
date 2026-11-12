<x-layout.admin title="Peta Kompetensi Smart City" subtitle="Sebaran dosen pada enam dimensi Smart City.">

    <div class="content-header">
        <div>
            <h2 class="mb-1">Peta Kompetensi Smart City</h2>
            <p class="mb-0">Sebaran pilar Smart City dan SDG berdasarkan publikasi, HKI, dan aktivitas dosen.</p>
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

        <div class="card-admin mb-4">
            <div class="card-body">
                <h5 class="card-title mb-3">Jumlah Dosen per SDG</h5>
                <div class="dss-chart" style="height: 520px">
                    <canvas id="dssPetaSdg" role="img" aria-label="Grafik jumlah dosen per SDG"></canvas>
                </div>
                <p class="small text-muted mt-2">Setiap dosen dihitung sekali per SDG berdasarkan hasil perhitungan terakhir.</p>
                <details class="dss-note">
                    <summary>Lihat rincian jumlah dosen per SDG</summary>
                    <div class="table-responsive mt-2">
                        <table class="data-table">
                            <thead><tr><th>SDG</th><th>Jumlah Dosen</th></tr></thead>
                            <tbody>
                                @foreach ($jumlahPerSdg as $nomor => $jumlah)
                                    <tr><td>SDG {{ $nomor }} · {{ config('sdg.' . $nomor) }}</td><td>{{ $jumlah }}</td></tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </details>
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
                                <th>SDG terkait</th>
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
                                    <td>
                                        @forelse ($item['sdgs'] as $sdg)
                                            <span class="dss-chip">SDG {{ $sdg }} · {{ config('sdg.' . $sdg) }}</span>
                                        @empty
                                            <span class="text-muted">—</span>
                                        @endforelse
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card-admin mt-4">
            <div class="card-body">
                <h5 class="card-title mb-3">Pilar dan SDG per Dosen</h5>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr><th>Dosen</th><th>Pilar Smart City</th><th>SDG</th></tr>
                        </thead>
                        <tbody>
                            @foreach ($dosenPerDimensi as $hasil)
                                @php
                                    $pilar = collect($hasil->kompetensi['dimensi'] ?? [])
                                        ->filter(fn ($item) => ($item['jumlah'] ?? 0) > 0);
                                @endphp
                                <tr>
                                    <td><a class="dss-chip" href="{{ route('admin.dss.hasil.show', $hasil->user_id) }}">{{ $hasil->user?->fullname ?? '—' }}</a></td>
                                    <td>
                                        @forelse ($pilar as $item)
                                            <span class="dss-chip">{{ $item['label'] }}</span>
                                        @empty
                                            —
                                        @endforelse
                                    </td>
                                    <td>
                                        @forelse ($hasil->kompetensi['sdgs'] ?? [] as $sdg)
                                            <span class="dss-chip">SDG {{ $sdg }} · {{ config('sdg.' . $sdg) }}</span>
                                        @empty
                                            —
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
                var css = getComputedStyle(document.documentElement);
                var rgb = (css.getPropertyValue('--adm-primary-rgb') || '76, 141, 201').trim();
                var teks = (css.getPropertyValue('--adm-text-2') || '#475569').trim();
                var garis = (css.getPropertyValue('--adm-border') || '#e2e8f0').trim();
                function buatGrafik(id, data) {
                    return new Chart(document.getElementById(id), {
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
                            plugins: {
                                legend: { display: false },
                                tooltip: data.nama ? {
                                    callbacks: { title: items => data.nama[items[0].dataIndex] }
                                } : {}
                            },
                            scales: {
                                x: { beginAtZero: true, ticks: { precision: 0, color: teks }, grid: { color: garis } },
                                y: { ticks: { color: teks }, grid: { display: false } }
                            }
                        }
                    });
                }

                var charts = [
                    buatGrafik('dssPeta', {{ \Illuminate\Support\Js::from($grafik) }}),
                    buatGrafik('dssPetaSdg', {{ \Illuminate\Support\Js::from($grafikSdg) }})
                ];

                document.addEventListener('adm:appearance-change', function () {
                    var current = getComputedStyle(document.documentElement);
                    var color = current.getPropertyValue('--adm-text-2').trim();
                    var border = current.getPropertyValue('--adm-border').trim();
                    var accent = current.getPropertyValue('--adm-primary-rgb').trim();
                    charts.forEach(function (chart) {
                        var dataset = chart.data.datasets[0];
                        dataset.borderColor = 'rgb(' + accent + ')';
                        dataset.backgroundColor = 'rgba(' + accent + ', 0.75)';
                        chart.options.scales.x.ticks.color = color;
                        chart.options.scales.x.grid.color = border;
                        chart.options.scales.y.ticks.color = color;
                        chart.update('none');
                    });
                });
            })();
        </script>
    @endif

</x-layout.admin>