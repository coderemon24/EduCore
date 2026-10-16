import './bootstrap';
import $ from 'jquery';
import Alpine from 'alpinejs';
import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

// Expose jQuery globally (preserve existing global instance & plugins if loaded)
window.$ = window.jQuery = window.jQuery || $;

// Expose Alpine.js globally and initialize
window.Alpine = Alpine;
Alpine.start();

// Expose globally on window
window.Swal = Swal;

// Global configured Toast helper
window.Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3500,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    }
});

console.log('EduCore SIS Admin Assets with jQuery & Alpine.js initialized successfully.');