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

<div class="modal fade" id="siswaModalEdit" aria-labelledby="addSiswaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSiswaLabel">Edit Siswa</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" id="editSiswa">
                    @csrf
                    @method('PUT') {{-- atau input hidden jika pakai HTML murni --}}
                    <input type="hidden" id="edit_siswa_id" name="edit_siswa_id">
                    <div>
                        <div class="row g-2">
                            <div class="col-md-6  mb-3">
                                <label class="form-label">Nama Depan</label>
                                <input class="form-control" type="text" name="edit_first_name" id="edit_first_name">
                                @error('first_name')
                                <div class="valid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6  mb-3">
                                <label class="form-label">Nama Belakang</label>
                                <input class="form-control" type="text" name="edit_last_name" id="edit_last_name">
                                @error('last_name')
                                <div class="valid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6  mb-3">
                                <label class="form-label">NIK</label>
                                <input class="form-control" type="number" name="edit_nik" id="edit_nik">
                                @error('nik')
                                <div class="valid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6  mb-3">
                                <label class="form-label">NISN</label>
                                <input class="form-control" type="number" name="edit_nisn" id="edit_nisn">
                                @error('nisn')
                                <div class="valid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6  mb-3">
                                <label class="form-label">Email</label>
                                <input class="form-control" type="email" name="edit_email" id="edit_email">
                                @error('email')
                                <div class="valid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6  mb-3">
                                <label class="form-label">Password</label>
                                <input class="form-control" type="password" name="edit_password" id="edit_password">
                                @error('kd_jurusan')
                                <div class="valid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <hr>
                            <h5>Detail Biodata</h5>
                            <hr>
                            <div class="col-md-6  mb-3">
                                <label class="form-label">Kategori Kelainan</label>
                                <select class="form-select" id="edit_kelainan" name="edit_kelainan">
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
                                <select class="form-select" id="edit_jenkel" name="edit_jenkel">
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
                                <input class="form-control" type="text" name="edit_tmpt_lahir" id="edit_tmpt_lahir">
                                @error('tmpt_lahir')
                                <div class="valid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6  mb-3">
                                <label class="form-label">Tanggal Lahir</label>
                                <input class="form-control" type="date" name="edit_tgl_lahir" id="edit_tgl_lahir">
                                @error('tgl_lahir')
                                <div class="valid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6  mb-3">
                                {{--                                'Islam','Kristen','Hindu','Buddha','Katolik','Khonghucu'--}}
                                <label class="form-label">Agama</label>
                                <select class="form-select" id="edit_agama" name="edit_agama">
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
                                <input type="text" class="form-control @error('almt_rumah') is-invalid @enderror" id="edit_almt_rumah" name="edit_almt_rumah" value="{{ old('almt_rumah', $params['almt_rumah'] ?? '') }}">
                                @error('almt_rumah')
                                <div class="valid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="no_hp" class="form-label">No HP</label>
                                <input type="text" class="form-control @error('no_hp') is-invalid @enderror" id="edit_no_hp" name="edit_no_hp" value="{{ old('no_hp', $params['no_hp'] ?? '') }}">
                                @error('no_hp')
                                <div class="valid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="col-md-6 mb-3">
                                <label for="angkatan" class="form-label">Angkatan</label>
                                <input type="text" class="form-control @error('angkatan') is-invalid @enderror" id="edit_angkatan" name="edit_angkatan" value="{{ old('angkatan', $params['angkatan'] ?? '') }}">
                                @error('angkatan')
                                <div class="valid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Data Wali --}}
                            <div class="col-md-6 mb-3">
                                <label for="nm_wali" class="form-label">Nama Wali</label>
                                <input type="text" class="form-control @error('nm_wali') is-invalid @enderror" id="edit_nm_wali" name="edit_nm_wali" value="{{ old('nm_wali', $params['nm_wali'] ?? '') }}">
                                @error('nm_wali')
                                <div class="valid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="tgl_lahir_wali" class="form-label">Tanggal Lahir Wali</label>
                                <input type="date" class="form-control @error('tgl_lahir_wali') is-invalid @enderror" id="edit_tgl_lahir_wali" name="edit_tgl_lahir_wali" value="{{ old('tgl_lahir_wali', $params['tgl_lahir_wali'] ?? '') }}">
                                @error('tgl_lahir_wali')
                                <div class="valid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="no_telp_wali" class="form-label">No Telp Wali</label>
                                <input type="hidden" id="siswa_id" name="siswa_id">
                                <input type="text" class="form-control @error('no_telp_wali') is-invalid @enderror" id="edit_no_telp_wali" name="edit_no_telp_wali" value="{{ old('no_telp_wali', $params['no_telp_wali'] ?? '') }}">
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



<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Import Data Siswa</h5>
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
