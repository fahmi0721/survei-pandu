import $ from 'jquery';
import 'datatables.net-bs5';

import { Notify } from '../../../plugins/notify';

let table = null;

const escapeText = $.fn.dataTable.render.text();

export function initRespondentTable() {

    const module = $('#respondentModule');

    if (!module.length) return;

    table = $('#tableRespondents').DataTable({

        processing: true,

        ajax: {
            url: module.attr('data-data-url'),

            data: function (d) {
                d.survey_id = $('#filterSurvey').val();
                d.status = $('#filterStatus').val();
                d.work_area_id = $('#filterWorkArea').val();
            },

            dataSrc: function (json) {

                $('#statTotal').text(json.summary?.total ?? 0);
                $('#statActive').text(json.summary?.aktif ?? 0);
                $('#statInactive').text(
                    json.summary?.tidak_aktif ?? 0
                );

                return json.data ?? [];
            },

            error: function (xhr) {
                Notify.error(
                    xhr.responseJSON?.message ||
                    'Gagal memuat daftar responden.'
                );
            },
        },

        order: [[6, 'desc']],

        columns: [
            {
                data: 'id',
                render: (data, type) =>
                    type === 'display'
                        ? `#${Number(data)}`
                        : Number(data),
            },
            {
                data: 'survey',
                render: $.fn.dataTable.render.text(),
            },
            {
                data: 'status',
                render: function (data, type) {
                    if (type !== 'display') return data;

                    return data === 'aktif'
                        ? '<span class="badge bg-success-lt">Aktif</span>'
                        : '<span class="badge bg-secondary-lt">Tidak Aktif</span>';
                },
            },
            {
                data: 'work_area',
                render: $.fn.dataTable.render.text(),
            },
            {
                data: 'kelas_pandu',
                render: $.fn.dataTable.render.text(),
            },
            {
                data: 'answers_count',
                className: 'text-center',
                render: (data) => Number(data),
            },
            {
                data: 'submitted_at',

                render: function (data, type, row) {
                    if (type === 'sort' || type === 'type') {
                        return row.submitted_at_sort ?? '';
                    }

                    return escapeText.display(data ?? '—');
                },
            },
            {
                data: 'id',
                orderable: false,
                searchable: false,

                render: function (id, type) {

                    if (type !== 'display') return id;

                    return `
                        <button type="button"
                                class="btn btn-sm btn-outline-primary btn-detail-respondent"
                                data-id="${Number(id)}">

                            <i class="ti ti-eye me-1"></i>
                            Detail

                        </button>
                    `;
                },
            },
        ],

        language: {
            search: 'Cari:',
            lengthMenu: 'Tampilkan _MENU_ data',
            info: 'Menampilkan _START_–_END_ dari _TOTAL_ data',
            infoEmpty: 'Belum ada respons',
            zeroRecords: 'Tidak ada respons yang sesuai',
            emptyTable: 'Belum ada respons survei',
            processing: 'Memuat...',
            paginate: {
                previous: 'Sebelumnya',
                next: 'Berikutnya',
            },
        },
    });

    $('#filterSurvey, #filterStatus, #filterWorkArea')
        .on('change', function () {
            table.ajax.reload();
        });
}

export function reloadRespondentTable() {
    table?.ajax.reload(null, false);
}