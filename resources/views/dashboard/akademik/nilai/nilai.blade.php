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
                                    <h5>Input Nilai {{ $mapel }}</h5>
                                </div>
                                <form class="theme-form" id="f_nilai" enctype="multipart/form-data">
                                    @csrf
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="col-form-label pt-0" for="id_siswa">Nama Siswa</label>
                                            <select class="form-control select2" name="id_siswa" id="id_siswa" required>
                                                <option value="">-- Pilih Siswa --</option>
                                                @foreach($siswa as $item)
                                                    <option value="{{ $item->id_siswa }}">{{ $item->nm_siswa }} - {{ $item->nisn }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="col-form-label pt-0">Mata Pelajaran</label>
                                            <input type="text" class="form-control" value="{{ $mapel }}" disabled>
                                            <input type="hidden" class="form-control" name="id_mapel" value="{{ $id_mapel }}">
                                        </div>

                                        <div class="mb-3">
                                            <label class="col-form-label pt-0" for="kategori_nilai">Kategori Nilai</label>
                                            <select class="form-control select2" name="kategori_nilai" id="kategori_nilai" required>
                                                <option value="Harian">Harian</option>
                                                <option value="UTS">UTS</option>
                                                <option value="UAS">UAS</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="col-form-label pt-0" for="jadwal">Pertemuan</label>
                                            <select class="form-control select2" name="jadwal" id="jadwal" required>
                                                <option value="Harian">-- Pilih Materi --</option>
                                                @foreach($jadwal as $data)
                                                    <option value="{{ $data->id }}">{{ $data->materi }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="col-form-label pt-0" for="nilai_value">Nilai</label>
                                            <small>( 1-100 )</small>
                                            <input class="form-control" id="nilai_value" name="nilai_value" type="number" min="1" max="100" placeholder="Masukkan Nilai Siswa" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="col-form-label pt-0" for="desc_nilai">Keterangan</label>
                                            <textarea class="form-control" name="desc_nilai" id="desc_nilai" cols="30" rows="3"></textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label class="col-form-label pt-0" for="lampiran">Lampiran File</label>
                                            <input class="form-control" type="file" name="lampiran" id="lampiran" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                        </div>
                                    </div>

                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary">Simpan Nilai</button>
                                    </div>
                                </form>


                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>
    @include('dashboard.akademik.nilai.js')

@endsection
