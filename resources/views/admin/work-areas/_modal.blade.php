<div class="modal modal-blur fade"
     id="modalWorkArea"
     tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form id="formWorkArea">
                @csrf

                <input type="hidden" id="workAreaId">

                <div class="modal-header">
                    <h5 class="modal-title" id="workAreaModalTitle">
                        Tambah Wilayah Kerja
                    </h5>
                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label required">
                            Kode Wilayah
                        </label>
                        <input type="text"
                               name="kode"
                               class="form-control"
                               placeholder="Masukkan Kode Wilayah"
                               maxlength="30"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">
                            Nama Wilayah
                        </label>
                        <input type="text"
                               name="nama"
                               placeholder="Masukkan Nama Wilayah"
                               class="form-control"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan"
                                  class="form-control"
                                  placeholder="Masukkan Keterangan"
                                  rows="3"></textarea>
                    </div>

                    <label class="form-check form-switch">
                        <input type="checkbox"
                               name="is_active"
                               class="form-check-input"
                               checked>
                        <span class="form-check-label">Aktif</span>
                    </label>

                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn"
                            data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit"
                            class="btn btn-primary"
                            id="btnSaveWorkArea">
                        Simpan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>