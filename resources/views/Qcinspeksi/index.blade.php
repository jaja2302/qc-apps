<x-layout.app>

    <div class="card">
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

                <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 ">
                    <button class="btn btn-primary ml-2 mr-2" id="sort-est-btn">Sort by Afd</button>
                    <button class="btn btn-primary ml-2 mr-2" id="sort-rank-btn">Sort by Rank</button>
                    <button class="btn btn-primary ml-2 mr-2" id="scrennshotimg">Download As IMG</button>
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
                                            <p style="font-size: 15px; text-align: center;"><b><u>BRONDOLAN TINGGAL</u></b></p>
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

                </div>

            </div>


            <div class="tab-pane fade" id="nav-data" role="tabpanel" aria-labelledby="nav-data-tab">
                <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark">
                    <h5><b>DATA</b></h5>
                </div>

                <div class="d-flex justify-content-end mr-3 mt-4">
                    <div class="margin g-2">
                        <div class="row align-items-center">
                            <div class="col-md">
                                {{csrf_field()}}
                                <select class="form-control" id="regDataIns">
                                    @foreach($option_reg as $key => $item)
                                    <option value="{{ $item['id'] }}">{{ $item['nama'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md">
                                {{ csrf_field() }}
                                <input class="form-control" value="{{ date('Y-m') }}" type="month" name="tgl" id="dateDataIns">

                            </div>
                            <div class="col-auto">
                                <button class="btn btn-primary" id="showDataIns">Show</button>
                            </div>
                            @if (auth()->user()->lokasi_kerja === 'Regional II' && auth()->user()->id_departement == '43' && in_array(auth()->user()->id_jabatan, ['10', '15', '20', '4', '5', '6']) )
                            <div class="col-auto">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    Edit Nilai
                                </button>
                            </div>
                            @endif
                            <!-- Button trigger modal -->


                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Ubah Nilai Estate</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="basic-addon1">Estate</span>
                                                <select class="form-select" aria-label="Default select example" id="estateedit">
                                                    @foreach ($estateoption as $items)
                                                    <option value="{{$items['est']}}">{{$items['est']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text" id="basic-addon1">Nilai</span>
                                                <input type="number" class="form-control" placeholder="Nilai" aria-label="Nilai" id="nilaiedit">
                                            </div>
                                            <span class="input-group-text" id="basic-addon1">Status</span>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1" checked>
                                                <label class="form-check-label" for="flexRadioDefault1">
                                                    Kurangkan Nilai
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2">
                                                <label class="form-check-label" for="flexRadioDefault2">
                                                    Tambahkan Nilai
                                                </label>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary" id="editnilaiestate">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <form id="exportForm" action="{{ route('excelqcinspeksi') }}" method="POST">
                                    @csrf
                                    <input type="hidden" id="getregional" name="getregional">
                                    <input type="hidden" id="getdate" name="getdate">
                                    <button type="submit" class="btn btn-primary">Export</button>
                                </form>
                            </div>
                        </div>
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

                <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark">
                    <div class="table-wrapper">
                        <table class="my-table">
                            <thead>
                                <tr>
                                    <th rowspan="3" class="sticky" style="background-color: #883c0c;">EST</th>
                                    <th rowspan="3" class="sticky" style="background-color: #883c0c;">AFD.</th>
                                    <th colspan="4" rowspan="2" class="sticky" style="background-color: #883c0c;">DATA BLOK SAMPEL</th>
                                    <th colspan="17" class="sticky" style="background-color: #96be25;">Mutu Ancak (MA)</th>
                                    <th colspan="8" class="sticky" style="background-color: #25a5be;">Mutu Transport (MT)</th>
                                    <th colspan="22" class="sticky" style="background-color: #be4d25;">Mutu Buah (MB)</th>
                                    <th rowspan="3" class="sticky" style="background-color: #883c0c;">Kategori</th>
                                    <th rowspan="3" class="sticky" style="background-color: #883c0c;">Total Skor</th>
                                    <th rowspan="3" class="sticky" style="background-color: #883c0c;">Skor</th>

                                </tr>
                                <tr>
                                    <th class="sticky-sub" colspan="6" style="background-color: #96be25; white-space: nowrap;">Brondolan Tinggal</th>
                                    <th class="sticky-sub" colspan="7" style="background-color: #96be25; white-space: nowrap;">Buah Tinggal</th>
                                    <th class="sticky-sub" colspan="3" style="background-color: #96be25; white-space: nowrap;">Pelepah Sengkleh</th>
                                    <th class="sticky-sub" rowspan="2" style="background-color: #96be25; white-space: nowrap;">Total Skor</th>
                                    <th class="sticky-sub" rowspan="2" style="background-color: #25a5be; white-space: nowrap;">TPH Sampel</th>
                                    <th class="sticky-sub" colspan="3" style="background-color: #25a5be; white-space: nowrap;">Brd Tinggal</th>
                                    <th class="sticky-sub" colspan="3" style="background-color: #25a5be; white-space: nowrap;">Buah Tinggal</th>
                                    <th class="sticky-sub" rowspan="2" style="background-color: #25a5be; white-space: nowrap;">Total Skor</th>
                                    <th class="sticky-sub" rowspan="2" style="background-color: #be4d25; white-space: nowrap;">TPH Sampel</th>
                                    <th class="sticky-sub" rowspan="2" style="background-color: #be4d25; white-space: nowrap;">Total Janjang Sampel</th>
                                    <th class="sticky-sub" colspan="3" style="background-color: #be4d25; white-space: nowrap;">Mentah (A)</th>
                                    <th class="sticky-sub" colspan="3" style="background-color: #be4d25; white-space: nowrap;">Matang (N)</th>
                                    <th class="sticky-sub" colspan="3" style="background-color: #be4d25; white-space: nowrap;">Lewat Matang (O)</th>
                                    <th class="sticky-sub" colspan="3" style="background-color: #be4d25; white-space: nowrap;">Janjang Kosong (E)</th>
                                    <th class="sticky-sub" colspan="3" style="background-color: #be4d25; white-space: nowrap;">Tidak Standar V-Cut</th>
                                    <th class="sticky-sub" colspan="2" style="background-color: #be4d25; white-space: nowrap;">Abnormal</th>
                                    <th class="sticky-sub" colspan="3" style="background-color: #be4d25; white-space: nowrap;">Penggunaan Karung Brondolan</th>
                                </tr>
                                <tr>
                                    <th class="sticky-second-row" style="background-color: #ffc404;">Jumlah Pokok Sampel</th>
                                    <th class="sticky-second-row" style="background-color: #ffc404;">Luas Ha Sampel</th>
                                    <th class="sticky-second-row" style="background-color: #ffc404;">Jumlah Jjg Panen</th>
                                    <th class="sticky-second-row" style="background-color: #ffc404;">AKP Realisasi</th>
                                    <th class="sticky-second-row" style="background-color: #96be25;">P</th>
                                    <th class="sticky-second-row" style="background-color: #96be25;">K</th>
                                    <th class="sticky-second-row" style="background-color: #96be25;">GL</th>
                                    <th class="sticky-second-row" style="background-color: #96be25;">Total Brd</th>
                                    <th class="sticky-second-row" style="background-color: #96be25;">Brd/JJG</th>
                                    <th class="sticky-second-row" style="background-color: #96be25;">Skor</th>
                                    <th class="sticky-second-row" style="background-color: #96be25;">S</th>
                                    <th class="sticky-second-row" style="background-color: #96be25;">M1</th>
                                    <th class="sticky-second-row" style="background-color: #96be25;">M2</th>
                                    <th class="sticky-second-row" style="background-color: #96be25;">M3</th>
                                    <th class="sticky-second-row" style="background-color: #96be25;">Total JJG</th>
                                    <th class="sticky-second-row" style="background-color: #96be25;">%</th>
                                    <th class="sticky-second-row" style="background-color: #96be25;">Skor</th>
                                    <th class="sticky-second-row" style="background-color: #96be25;">Pokok </th>
                                    <th class="sticky-second-row" style="background-color: #96be25;">%</th>
                                    <th class="sticky-second-row" style="background-color: #96be25;">Skor</th>

                                    <th class="sticky-second-row" style="background-color: #25a5be;">Butir</th>
                                    <th class="sticky-second-row" style="background-color: #25a5be;">Butir/TPH</th>
                                    <th class="sticky-second-row" style="background-color: #25a5be;">Skor</th>
                                    <th class="sticky-second-row" style="background-color: #25a5be;">Jjg</th>
                                    <th class="sticky-second-row" style="background-color: #25a5be;">Jjg/TPH</th>
                                    <th class="sticky-second-row" style="background-color: #25a5be;">Skor</th>
                                    {{-- table mutu Buah --}}
                                    <th class="sticky-second-row" style="background-color: #be4d25;">Jjg</th>
                                    <th class="sticky-second-row" style="background-color: #be4d25;">%</th>
                                    <th class="sticky-second-row" style="background-color: #be4d25;">Skor</th>

                                    <th class="sticky-second-row" style="background-color: #be4d25;">Jjg</th>
                                    <th class="sticky-second-row" style="background-color: #be4d25;">%</th>
                                    <th class="sticky-second-row" style="background-color: #be4d25;">Skor</th>

                                    <th class="sticky-second-row" style="background-color: #be4d25;">Jjg</th>
                                    <th class="sticky-second-row" style="background-color: #be4d25;">%</th>
                                    <th class="sticky-second-row" style="background-color: #be4d25;">Skor</th>

                                    <th class="sticky-second-row" style="background-color: #be4d25;">Jjg</th>
                                    <th class="sticky-second-row" style="background-color: #be4d25;">%</th>
                                    <th class="sticky-second-row" style="background-color: #be4d25;">Skor</th>

                                    <th class="sticky-second-row" style="background-color: #be4d25;">Jjg</th>
                                    <th class="sticky-second-row" style="background-color: #be4d25;">%</th>
                                    <th class="sticky-second-row" style="background-color: #be4d25;">Skor</th>

                                    <th class="sticky-second-row" style="background-color: #be4d25;">Jjg</th>
                                    <th class="sticky-second-row" style="background-color: #be4d25;">%</th>

                                    <th class="sticky-second-row" style="background-color: #be4d25;">Ya</th>
                                    <th class="sticky-second-row" style="background-color: #be4d25;">%</th>
                                    <th class="sticky-second-row" style="background-color: #be4d25;">Skor</th>
                                </tr>

                            </thead>
                            <tbody id="dataInspeksi">

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
                <div class="d-flex justify-content-end mr-3 mt-4">
                    <div class="margin g-2">
                        <div class="row align-items-center">
                            <div class="col-md">
                                {{csrf_field()}}
                                <select class="form-control" id="regDataMap">
                                    <option value="" disabled selected>Pilih REG</option>
                                    <option value="1,2,3">Region 1</option>
                                    <option value="4,5,6">Region 2</option>
                                    <option value="7,8">Region 3</option>
                                    <option value="10,11">Region 4</option>
                                </select>
                            </div>
                            <div class="col-md">
                                {{ csrf_field() }}
                                <select class="form-control" id="estDataMap" disabled>
                                    <option value="" disabled selected>Pilih EST</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-primary mb-2 ml-2" id="showEstMap">Show</button>
                                @if (can_edit_all_atasan())
                                <a class="btn btn-primary mb-2 ml-2" id="otherLink" href="{{ route('crudmatchblok') }}" target="_blank">
                                    <span>Ubah Data Blok</span>
                                </a>
                                @endif
                                <button class="btn btn-primary mb-2 ml-2" id="downloadimgmap">Download Image</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end mr-3 mt-4">
                    <div class="margin g-2">
                        <div class="row align-items-center">
                            <div class="col-md">
                                <select id="afdelingFilter" class="form-select">
                                    <option value="">Select Afdeling</option>
                                </select>

                            </div>
                            <div class="col-md">
                                <select id="blokFilter" class="form-select">
                                    <option value="">Select Blok</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ml-4 mr-4 mb-3">

                    <div class="row text-center mt-3">
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
                    </div>

                </div>
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








    <script type="module">
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


                // tahun1.style.display = "";
                // tahun2.style.display = "";
                // tahun3.style.display = "";
                // // tahun4.style.display = "none";
                // resetClassList(tahun4);

                // tahun1.classList.add("col-lg-4");
                // tahun2.classList.add("col-lg-4");
                // tahun3.classList.add("col-lg-4");

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



                // tahun1.style.display = "";
                // tahun2.style.display = "";
                // tahun3.style.display = "none";
                // // tahun4.style.display = "none";
                // resetClassList(tahun3);
                // resetClassList(tahun4);

                // tahun1.classList.add("col-lg-6");
                // tahun2.classList.add("col-lg-6");


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


                // tahun1.style.display = "";
                // tahun2.style.display = "";
                // tahun3.style.display = "";
                // // tahun4.style.display = "none";
                // resetClassList(tahun4);

                // tahun1.classList.add("col-lg-4");
                // tahun2.classList.add("col-lg-4");
                // tahun3.classList.add("col-lg-4");



            }




            isTableHeaderModified = true;

            changeData();
            getFindData();
            dataDashboard();
            dashboard_tahun();



            buttonimg.style.display = 'none';
        });






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
        $("#editnilaiestate").click(function() {
            Swal.fire({
                title: 'Loading',
                html: '<span class="loading-text">Mohon Tunggu...</span>',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });
            editnilaiqc()
        });

        let map = null;
        let layerGroup = null;
        const waktuini = @json($datenow);

        $('#showEstMap').click(function() {
            buttonimg.style.display = 'block';
            Swal.fire({
                title: 'Loading',
                html: '<span class="loading-text">Mohon Tunggu...</span>',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });
            getPlotBlok();
        });

        // var googleSat; // Define googleSat variable

        // function initializeMap() {
        //     var map = L.map('map', {
        //         preferCanvas: true, // Set preferCanvas to true
        //     }).setView([-2.2745234, 111.61404248], 13);

        //     googleSat = L.tileLayer('http://{s}.google.com/vt?lyrs=s&x={x}&y={y}&z={z}', {
        //         maxZoom: 20,
        //         subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
        //     }).addTo(map);

        //     // map.addControl(new L.Control.Fullscreen());

        //     return map;
        // }

        function initializeMap() {
            // Initialize the map
            map = L.map('map', {
                preferCanvas: true, // Set preferCanvas to true
            }).setView([-2.2745234, 111.61404248], 13);

            L.tileLayer('http://{s}.google.com/vt?lyrs=s&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
            }).addTo(map);
            layerGroup = L.layerGroup().addTo(map);
        }

        function getPlotBlok() {
            var _token = $('input[name="_token"]').val();
            var estData = $("#estDataMap").val();
            var regData = $("#regDataMap").val();
            var date = waktuini;

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
                    const blokResult = result['blok'];
                    const lgd = result['legend'];
                    const lowest = result['lowest'];
                    const highest = result['highest'];
                    console.log(lgd);
                    console.log(lowest);
                    console.log(highest);
                    // Remove old map instance if it exists
                    if (map) {
                        map.remove();
                    }

                    // Initialize a new map
                    initializeMap();

                    // Draw the new blocks
                    drawmapsblok(blokResult);

                    // Populate the filters
                    populateFilters(blokResult);

                    var legend = L.control({
                        position: "bottomleft"
                    });
                    legend.onAdd = function(map) {
                        var div = L.DomUtil.create("div", "legend");
                        div.innerHTML += '<table class="table table-bordered text-center" style="height:fit-content; font-size: 14px;"> <thead> <tr bgcolor="lightgrey"> <th rowspan="2" class="align-middle">Score</th><th colspan="2">Blok</th> </tr> <tr bgcolor="lightgrey"> <th>Jumlah</th> <th>%</th> </tr> </thead> <tbody><tr><td bgcolor="#4874c4">Excellent > 95</td><td>' + lgd['excellent'] + '</td><td>' + lgd['perExc'] + '</td></tr><tr><td bgcolor="#00ff2e">Good > 85</td><td>' + lgd['good'] + '</td><td>' + lgd['perGood'] + '</td></tr><tr><td bgcolor="yellow">Satisfactory > 75</td><td>' + lgd['satis'] + '</td><td>' + lgd['perSatis'] + '</td></tr><tr><td bgcolor="orange">Fair > 65</td><td>' + lgd['fair'] + '</td><td>' + lgd['perFair'] + '</td></tr><tr><td bgcolor="red">Poor < 65</td><td>' + lgd['poor'] + '</td><td>' + lgd['perPoor'] + '</td></tr><tr><td>Belum Sidak</td><td>' + lgd['excellent'] + '</td><td>' + lgd['excellent'] + '</td></tr><tr bgcolor="lightgrey"><td>TOTAL</td><td colspan="2">' + lgd['total'] + '</td></tr><tr bgcolor="lightgrey"><td>Highest</td><td colspan="2">' + highest['nilai'] + '</td></tr><tr bgcolor="lightgrey"><td>Lowest</td><td colspan="2">' + lowest['nilai'] + '</td></tr></tbody></table>';

                        return div;
                    };
                    legend.addTo(map);

                    // legendVar = legend;
                }
            });
        }

        function drawmapsblok(blok) {
            console.log('Drawing maps block...');
            layerGroup.clearLayers();

            // Create a LatLngBounds object to store the bounds of all polygons
            let bounds = L.latLngBounds([]);

            let hasValidData = false;

            for (let key in blok) {
                // Parse the latln value
                let latlnArray = blok[key].latln
                    .slice(1, -1) // Remove the outer square brackets
                    .split('],[') // Split by the coordinates
                    .map(coord => {
                        let [lat, lng] = coord.split(',').map(Number); // Split lat and lng, and convert to number
                        return [lng, lat]; // Leaflet uses [lng, lat]
                    });

                // Skip invalid data
                if (latlnArray.length < 3) continue;

                var nilai = blok[key].nilai;

                // Determine fill color based on nilai
                let fillColor;
                if (nilai >= 95.0 && nilai <= 100.0) {
                    fillColor = '#4874c4'; // Blue
                } else if (nilai >= 85.0 && nilai < 95.0) {
                    fillColor = '#00ff2e'; // Green
                } else if (nilai >= 75.0 && nilai < 85.0) {
                    fillColor = 'yellow'; // Yellow
                } else if (nilai >= 65.0 && nilai < 75.0) {
                    fillColor = 'orange'; // Orange
                } else if (nilai == 0) {
                    fillColor = 'white'; // White
                } else if (nilai < 65.0) {
                    fillColor = 'red'; // Red
                }

                // Ensure the polygon is closed
                if (latlnArray.length > 0 && !latlnArray[0].every((val, index) => val === latlnArray[latlnArray.length - 1][index])) {
                    latlnArray.push(latlnArray[0]);
                }

                // Draw the polygon
                const polygon = L.polygon(latlnArray, {
                    color: 'white', // Outline color
                    fillColor: fillColor, // Background color based on nilai
                    fillOpacity: 0.6 // Set opacity for the fill color
                }).addTo(layerGroup).bindPopup(`
            <div>
                <strong>Blok Sidak:</strong> ${blok[key].blok_asli}<br>
                <strong>Blok Database:</strong> ${blok[key].blok}<br>
                <strong>Afdeling:</strong> ${blok[key].afdeling}<br>
                <strong>Nilai:</strong> ${blok[key].nilai}<br>
                <strong>Kategori:</strong> ${blok[key].kategori}
            </div>
        `);

                // Calculate the center of the polygon
                let latlngs = polygon.getLatLngs()[0];
                let latSum = 0,
                    lngSum = 0,
                    len = latlngs.length;

                for (let i = 0; i < len; i++) {
                    latSum += latlngs[i].lat;
                    lngSum += latlngs[i].lng;
                }

                let centerLat = latSum / len;
                let centerLng = lngSum / len;

                // Add a label in the middle
                L.marker([centerLat, centerLng], {
                    icon: L.divIcon({
                        className: 'label-estate',
                        html: `<div>
                   <p style="color: white; display: inline;">${blok[key].blok}</p>
                   <p style="color: black; display: inline;">(${blok[key].nilai})</p>
               </div>`,
                        iconSize: [100, 20]
                    })
                }).addTo(layerGroup);


                // Extend bounds to include the current polygon
                bounds.extend(polygon.getBounds());

                hasValidData = true;
            }

            // Fit bounds to the accumulated bounds only if there is valid data
            if (hasValidData) {
                map.fitBounds(bounds);
            }
        }

        function populateFilters(blok) {
            const afdelingMap = new Map(); // Map to store afdeling and associated blok

            // Populate afdeling and blokMap
            for (let key in blok) {
                let afdeling = blok[key].afdeling;
                let blokValue = blok[key].blok;

                if (!afdelingMap.has(afdeling)) {
                    afdelingMap.set(afdeling, []);
                }
                afdelingMap.get(afdeling).push(blokValue);
            }

            const afdelingFilter = $('#afdelingFilter');
            const blokFilter = $('#blokFilter');

            afdelingFilter.empty().append('<option value="">Select Afdeling</option>');
            blokFilter.empty().append('<option value="">Select Blok</option>').prop('disabled', true);

            afdelingMap.forEach((bloks, afdeling) => {
                afdelingFilter.append(`<option value="${afdeling}">${afdeling}</option>`);
            });

            afdelingFilter.on('change', function() {
                const selectedAfdeling = $(this).val();

                // Enable and update the blokFilter based on selected afdeling
                if (selectedAfdeling) {
                    const bloks = afdelingMap.get(selectedAfdeling) || [];
                    blokFilter.empty().append('<option value="">Select Blok</option>').prop('disabled', false);
                    bloks.forEach(blok => {
                        blokFilter.append(`<option value="${blok}">${blok}</option>`);
                    });
                } else {
                    blokFilter.empty().append('<option value="">Select Blok</option>').prop('disabled', true);
                }

                // Draw maps based on selected filters
                const filteredBlok = Object.keys(blok).reduce((acc, key) => {
                    if (!selectedAfdeling || blok[key].afdeling === selectedAfdeling) {
                        acc[key] = blok[key];
                    }
                    return acc;
                }, {});
                drawmapsblok(filteredBlok);
            });

            blokFilter.on('change', function() {
                const selectedBlok = $(this).val();
                const selectedAfdeling = afdelingFilter.val();
                const filteredBlok = Object.keys(blok).reduce((acc, key) => {
                    if ((blok[key].blok === selectedBlok || !selectedBlok) &&
                        (blok[key].afdeling === selectedAfdeling || !selectedAfdeling)) {
                        acc[key] = blok[key];
                    }
                    return acc;
                }, {});
                drawmapsblok(filteredBlok);
            });
        }

        // Initialize map when the document is ready
        initializeMap();


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
                            let pdfButton = '<a href="/cetakPDFFI/' + i + '/' + item1 + '/' + date + '" class="btn btn-primary" target="_blank"><i class="bi bi-filetype-pdf"></i></a>';
                            let excelButton = '<a href="/exportExcel/' + i + '/' + item1 + '/' + date + '" class="btn btn-success" target="_blank"><i class="bi bi-file-earmark-excel"></i></a>';

                            if (i <= visit) {
                                itemElement.innerHTML = pdfButton + ' ' + excelButton;
                            } else {
                                pdfButton = '<a href="#" class="btn btn-secondary" disabled><i class="bi bi-filetype-pdf"></i></a>';
                                excelButton = '<a href="#" class="btn btn-secondary" disabled><i class="bi bi-file-earmark-excel"></i></a>';
                                itemElement.innerHTML = pdfButton + ' ' + excelButton;
                            }
                            tr.appendChild(itemElement);
                        }


                        // Check if visit is more than 1, if so, create a new button with ID 4
                        let itemElement7 = document.createElement('td');
                        let pdfButton = '<a href="/cetakPDFFI/4/' + item1 + '/' + date + '" class="btn btn-primary" target="_blank" id="4"><i class="bi bi-filetype-pdf"></i></a>';
                        let excelButton = '<a href="/exportExcel/4/' + item1 + '/' + date + '" class="btn btn-success" target="_blank" id="4"><i class="bi bi-file-earmark-excel"></i></a>';

                        if (visit > 1) {
                            itemElement7.innerHTML = pdfButton + ' ' + excelButton;
                        } else {
                            pdfButton = '<a href="#" class="btn btn-secondary" disabled><i class="bi bi-filetype-pdf"></i></a>';
                            excelButton = '<a href="#" class="btn btn-secondary" disabled><i class="bi bi-file-earmark-excel"></i></a>';
                            itemElement7.innerHTML = pdfButton + ' ' + excelButton;
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
        // var weekly_transBRD = new ApexCharts(document.querySelector("#weekly_transBRD"), options);
        // weekly_transBRD.render();
        // var weekly_transBuah = new ApexCharts(document.querySelector("#weekly_transBuah"), options);
        // weekly_transBuah.render();

        // var weekly_transBRDWIL = new ApexCharts(document.querySelector("#weekly_transBRDWIL"), will);
        // weekly_transBRDWIL.render();


        // var weekly_transBuahWIL = new ApexCharts(document.querySelector("#weekly_transBuahWIL"), will);
        // weekly_transBuahWIL.render();

        // mutu buah weekly
        // var weekly_mtb_mentah = new ApexCharts(document.querySelector("#weekly_mtb_mentah"), options);
        // weekly_mtb_mentah.render();
        // var weekly_mtb_masak = new ApexCharts(document.querySelector("#weekly_mtb_masak"), options);
        // weekly_mtb_masak.render();
        // var weekly_mtb_over = new ApexCharts(document.querySelector("#weekly_mtb_over"), options);
        // weekly_mtb_over.render();
        // var weekly_mtb_abnormal = new ApexCharts(document.querySelector("#weekly_mtb_abnormal"), options);
        // weekly_mtb_abnormal.render();
        // var weekly_mtb_kosong = new ApexCharts(document.querySelector("#weekly_mtb_kosong"), options);
        // weekly_mtb_kosong.render();
        // var weekly_mtb_vcut = new ApexCharts(document.querySelector("#weekly_mtb_vcut"), options);
        // weekly_mtb_vcut.render();

        // var weekly_mtbwil_mentah = new ApexCharts(document.querySelector("#weekly_mtbwil_mentah"), will);
        // weekly_mtbwil_mentah.render();
        // var weekly_mtbwil_masak = new ApexCharts(document.querySelector("#weekly_mtbwil_masak"), will);
        // weekly_mtbwil_masak.render();
        // var weekly_mtbwil_over = new ApexCharts(document.querySelector("#weekly_mtbwil_over"), will);
        // weekly_mtbwil_over.render();
        // var weekly_mtbwil_abnormal = new ApexCharts(document.querySelector("#weekly_mtbwil_abnormal"), will);
        // weekly_mtbwil_abnormal.render();
        // var weekly_mtbwil_kosong = new ApexCharts(document.querySelector("#weekly_mtbwil_kosong"), will);
        // weekly_mtbwil_kosong.render();
        // var weekly_mtbwil_vcut = new ApexCharts(document.querySelector("#weekly_mtbwil_vcut"), will);
        // weekly_mtbwil_vcut.render();


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

                    var rekapafd = result['rekap_per_afdeling']
                    var rekap_per_estate = result['rekap_per_estate']
                    var rekap_per_wil = result['rekap_per_wil']
                    var rekap_per_reg = result['rekap_per_reg']
                    var chart_for_estate = result['chart_for_estate']
                    var chart_for_wilayah = result['chart_for_wilayah']
                    // var datachart = result['datachart']

                    // console.log(rekapafd);
                    // untuk perbadeling 
                    let table1 = rekapafd[1] ?? rekapafd[4] ?? rekapafd[7] ?? rekapafd[10]
                    let table2 = rekapafd[2] ?? rekapafd[5] ?? rekapafd[8] ?? rekapafd[11]
                    let table3 = rekapafd[3] ?? rekapafd[6] ?? []
                    let tbody1 = document.getElementById('tbody1');
                    let tbody2 = document.getElementById('tbody2');
                    let tbody3 = document.getElementById('tbody3');
                    populateTableWithRanks(table1, tbody1);
                    populateTableWithRanks(table2, tbody2);
                    populateTableWithRanks(table3, tbody3);
                    //untuk perestate
                    let table1_est = rekap_per_estate[1] ?? rekap_per_estate[4] ?? rekap_per_estate[7] ?? rekap_per_estate[10]
                    let table2_est = rekap_per_estate[2] ?? rekap_per_estate[5] ?? rekap_per_estate[8] ?? rekap_per_estate[11]
                    let table3_est = rekap_per_estate[3] ?? rekap_per_estate[6] ?? []
                    populateTableWithRanks(table1_est, tbody1);
                    populateTableWithRanks(table2_est, tbody2);
                    populateTableWithRanks(table3_est, tbody3);
                    // untuk perwill 
                    let table1_wil = rekap_per_wil[1] ?? rekap_per_wil[4] ?? rekap_per_wil[7] ?? rekap_per_wil[10]
                    let table2_wil = rekap_per_wil[2] ?? rekap_per_wil[5] ?? rekap_per_wil[8] ?? rekap_per_wil[11]
                    let table3_wil = rekap_per_wil[3] ?? rekap_per_wil[6] ?? []
                    let theadreg = document.getElementById('theadreg');
                    // console.log(table2_wil);
                    // MUTU ANCAK 
                    chartGrain.updateOptions({
                        xaxis: {
                            categories: Object.keys(chart_for_estate) || []
                        },
                    })
                    chartGrain.updateSeries([{
                        name: 'Brondoln Tinggal',
                        data: Object.values(chart_for_estate).map(estate => estate.total_brdcak) || []
                    }])

                    chartFruit.updateSeries([{
                        name: 'Buah Tinggal',
                        data: Object.values(chart_for_estate).map(estate => estate.buah_jjgcak) || []
                    }])
                    chartFruit.updateOptions({
                        xaxis: {
                            categories: Object.keys(chart_for_estate) || []
                        },
                    })

                    chartGrainWil.updateOptions({
                        xaxis: {
                            categories: Object.keys(chart_for_wilayah) || []
                        },
                    })
                    chartGrainWil.updateSeries([{
                        name: 'Brondoln Tinggal',
                        data: Object.values(chart_for_wilayah).map(estate => estate.total_brdcak) || []
                    }])

                    chartFruitWil.updateSeries([{
                        name: 'Buah Tinggal',
                        data: Object.values(chart_for_wilayah).map(estate => estate.buah_jjgcak) || []
                    }])
                    chartFruitWil.updateOptions({
                        xaxis: {
                            categories: Object.keys(chart_for_wilayah) || []
                        },
                    })

                    // MUTU BUAH 
                    mtb_mentah.updateSeries([{
                        name: 'JANJANG MENTAH',
                        data: Object.values(chart_for_estate).map(estate => estate.total_mentahbh) || []
                    }])
                    mtb_mentah.updateOptions({
                        xaxis: {
                            categories: Object.keys(chart_for_estate) || []
                        },
                    })

                    mtb_masak.updateSeries([{
                        name: 'JANJANG MASAK',
                        data: Object.values(chart_for_estate).map(estate => estate.total_masakbh) || []
                    }])
                    mtb_masak.updateOptions({
                        xaxis: {
                            categories: Object.keys(chart_for_estate) || []
                        },
                    })


                    mtb_over.updateSeries([{
                        name: 'JANJANG OVER',
                        data: Object.values(chart_for_estate).map(estate => estate.total_overbh) || []
                    }])
                    mtb_over.updateOptions({
                        xaxis: {
                            categories: Object.keys(chart_for_estate) || []
                        },
                    })


                    mtb_abnr.updateSeries([{
                        name: 'JANJANG ABNORMAL',
                        data: Object.values(chart_for_estate).map(estate => estate.total_abnormalbh) || []
                    }])
                    mtb_abnr.updateOptions({
                        xaxis: {
                            categories: Object.keys(chart_for_estate) || []
                        },
                    })


                    mtb_kosong.updateSeries([{
                        name: 'JANJANG KOSONG',
                        data: Object.values(chart_for_estate).map(estate => estate.total_jjgKosongbh) || []
                    }])
                    mtb_kosong.updateOptions({
                        xaxis: {
                            categories: Object.keys(chart_for_estate) || []
                        },
                    })


                    mtb_vcuts.updateSeries([{
                        name: 'VCUT',
                        data: Object.values(chart_for_estate).map(estate => estate.total_vcutbh) || []
                    }])
                    mtb_vcuts.updateOptions({
                        xaxis: {
                            categories: Object.keys(chart_for_estate) || []
                        },
                    })

                    mtb_mentahwil.updateSeries([{
                        name: 'JANJANG MENTAH',
                        data: Object.values(chart_for_wilayah).map(estate => estate.total_mentahbh) || []
                    }])
                    mtb_mentahwil.updateOptions({
                        xaxis: {
                            categories: Object.keys(chart_for_wilayah) || []
                        },
                    })

                    mtb_masakwil.updateSeries([{
                        name: 'JANJANG MASAK',
                        data: Object.values(chart_for_wilayah).map(estate => estate.total_masakbh) || []
                    }])
                    mtb_masakwil.updateOptions({
                        xaxis: {
                            categories: Object.keys(chart_for_wilayah) || []
                        },
                    })


                    mtb_overwil.updateSeries([{
                        name: 'JANJANG OVER',
                        data: Object.values(chart_for_wilayah).map(estate => estate.total_overbh) || []
                    }])
                    mtb_overwil.updateOptions({
                        xaxis: {
                            categories: Object.keys(chart_for_wilayah) || []
                        },
                    })


                    mtb_abnrwil.updateSeries([{
                        name: 'JANJANG ABNORMAL',
                        data: Object.values(chart_for_wilayah).map(estate => estate.total_abnormalbh) || []
                    }])
                    mtb_abnrwil.updateOptions({
                        xaxis: {
                            categories: Object.keys(chart_for_wilayah) || []
                        },
                    })


                    mtb_kosongwil.updateSeries([{
                        name: 'JANJANG KOSONG',
                        data: Object.values(chart_for_wilayah).map(estate => estate.total_jjgKosongbh) || []
                    }])
                    mtb_kosongwil.updateOptions({
                        xaxis: {
                            categories: Object.keys(chart_for_wilayah) || []
                        },
                    })


                    mtb_vcutswil.updateSeries([{
                        name: 'VCUT',
                        data: Object.values(chart_for_wilayah).map(estate => estate.total_vcutbh) || []
                    }])
                    mtb_vcutswil.updateOptions({
                        xaxis: {
                            categories: Object.keys(chart_for_wilayah) || []
                        },
                    })


                    // MUTU TRANSPORT 

                    transprot_brd.updateSeries([{
                        name: 'BRD DI TPH',
                        data: Object.values(chart_for_estate).map(estate => estate.total_brdtrans) || []
                    }])
                    transprot_brd.updateOptions({
                        xaxis: {
                            categories: Object.keys(chart_for_estate) || []
                        },
                    })


                    transport_buah.updateSeries([{
                        name: 'BUAH DI TPH',
                        data: Object.values(chart_for_estate).map(estate => estate.total_buahtrans) || []
                    }])
                    transport_buah.updateOptions({
                        xaxis: {
                            categories: Object.keys(chart_for_estate) || []
                        },
                    })


                    transportwil_brd.updateSeries([{
                        name: 'BRD DI TPH',
                        data: Object.values(chart_for_wilayah).map(estate => estate.total_brdtrans) || []
                    }])
                    transportwil_brd.updateOptions({
                        xaxis: {
                            categories: Object.keys(chart_for_wilayah) || []
                        },
                    })


                    transportwil_buah.updateSeries([{
                        name: 'BUAH DI TPH',
                        data: Object.values(chart_for_wilayah).map(estate => estate.total_buahtrans) || []
                    }])
                    transportwil_buah.updateOptions({
                        xaxis: {
                            categories: Object.keys(chart_for_wilayah) || []
                        },
                    })


                    if (rekap_per_reg) {
                        TableForWilReg(rekap_per_reg, theadreg);
                    }

                    if (table2_wil) {
                        TableForWilReg(table2_wil, tbody2);
                    }

                    if (table3_wil) {
                        TableForWilReg(table3_wil, tbody3);
                    }

                    if (table1_wil) {
                        TableForWilReg(table1_wil, tbody1);
                    }


                }
            });
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

                    var resultreg = Object.entries(parseResult['resultreg'])
                    var resultwil = Object.entries(parseResult['resultwil'])
                    var resultestate = Object.entries(parseResult['resultestate'])
                    var resultafdeling = Object.entries(parseResult['resultafdeling'])
                    // console.log(resultafdeling);
                    var trekap2 = document.getElementById('tb_tahun');
                    var trekapwil = document.getElementById('tablewil');
                    var trekapafd = document.getElementById('rekapAFD');

                    function isDataKosong(estate) {
                        return estate.ancak.check_datacak === 'kosong' &&
                            estate.buah.check_databh === 'kosong' &&
                            estate.trans.check_datatrans === 'kosong';
                    }

                    function calculateTotalScore(estate) {
                        return estate.ancak.skor_akhircak +
                            estate.buah.TOTAL_SKORbh +
                            estate.trans.totalSkortrans;
                    }
                    let inc = 1;
                    resultestate.forEach(item => {
                        let item1 = inc++;
                        let item2 = item[0];
                        let item3 = '-';
                        let item4 = '-';
                        let estate5 = item[1].January.estate;
                        let item5 = isDataKosong(estate5) ? '-' : calculateTotalScore(estate5);
                        let estate6 = item[1].February.estate;
                        let item6 = isDataKosong(estate6) ? '-' : calculateTotalScore(estate6);
                        let estate7 = item[1].March.estate;
                        let item7 = isDataKosong(estate7) ? '-' : calculateTotalScore(estate7);
                        let estate8 = item[1].April.estate;
                        let item8 = isDataKosong(estate8) ? '-' : calculateTotalScore(estate8);
                        let estate9 = item[1].May.estate;
                        let item9 = isDataKosong(estate9) ? '-' : calculateTotalScore(estate9);
                        let estate10 = item[1].June.estate;
                        let item10 = isDataKosong(estate10) ? '-' : calculateTotalScore(estate10);
                        let estate11 = item[1].July.estate;
                        let item11 = isDataKosong(estate11) ? '-' : calculateTotalScore(estate11);
                        let estate12 = item[1].August.estate;
                        let item12 = isDataKosong(estate12) ? '-' : calculateTotalScore(estate12);
                        let estate13 = item[1].September.estate;
                        let item13 = isDataKosong(estate13) ? '-' : calculateTotalScore(estate13);
                        let estate14 = item[1].October.estate;
                        let item14 = isDataKosong(estate14) ? '-' : calculateTotalScore(estate14);
                        let estate15 = item[1].November.estate;
                        let item15 = isDataKosong(estate15) ? '-' : calculateTotalScore(estate15);
                        let estate16 = item[1].December.estate;
                        let item16 = isDataKosong(estate16) ? '-' : calculateTotalScore(estate16);

                        // Create table row and cell for each 'key'
                        let tr = document.createElement('tr');
                        let itemElement1 = document.createElement('td');
                        let itemElement2 = document.createElement('td');
                        let itemElement3 = document.createElement('td');
                        let itemElement4 = document.createElement('td');
                        let itemElement5 = document.createElement('td');
                        let itemElement6 = document.createElement('td');
                        let itemElement7 = document.createElement('td');
                        let itemElement8 = document.createElement('td');
                        let itemElement9 = document.createElement('td');
                        let itemElement10 = document.createElement('td');
                        let itemElement11 = document.createElement('td');
                        let itemElement12 = document.createElement('td');
                        let itemElement13 = document.createElement('td');
                        let itemElement14 = document.createElement('td');
                        let itemElement15 = document.createElement('td');
                        let itemElement16 = document.createElement('td');



                        itemElement1.classList.add("text-center");
                        itemElement1.innerText = item1
                        itemElement2.innerText = item2
                        itemElement3.innerText = item3
                        itemElement4.innerText = item4
                        itemElement5.innerText = item5
                        itemElement6.innerText = item6
                        itemElement7.innerText = item7
                        itemElement8.innerText = item8
                        itemElement9.innerText = item9
                        itemElement10.innerText = item10
                        itemElement11.innerText = item11
                        itemElement12.innerText = item12
                        itemElement13.innerText = item13
                        itemElement14.innerText = item14
                        itemElement15.innerText = item15
                        itemElement16.innerText = item16


                        setBackgroundColor(itemElement5, item5);
                        setBackgroundColor(itemElement6, item6);
                        setBackgroundColor(itemElement7, item7);
                        setBackgroundColor(itemElement8, item8);
                        setBackgroundColor(itemElement9, item9);
                        setBackgroundColor(itemElement10, item10);
                        setBackgroundColor(itemElement11, item11);
                        setBackgroundColor(itemElement12, item12);
                        setBackgroundColor(itemElement13, item13);
                        setBackgroundColor(itemElement14, item14);
                        setBackgroundColor(itemElement15, item15);
                        setBackgroundColor(itemElement16, item16);

                        tr.appendChild(itemElement1)
                        tr.appendChild(itemElement2)
                        tr.appendChild(itemElement3)
                        tr.appendChild(itemElement4)
                        tr.appendChild(itemElement5)
                        tr.appendChild(itemElement6)
                        tr.appendChild(itemElement7)
                        tr.appendChild(itemElement8)
                        tr.appendChild(itemElement9)
                        tr.appendChild(itemElement10)
                        tr.appendChild(itemElement11)
                        tr.appendChild(itemElement12)
                        tr.appendChild(itemElement13)
                        tr.appendChild(itemElement14)
                        tr.appendChild(itemElement15)
                        tr.appendChild(itemElement16)
                        // Append the row to the table
                        trekap2.appendChild(tr);
                    });
                    let incs = 1;

                    function isDataKosongwil(wil) {
                        return wil.ancak.check_datacak === 'kosong' &&
                            wil.buah.check_databh === 'kosong' &&
                            wil.trans.check_datatrans === 'kosong';
                    }

                    function calculateTotalScorewil(wil) {
                        return wil.ancak.skor_akhircak +
                            wil.buah.TOTAL_SKORbh +
                            wil.trans.totalSkortrans;
                    }
                    resultwil.forEach(item => {
                        let item1 = incs++;
                        let item2 = 'Wil-' + ' ' + item[0];
                        let item3 = '-';
                        let item4 = '-';
                        let wil5 = item[1].January.wil;
                        let item5 = isDataKosongwil(wil5) ? '-' : calculateTotalScorewil(wil5);
                        let wil6 = item[1].February.wil;
                        let item6 = isDataKosongwil(wil6) ? '-' : calculateTotalScorewil(wil6);
                        let wil7 = item[1].March.wil;
                        let item7 = isDataKosongwil(wil7) ? '-' : calculateTotalScorewil(wil7);
                        let wil8 = item[1].April.wil;
                        let item8 = isDataKosongwil(wil8) ? '-' : calculateTotalScorewil(wil8);
                        let wil9 = item[1].May.wil;
                        let item9 = isDataKosongwil(wil9) ? '-' : calculateTotalScorewil(wil9);
                        let wil10 = item[1].June.wil;
                        let item10 = isDataKosongwil(wil10) ? '-' : calculateTotalScorewil(wil10);
                        let wil11 = item[1].July.wil;
                        let item11 = isDataKosongwil(wil11) ? '-' : calculateTotalScorewil(wil11);
                        let wil12 = item[1].August.wil;
                        let item12 = isDataKosongwil(wil12) ? '-' : calculateTotalScorewil(wil12);
                        let wil13 = item[1].September.wil;
                        let item13 = isDataKosongwil(wil13) ? '-' : calculateTotalScorewil(wil13);
                        let wil14 = item[1].October.wil;
                        let item14 = isDataKosongwil(wil14) ? '-' : calculateTotalScorewil(wil14);
                        let wil15 = item[1].November.wil;
                        let item15 = isDataKosongwil(wil15) ? '-' : calculateTotalScorewil(wil15);
                        let wil16 = item[1].December.wil;
                        let item16 = isDataKosongwil(wil16) ? '-' : calculateTotalScorewil(wil16);

                        // Create table row and cell for each 'key'
                        let tr = document.createElement('tr');
                        let itemElement1 = document.createElement('td');
                        let itemElement2 = document.createElement('td');
                        let itemElement3 = document.createElement('td');
                        let itemElement4 = document.createElement('td');
                        let itemElement5 = document.createElement('td');
                        let itemElement6 = document.createElement('td');
                        let itemElement7 = document.createElement('td');
                        let itemElement8 = document.createElement('td');
                        let itemElement9 = document.createElement('td');
                        let itemElement10 = document.createElement('td');
                        let itemElement11 = document.createElement('td');
                        let itemElement12 = document.createElement('td');
                        let itemElement13 = document.createElement('td');
                        let itemElement14 = document.createElement('td');
                        let itemElement15 = document.createElement('td');
                        let itemElement16 = document.createElement('td');



                        itemElement1.classList.add("text-center");
                        itemElement1.innerText = item1
                        itemElement2.innerText = item2
                        itemElement3.innerText = item3
                        itemElement4.innerText = item4
                        itemElement5.innerText = item5
                        itemElement6.innerText = item6
                        itemElement7.innerText = item7
                        itemElement8.innerText = item8
                        itemElement9.innerText = item9
                        itemElement10.innerText = item10
                        itemElement11.innerText = item11
                        itemElement12.innerText = item12
                        itemElement13.innerText = item13
                        itemElement14.innerText = item14
                        itemElement15.innerText = item15
                        itemElement16.innerText = item16

                        setBackgroundColor(itemElement5, item5);
                        setBackgroundColor(itemElement6, item6);
                        setBackgroundColor(itemElement7, item7);
                        setBackgroundColor(itemElement8, item8);
                        setBackgroundColor(itemElement9, item9);
                        setBackgroundColor(itemElement10, item10);
                        setBackgroundColor(itemElement11, item11);
                        setBackgroundColor(itemElement12, item12);
                        setBackgroundColor(itemElement13, item13);
                        setBackgroundColor(itemElement14, item14);
                        setBackgroundColor(itemElement15, item15);
                        setBackgroundColor(itemElement16, item16);


                        tr.appendChild(itemElement1)
                        tr.appendChild(itemElement2)
                        tr.appendChild(itemElement3)
                        tr.appendChild(itemElement4)
                        tr.appendChild(itemElement5)
                        tr.appendChild(itemElement6)
                        tr.appendChild(itemElement7)
                        tr.appendChild(itemElement8)
                        tr.appendChild(itemElement9)
                        tr.appendChild(itemElement10)
                        tr.appendChild(itemElement11)
                        tr.appendChild(itemElement12)
                        tr.appendChild(itemElement13)
                        tr.appendChild(itemElement14)
                        tr.appendChild(itemElement15)
                        tr.appendChild(itemElement16)
                        // Append the row to the table
                        trekapwil.appendChild(tr);
                    });
                    let incx = 1;

                    function isDataKosongafd(afdeling) {
                        return afdeling.check_datacak === 'kosong' &&
                            afdeling.check_databh === 'kosong' &&
                            afdeling.check_datatrans === 'kosong';
                    }

                    function calculateTotalScoreafd(afdeling) {
                        return afdeling.skor_akhircak +
                            afdeling.TOTAL_SKORbh +
                            afdeling.totalSkortrans;
                    }
                    resultafdeling.forEach((estate) => {
                        let estateName = estate[0];
                        let afdelings = estate[1];

                        // Loop through each afdeling in the estate
                        Object.keys(afdelings).forEach((afdelingKey) => {
                            let afdeling = afdelings[afdelingKey];

                            let item1 = incx++;
                            let item2 = estateName;
                            let item3 = afdelingKey;
                            let item4 = '-';

                            let wil5 = afdeling.January;
                            let item5 = isDataKosongafd(wil5) ? '-' : calculateTotalScoreafd(wil5);
                            let wil6 = afdeling.February;
                            let item6 = isDataKosongafd(wil6) ? '-' : calculateTotalScoreafd(wil6);
                            let wil7 = afdeling.March;
                            let item7 = isDataKosongafd(wil7) ? '-' : calculateTotalScoreafd(wil7);
                            let wil8 = afdeling.April;
                            let item8 = isDataKosongafd(wil8) ? '-' : calculateTotalScoreafd(wil8);
                            let wil9 = afdeling.May;
                            let item9 = isDataKosongafd(wil9) ? '-' : calculateTotalScoreafd(wil9);
                            let wil10 = afdeling.June;
                            let item10 = isDataKosongafd(wil10) ? '-' : calculateTotalScoreafd(wil10);
                            let wil11 = afdeling.July;
                            let item11 = isDataKosongafd(wil11) ? '-' : calculateTotalScoreafd(wil11);
                            let wil12 = afdeling.August;
                            let item12 = isDataKosongafd(wil12) ? '-' : calculateTotalScoreafd(wil12);
                            let wil13 = afdeling.September;
                            let item13 = isDataKosongafd(wil13) ? '-' : calculateTotalScoreafd(wil13);
                            let wil14 = afdeling.October;
                            let item14 = isDataKosongafd(wil14) ? '-' : calculateTotalScoreafd(wil14);
                            let wil15 = afdeling.November;
                            let item15 = isDataKosongafd(wil15) ? '-' : calculateTotalScoreafd(wil15);
                            let wil16 = afdeling.December;
                            let item16 = isDataKosongafd(wil16) ? '-' : calculateTotalScoreafd(wil16);

                            // Create table row and cell for each 'key'
                            let tr = document.createElement('tr');
                            let itemElement1 = document.createElement('td');
                            let itemElement2 = document.createElement('td');
                            let itemElement3 = document.createElement('td');
                            let itemElement4 = document.createElement('td');
                            let itemElement5 = document.createElement('td');
                            let itemElement6 = document.createElement('td');
                            let itemElement7 = document.createElement('td');
                            let itemElement8 = document.createElement('td');
                            let itemElement9 = document.createElement('td');
                            let itemElement10 = document.createElement('td');
                            let itemElement11 = document.createElement('td');
                            let itemElement12 = document.createElement('td');
                            let itemElement13 = document.createElement('td');
                            let itemElement14 = document.createElement('td');
                            let itemElement15 = document.createElement('td');
                            let itemElement16 = document.createElement('td');

                            itemElement1.classList.add("text-center");
                            itemElement1.innerText = item1;
                            itemElement2.innerText = item2;
                            itemElement3.innerText = item3;
                            itemElement4.innerText = item4;
                            itemElement5.innerText = item5;
                            itemElement6.innerText = item6;
                            itemElement7.innerText = item7;
                            itemElement8.innerText = item8;
                            itemElement9.innerText = item9;
                            itemElement10.innerText = item10;
                            itemElement11.innerText = item11;
                            itemElement12.innerText = item12;
                            itemElement13.innerText = item13;
                            itemElement14.innerText = item14;
                            itemElement15.innerText = item15;
                            itemElement16.innerText = item16;

                            setBackgroundColor(itemElement5, item5);
                            setBackgroundColor(itemElement6, item6);
                            setBackgroundColor(itemElement7, item7);
                            setBackgroundColor(itemElement8, item8);
                            setBackgroundColor(itemElement9, item9);
                            setBackgroundColor(itemElement10, item10);
                            setBackgroundColor(itemElement11, item11);
                            setBackgroundColor(itemElement12, item12);
                            setBackgroundColor(itemElement13, item13);
                            setBackgroundColor(itemElement14, item14);
                            setBackgroundColor(itemElement15, item15);
                            setBackgroundColor(itemElement16, item16);

                            tr.appendChild(itemElement1);
                            tr.appendChild(itemElement2);
                            tr.appendChild(itemElement3);
                            tr.appendChild(itemElement4);
                            tr.appendChild(itemElement5);
                            tr.appendChild(itemElement6);
                            tr.appendChild(itemElement7);
                            tr.appendChild(itemElement8);
                            tr.appendChild(itemElement9);
                            tr.appendChild(itemElement10);
                            tr.appendChild(itemElement11);
                            tr.appendChild(itemElement12);
                            tr.appendChild(itemElement13);
                            tr.appendChild(itemElement14);
                            tr.appendChild(itemElement15);
                            tr.appendChild(itemElement16);

                            trekapafd.appendChild(tr);
                        });
                    });

                    function isDataKosongreg(reg) {
                        return reg.ancak.check_datacak === 'kosong' &&
                            reg.buah.check_databh === 'kosong' &&
                            reg.trans.check_datatrans === 'kosong';
                    }

                    function calculateTotalScorereg(reg) {
                        return reg.ancak.skor_akhircak +
                            reg.buah.TOTAL_SKORbh +
                            reg.trans.totalSkortrans;
                    }
                    // console.log(resultreg);
                    var theadreg = document.getElementById('reg');
                    let item1 = '='
                    let item2 = 'RH'
                    let item3 = '-'
                    let item4 = '-'
                    let wil5 = resultreg[0][1];
                    let item5 = isDataKosongreg(wil5) ? '-' : calculateTotalScorereg(wil5);
                    let wil6 = resultreg[1][1]
                    let item6 = isDataKosongreg(wil6) ? '-' : calculateTotalScorereg(wil6);
                    let wil7 = resultreg[2][1]
                    let item7 = isDataKosongreg(wil7) ? '-' : calculateTotalScorereg(wil7);
                    let wil8 = resultreg[3][1]
                    let item8 = isDataKosongreg(wil8) ? '-' : calculateTotalScorereg(wil8);
                    let wil9 = resultreg[4][1]
                    let item9 = isDataKosongreg(wil9) ? '-' : calculateTotalScorereg(wil9);
                    let wil10 = resultreg[5][1]
                    let item10 = isDataKosongreg(wil10) ? '-' : calculateTotalScorereg(wil10);
                    let wil11 = resultreg[6][1]
                    let item11 = isDataKosongreg(wil11) ? '-' : calculateTotalScorereg(wil11);
                    let wil12 = resultreg[7][1]
                    let item12 = isDataKosongreg(wil12) ? '-' : calculateTotalScorereg(wil12);
                    let wil13 = resultreg[8][1]
                    let item13 = isDataKosongreg(wil13) ? '-' : calculateTotalScorereg(wil13);
                    let wil14 = resultreg[9][1]
                    let item14 = isDataKosongreg(wil14) ? '-' : calculateTotalScorereg(wil14);
                    let wil15 = resultreg[10][1]
                    let item15 = isDataKosongreg(wil15) ? '-' : calculateTotalScorereg(wil15);
                    let wil16 = resultreg[11][1]
                    let item16 = isDataKosongreg(wil16) ? '-' : calculateTotalScorereg(wil16);


                    var tr = document.createElement('tr');
                    let itemElement1 = document.createElement('td');
                    let itemElement2 = document.createElement('td');
                    let itemElement3 = document.createElement('td');
                    let itemElement4 = document.createElement('td');
                    let itemElement5 = document.createElement('td');
                    let itemElement6 = document.createElement('td');
                    let itemElement7 = document.createElement('td');
                    let itemElement8 = document.createElement('td');
                    let itemElement9 = document.createElement('td');
                    let itemElement10 = document.createElement('td');
                    let itemElement11 = document.createElement('td');
                    let itemElement12 = document.createElement('td');
                    let itemElement13 = document.createElement('td');
                    let itemElement14 = document.createElement('td');
                    let itemElement15 = document.createElement('td');
                    let itemElement16 = document.createElement('td');



                    itemElement1.classList.add("text-center");
                    itemElement1.innerText = item1
                    itemElement2.innerText = item2
                    itemElement3.innerText = item3
                    itemElement4.innerText = item4
                    itemElement5.innerText = item5
                    itemElement6.innerText = item6
                    itemElement7.innerText = item7
                    itemElement8.innerText = item8
                    itemElement9.innerText = item9
                    itemElement10.innerText = item10
                    itemElement11.innerText = item11
                    itemElement12.innerText = item12
                    itemElement13.innerText = item13
                    itemElement14.innerText = item14
                    itemElement15.innerText = item15
                    itemElement16.innerText = item16

                    setBackgroundColor(itemElement5, item5);
                    setBackgroundColor(itemElement6, item6);
                    setBackgroundColor(itemElement7, item7);
                    setBackgroundColor(itemElement8, item8);
                    setBackgroundColor(itemElement9, item9);
                    setBackgroundColor(itemElement10, item10);
                    setBackgroundColor(itemElement11, item11);
                    setBackgroundColor(itemElement12, item12);
                    setBackgroundColor(itemElement13, item13);
                    setBackgroundColor(itemElement14, item14);
                    setBackgroundColor(itemElement15, item15);
                    setBackgroundColor(itemElement16, item16);

                    tr.appendChild(itemElement1)
                    tr.appendChild(itemElement2)
                    tr.appendChild(itemElement3)
                    tr.appendChild(itemElement4)
                    tr.appendChild(itemElement5)
                    tr.appendChild(itemElement6)
                    tr.appendChild(itemElement7)
                    tr.appendChild(itemElement8)
                    tr.appendChild(itemElement9)
                    tr.appendChild(itemElement10)
                    tr.appendChild(itemElement11)
                    tr.appendChild(itemElement12)
                    tr.appendChild(itemElement13)
                    tr.appendChild(itemElement14)
                    tr.appendChild(itemElement15)
                    tr.appendChild(itemElement16)
                    theadreg.appendChild(tr);

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

            // let currentRegion = regionalSelect.value;

            let firstClick = true; // Add a flag to indicate the first click

            // estBtn.addEventListener('click', () => {
            //     if (firstClick) {
            //         showBtn.click();
            //         firstClick = false; // Set the flag to false after the first click
            //     }
            //     handleSort('est');
            // });
            // rankBtn.addEventListener('click', () => {
            //     if (firstClick) {
            //         showBtn.click();
            //         firstClick = false; // Set the flag to false after the first click
            //     }
            //     handleSort('rank');
            // });
            // showBtn.addEventListener('click', handleShow);

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
            // const scrennshotimg = document.getElementById('scrennshotimg');
            // const downloadimgmap = document.getElementById('downloadimgmap');

            // let currentRegion = regionalSelect.value;

            let firstClick = true; // Add a flag to indicate the first click

            // estBtn.addEventListener('click', () => {
            //     if (firstClick) {
            //         showBtn.click();
            //         firstClick = false; // Set the flag to false after the first click
            //     }
            //     handleSort('est');
            // });
            // rankBtn.addEventListener('click', () => {
            //     if (firstClick) {
            //         showBtn.click();
            //         firstClick = false; // Set the flag to false after the first click
            //     }
            //     handleSort('rank');
            // });
            // showBtn.addEventListener('click', handleShow);

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


        $("#scrennshotimg").click(function() {
            captureTableScreenshot('screnshot_bulanan', 'REKAPITULASI RANKING NILAI SIDAK PEMERIKSAAN TPH')
        });
        $("#downloadimgmap").click(function() {
            captureTableScreenshot('map', 'SCORE KUALITAS PANEN BERDASARKAN BLOK')
        });

        function editnilaiqc() {
            var regIns = $("#regDataIns").val();
            var dateIns = $("#dateDataIns").val();
            var estateedit = $("#estateedit").val();
            var nilaiedit = $("#nilaiedit").val();
            var kurangstatus = $("#flexRadioDefault1").prop("checked");
            var tambahstatus = $("#flexRadioDefault2").prop("checked");

            let flexSwitchCheckDefault = kurangstatus === true;

            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: "{{ route('editnilaidataestate') }}",
                method: "post",
                cache: false,
                data: {
                    _token: _token,
                    regional: regIns,
                    date: dateIns,
                    estate: estateedit,
                    nilai: nilaiedit,
                    type: flexSwitchCheckDefault,
                },
                success: function(result, status, xhr) {
                    Swal.close();
                    // Handle responses based on the message
                    if (result.message === 'Nilai berhasil di kurang' || result.message === 'Nilai berhasil di tambah') {
                        alert(result.message); // Success message
                        location.reload();
                    } else if (result.message === 'Anda tidak dapat mengurangi atau menambah lagi estate ini') {
                        alert(result.message); // Record already exists message
                    } else {
                        alert('Unexpected error occurred.'); // Handle other unexpected messages
                        console.error(result.error); // Log the error for debugging
                        location.reload();
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error: ' + error); // Handle AJAX errors
                    console.error(xhr.responseText); // Log the detailed error response
                    location.reload();
                }
            });
        }
    </script>

</x-layout.app>