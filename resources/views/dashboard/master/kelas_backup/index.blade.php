@extends('layouts.app')
@section('content')
    @pushOnce('css')
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/sweetalert2.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
    @endPushOnce
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <h3>Kelas</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Applications</a></li>
                            <li class="breadcrumb-item">Data Master</li>
                            <li class="breadcrumb-item active">Kelas</li>
                        </ol>
                    </div>
                    <div class="col-sm-6">

                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <button class="btn btn-primary add" type="button">Add Kelas</button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display table table-bordered" id="data">
                                <thead>
                                <tr style="text-align: center">
                                    <th style="width: 55px">No</th>
                                    <th>Kode Kelas</th>
                                    <th>Nama Kelas</th>
                                    <th>Wali Kelas</th>
                                    <th>Status</th>
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
    @pushOnce('js')
        @include('dashboard.master.kelas_backup.add')
        @include('dashboard.master.kelas_backup.edit')
        @include('dashboard.master.kelas_backup.detail')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
        <script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
        <script src="{{ asset('assets/js/form-validation-custom.js') }}"></script>
        <script src="{{ asset('assets/js/tooltip-init.js') }}"></script>
        <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script type="text/javascript">

            $('.show_confirm').click(function (e) {
                var form = $(this).closest("form");
                e.preventDefault();
                swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this imaginary file!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                    .then((willDelete) => {
                        if (willDelete) {
                            swal("Poof! Your imaginary file has been deleted!", {
                                icon: "success",
                                // timer: 3000
                            });
                            form.submit();
                        } else {
                            swal("Your imaginary file is safe!", {
                                icon: "info"
                            });
                        }
                    })
            });
        </script>
        <script>
            @if (session()->has('success'))
            toastr.success(
                '{{ session('success') }}',
                'Wohoooo!', {
                    showDuration: 300,
                    hideDuration: 900,
                    timeOut: 900,
                    closeButton: true,
                    newestOnTop: true,
                    progressBar: true,
                }
            );
            @endif
        </script>
        <script>
            $(document).ready(function () {
                $('.add').on("click", function (e) {
                    $(".js-example-basic-single").select2();
                    e.preventDefault()
                    $.ajax({
                        url: "{{ route('kelas_backup/add') }}",
                        type: "GET",
                        dataType: "json",
                        success: function (data) {
                            $.each(data['wali_kelas'], function (i, value) {
                                $('#wali_kelas').append('<option value=' + value.id_guru + '>' + value.nm_guru +
                                    '</option>');
                            });

                            $('#addKelas').modal('show');
                        }
                    });
                });

                $('#saveKelas').on('submit', function (e) {
                    e.preventDefault();

                    console.log($(this).serialize());

                    $.ajax({
                        url: '{{ route("kelas_backup/save") }}',
                        type: 'POST',
                        data: $(this).serialize(),
                        success: function (response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sukses!',
                                    text: response.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                });

                                $('#data').DataTable().ajax.reload();
                                $('#adminModalAdd').modal('hide');
                                $('#saveKelas')[0].reset();
                            }
                        },
                        error: function (xhr) {
                            let errors = xhr.responseJSON.errors;
                            let errorMsg = '';

                            $.each(errors, function (key, value) {
                                errorMsg += value + '<br>';
                            });

                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                html: errorMsg
                            });
                        }
                    });
                });

                {{--$('#save').on("click", function (e) {--}}
                {{--    console.log($('#saveKelas').serialize());--}}
                {{--    e.preventDefault()--}}
                {{--    $.ajax({--}}
                {{--        type: "POST",--}}
                {{--        data: $('#saveKelas').serialize(),--}}
                {{--        url: "{{ route('kelas_backup/save') }}",--}}
                {{--        dataType: "json",--}}
                {{--        headers: {--}}
                {{--            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
                {{--        },--}}
                {{--        success: function (data) {--}}
                {{--            toastr.success(--}}
                {{--                data.success,--}}
                {{--                'Wohoooo!', {--}}
                {{--                    showDuration: 300,--}}
                {{--                    hideDuration: 900,--}}
                {{--                    timeOut: 900,--}}
                {{--                    closeButton: true,--}}
                {{--                    newestOnTop: true,--}}
                {{--                    progressBar: true,--}}
                {{--                    onHidden: function () {--}}
                {{--                        window.location.reload();--}}
                {{--                    }--}}
                {{--                }--}}
                {{--            );--}}
                {{--        },--}}
                {{--        error: function (data) {--}}
                {{--            var errors = data.responseJSON.errors;--}}
                {{--            var errorsHtml = '';--}}
                {{--            $.each(errors, function (key, value) {--}}
                {{--                errorsHtml += '- ' + value[0] + '<br>';--}}
                {{--            });--}}
                {{--            toastr.error(errorsHtml, 'Whoops!');--}}
                {{--        }--}}
                {{--    });--}}
                {{--});--}}

                $('.edit').on("click", function (e) {
                    $(".js-example-basic-single").select2();
                    e.preventDefault()
                    var id = $(this).attr('data-bs-id');
                    $.ajax({
                        url: "/master/kelas/edit/" + id,
                        type: "GET",
                        dataType: "json",
                        success: function (data) {
                            $('#id_kelas').val(data['item'][0].id_kelas);
                            $('#kd_kelas').val(data['item'][0].kd_kelas);
                            ;
                            $('#nm_kelas').val(data['item'][0].nm_kelas);
                            ;
                            $.each(data['wali_kelas'], function (key, value) {
                                $('#nip').append('<option value=' + value.nip + '>' + value.nm_guru +
                                    '</option>');
                                if (data['item'][0].nip == value.nip) {
                                    $('#nip').append('<option value="' + value.nip +
                                        '" selected>' + value.nm_guru + '</option>').trigger('change');
                                }
                            });

                            $('input[id="stts_kelas"][value="' + data['item'][0].stts_kelas + '"]').prop('checked',
                                true);
                            $('#editKelas').modal('show');
                        }
                    });
                });

                $('#update').on("click", function (e) {
                    e.preventDefault()
                    var id_kelas = $("#id_kelas").val();
                    $.ajax({
                        type: "PUT",
                        data: $('#dataKelas').serialize(),
                        url: '/master/kelas/update/' + id_kelas,
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (data) {
                            toastr.success(
                                data.success,
                                'Wohoooo!', {
                                    showDuration: 300,
                                    hideDuration: 900,
                                    timeOut: 900,
                                    closeButton: true,
                                    newestOnTop: true,
                                    progressBar: true,
                                    onHidden: function () {
                                        window.location.reload();
                                    }
                                }
                            );
                        },
                        error: function (data) {
                            var errors = data.responseJSON.errors;
                            var errorsHtml = '';
                            $.each(errors, function (key, value) {
                                errorsHtml += '- ' + value[0] + '<br>';
                            });
                            toastr.error(errorsHtml, 'Whoops!');
                        }
                    });
                });

                $('.detail').on("click", function (e) {
                    e.preventDefault();
                    var id = $(this).attr('data-bs-id');
                    $.ajax({
                        url: "kelas/detail/" + id,
                        type: "GET",
                        dataType: "JSON",
                        success: function (data) {
                            $('#a').html(data[0].nm_guru);
                            $('#c').html(data[0].kd_kelas);
                            $('#d').html(data[0].nm_kelas);
                            $('#i').html(data[0].nm_gedung);
                            if (data[0].stts_kelas == 'Active')
                                $('#j').html("<span class='span badge rounded badge-success'>Active</span>");
                            else
                                $('#j').html("<span class='span badge rounded badge-danger'>Non Active</span>");
                            $(".avatar").html("");
                            $('.avatar').append('<img class="img-100 b-r-8" alt="" src="' + data[0].foto + '">')
                            $('#detailKelas').modal('show');
                        }
                    });
                });
            });
        </script>
    @endPushOnce
@endsection
