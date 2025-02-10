<x-layout.app>
    <div class="container-fluid">
        <link rel="stylesheet" href="{{ asset('qc_css/absensi/absensi.css') }}">
        <section class="content"><br>
            <div class="container-fluid">
                <div class="card table_wrapper">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active" id="nav-utama-tab" data-toggle="tab" href="#nav-utama" role="tab" aria-controls="nav-utama" aria-selected="true">Absensi</a>
                            <a class="nav-item nav-link" id="nav-data-tab" data-toggle="tab" href="#nav-data" role="tab" aria-controls="nav-data" aria-selected="false">Foto Bukti</a>

                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-utama" role="tabpanel" aria-labelledby="nav-utama-tab">
                            <div class="mt-3 mb-2 ml-3 mr-3">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <div style=" display: flex; justify-content: flex-start;padding-bottom:20px">
                                            <button class="btn btn-primary" id="pdfdownload">Download PDF</button>

                                            <!-- Button to trigger modal -->
                                            @if (can_edit())
                                            <button type="button" class="btn btn-primary ml-2" data-toggle="modal" data-target="#editModal">
                                                Edit Data
                                            </button>
                                            @endif

                                            <!-- Edit Modal -->
                                            <!-- Edit Modal with Loading Screen and Select Options -->
                                            <div class="modal" id="editModal">
                                                <div class="modal-dialog modal-xl">
                                                    <div class="modal-content">

                                                        <!-- Modal Header -->
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Edit User Data</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>

                                                        <!-- Modal Body with Loading Screen -->
                                                        <div class="modal-body">
                                                            <form id="editForm">
                                                                <div class="form-group">
                                                                    <label for="userName">Select User:</label>
                                                                    <select class="form-control" id="userName">
                                                                        @foreach ($useroption as $items)
                                                                        <option value="{{$items['user_id']}}">{{$items['nama_lengkap']}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="datePicker">Select Date:</label>
                                                                    <input type="date" class="form-control" id="datePicker">
                                                                </div>
                                                                <button type="button" class="btn btn-primary" id="searchButton">Search</button>
                                                            </form>

                                                            <div class="loading-screen text-center" style="display: none;">
                                                                <p>Loading...</p>
                                                                <div class="spinner-border" role="status">
                                                                    <span class="sr-only">Loading...</span>
                                                                </div>
                                                            </div>

                                                            <!-- Table to display data -->
                                                            <table class="table mt-3" id="dataResults" style="display: none;">
                                                                <!-- Table headers -->
                                                                <thead>
                                                                    <tr>
                                                                        <th style="text-align:center">ID</th>
                                                                        <th style="text-align:center">Nama Pengguna</th>
                                                                        <th style="text-align:center">Jam Masuk</th>
                                                                        <th style="text-align:center">Tanggal Masuk</th>
                                                                        <th style="text-align:center" colspan="2">Aksi</th>

                                                                    </tr>
                                                                </thead>
                                                                <!-- Table body to populate with data -->
                                                                <tbody id="editabsensi">
                                                                    <!-- Data will be populated here -->
                                                                </tbody>
                                                            </table>

                                                        </div>

                                                    </div>
                                                </div>
                                            </div>





                                        </div>

                                    </div>

                                    <div class="col-sm-2">
                                        <div style=" display: flex; justify-content: flex-end;padding-bottom:20px;">
                                            {{ csrf_field() }}
                                            <select name="regional" id="regional" style="height:37px;width:auto">
                                                <option value="1">Regional 1</option>
                                                <option value="2">Regional 2</option>
                                                <option value="3">Regional 3</option>
                                                <option value="4">Regional 4</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div style=" display: flex; justify-content: flex-end;padding-bottom:20px;">
                                            {{ csrf_field() }}
                                            <input class="form-control" value="{{ date('Y-m') }}" type="month" name="inputDateMonth" id="inputDateMonth">
                                        </div>
                                    </div>

                                </div>

                                <div class="table-container" style="overflow-x: auto; width: 100%;">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th scope="col" colspan="35" style="text-align: center;">ABSENSI KEHADIRAN QC</th>

                                            </tr>
                                            <tr>
                                                <th scope="col" rowspan="3" style="text-align: center; vertical-align: middle;">NAMA</th>


                                            </tr>
                                            <tr>
                                                <th scope="col" colspan="31" style="text-align: center;" id="header_month">{{$header_month}}</th>

                                            </tr>
                                            <tr id="dates-container">
                                                <!-- <th scope="col" rowspan="3" style="text-align: center; vertical-align: middle;">Total</th> -->
                                            </tr>


                                        </thead>

                                        <tbody id="data">

                                        </tbody>
                                    </table>
                                </div>


                            </div>

                            <div class="mt-3 mb-2 ml-3 mr-3">


                                <div class="row">
                                    <div class="col-sm-4">
                                        <!-- <select name="userdata" id="userdata" style="height:37px;width:auto">
                                        @foreach ($useroption as $item)
                                        <option value="{{$item['user_id']}}"> {{$item['nama_lengkap']}}</option>
                                        @endforeach
                                    </select> -->
                                    </div>
                                    <div class="col-sm-4">

                                        <div style=" display: flex; justify-content: center;padding-bottom:20px;">
                                            <p style="text-align: center;">Tracking User QC Map</p style="text-align: center;">
                                        </div>


                                    </div>
                                    <div class="col-sm-4">
                                        <div style=" display: flex; justify-content: flex-end;padding-bottom:20px;">
                                            {{ csrf_field() }}
                                            <input class="form-control" value="{{ date('Y-m') }}" type="date" name="getDate" id="getDate" style="width: 200px;height:auto">
                                        </div>
                                    </div>

                                </div>
                                <div id="map" style="height: 540px; z-index: 1;"></div>



                            </div>
                        </div>

                        <!-- tab 2  -->
                        <div class=" tab-pane fade" id="nav-data" role="tabpanel" aria-labelledby="nav-data-tab">
                            <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark">
                                <h5><b>Foto Absensi: {{ session('lok') }}

                                    </b></h5>
                            </div>
                            <div class="mt-3 mb-2 ml-3 mr-3">


                                <div class="row">
                                    <div class="col-sm-4">

                                    </div>
                                    <div class="col-sm-4">

                                        <div style=" display: flex; justify-content: center;padding-bottom:20px;">
                                            <p style="text-align: center;">Foto Bukti User QC</p style="text-align: center;">
                                        </div>


                                    </div>
                                    <div class="col-sm-4">
                                        <div style=" display: flex; justify-content: flex-end;padding-bottom:20px;">
                                            {{ csrf_field() }}
                                            <input class="form-control" value="{{ date('Y-m') }}" type="date" name="dateBukti" id="dateBukti" style="width: 200px;height:auto">
                                        </div>
                                    </div>

                                </div>
                                <div class="flex">
                                    <div id="imgdata">
                                    </div>
                                </div>



                            </div>
                        </div>




                    </div>
                </div>
        </section>
    </div>
    <script type="module">
        var currentUserName = "{{ session('jabatan') }}";
        const canedit = @json(can_edit());

        $(document).ready(function() {

            $('#searchButton').click(function() {
                var selectedUser = $('#userName').val();
                var selectedDate = $('#datePicker').val();

                // Show loading screen
                showLoadingScreen();
                var _token = $('input[name="_token"]').val();

                $.ajax({
                    url: "{{route ('getEditabsensi')}}", // Replace with your controller URL
                    method: 'get',
                    data: {
                        userId: selectedUser,
                        date: selectedDate,
                        _token: _token
                    },
                    success: function(data) {
                        if (data && data.length > 0) {
                            // Update modal with received data
                            $('#dataResults tbody').empty();


                            $.each(data, function(index, item) {
                                var row = `
                            <tr>
                            <td style="text-align:center">${item.id}</td>
                            <td style="text-align:center">${item.nama}</td>
                            <td style="text-align:center">${item.jam_masuk}</td>
                            <td style="text-align:center">${item.tanggal}</td>
                            <td>
                                <button type="button" class="btn btn-primary edit-btn" data-id="${item.id}" data-nama="${item.nama}" data-jam="${item.jam_masuk}" data-tanggal="${item.tanggal}">
                                    <i class="fas fa-edit"></i> Jam
                                </button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger mk-btn" data-id="${item.id}">
                                <i class="fas fa-edit"></i> MK
                                </button>
                            </td>
                        </tr>

                        `;
                                $('#dataResults tbody').append(row);
                            });

                            hideLoadingScreen();

                            $('#dataResults').on('click', '.edit-btn', function() {
                                var id = $(this).data('id');
                                var nama = $(this).data('nama');
                                var jam = $(this).data('jam');
                                var tanggal = $(this).data('tanggal');
                                var type = 'editTime'

                                editRow(id, nama, jam, tanggal, type);
                            });

                            $('#dataResults').on('click', '.mk-btn', function() {
                                var id = $(this).data('id');
                                setMK(id);
                            });

                            $('#dataResults').on('click', '.ijin-btn', function() {
                                var id = $(this).data('id');
                                setMK(id);
                            });



                        } else {
                            // If data is empty, add a button or handle the case to add new data
                            $('#dataResults tbody').empty();
                            $('#dataResults tbody').append(`
                            <tr>
                                <td colspan="6">No data found</td>
                            </tr>
                        `);

                            // Adding a button to add new data
                            $('#dataResults tbody').append(`
                            <tr>
                                <td colspan="6">
                                    <button id="addDataButton" class="btn btn-primary">Check in</button>
                                </td>
                            </tr>
                        `);
                            $('#addDataButton').on('click', function() {
                                var id = $('#userName').val();
                                var tanggal = new Date().toISOString().split('T')[0]; // Formatting date as 'YYYY-MM-DD'
                                var jam = new Date().toLocaleTimeString([], {
                                    hour12: false
                                }); // Formatting time as 'HH:mm:ss'
                                var nama = ''
                                var type = 'nambahdata'



                                addRow(id, nama, jam, tanggal, type)
                            });

                            hideLoadingScreen(); // Hide loading screen
                        }
                    },


                    error: function(error) {
                        console.error('Error fetching data:', error);
                        // Handle errors if needed
                    }
                });

            });

            function editRow(id, nama, jam, tanggal, type) {
                // Format the tanggal and jam values to fit the datetime-local input format
                const formattedDate = tanggal.replace(/:/g, '-'); // Replace colons with dashes
                const formattedTime = jam.substring(0, 5); // Assuming jam is in the format "HH:MM:SS"

                // Show SweetAlert to input new date and time
                swal.fire({ ////
                    title: 'Masukan Jam Baru',
                    html: `
                <p>Harap Perhatikan tangal dan jam sebelum mengedit / menambah</p>
                <input id="swal-date" type="date" value="${formattedDate}">
                <input id="swal-time" type="time" value="${formattedTime}">`,
                    showCancelButton: true,
                    confirmButtonText: 'Submit',
                    cancelButtonText: 'Cancel',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    preConfirm: () => {
                        const newDate = document.getElementById('swal-date').value;
                        const newTime = document.getElementById('swal-time').value;

                        return {
                            newDate,
                            newTime

                        };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const {
                            newDate,
                            newTime

                        } = result.value;
                        var _token = $('input[name="_token"]').val();
                        // Send the updated time to your AJAX function
                        // var type = 'editTime' //
                        $.ajax({
                            url: "{{ route ('crudabsensi') }}",
                            method: 'POST',
                            data: {
                                id: id,
                                newTime: `${newDate} ${newTime}`, // Combine date and time
                                tanggal: newDate,
                                type: type,
                                _token: _token
                            },
                            success: function(response) {
                                // Handle success if needed
                                if (response && response === 'Successfully updated') {
                                    swal.fire('New time submitted!', '', 'success').then(() => {
                                        // Reload the window after displaying the success message
                                        window.location.reload();
                                    });
                                } else {
                                    swal.fire('Oops!', 'Failed to submit new time.', 'error');
                                }
                            },

                            error: function(error) {
                                // Handle error if needed
                                swal.fire('Error!', 'Failed to submit new time.', 'error');
                            }
                        });
                    }
                    swal.getPopup().setAttribute('onclick', '');
                    swal.getContainer().removeAttribute('tabindex');
                });
            }

            var listkh = @json($listkerja);
            // console.log(listkh);  
            function addRow(id, nama, jam, tanggal, type) {
                const formattedDate = tanggal.replace(/:/g, '-');
                const formattedTime = jam.substring(0, 5);
                const listkhOptions = listkh.map(item => `<option value="${item.id}">${item.nama}</option>`).join('');


                const selectDropdown = `
                <select id="pekerjaan" style="margin-bottom: 10px; padding: 5px;">
                ${listkhOptions}
                </select>
            `;

                const swalContent = document.createElement('div');
                swalContent.style.fontFamily = 'Arial, sans-serif';
                swalContent.style.padding = '20px';

                const swalDate = document.createElement('input');
                swalDate.id = 'swal-date';
                swalDate.type = 'date';
                swalDate.value = formattedDate;
                swalDate.style.marginBottom = '10px';
                swalDate.style.padding = '5px';
                swalContent.appendChild(swalDate);

                const swalTime = document.createElement('input');
                swalTime.id = 'swal-time';
                swalTime.type = 'time';
                swalTime.value = formattedTime;
                swalTime.style.marginBottom = '10px';
                swalTime.style.padding = '5px';
                swalContent.appendChild(swalTime);

                const selectContainer = document.createElement('div');
                selectContainer.innerHTML = selectDropdown;
                swalContent.appendChild(selectContainer);

                const fotoLabel = document.createElement('label');
                fotoLabel.htmlFor = 'foto';
                fotoLabel.style.display = 'block';
                fotoLabel.style.marginBottom = '5px';
                fotoLabel.textContent = 'FOTO';
                swalContent.appendChild(fotoLabel);

                const fotoInput = document.createElement('input');
                fotoInput.id = 'foto';
                fotoInput.type = 'file';
                fotoInput.accept = '.jpg, .png, .jpeg';
                fotoInput.placeholder = 'Opsional';
                fotoInput.style.marginBottom = '10px';
                fotoInput.style.padding = '5px';
                swalContent.appendChild(fotoInput);

                const cutiInput = document.createElement('input');
                cutiInput.id = 'cuti';
                cutiInput.type = 'checkbox';
                cutiInput.style.marginBottom = '10px';
                swalContent.appendChild(cutiInput);

                const cutiLabel = document.createElement('label');
                cutiLabel.htmlFor = 'cuti';
                cutiLabel.style.marginLeft = '5px';
                cutiLabel.textContent = 'Cuti (Check Box Jika mengambil Cuti)';
                swalContent.appendChild(cutiLabel);

                const dateRange = document.createElement('div');
                dateRange.id = 'dateRange';
                dateRange.style.display = 'none';
                dateRange.style.marginTop = '10px';

                const date1Input = document.createElement('input');
                date1Input.id = 'date1';
                date1Input.type = 'date';
                date1Input.style.marginBottom = '5px';
                date1Input.style.padding = '5px';
                dateRange.appendChild(date1Input);

                const date2Input = document.createElement('input');
                date2Input.id = 'date2';
                date2Input.type = 'date';
                date2Input.style.padding = '5px';
                dateRange.appendChild(date2Input);

                swalContent.appendChild(dateRange);

                cutiInput.addEventListener('change', function() {
                    dateRange.style.display = this.checked ? 'block' : 'none';
                });

                swal.fire({
                    title: 'Masukan Data Baru',
                    html: swalContent,
                    showCancelButton: true,
                    confirmButtonText: 'Submit',
                    cancelButtonText: 'Cancel',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    preConfirm: () => {
                        const newDate = document.getElementById('swal-date').value;
                        const newTime = document.getElementById('swal-time').value;
                        const pekerjaan = document.getElementById('pekerjaan').value;
                        const cuti = document.getElementById('cuti').checked;

                        let date1, date2;

                        if (cuti) {
                            date1 = document.getElementById('date1').value;
                            date2 = document.getElementById('date2').value;
                        }

                        const formData = new FormData();
                        formData.append('newDate_add', newDate);
                        formData.append('newTime_add', newTime);
                        formData.append('pekerjaan_add', pekerjaan);
                        formData.append('cuti_add', cuti);
                        formData.append('date1_add', date1);
                        formData.append('date2_add', date2);
                        formData.append('id', id);

                        const fotoInput = document.getElementById('foto');

                        if (fotoInput && fotoInput.files.length > 0) {
                            formData.append('foto', fotoInput.files[0]);
                        }

                        return formData;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const newDate_add = document.getElementById('swal-date').value;
                        const newTime_add = document.getElementById('swal-time').value;
                        const pekerjaan_add = document.getElementById('pekerjaan').value;
                        const cuti_add = document.getElementById('cuti').checked;
                        const date1_add = document.getElementById('date1').value;
                        const date2_add = document.getElementById('date2').value;
                        const fotoInput = document.getElementById('foto').files[0];

                        const formData = new FormData();
                        formData.append('newDate_add', newDate_add);
                        formData.append('newTime_add', newTime_add);
                        formData.append('pekerjaan_add', pekerjaan_add);
                        formData.append('cuti_add', cuti_add);
                        formData.append('date1_add', date1_add);
                        formData.append('date2_add', date2_add);
                        formData.append('foto', fotoInput);
                        formData.append('id', id);
                        formData.append('_token', $('input[name="_token"]').val());


                        $.ajax({
                            url: "{{ route('creatAbsen') }}",
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                // Handle success response here
                                console.log('Form submitted successfully.');
                                console.log('Response:', response);

                                // Check for undefined or unexpected response structure
                                if (response === undefined || response.message === undefined) {
                                    console.error('Unexpected response received:', response);
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Unexpected response received from the server. Please try again later.'
                                    });
                                    return; // Exit early to prevent further processing
                                }

                                // If the response indicates successful book upload
                                if (response.message === 'Success') {
                                    // Show success message using SweetAlert
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil check in',
                                        text: 'Berhasil check in.',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'OK'
                                    }).then((result) => {
                                        // Reload the page after the user clicks OK
                                        if (result.isConfirmed) {
                                            location.reload();
                                        }
                                    });
                                } else {
                                    // Handle other possible responses
                                    console.error('Unexpected response message:', response.message);
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Unexpected response message received from the server. Please try again later.'
                                    });
                                }
                            },


                        });
                    }
                    swal.getPopup().setAttribute('onclick', '');
                    swal.getContainer().removeAttribute('tabindex');
                });
            }


            function setMK(id) {

                console.log(id);
                swal.fire({
                    title: 'Warning!',
                    html: '<i class="fas fa-exclamation-triangle" style="color:#f8bb86; font-size: 24px;"></i> Anda yakin ingin melakukan tindakan ini?<br>' +
                        'Tindakan ini akan menghapus data kehadiran sekarang dan tidak dapat dikembalikan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, ubah ke MK(mangkir)',
                    cancelButtonText: 'Cancel',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                }).then((result) => {
                    if (result.isConfirmed) {
                        var _token = $('input[name="_token"]').val();
                        var type = 'setMK';

                        // Send the confirmation to your AJAX function
                        $.ajax({
                            url: "{{ route ('crudabsensi') }}",
                            method: 'POST',
                            data: {
                                id: id,
                                type: type,
                                _token: _token
                            },
                            success: function(response) {
                                // Handle success if needed
                                if (response && response === 'Successfully deleted') {
                                    swal.fire('Data berhasil di buah ke MK!', '', 'success');
                                    window.location.reload();
                                } else {
                                    swal.fire('Oops!', 'Failed to set the value to MK.', 'error');
                                }
                            },
                            error: function(error) {
                                // Handle error if needed
                                swal.fire('Error!', 'Failed to set the value to MK.', 'error');
                            }
                        });
                    }
                    swal.getPopup().setAttribute('onclick', '');
                    swal.getContainer().removeAttribute('tabindex');
                });

            }





            // Function to show loading screen and hide table
            function showLoadingScreen() {
                $('.loading-screen').show(); // Show loading screen
                $('#dataResults').hide(); // Hide table
            }

            // Function to hide loading screen and show table
            function hideLoadingScreen() {
                $('.loading-screen').hide(); // Hide loading screen
                $('#dataResults').show(); // Show the table with data
            }





            var lokasiKerja = "{{ session('lok') }}";


            // console.log(lokasiKerja);
            if (lokasiKerja == 'Regional II' || lokasiKerja == 'Regional 2') {
                $('#regional').val('2');
                $('#afdreg').val('2');

            } else if (lokasiKerja == 'Regional III' || lokasiKerja == 'Regional 3') {
                $('#regional').val('3');
                $('#afdreg').val('3');
            } else if (lokasiKerja == 'Regional IV' || lokasiKerja == 'Regional 4') {
                $('#regional').val('4');
                $('#afdreg').val('4');
            } else if (lokasiKerja == 'Regional IV' || lokasiKerja == 'Regional 4') {
                $('#regional').val('1');
                $('#afdreg').val('1');
            }

            var reg = document.getElementById('regional');
            var tahun = document.getElementById('inputDateMonth');

            // Function to handle the change event for both elements
            function handleFiltersChange() {
                // Get the selected values
                var selectedRegional = reg.value;
                var selectedDateMonth = tahun.value;

                // Perform your AJAX request here with the selected values
                var _token = $('input[name="_token"]').val();
                // Example:
                $('#data').empty()
                $.ajax({
                    type: 'get',
                    url: "{{ route('absensidata') }}",
                    data: {
                        regional: selectedRegional,
                        dateMonth: selectedDateMonth,
                        _token: _token
                    },
                    success: function(data) {
                        // Handle the response data
                        var header_month = data.header_month; // Access the header_month directly
                        var dates = data.dates;
                        var JumlahBulan = data.JumlahBulan;
                        var data_absensi = data.data_absensi;

                        var header_table = document.getElementById('header_month');
                        header_table.textContent = header_month;

                        // Set the colspan attribute based on the 'dates' value
                        header_table.setAttribute('colspan', JumlahBulan);


                        // Get the table row where you want to add the <th> elements
                        var datesContainer = document.getElementById("dates-container");

                        let addPlus = parseFloat(JumlahBulan) + 1;
                        datesContainer.innerHTML = '';

                        // Loop from 1 to 31 and create <th> elements for each number
                        for (var i = 1; i <= addPlus; i++) {
                            var th = document.createElement("th");
                            th.setAttribute("scope", "col");
                            if (i < addPlus) {
                                th.textContent = i;
                            } else {
                                th.textContent = "Total";
                            }
                            datesContainer.appendChild(th);
                        }



                        var Datatables = document.getElementById('data');
                        var DrawTables = data_absensi

                        for (var key in DrawTables) {
                            if (DrawTables.hasOwnProperty(key)) {
                                var element = DrawTables[key];
                                var tr = document.createElement('tr');

                                for (var prop in element) {
                                    if (element.hasOwnProperty(prop) && prop !== "id") {
                                        var td = document.createElement('td');
                                        td.innerText = element[prop];
                                        td.classList.add("large-text", "vertical-center"); // Add classes for styling

                                        // Check if it's the first cell, and set its width to 500px
                                        if (prop === Object.keys(element)[0]) {
                                            td.style.width = '500px';
                                        }

                                        // Check if the text is a string and contains "minggu", then change the background color to red
                                        if (typeof element[prop] === 'string' && element[prop].includes("minggu")) {
                                            td.style.backgroundColor = 'red';
                                            td.innerText = '';
                                        }

                                        if (typeof element[prop] === 'string' && element[prop].includes("CT")) {
                                            td.style.color = 'Blue';
                                            // td.innerText = '';
                                        }

                                        tr.appendChild(td);
                                    }
                                }
                                Datatables.appendChild(tr);
                            }
                        }

                    },

                });

                // You can put your AJAX request code here
            }

            // Add event listeners to trigger the function when the values change
            reg.addEventListener('change', handleFiltersChange);
            tahun.addEventListener('change', handleFiltersChange);

            // Trigger the function when the document is ready
            handleFiltersChange();

            sendAjaxRequest();

            $('#pdfdownload').click(function() {
                var _token = $('input[name="_token"]').val();
                var regional = $("#regional").val();
                var date = $("#inputDateMonth").val();

                // Construct the URL
                var url = "{{ route('absensipdf') }}?regional=" + regional + "&date=" + date + "&_token=" + _token;

                // Open the URL in a new tab
                window.open(url, '_blank');
            });

            getBuktiChange();



        });


        var map = L.map('map').setView([-2.2745234, 111.61404248], 11);

        // Define the "Google Satellite" tile layer
        var googleSatellite = L.tileLayer('http://{s}.google.com/vt?lyrs=s&x={x}&y={y}&z={z}', {
            maxZoom: 22,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
        });

        // Add "Google Satellite" as the only base map
        googleSatellite.addTo(map);

        // map.addControl(new L.Control.Fullscreen());

        // Rest of your code, including the marker-related functions
        var markersLayer = L.layerGroup().addTo(map);
        var markerBlok = L.layerGroup().addTo(map);



        function drawusermaps(datauser) {
            markersLayer.clearLayers();

            // console.log(datauser);
            var coordinatesArray = [];

            datauser.features.forEach(function(feature) {
                var coordinates = feature.geometry.coordinates;
                var properties = feature.properties;
                var images = feature.images;

                // Create a marker at the specified coordinates
                var marker = L.marker(coordinates).addTo(markersLayer);

                // Create a popup to display information
                var popupContent = `
            <b>Nama User:</b> ${properties.nama_user}<br>
            <b>Waktu Absensi:</b> ${properties.waktu_absensi}<br>
            <b>Jenis Kerja:</b> ${properties.jenis_kerja}<br>
            <img src="${images.img}" alt="Image">
        `;

                marker.bindPopup(popupContent);

                // Add the coordinates to the array for fitting bounds
                coordinatesArray.push(coordinates);
            });

            // Fit the map view to the bounds of the markers
            // Fit the map view to the bounds of the markers with a maximum zoom level
            if (coordinatesArray.length > 0) {
                map.fitBounds(coordinatesArray, {
                    maxZoom: 18
                }); // Adjust the maxZoom value as needed
            }

        }

        function drawMap(new_blok) {
            markerBlok.clearLayers();
            var bounds = new L.LatLngBounds();
            // console.log(new_blok);
            // Iterate through the keys (Rangda, Sulung, etc.) in new_blok
            for (var key in new_blok) {
                if (new_blok.hasOwnProperty(key)) {
                    var coordinates = [];
                    var points = new_blok[key];

                    // Extract the latlng values and convert them to an array of coordinates
                    for (var i = 0; i < points.length; i++) {
                        var latlng = points[i].lating.split("$");
                        var lat = parseFloat(latlng[0]);
                        var lng = parseFloat(latlng[1]);
                        coordinates.push([lat, lng]);
                    }

                    // Create a polygon for each key and add it to the map
                    var polygon = L.polygon(coordinates).addTo(markerBlok);
                    // var polygon = L.polygon(coordinates, polygonStyle).addTo(markerBlok);



                    // Extend the bounds to include the new polygon
                    bounds.extend(polygon.getBounds().getNorthEast());
                    bounds.extend(polygon.getBounds().getSouthWest());

                    var polygonCenter = polygon.getBounds().getCenter();
                    // Get other properties from your data
                    var afd_nama = key

                    var popupContent = `<div class="custom-popup"><strong>Nama Estate: </strong>${afd_nama}<br/>`;


                    var label = L.marker(polygonCenter, {
                        icon: L.divIcon({
                            className: 'label-blok',
                            html: afd_nama,
                            iconSize: [50, 10],
                            iconColor: 'white' // Set iconColor to 'white'
                        })
                    }).addTo(markerBlok);


                    label.bindPopup(popupContent);
                }
            }

            // Fit the map to the bounds of all polygons
            // map.fitBounds(bounds);
        }




        // Call the function with your GeoPlot data

        function sendAjaxRequest() {
            var _token = $('input[name="_token"]').val();
            // var user_id = $("#userdata").val();
            var getDate = $("#getDate").val();

            // Check if getDate is empty, and if so, set it to the current date
            if (!getDate) {
                var currentDate = new Date();
                var year = currentDate.getFullYear();
                var month = String(currentDate.getMonth() + 1).padStart(2, '0');
                var day = String(currentDate.getDate()).padStart(2, '0');
                getDate = year + '-' + month + '-' + day;
                $("#getDate").val(getDate);
            }

            $.ajax({
                url: "{{ route('absenmaps') }}",
                method: "GET",
                data: {

                    date: getDate,
                    _token: _token
                },
                headers: {
                    'X-CSRF-TOKEN': _token
                },
                success: function(result) {
                    var datauser = result.datauser;
                    var new_blok = result.GeoPlot;

                    drawusermaps(datauser)
                    drawMap(new_blok)
                    // console.log(datauser);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Handle errors here.
                }
            });
        }

        // $("#userdata").on('change', sendAjaxRequest);
        var userD = document.getElementById('userdata');
        var getDate = document.getElementById('getDate');
        // userD.addEventListener('change', sendAjaxRequest);
        getDate.addEventListener('change', sendAjaxRequest);

        function showimg(datauser) {
            const container = document.getElementById("imgdata");
            const imageBaseUrl = "https://mobilepro.srs-ssms.com/storage/app/public/qc/absensi/";
            const defaultImageUrl = "{{ asset('img/404img.png') }}";

            // Check if the object is not empty
            if (Object.keys(datauser).length > 0) {
                const heading = document.createElement("div");
                heading.classList.add("text-center");
                // heading.innerHTML = "<h1>Foto Bukti ABsensi</h1>";
                container.appendChild(heading);

                const rowContainer = document.createElement("div");
                rowContainer.classList.add("row", "justify-content-center");
                container.appendChild(rowContainer);

                for (const id in datauser) {
                    if (datauser.hasOwnProperty(id)) {
                        const data = datauser[id];
                        const imageUrl = imageBaseUrl + data.foto;

                        const card = document.createElement("div");
                        card.classList.add("col-md-6", "col-lg-3", "mb-3");
                        card.innerHTML = `
                        <div class="card">
                            <img src="${imageUrl}" alt="${data.foto}" class="img-thumbnail" data-toggle="modal" data-target="#myModal${id}">
                            <div class="card-body mt-2">
                                <h5 class="card-title text-right">Nama: ${data.nama}</h5>
                                <p class="card-text text-left">Pekerjaan: ${data.pekerjaan}</p>
                                <p class="card-text text-left">Jam Masuk: ${data.jam}</p>
                            </div>
                        </div>
                    `;
                        rowContainer.appendChild(card);

                        if (canedit) {
                            const buttonContainer = document.createElement("div");
                            buttonContainer.classList.add("btn-container");

                            const downloadLink = document.createElement("a");
                            downloadLink.href = "#"; // Set a placeholder link initially
                            downloadLink.innerHTML = '<i class="fas fa-download"></i> Download Image';
                            downloadLink.classList.add("btn", "btn-primary", "btn-sm");
                            buttonContainer.appendChild(downloadLink);


                            // Append the container to the card body
                            card.querySelector(".card-body").appendChild(buttonContainer);




                            // Add click event to trigger download
                            downloadLink.addEventListener("click", () => {
                                // Use the image URL to construct the download URL
                                const downloadUrl = "https://srs-ssms.com/qc_inspeksi/get_qcIMG.php?image=" + encodeURIComponent(imageUrl);

                                // Open a new tab/window to initiate the download
                                window.open(downloadUrl, "_blank");
                            });




                        }
                    }
                }
            } else {
                const noDataMessage = document.createElement("p");
                noDataMessage.textContent = "Data not found.";
                container.appendChild(noDataMessage);
            }
        }

        function getBuktiChange() {
            var _token = $('input[name="_token"]').val();
            var getDate = $("#dateBukti").val();

            // Check if getDate is empty, and if so, set it to the current date
            if (!getDate) {
                var currentDate = new Date();
                var year = currentDate.getFullYear();
                var month = String(currentDate.getMonth() + 1).padStart(2, '0');
                var day = String(currentDate.getDate()).padStart(2, '0');
                getDate = year + '-' + month + '-' + day;
                $("#dateBukti").val(getDate);
            }
            $('#imgdata').empty()
            $.ajax({
                url: "{{ route('absensibukti') }}",
                method: "GET",
                data: {
                    date: getDate,
                    _token: _token
                },
                headers: {
                    'X-CSRF-TOKEN': _token
                },
                success: function(result) {
                    var datauser = result.data;


                    showimg(datauser)
                },

                error: function(jqXHR, textStatus, errorThrown) {
                    // Handle errors here.
                }
            });
        }


        var dateBukti = document.getElementById('dateBukti');

        dateBukti.addEventListener('change', getBuktiChange);
    </script>
</x-layout.app>