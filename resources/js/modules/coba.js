
// alert('sss');
import $ from 'jquery';
import { Alert } from '../plugins/sweetalert';
import { Notify  } from '../plugins/notify';

window.$ = window.jQuery = $;
window.Alert = Alert;
window.Notify  = Notify ;


$(function () {
    $('#btnTestAlert').on('click', function () {
        Alert.confirm('Apakah Anda ingin mengirim survei?');
    });

    $('#btnTestNotify').on('click', function () {
        console.log("tombol di klik")
        if (Notify ) {
            console.log("tombol di klik dan notify berhasil")
            Notify.success('Notifikasi berhasil!');
        }
    });
});