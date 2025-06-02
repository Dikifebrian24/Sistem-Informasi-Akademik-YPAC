@pushOnce('js')
    <script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/js/form-validation-custom.js') }}"></script>
    <script src="{{ asset('assets/js/tooltip-init.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script type="text/javascript">


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
                    {data: 'waktu_selesai', name: 'waktu_selesai'}
                ]
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

        $('#addJadwalForm').on('submit', function (e) {
            e.preventDefault();

            let formData = $(this).serialize();

            $.ajax({
                url: '{{ route("jadwal/store") }}',
                method: 'POST',
                data: formData,
                success: function (response) {
                    alert(response.message);
                    $('#addJadwalModal').modal('hide');
                    $('#jadwalTable').DataTable().ajax.reload();
                },
                error: function (xhr, status, error) {
                    alert('There was an error adding the jadwal.');
                }
            });
        });

    </script>

@endpushonce
