// import './bootstrap';
import $ from "jquery";
window.$ = $;
import Swal from 'sweetalert2/dist/sweetalert2.js'
import 'sweetalert2/src/sweetalert2.scss'
import 'leaflet-arrowheads';
import 'leaflet-polylinedecorator';
import 'leaflet-rotatedmarker';

import DataTable from 'datatables.net-bs5';

import DateTime from 'datatables.net-datetime';
import 'datatables.net-fixedcolumns-bs5';
import 'datatables.net-fixedheader-bs5';
import html2canvas from 'html2canvas';
window.html2canvas = html2canvas;


// helper 

window.captureTableScreenshot = (tableId, fileName) => {
    const tableElement = document.getElementById(tableId);
    html2canvas(tableElement).then(canvas => {
        const dataUrl = canvas.toDataURL();
        const downloadLink = document.createElement('a');
        downloadLink.href = dataUrl;
        downloadLink.download = fileName || 'screenshot.png';

        // Trigger the download
        downloadLink.click();
    });
};