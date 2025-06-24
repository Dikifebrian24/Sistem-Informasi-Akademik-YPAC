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
                url: "{{ route('mapel/add') }}",
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $('#kelas').empty(); // Kosongkan dulu
                    $('#kelas').append('<option value="">-- Pilih Kelas --</option>'); // Tambah placeholder

                    $.each(data['kelas'], function(i, value) {
                        $('#kelas').append('<option value="' + value.id_kelas + '">' + value.nm_kelas + '</option>');
                    });

                    $('#mapelModalAdd').modal('show');
                }
            });
        });



        $(document).ready(function () {
            var table =  $('#data').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('mapel/data') }}',
                    data: function (d) {
                        d.kelas = $('#filterKelas').val();
                    }
                },
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
                    { data: 'nm_mapel', name: 'nm_mapel' },
                    { data: 'nm_kelas', name: 'nm_kelas' },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                ]
            });

            $('#filterKelas').change(function () {
                console.log('jaklsdjlkasd')
                table.ajax.reload();
            });
        });



        $('#saveMapel').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: '{{ route("mapel/store") }}',
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
                        $('#mapelModalAdd').modal('hide');
                        $('#saveMapel')[0].reset();
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

        $(document).on('click', '.edit', function () {
            let id = $(this).data('id');

            $.get('/mapel/' + id + '/edit', function (data) {
                // Load data mapel
                $('#edit-id').val(data.id);
                $('#edit-nm_mapel').val(data.nm_mapel);

                // Load data kelas lalu set option dan pilih yang sesuai
                $.get('/kelas', function (kelasData) {
                    let options = '<option value="">-- Pilih Kelas --</option>';
                    kelasData.forEach(function (k) {
                        options += `<option value="${k.id_kelas}" ${k.id_kelas == data.id_kelas ? 'selected' : ''}>${k.nm_kelas}</option>`;
                    });
                    $('#edit-id_kelas').html(options);
                });

                $('#editModal').modal('show');
            });
        });

        $('#editForm').submit(function (e) {
            e.preventDefault();

            let id = $('#edit-id').val();
            let formData = $(this).serialize();

            $.ajax({
                url: '/mapel/' + id,
                type: 'PUT',
                data: formData,
                success: function (response) {
                    $('#editModal').modal('hide');

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });

                    $('#data').DataTable().ajax.reload(null, false);
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat menyimpan data.'
                    });
                }
            });
        });




        $(document).on('click', '.delete', function () {
            let id = $(this).data('id');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

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
                        url: 'mapel/delete/' + id,
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


    </script>
    <script>
        @if (session()->has('success'))
        toastr.success('{{ session('success') }}', 'Wohoooo!');
        @else
        toastr.error('{{ session('error') }}', 'Whoops!');
        @endif
    </script>
@endPushOnce
