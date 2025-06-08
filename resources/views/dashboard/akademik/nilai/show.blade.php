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
            <div class="row">
                <div class="col-sm-12 col-xl-12">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h5>Filter</h5>
                                </div>
                                <div class="card-body">
                                    <form class="needs-validation" id="f_filter" novalidate>
                                        @csrf
                                        <div class="row g-3">
                                            {{-- Mata Pelajaran --}}
                                            <div class="col-md-4">
                                                <label for="mapel" class="form-label">Mata Pelajaran</label>
                                                <input type="text" class="form-control" id="mapel" value="{{ $mapel }}"
                                                       disabled>
                                                <input type="hidden" name="id_mapel" id="id_mapel"
                                                       value="{{ $id_mapel }}">
                                            </div>

                                            {{-- Nama Siswa --}}
                                            <div class="col-md-4">
                                                <label for="nama_siswa" class="form-label">Nama Siswa</label>
                                                <select class="form-control select2" name="id_siswa" id="id_siswa"
                                                        required>
                                                    <option value="">-- Pilih Siswa --</option>
                                                    @foreach($siswa as $item)
                                                        <option value="{{ $item->id_siswa }}">{{ $item->nm_siswa }}
                                                            - {{ $item->nisn }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            {{-- Tombol Submit --}}
                                            <div class="col-md-4 d-flex align-items-end">
                                                <div class="w-100">
                                                    <label class="form-label">&nbsp;</label>
                                                    <button type="submit" class="btn btn-primary w-100">Submit Form
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-sm-12 col-xl-12" id="hasil_nilai_card" style="display: none;">
                    <div class="row" id="hasil_nilai">

                    </div>
                </div>

                <div class="col-sm-12 col-xl-12" id="hasil_nilai_card">
                    <div class="row" id="hasil_nilai">
                        <div class="col-sm-12">
                            <div class="card">
                                {{--                                <div class="card-header pb-0">--}}
                                {{--                                </div>--}}

                                <style>
                                    .dropdown-item.dropdown-hover:hover {
                                        background-color: #4f4b4b; /* Warna merah Bootstrap (danger) */
                                        color: white;
                                    }
                                </style>
                                <div class="card-header">
                                    <h5>Nilai Siswa</h5><br>

                                    <button class="btn add" style="background-color: blue; color: white" type="button"
                                            id="openModalBtn">Add Data Siswa
                                    </button>
                                    {{--                                    <button class="btn btn-danger import" type="button" id="importBtn">Import</button>--}}
                                    <button class="btn btn-danger dropdown-toggle" type="button" id="importDropdown"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                        Import
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="importDropdown">
                                        <li><a class="dropdown-item" href="#" id="generateFileBtn">Generate File</a>
                                        </li>
                                        <li><a class="dropdown-item" href="#" id="importFileBtn">Import File</a></li>
                                    </ul>
                                    <button class="btn export" type="button"
                                            style="background-color: green; color: white" id="exportBtn">Export
                                    </button>
                                </div>
                                <div class="card-body">
                                    <table id="table-nilai" class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>Nama Siswa</th>
                                            <th>Materi</th>
                                            <th>Nilai</th>
                                            <th>Tanggal</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>

    @include('dashboard.akademik.nilai.modal')
    @include('dashboard.akademik.nilai.js')

@endsection
