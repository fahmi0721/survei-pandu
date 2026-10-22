import $ from 'jquery';
import 'datatables.net-bs5';

import { Alert } from '../../../plugins/sweetalert';
import { Notify } from '../../../plugins/notify';

export function initWorkAreas() {

    const module = $('#workAreaModule');

    if (!module.length) return;

    const form = $('#formWorkArea');
    const modal = $('#modalWorkArea');
    const baseUrl = module.attr('data-base-url');

    const csrf = $('meta[name="csrf-token"]').attr('content');

    const escape = $.fn.dataTable.render.text();

    const table = $('#tableWorkAreas').DataTable({
        ajax: {
            url: module.attr('data-data-url'),
            dataSrc: 'data',
        },

        columns: [
            {
                data: 'kode',
                render: $.fn.dataTable.render.text(),
            },
            {
                data: 'nama',
                render: $.fn.dataTable.render.text(),
            },
            {
                data: 'keterangan',
                defaultContent: '',
                render: (data, type) =>
                    type === 'display'
                        ? escape.display(data ?? '')
                        : data,
            },
            {
                data: 'is_active',
                render: (data, type) => {
                    if (type !== 'display') return Number(data);

                    return data
                        ? '<span class="badge bg-success-lt">Aktif</span>'
                        : '<span class="badge bg-secondary-lt">Nonaktif</span>';
                },
            },
            {
                data: 'id',
                orderable: false,
                searchable: false,
                render: (id, type) => {
                    if (type !== 'display') return id;

                    return `
                        <div class="btn-list flex-nowrap">
                            <button type="button"
                                    class="btn btn-sm btn-outline-primary edit-area"
                                    data-id="${Number(id)}">
                                Edit
                            </button>

                            <button type="button"
                                    class="btn btn-sm btn-outline-danger delete-area"
                                    data-id="${Number(id)}">
                                Nonaktifkan
                            </button>
                        </div>
                    `;
                },
            },
        ],
    });

    function getError(xhr) {
        const errors = xhr.responseJSON?.errors;

        return errors
            ? Object.values(errors).flat().join(', ')
            : xhr.responseJSON?.message || 'Terjadi kesalahan.';
    }

    function closeModal() {
        modal.find('[data-bs-dismiss="modal"]')
            .first()
            .trigger('click');
    }

    $('#btnAddWorkArea').on('click', function () {
        form[0].reset();
        $('#workAreaId').val('');
        $('#workAreaModalTitle').text('Tambah Wilayah Kerja');
    });

    $('#tableWorkAreas').on('click', '.edit-area', async function () {
        try {
            const response = await $.getJSON(
                `${baseUrl}/${Number($(this).data('id'))}`
            );

            const area = response.data;

            form[0].reset();

            $('#workAreaId').val(area.id);
            form.find('[name="kode"]').val(area.kode);
            form.find('[name="nama"]').val(area.nama);
            form.find('[name="keterangan"]').val(area.keterangan ?? '');
            form.find('[name="is_active"]').prop(
                'checked',
                Boolean(area.is_active)
            );

            $('#workAreaModalTitle').text('Edit Wilayah Kerja');

            // Memakai runtime modal Tabler yang sudah ada
            $('#btnAddWorkArea').trigger('click');

            // Reset ulang data edit, karena handler tambah
            // dijalankan oleh tombol pemicu yang sama.
            $('#workAreaId').val(area.id);
            form.find('[name="kode"]').val(area.kode);
            form.find('[name="nama"]').val(area.nama);
            form.find('[name="keterangan"]').val(area.keterangan ?? '');
            form.find('[name="is_active"]').prop(
                'checked', Boolean(area.is_active)
            );
            $('#workAreaModalTitle').text('Edit Wilayah Kerja');

        } catch (xhr) {
            Notify.error(getError(xhr));
        }
    });

    form.on('submit', async function (e) {
        e.preventDefault();

        const id = $('#workAreaId').val();

        const data = {
            kode: form.find('[name="kode"]').val(),
            nama: form.find('[name="nama"]').val(),
            keterangan: form.find('[name="keterangan"]').val(),
            is_active: form.find('[name="is_active"]').is(':checked') ? 1 : 0,
        };

        if (id) data._method = 'PUT';

        const button = $('#btnSaveWorkArea').prop('disabled', true);

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
            Notify.error(getError(xhr));
        } finally {
            button.prop('disabled', false);
        }
    });

    $('#tableWorkAreas').on('click', '.delete-area', async function () {
        const id = Number($(this).data('id'));

        const result = await Alert.confirm(
            'Nonaktifkan wilayah kerja ini?'
        );

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
            Notify.error(getError(xhr));
        }
    });
}