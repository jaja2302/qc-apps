// Import all Bootstrap components (from Bootstrap 5)
import * as bootstrap from 'bootstrap';

// Or import only specific components like Modal
// import { Modal } from 'bootstrap';

import Swal from 'sweetalert2/dist/sweetalert2.js';
import 'sweetalert2/src/sweetalert2.scss';
import 'leaflet-arrowheads';
import 'leaflet-polylinedecorator';
import 'leaflet-rotatedmarker';
import html2canvas from 'html2canvas';
import jQuery from 'jquery';
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
import select2 from 'select2';
import 'select2/dist/css/select2.css';
import 'bootstrap-select/dist/css/bootstrap-select.min.css';
import 'bootstrap-select';
import 'choices.js/public/assets/styles/choices.min.css';
import Choices from 'choices.js';
window.html2canvas = html2canvas;
window.jQuery = jQuery;
window.select2 = select2;
window.Choices = Choices;
window.$ = jQuery;
window.DataTable = DataTable;
window.jszip = jszip;
window.pdfmake = pdfmake;
window.DateTime = DateTime;


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
// Define calculateRanks function
function calculateRanks(data) {
    let totals = [];

    // Collect all totals
    Object.keys(data).forEach((estateName) => {
        let estate = data[estateName];

        Object.keys(estate).forEach((afdelingKey) => {
            let afdeling = estate[afdelingKey];
            let total;
            if (afdeling.check_databh === "kosong" && afdeling.check_datacak === "kosong" & afdeling.check_datatrans === "kosong") {
                 total = 0;
            }else{
                 total = afdeling.TOTAL_SKORbh + afdeling.totalSkortrans + afdeling.skor_akhircak;
            }
            // let total = afdeling.TOTAL_SKORbh + afdeling.totalSkortrans + afdeling.skor_akhircak;
            totals.push({
                estateName,
                afdelingKey,
                total
            });
        });
    });

    // Sort the totals in descending order
    totals.sort((a, b) => b.total - a.total);

    // Create a map for ranking based on the sorted totals
    let rankMap = new Map();
    totals.forEach((item, index) => {
        rankMap.set(`${item.estateName}-${item.afdelingKey}`, index + 1);
    });

    return rankMap;
}

// Define populateTableWithRanks function and attach it to the window object
function populateTableWithRanks(tableData, tableBody) {
    let rankMap = calculateRanks(tableData);

    Object.keys(tableData).forEach((estateName) => {
        let estate = tableData[estateName];

        Object.keys(estate).forEach((afdelingKey) => {
            let afdeling = estate[afdelingKey];
            let total;

            if (afdeling.TOTAL_SKORbh !== undefined) {
                if (afdeling.check_databh === "kosong" && afdeling.check_datacak === "kosong" & afdeling.check_datatrans === "kosong") {
                    total = '-';
                }else{
                    total = afdeling.TOTAL_SKORbh + afdeling.totalSkortrans + afdeling.skor_akhircak;
                }
            } 
            if (afdeling.total_score !== undefined) {
                total = afdeling.total_score;
            }

            if (afdeling.score_estate !== undefined) {
                total = afdeling.score_estate;
            }
            let rank = rankMap.get(`${estateName}-${afdelingKey}`);

            // Create table row and cells
            let tr = document.createElement('tr');
            let itemElement1 = document.createElement('td');
            let itemElement2 = document.createElement('td');
            let itemElement3 = document.createElement('td');
            let itemElement4 = document.createElement('td');
            let itemElement5 = document.createElement('td');

            itemElement1.classList.add("text-center");
            itemElement1.innerText = estateName;
            itemElement2.innerText = afdelingKey;
            itemElement3.innerText = afdeling.asisten
            itemElement4.innerText = total;
            itemElement5.innerText = rank;

  
            if (afdelingKey === 'estate') {
               
                itemElement1.style.backgroundColor = "#f0f0f0";
                itemElement2.style.backgroundColor = "#f0f0f0";
                itemElement3.style.backgroundColor = "#f0f0f0";
                setBackgroundColor(itemElement4, total);
                itemElement5.style.backgroundColor = "#f0f0f0";
            }else{
                setBackgroundColor(itemElement4, total);
            }
           
            tr.appendChild(itemElement1);
            tr.appendChild(itemElement2);
            tr.appendChild(itemElement3);
            tr.appendChild(itemElement4);
            tr.appendChild(itemElement5);

            tableBody.appendChild(tr); 
        });
    });
}
function setBackgroundColor(element, score) {
    if (score >= 95) {
        element.style.backgroundColor = "#609cd4";
    } else if (score >= 85) {
        element.style.backgroundColor = "#08b454";
    } else if (score >= 75) {
        element.style.backgroundColor = "#fffc04";
    } else if (score >= 65) {
        element.style.backgroundColor = "#ffc404";
    } else if (score === '-') {
        element.style.backgroundColor = "white";
    } else {
        element.style.backgroundColor = "red";
    }
    element.style.color = "black";
}
function setBackgroundColorCell(cell, score) {
    if (score >= 95) {
        cell.style.backgroundColor = "#609cd4";
    } else if (score >= 85) {
        cell.style.backgroundColor = "#08b454";
    } else if (score >= 75) {
        cell.style.backgroundColor = "#fffc04";
    } else if (score >= 65) {
        cell.style.backgroundColor = "#ffc404";
    } else if (score === '-') {
        cell.style.backgroundColor = "white";
    } else {
        cell.style.backgroundColor = "red";
    }
    cell.style.color = "black";
}

