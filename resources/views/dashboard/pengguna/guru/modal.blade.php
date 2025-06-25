<div class="modal fade" id="guruModalAdd" aria-labelledby="addGuruLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addGuruLabel">Tambah Guru</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" id="saveGuru" method="POST">
                    @csrf
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label">Nama Depan</label>
                            <input class="form-control" type="text" name="first_name" id="first_name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Belakang</label>
                            <input class="form-control" type="text" name="last_name" id="last_name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">NIP</label>
                            <input class="form-control" type="number" name="nip" id="nip" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">NIK</label>
                            <input class="form-control" type="number" name="nik" id="nik" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input class="form-control" type="email" name="email" id="email" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password</label>
                            <input class="form-control" type="password" name="password" id="password" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">NPWP</label>
                            <input class="form-control" type="text" name="npwp" id="npwp" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jenis Kelamin</label>
                            <select class="form-select" name="jenkel" id="jenkel" required>
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="Laki - Laki">Laki - Laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tempat Lahir</label>
                            <input class="form-control" type="text" name="tmpt_lahir" id="tmpt_lahir" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Lahir</label>
                            <input class="form-control" type="date" name="tgl_lahir" id="tgl_lahir" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Alamat Lengkap</label>
                            <input class="form-control" type="text" name="almt_jalan" id="almt_jalan" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No. HP</label>
                            <input class="form-control" type="text" name="no_hp" id="no_hp" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Agama</label>
                            <select class="form-select" name="agama" id="agama" required>
                                <option value="">-- Pilih Agama --</option>
                                <option value="Islam">Islam</option>
                                <option value="Kristen">Kristen</option>
                                <option value="Katolik">Katolik</option>
                                <option value="Hindu">Hindu</option>
                                <option value="Buddha">Buddha</option>
                                <option value="Khonghucu">Khonghucu</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kategori Guru</label>
                            <select class="form-select" name="level_guru" id="level_guru" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="1">Kepala Sekolah</option>
                                <option value="2">Staff Pengajar</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer mt-3">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="guruModalEdit" aria-labelledby="addGuruLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addGuruLabel">Tambah Guru</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" id="editGuru" method="POST">
                    @csrf
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label">Nama Depan</label>
                            <input class="form-control" type="text" name="edit_id_user" id="edit_id_user" required>
                            <input class="form-control" type="text" name="edit_first_name" id="edit_first_name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Belakang</label>
                            <input class="form-control" type="text" name="edit_last_name" id="edit_last_name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">NIP</label>
                            <input class="form-control" type="number" name="edit_nip" id="edit_nip" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">NIK</label>
                            <input class="form-control" type="number" name="edit_nik" id="edit_nik" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input class="form-control" type="email" name="edit_email" id="edit_email" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password</label>
                            <input class="form-control" type="password" name="edit_password" id="edit_password">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">NPWP</label>
                            <input class="form-control" type="text" name="edit_npwp" id="edit_npwp" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jenis Kelamin</label>
                            <select class="form-select" name="edit_jenkel" id="edit_jenkel" required>
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="Laki - Laki">Laki - Laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tempat Lahir</label>
                            <input class="form-control" type="text" name="edit_tmpt_lahir" id="edit_tmpt_lahir" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Lahir</label>
                            <input class="form-control" type="date" name="edit_tgl_lahir" id="edit_tgl_lahir" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Alamat Lengkap</label>
                            <input class="form-control" type="text" name="edit_almt_jalan" id="edit_almt_jalan" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No. HP</label>
                            <input class="form-control" type="text" name="edit_no_hp" id="edit_no_hp" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Agama</label>
                            <select class="form-select" name="edit_agama" id="edit_agama" required>
                                <option value="">-- Pilih Agama --</option>
                                <option value="Islam">Islam</option>
                                <option value="Kristen">Kristen</option>
                                <option value="Katolik">Katolik</option>
                                <option value="Hindu">Hindu</option>
                                <option value="Buddha">Buddha</option>
                                <option value="Khonghucu">Khonghucu</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kategori Guru</label>
                            <select class="form-select" name="edit_level_guru" id="edit_level_guru" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="1">Kepala Sekolah</option>
                                <option value="2">Staff Pengajar</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer mt-3">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Import Data Guru</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="f_import" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="container">
                        <!-- tombol download template -->
                        <div class="row">
                            <div class="col">
                                <label class="form-label">Template Import</label><br>
                                <button type="button" class="btn btn-primary" id="template_download">Download</button>
                            </div>
                        </div>

                        <!-- file input -->
                        <div class="row mt-3">
                            <div class="col">
                                <label class="form-label">File (.xlsx)</label>
                                <input class="form-control" type="file" name="import_data" id="import_data" required>
                            </div>
                        </div>

                        <div class="modal-footer mt-3">
                            <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                            <button class="btn btn-primary" type="submit">Save</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

