<button type="button"
        id="openQuestionModal"
        class="d-none"
        data-bs-toggle="modal"
        data-bs-target="#modalQuestion">
</button>

<div class="modal modal-blur fade"
     id="modalQuestion"
     tabindex="-1"
     aria-labelledby="modalQuestionTitle"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <form id="formQuestion">

                @csrf

                <input type="hidden" id="questionId">

                <div class="modal-header">

                    <h5 class="modal-title"
                        id="modalQuestionTitle">
                        Tambah Pertanyaan
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Tutup">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label required">
                            Pertanyaan / Aspek Prioritas
                        </label>

                        <textarea name="question_text"
                                  class="form-control"
                                  rows="3"
                                  maxlength="5000"
                                  required
                                  placeholder="Masukkan aspek prioritas yang akan diberi ranking oleh responden"></textarea>

                        <small class="form-hint">
                            Contoh: Penghasilan tetap yang cukup
                            untuk memenuhi kebutuhan sehari-hari.
                        </small>

                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            Deskripsi Pertanyaan
                        </label>

                        <textarea name="description"
                                class="form-control"
                                rows="5"
                                maxlength="10000"
                                placeholder="Masukkan penjelasan tambahan (opsional)"></textarea>

                        <small class="form-hint">
                            Deskripsi digunakan untuk memberikan
                            penjelasan tambahan
                        </small>

                    </div>

                    <div class="mb-3">

                        <label class="form-check form-switch">

                            <input type="checkbox"
                                   name="is_active"
                                   class="form-check-input"
                                   value="1"
                                   checked>

                            <span class="form-check-label">
                                Pertanyaan Aktif
                            </span>

                        </label>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-link"
                            data-bs-dismiss="modal"
                            id="btnCloseQuestion">

                        Batal

                    </button>

                    <button type="submit"
                            class="btn btn-primary"
                            id="btnSaveQuestion">

                        <i class="ti ti-device-floppy me-1"></i>
                        Simpan

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>