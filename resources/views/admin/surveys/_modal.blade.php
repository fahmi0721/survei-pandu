{{-- Tombol pemicu modal internal --}}
<button type="button"
        id="openSurveyModal"
        class="d-none"
        data-bs-toggle="modal"
        data-bs-target="#modalSurvey">
</button>

<div class="modal modal-blur fade"
     id="modalSurvey"
     tabindex="-1"
     aria-labelledby="modalSurveyTitle"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <form id="formSurvey">

                @csrf

                <input type="hidden" id="surveyId">

                <div class="modal-header">

                    <h5 class="modal-title" id="modalSurveyTitle">
                        Tambah Survei
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label required">
                            Judul Survei
                        </label>

                        <input type="text"
                               class="form-control"
                               name="title"
                               placeholder="Masukkan Judul Survey"
                               maxlength="255">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Deskripsi
                        </label>

                        <textarea name="description"
                                placeholder="Masukkan Deskripsi Survey"
                                class="form-control"
                                rows="3"></textarea>
                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Tanggal Mulai
                            </label>

                            <input type="date"
                                placeholder="Masukkan Tanggal Mulai"
                                name="start_date"
                                id="start_date"
                                class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Tanggal Selesai
                            </label>

                            <input type="date"
                                placeholder="Masukkan Tanggal Selesai"
                                name="end_date"
                                id="end_date"
                                class="form-control">
                        </div>

                    </div>

                    <div class="mb-3">

                        <label class="form-label required">
                            Status
                        </label>

                        <select name="status"
                                class="form-select"
                                required>
                            <option value="draft">
                                Draft
                            </option>
                            <option value="active">
                                Aktif
                            </option>
                            <option value="closed">
                                Ditutup
                            </option>

                        </select>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button"
                            id="closeSurveyModal"
                            class="btn btn-link link-secondary"
                            data-bs-dismiss="modal">

                        Batal

                    </button>

                    <button type="submit"
                            class="btn btn-primary"
                            id="btnSaveSurvey">

                        <i class="ti ti-device-floppy me-1"></i>
                        Simpan Survei

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>