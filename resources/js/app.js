import './bootstrap';
import $ from "jquery";


import Swal from 'sweetalert2/dist/sweetalert2.js'
import 'sweetalert2/src/sweetalert2.scss'
import 'leaflet-arrowheads';
import 'leaflet-polylinedecorator';
import 'leaflet-rotatedmarker';
import html2canvas from 'html2canvas';
window.html2canvas = html2canvas;

import jQuery from "jquery";
import jszip from 'jszip';
import pdfmake from 'pdfmake';
import DataTable from 'datatables.net-dt';
import 'datatables.net-autofill-dt';
import 'datatables.net-buttons-dt';
import 'datatables.net-buttons/js/buttons.colVis.mjs';
import 'datatables.net-buttons/js/buttons.html5.mjs';
import 'datatables.net-buttons/js/buttons.print.mjs';
import 'datatables.net-colreorder-dt';
import DateTime from 'datatables.net-datetime';
import 'datatables.net-fixedcolumns-dt';
import 'datatables.net-fixedheader-dt';
import 'datatables.net-keytable-dt';
import 'datatables.net-responsive-dt';
import 'datatables.net-rowgroup-dt';
import 'datatables.net-rowreorder-dt';
import 'datatables.net-scroller-dt';
import 'datatables.net-searchbuilder-dt';
import 'datatables.net-searchpanes-dt';
import 'datatables.net-select-dt';
import 'datatables.net-staterestore-dt';
window.$ = jQuery;
window.DataTable = DataTable;
window.jszip = jszip;
window.pdfmake = pdfmake;
window.DateTime = DateTime;
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
