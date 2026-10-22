import $ from 'jquery';
import Sortable from 'sortablejs';


import { Alert } from '../../../plugins/sweetalert';
import { Notify } from '../../../plugins/notify';

import { SectionApi } from './api';

let sortableInstance = null;
let modalInstance = null;

const escapeHtml = (value) => {
    return $('<div>').text(value ?? '').html();
};

function getError(xhr) {
    const errors = xhr.responseJSON?.errors;

    if (errors) {
        return Object.values(errors).flat().join(', ');
    }

    return xhr.responseJSON?.message
        || 'Terjadi kesalahan pada server.';
}

function getQuestionUrl(sectionId) {

    const baseUrl = $('#sectionModule')
        .attr('data-base-url');

    return `${baseUrl}/${Number(sectionId)}/questions`;
}

// =========================
// LOAD BAGIAN
// =========================

async function loadSections() {

    const list = $('#sectionList');

    $('#sectionLoading').removeClass('d-none');

    try {
        const response = await SectionApi.list();
        const sections = response.data;

        list.empty();

        $('#sectionEmpty').toggleClass(
            'd-none',
            sections.length > 0
        );

        sections.forEach((item, index) => {

            const status = item.is_active
                ? '<span class="badge bg-success-lt">Aktif</span>'
                : '<span class="badge bg-secondary-lt">Nonaktif</span>';

            list.append(`
                <div class="list-group-item section-item"
                     data-id="${Number(item.id)}">

                    <div class="d-flex align-items-center gap-3">

                        <span class="drag-handle"
                              title="Geser untuk mengurutkan">
                            <i class="ti ti-grip-vertical fs-2"></i>
                        </span>

                        <span class="badge bg-primary-lt section-number">
                            ${index + 1}
                        </span>

                        <div class="flex-fill section-content">

                            <div class="fw-semibold">
                                ${escapeHtml(item.title)}
                            </div>

                            <div class="text-secondary small">
                                ${escapeHtml(item.description || 'Tanpa deskripsi')}
                            </div>

                            <div class="mt-1">
                                ${status}
                                <span class="text-secondary small ms-2">
                                    ${Number(item.questions_count || 0)} pertanyaan
                                </span>
                            </div>

                        </div>

                        <div class="btn-list flex-nowrap">
                            <a href="${getQuestionUrl(item.id)}"
                            class="btn btn-sm btn-outline-success">

                                <i class="ti ti-help-circle me-1"></i>
                                Pertanyaan

                            </a>
                            <button type="button"
                                    class="btn btn-sm btn-outline-primary btn-edit-section"
                                    data-id="${Number(item.id)}">
                                <i class="ti ti-edit"></i>
                            </button>

                            <button type="button"
                                    class="btn btn-sm btn-outline-danger btn-delete-section"
                                    data-id="${Number(item.id)}">
                                <i class="ti ti-trash"></i>
                            </button>

                        </div>

                    </div>

                </div>
            `);
        });

    } catch (xhr) {

        Alert.error(getError(xhr));

    } finally {

        $('#sectionLoading').addClass('d-none');

    }
}

function openModal() {
    $('#openSectionModal').trigger('click');
}

function closeModal() {
    $('#closeSectionModal').trigger('click');
}

// =========================
// SORTABLE
// =========================

function initSortable() {

    const element = document.getElementById('sectionList');

    sortableInstance = Sortable.create(element, {

        animation: 150,

        handle: '.drag-handle',

        ghostClass: 'sortable-ghost',

        onEnd: async function (event) {

            if (event.oldIndex === event.newIndex) {
                return;
            }

            const ids = $('#sectionList .section-item')
                .map(function () {
                    return Number($(this).data('id'));
                })
                .get();

            try {

                await SectionApi.reorder(ids);

                Notify.success('Urutan bagian berhasil disimpan.');

                // Sinkronkan nomor dan data dari server
                await loadSections();

            } catch (xhr) {

                Alert.error(getError(xhr));

                // Kembalikan ke urutan tersimpan
                await loadSections();

            }

        },

    });
}

// =========================
// FORM
// =========================

function initForm() {

    const form = $('#formSection');

    $('#btnAddSection').on('click', function () {

        form[0].reset();
        $('#sectionId').val('');
        $('#modalSectionTitle').text('Tambah Bagian');

        openModal();

    });

    $('#sectionList').on(
        'click',
        '.btn-edit-section',
        async function () {

            try {

                const id = $(this).data('id');
                const response = await SectionApi.get(id);
                const section = response.data;

                form[0].reset();

                $('#sectionId').val(section.id);

                form.find('[name="title"]')
                    .val(section.title);

                form.find('[name="description"]')
                    .val(section.description ?? '');

                form.find('[name="is_active"]')
                    .prop('checked', Boolean(section.is_active));

                $('#modalSectionTitle').text('Edit Bagian');

                openModal();

            } catch (xhr) {

                Alert.error(getError(xhr));

            }

        }
    );

    form.on('submit', async function (e) {

        e.preventDefault();

        const id = $('#sectionId').val() || null;

        const button = $('#btnSaveSection');

        const data = {
            title: form.find('[name="title"]').val(),
            description: form.find('[name="description"]').val(),
            is_active: form.find('[name="is_active"]').is(':checked')
                ? 1 : 0,
        };

        button.prop('disabled', true);

        try {

            const response = await SectionApi.save(data, id);

            closeModal();

            Notify.success(response.message);

            await loadSections();

        } catch (xhr) {

            Alert.error(getError(xhr));

        } finally {

            button.prop('disabled', false);

        }

    });
}

// =========================
// DELETE
// =========================

function initDelete() {

    $('#sectionList').on(
        'click',
        '.btn-delete-section',
        async function () {

            const id = $(this).data('id');

            const confirmation = await Alert.confirm(
                'Apakah Anda yakin ingin menghapus bagian survei ini?'
            );

            if (!confirmation.isConfirmed) {
                return;
            }

            try {

                const response = await SectionApi.delete(id);

                Notify.success(response.message);

                await loadSections();

            } catch (xhr) {

                Alert.error(getError(xhr));

            }

        }
    );
}

// =========================
// INIT MODULE
// =========================

export function initSurveySections() {

    if (!document.getElementById('sectionModule')) {
        return;
    }


    initForm();
    initDelete();
    initSortable();

    loadSections();
}