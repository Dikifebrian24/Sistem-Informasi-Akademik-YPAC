<div class="modal fade" id="adminModalAdd" aria-labelledby="addKelasLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addKelasLabel">Add Kelas</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" id="saveAdmin">
                    @csrf
                    <div>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label">Nama Depan</label>
                                <input class="form-control" type="text" name="first_name" id="first_name" required>
                                @error('first_name')
                                <div class="valid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nama Belakang</label>
                                <input class="form-control" type="text" name="last_name" id="last_name" required>
                                @error('last_name')
                                <div class="valid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input class="form-control" type="email" name="email" id="email" required>
                                @error('email')
                                <div class="valid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Password</label>
                                <input class="form-control" type="password" name="password" id="password" required>
                                @error('kd_jurusan')
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

