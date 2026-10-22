import $ from 'jquery';

const getModule = () => $('#questionModule');

const csrfToken = () =>
    $('meta[name="csrf-token"]').attr('content');

const baseUrl = () =>
    getModule().attr('data-base-url');

const headers = () => ({
    'X-CSRF-TOKEN': csrfToken(),
    'Accept': 'application/json',
});

export const QuestionApi = {

    list() {
        return $.ajax({
            url: getModule().attr('data-data-url'),
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
                : getModule().attr('data-store-url'),

            method: 'POST',

            data: {
                ...data,
                ...(id ? { _method: 'PUT' } : {}),
            },

            headers: headers(),
            dataType: 'json',
        });
    },

    delete(id) {
        return $.ajax({
            url: `${baseUrl()}/${id}`,
            method: 'DELETE',
            headers: headers(),
            dataType: 'json',
        });
    },

    reorder(ids) {
        return $.ajax({
            url: getModule().attr('data-reorder-url'),
            method: 'POST',

            data: {
                _method: 'PUT',
                ids,
            },

            headers: headers(),
            dataType: 'json',
        });
    },
};