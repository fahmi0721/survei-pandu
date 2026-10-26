<button type="button"
        id="openResetPasswordModal"
        class="d-none"
        data-bs-toggle="modal"
        data-bs-target="#modalResetPassword">
</button>

<div class="modal modal-blur fade"
     id="modalResetPassword"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <form id="formResetPassword">
                @csrf

                <input type="hidden" id="resetUserId">

                <div class="modal-header">
                    <h5 class="modal-title">
                        Reset Password HRD
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">

                    <div class="alert alert-info">
                        Password akun HRD akan diganti.
                        Password lama tidak dapat dilihat kembali.
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">
                            Password Admin
                        </label>

                        <input type="password"
                               name="admin_password"
                               class="form-control"
                               autocomplete="current-password"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">
                            Password Baru HRD
                        </label>

                        <input type="password"
                               name="password"
                               class="form-control"
                               minlength="8"
                               autocomplete="new-password"
                               required>

                        <small class="form-hint">
                            Minimal 12 karakter, mengandung
                            huruf dan angka.
                        </small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">
                            Konfirmasi Password Baru
                        </label>

                        <input type="password"
                               name="password_confirmation"
                               class="form-control"
                               minlength="8"
                               autocomplete="new-password"
                               required>
                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button"
                            class="btn"
                            data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit"
                            class="btn btn-warning"
                            id="btnSaveResetPassword">
                        <i class="ti ti-key me-1"></i>
                        Reset Password
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>