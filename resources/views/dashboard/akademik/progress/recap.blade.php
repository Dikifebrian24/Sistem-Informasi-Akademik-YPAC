@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card mb-4">
            <div class="card-header">
                <h5>Rekap Data Progress Semua Siswa</h5>
            </div>
            <div class="card-body">

                @if($data->count())
                    {{-- Rata-rata nilai --}}
                    @php
                        $avgNilai = round($data->avg('nilai'), 2);
                    @endphp

                    <div class="mb-3">
                        <strong>Rata-rata Nilai Keseluruhan: </strong> {{ $avgNilai }} / 10
                    </div>

                    <table id="rekapTable" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Nama Siswa</th>
                            <th>Mata Pelajaran</th>
                            <th>Nilai (Bintang)</th>
                            <th>Keterangan</th>
                            <th>Lampiran</th>
                            <th>Tanggal Progress</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $item)
                            <tr>
                                <td>{{ $item->nama_siswa ?? '-' }}</td>
                                <td>{{ $item->nama_mapel ?? '-' }}</td>
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
                    <div class="alert alert-warning">Belum ada data progress.</div>
                @endif

            </div>
        </div>
    </div>

    {{-- Include jQuery dan DataTables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#rekapTable').DataTable({
                "order": [[ 5, "desc" ]], // urutkan tanggal terbaru
                "language": {
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "zeroRecords": "Data tidak ditemukan",
                    "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                    "infoEmpty": "Tidak ada data",
                    "infoFiltered": "(difilter dari total _MAX_ data)",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                }
            });
        });
    </script>
@endsection
