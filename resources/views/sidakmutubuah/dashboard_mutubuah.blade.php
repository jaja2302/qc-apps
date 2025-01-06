<x-layout.app>

    <style>
        .blur {
            filter: blur(4px);
            opacity: 0.5;
        }

        .big-table {
            width: 100% !important;
            position: absolute !important;
            left: 0 !important;
            z-index: 10;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .tabContainer .col-sm-3:not(.blur) {
            cursor: pointer;
        }

        .mode-active {
            background-color: #007bff !important;
            border-color: #007bff !important;
        }

        .mode-options {
            position: absolute;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 3px;
            padding: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .my-table {
            width: 100%;
            border-collapse: collapse;
        }

        .my-table th,
        .my-table td {
            text-align: center;
            padding: 8px;
            border: 1px solid black;
            white-space: nowrap;
        }

        .my-table thead th {
            font-weight: bold;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .my-table thead.horizontal-freeze th {
            position: sticky;
            left: 0;
            z-index: 101;
        }



        .title-row {
            font-size: 1.5em;
            text-align: center;
            margin-bottom: 10px;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: scroll;
        }

        @keyframes fadeInOut {
            0% {
                opacity: 0;
            }

            50% {
                opacity: 1;
            }

            100% {
                opacity: 0;
            }
        }

        .loading-text {
            animation: fadeInOut 2s ease-in-out infinite;
        }
    </style>



    <div class="container-fluid">
        <section class="content"><br>
            <div class="container-fluid">
                <div class="card table_wrapper">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active" id="nav-utama-tab" data-toggle="tab" href="#nav-utama" role="tab" aria-controls="nav-utama" aria-selected="true">Halaman Utama</a>
                            <a class="nav-item nav-link" id="nav-data-tab" data-toggle="tab" href="#nav-data" role="tab" aria-controls="nav-data" aria-selected="false">Data</a>
                            <a class="nav-item nav-link" id="nav-sbi-tab" data-toggle="tab" href="#nav-sbi" role="tab" aria-controls="nav-sbi" aria-selected="false">SBI</a>
                            <a class="nav-item nav-link" id="nav-issue-tab" data-toggle="tab" href="#nav-issue" role="tab" aria-controls="nav-issue" aria-selected="false">Finding Issue</a>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-utama" role="tabpanel" aria-labelledby="nav-utama-tab">
                            <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark">
                                <h5><b>REKAPITULASI RANKING NILAI SIDAK PEMERIKSAAN MUTU BUAH
                                    </b></h5>
                            </div>
                            <div class="content">
                                <div class="d-flex justify-content-end mt-3 mb-2 ml-3 mr-3" style="padding-top: 20px;">
                                    <div class="row w-100">
                                        <div class="col-md-2 offset-md-8">
                                            {{csrf_field()}}
                                            <select class="form-control" id="regionalPanen">
                                                @foreach($option_reg as $key => $item)
                                                <option value="{{ $item['id'] }}">{{ $item['nama'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                            {{csrf_field()}}
                                            <input class="form-control" value="{{ date('Y-m') }}" type="month" name="inputbulan" id="inputbulan">
                                        </div>
                                    </div>
                                    <button class="btn btn-primary mb-3" style="float: right" id="btnShow">Show</button>
                                </div>
                                <style>
                                    /* Add button styles */
                                    button {
                                        background-color: #4CAF50;
                                        border: none;
                                        color: white;
                                        padding: 8px 16px;
                                        text-align: center;
                                        text-decoration: none;
                                        display: inline-block;
                                        font-size: 16px;
                                        margin: 4px 2px;
                                        cursor: pointer;
                                        transition-duration: 0.4s;
                                    }

                                    /* Add hover effect */
                                    button:hover {
                                        background-color: #45a049;
                                    }
                                </style>
                                <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 ">
                                    <button id="sort-est-btn">Sort by Afd</button>
                                    <button id="sort-rank-btn">Sort by Rank</button>
                                    <button id="scrennshotimg">Download As IMG</button>
                                </div>
                                <div id="tablesContainer">
                                    <div class="tabContainer">
                                        <div class="ml-3 mr-3" id="scrensshot_bulanan">
                                            <div class="row justify-content-center">
                                                <div class="col-12 col-md-6 col-lg-3" data-regional="1" id="Tab1">
                                                    <div class="table-responsive">
                                                        <table class=" table table-bordered" style="font-size: 13px;background-color:white" id="table1">
                                                            <thead>
                                                                <tr bgcolor="yellow">
                                                                    <th colspan="5" id="thead1" class="text-center">WILAYAH I </th>
                                                                </tr>
                                                                <tr bgcolor="#2044a4" style="color: white">
                                                                    <th rowspan="2" class="text-center" style="vertical-align: middle;">KEBUN</th>
                                                                    <th rowspan="2" style="vertical-align: middle;">AFD</th>
                                                                    <th rowspan="2" style="text-align:center; vertical-align: middle;">Nama</th>
                                                                    <th colspan="2" class="text-center">Todate</th>
                                                                </tr>
                                                                <tr bgcolor="#1D43A2" style="color: white">
                                                                    <th>Score</th>
                                                                    <th>Rank</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="week1">
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6 col-lg-3" data-regional="1" id="Tab2">
                                                    <div class="table-responsive">
                                                        <table class=" table table-bordered" style="font-size: 13px;background-color:white" id="table2">
                                                            <thead>
                                                                <tr bgcolor="yellow">
                                                                    <th colspan="5" id="thead2" class="text-center">WILAYAH II</th>
                                                                </tr>
                                                                <tr bgcolor="#2044a4" style="color: white">
                                                                    <th rowspan="2" class="text-center" style="vertical-align: middle;">KEBUN</th>
                                                                    <th rowspan="2" style="vertical-align: middle;">AFD</th>
                                                                    <th rowspan="2" style="text-align:center; vertical-align: middle;">Nama</th>
                                                                    <th colspan="2" class="text-center">Todate</th>
                                                                </tr>
                                                                <tr bgcolor="#1D43A2" style="color: white">
                                                                    <th>Score</th>
                                                                    <th>Rank</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="week2">

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6 col-lg-3" data-regional="1" id="Tab3">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered" style="font-size: 13px;background-color:white" id="table3">
                                                            <thead>
                                                                <tr bgcolor="yellow">
                                                                    <th colspan="5" id="thead3" class="text-center">WILAYAH III</th>
                                                                </tr>
                                                                <tr bgcolor="#2044a4" style="color: white">
                                                                    <th rowspan="2" class="text-center" style="vertical-align: middle;">KEBUN</th>
                                                                    <th rowspan="2" style="vertical-align: middle;">AFD</th>
                                                                    <th rowspan="2" style="text-align:center; vertical-align: middle;">Nama</th>
                                                                    <th colspan="2" class="text-center">Todate</th>
                                                                </tr>
                                                                <tr bgcolor="#1D43A2" style="color: white">
                                                                    <th>Score</th>
                                                                    <th>Rank</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="week3">

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6 col-lg-3" data-regional="1" id="Tab4">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered" style="font-size: 13px;background-color:white" id="table4">
                                                            <thead>
                                                                <tr bgcolor="yellow">
                                                                    <th colspan="5" class="text-center" id="theadx3">PLASMA1</th>
                                                                </tr>
                                                                <tr bgcolor="#2044a4" style="color: white">
                                                                    <th rowspan="2" class="text-center" style="vertical-align: middle;">KEBUN</th>
                                                                    <th rowspan="2" style="vertical-align: middle;">AFD</th>
                                                                    <th rowspan="2" style="text-align:center; vertical-align: middle;">Nama</th>
                                                                    <th colspan="2" class="text-center">Todate</th>
                                                                </tr>
                                                                <tr bgcolor="#1D43A2" style="color: white">
                                                                    <th>Score</th>
                                                                    <th>Rank</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="plasma1">

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <div class="col-sm-12">
                                    <table class="table table-bordered">
                                        <thead id="theadreg">

                                        </thead>

                                    </table>
                                </div>

                                <p class="ml-3 mb-3 mr-3">
                                    <button style="width: 100%" class="btn btn-secondary" type="button" data-toggle="collapse" data-target="#showEstate" aria-expanded="false" aria-controls="showEstate">
                                        Grafik Sidak Mutu Buah Berdasarkan Estate
                                    </button>
                                </p>
                                <div class="collapse" id="showEstate">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p style="font-size: 15px; text-align: center;"><b><u>MATANG (%)</u></b></p>
                                                    <div id="matang"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p style="font-size: 15px; text-align: center;"><b><u>MENTAH (%)</u></b></p>
                                                    <div id="mentah"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p style="font-size: 15px; text-align: center;"><b><u>LEWAT MATANG (%)</u></b></p>
                                                    <div id="lewatmatang"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p style="font-size: 15px; text-align: center;"><b><u>JANGKOS (%)</u></b></p>
                                                    <div id="jangkos"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p style="font-size: 15px; text-align: center;"><b><u>TIDAK STANDAR V-CUT (%)</u></b></p>
                                                    <div id="tidakvcut"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p style="font-size: 15px; text-align: center;"><b><u>PENGGUNAAN KARUNG BRONDOLAN (%)</u></b></p>
                                                    <div id="karungbrondolan"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <p class="ml-3 mb-3 mr-3">
                                    <button style="width: 100%" class="btn btn-secondary" type="button" data-toggle="collapse" data-target="#showWilayah" aria-expanded="false" aria-controls="showWilayah">
                                        Grafik Sidak Mutu Buah Berdasarkan Wilayah
                                    </button>
                                </p>
                                <div class="collapse" id="showWilayah">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p style="font-size: 15px; text-align: center;"><b><u>MATANG (%)</u></b></p>
                                                    <div id="matang_wil"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p style="font-size: 15px; text-align: center;"><b><u>MENTAH (%)</u></b></p>
                                                    <div id="mentah_wil"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p style="font-size: 15px; text-align: center;"><b><u>LEWAT MATANG (%)</u></b></p>
                                                    <div id="lewatmatang_wil"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p style="font-size: 15px; text-align: center;"><b><u>JANGKOS (%)</u></b></p>
                                                    <div id="jangkos_wil"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p style="font-size: 15px; text-align: center;"><b><u>TIDAK STANDAR V-CUT (%)</u></b></p>
                                                    <div id="tidakvcut_wil"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p style="font-size: 15px; text-align: center;"><b><u>PENGGUNAAN KARUNG BRONDOLAN (%)</u></b></p>
                                                    <div id="karungbrondolan_wil"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                            <p class="ml-3 mb-3 mr-3">
                                <button style="width: 100%" class="btn btn-primary" type="button" data-toggle="collapse" data-target="#showByYear" aria-expanded="false" aria-controls="showByYear">
                                    TAMPILKAN PER MINGGU
                                </button>
                            </p>

                            <div class="collapse" id="showByYear">
                                <div class="d-flex justify-content-center mb-2 ml-3 mr-3 border border-dark">
                                    <h5><b>REKAPITULASI RANKING NILAI KUALITAS PANEN</b></h5>
                                </div>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tabTable" role="tabpanel">

                                        <style>
                                            .download-btn {
                                                background-color: green;
                                                color: white;
                                            }

                                            .download-btn.disabled {
                                                background-color: grey;
                                                pointer-events: none;
                                            }
                                        </style>

                                        <div class="d-flex justify-content-end mt-3 mb-2 ml-3 mr-3" style="padding-top: 20px;">
                                            <div class="row w-100">
                                                <div class="col-md-2 offset-md-8">
                                                    {{csrf_field()}}
                                                    <select class="form-control" id="regionalData">
                                                        @foreach($option_reg as $key => $item)
                                                        <option value="{{ $item['id'] }}">{{ $item['nama'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                                    {{ csrf_field() }}
                                                    <input type="hidden" id="startWeek" name="start" value="">
                                                    <input type="hidden" id="lastWeek" name="last" value="">
                                                    <input type="week" name="dateWeek" id="dateWeek" value="{{ date('Y').'-W'.date('W') }}" aria-describedby="dateWeekHelp">

                                                </div>
                                            </div>
                                            <button class="btn btn-primary mb-3 ml-2" id="showTahung">Show</button>

                                            <form action="{{ route('WeeklyReport') }}" method="POST" class="form-inline" style="display: inline;" target="_blank">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="tglPDF" id="tglPDF" value="">
                                                <input type="hidden" name="regPDF" id="regPDF" value="">
                                                <button type="submit" class="download-btn ml-2" id="download-button">
                                                    PDF
                                                </button>
                                                <!-- <button type="submit" class="btn btn-secondary" id="pdfButton" disabled>PDF</button> -->
                                            </form>
                                        </div>
                                        <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 ">
                                            <button id="sort-est-btnWek">Sort by Afd</button>
                                            <button id="sort-rank-btnWek">Sort by Rank</button>
                                        </div>

                                        <div id="tablesContainer">
                                            <div class="tabContainer">
                                                <div class="ml-3 mr-3">
                                                    <div class="row justify-content-center">
                                                        <div class="col-12 col-md-6 col-lg-3" data-regional="1" id="Tabsx1">
                                                            <div class="table-responsive">
                                                                <table class=" table table-bordered" style="font-size: 13px" id="table1">
                                                                    <thead>
                                                                        <tr bgcolor="yellow">
                                                                            <th colspan="5" id="theads1">WILAYAH I</th>
                                                                        </tr>
                                                                        <tr bgcolor="#2044a4" style="color: white">
                                                                            <th rowspan="2" class="text-center" style="vertical-align: middle;">KEBUN</th>
                                                                            <th rowspan="2" style="vertical-align: middle;">AFD</th>
                                                                            <th rowspan="2" style="text-align:center; vertical-align: middle;">Nama</th>
                                                                            <th colspan="2" class="text-center">Todate</th>
                                                                        </tr>
                                                                        <tr bgcolor="#1D43A2" style="color: white">
                                                                            <th>Score</th>
                                                                            <th>Rank</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="weeks1">
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-md-6 col-lg-3" data-regional="1" id="Tabsx2">
                                                            <div class="table-responsive">
                                                                <table class=" table table-bordered" style="font-size: 13px" id="table1">
                                                                    <thead>
                                                                        <tr bgcolor="yellow">
                                                                            <th colspan="5" id="theads2">WILAYAH II</th>
                                                                        </tr>
                                                                        <tr bgcolor="#2044a4" style="color: white">
                                                                            <th rowspan="2" class="text-center" style="vertical-align: middle;">KEBUN</th>
                                                                            <th rowspan="2" style="vertical-align: middle;">AFD</th>
                                                                            <th rowspan="2" style="text-align:center; vertical-align: middle;">Nama</th>
                                                                            <th colspan="2" class="text-center">Todate</th>
                                                                        </tr>
                                                                        <tr bgcolor="#1D43A2" style="color: white">
                                                                            <th>Score</th>
                                                                            <th>Rank</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="weeks2">

                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-md-6 col-lg-3" data-regional="1" id="Tabsx3">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered" style="font-size: 13px" id="Reg3">
                                                                    <thead>
                                                                        <tr bgcolor="yellow">
                                                                            <th colspan="5" id="theads3">WILAYAH III</th>
                                                                        </tr>
                                                                        <tr bgcolor="#2044a4" style="color: white">
                                                                            <th rowspan="2" class="text-center" style="vertical-align: middle;">KEBUN</th>
                                                                            <th rowspan="2" style="vertical-align: middle;">AFD</th>
                                                                            <th rowspan="2" style="text-align:center; vertical-align: middle;">Nama</th>
                                                                            <th colspan="2" class="text-center">Todate</th>
                                                                        </tr>
                                                                        <tr bgcolor="#1D43A2" style="color: white">
                                                                            <th>Score</th>
                                                                            <th>Rank</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="weeks3">

                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-md-6 col-lg-3" data-regional="1" id="Tabsx4">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered" style="font-size: 13px" id="plasmaID">
                                                                    <thead>
                                                                        <tr bgcolor="#fffc04">
                                                                            <th colspan="5" id="theads4" style="text-align:center">Plasma</th>
                                                                        </tr>
                                                                        <tr bgcolor="#2044a4" style="color: white">
                                                                            <th rowspan="2" class="text-center" style="vertical-align: middle;">KEBUN</th>
                                                                            <th rowspan="2" style="vertical-align: middle;">AFD</th>
                                                                            <th rowspan="2" style="text-align:center; vertical-align: middle;">Nama</th>
                                                                            <th colspan="2" class="text-center">Todate</th>
                                                                        </tr>
                                                                        <tr bgcolor="#2044a4" style="color: white">
                                                                            <th>Score</th>
                                                                            <th>Rank</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="plasmas1">
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                        <div class="col-sm-12">
                                            <table class="table table-bordered">
                                                <thead id="theadregs">
                                                    <tr>
                                                        <th colspan="1">REG-I</th>
                                                        <th colspan="1">RH-1</th>
                                                        <th colspan="1">Akhmad Faisyal</th>
                                                        <th colspan="8"></th>
                                                    </tr>
                                                </thead>

                                            </table>
                                        </div>

                                        <p class="ml-3 mb-3 mr-3">
                                            <button style="width: 100%" class="btn btn-secondary" type="button" data-toggle="collapse" data-target="#showEstate1" aria-expanded="false" aria-controls="showEstate1">
                                                Grafik Sidak Mutu Buah Berdasarkan Estate
                                            </button>
                                        </p>

                                        <div class="collapse" id="showEstate1">
                                            <div class="ml-4 mr-4">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <p style="font-size: 15px; text-align: center;"><b><u>MATANG (%)</u></b></p>
                                                                <div id="matangthun"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <p style="font-size: 15px; text-align: center;"><b><u>MENTAH (%)</u></b></p>
                                                                <div id="mentahtahun"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <p style="font-size: 15px; text-align: center;"><b><u>LEWAT MATANG (%)</u></b></p>
                                                                <div id="lewatmatangtahun"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <p style="font-size: 15px; text-align: center;"><b><u>JANGKOS (%)</u></b></p>
                                                                <div id="jangkostahun"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <p style="font-size: 15px; text-align: center;"><b><u>TIDAK STANDAR V-CUT (%)</u></b></p>
                                                                <div id="tidakvcuttahun"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <p style="font-size: 15px; text-align: center;"><b><u>PENGGUNAAN KARUNG BRONDOLAN (%)</u></b></p>
                                                                <div id="karungbrondolantahun"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <p class="ml-3 mb-3 mr-3">
                                            <button style="width: 100%" class="btn btn-secondary" type="button" data-toggle="collapse" data-target="#showWilayah1" aria-expanded="false" aria-controls="showWilayah1">
                                                Grafik Sidak Mutu Buah Berdasarkan Wilayah
                                            </button>
                                        </p>
                                        <div class="collapse" id="showWilayah1">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <p style="font-size: 15px; text-align: center;"><b><u>MATANG (%)</u></b></p>
                                                            <div id="matang_wils"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <p style="font-size: 15px; text-align: center;"><b><u>MENTAH (%)</u></b></p>
                                                            <div id="mentah_wils"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <p style="font-size: 15px; text-align: center;"><b><u>LEWAT MATANG (%)</u></b></p>
                                                            <div id="lewatmatang_wils"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <p style="font-size: 15px; text-align: center;"><b><u>JANGKOS (%)</u></b></p>
                                                            <div id="jangkos_wils"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <p style="font-size: 15px; text-align: center;"><b><u>TIDAK STANDAR V-CUT (%)</u></b></p>
                                                            <div id="tidakvcut_wils"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <p style="font-size: 15px; text-align: center;"><b><u>PENGGUNAAN KARUNG BRONDOLAN (%)</u></b></p>
                                                            <div id="karungbrondolan_wils"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="tab-pane" id="tabGraphs" role="tabpanel">
                                        <div class="d-flex flex-row-reverse justify-content-between align-items-center mr-3">
                                            <button class="btn btn-primary mb-3" id="showDataIns">Show</button>
                                            <div class="d-flex align-items-center">
                                                <div class="mr-2">
                                                    {{csrf_field()}}
                                                    <select class="form-control" id="regDataTahun">
                                                        @foreach($option_reg as $key => $item)
                                                        <option value="{{ $item['id'] }}">{{ $item['nama'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div>
                                                    {{csrf_field()}}
                                                    <select class="form-control" id="yearData" name="yearData">
                                                        @foreach($list_tahun as $item)
                                                        <option value="{{$item}}">{{$item}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>


                                    </div>


                                    <div class="tab-pane" id="tabFinding" role="tabpanel">
                                        <div class="d-flex flex-column flex-md-row justify-content-md-end">
                                            <div class="mb-3 mb-md-0 mr-md-2">
                                                <button class="btn btn-primary" id="showFindingYear">Show</button>
                                            </div>
                                            <div class="mb-3 mb-md-0 mr-md-2">
                                                {{csrf_field()}}
                                                <select class="form-control" id="regFindingYear">
                                                    @foreach($option_reg as $key => $item)
                                                    <option value="{{ $item['id'] }}">{{ $item['nama'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mr-md-2">
                                                {{csrf_field()}}
                                                <select class="form-control" id="yearFinding" name="yearFinding">
                                                    @foreach($list_tahun as $item)
                                                    <option value="{{$item}}">{{$item}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark">
                                            <p style="text-align: center;">MAIN ISSUE FOTO TEMUAN SIDAK PEMERIKSAAN MUTU BUAH DI TPH
                                            </p>
                                        </div>
                                        <div class="ml-4 mr-4">
                                            <div class="row text-center">
                                                <table class="table table-bordered" style="font-size: 13px">
                                                    <thead bgcolor="gainsboro">
                                                        <tr>
                                                            <thclass="align-middle">ESTATE</thclass=>
                                                                <th colspan="5">Temuan Pemeriksaan Panen</th>
                                                                <th rowspan="3" class="align-middle">Foto Temuan</th>
                                                                <!-- <th rowspan="3" class="align-middle">Visit 2</th>
                                                        <th rowspan="3" class="align-middle">Visit 3</th> -->
                                                        </tr>
                                                        <tr>
                                                            <th colspan="5" class="align-middle">Jumlah</th>

                                                        </tr>

                                                    </thead>
                                                    <tbody id="bodyFind">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- bagian data -->

                        <div class=" tab-pane fade" id="nav-data" role="tabpanel" aria-labelledby="nav-data-tab">
                            <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark">
                                <h5><b>DATA</b></h5>
                            </div>
                            <div class="d-flex justify-content-end mt-3 mb-2 ml-3 mr-3" style="padding-top: 20px;">

                                <div class="row w-100">
                                    <div class="col-md-2 offset-md-8">
                                        {{csrf_field()}}
                                        <select class="form-control" id="regional_data">
                                            @foreach($option_reg as $key => $item)
                                            <option value="{{ $item['id'] }}">{{ $item['nama'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                        {{csrf_field()}}
                                        <input class="form-control" value="{{ date('Y-m') }}" type="month" name="inputDateMonth" id="inputDateMonth">
                                    </div>

                                </div>

                                <button class="btn btn-primary mb-3" style="float: right" id="btnShoWeekdata">Show</button>
                                <form id="exportForm" action="{{ route('pdfmutubuhuahdata') }}" method="POST">
                                    @csrf
                                    <input type="hidden" id="getregionalexcel" name="getregionalexcel">
                                    <input type="hidden" id="getdateexcel" name="getdateexcel">
                                    <button type="submit" class="btn btn-primary">Export</button>
                                </form>
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

                            <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark">
                                <div class="table-wrapper">
                                    <table class="my-table">
                                        <thead>
                                            <tr>
                                                <th rowspan="4" class="sticky" style="background-color: #883c0c;">No</th>

                                                <th rowspan="4" class="sticky" rowspan="2" style="background-color: #883c0c;">Est.</th>
                                                <th rowspan="4" class="sticky" rowspan="2" style="background-color: #883c0c;">Afd.</th>
                                                <th rowspan="4" class="sticky" rowspan="2" style="background-color: #883c0c;">Nama Staff</th>
                                                <th colspan="27" class="sticky" style="background-color: #ffc404;">Mutu Buah</th>
                                                <th rowspan="4" class="sticky" style="background-color: #a8a4a4;" rowspan="2">AlL Skor.</th>
                                                <th rowspan="4" class="sticky" style="background-color: #a8a4a4;" rowspan="2">Katagori</th>
                                            </tr>
                                            <tr>
                                                <th rowspan="3" class="sticky-sub" style="background-color: #ffc404; white-space: nowrap;">Total Janjang Sample</th>

                                                <th colspan="7" class="sticky-sub" style="background-color: #ffc404;">Mentah</th>
                                                <th colspan="3" class="sticky-sub" rowspan="2" style="background-color: #ffc404;">Matang</th>
                                                <th colspan="3" class="sticky-sub" rowspan="2" style="background-color: #ffc404;">Lewat Matang (O)</th>
                                                <th colspan="3" class="sticky-sub" rowspan="2" style="background-color: #ffc404;">Janjang Kosong (E)</th>
                                                <th colspan="3" class="sticky-sub" rowspan="2" style="background-color: #ffc404;"> Tidak Standar Vcut</th>
                                                <th colspan="2" class="sticky-sub" rowspan="2" style="background-color: #ffc404;">Abnormal</th>
                                                <th colspan="2" class="sticky-sub" rowspan="2" style="background-color: #ffc404;">Rat Damage</th>
                                                <th colspan="3" class="sticky-sub" rowspan="2" style="background-color: #ffc404;">Penggunaan Karung Brondolan</th>
                                            </tr>
                                            <tr>
                                                <th colspan="2" class="sticky-second-row" style="background-color: #ffc404;">Tanpa Brondol</th>
                                                <th colspan="2" class="sticky-second-row" style="background-color: #ffc404;">Kurang Brondol</th>
                                                <th colspan="3" class="sticky-second-row" style="background-color: #ffc404;">Total</th>
                                            </tr>

                                            <tr>
                                                <th class="sticky-third-row" style="background-color: #ffc404;">Jjg</th>
                                                <th class="sticky-third-row" style="background-color: #ffc404;">%</th>
                                                <th class="sticky-third-row" style="background-color: #ffc404;">Jjg</th>
                                                <th class="sticky-third-row" style="background-color: #ffc404;">%</th>
                                                <th class="sticky-third-row" style="background-color: #ffc404;">Jjg</th>
                                                <th class="sticky-third-row" style="background-color: #ffc404;">%</th>
                                                <th class="sticky-third-row" style="background-color: #ffc404;">Total</th>
                                                <th class="sticky-third-row" style="background-color: #ffc404;">Jjg</th>
                                                <th class="sticky-third-row" style="background-color: #ffc404;">%</th>
                                                <th class="sticky-third-row" style="background-color: #ffc404;">Total</th>
                                                <th class="sticky-third-row" style="background-color: #ffc404;">Jjg</th>
                                                <th class="sticky-third-row" style="background-color: #ffc404;">%</th>
                                                <th class="sticky-third-row" style="background-color: #ffc404;">Total</th>
                                                <th class="sticky-third-row" style="background-color: #ffc404;">Jjg</th>
                                                <th class="sticky-third-row" style="background-color: #ffc404;">%</th>
                                                <th class="sticky-third-row" style="background-color: #ffc404;">Total</th>
                                                <th class="sticky-third-row" style="background-color: #ffc404;">Jjg</th>
                                                <th class="sticky-third-row" style="background-color: #ffc404;">%</th>
                                                <th class="sticky-third-row" style="background-color: #ffc404;">Total</th>
                                                <th class="sticky-third-row" style="background-color: #ffc404;">Jjg</th>
                                                <th class="sticky-third-row" style="background-color: #ffc404;">%</th>
                                                <th class="sticky-third-row" style="background-color: #ffc404;">Jjg</th>
                                                <th class="sticky-third-row" style="background-color: #ffc404;">%</th>
                                                <th class="sticky-third-row" style="background-color: #ffc404;">TPH</th>
                                                <th class="sticky-third-row" style="background-color: #ffc404;">%</th>
                                                <th class="sticky-third-row" style="background-color: #ffc404;">Skor</th>
                                            </tr>
                                        </thead>
                                        <tbody id="data_weekTab2">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class=" tab-pane fade" id="nav-sbi" role="tabpanel" aria-labelledby="nav-sbi-tab">
                            <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark">
                                <h5><b>REKAPITULASI RANKING NILAI SIDAK PEMERIKSAAN MUTU BUAH
                                    </b></h5>
                            </div>
                            <div class="content">
                                <div class="d-flex justify-content-end mt-3 mb-2 ml-3 mr-3" style="padding-top: 20px;">
                                    <div class="row w-100">
                                        <div class="col-md-2 offset-md-8">
                                            {{csrf_field()}}
                                            <select class="form-control" id="reg_sbiThun">
                                                @foreach($option_reg as $key => $item)
                                                <option value="{{ $item['id'] }}">{{ $item['nama'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                            {{csrf_field()}}
                                            <select class="form-control" id="sbi_tahun" name="sbi_tahun">
                                                @foreach($list_tahun as $item)
                                                <option value="{{$item}}">{{$item}}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary mb-3" style="float: right" id="show_sbithn">Show</button>
                                </div>
                                <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 ">
                                    <button id="sort-est-btnSBI">Sort by Afd</button>
                                    <button id="sort-rank-btnSBI">Sort by Rank</button>
                                </div>

                                <div id="tablesContainer">
                                    <div class="tabContainer">
                                        <div class="ml-3 mr-3">
                                            <div class="row text-center">
                                                <div class="col-12 col-md-6 col-lg-3" id="Tabss1">
                                                    <table class=" table table-bordered" style="font-size: 13px" id="table1">
                                                        <thead>
                                                            <tr bgcolor="fffc04">
                                                                <th colspan="5" id="theadsx1" style="text-align:center">WILAYAH I</th>
                                                            </tr>
                                                            <tr bgcolor="2044a4" style="color: white">
                                                                <th rowspan="2">KEBUN</th>
                                                                <th rowspan="2">AFD</th>
                                                                <th rowspan="2">Nama</th>
                                                                <th colspan="2">Todate</th>
                                                            </tr>
                                                            <tr bgcolor="2044a4" style="color: white">
                                                                <th>Score</th>
                                                                <th>Rank</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="tahun1">
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-12 col-md-6 col-lg-3" id="Tabss2">
                                                    <table class=" table table-bordered" style="font-size: 13px" id="table1">
                                                        <thead>
                                                            <tr bgcolor="fffc04">
                                                                <th colspan="5" id="theadsx2" style="text-align:center">WILAYAH II</th>
                                                            </tr>
                                                            <tr bgcolor="2044a4" style="color: white">
                                                                <th rowspan="2">KEBUN</th>
                                                                <th rowspan="2">AFD</th>
                                                                <th rowspan="2">Nama</th>
                                                                <th colspan="2">Todate</th>
                                                            </tr>
                                                            <tr bgcolor="2044a4" style="color: white">
                                                                <th>Score</th>
                                                                <th>Rank</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="tahun2">

                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-12 col-md-6 col-lg-3" id="Tabss3">
                                                    <table class="table table-bordered" style="font-size: 13px" id="Reg3">
                                                        <thead>
                                                            <tr bgcolor="fffc04">
                                                                <th colspan="5" id="theadsx3" style="text-align:center">WILAYAH III</th>
                                                            </tr>
                                                            <tr bgcolor="2044a4" style="color: white">
                                                                <th rowspan="2">KEBUN</th>
                                                                <th rowspan="2">AFD</th>
                                                                <th rowspan="2">Nama</th>
                                                                <th colspan="2">Todate</th>
                                                            </tr>
                                                            <tr bgcolor="2044a4" style="color: white">
                                                                <th>Score</th>
                                                                <th>Rank</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="tahun3">

                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-12 col-md-6 col-lg-3" id="Tabss4">
                                                    <table class="table table-bordered" style="font-size: 13px" id="plasmaID">
                                                        <thead>
                                                            <tr bgcolor="fffc04">
                                                                <th colspan="5" id="theadsx4" style="text-align:center">Plasma</th>
                                                            </tr>
                                                            <tr bgcolor="2044a4" style="color: white">
                                                                <th rowspan="2">KEBUN</th>
                                                                <th rowspan="2">AFD</th>
                                                                <th rowspan="2">Nama</th>
                                                                <th colspan="2">Todate</th>
                                                            </tr>
                                                            <tr bgcolor="2044a4" style="color: white">
                                                                <th>Score</th>
                                                                <th>Rank</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="tahun4">

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-sm-12">
                                    <table class="table table-bordered">
                                        <thead id="tahunreg">
                                            <tr>
                                                <th colspan="1">REG-I</th>
                                                <th colspan="1">RH-1</th>
                                                <th colspan="1">Akhmad Faisyal</th>
                                                <th colspan="8"></th>
                                            </tr>
                                        </thead>

                                    </table>
                                </div>
                                <style>
                                    /* CSS for mobile view */
                                    @media (max-width: 767.98px) {
                                        .mobile-view {
                                            display: flex;
                                            flex-wrap: nowrap;
                                            justify-content: flex-end;
                                        }

                                        .mobile-view .form-container {
                                            flex: 1;
                                            max-width: calc(100% - 90px);
                                        }

                                        .mobile-view .form-control {
                                            width: 100%;
                                            box-sizing: border-box;
                                            margin-left: 10px;
                                        }

                                        .mobile-view .btn {
                                            width: 80px;
                                            margin-left: 10px;
                                        }
                                    }
                                </style>

                                <div class="d-flex flex-row-reverse mr-2 mobile-view">
                                    <button class="btn btn-primary mb-3 ml-3" id="sbiGraphYear">Show</button>
                                    <div class="form-container">
                                        {{ csrf_field() }}
                                        <select class="form-control" name="estSidakYear" id="estSidakYear"></select>
                                        <input type="hidden" id="hiddenInput" name="hiddenInput">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <p style="font-size: 15px; text-align: center;"><b><u>MATANG (%)</u></b></p>
                                                <div id="matang_tahun"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <p style="font-size: 15px; text-align: center;"><b><u>MENTAH (%)</u></b></p>
                                                <div id="mentah_tahun"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <p style="font-size: 15px; text-align: center;"><b><u>LEWAT MATANG (%)</u></b></p>
                                                <div id="lewatmatang_tahun"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <p style="font-size: 15px; text-align: center;"><b><u>JANGKOS (%)</u></b></p>
                                                <div id="jangkos_tahun"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <p style="font-size: 15px; text-align: center;"><b><u>TIDAK STANDAR V-CUT (%)</u></b></p>
                                                <div id="tidakvcut_tahun"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <p style="font-size: 15px; text-align: center;"><b><u>PENGGUNAAN KARUNG BRONDOLAN (%)</u></b></p>
                                                <div id="karungbrondolan_tahun"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>


                            <!-- <a class="nav-item nav-link" id="nav-sbi-tab" data-toggle="tab" href="#nav-sbi" role="tab" aria-controls="nav-sbi" aria-selected="false">SBI</a> -->
                        </div>

                        <div class=" tab-pane fade" id="nav-issue" role="tabpanel" aria-labelledby="nav-issue-tab">
                            <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark">
                                <h5><b>QC PANEN</b></h5>
                            </div>

                            <div class="d-flex justify-content-end mt-3 mb-2 ml-3 mr-3" style="padding-top: 20px;">
                                <div class="row w-100">
                                    <div class="col-md-2 offset-md-8">
                                        {{csrf_field()}}
                                        <select class="form-control" id="regFind">
                                            @foreach($option_reg as $key => $item)
                                            <option value="{{ $item['id'] }}">{{ $item['nama'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                        {{ csrf_field() }}
                                        <input class="form-control" value="{{ date('Y-m') }}" type="month" name="tgl" id="dateFind">
                                    </div>
                                </div>
                                <button class="btn btn-primary mb-3 ml-3" id="showFinding">Show</button>
                            </div>

                            <div class="ml-4 mr-4">
                                <div class="row text-center">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" style="font-size: 13px">
                                            <thead bgcolor="gainsboro">
                                                <tr>
                                                    <th class="align-middle" style="width: 30%;">ESTATE</th>
                                                    <th>Jumlah Temuan Pemeriksaan Panen</th>
                                                    <th class="align-middle" style="width: 30%;"></th>
                                                </tr>
                                            </thead>
                                            <tbody id="bodyIssue">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </section>


        @if (can_edit_all_atasan())
        <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmationModalLabel">Terdeteksi data duplicate</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-primary">
                            <thead>
                                <tr>
                                    <th>id</th>
                                    <th>Estate</th>
                                    <th>Afdeling</th>
                                    <th>blok</th>
                                    <th>datetime</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($check_data as $items)
                                <tr>
                                    <td>{{$items['id']}}</td>
                                    <td>{{$items['estate']}}</td>
                                    <td>{{$items['afdeling']}}</td>
                                    <td>{{$items['blok']}}</td>
                                    <td>{{$items['datetime']}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        Apakah anda ingin menghapus?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                        <button type="button" class="btn btn-primary" id="confirmBtn">Yes</button>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>

    <script type="module">
        // untuk buat table data bisa d scroll dengan mouse
        document.addEventListener("DOMContentLoaded", function() {
            const tableWrapper = document.querySelector(".table-wrapper");
            let isMouseDown = false;
            let startX, scrollLeft;

            tableWrapper.addEventListener("mousedown", (e) => {
                isMouseDown = true;
                startX = e.pageX - tableWrapper.offsetLeft;
                scrollLeft = tableWrapper.scrollLeft;
                tableWrapper.style.cursor = "grabbing";
            });

            tableWrapper.addEventListener("mouseleave", () => {
                isMouseDown = false;
                tableWrapper.style.cursor = "default";
            });

            tableWrapper.addEventListener("mouseup", () => {
                isMouseDown = false;
                tableWrapper.style.cursor = "default";
            });

            tableWrapper.addEventListener("mousemove", (e) => {
                if (!isMouseDown) return;
                e.preventDefault();
                const x = e.pageX - tableWrapper.offsetLeft;
                const walk = (x - startX) * 2;
                tableWrapper.scrollLeft = scrollLeft - walk;
            });
        });

        ////untuk mode single and full mode
        let checkdata = @json($check);
        let recordsdupt = @json($idduplicate);

        // console.log(checkdata);
        // console.log(recordsdupt);
        if (checkdata === 'ada') {
            const modalElement = document.getElementById('confirmationModal');
            if (modalElement) {
                new bootstrap.Modal(modalElement).show();
            }
            // Attach a click event to the "Yes" button
            $('#confirmBtn').on('click', function() {
                // User clicked 'Yes', proceed with your actions
                let type = 'sidakmtb'

                // Hide the Bootstrap modal
                var _token = $('input[name="_token"]').val();

                $.ajax({
                    url: '{{ route("duplicatesidakmtb") }}', // Replace with your actual endpoint URL
                    type: 'post',
                    data: {
                        data: recordsdupt,
                        type: type,
                    },
                    headers: {
                        'X-CSRF-TOKEN': _token
                    },
                    success: function(response) {
                        if (response.success) {
                            // Show success alert
                            alert('Data berhasil dihapus');
                            // Reload the page
                            location.reload();
                        } else {
                            // Show error alert
                            alert('Gagal menghapus data');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });

                new bootstrap.Modal(modalElement).hide();

            });
        }




        var currentMode = 'all';
        var lokasiKerja = "{{ session('lok') }}";
        var isTableHeaderModified = false;
        $(document).ready(function() {
            const defaultRegional = $('#default_regional').val();

            // Set the regional select value
            $('#reg_sbiThun').val(defaultRegional);
            $('#regionalData').val(defaultRegional);
            $('#regional_data').val(defaultRegional);
            if (lokasiKerja == 'Regional II' && !isTableHeaderModified) {
                $('#regionalPanen').val('2');
                $('#regionalDataweek').val('2');
                $('#regionalData').val('2');
                $('#regDataIns').val('2');
                $('#regFind').val('2');
                $('#regGrafik').val('2');
                $('#reg_sbiThun').val('2');
                $('#regional_data').val('2');

                const thElement1 = document.getElementById('thead1');
                const thElement2 = document.getElementById('thead2');
                const thElement3 = document.getElementById('thead3');
                const thElement4 = document.getElementById('theadx3');
                const thElement1x = document.getElementById('theadsx1');
                const thElement2x = document.getElementById('theadsx2');
                const thElement3x = document.getElementById('theadsx3');
                const thElement4x = document.getElementById('theadsx4');
                const thElement1xx = document.getElementById('theads1');
                const thElement2xx = document.getElementById('theads2');
                const thElement3xx = document.getElementById('theads3');
                const thElement4xx = document.getElementById('theads4');
                thElement1.textContent = 'WILAYAH IV';
                thElement2.textContent = 'WILAYAH V';
                thElement3.textContent = 'WILAYAH VI';
                thElement4.textContent = 'PLASMA II';
                thElement1x.textContent = 'WILAYAH IV';
                thElement2x.textContent = 'WILAYAH V';
                thElement3x.textContent = 'WILAYAH VI';
                thElement4x.textContent = 'PLASMA II';
                thElement1xx.textContent = 'WILAYAH IV';
                thElement2xx.textContent = 'WILAYAH V';
                thElement3xx.textContent = 'WILAYAH VI';
                thElement4xx.textContent = 'PLASMA II';

                thElement1.classList.add("text-center");
                thElement2.classList.add("text-center");
                thElement3.classList.add("text-center");
                thElement4.classList.add("text-center");
                thElement1x.classList.add("text-center");
                thElement2x.classList.add("text-center");
                thElement3x.classList.add("text-center");
                thElement4x.classList.add("text-center");
                thElement1xx.classList.add("text-center");
                thElement2xx.classList.add("text-center");
                thElement3xx.classList.add("text-center");
                thElement4xx.classList.add("text-center");

                const nons = document.getElementById("Tab1");
                const nonx = document.getElementById("Tab2");
                const llon = document.getElementById("Tab3");
                const non = document.getElementById("Tab4");
                const tahun1 = document.getElementById("Tabsx1");
                const tahun2 = document.getElementById("Tabsx2");
                const tahun3 = document.getElementById("Tabsx3");
                const tahun4 = document.getElementById("Tabsx4");
                const sbi1 = document.getElementById("Tabss1");
                const sbi2 = document.getElementById("Tabss2");
                const sbi3 = document.getElementById("Tabss3");
                const sbi4 = document.getElementById("Tabss4");

                function resetClassList(element) {
                    element.classList.remove("col-md-6", "col-lg-3", "col-lg-4", "col-lg-6");
                    element.classList.add("col-md-6");
                }


                non.style.display = "none";
                // resetClassList(llon);
                resetClassList(non);
                llon.classList.add("col-lg-4");
                nons.classList.add("col-lg-4");
                nonx.classList.add("col-lg-4");


                tahun4.style.display = "none";
                // resetClassList(tahun3);
                resetClassList(tahun4);
                tahun3.classList.add("col-lg-4");
                tahun1.classList.add("col-lg-4");
                tahun2.classList.add("col-lg-4");



                sbi4.style.display = "none";
                // resetClassList(sbi3);
                resetClassList(sbi4);
                sbi3.classList.add("col-lg-4");
                sbi1.classList.add("col-lg-4");
                sbi2.classList.add("col-lg-4");




            } else if ((lokasiKerja == 'Regional III' || lokasiKerja == 'Regional 3') && !isTableHeaderModified) {
                $('#regionalPanen').val('3');
                $('#regionalDataweek').val('3');
                $('#regionalData').val('3');
                $('#regDataIns').val('3');
                $('#regFind').val('3');
                $('#regGrafik').val('3');
                $('#reg_sbiThun').val('3');
                $('#regional_data').val('3');


                const thElement3x = document.getElementById('theadsx3');
                const thElement4x = document.getElementById('theadsx4');
                const thElement1xx = document.getElementById('theads1');
                const thElement2xx = document.getElementById('theads2');
                const thElement3xx = document.getElementById('theads3');
                const thElement4xx = document.getElementById('theads4');

                const nons = document.getElementById("Tab1");
                const nonx = document.getElementById("Tab2");
                const llon = document.getElementById("Tab3");
                const non = document.getElementById("Tab4");
                const tahun1 = document.getElementById("Tabsx1");
                const tahun2 = document.getElementById("Tabsx2");
                const tahun3 = document.getElementById("Tabsx3");
                const tahun4 = document.getElementById("Tabsx4");
                const sbi1 = document.getElementById("Tabss1");
                const sbi2 = document.getElementById("Tabss2");
                const sbi3 = document.getElementById("Tabss3");
                const sbi4 = document.getElementById("Tabss4");

                function resetClassList(element) {
                    element.classList.remove("col-md-6", "col-lg-3", "col-lg-4", "col-lg-6");
                    element.classList.add("col-md-6");
                }


                non.style.display = "none";
                llon.style.display = "none";
                // resetClassList(llon);
                resetClassList(non);
                resetClassList(llon);
                // llon.classList.add("col-lg-4");
                nons.classList.add("col-lg-6");
                nonx.classList.add("col-lg-6");


                tahun3.style.display = "none";
                tahun4.style.display = "none";
                // resetClassList(tahun3);
                resetClassList(tahun3);
                resetClassList(tahun4);
                // tahun3.classList.add("col-lg-4");
                tahun1.classList.add("col-lg-6");
                tahun2.classList.add("col-lg-6");



                sbi3.style.display = "none";
                sbi4.style.display = "none";
                // resetClassList(sbi3);
                resetClassList(sbi3);
                resetClassList(sbi4);
                // sbi3.classList.add("col-lg-4");
                sbi1.classList.add("col-lg-6");
                sbi2.classList.add("col-lg-6");

                const thElement1 = document.getElementById('thead1');
                const thElement2 = document.getElementById('thead2');
                const thElement1x = document.getElementById('thead3');
                const thElement2x = document.getElementById('theadx3');
                const theads1 = document.getElementById('theads1');
                const theads2 = document.getElementById('theads2');
                const theads3 = document.getElementById('theads3');
                const theads4 = document.getElementById('theads4');
                const theadsx1 = document.getElementById('theadsx1');
                const theadsx2 = document.getElementById('theadsx2');
                const theadsx3 = document.getElementById('theadsx3');
                const theadsx4 = document.getElementById('theadsx4');

                thElement1.textContent = 'WILAYAH VII';
                thElement2.textContent = 'WILAYAH VII';
                thElement1x.textContent = 'Plasma III';
                thElement2x.textContent = 'Plasma III';

                thElement1.classList.add("text-center");
                thElement2.classList.add("text-center");
                thElement1x.classList.add("text-center");
                thElement2x.classList.add("text-center");

                theads1.textContent = 'WILAYAH VII';
                theads2.textContent = 'WILAYAH VII';
                theads3.textContent = 'Plasma III';
                theads3.textContent = 'Plasma III';

                theads1.classList.add("text-center");
                theads2.classList.add("text-center");
                theads3.classList.add("text-center");
                theads3.classList.add("text-center");

                theadsx1.textContent = 'WILAYAH VII';
                theadsx2.textContent = 'WILAYAH VII';
                theadsx3.textContent = 'Plasma III';
                theadsx3.textContent = 'Plasma III';

                theadsx1.classList.add("text-center");
                theadsx2.classList.add("text-center");
                theadsx3.classList.add("text-center");
                theadsx3.classList.add("text-center");

            } else if ((lokasiKerja == 'Regional IV' || lokasiKerja == 'Regional 4') && !isTableHeaderModified) {
                $('#regionalPanen').val('4');
                $('#regionalDataweek').val('4');
                $('#regionalData').val('4');
                $('#regDataIns').val('4');
                $('#regFind').val('4');
                $('#regGrafik').val('4');
                $('#reg_sbiThun').val('4');
                $('#regional_data').val('4');


                const nons = document.getElementById("Tab1");
                const nonx = document.getElementById("Tab2");
                const llon = document.getElementById("Tab3");
                const non = document.getElementById("Tab4");
                const tahun1 = document.getElementById("Tabsx1");
                const tahun2 = document.getElementById("Tabsx2");
                const tahun3 = document.getElementById("Tabsx3");
                const tahun4 = document.getElementById("Tabsx4");
                const sbi1 = document.getElementById("Tabss1");
                const sbi2 = document.getElementById("Tabss2");
                const sbi3 = document.getElementById("Tabss3");
                const sbi4 = document.getElementById("Tabss4");


                function resetClassList(element) {
                    element.classList.remove("col-md-6", "col-lg-3", "col-lg-4", "col-lg-6");
                    element.classList.add("col-md-6");
                }

                llon.style.display = "none";
                non.style.display = "none";
                resetClassList(llon);
                resetClassList(non);
                nons.classList.add("col-lg-6");
                nonx.classList.add("col-lg-6");

                tahun3.style.display = "none";
                tahun4.style.display = "none";
                resetClassList(tahun3);
                resetClassList(tahun4);
                tahun1.classList.add("col-lg-6");
                tahun2.classList.add("col-lg-6");


                sbi3.style.display = "none";
                sbi4.style.display = "none";
                resetClassList(sbi3);
                resetClassList(sbi4);
                sbi1.classList.add("col-lg-6");
                sbi2.classList.add("col-lg-6");

                const thElement1 = document.getElementById('thead1');
                const thElement2 = document.getElementById('thead2');
                const thElement1x = document.getElementById('theads1');
                const thElement2x = document.getElementById('theads2');

                thElement1.textContent = 'WILAYAH Inti';
                thElement2.textContent = 'WILAYAH Plasma';
                thElement1x.textContent = 'WILAYAH Inti';
                thElement2x.textContent = 'WILAYAH Plasma';

                thElement1.classList.add("text-center");
                thElement2.classList.add("text-center");
                thElement1x.classList.add("text-center");
                thElement2x.classList.add("text-center");
            } else if ((lokasiKerja == 'Regional I' || lokasiKerja == 'Regional 1') && !isTableHeaderModified) {
                $('#regionalPanen').val('1');
                $('#regionalDataweek').val('1');
                $('#regionalData').val('1');
                $('#regDataIns').val('1');
                $('#regFind').val('1');
                $('#regGrafik').val('1');
                $('#reg_sbiThun').val('1');
                $('#regional_data').val('1');



                const nons = document.getElementById("Tab1");
                const nonx = document.getElementById("Tab2");
                const llon = document.getElementById("Tab3");
                const non = document.getElementById("Tab4");
                const tahun1 = document.getElementById("Tabsx1");
                const tahun2 = document.getElementById("Tabsx2");
                const tahun3 = document.getElementById("Tabsx3");
                const tahun4 = document.getElementById("Tabsx4");
                const sbi1 = document.getElementById("Tabss1");
                const sbi2 = document.getElementById("Tabss2");
                const sbi3 = document.getElementById("Tabss3");
                const sbi4 = document.getElementById("Tabss4");

                function resetClassList(element) {
                    element.classList.remove("col-md-6", "col-lg-3", "col-lg-4", "col-lg-6");
                    element.classList.add("col-md-6");
                }


                non.style.display = "none";
                // resetClassList(llon);
                resetClassList(non);
                llon.classList.add("col-lg-4");
                nons.classList.add("col-lg-4");
                nonx.classList.add("col-lg-4");


                tahun4.style.display = "none";
                // resetClassList(tahun3);
                resetClassList(tahun4);
                tahun3.classList.add("col-lg-4");
                tahun1.classList.add("col-lg-4");
                tahun2.classList.add("col-lg-4");



                sbi4.style.display = "none";
                // resetClassList(sbi3);
                resetClassList(sbi4);
                sbi3.classList.add("col-lg-4");
                sbi1.classList.add("col-lg-4");
                sbi2.classList.add("col-lg-4");

            }

            isTableHeaderModified = true;
            getweek()
            dashboard_tahun()
            // dashboardData_tahun() 
            dashboardFindingYear()
            getweekData()
            sbi_tahun()
            getFindData()

            function handleSelectChange() {
                var regdata = $('#reg_sbiThun').val();

                optsbireg(regdata)
                // handleAjaxRequest(selectedValue, currentDate);
            }

            // Trigger the change event on page load
            handleSelectChange();

            // Bind the change event to the select element
            $('#reg_sbiThun').on('change', function() {
                handleSelectChange();
            });

        });

        function optsbireg(regdata) {

            $.ajax({
                url: '{{ route("getestatesidakmtbuah") }}', // Replace with your actual endpoint URL
                type: 'GET',
                data: {
                    reg: regdata,
                },
                success: function(data) {
                    var parseResult = JSON.parse(data);
                    var est = parseResult['est'];

                    // Assuming "est" is an array of options
                    var selectElement = document.getElementById("estSidakYear");

                    // Clear existing options (if any)
                    selectElement.innerHTML = "";

                    // Iterate through the array and create options
                    est.forEach(function(optionValue) {
                        var option = document.createElement("option");
                        option.value = optionValue;
                        option.text = optionValue;
                        selectElement.appendChild(option);
                    });

                    // Log the populated select element for verification
                    // console.log(selectElement);
                },

                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }


        const c = document.getElementById('btnShow');
        const o = document.getElementById('regionalPanen');
        const s = document.getElementById("Tab1");
        const m = document.getElementById("Tab2");
        const l = document.getElementById("Tab3");
        const n = document.getElementById("Tab4");
        const thElement1 = document.getElementById('thead1');
        const thElement2 = document.getElementById('thead2');
        const thElement3 = document.getElementById('thead3');
        const thElement4 = document.getElementById('theadx3');

        function resetClassList(element) {
            element.classList.remove("col-md-6", "col-lg-3", "col-lg-4", "col-lg-6");
            element.classList.add("col-md-6");
        }

        c.addEventListener('click', function() {
            const c = o.value;
            if (c === '1') {
                s.style.display = "";
                m.style.display = "";
                l.style.display = "";
                n.style.display = "none";

                resetClassList(s);
                resetClassList(m);
                resetClassList(l);


                thElement1.textContent = 'WILAYAH I';
                thElement2.textContent = 'WILAYAH II';
                thElement3.textContent = 'WILAYAH III';


                thElement1.classList.add("text-center");
                thElement2.classList.add("text-center");
                thElement3.classList.add("text-center");


                s.classList.add("col-lg-4");
                m.classList.add("col-lg-4");
                l.classList.add("col-lg-4");

            } else if (c === '2') {
                s.style.display = "";
                m.style.display = "";
                l.style.display = "";
                n.style.display = "none";

                resetClassList(s);
                resetClassList(m);
                resetClassList(l);
                resetClassList(n);


                thElement1.textContent = 'WILAYAH IV';
                thElement2.textContent = 'WILAYAH V';
                thElement3.textContent = 'WILAYAH VI';
                thElement4.textContent = 'PLASMA II';

                thElement1.classList.add("text-center");
                thElement2.classList.add("text-center");
                thElement3.classList.add("text-center");
                thElement4.classList.add("text-center");


                s.classList.add("col-lg-4");
                m.classList.add("col-lg-4");
                l.classList.add("col-lg-4");

            } else if (c === '3') {
                s.style.display = "";
                m.style.display = "";
                l.style.display = "none";
                n.style.display = "none";

                resetClassList(s);
                resetClassList(m);


                thElement1s.textContent = 'WILAYAH VII';
                thElement2s.textContent = 'WILAYAH VIII';

                thElement1.classList.add("text-center");
                thElement2.classList.add("text-center");

                s.classList.add("col-lg-6");
                m.classList.add("col-lg-6");
            } else if (c === '4') {
                s.style.display = "";
                m.style.display = "";
                l.style.display = "none";
                n.style.display = "none";

                resetClassList(s);
                resetClassList(m);


                thElement1.textContent = 'WILAYAH Inti';
                thElement2.textContent = 'WILAYAH Plasma';

                thElement1.classList.add("text-center");
                thElement2.classList.add("text-center");


                s.classList.add("col-lg-6");
                m.classList.add("col-lg-6");

            }
        });

        const cs = document.getElementById('show_sbithn');
        const os = document.getElementById('reg_sbiThun');
        const ss = document.getElementById("Tabss1");
        const ms = document.getElementById("Tabss2");
        const ls = document.getElementById("Tabss3");
        const ns = document.getElementById("Tabss4");
        const thElement1s = document.getElementById('theadsx1');
        const thElement2s = document.getElementById('theadsx2');
        const thElement3s = document.getElementById('theadsx3');
        const thElement4s = document.getElementById('theadsx4');


        cs.addEventListener('click', function() {
            const cs = os.value;
            if (cs === '1') {
                ss.style.display = "";
                ms.style.display = "";
                ls.style.display = "";
                ns.style.display = "none";

                resetClassList(ss);
                resetClassList(ms);
                resetClassList(ls);


                thElement1s.textContent = 'WILAYAH I';
                thElement2s.textContent = 'WILAYAH II';
                thElement3s.textContent = 'WILAYAH III';


                thElement1s.classList.add("text-center");
                thElement2s.classList.add("text-center");
                thElement3s.classList.add("text-center");


                ss.classList.add("col-lg-4");
                ms.classList.add("col-lg-4");
                ls.classList.add("col-lg-4");

            } else if (cs === '2') {
                ss.style.display = "";
                ms.style.display = "";
                ls.style.display = "";
                ns.style.display = "none";

                resetClassList(ss);
                resetClassList(ms);
                resetClassList(ls);

                thElement1s.textContent = 'WILAYAH IV';
                thElement2s.textContent = 'WILAYAH V';
                thElement3s.textContent = 'WILAYAH VI';
                thElement4s.textContent = 'Plasma2';

                thElement1s.classList.add("text-center");
                thElement2s.classList.add("text-center");
                thElement3s.classList.add("text-center");


                ss.classList.add("col-lg-4");
                ms.classList.add("col-lg-4");
                ls.classList.add("col-lg-4");

            } else if (cs === '3') {
                ss.style.display = "";
                ms.style.display = "";
                ls.style.display = "none";
                ns.style.display = "none";

                resetClassList(ss);
                resetClassList(ms);



                thElement1s.textContent = 'WILAYAH VII';
                thElement2s.textContent = 'WILAYAH VIII';


                thElement1s.classList.add("text-center");
                thElement2s.classList.add("text-center");


                ss.classList.add("col-lg-6");
                ms.classList.add("col-lg-6");
            } else if (cs === '4') {
                ss.style.display = "";
                ms.style.display = "";
                ls.style.display = "none";
                ns.style.display = "none";

                resetClassList(ss);
                resetClassList(ms);


                thElement1s.textContent = 'WILAYAH Inti';
                thElement2s.textContent = 'WILAYAH Plasma';

                thElement1s.classList.add("text-center");
                thElement2s.classList.add("text-center");


                ss.classList.add("col-lg-6");
                ms.classList.add("col-lg-6");

            }
        });


        //tampilakn filter perweek
        let btnShow;

        document.getElementById('btnShow').onclick = function() {
            btnShow = showLoadingSwal();
            // console.log('aaa');
            getweek()
        }
        let showFinding;

        document.getElementById('showFinding').onclick = function() {
            showFinding = showLoadingSwal();
            // console.log('aaa');
            getFindData()
        }

        var options = {

            series: [{
                name: '',
                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
            }],
            chart: {
                background: '#ffffff',
                height: 350,
                type: 'bar'
            },
            plotOptions: {
                bar: {
                    distributed: true
                }
            },

            colors: [
                '#00FF00',
                '#00FF00',
                '#00FF00',
                '#00FF00',
                '#3063EC',
                '#3063EC',
                '#3063EC',
                '#3063EC',
                '#FF8D1A',
                '#FF8D1A',
                '#FF8D1A',
                '#FF8D1A',
                '#00ffff'
            ],

            stroke: {
                curve: 'smooth'
            },
            xaxis: {
                type: '',
                // categories: estate
                categories: ['-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-']
            }
        };

        var options_wil = {

            series: [{
                name: '',
                data: [0, 0, 0]
            }],
            chart: {
                background: '#ffffff',
                height: 350,
                type: 'bar'
            },
            plotOptions: {
                bar: {
                    distributed: true
                }
            },

            colors: [
                '#00FF00',
                '#00FF00',
                '#00FF00',
                '#00FF00',
                '#3063EC',
                '#3063EC',
                '#3063EC',
                '#3063EC',
                '#FF8D1A',
                '#FF8D1A',
                '#FF8D1A',
                '#FF8D1A',
                '#00ffff'
            ],

            stroke: {
                curve: 'smooth'
            },
            xaxis: {
                type: '',
                // categories: estate
                categories: [1, 2, 3]
            }
        };




        var chart = new ApexCharts(document.querySelector("#matang"), options);
        var chartx = new ApexCharts(document.querySelector("#mentah"), options);
        var charts = new ApexCharts(document.querySelector("#lewatmatang"), options);
        var chartc = new ApexCharts(document.querySelector("#jangkos"), options);
        var chartv = new ApexCharts(document.querySelector("#tidakvcut"), options);
        var chartb = new ApexCharts(document.querySelector("#karungbrondolan"), options);

        var chart_wil = new ApexCharts(document.querySelector("#matang_wil"), options_wil);
        var chartx_wil = new ApexCharts(document.querySelector("#mentah_wil"), options_wil);
        var charts_wil = new ApexCharts(document.querySelector("#lewatmatang_wil"), options_wil);
        var chartc_wil = new ApexCharts(document.querySelector("#jangkos_wil"), options_wil);
        var chartv_wil = new ApexCharts(document.querySelector("#tidakvcut_wil"), options_wil);
        var chartb_wil = new ApexCharts(document.querySelector("#karungbrondolan_wil"), options_wil);

        chart.render();
        chartx.render();
        charts.render();
        chartc.render();
        chartv.render();
        chartb.render();

        chart_wil.render();
        chartx_wil.render();
        charts_wil.render();
        chartc_wil.render();
        chartv_wil.render();
        chartb_wil.render();

        function getweek() {
            const week1 = $("#week1");
            const week2 = $("#week2");
            const week3 = $("#week3");
            const plasma1 = $("#plasma1");
            const theadreg = $("#theadreg");

            if (week1.length) week1.empty();
            if (week2.length) week2.empty();
            if (week3.length) week3.empty();
            if (plasma1.length) plasma1.empty();
            if (theadreg.length) theadreg.empty();

            var reg = '';

            var bulan = '';
            var reg = document.getElementById('regionalPanen').value;

            var bulan = document.getElementById('inputbulan').value;
            var _token = $('input[name="_token"]').val();

            $.ajax({
                url: "{{ route('getWeek') }}",
                method: "GET",
                data: {
                    reg: reg,
                    bulan: bulan,
                    _token: _token
                },
                headers: {
                    'X-CSRF-TOKEN': _token
                },
                success: function(result) {

                    Swal.close();
                    var parseResult = JSON.parse(result)
                    var region = Object.entries(parseResult['listregion'])
                    var rekapmua = parseResult['sidak_buah_mua']
                    var mutu_buah = Object.entries(parseResult['mutu_buah'])
                    var mutubuah_est = Object.entries(parseResult['mutubuah_est'])
                    var mutuBuah_wil = Object.entries(parseResult['mutuBuah_wil'])
                    var regIonal = Object.entries(parseResult['regional'])
                    var regionaltab = Object.entries(parseResult['regionaltab'])
                    var queryAsisten = Object.entries(parseResult['queryAsisten'])

                    var chart_matang = Object.entries(parseResult['chart_matang'])
                    var chart_mentah = Object.entries(parseResult['chart_mentah'])
                    var chart_lewatmatang = Object.entries(parseResult['chart_lewatmatang'])
                    var chart_janjangkosong = Object.entries(parseResult['chart_janjangkosong'])
                    var chart_vcut = Object.entries(parseResult['chart_vcut'])
                    var chart_karung = Object.entries(parseResult['chart_karung'])

                    var chart_matangwil = Object.entries(parseResult['chart_matangwil'])
                    var chart_mentahwil = Object.entries(parseResult['chart_mentahwil'])
                    var chart_lewatmatangwil = Object.entries(parseResult['chart_lewatmatangwil'])
                    var chart_janjangkosongwil = Object.entries(parseResult['chart_janjangkosongwil'])
                    var chart_vcutwil = Object.entries(parseResult['chart_vcutwil'])
                    var chart_karungwil = Object.entries(parseResult['chart_karungwil'])
                    var optionREg = Object.entries(parseResult['optionREg'])
                    // console.log(chart_matang);
                    // console.log(mutu_buah);
                    var matang_Wil = '['
                    if (chart_matangwil.length > 0) {
                        chart_matangwil.forEach(element => {
                            matang_Wil += '"' + element[1] + '",'
                        });
                        matang_Wil = matang_Wil.substring(0, matang_Wil.length - 1);
                    }
                    matang_Wil += ']'
                    var mentah_wil = '['
                    if (chart_mentahwil.length > 0) {
                        chart_mentahwil.forEach(element => {
                            mentah_wil += '"' + element[1] + '",'
                        });
                        mentah_wil = mentah_wil.substring(0, mentah_wil.length - 1);
                    }
                    mentah_wil += ']'
                    var lewatmatangs_wil = '['
                    if (chart_lewatmatangwil.length > 0) {
                        chart_lewatmatangwil.forEach(element => {
                            lewatmatangs_wil += '"' + element[1] + '",'
                        });
                        lewatmatangs_wil = lewatmatangs_wil.substring(0, lewatmatangs_wil.length - 1);
                    }
                    lewatmatangs_wil += ']'

                    var jjgkosongs_wil = '['
                    if (chart_janjangkosongwil.length > 0) {
                        chart_janjangkosongwil.forEach(element => {
                            jjgkosongs_wil += '"' + element[1] + '",'
                        });
                        jjgkosongs_wil = jjgkosongs_wil.substring(0, jjgkosongs_wil.length - 1);
                    }
                    jjgkosongs_wil += ']'

                    var vcuts_wil = '['
                    if (chart_vcutwil.length > 0) {
                        chart_vcutwil.forEach(element => {
                            vcuts_wil += '"' + element[1] + '",'
                        });
                        vcuts_wil = vcuts_wil.substring(0, vcuts_wil.length - 1);
                    }
                    vcuts_wil += ']'
                    var karungs_wil = '['
                    if (chart_karungwil.length > 0) {
                        chart_karungwil.forEach(element => {
                            karungs_wil += '"' + element[1] + '",'
                        });
                        karungs_wil = karungs_wil.substring(0, karungs_wil.length - 1);
                    }
                    karungs_wil += ']'

                    // console.log(matang_Wil);
                    let regInpt = reg;

                    var wilayah = '['
                    region.forEach(element => {
                        wilayah += '"' + element + '",'
                    });
                    wilayah = wilayah.substring(0, wilayah.length - 1);
                    wilayah += ']'

                    // console.log(chart_matang);
                    var matang = '['
                    if (chart_matang.length > 0) {
                        chart_matang.forEach(element => {
                            matang += '"' + element[1] + '",'
                        });
                        matang = matang.substring(0, matang.length - 1);
                    }
                    matang += ']'

                    var mentah = '['
                    if (chart_mentah.length > 0) {
                        chart_mentah.forEach(element => {
                            mentah += '"' + element[1] + '",'
                        });
                        mentah = mentah.substring(0, mentah.length - 1);
                    }
                    mentah += ']'

                    var lewatmatangs = '['
                    if (chart_lewatmatang.length > 0) {
                        chart_lewatmatang.forEach(element => {
                            lewatmatangs += '"' + element[1] + '",'
                        });
                        lewatmatangs = lewatmatangs.substring(0, lewatmatangs.length - 1);
                    }
                    lewatmatangs += ']'

                    var jjgkosongs = '['
                    if (chart_janjangkosong.length > 0) {
                        chart_janjangkosong.forEach(element => {
                            jjgkosongs += '"' + element[1] + '",'
                        });
                        jjgkosongs = jjgkosongs.substring(0, jjgkosongs.length - 1);
                    }
                    jjgkosongs += ']'

                    var vcuts = '['
                    if (chart_vcut.length > 0) {
                        chart_vcut.forEach(element => {
                            vcuts += '"' + element[1] + '",'
                        });
                        vcuts = vcuts.substring(0, vcuts.length - 1);
                    }
                    vcuts += ']'

                    var karungs = '['
                    if (chart_karung.length > 0) {
                        chart_karung.forEach(element => {
                            karungs += '"' + element[1] + '",'
                        });
                        karungs = karungs.substring(0, karungs.length - 1);
                    }
                    karungs += ']'

                    var estate = JSON.parse(wilayah)
                    var matang_chart = JSON.parse(matang)
                    var matang_chart_wil = JSON.parse(matang_Wil)
                    var mentah_chart = JSON.parse(mentah)
                    var mentah_chart_wil = JSON.parse(mentah_wil)

                    var lwtmatang_chart = JSON.parse(lewatmatangs)
                    var lwtmatang_chart_wil = JSON.parse(lewatmatangs_wil)
                    var janjangksng_chart = JSON.parse(jjgkosongs)
                    var janjangksng_chart_wil = JSON.parse(jjgkosongs_wil)
                    var vcuts_chart = JSON.parse(vcuts)
                    var vcuts_chart_wil = JSON.parse(vcuts_wil)
                    var karungs_chart = JSON.parse(karungs)
                    var karungs_chart_wil = JSON.parse(karungs_wil)



                    const formatEst = estate.map((item) => item.split(',')[1]);
                    let plasma1Index = formatEst.indexOf("Plasma1");

                    if (plasma1Index !== -1) {
                        formatEst.splice(plasma1Index, 1);
                        formatEst.push("Plasma1");
                    }

                    // console.log(formatEst);

                    let colors = '';


                    if (regInpt === '1') {
                        colors = ['#00FF00',
                            '#00FF00',
                            '#00FF00',
                            '#00FF00',
                            '#3063EC',
                            '#3063EC',
                            '#3063EC',
                            '#3063EC',
                            '#FF8D1A',
                            '#FF8D1A',
                            '#FF8D1A',
                            '#FF8D1A',
                            '#00ffff'
                        ]

                    } else if (regInpt === '2') {
                        colors = ['#00FF00',
                            '#00FF00',
                            '#00FF00',
                            '#00FF00',
                            '#3063EC',
                            '#3063EC',
                            '#3063EC',
                            '#00ffff',
                            '#00ffff'
                        ]


                    } else if (regInpt === '3') {
                        colors = ['#00FF00',
                            '#00FF00',
                            '#00FF00',
                            '#00FF00',
                            '#3063EC',
                            '#3063EC',
                            '#3063EC',
                            '#3063EC',
                        ]
                    } else if (regInpt === '4') {
                        colors = ['#00FF00',
                            '#00FF00',
                            '#00FF00',
                            '#3063EC',
                            '#3063EC',

                        ]
                    }

                    // console.log(matang_chart);
                    chart.updateSeries([{
                        name: 'matang',
                        data: matang_chart,

                    }])
                    chart.updateOptions({
                        xaxis: {
                            categories: formatEst
                        },
                        colors: colors // Set the colors directly, no need for an object
                    })
                    ///////////
                    chartx.updateSeries([{
                        name: 'mentah',
                        data: mentah_chart,

                    }])
                    chartx.updateOptions({
                        xaxis: {
                            categories: formatEst
                        },
                        colors: colors // Set the colors directly, no need for an object
                    })
                    ///////////
                    charts.updateSeries([{
                        name: 'lewat matang',
                        data: lwtmatang_chart,

                    }])
                    charts.updateOptions({
                        xaxis: {
                            categories: formatEst
                        },
                        colors: colors // Set the colors directly, no need for an object
                    })
                    /////////
                    chartc.updateSeries([{
                        name: 'janjang kosong',
                        data: janjangksng_chart,

                    }])
                    chartc.updateOptions({
                        xaxis: {
                            categories: formatEst
                        },
                        colors: colors // Set the colors directly, no need for an object
                    })
                    ////////
                    chartv.updateSeries([{
                        name: 'vcut ',
                        data: vcuts_chart,

                    }])
                    chartv.updateOptions({
                        xaxis: {
                            categories: formatEst
                        },
                        colors: colors // Set the colors directly, no need for an object
                    })
                    ///////
                    chartb.updateSeries([{
                        name: 'karung',
                        data: karungs_chart,

                    }])
                    chartb.updateOptions({
                        xaxis: {
                            categories: formatEst
                        },
                        colors: colors // Set the colors directly, no need for an object
                    })


                    // Declare wil_est variable before using it
                    var wil_est;
                    var warna; // Also declare the warna variable

                    if (regInpt === '1') {
                        wil_est = ['I', 'II', 'III'];
                        warna = ['#00FF00', '#FF8D1A', '#00ffff'];
                    } else if (regInpt === '2') {
                        wil_est = ['IV', 'V', 'VI'];
                        warna = ['#00FF00', '#FF8D1A', '#00ffff'];
                    } else if (regInpt === '3') {
                        wil_est = ['VII', 'VIII'];
                        warna = ['#00FF00', '#00ffff'];
                    } else {
                        wil_est = ['IX', 'X'];
                        warna = ['#00FF00', '#00ffff'];
                    }

                    // Now you can use wil_est and warna in your code without getting a ReferenceError

                    chart_wil.updateSeries([{
                        name: 'matang',
                        data: matang_chart_wil,

                    }])
                    chart_wil.updateOptions({
                        xaxis: {
                            categories: wil_est
                        },
                        colors: warna
                    })
                    chartx_wil.updateSeries([{
                        name: 'mentah',
                        data: mentah_chart_wil,

                    }])
                    chartx_wil.updateOptions({
                        xaxis: {
                            categories: wil_est
                        },
                        colors: warna // Set the colors directly, no need for an object
                    })
                    ///////////
                    charts_wil.updateSeries([{
                        name: 'lewat matang',
                        data: lwtmatang_chart_wil,

                    }])
                    charts_wil.updateOptions({
                        xaxis: {
                            categories: wil_est
                        },
                        colors: warna // Set the colors directly, no need for an object
                    })
                    /////////
                    chartc_wil.updateSeries([{
                        name: 'janjang kosong',
                        data: janjangksng_chart_wil,

                    }])
                    chartc_wil.updateOptions({
                        xaxis: {
                            categories: wil_est
                        },
                        colors: warna // Set the colors directly, no need for an object
                    })
                    ////////
                    chartv_wil.updateSeries([{
                        name: 'vcut ',
                        data: vcuts_chart_wil,

                    }])
                    chartv_wil.updateOptions({
                        xaxis: {
                            categories: wil_est
                        },
                        colors: warna // Set the colors directly, no need for an object
                    })
                    ///////
                    chartb_wil.updateSeries([{
                        name: 'karung',
                        data: karungs_chart_wil,

                    }])
                    chartb_wil.updateOptions({
                        xaxis: {
                            categories: wil_est
                        },
                        colors: warna // Set the colors directly, no need for an object
                    })

                    //endchart


                    function createTableCell(text, customClass = null) {
                        const cell = document.createElement('td');
                        cell.innerText = text;
                        if (customClass) {
                            cell.classList.add(customClass);
                        }
                        return cell;
                    }

                    function setBackgroundColor(element, score) {
                        let color;
                        if (score === '-') {
                            color = "white";
                        } else if (score >= 95) {
                            color = "#609cd4";
                        } else if (score >= 85 && score < 95) {
                            color = "#08b454";
                        } else if (score >= 75 && score < 85) {
                            color = "#fffc04";
                        } else if (score >= 65 && score < 75) {
                            color = "#ffc404";
                        } else {
                            color = "red";
                        }

                        element.style.backgroundColor = color;
                        element.style.color = (color === "#609cd4" || color === "#08b454" || color === "red") ? "white" : "black";
                    }

                    function bgest(element, score) {
                        let color;
                        if (score === '-') {
                            color = "#609cd4";
                        } else if (score >= 85 && score < 95) {
                            color = "#08b454";
                        } else if (score >= 75 && score < 85) {
                            color = "#fffc04";
                        } else if (score >= 65 && score < 75) {
                            color = "#ffc404";
                        } else {
                            color = "red";
                        }

                        element.style.backgroundColor = color;
                        element.style.color = (color === "#609cd4" || color === "#08b454" || color === "red") ? "white" : "black";
                    }



                    var arrTbody1 = mutu_buah[0][1];

                    var tbody1 = document.getElementById('week1');

                    Object.entries(arrTbody1).forEach(([estateName, estateData]) => {
                        Object.entries(estateData).forEach(([key2, data], index) => {
                            const tr = document.createElement('tr');

                            let item4; // Declare item4 variable outside the object

                            if (data['csfxr'] !== 0) {
                                item4 = data['All_skor'];
                            } else {
                                item4 = '-';
                            }

                            const dataItems = {
                                item1: estateName,
                                item2: key2,
                                item3: data['nama_asisten'] !== undefined ? data['nama_asisten'] : '-',
                                item4: item4,
                                item5: data['rankAFD'],
                            };


                            const rowData = Object.values(dataItems);

                            rowData.forEach((item, cellIndex) => {
                                const cell = createTableCell(item, "text-center");
                                if (cellIndex === 2) {
                                    const item3nama = dataItems.item3;
                                    if (item3nama.trim() === "VACANT") { // Use trim to remove leading/trailing spaces
                                        cell.style.color = "red";
                                    } else {
                                        cell.style.color = "black";
                                    }
                                }

                                if (cellIndex === 3) {
                                    const item4Value = parseFloat(dataItems.item4);
                                    if (dataItems.item4 == '-') {
                                        cell.style.backgroundColor = "white";
                                        cell.style.color = "black";
                                    } // Convert to a number
                                    else if (item4Value >= 95) {
                                        cell.style.backgroundColor = "#609cd4";
                                        cell.style.color = "black";
                                    } else if (item4Value >= 85 && item4Value < 95) {
                                        cell.style.backgroundColor = "#08b454";
                                        cell.style.color = "black";
                                    } else if (item4Value >= 75 && item4Value < 85) {
                                        cell.style.backgroundColor = "#fffc04";
                                        cell.style.color = "black";
                                    } else if (item4Value >= 65 && item4Value < 75) {
                                        cell.style.backgroundColor = "#ffc404";
                                        cell.style.color = "black";
                                    } else {
                                        cell.style.backgroundColor = "red";
                                        cell.style.color = "black";
                                    }
                                }
                                tr.appendChild(cell);
                            });

                            tbody1.appendChild(tr);
                        });
                    });
                    var arrEst1 = mutubuah_est[0][1];
                    // console.log(arrEst1);
                    var tbody1 = document.getElementById('week1');

                    Object.entries(arrEst1).forEach(([estateName, estateData]) => {
                        const tr = document.createElement('tr');

                        // Set the background color for the entire row
                        tr.style.backgroundColor = '#C7E1AA';

                        // Data items to be added to the row
                        const dataItems = {
                            item1: estateName,
                            item2: estateData['EM'],
                            item3: estateData['Nama_assist'] || '-',
                            item4: estateData['All_skor'] < 0 ? 0 : estateData['All_skor'],
                            item5: estateData['rankEST']
                        };

                        // Iterate through the dataItems to create and style table cells
                        Object.values(dataItems).forEach((item, cellIndex) => {
                            const cell = document.createElement('td');
                            cell.textContent = item;
                            cell.style.backgroundColor = '#C7E1AA';
                            cell.classList.add('text-center');

                            // Apply specific styles based on cell content
                            if (cellIndex === 2) {
                                const item3nama = dataItems.item3;
                                cell.style.color = item3nama.trim() === "VACANT" ? "red" : "black";
                            }

                            if (cellIndex === 3) {
                                const item4Value = parseFloat(dataItems.item4); // Convert to a number
                                if (item4Value >= 95) {
                                    cell.style.backgroundColor = "#609cd4";
                                } else if (item4Value >= 85 && item4Value < 95) {
                                    cell.style.backgroundColor = "#08b454";
                                } else if (item4Value >= 75 && item4Value < 85) {
                                    cell.style.backgroundColor = "#fffc04";
                                } else if (item4Value >= 65 && item4Value < 75) {
                                    cell.style.backgroundColor = "#ffc404";
                                } else {
                                    cell.style.backgroundColor = "red";
                                }
                                cell.style.color = "black";
                            }

                            tr.appendChild(cell);
                        });

                        tbody1.appendChild(tr);
                    });

                    // Declare variables wil1, wil2, wil3, wil4 before using them
                    var wil1, wil2, wil3, wil4;

                    if (regInpt === '1') {
                        wil1 = 'WIL-I';
                        wil2 = 'WIL-II';
                        wil3 = 'WIL-III';
                        wil4 = 'Plasma1';
                    } else if (regInpt === '2') {
                        wil1 = 'WIL-IV';
                        wil2 = 'WIL-V';
                        wil3 = 'WIL-VI';
                        wil4 = 'Plasma2';
                    } else if (regInpt === '3') {
                        wil1 = 'WIL-VII';
                        wil2 = 'WIL-VIII';
                        wil3 = 'Plasma3';
                        wil4 = 'Plasma3';
                    } else {
                        wil1 = 'WIL-IX';
                        wil2 = 'WIL-X';
                        wil3 = '-';
                        wil4 = '-';
                    }

                    // Now you can use wil1, wil2, wil3, wil4 in your code without getting a ReferenceError

                    var arrEst1 = mutuBuah_wil[0][1];
                    // console.log(arrEst1);
                    var tbody1 = document.getElementById('week1');
                    const tr = document.createElement('tr');
                    // console.log(estateData);
                    let item3 = '-';
                    queryAsisten.forEach((asisten) => {
                        if (asisten[1].est === wil1 && asisten[1].afd === 'GM') {
                            item3 = asisten[1].nama;
                        }
                    });

                    const dataItems = {
                        item1: wil1,
                        item2: 'GM',
                        item3: item3,
                        item4: (arrEst1['All_skor'] < 0) ? 0 : arrEst1['All_skor'],
                        item5: arrEst1['rankWil'],
                    };


                    const rowData = Object.values(dataItems);

                    rowData.forEach((item, cellIndex) => {
                        const cell = createTableCell(item, "text-center");
                        if (cellIndex === 2) {
                            const item3nama = dataItems.item3;
                            if (item3nama.trim() === "VACANT") { // Use trim to remove leading/trailing spaces
                                cell.style.color = "red";
                            } else {
                                cell.style.color = "black";
                            }
                        }

                        if (cellIndex === 3) {
                            const item4Value = parseFloat(dataItems.item4); // Convert to a number
                            if (item4Value >= 95) {
                                cell.style.backgroundColor = "#609cd4";
                                cell.style.color = "black";
                            } else if (item4Value >= 85 && item4Value < 95) {
                                cell.style.backgroundColor = "#08b454";
                                cell.style.color = "black";
                            } else if (item4Value >= 75 && item4Value < 85) {
                                cell.style.backgroundColor = "#fffc04";
                                cell.style.color = "black";
                            } else if (item4Value >= 65 && item4Value < 75) {
                                cell.style.backgroundColor = "#ffc404";
                                cell.style.color = "black";
                            } else {
                                cell.style.backgroundColor = "red";
                                cell.style.color = "black";
                            }
                        }


                        tr.appendChild(cell);
                    });

                    tbody1.appendChild(tr);



                    var tab2 = mutu_buah[1][1];
                    var tbody2 = document.getElementById('week2');
                    // console.log(tab2);
                    Object.entries(tab2).forEach(([estateName, estateData]) => {
                        Object.entries(estateData).forEach(([key2, data], index) => {
                            const tr = document.createElement('tr');

                            let item4; // Declare item4 variable outside the object

                            if (data['csfxr'] !== 0) {
                                item4 = data['All_skor'];

                                item4 = (item4 < 0) ? 0 : item4;

                            } else {
                                item4 = '-';
                            }

                            const dataItems = {
                                item1: estateName,
                                item2: key2,
                                item3: data['nama_asisten'] !== undefined ? data['nama_asisten'] : '-',
                                item4: item4,
                                item5: data['rankAFD'],
                            };

                            const rowData = Object.values(dataItems);

                            rowData.forEach((item, cellIndex) => {
                                const cell = createTableCell(item, "text-center");

                                if (cellIndex === 2) {
                                    const item3nama = dataItems.item3;
                                    if (item3nama.trim() === "VACANT") { // Use trim to remove leading/trailing spaces
                                        cell.style.color = "red";
                                    } else {
                                        cell.style.color = "black";
                                    }
                                }

                                if (cellIndex === 3) {
                                    const item4Value = parseFloat(dataItems.item4);
                                    if (dataItems.item4 == '-') {
                                        cell.style.backgroundColor = "white";
                                        cell.style.color = "black";
                                    } // Convert to a number
                                    else if (item4Value >= 95) {
                                        cell.style.backgroundColor = "#609cd4";
                                        cell.style.color = "black";
                                    } else if (item4Value >= 85 && item4Value < 95) {
                                        cell.style.backgroundColor = "#08b454";
                                        cell.style.color = "black";
                                    } else if (item4Value >= 75 && item4Value < 85) {
                                        cell.style.backgroundColor = "#fffc04";
                                        cell.style.color = "black";
                                    } else if (item4Value >= 65 && item4Value < 75) {
                                        cell.style.backgroundColor = "#ffc404";
                                        cell.style.color = "black";
                                    } else {
                                        cell.style.backgroundColor = "red";
                                        cell.style.color = "black";
                                    }
                                }
                                tr.appendChild(cell);
                            });

                            tbody2.appendChild(tr);
                        });
                    });

                    var arrEst2 = mutubuah_est[1][1];
                    // console.log(arrEst2);
                    var tbody2 = document.getElementById('week2');

                    Object.entries(arrEst2).forEach(([estateName, estateData]) => {
                        const tr = document.createElement('tr');
                        // console.log(estateData);
                        const dataItems = {
                            item1: estateName,
                            item2: estateData['EM'],
                            item3: estateData['Nama_assist'] || '-',
                            item4: (estateData['All_skor'] < 0) ? 0 : estateData['All_skor'],
                            item5: estateData['rankEST'],
                        };

                        const rowData = Object.values(dataItems);

                        rowData.forEach((item, cellIndex) => {
                            const cell = createTableCell(item, "text-center");
                            if (cellIndex === 2) {
                                const item3nama = dataItems.item3;
                                if (item3nama.trim() === "VACANT") { // Use trim to remove leading/trailing spaces
                                    cell.style.color = "red";
                                } else {
                                    cell.style.color = "black";
                                }
                            }
                            cell.style.backgroundColor = '#C7E1AA';
                            if (cellIndex === 3) {
                                const item4Value = parseFloat(dataItems.item4); // Convert to a number
                                if (item4Value >= 95) {
                                    cell.style.backgroundColor = "#609cd4";
                                    cell.style.color = "black";
                                } else if (item4Value >= 85 && item4Value < 95) {
                                    cell.style.backgroundColor = "#08b454";
                                    cell.style.color = "black";
                                } else if (item4Value >= 75 && item4Value < 85) {
                                    cell.style.backgroundColor = "#fffc04";
                                    cell.style.color = "black";
                                } else if (item4Value >= 65 && item4Value < 75) {
                                    cell.style.backgroundColor = "#ffc404";
                                    cell.style.color = "black";
                                } else {
                                    cell.style.backgroundColor = "red";
                                    cell.style.color = "black";
                                }
                            }


                            tr.appendChild(cell);
                        });

                        tbody2.appendChild(tr);

                    });

                    var arrWil2 = mutuBuah_wil[1][1];
                    // console.log(arrWil2);
                    var tbody2 = document.getElementById('week2');
                    const tx = document.createElement('tr');
                    // console.log(estateData);
                    let item3s = '-';
                    queryAsisten.forEach((asisten) => {
                        if (asisten[1].est === wil2 && asisten[1].afd === 'GM') {
                            item3s = asisten[1].nama;
                        }
                    });
                    const dataItemx = {

                        item1: wil2,
                        item2: 'GM',
                        item3: item3s,
                        item4: (arrWil2['All_skor'] < 0) ? 0 : arrWil2['All_skor'],
                        item5: arrWil2['rankWil'],
                    };

                    const rowDatax = Object.values(dataItemx);

                    rowDatax.forEach((item, cellIndex) => {
                        const cell = createTableCell(item, "text-center");
                        if (cellIndex === 2) {
                            const item3nama = dataItems.item3;
                            if (item3nama.trim() === "VACANT") { // Use trim to remove leading/trailing spaces
                                cell.style.color = "red";
                            } else {
                                cell.style.color = "black";
                            }
                        }

                        if (cellIndex === 3) {
                            const item4Value = parseFloat(dataItems.item4); // Convert to a number
                            if (item4Value >= 95) {
                                cell.style.backgroundColor = "#609cd4";
                                cell.style.color = "black";
                            } else if (item4Value >= 85 && item4Value < 95) {
                                cell.style.backgroundColor = "#08b454";
                                cell.style.color = "black";
                            } else if (item4Value >= 75 && item4Value < 85) {
                                cell.style.backgroundColor = "#fffc04";
                                cell.style.color = "black";
                            } else if (item4Value >= 65 && item4Value < 75) {
                                cell.style.backgroundColor = "#ffc404";
                                cell.style.color = "black";
                            } else {
                                cell.style.backgroundColor = "red";
                                cell.style.color = "black";
                            }
                        }

                        tx.appendChild(cell);
                    });

                    tbody2.appendChild(tx);

                    var tbody3 = document.getElementById('week3');

                    if (mutu_buah[2] !== undefined) {
                        var tab3 = mutu_buah[2][1];

                        if (tab3 !== null && tab3 !== undefined) {
                            Object.entries(tab3).forEach(([estateName, estateData]) => {
                                Object.entries(estateData).forEach(([key2, data], index) => {
                                    const tr = document.createElement('tr');

                                    let item4; // Declare item4 variable outside the object

                                    if (data['csfxr'] !== 0) {
                                        item4 = data['All_skor'];
                                        item4 = (item4 < 0) ? 0 : item4;

                                    } else {
                                        item4 = '-';
                                    }

                                    const dataItems = {
                                        item1: estateName,
                                        item2: key2,
                                        item3: data['nama_asisten'] !== undefined ? data['nama_asisten'] : '-',
                                        item4: item4,
                                        item5: data['rankAFD'],
                                    };
                                    const rowData = Object.values(dataItems);

                                    rowData.forEach((item, cellIndex) => {
                                        const cell = createTableCell(item, "text-center");

                                        if (cellIndex === 2) {
                                            const item3nama = dataItems.item3;
                                            if (item3nama.trim() === "VACANT") { // Use trim to remove leading/trailing spaces
                                                cell.style.color = "red";
                                            } else {
                                                cell.style.color = "black";
                                            }
                                        }

                                        if (cellIndex === 3) {
                                            const item4Value = parseFloat(dataItems.item4);
                                            if (dataItems.item4 == '-') {
                                                cell.style.backgroundColor = "white";
                                                cell.style.color = "black";
                                            } // Convert to a number
                                            else if (item4Value >= 95) {
                                                cell.style.backgroundColor = "#609cd4";
                                                cell.style.color = "black";
                                            } else if (item4Value >= 85 && item4Value < 95) {
                                                cell.style.backgroundColor = "#08b454";
                                                cell.style.color = "black";
                                            } else if (item4Value >= 75 && item4Value < 85) {
                                                cell.style.backgroundColor = "#fffc04";
                                                cell.style.color = "black";
                                            } else if (item4Value >= 65 && item4Value < 75) {
                                                cell.style.backgroundColor = "#ffc404";
                                                cell.style.color = "black";
                                            } else {
                                                cell.style.backgroundColor = "red";
                                                cell.style.color = "black";
                                            }
                                        }

                                        tr.appendChild(cell);
                                    });

                                    tbody3.appendChild(tr);
                                });
                            });
                            // console.log(rekapmua);
                            Object.entries(rekapmua).forEach(([key, value]) => {
                                let tr = document.createElement('tr');

                                let itemElement1 = document.createElement('td');
                                let itemElement2 = document.createElement('td');
                                let itemElement3 = document.createElement('td');
                                let itemElement4 = document.createElement('td');
                                let itemElement5 = document.createElement('td');
                                itemElement1.classList.add("text-center")
                                itemElement2.classList.add("text-center")
                                itemElement3.classList.add("text-center")
                                itemElement4.classList.add("text-center")
                                itemElement5.classList.add("text-center")
                                itemElement1.innerText = key;
                                itemElement2.innerText = key;
                                itemElement3.innerText = value['Nama_assist'];
                                itemElement4.innerText = value['All_skor'];
                                itemElement5.innerText = '-'

                                setBackgroundColor(itemElement4, value['All_skor']);

                                tr.appendChild(itemElement1);
                                tr.appendChild(itemElement2);
                                tr.appendChild(itemElement3);
                                tr.appendChild(itemElement4);
                                tr.appendChild(itemElement5);

                                tbody3.appendChild(tr);
                            });
                        } else {
                            console.log("tab3 is null or undefined");
                        }
                    } else {
                        console.log("mutu_buah[2] is undefined");
                    }

                    var tbody3 = document.getElementById('week3');

                    if (mutubuah_est[2] !== undefined) {
                        var arrEst3 = mutubuah_est[2][1];

                        if (arrEst3 !== null && arrEst3 !== undefined) {
                            Object.entries(arrEst3).forEach(([estateName, estateData]) => {
                                const tr = document.createElement('tr');

                                const dataItems = {
                                    item1: estateName,
                                    item2: estateData['EM'],
                                    item3: estateData['Nama_assist'] || '-',
                                    item4: (estateData['All_skor'] < 0) ? 0 : estateData['All_skor'],
                                    item5: estateData['rankEST'],
                                };

                                const rowData = Object.values(dataItems);

                                rowData.forEach((item, cellIndex) => {
                                    const cell = createTableCell(item, "text-center");
                                    if (cellIndex === 2) {
                                        const item3nama = dataItems.item3;
                                        if (item3nama.trim() === "VACANT") { // Use trim to remove leading/trailing spaces
                                            cell.style.color = "red";
                                        } else {
                                            cell.style.color = "black";
                                        }
                                    }

                                    if (cellIndex === 3) {
                                        const item4Value = parseFloat(dataItems.item4); // Convert to a number
                                        if (item4Value >= 95) {
                                            cell.style.backgroundColor = "#609cd4";
                                            cell.style.color = "black";
                                        } else if (item4Value >= 85 && item4Value < 95) {
                                            cell.style.backgroundColor = "#08b454";
                                            cell.style.color = "black";
                                        } else if (item4Value >= 75 && item4Value < 85) {
                                            cell.style.backgroundColor = "#fffc04";
                                            cell.style.color = "black";
                                        } else if (item4Value >= 65 && item4Value < 75) {
                                            cell.style.backgroundColor = "#ffc404";
                                            cell.style.color = "black";
                                        } else {
                                            cell.style.backgroundColor = "red";
                                            cell.style.color = "black";
                                        }
                                    }

                                    tr.appendChild(cell);
                                });

                                tbody3.appendChild(tr);
                            });
                        } else {
                            console.log("arrEst3 is null or undefined");
                        }
                    } else {
                        console.log("mutubuah_est[2] is undefined");
                    }

                    var tbody3 = document.getElementById('week3');

                    if (mutuBuah_wil[2] !== undefined) {
                        var arrWIl3 = mutuBuah_wil[2][1];
                        let item3s = '-';
                        queryAsisten.forEach((asisten) => {
                            if (asisten[1].est === wil3 && asisten[1].afd === 'GM') {
                                item3s = asisten[1].nama;
                            }
                        });
                        if (arrWIl3 !== null && arrWIl3 !== undefined) {
                            const tm = document.createElement('tr');

                            const dataitemc = {
                                item1: wil3,
                                item2: 'GM',
                                item3: item3s,
                                item4: (arrWIl3['All_skor'] < 0) ? 0 : arrWIl3['All_skor'],
                                item5: arrWIl3['rankWil'],
                            };

                            const rowDatac = Object.values(dataitemc);

                            rowDatac.forEach((item, cellIndex) => {
                                const cell = createTableCell(item, "text-center");
                                if (cellIndex === 2) {
                                    const item3nama = dataItems.item3;
                                    if (item3nama.trim() === "VACANT") { // Use trim to remove leading/trailing spaces
                                        cell.style.color = "red";
                                    } else {
                                        cell.style.color = "black";
                                    }
                                }

                                if (cellIndex === 3) {
                                    const item4Value = parseFloat(dataItems.item4); // Convert to a number
                                    if (item4Value >= 95) {
                                        cell.style.backgroundColor = "#609cd4";
                                        cell.style.color = "black";
                                    } else if (item4Value >= 85 && item4Value < 95) {
                                        cell.style.backgroundColor = "#08b454";
                                        cell.style.color = "black";
                                    } else if (item4Value >= 75 && item4Value < 85) {
                                        cell.style.backgroundColor = "#fffc04";
                                        cell.style.color = "black";
                                    } else if (item4Value >= 65 && item4Value < 75) {
                                        cell.style.backgroundColor = "#ffc404";
                                        cell.style.color = "black";
                                    } else {
                                        cell.style.backgroundColor = "red";
                                        cell.style.color = "black";
                                    }
                                }

                                tm.appendChild(cell);
                            });

                            tbody3.appendChild(tm);
                        } else {
                            console.log("arrWIl3 is null or undefined");
                        }
                    } else {
                        console.log("mutuBuah_wil[2] is undefined");
                    }


                    var arrTbody1 = mutu_buah[0]?.[1] || [];


                    if (regInpt === '1') {
                        if (mutu_buah[3]) {
                            var tab4 = mutu_buah[3][1];
                        }
                    } else if (regInpt === '2') {
                        if (mutu_buah[3]) {
                            var tab4 = mutu_buah[3][1];
                        }
                    } else if (regInpt === '3') {
                        if (mutu_buah[3]) {
                            var tab4 = mutu_buah[2][1];
                        }
                    }


                    // var tab4 = mutu_buah[3][1];
                    var tbody4 = document.getElementById('plasma1');

                    if (tab4 !== null && tab4 !== undefined) {
                        Object.entries(tab4).forEach(([estateName, estateData]) => {
                            Object.entries(estateData).forEach(([key2, data], index) => {
                                const tr = document.createElement('tr');

                                const dataItems = {
                                    item1: estateName,
                                    item2: key2,
                                    item3: data['nama_asisten'] || '-',
                                    item4: (data['All_skor'] < 0) ? 0 : data['All_skor'],
                                    item5: data['rankAFD'],
                                };

                                const rowData = Object.values(dataItems);

                                rowData.forEach((item, cellIndex) => {
                                    const cell = createTableCell(item, "text-center");

                                    if (cellIndex === 2) {
                                        const item3nama = dataItems.item3;
                                        if (item3nama.trim() === "VACANT") { // Use trim to remove leading/trailing spaces
                                            cell.style.color = "red";
                                        } else {
                                            cell.style.color = "black";
                                        }
                                    }

                                    if (cellIndex === 3) {
                                        const item4Value = parseFloat(dataItems.item4); // Convert to a number
                                        if (item4Value >= 95) {
                                            cell.style.backgroundColor = "#609cd4";
                                            cell.style.color = "black";
                                        } else if (item4Value >= 85 && item4Value < 95) {
                                            cell.style.backgroundColor = "#08b454";
                                            cell.style.color = "black";
                                        } else if (item4Value >= 75 && item4Value < 85) {
                                            cell.style.backgroundColor = "#fffc04";
                                            cell.style.color = "black";
                                        } else if (item4Value >= 65 && item4Value < 75) {
                                            cell.style.backgroundColor = "#ffc404";
                                            cell.style.color = "black";
                                        } else {
                                            cell.style.backgroundColor = "red";
                                            cell.style.color = "black";
                                        }
                                    }

                                    tr.appendChild(cell);
                                });

                                tbody4.appendChild(tr);
                            });
                        });
                    } else {
                        console.log("tab4 is null or undefined");
                    }

                    var tbody4 = document.getElementById('plasma1');

                    if (mutubuah_est[3] !== undefined) {
                        var arrEst4 = mutubuah_est[3][1];

                        if (arrEst4 !== null && arrEst4 !== undefined) {
                            Object.entries(arrEst4).forEach(([estateName, estateData]) => {
                                const tr = document.createElement('tr');

                                const dataItems = {
                                    item1: estateName,
                                    item2: estateData['EM'],
                                    item3: estateData['Nama_assist'] || '-',
                                    item4: (estateData['All_skor'] < 0) ? 0 : estateData['All_skor'],
                                    item5: estateData['rankEST'],
                                };

                                const rowData = Object.values(dataItems);

                                rowData.forEach((item, cellIndex) => {
                                    const cell = createTableCell(item, "text-center");
                                    if (cellIndex === 2) {
                                        const item3nama = dataItems.item3;
                                        if (item3nama.trim() === "VACANT") { // Use trim to remove leading/trailing spaces
                                            cell.style.color = "red";
                                        } else {
                                            cell.style.color = "black";
                                        }
                                    }

                                    if (cellIndex === 3) {
                                        const item4Value = parseFloat(dataItems.item4); // Convert to a number
                                        if (item4Value >= 95) {
                                            cell.style.backgroundColor = "#609cd4";
                                            cell.style.color = "black";
                                        } else if (item4Value >= 85 && item4Value < 95) {
                                            cell.style.backgroundColor = "#08b454";
                                            cell.style.color = "black";
                                        } else if (item4Value >= 75 && item4Value < 85) {
                                            cell.style.backgroundColor = "#fffc04";
                                            cell.style.color = "black";
                                        } else if (item4Value >= 65 && item4Value < 75) {
                                            cell.style.backgroundColor = "#ffc404";
                                            cell.style.color = "black";
                                        } else {
                                            cell.style.backgroundColor = "red";
                                            cell.style.color = "black";
                                        }
                                    }
                                    tr.appendChild(cell);
                                });

                                tbody4.appendChild(tr);
                            });
                        } else {
                            console.log("arrEst4 is null or undefined");
                        }
                    } else {
                        console.log("mutubuah_est[3] is undefined");
                    }


                    var tbody4 = document.getElementById('plasma1');
                    const tl = document.createElement('tr');

                    if (mutuBuah_wil[3] !== undefined) {
                        var arrWIl3 = mutuBuah_wil[3][1];
                        let item3s = '-';
                        queryAsisten.forEach((asisten) => {
                            if (asisten[1].est === wil4 && asisten[1].afd === 'GM') {
                                item3s = asisten[1].nama;
                            }
                        });
                        if (arrWIl3 !== null && arrWIl3 !== undefined) {
                            const dataOm = {
                                item1: wil4,
                                item2: 'GM',
                                item3: item3s,
                                item4: (arrWIl3['All_skor'] < 0) ? 0 : arrWIl3['All_skor'],
                                item5: arrWIl3['rankWil'],
                            };

                            const rowOm = Object.values(dataOm);

                            rowOm.forEach((item, cellIndex) => {
                                const cell = createTableCell(item, "text-center");
                                if (cellIndex === 2) {
                                    const item3nama = dataItems.item3;
                                    if (item3nama.trim() === "VACANT") { // Use trim to remove leading/trailing spaces
                                        cell.style.color = "red";
                                    } else {
                                        cell.style.color = "black";
                                    }
                                }

                                if (cellIndex === 3) {
                                    const item4Value = parseFloat(dataItems.item4); // Convert to a number
                                    if (item4Value >= 95) {
                                        cell.style.backgroundColor = "#609cd4";
                                        cell.style.color = "black";
                                    } else if (item4Value >= 85 && item4Value < 95) {
                                        cell.style.backgroundColor = "#08b454";
                                        cell.style.color = "black";
                                    } else if (item4Value >= 75 && item4Value < 85) {
                                        cell.style.backgroundColor = "#fffc04";
                                        cell.style.color = "black";
                                    } else if (item4Value >= 65 && item4Value < 75) {
                                        cell.style.backgroundColor = "#ffc404";
                                        cell.style.color = "black";
                                    } else {
                                        cell.style.backgroundColor = "red";
                                        cell.style.color = "black";
                                    }
                                }

                                tl.appendChild(cell);
                            });

                            tbody4.appendChild(tl);
                        } else {
                            console.log("arrWIl3 is null or undefined");
                        }
                    } else {
                        console.log("mutuBuah_wil[3] is undefined");
                    }


                    var regionals = regIonal;
                    // console.log(regionaltab);
                    var headregional = document.getElementById('theadreg');
                    const trreg = document.createElement('tr');

                    const dataReg = {
                        // item1: regIonal[0] && regIonal[0][1] && regIonal[0][1].regional !== undefined ? regIonal[0][1].regional : '-',
                        item1: regionaltab[0][1]['nama'],
                        // item2: regIonal[0] && regIonal[0][1] && regIonal[0][1].jabatan !== undefined ? regIonal[0][1].jabatan : '-',
                        item2: regionaltab[0][1]['jabatan'],
                        // item3: regIonal[0] && regIonal[0][1] && regIonal[0][1].nama_asisten !== undefined ? regIonal[0][1].nama_asisten : '-',
                        item3: regionaltab[0][1]['nama_rh'],
                        item4: regIonal[0] && regIonal[0][1] && regIonal[0][1].all_skorYear !== undefined ? regIonal[0][1].all_skorYear : '-',
                    };

                    const rowREG = Object.values(dataReg);
                    rowREG.forEach((item, cellIndex) => {
                        const cell = createTableCell(item, "text-center");
                        if (cellIndex === 2) {
                            const item3nama = dataItems.item3;
                            if (item3nama.trim() === "VACANT") { // Use trim to remove leading/trailing spaces
                                cell.style.color = "red";
                            } else {
                                cell.style.color = "black";
                            }
                        }

                        if (cellIndex === 3) {
                            const item4Value = parseFloat(dataItems.item4); // Convert to a number
                            if (item4Value >= 95) {
                                cell.style.backgroundColor = "#609cd4";
                                cell.style.color = "black";
                            } else if (item4Value >= 85 && item4Value < 95) {
                                cell.style.backgroundColor = "#08b454";
                                cell.style.color = "black";
                            } else if (item4Value >= 75 && item4Value < 85) {
                                cell.style.backgroundColor = "#fffc04";
                                cell.style.color = "black";
                            } else if (item4Value >= 65 && item4Value < 75) {
                                cell.style.backgroundColor = "#ffc404";
                                cell.style.color = "black";
                            } else {
                                cell.style.backgroundColor = "red";
                                cell.style.color = "black";
                            }
                        }
                        trreg.appendChild(cell);
                    });
                    headregional.appendChild(trreg);


                },
                error: function(jqXHR, textStatus, errorThrown) {

                }
            });
        }

        let showTahung
        //tampilkan pertahun filter table utama
        document.getElementById('showTahung').onclick = function() {
            showTahung = showLoadingSwal();
            dashboard_tahun()
        }


        var char1 = new ApexCharts(document.querySelector("#matangthun"), options);
        var chart2 = new ApexCharts(document.querySelector("#mentahtahun"), options);
        var chart3 = new ApexCharts(document.querySelector("#lewatmatangtahun"), options);
        var chart4 = new ApexCharts(document.querySelector("#jangkostahun"), options);
        var chart5 = new ApexCharts(document.querySelector("#tidakvcuttahun"), options);
        var chart6 = new ApexCharts(document.querySelector("#karungbrondolantahun"), options);

        var chart_wils = new ApexCharts(document.querySelector("#matang_wils"), options_wil);
        var chartx_wils = new ApexCharts(document.querySelector("#mentah_wils"), options_wil);
        var charts_wils = new ApexCharts(document.querySelector("#lewatmatang_wils"), options_wil);
        var chartc_wils = new ApexCharts(document.querySelector("#jangkos_wils"), options_wil);
        var chartv_wils = new ApexCharts(document.querySelector("#tidakvcut_wils"), options_wil);
        var chartb_wils = new ApexCharts(document.querySelector("#karungbrondolan_wils"), options_wil);

        chart_wils.render();
        chartx_wils.render();
        charts_wils.render();
        chartc_wils.render();
        chartv_wils.render();
        chartb_wils.render();

        char1.render();
        chart2.render();
        chart3.render();
        chart4.render();
        chart5.render();
        chart6.render();

        function dashboard_tahun() {
            $('#weeks1').empty()
            $('#weeks2').empty()
            $('#weeks3').empty()
            $('#plasmas1').empty()
            $('#theadregs').empty()
            var week = ''
            var regData = ''
            var _token = $('input[name="_token"]').val();
            var week = document.getElementById('dateWeek').value
            var regData = document.getElementById('regionalData').value


            $.ajax({
                url: "{{ route('getYear') }}",
                method: "GET",
                data: {
                    week,
                    regData,
                    _token: _token
                },
                success: function(result) {
                    closeLoadingSwal(showTahung);
                    var parseResult = JSON.parse(result)
                    var region = Object.entries(parseResult['listregion'])
                    var rekapmua = parseResult['rekapmua']
                    var mutu_buah = Object.entries(parseResult['mutu_buah'])
                    // console.log(mutu_buah);
                    var mutubuah_est = Object.entries(parseResult['mutubuah_est'])
                    var mutuBuah_wil = Object.entries(parseResult['mutuBuah_wil'])
                    var regIonal = Object.entries(parseResult['regional'])
                    var queryAsisten = Object.entries(parseResult['queryAsisten'])

                    var chart_matang = Object.entries(parseResult['chart_matang'])
                    var chart_mentah = Object.entries(parseResult['chart_mentah'])
                    var chart_lewatmatang = Object.entries(parseResult['chart_lewatmatang'])
                    var chart_janjangkosong = Object.entries(parseResult['chart_janjangkosong'])
                    var chart_vcut = Object.entries(parseResult['chart_vcut'])
                    var chart_karung = Object.entries(parseResult['chart_karung'])

                    var chart_matangwil = Object.entries(parseResult['chart_matangwil'])
                    var chart_mentahwil = Object.entries(parseResult['chart_mentahwil'])
                    var chart_lewatmatangwil = Object.entries(parseResult['chart_lewatmatangwil'])
                    var chart_janjangkosongwil = Object.entries(parseResult['chart_janjangkosongwil'])
                    var chart_vcutwil = Object.entries(parseResult['chart_vcutwil'])
                    var chart_karungwil = Object.entries(parseResult['chart_karungwil'])
                    var regionaltab = Object.entries(parseResult['regionaltab'])
                    // console.log(chart_matang);
                    var matang_Wil = '['
                    if (chart_matangwil.length > 0) {
                        chart_matangwil.forEach(element => {
                            matang_Wil += '"' + element[1] + '",'
                        });
                        matang_Wil = matang_Wil.substring(0, matang_Wil.length - 1);
                    }
                    matang_Wil += ']'
                    var mentah_wil = '['
                    if (chart_mentahwil.length > 0) {
                        chart_mentahwil.forEach(element => {
                            mentah_wil += '"' + element[1] + '",'
                        });
                        mentah_wil = mentah_wil.substring(0, mentah_wil.length - 1);
                    }
                    mentah_wil += ']'
                    var lewatmatangs_wil = '['
                    if (chart_lewatmatangwil.length > 0) {
                        chart_lewatmatangwil.forEach(element => {
                            lewatmatangs_wil += '"' + element[1] + '",'
                        });
                        lewatmatangs_wil = lewatmatangs_wil.substring(0, lewatmatangs_wil.length - 1);
                    }
                    lewatmatangs_wil += ']'

                    var jjgkosongs_wil = '['
                    if (chart_janjangkosongwil.length > 0) {
                        chart_janjangkosongwil.forEach(element => {
                            jjgkosongs_wil += '"' + element[1] + '",'
                        });
                        jjgkosongs_wil = jjgkosongs_wil.substring(0, jjgkosongs_wil.length - 1);
                    }
                    jjgkosongs_wil += ']'

                    var vcuts_wil = '['
                    if (chart_vcutwil.length > 0) {
                        chart_vcutwil.forEach(element => {
                            vcuts_wil += '"' + element[1] + '",'
                        });
                        vcuts_wil = vcuts_wil.substring(0, vcuts_wil.length - 1);
                    }
                    vcuts_wil += ']'
                    var karungs_wil = '['
                    if (chart_karungwil.length > 0) {
                        chart_karungwil.forEach(element => {
                            karungs_wil += '"' + element[1] + '",'
                        });
                        karungs_wil = karungs_wil.substring(0, karungs_wil.length - 1);
                    }
                    karungs_wil += ']'

                    // console.log(matang_Wil);
                    let regInpt = regData;

                    var wilayah = '['
                    region.forEach(element => {
                        wilayah += '"' + element + '",'
                    });
                    wilayah = wilayah.substring(0, wilayah.length - 1);
                    wilayah += ']'

                    // console.log(chart_matang);
                    var matang = '['
                    if (chart_matang.length > 0) {
                        chart_matang.forEach(element => {
                            matang += '"' + element[1] + '",'
                        });
                        matang = matang.substring(0, matang.length - 1);
                    }
                    matang += ']'

                    var mentah = '['
                    if (chart_mentah.length > 0) {
                        chart_mentah.forEach(element => {
                            mentah += '"' + element[1] + '",'
                        });
                        mentah = mentah.substring(0, mentah.length - 1);
                    }
                    mentah += ']'

                    var lewatmatangs = '['
                    if (chart_lewatmatang.length > 0) {
                        chart_lewatmatang.forEach(element => {
                            lewatmatangs += '"' + element[1] + '",'
                        });
                        lewatmatangs = lewatmatangs.substring(0, lewatmatangs.length - 1);
                    }
                    lewatmatangs += ']'

                    var jjgkosongs = '['
                    if (chart_janjangkosong.length > 0) {
                        chart_janjangkosong.forEach(element => {
                            jjgkosongs += '"' + element[1] + '",'
                        });
                        jjgkosongs = jjgkosongs.substring(0, jjgkosongs.length - 1);
                    }
                    jjgkosongs += ']'

                    var vcuts = '['
                    if (chart_vcut.length > 0) {
                        chart_vcut.forEach(element => {
                            vcuts += '"' + element[1] + '",'
                        });
                        vcuts = vcuts.substring(0, vcuts.length - 1);
                    }
                    vcuts += ']'

                    var karungs = '['
                    if (chart_karung.length > 0) {
                        chart_karung.forEach(element => {
                            karungs += '"' + element[1] + '",'
                        });
                        karungs = karungs.substring(0, karungs.length - 1);
                    }
                    karungs += ']'

                    var estate = JSON.parse(wilayah)
                    var matang_chart = JSON.parse(matang)
                    var matang_chart_wil = JSON.parse(matang_Wil)
                    var mentah_chart = JSON.parse(mentah)
                    var mentah_chart_wil = JSON.parse(mentah_wil)

                    var lwtmatang_chart = JSON.parse(lewatmatangs)
                    var lwtmatang_chart_wil = JSON.parse(lewatmatangs_wil)
                    var janjangksng_chart = JSON.parse(jjgkosongs)
                    var janjangksng_chart_wil = JSON.parse(jjgkosongs_wil)
                    var vcuts_chart = JSON.parse(vcuts)
                    var vcuts_chart_wil = JSON.parse(vcuts_wil)
                    var karungs_chart = JSON.parse(karungs)
                    var karungs_chart_wil = JSON.parse(karungs_wil)



                    const formatEst = estate.map((item) => item.split(',')[1]);
                    let plasma1Index = formatEst.indexOf("Plasma1");

                    if (plasma1Index !== -1) {
                        formatEst.splice(plasma1Index, 1);
                        formatEst.push("Plasma1");
                    }

                    // console.log(formatEst);

                    let colors = '';


                    if (regInpt === '1') {
                        colors = ['#00FF00',
                            '#00FF00',
                            '#00FF00',
                            '#00FF00',
                            '#3063EC',
                            '#3063EC',
                            '#3063EC',
                            '#3063EC',
                            '#FF8D1A',
                            '#FF8D1A',
                            '#FF8D1A',
                            '#FF8D1A',
                            '#00ffff'
                        ]

                    } else if (regInpt === '2') {
                        colors = ['#00FF00',
                            '#00FF00',
                            '#00FF00',
                            '#00FF00',
                            '#3063EC',
                            '#3063EC',
                            '#3063EC',
                            '#00ffff',
                            '#00ffff'
                        ]


                    } else if (regInpt === '3') {
                        colors = ['#00FF00',
                            '#00FF00',
                            '#00FF00',
                            '#00FF00',
                            '#3063EC',
                            '#3063EC',
                            '#3063EC',
                            '#3063EC',
                        ]
                    } else if (regInpt === '4') {
                        colors = ['#00FF00',
                            '#00FF00',
                            '#00FF00',
                            '#3063EC',
                            '#3063EC',

                        ]
                    }

                    // console.log(matang_chart);
                    char1.updateSeries([{
                        name: 'matang',
                        data: matang_chart,

                    }])
                    char1.updateOptions({
                        xaxis: {
                            categories: formatEst
                        },
                        colors: colors // Set the colors directly, no need for an object
                    })
                    ///////////
                    chart2.updateSeries([{
                        name: 'mentah',
                        data: mentah_chart,

                    }])
                    chart2.updateOptions({
                        xaxis: {
                            categories: formatEst
                        },
                        colors: colors // Set the colors directly, no need for an object
                    })
                    ///////////
                    chart3.updateSeries([{
                        name: 'lewat matang',
                        data: lwtmatang_chart,

                    }])
                    chart3.updateOptions({
                        xaxis: {
                            categories: formatEst
                        },
                        colors: colors // Set the colors directly, no need for an object
                    })
                    /////////
                    chart4.updateSeries([{
                        name: 'janjang kosong',
                        data: janjangksng_chart,

                    }])
                    chart4.updateOptions({
                        xaxis: {
                            categories: formatEst
                        },
                        colors: colors // Set the colors directly, no need for an object
                    })
                    ////////
                    chart5.updateSeries([{
                        name: 'vcut ',
                        data: vcuts_chart,

                    }])
                    chart5.updateOptions({
                        xaxis: {
                            categories: formatEst
                        },
                        colors: colors // Set the colors directly, no need for an object
                    })
                    ///////
                    chart6.updateSeries([{
                        name: 'karung',
                        data: karungs_chart,

                    }])
                    chart6.updateOptions({
                        xaxis: {
                            categories: formatEst
                        },
                        colors: colors // Set the colors directly, no need for an object
                    })


                    // Declare wil_est variable before using it
                    var wil_est;
                    var warna; // Also declare the warna variable

                    if (regInpt === '1') {
                        wil_est = ['I', 'II', 'III'];
                        warna = ['#00FF00', '#FF8D1A', '#00ffff'];
                    } else if (regInpt === '2') {
                        wil_est = ['IV', 'V', 'VI'];
                        warna = ['#00FF00', '#FF8D1A', '#00ffff'];
                    } else if (regInpt === '3') {
                        wil_est = ['VII', 'VIII'];
                        warna = ['#00FF00', '#00ffff'];
                    } else {
                        wil_est = ['IX', 'X'];
                        warna = ['#00FF00', '#00ffff'];
                    }

                    // Now you can use wil_est and warna in your code without getting a ReferenceError

                    chart_wils.updateSeries([{
                        name: 'matang',
                        data: matang_chart_wil,

                    }])
                    chart_wils.updateOptions({
                        xaxis: {
                            categories: wil_est
                        },
                        colors: warna
                    })
                    chartx_wils.updateSeries([{
                        name: 'mentah',
                        data: mentah_chart_wil,

                    }])
                    chartx_wils.updateOptions({
                        xaxis: {
                            categories: wil_est
                        },
                        colors: warna // Set the colors directly, no need for an object
                    })
                    ///////////
                    charts_wils.updateSeries([{
                        name: 'lewat matang',
                        data: lwtmatang_chart_wil,

                    }])
                    charts_wils.updateOptions({
                        xaxis: {
                            categories: wil_est
                        },
                        colors: warna // Set the colors directly, no need for an object
                    })
                    /////////
                    chartc_wils.updateSeries([{
                        name: 'janjang kosong',
                        data: janjangksng_chart_wil,

                    }])
                    chartc_wils.updateOptions({
                        xaxis: {
                            categories: wil_est
                        },
                        colors: warna // Set the colors directly, no need for an object
                    })
                    ////////
                    chartv_wils.updateSeries([{
                        name: 'vcut ',
                        data: vcuts_chart_wil,

                    }])
                    chartv_wils.updateOptions({
                        xaxis: {
                            categories: wil_est
                        },
                        colors: warna // Set the colors directly, no need for an object
                    })
                    ///////
                    chartb_wils.updateSeries([{
                        name: 'karung',
                        data: karungs_chart_wil,

                    }])
                    chartb_wils.updateOptions({
                        xaxis: {
                            categories: wil_est
                        },
                        colors: warna // Set the colors directly, no need for an object
                    })

                    //endchart


                    function createTableCell(text, customClass = null) {
                        const cell = document.createElement('td');
                        cell.innerText = text;
                        if (customClass) {
                            cell.classList.add(customClass);
                        }
                        return cell;
                    }

                    function setBackgroundColor(element, score) {
                        let color;
                        if (score >= 95) {
                            color = "#609cd4";
                        } else if (score >= 85 && score < 95) {
                            color = "#08b454";
                        } else if (score >= 75 && score < 85) {
                            color = "#fffc04";
                        } else if (score >= 65 && score < 75) {
                            color = "#ffc404";
                        } else {
                            color = "red";
                        }

                        element.style.backgroundColor = color;
                        element.style.color = (color === "#609cd4" || color === "#08b454" || color === "red") ? "white" : "black";
                    }

                    function bgest(element, score) {
                        let color;
                        if (score >= 95) {
                            color = "#609cd4";
                        } else if (score >= 85 && score < 95) {
                            color = "#08b454";
                        } else if (score >= 75 && score < 85) {
                            color = "#fffc04";
                        } else if (score >= 65 && score < 75) {
                            color = "#ffc404";
                        } else {
                            color = "red";
                        }

                        element.style.backgroundColor = color;
                        element.style.color = (color === "#609cd4" || color === "#08b454" || color === "red") ? "white" : "black";
                    }



                    var arrTbody1 = mutu_buah[0][1];

                    var tbody1 = document.getElementById('weeks1');

                    Object.entries(arrTbody1).forEach(([estateName, estateData]) => {
                        Object.entries(estateData).forEach(([key2, data], index) => {
                            const tr = document.createElement('tr');

                            const dataItems = {
                                item1: estateName,
                                item2: key2,
                                item3: data['nama_asisten'] !== undefined ? data['nama_asisten'] : '-',
                                item4: data['All_skor'],
                                item5: data['rankAFD'],
                            };

                            const rowData = Object.values(dataItems);

                            rowData.forEach((item, cellIndex) => {
                                const cell = createTableCell(item, "text-center");

                                if (cellIndex === 2) {
                                    const item3nama = dataItems.item3;
                                    if (item3nama.trim() === "VACANT") { // Use trim to remove leading/trailing spaces
                                        cell.style.color = "red";
                                    } else {
                                        cell.style.color = "black";
                                    }
                                }

                                if (cellIndex === 3) {
                                    const item4Value = parseFloat(dataItems.item4); // Convert to a number
                                    if (item4Value >= 95) {
                                        cell.style.backgroundColor = "#609cd4";
                                        cell.style.color = "black";
                                    } else if (item4Value >= 85 && item4Value < 95) {
                                        cell.style.backgroundColor = "#08b454";
                                        cell.style.color = "black";
                                    } else if (item4Value >= 75 && item4Value < 85) {
                                        cell.style.backgroundColor = "#fffc04";
                                        cell.style.color = "black";
                                    } else if (item4Value >= 65 && item4Value < 75) {
                                        cell.style.backgroundColor = "#ffc404";
                                        cell.style.color = "black";
                                    } else {
                                        cell.style.backgroundColor = "red";
                                        cell.style.color = "black";
                                    }
                                }

                                tr.appendChild(cell);
                            });

                            tbody1.appendChild(tr);
                        });
                    });
                    var arrEst1 = mutubuah_est[0][1];
                    // console.log(arrEst1);
                    var tbody1 = document.getElementById('weeks1');

                    Object.entries(arrEst1).forEach(([estateName, estateData]) => {
                        const tr = document.createElement('tr');
                        // console.log(estateData);
                        const dataItems = {
                            item1: estateName,
                            item2: estateData['EM'],
                            item3: estateData['Nama_assist'] || '-',
                            item4: estateData['All_skor'],
                            item5: estateData['rankEST'],
                        };

                        const rowData = Object.values(dataItems);

                        rowData.forEach((item, cellIndex) => {
                            const cell = createTableCell(item, "text-center");
                            if (cellIndex === 2) {
                                const item3nama = dataItems.item3;
                                if (item3nama.trim() === "VACANT") { // Use trim to remove leading/trailing spaces
                                    cell.style.color = "red";
                                } else {
                                    cell.style.color = "black";
                                }
                            }

                            if (cellIndex === 3) {
                                const item4Value = parseFloat(dataItems.item4); // Convert to a number
                                if (item4Value >= 95) {
                                    cell.style.backgroundColor = "#609cd4";
                                    cell.style.color = "black";
                                } else if (item4Value >= 85 && item4Value < 95) {
                                    cell.style.backgroundColor = "#08b454";
                                    cell.style.color = "black";
                                } else if (item4Value >= 75 && item4Value < 85) {
                                    cell.style.backgroundColor = "#fffc04";
                                    cell.style.color = "black";
                                } else if (item4Value >= 65 && item4Value < 75) {
                                    cell.style.backgroundColor = "#ffc404";
                                    cell.style.color = "black";
                                } else {
                                    cell.style.backgroundColor = "red";
                                    cell.style.color = "black";
                                }
                            }


                            tr.appendChild(cell);
                        });

                        tbody1.appendChild(tr);

                    });
                    // Declare variables wil1, wil2, wil3, wil4 before using them
                    var wil1, wil2, wil3, wil4;

                    if (regInpt === '1') {
                        wil1 = 'WIL-I';
                        wil2 = 'WIL-II';
                        wil3 = 'WIL-III';
                        wil4 = 'Plasma1';
                    } else if (regInpt === '2') {
                        wil1 = 'WIL-IV';
                        wil2 = 'WIL-V';
                        wil3 = 'WIL-VI';
                        wil4 = 'Plasma2';
                    } else if (regInpt === '3') {
                        wil1 = 'WIL-VII';
                        wil2 = 'WIL-VIII';
                        wil3 = 'Plasma3';
                        wil4 = 'Plasma3';
                    } else {
                        wil1 = 'WIL-IX';
                        wil2 = 'WIL-X';
                        wil3 = '-';
                        wil4 = '-';
                    }

                    // Now you can use wil1, wil2, wil3, wil4 in your code without getting a ReferenceError

                    var arrEst1 = mutuBuah_wil[0][1];
                    // console.log(arrEst1);
                    var tbody1 = document.getElementById('weeks1');
                    const tr = document.createElement('tr');
                    // console.log(estateData);
                    let item3 = '-';
                    queryAsisten.forEach((asisten) => {
                        if (asisten[1].est === wil1 && asisten[1].afd === 'GM') {
                            item3 = asisten[1].nama;
                        }
                    });

                    const dataItems = {
                        item1: wil1,
                        item2: 'GM',
                        item3: item3,
                        item4: arrEst1['All_skor'],
                        item5: arrEst1['rankWil'],
                    };


                    const rowData = Object.values(dataItems);

                    rowData.forEach((item, cellIndex) => {
                        const cell = createTableCell(item, "text-center");
                        if (cellIndex === 2) {
                            const item3nama = dataItems.item3;
                            if (item3nama.trim() === "VACANT") { // Use trim to remove leading/trailing spaces
                                cell.style.color = "red";
                            } else {
                                cell.style.color = "black";
                            }
                        }

                        if (cellIndex === 3) {
                            const item4Value = parseFloat(dataItems.item4); // Convert to a number
                            if (item4Value >= 95) {
                                cell.style.backgroundColor = "#609cd4";
                                cell.style.color = "black";
                            } else if (item4Value >= 85 && item4Value < 95) {
                                cell.style.backgroundColor = "#08b454";
                                cell.style.color = "black";
                            } else if (item4Value >= 75 && item4Value < 85) {
                                cell.style.backgroundColor = "#fffc04";
                                cell.style.color = "black";
                            } else if (item4Value >= 65 && item4Value < 75) {
                                cell.style.backgroundColor = "#ffc404";
                                cell.style.color = "black";
                            } else {
                                cell.style.backgroundColor = "red";
                                cell.style.color = "black";
                            }
                        }


                        tr.appendChild(cell);
                    });

                    tbody1.appendChild(tr);



                    var tab2 = mutu_buah[1][1];
                    var tbody2 = document.getElementById('weeks2');
                    // console.log(tab2);
                    Object.entries(tab2).forEach(([estateName, estateData]) => {
                        Object.entries(estateData).forEach(([key2, data], index) => {
                            const tr = document.createElement('tr');

                            const dataItems = {
                                item1: estateName,
                                item2: key2,
                                item3: data['nama_asisten'] || '-',
                                item4: data['All_skor'],
                                item5: data['rankAFD'],
                            };

                            const rowData = Object.values(dataItems);

                            rowData.forEach((item, cellIndex) => {
                                const cell = createTableCell(item, "text-center");

                                if (cellIndex === 2) {
                                    const item3nama = dataItems.item3;
                                    if (item3nama.trim() === "VACANT") { // Use trim to remove leading/trailing spaces
                                        cell.style.color = "red";
                                    } else {
                                        cell.style.color = "black";
                                    }
                                }

                                if (cellIndex === 3) {
                                    const item4Value = parseFloat(dataItems.item4); // Convert to a number
                                    if (item4Value >= 95) {
                                        cell.style.backgroundColor = "#609cd4";
                                        cell.style.color = "black";
                                    } else if (item4Value >= 85 && item4Value < 95) {
                                        cell.style.backgroundColor = "#08b454";
                                        cell.style.color = "black";
                                    } else if (item4Value >= 75 && item4Value < 85) {
                                        cell.style.backgroundColor = "#fffc04";
                                        cell.style.color = "black";
                                    } else if (item4Value >= 65 && item4Value < 75) {
                                        cell.style.backgroundColor = "#ffc404";
                                        cell.style.color = "black";
                                    } else {
                                        cell.style.backgroundColor = "red";
                                        cell.style.color = "black";
                                    }
                                }

                                tr.appendChild(cell);
                            });

                            tbody2.appendChild(tr);
                        });
                    });

                    var arrEst2 = mutubuah_est[1][1];
                    // console.log(arrEst2);
                    var tbody2 = document.getElementById('weeks2');

                    Object.entries(arrEst2).forEach(([estateName, estateData]) => {
                        const tr = document.createElement('tr');
                        // console.log(estateData);
                        const dataItems = {
                            item1: estateName,
                            item2: estateData['EM'],
                            item3: estateData['Nama_assist'] || '-',
                            item4: estateData['All_skor'],
                            item5: estateData['rankEST'],
                        };

                        const rowData = Object.values(dataItems);

                        rowData.forEach((item, cellIndex) => {
                            const cell = createTableCell(item, "text-center");
                            if (cellIndex === 2) {
                                const item3nama = dataItems.item3;
                                if (item3nama.trim() === "VACANT") { // Use trim to remove leading/trailing spaces
                                    cell.style.color = "red";
                                } else {
                                    cell.style.color = "black";
                                }
                            }

                            if (cellIndex === 3) {
                                const item4Value = parseFloat(dataItems.item4); // Convert to a number
                                if (item4Value >= 95) {
                                    cell.style.backgroundColor = "#609cd4";
                                    cell.style.color = "black";
                                } else if (item4Value >= 85 && item4Value < 95) {
                                    cell.style.backgroundColor = "#08b454";
                                    cell.style.color = "black";
                                } else if (item4Value >= 75 && item4Value < 85) {
                                    cell.style.backgroundColor = "#fffc04";
                                    cell.style.color = "black";
                                } else if (item4Value >= 65 && item4Value < 75) {
                                    cell.style.backgroundColor = "#ffc404";
                                    cell.style.color = "black";
                                } else {
                                    cell.style.backgroundColor = "red";
                                    cell.style.color = "black";
                                }
                            }

                            tr.appendChild(cell);
                        });

                        tbody2.appendChild(tr);

                    });

                    var arrWil2 = mutuBuah_wil[1][1];
                    // console.log(arrWil2);
                    var tbody2 = document.getElementById('weeks2');
                    const tx = document.createElement('tr');
                    // console.log(estateData);
                    let item3s = '-';
                    queryAsisten.forEach((asisten) => {
                        if (asisten[1].est === wil2 && asisten[1].afd === 'GM') {
                            item3s = asisten[1].nama;
                        }
                    });
                    const dataItemx = {

                        item1: wil2,
                        item2: 'GM',
                        item3: item3s,
                        item4: arrWil2['All_skor'],
                        item5: arrWil2['rankWil'],
                    };

                    const rowDatax = Object.values(dataItemx);

                    rowDatax.forEach((item, cellIndex) => {
                        const cell = createTableCell(item, "text-center");
                        if (cellIndex === 2) {
                            const item3nama = dataItems.item3;
                            if (item3nama.trim() === "VACANT") { // Use trim to remove leading/trailing spaces
                                cell.style.color = "red";
                            } else {
                                cell.style.color = "black";
                            }
                        }

                        if (cellIndex === 3) {
                            const item4Value = parseFloat(dataItems.item4); // Convert to a number
                            if (item4Value >= 95) {
                                cell.style.backgroundColor = "#609cd4";
                                cell.style.color = "black";
                            } else if (item4Value >= 85 && item4Value < 95) {
                                cell.style.backgroundColor = "#08b454";
                                cell.style.color = "black";
                            } else if (item4Value >= 75 && item4Value < 85) {
                                cell.style.backgroundColor = "#fffc04";
                                cell.style.color = "black";
                            } else if (item4Value >= 65 && item4Value < 75) {
                                cell.style.backgroundColor = "#ffc404";
                                cell.style.color = "black";
                            } else {
                                cell.style.backgroundColor = "red";
                                cell.style.color = "black";
                            }
                        }

                        tx.appendChild(cell);
                    });

                    tbody2.appendChild(tx);

                    var tbody3 = document.getElementById('week3');

                    if (mutu_buah[2] !== undefined) {
                        var tab3 = mutu_buah[2][1];

                        if (tab3 !== null && tab3 !== undefined) {
                            Object.entries(tab3).forEach(([estateName, estateData]) => {
                                Object.entries(estateData).forEach(([key2, data], index) => {
                                    const tr = document.createElement('tr');

                                    const dataItems = {
                                        item1: estateName,
                                        item2: key2,
                                        item3: data['nama_asisten'] || '-',
                                        item4: data['All_skor'],
                                        item5: data['rankAFD'],
                                    };

                                    const rowData = Object.values(dataItems);

                                    rowData.forEach((item, cellIndex) => {
                                        const cell = createTableCell(item, "text-center");

                                        if (cellIndex === 2) {
                                            const item3nama = dataItems.item3;
                                            if (item3nama.trim() === "VACANT") { // Use trim to remove leading/trailing spaces
                                                cell.style.color = "red";
                                            } else {
                                                cell.style.color = "black";
                                            }
                                        }

                                        if (cellIndex === 3) {
                                            const item4Value = parseFloat(dataItems.item4); // Convert to a number
                                            if (item4Value >= 95) {
                                                cell.style.backgroundColor = "#609cd4";
                                                cell.style.color = "black";
                                            } else if (item4Value >= 85 && item4Value < 95) {
                                                cell.style.backgroundColor = "#08b454";
                                                cell.style.color = "black";
                                            } else if (item4Value >= 75 && item4Value < 85) {
                                                cell.style.backgroundColor = "#fffc04";
                                                cell.style.color = "black";
                                            } else if (item4Value >= 65 && item4Value < 75) {
                                                cell.style.backgroundColor = "#ffc404";
                                                cell.style.color = "black";
                                            } else {
                                                cell.style.backgroundColor = "red";
                                                cell.style.color = "black";
                                            }
                                        }

                                        tr.appendChild(cell);
                                    });

                                    tbody3.appendChild(tr);
                                });


                            });

                            Object.entries(rekapmua).forEach(([key, value]) => {
                                let tr = document.createElement('tr');

                                let itemElement1 = document.createElement('td');
                                let itemElement2 = document.createElement('td');
                                let itemElement3 = document.createElement('td');
                                let itemElement4 = document.createElement('td');
                                let itemElement5 = document.createElement('td');
                                itemElement1.classList.add("text-center")
                                itemElement2.classList.add("text-center")
                                itemElement3.classList.add("text-center")
                                itemElement4.classList.add("text-center")
                                itemElement5.classList.add("text-center")
                                itemElement1.innerText = key;
                                itemElement2.innerText = key;
                                itemElement3.innerText = value['Nama_assist'];
                                itemElement4.innerText = value['All_skor'];
                                itemElement5.innerText = '-'

                                setBackgroundColor(itemElement4, value['All_skor']);

                                tr.appendChild(itemElement1);
                                tr.appendChild(itemElement2);
                                tr.appendChild(itemElement3);
                                tr.appendChild(itemElement4);
                                tr.appendChild(itemElement5);

                                tbody3.appendChild(tr);
                            });
                        } else {
                            console.log("tab3 is null or undefined");
                        }
                    } else {
                        console.log("mutu_buah[2] is undefined");
                    }

                    var tbody3 = document.getElementById('weeks3');

                    if (mutubuah_est[2] !== undefined) {
                        var arrEst3 = mutubuah_est[2][1];

                        if (arrEst3 !== null && arrEst3 !== undefined) {
                            Object.entries(arrEst3).forEach(([estateName, estateData]) => {
                                const tr = document.createElement('tr');

                                const dataItems = {
                                    item1: estateName,
                                    item2: estateData['EM'],
                                    item3: estateData['Nama_assist'] || '-',
                                    item4: estateData['All_skor'],
                                    item5: estateData['rankEST'],
                                };

                                const rowData = Object.values(dataItems);

                                rowData.forEach((item, cellIndex) => {
                                    const cell = createTableCell(item, "text-center");
                                    if (cellIndex === 2) {
                                        const item3nama = dataItems.item3;
                                        if (item3nama.trim() === "VACANT") { // Use trim to remove leading/trailing spaces
                                            cell.style.color = "red";
                                        } else {
                                            cell.style.color = "black";
                                        }
                                    }

                                    if (cellIndex === 3) {
                                        const item4Value = parseFloat(dataItems.item4); // Convert to a number
                                        if (item4Value >= 95) {
                                            cell.style.backgroundColor = "#609cd4";
                                            cell.style.color = "black";
                                        } else if (item4Value >= 85 && item4Value < 95) {
                                            cell.style.backgroundColor = "#08b454";
                                            cell.style.color = "black";
                                        } else if (item4Value >= 75 && item4Value < 85) {
                                            cell.style.backgroundColor = "#fffc04";
                                            cell.style.color = "black";
                                        } else if (item4Value >= 65 && item4Value < 75) {
                                            cell.style.backgroundColor = "#ffc404";
                                            cell.style.color = "black";
                                        } else {
                                            cell.style.backgroundColor = "red";
                                            cell.style.color = "black";
                                        }
                                    }

                                    tr.appendChild(cell);
                                });

                                tbody3.appendChild(tr);
                            });
                        } else {
                            console.log("arrEst3 is null or undefined");
                        }
                    } else {
                        console.log("mutubuah_est[2] is undefined");
                    }

                    var tbody3 = document.getElementById('weeks3');

                    if (mutuBuah_wil[2] !== undefined) {
                        var arrWIl3 = mutuBuah_wil[2][1];
                        let item3s = '-';
                        queryAsisten.forEach((asisten) => {
                            if (asisten[1].est === wil3 && asisten[1].afd === 'GM') {
                                item3s = asisten[1].nama;
                            }
                        });
                        if (arrWIl3 !== null && arrWIl3 !== undefined) {
                            const tm = document.createElement('tr');

                            const dataitemc = {
                                item1: wil3,
                                item2: 'GM',
                                item3: item3s,
                                item4: arrWIl3['All_skor'],
                                item5: arrWIl3['rankWil'],
                            };

                            const rowDatac = Object.values(dataitemc);

                            rowDatac.forEach((item, cellIndex) => {
                                const cell = createTableCell(item, "text-center");
                                if (cellIndex <= 2) {
                                    cell.style.backgroundColor = "#e8ecdc";
                                    cell.style.color = "black";
                                } else if (cellIndex === 3) {
                                    bgest(cell, item);
                                }

                                tm.appendChild(cell);
                            });

                            tbody3.appendChild(tm);
                        } else {
                            console.log("arrWIl3 is null or undefined");
                        }
                    } else {
                        console.log("mutuBuah_wil[2] is undefined");
                    }


                    var arrTbody1 = mutu_buah[0]?.[1] || [];

                    if (mutu_buah[3]) {
                        var tab4 = mutu_buah[3][1];
                    }
                    // var tab4 = mutu_buah[3][1];
                    var tbody4 = document.getElementById('plasmas1');

                    if (tab4 !== null && tab4 !== undefined) {
                        Object.entries(tab4).forEach(([estateName, estateData]) => {
                            Object.entries(estateData).forEach(([key2, data], index) => {
                                const tr = document.createElement('tr');

                                const dataItems = {
                                    item1: estateName,
                                    item2: key2,
                                    item3: data['nama_asisten'] || '-',
                                    item4: data['All_skor'],
                                    item5: data['rankAFD'],
                                };

                                const rowData = Object.values(dataItems);

                                rowData.forEach((item, cellIndex) => {
                                    const cell = createTableCell(item, "text-center");

                                    if (cellIndex === 2) {
                                        const item3nama = dataItems.item3;
                                        if (item3nama.trim() === "VACANT") { // Use trim to remove leading/trailing spaces
                                            cell.style.color = "red";
                                        } else {
                                            cell.style.color = "black";
                                        }
                                    }

                                    if (cellIndex === 3) {
                                        const item4Value = parseFloat(dataItems.item4); // Convert to a number
                                        if (item4Value >= 95) {
                                            cell.style.backgroundColor = "#609cd4";
                                            cell.style.color = "black";
                                        } else if (item4Value >= 85 && item4Value < 95) {
                                            cell.style.backgroundColor = "#08b454";
                                            cell.style.color = "black";
                                        } else if (item4Value >= 75 && item4Value < 85) {
                                            cell.style.backgroundColor = "#fffc04";
                                            cell.style.color = "black";
                                        } else if (item4Value >= 65 && item4Value < 75) {
                                            cell.style.backgroundColor = "#ffc404";
                                            cell.style.color = "black";
                                        } else {
                                            cell.style.backgroundColor = "red";
                                            cell.style.color = "black";
                                        }
                                    }

                                    tr.appendChild(cell);
                                });

                                tbody4.appendChild(tr);
                            });
                        });
                    } else {
                        console.log("tab4 is null or undefined");
                    }

                    var tbody4 = document.getElementById('plasmas1');

                    if (mutubuah_est[3] !== undefined) {
                        var arrEst4 = mutubuah_est[3][1];

                        if (arrEst4 !== null && arrEst4 !== undefined) {
                            Object.entries(arrEst4).forEach(([estateName, estateData]) => {
                                const tr = document.createElement('tr');

                                const dataItems = {
                                    item1: estateName,
                                    item2: estateData['EM'],
                                    item3: estateData['Nama_assist'] || '-',
                                    item4: estateData['All_skor'],
                                    item5: estateData['rankEST'],
                                };

                                const rowData = Object.values(dataItems);

                                rowData.forEach((item, cellIndex) => {
                                    const cell = createTableCell(item, "text-center");
                                    if (cellIndex === 2) {
                                        const item3nama = dataItems.item3;
                                        if (item3nama.trim() === "VACANT") { // Use trim to remove leading/trailing spaces
                                            cell.style.color = "red";
                                        } else {
                                            cell.style.color = "black";
                                        }
                                    }

                                    if (cellIndex === 3) {
                                        const item4Value = parseFloat(dataItems.item4); // Convert to a number
                                        if (item4Value >= 95) {
                                            cell.style.backgroundColor = "#609cd4";
                                            cell.style.color = "black";
                                        } else if (item4Value >= 85 && item4Value < 95) {
                                            cell.style.backgroundColor = "#08b454";
                                            cell.style.color = "black";
                                        } else if (item4Value >= 75 && item4Value < 85) {
                                            cell.style.backgroundColor = "#fffc04";
                                            cell.style.color = "black";
                                        } else if (item4Value >= 65 && item4Value < 75) {
                                            cell.style.backgroundColor = "#ffc404";
                                            cell.style.color = "black";
                                        } else {
                                            cell.style.backgroundColor = "red";
                                            cell.style.color = "black";
                                        }
                                    }

                                    tr.appendChild(cell);
                                });

                                tbody4.appendChild(tr);
                            });
                        } else {
                            console.log("arrEst4 is null or undefined");
                        }
                    } else {
                        console.log("mutubuah_est[3] is undefined");
                    }


                    var tbody4 = document.getElementById('plasmas1');
                    const tl = document.createElement('tr');

                    if (mutuBuah_wil[3] !== undefined) {
                        var arrWIl3 = mutuBuah_wil[3][1];
                        let item3s = '-';
                        queryAsisten.forEach((asisten) => {
                            if (asisten[1].est === wil4 && asisten[1].afd === 'GM') {
                                item3s = asisten[1].nama;
                            }
                        });
                        if (arrWIl3 !== null && arrWIl3 !== undefined) {
                            const dataOm = {
                                item1: wil4,
                                item2: 'GM',
                                item3: item3s,
                                item4: (arrWIl3['All_skor'] < 0) ? 0 : arrWIl3['All_skor'],
                                item5: arrWIl3['rankWil'],
                            };

                            const rowOm = Object.values(dataOm);

                            rowOm.forEach((item, cellIndex) => {
                                const cell = createTableCell(item, "text-center");
                                if (cellIndex === 2) {
                                    const item3nama = dataItems.item3;
                                    if (item3nama.trim() === "VACANT") { // Use trim to remove leading/trailing spaces
                                        cell.style.color = "red";
                                    } else {
                                        cell.style.color = "black";
                                    }
                                }

                                if (cellIndex === 3) {
                                    const item4Value = parseFloat(dataItems.item4); // Convert to a number
                                    if (item4Value >= 95) {
                                        cell.style.backgroundColor = "#609cd4";
                                        cell.style.color = "black";
                                    } else if (item4Value >= 85 && item4Value < 95) {
                                        cell.style.backgroundColor = "#08b454";
                                        cell.style.color = "black";
                                    } else if (item4Value >= 75 && item4Value < 85) {
                                        cell.style.backgroundColor = "#fffc04";
                                        cell.style.color = "black";
                                    } else if (item4Value >= 65 && item4Value < 75) {
                                        cell.style.backgroundColor = "#ffc404";
                                        cell.style.color = "black";
                                    } else {
                                        cell.style.backgroundColor = "red";
                                        cell.style.color = "black";
                                    }
                                }

                                tl.appendChild(cell);
                            });

                            tbody4.appendChild(tl);
                        } else {
                            console.log("arrWIl3 is null or undefined");
                        }
                    } else {
                        console.log("mutuBuah_wil[3] is undefined");
                    }


                    var regionals = regIonal;
                    // console.log(regionals);
                    var headregional = document.getElementById('theadregs');
                    const trreg = document.createElement('tr');

                    const dataReg = {
                        // item1: regIonal[0] && regIonal[0][1] && regIonal[0][1].regional !== undefined ? regIonal[0][1].regional : '-',
                        item1: regionaltab[0][1]['nama'],
                        // item2: regIonal[0] && regIonal[0][1] && regIonal[0][1].jabatan !== undefined ? regIonal[0][1].jabatan : '-',
                        item2: regionaltab[0][1]['jabatan'],
                        // item3: regIonal[0] && regIonal[0][1] && regIonal[0][1].nama_asisten !== undefined ? regIonal[0][1].nama_asisten : '-',
                        item3: regionaltab[0][1]['nama_rh'],
                        item4: regIonal[0] && regIonal[0][1] && regIonal[0][1].all_skorYear !== undefined ? regIonal[0][1].all_skorYear : '-',
                    };
                    const rowREG = Object.values(dataReg);
                    rowREG.forEach((item, cellIndex) => {
                        const cell = createTableCell(item, "text-center");
                        if (cellIndex === 2) {
                            const item3nama = dataItems.item3;
                            if (item3nama.trim() === "VACANT") { // Use trim to remove leading/trailing spaces
                                cell.style.color = "red";
                            } else {
                                cell.style.color = "black";
                            }
                        }

                        if (cellIndex === 3) {
                            const item4Value = parseFloat(dataItems.item4); // Convert to a number
                            if (item4Value >= 95) {
                                cell.style.backgroundColor = "#609cd4";
                                cell.style.color = "black";
                            } else if (item4Value >= 85 && item4Value < 95) {
                                cell.style.backgroundColor = "#08b454";
                                cell.style.color = "black";
                            } else if (item4Value >= 75 && item4Value < 85) {
                                cell.style.backgroundColor = "#fffc04";
                                cell.style.color = "black";
                            } else if (item4Value >= 65 && item4Value < 75) {
                                cell.style.backgroundColor = "#ffc404";
                                cell.style.color = "black";
                            } else {
                                cell.style.backgroundColor = "red";
                                cell.style.color = "black";
                            }
                        }
                        trreg.appendChild(cell);
                    });
                    headregional.appendChild(trreg);



                },
                error: function(jqXHR, textStatus, errorThrown) {
                    closeLoadingSwal(showTahung);
                    showErrorSwal('No data found.');
                },
            });
        }


        let showFindingYear
        document.getElementById('showFindingYear').onclick = function() {
            showFindingYear = showLoadingSwal();

            dashboardFindingYear()
        }



        function dashboardFindingYear() {
            $('#bodyFind').empty()


            var reg = ''
            var tahun = ''

            var _token = $('input[name="_token"]').val();
            var reg = document.getElementById('regFindingYear').value
            var tahun = document.getElementById('yearFinding').value


            $.ajax({
                url: "{{ route('findingIsueTahun') }}",
                method: "GET",
                data: {
                    reg,
                    tahun,
                    _token: _token
                },
                success: function(result) {
                    closeLoadingSwal(showFindingYear);
                    var parseResult = JSON.parse(result)
                    var findingIsue = Object.entries(parseResult['finding_nemo'])


                    const Findissu = findingIsue.map(([_, data]) => ({

                        est: data.est,
                        temuan: data.foto_temuan,
                        visit: data.visit,
                    }));

                    // console.log(Findissu);

                    var arrTbody1 = Findissu

                    var tbody1 = document.getElementById('bodyFind');
                    //         $('#thead1').empty()
                    // $('#thead2').empty()
                    // $('#thead3').empty()

                    arrTbody1.forEach(element => {
                        const {
                            est: item1,
                            temuan: item2,
                            visit: item3
                        } = element;

                        const tr = document.createElement('tr');
                        const itemElement1 = document.createElement('td');
                        const itemElement2 = document.createElement('td');
                        const itemElement3 = document.createElement('td');

                        itemElement1.textContent = item1;
                        itemElement1.colSpan = 3;

                        itemElement2.textContent = item2;
                        itemElement2.colSpan = 5;

                        const downloadButton = document.createElement('a');
                        downloadButton.classList.add('btn');

                        if (item2 != 0) {
                            downloadButton.href = `/cetakmutubuah_id/${item1}/${tahun}/${reg}`;
                            downloadButton.classList.add('btn-primary');
                            // downloadButton.target = '_blank';
                        } else {
                            downloadButton.classList.add('btn-secondary');
                            downloadButton.setAttribute('disabled', '');
                        }

                        const downloadIcon = document.createElement('i');
                        downloadIcon.classList.add('nav-icon', 'fa', 'fa-download');
                        downloadButton.appendChild(downloadIcon);

                        itemElement3.appendChild(downloadButton);
                        itemElement2.colSpan = 3;

                        tr.append(itemElement1, itemElement2, itemElement3);
                        tbody1.appendChild(tr);
                    });


                },
                error: function(jqXHR, textStatus, errorThrown) {
                    showTahung = showFindingYear();
                    showErrorSwal('No data found.');
                },
            });
        }

        let btnShoWeekdata
        document.getElementById('btnShoWeekdata').onclick = function() {
            btnShoWeekdata = showLoadingSwal();
            getweekData();
        }

        function getweekData() {
            // $('#data_weekTab').empty()
            $('#data_weekTab2').empty()
            var reg = '';
            var bulan = '';
            var reg = document.getElementById('regional_data').value;
            var bulan = document.getElementById('inputDateMonth').value;
            var _token = $('input[name="_token"]').val();

            // console.log(dateWeek);

            $.ajax({
                url: "{{ route('getWeekData') }}",
                method: "GET",
                data: {
                    reg,
                    bulan,
                    _token: _token
                },
                success: function(result) {

                    closeLoadingSwal(btnShoWeekdata);
                    var data = result['data_tabel']
                    let inc = 1
                    let tableBody = document.getElementById('data_weekTab2');
                    // console.log(data);
                    Object.keys(data).forEach((regname) => {
                        let regs = data[regname];
                        Object.keys(regs).forEach((estatename) => {
                            let estate = regs[estatename];
                            Object.keys(estate).forEach((afdelingname) => {
                                let afdeling = estate[afdelingname];
                                let item1 = inc++;
                                let estatelink;

                                if (afdelingname === 'ESTATE') {
                                    estatelink = document.createElement('a');
                                    estatelink.href = `detailtmutubuah/${estatename}/${afdelingname}/${bulan}`;
                                    estatelink.target = '_blank';
                                    estatelink.innerText = afdelingname;
                                } else {
                                    estatelink = afdelingname;
                                }

                                let rowData = [
                                    item1, estatename, estatelink, afdeling.nama_staff, afdeling.Jumlah_janjang,
                                    afdeling.tnp_brd, afdeling.persenTNP_brd, afdeling.krg_brd, afdeling.persenKRG_brd,
                                    afdeling.total_jjg, afdeling.persen_totalJjg, afdeling.skor_total, afdeling.jjg_matang,
                                    afdeling.persen_jjgMtang, afdeling.skor_jjgMatang, afdeling.lewat_matang,
                                    afdeling.persen_lwtMtng, afdeling.skor_lewatMTng, afdeling.janjang_kosong,
                                    afdeling.persen_kosong, afdeling.skor_kosong, afdeling.vcut, afdeling.vcut_persen,
                                    afdeling.vcut_skor, afdeling.abnormal, afdeling.abnormal_persen, afdeling.rat_dmg,
                                    afdeling.rd_persen, afdeling.TPH, afdeling.persen_krg, afdeling.skor_kr,
                                    afdeling.All_skor, afdeling.kategori
                                ];

                                let tr = document.createElement('tr');
                                rowData.forEach((data, cellIndex) => {
                                    let cell = document.createElement('td');
                                    if (cellIndex === 2 && afdelingname === 'ESTATE') {
                                        cell.appendChild(estatelink); // Append the link element for 'ESTATE'
                                    } else {
                                        cell.textContent = data;
                                    }
                                    if (cellIndex === 32 || cellIndex === 31) {
                                        let colorData = rowData[31]; // Correctly retrieve the data from index 32
                                        setBackgroundColorCell(cell, colorData);
                                    }

                                    if (afdelingname === 'ESTATE') {
                                        if (cellIndex !== 31 && cellIndex !== 32) {
                                            cell.style.backgroundColor = '#61C9D6'; // Set color for all cells except 31 and 32
                                        }
                                    }
                                    cell.classList.add('text-center');


                                    if (cellIndex === 1) {
                                        cell.classList.add('sticky-cell');
                                        cell.style.left = '0';
                                    } else if (cellIndex === 2) {
                                        cell.classList.add('sticky-cell');
                                        cell.style.left = '30px'; // You can adjust this value based on the width of the 4th column
                                    }


                                    tr.appendChild(cell);
                                });

                                tableBody.appendChild(tr);
                            });
                        })
                    });
                },
                error: function(xhr, status, error) {
                    closeLoadingSwal(btnShoWeekdata);
                    showErrorSwal('No data found.');
                    console.log("An error occurred:", error);
                }


            });
        }
        let show_sbithn
        document.getElementById('show_sbithn').onclick = function() {
            show_sbithn = showLoadingSwal();
            sbi_tahun()
        }

        var list_month = <?php echo json_encode($list_bulan); ?>;


        var selectedTahun = ''; // Global variable to store the selected tahun value
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

        function sbi_tahun() {
            $('#tahun1').empty()
            $('#tahun2').empty()
            $('#tahun3').empty()
            $('#tahun4').empty()
            $('#tahunreg').empty()

            // document.getElementById('sbiGraphYear').addEventListener('click', function() {
            //     const selectedEstateValue = estSidakYear.value;
            //     const selectedEstateText = estSidakYear.options[estSidakYear.selectedIndex].text;

            selectedTahun = document.getElementById('sbi_tahun').value; //
            //     // Perform any actions with the selected estate text here
            // });

            var reg = '';
            var tahun = '';
            var reg = document.getElementById('reg_sbiThun').value;
            var tahun = document.getElementById('sbi_tahun').value;
            var _token = $('input[name="_token"]').val();

            $.ajax({
                url: "{{ route('getahun_sbi') }}",
                method: "GET",
                data: {
                    reg: reg,
                    tahun: tahun,
                    _token: _token
                },
                headers: {
                    'X-CSRF-TOKEN': _token
                },
                success: function(result) {

                    closeLoadingSwal(show_sbithn);

                    var parseResult = JSON.parse(result)
                    var mutu_buah = Object.entries(parseResult['mutu_buah'])
                    var rhdata = Object.entries(parseResult['rhdata'])
                    var rekapmua = parseResult['rekapmua']
                    // console.log(rhdata);
                    //         $('#tahun1').empty()
                    // $('#tahun2').empty()
                    // $('#tahun3').empty()
                    let table1 = mutu_buah[0]
                    let table2 = mutu_buah[1]
                    let table3 = mutu_buah[2]

                    //     $('#tbody1Year').empty()
                    // $('#tbody2Year').empty()
                    // $('#tbody3Year').empty()
                    var theadreg = document.getElementById('tahunreg');

                    // console.log(regional);

                    let tr = document.createElement('tr')
                    let reg1 = rhdata[1][1]
                    let reg2 = rhdata[3][1]
                    let reg3 = '-'
                    // let reg4 = rhdata[0][1]
                    let reg4 = rhdata[0][1]

                    // Check if item4 is less than 0, and set it to 0 if true
                    reg4 = (reg4 < 0) ? 0 : reg4;
                    // let reg4 = '-'
                    let regElement1 = document.createElement('td')
                    let regElement2 = document.createElement('td')
                    let regElement3 = document.createElement('td')
                    let regElement4 = document.createElement('td')

                    regElement1.classList.add("text-center")
                    regElement2.classList.add("text-center")
                    regElement3.classList.add("text-center")
                    regElement4.classList.add("text-center")

                    regElement1.innerText = reg1;
                    regElement2.innerText = reg2;
                    regElement3.innerText = reg3;
                    regElement4.innerText = reg4;
                    setBackgroundColor(regElement4, reg4);
                    tr.appendChild(regElement1)
                    tr.appendChild(regElement2)
                    tr.appendChild(regElement3)
                    tr.appendChild(regElement4)

                    theadreg.appendChild(tr)
                    // console.log(table1);
                    var trekap1 = document.getElementById('tahun1');
                    Object.keys(table1[1]).forEach(key => {
                        Object.keys(table1[1][key]).forEach(subKey => {
                            let item1 = table1[1][key][subKey]['est'];
                            let item2 = table1[1][key][subKey]['afd'];
                            let item3 = table1[1][key][subKey]['nama']
                            let item4 = table1[1][key][subKey]['total_score'];
                            let item5 = table1[1][key][subKey]['rank'] ?? '-';
                            item4 = (item4 < 0) ? 0 : item4;

                            let bg = table1[1][key][subKey]['bgcolor'];

                            // Create table row and cell for each 'total' value
                            let tr = document.createElement('tr');
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
                            itemElement5.innerText = item5

                            setBackgroundColor(itemElement4, item4);
                            tr.style.backgroundColor = bg;

                            tr.appendChild(itemElement1)
                            tr.appendChild(itemElement2)
                            tr.appendChild(itemElement3)
                            tr.appendChild(itemElement4)
                            tr.appendChild(itemElement5)
                            trekap1.appendChild(tr);
                        });
                    });

                    var trekap2 = document.getElementById('tahun2');
                    Object.keys(table2[1]).forEach(key => {
                        Object.keys(table2[1][key]).forEach(subKey => {
                            let item1 = table2[1][key][subKey]['est'];
                            let item2 = table2[1][key][subKey]['afd'];
                            let item3 = table2[1][key][subKey]['nama'] ?? '-'
                            let item4 = table2[1][key][subKey]['total_score'];
                            let item5 = table2[1][key][subKey]['rank'] ?? '-';
                            item4 = (item4 < 0) ? 0 : item4;

                            let bg = table2[1][key][subKey]['bgcolor'];

                            // Create table row and cell for each 'total' value
                            let tr = document.createElement('tr');
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
                            itemElement5.innerText = item5

                            setBackgroundColor(itemElement4, item4);
                            tr.style.backgroundColor = bg;

                            tr.appendChild(itemElement1)
                            tr.appendChild(itemElement2)
                            tr.appendChild(itemElement3)
                            tr.appendChild(itemElement4)
                            tr.appendChild(itemElement5)
                            trekap2.appendChild(tr);
                        });
                    });

                    var trekap3 = document.getElementById('tahun3');
                    Object.keys(table3[1]).forEach(key => {
                        Object.keys(table3[1][key]).forEach(subKey => {
                            let item1 = table3[1][key][subKey]['est'];
                            let item2 = table3[1][key][subKey]['afd'];
                            let item3 = table3[1][key][subKey]['nama']
                            let item4 = table3[1][key][subKey]['total_score'];
                            let item5 = table3[1][key][subKey]['rank'] ?? '-';
                            item4 = (item4 < 0) ? 0 : item4;

                            let bg = table3[1][key][subKey]['bgcolor'];

                            // Create table row and cell for each 'total' value
                            let tr = document.createElement('tr');
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
                            itemElement5.innerText = item5

                            setBackgroundColor(itemElement4, item4);
                            tr.style.backgroundColor = bg;

                            tr.appendChild(itemElement1)
                            tr.appendChild(itemElement2)
                            tr.appendChild(itemElement3)
                            tr.appendChild(itemElement4)
                            tr.appendChild(itemElement5)
                            trekap3.appendChild(tr);
                        });
                    });

                    Object.entries(rekapmua).forEach(([key, value]) => {
                        let tr = document.createElement('tr');

                        let itemElement1 = document.createElement('td');
                        let itemElement2 = document.createElement('td');
                        let itemElement3 = document.createElement('td');
                        let itemElement4 = document.createElement('td');
                        let itemElement5 = document.createElement('td');
                        itemElement1.classList.add("text-center")
                        itemElement2.classList.add("text-center")
                        itemElement3.classList.add("text-center")
                        itemElement4.classList.add("text-center")
                        itemElement5.classList.add("text-center")
                        itemElement1.innerText = key;
                        itemElement2.innerText = key;
                        itemElement3.innerText = value['Nama_assist'];
                        itemElement4.innerText = value['All_skor'];
                        itemElement5.innerText = '-'

                        setBackgroundColor(itemElement4, value['All_skor']);

                        tr.appendChild(itemElement1);
                        tr.appendChild(itemElement2);
                        tr.appendChild(itemElement3);
                        tr.appendChild(itemElement4);
                        tr.appendChild(itemElement5);

                        trekap3.appendChild(tr);
                    });
                    sbi_chart();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    closeLoadingSwal(show_sbithn);
                    showErrorSwal('No data found.');
                }
            });
        }
        var options_tahun = {

            series: [{
                name: '',
                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
            }],
            chart: {
                background: '#ffffff',
                height: 350,
                type: 'bar'
            },
            plotOptions: {
                bar: {
                    distributed: true
                }
            },

            colors: [
                '#00FF00',
                '#00FF00',
                '#00FF00',
                '#00FF00',
                '#3063EC',
                '#3063EC',
                '#3063EC',
                '#3063EC',
                '#FF8D1A',
                '#FF8D1A',
                '#FF8D1A',
                '#FF8D1A',
                '#00ffff'
            ],

            stroke: {
                curve: 'smooth'
            },
            xaxis: {
                type: '',
                // categories: estate
                categories: list_month
            }
        };


        var sbi1 = new ApexCharts(document.querySelector("#matang_tahun"), options_tahun);
        var sbi2 = new ApexCharts(document.querySelector("#mentah_tahun"), options_tahun);
        var sbi3 = new ApexCharts(document.querySelector("#lewatmatang_tahun"), options_tahun);
        var sbi4 = new ApexCharts(document.querySelector("#jangkos_tahun"), options_tahun);
        var sbi5 = new ApexCharts(document.querySelector("#tidakvcut_tahun"), options_tahun);
        var sbi6 = new ApexCharts(document.querySelector("#karungbrondolan_tahun"), options_tahun);

        sbi1.render();
        sbi2.render();
        sbi3.render();
        sbi4.render();
        sbi5.render();
        sbi6.render();

        document.getElementById('hiddenInput').value = document.getElementById('sbi_tahun').value;
        document.getElementById('sbi_tahun').addEventListener('change', function() {
            document.getElementById('hiddenInput').value = this.value;
        });


        let sbiGraphYear
        document.getElementById('sbiGraphYear').onclick = function() {
            sbiGraphYear = showLoadingSwal();
            sbi_chart()
        }

        function sbi_chart() {
            var estSidakYear = document.getElementById('estSidakYear');
            var est = estSidakYear.value;
            var estText = '';

            // Check if there are any options in the estSidakYear select element
            if (estSidakYear.options.length > 0) {
                estText = estSidakYear.options[estSidakYear.selectedIndex].text;
            }

            var tahun = document.getElementById('hiddenInput').value; // Get the value from the hidden input element

            var _token = $('input[name="_token"]').val();

            $.ajax({
                url: "{{ route('chartsbi_oke') }}",
                method: "GET",
                data: {
                    est: est,
                    estText: estText,
                    tahun: tahun, // Add the tahun value to the AJAX request
                    _token: _token,
                },
                headers: {
                    'X-CSRF-TOKEN': _token,
                },
                success: function(result) {

                    closeLoadingSwal(sbiGraphYear);
                    var parseResult = JSON.parse(result)


                    var chart_mentah = Object.entries(parseResult['chart_mentah'])
                    var chart_lewatmatang = Object.entries(parseResult['chart_lewatmatang'])
                    var chart_janjangkosong = Object.entries(parseResult['chart_janjangkosong'])
                    var chart_vcut = Object.entries(parseResult['chart_vcut'])
                    var chart_karung = Object.entries(parseResult['chart_karung'])

                    var chart_matang = Object.entries(parseResult['chart_matang']);
                    // console.log(chart_matang);

                    var matang = '[';
                    if (chart_matang.length > 0 && chart_matang[0].length > 0) {
                        var data = chart_matang[0][1];
                        matang += data.January + ',';
                        matang += data.February + ',';
                        matang += data.March + ',';
                        matang += data.April + ',';
                        matang += data.May + ',';
                        matang += data.June + ',';
                        matang += data.July + ',';
                        matang += data.August + ',';
                        matang += data.September + ',';
                        matang += data.October + ',';
                        matang += data.November + ',';
                        matang += data.December;
                    }
                    matang += ']';

                    // console.log(matang);

                    var mentah = '[';
                    if (chart_mentah.length > 0 && chart_mentah[0].length > 0) {
                        var data = chart_mentah[0][1];
                        mentah += data.January + ',';
                        mentah += data.February + ',';
                        mentah += data.March + ',';
                        mentah += data.April + ',';
                        mentah += data.May + ',';
                        mentah += data.June + ',';
                        mentah += data.July + ',';
                        mentah += data.August + ',';
                        mentah += data.September + ',';
                        mentah += data.October + ',';
                        mentah += data.November + ',';
                        mentah += data.December;
                    }
                    mentah += ']';


                    var lewatmatangs = '[';
                    if (chart_lewatmatang.length > 0 && chart_lewatmatang[0].length > 0) {
                        var data = chart_lewatmatang[0][1];
                        lewatmatangs += data.January + ',';
                        lewatmatangs += data.February + ',';
                        lewatmatangs += data.March + ',';
                        lewatmatangs += data.April + ',';
                        lewatmatangs += data.May + ',';
                        lewatmatangs += data.June + ',';
                        lewatmatangs += data.July + ',';
                        lewatmatangs += data.August + ',';
                        lewatmatangs += data.September + ',';
                        lewatmatangs += data.October + ',';
                        lewatmatangs += data.November + ',';
                        lewatmatangs += data.December;
                    }
                    lewatmatangs += ']';

                    var jjgkosongs = '[';
                    if (chart_janjangkosong.length > 0 && chart_janjangkosong[0].length > 0) {
                        var data = chart_janjangkosong[0][1];
                        jjgkosongs += data.January + ',';
                        jjgkosongs += data.February + ',';
                        jjgkosongs += data.March + ',';
                        jjgkosongs += data.April + ',';
                        jjgkosongs += data.May + ',';
                        jjgkosongs += data.June + ',';
                        jjgkosongs += data.July + ',';
                        jjgkosongs += data.August + ',';
                        jjgkosongs += data.September + ',';
                        jjgkosongs += data.October + ',';
                        jjgkosongs += data.November + ',';
                        jjgkosongs += data.December;
                    }
                    jjgkosongs += ']';

                    var vcuts = '[';
                    if (chart_vcut.length > 0 && chart_vcut[0].length > 0) {
                        var data = chart_vcut[0][1];
                        vcuts += data.January + ',';
                        vcuts += data.February + ',';
                        vcuts += data.March + ',';
                        vcuts += data.April + ',';
                        vcuts += data.May + ',';
                        vcuts += data.June + ',';
                        vcuts += data.July + ',';
                        vcuts += data.August + ',';
                        vcuts += data.September + ',';
                        vcuts += data.October + ',';
                        vcuts += data.November + ',';
                        vcuts += data.December;
                    }
                    vcuts += ']';

                    var karungs = '[';
                    if (chart_karung.length > 0 && chart_karung[0].length > 0) {
                        var data = chart_karung[0][1];
                        karungs += data.January + ',';
                        karungs += data.February + ',';
                        karungs += data.March + ',';
                        karungs += data.April + ',';
                        karungs += data.May + ',';
                        karungs += data.June + ',';
                        karungs += data.July + ',';
                        karungs += data.August + ',';
                        karungs += data.September + ',';
                        karungs += data.October + ',';
                        karungs += data.November + ',';
                        karungs += data.December;
                    }
                    karungs += ']';


                    var matang_chart = JSON.parse(matang)
                    var mentah_chart = JSON.parse(mentah)

                    var lwtmatang_chart = JSON.parse(lewatmatangs)
                    var janjangksng_chart = JSON.parse(jjgkosongs)
                    var vcuts_chart = JSON.parse(vcuts)
                    var karungs_chart = JSON.parse(karungs)



                    // console.log(matang_chart);

                    // console.log(formatEst);


                    // console.log(matang_chart);
                    sbi1.updateSeries([{
                        name: 'matang',
                        data: matang_chart,

                    }])

                    ///////////
                    sbi2.updateSeries([{
                        name: 'mentah',
                        data: mentah_chart,

                    }])

                    ///////////
                    sbi3.updateSeries([{
                        name: 'lewat matang',
                        data: lwtmatang_chart,

                    }])

                    /////////
                    sbi4.updateSeries([{
                        name: 'janjang kosong',
                        data: janjangksng_chart,

                    }])

                    ////////
                    sbi5.updateSeries([{
                        name: 'vcut ',
                        data: vcuts_chart,

                    }])

                    ///////
                    sbi6.updateSeries([{
                        name: 'karung',
                        data: karungs_chart,

                    }])




                },
                error: function(jqXHR, textStatus, errorThrown) {
                    closeLoadingSwal(sbiGraphYear);
                    showErrorSwal('No data found.');
                },
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const selectedTab = localStorage.getItem('selectedTab');

            if (selectedTab) {
                // Get the tab element
                const tabElement = document.getElementById(selectedTab);

                if (tabElement) {
                    // Click the tab to activate it
                    tabElement.click();
                }

                // Clear the selectedTab value from local storage
                localStorage.removeItem('selectedTab');
            }
        });

        function getFindData() {
            $('#bodyIssue').empty()

            var regional = $("#regFind").val();
            var date = $("#dateFind").val();
            var _token = $('input[name="_token"]').val();

            $.ajax({
                url: "{{ route('findIssueSmb') }}",
                method: "get",
                data: {
                    regional: regional,
                    date: date,
                    _token: _token
                },
                success: function(result) {

                    closeLoadingSwal(showFinding);
                    var parseResult = JSON.parse(result)
                    var dataFinding = Object.entries(parseResult['dataFinding'])

                    dataFinding.forEach(function(value, key) {
                        dataFinding[key].forEach(function(value1, key1) {
                            Object.entries(value1).forEach(function(value2, key2) {
                                if (value2[0] != 0) {
                                    // console.log(value2)
                                    var tbody1 = document.getElementById('bodyIssue');

                                    let tr = document.createElement('tr')

                                    let item1 = value2[0]
                                    let item2 = value2[1]['total_temuan']

                                    let itemElement1 = document.createElement('td')
                                    let itemElement2 = document.createElement('td')
                                    let itemElement3 = document.createElement('td')

                                    itemElement1.innerText = item1
                                    itemElement2.innerText = item2
                                    itemElement3.innerHTML = '<a href="/cetakFiSmb/' + value2[0] + '/' + date + '" class="btn btn-primary" target="_blank"><i class="nav-icon fa fa-download"></i></a>'

                                    tr.appendChild(itemElement1)
                                    tr.appendChild(itemElement2)
                                    tr.appendChild(itemElement3)
                                    tbody1.appendChild(tr)
                                }
                            });
                        });
                    });
                },
                error: function(xhr, status, error) {
                    closeLoadingSwal(showFinding);
                    showErrorSwal('No data found.');
                    console.log("An error occurred:", error);
                }
            });
        }

        function sortTable(tableId, columnIndex, compareFunction, numRowsToSort, useSecondColumn = false) {
            const tbody = document.getElementById(tableId);
            const allRows = Array.from(tbody.rows);
            const rows = allRows.slice(0, numRowsToSort);
            const excludedRows = allRows.slice(numRowsToSort);

            rows.sort((a, b) => {
                let aValue = a.cells[columnIndex].innerText.toLowerCase();
                let bValue = b.cells[columnIndex].innerText.toLowerCase();

                if (useSecondColumn) {
                    aValue += '|' + a.cells[columnIndex + 1].innerText.toLowerCase();
                    bValue += '|' + b.cells[columnIndex + 1].innerText.toLowerCase();
                }

                let result = compareFunction(aValue, bValue);

                // If the values are equal, sort based on the name in column 2 (index 1)
                if (result === 0 && !useSecondColumn) {
                    let nameA = a.cells[1].innerText.trim().toLowerCase();
                    let nameB = b.cells[1].innerText.trim().toLowerCase();
                    return nameA.localeCompare(nameB);
                }

                return result;
            });

            // Remove existing rows from the tbody
            while (tbody.firstChild) {
                tbody.removeChild(tbody.firstChild);
            }

            // Append sorted rows to the tbody
            rows.forEach(row => tbody.appendChild(row));

            // Append excluded rows to the tbody without sorting
            excludedRows.forEach(row => tbody.appendChild(row));
        }


        document.addEventListener('DOMContentLoaded', function() {
            const estBtn = document.getElementById('sort-est-btn');
            const rankBtn = document.getElementById('sort-rank-btn');
            const showBtn = document.getElementById('btnShow');
            const regionalSelect = document.getElementById('regionalPanen');

            let currentRegion = regionalSelect.value;

            let firstClick = true; // Add a flag to indicate the first click

            estBtn.addEventListener('click', () => {
                if (firstClick) {
                    showBtn.click();
                    firstClick = false; // Set the flag to false after the first click
                }
                handleSort('est');
            });
            rankBtn.addEventListener('click', () => {
                if (firstClick) {
                    showBtn.click();
                    firstClick = false; // Set the flag to false after the first click
                }
                handleSort('rank');
            });
            showBtn.addEventListener('click', handleShow);

            // Define the new handleShow function
            function handleShow() {
                currentRegion = regionalSelect.value;
                handleFilterShow(currentRegion);
            }

            function handleSort(sortType) {
                const sortMap = {
                    '1': {
                        est: [16, 18, 17, 3],
                        rank: [16, 18, 17, 3]
                    },
                    '2': {
                        est: [16, 13, 10, 5],
                        rank: [16, 13, 10, 5]
                    },
                    '3': {
                        est: [20, 11, 2, 2],
                        rank: [20, 11, 2, 2]
                    }
                };

                const tbodies = ['week1', 'week2', 'week3', 'plasma1'];

                const columnIndex = sortType === 'est' ? 0 : 4;
                const useSecondColumn = sortType === 'est';

                tbodies.forEach((tableId, index) => {
                    if (sortType === 'rank') {
                        sortTable(tableId, columnIndex, (a, b) => parseInt(a) - parseInt(b), sortMap[currentRegion][sortType][index], useSecondColumn);
                    } else {
                        sortTable(tableId, columnIndex, (a, b) => a.localeCompare(b), sortMap[currentRegion][sortType][index], useSecondColumn);
                    }
                });

            }

            function handleFilterShow(filterShowValue) {
                // Implement your filtering logic here, if necessary
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const estBtn = document.getElementById('sort-est-btnWek');
            const rankBtn = document.getElementById('sort-rank-btnWek');
            const showBtn = document.getElementById('showTahung');
            const regionalSelect = document.getElementById('regionalData');

            let currentRegion = regionalSelect.value;

            let firstClick = true; // Add a flag to indicate the first click

            estBtn.addEventListener('click', () => {
                if (firstClick) {
                    showBtn.click();
                    firstClick = false; // Set the flag to false after the first click
                }
                handleSort('est');
            });
            rankBtn.addEventListener('click', () => {
                if (firstClick) {
                    showBtn.click();
                    firstClick = false; // Set the flag to false after the first click
                }
                handleSort('rank');
            });
            showBtn.addEventListener('click', handleShow);

            // Define the new handleShow function
            function handleShow() {
                currentRegion = regionalSelect.value;
                handleFilterShow(currentRegion);
            }

            function handleSort(sortType) {
                const sortMap = {
                    '1': {
                        est: [16, 18, 17, 3],
                        rank: [16, 18, 17, 3]
                    },
                    '2': {
                        est: [16, 13, 10, 5],
                        rank: [16, 13, 10, 5]
                    },
                    '3': {
                        est: [20, 11, 2, 2],
                        rank: [20, 11, 2, 2]
                    }
                };


                const tbodies2 = ['weeks1', 'weeks2', 'weeks3', 'plasmas1'];
                const columnIndex = sortType === 'est' ? 0 : 4;
                const useSecondColumn = sortType === 'est';

                tbodies2.forEach((tableId, index) => {
                    if (sortType === 'rank') {
                        sortTable(tableId, columnIndex, (a, b) => parseInt(a) - parseInt(b), sortMap[currentRegion][sortType][index], useSecondColumn);
                    } else {
                        sortTable(tableId, columnIndex, (a, b) => a.localeCompare(b), sortMap[currentRegion][sortType][index], useSecondColumn);
                    }
                });
            }

            function handleFilterShow(filterShowValue) {
                // Implement your filtering logic here, if necessary
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const estBtn = document.getElementById('sort-est-btnSBI');
            const rankBtn = document.getElementById('sort-rank-btnSBI');
            const showBtn = document.getElementById('show_sbithn');
            const regionalSelect = document.getElementById('reg_sbiThun');

            let currentRegion = regionalSelect.value;

            let firstClick = true; // Add a flag to indicate the first click

            estBtn.addEventListener('click', () => {
                if (firstClick) {
                    showBtn.click();
                    firstClick = false; // Set the flag to false after the first click
                }
                handleSort('est');
            });
            rankBtn.addEventListener('click', () => {
                if (firstClick) {
                    showBtn.click();
                    firstClick = false; // Set the flag to false after the first click
                }
                handleSort('rank');
            });
            showBtn.addEventListener('click', handleShow);

            // Define the new handleShow function
            function handleShow() {
                currentRegion = regionalSelect.value;
                handleFilterShow(currentRegion);
            }

            function handleSort(sortType) {
                const sortMap = {
                    '1': {
                        est: [16, 18, 17, 3],
                        rank: [16, 18, 17, 3]
                    },
                    '2': {
                        est: [16, 13, 10, 5],
                        rank: [16, 13, 10, 5]
                    },
                    '3': {
                        est: [20, 11, 2, 2],
                        rank: [20, 11, 2, 2]
                    }
                };


                const tbodies2 = ['tahun1', 'tahun2', 'tahun3', 'tahun4'];
                const columnIndex = sortType === 'est' ? 0 : 4;
                const useSecondColumn = sortType === 'est';

                tbodies2.forEach((tableId, index) => {
                    if (sortType === 'rank') {
                        sortTable(tableId, columnIndex, (a, b) => parseInt(a) - parseInt(b), sortMap[currentRegion][sortType][index], useSecondColumn);
                    } else {
                        sortTable(tableId, columnIndex, (a, b) => a.localeCompare(b), sortMap[currentRegion][sortType][index], useSecondColumn);
                    }
                });
            }

            function handleFilterShow(filterShowValue) {
                // Implement your filtering logic here, if necessary
            }
        });


        var downloadButton = document.getElementById("download-button");
        downloadButton.disabled = true;
        downloadButton.classList.add("disabled");

        // Enable PDF download button when Show button is clicked
        document.getElementById("showTahung").addEventListener("click", function() {
            var weekDate = document.getElementById("dateWeek").value;
            var selectedRegion = document.getElementById("regionalData").value; // Get the selected value

            document.getElementById("tglPDF").value = weekDate;
            document.getElementById("startWeek").value = weekDate;
            document.getElementById("lastWeek").value = weekDate;
            document.getElementById("regPDF").value = selectedRegion;
            // Enable PDF download button
            downloadButton.disabled = false;
            downloadButton.classList.remove("disabled");
        });




        function openNewTabAndSendData() {
            // Define the URL of the new page where you want to send the data
            const newPageUrl = '/getimgqc'; // Replace with the actual URL

            // Retrieve the CSRF token from the input field using jQuery
            var csrfToken = $('input[name="_token"]').val(); // Changed variable name to csrfToken

            // Create an empty form element
            const form = document.createElement('form');
            form.method = 'POST'; // Change the method to POST
            form.action = newPageUrl;

            // Add a hidden input field for the CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);

            // Add hidden input fields for your data
            const tables = [
                document.getElementById('table1'),
                document.getElementById('table2'),
                document.getElementById('table3'),
                document.getElementById('table4')
            ];
            let date = document.getElementById('inputbulan').value;
            let reg = document.getElementById('regionalPanen').value;

            // Track how many tables have been processed
            let tablesProcessed = 0;

            // Function to submit the form when all tables are processed
            function submitFormIfReady() {
                tablesProcessed++;
                if (tablesProcessed === tables.length) {
                    const dateInput = document.createElement('input');
                    dateInput.type = 'hidden';
                    dateInput.name = 'date';
                    dateInput.value = date;

                    const regInput = document.createElement('input');
                    regInput.type = 'hidden';
                    regInput.name = 'reg';
                    regInput.value = reg;
                    const title = document.createElement('input');
                    title.type = 'hidden';
                    title.name = 'title';
                    title.value = 'Sidak Mutu Buah';

                    const href = document.createElement('input');
                    href.type = 'hidden';
                    href.name = 'href';
                    href.value = '/dashboard_mutubuah';

                    form.appendChild(dateInput);
                    form.appendChild(regInput);
                    form.appendChild(title);
                    form.appendChild(href);

                    // Submit the form to open the new tab
                    document.body.appendChild(form);
                    form.submit();
                }
            }

            tables.forEach((table, index) => {
                const options = {
                    scale: 10, // Increase the scale for higher resolution (adjust as needed)
                };

                html2canvas(table, options).then(canvas => {
                    const dataURL = canvas.toDataURL('image/jpeg');
                    const base64Data = dataURL.split(',')[1];

                    // Create a hidden input field for each table's data
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `table${index + 1}`;
                    input.value = base64Data;

                    form.appendChild(input);

                    // Check if it's the last table, then add date and reg and submit
                    if (index === tables.length - 1) {
                        submitFormIfReady();
                    } else {
                        submitFormIfReady();
                    }
                });
            });
        }


        function showLoadingSwal() {
            return Swal.fire({
                title: 'Loading',
                html: '<span class="loading-text">Mohon Tunggu...</span>',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });
        }

        function closeLoadingSwal(swalInstance) {
            if (swalInstance && Swal.isVisible()) {
                Swal.close();
                swalInstance.close();
            }
        }


        function showErrorSwal(message) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: message
            });
        }
        // document.getElementById('btnShoWeekdata').addEventListener('click', function() {
        //     document.getElementById('downloaddatapdf').disabled = false;
        // });
        // $('#downloaddatapdf').click(function() {
        //     var reg = $('#regional_data').val();
        //     var month = $('#inputDateMonth').val();
        //     var _token = $('input[name="_token"]').val();

        //     // Construct the URL
        //     var url = '/pdfmutubuhuahdata/' + reg + '/' + month;

        //     // Open the URL in a new tab
        //     window.open(url, '_blank');
        // });

        document.getElementById('exportForm').addEventListener('submit', function(event) {
            // Prevent the default form submission
            event.preventDefault();

            // Get the selected value from regDataIns select element
            var regDataInsValue = document.getElementById('regional_data').value;

            // Get the value from dateDataIns input element
            var dateDataInsValue = document.getElementById('inputDateMonth').value;

            // Set the values to the hidden inputs
            document.getElementById('getregionalexcel').value = regDataInsValue;
            document.getElementById('getdateexcel').value = dateDataInsValue;


            // Open a new tab/window and submit the form there
            var newWindow = window.open('', '_blank');
            this.target = '_blank';
            this.submit();

            // Close the new tab/window after submission (optional)
            newWindow.close();
        });
        $("#scrennshotimg").click(function() {
            captureTableScreenshot('scrensshot_bulanan', 'REKAPITULASI RANKING NILAI SIDAK PEMERIKSAAN TPH')
        });
    </script>

</x-layout.app>