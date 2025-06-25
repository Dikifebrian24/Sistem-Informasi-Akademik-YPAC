@pushOnce('js')
    <script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/js/form-validation-custom.js') }}"></script>
    <script src="{{ asset('assets/js/tooltip-init.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
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

        $('#exportKepsekBtn').on("click", function (e) {
            e.preventDefault();
            window.location.href = "/master/kepsek/export";
        });
    </script>
    <script>
        @if (session()->has('success'))
        toastr.success('{{ session('success') }}', 'Wohoooo!');
        @else
        toastr.error('{{ session('error') }}', 'Whoops!');
        @endif
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#data').DataTable({
                aLengthMenu: [
                    [5, 10, 25, 50, 100, -1],
                    [5, 10, 25, 50, 100, "All"]
                ],
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('kepsek/data') }}",
                columns: [{
                    "data": null,
                    "sortable": false,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                    {
                        data: 'nip',
                        name: 'nip'
                    },
                    {
                        data: 'nm_guru',
                        name: 'nm_guru'
                    },
                    {
                        data: 'jenkel',
                        name: 'jenkel'
                    },
                    {
                        data: 'no_hp',
                        name: 'no_hp'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                ]
            });
        });

        $(document).on('click', '.edit', function () {
            let id = $(this).data('id');

            $.ajax({
                url: `/guru/edit/${id}`,
                type: 'GET',
                success: function (response) {
                    if (response.success) {
                        let user = response.data;
                        let siswa = user.guru;

                        console.log(user)

                        $('#edit_id_user').val(user.id_user); // atau id siswa yang sesuai
                        $('#edit_first_name').val(user.first_name);
                        $('#edit_last_name').val(user.last_name);
                        $('#edit_nip').val(user.nip);
                        $('#edit_nik').val(user.nik);
                        $('#edit_email').val(user.email);
                        $('#edit_npwp').val(user.npwp);
                        $('#edit_jenkel').val(user.jenkel);
                        $('#edit_tmpt_lahir').val(user.tmpt_lahir);
                        $('#edit_tgl_lahir').val(user.tgl_lahir);
                        $('#edit_agama').val(user.agama);
                        $('#edit_almt_jalan').val(user.almt_jalan);
                        $('#edit_no_hp').val(user.no_hp);
                        $('#edit_level_guru').val(user.edit_level_guru);

                        $('#guruModalEdit').modal('show');
                    }
                }
            });
        });

        $(document).on('click', '.delete', function () {
            let id = $(this).data('id');

            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: "Data ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'guru/delete/' + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });

                            // Reload DataTable
                            $('#data').DataTable().ajax.reload();
                        },
                        error: function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan saat menghapus data.'
                            });
                        }
                    });
                }
            });
        });

        $('#saveGuru').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: '{{ route("kepsek/store") }}',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });

                        $('#data').DataTable().ajax.reload();
                        $('#guruModalAdd').modal('hide');
                        $('#saveGuru')[0].reset();
                    }
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    let errorMsg = '';

                    $.each(errors, function(key, value) {
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

        $('#openModalBtn').on("click", function(e) {
            $(".js-example-basic-single").select2();
            e.preventDefault()
            $.ajax({
                url: "{{ route('guru/add') }}",
                type: "GET",
                dataType: "json",
                success: function(data) {


                    $('#guruModalAdd').modal('show');
                }
            });
        });
    </script>
@endPushOnce
