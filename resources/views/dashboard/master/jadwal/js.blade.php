@pushOnce('js')
    <script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/js/form-validation-custom.js') }}"></script>
    <script src="{{ asset('assets/js/tooltip-init.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script type="text/javascript">


        $(document).ready(function () {
            // Ambil kelas ID dari URL
            const urlParts = window.location.pathname.split('/');
            const kelasId = urlParts[urlParts.length - 1];

            // Set value ke input hidden
            $('#kelas_id').val(kelasId);
        });


        $(document).ready(function () {
            $('#jadwalTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("jadwal/get-jadwal") }}',
                    data: function (d) {
                        d.id_kelas = kelasId;
                    }
                },
                columns: [
                    {
                        data: null,
                        name: 'id',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {data: 'nm_guru', name: 'nm_guru'},
                    {data: 'nm_mapel', name: 'nm_mapel'},
                    {data: 'materi', name: 'materi'},
                    {data: 'tanggal', name: 'tanggal'},
                    {data: 'waktu_mulai', name: 'waktu_mulai'},
                    {data: 'waktu_selesai', name: 'waktu_selesai'},
                    {data: 'action', name: 'action'}
                ]
            });

            $(document).on('click', '.edit', function () {
                let id = $(this).data('id');

                $.ajax({
                    url: '/jadwal/edit/'+ id,
                    method: 'GET',
                    success: function (response) {
                        // Isi nilai form
                        $('#addJadwalModalLabel').text('Edit Jadwal');
                        $('#mapel').empty();
                        $('#guru').empty();

                        // Isi dropdown mapel
                        $('#mapel').append('<option value="">-- Pilih Mapel --</option>');
                        $.each(response.mapel, function (i, item) {
                            let selected = (item.id == response.jadwal.id_mapel) ? 'selected' : '';
                            $('#mapel').append(`<option value="${item.id}" ${selected}>${item.nm_mapel}</option>`);
                        });

                        // Isi dropdown guru
                        $('#guru').append('<option value="">-- Pilih Guru --</option>');
                        $.each(response.guru, function (i, item) {
                            let selected = (item.id_guru == response.jadwal.id_guru) ? 'selected' : '';
                            $('#guru').append(`<option value="${item.id_guru}" ${selected}>${item.nm_guru}</option>`);
                        });

                        // Isi input lain
                        $('#materi').val(response.jadwal.materi);
                        $('#tanggal').val(response.jadwal.tanggal);
                        $('#waktu_mulai').val(response.jadwal.waktu_mulai);
                        $('#waktu_selesai').val(response.jadwal.waktu_selesai);

                        // Simpan id jadwal di form sebagai hidden input atau di data attribute form
                        $('#addJadwalForm').attr('data-id', id);

                        // Tampilkan modal
                        $('#addJadwalModal').modal('show');
                    },
                    error: function () {
                        alert('Data gagal dimuat');
                    }
                });
            });

            $('#editJadwalForm').on('submit', function (e) {
                e.preventDefault();

                let formData = $(this).serialize();
                let id = $('#edit-id').val();

                $.ajax({
                    url: '/jadwal/' + id,
                    method: 'PUT',
                    data: formData,
                    success: function (response) {
                        $('#editModal').modal('hide');
                        $('#jadwalTable').DataTable().ajax.reload();
                        alert(response.message);
                    },
                    error: function () {
                        alert('Gagal mengedit data.');
                    }
                });
            });

            $(document).on('click', '.delete', function () {
                const id = $(this).data('id');

                Swal.fire({
                    title: 'Yakin hapus?',
                    text: 'Data tidak bisa dikembalikan!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/jadwal/' + id,
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                $('#jadwalTable').DataTable().ajax.reload();
                                Swal.fire('Berhasil!', response.message, 'success');
                            },
                            error: function () {
                                Swal.fire('Gagal!', 'Gagal menghapus data.', 'error');
                            }
                        });
                    }
                });
            });

            $('#template_download').on('click', function () {
                window.location.href = "{{ route('download.template_jadwal') }}";
            });

            $('#data').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('jadwal/data') }}',
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
                    {data: 'nm_kelas', name: 'nm_kelas'},
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

        // $(document).on('click', '.show-btn', function () {
        //     let id = $(this).data('id');
        //
        //     console.log(id, 'kontol');
        //
        //     window.location.href = `jadwal_detail/add?id_kelas=${id}`;
        // });

        $(document).on('click', '#show', function () {
            var id = $(this).data('id');
            window.location.href = 'jadwal/kelas/' + id;
        });

        let kelasId = window.location.pathname.split("/").pop();


        let table;
        let currentIdKelas = null;

        {{--$(document).on('click', '.show', function () {--}}
        {{--    currentIdKelas = $(this).data('id');--}}
        {{--    console.log('Clicked ID:', currentIdKelas);--}}

        {{--    $('#id_kelas').val(currentIdKelas);--}}

        {{--    $('#jadwalModal').modal('show');--}}

        {{--    if ($.fn.DataTable.isDataTable('#jadwalTable')) {--}}
        {{--        table.ajax.reload();--}}
        {{--    } else {--}}
        {{--        table = $('#jadwalTable').DataTable({--}}
        {{--            processing: true,--}}
        {{--            serverSide: true,--}}
        {{--            ajax: {--}}
        {{--                url: '{{ route("jadwal/get-jadwal") }}',--}}
        {{--                data: function (d) {--}}
        {{--                    d.id_kelas = currentIdKelas; // Pass dynamic class ID--}}
        {{--                }--}}
        {{--            },--}}
        {{--            columns: [--}}
        {{--                { data: 'id', name: 'id' },--}}
        {{--                { data: 'materi', name: 'materi' },--}}
        {{--                { data: 'tanggal', name: 'tanggal' },--}}
        {{--                { data: 'waktu_mulai', name: 'waktu_mulai' },--}}
        {{--                { data: 'waktu_selesai', name: 'waktu_selesai' }--}}
        {{--            ]--}}
        {{--        });--}}
        {{--    }--}}
        {{--});--}}

        $('#f_import').on('submit', function (e) {
            e.preventDefault();

            let currentIdKelas = window.location.pathname.split("/").pop();

            let formData = new FormData(this);

            formData.append('id_kelas', currentIdKelas);

            console.log(formData)

            $.ajax({
                url: "{{ route('jadwal/import') }}",
                method: "POST",
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                beforeSend: function () {
                    // Optional: tampilkan loading indicator
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 2000
                    });

                    $('#import_data').val('');

                    $('#importJadwalModal').modal('hide');
                    $('#jadwalTable').DataTable().ajax.reload();
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


        $(document).on('click', '#addJadwalBtn', function () {
            let idKelas = $('.show').data('id');

            console.log(idKelas);

            $('#id_kelas').val(idKelas);

            $('#addJadwalModal').modal('show');
        });

        $(document).on('click', '#importJadwalBtn', function () {
            // let idKelas = $('.show').data('id');
            //
            // console.log(idKelas);
            //
            // $('#id_kelas').val(idKelas);

            let currentIdKelas = window.location.pathname.split("/").pop();

            $('#importJadwalModal').modal('show');
        });

        // // Ambil ID kelas dari URL (misal: .../kelas/1)
        // function getKelasIdFromURL() {
        //     let urlParts = window.location.pathname.split('/');
        //     return urlParts[urlParts.length - 1]; // Ambil angka terakhir
        // }
        //
        // // Set ID ke input hidden saat halaman siap
        // $(document).ready(function () {
        //     let kelasId = getKelasIdFromURL();
        //     $('#id_kelas').val(kelasId);
        // });


        $('#addJadwalForm').on('submit', function (e) {
            e.preventDefault();

            let id = $(this).attr('data-id'); // ambil id jika ada (edit)

            let url = '';
            let method = '';

            if (id) {
                url = `/jadwal/${id}`;  // URL update
                method = 'PUT';
            } else {
                url = '{{ route('jadwal/store') }}';  // URL tambah
                method = 'POST';
            }

            $.ajax({
                url: url,
                method: method,
                data: $(this).serialize(),
                success: function (response) {
                    alert(response.message);
                    $('#addJadwalModal').modal('hide');
                    $('#jadwalTable').DataTable().ajax.reload();

                    // reset form dan hapus data-id agar modal siap untuk tambah lagi
                    $('#addJadwalForm')[0].reset();
                    $('#addJadwalForm').removeAttr('data-id');
                    $('#addJadwalModalLabel').text('Tambah Jadwal');
                },
                error: function (xhr) {
                    alert('Terjadi kesalahan');
                }
            });
        });



    </script>

@endpushonce
