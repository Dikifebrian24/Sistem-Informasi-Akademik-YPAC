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
                                            <div class="col-md-8">
                                                <label for="mapel" class="form-label">Kelas</label>
                                                <select class="form-control select2" name="id_kelas" id="id_kelas"
                                                        required>
                                                    <option value="">-- Pilih Kelas --</option>
                                                    @foreach($params['kelas'] as $item)
                                                        <option value="{{ $item->id_kelas }}">{{ $item->kd_kelas }}
                                                            - {{ $item->nm_kelas }}</option>
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

                <div class="col-sm-12 col-xl-12" id="hasil_filter_card" style="display: none;">
                    <div class="row" id="hasil_filter">

                    </div>
                </div>

            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>

    @include('dashboard.master.raport.modal')
    @include('dashboard.master.raport.js')

@endsection
