import $ from 'jquery';

function csrfToken() {
    return $('meta[name="csrf-token"]').attr('content');
}

function baseUrl() {
    return $('#surveyModule').data('url');
}

export const SurveyApi = {

    async get(id) {
        return $.ajax({
            url: `${baseUrl()}/${id}`,
            method: 'GET',
            dataType: 'json',
        });
    },

    async save(data, id = null) {

        const isEdit = id !== null;

        return $.ajax({
            url: isEdit
                ? `${baseUrl()}/${id}`
                : $('#surveyModule').data('store-url'),

            method: 'POST',

            data: isEdit
                ? `${data}&_method=PUT`
                : data,

            headers: {
                'X-CSRF-TOKEN': csrfToken(),
                'Accept': 'application/json',
            },

            dataType: 'json',
        });
    },

    async delete(id) {
        return $.ajax({
            url: `${baseUrl()}/${id}`,
            method: 'DELETE',

            headers: {
                'X-CSRF-TOKEN': csrfToken(),
                'Accept': 'application/json',
            },

            dataType: 'json',
        });
    },

};