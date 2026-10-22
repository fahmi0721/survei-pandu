<button type="button"
        id="openSectionModal"
        class="d-none"
        data-bs-toggle="modal"
        data-bs-target="#modalSection">
</button>
<div class="modal modal-blur fade"
     id="modalSection"
     tabindex="-1"
     aria-labelledby="modalSectionTitle"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <form id="formSection">

                @csrf

                <input type="hidden" id="sectionId">

                <div class="modal-header">

                    <h5 class="modal-title" id="modalSectionTitle">
                        Tambah Bagian
                    </h5>

                    <button type="button"
                            class="btn-close"
                            id="closeSectionModal"
                            data-bs-dismiss="modal"
                            aria-label="Tutup">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label required">
                            Nama Bagian
                        </label>

                        <input type="text"
                               class="form-control"
                               name="title"
                               maxlength="255"
                               required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Deskripsi / Instruksi Bagian
                        </label>

                        <textarea name="description"
                                  class="form-control"
                                  rows="4"></textarea>

                        <small class="form-hint">
                            Isi dengan instruksi yang akan dibaca responden.
                        </small>

                    </div>

                    <div class="mb-3">

                        <label class="form-check form-switch">

                            <input class="form-check-input"
                                   type="checkbox"
                                   name="is_active"
                                   value="1"
                                   checked>

                            <span class="form-check-label">
                                Bagian Aktif
                            </span>

                        </label>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-link"
                            data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit"
                            id="btnSaveSection"
                            class="btn btn-primary">

                        <i class="ti ti-device-floppy me-1"></i>
                        Simpan

                    </button>

                </div>

            </form>

        </div>
    </div>
</div>