import $ from 'jquery';
import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import Sortable from 'sortablejs';
import { initSelect2Survey,initStatusKerja, } from './modules/survey/select2';
import { Alert } from './plugins/sweetalert';
import { Notify } from './plugins/notify';

$(function () {
    if (!document.getElementById('publicSurveyApp')) return;
    const app = $('#publicSurveyApp');
    const submitUrl = app.attr('data-submit-url');
    const surveyId = Number($('#publicSurveyApp').attr('data-survey-id'));
    const tokenKey = `survey-submission-${surveyId}`;
    let submissionToken = sessionStorage.getItem(tokenKey);
    if (!submissionToken) {
        submissionToken = crypto.randomUUID();
        sessionStorage.setItem(tokenKey, submissionToken);
    }
    let isSubmitted = false;
    let isSubmitting = false;

    const sections = JSON.parse(document.getElementById('surveySectionsData').textContent);
    
    const orders = sections.map(s => s.items.map(q => q.id));
    const confirmed = sections.map(() => false);
    let current = 0;
    let sortable = null;

    const el = id => document.getElementById(id);
    const countConfirmed = () => confirmed.filter(Boolean).length;
    const notify = message => { Notify.warning(message); }; // Dapat diganti dengan Notyf.

    async function submitSurvey() {

        if (isSubmitting || isSubmitted) {
            return;
        }

        // Seluruh bagian wajib dikonfirmasi.
        if (confirmed.some(value => !value)) {

            Notify.warning(
                'Masih ada bagian survei yang belum dikonfirmasi.'
            );

            return;
        }

        const data = payload();

        // Validasi frontend wilayah kerja.
        if (
            data.respondent.status_kerja === 'aktif' &&
            !data.respondent.work_area_id
        ) {

            Notify.warning(
                'Wilayah kerja wajib dipilih untuk responden aktif.'
            );

            show('intro');

            $('#wilayah').select2('open');

            return;
        }

        const confirmation = await Swal.fire({
            icon: 'question',
            title: 'Simpan Jawaban Survei?',
            text: 'Pastikan seluruh jawaban sudah benar. Jawaban yang dikirim tidak dapat diubah kembali.',
            showCancelButton: true,
            confirmButtonText: 'Ya, Simpan Jawaban',
            cancelButtonText: 'Periksa Kembali',
            reverseButtons: true,
        });

        if (!confirmation.isConfirmed) {
            return;
        }

        isSubmitting = true;

        const button = $('#btnSubmitSurvey');

        button.prop('disabled', true);

        $('#submitText').text('Menyimpan...');

        try {

            const response = await $.ajax({
                url: submitUrl,
                method: 'POST',

                contentType: 'application/json',

                dataType: 'json',

                data: JSON.stringify(data),

                headers: {
                    'X-CSRF-TOKEN':
                        $('meta[name="csrf-token"]').attr('content'),

                    'Accept': 'application/json',
                },
            });


            if (!response.success || !response.redirect) {
                throw new Error('Respons server tidak valid.');
            }

            isSubmitted = true;

            window.location.assign(response.redirect);

        } catch (xhr) {
            console.log(xhr);
            let message =
                xhr.responseJSON?.message ||
                'Terjadi kesalahan saat menyimpan jawaban.';

            const errors = xhr.responseJSON?.errors;

            if (errors) {
                message = Object.values(errors)
                    .flat()
                    .join('\n');
            }

            if (xhr.status === 419) {
                message =
                    'Sesi formulir telah berakhir. Muat ulang halaman sebelum mencoba kembali.';
            }

            if (xhr.status === 429) {
                message =
                    'Terlalu banyak percobaan pengiriman. Silakan coba kembali nanti.';
            }

            await Swal.fire({
                icon: 'error',
                title: 'Gagal Menyimpan',
                text: message,
            });

        } finally {

            isSubmitting = false;

            if (!isSubmitted) {
                button.prop('disabled', false);
                $('#submitText').text('Simpan Jawaban');
            }

        }
    }

    function show(id) {
        for (const name of ['intro', 'survey', 'finish']) {
            el(name).classList.toggle('hidden', name !== id);
        }
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function markChanged() {
        confirmed[current] = false;
    }

    function reorder(from, to) {
        if (from < 0 || to < 0 || to >= orders[current].length || from === to) return;
        const [value] = orders[current].splice(from, 1);
        orders[current].splice(to, 0, value);
        markChanged();
        render();
    }

    function makeButton(text, className, title, callback, disabled = false) {
        const button = document.createElement('button');
        button.type = 'button';
        button.className = className;
        button.textContent = text;
        button.title = title;
        button.disabled = disabled;
        button.addEventListener('click', callback);
        return button;
    }

    function render() {
        if (sortable) { sortable.destroy(); sortable = null; }
        const section = sections[current];
        el('partlabel').textContent = `BAGIAN ${current + 1} DARI ${sections.length}`;
        el('counter').textContent = `${countConfirmed()} / ${sections.length} bagian dikonfirmasi`;
        el('bar').style.width = `${countConfirmed() / sections.length * 100}%`;
        el('parttitle').textContent = section.title;
        el('partquestion').textContent = section.question;
        el('steps').replaceChildren();

        sections.forEach((_, idx) => {
            const button = makeButton(String(idx + 1),
                `step ${idx === current ? 'current' : ''} ${confirmed[idx] ? 'done' : ''}`,
                `Bagian ${idx + 1}`, () => { current = idx; render(); });
            el('steps').append(button);
        });

        const list = el('ranklist');
        list.replaceChildren();
        orders[current].forEach((questionId, index) => {
            const question = section.items.find(q => q.id === questionId);
            const li = document.createElement('li');
            li.className = 'rankitem';
            li.dataset.questionId = questionId;

            const num = document.createElement('span');
            num.className = 'ranknum';
            num.textContent = String(index + 1);
            num.title = 'Geser untuk mengubah ranking';

            const content = document.createElement('span');
            content.className = 'rankcontent';
            const text = document.createElement('span');
            text.className = 'ranktext';
            text.textContent = question.text;
            content.append(text);
            if (question.description) {
                const description = document.createElement('small');
                description.className = 'rankdescription';
                description.textContent = question.description;
                content.append(description);
            }

            const actions = document.createElement('span');
            actions.className = 'move';
            actions.append(
                makeButton('↑', '', 'Naikkan prioritas', () => reorder(index, index - 1), index === 0),
                makeButton('↓', '', 'Turunkan prioritas', () => reorder(index, index + 1), index === orders[current].length - 1)
            );
            li.append(num, content, actions);
            list.append(li);
        });

        sortable = Sortable.create(list, {
            animation: 150,
            handle: '.ranknum',
            ghostClass: 'dragging',
            onEnd(event) { reorder(event.oldIndex, event.newIndex); }
        });

        el('confirm').checked = confirmed[current];
        el('back').textContent = current === 0 ? '← Petunjuk' : '← Sebelumnya';
        el('next').textContent = current === sections.length - 1 ? 'Lihat ringkasan →' : 'Berikutnya →';
    }

    function finish() {
        const summary = el('summary');
        summary.replaceChildren();
        sections.forEach((section, i) => {
            const top = section.items.find(q => q.id === orders[i][0]);
            const div = document.createElement('div');
            div.className = 'result';
            const title = document.createElement('strong');
            title.textContent = `Bagian ${i + 1} – ${section.title}`;
            const first = document.createElement('p');
            first.textContent = `Prioritas 1: ${top.text}`;
            div.append(title, first);
            summary.append(div);
        });
        show('finish');
    }

    function payload() {

        const statusKerja = $('#status').val();

        const workAreaId = $('#wilayah').val();

        return {
            submission_token: submissionToken,

            respondent: {
                status: statusKerja,

                work_area_id: statusKerja === 'aktif' && workAreaId
                    ? Number(workAreaId)
                    : null,

                kelas_pandu: $('#kelas').val() || null,

                kode_anonim: $('#kode').val()?.trim() || null,
            },

            saran: $('#comment').val()?.trim() || null,

            answers: sections.flatMap((section, index) => {

                return orders[index].map((questionId, rank) => ({

                    survey_section_id: Number(section.id),

                    survey_question_id: Number(questionId),

                    ranking: rank + 1,

                }));

            }),
        };
    }

    

   

    el('confirm').addEventListener('change', event => {
        confirmed[current] = event.target.checked;
        render();
    });
    el('start').addEventListener('click', () => { 
         const statusKerja = $('#status').val();
        const workAreaId = $('#wilayah').val();
        if (!statusKerja) {
            Notify.warning('Silakan pilih status kerja.');
            return;
        }
        if (statusKerja === 'aktif' && !workAreaId) {
            Notify.warning(
                'Wilayah kerja wajib dipilih untuk responden aktif.'
            );
            $('#wilayah').select2('open');
            return;
        }
        current = 0;
        render();
        show('survey');
    });

    el('back').addEventListener('click', () => {
        if (current === 0) { show('intro'); return; }
        current--; render();
    });
    el('next').addEventListener('click', () => {
        if (!confirmed[current]) { notify('Periksa dan centang konfirmasi ranking sebelum melanjutkan.'); return; }
        if (current < sections.length - 1) { current++; render(); return; }
        const missing = confirmed.findIndex(value => !value);
        if (missing >= 0) { current = missing; render(); notify('Masih ada bagian yang belum dikonfirmasi.'); return; }
        finish();
    });
    el('edit').addEventListener('click', () => { current = sections.length - 1; render(); show('survey'); });
    $('#btnSubmitSurvey').on('click', submitSurvey);
    
});

document.addEventListener('DOMContentLoaded', () => {
    initSelect2Survey();
    initStatusKerja();
});