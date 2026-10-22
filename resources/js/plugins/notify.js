import { Notyf } from 'notyf';
import 'notyf/notyf.min.css';

const notyf = new Notyf({
    duration: 3500,
    position: {
        x: 'right',
        y: 'top',
    },
    dismissible: true,
    ripple: true,
});

export const Notify = {

    success(message) {
        return notyf.success(message);
    },

    error(message) {
        return notyf.error(message);
    },

    warning(message) {
        return notyf.open({
            type: 'warning',
            message: message,
            background: '#f59e0b',
            icon: false,
        });
    },

    info(message) {
        return notyf.open({
            type: 'info',
            message: message,
            background: '#3b82f6',
            icon: false,
        });
    },

};