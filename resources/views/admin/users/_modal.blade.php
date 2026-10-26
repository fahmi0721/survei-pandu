<button type="button"
        id="userModalTrigger"
        class="d-none"
        data-bs-toggle="modal"
        data-bs-target="#modalUser"></button>
<div class="modal modal-blur fade"
     id="modalUser"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form id="formUser">
                @csrf

                <input type="hidden" id="userId">

                <div class="modal-header">
                    <h5 id="userModalTitle" class="modal-title">
                        Tambah User HRD
                    </h5>
                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label required">Nama</label>
                        <input type="text"
                               class="form-control"
                               placeholder="Masukkan Nama"
                               name="name"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Email</label>
                        <input type="email"
                               class="form-control"
                               placeholder="Masukkan Email"
                               name="email"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <input class="form-control"
                               value="HRD"
                               disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="userPassword">
                            Password
                        </label>
                        <input id="userPassword"
                               type="password"
                               class="form-control"
                               placeholder="Masukkan Password Minimal 8 Karakter"
                               name="password"
                               minlength="8"
                               autocomplete="new-password">
                        <small id="passwordHelp"
                               class="text-secondary">
                            Minimal 12 karakter, mengandung huruf dan angka.
                        </small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Konfirmasi Password
                        </label>
                        <input type="password"
                               class="form-control"
                               placeholder="Pastikan Password sama dengan diatas"
                               name="password_confirmation"
                               autocomplete="new-password">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn"
                            data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit"
                            class="btn btn-primary"
                            id="btnSaveUser">
                        Simpan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>