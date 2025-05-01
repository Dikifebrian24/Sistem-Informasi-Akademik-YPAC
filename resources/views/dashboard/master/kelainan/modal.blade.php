<div class="modal fade" id="kelainanModalAdd" aria-labelledby="addKelasLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addKelasLabel">Add Data kelainan</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" id="saveKelainan">
                    @csrf
                    <div>
                        <div class="row g-2">
                            <div class="col-md-12">
                                <label class="form-label">Nama Kelainan</label>
                                <input class="form-control" type="text" name="nm_kelainan" id="nm_kelainan" required>
                                @error('nm_kelainan')
                                <div class="valid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Deskripsi</label>
                                <textarea class="form-control" name="desc_kelainan" id="desc_kelainan"></textarea>

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

