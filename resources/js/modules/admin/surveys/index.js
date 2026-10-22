import { initSurveyTable } from './table';
import { initSurveyForm } from './form';

export function initSurveyMaster() {

    if (!document.getElementById('surveyModule')) {
        return;
    }

    initSurveyTable();
    initSurveyForm();

}