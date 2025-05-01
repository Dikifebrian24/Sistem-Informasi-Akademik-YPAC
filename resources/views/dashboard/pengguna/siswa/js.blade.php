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

        $('#openModalBtn').on("click", function(e) {
            $(".js-example-basic-single").select2();
            e.preventDefault()
            $.ajax({
                url: "{{ route('siswa/add') }}",
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $('#kelainan').empty();
                    $('#kelainan').append('<option value="">-- Pilih Kategori Kelainan --</option>'); // Tambah placeholder

                    $.each(data['kelainan'], function(i, value) {
                        $('#role_level').append('<option value="' + value.id + '">' + value.nm_kelainan + '</option>');
                    });

                    $('#adminModalAdd').modal('show');
                }
            });
        });



        $(document).ready(function () {
            $('#data').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('siswa/data') }}',
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
                    { data: 'nama', name: 'nama' },
                    { data: 'nisn', name: 'nisn' },
                    { data: 'nm_kelainan', name: 'nm_kelainan' },
                    { data: 'no_hp', name: 'no_hp' },
                    { data: 'level', name: 'level', className: 'text-center' },
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


        $('#saveAdmin').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: '{{ route("admin/store") }}',
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
                        $('#adminModalAdd').modal('hide');
                        $('#saveKelas')[0].reset();
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

        $(document).on('click', '.edit', function() {
            let userId = $(this).data('id');

            $.ajax({
                url: 'admin/' + userId + '/edit',
                type: 'GET',
                success: function(user) {
                    $('#first_name').val(user.first_name);
                    $('#last_name').val(user.last_name);
                    $('#email').val(user.email);
                    $('#role_level').val(user.level);
                    $('#password').val('');

                    $('#adminModalAdd').modal('show');

                    $('#saveKelas').attr('data-id', user.id);
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
                        url: 'admin/delete/' + id,
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

        $('.show_confirm').click(function(e) {
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
