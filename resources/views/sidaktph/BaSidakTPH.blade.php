<x-layout.app>

    <style>
        .Wraping {
            width: 100%;
            overflow-x: auto;
            white-space: nowrap;
            padding: 0;
            /* Remove padding */
        }

        table {
            border-collapse: collapse;
            width: 100%;
            /* Remove the margin property to prevent centering */
        }



        th,
        td {
            border: 1px solid black;
            text-align: center;
            padding: 8px;
        }

        .sticky-footer {
            margin-top: auto;
            /* Push the footer to the bottom */
        }

        .my-table {
            margin-bottom: 50px;
            /* Adjust this value as needed */
        }

        .header {
            align-items: center;
        }

        .logo-container {
            display: flex;
            align-items: center;
        }

        .logo {
            height: 80px;
            width: auto;
        }

        .text-container {
            margin-left: 15px;
        }

        .pt-name,
        .qc-name {
            margin: 0;
        }

        .center-space {
            flex-grow: 1;
        }

        .right-container {
            text-align: right;
        }

        .rights-container {
            display: flex;

            justify-content: flex-end;
        }


        .form-inline {
            display: flex;
            align-items: center;
        }

        /* The Modal (background) */



        /* Add Bootstrap-like button styling */
        .btn {
            display: inline-block;
            font-weight: 400;
            color: #212529;
            text-align: center;
            vertical-align: middle;
            cursor: pointer;
            background-color: transparent;
            border: 1px solid transparent;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            user-select: none;
        }

        .btn-primary {
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-secondary {
            color: #fff;
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .btn-primary:hover,
        .btn-secondary:hover {
            filter: brightness(90%);
        }

        .btn-primary:active,
        .btn-secondary:active {
            filter: brightness(80%);
        }

        .btn:focus,
        .btn:active {
            outline: none;
        }

        /* Add Bootstrap-like form control styling */
        .form-control {
            display: block;
            width: 100%;
            height: calc(1.5em + 0.75rem + 2px);
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-control:focus {
            color: #495057;
            background-color: #fff;
            border-color: #80bdff;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .mb-3 {
            margin-bottom: 1rem !important;
        }


        /* The image inside the modal */
        #modalImage {
            width: 100%;
            /* Adjust this value to change the image width */
            max-height: 70vh;
            /* Limit the height of the image */
            object-fit: contain;
            /* Maintain aspect ratio */
        }

        /* Add Animation */
        @keyframes animatetop {
            from {
                top: -300px;
                opacity: 0;
            }

            to {
                top: 0;
                opacity: 1;
            }
        }

        /* The Close Button */
        .close {
            color: white;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }



        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes scaleUp {
            from {
                transform: scale(0.95);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        #pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1rem 0;
        }

        .pagination-button {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            color: #495057;
            text-align: center;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            cursor: pointer;
            margin-right: 0.25rem;
        }

        .pagination-button:hover {
            background-color: #e9ecef;
            border-color: #dee2e6;
            color: #495057;
        }

        .current-page {
            background-color: #007bff;
            border-color: #007bff;
            color: #fff;
        }

        /* Add this @media query for mobile view */
        @media (max-width: 767px) {
            .header {
                flex-direction: column;
            }

            .right-container {
                text-align: center;
                margin-top: 15px;
            }

            .form-inline {
                justify-content: center;
            }
        }

        /* The rest of the CSS */
        .Wraping {
            width: 100%;
            overflow-x: auto;
            white-space: nowrap;
            padding: 0;
            /* Remove padding */
        }

        table {
            border-collapse: collapse;
            width: 100%;
            /* Remove the margin property to prevent centering */
        }

        th,
        td {
            border: 1px solid black;
            text-align: center;
            padding: 8px;
        }

        .sticky-footer {
            margin-top: auto;
            /* Push the footer to the bottom */
        }

        .my-table {
            margin-bottom: 50px;
            /* Adjust this value as needed */
        }

        .header {
            align-items: center;
        }

        .logo-container {
            display: flex;
            align-items: center;
        }

        .logo {
            height: 80px;
            width: auto;
        }

        .text-container {
            margin-left: 15px;
        }

        .pt-name,
        .qc-name {
            margin: 0;
        }

        .center-space {
            flex-grow: 1;
        }

        .right-container {
            text-align: right;
        }

        .rights-container {
            display: flex;

            justify-content: flex-end;
        }

        .form-inline {
            display: flex;
            align-items: center;
        }

        .legend {
            background-color: white;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .legend h4 {
            margin-top: 0;
            margin-bottom: 10px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }

        .legend-icon {
            width: 14px;
            height: 21px;
            margin-right: 5px;
        }
    </style>


    <div class="container-fluid">



        <div class="card table_wrapper">
            <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark ">
                <h2>REKAP HARIAN SIDAK TPH </h2>
            </div>
            <div class="alert alert-danger d-none d-flex flex-column align-items-start justify-content-between" role="alert" id="notverif">
                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:">
                    <use xlink:href="#exclamation-triangle-fill" />
                </svg>
                <div>
                    Data belum Tervertifikasi oleh Manager/Askep
                </div>
                @if (can_edit_mananger_askep())
                <div>
                    <button class="btn btn-primary align-self-end" onclick="verifbutton()">Verif now</button>
                </div>
                @endif
            </div>
            <div class="alert alert-warning d-none d-flex align-items-center" role="alert" id="condition_not_met">
                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:">
                    <use xlink:href="#exclamation-triangle-fill" />
                </svg>
                <div>
                    Terjadi Kesalahan
                </div>
            </div>
            <div class="alert alert-primary d-none  d-flex align-items-center" role="alert" id="verifdone" dis>
                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:">
                    <use xlink:href="#info-fill" />
                </svg>
                <div>
                    Data Sudah Tervertifikasi
                </div>
            </div>

            <div class="header d-flex justify-content-center mt-3 mb-2 ml-3 mr-3">
                <div class="logo-container">
                    <img src="{{ asset('img/Logo-SSS.png') }}" alt="Logo" class="logo">
                    <div class="text-container">
                        <div class="pt-name">PT. SAWIT SUMBERMAS SARANA, TBK</div>
                        <div class="qc-name">QUALITY CONTROL</div>
                    </div>
                </div>
                <div class="center-space"></div>
                <div class="right-container">
                    <form action="{{ route('filtersidaktphrekap') }}" method="POST" class="form-inline">
                        <div class="date">
                            {{ csrf_field() }}
                            <input type="hidden" name="est" id="est" value="{{$est}}">
                            <select class="form-control" name="date" id="inputDate">
                                <option value="" disabled selected hidden>Pilih tanggal</option>
                                @foreach($filter as $item)
                                <option value="{{ $item}}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="button" class="ml-2 btn btn-primary" id="showFindingYear" disabled>Show</button>
                    </form>
                    <div class="afd"> ESTATE : {{$est}} / {{$afd}}</div>
                    <div class="afd">TANGGAL : <span id="selectedDate">{{ $tanggal }}</span></div>
                </div>
            </div>

            <!-- animasi loading -->
            <div id="lottie-container" style="width: 100%; height: 100%; position: fixed; top: 0; left: 0; background-color: rgba(255, 255, 255, 0.8); display: none; z-index: 9999;">
                <div id="lottie-animation" style="width: 200px; height: 200px; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                </div>
            </div>

            <!-- end animasi -->
        </div>
        <div class="d-flex justify-content-end mt-3 mb-2 ml-3 mr-3">
            @if (session('jabatan') == 'Manager' || session('jabatan') == 'Askep' || session('jabatan') == 'Asisten'|| session('jabatan') == 'Askep/Asisten' )

            <button id="moveDataButton" class="btn btn-primary mr-3" disabled>Pindah Data</button>
            @endif

            <button id="back-to-data-btn" class="btn btn-primary" onclick="goBack()">Back to Data</button>
            <form action="{{ route('pdfBAsidak') }}" method="GET" class="form-inline" style="display: inline;" target="_blank">
                {{ csrf_field() }}
                <input type="hidden" name="est" id="est" value="{{$est}}">
                <input type="hidden" name="afdling" id="afdling" value="{{$afd}}">
                <input type="hidden" name="inputDates" id="inputDates" value="">

                <button type="submit" class="btn btn-primary ml-2" id="download-button">
                    Download BA
                </button>
            </form>
        </div>


        <!-- animasi loading -->
        <div id="lottie-container" style="width: 100%; height: 100%; position: fixed; top: 0; left: 0; background-color: rgba(255, 255, 255, 0.8); display: none; z-index: 9999;">
            <div id="lottie-animation" style="width: 200px; height: 200px; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
            </div>
        </div>

        <style>
            .table-wrapper {
                overflow-x: auto;
                overflow-y: auto;
                max-height: 600px;
            }

            .my-table {
                width: 100%;
                font-size: 0.8rem;
                border-collapse: collapse;
            }

            .my-table th,
            .my-table td {
                padding: 5px;
                text-align: center;
                border: 1px solid #ccc;
            }

            .my-table thead {
                background-color: #f2f2f2;
            }



            .my-table tbody tr:nth-child(even) {
                background-color: #f8f8f8;
            }

            .my-table tbody tr:hover {
                background-color: #eaeaea;
            }


            .center {
                display: flex;
                justify-content: center;
            }

            .my-table thead th.sticky {
                position: -webkit-sticky;
                position: sticky;
                top: 0;
                z-index: 10;
                background-color: inherit;
            }

            .my-table thead th.sticky-sub {
                position: -webkit-sticky;
                position: sticky;
                top: 30px;
                /* Adjust this value based on the height of the first row in the header */
                z-index: 10;
                background-color: inherit;
            }

            .my-table thead th.sticky-third-row {
                position: -webkit-sticky;
                position: sticky;
                top: 90px;
                /* Adjust this value based on the total height of the first two rows in the header */
                z-index: 10;
                background-color: inherit;
            }

            .my-table thead th.sticky-second-row {
                position: -webkit-sticky;
                position: sticky;
                top: 60px;
                z-index: 10;
                background-color: inherit;
            }

            .my-table tbody td.sticky-cell {
                position: -webkit-sticky;
                position: sticky;
                z-index: 5;
                background-color: white;
            }
        </style>

        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <h1 style="text-align: center;">Tabel Mutu Transport</h1>
                        <div class="table-wrapper">
                            <table class="my-table" id="new_Sidak">
                                <thead>
                                    <tr>
                                        <th class="sticky" style="background-color: white;">ID</th>
                                        <th class="sticky" style="background-color: white;">Estate</th>
                                        <th class="sticky" style="background-color: white;">Afdeling</th>
                                        <th class="sticky" style="background-color: white;">Blok</th>
                                        <th class="sticky" style="background-color: white;">H+</th>
                                        <th class="sticky" style="background-color: white;">QC</th>
                                        <th class="sticky" style="background-color: white;">No TPH</th>
                                        <th class="sticky" style="background-color: white;">Brondolan Tinggal TPH</th>
                                        <th class="sticky" style="background-color: white;">Brondolan TInggal di Jalan</th>
                                        <th class="sticky" style="background-color: white;">Brondolan Tinggal di BIN</th>
                                        <th class="sticky" style="background-color: white;">Jumlah Karung</th>
                                        <th class="sticky" style="background-color: white;">Buah Tinggal</th>
                                        <th class="sticky" style="background-color: white;">Restan Unreported</th>
                                        <th class="sticky" style="background-color: white;">Maps</th>
                                        <th class="sticky" style="background-color: white;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div id="editModalTPH" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" style="text-align: center; font-weight: bold;">Update Mutu Transport</h5>
                        <button type="button" class="close" id="closeModalBtn" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editForm_buah" action="{{ route('updatesidakTPhnew') }}" method="POST">
                            {{ csrf_field() }}
                            <div class=" row m-1">
                                <div class="col">

                                    <label for="update-editId_buah" class="col-form-label">ID</label>
                                    <input type="text" class="form-control" id="editId_buah" name="id">

                                    <label for="update-estBH" class="col-form-label">Estate</label>
                                    <input type="text" class="form-control" id="update-estBH" name="estBH" value="">


                                    <label for="update-afdBH" class="col-form-label">Afdeling</label>
                                    <input type="text" class="form-control" id="update-afdBH" name="afdBH" value="">


                                    <label for="update-blok_bh" class="col-form-label">Blok</label>
                                    <input type="text" class="form-control" id="update-blok_bh" name="blok_bh" value="">


                                </div>

                                <div class="col">

                                    <label for="update-brdtgl" class="col-form-label">Brondolan TPH</label>
                                    <input type="text" class="form-control" id="update-brdtgl" name="brdtgl" value="" required>

                                    <label for="update-brdjln" class="col-form-label">Brondolan Jalan</label>
                                    <input type="text" class="form-control" id="update-brdjln" name="brdjln" value="" required>

                                    <label for="update-brdbin" class="col-form-label">Brondolan BIN</label>
                                    <input type="text" class="form-control" id="update-brdbin" name="brdbin" value="" required>

                                    <label for="update-qc" class="col-form-label">QC</label>
                                    <input type="text" class="form-control" id="update-qc" name="qc" value="">

                                </div>

                            </div>

                            <div class="row m-1">
                                <div class="col">

                                    <label for="update-buahtgl" class="col-form-label">Buah Tinggal</label>
                                    <input type="text" class="form-control" id="update-buahtgl" name="buahtgl" value="" required>

                                    <label for="update-restan" class="col-form-label">Restan Unreported Karung</label>
                                    <input type="text" class="form-control" id="update-restan" name="restan" value="" required>

                                </div>
                                <div class="col">

                                    <label for="update-jumkrng" class="col-form-label">Jumlah Karung</label>
                                    <input type="text" class="form-control" id="update-jumkrng" name="jumkrng" value="" required>

                                    <label for="update-hplus" class="col-form-label mt-4">Plih H+</label>
                                    <select class="form-control" name="hpluss" id="update-hplus">
                                        <option value="H+1">H+1</option>
                                        <option value="H+2">H+2</option>
                                        <option value="H+3">H+3</option>
                                        <option value="H+4">H+4</option>
                                        <option value="H+5">H+5</option>
                                        <option value="H+6">H+6</option>
                                        <option value="H+7">H+7</option>
                                        <option value=">H+7">>H+7</option>
                                    </select>



                                    <!-- <input type="text" class="form-control" id="update-hplus" name="hplus" value="" required> -->

                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="saveChangesBtn_buah">Save Changes</button>
                        <button type="button" class="btn btn-secondary" id="closeModalBtn_buah" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="deleteModalancak" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Delete Confirmation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Anda yakin ingin menghapus data??</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                        <button class="btn btn-danger" id="confirmDeleteBtn">Yes</button>
                    </div>
                </div>
            </div>
        </div>


        <div id="foto_temuan">


        </div>



        <div class="card p-4">

            <h4 class="text-center mt-2" style="font-weight: bold">Tracking Plot Sidak TPH - {{ $est }}
                {{ $afd }}
            </h4>
            <hr>

            <div id="map" style="height:800px"></div>
        </div>



        <style>
            .download-button-container {
                position: absolute;
                top: 0;
                right: 0;
                padding: 10px;
            }
        </style>

        <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" id="modalCloseButton" class="btn-close" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="text-center">
                                <img id="img01" src="path_to_your_image.jpg" alt="..." class="img-fluid">
                            </div>
                            <div class="col-12 col-lg-6">
                                <p id="modalKomentar"></p>
                                <div class="download-button-container">
                                    <a id="downloadButton" class="btn btn-primary" href="#">Download Image</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div>
    <input type="hidden" id="estate" value="{{$est}}">
    <input type="hidden" id="afd" value="{{$afd}}">


    <script type="text/javascript">
        const canedit = @json(can_edit());

        var currentUserName = "{{ session('jabatan') }}";
        var user_id = "{{ auth()->user()->user_id }}";
        var user_name = "{{ session('user_name') }}";
        document.addEventListener("DOMContentLoaded", function() {
            var inputDate = document.getElementById("inputDate");
            // var showFindingYear = document.getElementById("showFindingYear");

            inputDate.addEventListener("change", function() {
                document.getElementById('showFindingYear').disabled = false;
                inputDates.value = inputDate.value;

                // console.log(in);
            });
            // if (inputDate.value !== "") {
            //     showFindingYear.disabled = false;
            //     inputDates.value = inputDate.value; // Update the hidden input field value
            // } else {
            //     showFindingYear.disabled = true;
            //     inputDates.value = ""; // Reset the hidden input field value
            // }
        });

        document.getElementById('showFindingYear').addEventListener('click', function() {
            if (currentUserName === 'Askep' || currentUserName === 'Manager') {
                document.getElementById('moveDataButton').disabled = false;
            }

        });

        document.getElementById('showFindingYear').onclick = function() {
            Swal.fire({
                title: 'Loading',
                html: '<span class="loading-text">Mohon Tunggu...</span>',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });
            dashboardFindingYear()
            getverif()
        }

        function getverif() {
            let Tanggal = document.getElementById('inputDate').value;
            let est = document.getElementById('est').value;
            let afd = document.getElementById('afd').value;
            let menu = 'sidaktph'
            var _token = $('input[name="_token"]').val();

            document.getElementById('notverif').classList.add('d-none');
            document.getElementById('condition_not_met').classList.add('d-none');
            document.getElementById('verifdone').classList.add('d-none');
            $.ajax({
                url: "{{ route('verifinspeksi') }}",
                method: "GET",
                data: {
                    Tanggal: Tanggal,
                    est: est,
                    afd: afd,
                    menu: menu,
                    _token: _token
                },
                success: function(response) {
                    // console.log(response);
                    if (response === 'not_approved_all') {
                        document.getElementById('notverif').classList.remove('d-none');
                    } else if (response === 'all_approved') {
                        document.getElementById('verifdone').classList.remove('d-none');
                    } else if (response === 'condition_not_met') {
                        document.getElementById('condition_not_met').classList.remove('d-none');
                    } else {
                        console.error('Unexpected response:', response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });

        }



        const addClickListener = (elementId) => {
            const element = document.getElementById(elementId);
            if (element !== null) {
                element.addEventListener('click', verifbutton);
            }
        };

        addClickListener('verifbutton_default');
        addClickListener('verifbutton_manager');
        addClickListener('verifbutton_askep');

        function hariini() {
            let today = new Date();
            let year = today.getFullYear();
            let month = String(today.getMonth() + 1).padStart(2, '0');
            let day = String(today.getDate()).padStart(2, '0');
            let hours = String(today.getHours()).padStart(2, '0');
            let minutes = String(today.getMinutes()).padStart(2, '0');

            return `${year}-${month}-${day} ${hours}:${minutes}`;
        }
        var departemen = "{{ session('departemen') }}";
        var lokasikerja = "{{ session('lok') }}";
        // console.log(lokasikerja);
        function verifbutton() {
            Swal.fire({
                title: "Apakah Anda ingin Approve Laporan ini?",
                text: `Jabatan Saat Ini ${currentUserName}`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    let Tanggal = document.getElementById('inputDate').value;
                    let est = document.getElementById('est').value;
                    let afd = document.getElementById('afd').value;
                    let menu = 'sidaktph'
                    let tanggal_approve = hariini();
                    $.ajax({
                        url: "{{ route('verifaction') }}",
                        method: "post",
                        data: {
                            Tanggal: Tanggal,
                            est: est,
                            afd: afd,
                            menu: menu,
                            jabatan: currentUserName,
                            nama: user_name,
                            departemen: departemen,
                            lokasikerja: lokasikerja,
                            tanggal_approve: tanggal_approve,
                            user_id: user_id,
                            action: 'approve',
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            console.log('Approval successful:', response);
                            Swal.fire({
                                title: 'Success',
                                text: 'Data berhasil diupdate',
                                icon: 'success',
                                allowOutsideClick: false
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('Approval error:', xhr.responseText);
                            // Handle the error response as needed
                        }
                    });
                } else if (result.isDenied) {
                    // User clicked No
                    console.log('User declined approval.');
                }
            });
        }


        // window.onload = function() {
        //     // Add the event listener for the "Save changes" button when the DOM is ready
        //     document.getElementById('save-changes-button').addEventListener('click', updateFunction);
        // };

        function dashboardFindingYear(page = 1) {
            // $('#tab1').empty()


            if ($.fn.DataTable.isDataTable('#new_Sidak')) {
                $('#new_Sidak').DataTable().destroy();
            }
            var tanggal = ''
            var est = ''

            var _token = $('input[name="_token"]').val();

            var tanggal = document.getElementById('inputDate').value
            var est = document.getElementById('est').value

            var estate = document.getElementById('estate').value;
            var afd = document.getElementById('afd').value;

            // console.log(tanggal);
            // console.log(est);
            $.ajax({
                url: "{{ route('filtersidaktphrekap') }}",
                method: "GET",
                data: {
                    est,
                    estate,
                    afd,
                    tanggal,
                    page,
                    _token: _token
                },
                success: function(result) {

                    var parseResult = JSON.parse(result);


                    // new sida 
                    var sidakTPhNEw = $('#new_Sidak').DataTable({
                        columns: [{

                                data: 'id',
                            },
                            {

                                data: 'est'
                            },
                            {

                                data: 'afd'
                            },
                            {

                                data: 'blok'
                            },
                            {

                                data: 'status'
                            },
                            {

                                data: 'qc'
                            },
                            {

                                data: 'no_tph'
                            },
                            {

                                data: 'bt_tph'
                            },
                            {

                                data: 'bt_jalan'
                            },
                            {

                                data: 'bt_bin'
                            },
                            {

                                data: 'jum_karung'
                            },
                            {

                                data: 'buah_tinggal'
                            },
                            {

                                data: 'restan_unreported'
                            },
                            {

                                data: 'app_version',
                                render: function(data, type, row, meta) {
                                    var parts = data.split(';'); // Use the 'data' parameter instead of 'dataString'

                                    // Get the last part
                                    var lastPart = parts[parts.length - 1];

                                    // Define variables for the conditions
                                    var Akurat = 'Akurat';
                                    var Liar = 'Liar';
                                    var result = null;

                                    // Check conditions and assign values
                                    if (lastPart === 'GA') {
                                        result = Akurat;
                                    } else if (lastPart === 'GL') {
                                        result = Liar;
                                    }

                                    return result; // Return the computed result
                                }
                            },
                            {

                                visible: canedit,
                                render: function(data, type, row, meta) {
                                    var buttons =
                                        '<button class="edit-btn">Edit</button>' +
                                        '<button class="delete-btn">Delete</button>';
                                    return buttons;
                                }
                            }
                        ],
                    });

                    // Populate DataTable with data
                    sidakTPhNEw.clear().rows.add(parseResult['sidak_tph2']).draw();


                    $('#new_Sidak').on('click', '.edit-btn', function() {
                        var rowData = sidakTPhNEw.row($(this).closest('tr')).data();

                        editSidakTPh(rowData);
                    });

                    $('#new_Sidak').on('click', '.delete-btn', function() {
                        var rowData = sidakTPhNEw.row($(this).closest('tr')).data();

                        deleteRowBuah(rowData);
                    });


                    function editSidakTPh(rowData) {
                        // Save the selected row index
                        // selectedRowIndex = id;

                        // Retrieve the id from the first column of the selected row
                        // var rowData = sidakTPhNEw.row(id).data();
                        // var rowId = rowData[0];

                        // Populate the form with the data of the selected row
                        $('#editId_buah').val(rowData.id).prop('disabled', true);
                        $('#update-estBH').val(rowData.est).prop('disabled', true);
                        $('#update-afdBH').val(rowData.afd).prop('disabled', true);
                        $('#update-blok_bh').val(rowData.blok)
                        $('#update-qc').val(rowData.qc)


                        $('#update-brdtgl').val(rowData.bt_tph)
                        $('#update-brdjln').val(rowData.bt_jalan)
                        $('#update-brdbin').val(rowData.bt_bin)
                        $('#update-jumkrng').val(rowData.jum_karung)
                        $('#update-buahtgl').val(rowData.buah_tinggal)
                        $('#update-restan').val(rowData.restan_unreported)
                        $('#update-hplus').val(rowData.status)
                        // update-hplus
                        // hplus
                        var modal = new bootstrap.Modal(document.getElementById('editModalTPH'));
                        modal.show();

                        // $('#editModalTPH').modal('show');
                    }

                    $(document).ready(function() {
                        // Close modal when the close button is clicked
                        $('#closeModalBtn_buah').click(function() {
                            // $('#editModalTPH').modal('hide');
                            var modal = new bootstrap.Modal(document.getElementById('editModalTPH'));
                            modal.hide();
                        });

                        // Submit the form when the Save Changes button is clicked
                        $('#saveChangesBtn_buah').off('click').on('click', function() {
                            $('#editForm_buah').submit();
                        });

                        function isNumber(value) {
                            return !isNaN(parseFloat(value)) && isFinite(value);
                        }

                        $('#editForm_buah').submit(function(e) {
                            e.preventDefault(); // Prevent the default form submission

                            // Get the form data
                            var formData = new FormData(this);
                            formData.append('id', $('#editId_buah').val());

                            var brdtgl = $('#update-brdtgl').val();
                            var brdjln = $('#update-brdjln').val();
                            var brdbin = $('#update-brdbin').val();
                            var jumkarung = $('#update-jumkrng').val();
                            var buahtgl = $('#update-buahtgl').val();
                            var restan = $('#update-restan').val();
                            var hplus = $('#update-hplus').val();

                            if (!isNumber(brdtgl) ||
                                !isNumber(brdjln) ||
                                !isNumber(brdbin) ||
                                !isNumber(jumkarung) ||
                                !isNumber(buahtgl) ||

                                !isNumber(restan)
                            ) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Masukan Error',
                                    text: 'Hanya bisa di masukan angka Saja!'
                                });
                                return;
                            }

                            // console.log(brdtgl);
                            // Send the AJAX request
                            $.ajax({
                                type: 'POST',
                                url: '{{ route("updatesidakTPhnew") }}',
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function(response) {
                                    // console.log(response);
                                    // Close the modal
                                    // $('#editModalTPH').modal('hide');
                                    var modal = new bootstrap.Modal(document.getElementById('editModalTPH'));
                                    modal.hide();

                                    // console.log(formData);

                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success',
                                        text: 'Data berhasil diperbarui!'
                                    }).then(function() {
                                        // Refresh the data on the page
                                        // fetchAndUpdateData();
                                        location.reload();
                                    });
                                },
                                error: function(xhr, status, error) {
                                    console.error(xhr.responseText);
                                    // Show an error message or perform any other actions
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Gagal memperbarui data!'
                                    });
                                }
                            });
                        });
                    });

                    function deleteRowBuah(id) {
                        // Save the selected row index
                        selectedRowIndex = id;

                        // Get the selected row data
                        var rowData = sidakTPhNEw.row(id).data();
                        var rowId = rowData.id;

                        // Show the delete modal
                        var modal = new bootstrap.Modal(document.getElementById('deleteModalancak'));
                        modal.show();
                        // Handle delete confirmation
                        $('#confirmDeleteBtn').off('click').on('click', function() {
                            // Create a form data object
                            var formData = new FormData();
                            formData.append('delete_id', rowId);

                            // Get the CSRF token from the meta tag
                            var csrfToken = $('meta[name="csrf-token"]').attr('content');

                            // Set the CSRF token in the request headers
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken
                                }
                            });

                            // Send the AJAX request to the controller
                            $.ajax({
                                url: '{{ route("deletedetailtph") }}',
                                method: 'POST',
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function(response) {
                                    // Close the delete modal
                                    // $('#deleteModalancak').modal('hide');

                                    // Show a success message using SweetAlert
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success',
                                        text: 'Data deleted successfully!',
                                    }).then(function() {
                                        location.reload();
                                    });
                                },
                                error: function(xhr, status, error) {
                                    // Handle the error if needed
                                    console.error(error);

                                    // Close the delete modal
                                    // $('#deleteModalancak').modal('hide');
                                }
                            });
                        });
                    }


                    // end new sidak 
                }


            });
        }


        function openModal(src, komentar) {
            var modalImg = document.getElementById("img01");
            modalImg.src = src;
            var modalKomentar = document.getElementById("modalKomentar");
            modalKomentar.textContent = komentar;

            var downloadButton = document.getElementById("downloadButton");
            downloadButton.addEventListener("click", handleDownload);

            var myModal = new bootstrap.Modal(document.getElementById('myModal'), {});
            myModal.show();

            var closeButton = document.getElementById('modalCloseButton');
            closeButton.addEventListener('click', function() {
                myModal.hide();
                downloadButton.removeEventListener("click", handleDownload); // Remove the event listener when the modal is closed
                URL.revokeObjectURL(modalImg.src); // Clean up the object URL to avoid memory leaks
            });
        }

        function handleDownload(event) {
            var src = document.getElementById("img01").src;
            var filename = getFilenameFromSrc(src);
            downloadImage(src, filename);
        }

        function getFilenameFromSrc(src) {
            var startIndex = src.lastIndexOf("/") + 1;
            var endIndex = src.lastIndexOf(".");
            var filename = src.substring(startIndex, endIndex);

            // Split the filename into an array using "_" as the delimiter
            var parts = filename.split("_");

            // Extract the desired parts from the array
            var part1 = parts[0]; // IMA
            var part2 = parts[1]; // 2023710
            var part3 = parts[2]; // 100348
            var part4 = parts[3]; // KNE
            var part5 = parts[4]; // OA
            var part6 = parts[5]; // R01404
            var part7 = parts[6]; // 102

            // Construct the desired filename using the extracted parts and spaces
            var customPart = "Est " + "_" + part4 + " Afd " + "_" + part5 + " Sidak " + "_" + part1 + " Blok " + "_" + part6;

            return customPart;
        }




        function downloadImage(imageName, filename) {
            var downloadLink = "https://srs-ssms.com/qc_inspeksi/get_qcIMG.php?image=" + encodeURIComponent(imageName);

            fetch(downloadLink)
                .then(response => response.blob())
                .then(blob => {
                    var url = URL.createObjectURL(blob);
                    var a = document.createElement("a");
                    a.href = url;
                    a.download = filename + ".jpg"; // Use the filename for the downloaded image
                    a.style.display = "none"; // Hide the anchor element

                    document.body.appendChild(a);

                    a.click(); // Trigger the click event on the hidden anchor element

                    // Clean up and remove the anchor element after the download
                    a.remove();
                    URL.revokeObjectURL(url);
                })
                .catch(error => {
                    console.error("Error downloading image:", error);
                });
        }

        function goBack() {
            // Save the selected tab to local storage
            localStorage.setItem('selectedTab', 'nav-data-tab');

            // Redirect to the target page
            window.location.href = "https://qc-apps.srs-ssms.com/dashboardtph";
        }


        $("#showFindingYear").click(function() {

            getMapsTph();
        });

        function getMapsTph() {
            var _token = $('input[name="_token"]').val();
            var est = $("#est").val();
            var afd = $("#afd").val();
            var date = $("#inputDate").val();
            var map = L.map('map');
            map.remove();

            $.ajax({
                url: "{{ route('getMapsTph') }}",
                method: "get",
                data: {
                    est: est,
                    afd: afd,
                    date: date,
                    _token: _token
                },
                success: function(result) {
                    var plot = JSON.parse(result);

                    const plotResult = Object.entries(plot['plot']);
                    const markerResult = Object.entries(plot['marker']);
                    const blokResult = Object.entries(plot['blok']);
                    var imgArray = Object.entries(plot['img']);
                    const plotarrow = Object.entries(plot['plotarrow']);
                    $('#foto_temuan').empty();

                    // Add the header and horizontal rule
                    $('#foto_temuan').append('<h4 class="text-center mt-2" style="font-weight: bold">FOTO TEMUAN 2</h4>');
                    $('#foto_temuan').append('<hr>');

                    // Create the row div
                    const rowDiv = $('<div>').addClass('row');
                    $('#foto_temuan').append(rowDiv);

                    // Iterate over the imgArray and populate the div with the images
                    imgArray.forEach(function(item) {
                        const foto = item[1]['foto'];
                        const title = item[1]['title'];
                        const file = 'https://mobilepro.srs-ssms.com/storage/app/public/qc/sidak_tph/' + foto;
                        const file_headers = $.ajax({
                            url: file,
                            type: 'HEAD',
                            async: false
                        }).done(function() {
                            return true;
                        }).fail(function() {
                            return false;
                        });

                        if (file_headers !== false) {
                            // Create the column div
                            const colDiv = $('<div>').addClass('col-3');
                            rowDiv.append(colDiv);

                            // Add the image, hidden input, and paragraph
                            colDiv.append($('<img>').attr('src', 'https://mobilepro.srs-ssms.com/storage/app/public/qc/sidak_tph/' + foto).addClass('img-fluid popup_image'));
                            colDiv.append($('<input>').attr('type', 'hidden').val(title).attr('id', 'titleImg'));
                            colDiv.append($('<p>').addClass('text-center mt-3').css('font-weight', 'bold').text(title));
                        }
                    });
                    var mapContainer = L.DomUtil.get('map');
                    if (mapContainer != null) {
                        mapContainer._leaflet_id = null;
                    }
                    var map = L.map('map').setView([-2.2745234, 111.61404248], 13);


                    var googleStreet = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png").addTo(map);

                    var googleSatellite = L.tileLayer('http://{s}.google.com/vt?lyrs=s&x={x}&y={y}&z={z}', {
                        maxZoom: 20,
                        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
                    });
                    // map.addControl(new L.Control.Fullscreen());
                    // return map;
                    var baseMaps = {
                        "Google Street": googleStreet,
                        "Google Satellite": googleSatellite
                    };
                    L.control.layers(baseMaps).addTo(map);


                    var getPlotStr = '{"type"'
                    getPlotStr += ":"
                    getPlotStr += '"FeatureCollection",'
                    getPlotStr += '"features"'
                    getPlotStr += ":"
                    getPlotStr += '['
                    for (let i = 0; i < blokResult.length; i++) {
                        getPlotStr += '{"type"'
                        getPlotStr += ":"
                        getPlotStr += '"Feature",'
                        getPlotStr += '"properties"'
                        getPlotStr += ":"
                        getPlotStr += '{"blok"'
                        getPlotStr += ":"
                        getPlotStr += '"' + blokResult[i][1]['blok'] + '",'
                        getPlotStr += '"estate"'
                        getPlotStr += ":"
                        getPlotStr += '"' + blokResult[i][1]['estate'] + '"'
                        getPlotStr += '},'
                        getPlotStr += '"geometry"'
                        getPlotStr += ":"
                        getPlotStr += '{"coordinates"'
                        getPlotStr += ":"
                        getPlotStr += '[['
                        getPlotStr += blokResult[i][1]['latln']
                        getPlotStr += ']],"type"'
                        getPlotStr += ":"
                        getPlotStr += '"Polygon"'
                        getPlotStr += '}},'
                    }
                    getPlotStr = getPlotStr.substring(0, getPlotStr.length - 1);
                    getPlotStr += ']}'


                    var blok = JSON.parse(getPlotStr)

                    var test = L.geoJSON(blok, {
                            onEachFeature: function(feature, layer) {

                                layer.myTag = 'BlokMarker'
                                var label = L.marker(layer.getBounds().getCenter(), {
                                    icon: L.divIcon({
                                        className: 'label-bidang',
                                        html: feature.properties.blok,
                                        iconSize: [50, 10]
                                    })
                                }).addTo(map);

                                layer.addTo(map);
                            },
                            style: function(feature) {
                                switch (feature.properties.afdeling) {
                                    case 'OA':
                                        return {
                                            fillColor: "#ff1744",
                                                color: 'white',
                                                fillOpacity: 0.4,
                                                opacity: 0.4,
                                        };
                                    case 'OB':
                                        return {
                                            fillColor: "#d500f9",
                                                color: 'white',
                                                fillOpacity: 0.4,
                                                opacity: 0.4,
                                        };
                                    case 'OC':
                                        return {
                                            fillColor: "#ffa000",
                                                color: 'white',
                                                fillOpacity: 0.4,
                                                opacity: 0.4,
                                        };
                                    case 'OD':
                                        return {
                                            fillColor: "#00b0ff",
                                                color: 'white',
                                                fillOpacity: 0.4,
                                                opacity: 0.4,
                                        };

                                    case 'OE':
                                        return {
                                            fillColor: "#67D98A",
                                                color: 'white',
                                                fillOpacity: 0.4,
                                                opacity: 0.4,

                                        };
                                    case 'OF':
                                        return {
                                            fillColor: "#666666",
                                                color: 'white',
                                                fillOpacity: 0.4,
                                                opacity: 0.4,

                                        };
                                }
                            }
                        })
                        .addTo(map);

                    map.fitBounds(test.getBounds());


                    for (let i = 0; i < markerResult.length; i++) {
                        let latlng = JSON.parse(markerResult[i][1]['latln']);
                        // Define the custom icons
                        let numberIcon = L.icon({
                            iconUrl: "https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-gold.png",
                            shadowUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png",
                            iconSize: [14, 21],
                            iconAnchor: [7, 22],
                            popupAnchor: [1, -34],
                            shadowSize: [28, 20],
                        });

                        let fotoTemuanIcon = L.icon({
                            iconUrl: "https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png",
                            shadowUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png",
                            iconSize: [14, 21],
                            iconAnchor: [7, 22],
                            popupAnchor: [1, -34],
                            shadowSize: [28, 20],
                        });

                        let markerIcon = numberIcon; // Default icon

                        if (markerResult[i][1]['foto_temuan1'] || markerResult[i][1]['foto_temuan2']) {
                            markerIcon = fotoTemuanIcon; // Use fotoTemuanIcon if either foto_temuan1 or foto_temuan2 exists
                        }

                        let marker = L.marker(latlng, {
                            icon: markerIcon
                        });


                        var popupContent = `<strong>Jam Sidak: </strong>${markerResult[i][1]['jam']}<br/>`;
                        popupContent += `<strong>Nomor TPH: </strong>${markerResult[i][1]['notph']}<br/>`;
                        popupContent += `<strong>Afdeling: </strong>${markerResult[i][1]['afd']}<br/>`;
                        popupContent += `<strong>Blok: </strong>${markerResult[i][1]['blok']}<br/>`;
                        popupContent += `<strong>Brondol_tinggal: </strong>${markerResult[i][1]['brondol_tinggal']}<br/>`;
                        popupContent += `<strong>Jumlah Karung: </strong>${markerResult[i][1]['jum_karung']}<br/>`;
                        popupContent += `<strong>Buah Tinggal: </strong>${markerResult[i][1]['buah_tinggal']}<br/>`;
                        popupContent += `<strong>Restan Unreported: </strong>${markerResult[i][1]['restan_unreported']}<br/>`;

                        if (markerResult[i][1]['foto_temuan1']) {
                            popupContent += `<img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/sidak_tph/${markerResult[i][1]['foto_temuan1']}" alt="Foto Temuan" style="max-width:200px; height:auto;" onclick="openModal(this.src, '${markerResult[i][1]['komentar1']}')"><br/>`;
                        }
                        if (markerResult[i][1]['foto_temuan2']) {
                            popupContent += `<img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/sidak_tph/${markerResult[i][1]['foto_temuan2']}" alt="Foto Temuan" style="max-width:200px; height:auto;" onclick="openModal(this.src, '${markerResult[i][1]['komentar2']}')"><br/>`;
                        }

                        marker.bindPopup(popupContent);

                        // Add the marker to the map
                        marker.addTo(map);

                    }
                    const latLngArray = plotarrow.map((item) => {
                        const latLngString = item[1].latln;
                        const coordinates = latLngString.match(/\[(.*?)\]/g);
                        if (coordinates) {
                            return coordinates.map((coord) => {
                                const [longitude, latitude] = coord
                                    .replace('[', '')
                                    .replace(']', '')
                                    .split(',')
                                    .map(parseFloat);
                                return [latitude, longitude]; // Reversed to follow [lat, lon] format
                            });
                        }
                        return [];
                    });

                    latLngArray.forEach((coordinates, index) => {
                        for (let i = 0; i < coordinates.length - 1; i++) {
                            const startLatLng = coordinates[i];
                            const endLatLng = coordinates[i + 1];
                            const name = plotarrow[index][0]; // Get the name from plotarrow at the corresponding index

                            const arrow = L.polyline([startLatLng, endLatLng], {
                                color: 'red',
                                weight: 2
                            }).addTo(map);

                            arrow.on('click', function() {
                                const popupContent = `<strong>Petugas QC: </strong>${name}<br/><strong>`;
                                L.popup()
                                    .setLatLng(startLatLng) // or any suitable position
                                    .setContent(popupContent)
                                    .openOn(map);
                            });

                            const arrowHead = L.polylineDecorator(arrow, {
                                patterns: [{
                                    offset: '50%',
                                    repeat: 50,
                                    symbol: L.Symbol.arrowHead({
                                        pixelSize: 12,
                                        polygon: false,
                                        pathOptions: {
                                            color: 'yellow'
                                        }
                                    })
                                }]
                            }).addTo(map);
                        }
                    });


                    // Define legendContainer first
                    var legendContainer = L.control({
                        position: 'bottomright'
                    });

                    // Now define the onAdd function for legendContainer
                    legendContainer.onAdd = function(map) {
                        var div = L.DomUtil.create('div', 'legend');
                        div.innerHTML = '<h4 style="text-align: center;">Info</h4>';

                        var temuanCount = 0;
                        for (let i = 0; i < markerResult.length; i++) {
                            if (markerResult[i][1]['foto_temuan1'] || markerResult[i][1]['foto_temuan2']) {
                                temuanCount++;
                            }
                        }

                        var totalItemsCount = markerResult.length;
                        // div.innerHTML += '<div class="legend-item">Total Sidak TPH: ' + totalItemsCount + '</div>'; // Added the legend item for total items count

                        div.innerHTML += '<div class="legend-item"><img src="https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png" class="legend-icon"> Temuan (' + temuanCount + ')</div>';

                        return div;
                    };

                    // Now add legendContainer to the map
                    legendContainer.addTo(map);

                    Swal.close()
                },
                error: function(xhr, status, error) {
                    console.log("An error occurred:", error);
                    Swal.fire('Error', 'Operation Error', 'error');
                    // Swal.close()
                }
            });
        }


        let getest = @json($est)

        function selectDate() {
            Swal.fire({
                title: "Apakah Anda ingin mengubah tanggal sidak?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    let tanggalorix = document.getElementById('inputDate').value

                    Swal.fire({
                        title: "Perhatian!",
                        html: 'Ini Akan memindahkan Data dari semua afdeling {{$est}} di tanggal ' + tanggalorix + '  ke tanggal yang dipilih: <br><input id="swal-input-date" type="date" class="swal2-input">',
                        showCancelButton: true,
                        confirmButtonText: "Pindahkan",
                        cancelButtonText: "Batal",
                        showLoaderOnConfirm: true,
                        allowOutsideClick: false,
                        preConfirm: () => {
                            const selectedDate = document.getElementById('swal-input-date').value;
                            if (!selectedDate) {
                                Swal.showValidationMessage('Silakan pilih tanggal!');
                            }
                            // Handle the selected date with AJAX request
                            // Replace the code below with your actual logic to handle the selected date with AJAX
                            return new Promise((resolve) => {
                                // Simulate AJAX request delay
                                setTimeout(() => {
                                    resolve(selectedDate);
                                }, 1000);
                            });
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // If user confirms, show a success message

                            // console.log(getest);

                            var tanggalori = document.getElementById('inputDate').value
                            var tanggalset = result.value
                            var type = 'sidaktph'
                            // console.log(tanggal);
                            var _token = $('input[name="_token"]').val();
                            $.ajax({
                                url: "{{ route('changedatadate') }}",
                                method: "post",
                                data: {
                                    tglreal: tanggalori,
                                    tgledit: tanggalset,
                                    est: getest,
                                    type: type,
                                    _token: _token
                                },
                                success: function(result) {
                                    // Check if data was successfully updated
                                    if (result && result.message === 'Data berhasil diupdate') {
                                        Swal.fire({
                                            title: 'Success',
                                            text: 'Data berhasil diupdate',
                                            icon: 'success',
                                            allowOutsideClick: false // Prevents clicking outside the modal to close it
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                location.reload(); // Reload the page
                                            }
                                        });
                                    } else {
                                        Swal.fire('Error', 'Gagal mengupdate data', 'error');
                                    }
                                },
                                error: function() {
                                    Swal.fire('Error', 'Gagal menghubungi server', 'error');
                                }
                            });

                        } else {
                            // If user cancels, show a message
                            Swal.fire("Pemilihan tanggal dibatalkan!", "", "info");
                        }
                    });
                }
            });
        }
        if (currentUserName === 'Askep' || currentUserName === 'Manager') {
            document.getElementById("moveDataButton").addEventListener("click", selectDate);
        }
        // Attach click event listener to the button
    </script>

</x-layout.app>