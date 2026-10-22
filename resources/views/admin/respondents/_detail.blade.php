<button type="button"
        id="openRespondentDetail"
        class="d-none"
        data-bs-toggle="modal"
        data-bs-target="#modalRespondentDetail">
</button>

<div class="modal modal-blur fade"
     id="modalRespondentDetail"
     tabindex="-1"
     aria-labelledby="respondentDetailTitle"
     aria-hidden="true">

    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title"
                    id="respondentDetailTitle">
                    Detail Jawaban Responden
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Tutup">
                </button>
            </div>

            <div class="modal-body">

                <div id="respondentInfo"
                     class="mb-4">
                </div>

                <div id="respondentAnswers">
                </div>

                <div id="respondentSuggestion"
                     class="mt-4">
                </div>

            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn"
                        data-bs-dismiss="modal">
                    Tutup
                </button>
            </div>

        </div>
    </div>
</div>