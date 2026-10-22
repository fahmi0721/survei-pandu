import { initRespondentTable } from './table';
import { initRespondentDetail } from './detail';
import { initRespondentSelect2 } from './select2';

export function initRespondents() {

    if (!document.getElementById('respondentModule')) {
        return;
    }

    // Inisialisasi Select2 terlebih dahulu
    initRespondentSelect2();

    initRespondentTable();
    initRespondentDetail();
}