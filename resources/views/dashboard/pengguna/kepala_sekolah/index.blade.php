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
                        <h3>{{ $params['title'] }}</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Applications</a></li>
                            <li class="breadcrumb-item">Data Pengguna</li>
                            <li class="breadcrumb-item active">{{ $params['title'] }}</li>
                        </ol>
                    </div>
                    <div class="col-sm-6">
                        <!-- Bookmark Start-->
                        <div class="bookmark">

                        </div>
                        <!-- Bookmark Ends-->
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <button class="btn btn-primary add" type="button" id="openModalBtn">Add {{ $params['title'] }}</button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display datatables table table-bordered" id="data">
                                <thead>
                                <tr style="text-align: center">
                                    <th style="width: 55px">No</th>
                                    <th>NIP</th>
                                    <th>Nama Kepala Sekolah</th>
                                    <th>Jenis Kelamin</th>
                                    <th>No Telepon</th>
                                    <th style="width: 120px;">Action</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('dashboard.pengguna.kepala_sekolah.modal')
    @include('dashboard.pengguna.kepala_sekolah.js')
@endsection
