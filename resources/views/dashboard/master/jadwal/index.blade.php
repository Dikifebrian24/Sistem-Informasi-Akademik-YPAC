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
                            <li class="breadcrumb-item">Data Master</li>
                            <li class="breadcrumb-item active">{{ $params['title'] }}</li>
                        </ol>
                    </div>
                    <div class="col-sm-6">
                        <!-- Bookmark Start-->
                        <div class="bookmark">
{{--                            <ul>--}}
{{--                                <li><a href="javascript:void(0)" data-container="body" data-bs-toggle="popover"--}}
{{--                                       data-placement="top"--}}
{{--                                       title="" data-original-title="Tables"><i data-feather="inbox"></i></a></li>--}}
{{--                                <li><a href="javascript:void(0)" data-container="body" data-bs-toggle="popover"--}}
{{--                                       data-placement="top"--}}
{{--                                       title="" data-original-title="Chat"><i data-feather="message-square"></i></a>--}}
{{--                                </li>--}}
{{--                                <li><a href="javascript:void(0)" data-container="body" data-bs-toggle="popover"--}}
{{--                                       data-placement="top"--}}
{{--                                       title="" data-original-title="Icons"><i data-feather="command"></i></a></li>--}}
{{--                                <li><a href="javascript:void(0)" data-container="body" data-bs-toggle="popover"--}}
{{--                                       data-placement="top"--}}
{{--                                       title="" data-original-title="Learning"><i data-feather="layers"></i></a></li>--}}
{{--                                <li><a href="javascript:void(0)"><i class="bookmark-search" data-feather="star"></i></a>--}}
{{--                                    <form class="form-inline search-form">--}}
{{--                                        <div class="form-group form-control-search">--}}
{{--                                            <input type="text" placeholder="Search..">--}}
{{--                                        </div>--}}
{{--                                    </form>--}}
{{--                                </li>--}}
{{--                            </ul>--}}
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
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display datatables table table-bordered" id="data">
                                <thead>
                                <tr style="text-align: center">
                                    <th style="width: 55px">No</th>
                                    <th>Kelas</th>
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
    @include('dashboard.master.jadwal.modal')
    @include('dashboard.master.jadwal.js')

@endsection
