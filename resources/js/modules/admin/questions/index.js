import $ from 'jquery';
import Sortable from 'sortablejs';

import { Alert } from '../../../plugins/sweetalert';
import { Notify } from '../../../plugins/notify';
import { QuestionApi } from './api';

const escapeHtml = value =>
    $('<div>').text(value ?? '').html();

function errorMessage(xhr) {
    const errors = xhr.responseJSON?.errors;

    if (errors) {
        return Object.values(errors).flat().join(', ');
    }

    return xhr.responseJSON?.message ||
        'Terjadi kesalahan pada server.';
}

function showModal() {
    $('#openQuestionModal').trigger('click');
}

function hideModal() {
    $('#modalQuestion [data-bs-dismiss="modal"]')
        .first()
        .trigger('click');
}

// ===================================
// LOAD QUESTIONS
// ===================================

async function loadQuestions() {

    const list = $('#questionList');

    $('#questionLoading').removeClass('d-none');

    try {

        const response = await QuestionApi.list();
        const questions = response.data;

        list.empty();

        $('#questionCount').text(
            `${questions.length} Pertanyaan`
        );

        $('#questionEmpty').toggleClass(
            'd-none',
            questions.length !== 0
        );

        questions.forEach((item, index) => {

            const status = item.is_active
                ? '<span class="badge bg-success-lt">Aktif</span>'
                : '<span class="badge bg-secondary-lt">Nonaktif</span>';

            list.append(`
                <div class="list-group-item question-item"
                    data-id="${Number(item.id)}">

                    <div class="d-flex align-items-start gap-3 question-row">

                        <div class="question-left d-flex align-items-center gap-3">
                            <span class="question-drag-handle"
                                title="Geser untuk mengurutkan">
                                <i class="ti ti-grip-vertical fs-2"></i>
                            </span>

                            <span class="badge bg-primary-lt question-number">
                                ${index + 1}
                            </span>
                        </div>

                        <div class="flex-fill question-content">

                            <div class="fw-semibold question-text">
                                ${escapeHtml(item.question_text)}
                            </div>

                            ${
                                item.description
                                    ? `
                                        <div class="text-secondary small mt-2">
                                            ${escapeHtml(item.description)}
                                        </div>
                                    `
                                    : ''
                            }

                            <div class="mt-3 d-flex align-items-center gap-2 flex-wrap">
                                ${status}
                            </div>
                        </div>

                        <div class="btn-list flex-nowrap question-actions">
                            <button type="button"
                                    class="btn btn-sm btn-outline-primary btn-edit-question"
                                    data-id="${Number(item.id)}">
                                <i class="ti ti-edit"></i>
                            </button>

                            <button type="button"
                                    class="btn btn-sm btn-outline-danger btn-delete-question"
                                    data-id="${Number(item.id)}">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>

                    </div>

                </div>
            `);

        });

    } catch (xhr) {

        Notify.error(errorMessage(xhr));

    } finally {

        $('#questionLoading').addClass('d-none');

    }
}

// ===================================
// FORM TAMBAH & EDIT
// ===================================

function initQuestionForm() {

    const form = $('#formQuestion');

    $('#btnAddQuestion').on('click', function () {

        form[0].reset();

        $('#questionId').val('');
        $('#modalQuestionTitle').text('Tambah Pertanyaan');

        form.find('[name="is_active"]')
            .prop('checked', true);

        showModal();

    });

    $('#questionList').on(
        'click',
        '.btn-edit-question',
        async function () {

            try {

                const id = Number($(this).data('id'));

                const response = await QuestionApi.get(id);
                const question = response.data;

                form[0].reset();

                $('#questionId').val(question.id);

                form.find('[name="question_text"]')
                    .val(question.question_text);

                form.find('[name="description"]')
                    .val(question.description ?? '');

                form.find('[name="is_active"]')
                    .prop('checked', Boolean(question.is_active));

                $('#modalQuestionTitle').text('Edit Pertanyaan');

                showModal();

            } catch (xhr) {

                Notify.error(errorMessage(xhr));

            }

        }
    );

    form.on('submit', async function (e) {

        e.preventDefault();

        const id = $('#questionId').val() || null;

        const button = $('#btnSaveQuestion');

        const data = {
            question_text: form
                .find('[name="question_text"]')
                .val(),
            
            description: form
                .find('[name="description"]')
                .val(),

            is_active: form
                .find('[name="is_active"]')
                .is(':checked') ? 1 : 0,
        };

        button.prop('disabled', true);

        try {

            const response = await QuestionApi.save(data, id);

            hideModal();

            Notify.success(response.message);

            await loadQuestions();

        } catch (xhr) {

            Notify.error(errorMessage(xhr));

        } finally {

            button.prop('disabled', false);

        }

    });
}

// ===================================
// HAPUS
// ===================================

function initQuestionDelete() {

    $('#questionList').on(
        'click',
        '.btn-delete-question',
        async function () {

            const id = Number($(this).data('id'));

            const result = await Alert.confirm(
                'Apakah Anda yakin ingin menghapus pertanyaan ini?'
            );

            if (!result.isConfirmed) {
                return;
            }

            try {

                const response = await QuestionApi.delete(id);

                Notify.success(response.message);

                await loadQuestions();

            } catch (xhr) {

                Notify.error(errorMessage(xhr));

            }

        }
    );
}

// ===================================
// SORTABLE
// ===================================

function initQuestionSortable() {

    const element = document.getElementById('questionList');

    Sortable.create(element, {

        animation: 150,

        handle: '.question-drag-handle',

        ghostClass: 'sortable-ghost',

        onEnd: async function (event) {

            if (event.oldIndex === event.newIndex) {
                return;
            }

            const ids = $('#questionList .question-item')
                .map(function () {
                    return Number($(this).data('id'));
                })
                .get();

            try {

                const response = await QuestionApi.reorder(ids);

                Notify.success(response.message);

            } catch (xhr) {

                Notify.error(errorMessage(xhr));

            } finally {

                // Pastikan urutan di UI sama dengan database.
                await loadQuestions();

            }

        },

    });
}

// ===================================
// INIT
// ===================================

export function initSurveyQuestions() {

    if (!document.getElementById('questionModule')) {
        return;
    }

    initQuestionForm();
    initQuestionDelete();
    initQuestionSortable();

    loadQuestions();
}