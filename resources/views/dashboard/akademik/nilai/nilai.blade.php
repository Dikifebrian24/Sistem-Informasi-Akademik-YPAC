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
                                    <h5>Input Nilai Siswa</h5>
                                </div>
                                <div class="card-body">
                                    <form class="theme-form" id="f_nilai">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="col-form-label pt-0" for="nama_siswa">Nama Siswa</label>
                                            <select class="form-control select2" name="id_siswa" id="nama_siswa">
                                                <option value="">-- Pilih Siswa --</option>
                                                @foreach($siswa as $item)
                                                    <option value="{{ $item->id_siswa }}">{{ $item->nm_siswa }} - {{ $item->nisn }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="col-form-label pt-0" for="exampleInputPassword1">Mata Pelajaran</label>
                                            <input type="text" class="form-control" value="{{ $mapel }}" disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label class="col-form-label pt-0" for="exampleInputPassword1">Kategori Nilai</label>
                                            <select class="form-control select2" name="" id="">
                                                <option value="Harian">Harian</option>
                                                <option value="UTS">UTS</option>
                                                <option value="UAS">UAS</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="col-form-label pt-0" for="exampleInputPassword1">Nilai </label><small>( 1-100 )</small>
                                            <input class="form-control" id="exampleInputPassword1" type="number" placeholder="Masukkan Nilai Siswa" min="1" max="100">
                                        </div>
                                        <div class="mb-3">
                                            <label class="col-form-label pt-0" for="exampleInputPassword1">Keterangan </label>
                                            <textarea class="form-control" name="" id="" cols="30" rows="10"></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="col-form-label pt-0" for="exampleInputPassword1">Lampiran File </label>
                                            <input class="form-control" id="exampleInputPassword1" type="file" placeholder="Masukkan Nilai Siswa">
                                        </div>

                                    </form>
                                </div>
                                <div class="card-footer">
                                    <button class="btn btn-primary">Submit</button>
                                    <button class="btn btn-secondary">Cancel</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>
    @include('dashboard.akademik.kelas_siswa.js')

@endsection
