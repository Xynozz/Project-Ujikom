import './bootstrap';
import { Notyf } from 'notyf';
// import 'notyf/notyf.min.css';

// Inisialisasi Notyf
// const notyf = new Notyf();

const notyf = new Notyf({
    duration: 1000,
    position: {
        x: 'right',
        y: 'top',
    },
    types: [
        {
            type: 'warning',
            background: 'orange',
            icon: {
                className: 'material-icons',
                tagName: 'i',
                text: 'warning'
            }
        },
        {
        type: 'error',
        background: 'indianred',
        duration: 2000,
        dismissible: true
      }
    ]
  });

    // Contoh penggunaan
    notyf.success('Berhasil!');
    notyf.error('Terjadi kesalahan!');
