<!-- Modal to show Jadwal Table -->
{{--<div class="modal fade" id="jadwalModal" tabindex="-1" aria-labelledby="jadwalModalLabel" aria-hidden="true">--}}
{{--    <div class="modal-dialog modal-xl">--}}
{{--        <div class="modal-content">--}}
{{--            <div class="modal-header">--}}
{{--                <h5 class="modal-title" id="jadwalModalLabel">Jadwal Kelas</h5>--}}
{{--                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>--}}
{{--            </div>--}}
{{--            <div class="modal-body">--}}
{{--                <!-- Add Jadwal Button -->--}}
{{--                <button class="btn btn-primary mb-3" id="addJadwalBtn">Add Jadwal</button>--}}
{{--                <button class="btn btn-primary mb-3" id="importJadwalBtn">Import Jadwal</button>--}}
{{--                <input type="text" id="id_kelas">--}}
{{--                <!-- Jadwal Table -->--}}
    {{--                <table id="jadwalTable" class="table table-bordered table-striped">--}}
{{--                    <thead>--}}
{{--                    <tr>--}}
{{--                        <th>ID</th>--}}
{{--                        <th>Guru</th>--}}
{{--                        <th>Materi</th>--}}
{{--                        <th>Tanggal</th>--}}
{{--                        <th>Waktu Mulai</th>--}}
{{--                        <th>Waktu Selesai</th>--}}
{{--                    </tr>--}}
{{--                    </thead>--}}
{{--                </table>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

<!-- Modal to Add New Jadwal -->
<div class="modal fade" id="addJadwalModal" tabindex="-1" aria-labelledby="addJadwalModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addJadwalModalLabel">Tambah Jadwal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addJadwalForm">
                    @csrf
                    <div class="mb-3">
                        <label for="materi" class="form-label">Mata Pelajaran</label>
                        <select name="mapel" id="mapel" class="form-control">
                            <option value="">-- Pilih Mapel --</option>
                            @foreach($params['mapel'] as $item)
                                <option value="{{ $item->id }}">{{ $item->nm_mapel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="materi" class="form-label">Materi</label>
                        <input type="text" class="form-control" id="materi" name="materi" required>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                    </div>
                    <div class="mb-3">
                        <label for="waktu_mulai" class="form-label">Waktu Mulai</label>
                        <input type="time" class="form-control" id="waktu_mulai" name="waktu_mulai" required>
                    </div>
                    <div class="mb-3">
                        <label for="waktu_selesai" class="form-label">Waktu Selesai</label>
                        <input type="time" class="form-control" id="waktu_selesai" name="waktu_selesai" required>
                    </div>
                    <input type="hidden" id="id_kelas" name="id_kelas">
                    <button type="submit" class="btn btn-primary">Save Jadwal</button>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="importJadwalModal" tabindex="-1" aria-labelledby="importJadwalModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importJadwalModalLabel">Tambah Jadwal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="f_import">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Template Import</label><br>
                        <button type="button" class="btn btn-primary" id="template_download">Download</button>
                    </div>
                    <div class="mb-3">
                        <label for="materi" class="form-label">File Import</label>
                        <input type="file" class="form-control" id="import_data" name="import_data" required>
                    </div>

                    <input type="hidden" id="id_kelas" name="id_kelas">
                    <button type="submit" class="btn btn-primary">Save Jadwal</button>
                </form>
            </div>
        </div>
    </div>
</div>
