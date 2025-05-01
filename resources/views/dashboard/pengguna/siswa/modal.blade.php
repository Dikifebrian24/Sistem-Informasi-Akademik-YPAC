<div class="modal fade" id="siswaModalAdd" aria-labelledby="addSiswaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSiswaLabel">Add Siswa</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" id="saveSiswa">
                    @csrf
                    <div>
                        <div class="row g-2">
                            <div class="col-md-6  mb-3">
                                <label class="form-label">Nama Depan</label>
                                <input class="form-control" type="text" name="first_name" id="first_name" required>
                                @error('first_name')
                                <div class="valid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6  mb-3">
                                <label class="form-label">Nama Belakang</label>
                                <input class="form-control" type="text" name="last_name" id="last_name" required>
                                @error('last_name')
                                <div class="valid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6  mb-3">
                                <label class="form-label">NIK</label>
                                <input class="form-control" type="number" name="nik" id="nik" required>
                                @error('nik')
                                <div class="valid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6  mb-3">
                                <label class="form-label">NISN</label>
                                <input class="form-control" type="number" name="nisn" id="nisn" required>
                                @error('nisn')
                                <div class="valid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6  mb-3">
                                <label class="form-label">Email</label>
                                <input class="form-control" type="email" name="email" id="email" required>
                                @error('email')
                                <div class="valid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6  mb-3">
                                <label class="form-label">Password</label>
                                <input class="form-control" type="password" name="password" id="password" required>
                                @error('kd_jurusan')
                                <div class="valid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <hr>
                            <h5>Detail Biodata</h5>
                            <hr>
                            <div class="col-md-6  mb-3">
                                <label class="form-label">Kategori Kelainan</label>
                                <select class="form-select" id="kelainan" name="kelainan" required>
                                    <option value="">-- Pilih Kategori Kelainan --</option>
                                    @foreach($params['kelainan'] as $item)
                                        <option value="{{ $item->id }}">{{ $item->nm_kelainan }}</option>
                                    @endforeach
                                </select>
                                @error('kelainan')
                                <div class="valid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6  mb-3">
                                <label class="form-label">Jenis Kelamin</label>
                                <select class="form-select" id="jenkel" name="jenkel" required>
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="Laki - Laki">Laki - Laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                                @error('jenkel')
                                <div class="valid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6  mb-3">
                                <label class="form-label">Tempat Lahir (Kota)</label>
                                <input class="form-control" type="text" name="tmpt_lahir" id="tmpt_lahir" required>
                                @error('tmpt_lahir')
                                <div class="valid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6  mb-3">
                                <label class="form-label">Tanggal Lahir</label>
                                <input class="form-control" type="date" name="tgl_lahir" id="tgl_lahir" required>
                                @error('tgl_lahir')
                                <div class="valid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6  mb-3">
{{--                                'Islam','Kristen','Hindu','Buddha','Katolik','Khonghucu'--}}
                                <label class="form-label">Agama</label>
                                <select class="form-select" id="agama" name="agama" required>
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="Islam">Islam</option>
                                    <option value="Kristen">Kristen</option>
                                    <option value="Hindu">Hindu</option>
                                    <option value="Buddha">Buddha</option>
                                    <option value="Katolik">Katolik</option>
                                    <option value="Khonghucu">Khonghucu</option>
                                </select>
                                @error('agama')
                                <div class="valid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="almt_rumah" class="form-label">Alamat Rumah</label>
                                <input type="text" class="form-control @error('almt_rumah') is-invalid @enderror" id="almt_rumah" name="almt_rumah" value="{{ old('almt_rumah', $params['almt_rumah'] ?? '') }}" required>
                                @error('almt_rumah')
                                <div class="valid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="no_hp" class="form-label">No HP</label>
                                <input type="text" class="form-control @error('no_hp') is-invalid @enderror" id="no_hp" name="no_hp" value="{{ old('no_hp', $params['no_hp'] ?? '') }}" required>
                                @error('no_hp')
                                <div class="valid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="col-md-6 mb-3">
                                <label for="angkatan" class="form-label">Angkatan</label>
                                <input type="text" class="form-control @error('angkatan') is-invalid @enderror" id="angkatan" name="angkatan" value="{{ old('angkatan', $params['angkatan'] ?? '') }}" required>
                                @error('angkatan')
                                <div class="valid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Data Wali --}}
                            <div class="col-md-6 mb-3">
                                <label for="nm_wali" class="form-label">Nama Wali</label>
                                <input type="text" class="form-control @error('nm_wali') is-invalid @enderror" id="nm_wali" name="nm_wali" value="{{ old('nm_wali', $params['nm_wali'] ?? '') }}">
                                @error('nm_wali')
                                <div class="valid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="tgl_lahir_wali" class="form-label">Tanggal Lahir Wali</label>
                                <input type="date" class="form-control @error('tgl_lahir_wali') is-invalid @enderror" id="tgl_lahir_wali" name="tgl_lahir_wali" value="{{ old('tgl_lahir_wali', $params['tgl_lahir_wali'] ?? '') }}">
                                @error('tgl_lahir_wali')
                                <div class="valid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="no_telp_wali" class="form-label">No Telp Wali</label>
                                <input type="text" class="form-control @error('no_telp_wali') is-invalid @enderror" id="no_telp_wali" name="no_telp_wali" value="{{ old('no_telp_wali', $params['no_telp_wali'] ?? '') }}">
                                @error('no_telp_wali')
                                <div class="valid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary" id="save" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

