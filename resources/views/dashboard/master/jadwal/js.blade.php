@pushOnce('js')
    <script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/js/form-validation-custom.js') }}"></script>
    <script src="{{ asset('assets/js/tooltip-init.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script type="text/javascript">
        $(document).ready(function () {
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

        $(document).on('click', '.show', function () {
            let idKelas = $(this).data('id');
            console.log(idKelas, 'kontol');
            let temp_kelas = $('#id_kelas').va;(idKelas);

            // Show modal
            $('#jadwalModal').modal('show');

            // Destroy old instance if exists
            if ($.fn.DataTable.isDataTable('#jadwalTable')) {
                $('#jadwalTable').DataTable().clear().destroy();
            }

            // Initialize DataTable with server-side and pass id_kelas
            table = $('#jadwalTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("jadwal/get-jadwal") }}',
                    data: { id_kelas: idKelas }
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'materi', name: 'materi' },
                    { data: 'tanggal', name: 'tanggal' },
                    { data: 'waktu_mulai', name: 'waktu_mulai' },
                    { data: 'waktu_selesai', name: 'waktu_selesai' }
                ]
            });
        });

        $(document).on('click', '#addJadwalBtn', function() {
            let idKelas = $('.show').data('id');

            console.log(idKelas);

            $('#id_kelas').val(idKelas);

            $('#addJadwalModal').modal('show');
        });

        $('#addJadwalForm').on('submit', function(e) {
            e.preventDefault();

            let formData = $(this).serialize();

            $.ajax({
                url: '{{ route("jadwal/store") }}',
                method: 'POST',
                data: formData,
                success: function(response) {
                    alert(response.message);
                    $('#addJadwalModal').modal('hide');
                    $('#jadwalTable').DataTable().ajax.reload();
                },
                error: function(xhr, status, error) {
                    alert('There was an error adding the jadwal.');
                }
            });
        });

    </script>

@endpushonce
