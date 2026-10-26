import $ from 'jquery';
import 'datatables.net-bs5';
import Swal from 'sweetalert2';

import { Notify } from '../../../plugins/notify';

export function initUsers() {
    const module = $('#userModule');

    if (!module.length) return;

    const baseUrl = module.attr('data-url');
    const form = $('#formUser');
    const modal = $('#modalUser');

    const csrf = $('meta[name="csrf-token"]').attr('content');
    const text = $.fn.dataTable.render.text();

    const table = $('#tableUsers').DataTable({
        ajax: {
            url: module.attr('data-data-url'),
            dataSrc: 'data',
        },

        columns: [
            {
                data: 'name',
                render: $.fn.dataTable.render.text(),
            },
            {
                data: 'email',
                render: $.fn.dataTable.render.text(),
            },
            {
                data: 'role',
                render: () =>
                    '<span class="badge bg-primary-lt">HRD</span>',
            },
            {
                data: 'created_at',
                render: (value, type) => {
                    if (!value) return '—';
                    if (type !== 'display') return value;

                    return text.display(
                        new Date(value).toLocaleString('id-ID')
                    );
                },
            },
            {
                data: 'id',
                orderable: false,
                searchable: false,
                render: (id) => `
                    <div class="btn-list flex-nowrap">

                        <button type="button"
                                class="btn btn-sm btn-outline-primary btn-edit-user"
                                data-id="${Number(id)}">
                            Edit
                        </button>

                        <button type="button"
                                class="btn btn-sm btn-outline-warning btn-reset-password"
                                data-id="${Number(id)}">
                            <i class="ti ti-key me-1"></i>
                            Reset Password
                        </button>

                        <button type="button"
                                class="btn btn-sm btn-outline-danger btn-delete-user"
                                data-id="${Number(id)}">
                            Hapus
                        </button>

                    </div>
                `,
            },
        ],

        language: {
            emptyTable: 'Belum ada akun HRD.',
            search: 'Cari:',
            zeroRecords: 'Tidak ada user ditemukan.',
        },
    });

    // Membuka modal reset password
    $('#tableUsers').on(
        'click',
        '.btn-reset-password',
        function () {

            const id = Number($(this).data('id'));

            const form = $('#formResetPassword')[0];

            form.reset();

            $('#resetUserId').val(id);

            $('#openResetPasswordModal').trigger('click');
        }
    );


    // Proses reset password
    $('#formResetPassword').on('submit', async function (event) {

        event.preventDefault();

        const id = Number($('#resetUserId').val());

        const form = $(this);

        const confirmation = await Swal.fire({
            icon: 'warning',
            title: 'Reset Password?',
            text: 'Password akun HRD akan diganti dengan password baru.',
            showCancelButton: true,
            confirmButtonText: 'Ya, Reset',
            cancelButtonText: 'Batal',
        });

        if (!confirmation.isConfirmed) return;

        const button = $('#btnSaveResetPassword');

        button.prop('disabled', true);

        try {

            const response = await $.ajax({
                url: `${baseUrl}/${id}/reset-password`,

                method: 'PATCH',

                data: {
                    admin_password:
                        form.find('[name="admin_password"]').val(),

                    password:
                        form.find('[name="password"]').val(),

                    password_confirmation:
                        form.find('[name="password_confirmation"]').val(),
                },

                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                },
            });

            $('#modalResetPassword')
                .find('[data-bs-dismiss="modal"]')
                .first()
                .trigger('click');

            form[0].reset();

            Notify.success(response.message);

            table.ajax.reload(null, false);

        } catch (xhr) {

            const errors = xhr.responseJSON?.errors;

            const message = errors
                ? Object.values(errors).flat().join('\n')
                : xhr.responseJSON?.message ||
                'Gagal mereset password.';

            await Swal.fire({
                icon: 'error',
                title: 'Reset Password Gagal',
                text: message,
            });

        } finally {

            button.prop('disabled', false);

        }
    });

    function showError(xhr) {
        const errors = xhr.responseJSON?.errors;

        const message = errors
            ? Object.values(errors).flat().join('\n')
            : xhr.responseJSON?.message || 'Terjadi kesalahan.';

        Notify.error(message);
    }

    function openModal() {
        // Menggunakan Bootstrap/Tabler modal yang sudah dimuat.
        $('#userModalTrigger').trigger('click');
    }

    function closeModal() {
        modal.find('[data-bs-dismiss="modal"]').first().trigger('click');
    }

    $('#btnAddUser').on('click', function () {
        form[0].reset();
        $('#userId').val('');
        $('#userModalTitle').text('Tambah User HRD');
        $('#userPassword').prop('required', true);
        $('#passwordHelp').text(
            'Minimal 12 karakter, mengandung huruf dan angka.'
        );
    });

    $('#tableUsers').on('click', '.btn-edit-user', async function () {
        const id = Number($(this).data('id'));

        try {
            const response = await $.getJSON(`${baseUrl}/${id}`);

            form[0].reset();
            $('#userId').val(id);

            form.find('[name="name"]').val(response.data.name);
            form.find('[name="email"]').val(response.data.email);

            $('#userModalTitle').text('Edit User HRD');
            $('#userPassword').prop('required', false);
            $('#passwordHelp').text(
                'Kosongkan jika tidak ingin mengubah password.'
            );

            openModal();
        } catch (xhr) {
            showError(xhr);
        }
    });

    form.on('submit', async function (event) {
        event.preventDefault();

        const id = $('#userId').val();

        const data = {
            name: form.find('[name="name"]').val(),
            email: form.find('[name="email"]').val(),
            password: form.find('[name="password"]').val(),
            password_confirmation:
                form.find('[name="password_confirmation"]').val(),
        };

        if (id) data._method = 'PUT';

        const button = $('#btnSaveUser').prop('disabled', true);

        try {
            const response = await $.ajax({
                url: id ? `${baseUrl}/${id}` : module.attr('data-store-url'),
                method: 'POST',
                data,
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                },
            });

            closeModal();
            Notify.success(response.message);
            table.ajax.reload(null, false);

        } catch (xhr) {
            showError(xhr);
        } finally {
            button.prop('disabled', false);
        }
    });

    $('#tableUsers').on('click', '.btn-delete-user', async function () {
        const id = Number($(this).data('id'));

        const result = await Swal.fire({
            icon: 'warning',
            title: 'Hapus akun HRD?',
            text: 'Akun ini tidak dapat digunakan untuk login setelah dihapus.',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d63939',
        });

        if (!result.isConfirmed) return;

        try {
            const response = await $.ajax({
                url: `${baseUrl}/${id}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                },
            });

            Notify.success(response.message);
            table.ajax.reload(null, false);

        } catch (xhr) {
            showError(xhr);
        }
    });
}