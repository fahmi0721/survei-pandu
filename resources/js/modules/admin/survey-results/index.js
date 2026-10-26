import $ from 'jquery';
import 'datatables.net-bs5';
import select2 from 'select2';
import 'select2/dist/css/select2.min.css';
import { renderSurveyCharts } from './charts';

if (typeof $.fn.select2 !== 'function') {
    select2(window, $);
}

function escapeHtml(value) {
    return $('<div>').text(value ?? '').html();
}

function renderSummary(sections) {
    const container = $('#resultSectionSummary');
    container.empty();

    if (!sections.length) {
        container.text('Belum tersedia bagian survei.');
        return;
    }

    sections.forEach((section, index) => {
        const rows = section.questions.map((q) => `
            <tr>
                <td>${escapeHtml(q.question_text)}</td>
                <td class="text-center">${Number(q.total_rank_1)}</td>
                <td class="text-center">
                    ${Number(q.percentage).toFixed(2)}%
                </td>
            </tr>
        `).join('');

        container.append(`
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        Bagian ${index + 1} —
                        ${escapeHtml(section.section_title)}
                    </h3>
                </div>

                <div class="card-body">
                    <div class="text-secondary mb-2">
                        Responden dengan ranking 1:
                        <strong>${Number(section.respondents)}</strong>
                    </div>

                    <div class="alert alert-success">
                        ${escapeHtml(section.conclusion)}
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-vcenter">
                            <thead>
                                <tr>
                                    <th>Aspek</th>
                                    <th class="text-center">Ranking 1</th>
                                    <th class="text-center">Persentase</th>
                                </tr>
                            </thead>
                            <tbody>${rows}</tbody>
                        </table>
                    </div>
                </div>
            </div>
        `);
    });
}

export function initSurveyResults() {
    const module = $('#surveyResultModule');

    if (!module.length) return;

    const workArea = $('#resultWorkArea');

    workArea.select2({
        width: '100%',
        placeholder: 'Semua Wilayah',
        allowClear: true,
    });

    const table = $('#resultRespondentsTable').DataTable({
        data: [],
        pageLength: 10,
        order: [[0, 'desc']],
        columns: [
            {
                data: 'response_id',
                render: (id) => `#${Number(id)}`,
            },
            {
                data: 'status',
                render: $.fn.dataTable.render.text(),
            },
            {
                data: 'wilayah',
                render: $.fn.dataTable.render.text(),
            },
            {
                data: 'priorities',
                orderable: false,
                searchable: false,
                render: (items) => {
                    return items.map((item) => `
                        <div class="mb-1">
                            <strong>
                                ${escapeHtml(item.section_title)}:
                            </strong>
                            ${escapeHtml(item.question_text)}
                        </div>
                    `).join('');
                },
            },
        ],
        language: {
            emptyTable: 'Belum ada jawaban ranking 1.',
            search: 'Cari:',
            info: 'Menampilkan _START_–_END_ dari _TOTAL_ responden',
            lengthMenu: 'Tampilkan _MENU_ responden',
            zeroRecords: 'Tidak ditemukan',
        },
    });

    async function loadResults() {
        const surveyId = $('#resultSurvey').val();

        if (!surveyId) {
            table.clear().draw();
            $('#resultSectionSummary').text('Belum ada survei.');
            return;
        }

        try {
            const result = await $.getJSON(module.data('url'), {
                survey_id: surveyId,
                status: $('#resultStatus').val(),
                work_area_id: workArea.val(),
            });

            table.clear();
            table.rows.add(result.respondents ?? []);
            table.draw();

            $('#resultTotalRespondents').text(
                result.summary.total_respondents
            );

            $('#resultTotalSections').text(
                result.summary.sections.length
            );

            renderSurveyCharts(result.summary);
            // Perbarui kesimpulan setiap bagian
            renderSummary(result.summary.sections);

        } catch (xhr) {
            console.error(xhr);

            $('#resultSectionSummary').text(
                xhr.responseJSON?.message ||
                'Gagal mengambil hasil survei.'
            );
        }
    }

    $('#resultSurvey, #resultStatus, #resultWorkArea')
        .on('change', loadResults);

    loadResults();
}