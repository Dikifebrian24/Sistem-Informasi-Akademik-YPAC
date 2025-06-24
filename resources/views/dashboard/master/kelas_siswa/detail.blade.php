@extends('layouts.app')
@section('content')
    @pushOnce('css')
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/sweetalert2.css') }}">
        <!-- Select2 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

    @endPushOnce
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <h3>{{ $params['title'] }}</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Applications</a></li>
                            <li class="breadcrumb-item">Data Master</li>
                            <li class="breadcrumb-item active">{{ $params['title'] }}</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <div class="container">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Form Pembagian Kelas</h5>
                        <hr>
                    </div>
                    <div class="card-body">
                        <form id="f_siswaKelas">
                            @csrf
                            <div class="mb-3">
                                <label class="col-form-label pt-0" for="exampleInputEmail1">Kelas</label>
                                <input class="form-control" id="exampleInputEmail1" type="text"
                                       value="{{ $params['kelas']->nm_kelas }}" disabled>
                                <input class="form-control" id="id_kelas" name="id_kelas" type="hidden"
                                       value="{{ $params['kelas']->id_kelas }}">
                            </div>
                            <div class="mb-3">
                                <label class="col-form-label pt-0" for="siswaSelect">Nama Siswa</label>
{{--                                <select class="form-control" id="siswaSelect" name="siswa[]" multiple>--}}
{{--                                    @foreach($params['siswa'] as $s)--}}
{{--                                        <option value="{{ $s->id_siswa }}"--}}
{{--                                            {{ in_array($s->id_siswa, $params['assigned']) ? 'disabled selected' : '' }}>--}}
{{--                                            {{ $s->nm_siswa }}--}}
{{--                                        </option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
                                <select class="form-control" id="siswaSelect" name="siswa[]" multiple>
                                    @foreach($params['siswa'] as $s)
                                        <option value="{{ $s->id_siswa }}">{{ $s->nm_siswa }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="pull-right">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="button" class="btn btn-secondary">Cancel</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @pushOnce('js')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            $(document).ready(function () {
                $('#siswaSelect').select2({
                    placeholder: "Pilih siswa",
                    allowClear: true
                });
            });

            $('#f_siswaKelas').on('submit', function (e) {
                e.preventDefault();
                let formData = $(this).serialize();

                $.ajax({
                    url: '{{ route("kelas_siswa/store") }}',
                    type: 'POST',
                    data: formData,
                    success: function (response) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Siswa berhasil ditambahkan ke kelas.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                    },
                    error: function (xhr) {
                        Swal.fire({
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat menyimpan data.',
                            icon: 'error',
                            confirmButtonText: 'Coba Lagi'
                        });
                    }
                });
            });
        </script>
    @endPushOnce

@endsection
