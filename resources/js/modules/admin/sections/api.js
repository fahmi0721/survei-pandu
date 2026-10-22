import $ from 'jquery';

const moduleElement = $('#sectionModule');

const csrf = () =>
    $('meta[name="csrf-token"]').attr('content');

const baseUrl = () =>
    moduleElement.data('base-url');

export const SectionApi = {

    list() {
        return $.ajax({
            url: moduleElement.data('data-url'),
            method: 'GET',
            dataType: 'json',
        });
    },

    get(id) {
        return $.ajax({
            url: `${baseUrl()}/${id}`,
            method: 'GET',
            dataType: 'json',
        });
    },

    save(data, id = null) {
        return $.ajax({
            url: id
                ? `${baseUrl()}/${id}`
                : moduleElement.data('store-url'),

            method: 'POST',

            data: {
                ...data,
                ...(id ? { _method: 'PUT' } : {}),
            },

            headers: {
                'X-CSRF-TOKEN': csrf(),
                'Accept': 'application/json',
            },

            dataType: 'json',
        });
    },

    delete(id) {
        return $.ajax({
            url: `${baseUrl()}/${id}`,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrf(),
                'Accept': 'application/json',
            },
            dataType: 'json',
        });
    },

    reorder(ids) {
        return $.ajax({
            url: moduleElement.data('reorder-url'),
            method: 'POST',
            data: {
                _method: 'PUT',
                ids,
            },
            headers: {
                'X-CSRF-TOKEN': csrf(),
                'Accept': 'application/json',
            },
            dataType: 'json',
        });
    },
};