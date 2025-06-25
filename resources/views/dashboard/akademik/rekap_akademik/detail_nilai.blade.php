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
                        <h3>Detail Nilai</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Applications</a></li>
                            <li class="breadcrumb-item">Data Akademik</li>
                            <li class="breadcrumb-item active">Detail Nilai</li>
                        </ol>
                    </div>
                </div>
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
                                    <th>Nama Materi</th>
                                    <th>Nilai</th>
                                    <th>Lampiran</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($nilai as $index => $data)
                                    <tr>
                                        <td style="text-align: center">{{ $index + 1 }}</td>
                                        <td>{{ $data->kategori_nilai }}</td>
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
    @include('dashboard.akademik.rekap_akademik.modal')
    @include('dashboard.akademik.rekap_akademik.js')

@endsection
