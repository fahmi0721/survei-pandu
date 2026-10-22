import $ from 'jquery';
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.min.css';
import { Indonesian } from 'flatpickr/dist/l10n/id.js';

import { Alert } from '../../../plugins/sweetalert';
import { Notify } from '../../../plugins/notify';



import { SurveyApi } from './api';
import { reloadSurveyTable } from './table';
window.$ = window.jQuery = $;
window.flatpickr = flatpickr;
window.flatpickr.localize(Indonesian);
function getErrorMessage(xhr) {

    const errors = xhr.responseJSON?.errors;

    if (errors) {
        return Object.values(errors).flat().join('\n');
    }

    return xhr.responseJSON?.message
        || 'Terjadi kesalahan pada server.';
}

function openModal() {
    $('#openSurveyModal').trigger('click');
}

function closeModal() {
    $('#closeSurveyModal').trigger('click');
}

function initSurveyDatepicker() {

    const startInput = document.querySelector('#start_date');
    const endInput = document.querySelector('#end_date');

    if (!startInput || !endInput) return;

    const endPicker = flatpickr(endInput, {
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'd F Y',
        allowInput: false,
        monthSelectorType: 'dropdown',
        plugins: [
            yearSelectPlugin({
                start: 10,
                end: 10,
            }),
        ],
        minDate: startInput.value || undefined,
    });

    flatpickr(startInput, {
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'd F Y',
        allowInput: false,
        monthSelectorType: 'dropdown',
        plugins: [
            yearSelectPlugin({
                start: 10,
                end: 10,
            }),
        ],
        onChange: function(selectedDates, dateStr) {

            endPicker.set('minDate', dateStr || null);

            if (
                endInput.value &&
                dateStr &&
                endInput.value < dateStr
            ) {
                endPicker.clear();
            }
        },
    });
}

export function initSurveyForm() {

    const form = $('#formSurvey');

    if (!form.length) return;

    // TAMBAH
    $('#btnAddSurvey').on('click', function () {
        form[0].reset();

        $('#surveyId').val('');
        $('#modalSurveyTitle').text('Tambah Survei');

        openModal();
        initSurveyDatepicker();

    });

    // EDIT
    $('#tableSurveys').on(
        'click',
        '.btn-edit-survey',
        async function () {

            const id = $(this).data('id');

            try {

                const response = await SurveyApi.get(id);
                const survey = response.data;

                form[0].reset();

                $('#surveyId').val(survey.id);

                form.find('[name="title"]').val(survey.title);
                form.find('[name="description"]').val(survey.description ?? '');

                form.find('[name="start_date"]').val(
                    survey.start_date?.slice(0, 10) ?? ''
                );

                form.find('[name="end_date"]').val(
                    survey.end_date?.slice(0, 10) ?? ''
                );

                form.find('[name="status"]').val(survey.status);

                $('#modalSurveyTitle').text('Edit Survei');

                openModal();

            } catch (xhr) {
                Notify.error(getErrorMessage(xhr));
            }

        }
    );

    // SIMPAN
    form.on('submit', async function (e) {

        e.preventDefault();

        const id = $('#surveyId').val() || null;
        const button = $('#btnSaveSurvey');

        button.prop('disabled', true);

        try {

            const response = await SurveyApi.save(
                form.serialize(),
                id
            );

            closeModal();

            Notify.success(response.message);

            reloadSurveyTable();

        } catch (xhr) {

            Notify.error(getErrorMessage(xhr));

        } finally {

            button.prop('disabled', false);

        }

    });

    // HAPUS
    $('#tableSurveys').on(
        'click',
        '.btn-delete-survey',
        async function () {

            const id = $(this).data('id');

            const confirmation = await Alert.confirm(
                'Apakah Anda yakin ingin menghapus survei ini?'
            );

            if (!confirmation.isConfirmed) return;

            try {

                const response = await SurveyApi.delete(id);

                Notify.success(response.message);

                reloadSurveyTable();

            } catch (xhr) {

                Notify.error(getErrorMessage(xhr));

            }

        }
    );

}