import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

export const Alert = {
    success(message) {
        return Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: message,
            confirmButtonText: 'OK',
        });
    },

    error(message) {
        return Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan',
            text: message,
        });
    },

    confirm(message) {
        return Swal.fire({
            icon: 'question',
            title: 'Konfirmasi',
            text: message,
            showCancelButton: true,
            confirmButtonText: 'Ya, lanjutkan',
            cancelButtonText: 'Batal',
            reverseButtons: true,
        });
    },
};