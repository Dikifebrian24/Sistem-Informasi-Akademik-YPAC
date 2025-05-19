@if(count($data))

    @php
        $avgNilai = $data->avg('nilai');
        $avgNilaiRounded = round($avgNilai, 2);

        // Fungsi simpel untuk memberikan label kualitas nilai
        if ($avgNilai <= 3) {
        $keterangan = 'Perlu banyak latihan, ayo semangat!';
    } elseif ($avgNilai <= 5) {
        $keterangan = 'Mulai terlihat kemajuan, terus coba ya!';
    } elseif ($avgNilai <= 7) {
        $keterangan = 'Bagus, kamu sudah berkembang!';
    } elseif ($avgNilai <= 9) {
        $keterangan = 'Hebat, terus pertahankan prestasimu!';
    } else {
        $keterangan = 'Sempurna! Kamu luar biasa!';
    }
    @endphp


    <div class="card mt-4">
        <div class="card-header">
            <h5>Grafik Perkembangan</h5>
        </div>
        <div class="card-body" style="height: 300px;">
            <div class="mb-0">
                <strong>Rata-rata Nilai:</strong>
                @for ($i = 1; $i <= 5; $i++)
                    @if ($i <= round($avgNilai / 2))
                        <span style="color: gold; font-size: 20px;">&#9733;</span>
                    @else
                        <span style="color: #ddd; font-size: 20px;">&#9733;</span>
                    @endif
                @endfor
                <span style="margin-left: 10px;">({{ $avgNilaiRounded }})</span>
                <div><em><strong>Simpulan:</strong> {{ $keterangan }}</em></div>
            </div>
            <canvas id="progressChart" style="height: 100%; width: 100%;"></canvas>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="card">
            <div class="card-header pb-0">
                <h5>Filter</h5>
            </div>
            <div class="card-body">
                @if(count($data))
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Nilai (Bintang)</th>
                            <th>Keterangan</th>
                            <th>Lampiran</th>
                            <th>Tanggal</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $item)
                            <tr>
                                <td>{{ $item->nilai }}</td>
                                <td>{{ $item->desc_nilai ?? '-' }}</td>
                                <td>
                                    @if($item->lampiran)
                                        <a href="{{ asset('storage/' . $item->lampiran) }}" target="_blank">Lihat File</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($item->tgl_progress)->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-warning">Belum ada data nilai untuk siswa ini.</div>
                @endif
            </div>
        </div>
    </div>

    @php
        $dataSorted = $data->sortBy('tgl_progress');
    @endphp

{{--    <div class="card mt-4">--}}
{{--        <div class="card-header">--}}
{{--            <h5>Grafik Perkembangan</h5>--}}
{{--        </div>--}}
{{--        <div class="card-body" style="height: 300px;">--}}
{{--            <canvas id="progressChart" style="height: 100%; width: 100%;"></canvas>--}}
{{--        </div>--}}
{{--    </div>--}}


    {{-- 1. Include Chart.js dulu --}}

    {{-- 2. Baru jalankan script Chart-nya --}}
    <script>
        function renderChart() {
            const labels = {!! json_encode($data->pluck('tgl_progress')->map(fn($tgl) => \Carbon\Carbon::parse($tgl)->format('d M'))) !!};
            const nilai = {!! json_encode($data->pluck('nilai')) !!};

            const ctx = document.getElementById('progressChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Progress Harian',
                        data: nilai,
                        borderColor: '#4e73df',
                        backgroundColor: 'rgba(78, 115, 223, 0.1)',
                        tension: 0.3,
                        fill: true,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#4e73df'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            min: 0,
                            max: 10,
                            ticks: {
                                stepSize: 1
                            },
                            title: {
                                display: true,
                                text: 'Nilai'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                            }
                        }
                    }
                }
            });
        }
    </script>
@else
    <div class="alert alert-warning">Belum ada data nilai untuk siswa ini.</div>
@endif
