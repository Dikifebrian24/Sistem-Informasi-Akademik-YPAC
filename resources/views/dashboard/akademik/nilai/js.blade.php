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
                ajax: '{{ route('data_nilai_jadwal/data') }}',
                columns: [
                    {
                        data: null,
                        name: 'id',
                        className: 'text-center',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 3;
                        }
                    },
                    {data: 'nm_mapel', name: 'nm_mapel'},
                    {data: 'nm_kelas', name: 'nm_kelas'},
                    {data: 'jumlah_siswa', name: 'jumlah_siswa'},
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

        $(document).on('click', '.edit-btn', function () {
            $('#nilai_id').val($(this).data('id'));
            $('#mapel').val($(this).data('mapel'));
            $('#siswa').val($(this).data('siswa'));
            $('#nilai').val($(this).data('nilai'));
            $('#editModal').modal('show');
        });

        $('#editForm').submit(function(e){
            e.preventDefault();
            let formData = $(this).serialize();
            $.ajax({
                url: '{{ route('data_nilai/update') }}', // Ganti sesuai route
                method: 'POST',
                data: formData,
                success: function(res){
                    $('#editModal').modal('hide');
                    $('#table-nilai').DataTable().ajax.reload(); // reload DataTable
                },
                error: function(err){
                    alert("Gagal update nilai.");
                }
            });
        });

        $(document).ready(function () {
            var nilai_siswa = $('#table-nilai').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('data_nilai/nilai_data') }}',
                columns: [

                    {data: 'nm_siswa', name: 'nm_siswa'},
                    {data: 'materi', name: 'materi'},
                    {data: 'nilai', name: 'nilai'},
                    {data: 'lampiran', name: 'lampiran'},
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

        $(document).on('click', '.delete-btn', function () {
            let id = $(this).data('id');

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data nilai akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/nilai/delete/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            if (response.success) {
                                Swal.fire('Berhasil!', response.message, 'success');
                                $('#table-nilai').DataTable().ajax.reload();
                            }
                        },
                        error: function () {
                            Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus data.', 'error');
                        }
                    });
                }
            });
        });

        $(document).on('click', '#input_nilai', function (e) {
            e.preventDefault();

            // Ambil data dari tombol yang diklik
            let id_mapel = $(this).data('id_mapel');
            let id_kelas = $(this).data('id_kelas');
            let id_jadwal = $(this).data('id');

            console.log('id_mapel:', id_mapel);
            console.log('id_kelas:', id_kelas);
            console.log('id_jadwal:', id_jadwal);

            // Redirect ke route dengan parameter query string
            window.location.href = `/master/data_nilai/nilai_add?id_mapel=${id_mapel}&id_kelas=${id_kelas}&id_jadwal=${id_jadwal}`;
        });

        $(document).on('click', '#show_nilai', function (e) {
            e.preventDefault();

            // Ambil data dari tombol yang diklik
            let id_mapel = $(this).data('id_mapel');
            let id_kelas = $(this).data('id_kelas');
            let id_jadwal = $(this).data('id');

            console.log('id_mapel:', id_mapel);
            console.log('id_kelas:', id_kelas);
            console.log('id_jadwal:', id_jadwal);

            // Redirect ke route dengan parameter query string
            window.location.href = `/master/data_nilai/nilai_show?id_mapel=${id_mapel}&id_kelas=${id_kelas}&id_jadwal=${id_jadwal}`;
        });

        $(document).ready(function () {
            $('#f_filter').on('submit', function (e) {
                e.preventDefault();

                const id_mapel = $('#id_mapel').val();
                const id_siswa = $('#id_siswa').val();

                if (!id_siswa) {
                    Swal.fire('Peringatan', 'Silakan pilih siswa terlebih dahulu.', 'warning');
                    return;
                }

                $.ajax({
                    url: '{{ route("data_nilai_jadwal/filter") }}', // Buat route ini di web.php
                    type: 'POST',
                    data: {
                        _token: $('input[name="_token"]').val(),
                        id_mapel: id_mapel,
                        id_siswa: id_siswa
                    },
                    success: function (response) {
                        $('#hasil_nilai').html(response);
                        $('#hasil_nilai_card').show();
                    },
                    error: function (xhr) {
                        Swal.fire('Gagal', 'Terjadi kesalahan: ' + xhr.responseText, 'error');
                    }
                });
            });
        });

        {{--$('#f_nilai').on('submit', function(e) {--}}
        {{--    e.preventDefault();--}}

        {{--    console.log('tes')--}}

        {{--    let formData = new FormData(this);--}}

        {{--    $.ajax({--}}
        {{--        url: '{{ route("data_nilai_jadwal/save") }}',--}}
        {{--        method: 'POST',--}}
        {{--        data: formData,--}}
        {{--        processData: false,--}}
        {{--        contentType: false,--}}
        {{--        success: function(res) {--}}
        {{--            alert('Nilai berhasil disimpan!');--}}
        {{--            $('#f_nilai')[0].reset();--}}
        {{--        },--}}
        {{--        error: function(xhr) {--}}
        {{--            alert('Terjadi kesalahan: ' + xhr.responseText);--}}
        {{--        }--}}
        {{--    });--}}
        {{--});--}}

        $(document).ready(function () {
            $('#f_nilai').on('submit', function (e) {
                e.preventDefault();

                console.log('📨 Form submit triggered');

                let formData = new FormData(this);

                // Cek isi formData (pakai loop karena tidak bisa langsung console.log formData)
                for (let pair of formData.entries()) {
                    console.log('📦 FormData:', pair[0] + ' =>', pair[1]);
                }

                $.ajax({
                    url: '{{ route("data_nilai_jadwal/save") }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function () {
                        // Optional: loader
                        Swal.fire({
                            title: 'Menyimpan...',
                            text: 'Silakan tunggu',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function (res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Nilai berhasil disimpan.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        $('#f_nilai')[0].reset();
                    },
                    error: function (xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan: ' + xhr.responseText,
                        });
                    }
                });
            });
        });

        $(document).on('click', '.edit', function () {
            let userId = $(this).data('id');
            $('#saveEdit').show();
            $('#save').hide();

            $.ajax({
                url: 'kelas/' + userId + '/edit',
                type: 'GET',
                success: function (user) {
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

        // $('#generateFileBtn').on('click', function (e) {
        //     e.preventDefault();
        //
        //     const params = new URLSearchParams(window.location.search);
        //     let id_mapel = params.get("id_mapel");
        //     let id_kelas = params.get("id_kelas");
        //     let id_jadwal = params.get("id_jadwal");
        //
        //     // Redirect ke route untuk generate file
        //     window.location.href = `data_nilai/generate-template?id_mapel=${id_mapel}&id_kelas=${id_kelas}&id_jadwal=${id_jadwal}`;
        // });

        $('#generateFileBtn').on('click', function (e) {
            e.preventDefault();

            // Ambil parameter dari URL
            const params = new URLSearchParams(window.location.search);
            const id_kelas = params.get("id_kelas");
            const id_mapel = params.get("id_mapel");
            const id_jadwal = params.get("id_jadwal");

            if (!id_kelas || !id_mapel || !id_jadwal) {
                Swal.fire('Error', 'Parameter tidak lengkap di URL.', 'error');
                return;
            }
            //
            // console.log(id_kelas, id_mapel, id_jadwal);

            {{--$.ajax({--}}
            {{--    --}}{{--url: {{ url('template-nilai') }},--}}
            {{--    url: "{{ url('template-nilai') }}",--}}
            {{--    type: 'GET',--}}
            {{--    data: {--}}
            {{--        _id_kelas: id_kelas,--}}
            {{--        _id_mapel: id_mapel--}}
            {{--    },--}}
            {{--    success: function (response) {--}}
            {{--        Swal.fire({--}}
            {{--            icon: 'success',--}}
            {{--            title: 'Berhasil!',--}}
            {{--            text: response.message,--}}
            {{--            timer: 2000,--}}
            {{--            showConfirmButton: false--}}
            {{--        });--}}

            {{--        // Reload DataTable--}}
            {{--        $('#data').DataTable().ajax.reload();--}}
            {{--    },--}}
            {{--    error: function () {--}}
            {{--        Swal.fire({--}}
            {{--            icon: 'error',--}}
            {{--            title: 'Gagal!',--}}
            {{--            text: 'Terjadi kesalahan saat menghapus data.'--}}
            {{--        });--}}
            {{--    }--}}
            {{--});--}}

            // Contoh endpoint: sesuaikan route-mu
            const downloadUrl = `template-nilai/${id_kelas}?id_mapel=${id_mapel}&id_jadwal=${id_jadwal}`;

            fetch(downloadUrl, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    if (!response.ok) throw new Error('Gagal mengunduh file');
                    return response.blob();
                })
                .then(blob => {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `template_nilai_kelas_${id_kelas}.xlsx`;
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                    window.URL.revokeObjectURL(url);

                    Swal.fire('Sukses', 'Template berhasil digenerate dan diunduh!', 'success');
                })
                .catch(error => {
                    Swal.fire('Error', error.message, 'error');
                });
        });

        $('#importFileBtn').on('click', function (e) {
            e.preventDefault();
            console.log('Import File clicked');
            const importModal = new bootstrap.Modal(document.getElementById('importModal'));
            importModal.show();
        });

        function getUrlParams() {
            const params = new URLSearchParams(window.location.search);
            return {
                id_mapel: params.get('id_mapel'),
                id_kelas: params.get('id_kelas'),
                id_jadwal: params.get('id_jadwal'),
            };
        }

        $('#importForm').on('submit', function(e) {
            e.preventDefault();

            let { id_mapel, id_kelas } = getUrlParams();

            if (!id_mapel || !id_kelas) {
                Swal.fire('Error', 'Parameter URL tidak lengkap!', 'error');
                return;
            }

            let formData = new FormData(this);
            formData.append('id_mapel', id_mapel);
            formData.append('id_kelas', id_kelas);

            $.ajax({
                url: '{{ route("data_nilai_jadwal.import") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                beforeSend: function() {
                    // contoh disable tombol submit, spinner, dll
                    $('#importForm button[type=submit]').attr('disabled', true);
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sukses',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });

                    // Bootstrap 5 modal hide
                    var importModalEl = document.getElementById('importModal');
                    var modal = bootstrap.Modal.getInstance(importModalEl);
                    modal.hide();

                    $('#jadwalTable').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    let errMsg = 'Gagal import file!';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errMsg = xhr.responseJSON.message;
                    }
                    Swal.fire('Error', errMsg, 'error');
                },
                complete: function() {
                    $('#importForm button[type=submit]').attr('disabled', false);
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
