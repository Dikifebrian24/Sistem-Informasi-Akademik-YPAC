<div class="modal fade" id="kelasModalAdd" aria-labelledby="addKelasLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addKelasLabel">Add Kelas</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" id="saveKelas">
                    @csrf
                    <div>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label">Kode Kelas</label>
                                <input class="form-control" type="text" name="kd_kelas" id="kd_kelas" required>
                                @error('kd_kelas')
                                <div class="valid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nama Kelas</label>
                                <input class="form-control" type="text" name="nm_kelas" id="nm_kelas" required>
                                @error('nm_kelas')
                                <div class="valid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Wali Kelas</label>
                                <select class="form-control" id="wali_kelas" name="wali_kelas" required="">
                                    <option value="">-- Pilih Wali Kelas -- </option>
                                    @foreach($params['guru'] as $item)
                                        <option value="{{ $item->id_guru }}">{{ $item->nm_guru }} - {{ $item->nip }}</option>
                                    @endforeach
                                </select>
                                @error('wali_kelas')
                                <div class="valid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary" id="save" type="submit">Save</button>
                        <button class="btn btn-primary" id="saveEdit" type="submit" style="display: none;">Save edit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

