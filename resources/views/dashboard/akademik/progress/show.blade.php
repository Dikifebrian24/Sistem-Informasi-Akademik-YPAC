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
                                                <input type="text" class="form-control" id="mapel" value="{{ $mapel }}" disabled>
                                                <input type="hidden" name="id_mapel" id="id_mapel" value="{{ $id_mapel }}">
                                            </div>

                                            {{-- Nama Siswa --}}
                                            <div class="col-md-4">
                                                <label for="nama_siswa" class="form-label">Nama Siswa</label>
                                                <select class="form-control select2" name="id_siswa" id="id_siswa" required>
                                                    <option value="">-- Pilih Siswa --</option>
                                                    @foreach($siswa as $item)
                                                        <option value="{{ $item->id_siswa }}">{{ $item->nm_siswa }} - {{ $item->nisn }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            {{-- Tombol Submit --}}
                                            <div class="col-md-4 d-flex align-items-end">
                                                <div class="w-100">
                                                    <label class="form-label">&nbsp;</label>
                                                    <button type="submit" class="btn btn-primary w-100">Submit Form</button>
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

            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>

    @include('dashboard.akademik.nilai.js')

@endsection