function TableForWilReg(data, tableBody) {
    let item1 = data['afd'] ?? data['wil'] ?? 'WIL';
    let item2 = data['est']  ?? data['wil'];;
    let item3 = data['gm'] ?? data['rh'] ?? '-';
    let item4;
    if (data['TOTAL_SKORbh'] !== undefined) {
        if (data['check_databh'] === "kosong" && data['check_datacak'] === "kosong" & data['check_datatrans'] === "kosong") {
            item4 = '-';
        }else{
            item4 = data['TOTAL_SKORbh'] + data['totalSkortrans'] + data['skor_akhircak'];
        }
      
    }else{
        item4 = data['skor'].toFixed(2) ;
    }
    let item5 = '-';

    var tr = document.createElement('tr');
    let itemElement1 = document.createElement('td');
    let itemElement2 = document.createElement('td');
    let itemElement3 = document.createElement('td');
    let itemElement4 = document.createElement('td');
    let itemElement5 = document.createElement('td');

    itemElement1.classList.add("text-center");
    itemElement1.innerText = item1;
    itemElement2.innerText = item2;
    itemElement3.innerText = item3;
    itemElement4.innerText = item4;
    itemElement5.innerText = item5;

    setBackgroundColor(itemElement4, item4);
    tr.style.backgroundColor = "#FCF086";
    tr.appendChild(itemElement1);
    tr.appendChild(itemElement2);
    tr.appendChild(itemElement3);
    tr.appendChild(itemElement4);
    tr.appendChild(itemElement5);

    tableBody.appendChild(tr);
}

function editRecord(id) {
    // Handle the edit logic here, e.g., show a form with the record details for editing
    console.log('Edit record:', id);
    // You could retrieve the specific record data and populate an edit form
}

function deleteRecord(id) {
    if (confirm('Are you sure you want to delete this record?')) {
        $.ajax({
            url: "#", // Replace with your actual delete route
            method: "DELETE",
            data: {
                id: id,
                _token: $('input[name="_token"]').val()
            },
            success: function(result) {
                alert('Record deleted successfully!');
                // Optionally refresh the data table
                $('#dataModal').modal('hide'); // Close the modal if desired
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error deleting record:', errorThrown);
            }
        });
    }
}

// Example usage:


// Attach the function to the window object to make it globally accessible
window.populateTableWithRanks = populateTableWithRanks;
window.setBackgroundColor = setBackgroundColor;
window.setBackgroundColorCell = setBackgroundColorCell;
window.TableForWilReg = TableForWilReg;
window.editRecord = editRecord;
window.deleteRecord = deleteRecord;
