<x-layout.app>

    <div class="container-fluid">
        <div class="card table_wrapper">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-utama-tab" data-toggle="tab" href="#nav-utama" role="tab" aria-controls="nav-utama" aria-selected="true">Halaman Utama</a>
                    <a class="nav-item nav-link" id="nav-data-tab" data-toggle="tab" href="#nav-data" role="tab" aria-controls="nav-data" aria-selected="false">Data</a>
                    <a class="nav-item nav-link" id="nav-issue-tab" data-toggle="tab" href="#nav-issue" role="tab" aria-controls="nav-issue" aria-selected="false">Finding Issue</a>
                    <a class="nav-item nav-link" id="nav-score-tab" data-toggle="tab" href="#nav-score" role="tab" aria-controls="nav-score" aria-selected="false">Score By Map</a>
                    <a class="nav-item nav-link" id="nav-grafik-tab" data-toggle="tab" href="#nav-grafik" role="tab" aria-controls="nav-grafik" aria-selected="false">Grafik</a>
                </div>
            </nav>

            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-utama" role="tabpanel" aria-labelledby="nav-utama-tab">

                    <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark">
                        <h5><b>REKAPITULASI RANKING NILAI KUALITAS PANEN</b></h5>
                    </div>

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
                                {{ csrf_field() }}
                                <input class="form-control" value="{{ date('Y-m') }}" type="month" name="date" id="inputDate">

                            </div>
                        </div>
                        <button class="btn btn-primary mb-3 ml-3" id="btnShow">Show</button>
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


                    <div class="row justify-content-center" id="screnshot_bulanan">
                        <div class="col-12 col-md-4 " data-regional="1" id="Tab1">
                            <div class="table-responsive">
                                <table class="table table-bordered" style="font-size: 13px;background-color:white" id="tabblan1">
                                    <thead>
                                        <tr bgcolor="#fffc04">
                                            <th colspan="5" id="thead1" style="text-align:center">
                                                WILAYAH I</th>
                                        </tr>
                                        <tr bgcolor="#2044a4" style="color: white">
                                            <th rowspan="2" class="text-center" style="vertical-align: middle;">KEBUN</th>
                                            <th rowspan="2" style="vertical-align: middle;">AFD</th>
                                            <th rowspan="2" style="text-align:center; vertical-align: middle;">Nama
                                            </th>
                                            <th colspan="2" class="text-center">Todate</th>
                                        </tr>

                                        <tr bgcolor="#2044a4" style="color: white">
                                            <th>Score</th>
                                            <th>Rank</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody1">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-12 col-md-4" data-regional="1" id="Tab2">
                            <div class="table-responsive">
                                <table class="table table-bordered" style="font-size: 13px;background-color:white" id="tabblan2">
                                    <thead>
                                        <tr bgcolor="#fffc04">
                                            <th colspan="5" id="thead2" style="text-align:center">
                                                WILAYAH II</th>
                                        </tr>
                                        <tr bgcolor="#2044a4" style="color: white">
                                            <th rowspan="2" class="text-center" style="vertical-align: middle;">KEBUN</th>
                                            <th rowspan="2" style="vertical-align: middle;">AFD</th>
                                            <th rowspan="2" style="text-align:center; vertical-align: middle;">Nama
                                            </th>
                                            <th colspan="2" class="text-center">Todate</th>
                                        </tr>
                                        <tr bgcolor="#2044a4" style="color: white">
                                            <th>Score</th>
                                            <th>Rank</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody2">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 " data-regional="1" id="Tab3">
                            <div class="table-responsive">
                                <table class="table table-bordered" style="font-size: 13px;background-color:white" id="tabblan3">
                                    <thead>
                                        <tr bgcolor="#fffc04">
                                            <th colspan="5" id="thead3" style="text-align:center">
                                                WILAYAH III</th>
                                        </tr>
                                        <tr bgcolor="#2044a4" style="color: white">
                                            <th rowspan="2" class="text-center" style="vertical-align: middle;">KEBUN</th>
                                            <th rowspan="2" style="vertical-align: middle;">AFD</th>
                                            <th rowspan="2" style="text-align:center; vertical-align: middle;">Nama
                                            </th>
                                            <th colspan="2" class="text-center">Todate</th>
                                        </tr>
                                        <tr bgcolor="#2044a4" style="color: white">
                                            <th>Score</th>
                                            <th>Rank</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody3">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>



                    <div class="col-sm-12">
                        <table class="table table-bordered" id="tabblan4">
                            <thead id="theadreg">
                                <tr>
                                    <th colspan="1">REG-I</th>
                                    <th colspan="1">RH-1</th>
                                    <th colspan="1">Akhmad Faisyal</th>
                                    <th colspan="8" style="background-color: white;"></th>
                                </tr>
                            </thead>
                            <tbody style="background-color: whitesmoke">
                                <!-- Isi tabel di sini -->
                            </tbody>
                        </table>
                    </div>
                    <div class="container-fluid">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#mutuAncak">Mutu Ancak</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#mutuTransport">Mutu Transport</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#mutuBuah">Mutu Buah</a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="mutuAncak">
                                <div class="row ml-2 mr-2">
                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <p style="font-size: 15px; text-align: center;"><b><u>BRONDOLAN
                                                            TINGGAL</u></b></p>
                                                <div id="brondolanGraph"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <p style="font-size: 15px; text-align: center;"><b><u>BUAH
                                                            TINGGAL</u></b></p>
                                                <div id="buahGraph"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row ml-2 mr-2">
                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <p style="font-size: 15px; text-align: center;"><b><u>BRONDOLAN
                                                            TINGGAL</u></b></p>
                                                <div id="brondolanGraphWil"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <p style="font-size: 15px; text-align: center;"><b><u>BUAH
                                                            TINGGAL</u></b></p>
                                                <div id="buahGraphWil"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="mutuTransport">
                                <!-- Mutu Transport content here -->
                                <div class="row ml-2 mr-2">
                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <p style="font-size: 15px; text-align: center;"><b><u>BRD DI
                                                            TPH</u></b></p>
                                                <div id="transport_brd"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <p style="font-size: 15px; text-align: center;"><b><u>BUAH DI
                                                            TPH</u></b></p>
                                                <div id="transport_tph"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row ml-2 mr-2">
                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <p style="font-size: 15px; text-align: center;"><b><u>BRD DI
                                                            TPH</u></b></p>
                                                <div id="transportwil_brd"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <p style="font-size: 15px; text-align: center;"><b><u>BUAH DI
                                                            TPH</u></b></p>
                                                <div id="transportwil_buah"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="mutuBuah">
                                <div class="row ml-2 mr-2">
                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <p style="font-size: 15px; text-align: center;"><b><u>JANJANG
                                                            MENTAH</u></b></p>
                                                <div id="mtb_mentah"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <p style="font-size: 15px; text-align: center;"><b><u>JANJANG
                                                            MASAK</u></b></p>
                                                <div id="mtb_masak"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row ml-2 mr-2">
                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <p style="font-size: 15px; text-align: center;"><b><u>JANJANG
                                                            OVERRIPE</u></b></p>
                                                <div id="mtb_over"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <p style="font-size: 15px; text-align: center;"><b><u>BUAH
                                                            ABNORMAL</u></b></p>
                                                <div id="mtb_abnr"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row ml-2 mr-2">
                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <p style="font-size: 15px; text-align: center;"><b><u>JANJANG
                                                            EMPTY</u></b></p>
                                                <div id="mtb_kosong"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <p style="font-size: 15px; text-align: center;"><b><u>TIDAK STANDAR
                                                            VCUT</u></b></p>
                                                <div id="mtb_vcuts"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row ml-2 mr-2">
                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <p style="font-size: 15px; text-align: center;"><b><u>JANJANG
                                                            MENTAH</u></b></p>
                                                <div id="mtb_mentahwil"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <p style="font-size: 15px; text-align: center;"><b><u>JANJANG
                                                            MASAK</u></b></p>
                                                <div id="mtb_masakwil"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row ml-2 mr-2">
                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <p style="font-size: 15px; text-align: center;"><b><u>JANJANG
                                                            OVERRIPE</u></b></p>
                                                <div id="mtb_overwil"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <p style="font-size: 15px; text-align: center;"><b><u>JANJANG
                                                            ABNORMAL</u></b></p>
                                                <div id="mtb_abnrwil"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row ml-2 mr-2">
                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <p style="font-size: 15px; text-align: center;"><b><u>JANJANG
                                                            EMPTY</u></b></p>
                                                <div id="mtb_kosongwil"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <p style="font-size: 15px; text-align: center;"><b><u>TIDAK STANDAR
                                                            VCUT</u></b></p>
                                                <div id="mtb_vcutswil"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>









                    <p class="ml-3 mb-3 mr-3">
                        <button style="width: 100%" class="btn btn-primary" type="button" data-toggle="collapse" data-target="#ShowbyWeek" aria-expanded="false" aria-controls="ShowbyWeek">
                            TAMPILKAN PER MINGGU
                        </button>
                    </p>

                    <div class="collapse" id="ShowbyWeek">

                        <div class="d-flex justify-content-center mb-2 ml-3 mr-3 border border-dark">
                            <h5><b>REKAPITULASI RANKING NILAI KUALITAS PANEN</b></h5>
                        </div>
                        <div class="d-flex justify-content-end mt-3 mb-2 ml-3 mr-3" style="padding-top: 20px;">
                            <div class="row w-100">
                                <div class="col-md-2 offset-md-8">
                                    {{csrf_field()}}
                                    <select class="form-control" id="regionalDataweek">
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
                            <button class="btn btn-primary mb-3 ml-2" id="showWeek">Show</button>
                        </div>

                        <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 ">
                            <button id="sort-afd-btnWeek">Sort by Afd</button>
                            <button id="sort-est-btnWeek">Sort by Rank</button>
                            <button onclick="downloadTablesAsImages2()" id="downladbulanx">Download As Zip</button>
                        </div>
                        <div id="tablesContainer">
                            <div class="tabContainer">
                                <div class="ml-3 mr-3">
                                    <div class="row justify-content-center">
                                        <div class="col-12 col-md-4" data-regional="1" id="Tabs1">
                                            <div class="table-responsive">
                                                <table class="table table-bordered" style="font-size: 13px;background-color:white" id="tableminggu1">
                                                    <thead>
                                                        <tr bgcolor="#fffc04">
                                                            <th colspan="5" id="theads1" style="text-align:center">
                                                                WILAYAH I</th>
                                                        </tr>
                                                        <tr bgcolor="#2044a4" style="color: white">
                                                            <th rowspan="2" class="text-center" style="vertical-align: middle;">KEBUN</th>
                                                            <th rowspan="2" style="vertical-align: middle;">AFD</th>
                                                            <th rowspan="2" style="text-align:center; vertical-align: middle;">
                                                                Nama</th>
                                                            <th colspan="2" class="text-center">Todate</th>
                                                        </tr>
                                                        <tr bgcolor="#2044a4" style="color: white">
                                                            <th>Score</th>
                                                            <th>Rank</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tbodys1">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4" data-regional="1" id="Tabs2">
                                            <div class="table-responsive">
                                                <table class="table table-bordered" style="font-size: 13px;background-color:white" id="tableminggu2">
                                                    <thead>
                                                        <tr bgcolor="#fffc04">
                                                            <th colspan="5" id="theads2" style="text-align:center">
                                                                WILAYAH II</th>
                                                        </tr>
                                                        <tr bgcolor="#2044a4" style="color: white">
                                                            <th rowspan="2" class="text-center" style="vertical-align: middle;">KEBUN</th>
                                                            <th rowspan="2" style="vertical-align: middle;">AFD</th>
                                                            <th rowspan="2" style="text-align:center; vertical-align: middle;">
                                                                Nama</th>
                                                            <th colspan="2" class="text-center">Todate</th>
                                                        </tr>
                                                        <tr bgcolor="#2044a4" style="color: white">
                                                            <th>Score</th>
                                                            <th>Rank</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tbodys2">

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4" data-regional="1" id="Tabs3">
                                            <div class="table-responsive">
                                                <table class="table table-bordered" style="font-size: 13px;background-color:white" id="tableminggu3">
                                                    <thead>
                                                        <tr bgcolor="#fffc04">
                                                            <th colspan="5" id="theads3" style="text-align:center">
                                                                WILAYAH III</th>
                                                        </tr>
                                                        <tr bgcolor="#2044a4" style="color: white">
                                                            <th rowspan="2" class="text-center" style="vertical-align: middle;">KEBUN</th>
                                                            <th rowspan="2" style="vertical-align: middle;">AFD</th>
                                                            <th rowspan="2" style="text-align:center; vertical-align: middle;">
                                                                Nama</th>
                                                            <th colspan="2" class="text-center">Todate</th>
                                                        </tr>
                                                        <tr bgcolor="#2044a4" style="color: white">
                                                            <th>Score</th>
                                                            <th>Rank</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tbodys3">
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

                                </thead>
                                <tbody>
                                    <!-- Isi tabel di sini -->
                                </tbody>
                            </table>
                        </div>


                        <div class="container-fluid">
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#mutuAncakWeek">Mutu
                                        Ancak</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#mutuTransportWeek">Mutu
                                        Transport</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#mutuBuahWeek">Mutu Buah</a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="mutuAncakWeek">
                                    <div class="row ml-2 mr-2">
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p style="font-size: 15px; text-align: center;"><b><u>BRONDOLAN
                                                                TINGGAL</u></b></p>
                                                    <div id="brondolanGraphs"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p style="font-size: 15px; text-align: center;"><b><u>BUAH
                                                                TINGGAL</u></b></p>
                                                    <div id="buahGraphs"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row ml-2 mr-2">
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p style="font-size: 15px; text-align: center;"><b><u>BRONDOLAN
                                                                TINGGAL</u></b></p>
                                                    <div id="brondolanGraphWils"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p style="font-size: 15px; text-align: center;"><b><u>BUAH
                                                                TINGGAL</u></b></p>
                                                    <div id="buahGraphWils"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="mutuTransportWeek">
                                    <!-- Mutu Transport content here -->
                                    <div class="row ml-2 mr-2">
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p style="font-size: 15px; text-align: center;"><b><u>BRD DI
                                                                TPH</u></b></p>
                                                    <div id="weekly_transBRD"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p style="font-size: 15px; text-align: center;"><b><u>BUAH DI
                                                                TPH</u></b></p>
                                                    <div id="weekly_transBRDWIL"></div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row ml-2 mr-2">
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p style="font-size: 15px; text-align: center;"><b><u>BRD DI
                                                                TPH</u></b></p>
                                                    <div id="weekly_transBuah"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p style="font-size: 15px; text-align: center;"><b><u>BUAH DI
                                                                TPH</u></b></p>
                                                    <div id="weekly_transBuahWIL"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="mutuBuahWeek">
                                    <!-- mutu buah -->
                                    <div class="row ml-2 mr-2">
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p style="font-size: 15px; text-align: center;"><b><u>JANJANG
                                                                MENTAH</u></b></p>
                                                    <div id="weekly_mtb_mentah"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p style="font-size: 15px; text-align: center;"><b><u>JANJANG
                                                                MASAK</u></b></p>
                                                    <div id="weekly_mtb_masak"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row ml-2 mr-2">
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p style="font-size: 15px; text-align: center;"><b><u>JANJANG
                                                                OVERRIPE</u></b></p>
                                                    <div id="weekly_mtb_over"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p style="font-size: 15px; text-align: center;"><b><u>JANJANG
                                                                ABNORMAL</u></b></p>
                                                    <div id="weekly_mtb_abnormal"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row ml-2 mr-2">
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p style="font-size: 15px; text-align: center;"><b><u>JANJANG
                                                                KOSONG</u></b></p>
                                                    <div id="weekly_mtb_kosong"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p style="font-size: 15px; text-align: center;"><b><u>TIDAK
                                                                STANDAR VCUT</u></b></p>
                                                    <div id="weekly_mtb_vcut"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row ml-2 mr-2">
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p style="font-size: 15px; text-align: center;"><b><u>JANJANG
                                                                MENTAH</u></b></p>
                                                    <div id="weekly_mtbwil_mentah"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p style="font-size: 15px; text-align: center;"><b><u>JANJANG
                                                                MASAK</u></b></p>
                                                    <div id="weekly_mtbwil_masak"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row ml-2 mr-2">
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p style="font-size: 15px; text-align: center;"><b><u>JANJANG
                                                                OVERRIPE</u></b></p>
                                                    <div id="weekly_mtbwil_over"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p style="font-size: 15px; text-align: center;"><b><u>JANJANG
                                                                ABNORMAL</u></b></p>
                                                    <div id="weekly_mtbwil_abnormal"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row ml-2 mr-2">
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p style="font-size: 15px; text-align: center;"><b><u>JANJANG
                                                                EMPTY</u></b></p>
                                                    <div id="weekly_mtbwil_kosong"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p style="font-size: 15px; text-align: center;"><b><u>TIDAK
                                                                STANDAR VCUT</u></b></p>
                                                    <div id="weekly_mtbwil_vcut"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- end perminggu  -->



                    <p class="ml-3 mb-3 mr-3">
                        <button style="width: 100%" class="btn btn-primary" type="button" data-toggle="collapse" data-target="#showByYear" aria-expanded="false" aria-controls="showByYear">
                            TAMPILKAN PER TAHUN
                        </button>
                    </p>

                    <div class="collapse" id="showByYear">
                        <div class="d-flex justify-content-center mb-2 ml-3 mr-3 border border-dark">
                            <h5><b>REKAPITULASI RANKING NILAI KUALITAS PANEN</b></h5>
                        </div>



                        <div class="filter-container">
                            <div class="date-filter">
                                <select class="form-control" id="yearDate" name="year">
                                    @foreach($datefilter as $item)
                                    <option value="{{$item}}">{{$item}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="regional-filter">
                                {{csrf_field()}}
                                <select class="form-control" id="regionalData">
                                    @foreach($option_reg as $key => $item)
                                    <option value="{{ $item['id'] }}">{{ $item['nama'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="show-button">
                                <button class="btn btn-primary mb-3 ml-3" id="showTahung">Show</button>
                            </div>
                        </div>


                        <div class="ml-4 mr-4">
                            <div class=" row text-center">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sticky-header" style="font-size: 13px">
                                        <thead>
                                            <tr>
                                                @foreach ($arrHeader as $item)
                                                <th>{{ $item }}</th>

                                                @endforeach
                                                <th id="th_year">2023</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tb_tahun">
                                        </tbody>
                                        <thead>
                                            <tr>
                                                <th colspan='17' style="background-color: white;"></th>
                                            </tr>
                                            <tr>
                                                @foreach ($arrHeaderSc as $key => $item)
                                                @if ($key == 0)
                                                <th colspan="3">{{ $item }}</th>
                                                @else
                                                <th>{{ $item }}</th>
                                                @endif
                                                @endforeach
                                                <th id="th_years">2023</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tablewil">

                                        </tbody>

                                        <thead>
                                            <tr>

                                                <th colspan='17' style="background-color: white;"></th>
                                            </tr>

                                            <tr>
                                                @foreach ($arrHeaderReg as $key => $item)
                                                @if ($key == 0)
                                                <th colspan="3">{{ $item }}</th>
                                                @else
                                                <th>{{ $item }}</th>
                                                @endif
                                                @endforeach
                                                <th id="th_years">2023</th>
                                            </tr>
                                        </thead>
                                        <tbody id="reg">

                                        </tbody>

                                        <thead>
                                            <tr>

                                                <th colspan='17' style="background-color: white;"></th>
                                            </tr>
                                            <tr>
                                                @foreach ($arrHeaderTrd as $item)
                                                <th>{{ $item }}</th>
                                                @endforeach
                                                <th id="th_years">2023</th>
                                            </tr>
                                        </thead>
                                        <tbody id="rekapAFD">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <p style="font-size: 15px; text-align: center;"><b><u>BRONDOLAN
                                                    TINGGAL</u></b></p>
                                        <div id="brondolanGraphYear"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <p style="font-size: 15px; text-align: center;"><b><u>BUAH TINGGAL</u></b>
                                        </p>
                                        <div id="buahGraphYear"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <p style="font-size: 15px; text-align: center;"><b><u>BRONDOLAN
                                                    TINGGAL</u></b></p>
                                        <div id="brondolanGraphWilYear"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <p style="font-size: 15px; text-align: center;"><b><u>BUAH TINGGAL</u></b>
                                        </p>
                                        <div id="buahGraphWilYear"></div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>

                </div>

                <div class="tab-pane fade" id="nav-data" role="tabpanel" aria-labelledby="nav-data-tab">
                    <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark">
                        <h5><b>DATA</b></h5>
                    </div>

                    <div class="d-flex justify-content-end mt-3 mb-2 ml-3 mr-3" style="padding-top: 20px;">
                        <div class="row w-100">
                            <div class="col-md-2 offset-md-8">
                                {{csrf_field()}}
                                <select class="form-control" id="regDataIns">
                                    @foreach($option_reg as $key => $item)
                                    <option value="{{ $item['id'] }}">{{ $item['nama'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                {{ csrf_field() }}
                                <input class="form-control" value="{{ date('Y-m') }}" type="month" name="tgl" id="dateDataIns">

                            </div>
                        </div>
                        <button class="btn btn-primary mb-3 ml-3" id="showDataIns">Show</button>
                        <form id="exportForm" action="{{ route('excelqcinspeksi') }}" method="POST">
                            @csrf
                            <input type="hidden" id="getregional" name="getregional">
                            <input type="hidden" id="getdate" name="getdate">
                            <button type="submit" class="btn btn-primary">Export</button>
                        </form>

                    </div>



                    <div class="ml-3 mr-3 mb-3">
                        <div class="row text-center">
                            <table class="table-responsive">
                                <thead style="color: white;">
                                    <tr>
                                        {{-- <th rowspan="3" bgcolor="darkblue">Est.</th>
                                            <th rowspan="3" bgcolor="darkblue">Afd.</th> --}}
                                        <th class="freeze-col align-middle" rowspan="3" bgcolor="#1c5870">Est.</th>
                                        <th class="freeze-col align-middle" rowspan="3" bgcolor="#1c5870">Afd.</th>
                                        <th class="align-middle" colspan="4" rowspan="2" bgcolor="#588434">DATA BLOK
                                            SAMPEL</th>
                                        <th class="align-middle" colspan="17" bgcolor="#588434">Mutu Ancak (MA)</th>
                                        <th class="align-middle" colspan="8" bgcolor="blue">Mutu Transport (MT)</th>
                                        <th class="align-middle" colspan="23" bgcolor="#ffc404" style="color: #000000;">Mutu Buah (MB)
                                        <th class="align-middle" rowspan="3" bgcolor="gray" style="color: #fff;">All
                                            Skor</th>
                                        <th class="align-middle" rowspan="3" bgcolor="gray" style="color: #fff;">
                                            Kategori</th>
                                        </th>
                                    </tr>
                                    <tr>
                                        {{-- Table Mutu Ancak --}}
                                        <th class="align-middle" colspan="6" bgcolor="#588434">Brondolan Tinggal
                                        </th>
                                        <th class="align-middle" colspan="7" bgcolor="#588434">Buah Tinggal</th>
                                        <th class="align-middle" colspan="3" bgcolor="#588434">Pelepah Sengkleh</th>
                                        <th class="align-middle" rowspan="2" bgcolor="#588434">Total Skor</th>

                                        <th class="align-middle" rowspan="2" bgcolor="blue">TPH Sampel</th>
                                        <th class="align-middle" colspan="3" bgcolor="blue">Brd Tinggal</th>
                                        <th class="align-middle" colspan="3" bgcolor="blue">Buah Tinggal</th>
                                        <th class="align-middle" rowspan="2" bgcolor="blue">Total Skor</th>

                                        {{-- Table Mutu Buah --}}
                                        <th class="align-middle" rowspan="2" bgcolor="#ffc404" style="color: #000000;">TPH Sampel</th>
                                        <th class="align-middle" rowspan="2" bgcolor="#ffc404" style="color: #000000;">Total Janjang
                                            Sampel</th>
                                        <th class="align-middle" colspan="3" bgcolor="#ffc404" style="color: #000000;">Mentah (A)</th>
                                        <th class="align-middle" colspan="3" bgcolor="#ffc404" style="color: #000000;">Matang (N)</th>
                                        <th class="align-middle" colspan="3" bgcolor="#ffc404" style="color: #000000;">Lewat Matang
                                            (O)</th>
                                        <th class="align-middle" colspan="3" bgcolor="#ffc404" style="color: #000000;">Janjang Kosong
                                            (E)</th>
                                        <th class="align-middle" colspan="3" bgcolor="#ffc404" style="color: #000000;">Tidak Standar
                                            V-Cut</th>
                                        <th class="align-middle" colspan="2" bgcolor="#ffc404" style="color: #000000;">Abnormal</th>
                                        <th class="align-middle" colspan="3" bgcolor="#ffc404" style="color: #000000;">Penggunaan
                                            Karung Brondolan</th>
                                        <th class="align-middle" rowspan="2" bgcolor="#ffc404" style="color: #000000;">Total Skor</th>
                                    </tr>
                                    <tr>
                                        {{-- Table Mutu Ancak --}}
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
                                        <th class="align-middle" bgcolor="#588434">Pokok </th>
                                        <th class="align-middle" bgcolor="#588434">%</th>
                                        <th class="align-middle" bgcolor="#588434">Skor</th>

                                        <th class="align-middle" bgcolor="blue">Butir</th>
                                        <th class="align-middle" bgcolor="blue">Butir/TPH</th>
                                        <th class="align-middle" bgcolor="blue">Skor</th>
                                        <th class="align-middle" bgcolor="blue">Jjg</th>
                                        <th class="align-middle" bgcolor="blue">Jjg/TPH</th>
                                        <th class="align-middle" bgcolor="blue">Skor</th>
                                        {{-- table mutu Buah --}}
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

                                <tbody id="dataInspeksi">
                                    <!-- <td>PLE</td>
                                        <td>OG</td> -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="nav-issue" role="tabpanel" aria-labelledby="nav-issue-tab">
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
                                            <th rowspan="3" class="align-middle">ESTATE</th>
                                            <th colspan="6">Temuan Pemeriksaan Panen</th>
                                            <th rowspan="3" class="align-middle">Visit 1</th>
                                            <th rowspan="3" class="align-middle">Visit 2</th>
                                            <th rowspan="3" class="align-middle">Visit 3</th>

                                            <th rowspan="3" class="align-middle">Gabungan</th>
                                        </tr>
                                        <tr>
                                            <th rowspan="2" class="align-middle">Jumlah</th>
                                            <th colspan="2">Tuntas</th>
                                            <th colspan="3">Belum Tuntas</thr>
                                        </tr>
                                        <tr>
                                            <th>Jumlah</th>
                                            <th>%</th>
                                            <th>Jumlah</th>
                                            <th colspan="2">%</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bodyFind">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="nav-score" role="tabpanel" aria-labelledby="nav-score-tab">
                    <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark">
                        <h5><b>SCORE KUALITAS PANEN BERDASARKAN BLOK : {{ \Carbon\Carbon::now()->format('Y') }}</b></h5>
                    </div>

                    @php

                    $datenow = \Carbon\Carbon::now()->format('Y');
                    @endphp
                    <div class="d-flex justify-content-end mt-3 mb-2 ml-3 mr-3" style="padding-top: 20px;">
                        <div class="row w-100">
                            <div class="col-md-2 offset-md-8">
                                {{csrf_field()}}
                                <select class="form-control" id="regDataMap">
                                    <option value="" disabled>Pilih REG</option>
                                    <option value="1,2,3" selected>Region 1</option>
                                    <option value="4,5,6">Region 2</option>
                                    <option value="7,8">Region 3</option>
                                    <option value="10,11">Region 4</option>
                                </select>
                            </div>

                            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                {{ csrf_field() }}
                                <select class="form-control" id="estDataMap" disabled>
                                    <option value="" disabled>Pilih EST</option>
                                </select>

                            </div>
                        </div>
                        <button class="btn btn-primary mb-3 ml-3" id="showEstMap">Show</button>
                        <button class="btn btn-primary" id="downloadimgmap">Download Image</button>

                    </div>


                    <div class="ml-4 mr-4 mb-3">
                        <div class="row text-center">


                            <div id="map" style="width: 100%; height: 700px;"></div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="nav-grafik" role="tabpanel" aria-labelledby="nav-grafik-tab">
                    <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark">
                        <h5><b>GRAFIK SCORE</b></h5>
                    </div>

                    <div class="d-flex justify-content-end mt-3 mb-2 ml-3 mr-3" style="padding-top: 20px;">
                        <div class="row w-100">
                            <div class="col-md-4">
                                {{csrf_field()}}
                                <select class="form-control" id="yearGraph" name="yearGraph">
                                    @foreach($datefilter as $item)
                                    <option value="{{$item}}">{{$item}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                {{csrf_field()}}
                                <select class="form-control" id="regGrafik">
                                    @foreach($option_reg as $key => $item)
                                    <option value="{{ $item['id'] }}">{{ $item['nama'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <select class="form-control" id="wilayahGrafik">
                                    <!-- Options will be populated dynamically based on the selected regional value -->
                                </select>
                            </div>

                            <div class="col-md-4">
                                <select class="form-control" id="estateGrafik">
                                    <!-- Options will be populated dynamically based on the selected wilayah preset value -->
                                </select>
                            </div>

                            <!-- <div class="col-md-4">
                                    {{ csrf_field() }}
                                    <select class="form-control" id="estData" name="estData">

                                    </select>
                                </div> -->
                        </div>
                        <button class="btn btn-primary mb-3 ml-3" id="GraphFilter">Show</button>
                        <!-- <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                    {{ csrf_field() }}
                                    <select class="form-control" id="estData" name="estData">
                                        @foreach($listEstate as $item)
                                        <option value={{$item}}>{{$item}}</option>
                                        @endforeach
                                    </select>
                                </div> -->

                    </div>

                    <!-- <div class="row text-center">
                            <div class="col">
                                <div class="card-body">
                                    <p style="font-size: 15px"><b><u>HISTORIS SKOR</u></b></p>
                                    <div id="skorGraph"></div>
                                </div>
                            </div>
                        </div> -->

                    <div class="container-fluid">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#historisEst">Historis Estate</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#historisWil">Historis Wilayah</a>
                            </li>

                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="historisEst">
                                <div class="row text-center">
                                    <div class="col">
                                        <div class="card-body">
                                            <p style="font-size: 15px"><b><u>HISTORIS SKOR Estate</u></b></p>
                                            <div id="skorGraph"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="historisWil">
                                <div class="row text-center">
                                    <div class="col">
                                        <div class="card-body">
                                            <p style="font-size: 15px"><b><u>HISTORIS SKOR Wilayah</u></b></p>
                                            <div id="skorGraphWil"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="container-fluid">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#mutuAncakGraph">Mutu Ancak</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#mutuTransportGraph">Mutu Transport</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#mutuBuahGraph">Mutu Buah</a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="mutuAncakGraph">
                                <div class="row text-center">
                                    <div class="col">
                                        <div class="card-body">
                                            <p style="font-size: 15px"><b><u>HISTORIS BRONDOLAN TINGGAL DI
                                                        ANCAK</u></b></p>
                                            <div id="skorBronGraph"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row text-center">
                                    <div class="col">
                                        <div class="card-body">
                                            <p style="font-size: 15px"><b><u>HISTORIS JANJANG TINGGAL DI
                                                        ANCAK</u></b></p>
                                            <div id="skorJanGraph"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="mutuTransportGraph">

                                <div class="row text-center">
                                    <div class="col">
                                        <div class="card-body">
                                            <p style="font-size: 15px"><b><u>HISTORIS BRD MUTU Transport</u></b></p>
                                            <div id="tr_brd_Graph"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row text-center">
                                    <div class="col">
                                        <div class="card-body">
                                            <p style="font-size: 15px"><b><u>HISTORIS BUAH MUTU Transport</u></b>
                                            </p>
                                            <div id="tr_buah_Graph"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="mutuBuahGraph">
                                <div class="row text-center">
                                    <div class="col">
                                        <div class="card-body">
                                            <p style="font-size: 15px"><b><u>HISTORIS MENTAH MUTU BUAH</u></b></p>
                                            <div id="bh_mentah_Graph"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row text-center">
                                    <div class="col">
                                        <div class="card-body">
                                            <p style="font-size: 15px"><b><u>HISTORIS MASAK MUTU BUAH</u></b></p>
                                            <div id="bh_masak_Graph"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row text-center">
                                    <div class="col">
                                        <div class="card-body">
                                            <p style="font-size: 15px"><b><u>HISTORIS OVER MUTU BUAH</u></b></p>
                                            <div id="bh_over_Graph"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row text-center">
                                    <div class="col">
                                        <div class="card-body">
                                            <p style="font-size: 15px"><b><u>HISTORIS EMPTY MUTU BUAH</u></b></p>
                                            <div id="bh_empty_Graph"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row text-center">
                                    <div class="col">
                                        <div class="card-body">
                                            <p style="font-size: 15px"><b><u>HISTORIS TIDAK S-VCUT MUTU BUAH</u></b>
                                            </p>
                                            <div id="bh_svcut_Graph"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row text-center">
                                    <div class="col">
                                        <div class="card-body">
                                            <p style="font-size: 15px"><b><u>HISTORIS TIDAK ABNORMAL MUTU
                                                        BUAH</u></b></p>
                                            <div id="bh_abnor_Graph"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div id="lottie-container" style="display: none; width: 100%; height: 100%; position: fixed; top: 0; left: 0; z-index: 9999; background-color: rgba(0, 0, 0, 0.5); justify-content: center; align-items: center;">
            <div id="lottie-animation" style="width: 200px; height: 200px;"></div>
        </div>

        <script type="module">
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


            const estDataMapSelect = document.querySelector('#estDataMap');
            const regDataMapSelect = document.querySelector('#regDataMap');
            const buttonimg = document.getElementById('downloadimgmap');
            // Add an event listener to the select element
            // Add an event listener to the select element
            function fetchEstates(region) {
                var _token = $('input[name="_token"]').val();

                // Fetch the estates for the selected region and update the estate filter
                $.ajax({
                    url: "{{ route('fetchEstatesByRegion') }}",
                    method: "POST",
                    data: {
                        region: region,
                        _token: _token
                    },
                    success: function(result) {
                        // Update the estate filter with the fetched estates
                        estDataMapSelect.innerHTML = '<option value="" disabled>Pilih EST</option>';
                        result.estates.forEach(function(estate) {
                            const option = document.createElement('option');
                            option.value = estate;
                            option.textContent = estate;
                            estDataMapSelect.appendChild(option);
                        });
                        estDataMapSelect.disabled = false;
                    }
                });
            }
            regDataMapSelect.addEventListener('change', function() {
                // Get the selected option value
                const selectedOptionValue = this.value;

                // Fetch the estates for the selected region
                fetchEstates(selectedOptionValue);
            });

            // Fetch the estates for the default region when the page loads
            fetchEstates(regDataMapSelect.value);


            var lokasiKerja = "{{ session('lok') }}";
            var isTableHeaderModified = false;

            $(document).ready(function() {
                if ((lokasiKerja == 'Regional II' || lokasiKerja == 'Regional 2') && !isTableHeaderModified) {
                    $('#regionalPanen').val('2');
                    $('#regionalDataweek').val('2');
                    $('#regionalData').val('2');
                    $('#regDataIns').val('2');
                    $('#regFind').val('2');

                    const nons = document.getElementById("Tab1");
                    const nonx = document.getElementById("Tab2");
                    const llon = document.getElementById("Tab3");
                    const non = document.getElementById("Tab4");
                    const tahun1 = document.getElementById("Tabs1");
                    const tahun2 = document.getElementById("Tabs2");
                    const tahun3 = document.getElementById("Tabs3");
                    const tahun4 = document.getElementById("Tabs4");


                    function resetClassList(element) {
                        // element.classList.remove("col-lg-4");
                        // element.classList.add("col-md-4");
                    }



                    nons.style.display = "";
                    nonx.style.display = "";
                    llon.style.display = "";
                    // non.style.display = "none";
                    resetClassList(non);

                    nons.classList.add("col-lg-4");
                    nonx.classList.add("col-lg-4");
                    llon.classList.add("col-lg-4");


                    tahun1.style.display = "";
                    tahun2.style.display = "";
                    tahun3.style.display = "";
                    // tahun4.style.display = "none";
                    resetClassList(tahun4);

                    tahun1.classList.add("col-lg-4");
                    tahun2.classList.add("col-lg-4");
                    tahun3.classList.add("col-lg-4");

                    const thElement1 = document.getElementById('thead1');
                    const thElement2 = document.getElementById('thead2');
                    const thElement3 = document.getElementById('thead3');
                    const thElement4 = document.getElementById('thead3x');
                    const thElement1x = document.getElementById('theads1');
                    const thElement2x = document.getElementById('theads2');
                    const thElement3x = document.getElementById('theads3');
                    const thElement4x = document.getElementById('theads3x');
                    thElement1.textContent = 'WILAYAH IV';
                    thElement2.textContent = 'WILAYAH V';
                    thElement3.textContent = 'WILAYAH VI';
                    // thElement4.textContent = 'PLASMA II';
                    thElement1x.textContent = 'WILAYAH IV';
                    thElement2x.textContent = 'WILAYAH V';
                    thElement3x.textContent = 'WILAYAH VI';
                    // thElement4x.textContent = 'PLASMA II';

                    thElement1.classList.add("text-center");
                    thElement2.classList.add("text-center");
                    thElement3.classList.add("text-center");
                    // thElement4.classList.add("text-center");
                    thElement1x.classList.add("text-center");
                    thElement2x.classList.add("text-center");
                    thElement3x.classList.add("text-center");
                    // thElement4x.classList.add("text-center");

                } else if ((lokasiKerja == 'Regional III' || lokasiKerja == 'Regional 3') && !isTableHeaderModified) {
                    $('#regionalPanen').val('3');
                    $('#regionalDataweek').val('3');
                    $('#regionalData').val('3');
                    $('#regDataIns').val('3');
                    $('#regFind').val('3');
                    // $('#regGrafik').val('3');

                    const thElement1 = document.getElementById('thead1');
                    const thElement2 = document.getElementById('thead2');
                    const thElement3 = document.getElementById('thead3');
                    const thElement4 = document.getElementById('thead3x');
                    const thElement1x = document.getElementById('theads1');
                    const thElement2x = document.getElementById('theads2');
                    const thElement3x = document.getElementById('theads3');
                    const thElement4x = document.getElementById('theads3x');
                    thElement1.textContent = 'WILAYAH VII';
                    thElement2.textContent = 'WILAYAH VIII';
                    thElement3.textContent = 'WILAYAH VIII';
                    // thElement4.textContent = 'PLASMA III';
                    thElement1x.textContent = 'WILAYAH VII';
                    thElement2x.textContent = 'WILAYAH VIII';
                    thElement3x.textContent = 'WILAYAH VIII';
                    // thElement4x.textContent = 'PLASMA III';

                    thElement1.classList.add("text-center");
                    thElement2.classList.add("text-center");
                    thElement3.classList.add("text-center");
                    // thElement4.classList.add("text-center");
                    thElement1x.classList.add("text-center");
                    thElement2x.classList.add("text-center");
                    thElement3x.classList.add("text-center");
                    // thElement4x.classList.add("text-center");


                    const nons = document.getElementById("Tab1");
                    const nonx = document.getElementById("Tab2");
                    const llon = document.getElementById("Tab3");
                    const non = document.getElementById("Tab4");
                    const tahun1 = document.getElementById("Tabs1");
                    const tahun2 = document.getElementById("Tabs2");
                    const tahun3 = document.getElementById("Tabs3");
                    const tahun4 = document.getElementById("Tabs4");


                    function resetClassList(element) {
                        // element.classList.remove("col-lg-4");
                        // element.classList.add("col-md-4");
                    }



                    nons.style.display = "";
                    nonx.style.display = "";
                    llon.style.display = "none";
                    // non.style.display = "none";
                    resetClassList(llon);
                    resetClassList(non);

                    nons.classList.add("col-lg-6");
                    nonx.classList.add("col-lg-6");



                    tahun1.style.display = "";
                    tahun2.style.display = "";
                    tahun3.style.display = "none";
                    // tahun4.style.display = "none";
                    resetClassList(tahun3);
                    resetClassList(tahun4);

                    tahun1.classList.add("col-lg-6");
                    tahun2.classList.add("col-lg-6");


                } else if ((lokasiKerja == 'Regional IV' || lokasiKerja == 'Regional 4') && !isTableHeaderModified) {
                    $('#regionalPanen').val('4');
                    $('#regionalDataweek').val('4');
                    $('#regionalData').val('4');
                    $('#regDataIns').val('4');
                    $('#regFind').val('4');
                    // $('#regGrafik').val('4');


                    const nons = document.getElementById("Tab1");
                    const nonx = document.getElementById("Tab2");
                    const llon = document.getElementById("Tab3");
                    const non = document.getElementById("Tab4");
                    const tahun1 = document.getElementById("Tabs1");
                    const tahun2 = document.getElementById("Tabs2");
                    const tahun3 = document.getElementById("Tabs3");
                    const tahun4 = document.getElementById("Tabs4");


                    function resetClassList(element) {
                        // element.classList.remove("col-lg-4");
                        // element.classList.add("col-md-4");
                    }



                    llon.style.display = "none";
                    // non.style.display = "none";
                    resetClassList(llon);
                    resetClassList(non);
                    nons.classList.add("col-lg-6");
                    nonx.classList.add("col-lg-6");

                    tahun3.style.display = "none";
                    // tahun4.style.display = "none";
                    resetClassList(tahun3);
                    resetClassList(tahun4);
                    tahun1.classList.add("col-lg-6");
                    tahun2.classList.add("col-lg-6");

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

                    const nons = document.getElementById("Tab1");
                    const nonx = document.getElementById("Tab2");
                    const llon = document.getElementById("Tab3");
                    const non = document.getElementById("Tab4");
                    const tahun1 = document.getElementById("Tabs1");
                    const tahun2 = document.getElementById("Tabs2");
                    const tahun3 = document.getElementById("Tabs3");
                    const tahun4 = document.getElementById("Tabs4");


                    function resetClassList(element) {
                        // element.classList.remove("col-lg-4");
                        // element.classList.add("col-md-4");
                    }



                    nons.style.display = "";
                    nonx.style.display = "";
                    llon.style.display = "";
                    // non.style.display = "none";
                    resetClassList(non);

                    nons.classList.add("col-lg-4");
                    nonx.classList.add("col-lg-4");
                    llon.classList.add("col-lg-4");


                    tahun1.style.display = "";
                    tahun2.style.display = "";
                    tahun3.style.display = "";
                    // tahun4.style.display = "none";
                    resetClassList(tahun4);

                    tahun1.classList.add("col-lg-4");
                    tahun2.classList.add("col-lg-4");
                    tahun3.classList.add("col-lg-4");



                }




                isTableHeaderModified = true;

                changeData();
                getFindData();
                dataDashboard();
                dashboard_tahun();
                graphFilter();
                dashboard_week();


                buttonimg.style.display = 'none';
            });




            var googleSat; // Define googleSat variable

            function initializeMap() {
                var map = L.map('map', {
                    preferCanvas: true, // Set preferCanvas to true
                }).setView([-2.2745234, 111.61404248], 13);

                googleSat = L.tileLayer('http://{s}.google.com/vt?lyrs=s&x={x}&y={y}&z={z}', {
                    maxZoom: 20,
                    subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
                }).addTo(map);

                // map.addControl(new L.Control.Fullscreen());

                return map;
            }


            $("#showDataIns").click(function() {
                Swal.fire({
                    title: 'Loading',
                    html: '<span class="loading-text">Mohon Tunggu...</span>',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });
                changeData()
            });

            $("#showFinding").click(function() {
                Swal.fire({
                    title: 'Loading',
                    html: '<span class="loading-text">Mohon Tunggu...</span>',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });
                getFindData()
            });

            var map = null;
            var legendVar = null;

            $('#showEstMap').click(function() {
                buttonimg.style.display = 'block';
                if (map === null) {
                    map = initializeMap();
                } else {
                    map.invalidateSize();
                }

                if (legendVar !== null) {
                    map.removeControl(legendVar);
                }
                Swal.fire({
                    title: 'Loading',
                    html: '<span class="loading-text">Mohon Tunggu...</span>',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });
                removeMarkers();
                getPlotBlok();
                drawEstatePlot();


                // button.style.display = 'none';

            });

            function drawBlokPlot(blok) {
                if (blok.length === 0) {
                    const errorAnimationPath = 'https://assets1.lottiefiles.com/packages/lf20_no386ede.json';
                    showLottieAlert(errorAnimationPath);
                    return;
                }
                var afdelingColors = {
                    // Define the colors for each "afdeling" value
                    OA: 'red',
                    OB: 'blue',
                    OC: 'green',
                    OD: 'yellow',
                    OE: 'purple',
                    OF: 'orange',
                    OG: 'cyan',
                    OH: 'magenta',
                };

                var test;
                var checkboxes = [];
                var legendContainer = null;

                function handleCheckboxChange() {
                    var checkedAfdeling = this.value;

                    // Uncheck all checkboxes except the clicked one
                    checkboxes.forEach(function(checkbox) {
                        if (checkbox !== this) {
                            checkbox.checked = false;
                        }
                    }, this);

                    // Reset the style of all features and labels
                    test.eachLayer(function(layer) {
                        layer.setStyle({
                            fillOpacity: 0.2, // Set a lower opacity for unchecked features
                        });

                        if (layer.myLabel) {
                            layer.myLabel.setStyle({
                                opacity: 0.2, // Set a lower opacity for unchecked labels
                            });
                        }
                    });

                    // Find all the features with the selected "afdeling"
                    var features = test.getLayers().filter(function(layer) {
                        return (
                            layer.myTag === 'BlokMarker' &&
                            layer.feature.properties.afdeling === checkedAfdeling
                        );
                    });

                    if (features.length > 0) {
                        // Highlight the features with the selected "afdeling" by updating their styles
                        features.forEach(function(feature) {
                            feature.setStyle({
                                fillOpacity: 1,
                            });

                            if (feature.myLabel) {
                                feature.myLabel.setStyle({
                                    opacity: 1, // Set full opacity for checked labels
                                });
                            }
                        });
                    }
                }



                function handleFeatureClick(e) {
                    var clickedFeature = e.target;

                    // Get the "afdeling" value of the clicked feature
                    var clickedAfdeling = clickedFeature.feature.properties.afdeling;

                    // Check the corresponding checkbox
                    checkboxes.forEach(function(checkbox) {
                        if (checkbox.value === clickedAfdeling) {
                            checkbox.checked = true;
                            handleCheckboxChange.call(checkbox); // Highlight the "afdeling"
                        } else {
                            checkbox.checked = false;
                        }
                    });

                    // Prevent event propagation to avoid triggering the map's click event
                    L.DomEvent.stopPropagation(e);
                }

                var getPlotStr = '{"type":"FeatureCollection","features":[';

                for (let i = 0; i < blok.length; i++) {
                    getPlotStr +=
                        '{"type":"Feature","properties":{"blok":"' +
                        blok[i][1]['blok'] +
                        '","estate":"' +
                        blok[i][1]['estate'] +
                        '","afdeling":"' +
                        blok[i][1]['afdeling'] +
                        '","nilai":"' +
                        blok[i][1]['nilai'] +
                        '"},"geometry":{"coordinates":[[' +
                        blok[i][1]['latln'] +
                        ']],"type":"Polygon"}}';

                    if (i < blok.length - 1) {
                        getPlotStr += ',';
                    }
                }

                getPlotStr += ']}';

                var blok = JSON.parse(getPlotStr);

                // Remove the previous legend if it exists

                legendContainer = L.control({
                    position: 'topright',
                });

                legendContainer.onAdd = function() {
                    var div = L.DomUtil.create('div', 'legend');
                    var legendHTML = '<h3>Afdeling</h3>';

                    var uniqueAfdelingValues = new Set(
                        blok.features.map(function(feature) {
                            return feature.properties.afdeling;
                        })
                    );

                    uniqueAfdelingValues.forEach(function(afdeling) {
                        var color = afdelingColors[afdeling];
                        var checkboxId = 'checkbox-' + afdeling;

                        legendHTML +=
                            '<div><input type="checkbox" id="' +
                            checkboxId +
                            '" name="afdeling" value="' +
                            afdeling +
                            '">';
                        legendHTML +=
                            '<label for="' +
                            checkboxId +
                            '" style="background-color:' +
                            color +
                            '"></label>' +
                            afdeling +
                            '</div>';
                    });

                    div.innerHTML = legendHTML;

                    // Attach event listeners to the checkboxes
                    checkboxes = div.querySelectorAll('input[name="afdeling"]');
                    checkboxes.forEach(function(checkbox) {
                        checkbox.addEventListener('change', handleCheckboxChange);
                    });

                    return div;
                };
                if (document.getElementsByClassName('legend')[0]) {
                    document.getElementsByClassName('legend')[0].remove();
                }



                test = L.geoJSON(blok, {
                    style: function(feature) {
                        const afdeling = feature.properties.afdeling;
                        var fillColor;

                        if (!afdelingColors[afdeling]) {
                            // Assign a default color if the "afdeling" value is not defined in the colors object
                            fillColor = 'gray';
                        } else {
                            // Assign the color to the fill based on the "nilai" property
                            var nilai = feature.properties.nilai;

                            if (nilai >= 95.0 && nilai <= 100.0) {
                                fillColor = '#4874c4';
                            } else if (nilai >= 85.0 && nilai < 95.0) {
                                fillColor = '#00ff2e';
                            } else if (nilai >= 75.0 && nilai < 85.0) {
                                fillColor = 'yellow';
                            } else if (nilai >= 65.0 && nilai < 75.0) {
                                fillColor = 'orange';
                            } else if (nilai == 0) {
                                fillColor = 'white';
                            } else if (nilai < 65.0) {
                                fillColor = 'red';
                            }
                        }

                        // Assign the color to the outline of the current feature based on its "afdeling" property
                        var outlineColor = afdelingColors[afdeling] || 'gray';

                        return {
                            color: outlineColor,
                            fillColor: fillColor,
                            fillOpacity: 1,
                            opacity: 0.3,
                        };
                    },
                    onEachFeature: function(feature, layer) {
                        layer.myTag = 'BlokMarker';
                        layer.bindPopup(
                            "<p><b>Blok</b>: " +
                            feature.properties.blok +
                            '</p> ' +
                            "<p><b>Afdeling</b>: " +
                            feature.properties.afdeling +
                            '</p>'
                        );

                        var label = L.marker(layer.getBounds().getCenter(), {
                            icon: L.divIcon({
                                className: 'label-blok',
                                html: feature.properties.nilai,
                                iconSize: [50, 10],
                            }),
                        }).addTo(map);

                        titleBlok.push(label);

                        layer.on('click', function(e) {
                            handleFeatureClick(e);
                            layer.openPopup();
                        });
                    },
                }).addTo(map);


                legendContainer.addTo(map);


                if (test.getBounds().isValid()) {
                    map.fitBounds(test.getBounds());
                } else {
                    console.error('Invalid bounds:', test.getBounds());
                }
            }
            var titleEstate = new Array();

            function drawEstatePlot(est, plot) {

                if (typeof est === 'undefined') {

                } else {
                    var geoJsonEst = '{"type"'
                    geoJsonEst += ":"
                    geoJsonEst += '"FeatureCollection",'
                    geoJsonEst += '"features"'
                    geoJsonEst += ":"
                    geoJsonEst += '['

                    geoJsonEst += '{"type"'
                    geoJsonEst += ":"
                    geoJsonEst += '"Feature",'
                    geoJsonEst += '"properties"'
                    geoJsonEst += ":"
                    geoJsonEst += '{"estate"'
                    geoJsonEst += ":"
                    geoJsonEst += '"' + est + '"},'
                    geoJsonEst += '"geometry"'
                    geoJsonEst += ":"
                    geoJsonEst += '{"coordinates"'
                    geoJsonEst += ":"
                    geoJsonEst += '[['
                    geoJsonEst += plot
                    geoJsonEst += ']],"type"'
                    geoJsonEst += ":"
                    geoJsonEst += '"Polygon"'
                    geoJsonEst += '}},'

                    geoJsonEst = geoJsonEst.substring(0, geoJsonEst.length - 1);
                    geoJsonEst += ']}'

                    var estate = JSON.parse(geoJsonEst)

                    var estateObj = L.geoJSON(estate, {
                            onEachFeature: function(feature, layer) {
                                layer.myTag = 'EstateMarker'
                                var label = L.marker(layer.getBounds().getCenter(), {
                                    icon: L.divIcon({
                                        className: 'label-estate',
                                        html: feature.properties.estate,
                                        iconSize: [100, 20]
                                    })
                                }).addTo(map);
                                titleEstate.push(label)
                                layer.addTo(map);
                            }
                        })
                        .addTo(map);

                    map.fitBounds(estateObj.getBounds());
                }
            }

            var titleBlok = new Array();



            var test;
            // Declare a variable to store the previous Lottie animation instance
            let previousAnimation = null;

            function showLottieAlert(animationPath, callback) {
                // Get the Lottie container and animation elements
                const lottieContainer = document.getElementById('lottie-container');
                const lottieAnimation = document.getElementById('lottie-animation');

                // Destroy the previous Lottie animation instance if it exists
                if (previousAnimation) {
                    previousAnimation.destroy();
                }

                // Show the Lottie container
                lottieContainer.style.display = 'flex';

                // Load and play the Lottie animation
                const animation = lottie.loadAnimation({
                    container: lottieAnimation,
                    renderer: 'svg',
                    loop: false,
                    autoplay: true,
                    path: animationPath
                });

                // Save the current Lottie animation instance as the previous one
                previousAnimation = animation;

                // Hide the Lottie container after the animation is completed
                animation.addEventListener('complete', () => {
                    if (callback) {
                        callback();
                    }
                    lottieContainer.style.display = 'none';
                });
            }


            var removeMarkers = function() {
                map.eachLayer(function(layer) {
                    if (layer.myTag && layer.myTag === "EstateMarker") {
                        map.removeLayer(layer);
                    }
                    if (layer.myTag && layer.myTag === "BlokMarker") {
                        map.removeLayer(layer);
                    }
                });

                var i; // Declare i here

                for (i = 0; i < titleBlok.length; i++) {
                    map.removeLayer(titleBlok[i]);
                }
                for (i = 0; i < titleEstate.length; i++) {
                    map.removeLayer(titleEstate[i]);
                }
            }



            let waktuini = @json($datenow);

            // console.log(waktuini);

            function getPlotBlok() {
                var _token = $('input[name="_token"]').val();
                var estData = $("#estDataMap").val();
                var regData = $("#regDataMap").val();
                var date = waktuini
                const params = new URLSearchParams(window.location.search)
                var paramArr = [];
                for (const param of params) {
                    paramArr = param
                }

                $.ajax({
                    url: "{{ route('plotBlok') }}",
                    method: "get",
                    data: {
                        est: estData,
                        regData: regData,
                        date: date,
                        _token: _token
                    },
                    success: function(result) {
                        Swal.close();
                        var plot = JSON.parse(result);
                        const blokResult = Object.entries(plot['blok']);
                        const lgd = Object.entries(plot['legend']);
                        const lowest = Object.entries(plot['lowest']);
                        const highest = Object.entries(plot['highest']);

                        // console.log(lgd);
                        drawBlokPlot(blokResult)

                        var legend = L.control({
                            position: "bottomleft"
                        });
                        legend.onAdd = function(map) {
                            var div = L.DomUtil.create("div", "legend");
                            div.innerHTML += '<table class="table table-bordered text-center" style="height:fit-content; font-size: 14px;"> <thead> <tr bgcolor="lightgrey"> <th rowspan="2" class="align-middle">Score</th><th colspan="2">Blok</th> </tr> <tr bgcolor="lightgrey"> <th>Jumlah</th> <th>%</th> </tr> </thead> <tbody><tr><td bgcolor="#4874c4">Excellent > 95</td><td>' + lgd[0][1] + '</td><td>' + lgd[6][1] + '</td></tr><tr><td bgcolor="#00ff2e">Good > 85</td><td>' + lgd[1][1] + '</td><td>' + lgd[7][1] + '</td></tr><tr><td bgcolor="yellow">Satisfactory > 75</td><td>' + lgd[2][1] + '</td><td>' + lgd[8][1] + '</td></tr><tr><td bgcolor="orange">Fair > 65</td><td>' + lgd[3][1] + '</td><td>' + lgd[9][1] + '</td></tr><tr><td bgcolor="red">Poor < 65</td><td>' + lgd[4][1] + '</td><td>' + lgd[10][1] + '</td></tr><tr><td>Belum Sidak</td><td>' + lgd[5][1] + '</td><td>' + lgd[12][1] + '</td></tr><tr bgcolor="lightgrey"><td>TOTAL</td><td colspan="2">' + lgd[6][1] + '</td></tr><tr bgcolor="lightgrey"><td>Highest</td><td colspan="2">' + highest[2][1] + '</td></tr><tr bgcolor="lightgrey"><td>Lowest</td><td colspan="2">' + lowest[2][1] + '</td></tr></tbody></table>';

                            return div;
                        };
                        legend.addTo(map);

                        legendVar = legend;
                    }
                })
            }

            function getPlotEstate() {
                var _token = $('input[name="_token"]').val();
                var estData = $("#estDataMap").val();
                const params = new URLSearchParams(window.location.search)
                var paramArr = [];
                for (const param of params) {
                    paramArr = param
                }
                $.ajax({
                    url: "{{ route('plotEstate') }}",
                    method: "POST",
                    data: {
                        est: estData,
                        _token: _token
                    },
                    success: function(result) {
                        var estate = JSON.parse(result);

                        // console.log(estate);


                        drawEstatePlot(estate['est'], estate['plot'])
                    }
                })
            }

            function changeData() {
                var regIns = $("#regDataIns").val();
                var dateIns = $("#dateDataIns").val();
                var _token = $('input[name="_token"]').val();

                $.ajax({
                    url: "{{ route('changeDataInspeksi') }}",
                    method: "get",
                    cache: false,
                    data: {
                        _token: _token,
                        regional: regIns,
                        date: dateIns
                    },
                    success: function(result) {
                        Swal.close();
                        $("#dataInspeksi").html(result);

                        // Select all rows in the table except for the first one


                    }

                });
            }

            function getFindData() {
                $('#bodyFind').empty()

                var regional = $("#regFind").val();
                var date = $("#dateFind").val();
                var _token = $('input[name="_token"]').val();

                $.ajax({
                    url: "{{ route('getFindData') }}",
                    method: "get",
                    data: {
                        regional: regional,
                        date: date,
                        _token: _token
                    },
                    success: function(result) {
                        Swal.close();
                        var parseResult = JSON.parse(result)
                        // var dataResFind = Object.entries(parseResult['dataResFind']) //parsing data brondolan ke dalam var list
                        // var dataResFindes = Object.entries(parseResult['dataResFindes']) //parsing data brondolan ke dalam var list

                        // console.log(dataResFind)
                        const dataResFindes = Object.entries(parseResult['dataResFindes']);
                        // console.log(dataResFindes);
                        var newCum = dataResFindes.map(([_, data]) => ({

                            est: data.est,
                            tot: data.total_temuan,
                            tuntan: data.tuntas,
                            perTuntas: data.perTuntas,
                            no_tuntas: data.no_tuntas,
                            perNoTuntas: data.perNoTuntas,
                            visit: data.visit,
                        }));


                        function sortByEst(arr) {
                            arr.sort((a, b) => a.est.localeCompare(b.est));
                            return arr;
                        }

                        const sortedArray = sortByEst(newCum);
                        // console.log(sortedArray);


                        // console.log(newCum);
                        var arrTbody1 = newCum
                        // console.log(arrTbody1);

                        var tbody1 = document.getElementById('bodyFind');
                        //         $('#thead1').empty()
                        // $('#thead2').empty()
                        // $('#thead3').empty()

                        arrTbody1.forEach(element => {

                            var tr = document.createElement('tr')
                            let item1 = element['est']
                            let item2 = element['tot']
                            let item3 = element['tuntan']
                            let item4 = element['perTuntas']
                            let item5 = element['no_tuntas']
                            let item6 = element['perNoTuntas']
                            let visit = element['visit'];

                            // Create table data elements
                            let itemElement1 = document.createElement('td')
                            let itemElement2 = document.createElement('td')
                            let itemElement3 = document.createElement('td')
                            let itemElement4 = document.createElement('td')
                            let itemElement5 = document.createElement('td')
                            let itemElement6 = document.createElement('td')

                            itemElement6.colSpan = 2;

                            // Assign inner text for each table data
                            itemElement1.innerText = item1
                            itemElement2.innerText = item2
                            itemElement3.innerText = item3
                            itemElement4.innerText = item4
                            itemElement5.innerText = item5
                            itemElement6.innerText = item6

                            tr.appendChild(itemElement1)
                            tr.appendChild(itemElement2)
                            tr.appendChild(itemElement3)
                            tr.appendChild(itemElement4)
                            tr.appendChild(itemElement5)
                            tr.appendChild(itemElement6)

                            const maxVisits = 3;

                            for (let i = 1; i <= maxVisits; i++) {
                                let itemElement = document.createElement('td');
                                if (i <= visit) {
                                    itemElement.innerHTML = '<a href="/cetakPDFFI/' + i + '/' + item1 + '/' + date + '" class="btn btn-primary" target="_blank"><i class="bi bi-filetype-pdf"></i></a>';
                                } else {
                                    itemElement.innerHTML = '<a href="#" class="btn btn-secondary" disabled><i class="bi bi-filetype-pdf"></i></a>';
                                }
                                tr.appendChild(itemElement);
                            }

                            // Check if visit is more than 1, if so, create a new button with ID 4
                            let itemElement7 = document.createElement('td');
                            if (visit > 1) {
                                itemElement7.innerHTML = '<a href="/cetakPDFFI/4/' + item1 + '/' + date + '" class="btn btn-primary" target="_blank" id="4"><i class="bi bi-filetype-pdf"></i></a>';
                            } else {
                                itemElement7.innerHTML = '<a href="#" class="btn btn-secondary" disabled><i class="bi bi-filetype-pdf"></i></a>';
                            }
                            tr.appendChild(itemElement7)

                            tbody1.appendChild(tr)
                        });



                    }
                });
            }

            //testing  table ke tengah
            const c = document.getElementById('btnShow');
            const o = document.getElementById('regionalPanen');
            const s = document.getElementById("Tab1");
            const m = document.getElementById("Tab2");
            const l = document.getElementById("Tab3");
            const n = document.getElementById("Tab4");
            const thElement1 = document.getElementById('thead1');
            const thElement2 = document.getElementById('thead2');
            const thElement3 = document.getElementById('thead3');
            const thElement4 = document.getElementById('thead3x');

            function resetClassList(element) {
                // element.classList.remove("col-lg-4");
                // element.classList.add("col-md-4");
            }

            c.addEventListener('click', function() {
                const c = o.value;
                if (c === '1') {
                    s.style.display = "";
                    m.style.display = "";
                    l.style.display = "";
                    // n.style.display = "none";

                    resetClassList(n);



                    thElement1.textContent = 'WILAYAH I';
                    thElement2.textContent = 'WILAYAH II';
                    // thElement4.textContent = 'WILAYAH III';

                    thElement1.classList.add("text-center");
                    thElement2.classList.add("text-center");
                    // thElement4.classList.add("text-center");

                    s.classList.add("col-lg-4");
                    m.classList.add("col-lg-4");
                    l.classList.add("col-lg-4");
                } else if (c === '2') {


                    s.style.display = "";
                    m.style.display = "";
                    l.style.display = "";
                    // n.style.display = "none";

                    resetClassList(n);




                    thElement1.textContent = 'WILAYAH IV';
                    thElement2.textContent = 'WILAYAH V';
                    thElement3.textContent = 'WILAYAH VI';
                    // thElement4.textContent = 'PLASMA II';


                    thElement1.classList.add("text-center");
                    thElement2.classList.add("text-center");
                    // thElement4.classList.add("text-center");

                    s.classList.add("col-lg-4");
                    m.classList.add("col-lg-4");
                    // n.classList.add("col-lg-4");
                    l.classList.add("col-lg-4");
                } else if (c === '3') {
                    s.style.display = "";
                    m.style.display = "";
                    l.style.display = "none";

                    resetClassList(l);
                    resetClassList(n);

                    thElement1.textContent = 'WILAYAH VII';
                    thElement2.textContent = 'WILAYAH VIII';
                    // thElement4.textContent = 'PLASMA III';

                    thElement1.classList.add("text-center");
                    thElement2.classList.add("text-center");
                    // thElement4.classList.add("text-center");

                    s.classList.add("col-lg-6");
                    m.classList.add("col-lg-6");

                } else if (c === '4') {
                    s.style.display = "";
                    m.style.display = "";
                    l.style.display = "none";

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
            //untuk chart

            ///Data test


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
                    categories: ['-', '-', '-', ]
                }
            };

            //chart wil
            var will = {
                series: [{
                    name: 'Butir/Ha Sample',
                    data: [0, 0, 0]
                }],
                chart: {
                    height: 350,
                    type: 'bar'
                },
                plotOptions: {
                    bar: {
                        distributed: true
                    }
                },
                colors: ['#00FF00', '#3063EC', '#FF8D1A'],
                stroke: {
                    curve: 'smooth'
                },
                xaxis: {
                    type: '',
                    categories: ['WIL-I', 'WIL-II', 'WIL-III']
                }
            };




            //chart untuk perbulan
            var chartGrain = new ApexCharts(document.querySelector("#brondolanGraph"), options);
            chartGrain.render();
            var chartFruit = new ApexCharts(document.querySelector("#buahGraph"), options);
            chartFruit.render();

            var chartGrainWil = new ApexCharts(document.querySelector("#brondolanGraphWil"), will);
            chartGrainWil.render();
            var chartFruitWil = new ApexCharts(document.querySelector("#buahGraphWil"), will);
            chartFruitWil.render();
            ///chart untuk pertahun
            var chartGrainYear = new ApexCharts(document.querySelector("#brondolanGraphYear"), options);
            chartGrainYear.render();
            var chartFruitYear = new ApexCharts(document.querySelector("#buahGraphYear"), options);
            chartFruitYear.render();

            var chartGrainWilYear = new ApexCharts(document.querySelector("#brondolanGraphWilYear"), will);
            chartGrainWilYear.render();

            var chartFruitWilYear = new ApexCharts(document.querySelector("#buahGraphWilYear"), will);
            chartFruitWilYear.render();
            //chart untuk perminggu 
            var chartGrains = new ApexCharts(document.querySelector("#brondolanGraphs"), options);
            chartGrains.render();
            var chartFruits = new ApexCharts(document.querySelector("#buahGraphs"), options);
            chartFruits.render();

            var chartGrainWils = new ApexCharts(document.querySelector("#brondolanGraphWils"), will);
            chartGrainWils.render();
            var chartFruitWils = new ApexCharts(document.querySelector("#buahGraphWils"), will);
            chartFruitWils.render();

            // mutu buah
            var mtb_mentah = new ApexCharts(document.querySelector("#mtb_mentah"), options);
            mtb_mentah.render();
            var mtb_masak = new ApexCharts(document.querySelector("#mtb_masak"), options);
            mtb_masak.render();
            var mtb_over = new ApexCharts(document.querySelector("#mtb_over"), options);
            mtb_over.render();
            var mtb_abnr = new ApexCharts(document.querySelector("#mtb_abnr"), options);
            mtb_abnr.render();
            var mtb_kosong = new ApexCharts(document.querySelector("#mtb_kosong"), options);
            mtb_kosong.render();
            var mtb_vcuts = new ApexCharts(document.querySelector("#mtb_vcuts"), options);
            mtb_vcuts.render();

            var mtb_mentahwil = new ApexCharts(document.querySelector("#mtb_mentahwil"), will);
            mtb_mentahwil.render();
            var mtb_masakwil = new ApexCharts(document.querySelector("#mtb_masakwil"), will);
            mtb_masakwil.render();
            var mtb_overwil = new ApexCharts(document.querySelector("#mtb_overwil"), will);
            mtb_overwil.render();
            var mtb_abnrwil = new ApexCharts(document.querySelector("#mtb_abnrwil"), will);
            mtb_abnrwil.render();
            var mtb_kosongwil = new ApexCharts(document.querySelector("#mtb_kosongwil"), will);
            mtb_kosongwil.render();
            var mtb_vcutswil = new ApexCharts(document.querySelector("#mtb_vcutswil"), will);
            mtb_vcutswil.render();

            // mutu transport
            var transprot_brd = new ApexCharts(document.querySelector("#transport_brd"), options);
            transprot_brd.render();
            var transport_buah = new ApexCharts(document.querySelector("#transport_tph"), options);
            transport_buah.render();

            var transportwil_brd = new ApexCharts(document.querySelector("#transportwil_brd"), will);
            transportwil_brd.render();


            var transportwil_buah = new ApexCharts(document.querySelector("#transportwil_buah"), will);
            transportwil_buah.render();

            // mutu transport weekly
            var weekly_transBRD = new ApexCharts(document.querySelector("#weekly_transBRD"), options);
            weekly_transBRD.render();
            var weekly_transBuah = new ApexCharts(document.querySelector("#weekly_transBuah"), options);
            weekly_transBuah.render();

            var weekly_transBRDWIL = new ApexCharts(document.querySelector("#weekly_transBRDWIL"), will);
            weekly_transBRDWIL.render();


            var weekly_transBuahWIL = new ApexCharts(document.querySelector("#weekly_transBuahWIL"), will);
            weekly_transBuahWIL.render();

            // mutu buah weekly
            var weekly_mtb_mentah = new ApexCharts(document.querySelector("#weekly_mtb_mentah"), options);
            weekly_mtb_mentah.render();
            var weekly_mtb_masak = new ApexCharts(document.querySelector("#weekly_mtb_masak"), options);
            weekly_mtb_masak.render();
            var weekly_mtb_over = new ApexCharts(document.querySelector("#weekly_mtb_over"), options);
            weekly_mtb_over.render();
            var weekly_mtb_abnormal = new ApexCharts(document.querySelector("#weekly_mtb_abnormal"), options);
            weekly_mtb_abnormal.render();
            var weekly_mtb_kosong = new ApexCharts(document.querySelector("#weekly_mtb_kosong"), options);
            weekly_mtb_kosong.render();
            var weekly_mtb_vcut = new ApexCharts(document.querySelector("#weekly_mtb_vcut"), options);
            weekly_mtb_vcut.render();

            var weekly_mtbwil_mentah = new ApexCharts(document.querySelector("#weekly_mtbwil_mentah"), will);
            weekly_mtbwil_mentah.render();
            var weekly_mtbwil_masak = new ApexCharts(document.querySelector("#weekly_mtbwil_masak"), will);
            weekly_mtbwil_masak.render();
            var weekly_mtbwil_over = new ApexCharts(document.querySelector("#weekly_mtbwil_over"), will);
            weekly_mtbwil_over.render();
            var weekly_mtbwil_abnormal = new ApexCharts(document.querySelector("#weekly_mtbwil_abnormal"), will);
            weekly_mtbwil_abnormal.render();
            var weekly_mtbwil_kosong = new ApexCharts(document.querySelector("#weekly_mtbwil_kosong"), will);
            weekly_mtbwil_kosong.render();
            var weekly_mtbwil_vcut = new ApexCharts(document.querySelector("#weekly_mtbwil_vcut"), will);
            weekly_mtbwil_vcut.render();


            document.getElementById('btnShow').onclick = function() {
                Swal.fire({
                    title: 'Loading',
                    html: '<span class="loading-text">Mohon Tunggu...</span>',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });
                dataDashboard()
            }

            function dataDashboard() {
                $('#tbody1').empty()
                $('#tbody2').empty()
                $('#tbody3').empty()

                $('#theadreg').empty()
                $('#plbody').empty()

                var date = ''
                var reg = ''
                var _token = $('input[name="_token"]').val();
                var date = document.getElementById('inputDate').value
                var reg = document.getElementById('regionalPanen').value

                // console.log(date);
                $.ajax({
                    url: "{{ route('filter') }}",
                    method: "GET",
                    data: {
                        date,
                        reg,
                        _token: _token
                    },
                    success: function(result) {
                        Swal.close();
                        // console.log(reg);
                        var parseResult = JSON.parse(result)
                        var rekapafd = Object.entries(parseResult['tab_afdeling'])
                        var rekapest = Object.entries(parseResult['tab_estate'])
                        var rekapwil = Object.entries(parseResult['tab_wil'])
                        var dataReg = Object.entries(parseResult['dataReg'])
                        let table1 = rekapafd[0]
                        let table2 = rekapafd[1]
                        let table3 = rekapafd[2] ?? []
                        let table1est = rekapest[0]
                        let table2est = rekapest[1]
                        let table3est = rekapest[2] ?? []
                        let table1wil = rekapwil[0]
                        let table2wil = rekapwil[1]
                        let table3wil = rekapwil[2] ?? []
                        // chart est 
                        var getnameest = parseResult['getnameest'];
                        var cakbrd = parseResult['cakbrd'];
                        var cakbuah = parseResult['cakbuah'];
                        var brdtrans = parseResult['brdtrans'];
                        var buahtrans = parseResult['buahtrans'];
                        var mentahbuah = parseResult['mentahbuah'];
                        var masakbuah = parseResult['masakbuah'];
                        var overbuah = parseResult['overbuah'];
                        var abrbuah = parseResult['abrbuah'];
                        var emptybuah = parseResult['emptybuah'];
                        var vcutbuah = parseResult['vcutbuah'];
                        // chart wil 
                        var getnamewil = parseResult['getnamewil'];
                        var cakbrdwil = parseResult['cakbrdwil'];
                        var cakbuahwil = parseResult['cakbuahwil'];
                        var brdtranswil = parseResult['brdtranswil'];
                        var buahtranswil = parseResult['buahtranswil'];
                        var mentahbuahwil = parseResult['mentahbuahwil'];
                        var masakbuahwil = parseResult['masakbuahwil'];
                        var overbuahwil = parseResult['overbuahwil'];
                        var abrbuahwil = parseResult['abrbuahwil'];
                        var emptybuahwil = parseResult['emptybuahwil'];
                        var vcutbuahwil = parseResult['vcutbuahwil'];
                        // table pertama 
                        var trekap1 = document.getElementById('tbody1');
                        Object.keys(table1[1]).forEach(key => {
                            Object.keys(table1[1][key]).forEach(subKey => {
                                let item1 = table1[1][key][subKey]['est'];
                                let item2 = table1[1][key][subKey]['afd'];
                                let item3 = table1[1][key][subKey]['nama']
                                let item4 = table1[1][key][subKey]['data'] !== 'kosong' ? table1[1][key][subKey]['total_skor'] : '-';


                                // item4 = (item4 < 0) ? 0 : item4;
                                let item5 = table1[1][key][subKey]['rank'] ?? '-';

                                let bg = table1[1][key][subKey]['bgcolor'];

                                // Create table row and cell for each 'total' value
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

                        Object.keys(table1est[1]).forEach(key => {
                            let item1 = table1est[1][key]['est'];
                            let item2 = table1est[1][key]['afd'];
                            let item3 = table1est[1][key]['nama']
                            let item4 = table1est[1][key]['data'] !== 'kosong' ? table1est[1][key]['total_skor'] : '-';

                            // item4 = (item4 < 0) ? 0 : item4;
                            let item5 = table1est[1][key]['rank'] ?? '-';

                            let bg = table1est[1][key]['bgcolor'];

                            // Create table row and cell for each 'total' value

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
                            itemElement5.innerText = item5
                            // itemElement3.style.color === "#609cd4"
                            setBackgroundColor(itemElement4, item4);
                            tr.style.backgroundColor = "#f0f0f0";
                            tr.appendChild(itemElement1)
                            tr.appendChild(itemElement2)
                            tr.appendChild(itemElement3)
                            tr.appendChild(itemElement4)
                            tr.appendChild(itemElement5)
                            trekap1.appendChild(tr);
                        });

                        getwil1(table1wil)




                        // table kedua 
                        var trekap2 = document.getElementById('tbody2');
                        Object.keys(table2[1]).forEach(key => {
                            Object.keys(table2[1][key]).forEach(subKey => {
                                let item1 = table2[1][key][subKey]['est'];
                                let item2 = table2[1][key][subKey]['afd'];
                                let item3 = table2[1][key][subKey]['nama'] ?? '-'
                                let item4 = table2[1][key][subKey]['data'] !== 'kosong' ? table2[1][key][subKey]['total_skor'] : '-';
                                let item5 = table2[1][key][subKey]['rank'] ?? '-';

                                let bg = table2[1][key][subKey]['bgcolor'];

                                // Create table row and cell for each 'total' value

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

                        Object.keys(table2est[1]).forEach(key => {
                            let item1 = table2est[1][key]['est'];
                            let item2 = table2est[1][key]['afd'];
                            let item3 = table2est[1][key]['nama']
                            let item4 = table2est[1][key]['data'] !== 'kosong' ? table2est[1][key]['total_skor'] : '-';

                            let item5 = table2est[1][key]['rank'] ?? '-';

                            let bg = table2est[1][key]['bgcolor'];

                            // Create table row and cell for each 'total' value

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
                            itemElement5.innerText = item5

                            setBackgroundColor(itemElement4, item4);
                            tr.style.backgroundColor = "#f0f0f0";

                            tr.appendChild(itemElement1)
                            tr.appendChild(itemElement2)
                            tr.appendChild(itemElement3)
                            tr.appendChild(itemElement4)
                            tr.appendChild(itemElement5)
                            trekap2.appendChild(tr);
                        });

                        getwil2(table2wil)



                        // tableketiga 
                        if (table3.length > 0) {
                            // console.log('tidak kosong');
                            // console.log(table3);
                            var trekap3 = document.getElementById('tbody3');
                            Object.keys(table3[1]).forEach(key => {
                                Object.keys(table3[1][key]).forEach(subKey => {
                                    let item1 = table3[1][key][subKey]['est'];
                                    let item2 = table3[1][key][subKey]['afd'];
                                    let item3 = table3[1][key][subKey]['nama']
                                    let item4 = table3[1][key][subKey]['data'] !== 'kosong' ? table3[1][key][subKey]['total_skor'] : '-';

                                    let item5 = table3[1][key][subKey]['rank'] ?? '-';

                                    let bg = table3[1][key][subKey]['bgcolor'];

                                    // Create table row and cell for each 'total' value

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

                            Object.keys(table3est[1]).forEach(key => {
                                let item1 = table3est[1][key]['est'];
                                let item2 = table3est[1][key]['afd'];
                                let item3 = table3est[1][key]['nama']
                                let item4 = table3est[1][key]['data'] !== 'kosong' ? table3est[1][key]['total_skor'] : '-';

                                let item5 = table3est[1][key]['rank'] ?? '-';

                                let bg = table3est[1][key]['bgcolor'];

                                // Create table row and cell for each 'total' value

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
                                itemElement5.innerText = item5

                                setBackgroundColor(itemElement4, item4);
                                tr.style.backgroundColor = "#f0f0f0";

                                tr.appendChild(itemElement1)
                                tr.appendChild(itemElement2)
                                tr.appendChild(itemElement3)
                                tr.appendChild(itemElement4)
                                tr.appendChild(itemElement5)
                                trekap3.appendChild(tr);
                            });

                            getwil3(table3wil)


                        } else {
                            // console.log('kosong');
                        }
                        // console.log(dataReg);

                        var theadreg = document.getElementById('theadreg');
                        let item1 = dataReg[2][1]
                        let item2 = 'RH'
                        let item3 = dataReg[1][1]
                        let item4 = dataReg[21][1] + dataReg[33][1] + dataReg[60][1]


                        var tr = document.createElement('tr');
                        let itemElement1 = document.createElement('td');
                        let itemElement2 = document.createElement('td');
                        let itemElement3 = document.createElement('td');
                        let itemElement4 = document.createElement('td');



                        itemElement1.classList.add("text-center");
                        itemElement1.innerText = item1;
                        itemElement2.innerText = item2;
                        itemElement3.innerText = item3;
                        itemElement4.innerText = item4;

                        // itemElement3.style.color === "#609cd4"
                        setBackgroundColor(itemElement4, item4);
                        tr.style.backgroundColor = "#d0e4b4";
                        tr.appendChild(itemElement1)
                        tr.appendChild(itemElement2)
                        tr.appendChild(itemElement3)
                        tr.appendChild(itemElement4)

                        theadreg.appendChild(tr);
                        chartGrain.updateSeries([{
                            name: 'Btr / jjg Panen',
                            data: cakbrd
                        }]);

                        chartGrain.updateOptions({
                            xaxis: {
                                categories: getnameest
                            }
                        });

                        chartFruit.updateSeries([{
                            name: '% Buah tinggal',
                            data: cakbuah
                        }]);

                        chartFruit.updateOptions({
                            xaxis: {
                                categories: getnameest
                            }
                        });

                        mtb_mentah.updateSeries([{
                            name: 'Mentah / TPH',
                            data: mentahbuah
                        }]);

                        mtb_mentah.updateOptions({
                            xaxis: {
                                categories: getnameest
                            }
                        });

                        mtb_masak.updateSeries([{
                            name: 'Masak / TPH',
                            data: masakbuah
                        }]);

                        mtb_masak.updateOptions({
                            xaxis: {
                                categories: getnameest
                            }
                        });

                        mtb_over.updateSeries([{
                            name: 'Over / TPH',
                            data: overbuah
                        }]);

                        mtb_over.updateOptions({
                            xaxis: {
                                categories: getnameest
                            }
                        });

                        mtb_abnr.updateSeries([{
                            name: 'Abnormal / TPH',
                            data: abrbuah
                        }]);

                        mtb_abnr.updateOptions({
                            xaxis: {
                                categories: getnameest
                            }
                        });

                        mtb_kosong.updateSeries([{
                            name: 'Kosong / TPH',
                            data: emptybuah
                        }]);

                        mtb_kosong.updateOptions({
                            xaxis: {
                                categories: getnameest
                            }
                        });

                        mtb_vcuts.updateSeries([{
                            name: 'Tidak Standar Vcut',
                            data: vcutbuah
                        }]);

                        mtb_vcuts.updateOptions({
                            xaxis: {
                                categories: getnameest
                            }
                        });

                        transprot_brd.updateSeries([{
                            name: 'Brd / TPH',
                            data: brdtrans
                        }]);

                        transprot_brd.updateOptions({
                            xaxis: {
                                categories: getnameest
                            }
                        });

                        transport_buah.updateSeries([{
                            name: 'Buah / TPH',
                            data: buahtrans
                        }]);

                        transport_buah.updateOptions({
                            xaxis: {
                                categories: getnameest
                            }
                        });


                        // chart wil 
                        // var getnamewil = parseResult['getnamewil'];
                        // var cakbrdwil = parseResult['cakbrdwil'];
                        // var cakbuahwil = parseResult['cakbuahwil'];
                        // var brdtranswil = parseResult['brdtranswil'];
                        // var buahtranswil = parseResult['buahtranswil'];
                        // var mentahbuahwil = parseResult['mentahbuahwil'];
                        // var masakbuahwil = parseResult['masakbuahwil'];
                        // var overbuahwil = parseResult['overbuahwil'];
                        // var abrbuahwil = parseResult['abrbuahwil'];
                        // var emptybuahwil = parseResult['emptybuahwil'];
                        // var vcutbuahwil = parseResult['vcutbuahwil'];
                        chartGrainWil.updateSeries([{
                            name: 'Btr / jjg Panen',
                            data: cakbrdwil
                        }]);

                        chartGrainWil.updateOptions({
                            xaxis: {
                                categories: getnamewil
                            }
                        });

                        chartFruitWil.updateSeries([{
                            name: '% Buah tinggal',
                            data: cakbuahwil
                        }]);

                        chartFruitWil.updateOptions({
                            xaxis: {
                                categories: getnamewil
                            }
                        });

                        mtb_mentahwil.updateSeries([{
                            name: 'Mentah / TPH',
                            data: mentahbuahwil
                        }]);

                        mtb_mentahwil.updateOptions({
                            xaxis: {
                                categories: getnamewil
                            }
                        });

                        mtb_masakwil.updateSeries([{
                            name: 'Masak / TPH',
                            data: masakbuahwil
                        }]);

                        mtb_masakwil.updateOptions({
                            xaxis: {
                                categories: getnamewil
                            }
                        });

                        mtb_overwil.updateSeries([{
                            name: 'Over / TPH',
                            data: overbuahwil
                        }]);

                        mtb_overwil.updateOptions({
                            xaxis: {
                                categories: getnamewil
                            }
                        });

                        mtb_abnrwil.updateSeries([{
                            name: 'Abnormal / TPH',
                            data: abrbuahwil
                        }]);

                        mtb_abnrwil.updateOptions({
                            xaxis: {
                                categories: getnamewil
                            }
                        });

                        mtb_kosongwil.updateSeries([{
                            name: 'Kosong / TPH',
                            data: emptybuahwil
                        }]);

                        mtb_kosongwil.updateOptions({
                            xaxis: {
                                categories: getnamewil
                            }
                        });

                        mtb_vcutswil.updateSeries([{
                            name: 'Tidak Standar Vcut',
                            data: vcutbuahwil
                        }]);

                        mtb_vcutswil.updateOptions({
                            xaxis: {
                                categories: getnamewil
                            }
                        });

                        transportwil_brd.updateSeries([{
                            name: 'Brd / TPH',
                            data: brdtranswil
                        }]);

                        transportwil_brd.updateOptions({
                            xaxis: {
                                categories: getnamewil
                            }
                        });

                        transportwil_buah.updateSeries([{
                            name: 'Buah / TPH',
                            data: buahtranswil
                        }]);

                        transportwil_buah.updateOptions({
                            xaxis: {
                                categories: getnamewil
                            }
                        });

                    }
                });
            }

            function getwil1(table1wil) {
                var trekap1 = document.getElementById('tbody1');
                let item1 = table1wil[1]['est'];
                let item2 = table1wil[1]['afd'];
                let item3 = table1wil[1]['nama']
                let item4 = table1wil[1]['total_skor'];
                let item5 = table1wil[1]['rank'] ?? '-';
                let bg = table1wil[1]['bgcolor'];

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
                itemElement5.innerText = item5
                // itemElement3.style.color === "#609cd4"
                setBackgroundColor(itemElement4, item4);
                tr.style.backgroundColor = "#FCF086";
                tr.appendChild(itemElement1)
                tr.appendChild(itemElement2)
                tr.appendChild(itemElement3)
                tr.appendChild(itemElement4)
                tr.appendChild(itemElement5)
                trekap1.appendChild(tr);

            }

            function getwil2(table1wil) {
                var trekap1 = document.getElementById('tbody2');
                let item1 = table1wil[1]['est'];
                let item2 = table1wil[1]['afd'];
                let item3 = table1wil[1]['nama']
                let item4 = table1wil[1]['total_skor'];
                let item5 = table1wil[1]['rank'] ?? '-';
                let bg = table1wil[1]['bgcolor'];

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
                itemElement5.innerText = item5
                // itemElement3.style.color === "#609cd4"
                setBackgroundColor(itemElement4, item4);
                tr.style.backgroundColor = "#FCF086";
                tr.appendChild(itemElement1)
                tr.appendChild(itemElement2)
                tr.appendChild(itemElement3)
                tr.appendChild(itemElement4)
                tr.appendChild(itemElement5)
                trekap1.appendChild(tr);

            }

            function getwil3(table1wil) {
                var trekap1 = document.getElementById('tbody3');
                let item1 = table1wil[1]['est'];
                let item2 = table1wil[1]['afd'];
                let item3 = table1wil[1]['nama']
                let item4 = table1wil[1]['total_skor'];
                let item5 = table1wil[1]['rank'] ?? '-';
                let bg = table1wil[1]['bgcolor'];

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
                itemElement5.innerText = item5
                // itemElement3.style.color === "#609cd4"
                setBackgroundColor(itemElement4, item4);
                tr.style.backgroundColor = "#FCF086";
                tr.appendChild(itemElement1)
                tr.appendChild(itemElement2)
                tr.appendChild(itemElement3)
                tr.appendChild(itemElement4)
                tr.appendChild(itemElement5)
                trekap1.appendChild(tr);

            }

            document.getElementById('showTahung').onclick = function() {
                // Show loading screen
                Swal.fire({
                    title: 'Loading',
                    html: '<span class="loading-text">Mohon Tunggu...</span>',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

                dashboard_tahun();
            }

            function dashboard_tahun() {

                $('#tb_tahun').empty()
                $('#tablewil').empty()
                $('#reg').empty()
                $('#rekapAFD').empty()


                var _token = $('input[name="_token"]').val();
                var year = document.getElementById('yearDate').value
                var regData = document.getElementById('regionalData').value



                $.ajax({
                    url: "{{ route('filterTahun') }}",
                    method: "GET",
                    data: {
                        year,
                        regData,
                        _token: _token
                    },
                    success: function(result) {
                        Swal.close();

                        var parseResult = JSON.parse(result)
                        //list estate
                        //untuk tabel pertahun
                        //   var FinalTahun = Object.entries(parseResult['FinalTahun'])

                        var list_tabel = Object.entries(parseResult['FinalTahun'])
                        var total_tahun = Object.entries(parseResult['Final_end'])
                        var rekap_bulan = Object.entries(parseResult['RekapBulan'])
                        var rekap_tahun = Object.entries(parseResult['RekapTahun'])
                        var rekap_bulanwil = Object.entries(parseResult['RekapBulanwil'])
                        // console.log(rekap_bulanwil);
                        var rekap_tahunwil = Object.entries(parseResult['RekapTahunwil'])
                        var RekapBulanReg = Object.entries(parseResult['RekapBulanReg'])
                        var RekapTahunReg = Object.entries(parseResult['RekapTahunReg'])
                        var RekapBulanAFD = Object.entries(parseResult['RekapBulanAFD'])
                        var RekapTahunAFD = Object.entries(parseResult['RekapTahunAFD'])
                        var chart_brdTH = Object.entries(parseResult['chart_brdTAHUN'])
                        var chart_bhTH = Object.entries(parseResult['chart_buahTAHUN'])
                        var chart_brdWIl = Object.entries(parseResult['chartbrdWilTH'])
                        var chart_bhWil = Object.entries(parseResult['chartBhwilTH'])
                        var list_will = Object.entries(parseResult['list_estate'])
                        var estateEST = Object.entries(parseResult['estateEST'])
                        var RekapBulanPlasma = Object.entries(parseResult['RekapBulanPlasma'])
                        var regInpt = regData
                        // console.log(RekapBulanPlasma);

                        var nama_asisten = Object.entries(parseResult['asisten'])
                        const assistants = nama_asisten;

                        const filteredAssistants = assistants.map((assistant) => ({
                            est: assistant[1].est,
                            afd: assistant[1].afd,
                            nama: assistant[1].nama,
                        }));



                        // console.log(RekapTahunReg);


                        var bttEST = '[';
                        chart_brdTH.forEach(element => {
                            bttEST += '"' + element.toString().split(',')[1] + '",';
                        });
                        bttEST = bttEST.substring(0, bttEST.length - 1);
                        bttEST += ']';

                        var bhEST = '[';
                        chart_bhTH.forEach(element => {
                            bhEST += '"' + element.toString().split(',')[1] + '",';
                        });
                        bhEST = bhEST.substring(0, bhEST.length - 1);
                        bhEST += ']';

                        var brdWil = '[';
                        chart_brdWIl.forEach(element => {
                            brdWil += '"' + element.toString().split(',')[1] + '",';
                        });
                        brdWil = brdWil.substring(0, brdWil.length - 1);
                        brdWil += ']';

                        var bhWil = '[';
                        chart_bhWil.forEach(element => {
                            bhWil += '"' + element.toString().split(',')[1] + '",';
                        });
                        bhWil = bhWil.substring(0, bhWil.length - 1);
                        bhWil += ']';


                        var wilayah = '['
                        list_will.forEach(element => {
                            wilayah += '"' + element + '",'
                        });
                        wilayah = wilayah.substring(0, wilayah.length - 1);
                        wilayah += ']'



                        var estate = JSON.parse(wilayah)
                        var bttEst = JSON.parse(bttEST)
                        var bhEST = JSON.parse(bhEST)

                        var brdWil = JSON.parse(brdWil)
                        var bhWil = JSON.parse(bhWil)

                        //untuk table
                        var arrbodywil = rekap_bulan;

                        var yearStr = year.toString();
                        var totalBody = rekap_tahun;

                        const arr = estate


                        let formatEst;

                        if (regInpt === '1') {
                            formatEst = Array.from(arr, (item, index) => {
                                    const value = item.split(',')[1];
                                    return {
                                        index,
                                        value
                                    };
                                })
                                .filter((item) => item.index < 13 || item.index > 15)
                                .map((item) => item.value);

                            formatEst.push("PT.MUA");
                        } else {

                            formatEst = Array.from(arr, (item, index) => {
                                    const value = item.split(',')[1];
                                    return {
                                        index,
                                        value
                                    };
                                })
                                .map((item) => item.value);
                        }

                        const arrEst = estateEST;
                        const est = arrEst.map((item) => item.slice(1)); // remove the first element of each array

                        // console.log(arrbodywil);

                        const array = est

                        const estAndNamaValues = array.map(([{
                            est,
                            nama
                        }]) => ({
                            est,
                            nama
                        }));

                        var tbody1 = document.getElementById('tb_tahun');

                        arrbodywil.forEach((element, index) => {

                            var tr = document.createElement('tr');
                            let namaEst = {
                                "KNE": "Samuel M. Sidabutar",
                                "PLE": "Hamdani",
                                "RDE": "Muhammad Rizaldi",
                                "SLE": "Wahyu Binarko",
                                "BKE": "Andri J. A. Engkang",
                                "KDE": "Ahmad Seno Aji",
                                "RGE": "Angga Putera Perdana",
                                "SGE": "Jurianto",
                                "BGE": "Prawito",
                                "NBE": "Larmaya Aji Pamungkas",
                                "SYE": "Dedi Yusdarty",
                                "UPE": "M. Rasyid Fauzirin"
                            }



                            let item1 = index + 1;
                            let item3 = element[0];
                            let item2 = '-';

                            // // Iterate over estAndNamaValues to get the corresponding nama value
                            for (let i = 0; i < estAndNamaValues.length; i++) {
                                if (estAndNamaValues[i].est === item3) {
                                    item2 = estAndNamaValues[i].nama;
                                    break;
                                }
                            }
                            let item4;

                            filteredAssistants.forEach((element, index) => {
                                const assistantEstate = element['est'];
                                const assistantAfd = element['afd'];
                                // console.log(assistantEstate)

                                if (assistantEstate === item3 && assistantAfd == 'EM') {
                                    item4 = element['nama'];
                                }
                            });

                            if (item4 === undefined) {
                                item4 = '-';
                            } else if (item4 === 'Budi Saputra') {
                                item4 = 'SEPTIAN ADHI P';
                            }
                            // let item2 = slangEst[item3];
                            // // <!-- let item4 = namaEst[item3];
                            // if (item4 === undefined) {
                            //     item4 = '-';
                            // } -->

                            let item5 = element[1].January.bulan_skor;
                            let item6 = element[1].February.bulan_skor;
                            let item7 = element[1].March.bulan_skor;
                            let item8 = element[1].April.bulan_skor;
                            let item9 = element[1].May.bulan_skor;
                            let item10 = element[1].June.bulan_skor;
                            let item11 = element[1].July.bulan_skor;
                            let item12 = element[1].August.bulan_skor;
                            let item13 = element[1].September.bulan_skor;
                            let item14 = element[1].October.bulan_skor;
                            let item15 = element[1].November.bulan_skor;
                            let item16 = element[1].December.bulan_skor;

                            // Find the skor_tahun for the current element
                            let item17;
                            for (var i = 0; i < totalBody.length; i++) {
                                if (totalBody[i][0] == item3) {
                                    item17 = totalBody[i][1]['tahun_skor'];
                                    break;
                                }
                            }

                            let items = [item1, item2, item3, item4, item5, item6, item7, item8, item9, item10, item11, item12, item13, item14, item15, item16, item17];

                            let column = 0;
                            items.forEach(item => {
                                let td = document.createElement('td');
                                if (column >= 4) {
                                    if (item >= 95) {
                                        td.style.backgroundColor = "#0804fc";
                                    } else if (item >= 85 && item < 95) {
                                        td.style.backgroundColor = "#08b454";
                                    } else if (item >= 75 && item < 85) {
                                        td.style.backgroundColor = "#fffc04";
                                    } else if (item >= 65 && item < 75) {
                                        td.style.backgroundColor = "#ffc404";
                                    } else if (item == 0) {
                                        td.style.backgroundColor = "white";
                                    } else {
                                        td.style.backgroundColor = "red";
                                    }
                                }
                                column++;

                                td.innerText = item;
                                tr.appendChild(td);
                            });

                            tbody1.appendChild(tr);
                            var header = document.getElementById('th_year');
                            header.innerText = yearStr;
                        });


                        //bagian untuk table perwil


                        // var yearStr = year.toString();
                        var arrwil = rekap_bulanwil;
                        // console.log(rekap_tahunwil);
                        var totalBodywil = rekap_tahunwil;

                        var tbody2 = document.getElementById('tablewil');

                        arrwil.forEach((element, index) => {
                            var tr = document.createElement('tr')
                            let angka = element[0];
                            if (angka === '1') {
                                angka = 'I';
                            } else if (angka === '2') {
                                angka = 'II';
                            } else if (angka === '3') {
                                angka = 'III';
                            } else if (angka === '4') {
                                angka = 'IV';
                            } else if (angka === '5') {
                                angka = 'V';
                            } else if (angka === '6') {
                                angka = 'VI';
                            } else if (angka === '7') {
                                angka = 'VII';
                            } else if (angka === '8') {
                                angka = 'VIII';
                            } else if (angka === '10') {
                                angka = 'IX';
                            } else if (angka === '11') {
                                angka = 'X';
                            }

                            let item2 = '-';
                            let item1 = angka;
                            let wilKe = 'WIL-' + angka;
                            filteredAssistants.forEach((data, j) => {
                                if (wilKe == data['est'] && data['afd'] == 'GM') {
                                    item2 = data['nama']
                                }
                            });

                            let item3 = element[1].January.skor_bulanTotal;
                            let item4 = element[1].February.skor_bulanTotal;
                            let item5 = element[1].March.skor_bulanTotal;
                            let item6 = element[1].April.skor_bulanTotal;
                            let item7 = element[1].May.skor_bulanTotal;
                            let item8 = element[1].June.skor_bulanTotal;
                            let item9 = element[1].July.skor_bulanTotal;
                            let item10 = element[1].August.skor_bulanTotal;
                            let item11 = element[1].September.skor_bulanTotal;
                            let item12 = element[1].October.skor_bulanTotal;
                            let item13 = element[1].November.skor_bulanTotal;
                            let item14 = element[1].December.skor_bulanTotal;

                            let tahunskor = [];
                            for (var i = 0; i < totalBodywil.length; i++) {
                                if (totalBodywil[i][0] == element[0]) {
                                    tahunskor.push(totalBodywil[i][1]['tahun_skorwil']);
                                    break;
                                }

                            }

                            let item15 = tahunskor;

                            let items = [item1, item2, item3, item4, item5, item6, item7, item8, item9, item10, item11, item12, item13, item14, item15];

                            // Create a td element with colspan="3" for the first three items
                            let td1 = document.createElement('td');
                            td1.colSpan = "3";
                            td1.innerText = item1;
                            tr.appendChild(td1);

                            let column = 1; // Start column after the first three items
                            for (let i = 1; i < items.length; i++) {
                                let item = items[i];
                                let td = document.createElement('td');
                                if (column >= 2) {
                                    if (item >= 95) {
                                        td.style.backgroundColor = "#0804fc";
                                    } else if (item >= 85 && item < 95) {
                                        td.style.backgroundColor = "#08b454";
                                    } else if (item >= 75 && item < 85) {
                                        td.style.backgroundColor = "#fffc04";
                                    } else if (item >= 65 && item < 75) {
                                        td.style.backgroundColor = "#ffc404";
                                    } else if (item == 0) {
                                        td.style.backgroundColor = "white";
                                    } else {
                                        td.style.backgroundColor = "red";
                                    }
                                }
                                column++;
                                td.innerText = item;
                                tr.appendChild(td);
                            }

                            tbody2.appendChild(tr)
                            var header = document.getElementById('th_years');
                            header.innerText = yearStr;
                        });

                        var plasmaWil = RekapBulanPlasma;
                        // console.log(plasmaWil);


                        // if (regInpt === '1') {
                        var plasma = document.getElementById('tablewil');

                        plasmaWil.forEach((element, index) => {
                            var tr = document.createElement('tr')

                            let item1 = element[0];
                            let item2 = '-';
                            filteredAssistants.forEach((data, j) => {
                                if (item1 == data['est'] && data['afd'] == 'GM') {
                                    item2 = data['nama']
                                }
                            });

                            let item3 = element[1].January.Bulan;
                            let item4 = element[1].February.Bulan;
                            let item5 = element[1].March.Bulan;
                            let item6 = element[1].April.Bulan;
                            let item7 = element[1].May.Bulan;
                            let item8 = element[1].June.Bulan;
                            let item9 = element[1].July.Bulan;
                            let item10 = element[1].August.Bulan;
                            let item11 = element[1].September.Bulan;
                            let item12 = element[1].October.Bulan;
                            let item13 = element[1].November.Bulan;
                            let item14 = element[1].December.Bulan;

                            let item15 = element[1].Tahun;

                            let items = [item1, item2, item3, item4, item5, item6, item7, item8, item9, item10, item11, item12, item13, item14, item15];

                            // Create a td element with colspan="3" for the first three items
                            let td1 = document.createElement('td');
                            td1.colSpan = "3";
                            td1.innerText = item1;
                            tr.appendChild(td1);

                            let column = 1; // Start column after the first three items
                            for (let i = 1; i < items.length; i++) {
                                let item = items[i];
                                let td = document.createElement('td');
                                if (column >= 2) {
                                    if (item >= 95) {
                                        td.style.backgroundColor = "#0804fc";
                                    } else if (item >= 85 && item < 95) {
                                        td.style.backgroundColor = "#08b454";
                                    } else if (item >= 75 && item < 85) {
                                        td.style.backgroundColor = "#fffc04";
                                    } else if (item >= 65 && item < 75) {
                                        td.style.backgroundColor = "#ffc404";
                                    } else if (item == 0) {
                                        td.style.backgroundColor = "white";
                                    } else {
                                        td.style.backgroundColor = "red";
                                    }
                                }
                                column++;
                                td.innerText = item;
                                tr.appendChild(td);
                            }

                            plasma.appendChild(tr)

                        });

                        // }

                        // console.log(RekapBulanReg);

                        //table untuk regional 1
                        var regbln = RekapBulanReg;
                        var regthn = RekapTahunReg;
                        // console.log(regbln);

                        var tbody3 = document.getElementById('reg');


                        let regWil = '';
                        let regW = '';


                        if (regInpt === '1') {
                            regW = 'I'
                        } else if (regInpt === '2') {
                            regW = 'II'
                        } else if (regInpt === '3') {
                            regW = 'III'
                        } else if (regInpt === '4') {
                            regW = 'IV'
                        } else if (regInpt === '5') {
                            regW = 'V'
                        } else if (regInpt === '6') {
                            regW = 'VI'
                        } else if (regInpt === '7') {
                            regW = 'VIII'
                        } else if (regInpt === '8') {
                            regW = 'VIII'
                        } else if (regInpt === '10') {
                            regW = 'IX'
                        } else if (regInpt === '11') {
                            regW = 'X'
                        }
                        var tr = document.createElement('tr');

                        let item1 = regW
                        let regKe = 'REG-' + regW
                        let item2 = '-';
                        filteredAssistants.forEach((data, j) => {
                            if (regKe == data['est'] && data['afd'] == 'RH') {
                                item2 = data['nama']
                            }
                        });
                        let item3 = regbln[0][1].skor_bulanTotal;
                        let item4 = regbln[1][1].skor_bulanTotal;
                        let item5 = regbln[2][1].skor_bulanTotal;
                        let item6 = regbln[3][1].skor_bulanTotal;
                        let item7 = regbln[4][1].skor_bulanTotal;
                        let item8 = regbln[5][1].skor_bulanTotal;
                        let item9 = regbln[6][1].skor_bulanTotal;
                        let item10 = regbln[7][1].skor_bulanTotal;
                        let item11 = regbln[8][1].skor_bulanTotal;
                        let item12 = regbln[9][1].skor_bulanTotal;
                        let item13 = regbln[10][1].skor_bulanTotal;
                        let item14 = regbln[11][1].skor_bulanTotal;
                        let item15 = regthn[0][1].tahun_skorwil;

                        let items = [item1, item2, item3, item4, item5, item6, item7, item8, item9, item10, item11, item12, item13, item14, item15];

                        let td1 = document.createElement('td');
                        td1.colSpan = "3";
                        td1.innerText = item1;
                        tr.appendChild(td1);

                        let column = 1; // Start column after the first three items
                        for (let j = 1; j < items.length; j++) {
                            let item = items[j];
                            let td = document.createElement('td');
                            if (column >= 2) {
                                if (item >= 95) {
                                    td.style.backgroundColor = "#0804fc";
                                } else if (item >= 85 && item < 95) {
                                    td.style.backgroundColor = "#08b454";
                                } else if (item >= 75 && item < 85) {
                                    td.style.backgroundColor = "#fffc04";
                                } else if (item >= 65 && item < 75) {
                                    td.style.backgroundColor = "#ffc404";
                                } else if (item == 0) {
                                    td.style.backgroundColor = "white";
                                } else {
                                    td.style.backgroundColor = "red";
                                }
                            }
                            column++;
                            td.innerText = item;
                            tr.appendChild(td);
                        }

                        tbody3.appendChild(tr);

                        ///table untuk rekap perafd
                        var arrAFD = RekapBulanAFD;
                        // console.log(arrAFD);

                        var arrAFDTH = RekapTahunAFD;
                        //   console.log(arrAFDTH)

                        var tbody4 = document.getElementById('rekapAFD');
                        let currentIndex = 1;



                        // console.log(assist[0][1])
                        arrAFD.forEach((element, index) => {
                            let estate = element[0];
                            let namaAFD = Object.keys(element[1].January);
                            namaAFD.forEach((asisten) => {
                                var tr = document.createElement('tr');
                                let item0 = '-';
                                let item1 = estate;
                                let item2 = asisten;
                                let item3;

                                filteredAssistants.forEach((element, index) => {
                                    const assistantEstate = element['est'];
                                    const assistantAfd = element['afd'];
                                    // console.log(assistantEstate)

                                    if (assistantEstate === item1 && assistantAfd === item2) {
                                        item3 = element['nama'];
                                    }
                                });

                                if (item3 === undefined) {
                                    item3 = '-';
                                }

                                let item4 = element[1].January[asisten].bulan_afd;
                                let item5 = element[1].February[asisten].bulan_afd;
                                let item6 = element[1].March[asisten].bulan_afd;
                                let item7 = element[1]?.April?.asisten?.bulan_afd ?? '-';
                                let item8 = element[1].May?.[asisten]?.bulan_afd;
                                let item9 = element[1].June?.[asisten]?.bulan_afd;
                                let item10 = element[1].July?.[asisten]?.bulan_afd;
                                let item11 = element[1].August?.[asisten]?.bulan_afd;
                                let item12 = element[1].September?.[asisten]?.bulan_afd;
                                let item13 = element[1].October?.[asisten]?.bulan_afd;
                                let item14 = element[1].November?.[asisten]?.bulan_afd;
                                let item15 = element[1].December?.[asisten]?.bulan_afd;

                                // let item17 = arrAFDTH[0][1].OA.tahun_skorwil;
                                let item16;
                                for (var i = 0; i < arrAFDTH.length; i++) {
                                    if (arrAFDTH[i][0] == item1) {
                                        item16 = arrAFDTH[i][1][item2]['tahun_skorwil'];
                                        break;
                                    }
                                }

                                let items = [item0, item1, item2, item3, item4, item5, item6, item7, item8, item9, item10, item11, item12, item13, item14, item15, item16];

                                let column = 1; // Start column after the first three items
                                for (let j = 0; j < items.length; j++) {
                                    let item = items[j];
                                    let td = document.createElement('td');
                                    if (column >= 5) {
                                        if (item >= 95) {
                                            td.style.backgroundColor = "#0804fc";
                                        } else if (item >= 85 && item < 95) {
                                            td.style.backgroundColor = "#08b454";
                                        } else if (item >= 75 && item < 85) {
                                            td.style.backgroundColor = "#fffc04";
                                        } else if (item >= 65 && item < 75) {
                                            td.style.backgroundColor = "#ffc404";
                                        } else if (item === 0) {
                                            td.style.backgroundColor = "white";
                                        } else {
                                            td.style.backgroundColor = "red";
                                        }
                                    }
                                    column++;
                                    td.innerText = item;
                                    tr.appendChild(td);
                                }

                                tbody4.appendChild(tr);
                            });
                        });

                        const sorting = document.getElementById('rekapAFD');

                        if (sorting) {
                            // Convert the table rows to an array for sorting
                            const rows = Array.from(sorting.rows);

                            // Sort the rows based on the values in the 16th column
                            rows.sort((row1, row2) => {
                                const value1 = parseInt(row1.cells[16].textContent);
                                const value2 = parseInt(row2.cells[16].textContent);
                                return value2 - value1;
                            });

                            // Remove the existing rows from the table body
                            while (sorting.firstChild) {
                                sorting.removeChild(sorting.firstChild);
                            }

                            // Add the sorted rows back to the table body
                            rows.forEach((row) => {
                                sorting.appendChild(row);
                            });
                        } else {
                            console.error("Element with id 'rekapAFD' not found");
                        }

                        const index = document.getElementById('rekapAFD');
                        if (index) {
                            const rows = Array.from(index.rows);
                            let i = 1;
                            rows.forEach(row => {
                                row.cells[0].textContent = i;
                                i++;
                            });
                        }


                        //   end rekap table afd
                        //endtable

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



                        //chart table untuk pertahun
                        chartGrainYear.updateSeries([{
                            name: 'butir/jjg panen',
                            data: bttEst
                        }])
                        chartGrainYear.updateOptions({
                            xaxis: {
                                categories: formatEst
                            },
                            colors: colors // Set the colors directly, no need for an object
                        })

                        chartFruitYear.updateSeries([{
                            name: '% buah tinggal',
                            data: bhEST
                        }])
                        chartFruitYear.updateOptions({
                            xaxis: {
                                categories: formatEst
                            },
                            colors: colors // Set the colors directly, no need for an object
                        })


                        let wilayahReg = '';


                        if (regInpt === '1') {
                            wilayahReg = ['WIL I', 'WIL II', 'WIL III']

                        } else if (regInpt === '2') {
                            wilayahReg = ['WIL IV', 'WIL V', 'WIL VI']

                        } else if (regInpt === '3') {
                            wilayahReg = ['WIL VII', 'WIL VIII']

                        } else if (regInpt === '4') {
                            wilayahReg = ['WIL IX', 'WIL X']

                        }
                        chartGrainWilYear.updateSeries([{
                            name: 'butir/jjg panen',
                            data: brdWil
                        }])

                        chartGrainWilYear.updateOptions({
                            xaxis: {
                                categories: wilayahReg
                            }
                        })

                        chartFruitWilYear.updateSeries([{
                            name: '% buah tinggal',
                            data: bhWil
                        }])

                        chartFruitWilYear.updateOptions({
                            xaxis: {
                                categories: wilayahReg
                            }
                        })


                        //endchart
                    }
                });
            }



            var options = {
                chart: {
                    height: 280,
                    type: "line"
                },
                dataLabels: {
                    enabled: false
                },
                series: [{
                    name: "ESTATE",
                    data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                }, ],
                fill: {
                    type: "gradient",
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.9,
                        stops: [0, 90, 100]
                    }
                },
                yaxis: [{
                    axisTicks: {
                        show: true
                    },
                    axisBorder: {
                        show: true,
                        color: "#FF1654"
                    },
                    labels: {
                        style: {
                            colors: "#FF1654"
                        }
                    },
                    title: {
                        text: "Series A",
                        style: {
                            color: "#FF1654"
                        }
                    },

                }],

                // annotations: {
                //     yaxis: [{
                //             y: 95,
                //             y2: 100,
                //             borderColor: '#000',
                //             fillColor: '#96be25',
                //             opacity: 0.4,
                //             label: {
                //                 text: ' '
                //             }
                //         }, {
                //             y: 85,
                //             y2: 95,
                //             borderColor: '#000',
                //             fillColor: '#78ac44',
                //             opacity: 0.4,
                //             label: {
                //                 text: ''
                //             }
                //         }, {
                //             y: 75,
                //             y2: 85,
                //             borderColor: '#000',
                //             fillColor: '#fffc04',
                //             opacity: 0.4,
                //             label: {
                //                 text: 'Standar QC'
                //             }
                //         }, {
                //             y: 65,
                //             y2: 75,
                //             borderColor: '#000',
                //             fillColor: '#f07c34',
                //             opacity: 0.4,
                //             label: {
                //                 text: ''
                //             }
                //         }, {
                //             y: 0,
                //             y2: 65,
                //             borderColor: '#000',
                //             fillColor: '#ff0404',
                //             opacity: 0.4,


                //             label: {
                //                 text: ''
                //             }
                //         }

                //     ]
                // },
                xaxis: {
                    categories: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "July", "Agustus", "Sept", "Okt", "Nov", "Dec"]
                }
            };



            var chartScore = new ApexCharts(document.querySelector("#skorGraph"), options);
            chartScore.render();
            var chartScorePerwil = new ApexCharts(document.querySelector("#skorGraphWil"), options);
            chartScorePerwil.render();
            var chartScoreBron = new ApexCharts(document.querySelector("#skorBronGraph"), options);
            chartScoreBron.render();
            var chatScoreJan = new ApexCharts(document.querySelector("#skorJanGraph"), options);
            chatScoreJan.render();

            var GraphBhmth = new ApexCharts(document.querySelector("#bh_mentah_Graph"), options);
            GraphBhmth.render();

            var GraphBhMsak = new ApexCharts(document.querySelector("#bh_masak_Graph"), options);
            GraphBhMsak.render();

            var GraphBhOver = new ApexCharts(document.querySelector("#bh_over_Graph"), options);
            GraphBhOver.render();

            var GraphBhEmpty = new ApexCharts(document.querySelector("#bh_empty_Graph"), options);
            GraphBhEmpty.render();

            var GraphBhvcute = new ApexCharts(document.querySelector("#bh_svcut_Graph"), options);
            GraphBhvcute.render();
            var GraphBhAbnrl = new ApexCharts(document.querySelector("#bh_abnor_Graph"), options);
            GraphBhAbnrl.render();



            var GraphTranBrd = new ApexCharts(document.querySelector("#tr_brd_Graph"), options);
            GraphTranBrd.render();
            var GraphTranBH = new ApexCharts(document.querySelector("#tr_buah_Graph"), options);
            GraphTranBH.render();

            document.getElementById('GraphFilter').onclick = function() {
                Swal.fire({
                    title: 'Loading',
                    html: '<span class="loading-text">Mohon Tunggu...</span>',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });
                graphFilter()
            }




            //tampilkan perminggu filter table utama





            //testing  table ke tengah
            const cx = document.getElementById('showWeek');
            const ox = document.getElementById('regionalDataweek');
            const sx = document.getElementById("Tabs1");
            const mx = document.getElementById("Tabs2");
            const lx = document.getElementById("Tabs3");
            const nx = document.getElementById("Tabs4");
            const thElement1x = document.getElementById('theads1');
            const thElement2x = document.getElementById('theads2');
            const thElement3x = document.getElementById('theads3');
            const thElement4x = document.getElementById('theads3x');

            cx.addEventListener('click', function() {
                const cx = ox.value;
                if (cx === '1') {
                    sx.style.display = "";
                    mx.style.display = "";
                    lx.style.display = "";
                    // nx.style.display = "none";


                    resetClassList(nx);

                    thElement1x.textContent = 'WILAYAH I';
                    thElement2x.textContent = 'WILAYAH II';
                    thElement3x.textContent = 'WILAYAH III';

                    thElement1x.classList.add("text-center");
                    thElement2x.classList.add("text-center");
                    thElement3x.classList.add("text-center");
                    // thElement4x.textContent = 'PLASMA I';
                    // thElement4x.classList.add("text-center");


                    sx.classList.add("col-lg-4");
                    mx.classList.add("col-lg-4");
                    lx.classList.add("col-lg-4");
                    // nx.classList.add("col-lg-3");
                } else if (cx === '2') {

                    sx.style.display = "";
                    mx.style.display = "";
                    lx.style.display = "";
                    // nx.style.display = "none";


                    resetClassList(nx);

                    thElement1x.textContent = 'WILAYAH I';
                    thElement2x.textContent = 'WILAYAH II';
                    thElement3x.textContent = 'WILAYAH III';

                    thElement1x.textContent = 'WILAYAH IV';
                    thElement2x.textContent = 'WILAYAH V';
                    thElement3x.textContent = 'WILAYAH VI';
                    // thElement4x.textContent = 'PLASMA II';



                    sx.classList.add("col-lg-4");
                    mx.classList.add("col-lg-4");
                    lx.classList.add("col-lg-4");
                } else if (cx === '3') {
                    sx.style.display = "";
                    mx.style.display = "";
                    lx.style.display = "none";
                    // nx.style.display = "none";


                    resetClassList(lx);
                    resetClassList(nx);

                    thElement1x.textContent = 'WILAYAH VII';
                    thElement2x.textContent = 'WILAYAH VIII';
                    // thElement4x.textContent = 'PLASMA III';

                    thElement1x.classList.add("text-center");
                    thElement2x.classList.add("text-center");
                    // thElement4x.classList.add("text-center");

                    sx.classList.add("col-lg-6");
                    mx.classList.add("col-lg-6");

                } else if (cx === '4') {
                    sx.style.display = "";
                    mx.style.display = "";
                    lx.style.display = "none";
                    // nx.style.display = "none";

                    resetClassList(sx);
                    resetClassList(mx);


                    thElement1x.textContent = 'WILAYAH Inti';
                    thElement2x.textContent = 'WILAYAH Plasma';

                    thElement1x.classList.add("text-center");
                    thElement2x.classList.add("text-center");


                    sx.classList.add("col-lg-6");
                    mx.classList.add("col-lg-6");

                }
            });

            document.getElementById('showWeek').onclick = function() {
                Swal.fire({
                    title: 'Loading',
                    html: '<span class="loading-text">Mohon Tunggu...</span>',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

                dashboard_week()
            }

            function dashboard_week() {
                $('#tbodys1').empty()
                $('#tbodys2').empty()
                $('#tbodys3').empty()
                $('#plbodys').empty()
                $('#theadregs').empty()
                var week = ''
                var regData = ''
                var _token = $('input[name="_token"]').val();
                var week = document.getElementById('dateWeek').value
                var regData = document.getElementById('regionalDataweek').value


                $.ajax({
                    url: "{{ route('getWeekInpeksi') }}",
                    method: "GET",
                    data: {
                        week,
                        regData,
                        _token: _token
                    },
                    success: function(result) {
                        Swal.close()
                        // console.log(reg);
                        var parseResult = JSON.parse(result)
                        //list estate
                        var list_will = Object.entries(parseResult['list_estate']);

                        var text_values = list_will.map(function(entry) {
                            return entry[1];
                        });

                        // console.log(text_values);
                        // //untuk chart
                        var chart_btt = Object.entries(parseResult['chart_brd'])
                        var chart_buah = Object.entries(parseResult['chart_buah'])

                        var chartWillbt = Object.entries(parseResult['chart_brdwil'])
                        var chartWillbh = Object.entries(parseResult['chart_buahwil'])



                        //perbaikan untuk table utama unutuk rekap 
                        // var Data_TableUtama = Object.entries(parseResult['data_tabelutama'])
                        // console.log(Data_TableUtama);
                        // unutk table utama
                        const Data_TableUtama = Object.entries(parseResult['data_tabelutama']);
                        const regional = Object.entries(parseResult['RekapRegTable']);
                        const plasma = Object.entries(parseResult['plasma']);
                        const plasmaEM = Object.entries(parseResult['plasmaEM']);
                        const plasmaGM = Object.entries(parseResult['plasmaGM']);
                        const skor_ptmua = Object.entries(parseResult['pt_mua']);
                        // console.log(plasma);
                        const newPlasma = plasma.map(([_, data]) => ({

                            est: data.est,
                            afd: data.afd,
                            nama: data.nama,
                            skor: data.skor,
                            rank: data.rank,
                            // namaEM: namaEM,
                            // namaGM: namaGM,
                        }));
                        const newPlasmaEM = plasmaEM.map(([_, data]) => ({

                            est: data.est,
                            afd: data.afd,
                            nama: data.namaEM,
                            skor: data.Skor,

                            // namaEM: namaEM,
                            // namaGM: namaGM,
                        }));
                        const newPlasmaGM = plasmaGM.map(([_, data]) => ({

                            est: data.est,
                            afd: data.afd,
                            nama: data.namaEM,
                            skor: data.Skor,

                            // namaEM: namaEM,
                            // namaGM: namaGM,
                        }));
                        // console.log(Data_TableUtama);

                        const newData_TableUtama = Data_TableUtama.map(([_, data]) => ({
                            afd: data.afd,
                            est: data.est,
                            est_afd: `${data.est}_${data.afd}`,
                            nama: data.nama,
                            rank: data.rank,
                            skor: data.data === 'kosong' ? '-' : data.skor,
                            data: data.data,
                        }));

                        const Data_TableKedua = Object.entries(parseResult['data_tabelkedua']);

                        // console.log(Data_TableKedua);
                        const newData_TableKedua = Data_TableKedua.map(([_, data]) => ({
                            afd: data.afd,
                            est: data.est,
                            est_afd: `${data.est}_${data.afd}`,
                            nama: data.nama,
                            rank: data.rank,
                            skor: data.data === 'kosong' ? '-' : data.skor,
                            data: data.data,
                        }));
                        const Data_TableKetiga = Object.entries(parseResult['data_tabeketiga']);
                        const newData_TableKetiga = Data_TableKetiga.map(([_, data]) => ({
                            afd: data.afd,
                            est: data.est,
                            est_afd: `${data.est}_${data.afd}`,
                            nama: data.nama,
                            rank: data.rank,
                            skor: data.data === 'kosong' ? '-' : data.skor,
                            data: data.data,
                        }));


                        //untuk table perestate
                        const data_Est1 = Object.entries(parseResult['data_Est1']);
                        // console.log(data_Est1);
                        const newData_data_Est1 = data_Est1.map(([_, data]) => ({

                            est: data.est,
                            em: data.EM,
                            nama: data.nama,
                            rank: data.rank,
                            skor: data.data === 'kosong' ? '-' : data.skor
                        }));
                        const data_Est2 = Object.entries(parseResult['data_Est2']);
                        const newData_data_Est2 = data_Est2.map(([_, data]) => ({

                            est: data.est,
                            em: data.EM,
                            nama: data.nama,
                            rank: data.rank,
                            skor: data.data === 'kosong' ? '-' : data.skor
                        }));

                        const data_Est3 = Object.entries(parseResult['data_Est3']);



                        const newData_data_Est3 = data_Est3.map(([_, data]) => ({

                            est: data.est,
                            em: data.EM,
                            nama: data.nama,
                            rank: data.rank,
                            skor: data.data === 'kosong' ? '-' : data.skor
                        }));

                        const data_GM = Object.entries(parseResult['data_GM']);
                        // console.log(data_GM);
                        const GM_list = data_GM.map(([_, data]) => ({

                            est: data.est,
                            em: data.EM,
                            nama: data.nama,
                            skor: data.skor,
                        }));
                        //testing table otomatis ketengah
                        let regInpt = regData;

                        var GM_1 = Object.entries(parseResult['GM_1'])
                        var GM_2 = Object.entries(parseResult['GM_2'])
                        var GM_3 = Object.entries(parseResult['GM_3'])





                        function filterArrayByEst(array) {
                            return array.filter(obj => obj.est !== 'Plasma1');
                        }
                        const originalArray = newData_TableUtama
                        const filteredArray = filterArrayByEst(originalArray);



                        // console.log(filteredArray);
                        var arrTbody1 = filteredArray

                        // console.log(arrTbody1);
                        var tbody1 = document.getElementById('tbodys1');
                        //         $('#thead1').empty()
                        // $('#thead2').empty()
                        // $('#thead3').empty()

                        arrTbody1.forEach(element => {


                            var tr = document.createElement('tr')
                            let item1 = element['est']
                            let item2 = element['afd']
                            let item3 = element['nama']
                            let item4 = element['skor']
                            let item5 = element['rank']

                            let itemElement1 = document.createElement('td')
                            let itemElement2 = document.createElement('td')
                            let itemElement3 = document.createElement('td')
                            let itemElement4 = document.createElement('td')
                            let itemElement5 = document.createElement('td')



                            itemElement1.classList.add("text-center")
                            itemElement2.classList.add("text-center")
                            itemElement3.classList.add("text-center")
                            itemElement4.classList.add("text-center")
                            itemElement5.classList.add("text-center")



                            if (item3.trim() === "VACANT") { // Use trim to remove leading/trailing spaces
                                itemElement3.style.color = "red";
                            } else {
                                itemElement3.style.color = "black";
                            }

                            if (item4 === '-') {
                                itemElement4.style.backgroundColor = "white";
                                itemElement4.style.color = "black";
                            } else if (item4 >= 95) {
                                itemElement4.style.backgroundColor = "#609cd4";
                                itemElement4.style.color = "black";
                            } else if (item4 >= 85 && item4 < 95) {
                                itemElement4.style.backgroundColor = "#08b454";
                                itemElement4.style.color = "black";
                            } else if (item4 >= 75 && item4 < 85) {
                                itemElement4.style.backgroundColor = "#fffc04";
                                itemElement4.style.color = "black";
                            } else if (item4 >= 65 && item4 < 75) {
                                itemElement4.style.backgroundColor = "#ffc404";
                                itemElement4.style.color = "black";
                            } else {
                                itemElement4.style.backgroundColor = "red";
                                itemElement4.style.color = "black";
                            }

                            if (itemElement4.style.backgroundColor === "#609cd4") {
                                itemElement4.style.color = "black";
                            } else if (itemElement4.style.backgroundColor === "#08b454") {
                                itemElement4.style.color = "black";
                            } else if (itemElement4.style.backgroundColor === "#fffc04") {
                                itemElement4.style.color = "black";
                            } else if (itemElement4.style.backgroundColor === "#ffc404") {
                                itemElement4.style.color = "black";
                            } else if (itemElement4.style.backgroundColor === "red") {
                                itemElement4.style.color = "black";
                            }

                            // if (item4 != 0 && item4 != 90) {
                            //     itemElement4.innerHTML = '<a href="detailInpeksi/' + element['est'] + '/' + element['afd'] + '/' + date + '">' + element['skor'] + ' </a>'
                            // } else {
                            //     itemElement4.innerText = item4
                            // }

                            itemElement4.innerText = item4
                            itemElement1.innerText = item1
                            // itemElement2.innerHTML = '<a href="detailInpeksi/' + element['est'] + '/' + element['afd'] + '/' + date + '" target="_blank">' + element['afd'] + ' </a>';

                            itemElement2.innerText = item2
                            itemElement3.innerText = item3
                            //   itemElement4.innerText  = item4
                            itemElement5.innerText = item5

                            tr.appendChild(itemElement1)
                            tr.appendChild(itemElement2)
                            tr.appendChild(itemElement3)
                            tr.appendChild(itemElement4)
                            tr.appendChild(itemElement5)

                            tbody1.appendChild(tr)
                            // }
                        });

                        const arrTab1 = newData_data_Est1
                        const EstTab1 = filterArrayByEst(arrTab1);
                        var arrTbody1 = EstTab1
                        // console.log(arrTbody1);
                        // var table1 = document.getElementById('table1');
                        var tbody1 = document.getElementById('tbodys1');


                        arrTbody1.forEach(element => {
                            // for (let i = 0; i < 5; i++) {


                            var tr = document.createElement('tr')
                            let item1 = element['est']
                            let item2 = element['em']
                            let item3 = element['nama']
                            let item4 = element['skor']
                            let item5 = element['rank']


                            let itemElement1 = document.createElement('td')
                            let itemElement2 = document.createElement('td')
                            let itemElement3 = document.createElement('td')
                            let itemElement4 = document.createElement('td')
                            let itemElement5 = document.createElement('td')



                            itemElement1.classList.add("text-center")
                            itemElement2.classList.add("text-center")
                            itemElement3.classList.add("text-center")
                            itemElement4.classList.add("text-center")
                            itemElement5.classList.add("text-center")


                            itemElement1.style.backgroundColor = "#e8ecdc";
                            itemElement2.style.backgroundColor = "#e8ecdc";
                            itemElement3.style.backgroundColor = "#e8ecdc";
                            if (item3.trim() === "VACANT") { // Use trim to remove leading/trailing spaces
                                itemElement3.style.color = "red";
                            } else {
                                itemElement3.style.color = "black";
                            }

                            if (item4 === '-') {
                                itemElement4.style.backgroundColor = "white";
                                itemElement4.style.color = "black";
                            } else if (item4 >= 95) {
                                itemElement4.style.backgroundColor = "#609cd4";
                                itemElement4.style.color = "black";
                            } else if (item4 >= 85 && item4 < 95) {
                                itemElement4.style.backgroundColor = "#08b454";
                                itemElement4.style.color = "black";
                            } else if (item4 >= 75 && item4 < 85) {
                                itemElement4.style.backgroundColor = "#fffc04";
                                itemElement4.style.color = "black";
                            } else if (item4 >= 65 && item4 < 75) {
                                itemElement4.style.backgroundColor = "#ffc404";
                                itemElement4.style.color = "black";
                            } else {
                                itemElement4.style.backgroundColor = "red";
                                itemElement4.style.color = "black";
                            }

                            if (itemElement4.style.backgroundColor === "#609cd4") {
                                itemElement4.style.color = "black";
                            } else if (itemElement4.style.backgroundColor === "#08b454") {
                                itemElement4.style.color = "black";
                            } else if (itemElement4.style.backgroundColor === "#fffc04") {
                                itemElement4.style.color = "black";
                            } else if (itemElement4.style.backgroundColor === "#ffc404") {
                                itemElement4.style.color = "black";
                            } else if (itemElement4.style.backgroundColor === "red") {
                                itemElement4.style.color = "black";
                            }


                            itemElement4.innerText = item4;
                            itemElement1.innerText = item1
                            itemElement2.innerText = item2
                            itemElement3.innerText = item3
                            itemElement4.innerText = item4
                            itemElement5.innerText = item5

                            tr.appendChild(itemElement1)
                            tr.appendChild(itemElement2)
                            tr.appendChild(itemElement3)
                            tr.appendChild(itemElement4)
                            tr.appendChild(itemElement5)

                            tbody1.appendChild(tr)
                            // }
                        });

                        //untuk GM

                        var tr = document.createElement('tr')
                        let item1 = GM_list[0].est;
                        let item2 = GM_list[0].em;
                        let item3 = GM_list[0].nama;
                        let item4 = GM_list[0].skor;
                        let item5 = ''
                        let itemElement1 = document.createElement('td')
                        let itemElement2 = document.createElement('td')
                        let itemElement3 = document.createElement('td')
                        let itemElement4 = document.createElement('td')
                        let itemElement5 = document.createElement('td')
                        itemElement1.classList.add("text-center")
                        itemElement2.classList.add("text-center")
                        itemElement3.classList.add("text-center")
                        itemElement4.classList.add("text-center")
                        itemElement5.classList.add("text-center")
                        itemElement1.style.backgroundColor = "#fff4cc";
                        itemElement2.style.backgroundColor = "#fff4cc";
                        itemElement3.style.backgroundColor = "#fff4cc";
                        if (item3.trim() === "VACANT") { // Use trim to remove leading/trailing spaces
                            itemElement3.style.color = "red";
                        } else {
                            itemElement3.style.color = "black";
                        }


                        if (item4 >= 95) {
                            itemElement4.style.backgroundColor = "#609cd4";
                            itemElement4.style.color = "black";
                        } else if (item4 >= 85 && item4 < 95) {
                            itemElement4.style.backgroundColor = "#08b454";
                            itemElement4.style.color = "black";
                        } else if (item4 >= 75 && item4 < 85) {
                            itemElement4.style.backgroundColor = "#fffc04";
                            itemElement4.style.color = "black";
                        } else if (item4 >= 65 && item4 < 75) {
                            itemElement4.style.backgroundColor = "#ffc404";
                            itemElement4.style.color = "black";
                        } else {
                            itemElement4.style.backgroundColor = "red";
                            itemElement4.style.color = "black";
                        }

                        if (itemElement4.style.backgroundColor === "#609cd4") {
                            itemElement4.style.color = "black";
                        } else if (itemElement4.style.backgroundColor === "#08b454") {
                            itemElement4.style.color = "black";
                        } else if (itemElement4.style.backgroundColor === "#fffc04") {
                            itemElement4.style.color = "black";
                        } else if (itemElement4.style.backgroundColor === "#ffc404") {
                            itemElement4.style.color = "black";
                        } else if (itemElement4.style.backgroundColor === "red") {
                            itemElement4.style.color = "black";
                        }
                        itemElement1.innerText = item1;
                        itemElement2.innerText = item2;
                        itemElement3.innerText = item3;
                        itemElement4.innerText = item4;
                        itemElement5.innerText = item5;
                        tr.appendChild(itemElement1)
                        tr.appendChild(itemElement2)
                        tr.appendChild(itemElement3)
                        tr.appendChild(itemElement4)
                        tr.appendChild(itemElement5)
                        tbody1.appendChild(tr)

                        //table wil 2
                        var arrTbody2 = newData_TableKedua


                        var tbody2 = document.getElementById('tbodys2');


                        arrTbody2.forEach(element => {


                            var tr = document.createElement('tr')
                            let item1 = element['est']
                            let item2 = element['afd']
                            let item3 = element['nama']
                            let item4 = element['skor']
                            let item5 = element['rank']

                            let itemElement1 = document.createElement('td')
                            let itemElement2 = document.createElement('td')
                            let itemElement3 = document.createElement('td')
                            let itemElement4 = document.createElement('td')
                            let itemElement5 = document.createElement('td')



                            itemElement1.classList.add("text-center")
                            itemElement2.classList.add("text-center")
                            itemElement3.classList.add("text-center")
                            itemElement4.classList.add("text-center")
                            itemElement5.classList.add("text-center")



                            if (item3.trim() === "VACANT") { // Use trim to remove leading/trailing spaces
                                itemElement3.style.color = "red";
                            } else {
                                itemElement3.style.color = "black";
                            }


                            if (item4 === '-') {
                                itemElement4.style.backgroundColor = "white";
                                itemElement4.style.color = "black";
                            } else if (item4 >= 95) {
                                itemElement4.style.backgroundColor = "#609cd4";
                                itemElement4.style.color = "black";
                            } else if (item4 >= 85 && item4 < 95) {
                                itemElement4.style.backgroundColor = "#08b454";
                                itemElement4.style.color = "black";
                            } else if (item4 >= 75 && item4 < 85) {
                                itemElement4.style.backgroundColor = "#fffc04";
                                itemElement4.style.color = "black";
                            } else if (item4 >= 65 && item4 < 75) {
                                itemElement4.style.backgroundColor = "#ffc404";
                                itemElement4.style.color = "black";
                            } else {
                                itemElement4.style.backgroundColor = "red";
                                itemElement4.style.color = "black";
                            }

                            if (itemElement4.style.backgroundColor === "#609cd4") {
                                itemElement4.style.color = "black";
                            } else if (itemElement4.style.backgroundColor === "#08b454") {
                                itemElement4.style.color = "black";
                            } else if (itemElement4.style.backgroundColor === "#fffc04") {
                                itemElement4.style.color = "black";
                            } else if (itemElement4.style.backgroundColor === "#ffc404") {
                                itemElement4.style.color = "black";
                            } else if (itemElement4.style.backgroundColor === "red") {
                                itemElement4.style.color = "black";
                            }

                            // if (item4 != 0 && item4 != 90) {
                            //     itemElement4.innerHTML = '<a href="detailInpeksi/' + element['est'] + '/' + element['afd'] + '/' + date + '">' + element['skor'] + ' </a>'
                            // } else {
                            //     itemElement4.innerText = item4
                            // }
                            itemElement1.innerText = item1
                            itemElement2.innerText = item2
                            // itemElement2.innerHTML = '<a href="detailInpeksi/' + element['est'] + '/' + element['afd'] + '/' + date + '" target="_blank">' + element['afd'] + ' </a>';

                            itemElement3.innerText = item3
                            itemElement4.innerText = item4
                            itemElement5.innerText = item5

                            tr.appendChild(itemElement1)
                            tr.appendChild(itemElement2)
                            tr.appendChild(itemElement3)
                            tr.appendChild(itemElement4)
                            tr.appendChild(itemElement5)

                            tbody2.appendChild(tr)
                            // }
                        });
                        var arrTbody2 = newData_data_Est2
                        // var table1 = document.getElementById('table1');
                        var tbody2 = document.getElementById('tbodys2');


                        arrTbody2.forEach(element => {
                            // for (let i = 0; i < 5; i++) {


                            var tr = document.createElement('tr')
                            let item1 = element['est']
                            let item2 = element['em']
                            let item3 = element['nama']
                            let item4 = element['skor']
                            let item5 = element['rank']


                            let itemElement1 = document.createElement('td')
                            let itemElement2 = document.createElement('td')
                            let itemElement3 = document.createElement('td')
                            let itemElement4 = document.createElement('td')
                            let itemElement5 = document.createElement('td')



                            itemElement1.classList.add("text-center")
                            itemElement2.classList.add("text-center")
                            itemElement3.classList.add("text-center")
                            itemElement4.classList.add("text-center")
                            itemElement5.classList.add("text-center")


                            itemElement1.style.backgroundColor = "#e8ecdc";
                            itemElement2.style.backgroundColor = "#e8ecdc";
                            itemElement3.style.backgroundColor = "#e8ecdc";
                            if (item3.trim() === "VACANT") { // Use trim to remove leading/trailing spaces
                                itemElement3.style.color = "red";
                            } else {
                                itemElement3.style.color = "black";
                            }


                            if (item4 === '-') {
                                itemElement4.style.backgroundColor = "white";
                                itemElement4.style.color = "black";
                            } else if (item4 >= 95) {
                                itemElement4.style.backgroundColor = "#609cd4";
                                itemElement4.style.color = "black";
                            } else if (item4 >= 85 && item4 < 95) {
                                itemElement4.style.backgroundColor = "#08b454";
                                itemElement4.style.color = "black";
                            } else if (item4 >= 75 && item4 < 85) {
                                itemElement4.style.backgroundColor = "#fffc04";
                                itemElement4.style.color = "black";
                            } else if (item4 >= 65 && item4 < 75) {
                                itemElement4.style.backgroundColor = "#ffc404";
                                itemElement4.style.color = "black";
                            } else {
                                itemElement4.style.backgroundColor = "red";
                                itemElement4.style.color = "black";
                            }

                            if (itemElement4.style.backgroundColor === "#609cd4") {
                                itemElement4.style.color = "black";
                            } else if (itemElement4.style.backgroundColor === "#08b454") {
                                itemElement4.style.color = "black";
                            } else if (itemElement4.style.backgroundColor === "#fffc04") {
                                itemElement4.style.color = "black";
                            } else if (itemElement4.style.backgroundColor === "#ffc404") {
                                itemElement4.style.color = "black";
                            } else if (itemElement4.style.backgroundColor === "red") {
                                itemElement4.style.color = "black";
                            }
                            itemElement4.innerText = item4;
                            itemElement1.innerText = item1
                            itemElement2.innerText = item2
                            itemElement3.innerText = item3
                            itemElement4.innerText = item4
                            itemElement5.innerText = item5

                            tr.appendChild(itemElement1)
                            tr.appendChild(itemElement2)
                            tr.appendChild(itemElement3)
                            tr.appendChild(itemElement4)
                            tr.appendChild(itemElement5)

                            tbody2.appendChild(tr)
                            // }
                        });
                        //untuk GM

                        var tr = document.createElement('tr')
                        let items1 = GM_list[1].est;
                        let items2 = GM_list[1].em;
                        let items3 = GM_list[1].nama;
                        let items4 = GM_list[1].skor;
                        let items5 = ''
                        let itemsElement1 = document.createElement('td')
                        let itemsElement2 = document.createElement('td')
                        let itemsElement3 = document.createElement('td')
                        let itemsElement4 = document.createElement('td')
                        let itemsElement5 = document.createElement('td')
                        itemsElement1.classList.add("text-center")
                        itemsElement2.classList.add("text-center")
                        itemsElement3.classList.add("text-center")
                        itemsElement4.classList.add("text-center")
                        itemsElement5.classList.add("text-center")
                        itemsElement1.style.backgroundColor = "#fff4cc";
                        itemsElement2.style.backgroundColor = "#fff4cc";
                        itemsElement3.style.backgroundColor = "#fff4cc";
                        if (items4 >= 95) {
                            itemsElement4.style.backgroundColor = "#0804fc";
                        } else if (items4 >= 85 && items4 < 95) {
                            itemsElement4.style.backgroundColor = "#08b454";
                        } else if (items4 >= 75 && items4 < 85) {
                            itemsElement4.style.backgroundColor = "#fffc04";
                        } else if (items4 >= 65 && items4 < 75) {
                            itemsElement4.style.backgroundColor = "#ffc404";
                        } else {
                            itemsElement4.style.backgroundColor = "red";
                        }
                        itemsElement1.innerText = items1;
                        itemsElement2.innerText = items2;
                        itemsElement3.innerText = items3;
                        itemsElement4.innerText = items4;
                        itemsElement5.innerText = items5;
                        tr.appendChild(itemsElement1)
                        tr.appendChild(itemsElement2)
                        tr.appendChild(itemsElement3)
                        tr.appendChild(itemsElement4)
                        tr.appendChild(itemsElement5)
                        tbody2.appendChild(tr)

                        //table wil 2
                        var arrTbody3 = newData_TableKetiga
                        // console.log(newData_TableKetiga);

                        var tbody3 = document.getElementById('tbodys3');


                        arrTbody3.forEach(element => {


                            var tr = document.createElement('tr')
                            let item1 = element['est']
                            let item2 = element['afd']
                            let item3 = element['nama']
                            let item4 = element['skor']
                            let item5 = element['rank']

                            let itemElement1 = document.createElement('td')
                            let itemElement2 = document.createElement('td')
                            let itemElement3 = document.createElement('td')
                            let itemElement4 = document.createElement('td')
                            let itemElement5 = document.createElement('td')



                            itemElement1.classList.add("text-center")
                            itemElement2.classList.add("text-center")
                            itemElement3.classList.add("text-center")
                            itemElement4.classList.add("text-center")
                            itemElement5.classList.add("text-center")



                            if (item3.trim() === "VACANT") { // Use trim to remove leading/trailing spaces
                                itemElement3.style.color = "red";
                            } else {
                                itemElement3.style.color = "black";
                            }


                            if (item4 === '-') {
                                itemElement4.style.backgroundColor = "white";
                                itemElement4.style.color = "black";
                            } else if (item4 >= 95) {
                                itemElement4.style.backgroundColor = "#609cd4";
                                itemElement4.style.color = "black";
                            } else if (item4 >= 85 && item4 < 95) {
                                itemElement4.style.backgroundColor = "#08b454";
                                itemElement4.style.color = "black";
                            } else if (item4 >= 75 && item4 < 85) {
                                itemElement4.style.backgroundColor = "#fffc04";
                                itemElement4.style.color = "black";
                            } else if (item4 >= 65 && item4 < 75) {
                                itemElement4.style.backgroundColor = "#ffc404";
                                itemElement4.style.color = "black";
                            } else {
                                itemElement4.style.backgroundColor = "red";
                                itemElement4.style.color = "black";
                            }

                            if (itemElement4.style.backgroundColor === "#609cd4") {
                                itemElement4.style.color = "black";
                            } else if (itemElement4.style.backgroundColor === "#08b454") {
                                itemElement4.style.color = "black";
                            } else if (itemElement4.style.backgroundColor === "#fffc04") {
                                itemElement4.style.color = "black";
                            } else if (itemElement4.style.backgroundColor === "#ffc404") {
                                itemElement4.style.color = "black";
                            } else if (itemElement4.style.backgroundColor === "red") {
                                itemElement4.style.color = "black";
                            }


                            // if (item4 != 0 && item4 != 90) {
                            //     itemElement4.innerHTML = '<a href="detailInpeksi/' + element['est'] + '/' + element['afd'] + '/' + date + '">' + element['skor'] + ' </a>'
                            // } else {
                            //     itemElement4.innerText = item4
                            // }
                            itemElement1.innerText = item1
                            itemElement2.innerText = item2
                            // itemElement2.innerHTML = '<a href="detailInpeksi/' + element['est'] + '/' + element['afd'] + '/' + date + '" target="_blank">' + element['afd'] + ' </a>';

                            itemElement3.innerText = item3
                            itemElement4.innerText = item4
                            itemElement5.innerText = item5

                            tr.appendChild(itemElement1)
                            tr.appendChild(itemElement2)
                            tr.appendChild(itemElement3)
                            tr.appendChild(itemElement4)
                            tr.appendChild(itemElement5)

                            tbody3.appendChild(tr)
                            // }
                        });
                        // console.log(newData_data_Est3)

                        if (regData == '1') {
                            var arrTbody3 = newData_data_Est3.filter(element => !["SRE", "SKE", "LDE"].includes(element.est));
                            arrTbody3.push({
                                est: 'PT.MUA',
                                em: 'EM',
                                nama: '-',
                                rank: '-',
                                skor: skor_ptmua[0][1]
                            });
                        } else {
                            var arrTbody3 = newData_data_Est3
                        }

                        arrTbody3.sort((a, b) => b['skor'] - a['skor']);

                        // Assign ranks to the sorted array
                        let rank = 1;
                        arrTbody3.forEach((element, index) => {
                            // Always increment rank regardless of the score
                            element['rank'] = rank++;
                        });

                        // var table1 = document.getElementById('table1');
                        var tbody3 = document.getElementById('tbodys3');
                        arrTbody3.forEach(element => {

                            var tr = document.createElement('tr')
                            let item1 = element['est']
                            let item2 = element['em']
                            let item3 = element['nama']
                            let item4 = element['skor']
                            let item5 = element['rank'];


                            let itemElement1 = document.createElement('td')
                            let itemElement2 = document.createElement('td')
                            let itemElement3 = document.createElement('td')
                            let itemElement4 = document.createElement('td')
                            let itemElement5 = document.createElement('td')

                            itemElement1.classList.add("text-center")
                            itemElement2.classList.add("text-center")
                            itemElement3.classList.add("text-center")
                            itemElement4.classList.add("text-center")
                            itemElement5.classList.add("text-center")

                            itemElement1.style.backgroundColor = "#e8ecdc";
                            itemElement2.style.backgroundColor = "#e8ecdc";
                            itemElement3.style.backgroundColor = "#e8ecdc";
                            if (item3.trim() === "VACANT") { // Use trim to remove leading/trailing spaces
                                itemElement3.style.color = "red";
                            } else {
                                itemElement3.style.color = "black";
                            }


                            if (item4 === '-') {
                                itemElement4.style.backgroundColor = "white";
                                itemElement4.style.color = "black";
                            } else if (item4 >= 95) {
                                itemElement4.style.backgroundColor = "#609cd4";
                                itemElement4.style.color = "black";
                            } else if (item4 >= 85 && item4 < 95) {
                                itemElement4.style.backgroundColor = "#08b454";
                                itemElement4.style.color = "black";
                            } else if (item4 >= 75 && item4 < 85) {
                                itemElement4.style.backgroundColor = "#fffc04";
                                itemElement4.style.color = "black";
                            } else if (item4 >= 65 && item4 < 75) {
                                itemElement4.style.backgroundColor = "#ffc404";
                                itemElement4.style.color = "black";
                            } else {
                                itemElement4.style.backgroundColor = "red";
                                itemElement4.style.color = "black";
                            }

                            if (itemElement4.style.backgroundColor === "#609cd4") {
                                itemElement4.style.color = "black";
                            } else if (itemElement4.style.backgroundColor === "#08b454") {
                                itemElement4.style.color = "black";
                            } else if (itemElement4.style.backgroundColor === "#fffc04") {
                                itemElement4.style.color = "black";
                            } else if (itemElement4.style.backgroundColor === "#ffc404") {
                                itemElement4.style.color = "black";
                            } else if (itemElement4.style.backgroundColor === "red") {
                                itemElement4.style.color = "black";
                            }

                            itemElement4.innerText = item4;
                            itemElement1.innerText = item1
                            itemElement2.innerText = item2
                            itemElement3.innerText = item3
                            itemElement4.innerText = item4
                            itemElement5.innerText = item5

                            tr.appendChild(itemElement1)
                            tr.appendChild(itemElement2)
                            tr.appendChild(itemElement3)
                            tr.appendChild(itemElement4)
                            tr.appendChild(itemElement5)

                            tbody3.appendChild(tr)
                            // }
                        });


                        //untuk GM
                        var tr = document.createElement('tr')
                        let itemx1 = GM_list[2].est;
                        let itemx2 = GM_list[2].em;
                        let itemx3 = GM_list[2].nama;
                        let itemx4 = GM_list[2].skor;
                        let itemx5 = ''
                        let itemxElement1 = document.createElement('td')
                        let itemxElement2 = document.createElement('td')
                        let itemxElement3 = document.createElement('td')
                        let itemxElement4 = document.createElement('td')
                        let itemxElement5 = document.createElement('td')
                        itemxElement1.classList.add("text-center")
                        itemxElement2.classList.add("text-center")
                        itemxElement3.classList.add("text-center")
                        itemxElement4.classList.add("text-center")
                        itemxElement5.classList.add("text-center")
                        itemxElement1.style.backgroundColor = "#fff4cc";
                        itemxElement2.style.backgroundColor = "#fff4cc";
                        itemxElement3.style.backgroundColor = "#fff4cc";
                        if (itemx4 >= 95) {
                            itemxElement4.style.backgroundColor = "#0804fc";
                        } else if (itemx4 >= 85 && itemx4 < 95) {
                            itemxElement4.style.backgroundColor = "#08b454";
                        } else if (itemx4 >= 75 && itemx4 < 85) {
                            itemxElement4.style.backgroundColor = "#fffc04";
                        } else if (itemx4 >= 65 && itemx4 < 75) {
                            itemxElement4.style.backgroundColor = "#ffc404";
                        } else {
                            itemxElement4.style.backgroundColor = "red";
                        }
                        itemxElement1.innerText = itemx1;
                        itemxElement2.innerText = itemx2;
                        itemxElement3.innerText = itemx3;
                        itemxElement4.innerText = itemx4;
                        itemxElement5.innerText = itemx5;
                        tr.appendChild(itemxElement1)
                        tr.appendChild(itemxElement2)
                        tr.appendChild(itemxElement3)
                        tr.appendChild(itemxElement4)
                        tr.appendChild(itemxElement5)
                        tbody3.appendChild(tr)


                        // // <thead id="theadreg">

                        //buat menambahkan berdsarkan inputan reg 

                        let regIonal = '';
                        let regIonalRH = '';
                        let regIonalNama = '';
                        let titleHead1 = '';
                        let titleHead2 = '';
                        let titleHead3 = '';

                        switch (regInpt) {
                            case '1':
                                regIonal = 'REG-I';
                                regIonalRH = 'RH-I';
                                regIonalNama = 'Akhmad Faisyal';
                                titleHead1 = 'WIL I';
                                titleHead2 = 'WIL II';
                                titleHead3 = 'WIL III';
                                break;
                            case '2':
                                regIonal = 'REG-II';
                                regIonalRH = 'RH-II';
                                regIonalNama = '';
                                titleHead1 = 'WIL IV';
                                titleHead2 = 'WIL V';
                                titleHead3 = 'WIL VI';
                                break;
                            case '3':
                                regIonal = 'REG-III';
                                regIonalRH = 'RH-III';
                                regIonalNama = '';
                                titleHead1 = 'WIL VII';
                                titleHead2 = 'WIL VIII';
                                titleHead3 = 'WIL NULL';

                                break;
                        }


                        var theadreg = document.getElementById('theadregs');

                        // console.log(regional);

                        var tr = document.createElement('tr')
                        let reg1 = regIonal
                        let reg2 = regIonalRH
                        let reg3 = regIonalNama
                        let reg4 = regional[0][1]
                        // let reg4 = '-'
                        let regElement1 = document.createElement('td')
                        let regElement2 = document.createElement('td')
                        let regElement3 = document.createElement('td')
                        let regElement4 = document.createElement('td')

                        regElement1.classList.add("text-center")
                        regElement2.classList.add("text-center")
                        regElement3.classList.add("text-center")
                        regElement4.classList.add("text-center")

                        regElement1.style.backgroundColor = "#c8e4b4";
                        regElement2.style.backgroundColor = "#c8e4b4";
                        regElement3.style.backgroundColor = "#c8e4b4";
                        if (reg4 === '-') {
                            regElement4.style.backgroundColor = "white";
                        } else if (reg4 >= '95') {
                            regElement4.style.backgroundColor = "#0804fc";
                        } else if (reg4 >= '85' && reg4 < '95') {
                            regElement4.style.backgroundColor = "#08b454";
                        } else if (reg4 >= '75' && reg4 < '85') {
                            regElement4.style.backgroundColor = "red";
                        } else if (reg4 >= '65' && reg4 < '75') {
                            regElement4.style.backgroundColor = "#ffc404";
                        } else {
                            regElement4.style.backgroundColor = "red";
                        }

                        regElement1.innerText = reg1;
                        regElement2.innerText = reg2;
                        regElement3.innerText = reg3;
                        regElement4.innerText = reg4;

                        tr.appendChild(regElement1)
                        tr.appendChild(regElement2)
                        tr.appendChild(regElement3)
                        tr.appendChild(regElement4)

                        theadreg.appendChild(tr)




                        //chart

                        var wilayah = '['
                        list_will.forEach(element => {
                            wilayah += '"' + element + '",'
                        });

                        wilayah = wilayah.substring(0, wilayah.length - 1);
                        wilayah += ']'

                        var brd = '['
                        if (chart_btt.length > 0) {
                            chart_btt.forEach(element => {
                                brd += '"' + element[1] + '",'
                            });
                            brd = brd.substring(0, brd.length - 1);
                        } else {
                            brd = '[0, 0 , 0, 0, 0, 0, 0 , 0 ,0 , 0 , 0 , 0]'
                        }
                        brd += ']'

                        var buah = '['
                        chart_buah.forEach(element => {
                            buah += '"' + element[1] + '",'
                        });
                        buah = buah.substring(0, buah.length - 1);
                        buah += ']'

                        var bttWil = '['
                        chartWillbt.forEach(element => {
                            bttWil += '"' + element[1] + '",'
                        });
                        bttWil = bttWil.substring(0, bttWil.length - 1);
                        bttWil += ']'

                        var bhWil = '['
                        chartWillbh.forEach(element => {
                            bhWil += '"' + element[1] + '",'
                        });
                        bhWil = bhWil.substring(0, bhWil.length - 1);
                        bhWil += ']'

                        var estate = JSON.parse(wilayah)
                        var brd_jjgJson = JSON.parse(brd)
                        var buah_jjgJson = JSON.parse(buah)

                        var brd_wilJson = JSON.parse(bttWil)
                        var buah_wilJson = JSON.parse(bhWil)


                        const arr = estate;
                        let formatEst;

                        if (regInpt === '1') {
                            formatEst = Array.from(arr, (item, index) => {
                                    const value = item.split(',')[1];
                                    return {
                                        index,
                                        value
                                    };
                                })
                                .filter((item) => item.index < 13 || item.index > 15)
                                .map((item) => item.value);

                            formatEst.push("PT.MUA");
                        } else {

                            formatEst = Array.from(arr, (item, index) => {
                                    const value = item.split(',')[1];
                                    return {
                                        index,
                                        value
                                    };
                                })
                                .map((item) => item.value);
                        }

                        // console.log(formatEst);




                        // let regInpt = reg;
                        let wilayahReg = '';


                        if (regInpt === '1') {
                            wilayahReg = ['WIL I', 'WIL II', 'WIL III']

                        } else if (regInpt === '2') {
                            wilayahReg = ['WIL IV', 'WIL V', 'WIL VI']

                        } else if (regInpt === '3') {
                            wilayahReg = ['WIL VII', 'WIL VIII']

                        } else if (regInpt === '4') {
                            wilayahReg = ['WIL IX', 'WIL X']

                        }

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

                        chartGrains.updateSeries([{
                            name: 'butir/jjg panen',
                            data: brd_jjgJson,

                        }])
                        chartGrains.updateOptions({
                            xaxis: {
                                categories: formatEst
                            },
                            colors: colors // Set the colors directly, no need for an object
                        })




                        chartFruits.updateSeries([{
                            name: '% buah tinggal',
                            data: buah_jjgJson,

                        }])
                        chartFruits.updateOptions({
                            xaxis: {
                                categories: formatEst
                            },
                            colors: colors // Set the colors directly, no need for an object
                        })

                        chartGrainWils.updateSeries([{
                            name: 'butir/jjg panen',
                            data: brd_wilJson
                        }])

                        chartGrainWils.updateOptions({
                            xaxis: {
                                categories: wilayahReg
                            }
                        })


                        chartFruitWils.updateSeries([{
                            name: '% buah tinggal',
                            data: buah_wilJson
                        }])

                        chartFruitWils.updateOptions({
                            xaxis: {
                                categories: wilayahReg
                            }
                        })


                        var mttrans_brd = Object.entries(parseResult['mttrans_brd'])
                        var mttrans_buah = Object.entries(parseResult['mttrans_buah'])
                        var mttrans_wilbrd = Object.entries(parseResult['mttrans_wilbrd'])
                        var mttrans_wilbuah = Object.entries(parseResult['mttrans_wilbuah'])

                        var mutu_transbrd = '['
                        mttrans_brd.forEach(element => {
                            mutu_transbrd += '"' + element[1] + '",'
                        });
                        mutu_transbrd = mutu_transbrd.substring(0, mutu_transbrd.length - 1);
                        mutu_transbrd += ']'
                        var finaltrans_brd = JSON.parse(mutu_transbrd)

                        var mutu_transbuah = '['
                        mttrans_buah.forEach(element => {
                            mutu_transbuah += '"' + element[1] + '",'
                        });
                        mutu_transbuah = mutu_transbuah.substring(0, mutu_transbuah.length - 1);
                        mutu_transbuah += ']'
                        var final_transbuah = JSON.parse(mutu_transbuah)

                        var mutu_transwilbrd = '['
                        mttrans_wilbrd.forEach(element => {
                            mutu_transwilbrd += '"' + element[1] + '",'
                        });
                        mutu_transwilbrd = mutu_transwilbrd.substring(0, mutu_transwilbrd.length - 1);
                        mutu_transwilbrd += ']'
                        var finalwil_transbrd = JSON.parse(mutu_transwilbrd)

                        var mutu_transwilbuah = '['
                        mttrans_wilbuah.forEach(element => {
                            mutu_transwilbuah += '"' + element[1] + '",'
                        });
                        mutu_transwilbuah = mutu_transwilbuah.substring(0, mutu_transwilbuah.length - 1);
                        mutu_transwilbuah += ']'
                        var finalwil_transbuah = JSON.parse(mutu_transwilbuah)

                        weekly_transBRD.updateSeries([{
                            name: 'brd/tph',
                            data: finaltrans_brd,

                        }])
                        weekly_transBRD.updateOptions({
                            xaxis: {
                                categories: formatEst
                            },
                            colors: colors // Set the colors directly, no need for an object
                        })
                        weekly_transBuah.updateSeries([{
                            name: 'brd/tph',
                            data: finalwil_transbrd
                        }])

                        weekly_transBuah.updateOptions({
                            xaxis: {
                                categories: wilayahReg
                            }
                        })

                        weekly_transBRDWIL.updateSeries([{
                            name: 'brd/tph',
                            data: final_transbuah,

                        }])
                        weekly_transBRDWIL.updateOptions({
                            xaxis: {
                                categories: formatEst
                            },
                            colors: colors // Set the colors directly, no need for an object
                        })

                        weekly_transBuahWIL.updateSeries([{
                            name: 'buah/tph',
                            data: finalwil_transbuah
                        }])

                        weekly_transBuahWIL.updateOptions({
                            xaxis: {
                                categories: wilayahReg
                            }
                        })


                        var mtbuah_mentah = Object.entries(parseResult['mtbuah_mentah'])
                        var mtbuah_masak = Object.entries(parseResult['mtbuah_masak'])
                        var mtbuah_over = Object.entries(parseResult['mtbuah_over'])
                        var mtbuah_abnr = Object.entries(parseResult['mtbuah_abnr'])
                        var mtbuah_ksong = Object.entries(parseResult['mtbuah_ksong'])
                        var mtbuah_vcut = Object.entries(parseResult['mtbuah_vcut'])
                        var willBuah_Mentah = Object.entries(parseResult['willBuah_Mentah'])
                        var willBuah_Masak = Object.entries(parseResult['willBuah_Masak'])
                        var willBuah_Over = Object.entries(parseResult['willBuah_Over'])
                        var willBuah_Abr = Object.entries(parseResult['willBuah_Abr'])
                        var willBuah_Kosong = Object.entries(parseResult['willBuah_Kosong'])
                        var willBuah_Vcut = Object.entries(parseResult['willBuah_Vcut'])

                        // mutu buah 
                        var mutubuah_mentah = '['
                        mtbuah_mentah.forEach(element => {
                            mutubuah_mentah += '"' + element[1] + '",'
                        });
                        mutubuah_mentah = mutubuah_mentah.substring(0, mutubuah_mentah.length - 1);
                        mutubuah_mentah += ']'
                        var finalbh_mentah = JSON.parse(mutubuah_mentah)
                        var mutubuah_msak = '['
                        mtbuah_masak.forEach(element => {
                            mutubuah_msak += '"' + element[1] + '",'
                        });
                        mutubuah_msak = mutubuah_msak.substring(0, mutubuah_msak.length - 1);
                        mutubuah_msak += ']'
                        var finalbh_masak = JSON.parse(mutubuah_msak)
                        var mutubuah_over = '['
                        mtbuah_over.forEach(element => {
                            mutubuah_over += '"' + element[1] + '",'
                        });
                        mutubuah_over = mutubuah_over.substring(0, mutubuah_over.length - 1);
                        mutubuah_over += ']'
                        var finalbh_over = JSON.parse(mutubuah_over)
                        var mutubuah_abnr = '['
                        mtbuah_abnr.forEach(element => {
                            mutubuah_abnr += '"' + element[1] + '",'
                        });
                        mutubuah_abnr = mutubuah_abnr.substring(0, mutubuah_abnr.length - 1);
                        mutubuah_abnr += ']'
                        var finalbh_abnormal = JSON.parse(mutubuah_abnr)
                        var mutubuah_ksong = '['
                        mtbuah_ksong.forEach(element => {
                            mutubuah_ksong += '"' + element[1] + '",'
                        });
                        mutubuah_ksong = mutubuah_ksong.substring(0, mutubuah_ksong.length - 1);
                        mutubuah_ksong += ']'
                        var finalbh_ksong = JSON.parse(mutubuah_ksong)
                        var mutubuah_vcut = '['
                        mtbuah_vcut.forEach(element => {
                            mutubuah_vcut += '"' + element[1] + '",'
                        });
                        mutubuah_vcut = mutubuah_vcut.substring(0, mutubuah_vcut.length - 1);
                        mutubuah_vcut += ']'
                        var finalbh_vcut = JSON.parse(mutubuah_vcut)


                        var wilayahbuah_mentah = '['
                        willBuah_Mentah.forEach(element => {
                            wilayahbuah_mentah += '"' + element[1] + '",'
                        });
                        wilayahbuah_mentah = wilayahbuah_mentah.substring(0, wilayahbuah_mentah.length - 1);
                        wilayahbuah_mentah += ']'
                        var finalwilayahmentah = JSON.parse(wilayahbuah_mentah)
                        // console.log();

                        var wilayahbuah_masak = '['
                        willBuah_Masak.forEach(element => {
                            wilayahbuah_masak += '"' + element[1] + '",'
                        });
                        wilayahbuah_masak = wilayahbuah_masak.substring(0, wilayahbuah_masak.length - 1);
                        wilayahbuah_masak += ']'
                        var finalwilayahmasak = JSON.parse(wilayahbuah_masak)

                        var wilayahbuah_over = '['
                        willBuah_Over.forEach(element => {
                            wilayahbuah_over += '"' + element[1] + '",'
                        });
                        wilayahbuah_over = wilayahbuah_over.substring(0, wilayahbuah_over.length - 1);
                        wilayahbuah_over += ']'
                        var finalwilayahover = JSON.parse(wilayahbuah_over)

                        var wilayahbuah_abr = '['
                        willBuah_Abr.forEach(element => {
                            wilayahbuah_abr += '"' + element[1] + '",'
                        });
                        wilayahbuah_abr = wilayahbuah_abr.substring(0, wilayahbuah_abr.length - 1);
                        wilayahbuah_abr += ']'
                        var finalwilayahabr = JSON.parse(wilayahbuah_abr)

                        var wilayahbuah_kosong = '['
                        willBuah_Kosong.forEach(element => {
                            wilayahbuah_kosong += '"' + element[1] + '",'
                        });
                        wilayahbuah_kosong = wilayahbuah_kosong.substring(0, wilayahbuah_kosong.length - 1);
                        wilayahbuah_kosong += ']'
                        var finalwilayahkosong = JSON.parse(wilayahbuah_kosong)

                        var wilayahbuah_vcut = '['
                        willBuah_Vcut.forEach(element => {
                            wilayahbuah_vcut += '"' + element[1] + '",'
                        });
                        wilayahbuah_vcut = wilayahbuah_vcut.substring(0, wilayahbuah_vcut.length - 1);
                        wilayahbuah_vcut += ']'
                        var finalwilayahvcut = JSON.parse(wilayahbuah_vcut)


                        // chart mutu buah estate 
                        weekly_mtb_mentah.updateSeries([{
                            name: 'Mentah/tph',
                            data: finalbh_mentah,

                        }])
                        weekly_mtb_mentah.updateOptions({
                            xaxis: {
                                categories: formatEst
                            },
                            colors: colors // Set the colors directly, no need for an object
                        })

                        weekly_mtb_masak.updateSeries([{
                            name: 'Masak/tph',
                            data: finalbh_masak,

                        }])
                        weekly_mtb_masak.updateOptions({
                            xaxis: {
                                categories: formatEst
                            },
                            colors: colors // Set the colors directly, no need for an object
                        })

                        weekly_mtb_over.updateSeries([{
                            name: 'Over/tph',
                            data: finalbh_over,

                        }])
                        weekly_mtb_over.updateOptions({
                            xaxis: {
                                categories: formatEst
                            },
                            colors: colors // Set the colors directly, no need for an object
                        })

                        weekly_mtb_abnormal.updateSeries([{
                            name: 'Abnormal/tph',
                            data: finalbh_abnormal,

                        }])
                        weekly_mtb_abnormal.updateOptions({
                            xaxis: {
                                categories: formatEst
                            },
                            colors: colors // Set the colors directly, no need for an object
                        })

                        weekly_mtb_kosong.updateSeries([{
                            name: 'Kosong/tph',
                            data: finalbh_ksong,

                        }])
                        weekly_mtb_kosong.updateOptions({
                            xaxis: {
                                categories: formatEst
                            },
                            colors: colors // Set the colors directly, no need for an object
                        })

                        weekly_mtb_vcut.updateSeries([{
                            name: 'tidak standar vcut',
                            data: finalbh_vcut,

                        }])
                        weekly_mtb_vcut.updateOptions({
                            xaxis: {
                                categories: formatEst
                            },
                            colors: colors // Set the colors directly, no need for an object
                        })



                        // wilayah 
                        weekly_mtbwil_mentah.updateSeries([{
                            name: 'mentah/tph',
                            data: finalwilayahmentah
                        }])

                        weekly_mtbwil_mentah.updateOptions({
                            xaxis: {
                                categories: wilayahReg
                            }
                        })


                        weekly_mtbwil_masak.updateSeries([{
                            name: 'masak/tph',
                            data: finalwilayahmasak
                        }])

                        weekly_mtbwil_masak.updateOptions({
                            xaxis: {
                                categories: wilayahReg
                            }
                        })

                        weekly_mtbwil_over.updateSeries([{
                            name: 'over/tph',
                            data: finalwilayahover
                        }])

                        weekly_mtbwil_over.updateOptions({
                            xaxis: {
                                categories: wilayahReg
                            }
                        })

                        weekly_mtbwil_abnormal.updateSeries([{
                            name: 'abnormal/tph',
                            data: finalwilayahabr
                        }])

                        weekly_mtbwil_abnormal.updateOptions({
                            xaxis: {
                                categories: wilayahReg
                            }
                        })

                        weekly_mtbwil_kosong.updateSeries([{
                            name: 'kosong/tph',
                            data: finalwilayahkosong
                        }])

                        weekly_mtbwil_kosong.updateOptions({
                            xaxis: {
                                categories: wilayahReg
                            }
                        })
                        weekly_mtbwil_vcut.updateSeries([{
                            name: 'tidak standar vcut',
                            data: finalwilayahvcut
                        }])

                        weekly_mtbwil_vcut.updateOptions({
                            xaxis: {
                                categories: wilayahReg
                            }
                        })


                    }
                });
            }

            // Event listeners


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
                            est: [16, 18, 20, 3],
                            rank: [16, 18, 20, 3]
                        },
                        '2': {
                            est: [16, 13, 10, 5],
                            rank: [16, 13, 10, 5]
                        },
                        '3': {
                            est: [20, 11, 10, 3],
                            rank: [20, 11, 10, 3]
                        }
                    };

                    const tbodies = ['tbody1', 'tbody2', 'tbody3', 'plbody'];
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
                const estBtn = document.getElementById('sort-afd-btnWeek');
                const rankBtn = document.getElementById('sort-est-btnWeek');
                const showBtn = document.getElementById('showWeek');
                const regionalSelect = document.getElementById('regionalDataweek');
                const scrennshotimg = document.getElementById('scrennshotimg');
                const downloadimgmap = document.getElementById('downloadimgmap');

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
                            est: [16, 18, 20, 3],
                            rank: [16, 18, 20, 3]
                        },
                        '2': {
                            est: [16, 13, 10, 5],
                            rank: [16, 13, 10, 5]
                        },
                        '3': {
                            est: [20, 11, 10, 3],
                            rank: [20, 11, 10, 3]
                        }
                    };

                    const tbodies = ['tbodys1', 'tbodys2', 'tbodys3', 'plbodys'];
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

                scrennshotimg.addEventListener('click', () => {

                    captureTableScreenshot('screnshot_bulanan', 'REKAPITULASI RANKING NILAI KUALITAS PANEN')
                });
                downloadimgmap.addEventListener('click', () => {

                    captureTableScreenshot('map', 'SCORE KUALITAS PANEN BERDASARKAN BLOK')
                });
            });


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

            // filter grafik perwilayah
            // Define the group data obtained from the server
            var groupData = <?php echo json_encode($groupedArray); ?>;

            // Function to populate the options for the wilayah preset filter
            function populateWilayahOptions(regionalValue) {
                var wilayahSelect = $('#wilayahGrafik');
                wilayahSelect.empty();

                // Define the minimum and maximum wilayah values based on the selected regional value
                var minWilayah, maxWilayah;
                if (regionalValue == 1) {
                    minWilayah = 1;
                    maxWilayah = 3;
                } else if (regionalValue == 2) {
                    minWilayah = 4;
                    maxWilayah = 6;
                } else if (regionalValue == 3) {
                    minWilayah = 7;
                    maxWilayah = 8;
                } else if (regionalValue == 4) {
                    minWilayah = 10;
                    maxWilayah = 11;
                }

                // Populate the options for the wilayah preset filter
                for (var i = minWilayah; i <= maxWilayah; i++) {
                    var option = $('<option>').val(i).text('Wilayah ' + i);
                    wilayahSelect.append(option);
                }

                // Trigger change event for the wilayah preset filter to populate the options for the estate filter
                wilayahSelect.trigger('change');
            }

            // Function to populate the options for the estate filter


            function populateEstateOptions(wilayahValue) {
                var estateSelect = $('#estateGrafik');
                estateSelect.empty();

                // Get the corresponding estate array based on the selected wilayah preset value
                var estateArray = groupData[wilayahValue];
                // console.log(estateArray);

                // for (var i = 0; i < estateArray.length; i++) {
                //     if (estateArray[i] === 'KTE4') {
                //         estateArray[i] = 'KTE';
                //     }
                // }

                // console.log(estateArray);

                // Populate the options for the estate filter
                $.each(estateArray, function(index, value) {
                    var option = $('<option>').val(value).text(value);
                    estateSelect.append(option);
                });
            }

            // Event listener for the regional filter change
            $('#regGrafik').on('change', function() {
                var selectedRegional = $(this).val();

                // Populate the options for the wilayah preset filter based on the selected regional value
                populateWilayahOptions(selectedRegional);
            });

            // Event listener for the wilayah preset filter change
            $('#wilayahGrafik').on('change', function() {
                var selectedWilayah = $(this).val();

                // Populate the options for the estate filter based on the selected wilayah preset value
                populateEstateOptions(selectedWilayah);
            });

            // Initial population of the wilayah preset options based on the default regional value
            var defaultRegional = $('#regGrafik').val();
            populateWilayahOptions(defaultRegional);

            function graphFilter() {
                var est = ''
                var yearGraph = ''
                var reg = ''
                var wilayahGrafik = ''
                var est = document.getElementById('estateGrafik').value
                var wilayahGrafik = document.getElementById('wilayahGrafik').value

                var yearGraph = document.getElementById('yearGraph').value
                var reg = document.getElementById('regGrafik').value
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('graphfilter') }}",
                    method: "GET",
                    data: {
                        est,
                        yearGraph,
                        reg,
                        wilayahGrafik,
                        _token: _token
                    },
                    success: function(result) {
                        Swal.close();
                        // console.log(est)
                        // console.log(yearGraph)
                        var parseResult = JSON.parse(result)
                        //list estate


                        var chart_btt = Object.entries(parseResult['GraphBtt'])
                        var chart_buah = Object.entries(parseResult['GraphBuah'])
                        var chart_skor = Object.entries(parseResult['GraphSkorTotal'])
                        var list_est = Object.entries(parseResult['list_est'])
                        var mtbuah_mth = Object.entries(parseResult['mtbuah_mth'])
                        var mtbuah_masak = Object.entries(parseResult['mtbuah_masak'])



                        var mtbuah_over = Object.entries(parseResult['mtbuah_over'])
                        var mtbuah_ksng = Object.entries(parseResult['mtbuah_ksng'])
                        var mtbuah_vcut = Object.entries(parseResult['mtbuah_vcut'])
                        var mtbuah_abr = Object.entries(parseResult['mtbuah_abr'])


                        var mttransbrd = Object.entries(parseResult['mttransbrd'])
                        var mttransbb = Object.entries(parseResult['mttransbb'])
                        var rekap_wil = Object.entries(parseResult['rekap_wil'])

                        var est_values = list_est.map(item => item[1].est);
                        // console.log(est_values);
                        // console.log(rekap_wil);

                        var regGrafik = document.getElementById("regGrafik");



                        var graphBtt = '['
                        chart_btt.forEach(element => {
                            graphBtt += '"' + element[1] + '",'
                        });
                        graphBtt = graphBtt.substring(0, graphBtt.length - 1);
                        graphBtt += ']'

                        var graphBuah = '['
                        chart_buah.forEach(element => {
                            graphBuah += '"' + element[1] + '",'
                        });
                        graphBuah = graphBuah.substring(0, graphBuah.length - 1);
                        graphBuah += ']'

                        var graphSkor = '['
                        chart_skor.forEach(element => {
                            graphSkor += '"' + element[1] + '",'
                        });
                        graphSkor = graphSkor.substring(0, graphSkor.length - 1);
                        graphSkor += ']'

                        // mutu buah
                        var buahmth = '['
                        mtbuah_mth.forEach(element => {
                            buahmth += '"' + element[1] + '",'
                        });
                        buahmth = buahmth.substring(0, buahmth.length - 1);
                        buahmth += ']'

                        var buahmsk = '['
                        mtbuah_masak.forEach(element => {
                            buahmsk += '"' + element[1] + '",'
                        });
                        buahmsk = buahmsk.substring(0, buahmsk.length - 1);
                        buahmsk += ']'





                        var buahovr = '['
                        mtbuah_over.forEach(element => {
                            buahovr += '"' + element[1] + '",'
                        });
                        buahovr = buahovr.substring(0, buahovr.length - 1);
                        buahovr += ']'

                        var buahKsong = '['
                        mtbuah_ksng.forEach(element => {
                            buahKsong += '"' + element[1] + '",'
                        });
                        buahKsong = buahKsong.substring(0, buahKsong.length - 1);
                        buahKsong += ']'

                        var buahvcuts = '['
                        mtbuah_vcut.forEach(element => {
                            buahvcuts += '"' + element[1] + '",'
                        });
                        buahvcuts = buahvcuts.substring(0, buahvcuts.length - 1);
                        buahvcuts += ']'

                        var buahbrnrm = '['
                        mtbuah_abr.forEach(element => {
                            buahbrnrm += '"' + element[1] + '",'
                        });
                        buahbrnrm = buahbrnrm.substring(0, buahbrnrm.length - 1);
                        buahbrnrm += ']'


                        var transbrd = '['
                        mttransbrd.forEach(element => {
                            transbrd += '"' + element[1] + '",'
                        });
                        transbrd = transbrd.substring(0, transbrd.length - 1);
                        transbrd += ']'

                        var transbuah = '['
                        mttransbb.forEach(element => {
                            transbuah += '"' + element[1] + '",'
                        });
                        transbuah = transbuah.substring(0, transbuah.length - 1);
                        transbuah += ']'


                        var bttJson = JSON.parse(graphBtt)
                        var bhJson = JSON.parse(graphBuah)
                        var skorJson = JSON.parse(graphSkor)

                        var mths = JSON.parse(buahmth)
                        var masak = JSON.parse(buahmsk)

                        var ovr = JSON.parse(buahovr)
                        var Ksong = JSON.parse(buahKsong)
                        var vcuts = JSON.parse(buahvcuts)
                        var brnrm = JSON.parse(buahbrnrm)
                        var trbrd = JSON.parse(transbrd)
                        var trbuah = JSON.parse(transbuah)


                        // console.log(ovr);
                        // <!-- var wil_rekap = rekap_wil.map(item => item[0]); -->
                        // console.log(wil_rekap);
                        chartScore.updateSeries([{
                            name: est,
                            data: skorJson
                        }, ])



                        chartScore.updateOptions({
                            yaxis: {
                                show: true,
                                showAlways: true,
                                showForNullSeries: true,
                                seriesName: est,
                                opposite: false,
                                reversed: false,
                                logarithmic: false,
                                logBase: 10,
                                tickAmount: 5,
                                min: 0,
                                max: 100,
                                forceNiceScale: false,
                                floating: false,
                                decimalsInFloat: undefined,
                                crosshairs: {
                                    show: true,
                                    position: 'back',
                                    stroke: {
                                        color: '#b6b6b6',
                                        width: 1,
                                        dashArray: 0,
                                    },
                                },
                                tooltip: {
                                    enabled: true,
                                    offsetX: 0,
                                },

                            },
                            annotations: {
                                yaxis: [{
                                        y: 95,
                                        y2: 100,
                                        borderColor: '#000',
                                        fillColor: '#96be25',
                                        opacity: 0.4,
                                        label: {
                                            text: ' '
                                        }
                                    }, {
                                        y: 85,
                                        y2: 95,
                                        borderColor: '#000',
                                        fillColor: '#78ac44',
                                        opacity: 0.4,
                                        label: {
                                            text: ''
                                        }
                                    }, {
                                        y: 75,
                                        y2: 85,
                                        borderColor: '#000',
                                        fillColor: '#fffc04',
                                        opacity: 0.4,
                                        label: {
                                            text: 'Standar QC'
                                        }
                                    }, {
                                        y: 65,
                                        y2: 75,
                                        borderColor: '#000',
                                        fillColor: '#f07c34',
                                        opacity: 0.4,
                                        label: {
                                            text: ''
                                        }
                                    }, {
                                        y: 0,
                                        y2: 65,
                                        borderColor: '#000',
                                        fillColor: '#ff0404',
                                        opacity: 0.4,


                                        label: {
                                            text: ''
                                        }
                                    }

                                ]
                            },
                        });




                        var seriesData = [];

                        for (var i = 0; i < rekap_wil.length; i++) {
                            var item = rekap_wil[i];
                            var name = item[0];
                            var data = Object.values(item[1]).map(function(value) {
                                return value || 0;
                            });
                            var series = {
                                name: name,
                                data: data
                            };

                            // Assign different colors to each series
                            var color = getRandomColor(); // Generate a random color for each series
                            series.color = color;

                            seriesData.push(series);
                        }

                        options.series = seriesData;

                        function getColorByNumber(number) {
                            const colors = ["red", "blue", "yellow", "green", "pink", "black"];

                            // If the number is out of range, return a random color instead
                            if (number < 1 || number > colors.length) {
                                return getRandomColor();
                            }

                            return colors[number - 1];
                        }

                        function getRandomColor() {
                            var letters = "0123456789ABCDEF";
                            var color = "#";
                            for (var i = 0; i < 6; i++) {
                                color += letters[Math.floor(Math.random() * 16)];
                            }
                            return color;
                        }


                        chartScorePerwil.updateOptions(options);
                        chartScorePerwil.updateOptions({
                            stroke: {
                                curve: 'smooth',
                            }
                        });



                        chartScoreBron.updateSeries([{
                                name: est,
                                data: bttJson
                            },


                        ])
                        chartScoreBron.updateOptions({
                            colors: ['#041014'],
                            yaxis: [{
                                axisTicks: {
                                    show: true
                                },
                                axisBorder: {
                                    show: true,
                                    color: "#FF1654"
                                },
                                labels: {
                                    style: {
                                        colors: "#FF1654"
                                    }
                                },
                                title: {
                                    text: est,
                                    style: {
                                        color: "#FF1654"
                                    }
                                }
                            }],
                            annotations: {
                                yaxis: [{
                                    y: 0,
                                    y2: 1,
                                    borderColor: '#000',
                                    fillColor: '#96be25',
                                    opacity: 0.4,
                                    label: {
                                        text: ' '
                                    }
                                }, {
                                    y: 1,
                                    y2: 10,
                                    borderColor: '#000',
                                    fillColor: '#ff0404',
                                    opacity: 0.4,
                                    label: {
                                        text: 'Standar QC'
                                    }
                                }]
                            },
                        });
                        chatScoreJan.updateSeries([{
                                name: est,
                                data: bhJson
                            },


                        ])
                        chatScoreJan.updateOptions({
                            colors: ['#041014'],
                            yaxis: [{
                                axisTicks: {
                                    show: true
                                },
                                axisBorder: {
                                    show: true,
                                    color: "#FF1654"
                                },
                                labels: {
                                    style: {
                                        colors: "#FF1654"
                                    }
                                },
                                title: {
                                    text: est,
                                    style: {
                                        color: "#FF1654"
                                    }
                                }
                            }],
                            annotations: {
                                yaxis: [{
                                    y: -10,
                                    y2: 0,
                                    borderColor: '#000',
                                    fillColor: '#96be25',
                                    opacity: 0.4,
                                    label: {
                                        text: ' '
                                    }
                                }, {
                                    y: 0,
                                    y2: 10,
                                    borderColor: '#000',
                                    fillColor: '#ff0404',
                                    opacity: 0.4,
                                    label: {
                                        text: 'Standar QC'
                                    }
                                }]
                            },
                        });
                        GraphBhmth.updateSeries([{
                            name: est,
                            data: mths
                        }, ])
                        GraphBhmth.updateOptions({
                            colors: ['#041014'],
                            yaxis: [{
                                axisTicks: {
                                    show: true
                                },
                                axisBorder: {
                                    show: true,
                                    color: "#FF1654"
                                },
                                labels: {
                                    style: {
                                        colors: "#FF1654"
                                    }
                                },
                                title: {
                                    text: est,
                                    style: {
                                        color: "#FF1654"
                                    }
                                }
                            }],
                            annotations: {
                                yaxis: [{
                                    y: 1,
                                    y2: 100,
                                    borderColor: '#000',

                                    fillColor: '#ff0404', //merah
                                    opacity: 0.4,
                                    label: {
                                        text: ' '
                                    }
                                }, {
                                    y: -10,
                                    y2: 1,
                                    borderColor: '#000',
                                    fillColor: '#96be25', //biru
                                    opacity: 0.4,
                                    label: {
                                        text: 'Standar QC'
                                    }
                                }]
                            },
                        });
                        GraphBhMsak.updateSeries([{
                                name: est,
                                data: masak
                            },

                        ])
                        GraphBhMsak.updateOptions({
                            colors: ['#041014'],
                            yaxis: [{
                                axisTicks: {
                                    show: true
                                },
                                axisBorder: {
                                    show: true,
                                    color: "#FF1654"
                                },
                                labels: {
                                    style: {
                                        colors: "#FF1654"
                                    }
                                },
                                title: {
                                    text: est,
                                    style: {
                                        color: "#FF1654"
                                    }
                                }
                            }],
                            annotations: {
                                yaxis: [{
                                    y: 1,
                                    y2: 90,
                                    borderColor: '#000',

                                    fillColor: '#ff0404', //merah
                                    opacity: 0.4,
                                    label: {
                                        text: ' '
                                    }
                                }, {
                                    y: 90,
                                    y2: 100,
                                    borderColor: '#000',
                                    fillColor: '#96be25', //biru
                                    opacity: 0.4,
                                    label: {
                                        text: 'Standar QC'
                                    }
                                }]
                            },
                        });
                        GraphBhOver.updateSeries([{
                                name: est,
                                data: ovr
                            },

                        ])
                        GraphBhOver.updateOptions({
                            colors: ['#041014'],
                            yaxis: [{
                                axisTicks: {
                                    show: true
                                },
                                axisBorder: {
                                    show: true,
                                    color: "#FF1654"
                                },
                                labels: {
                                    style: {
                                        colors: "#FF1654"
                                    }
                                },
                                title: {
                                    text: est,
                                    style: {
                                        color: "#FF1654"
                                    }
                                }
                            }],
                            annotations: {
                                yaxis: [{
                                    y: 2,
                                    y2: 100,
                                    borderColor: '#000',

                                    fillColor: '#ff0404', //merah
                                    opacity: 0.4,
                                    label: {
                                        text: ' '
                                    }
                                }, {
                                    y: 0,
                                    y2: 2,
                                    borderColor: '#000',
                                    fillColor: '#96be25', //biru
                                    opacity: 0.4,
                                    label: {
                                        text: 'Standar QC'
                                    }
                                }]
                            },
                        });
                        GraphBhEmpty.updateSeries([{
                                name: est,
                                data: Ksong
                            },

                        ])
                        GraphBhEmpty.updateOptions({
                            colors: ['#041014'],
                            yaxis: [{
                                axisTicks: {
                                    show: true
                                },
                                axisBorder: {
                                    show: true,
                                    color: "#FF1654"
                                },
                                labels: {
                                    style: {
                                        colors: "#FF1654"
                                    }
                                },
                                title: {
                                    text: est,
                                    style: {
                                        color: "#FF1654"
                                    }
                                }
                            }],
                            annotations: {
                                yaxis: [{
                                    y: 1,
                                    y2: 100,
                                    borderColor: '#000',

                                    fillColor: '#ff0404', //merah
                                    opacity: 0.4,
                                    label: {
                                        text: ' '
                                    }
                                }, {
                                    y: -10,
                                    y2: 1,
                                    borderColor: '#000',
                                    fillColor: '#96be25', //biru
                                    opacity: 0.4,
                                    label: {
                                        text: 'Standar QC'
                                    }
                                }]
                            },
                        });
                        GraphBhvcute.updateSeries([{
                            name: est,
                            data: vcuts
                        }, ])
                        GraphBhvcute.updateOptions({
                            colors: ['#041014'],
                            yaxis: [{
                                axisTicks: {
                                    show: true
                                },
                                axisBorder: {
                                    show: true,
                                    color: "#FF1654"
                                },
                                labels: {
                                    style: {
                                        colors: "#FF1654"
                                    }
                                },
                                title: {
                                    text: est,
                                    style: {
                                        color: "#FF1654"
                                    }
                                }
                            }],
                            annotations: {
                                yaxis: [{
                                    y: 2,
                                    y2: 100,
                                    borderColor: '#000',

                                    fillColor: '#ff0404', //merah
                                    opacity: 0.4,
                                    label: {
                                        text: ' '
                                    }
                                }, {

                                    y: -10,
                                    y2: 2,
                                    borderColor: '#000',
                                    fillColor: '#96be25', //biru
                                    opacity: 0.4,
                                    label: {
                                        text: 'Standar QC'
                                    }
                                }]
                            },
                        });
                        GraphBhAbnrl.updateSeries([{
                                name: est,
                                data: brnrm
                            },

                        ])
                        GraphBhAbnrl.updateOptions({
                            colors: ['#041014'],
                            yaxis: [{
                                axisTicks: {
                                    show: true
                                },
                                axisBorder: {
                                    show: true,
                                    color: "#FF1654"
                                },
                                labels: {
                                    style: {
                                        colors: "#FF1654"
                                    }
                                },
                                title: {
                                    text: est,
                                    style: {
                                        color: "#FF1654"
                                    }
                                }
                            }],
                            annotations: {
                                yaxis: [{
                                    y: 1,
                                    y2: 100,
                                    borderColor: '#000',

                                    fillColor: '#ff0404', //merah
                                    opacity: 0.4,
                                    label: {
                                        text: ' '
                                    }
                                }, {
                                    y: -10,
                                    y2: 1,
                                    borderColor: '#000',
                                    fillColor: '#96be25', //biru
                                    opacity: 0.4,
                                    label: {
                                        text: 'Standar QC'
                                    }
                                }]
                            },
                        });


                        GraphTranBrd.updateSeries([{
                            name: est,
                            data: trbrd
                        }, ])
                        GraphTranBrd.updateOptions({
                            colors: ['#041014'],
                            yaxis: [{
                                axisTicks: {
                                    show: true
                                },
                                axisBorder: {
                                    show: true,
                                    color: "#FF1654"
                                },
                                labels: {
                                    style: {
                                        colors: "#FF1654"
                                    }
                                },
                                title: {
                                    text: est,
                                    style: {
                                        color: "#FF1654"
                                    }
                                }
                            }],
                            annotations: {
                                yaxis: [{
                                    y: 3,
                                    y2: 100,
                                    borderColor: '#000',

                                    fillColor: '#ff0404', //merah
                                    opacity: 0.4,
                                    label: {
                                        text: ' '
                                    }
                                }, {
                                    y: 0,
                                    y2: 3,
                                    borderColor: '#000',
                                    fillColor: '#96be25', //biru
                                    opacity: 0.4,
                                    label: {
                                        text: 'Standar QC'
                                    }
                                }]
                            },
                        });

                        GraphTranBH.updateSeries([{
                                name: est,
                                data: trbuah
                            },

                        ])
                        GraphTranBH.updateOptions({
                            colors: ['#041014'],
                            yaxis: [{
                                axisTicks: {
                                    show: true
                                },
                                axisBorder: {
                                    show: true,
                                    color: "#FF1654"
                                },
                                labels: {
                                    style: {
                                        colors: "#FF1654"
                                    }
                                },
                                title: {
                                    text: est,
                                    style: {
                                        color: "#FF1654"
                                    }
                                }
                            }],
                            annotations: {
                                yaxis: [{
                                    y: 0,
                                    y2: 100,
                                    borderColor: '#000',

                                    fillColor: '#ff0404', //merah
                                    opacity: 0.4,
                                    label: {
                                        text: ' '
                                    }
                                }, {
                                    y: -10,
                                    y2: 0,
                                    borderColor: '#000',
                                    fillColor: '#96be25', //biru
                                    opacity: 0.4,
                                    label: {
                                        text: 'Standar QC'
                                    }
                                }]
                            },
                        });

                    }
                });
            }



            function convertMapToImage() {
                const mapContainer = document.getElementById('map');

                try {
                    buttonimg.style.display = 'none';
                    Swal.fire({
                        title: 'Tunggu saat siap download PDF',
                        html: '<span class="loading-text">Mohon Tunggu...</span>',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    html2canvas(mapContainer).then(function(canvas) {
                        var dataURL = canvas.toDataURL('image/png');
                        // console.log(dataURL);

                        var _token = $('input[name="_token"]').val();
                        var estData = $("#estDataMap").val();
                        var regData = $("#regDataMap").val();

                        // Create a FormData object to send the data as a POST request
                        var formData = new FormData();
                        formData.append('imgData', dataURL); // Fix the variable name to dataURL
                        formData.append('estData', estData);
                        formData.append('regData', regData);
                        formData.append('_token', _token);

                        // Use the $.ajax method to send the data to the server
                        $.ajax({
                            url: "{{ route('downloadMaptahun') }}",
                            method: "post",
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function(result) {
                                if (result.message === 'Image saved successfully') {
                                    Swal.close();

                                    let alert = Swal.fire({
                                        icon: 'success',
                                        title: 'PDF siap di download',
                                        showConfirmButton: false,
                                        html: '<a href="/pdfPage/' + result.filename + '/' + result.est + '" class="btn btn-primary download-btn" target="_blank"><i class="bi bi-filetype-pdf"></i> Download PDF</a>',
                                    });

                                    document.querySelector('.download-btn').addEventListener('click', function() {
                                        alert.close();
                                    });
                                } else {
                                    Swal.fire('Error', 'Operation Error', 'error');
                                }
                            },
                            error: function(error) {
                                console.error('Error in AJAX request:', error);
                                Swal.fire('Error', 'An error occurred while sending the data.', 'error');
                            }
                        });
                    });
                } catch (error) {
                    console.error('Unexpected error:', error);
                    Swal.fire('Error', 'An unexpected error occurred.', 'error');
                }
            }

            // showDataIns

            document.getElementById('exportForm').addEventListener('submit', function(event) {
                // Prevent the default form submission
                event.preventDefault();

                // Get the selected value from regDataIns select element
                var regDataInsValue = document.getElementById('regDataIns').value;

                // Get the value from dateDataIns input element
                var dateDataInsValue = document.getElementById('dateDataIns').value;

                // Set the values to the hidden inputs
                document.getElementById('getregional').value = regDataInsValue;
                document.getElementById('getdate').value = dateDataInsValue;

                // Open a new tab/window and submit the form there
                var newWindow = window.open('', '_blank');
                this.target = '_blank';
                this.submit();

                // Close the new tab/window after submission (optional)
                newWindow.close();
            });
        </script>

</x-layout.app>