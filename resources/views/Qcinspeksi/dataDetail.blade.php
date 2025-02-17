<x-layout.app>
    <link rel="stylesheet" href="{{ asset('qc_css/qcinspeksi/detail.css') }}">
    <div class="content-wrapper">
        <!-- Button trigger modal -->

        <div class="card table_wrapper">
            <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark ">
                <h2>REKAP HARIAN SIDAK INPEKSI </h2>
                <!-- <h1>{{auth()->user()->id_jabatan}}</h1> -->
            </div>
            @if ($edit_permittion)
            <div class="alert alert-danger d-none d-flex flex-column align-items-start justify-content-between" role="alert" id="notverif">
                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:">
                    <use xlink:href="#exclamation-triangle-fill" />
                </svg>
                <div>
                    Data belum Tervertifikasi oleh Manager/Askep/Asisten
                </div>
                @if (can_edit_all_atasan())
                <div>
                    <button class="btn btn-primary align-self-end" onclick="verifbutton()">Verif now</button>
                </div>

                @endif
            </div>
            <div class="alert alert-warning d-none d-flex align-items-center" role="alert" id="asistennotverif">
                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:">
                    <use xlink:href="#exclamation-triangle-fill" />
                </svg>
                <div>
                    Asisten Belum melakukan Aprroval
                </div>
                @if (can_edit_asisten())

                <div>
                    <button class="btn btn-primary align-self-end" onclick="verifbutton()">Verif now</button>
                </div>

                @endif
            </div>
            <div class="alert alert-warning d-none d-flex align-items-center" role="alert" id="askep_manager_not_approved">
                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:">
                    <use xlink:href="#exclamation-triangle-fill" />
                </svg>
                <div>
                    Askep/Manager Belum melakukan Aprroval
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
            @endif

            <div class="header-container">
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
                        <form action="{{ route('filterDataDetail') }}" method="POST" class="form-inline">
                            <div class="date">
                                {{ csrf_field() }}

                                <input type="hidden" name="est" id="est" value="{{$est}}">
                                <input type="hidden" name="afd" id="afd" value="{{$afd}}">

                                <div class="form-group">
                                    <select class="form-control mb-2 mr-sm-2" name="date" id="inputDate">
                                        @foreach($dates_option as $common)
                                        <option value="{{$common}}">{{$common}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <button type="button" class="ml-2 btn btn-primary mb-2" id="show-button">Show</button>

                            </div>
                        </form>

                        <div class="afd mt-2"> ESTATE/ AFD : {{$est}}-{{$afd}}</div>
                        <!-- <div class="afd mt-2"> Rrgional : {{$reg}}-{{$afd}}</div> -->
                        <div class="afd">TANGGAL : <span id="selectedDate">{{ $tanggal }}</span></div>
                    </div>
                </div>
            </div>
            <!-- animasi loading -->
            <div id="lottie-container" style="width: 100%; height: 100%; position: fixed; top: 0; left: 0; background-color: rgba(255, 255, 255, 0.8); display: none; z-index: 9999;">
                <div id="lottie-animation" style="width: 200px; height: 200px; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                </div>
            </div>
            <div id="lottie-container1" style="width: 100%; height: 100%; position: fixed; top: 0; left: 0; background-color: rgba(255, 255, 255, 0.8); display: none; z-index: 9999;">
                <div id="lottie-animation" style="width: 100px; height: 100px; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                </div>
            </div>


            <!-- end animasi -->
        </div>
        <div class="d-flex justify-content-end mt-3 mb-2 ml-3 mr-3">
            @if (can_edit_all_atasan() )

            <button id="moveDataButton" class="btn btn-primary mr-3" disabled>Pindah Data</button>
            @endif
            <button id="back-to-data-btn" class="btn btn-primary" onclick="goBack()">Back to Data</button>
            <div class="d-flex align-items-center">
                <!-- Your existing PDF and Excel buttons and their respective forms -->


                <form action="{{ route('pdfBA') }}" method="POST" class="form-inline" style="display: inline;" target="_blank">
                    {{ csrf_field() }}
                    <!-- Your hidden inputs -->

                    <input type="hidden" name="estBA" id="estpdf" value="{{$est}}">
                    <input type="hidden" name="afdBA" id="afdpdf" value="{{$afd}}">
                    <input type="hidden" name="tglPDF" id="tglPDF" value="{{ $tanggal }}">
                    <input type="hidden" name="regPDF" id="regPDF" value="{{ $reg }}">
                    <button type="submit" class="download-btn ml-2" id="download-button" disabled>
                        <div id="lottie-download" style="width: 24px; height: 24px; display: inline-block;"></div> Download
                        BA PDF
                    </button>
                </form>
            </div>
        </div>


        <div class="row">


            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h1 style="text-align: center;">Tabel Mutu Ancak</h1>
                        <div class="table-wrapper">
                            <table class="my-table" id="mutuAncakTable">
                                <thead>
                                    <tr>
                                        <th class="sticky" style="background-color: white;">ID</th>
                                        <th class="sticky" style="background-color: white;">Estate.</th>
                                        <th class="sticky" style="background-color: white;">Afdeling.</th>
                                        <th class="sticky" style="background-color: white;">Blok.</th>
                                        <th class="sticky" style="background-color: white;">petugas.</th>
                                        <th class="sticky" style="background-color: white;">datetime.</th>
                                        <th class="sticky" style="background-color: white;">luas blok.</th>
                                        <th class="sticky" style="background-color: white;">Sph.</th>
                                        <th class="sticky" style="background-color: white;">Baris 1.</th>
                                        <th class="sticky" style="background-color: white;">Baris 2.</th>
                                        <th class="sticky" style="background-color: white;">Jalur masuk.</th>
                                        <th class="sticky" style="background-color: white;">Status Panen.</th>
                                        <th class="sticky" style="background-color: white;">Kemandoran.</th>
                                        <th class="sticky" style="background-color: white;">Ancak Pemanen.</th>
                                        <th class="sticky" style="background-color: white;">Pokok Panen.</th>
                                        <th class="sticky" style="background-color: white;">Pokok Sample.</th>
                                        <th class="sticky" style="background-color: white;">Janjang Panen.</th>
                                        <th class="sticky" style="background-color: white;">Brondolan (P).</th>
                                        <th class="sticky" style="background-color: white;">Brondolan (K).</th>
                                        <th class="sticky" style="background-color: white;">Brondolan (GL).</th>
                                        <th class="sticky" style="background-color: white;">Buah Tinggal (S).</th>
                                        <th class="sticky" style="background-color: white;">Buah Tinggal (M1)</th>
                                        <th class="sticky" style="background-color: white;">Buah Tinggal (M2)</th>
                                        <th class="sticky" style="background-color: white;">Buah Tinggal (M3)</th>
                                        <th class="sticky" style="background-color: white;">Pelepah Sengkleh</th>
                                        <th class="sticky" style="background-color: white;">Frond Stacking</th>
                                        <th class="sticky" style="background-color: white;">Piringan Semak</th>
                                        <th class="sticky" style="background-color: white;">Pokok Kuning</th>
                                        <th class="sticky" style="background-color: white;">Underpruning</th>
                                        <th class="sticky" style="background-color: white;">Overpruning</th>
                                        <th class="sticky" style="background-color: white;width:fit-content">Maps</th>
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



            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h1 style="text-align: center;">Tabel Mutu Buah</h1>
                        <div class="table-wrapper">
                            <table class="my-table" id="mutuBuahable">
                                <thead>
                                    <tr>
                                        <th class="sticky" style="background-color: white;">ID</th>
                                        <th class="sticky" style="background-color: white;">Estate</th>
                                        <th class="sticky" style="background-color: white;">Afdeling</th>
                                        <th class="sticky" style="background-color: white;">TPH Baris</th>
                                        <th class="sticky" style="background-color: white;">Blok</th>
                                        <th class="sticky" style="background-color: white;">Status Panen</th>
                                        <th class="sticky" style="background-color: white;">Petugas</th>
                                        <th class="sticky" style="background-color: white;">Ancak Pemanen</th>
                                        <th class="sticky" style="background-color: white;">Kemandoran</th>
                                        <th class="sticky" style="background-color: white;">Buah Mentah Tanpa Brondol</th>
                                        <th class="sticky" style="background-color: white;">Buah Mentah Kurang Brondol</th>
                                        <th class="sticky" style="background-color: white;">Empty Bunch</th>
                                        <th class="sticky" style="background-color: white;">Jumlah Janjang</th>
                                        <th class="sticky" style="background-color: white;">Overripe</th>
                                        <th class="sticky" style="background-color: white;">Abnormal</th>
                                        <th class="sticky" style="background-color: white;">Tidak Standar V-cut</th>
                                        <th class="sticky" style="background-color: white;">Alas Brondolan</th>
                                        <th class="sticky" style="background-color: white;">Akurasi Maps</th>
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
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h1 style="text-align: center;">Tabel Mutu Transport</h1>
                        <div class="table-wrapper">
                            <table class="my-table" id="mutuTransportable">
                                <thead>
                                    <tr>
                                        <th class="sticky" style="background-color: white;">ID</th>
                                        <th class="sticky" style="background-color: white;">Estate</th>
                                        <th class="sticky" style="background-color: white;">Afdeling</th>
                                        <th class="sticky" style="background-color: white;">TPH Baris</th>
                                        <th class="sticky" style="background-color: white;">Blok</th>
                                        <th class="sticky" style="background-color: white;">Status Panen</th>
                                        <th class="sticky" style="background-color: white;">Petugas</th>
                                        <th class="sticky" style="background-color: white;">Datetime</th>
                                        <th class="sticky" style="background-color: white;">Kemandoran</th>
                                        <th class="sticky" style="background-color: white;">Luas Blok</th>
                                        <th class="sticky" style="background-color: white;">Brondol di TPH</th>
                                        <th class="sticky" style="background-color: white;">Buah di TPH</th>
                                        <th class="sticky" style="background-color: white;">Akurasi Maps</th>
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



        <!-- Modal -->
        <div id="imageModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <img id="modalImage" src="" style="width: 100%;">
            </div>
        </div>


        <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <h2>Delete Mutu Ancak</h2>
                <p>Apakah anda Yakin ingin Menghapus?</p>
                <div class="row">
                    <div class="col text-right">
                        <form id="delete-form" action="{{ route('deleteBA') }}" method="POST" onsubmit="event.preventDefault(); handleDeleteFormSubmit();">
                            {{ csrf_field() }}
                            <input type="hidden" id="delete-id" name="id">

                            <div class="button-group">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </div>
                        </form>
                    </div>
                    <div class="col  text-left">

                        <button id="close-delete-modal" class="btn btn-secondary">Tutup</button>
                    </div>
                </div>

            </div>
        </div>
        <div class="modal fade" id="delete-modal-buah" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <h2>Delete Mutu Buah</h2>
                <p>Apakah anda Yakin ingin Menghapus?</p>
                <div class="row">
                    <div class="col text-right">

                        <form id="delete-forms" action="{{ route('deleteBA') }}" method="POST" onsubmit="event.preventDefault();">
                            {{ csrf_field() }}
                            <input type="hidden" id="delete-ids" name="ids">

                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                    <div class="col text-left">
                        <button id="close-delete-modals" class="btn btn-secondary">Tutup</button>

                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="delete-modal-transport" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <h2>Delete Mutu Transport</h2>
                <p>Apakah anda Yakin ingin Menghapus?</p>
                <div class="row">
                    <div class="col text-right">
                        <form id="delete-form-trans" method="POST" onsubmit="event.preventDefault();">
                            {{ csrf_field() }}
                            <input type="hidden" id="delete-transport" name="id_transport">

                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                    <div class="col text-left">
                        <button id="close-delete-transport" class="btn btn-secondary">Tutup</button>

                    </div>
                </div>
            </div>
        </div>

        <br>
        <br>


        <H1 class="text-center ">Data Perblok</H1>
        <div class="ml-3 mr-3 mb-3">
            <div class="row text-center tbl-fixed">
                <table class="table-responsive">
                    <thead style="color: white;">
                        <tr>
                            <th class="freeze-col align-middle" rowspan="3" bgcolor="#1c5870">No</th>
                            <th class="freeze-col align-middle" rowspan="3" bgcolor="#1c5870">BLOK</th>
                            <th class="align-middle" colspan="5" rowspan="2" bgcolor="#588434">DATA BLOK SAMPEL</th>
                            <th class="align-middle" colspan="17" bgcolor="#588434">Mutu Ancak (MA)</th>
                            <th class="align-middle" colspan="8" bgcolor="blue">Mutu Transport (MT)</th>
                            <th class="align-middle" rowspan="3" bgcolor="gray" style="color: #fff;">All Skor</th>
                            <th class="align-middle" rowspan="3" bgcolor="gray" style="color: #fff;">Kategori</th>
                            </th>
                        </tr>
                        <tr>
                            <!-- Table Mutu Ancak -->
                            <th class="align-middle" colspan="6" bgcolor="#588434">Brondolan Tinggal</th>
                            <th class="align-middle" colspan="7" bgcolor="#588434">Buah Tinggal</th>
                            <th class="align-middle" colspan="3" bgcolor="#588434">Pelepah Sengkleh</th>
                            <th class="align-middle" rowspan="2" bgcolor="#588434">Total Skor</th>

                            <th class="align-middle" rowspan="2" bgcolor="blue">TPH Sampel</th>
                            <th class="align-middle" colspan="3" bgcolor="blue">Brd Tinggal</th>
                            <th class="align-middle" colspan="3" bgcolor="blue">Buah Tinggal</th>
                            <th class="align-middle" rowspan="2" bgcolor="blue">Total Skor</th>
                        </tr>
                        <tr>
                            <!-- Table Mutu Ancak -->
                            <th class="align-middle" bgcolor="#588434">Status Panen</th>
                            <th class="align-middle" bgcolor="#588434">Jumlah Pokok Sampel</th>
                            <th class="align-middle" bgcolor="#588434">Luas Ha Sampel</th>
                            <th class="align-middle" bgcolor="#588434">Jumlah Jjg Panen</th>
                            <th class="align-middle" bgcolor="#588434">AKP Realisasi</th>
                            <th class="align-middle" bgcolor="#588434">P</th>
                            <th class="align-middle" bgcolor="#588434">K</th>
                            <th class="align-middle" bgcolor="#588434">GL</th>
                            <th class="align-middle" bgcolor="#588434">Total Brd</th>
                            <th class="align-middle" bgcolor="#588434">Brd/JJG</th>
                            <th class="align-middle" bgcolor="#588434">Skor</th>
                            <th class="align-middle" bgcolor="#588434">S</th>
                            <th class="align-middle" bgcolor="#588434">M1</th>
                            <th class="align-middle" bgcolor="#588434">M2</th>
                            <th class="align-middle" bgcolor="#588434">M3</th>
                            <th class="align-middle" bgcolor="#588434">Total JJG</th>
                            <th class="align-middle" bgcolor="#588434">%</th>
                            <th class="align-middle" bgcolor="#588434">Skor</th>
                            <th class="align-middle" bgcolor="#588434">Pokok</th>
                            <th class="align-middle" bgcolor="#588434">%</th>
                            <th class="align-middle" bgcolor="#588434">Skor</th>

                            <th class="align-middle" bgcolor="blue">Butir</th>
                            <th class="align-middle" bgcolor="blue">Butir/TPH</th>
                            <th class="align-middle" bgcolor="blue">Skor</th>
                            <th class="align-middle" bgcolor="blue">Jjg</th>
                            <th class="align-middle" bgcolor="blue">Jjg/TPH</th>
                            <th class="align-middle" bgcolor="blue">Skor</th>
                            <!-- Table Mutu Buah -->
                        </tr>
                    </thead>

                    <tbody id="dataInspeksi">
                        <!-- <td>PLE</td>
                                    <td>OG</td> -->
                    </tbody>
                </table>
            </div>
        </div>
        @if ($reg !== 2 )
        <H1 class="text-center ">Data Per Kemandoran</H1>
        <div class="ml-3 mr-3 mb-3">
            <div class="row text-center tbl-fixed">
                <table class="table-responsive">
                    <thead style="color: white;">
                        <tr>
                            <th class="freeze-col align-middle" rowspan="3" bgcolor="#1c5870">Mandor</th>
                            <th class="align-middle" colspan="4" rowspan="2" bgcolor="#588434">DATA BLOK SAMPEL</th>
                            <th class="align-middle" colspan="17" bgcolor="#588434">Mutu Ancak (MA)</th>
                            <th class="align-middle" colspan="8" bgcolor="blue">Mutu Transport (MT)</th>
                            <th class="align-middle" colspan="23" bgcolor="#ffc404" style="color: #000000;">Mutu Buah (MB)
                            <th class="align-middle" rowspan="3" bgcolor="gray" style="color: #fff;">All Skor</th>
                            <th class="align-middle" rowspan="3" bgcolor="gray" style="color: #fff;">Kategori</th>
                            </th>
                        </tr>
                        <tr>
                            <!-- Table Mutu Ancak -->
                            <th class="align-middle" colspan="6" bgcolor="#588434">Brondolan Tinggal</th>
                            <th class="align-middle" colspan="7" bgcolor="#588434">Buah Tinggal</th>
                            <th class="align-middle" colspan="3" bgcolor="#588434">Pelepah Sengkleh</th>
                            <th class="align-middle" rowspan="2" bgcolor="#588434">Total Skor</th>

                            <th class="align-middle" rowspan="2" bgcolor="blue">TPH Sampel</th>
                            <th class="align-middle" colspan="3" bgcolor="blue">Brd Tinggal</th>
                            <th class="align-middle" colspan="3" bgcolor="blue">Buah Tinggal</th>
                            <th class="align-middle" rowspan="2" bgcolor="blue">Total Skor</th>

                            <!-- Table Mutu Buah -->
                            <th class="align-middle" rowspan="2" bgcolor="#ffc404" style="color: #000000;">TPH Sampel</th>
                            <th class="align-middle" rowspan="2" bgcolor="#ffc404" style="color: #000000;">Total Janjang
                                Sampel</th>
                            <th class="align-middle" colspan="3" bgcolor="#ffc404" style="color: #000000;">Mentah (A)</th>
                            <th class="align-middle" colspan="3" bgcolor="#ffc404" style="color: #000000;">Matang (N)</th>
                            <th class="align-middle" colspan="3" bgcolor="#ffc404" style="color: #000000;">Lewat Matang (O)
                            </th>
                            <th class="align-middle" colspan="3" bgcolor="#ffc404" style="color: #000000;">Janjang Kosong
                                (E)</th>
                            <th class="align-middle" colspan="3" bgcolor="#ffc404" style="color: #000000;">Tidak Standar
                                V-Cut</th>
                            <th class="align-middle" colspan="2" bgcolor="#ffc404" style="color: #000000;">Abnormal</th>
                            <th class="align-middle" colspan="3" bgcolor="#ffc404" style="color: #000000;">Penggunaan Karung
                                Brondolan</th>
                            <th class="align-middle" rowspan="2" bgcolor="#ffc404" style="color: #000000;">Total Skor</th>
                        </tr>
                        <tr>
                            <!-- Table Mutu Ancak -->
                            <th class="align-middle" bgcolor="#588434">Jumlah Pokok Sampel</th>
                            <th class="align-middle" bgcolor="#588434">Luas Ha Sampel</th>
                            <th class="align-middle" bgcolor="#588434">Jumlah Jjg Panen</th>
                            <th class="align-middle" bgcolor="#588434">AKP Realisasi</th>
                            <th class="align-middle" bgcolor="#588434">P</th>
                            <th class="align-middle" bgcolor="#588434">K</th>
                            <th class="align-middle" bgcolor="#588434">GL</th>
                            <th class="align-middle" bgcolor="#588434">Total Brd</th>
                            <th class="align-middle" bgcolor="#588434">Brd/JJG</th>
                            <th class="align-middle" bgcolor="#588434">Skor</th>
                            <th class="align-middle" bgcolor="#588434">S</th>
                            <th class="align-middle" bgcolor="#588434">M1</th>
                            <th class="align-middle" bgcolor="#588434">M2</th>
                            <th class="align-middle" bgcolor="#588434">M3</th>
                            <th class="align-middle" bgcolor="#588434">Total JJG</th>
                            <th class="align-middle" bgcolor="#588434">%</th>
                            <th class="align-middle" bgcolor="#588434">Skor</th>
                            <th class="align-middle" bgcolor="#588434">Pokok</th>
                            <th class="align-middle" bgcolor="#588434">%</th>
                            <th class="align-middle" bgcolor="#588434">Skor</th>

                            <th class="align-middle" bgcolor="blue">Butir</th>
                            <th class="align-middle" bgcolor="blue">Butir/TPH</th>
                            <th class="align-middle" bgcolor="blue">Skor</th>
                            <th class="align-middle" bgcolor="blue">Jjg</th>
                            <th class="align-middle" bgcolor="blue">Jjg/TPH</th>
                            <th class="align-middle" bgcolor="blue">Skor</th>
                            <!-- Table Mutu Buah -->
                            <th class="align-middle" bgcolor="#ffc404" style="color: #000000;">Jjg</th>
                            <th class="align-middle" bgcolor="#ffc404" style="color: #000000;">%</th>
                            <th class="align-middle" bgcolor="#ffc404" style="color: #000000;">Skor</th>

                            <th class="align-middle" bgcolor="#ffc404" style="color: #000000;">Jjg</th>
                            <th class="align-middle" bgcolor="#ffc404" style="color: #000000;">%</th>
                            <th class="align-middle" bgcolor="#ffc404" style="color: #000000;">Skor</th>

                            <th class="align-middle" bgcolor="#ffc404" style="color: #000000;">Jjg</th>
                            <th class="align-middle" bgcolor="#ffc404" style="color: #000000;">%</th>
                            <th class="align-middle" bgcolor="#ffc404" style="color: #000000;">Skor</th>

                            <th class="align-middle" bgcolor="#ffc404" style="color: #000000;">Jjg</th>
                            <th class="align-middle" bgcolor="#ffc404" style="color: #000000;">%</th>
                            <th class="align-middle" bgcolor="#ffc404" style="color: #000000;">Skor</th>

                            <th class="align-middle" bgcolor="#ffc404" style="color: #000000;">Jjg</th>
                            <th class="align-middle" bgcolor="#ffc404" style="color: #000000;">%</th>
                            <th class="align-middle" bgcolor="#ffc404" style="color: #000000;">Skor</th>

                            <th class="align-middle" bgcolor="#ffc404" style="color: #000000;">Jjg</th>
                            <th class="align-middle" bgcolor="#ffc404" style="color: #000000;">%</th>

                            <th class="align-middle" bgcolor="#ffc404" style="color: #000000;">Ya</th>
                            <th class="align-middle" bgcolor="#ffc404" style="color: #000000;">%</th>
                            <th class="align-middle" bgcolor="#ffc404" style="color: #000000;">Skor</th>
                        </tr>
                    </thead>

                    <tbody id="datakemandoran">

                    </tbody>
                </table>
            </div>
        </div>

        @endif

        <div class="card p-4">
            <h4 class="text-center mt-2" style="font-weight: bold">Tracking Plot Inpeksi - {{$est}} {{$afd}} </h4>
            <hr>
            <div id="map" style="height:650px"></div>
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
                                <img id="img01" alt="..." class="img-fluid">
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


        <div id="editModal" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" style="text-align: center; font-weight: bold;">Update Mutu Ancak</h5>
                        <button type="button" class="close" id="closeModalBtn" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editForm" action="{{ route('updateBA') }}" method="POST">
                            {{ csrf_field() }}
                            <div class="row m-1">
                                <div class="col">

                                    <label for="estate">id</label>
                                    <input type="text" class="form-control" id="editId" name="id">


                                    <label for="estate">Estate</label>
                                    <input type="text" class="form-control" id="estate" name="estate">


                                    <label for="afdeling">Afdeling</label>
                                    <input type="text" class="form-control" id="afdeling" name="afdeling">

                                    <label for="update-blokCak" class="col-form-label">Blok</label>
                                    <input type="text" class="form-control" id="update-blokCak" name="blokCak" value="" required>


                                    <label for="update-StatusPnen" class="col-form-label">Status Panen</label>
                                    <input type="text" class="form-control" id="update-StatusPnen" name="StatusPnen" value="" required>

                                </div>
                                <div class="col">



                                    <label for="update-sph" class="col-form-label">SPH</label>
                                    <input type="text" class="form-control" id="update-sph" name="sph" value="" required>


                                    <label for="update-br1" class="col-form-label">BR 1</label>
                                    <input type="text" class="form-control" id="update-br1" name="br1" value="" required>


                                    <label for="update-br2" class="col-form-label">BR 2</label>
                                    <input type="text" class="form-control" id="update-br2" name="br2" value="" required>


                                    <label for="update-sampCak" class="col-form-label">Pokok Sample</label>
                                    <input type="text" class="form-control" id="update-sampCak" name="sampCak" value="" required>


                                    <label for="update-pkKuning" class="col-form-label">Pokok Kuning</label>
                                    <input type="text" class="form-control" id="update-pkKuning" name="pkKuning" value="" required>

                                </div>
                                <div class="col">

                                    <label for="update-prSmk" class="col-form-label">Piringan Semak</label>
                                    <input type="text" class="form-control" id="update-prSmk" name="prSmk" value="" required>


                                    <label for="update-undrPR" class="col-form-label">Underpruning</label>
                                    <input type="text" class="form-control" id="update-undrPR" name="undrPR" value="" required>


                                    <label for="update-overPR" class="col-form-label">Overpruning</label>
                                    <input type="text" class="form-control" id="update-overPR" name="overPR" value="" required>


                                    <label for="update-jjgCak" class="col-form-label">Janjang</label>
                                    <input type="text" class="form-control" id="update-jjgCak" name="jjgCak" value="" required>


                                    <label for="update-brtp" class="col-form-label">BRTP</label>
                                    <input type="text" class="form-control" id="update-brtp" name="brtp" value="" required>



                                </div>
                                <div class="col">
                                    <label for="update-brtk" class="col-form-label">BRTK</label>
                                    <input type="text" class="form-control" id="update-brtk" name="brtk" value="" required>


                                    <label for="update-brtgl" class="col-form-label">BRTGL</label>
                                    <input type="text" class="form-control" id="update-brtgl" name="brtgl" value="" required>


                                    <label for="update-bhts" class="col-form-label">BHTS</label>
                                    <input type="text" class="form-control" id="update-bhts" name="bhts" value="" required>


                                    <label for="update-bhtm1" class="col-form-label">BHTM1</label>
                                    <input type="text" class="form-control" id="update-bhtm1" name="bhtm1" value="" required>


                                    <label for="update-bhtm2" class="col-form-label">BHTM2</label>
                                    <input type="text" class="form-control" id="update-bhtm2" name="bhtm2" value="" required>



                                </div>
                                <div class="col">
                                    <label for="update-bhtm3" class="col-form-label">BHTM3</label>
                                    <input type="text" class="form-control" id="update-bhtm3" name="bhtm3" value="" required>

                                    <label for="update-cakmandor" class="col-form-label">Kemandoran</label>
                                    <input type="text" class="form-control" id="update-cakmandor" name="cakmandor" value="" required>


                                    <label for="update-ps" class="col-form-label">PS</label>
                                    <input type="text" class="form-control" id="update-ps" name="ps" value="" required>


                                    <label for="update-sp" class="col-form-label">frond Stacking</label>
                                    <input type="text" class="form-control" id="update-sp" name="sp" value="" required>


                                    <label for="update-pk_panenCAk" class="col-form-label">Pokok Panen</label>
                                    <input type="text" class="form-control" id="update-pk_panenCAk" name="pk_panenCAk" value="" required>

                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="saveChangesBtn">Save Changes</button>
                        <button type="button" class="btn btn-secondary" id="closeModalBtn_Ancak" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="editModalBuah" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" style="text-align: center; font-weight: bold;">Update Mutu Buah</h5>
                        <button type="button" class="close" id="closeModalBtn" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editForm_buah" action="{{ route('updateBA') }}" method="POST">
                            {{ csrf_field() }}
                            <div class="row m-1">
                                <div class="col">


                                    <input type="hidden" class="form-control" id="editId_buah" name="editId_buah">


                                    <input type="hidden" class="form-control" id="update-estBH" name="estBH" value="">



                                    <input type="hidden" class="form-control" id="update-afdBH" name="afdBH" value="">


                                    <label for="update-tphBH" class="col-form-label">TPH Baris</label>
                                    <input type="text" class="form-control" id="update-tphBH" name="tphBH" value="">


                                    <label for="update-blok_bh" class="col-form-label">Blok</label>
                                    <input type="text" class="form-control" id="update-blok_bh" name="blok_bh" value="">


                                    <label for="update-StatusBhpnen" class="col-form-label">Status Panen</label>
                                    <input type="text" class="form-control" id="update-StatusBhpnen" name="StatusBhpnen" value="">

                                    <label for="update-petugasBH" class="col-form-label">Petugas</label>
                                    <input type="text" class="form-control" id="update-petugasBH" name="petugasBH" value="">



                                    <label for="update-pemanen_bh" class="col-form-label">Ancak Pemanen</label>
                                    <input type="text" class="form-control" id="update-pemanen_bh" name="pemanen_bh" value="">

                                </div>
                                <div class="col">


                                    <label for="update-bmt" class="col-form-label">BMT</label>
                                    <input type="text" class="form-control" id="update-bmt" name="bmt" value="" required>


                                    <label for="update-bmk" class="col-form-label">BMK </label>
                                    <input type="text" class="form-control" id="update-bmk" name="bmk" value="" required>



                                    <label for="update-emptyBH" class="col-form-label">Empty</label>
                                    <input type="text" class="form-control" id="update-emptyBH" name="emptyBH" value="" required>

                                    <label for="update-jjgBH" class="col-form-label">Jumlah Janjang</label>
                                    <input type="text" class="form-control" id="update-jjgBH" name="jjgBH" value="" required>

                                    <label for="update-overBH" class="col-form-label">OverRipe</label>
                                    <input type="text" class="form-control" id="update-overBH" name="overBH" value="" required>

                                </div>
                                <div class="col">


                                    <label for="update-abrBH" class="col-form-label">Abnormal</label>
                                    <input type="text" class="form-control" id="update-abrBH" name="abrBH" value="" required>

                                    <label for="update-bhmandor" class="col-form-label">Kemandoran</label>
                                    <input type="text" class="form-control" id="update-bhmandor" name="bhmandor" value="" required>


                                    <label for="update-vcutBH" class="col-form-label">V Cut</label>
                                    <input type="text" class="form-control" id="update-vcutBH" name="vcutBH" value="" required>


                                    <label for="update-alsBR" class="col-form-label">Alas BR</label>
                                    <input type="text" class="form-control" id="update-alsBR" name="alsBR" value="" required>



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
        <div id="editModalTrans" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" style="text-align: center; font-weight: bold;">Update Mutu Trans</h5>
                        <button type="button" class="close" id="closeModalBtn" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editForm_Trans" action="{{ route('updateBA') }}" method="POST">
                            {{ csrf_field() }}
                            <div class="row m-1">
                                <div class="col">


                                    <input type="hidden" class="form-control" id="id_trans" name="id_trans">



                                    <input type="hidden" class="form-control" id="update-estTrans" name="estTrans" value="">



                                    <input type="hidden" class="form-control" id="update-afd_trans" name="afd_trans" value="">


                                    <label for="update-blok_trans" class="col-form-label">Blok</label>
                                    <input type="text" class="form-control" id="update-blok_trans" name="blok_trans" value="" required>

                                    <label for="update-Status_trPanen" class="col-form-label">Status Panen</label>
                                    <input type="text" class="form-control" id="update-Status_trPanen" name="Status_trPanen" value="" required>


                                    <label for="update-tphbrTrans" class="col-form-label">TPH Baris</label>
                                    <input type="text" class="form-control" id="update-tphbrTrans" name="tphbrTrans" value="">

                                </div>
                                <div class="col">




                                    <label for="update-petugasTrans" class="col-form-label">Petugas</label>
                                    <input type="text" class="form-control" id="update-petugasTrans" name="petugasTrans" value="">

                                    <label for="update-bt_trans" class="col-form-label">Brondolan di TPH </label>
                                    <input type="text" class="form-control" id="update-bt_trans" name="bt_trans" value="" required>
                                    <label for="update-transmandor" class="col-form-label">Kemandoran </label>
                                    <input type="text" class="form-control" id="update-transmandor" name="transmandor" value="" required>



                                    <label for="update-rstTrans" class="col-form-label">Buah di TPH</label>
                                    <input type="text" class="form-control" id="update-rstTrans" name="rstTrans" value="" required>



                                </div>


                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="saveChangesBtn_trans">Save Changes</button>
                        <button type="button" class="btn btn-secondary" id="closeModalBtn_Trans" data-dismiss="modal">Close</button>
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

        <div class="modal fade" id="deleteModalBuah" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
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
                        <button class="btn btn-danger" id="confirmDeleteBtn_buah">Yes</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="deleteModalTrans" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
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
                        <button class="btn btn-danger" id="confirmDeleteBtn_trans">Yes</button>
                    </div>
                </div>
            </div>
        </div>




    </div>


    <script type="text/javascript">
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

        //constvar $j = jQuery.noConflict();
        const canedit = @json(can_edit());

        // console.log(canedit);

        document.addEventListener("DOMContentLoaded", function() {
            const inputDate = document.getElementById("inputDate");

            // Add event listener for change event
            inputDate.addEventListener("change", function() {
                const selectedDate = inputDate.value;
                document.getElementById('show-button').disabled = false;
                // console.log("Selected date:", selectedDate); // You can replace console.log with any action you want

            });
        });
        // Call setInitialDate function and add event listener to update the date when the selected date changes

        function updateTanggal() {
            const selectedDate = document.getElementById("inputDate").value;
            document.getElementById("tglPDF_excel").value = selectedDate;
            document.getElementById("selectedDate").textContent = selectedDate;
        }



        let getest = @json($est);
        let getafd = @json($afd);

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





        // bagian untuk map



        function getmaps() {
            var map = L.map('map');
            map.remove();

            var Tanggal = document.getElementById('inputDate').value;
            var est = document.getElementById('est').value;
            var afd = document.getElementById('afd').value;
            var _token = $('input[name="_token"]').val();

            $.ajax({
                url: "{{ route('getMapsdetail') }}",
                method: "get",
                data: {
                    Tanggal,
                    est,
                    afd,
                    _token: _token
                },
                success: function(result) {
                    var polygonCoords = result.coords;

                    var blok_sidak = result.blok_sidak;
                    var plot_blok_all = result.plot_blok_all;
                    var plot_line = result.plot_line;
                    var trans_plot = result.trans_plot;
                    var buah_plot = result.buah_plot;
                    var ancak_plot = result.ancak_plot;
                    var mapContainer = L.DomUtil.get('map');
                    if (mapContainer != null) {
                        mapContainer._leaflet_id = null;
                    }
                    // Initialize the new map instance
                    var map = L.map('map').fitBounds(polygonCoords.concat(plot_blok_all), 13);


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



                    for (var blockName in plot_blok_all) {
                        // Get the coordinates array for the current block
                        var coordinates = plot_blok_all[blockName];

                        // Create a polygon for the current block
                        var polygonOptions = {
                            color: 'rgba(39, 138, 216, 0.5)',
                            fillColor: '#278ad8',
                            fillOpacity: 0.5
                        };
                        var textIcon;

                        if (blok_sidak.includes(blockName)) {
                            polygonOptions.color = 'green';
                            polygonOptions.fillColor = 'green';
                            polygonOptions.fillOpacity = 0.5;

                            textIcon = L.divIcon({
                                className: 'blok_visit',
                                html: blockName,
                                iconSize: [100, 20],
                                iconAnchor: [50, 10]
                            });
                        } else {
                            textIcon = L.divIcon({
                                className: 'blok_all',
                                html: blockName,
                                iconSize: [100, 20],
                                iconAnchor: [50, 10]
                            });
                        }

                        var plotBlokPolygon = L.polygon(coordinates, polygonOptions)
                            .addTo(map)
                            .bindPopup('<strong>Afdeling:</strong>' + blockName);

                        var bounds = plotBlokPolygon.getBounds();
                        var center = bounds.getCenter();

                        // Create a custom HTML icon with text and modified class name


                        // Place the text icon in the center of the polygon
                        L.marker(center, {
                            icon: textIcon
                        }).addTo(map);
                    }

                    var latlngs = [];

                    for (var i = 0; i < plot_line.length; i++) {
                        var coordinates = plot_line[i].split("],[");
                        var latlngGroup = [];

                        for (var j = 0; j < coordinates.length; j++) {
                            var latlng = coordinates[j].replace("[", "").replace("]", "").split(",");
                            latlngGroup.push([parseFloat(latlng[0]), parseFloat(latlng[1])]);
                        }

                        latlngs.push(latlngGroup);
                    }



                    var polyline = L.polyline(latlngs, {
                        color: 'yellow'
                    }).addTo(map);

                    var decorator = L.polylineDecorator(polyline, {
                        patterns: [{
                            offset: 0,
                            repeat: 50,
                            symbol: L.Symbol.arrowHead({
                                pixelSize: 8,
                                pathOptions: {
                                    fillOpacity: 1
                                }
                            })
                        }]
                    }).addTo(map);
                    // Place the text icon in the center of the polygon
                    L.marker(center, {
                        icon: textIcon
                    }).addTo(map);
                    // Iterate over the keys of plot_blok





                    var yellowIcon = L.icon({
                        iconSize: [38, 95], // size of the icon
                        shadowSize: [50, 64], // size of the shadow
                        iconAnchor: [22, 94], // point of the icon which will correspond to marker's location
                        shadowAnchor: [4, 62], // the same for the shadow
                        popupAnchor: [-3, -76] // point from which the popup should open relative to the iconAnchor
                    });

                    // Red marker icon
                    var redIcon = L.icon({
                        iconSize: [38, 95], // size of the icon
                        shadowSize: [50, 64], // size of the shadow
                        iconAnchor: [22, 94], // point of the icon which will correspond to marker's location
                        shadowAnchor: [4, 62], // the same for the shadow
                        popupAnchor: [-3, -76] // point from which the popup should open relative to the iconAnchor
                    });

                    // Create Layer Groups for each layer type
                    var transGroup = L.layerGroup();
                    var buahGroup = L.layerGroup();
                    var ancakGroup = L.layerGroup();


                    var transIconUrl = '{{ asset("img/placeholder.png") }}';
                    var transicon = L.icon({
                        iconUrl: "{{asset('img/marker/marker-icon-2x-gold.png')}}",
                        shadowUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png",
                        iconSize: [14, 21],
                        iconAnchor: [7, 22],
                        popupAnchor: [1, -34],
                        shadowSize: [28, 20],

                    });

                    var transTmuanUrl = '{{ asset("img/placeholder2.png") }}';
                    var transtemuan = L.icon({
                        iconUrl: "{{asset('img/marker/marker-icon-2x-yellow.png')}}",
                        shadowUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png",
                        iconSize: [14, 21],
                        iconAnchor: [7, 22],
                        popupAnchor: [1, -34],
                        shadowSize: [28, 20],
                    });
                    var transFollowUrl = '{{ asset("img/placeholder3.png") }}';
                    var transFollowup = L.icon({
                        iconUrl: "{{asset('img/marker/marker-icon-2x-orange.png')}}",
                        shadowUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png",
                        iconSize: [14, 21],
                        iconAnchor: [7, 22],
                        popupAnchor: [1, -34],
                        shadowSize: [28, 20],


                    });
                    // console.log(trans_plot);


                    function trans() {
                        for (var key in trans_plot) {
                            if (trans_plot.hasOwnProperty(key)) {
                                var plots = trans_plot[key];
                                // var latLngs = []; // Array to store latitudes and longitudes for drawing lines

                                for (var i = 0; i < plots.length; i++) {
                                    var plot = plots[i];
                                    var lat = parseFloat(plot.lat);
                                    var lon = parseFloat(plot.lon);
                                    var blok = plot.blok;
                                    var foto_temuan = plot.foto_temuan;
                                    var foto_fu = plot.foto_fu;
                                    var komentar = plot.komentar;
                                    var status_panen = plot.status_panen;
                                    var luas_blok = plot.luas_blok;
                                    var bt = plot.bt;
                                    var rst = plot.Rst;
                                    var time = plot.time;
                                    var maps = plot.maps;

                                    var markerIcon = foto_fu ? transFollowup : (foto_temuan ? transtemuan : transicon);

                                    var popupContent = `<strong>Mutu Transport Blok: </strong>${blok}<br/>`;
                                    if (status_panen) {
                                        popupContent += `<strong>Status Panen: </strong>${status_panen}<br/>`;
                                    }

                                    popupContent += `<strong>Luas Blok: </strong>${luas_blok}<br/>`;


                                    popupContent += `<strong>Brondolan tinggal: </strong>${bt}<br/>`;


                                    popupContent += `<strong>Buah Tinggal: </strong>${rst}<br/>`;


                                    popupContent += `<strong>Sidak: </strong>${time}<br/>`;
                                    popupContent += `<strong>Akurasi Maps : </strong>${maps}<br/>`;


                                    if (foto_temuan) {
                                        popupContent += `<img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_mt/${foto_temuan}" alt="Foto Temuan" style="max-width:200px; height:auto;" onclick="openModal(this.src, '${komentar}')"><br/>`;
                                    }

                                    if (foto_fu) {
                                        popupContent += `<img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_mt/${foto_fu}" alt="Foto FU" style="max-width:200px; height:auto;" onclick="openModal(this.src, '${komentar}')"><br/>`;
                                    }

                                    if (!isNaN(lat) && !isNaN(lon)) { // Check if lat and lon are valid numbers
                                        var marker = L.marker([lat, lon], {
                                            icon: markerIcon
                                        });

                                        marker.bindPopup(popupContent);

                                        transGroup.addLayer(marker);

                                        // latLngs.push([lat, lon]); // Add latitudes and longitudes to the latLngs array
                                    }
                                }


                                // Create a polyline from latLngs array to connect the plots within each block
                                // var polyline = L.polyline(latLngs, {
                                //     color: 'blue'
                                // }).addTo(map);
                            }
                        }
                    }



                    var myIconUrl = '{{ asset("img/pin.png") }}';
                    var myIcon = L.icon({
                        iconUrl: "{{asset('img/marker/marker-icon-2x-grey.png')}}",
                        shadowUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png",
                        iconSize: [14, 21],
                        iconAnchor: [7, 22],
                        popupAnchor: [1, -34],
                        shadowSize: [28, 20],

                    });
                    var myIconUrl2 = '{{ asset("img/pin2.png") }}';
                    var myIcon2 = L.icon({
                        iconUrl: "{{asset('img/marker/marker-icon-2x-black.png')}}",
                        shadowUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png",
                        iconSize: [14, 21],
                        iconAnchor: [7, 22],
                        popupAnchor: [1, -34],
                        shadowSize: [28, 20],

                    });



                    function buah() {
                        for (var key in buah_plot) {
                            if (buah_plot.hasOwnProperty(key)) {
                                var plots = buah_plot[key];
                                for (var i = 0; i < plots.length; i++) {
                                    var plot = plots[i];
                                    var lat = parseFloat(plot.lat);
                                    var lon = parseFloat(plot.lon);
                                    var blok = plot.blok;
                                    var foto_temuan = plot.foto_temuan;
                                    var komentar = plot.komentar;

                                    var tph_baris = plot.tph_baris;
                                    var status_panen = plot.status_panen;
                                    var jumlah_jjg = plot.jumlah_jjg;
                                    var bmt = plot.bmt;
                                    var bmk = plot.bmk;
                                    var overripe = plot.overripe;
                                    var empty_bunch = plot.empty_bunch;
                                    var abnormal = plot.abnormal;
                                    var vcut = plot.vcut;
                                    var alas_br = plot.alas_br;
                                    var time = plot.time;
                                    var maps = plot.maps;
                                    var markerIcon = foto_temuan ? myIcon : myIcon2; // Choose the icon based on the condition

                                    var popupContent = `<strong>Mutu Buah Blok: </strong>${blok}<br/>`;

                                    popupContent += `<strong>Tph Baris: </strong>${tph_baris}<br/>`;
                                    popupContent += `<strong>Status Panen: </strong>${status_panen}<br/>`;
                                    popupContent += `<strong>Buah Mentah Kurang Brondol: </strong>${bmt}<br/>`;
                                    popupContent += `<strong>Buah Masak Kurang Brondol: </strong>${bmk}<br/>`;
                                    popupContent += `<strong>overripe: </strong>${overripe}<br/>`;
                                    popupContent += `<strong>Janjang Kosong: </strong>${empty_bunch}<br/>`;
                                    popupContent += `<strong>abnormal: </strong>${abnormal}<br/>`;
                                    popupContent += `<strong>Tidak Standar vcut: </strong>${vcut}<br/>`;
                                    popupContent += `<strong>Alas Karung: </strong>${alas_br}<br/>`;
                                    popupContent += `<strong>Sidak: </strong>${time}<br/>`;
                                    popupContent += `<strong>Akurasi Maps: </strong>${maps}<br/>`;


                                    if (foto_temuan) {
                                        popupContent += `<img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_mb/${foto_temuan}" alt="Foto Temuan" style="max-width:200px; height:auto;" onclick="openModal(this.src, '${komentar}')"><br/>`;
                                    }

                                    popupContent += `<strong>Komentar: </strong>${komentar}`;

                                    if (!isNaN(lat) && !isNaN(lon)) { // Check if lat and lon are valid numbers
                                        var marker = L.marker([lat, lon], {
                                            icon: markerIcon
                                        });

                                        marker.bindPopup(popupContent);

                                        buahGroup.addLayer(marker);
                                    }
                                }
                            }
                        }
                    }








                    var caktemuan1 = L.icon({
                        iconUrl: "{{ asset('img/marker/marker-icon-2x-blue.png') }}",
                        shadowUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png",
                        iconSize: [14, 21],
                        iconAnchor: [7, 22],
                        popupAnchor: [1, -34],
                        shadowSize: [28, 20],
                    });


                    var caktemuan2 = L.icon({
                        iconUrl: "{{ asset('img/marker/marker-icon-2x-red.png')}}",
                        shadowUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png",
                        iconSize: [14, 21],
                        iconAnchor: [7, 22],
                        popupAnchor: [1, -34],
                        shadowSize: [28, 20],
                    });

                    var cakfu1 = L.icon({
                        iconUrl: "{{asset('img/marker/marker-icon-2x-green.png')}}",
                        shadowUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png",
                        iconSize: [14, 21],
                        iconAnchor: [7, 22],
                        popupAnchor: [1, -34],
                        shadowSize: [28, 20],
                    });
                    var ancak_fu2 = '{{ asset("img/push-pin1.png") }}';
                    var cakfu2 = L.icon({
                        iconUrl: "{{ asset('img/marker/marker-icon-2x-green.png')}}",
                        shadowUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png",
                        iconSize: [14, 21],
                        iconAnchor: [7, 22],
                        popupAnchor: [1, -34],
                        shadowSize: [28, 20],
                    });



                    function ancak() {
                        for (var i = 0; i < ancak_plot.length; i++) {
                            var lat = parseFloat(ancak_plot[i].lat_);
                            var lon = parseFloat(ancak_plot[i].lon_);
                            var blok = ancak_plot[i].blok;
                            var ket = ancak_plot[i].ket;
                            var foto_temuan = ancak_plot[i].foto_temuan;

                            var luas_blok = ancak_plot[i].luas_blok;
                            var sph = ancak_plot[i].sph;
                            var sample = ancak_plot[i].sample;
                            var pokok_kuning = ancak_plot[i].pokok_kuning;
                            var piringan_semak = ancak_plot[i].piringan_semak;
                            var underpruning = ancak_plot[i].underpruning;
                            var overpruning = ancak_plot[i].overpruning;
                            var jjg = ancak_plot[i].jjg;
                            var brtp = ancak_plot[i].brtp;
                            var brtk = ancak_plot[i].brtk;
                            var brtgl = ancak_plot[i].brtgl;
                            var bhts = ancak_plot[i].bhts;
                            var bhtm1 = ancak_plot[i].bhtm1;
                            var bhtm2 = ancak_plot[i].bhtm2;
                            var bhtm3 = ancak_plot[i].bhtm3;
                            var ps = ancak_plot[i].ps;
                            var sp = ancak_plot[i].sp;
                            var time = ancak_plot[i].time;
                            var maps = ancak_plot[i].maps;

                            var markerIcon2 = caktemuan1;

                            if (foto_temuan && foto_temuan.length > 0) {
                                for (var j = 0; j < foto_temuan.length; j++) {
                                    var temuan = foto_temuan[j].split(',');

                                    var foto_temuan1, foto_temuan2, foto_fu1, foto_fu2, lat_temuan, lon_temuan, komentar;

                                    for (var k = 0; k < temuan.length; k++) {
                                        var keyValuePair = temuan[k].split(':');
                                        var key = keyValuePair[0].trim();
                                        var value = keyValuePair.slice(1).join(':').trim();

                                        if (key === 'foto_temuan1') {
                                            foto_temuan1 = value;
                                        } else if (key === 'foto_temuan2') {
                                            foto_temuan2 = value;
                                        } else if (key === 'foto_fu1') {
                                            foto_fu1 = value;
                                        } else if (key === 'foto_fu2') {
                                            foto_fu2 = value;
                                        } else if (key === 'lat') {
                                            lat_temuan = parseFloat(value);
                                        } else if (key === 'lon') {
                                            lon_temuan = parseFloat(value);
                                        } else if (key === 'komentar') {
                                            komentar = value;
                                        }
                                    }

                                    // if (foto_temuan1 || foto_temuan2) {
                                    //     markerIcon = caktemuan2;
                                    // } else if (foto_fu1 || foto_fu2) {
                                    //     markerIcon = cakfu1;
                                    // }
                                    var markerIcon3 = caktemuan2;
                                    var markerIcon4 = cakfu1;


                                    var popupContent = `<strong>Mutu Ancak Blok: </strong>${blok}<br/>`;

                                    if (foto_temuan1) {
                                        popupContent += `<strong>Foto_temuan: </strong><br/>`;
                                        popupContent += `<img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_ma/${foto_temuan1}" alt="Foto Temuan 1" style="max-width:200px; height:auto;" onclick="openModal(this.src, '${komentar}')"><br/>`;
                                        // var marker = L.marker([lat_temuan, lon_temuan]);
                                        var marker = L.marker([lat_temuan, lon_temuan], {
                                            icon: markerIcon3
                                        });

                                        marker.bindPopup(popupContent);
                                        ancakGroup.addLayer(marker);
                                    }

                                    if (foto_temuan2) {
                                        popupContent += `<strong>foto_temuan 2: </strong><br/>`;
                                        popupContent += `<img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_ma/${foto_temuan2}" alt="Foto Temuan 2" style="max-width:200px; height:auto;" onclick="openModal(this.src, '${komentar}')"><br/>`;
                                        var marker = L.marker([lat_temuan, lon_temuan], {
                                            icon: markerIcon3
                                        });
                                        marker.bindPopup(popupContent);
                                        ancakGroup.addLayer(marker);
                                    }

                                    if (foto_fu1) {
                                        popupContent += `<strong>Foto Follow Up: </strong><br/>`;
                                        popupContent += `<img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_ma/${foto_fu1}" alt="Foto FU 1" style="max-width:200px; height:auto;" onclick="openModal(this.src, '${komentar}')"><br/>`;
                                        var marker = L.marker([lat_temuan, lon_temuan], {
                                            icon: markerIcon4
                                        });
                                        marker.bindPopup(popupContent);
                                        ancakGroup.addLayer(marker);
                                    }

                                    if (foto_fu2) {
                                        popupContent += `<strong>Foto Follow Up 2: </strong><br/>`;
                                        popupContent += `<img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_ma/${foto_fu2}" alt="Foto FU 2" style="max-width:200px; height:auto;" onclick="openModal(this.src, '${komentar}')"><br/>`;
                                        var marker = L.marker([lat_temuan, lon_temuan], {
                                            icon: markerIcon4
                                        });
                                        marker.bindPopup(popupContent);
                                        ancakGroup.addLayer(marker);
                                    }
                                }
                            }

                            var popupContent2 = `<strong>Mutu Ancak Blok: </strong>${blok}<br/>`;
                            popupContent2 += `<strong>luas_blok: </strong>${luas_blok}<br/>`;
                            popupContent2 += `<strong>sph: </strong>${sph}<br/>`;
                            popupContent2 += `<strong>sample: </strong>${sample}<br/>`;
                            popupContent2 += `<strong>Pokok Kuning: </strong>${pokok_kuning}<br/>`;
                            popupContent2 += `<strong>Piringan Semak: </strong>${piringan_semak}<br/>`;
                            popupContent2 += `<strong>Underpruning: </strong>${underpruning}<br/>`;
                            popupContent2 += `<strong>Overpruning: </strong>${overpruning}<br/>`;
                            popupContent2 += `<strong>Janjang: </strong>${jjg}<br/>`;
                            popupContent2 += `<strong>Brondolan (P): </strong>${brtp}<br/>`;
                            popupContent2 += `<strong>Brondolan (K): </strong>${brtk}<br/>`;
                            popupContent2 += `<strong>Brondolan (TGL): </strong>${brtgl}<br/>`;
                            popupContent2 += `<strong>Buah Tinggal (S): </strong>${bhts}<br/>`;
                            popupContent2 += `<strong>Buah Tinggal (M1): </strong>${bhtm1}<br/>`;
                            popupContent2 += `<strong>Buah Tinggal (M2): </strong>${bhtm2}<br/>`;
                            popupContent2 += `<strong>Buah Tinggal (M3): </strong>${bhtm3}<br/>`;
                            popupContent2 += `<strong>Palepah Sengklek: </strong>${ps}<br/>`;
                            popupContent2 += `<strong>Frond Stacking: </strong>${sp}<br/>`;
                            popupContent2 += `<strong>Akurasi Maps: </strong>${maps}<br/>`;
                            popupContent2 += `<strong>Sidak: </strong>${time}<br/>`;


                            var marker2 = L.marker([lat, lon], {
                                icon: markerIcon2
                            });

                            marker2.bindPopup(popupContent2);
                            ancakGroup.addLayer(marker2);
                        }
                    }



                    // Call the functions to create the markers and add them to their Layer Groups
                    trans();
                    buah();
                    ancak();

                    // Add the Layer Groups to the map
                    transGroup.addTo(map);
                    buahGroup.addTo(map);
                    ancakGroup.addTo(map);
                    var legend = L.control({
                        position: 'bottomright'
                    });


                    var estateIcon = '{{ asset("img/brazil.png") }}';
                    var Estatecon = L.icon({
                        iconUrl: estateIcon,
                        iconSize: [30, 80],
                        iconAnchor: [30, 80],
                        popupAnchor: [-3, -76],
                        shadowUrl: estateIcon,
                        shadowSize: [30, 80],
                        shadowAnchor: [30, 80],
                    });
                    var afdIcon = '{{ asset("img/territory.png") }}';
                    var afdCon = L.icon({
                        iconUrl: afdIcon,
                        iconSize: [30, 80],
                        iconAnchor: [30, 80],
                        popupAnchor: [-3, -76],
                        shadowUrl: afdIcon,
                        shadowSize: [30, 80],
                        shadowAnchor: [30, 80],
                    });
                    var markerIconUrl1 = "{{ asset('img/marker/marker-icon-2x-blue.png') }}";
                    var markerIconUrl2 = "{{ asset('img/marker/marker-icon-2x-red.png') }}";
                    var markerIconUrl3 = "{{ asset('img/marker/marker-icon-2x-green.png') }}";
                    var markerIconUrl4 = "{{ asset('img/marker/marker-icon-2x-gold.png') }}";
                    var markerIconUrl5 = "{{ asset('img/marker/marker-icon-2x-yellow.png') }}";
                    var markerIconUrl6 = "{{ asset('img/marker/marker-icon-2x-orange.png') }}";
                    var markerIconUrl7 = "{{ asset('img/marker/marker-icon-2x-black.png') }}";
                    var markerIconUrl8 = "{{ asset('img/marker/marker-icon-2x-grey.png') }}";

                    legend.onAdd = function(map) {

                        var div = L.DomUtil.create("div", "legend");
                        div.innerHTML += "<h4>Keterangan :</h4>";
                        div.innerHTML += '<div><img src="' + markerIconUrl1 + '" style="width:12pt;height:13pt"> Mutu Ancak</div>';
                        div.innerHTML += '</div>';
                        div.innerHTML += '<div>  <img src="' + markerIconUrl2 + '" style="width:12pt;height:13pt" >  MA Temuan';
                        div.innerHTML += '</div>';
                        div.innerHTML += '<div>  <img src="' + markerIconUrl3 + '" style="width:12pt;height:13pt" >  MA Follow Up';
                        div.innerHTML += '</div>';
                        div.innerHTML += '<div>  <img src="' + markerIconUrl4 + '" style="width:12pt;height:13pt" >  Mutu Transport';
                        div.innerHTML += '</div>';
                        div.innerHTML += '<div> <img src="' + markerIconUrl5 + '" style="width:12pt;height:13pt" >  MT Temuan';
                        div.innerHTML += '</div>';
                        div.innerHTML += '<div>  <img src="' + markerIconUrl6 + '" style="width:12pt;height:13pt" >  MT Follow Up';
                        div.innerHTML += '</div>';
                        div.innerHTML += '<div>  <img src="' + markerIconUrl7 + '" style="width:12pt;height:13pt" >  Mutu Buah';
                        div.innerHTML += '</div>';
                        div.innerHTML += '<div>  <img src="' + markerIconUrl8 + '" style="width:12pt;height:13pt" >  MB Temuan';
                        div.innerHTML += '</div>';
                        div.innerHTML += '<div><i style="background: #88b87a;width:15px;height:15px;margin-top:5px;border:1px solid green"></i> Blok yang dikunjungi';
                        div.innerHTML += '</div>';
                        div.innerHTML += '<div><i style="margin-top:7px;  width: 0; height: 0; border-left: 6px solid transparent;border-right: 6px solid transparent;border-bottom: 10px solid #4e86fc;"></i> Arah jalan sidak';
                        div.innerHTML += '</div>';

                        return div;
                    };

                    legend.addTo(map);





                },


                error: function() {

                }
            });
        }

        // function enableExcelDownloadButton() {
        //     const downloadExcelButton = document.getElementById('download-excel-button');
        //     downloadExcelButton.disabled = false;
        // }


        // end bagian untuk map 
        var currentUserName = "{{ $jabatan }}";
        var user_name = "{{ $user_name }}";
        //untuk mengirim parameter tanggal ke download pdf BA
        document.addEventListener('DOMContentLoaded', function() {
            const showButton = document.getElementById('show-button');
            const inputDate = document.getElementById('inputDate');
            const selectedDate = document.getElementById('selectedDate');
            const tglPDF = document.getElementById('tglPDF');
            const downloadButton = document.getElementById('download-button');
            const lottieDownload = document.getElementById('lottie-download');

            // Initialize Lottie animation
            const downloadAnimation = lottie.loadAnimation({
                container: lottieDownload,
                renderer: 'svg',
                loop: true,
                autoplay: true,
                path: 'https://assets2.lottiefiles.com/packages/lf20_eUext1.json'
            });

            showButton.addEventListener('click', function() {
                selectedDate.textContent = inputDate.value;
                tglPDF.value = inputDate.value;
                downloadButton.disabled = false;
                // enableExcelDownloadButton();

                if (currentUserName === 'Askep' || currentUserName === 'Manager') {
                    document.getElementById('moveDataButton').disabled = false;
                }
            });
        });
        ///



        //untuk menbuat timbol tutup di modal
        function closeModal() {
            const updateModal = document.getElementById("update-modal");
            updateModal.style.display = "none";
            const updateModal2 = document.getElementById("update-modal-buah");
            updateModal2.style.display = "none";
            const updateModal3 = document.getElementById("update-modal-trans");
            updateModal3.style.display = "none";
        }

        //buat animasi loading ketika tombol show di klik
        const lottieContainer = document.getElementById('lottie-container');
        const lottieAnimation = lottie.loadAnimation({
            container: lottieContainer,
            renderer: "svg",
            loop: true,
            autoplay: false,
            path: "https://assets3.lottiefiles.com/private_files/lf30_fup2uejx.json",
        });
        const lottieContainer1 = document.getElementById('lottie-container1');
        const lottieAnimation1 = lottie.loadAnimation({
            container: lottieContainer1,
            renderer: "svg",
            loop: true,
            autoplay: false,
            path: "https://assets3.lottiefiles.com/packages/lf20_vfcbh2yp.json",
        });



        function fetchAndUpdateData() {
            lottieAnimation.play(); // Start the Lottie animation
            lottieContainer.style.display = 'block'; // Display the Lottie container

            if ($.fn.DataTable.isDataTable('#mutuAncakTable')) {
                $('#mutuAncakTable').DataTable().destroy();
            }
            if ($.fn.DataTable.isDataTable('#mutuBuahable')) {
                $('#mutuBuahable').DataTable().destroy();
            }
            if ($.fn.DataTable.isDataTable('#mutuTransportable')) {
                $('#mutuTransportable').DataTable().destroy();
            }
            var Tanggal = document.getElementById('inputDate').value;
            var est = document.getElementById('est').value;
            var afd = document.getElementById('afd').value;
            var _token = $('input[name="_token"]').val();

            $.ajax({
                url: "{{ route('filterDataDetail') }}",
                method: "GET",
                data: {
                    Tanggal,
                    est,
                    afd,
                    _token: _token
                },
                success: function(result) {
                    lottieAnimation.stop(); // Stop the Lottie animation
                    lottieContainer.style.display = 'none'; // Hide the Lottie container
                    // Get the modal
                    const modal = document.getElementById("imageModal");

                    // Get the image element inside the modal
                    const modalImage = document.getElementById("modalImage");

                    // Get the close button
                    const closeBtn = document.getElementsByClassName("close")[0];

                    // Function to show the modal with the clicked image
                    function showModal(src) {
                        modalImage.src = src;
                        modal.style.display = "block";
                    }

                    // When the user clicks on the close button, close the modal
                    closeBtn.onclick = function() {
                        modal.style.display = "none";
                    }

                    // When the user clicks anywhere outside of the modal, close it
                    window.onclick = function(event) {
                        if (event.target == modal) {
                            modal.style.display = "none";
                        }
                    }


                    var parseResult = JSON.parse(result)

                    var mutuBuah = Object.entries(parseResult['mutuBuah'])
                    var mutuTransport = Object.entries(parseResult['mutuTransport'])
                    var mutuAncak = Object.entries(parseResult['mutuAncak'])



                    // console.log(mutuAncak);
                    var mutuAncakData = [];
                    for (var i = 0; i < mutuAncak.length; i++) {
                        var rowData = Object.values(mutuAncak[i][1]);
                        mutuAncakData.push(rowData);
                    }


                    // console.log(mutuAncak);
                    // console.log(mutuAncakData);

                    document.getElementById('closeModalBtn').addEventListener('click', function() {
                        // $('#editModal').modal('hide');
                        var modal = new bootstrap.Modal(document.getElementById('editModal'));
                        modal.hide();
                    });


                    function editRow(id) {
                        // Save the selected row index
                        selectedRowIndex = id;

                        // Retrieve the id from the first column of the selected row
                        var rowData = dataTableAncakTest.row(id).data();
                        // console.log("Row Data:", rowData); // Debug output
                        var rowId = rowData[0];

                        // Populate the form with the data of the selected row
                        $('#editId').val(rowData.id).prop('disabled', true);
                        $('#estate').val(rowData.estate).prop('disabled', true);
                        $('#afdeling').val(rowData.afdeling).prop('disabled', true);
                        $('#update-blokCak').val(rowData.blok);
                        $('#update-StatusPnen').val(rowData.status_panen);
                        $('#update-sph').val(rowData.sph);
                        $('#update-br1').val(rowData.br1);
                        $('#update-br2').val(rowData.br2);
                        $('#update-sampCak').val(rowData.sample);
                        $('#update-pkKuning').val(rowData.pokok_kuning);

                        $('#update-prSmk').val(rowData.piringan_semak);
                        $('#update-undrPR').val(rowData.underpruning);
                        $('#update-overPR').val(rowData.overpruning);
                        $('#update-jjgCak').val(rowData.jjg);

                        $('#update-brtp').val(rowData.brtp);
                        $('#update-brtk').val(rowData.brtk);
                        $('#update-brtgl').val(rowData.brtgl);
                        $('#update-bhts').val(rowData.bhts);

                        $('#update-bhtm1').val(rowData.bhtm1);
                        $('#update-bhtm2').val(rowData.bhtm2);
                        $('#update-bhtm3').val(rowData.bhtm3);
                        $('#update-ps').val(rowData.ps);
                        $('#update-sp').val(rowData.sp);
                        $('#update-pk_panenCAk').val(rowData.pokok_panen);
                        $('#update-cakmandor').val(rowData.kemandoran);

                        // Add similar lines for other fields

                        // Show the modal
                        var modal = new bootstrap.Modal(document.getElementById('editModal'));
                        modal.show();
                        // $('#editModal').modal('show');
                    }

                    $(document).ready(function() {
                        // Close modal when the close button is clicked
                        $('#closeModalBtn_Ancak').click(function() {
                            var modal = new bootstrap.Modal(document.getElementById('editModal'));
                            modal.hide();
                            // $('#editModal').modal('hide');
                        });

                        // Submit the form when the Save Changes button is clicked
                        $('#saveChangesBtn').off('click').on('click', function() {
                            $('#editForm').submit();
                        });

                        $('#editForm').submit(function(e) {
                            e.preventDefault(); // Prevent the default form submission

                            // Get the form data
                            var formData = new FormData(this);
                            formData.append('id', $('#editId').val()); // Append the id field to the form data
                            formData.append('type', 'mutuancak'); // Add the type parameter with the desired value


                            var sampCak = $('#update-sampCak').val();
                            var pkKuning = $('#update-pkKuning').val();
                            var prSmk = $('#update-prSmk').val();
                            var undrPR = $('#update-undrPR').val();
                            var overPR = $('#update-overPR').val();
                            var jjgCak = $('#update-jjgCak').val();
                            var brtp = $('#update-brtp').val();
                            var brtk = $('#update-brtk').val();
                            var brtgl = $('#update-brtgl').val();
                            var bhts = $('#update-bhts').val();
                            var bhtm1 = $('#update-bhtm1').val();
                            var bhtm2 = $('#update-bhtm2').val();
                            var bhtm3 = $('#update-bhtm3').val();
                            var ps = $('#update-ps').val();
                            var sp = $('#update-sp').val();
                            var cakmandor = $('#update-cakmandor').val();
                            var pk_panenCAk = $('#update-pk_panenCAk').val();

                            if (!isNumber(sampCak) ||
                                !isNumber(pkKuning) ||
                                !isNumber(prSmk) ||
                                !isNumber(undrPR) ||
                                !isNumber(overPR) ||
                                !isNumber(jjgCak) ||
                                !isNumber(brtp) ||
                                !isNumber(brtk) ||
                                !isNumber(brtgl) ||
                                !isNumber(bhts) ||
                                !isNumber(bhtm1) ||
                                !isNumber(bhtm2) ||
                                !isNumber(bhtm3) ||
                                !isNumber(ps) ||
                                !isNumber(sp) ||
                                !isNumber(pk_panenCAk)
                            ) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Masukan Error',
                                    text: 'Hanya bisa di masukan angka Saja!'
                                });
                                return;
                            }

                            // Send the AJAX request
                            $.ajax({
                                type: 'POST',
                                url: '{{ route("updateBA") }}',
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function(response) {
                                    // console.log(response);
                                    // Close the modal
                                    // $('#editModal').modal('hide');
                                    var modal = new bootstrap.Modal(document.getElementById('editModal'));
                                    modal.hide();
                                    // Show a success message or perform any other actions
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
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Gagal memperbarui data!'
                                    });
                                }
                            });
                        });
                    });

                    var selectedRowIndex; // Variable to store the selected row index

                    function deleteRow(id) {
                        // Save the selected row index
                        selectedRowIndex = id;

                        // Retrieve the ID from the first column of the selected row
                        var rowData = dataTableAncakTest.row(id).data();
                        var rowId = rowData.id; // Directly access the ID property

                        // Show the delete modal
                        var modal = new bootstrap.Modal(document.getElementById('deleteModalancak'));
                        modal.show();
                        // $('#deleteModalancak').modal('show');

                        $(document).ready(function() {
                            // Handle delete confirmation
                            $('#confirmDeleteBtn').click(function() {
                                // Get the selected row index and ID
                                var rowIndex = selectedRowIndex;
                                var id = rowId;

                                // Create a form data object
                                var formData = new FormData();
                                formData.append('delete_id', id);
                                formData.append('type', 'mutuancak'); // Add the type parameter with the desired value

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
                                    url: '{{ route("deleteBA") }}',
                                    method: 'POST',
                                    data: formData,
                                    processData: false,
                                    contentType: false,
                                    success: function(response) {

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

                                        // var modal = new bootstrap.Modal(document.getElementById('deleteModalancak'));
                                        // modal.hide();
                                        // fetchAndUpdateData()
                                    }
                                });
                            });
                        });
                    }

                    // Example function to save changes
                    document.getElementById('saveChangesBtn').addEventListener('click', function() {
                        var modal = new bootstrap.Modal(document.getElementById('editModal'));
                        modal.hide();
                        // $('#editModal').modal('hide');
                    });


                    var dataTableAncakTest = $('#mutuAncakTable').DataTable({
                        columns: [{

                                data: 'id'
                            },
                            {

                                data: 'estate'
                            },
                            {

                                data: 'afdeling'
                            },
                            {

                                data: 'blok'
                            },
                            {

                                data: 'petugas'
                            },
                            {

                                data: 'datetime'
                            },
                            {

                                data: 'luas_blok',

                            },
                            {

                                data: 'sph',

                            },
                            {

                                data: 'br1',

                            },
                            {

                                data: 'br2',

                            },
                            {

                                data: 'jalur_masuk',
                            },
                            {

                                data: 'status_panen',
                            },
                            {

                                data: 'kemandoran',
                            },
                            {

                                data: 'ancak_pemanen',
                            },
                            {

                                data: 'pokok_panen',

                            },
                            {

                                data: 'sample',

                            },
                            {

                                data: 'jjg',

                            },
                            {

                                data: 'brtp',

                            },
                            {

                                data: 'brtk',

                            },
                            {

                                data: 'brtgl',

                            },
                            {

                                data: 'bhts',

                            },
                            {

                                data: 'bhtm1',

                            },
                            {

                                data: 'bhtm2',

                            },
                            {

                                data: 'bhtm3',

                            },
                            {

                                data: 'ps',

                            },
                            {

                                data: 'sp',

                            },
                            {

                                data: 'piringan_semak',

                            },
                            {

                                data: 'pokok_kuning',

                            },
                            {

                                data: 'underpruning',

                            },
                            {

                                data: 'overpruning',

                            },
                            {

                                data: 'app_version',

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

                    dataTableAncakTest.clear().rows.add(parseResult['mutuAncak']).draw();

                    // Attach event handlers to dynamically created buttons
                    $('#mutuAncakTable').on('click', '.edit-btn', function() {
                        var rowData = dataTableAncakTest.row($(this).closest('tr')).data();
                        var rowIndex = dataTableAncakTest.row($(this).closest('tr')).index();
                        editRow(rowIndex);
                    });

                    $('#mutuAncakTable').on('click', '.delete-btn', function() {
                        var rowIndex = dataTableAncakTest.row($(this).closest('tr')).index();
                        deleteRow(rowIndex);
                    });

                    // end table ajax mutu ancak 
                    // console.log(mutuBuah);
                    // table mutu buah 
                    var mutuBuahData = [];
                    for (var i = 0; i < mutuBuah.length; i++) {
                        var rowData = Object.values(mutuBuah[i][1]);
                        mutuBuahData.push(rowData);
                    }

                    // console.log(mutuBuahData);

                    function editRowBuah(id) {
                        // Save the selected row index
                        selectedRowIndex = id;

                        // Retrieve the id from the first column of the selected row
                        var rowData = dataTablesBuah.row(id).data();
                        var rowId = rowData[0];

                        // Populate the form with the data of the selected row
                        $('#editId_buah').val(rowData.id)

                        $('#update-estBH').val(rowData.estate)
                        $('#update-afdBH').val(rowData.afdeling)
                        $('#update-tphBH').val(rowData.tph_baris)
                        $('#update-blok_bh').val(rowData.blok)
                        $('#update-StatusBhpnen').val(rowData.status_panen)
                        $('#update-petugasBH').val(rowData.petugas)
                        $('#update-pemanen_bh').val(rowData.ancak_pemanen)
                        $('#update-bmt').val(rowData.bmt)
                        $('#update-bmk').val(rowData.bmk)
                        $('#update-emptyBH').val(rowData.empty_bunch)
                        $('#update-jjgBH').val(rowData.jumlah_jjg)
                        $('#update-overBH').val(rowData.overripe)
                        $('#update-abrBH').val(rowData.abnormal)
                        $('#update-vcutBH').val(rowData.vcut)
                        $('#update-alsBR').val(rowData.alas_br)
                        $('#update-bhmandor').val(rowData.kemandoran)

                        var modal = new bootstrap.Modal(document.getElementById('editModalBuah'));
                        modal.show();
                        // $('#editModalBuah').modal('show');
                    }

                    $(document).ready(function() {
                        // Close modal when the close button is clicked
                        $('#closeModalBtn_buah').click(function() {
                            var modal = new bootstrap.Modal(document.getElementById('editModalBuah'));
                            modal.hide();
                            // $('#editModalBuah').modal('hide');
                        });

                        // Submit the form when the Save Changes button is clicked
                        $('#saveChangesBtn_buah').off('click').on('click', function() {
                            $('#editForm_buah').submit();
                        });

                        $('#editForm_buah').submit(function(e) {
                            e.preventDefault(); // Prevent the default form submission

                            // Get the form data
                            var formData = new FormData(this);
                            formData.append('id', $('#editId_buah').val()); // Append the id field to the form data
                            formData.append('type', 'mutubuah'); // Add the type parameter with the desired value

                            var bmt = $('#update-bmt').val();
                            var bmk = $('#update-bmk').val();
                            var emptyBH = $('#update-emptyBH').val();
                            var jjgBH = $('#update-jjgBH').val();
                            var overBH = $('#update-overBH').val();
                            var abrBH = $('#update-abrBH').val();
                            var vcutBH = $('#update-vcutBH').val();
                            var alsBR = $('#update-alsBR').val();
                            var bhmandor = $('#update-bhmandor').val();

                            if (!isNumber(bmt) ||
                                !isNumber(bmk) ||
                                !isNumber(emptyBH) ||
                                !isNumber(jjgBH) ||
                                !isNumber(overBH) ||
                                !isNumber(abrBH) ||
                                !isNumber(vcutBH) ||
                                !isNumber(alsBR)
                            ) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Masukan Error',
                                    text: 'Hanya bisa di masukan angka Saja!'
                                });
                                return;
                            }
                            // Send the AJAX request
                            $.ajax({
                                type: 'POST',
                                url: '{{ route("updateBA") }}',
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function(response) {
                                    // console.log(response);
                                    // Close the modal
                                    // $('#editModalBuah').modal('hide');
                                    var modal = new bootstrap.Modal(document.getElementById('editModalBuah'));
                                    modal.hide();
                                    // Show a success message
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





                    var selectedRowIndex; // Variable to store the selected row index

                    function deleteRowBuah(id) {
                        // Save the selected row index
                        selectedRowIndex = id;

                        // Retrieve the ID from the first column of the selected row
                        var rowData = dataTablesBuah.row(id).data();
                        var rowId = rowData.id;
                        var modal = new bootstrap.Modal(document.getElementById('deleteModalBuah'));
                        modal.show();
                        // Show the delete modal
                        // $('#deleteModalBuah').modal('show');

                        $(document).ready(function() {
                            // Handle delete confirmation
                            $('#confirmDeleteBtn_buah').click(function() {
                                // Get the selected row index and ID
                                var rowIndex = selectedRowIndex;
                                var id = rowId;

                                // Create a form data object
                                var formData = new FormData();
                                formData.append('delete_idBuah', id);
                                formData.append('type', 'mutubuah'); // Add the type parameter with the desired value

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
                                    url: '{{ route("deleteBA") }}',
                                    method: 'POST',
                                    data: formData,
                                    processData: false,
                                    contentType: false,
                                    success: function(response) {
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
                                        $('#deleteModalBuah').modal('hide');
                                    }
                                });
                            });
                        });
                    }

                    // Example function to save changes
                    document.getElementById('saveChangesBtn').addEventListener('click', function() {
                        var modal = new bootstrap.Modal(document.getElementById('editModal'));
                        modal.hide();
                        // $('#editModal').modal('hide');
                    });


                    var dataTablesBuah = $('#mutuBuahable').DataTable({
                        columns: [{

                                data: 'id'
                            },
                            {

                                data: 'estate'
                            },
                            {

                                data: 'afdeling'
                            },
                            {

                                data: 'tph_baris'
                            },
                            {

                                data: 'blok'
                            },
                            {

                                data: 'status_panen'
                            },
                            {

                                data: 'petugas',

                            },
                            {

                                data: 'ancak_pemanen',

                            },
                            {

                                data: 'kemandoran',

                            },
                            {

                                data: 'bmt',

                            },
                            {

                                data: 'bmk',

                            },
                            {

                                data: 'empty_bunch',
                            },
                            {

                                data: 'jumlah_jjg',
                            },
                            {

                                data: 'overripe',
                            },
                            {

                                data: 'abnormal',
                            },
                            {

                                data: 'vcut',

                            },
                            {

                                data: 'alas_br',

                            },
                            {

                                data: 'app_version',

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


                    dataTablesBuah.clear().rows.add(parseResult['mutuBuah']).draw();



                    // Attach event handlers to dynamically created buttons
                    $('#mutuBuahable').on('click', '.edit-btn', function() {
                        var rowData = dataTablesBuah.row($(this).closest('tr')).data();
                        var rowIndex = dataTablesBuah.row($(this).closest('tr')).index();
                        editRowBuah(rowIndex);
                    });

                    $('#mutuBuahable').on('click', '.delete-btn', function() {
                        var rowIndex = dataTablesBuah.row($(this).closest('tr')).index();
                        deleteRowBuah(rowIndex);
                    });


                    // end table mutu buah 

                    function removeLatLon(array) {
                        array.forEach((item) => {
                            if (Array.isArray(item)) {
                                removeLatLon(item); // Recursively remove "lat" and "lon" from nested arrays
                            } else {
                                delete item.lat;
                                delete item.lon;
                                delete item.bulan;
                                delete item.tahun;

                                delete item.foto_fu;
                                delete item.foto_temuan;
                                delete item.foto_komentar;
                                delete item.komentar;
                            }
                        });
                    }


                    // Call the function to remove "lat" and "lon" properties
                    removeLatLon(mutuTransport);

                    // console.log(mutuTransport);
                    var mutuTransData = [];
                    for (var i = 0; i < mutuTransport.length; i++) {
                        var rowData = Object.values(mutuTransport[i][1]);
                        mutuTransData.push(rowData);
                    }

                    // console.log(mutuTransData);

                    function editRowTrans(id) {
                        // Save the selected row index
                        selectedRowIndex = id;

                        // Retrieve the id from the first column of the selected row
                        var rowData = dataTablesTrans.row(id).data();
                        var rowId = rowData[0];

                        // Populate the form with the data of the selected row
                        $('#id_trans').val(rowData.id)

                        $('#update-estTrans').val(rowData.estate)
                        $('#update-afd_trans').val(rowData.afdeling)
                        $('#update-tphbrTrans').val(rowData.tph_baris)
                        $('#update-blok_trans').val(rowData.blok)
                        $('#update-Status_trPanen').val(rowData.status_panen)
                        $('#update-petugasTrans').val(rowData.petugas)
                        $('#update-bt_trans').val(rowData.bt)
                        $('#update-rstTrans').val(rowData.rst)
                        $('#update-transmandor').val(rowData.kemandoran)
                        var modal = new bootstrap.Modal(document.getElementById('editModalTrans'));
                        modal.show();
                        // $('#editModalTrans').modal('show');
                    }

                    $(document).ready(function() {
                        // Close modal when the close button is clicked
                        $('#closeModalBtn_Trans').click(function() {
                            var modal = new bootstrap.Modal(document.getElementById('editModalTrans'));
                            modal.hide();
                            // $('#editModalTrans').modal('hide');
                        });

                        // Submit the form when the Save Changes button is clicked
                        $('#saveChangesBtn_trans').off('click').on('click', function() {
                            $('#editForm_Trans').submit();
                        });

                        $('#editForm_Trans').submit(function(e) {
                            e.preventDefault(); // Prevent the default form submission

                            // Get the form data
                            var formData = new FormData(this);
                            formData.append('id', $('#id_trans').val()); // Append the id field to the form data
                            formData.append('type', 'mututrans'); // Add the type parameter with the desired value

                            // Validate the bt_trans and rstTrans fields
                            var btTransValue = $('#update-bt_trans').val();
                            var rstTransValue = $('#update-rstTrans').val();
                            var transmandor = $('#update-transmandor').val();
                            if (!isNumber(btTransValue) || !isNumber(rstTransValue)) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Masukan Error',
                                    text: 'Hanya bisa di masukan angka Saja!'
                                });
                                return;
                            }

                            // Send the AJAX request
                            $.ajax({
                                type: 'POST',
                                url: '{{ route("updateBA") }}',
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function(response) {
                                    // console.log(response);
                                    // Close the modal
                                    // $('#editModalTrans').modal('hide');
                                    var modal = new bootstrap.Modal(document.getElementById('editModalTrans'));
                                    modal.hide();
                                    // Show a success message
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
                                    // Show an error message
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Gagal memperbarui data!'
                                    });
                                }
                            });
                        });
                    });






                    var selectedRowIndex; // Variable to store the selected row index

                    function deleteRowTrans(id) {
                        // Save the selected row index
                        selectedRowIndex = id;

                        // Retrieve the ID from the first column of the selected row
                        var rowData = dataTablesTrans.row(id).data();
                        var rowId = rowData.id;

                        // Show the delete modal
                        // $('#deleteModalTrans').modal('show');
                        var modal = new bootstrap.Modal(document.getElementById('deleteModalTrans'));
                        modal.show();
                        $(document).ready(function() {
                            // Handle delete confirmation
                            $('#confirmDeleteBtn_trans').click(function() {
                                // Get the selected row index and ID
                                var rowIndex = selectedRowIndex;
                                var id = rowId;

                                // Create a form data object
                                var formData = new FormData();
                                formData.append('id_trans', id);
                                formData.append('type', 'mututrans'); // Add the type parameter with the desired value

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
                                    url: '{{ route("deleteBA") }}',
                                    method: 'POST',
                                    data: formData,
                                    processData: false,
                                    contentType: false,
                                    success: function(response) {
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
                                        // $('#deleteModalTrans').modal('hide');
                                    }
                                });
                            });
                        });
                    }

                    function isNumber(value) {
                        return !isNaN(parseFloat(value)) && isFinite(value);
                    }
                    // Example function to save changes
                    document.getElementById('saveChangesBtn').addEventListener('click', function() {
                        var modal = new bootstrap.Modal(document.getElementById('editModal'));
                        modal.hide();
                        // $('#editModal').modal('hide');
                    });

                    var dataTablesTrans = $('#mutuTransportable').DataTable({
                        columns: [{

                                data: 'id'
                            },
                            {

                                data: 'estate'
                            },
                            {

                                data: 'afdeling'
                            },
                            {

                                data: 'tph_baris'
                            },
                            {

                                data: 'blok'
                            },
                            {

                                data: 'status_panen'
                            },
                            {

                                data: 'petugas',

                            },
                            {

                                data: 'datetime',

                            },
                            {

                                data: 'kemandoran',

                            },
                            {

                                data: 'luas_blok',

                            },
                            {

                                data: 'bt',

                            },
                            {

                                data: 'rst',
                            },
                            {

                                data: 'app_version',

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



                    dataTablesTrans.clear().rows.add(parseResult['mutuTransport']).draw();


                    // Attach event handlers to dynamically created buttons
                    $('#mutuTransportable').on('click', '.edit-btn', function() {
                        var rowData = dataTablesTrans.row($(this).closest('tr')).data();
                        var rowIndex = dataTablesTrans.row($(this).closest('tr')).index();
                        editRowTrans(rowIndex);
                    });

                    $('#mutuTransportable').on('click', '.delete-btn', function() {
                        var rowIndex = dataTablesTrans.row($(this).closest('tr')).index();
                        deleteRowTrans(rowIndex);
                    });



                },
                error: function() {
                    lottieAnimation.stop(); // Stop the Lottie animation
                    lottieContainer.style.display = 'none'; // Hide the Lottie container
                }
            });




        }

        function Show() {
            // console.log('date');
            fetchAndUpdateData();
            getmaps();
            getDataDay();
            getverif()
        }
        document.querySelector('button[type="button"]').addEventListener('click', Show);

        function getverif() {
            let Tanggal = document.getElementById('inputDate').value;
            let est = document.getElementById('est').value;
            let afd = document.getElementById('afd').value;
            let menu = 'qcinspeksi'
            var _token = $('input[name="_token"]').val();

            document.getElementById('notverif').classList.add('d-none');
            document.getElementById('verifdone').classList.add('d-none');
            document.getElementById('asistennotverif').classList.add('d-none');
            document.getElementById('askep_manager_not_approved').classList.add('d-none');
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
                    } else if (response === 'asisten_not_approved') {
                        // console.log('manager_not_approved');
                        document.getElementById('asistennotverif').classList.remove('d-none');
                    } else if (response === 'askep_manager_not_approved') {
                        // console.log('manager_not_approved');
                        document.getElementById('askep_manager_not_approved').classList.remove('d-none');
                    } else if (response === 'condition_not_met') {
                        console.error('Unexpected response:', response);
                    } else {
                        console.error('Unexpected response:', response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });

        }

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
        var user_id = "{{ auth()->user()->user_id }}";

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
                    let menu = 'qcinspeksi'
                    let tanggal_approve = hariini();
                    $.ajax({
                        url: "{{ route('verifaction') }}",
                        method: "POST",
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


        function goBack() {
            // Save the selected tab to local storage
            localStorage.setItem('selectedTab', 'nav-data-tab');

            // Redirect to the target page
            window.location.href = "https://qc-apps.srs-ssms.com/dashboard_inspeksi";
        }
        var regional = '{{$reg}}';

        function getDataDay() {
            $('#dataInspeksi').empty()
            var Tanggal = document.getElementById('inputDate').value;
            var est = document.getElementById('est').value;
            var afd = document.getElementById('afd').value;
            var reg = regional; // Assign the "regional" variable to "reg"
            var _token = $('input[name="_token"]').val();

            $.ajax({
                url: "{{ route('getDataDay') }}",
                method: "GET",
                data: {
                    Tanggal: Tanggal,
                    est: est,
                    afd: afd,
                    reg: reg, // Pass the "reg" value in the data object
                    _token: _token
                },
                success: function(result) {
                    var parseResult = JSON.parse(result)
                    var resultnew = Object.entries(parseResult['resultnew'])
                    // console.log(resmandr);
                    var tbody1 = document.getElementById('dataInspeksi');
                    var resmandr = Object.entries(parseResult['tabelmandor'])

                    // console.log(resultnew);
                    let inc = 1;
                    resultnew.forEach(item => {
                        let item1 = inc++;
                        let item2 = item[0];
                        let item3 = item[1]?.status_panen ?? '-';
                        let item4 = item[1]?.pokok_samplecak ?? '-';
                        let item5 = item[1]?.luas_ha ?? '-';

                        // Create an array of item values
                        let itemValues = [
                            item1, item2, item3, item4, item5,
                            item[1]?.jumlah_panencak ?? '-', (item[1]?.akp_rlcak?.toFixed(2)) ?? '-', item[1]?.pcak ?? '-', item[1]?.kcak ?? '-',
                            item[1]?.tglcak ?? '-', item[1]?.total_brdcak ?? '-', item[1]?.brdperjjgcak?.toFixed(2) ?? '-', item[1]?.skor_brdcak ?? '-', item[1]?.bhts_scak ?? '-',
                            item[1]?.bhtm1cak ?? '-', item[1]?.bhtm2cak ?? '-', item[1]?.bhtm3cak ?? '-', item[1]?.total_buahcak ?? '-',
                            item[1]?.jjgperBuahcak ?? '-', item[1]?.skor_bhcak ?? '-', item[1]?.palepah_pokokcak ?? '-', item[1]?.palepah_percak?.toFixed(2) ?? '-',
                            item[1]?.skor_pscak ?? '-', item[1]?.skor_akhircak ?? '-', item[1]?.tph_sampleNew ?? '-', item[1]?.total_brdtrans ?? '-',
                            item[1]?.total_brdperTPHtrans ?? '-', item[1]?.skor_brdPertphtrans ?? '-', item[1]?.total_buahtrans ?? '-', item[1]?.total_buahPerTPHtrans ?? '-',
                            item[1]?.skor_buahPerTPHtrans ?? '-', item[1]?.totalSkortrans ?? '-', item[1]?.skorAkhir ?? '-', item[1]?.kategori ?? '-'
                        ];

                        let tr = document.createElement('tr');

                        let skorAkhirValue;

                        itemValues.forEach((value, index) => {
                            let itemElement = document.createElement('td');
                            itemElement.classList.add("text-center");
                            itemElement.innerText = value;

                            // Store the skorAkhir value
                            if (index === itemValues.length - 2) { // Assuming skorAkhir is at the second last position
                                if (value === '-') {
                                    skorAkhirValue = '-';
                                } else {
                                    skorAkhirValue = parseFloat(value);
                                }
                                setBackgroundColor(itemElement, skorAkhirValue);
                            }

                            // Set background color based on skorAkhir for the last item
                            if (index === itemValues.length - 1) {
                                setBackgroundColor(itemElement, skorAkhirValue);
                            }

                            tr.appendChild(itemElement);
                        });

                        // Append the row to the table
                        tbody1.appendChild(tr);
                    });


                    if (reg !== 2) {
                        let tbody1 = document.getElementById('datakemandoran');
                        // Assuming 'resmandr' is your array
                        resmandr.forEach(element => {
                            let tr = document.createElement('tr');

                            for (let i = 0; i <= 54; i++) {
                                let itemElement = document.createElement('td');
                                let value;
                                let bgcolor = ''; // Declare bgcolor variable here

                                let total = (element[1]['tot_skorbuah'] ?? 0) + (element[1]['totskor_ancak'] ?? 0) + (element[1]['tot_skortra'] ?? 0);

                                let color, text;

                                if (total >= 95.0 && total <= 100.0) {
                                    color = "#4874c4";
                                    text = "EXCELLENT";
                                } else if (total >= 85.0 && total < 95.0) {
                                    color = "#00ff2e";
                                    text = "GOOD";
                                } else if (total >= 75.0 && total < 85.0) {
                                    color = "yellow";
                                    text = "SATISFACTORY";
                                } else if (total >= 65.0 && total < 75.0) {
                                    color = "orange";
                                    text = "FAIR";
                                } else {
                                    color = "red";
                                    text = "POOR";
                                }
                                switch (i) {
                                    case 0:
                                        value = element[0]; // Accessing the key ('A', 'B', 'Total')
                                        break;
                                    case 1:
                                        value = element[1]['pokok_sample'] ?? 0
                                        break;
                                    case 2:
                                        value = element[1]['luas_ha'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 3:
                                        value = element[1]['pokok_panen'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 4:
                                        value = element[1]['akp_real'] ?? element[1]['akp'] // Accessing the 'luas_ha' value
                                        break;
                                    case 5:
                                        value = element[1]['p_ma'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 6:
                                        value = element[1]['k_ma'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 7:
                                        value = element[1]['gl_ma'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 8:
                                        value = element[1]['total_brd_ma'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 9:
                                        value = element[1]['btr_jjg_ma'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 10:
                                        value = element[1]['skor_brd'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 11:
                                        value = element[1]['bhts_ma'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 12:
                                        value = element[1]['bhtm1_ma'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 13:
                                        value = element[1]['bhtm2_ma'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 14:
                                        value = element[1]['bhtm3_ma'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 15:
                                        value = element[1]['tot_jjg_ma'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 16:
                                        value = element[1]['jjg_tgl_ma'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 17:
                                        value = element[1]['skor_buah'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 18:
                                        value = element[1]['ps_ma'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 19:
                                        value = element[1]['PerPSMA'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 20:
                                        value = element[1]['skor_pale'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 21:
                                        value = element[1]['totskor_ancak'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 22:
                                        value = element[1]['tph_sample'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 23:
                                        value = element[1]['bt_total'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 24:
                                        value = element[1]['bt_tph'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 25:
                                        value = element[1]['skor_bt'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 26:
                                        value = element[1]['restan_total'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 27:
                                        value = element[1]['restan_tph'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 28:
                                        value = element[1]['skor_restan'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 29:
                                        value = element[1]['tot_skortra'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 30:
                                        value = element[1]['blok_mb'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 31:
                                        value = element[1]['jml_janjang'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 32:
                                        value = element[1]['jml_mentah'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 33:
                                        value = element[1]['PersenBuahMentah'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 34:
                                        value = element[1]['skorbh_mentah'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 35:
                                        value = element[1]['jml_masak'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 36:
                                        value = element[1]['PersenBuahMasak'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 37:
                                        value = element[1]['skorbh_masak'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 38:
                                        value = element[1]['jml_over'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 39:
                                        value = element[1]['PersenBuahOver'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 40:
                                        value = element[1]['skorbh_over'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 41:
                                        value = element[1]['jml_empty'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 42:
                                        value = element[1]['PersenPerempty'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 43:
                                        value = element[1]['skorbh_empty'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 44:
                                        value = element[1]['jml_vcut'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 45:
                                        value = element[1]['PersenVcut'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 46:
                                        value = element[1]['skorbh_vcut'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 47:
                                        value = element[1]['jml_abnormal'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 48:
                                        value = element[1]['PersenAbr'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 49:
                                        value = element[1]['alas_mb'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 50:
                                        value = element[1]['PersenKrgBrd'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 51:
                                        value = element[1]['skorkarung'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 52:
                                        value = element[1]['tot_skorbuah'] ?? 0 // Accessing the 'luas_ha' value
                                        break;
                                    case 53:

                                        value = element[1]['totalall'] ?? total
                                        bgcolor = color
                                        break;
                                    case 54:

                                        value = element[1]['kategori'] ?? text
                                        bgcolor = color
                                        break;
                                    default:
                                        break;
                                }

                                itemElement.innerText = value;
                                itemElement.style.backgroundColor = bgcolor;
                                tr.appendChild(itemElement);
                            }

                            tbody1.appendChild(tr);
                        });

                    }
                }
            });
        }

        if (canedit) {
            document.getElementById('moveDataButton').onclick = function() {


                Swal.fire({
                    title: "Apakah Anda ingin mengubah tanggal sidak?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        let tanggalorix = document.getElementById('inputDate').value;

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
                                // console.log(selectDate);
                                return selectedDate; // Return the selected date
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {

                                Swal.fire({
                                    title: "Perhatian!",
                                    html: 'Silahkan pilih kategori',
                                    input: 'select',
                                    inputOptions: {
                                        'mutu_ancak': 'Mutu Ancak',
                                        'mutu_buah': 'Mutu Buah',
                                        'mutu_transport': 'Mutu Transport'
                                    },
                                    inputPlaceholder: 'Pilih kategori',
                                    showCancelButton: true,
                                    confirmButtonText: "Pindahkan",
                                    cancelButtonText: "Batal",
                                    allowOutsideClick: false,
                                    preConfirm: (category) => {
                                        if (!category) {
                                            Swal.showValidationMessage('Silakan pilih kategori!');
                                        }
                                        return category;
                                    }

                                }).then((result1) => {
                                    if (result.isConfirmed) {
                                        // console.log(date);
                                        Swal.fire({
                                            title: 'Loading',
                                            html: '<span class="loading-text">Mohon Tunggu...</span>',
                                            allowOutsideClick: false,
                                            showConfirmButton: false,
                                            willOpen: () => {
                                                Swal.showLoading();
                                            }
                                        });
                                        var tanggalori = document.getElementById('inputDate').value;
                                        var selectedDate = result.dismiss ? '' : result.value; // Get the selected date
                                        var category = result1.dismiss ? '' : result1.value;
                                        var type = 'qcinspeksi';
                                        var _token = $('input[name="_token"]').val();
                                        $.ajax({
                                            url: "{{ route('changedatadate') }}",
                                            method: "post",
                                            data: {
                                                tglreal: tanggalori,
                                                tgledit: selectedDate, // Set tgledit to the selected dateenableShowButton
                                                est: getest,
                                                afd: getafd,
                                                type: type,
                                                category: category,
                                                _token: _token
                                            },
                                            success: function(result) {
                                                if (result && result.message === 'Data berhasil diupdate') {
                                                    Swal.close();
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
                                                } else {
                                                    Swal.close();
                                                    Swal.fire('Error', 'Gagal mengupdate data', 'error');

                                                }
                                            },
                                            error: function() {
                                                Swal.close();
                                                Swal.fire('Error', 'Gagal menghubungi server', 'error');
                                            }
                                        });
                                    } else {
                                        Swal.fire("Pemilihan tanggal dibatalkan!", "", "info");
                                    }
                                });
                            }
                        });
                    }
                });
            }
        }
    </script>

</x-layout.app>