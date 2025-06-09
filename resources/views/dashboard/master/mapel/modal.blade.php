<div class="modal fade" id="mapelModalAdd" aria-labelledby="addMapelLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMapelLabel">Add Mapel</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" id="saveMapel">
                    @csrf
                    <div>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label">Nama Mapel</label>
                                <input class="form-control" type="text" name="nm_mapel" id="nm_mapel" required>
                                @error('nm_mapel')
                                <div class="valid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Golongan Kelas</label>
                                <select class="form-select" id="kelas" name="kelas" required>
                                    <option value="">-- Pilih Kelas --</option>
                                </select>
                                @error('id_kelas')
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

<!-- Modal Edit Mapel -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Mapel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-id">
                    <div class="mb-3">
                        <label for="edit-nm_mapel" class="form-label">Nama Mapel</label>
                        <input type="text" class="form-control" id="edit-nm_mapel" name="nm_mapel" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-id_kelas" class="form-label">Kelas</label>
                        <select class="form-control" id="edit-id_kelas" name="id_kelas" required>
                            <!-- isi dari controller (opsional bisa load dinamis) -->
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </form>
    </div>
</div>

