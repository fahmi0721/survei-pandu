import $ from 'jquery';

import 'datatables.net-bs5';

let table = null;

const escapeHtml = $.fn.dataTable.render.text();

function formatDate(value) {
    if (!value) return '—';
    return String(value).slice(0, 10);
}

function statusBadge(status) {

    const badges = {
        draft: '<span class="badge bg-secondary-lt">Draft</span>',
        active: '<span class="badge bg-success-lt">Aktif</span>',
        closed: '<span class="badge bg-danger-lt">Ditutup</span>',
    };

    return badges[status] || '—';
}

export function initSurveyTable() {

    if (!$('#tableSurveys').length) return;

    table = $('#tableSurveys').DataTable({

        processing: true,

        ajax: {
            url: $('#surveyModule').data('data-url'),
            dataSrc: 'data',
        },

        order: [[0, 'asc']],

        columns: [
            {
                data: 'title',
                render: (data, type) =>
                    type === 'display'
                        ? escapeHtml.display(data ?? '')
                        : data,
            },
            {
                data: null,
                render: (data, type, row) => {

                    const start = formatDate(row.start_date);
                    const end = formatDate(row.end_date);
                    const value = `${start} - ${end}`;

                    return type === 'display'
                        ? escapeHtml.display(value)
                        : value;
                },
            },
            {
                data: 'status',
                render: (data, type) =>
                    type === 'display'
                        ? statusBadge(data)
                        : data,
            },
            {
                data: 'sections_count',
                className: 'text-center',
            },
            {
                data: 'creator.name',
                defaultContent: '—',
                render: (data, type) =>
                    type === 'display'
                        ? escapeHtml.display(data ?? '—')
                        : data,
            },
            {
                data: 'id',
                orderable: false,
                searchable: false,
                className: 'table-actions',
                render: (id, type) => {

                    if (type !== 'display') return id;

                    const safeId = Number(id);

                    return `
                        <div class="btn-list flex-nowrap">
                            <a href="/admin/surveys/${Number(id)}/sections"
                                class="btn btn-sm btn-outline-success">
                                    <i class="ti ti-list-details"></i>
                                    Bagian
                                </a>

                            <button type="button"
                                    class="btn btn-sm btn-outline-primary btn-edit-survey"
                                    data-id="${safeId}">
                                <i class="ti ti-edit"></i>
                                Edit
                            </button>

                            <button type="button"
                                    class="btn btn-sm btn-outline-danger btn-delete-survey"
                                    data-id="${safeId}">
                                <i class="ti ti-trash"></i>
                                Hapus
                            </button>

                        </div>
                    `;
                },
            },
        ],

        language: {
            search: 'Cari:',
            lengthMenu: 'Tampilkan _MENU_ data',
            info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ survei',
            infoEmpty: 'Belum ada survei',
            zeroRecords: 'Data survei tidak ditemukan',
            emptyTable: 'Belum ada survei',
            processing: 'Memuat data...',
            paginate: {
                previous: 'Sebelumnya',
                next: 'Berikutnya',
            },
        },

    });

}

export function reloadSurveyTable() {
    table?.ajax.reload(null, false);
}