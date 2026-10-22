import $ from 'jquery';

import { Alert } from '../../plugins/sweetalert';
import { Notify } from '../../plugins/notify';
import { initSurveyMaster } from './surveys';
import { initSurveySections } from './sections';
import { initSurveyQuestions } from './questions';
import { initWorkAreas } from './work-areas';
import { initRespondents } from './respondents';

export function initAdmin() {

    // Hanya aktif pada layout admin
    if (!document.querySelector('.page-wrapper')) {
        return;
    }

    initTheme();
    initNotifications();
    initLogout();
    initSurveyMaster();
    initSurveySections();
    initSurveyQuestions();
    initWorkAreas();
    initRespondents();
}

function initTheme() {

    const root = document.documentElement;
    const button = $('#btnToggleTheme');

    const savedTheme = localStorage.getItem('admin-theme');
    const theme = savedTheme === 'dark' ? 'dark' : 'light';

    root.setAttribute('data-bs-theme', theme);

    updateThemeIcon(theme);

    button.on('click', function () {

        const current = root.getAttribute('data-bs-theme');

        const next = current === 'dark' ? 'light' : 'dark';

        root.setAttribute('data-bs-theme', next);

        localStorage.setItem('admin-theme', next);

        updateThemeIcon(next);

    });

}

function updateThemeIcon(theme) {

    const icon = $('#btnToggleTheme i');

    icon.removeClass('ti-moon ti-sun');

    icon.addClass(
        theme === 'dark' ? 'ti-sun' : 'ti-moon'
    );

}

function initNotifications() {

    $('#btnTestAlert').on('click', function () {

        Alert.success(
            'SweetAlert2 berhasil terintegrasi dengan Tabler!'
        );

    });

    $('#btnTestNotify').on('click', function () {

        Notify.success(
            'Notify.js berhasil terintegrasi dengan Tabler!'
        );

    });

}

function initLogout() {

    $('#formLogout').on('submit', async function (e) {

        e.preventDefault();

        const form = this;

        const result = await Alert.confirm(
            'Apakah Anda yakin ingin keluar dari aplikasi?'
        );

        if (result.isConfirmed) {
            form.submit();
        }

    });

}