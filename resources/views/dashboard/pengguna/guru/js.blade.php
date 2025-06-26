@pushOnce('js')
    <script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/js/form-validation-custom.js') }}"></script>
    <script src="{{ asset('assets/js/tooltip-init.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                ajax: "{{ route('guru/data') }}",
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

        $('#saveGuru').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                url: '{{ route("guru/store") }}',
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
                        $('#guruModalAdd').modal('hide');
                        $('#saveGuru')[0].reset();
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

        $('#exportGuruBtn').on("click", function (e) {
            e.preventDefault();
            window.location.href = "/master/guru/export";
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

        $('#editGuru').on('submit', function (e) {
            e.preventDefault();

            let id = $('#edit_id_guru').val();

            $.ajax({
                url: `/guru/update/${id}`,
                type: 'PUT',
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
                        $('#guruModalEdit').modal('hide');
                        $('#editGuru')[0].reset();
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

                        $('#edit_id_guru').val(user.id_guru); // atau id siswa yang sesuai
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

        $('#openModalBtn').on("click", function (e) {
            $(".js-example-basic-single").select2();
            e.preventDefault()
            $.ajax({
                url: "{{ route('guru/add') }}",
                type: "GET",
                dataType: "json",
                success: function (data) {
                    // $('#role_level').empty(); // Kosongkan dulu
                    // $('#role_level').append('<option value="">-- Pilih Role --</option>'); // Tambah placeholder

                    // $.each(data['role_level'], function(i, value) {
                    //     $('#role_level').append('<option value="' + value.id + '">' + value.name + '</option>');
                    // });

                    $('#guruModalAdd').modal('show');
                }
            });
        });

        $('#importBtn').on("click", function (e) {
            e.preventDefault()
            $('#importModal').modal('show');
        });

        $('#template_download').on('click', function () {
            window.location.href = "{{ route('download.template') }}";
        });

        $('#f_import').on('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: "{{ route('guru/import') }}",
                method: "POST",
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                beforeSend: function () {
                    // Optional: tampilkan loading indicator
                },
                success: function (response) {
                    alert(response.message); // tampilkan pesan sukses
                    $('#import_data').val(''); // reset file input

                    $('#importModal').modal('hide');
                    $('#data').DataTable().ajax.reload();
                },
                error: function (xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        alert("Error: " + xhr.responseJSON.message);
                    } else {
                        alert("Terjadi kesalahan saat upload.");
                    }
                }
            });
        });
    </script>
@endPushOnce
