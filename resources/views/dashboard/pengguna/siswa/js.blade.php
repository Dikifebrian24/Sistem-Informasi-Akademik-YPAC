@pushOnce('js')
    <script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/js/form-validation-custom.js') }}"></script>
    <script src="{{ asset('assets/js/tooltip-init.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        // document.addEventListener('DOMContentLoaded', function () {
        //     const modalElement = document.getElementById('adminModal');
        //     const openModalBtn = document.getElementById('openModalBtn');
        //
        //     // Create a Bootstrap modal instance
        //     const modal = new bootstrap.Modal(modalElement);
        //
        //     // Show modal on button click
        //     openModalBtn.addEventListener('click', function () {
        //         modal.show();
        //     });
        // });

        $('#openModalBtn').on("click", function (e) {
            $('#siswaModalAdd').modal('show');
        });


        $(document).ready(function () {
            $('#data').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ url('master/siswa/data') }}',
                columns: [
                    {
                        data: null,
                        name: 'id',
                        className: 'text-center',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {data: 'nama', name: 'nama'},
                    {data: 'nisn', name: 'nisn'},
                    {data: 'nm_kelainan', name: 'nm_kelainan'},
                    {data: 'no_hp', name: 'no_hp'},
                    {data: 'angkatan', name: 'angkatan', className: 'text-center'},
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

        $('#siswaModalAdd').on('hidden.bs.modal', function () {
            $('#saveSiswa')[0].reset();
            $('#siswa_id').remove();
            $('#addSiswaLabel').text('Add Siswa');
            $('#saveSiswa').attr('action', '/siswa/store'); // Kembali ke mode add
        });


        $('#saveSiswa').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                url: '{{ route("siswa/store") }}',
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
                        $('#siswaModalAdd').modal('hide');
                        $('#saveSuswa')[0].reset();
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
                url: `/siswa/edit/${id}`,
                type: 'GET',
                success: function (response) {
                    if (response.success) {
                        let user = response.data;
                        let siswa = user.siswa;

                        // console.log(user)



                        // Data user
                        $('#edit_siswa_id').val(user.id_user); // atau id siswa yang sesuai
                        $('#edit_first_name').val(user.first_name);
                        $('#edit_last_name').val(user.last_name);
                        $('#edit_email').val(user.email);
                        $('#edit_nik').val(user.nik);
                        $('#edit_nisn').val(user.nisn);
                        $('#edit_jenkel').val(user.jenkel);
                        $('#edit_tmpt_lahir').val(user.tmpt_lahir);
                        $('#edit_tgl_lahir').val(user.tgl_lahir);
                        $('#edit_agama').val(user.agama);
                        $('#edit_almt_rumah').val(user.almt_rumah);
                        $('#edit_angkatan').val(user.angkatan);
                        $('#edit_nm_wali').val(user.nm_wali);
                        $('#edit_tgl_lahir_wali').val(user.tgl_lahir_wali);
                        $('#edit_no_telp_wali').val(user.no_telp_wali);
                        $('#edit_no_hp').val(user.no_hp);
                        $('#edit_kelainan').val(user.id_kelainan);

                        $('#siswaModalEdit').modal('show');
                    }
                }
            });
        });


        $('#editSiswa').on('submit', function (e) {
            e.preventDefault();

            let id = $('#edit_siswa_id').val();

            $.ajax({
                url: `/siswa/update/${id}`,
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
                        $('#siswaModalEdit').modal('hide');
                        $('#editSiswa')[0].reset();
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
                        url: 'siswa/delete/' + id,
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

        // Load select role
        {{--$.ajax({--}}
        {{--    url: '{{ route("admin/level") }}',--}}
        {{--    type: 'GET',--}}
        {{--    success: function(data) {--}}
        {{--        $('#role_level').empty().append('<option value="">-- Pilih Role --</option>');--}}
        {{--        $.each(data.role_level, function(i, role) {--}}
        {{--            $('#role_level').append(`<option value="${role.value}">${role.label}</option>`);--}}
        {{--        });--}}

        {{--        // Kalau sedang edit, set value di sini setelah semua option dimasukkan--}}
        {{--        if (editMode) {--}}
        {{--            $('#role_level').val(selectedRole).trigger('change');--}}
        {{--        }--}}
        {{--    }--}}
        {{--});--}}
        $('#importBtn').on("click", function (e) {
            e.preventDefault()
            $('#importModal').modal('show');
        });

        $('#exportBtn').on("click", function (e) {
            e.preventDefault();
            window.location.href = "/master/siswa/export";
        });

        $('#template_download').on('click', function () {
            window.location.href = "{{ route('download.template_siswa') }}";
        });

        $('#f_import').on('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: "{{ route('siswa/import') }}",
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
        toastr.success('{{ session('success') }}', 'Wohoooo!');
        @else
        toastr.error('{{ session('error') }}', 'Whoops!');
        @endif
    </script>
@endPushOnce
