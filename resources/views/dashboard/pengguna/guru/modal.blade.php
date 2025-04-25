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
                            <label class="form-label">NIP</label>
                            <input class="form-control" type="text" name="nip" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">NIK</label>
                            <input class="form-control" type="text" name="nik" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Guru</label>
                            <input class="form-control" type="text" name="nm_guru" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jenis Kelamin</label>
                            <select class="form-select" name="jenkel" required>
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="Laki - Laki">Laki - Laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tempat Lahir</label>
                            <input class="form-control" type="text" name="tmpt_lahir" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Lahir</label>
                            <input class="form-control" type="date" name="tgl_lahir" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Alamat Jalan</label>
                            <input class="form-control" type="text" name="almt_jalan" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">RT/RW</label>
                            <input class="form-control" type="text" name="rt_rw" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kab/Kota</label>
                            <input class="form-control" type="text" name="kab_kota" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kelurahan</label>
                            <input class="form-control" type="text" name="kelurahan" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kecamatan</label>
                            <input class="form-control" type="text" name="kecamatan" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Provinsi</label>
                            <input class="form-control" type="text" name="provinsi" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kode Pos</label>
                            <input class="form-control" type="text" name="kd_pos" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No. Telepon</label>
                            <input class="form-control" type="text" name="no_telp" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No. HP</label>
                            <input class="form-control" type="text" name="no_hp" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input class="form-control" type="email" name="email" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Agama</label>
                            <select class="form-select" name="agama" required>
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
                            <label class="form-label">Status Guru</label>
                            <select class="form-select" name="stts_guru" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="Active">Active</option>
                                <option value="Non Active">Non Active</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">NPWP</label>
                            <input class="form-control" type="text" name="npwp" required>
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
