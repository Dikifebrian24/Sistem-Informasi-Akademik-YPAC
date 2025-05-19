@pushOnce('js')
    <script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/js/form-validation-custom.js') }}"></script>
    <script src="{{ asset('assets/js/tooltip-init.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">


        $(document).ready(function () {
            var siswa_kelas = $('#siswa_kelas_data').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('progress_jadwal/data') }}',
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
        });

        $(document).on('click', '#input_progress', function(e) {
            e.preventDefault();

            // Ambil data dari tombol yang diklik
            let id_mapel = $(this).data('id_mapel');
            let id_kelas = $(this).data('id_kelas');
            let id_jadwal = $(this).data('id');

            console.log('id_mapel:', id_mapel);
            console.log('id_kelas:', id_kelas);
            console.log('id_jadwal:', id_jadwal);

            // Redirect ke route dengan parameter query string
            window.location.href = `/master/data_progress/progress_add?id_mapel=${id_mapel}&id_kelas=${id_kelas}&id_jadwal=${id_jadwal}`;
        });

        $(document).on('click', '#show_progress', function(e) {
            e.preventDefault();

            // Ambil data dari tombol yang diklik
            let id_mapel = $(this).data('id_mapel');
            let id_kelas = $(this).data('id_kelas');
            let id_jadwal = $(this).data('id');

            console.log('id_mapel:', id_mapel);
            console.log('id_kelas:', id_kelas);
            console.log('id_jadwal:', id_jadwal);

            // Redirect ke route dengan parameter query string
            window.location.href = `/master/data_progress/progress_show?id_mapel=${id_mapel}&id_kelas=${id_kelas}&id_jadwal=${id_jadwal}`;
        });

        $.getScript('https://cdn.jsdelivr.net/npm/chart.js')
            .done(function() {
                // Setelah Chart.js berhasil dimuat, baru jalankan chart-nya
                renderChart();
            })
            .fail(function() {
                alert('Gagal memuat Chart.js');
            });



        $(document).ready(function () {
            $('#f_filter').on('submit', function(e) {
                e.preventDefault();

                const id_mapel = $('#id_mapel').val();
                const id_siswa = $('#id_siswa').val();

                if (!id_siswa) {
                    Swal.fire('Peringatan', 'Silakan pilih siswa terlebih dahulu.', 'warning');
                    return;
                }

                $.ajax({
                    url: '{{ route("progress_jadwal/filter") }}', // Buat route ini di web.php
                    type: 'POST',
                    data: {
                        _token: $('input[name="_token"]').val(),
                        id_mapel: id_mapel,
                        id_siswa: id_siswa
                    },
                    success: function(response) {
                        $('#hasil_nilai').html(response);
                        $('#hasil_nilai_card').show();

                        // Jalankan script setelah elemen sudah masuk ke DOM
                        setTimeout(function () {
                            renderChart(); // panggil fungsi untuk menggambar chart
                        }, 200);
                    },
                    error: function(xhr) {
                        Swal.fire('Gagal', 'Terjadi kesalahan: ' + xhr.responseText, 'error');
                    }
                });
            });
        });

        {{--$('#f_progress').on('submit', function(e) {--}}
        {{--    e.preventDefault();--}}

        {{--    console.log('tes')--}}

        {{--    let formData = new FormData(this);--}}

        {{--    $.ajax({--}}
        {{--        url: '{{ route("data_progress/save") }}',--}}
        {{--        method: 'POST',--}}
        {{--        data: formData,--}}
        {{--        processData: false,--}}
        {{--        contentType: false,--}}
        {{--        success: function(res) {--}}
        {{--            alert('Nilai berhasil disimpan!');--}}
        {{--            $('#f_progress')[0].reset();--}}
        {{--        },--}}
        {{--        error: function(xhr) {--}}
        {{--            alert('Terjadi kesalahan: ' + xhr.responseText);--}}
        {{--        }--}}
        {{--    });--}}
        {{--});--}}

        $(document).ready(function () {
            $('#f_progress').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);

                $.ajax({
                    url: '{{ route("progress_jadwal/save") }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Menyimpan...',
                            text: 'Silakan tunggu',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: res.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        $('#f_progress')[0].reset();
                        renderStars(0); // Reset bintang
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: xhr.responseText,
                        });
                    }
                });
            });
        });


        $(document).on('click', '.edit', function() {
            let userId = $(this).data('id');
            $('#saveEdit').show();
            $('#save').hide();

            $.ajax({
                url: 'kelas/' + userId + '/edit',
                type: 'GET',
                success: function(user) {
                    $('#kd_kelas').val(user.kd_kelas);
                    $('#nm_kelas').val(user.nm_kelas);

                    $('#kelasModalAdd').modal('show');
                    // $('#saveEdit').hide();
                    // $('#save').show();
                    $('#saveEdit').show();
                    $('#save').hide();

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
                        url: 'kelas/delete/' + id,
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
