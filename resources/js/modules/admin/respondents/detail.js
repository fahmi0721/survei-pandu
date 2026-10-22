import $ from 'jquery';

import { Notify } from '../../../plugins/notify';

function escapeHtml(value) {
    return $('<div>').text(value ?? '').html();
}

function getError(xhr) {
    return xhr.responseJSON?.message ||
        'Gagal mengambil detail jawaban.';
}

function renderInfo(data) {

    $('#respondentInfo').empty().append(`
        <div class="card bg-secondary-lt">

            <div class="card-body">

                <h4 class="mb-3">
                    ${escapeHtml(data.survey)}
                </h4>

                <div class="row g-3">

                    <div class="col-md-4">
                        <div class="subheader">Status Kerja</div>
                        <div>${escapeHtml(data.status_kerja)}</div>
                    </div>

                    <div class="col-md-4">
                        <div class="subheader">Wilayah</div>
                        <div>${escapeHtml(data.work_area ?? '—')}</div>
                    </div>

                    <div class="col-md-4">
                        <div class="subheader">Kelas Pandu</div>
                        <div>${escapeHtml(data.kelas_pandu ?? '—')}</div>
                    </div>


                    <div class="col-md-4">
                        <div class="subheader">Kode Anonim</div>
                        <div>${escapeHtml(data.kode_anonim ?? '—')}</div>
                    </div>

                    <div class="col-md-4">
                        <div class="subheader">Waktu Pengisian</div>
                        <div>${escapeHtml(data.submitted_at ?? '—')}</div>
                    </div>

                </div>

            </div>
        </div>
    `);
}

function renderAnswers(answers) {

    const container = $('#respondentAnswers');
    container.empty();

    const grouped = new Map();

    answers.forEach(answer => {

        const sectionId = Number(answer.section_id);

        if (!grouped.has(sectionId)) {
            grouped.set(sectionId, {
                title: answer.section_title,
                order: answer.section_order,
                answers: [],
            });
        }

        grouped.get(sectionId).answers.push(answer);
    });

    const sections = [...grouped.values()]
        .sort((a, b) => a.order - b.order);

    if (!sections.length) {
        container.text('Belum terdapat jawaban.');
        return;
    }

    sections.forEach((section, index) => {

        const sortedAnswers = section.answers
            .sort((a, b) => a.ranking - b.ranking);

        const rows = sortedAnswers.map(answer => `
            <tr>
                <td class="text-center" style="width:90px">
                    <span class="badge bg-primary-lt">
                        ${Number(answer.ranking)}
                    </span>
                </td>

                <td>
                    ${escapeHtml(answer.question_text)}
                </td>
            </tr>
        `).join('');

        container.append(`
            <div class="card mb-3">

                <div class="card-header">
                    <h3 class="card-title">
                        Bagian ${index + 1} —
                        ${escapeHtml(section.title)}
                    </h3>
                </div>

                <div class="table-responsive">
                    <table class="table table-vcenter card-table">
                        <thead>
                            <tr>
                                <th>Ranking</th>
                                <th>Aspek Prioritas</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${rows}
                        </tbody>
                    </table>
                </div>

            </div>
        `);
    });
}

function renderSuggestion(saran) {

    const container = $('#respondentSuggestion');

    container.empty();

    if (!saran) return;

    container.append(`
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Saran dan Masukan</h3>
            </div>

            <div class="card-body">
                <div class="respondent-suggestion">
                    ${escapeHtml(saran)}
                </div>
            </div>
        </div>
    `);
}

export function initRespondentDetail() {

    $('#tableRespondents').on(
        'click',
        '.btn-detail-respondent',
        async function () {

            const id = Number($(this).data('id'));

            const baseUrl = $('#respondentModule')
                .attr('data-url');

            const button = $(this);

            button.prop('disabled', true);

            try {

                const response = await $.ajax({
                    url: `${baseUrl}/${id}`,
                    method: 'GET',
                    dataType: 'json',
                });

                renderInfo(response.data);

                renderAnswers(response.data.answers);

                renderSuggestion(response.data.saran);

                $('#openRespondentDetail').trigger('click');

            } catch (xhr) {

                Notify.error(getError(xhr));

            } finally {

                button.prop('disabled', false);

            }

        }
    );
}