import $ from 'jquery';
import select2 from 'select2';

import 'select2/dist/css/select2.min.css';

// Registrasikan Select2 pada instance jQuery aplikasi
if (typeof $.fn.select2 !== 'function') {
    select2(window, $);
}

export function initRespondentSelect2() {

    const element = $('#filterWorkArea');

    if (!element.length) {
        return;
    }

    element.select2({
        theme: 'default',
        width: '100%',
        placeholder: 'Semua Wilayah',
        allowClear: true,
        minimumResultsForSearch: 0,

        language: {
            noResults: () => 'Wilayah tidak ditemukan',
            searching: () => 'Mencari wilayah...',
        },
    });
}