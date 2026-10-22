import $ from 'jquery';
import select2 from 'select2';

import 'select2/dist/css/select2.min.css';

// Register plugin pada jQuery
if (typeof $.fn.select2 !== 'function') {
    select2(window, $);
}

export function initSelect2Survey() {

    const element = $('#wilayah');

    if (!element.length) {
        return;
    }

    element.select2({
        width: '100%',
        placeholder: '-- Pilih Wilayah Kerja --',
        allowClear: true,
        minimumResultsForSearch: 0,

        language: {
            noResults: () => 'Wilayah tidak ditemukan',
            searching: () => 'Mencari...',
            inputTooShort: () => 'Masukkan kata pencarian',
        },
    });
}

export function initStatusKerja() {

    const status = $('#status_kerja');
    const wilayah = $('#wilayah');

    if (!status.length || !wilayah.length) {
        return;
    }

    function updateWilayah() {

        const isActive = status.val() === 'aktif';

        if (isActive) {

            wilayah.prop('disabled', false);
            wilayah.prop('required', true);

            $('#wilayahRequired').show();

        } else {

            wilayah.val(null).trigger('change');

            wilayah.prop('disabled', true);
            wilayah.prop('required', false);

            $('#wilayahRequired').hide();

        }
    }

    status.on('change', updateWilayah);

    // Jalankan saat halaman pertama kali dimuat
    updateWilayah();
}