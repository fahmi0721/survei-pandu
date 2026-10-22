import $ from 'jquery';

import { Alert } from '../../plugins/sweetalert';
import { Notify } from '../../plugins/notify';

export function initLogin() {

    const form = $('#formLogin');

    if (!form.length) {
        return;
    }

    form.on('submit', async function (e) {

        e.preventDefault();

        const button = $('#btnLogin');
        const originalText = button.text();

        button.prop('disabled', true).text('Memproses...');

        try {

            const response = await $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: form.serialize(),
                dataType: 'json',
                headers: {
                    Accept: 'application/json'
                }
            });

            Notify.success(response.message);

            if (response.redirect) {
                window.location.assign(response.redirect);
            }

        } catch (xhr) {

            let message =
                xhr.responseJSON?.message ||
                'Terjadi kesalahan saat login.';

            if (xhr.status === 429) {
                message =
                    'Terlalu banyak percobaan login. Silakan coba lagi nanti.';
            }

            Notify.error(message);

        } finally {

            button.prop('disabled', false).text(originalText);

        }

    });

}