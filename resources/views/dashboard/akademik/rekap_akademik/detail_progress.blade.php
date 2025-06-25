@extends('layouts.app')
@section('content')
    @pushOnce('css')
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/sweetalert2.css') }}">
    @endPushOnce
    <style type="text/css">
        #data tr {
            text-align: center;
        }
    </style>
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <h3>Detail Progress Siswa</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Applications</a></li>
                            <li class="breadcrumb-item">Data Akademik</li>
                            <li class="breadcrumb-item active">Detail Progress Siswa</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

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

        <div class="container-fluid">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display datatables table table-bordered" id="nilaiTable">
                                <thead>
                                <tr style="text-align: center">
                                    <th style="width: 55px">No</th>
                                    <th>Deskripsi</th>
                                    <th>Value (1-10)</th>
                                    <th>Lampiran Guru</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($nilai as $index => $data)
                                    <tr>
                                        <td style="text-align: center">{{ $index + 1 }}</td>
                                        <td>{{ $data->desc_nilai }}</td>
                                        <td style="text-align: center">{{ $data->nilai ?? '-' }}</td>
                                        <td style="text-align: center">
                                            @if ($data->lampiran)
                                                <a href="{{ asset('storage/' . $data->lampiran) }}" target="_blank">Download</a>
                                            @else
                                                Tidak ada lampiran
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', renderChart);

        function renderChart() {
            const labels = {!! json_encode($data_tgl->map(fn($tgl) => \Carbon\Carbon::parse($tgl)->format('d M'))) !!};
            const nilai = {!! json_encode($data_nilai) !!};

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
                                text: 'Tanggal'
                            }
                        }
                    }
                }
            });
        }
    </script>
    @include('dashboard.akademik.rekap_akademik.modal')
    @include('dashboard.akademik.rekap_akademik.js')

@endsection
