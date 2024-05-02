import './bootstrap';
// import $ from "jquery";
// window.$data = $;

import Swal from 'sweetalert2/dist/sweetalert2.js'
import 'sweetalert2/src/sweetalert2.scss'
import 'leaflet-arrowheads';
import 'leaflet-polylinedecorator';
import 'leaflet-rotatedmarker';
import 'datatables.net-fixedcolumns-bs5';
import 'datatables.net-fixedheader-bs5';
import html2canvas from 'html2canvas';
window.html2canvas = html2canvas;
import jQuery from "jquery";
import DataTable from 'datatables.net-bs5';
window.DataTable = DataTable;
window.$$ = jQuery;
// helper 
window.captureTableScreenshot = (tableId, fileName) => {
    Swal.fire({
        title: 'Loading',
        html: '<span class="loading-text">Mohon Tunggu...</span>',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
            setTimeout(() => {
                const tableElement = document.getElementById(tableId);
                html2canvas(tableElement).then(canvas => {
                    const dataUrl = canvas.toDataURL();
                    const downloadLink = document.createElement('a');
                    downloadLink.href = dataUrl;
                    downloadLink.download = fileName || 'screenshot.png';

                    // Trigger the download
                    downloadLink.click();

                    // Close Swal after the download link is clicked
                    Swal.close();
                }).catch(error => {
                    console.error('Error capturing screenshot:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to capture screenshot. Please try again.',
                    });
                });
            }, 2000);
        }
    });
};
