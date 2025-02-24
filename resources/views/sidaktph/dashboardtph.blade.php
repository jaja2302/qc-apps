<x-layout.app>
  <link rel="stylesheet" href="{{ asset('qc_css/tph/tph.css') }}">
  <div class="container-fluid">

    <section class="content"><br>
      <div class="card table_wrapper">
        <nav>
          <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <a class="nav-item nav-link active" id="nav-utama-tab" data-toggle="tab" href="#nav-utama" role="tab" aria-controls="nav-utama" aria-selected="true">Halaman Utama</a>
            <a class="nav-item nav-link" id="nav-data-tab" data-toggle="tab" href="#nav-data" role="tab" aria-controls="nav-data" aria-selected="false">Data</a>
            <a class="nav-item nav-link" id="nav-sbi-tab" data-toggle="tab" href="#nav-sbi" role="tab" aria-controls="nav-sbi" aria-selected="false">SBI</a>
          </div>
        </nav>

        <div class="tab-content" id="nav-tabContent">
          <div class="tab-pane fade show active" id="nav-utama" role="tabpanel" aria-labelledby="nav-utama-tab">
            <div class="d-flex justify-content-center mt-3 mb-3 ml-3 mr-3 border border-dark">
              <h5><b>REKAPITULASI RANKING NILAI SIDAK PEMERIKSAAN TPH</b></h5>
            </div>

            <div class="d-flex justify-content-end mt-3 mb-2 ml-3 mr-3" style="padding-top: 20px;">
              <div class="row w-100">
                <div class="col-md-2 offset-md-8">
                  {{csrf_field()}}
                  <select class="form-control" name="regionalSidakMonth" id="regionalSidakMonth">
                    @foreach($option_reg as $key => $item)
                    <option value="{{ $item['id'] }}">{{ $item['nama'] }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                  {{ csrf_field() }}
                  <input class="form-control" value="{{ date('Y-m') }}" type="month" name="inputDateMonth" id="inputDateMonth">

                </div>
              </div>
              <button class="btn btn-primary mb-3 ml-3" id="btnShowMonth">Show</button>
            </div>
            <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 ">
              <button id="sort-est-btn">sort by Afd</button>
              <button id="sort-rank-btn">Sort by Rank</button>
              <button id="scrennshotimg">Download As IMG</button>
            </div>
            <div id="tablesContainer">
              <div class="tabContainer">
                <div class="ml-3 mr-3">
                  <div class="row justify-content-center" id="scrensshot_bulanan">
                    <div class="col-12 col-md-6 col-lg-3" data-regional="1" id="table1Month">
                      <div class="table-responsive">
                        <table class=" table table-bordered" style="font-size: 13px;background-color:white" id="table1">
                          <thead>
                            <tr bgcolor="yellow">
                              <th colspan="5" id="thWilOneMonth">WILAYAH I</th>
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
                          <tbody id="tbody1Month">
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3" data-regional="1" id="classTwoMonth">
                      <div class="table-responsive">
                        <table class=" table table-bordered" style="font-size: 13px;background-color:white" id="table2">
                          <thead>
                            <tr bgcolor="yellow">
                              <th colspan="5" id="thWilTwoMonth">WILAYAH II</th>
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
                          <tbody id="tbody2Month">

                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3" data-regional="1" id="classThreeMonth">
                      <div class="table-responsive">
                        <table class="table table-bordered" style="font-size: 13px;background-color:white" id="table3">
                          <thead>
                            <tr bgcolor="yellow">
                              <th colspan="5" id="thWilThreeMonth">WILAYAH III</th>
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
                          <tbody id="tbody3Month">

                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3" data-regional="1" id="classFourMonth">
                      <div class="table-responsive">
                        <table class="table table-bordered" style="font-size: 13px;background-color:white" id="table4">
                          <thead>
                            <tr bgcolor="yellow">
                              <th colspan="5" id="thPlasmamonth">PLASMA</th>
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
                          <tbody id="plasmaMonth">

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
                <thead id="tbodySkorRHMonth">
                </thead>
              </table>
            </div>

            <div id="accordion" class="ml-3 mr-3">
              <div class="card">
                <button class="btn btn-secondary text-uppercase" data-toggle="collapse" data-target="#graphEstMonth" aria-expanded="false" aria-controls="graphEstMonth">
                  Grafik Sidak TPH berdasarkan Estate
                </button>
                <div id="graphEstMonth" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-sm-6">
                        <div class="card">
                          <div class="card-body">
                            <p style="font-size: 15px; text-align: center;" class="text-uppercase">
                              <b>TOTAL BRONDOLAN TINGGAL</b>
                            </p>
                            <div id="bttinggalMonth"></div>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="card">
                          <div class="card-body">
                            <p style="font-size: 15px; text-align: center;" class="text-uppercase"><b>
                                TOTAL BUAH TINGGAL</b>
                            <div id="karungMonth"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div id="accordion" class="ml-3 mr-3">
              <div class="card">
                <button class="btn btn-secondary text-uppercase" data-toggle="collapse" data-target="#graphWilMonth" aria-expanded="false" aria-controls="graphWilMonth">
                  Grafik Sidak TPH berdasarkan Wilayah
                </button>
                <div id="graphWilMonth" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-sm-6">
                        <div class="card">
                          <div class="card-body">
                            <p style="font-size: 15px; text-align: center;" class="text-uppercase">
                              <b>TOTAL BRONDOLAN TINGGAL </b>
                            </p>
                            <div id="btt_idMonth"></div>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="card">
                          <div class="card-body">
                            <p style="font-size: 15px; text-align: center;" class="text-uppercase"><b>
                                TOTAL BUAH TINGGAL</b>
                            <div id="karung_idMonth"></div>
                          </div>
                        </div>
                      </div>
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
                  <select class="form-control" id="regDataTph">
                    @foreach($option_reg as $key => $item)
                    <option value="{{ $item['id'] }}">{{ $item['nama'] }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                  {{ csrf_field() }}
                  <input class="form-control" value="{{ date('Y-m') }}" type="month" name="tgl" id="dateDataTph">

                </div>
              </div>
              <button class="btn btn-primary mb-3 ml-3" id="showDataTph">Show</button>
              <form id="exportForm" action="{{ route('excelsidaktph') }}" method="POST">
                @csrf
                <input type="hidden" id="getregionalexcel" name="getregionalexcel">
                <input type="hidden" id="getdateexcel" name="getdateexcel">
                <button type="submit" class="btn btn-primary">Export</button>
              </form>
            </div>




            <div class="ml-3 mr-3 mb-3">
              <ul class="nav nav-tabs" id="myTabs" role="tablist">
                <li class="nav-item" role="presentation">
                  <a class="nav-link active" id="week1-tab" data-toggle="tab" href="#week1" role="tab" aria-controls="week1" aria-selected="true">week1</a>
                </li>
                <li class="nav-item" role="presentation">
                  <a class="nav-link" id="week2-tab" data-toggle="tab" href="#week2" role="tab" aria-controls="week2" aria-selected="false">week2</a>
                </li>
                <li class="nav-item" role="presentation">
                  <a class="nav-link" id="week3-tab" data-toggle="tab" href="#week3" role="tab" aria-controls="week3" aria-selected="false">week3</a>
                </li>
                <li class="nav-item" role="presentation">
                  <a class="nav-link" id="week4-tab" data-toggle="tab" href="#week4" role="tab" aria-controls="week4" aria-selected="false">week4</a>
                </li>
                <li class="nav-item" role="presentation">
                  <a class="nav-link" id="month-tab" data-toggle="tab" href="#month" role="tab" aria-controls="month" aria-selected="false">week5</a>
                </li>
              </ul>
              <div class="tab-content" id="myTabsContent">

                <!-- mingg pertama  -->
                <div class="tab-pane fade show active" id="week1" role="tabpanel" aria-labelledby="week1-tab">
                  <table id="newweek1" class="table table-striped nowrap" style="width:100%">
                    <thead>
                      <tr>
                        <th rowspan="3">EST</th>
                        <th rowspan="3">AFD</th>
                        <th colspan="10"> H+1</th>
                        <th colspan="10"> H+2</th>
                        <th colspan="10"> H+3</th>
                        <th colspan="10"> H+4</th>
                        <th colspan="10"> H+5</th>
                        <th colspan="10"> H+6</th>
                        <th colspan="10"> H+7</th>
                        <th colspan="10"> >H+7 </th>
                        <th rowspan="3"> All Skor</th>
                        <th rowspan="3"> Kategori</th>
                      </tr>
                      <tr>
                        <th colspan="6">Brondolan Tinggal</th>
                        <th colspan="4">Buah Tinggal</th>
                        <th colspan="6">Brondolan Tinggal</th>
                        <th colspan="4">Buah Tinggal</th>
                        <th colspan="6">Brondolan Tinggal</th>
                        <th colspan="4">Buah Tinggal</th>
                        <th colspan="6">Brondolan Tinggal</th>
                        <th colspan="4">Buah Tinggal</th>
                        <th colspan="6">Brondolan Tinggal</th>
                        <th colspan="4">Buah Tinggal</th>
                        <th colspan="6">Brondolan Tinggal</th>
                        <th colspan="4">Buah Tinggal</th>
                        <th colspan="6">Brondolan Tinggal</th>
                        <th colspan="4">Buah Tinggal</th>
                        <th colspan="6">Brondolan Tinggal</th>
                        <th colspan="4">Buah Tinggal</th>
                      </tr>
                      <tr>
                        <th> Di TPH</th>
                        <th> Di Jalan</th>
                        <th> Di Bin</th>
                        <th> Di Karung</th>
                        <th> Total Brd</th>
                        <th> Skor</th>
                        <th> Buah Sortiran / Buah Jatuh </th>
                        <th>Restan Tidak Dilaporkan </th>
                        <th>Total Jjg </th>
                        <th>Skor</th>
                        <th> Di TPH</th>
                        <th> Di Jalan</th>
                        <th> Di Bin</th>
                        <th> Di Karung</th>
                        <th> Total Brd</th>
                        <th> Skor</th>
                        <th> Buah Sortiran / Buah Jatuh </th>
                        <th>Restan Tidak Dilaporkan </th>
                        <th>Total Jjg </th>
                        <th>Skor</th>
                        <th> Di TPH</th>
                        <th> Di Jalan</th>
                        <th> Di Bin</th>
                        <th> Di Karung</th>
                        <th> Total Brd</th>
                        <th> Skor</th>
                        <th> Buah Sortiran / Buah Jatuh </th>
                        <th>Restan Tidak Dilaporkan </th>
                        <th>Total Jjg </th>
                        <th>Skor</th>
                        <th> Di TPH</th>
                        <th> Di Jalan</th>
                        <th> Di Bin</th>
                        <th> Di Karung</th>
                        <th> Total Brd</th>
                        <th> Skor</th>
                        <th> Buah Sortiran / Buah Jatuh </th>
                        <th>Restan Tidak Dilaporkan </th>
                        <th>Total Jjg </th>
                        <th>Skor</th>
                        <th> Di TPH</th>
                        <th> Di Jalan</th>
                        <th> Di Bin</th>
                        <th> Di Karung</th>
                        <th> Total Brd</th>
                        <th> Skor</th>
                        <th> Buah Sortiran / Buah Jatuh </th>
                        <th>Restan Tidak Dilaporkan </th>
                        <th>Total Jjg </th>
                        <th>Skor</th>
                        <th> Di TPH</th>
                        <th> Di Jalan</th>
                        <th> Di Bin</th>
                        <th> Di Karung</th>
                        <th> Total Brd</th>
                        <th> Skor</th>
                        <th> Buah Sortiran / Buah Jatuh </th>
                        <th>Restan Tidak Dilaporkan </th>
                        <th>Total Jjg </th>
                        <th>Skor</th>
                        <th> Di TPH</th>
                        <th> Di Jalan</th>
                        <th> Di Bin</th>
                        <th> Di Karung</th>
                        <th> Total Brd</th>
                        <th> Skor</th>
                        <th> Buah Sortiran / Buah Jatuh </th>
                        <th>Restan Tidak Dilaporkan </th>
                        <th>Total Jjg </th>
                        <th>Skor</th>
                        <th> Di TPH</th>
                        <th> Di Jalan</th>
                        <th> Di Bin</th>
                        <th> Di Karung</th>
                        <th> Total Brd</th>
                        <th> Skor</th>
                        <th> Buah Sortiran / Buah Jatuh </th>
                        <th>Restan Tidak Dilaporkan </th>
                        <th>Total Jjg </th>
                        <th>Skor</th>

                      </tr>
                    </thead>

                    <tbody>

                    </tbody>
                  </table>

                </div>

                <!-- minggu ke dua  -->
                <div class="tab-pane fade" id="week2" role="tabpanel" aria-labelledby="week2-tab">
                  <table id="newweek2" class="table table-striped nowrap" style="width:100%">
                    <thead>
                      <tr>
                        <th rowspan="3">EST</th>
                        <th rowspan="3">AFD</th>
                        <th colspan="10"> H+1</th>
                        <th colspan="10"> H+2</th>
                        <th colspan="10"> H+3</th>
                        <th colspan="10"> H+4</th>
                        <th colspan="10"> H+5</th>
                        <th colspan="10"> H+6</th>
                        <th colspan="10"> H+7</th>
                        <th colspan="10"> >H+7 </th>
                        <th rowspan="3"> All Skor</th>
                        <th rowspan="3"> Kategori</th>
                      </tr>
                      <tr>
                        <th colspan="6">Brondolan Tinggal</th>
                        <th colspan="4">Buah Tinggal</th>
                        <th colspan="6">Brondolan Tinggal</th>
                        <th colspan="4">Buah Tinggal</th>
                        <th colspan="6">Brondolan Tinggal</th>
                        <th colspan="4">Buah Tinggal</th>
                        <th colspan="6">Brondolan Tinggal</th>
                        <th colspan="4">Buah Tinggal</th>
                        <th colspan="6">Brondolan Tinggal</th>
                        <th colspan="4">Buah Tinggal</th>
                        <th colspan="6">Brondolan Tinggal</th>
                        <th colspan="4">Buah Tinggal</th>
                        <th colspan="6">Brondolan Tinggal</th>
                        <th colspan="4">Buah Tinggal</th>
                        <th colspan="6">Brondolan Tinggal</th>
                        <th colspan="4">Buah Tinggal</th>
                      </tr>
                      <tr>
                        <th> Di TPH</th>
                        <th> Di Jalan</th>
                        <th> Di Bin</th>
                        <th> Di Karung</th>
                        <th> Total Brd</th>
                        <th> Skor</th>
                        <th> Buah Sortiran / Buah Jatuh </th>
                        <th>Restan Tidak Dilaporkan </th>
                        <th>Total Jjg </th>
                        <th>Skor</th>
                        <th> Di TPH</th>
                        <th> Di Jalan</th>
                        <th> Di Bin</th>
                        <th> Di Karung</th>
                        <th> Total Brd</th>
                        <th> Skor</th>
                        <th> Buah Sortiran / Buah Jatuh </th>
                        <th>Restan Tidak Dilaporkan </th>
                        <th>Total Jjg </th>
                        <th>Skor</th>
                        <th> Di TPH</th>
                        <th> Di Jalan</th>
                        <th> Di Bin</th>
                        <th> Di Karung</th>
                        <th> Total Brd</th>
                        <th> Skor</th>
                        <th> Buah Sortiran / Buah Jatuh </th>
                        <th>Restan Tidak Dilaporkan </th>
                        <th>Total Jjg </th>
                        <th>Skor</th>
                        <th> Di TPH</th>
                        <th> Di Jalan</th>
                        <th> Di Bin</th>
                        <th> Di Karung</th>
                        <th> Total Brd</th>
                        <th> Skor</th>
                        <th> Buah Sortiran / Buah Jatuh </th>
                        <th>Restan Tidak Dilaporkan </th>
                        <th>Total Jjg </th>
                        <th>Skor</th>
                        <th> Di TPH</th>
                        <th> Di Jalan</th>
                        <th> Di Bin</th>
                        <th> Di Karung</th>
                        <th> Total Brd</th>
                        <th> Skor</th>
                        <th> Buah Sortiran / Buah Jatuh </th>
                        <th>Restan Tidak Dilaporkan </th>
                        <th>Total Jjg </th>
                        <th>Skor</th>
                        <th> Di TPH</th>
                        <th> Di Jalan</th>
                        <th> Di Bin</th>
                        <th> Di Karung</th>
                        <th> Total Brd</th>
                        <th> Skor</th>
                        <th> Buah Sortiran / Buah Jatuh </th>
                        <th>Restan Tidak Dilaporkan </th>
                        <th>Total Jjg </th>
                        <th>Skor</th>
                        <th> Di TPH</th>
                        <th> Di Jalan</th>
                        <th> Di Bin</th>
                        <th> Di Karung</th>
                        <th> Total Brd</th>
                        <th> Skor</th>
                        <th> Buah Sortiran / Buah Jatuh </th>
                        <th>Restan Tidak Dilaporkan </th>
                        <th>Total Jjg </th>
                        <th>Skor</th>
                        <th> Di TPH</th>
                        <th> Di Jalan</th>
                        <th> Di Bin</th>
                        <th> Di Karung</th>
                        <th> Total Brd</th>
                        <th> Skor</th>
                        <th> Buah Sortiran / Buah Jatuh </th>
                        <th>Restan Tidak Dilaporkan </th>
                        <th>Total Jjg </th>
                        <th>Skor</th>

                      </tr>
                    </thead>

                    <tbody>

                    </tbody>
                  </table>
                </div>

                <!-- minggu ke 3  -->
                <div class="tab-pane fade" id="week3" role="tabpanel" aria-labelledby="week3-tab">

                  <table id="newweek3" class="table table-striped nowrap" style="width:100%">
                    <thead>
                      <tr>
                        <th rowspan="3">EST</th>
                        <th rowspan="3">AFD</th>
                        <th colspan="10"> H+1</th>
                        <th colspan="10"> H+2</th>
                        <th colspan="10"> H+3</th>
                        <th colspan="10"> H+4</th>
                        <th colspan="10"> H+5</th>
                        <th colspan="10"> H+6</th>
                        <th colspan="10"> H+7</th>
                        <th colspan="10"> >H+7 </th>
                        <th rowspan="3"> All Skor</th>
                        <th rowspan="3"> Kategori</th>
                      </tr>
                      <tr>
                        <th colspan="6">Brondolan Tinggal</th>
                        <th colspan="4">Buah Tinggal</th>
                        <th colspan="6">Brondolan Tinggal</th>
                        <th colspan="4">Buah Tinggal</th>
                        <th colspan="6">Brondolan Tinggal</th>
                        <th colspan="4">Buah Tinggal</th>
                        <th colspan="6">Brondolan Tinggal</th>
                        <th colspan="4">Buah Tinggal</th>
                        <th colspan="6">Brondolan Tinggal</th>
                        <th colspan="4">Buah Tinggal</th>
                        <th colspan="6">Brondolan Tinggal</th>
                        <th colspan="4">Buah Tinggal</th>
                        <th colspan="6">Brondolan Tinggal</th>
                        <th colspan="4">Buah Tinggal</th>
                        <th colspan="6">Brondolan Tinggal</th>
                        <th colspan="4">Buah Tinggal</th>
                      </tr>
                      <tr>
                        <th> Di TPH</th>
                        <th> Di Jalan</th>
                        <th> Di Bin</th>
                        <th> Di Karung</th>
                        <th> Total Brd</th>
                        <th> Skor</th>
                        <th> Buah Sortiran / Buah Jatuh </th>
                        <th>Restan Tidak Dilaporkan </th>
                        <th>Total Jjg </th>
                        <th>Skor</th>
                        <th> Di TPH</th>
                        <th> Di Jalan</th>
                        <th> Di Bin</th>
                        <th> Di Karung</th>
                        <th> Total Brd</th>
                        <th> Skor</th>
                        <th> Buah Sortiran / Buah Jatuh </th>
                        <th>Restan Tidak Dilaporkan </th>
                        <th>Total Jjg </th>
                        <th>Skor</th>
                        <th> Di TPH</th>
                        <th> Di Jalan</th>
                        <th> Di Bin</th>
                        <th> Di Karung</th>
                        <th> Total Brd</th>
                        <th> Skor</th>
                        <th> Buah Sortiran / Buah Jatuh </th>
                        <th>Restan Tidak Dilaporkan </th>
                        <th>Total Jjg </th>
                        <th>Skor</th>
                        <th> Di TPH</th>
                        <th> Di Jalan</th>
                        <th> Di Bin</th>
                        <th> Di Karung</th>
                        <th> Total Brd</th>
                        <th> Skor</th>
                        <th> Buah Sortiran / Buah Jatuh </th>
                        <th>Restan Tidak Dilaporkan </th>
                        <th>Total Jjg </th>
                        <th>Skor</th>
                        <th> Di TPH</th>
                        <th> Di Jalan</th>
                        <th> Di Bin</th>
                        <th> Di Karung</th>
                        <th> Total Brd</th>
                        <th> Skor</th>
                        <th> Buah Sortiran / Buah Jatuh </th>
                        <th>Restan Tidak Dilaporkan </th>
                        <th>Total Jjg </th>
                        <th>Skor</th>
                        <th> Di TPH</th>
                        <th> Di Jalan</th>
                        <th> Di Bin</th>
                        <th> Di Karung</th>
                        <th> Total Brd</th>
                        <th> Skor</th>
                        <th> Buah Sortiran / Buah Jatuh </th>
                        <th>Restan Tidak Dilaporkan </th>
                        <th>Total Jjg </th>
                        <th>Skor</th>
                        <th> Di TPH</th>
                        <th> Di Jalan</th>
                        <th> Di Bin</th>
                        <th> Di Karung</th>
                        <th> Total Brd</th>
                        <th> Skor</th>
                        <th> Buah Sortiran / Buah Jatuh </th>
                        <th>Restan Tidak Dilaporkan </th>
                        <th>Total Jjg </th>
                        <th>Skor</th>
                        <th> Di TPH</th>
                        <th> Di Jalan</th>
                        <th> Di Bin</th>
                        <th> Di Karung</th>
                        <th> Total Brd</th>
                        <th> Skor</th>
                        <th> Buah Sortiran / Buah Jatuh </th>
                        <th>Restan Tidak Dilaporkan </th>
                        <th>Total Jjg </th>
                        <th>Skor</th>

                      </tr>
                    </thead>

                    <tbody>

                    </tbody>
                  </table>

                </div>

                <!-- minggu ke 4  -->
                <div class="tab-pane fade" id="week4" role="tabpanel" aria-labelledby="week4-tab">
                  <table id="newweek4" class="table table-striped nowrap" style="width:100%;">
                    <thead>
                      <tr>
                        <th rowspan="3">EST</th>
                        <th rowspan="3">AFD</th>
                        <th colspan="10"> H+1</th>
                        <th colspan="10"> H+2</th>
                        <th colspan="10"> H+3</th>
                        <th colspan="10"> H+4</th>
                        <th colspan="10"> H+5</th>
                        <th colspan="10"> H+6</th>
                        <th colspan="10"> H+7</th>
                        <th colspan="10"> >H+7 </th>
                        <th rowspan="3"> All Skor</th>
                        <th rowspan="3"> Kategori</th>
                      </tr>
                      <tr>
                        <th colspan="6">Brondolan Tinggal</th>
                        <th colspan="4">Buah Tinggal</th>
                        <th colspan="6">Brondolan Tinggal</th>
                        <th colspan="4">Buah Tinggal</th>
                        <th colspan="6">Brondolan Tinggal</th>
                        <th colspan="4">Buah Tinggal</th>
                        <th colspan="6">Brondolan Tinggal</th>
                        <th colspan="4">Buah Tinggal</th>
                        <th colspan="6">Brondolan Tinggal</th>
                        <th colspan="4">Buah Tinggal</th>
                        <th colspan="6">Brondolan Tinggal</th>
                        <th colspan="4">Buah Tinggal</th>
                        <th colspan="6">Brondolan Tinggal</th>
                        <th colspan="4">Buah Tinggal</th>
                        <th colspan="6">Brondolan Tinggal</th>
                        <th colspan="4">Buah Tinggal</th>
                      </tr>
                      <tr>
                        <th> Di TPH</th>
                        <th> Di Jalan</th>
                        <th> Di Bin</th>
                        <th> Di Karung</th>
                        <th> Total Brd</th>
                        <th> Skor</th>
                        <th> Buah Sortiran / Buah Jatuh </th>
                        <th>Restan Tidak Dilaporkan </th>
                        <th>Total Jjg </th>
                        <th>Skor</th>
                        <th> Di TPH</th>
                        <th> Di Jalan</th>
                        <th> Di Bin</th>
                        <th> Di Karung</th>
                        <th> Total Brd</th>
                        <th> Skor</th>
                        <th> Buah Sortiran / Buah Jatuh </th>
                        <th>Restan Tidak Dilaporkan </th>
                        <th>Total Jjg </th>
                        <th>Skor</th>
                        <th> Di TPH</th>
                        <th> Di Jalan</th>
                        <th> Di Bin</th>
                        <th> Di Karung</th>
                        <th> Total Brd</th>
                        <th> Skor</th>
                        <th> Buah Sortiran / Buah Jatuh </th>
                        <th>Restan Tidak Dilaporkan </th>
                        <th>Total Jjg </th>
                        <th>Skor</th>
                        <th> Di TPH</th>
                        <th> Di Jalan</th>
                        <th> Di Bin</th>
                        <th> Di Karung</th>
                        <th> Total Brd</th>
                        <th> Skor</th>
                        <th> Buah Sortiran / Buah Jatuh </th>
                        <th>Restan Tidak Dilaporkan </th>
                        <th>Total Jjg </th>
                        <th>Skor</th>
                        <th> Di TPH</th>
                        <th> Di Jalan</th>
                        <th> Di Bin</th>
                        <th> Di Karung</th>
                        <th> Total Brd</th>
                        <th> Skor</th>
                        <th> Buah Sortiran / Buah Jatuh </th>
                        <th>Restan Tidak Dilaporkan </th>
                        <th>Total Jjg </th>
                        <th>Skor</th>
                        <th> Di TPH</th>
                        <th> Di Jalan</th>
                        <th> Di Bin</th>
                        <th> Di Karung</th>
                        <th> Total Brd</th>
                        <th> Skor</th>
                        <th> Buah Sortiran / Buah Jatuh </th>
                        <th>Restan Tidak Dilaporkan </th>
                        <th>Total Jjg </th>
                        <th>Skor</th>
                        <th> Di TPH</th>
                        <th> Di Jalan</th>
                        <th> Di Bin</th>
                        <th> Di Karung</th>
                        <th> Total Brd</th>
                        <th> Skor</th>
                        <th> Buah Sortiran / Buah Jatuh </th>
                        <th>Restan Tidak Dilaporkan </th>
                        <th>Total Jjg </th>
                        <th>Skor</th>
                        <th> Di TPH</th>
                        <th> Di Jalan</th>
                        <th> Di Bin</th>
                        <th> Di Karung</th>
                        <th> Total Brd</th>
                        <th> Skor</th>
                        <th> Buah Sortiran / Buah Jatuh </th>
                        <th>Restan Tidak Dilaporkan </th>
                        <th>Total Jjg </th>
                        <th>Skor</th>

                      </tr>
                    </thead>

                    <tbody>

                    </tbody>
                  </table>

                </div>


                <!-- bulan  -->
                <div class="tab-pane fade" id="month" role="tabpanel" aria-labelledby="month-tab">
                  <table id="newweek5" class="table table-striped nowrap" style="width:100%;">
                    <thead>
                      <tr>
                        <th rowspan="3">EST</th>
                        <th rowspan="3">AFD</th>
                        <th colspan="10"> H+1</th>
                        <th colspan="10"> H+2</th>
                        <th colspan="10"> H+3</th>
                        <th colspan="10"> H+4</th>
                        <th colspan="10"> H+5</th>
                        <th colspan="10"> H+6</th>
                        <th colspan="10"> H+7</th>
                        <th colspan="10"> >H+7 </th>
                        <th rowspan="3"> All Skor</th>
                        <th rowspan="3"> Kategori</th>
                      </tr>
                      <tr>
                        <th colspan="6">Brondolan Tinggal</th>
                        <th colspan="4">Buah Tinggal</th>
                        <th colspan="6">Brondolan Tinggal</th>
                        <th colspan="4">Buah Tinggal</th>
                        <th colspan="6">Brondolan Tinggal</th>
                        <th colspan="4">Buah Tinggal</th>
                        <th colspan="6">Brondolan Tinggal</th>
                        <th colspan="4">Buah Tinggal</th>
                        <th colspan="6">Brondolan Tinggal</th>
                        <th colspan="4">Buah Tinggal</th>
                        <th colspan="6">Brondolan Tinggal</th>
                        <th colspan="4">Buah Tinggal</th>
                        <th colspan="6">Brondolan Tinggal</th>
                        <th colspan="4">Buah Tinggal</th>
                        <th colspan="6">Brondolan Tinggal</th>
                        <th colspan="4">Buah Tinggal</th>
                      </tr>
                      <tr>
                        <th> Di TPH</th>
                        <th> Di Jalan</th>
                        <th> Di Bin</th>
                        <th> Di Karung</th>
                        <th> Total Brd</th>
                        <th> Skor</th>
                        <th> Buah Sortiran / Buah Jatuh </th>
                        <th>Restan Tidak Dilaporkan </th>
                        <th>Total Jjg </th>
                        <th>Skor</th>
                        <th> Di TPH</th>
                        <th> Di Jalan</th>
                        <th> Di Bin</th>
                        <th> Di Karung</th>
                        <th> Total Brd</th>
                        <th> Skor</th>
                        <th> Buah Sortiran / Buah Jatuh </th>
                        <th>Restan Tidak Dilaporkan </th>
                        <th>Total Jjg </th>
                        <th>Skor</th>
                        <th> Di TPH</th>
                        <th> Di Jalan</th>
                        <th> Di Bin</th>
                        <th> Di Karung</th>
                        <th> Total Brd</th>
                        <th> Skor</th>
                        <th> Buah Sortiran / Buah Jatuh </th>
                        <th>Restan Tidak Dilaporkan </th>
                        <th>Total Jjg </th>
                        <th>Skor</th>
                        <th> Di TPH</th>
                        <th> Di Jalan</th>
                        <th> Di Bin</th>
                        <th> Di Karung</th>
                        <th> Total Brd</th>
                        <th> Skor</th>
                        <th> Buah Sortiran / Buah Jatuh </th>
                        <th>Restan Tidak Dilaporkan </th>
                        <th>Total Jjg </th>
                        <th>Skor</th>
                        <th> Di TPH</th>
                        <th> Di Jalan</th>
                        <th> Di Bin</th>
                        <th> Di Karung</th>
                        <th> Total Brd</th>
                        <th> Skor</th>
                        <th> Buah Sortiran / Buah Jatuh </th>
                        <th>Restan Tidak Dilaporkan </th>
                        <th>Total Jjg </th>
                        <th>Skor</th>
                        <th> Di TPH</th>
                        <th> Di Jalan</th>
                        <th> Di Bin</th>
                        <th> Di Karung</th>
                        <th> Total Brd</th>
                        <th> Skor</th>
                        <th> Buah Sortiran / Buah Jatuh </th>
                        <th>Restan Tidak Dilaporkan </th>
                        <th>Total Jjg </th>
                        <th>Skor</th>
                        <th> Di TPH</th>
                        <th> Di Jalan</th>
                        <th> Di Bin</th>
                        <th> Di Karung</th>
                        <th> Total Brd</th>
                        <th> Skor</th>
                        <th> Buah Sortiran / Buah Jatuh </th>
                        <th>Restan Tidak Dilaporkan </th>
                        <th>Total Jjg </th>
                        <th>Skor</th>
                        <th> Di TPH</th>
                        <th> Di Jalan</th>
                        <th> Di Bin</th>
                        <th> Di Karung</th>
                        <th> Total Brd</th>
                        <th> Skor</th>
                        <th> Buah Sortiran / Buah Jatuh </th>
                        <th>Restan Tidak Dilaporkan </th>
                        <th>Total Jjg </th>
                        <th>Skor</th>

                      </tr>
                    </thead>

                    <tbody>

                    </tbody>
                  </table>
                </div>

              </div>
            </div>




          </div>

          <div class="tab-pane fade" id="nav-sbi" role="tabpanel" aria-labelledby="nav-sbi-tab">
            <div class="d-flex justify-content-center mt-3 mb-3 ml-3 mr-3 border border-dark">
              <h5><b>REKAPITULASI RANKING NILAI SIDAK PEMERIKSAAN TPH</b></h5>
            </div>

            <div class="container">
              <div class="row align-items-center">
                <div class="col-md-8 offset-md-2">
                  <div class="form-group"> {{ csrf_field() }}
                    <select class="form-control" name="regionalSidakYear" id="regionalSidakYear">
                      @foreach($option_reg as $key => $item)
                      <option value="{{ $item['id'] }}">{{ $item['nama'] }}</option>
                      @endforeach
                    </select>
                  </div>

                  <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                      <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">By Year</button>
                    </li>
                    <li class="nav-item" role="presentation">
                      <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">By Month</button>
                    </li>
                  </ul>

                  <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                      {{ csrf_field() }}
                      <select class="form-control" name="inputYear" id="inputYear">
                        @foreach ($optYear as $value)
                        <option value="{{ $value }}">{{ $value }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">

                      <div class="form-group">
                        <label for="start_date">Start Date:</label>
                        <input type="month" class="form-control datepicker" id="start_date" name="start_date" autocomplete="off">
                      </div>
                      <div class="form-group">
                        <label for="end_date">End Date:</label>
                        <input type="month" class="form-control datepicker" id="end_date" name="end_date" autocomplete="off">
                      </div>

                    </div>
                  </div>

                  <button class="btn btn-primary mb-3 float-end" id="btnShowYear">Show</button>
                </div>
              </div>
            </div>

            <div id="tablesContainer">
              <div class="tabContainer">
                <div class="ml-3 mr-3">
                  <div class="row text-center">
                    <div class="col-12 col-md-6 col-lg-4" id="Tab1">
                      <div class="table-responsive">
                        <table class=" table table-bordered" style="font-size: 13px" id="table1">
                          <thead>
                            <tr bgcolor="darkorange">
                              <th colspan="5" id="thead1">WILAYAH I</th>
                            </tr>
                            <tr bgcolor="#2044a4" style="color: white">
                              <th rowspan="2" class="text-center" style="vertical-align: middle;">KEBUN</th>
                              <th rowspan="2" style="vertical-align: middle;">AFD</th>
                              <th rowspan="2" style="text-align:center; vertical-align: middle;">Nama</th>
                              <th colspan="2" class="text-center">Todate</th>
                            </tr>
                            <tr bgcolor="darkblue" style="color: white">
                              <th>Score</th>
                              <th>Rank</th>
                            </tr>
                          </thead>
                          <tbody id="tbody1Year">
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4" id="Tab2">
                      <div class="table-responsive">
                        <table class=" table table-bordered" style="font-size: 13px" id="table1">
                          <thead>
                            <tr bgcolor="darkorange">
                              <th colspan="5" id="thead2">WILAYAH II</th>
                            </tr>
                            <tr bgcolor="#2044a4" style="color: white">
                              <th rowspan="2" class="text-center" style="vertical-align: middle;">KEBUN</th>
                              <th rowspan="2" style="vertical-align: middle;">AFD</th>
                              <th rowspan="2" style="text-align:center; vertical-align: middle;">Nama</th>
                              <th colspan="2" class="text-center">Todate</th>
                            </tr>
                            <tr bgcolor="darkblue" style="color: white">
                              <th>Score</th>
                              <th>Rank</th>
                            </tr>
                          </thead>
                          <tbody id="tbody2Year">

                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4" id="Tab3">
                      <div class="table-responsive">
                        <table class="table table-bordered" style="font-size: 13px" id="Reg3">
                          <thead>
                            <tr bgcolor="darkorange">
                              <th colspan="5" id="thead3">WILAYAH III</th>
                            </tr>
                            <tr bgcolor="#2044a4" style="color: white">
                              <th rowspan="2" class="text-center" style="vertical-align: middle;">KEBUN</th>
                              <th rowspan="2" style="vertical-align: middle;">AFD</th>
                              <th rowspan="2" style="text-align:center; vertical-align: middle;">Nama</th>
                              <th colspan="2" class="text-center">Todate</th>
                            </tr>
                            <tr bgcolor="darkblue" style="color: white">
                              <th>Score</th>
                              <th>Rank</th>
                            </tr>
                          </thead>
                          <tbody id="tbody3Year">

                          </tbody>
                        </table>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
              <div class="col-sm-12">
                <table class="table table-bordered">
                  <thead id="tbodySkorRHYear">
                  </thead>
                </table>
              </div>
            </div>


            <div class="d-flex justify-content-center mb-3 ml-3 mr-3 border border-dark text-uppercase">
              <h5><b>GRAFIK REKAPITULASI SIDAK PEMERIKSAAN TPH</b></h5>
            </div>
            <div class="d-flex flex-row-reverse mr-2 mobile-view">
              <button class="btn btn-primary mb-3 mr-2" style="float: right" id="showGraphYear">Show</button>
              <div class="form-container mr-2">
                {{ csrf_field() }}
                <select class="form-control" name="estSidakYear" id="estSidakYear">
                </select>
              </div>
            </div>


            <div class="row ml-2 mr-2">
              <div class="col-sm-6">
                <div class="card">
                  <div class="card-body">
                    <p style="font-size: 15px; text-align: center;" class="text-uppercase">
                      <b>Total Brondolan Tinggal</b>
                    </p>
                    <div id="bttinggalYear"></div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="card">
                  <div class="card-body">
                    <p style="font-size: 15px; text-align: center;" class="text-uppercase">
                      <b>Total Buah Tinggal</b>
                    <div id="karungYear"></div>
                  </div>
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
                  <td>{{$items['est']}}</td>
                  <td>{{$items['afd']}}</td>
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
    let checkdata = @json($check);
    let recordsdupt = @json($idduplicate);
    if (checkdata === 'ada') {
      // Show Bootstrap modal
      const modalElement = document.getElementById('confirmationModal');
      if (modalElement) {
        new bootstrap.Modal(modalElement).show();
      }


      // Attach a click event to the "Yes" button
      $('#confirmBtn').on('click', function() {
        // User clicked 'Yes', proceed with your actions
        // console.log('User clicked Yes, proceed with AJAX request here');
        // console.log(recordsdupt);

        // Hide the Bootstrap modal
        var _token = $('input[name="_token"]').val();
        let type = 'sidaktph'

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






    $(document).ready(function() {
      const estDataMapSelect = document.querySelector('#estSidakYear');
      const regDataMapSelect = document.querySelector('#regionalSidakYear');

      ///membuat temp data value 0 untuk chart ketika ganti tanggal tidak ada data
      var list_estate = <?php echo json_encode($list_estate); ?>;
      var list_wilayah = <?php echo json_encode($list_wilayah); ?>;
      var list_month = <?php echo json_encode($list_month); ?>;

      //buat grafik temporary untuk value 0 untuk estate
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
          '#9208FD'
        ],
        stroke: {
          curve: 'smooth'
        },
        xaxis: {
          labels: {
            rotate: -50,
            rotateAlways: true,
          },
          type: '',
          categories: list_estate
        }
      };
      //buat grafik dengan value 0 dengan wilayah
      var will = {
        series: [{
          name: '',
          data: [0, 0, 0]
        }],
        chart: {
          height: 250,
          background: '#E2EAEA',
          type: 'bar'
        },
        plotOptions: {
          bar: {
            // horizontal: false
            distributed: true
          }
        },
        colors: ['#E6F011', '#0F0F0E', '#0068A3'],
        stroke: {
          curve: 'smooth'
        },
        xaxis: {
          type: 'string',
          categories: list_wilayah
        }
      };
      // Options List Month
      var lbMonth = {
        series: [{
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
          '#6fda7e',
          '#2596be',
          '#D04545',
          '#D0bf45',
          '#B3D816',
          '#16d8b9',
          '#1683D8',
          '#0d3df1',
          '#950df1',
          '#F10d85',
          '#2b516b',
          '#339691',
          '#3c9c5a'
        ],
        stroke: {
          curve: 'smooth'
        },
        xaxis: {
          labels: {
            rotate: -50,
            rotateAlways: true,
          },
          type: '',
          categories: list_month
        }
      };


      // Render Chart Month
      var renderChartTphMonth = new ApexCharts(document.querySelector("#bttinggalMonth"), options);
      renderChartTphMonth.render();
      var renderChartKarungMonth = new ApexCharts(document.querySelector("#karungMonth"), options);
      renderChartKarungMonth.render();


      //render chart perwilayah temporary /0 value
      var will_bttMonth = new ApexCharts(document.querySelector("#btt_idMonth"), will);
      will_bttMonth.render();
      var renderChartKarungWilMonth = new ApexCharts(document.querySelector("#karung_idMonth"), will);
      renderChartKarungWilMonth.render();

      // Render Chart Year
      var renderChartTphYear = new ApexCharts(document.querySelector("#bttinggalYear"), lbMonth);
      renderChartTphYear.render();
      var renderChartKarungYear = new ApexCharts(document.querySelector("#karungYear"), lbMonth);
      renderChartKarungYear.render();

      var lokasiKerja = "{{ session('lok') }}";
      // console.log(lokasiKerja);
      if (lokasiKerja == 'Regional II' || lokasiKerja == 'Regional 2') {
        // $('#regionalSidak').val('2');
        $('#regionalSidakMonth').val('2');
        $('#regionalSidakYear').val('2');
        $('#regDataTph').val('2');
      } else if (lokasiKerja == 'Regional III' || lokasiKerja == 'Regional 3') {
        // $('#regionalSidak').val('3');
        $('#regionalSidakMonth').val('3');
        $('#regionalSidakYear').val('3');
        $('#regDataTph').val('3');
      } else if (lokasiKerja == 'Regional IV' || lokasiKerja == 'Regional 4') {
        // $('#regionalSidak').val('4');
        $('#regionalSidakMonth').val('4');
        $('#regionalSidakYear').val('4');
        $('#regDataTph').val('4');
      }
      // getDataTph()
      getDataTphMonth()
      getDataTphYear()
      changeData()
      fetchEstates(regDataMapSelect.value)



      $("#btnShow").click(function() {
        $('#tbody1').empty()
        $('#tbody2').empty()
        $('#tbody3').empty()

        Swal.fire({
          title: 'Loading',
          html: '<span class="loading-text">Mohon Tunggu...</span>',
          allowOutsideClick: false,
          showConfirmButton: false,
          willOpen: () => {
            Swal.showLoading();
          }
        });
        getDataTph()
      });

      $("#btnShowMonth").click(function() {
        $('#tbody1Month').empty()
        $('#tbody2Month').empty()
        $('#tbody3Month').empty()
        $('#plasmaMonth').empty()
        $('#tbodySkorRHMonth').empty()
        // console.log('button clicked');
        Swal.fire({
          title: 'Loading',
          html: '<span class="loading-text">Mohon Tunggu...</span>',
          allowOutsideClick: false,
          showConfirmButton: false,
          willOpen: () => {
            Swal.showLoading();
          }
        });
        getDataTphMonth()
      });


      $("#btnShowYear").click(function() {
        $('#tbody1Year').empty()
        $('#tbody2Year').empty()
        $('#tbody3Year').empty()
        $('#tbodySkorRHYear').empty()

        // console.log('button clicked');
        Swal.fire({
          title: 'Loading',
          html: '<span class="loading-text">Mohon Tunggu...</span>',
          allowOutsideClick: false,
          showConfirmButton: false,
          willOpen: () => {
            Swal.showLoading();
          }
        });
        getDataTphYear()
      });

      $("#showDataTph").click(function() {
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

      $("#showGraphYear").click(function() {
        Swal.fire({
          title: 'Loading',
          html: '<span class="loading-text">Mohon Tunggu...</span>',
          allowOutsideClick: false,
          showConfirmButton: false,
          willOpen: () => {
            Swal.showLoading();
          }
        });
        graphFilterYear()
      });

      setTimeout(function() {
        Swal.fire({
          title: 'Loading',
          html: '<span class="loading-text">Mohon Tunggu...</span>',
          allowOutsideClick: false,
          showConfirmButton: false,
          willOpen: () => {
            Swal.showLoading();
          }
        });
        $("#showGraphYear").click();
      }, 1000);

      regDataMapSelect.addEventListener('change', function() {
        const selectedOptionValue = this.value;
        fetchEstates(selectedOptionValue);
      });

      function fetchEstates(region) {
        var _token = $('input[name="_token"]').val();
        // Fetch the estates for the selected region and update the estate filter
        $.ajax({
          url: "{{ route('changeRegionEst') }}",
          method: "POST",
          data: {
            region: region,
            _token: _token
          },
          success: function(result) {
            estDataMapSelect.innerHTML = '';
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


      function changeData() {
        var regTph = $("#regDataTph").val();
        var dateTph = $("#dateDataTph").val();
        var _token = $('input[name="_token"]').val();
        if ($.fn.DataTable.isDataTable('#newweek1')) {
          $('#newweek1').DataTable().destroy();
        }
        if ($.fn.DataTable.isDataTable('#newweek2')) {
          $('#newweek2').DataTable().destroy();
        }
        if ($.fn.DataTable.isDataTable('#newweek3')) {
          $('#newweek3').DataTable().destroy();
        }
        if ($.fn.DataTable.isDataTable('#newweek4')) {
          $('#newweek4').DataTable().destroy();
        }
        if ($.fn.DataTable.isDataTable('#newweek5')) {
          $('#newweek5').DataTable().destroy();
        }
        $.ajax({
          url: "{{ route('changeDataTph') }}",
          method: "GET",
          cache: false,
          data: {
            _token: _token,
            regional: regTph,
            date: dateTph
          },
          success: function(result) {
            Swal.close();
            var parseResult = JSON.parse(result)



            var datatableweek1 = $('#newweek1').DataTable({
              columns: [{
                  data: 'est'
                },
                {
                  data: 'afd',
                  render: function(data, type, row) {
                    if (row.afd === 'EST') {



                      var linkText = 'Link';
                      var linkUrl = 'BaSidakTPH/' + row.est + '/' + row.start + '/' + row.end + '/' + row.reg;
                      return '<a href="' + linkUrl + '">' + row.afd + '</a>';
                    } else {

                      return row.afd;
                    }
                  }
                },

                {
                  data: 'tph1'
                },
                {
                  data: 'jalan1'
                },
                {
                  data: 'bin1'
                },
                {
                  data: 'karung1'
                },

                {
                  data: 'tot_brd1'
                },
                {
                  data: 'skor_brd1'
                },
                {
                  data: 'buah1'
                },
                {
                  data: 'restan1'
                },
                {
                  data: 'tod_jjg1'
                },
                {
                  data: 'skor_janjang1'
                },


                {
                  data: 'tph2'
                },
                {
                  data: 'jalan2'
                },
                {
                  data: 'bin2'
                },
                {
                  data: 'karung2'
                },

                {
                  data: 'tot_brd2'
                },
                {
                  data: 'skor_brd2'
                },
                {
                  data: 'buah2'
                },
                {
                  data: 'restan2'
                },
                {
                  data: 'tod_jjg2'
                },
                {
                  data: 'skor_janjang2'
                },



                {
                  data: 'tph3'
                },
                {
                  data: 'jalan3'
                },
                {
                  data: 'bin3'
                },
                {
                  data: 'karung3'
                },

                {
                  data: 'tot_brd3'
                },
                {
                  data: 'skor_brd3'
                },
                {
                  data: 'buah3'
                },
                {
                  data: 'restan3'
                },
                {
                  data: 'tod_jjg3'
                },
                {
                  data: 'skor_janjang3'
                },


                {
                  data: 'tph4'
                },
                {
                  data: 'jalan4'
                },
                {
                  data: 'bin4'
                },
                {
                  data: 'karung4'
                },

                {
                  data: 'tot_brd4'
                },
                {
                  data: 'skor_brd4'
                },
                {
                  data: 'buah4'
                },
                {
                  data: 'restan4'
                },
                {
                  data: 'tod_jjg4'
                },
                {
                  data: 'skor_janjang4'
                },


                {
                  data: 'tph5'
                },
                {
                  data: 'jalan5'
                },
                {
                  data: 'bin5'
                },
                {
                  data: 'karung5'
                },

                {
                  data: 'tot_brd5'
                },
                {
                  data: 'skor_brd5'
                },
                {
                  data: 'buah5'
                },
                {
                  data: 'restan5'
                },
                {
                  data: 'tod_jjg5'
                },
                {
                  data: 'skor_janjang5'
                },


                {
                  data: 'tph6'
                },
                {
                  data: 'jalan6'
                },
                {
                  data: 'bin6'
                },
                {
                  data: 'karung6'
                },

                {
                  data: 'tot_brd6'
                },
                {
                  data: 'skor_brd6'
                },
                {
                  data: 'buah6'
                },
                {
                  data: 'restan6'
                },
                {
                  data: 'tod_jjg6'
                },
                {
                  data: 'skor_janjang6'
                },


                {
                  data: 'tph7'
                },
                {
                  data: 'jalan7'
                },
                {
                  data: 'bin7'
                },
                {
                  data: 'karung7'
                },

                {
                  data: 'tot_brd7'
                },
                {
                  data: 'skor_brd7'
                },
                {
                  data: 'buah7'
                },
                {
                  data: 'restan7'
                },
                {
                  data: 'tod_jjg7'
                },
                {
                  data: 'skor_janjang7'
                },

                {
                  data: 'tph8'
                },
                {
                  data: 'jalan8'
                },
                {
                  data: 'bin8'
                },
                {
                  data: 'karung8'
                },

                {
                  data: 'tot_brd8'
                },
                {
                  data: 'skor_brd8'
                },
                {
                  data: 'buah8'
                },
                {
                  data: 'restan8'
                },
                {
                  data: 'tod_jjg8'
                },
                {
                  data: 'skor_janjang8'
                },
                {
                  data: 'total_score',
                  render: function(data, type, row) {
                    var ispeksi = row.inspek; // Assuming 'inspek' is a property in the 'row' object
                    var totalScoreValue = row.total_score; // Assuming 'total_score' is a property in the 'row' object
                    var kategori = '-';

                    if ('inspek' in row) {
                      if (ispeksi === 'ada') {
                        kategori = (totalScoreValue < 0) ? 0 : totalScoreValue;
                      } else if (totalScoreValue === 'kosong') {
                        kategori = '-';
                      }
                    } else {
                      if (totalScoreValue < 0) {
                        kategori = 0;
                      } else {
                        kategori = totalScoreValue;
                      }
                    }

                    return kategori;
                  }
                },
                {
                  data: 'total_score',
                  render: function(data, type, row) {
                    var totalScoreValue = parseFloat(data);
                    var kategori = '';
                    var ispeksi = row.inspek;
                    if ('inspek' in row) {
                      if (ispeksi === 'ada') {
                        if (totalScoreValue >= 95) {
                          kategori = 'EXCELLENT';
                        } else if (totalScoreValue >= 85) {
                          kategori = 'GOOD';
                        } else if (totalScoreValue >= 75) {
                          kategori = 'SATISFACTORY';
                        } else if (totalScoreValue >= 65) {
                          kategori = 'FAIR';
                        } else if (totalScoreValue < 65) {
                          kategori = 'POOR';
                        } else {
                          kategori = '-';
                        }
                      } else if (ispeksi === 'kosong') {
                        kategori = '-';
                      }
                    } else {
                      if (totalScoreValue >= 95) {
                        kategori = 'EXCELLENT';
                      } else if (totalScoreValue >= 85) {
                        kategori = 'GOOD';
                      } else if (totalScoreValue >= 75) {
                        kategori = 'SATISFACTORY';
                      } else if (totalScoreValue >= 65) {
                        kategori = 'FAIR';
                      } else if (totalScoreValue < 65) {
                        kategori = 'POOR';
                      } else {
                        kategori = '-';
                      }
                    }
                    return kategori;
                  }
                }
              ],
              "createdRow": function(row, data, dataIndex) {
                // Assuming 'data' contains the data for each row
                var afdValue = data['afd']; // Replace with the correct column name or data source
                var totalScoreValue = parseFloat(data['total_score']); // Convert total_score to a number

                // Check if the 'afd' value is 'EST' and set background color to blue
                if (afdValue === 'EST') {
                  $(row).css('background-color', '#b0d48c');
                }


                if (totalScoreValue <= 65) {
                  $(row).find('td:eq(82)').css('background-color', 'red');
                  $(row).find('td:eq(83)').css('background-color', 'red');
                }

                if (totalScoreValue >= 65 && totalScoreValue <= 75) {
                  $(row).find('td:eq(82)').css('background-color', '#ffc404');
                  $(row).find('td:eq(83)').css('background-color', '#ffc404');
                }

                if (totalScoreValue >= 75 && totalScoreValue <= 85) {
                  $(row).find('td:eq(82)').css('background-color', '#fffc04');
                  $(row).find('td:eq(83)').css('background-color', '#fffc04');
                }

                if (totalScoreValue >= 85 && totalScoreValue <= 95) {
                  $(row).find('td:eq(82)').css('background-color', '#08b454');
                  $(row).find('td:eq(83)').css('background-color', '#08b454');
                }

                if (totalScoreValue >= 95) {
                  $(row).find('td:eq(82)').css('background-color', '#609cd4');
                  $(row).find('td:eq(83)').css('background-color', '#609cd4');
                }

              },
              fixedColumns: true,
              paging: false,
              scrollCollapse: true,
              scrollX: true,
              scrollY: 500
            });
            datatableweek1.clear().rows.add(parseResult['week1']).draw();

            var datatableweek2 = $('#newweek2').DataTable({
              columns: [{
                  data: 'est'
                },
                {
                  data: 'afd',
                  render: function(data, type, row) {
                    if (row.afd === 'EST') {



                      var linkText = 'Link';
                      var linkUrl = 'BaSidakTPH/' + row.est + '/' + row.start + '/' + row.end + '/' + row.reg;
                      return '<a href="' + linkUrl + '">' + row.afd + '</a>';
                    } else {

                      return row.afd;
                    }
                  }
                },

                {
                  data: 'tph1'
                },
                {
                  data: 'jalan1'
                },
                {
                  data: 'bin1'
                },
                {
                  data: 'karung1'
                },

                {
                  data: 'tot_brd1'
                },
                {
                  data: 'skor_brd1'
                },
                {
                  data: 'buah1'
                },
                {
                  data: 'restan1'
                },
                {
                  data: 'tod_jjg1'
                },
                {
                  data: 'skor_janjang1'
                },


                {
                  data: 'tph2'
                },
                {
                  data: 'jalan2'
                },
                {
                  data: 'bin2'
                },
                {
                  data: 'karung2'
                },

                {
                  data: 'tot_brd2'
                },
                {
                  data: 'skor_brd2'
                },
                {
                  data: 'buah2'
                },
                {
                  data: 'restan2'
                },
                {
                  data: 'tod_jjg2'
                },
                {
                  data: 'skor_janjang2'
                },



                {
                  data: 'tph3'
                },
                {
                  data: 'jalan3'
                },
                {
                  data: 'bin3'
                },
                {
                  data: 'karung3'
                },

                {
                  data: 'tot_brd3'
                },
                {
                  data: 'skor_brd3'
                },
                {
                  data: 'buah3'
                },
                {
                  data: 'restan3'
                },
                {
                  data: 'tod_jjg3'
                },
                {
                  data: 'skor_janjang3'
                },


                {
                  data: 'tph4'
                },
                {
                  data: 'jalan4'
                },
                {
                  data: 'bin4'
                },
                {
                  data: 'karung4'
                },

                {
                  data: 'tot_brd4'
                },
                {
                  data: 'skor_brd4'
                },
                {
                  data: 'buah4'
                },
                {
                  data: 'restan4'
                },
                {
                  data: 'tod_jjg4'
                },
                {
                  data: 'skor_janjang4'
                },


                {
                  data: 'tph5'
                },
                {
                  data: 'jalan5'
                },
                {
                  data: 'bin5'
                },
                {
                  data: 'karung5'
                },

                {
                  data: 'tot_brd5'
                },
                {
                  data: 'skor_brd5'
                },
                {
                  data: 'buah5'
                },
                {
                  data: 'restan5'
                },
                {
                  data: 'tod_jjg5'
                },
                {
                  data: 'skor_janjang5'
                },


                {
                  data: 'tph6'
                },
                {
                  data: 'jalan6'
                },
                {
                  data: 'bin6'
                },
                {
                  data: 'karung6'
                },

                {
                  data: 'tot_brd6'
                },
                {
                  data: 'skor_brd6'
                },
                {
                  data: 'buah6'
                },
                {
                  data: 'restan6'
                },
                {
                  data: 'tod_jjg6'
                },
                {
                  data: 'skor_janjang6'
                },


                {
                  data: 'tph7'
                },
                {
                  data: 'jalan7'
                },
                {
                  data: 'bin7'
                },
                {
                  data: 'karung7'
                },

                {
                  data: 'tot_brd7'
                },
                {
                  data: 'skor_brd7'
                },
                {
                  data: 'buah7'
                },
                {
                  data: 'restan7'
                },
                {
                  data: 'tod_jjg7'
                },
                {
                  data: 'skor_janjang7'
                },

                {
                  data: 'tph8'
                },
                {
                  data: 'jalan8'
                },
                {
                  data: 'bin8'
                },
                {
                  data: 'karung8'
                },

                {
                  data: 'tot_brd8'
                },
                {
                  data: 'skor_brd8'
                },
                {
                  data: 'buah8'
                },
                {
                  data: 'restan8'
                },
                {
                  data: 'tod_jjg8'
                },
                {
                  data: 'skor_janjang8'
                },

                {
                  data: 'total_score',
                  render: function(data, type, row) {
                    var ispeksi = row.inspek; // Assuming 'inspek' is a property in the 'row' object
                    var totalScoreValue = row.total_score; // Assuming 'total_score' is a property in the 'row' object
                    var kategori = '-';
                    if ('inspek' in row) {
                      if (ispeksi === 'ada') {
                        kategori = (totalScoreValue < 0) ? 0 : totalScoreValue;
                      } else if (totalScoreValue === 'kosong') {
                        kategori = '-';
                      }
                    } else {
                      if (totalScoreValue < 0) {
                        kategori = 0;
                      } else {
                        kategori = totalScoreValue;
                      }
                    }

                    return kategori;
                  }
                },
                {
                  data: 'total_score',
                  render: function(data, type, row) {
                    var totalScoreValue = parseFloat(data);
                    var kategori = '';
                    var ispeksi = row.inspek;
                    if ('inspek' in row) {
                      if (ispeksi === 'ada') {
                        if (totalScoreValue >= 95) {
                          kategori = 'EXCELLENT';
                        } else if (totalScoreValue >= 85) {
                          kategori = 'GOOD';
                        } else if (totalScoreValue >= 75) {
                          kategori = 'SATISFACTORY';
                        } else if (totalScoreValue >= 65) {
                          kategori = 'FAIR';
                        } else if (totalScoreValue < 65) {
                          kategori = 'POOR';
                        } else {
                          kategori = '-';
                        }
                      } else if (ispeksi === 'kosong') {
                        kategori = '-';
                      }
                    } else {
                      if (totalScoreValue >= 95) {
                        kategori = 'EXCELLENT';
                      } else if (totalScoreValue >= 85) {
                        kategori = 'GOOD';
                      } else if (totalScoreValue >= 75) {
                        kategori = 'SATISFACTORY';
                      } else if (totalScoreValue >= 65) {
                        kategori = 'FAIR';
                      } else if (totalScoreValue < 65) {
                        kategori = 'POOR';
                      } else {
                        kategori = '-';
                      }
                    }
                    return kategori;
                  }
                }
              ],
              "createdRow": function(row, data, dataIndex) {
                // Assuming 'data' contains the data for each row
                var afdValue = data['afd']; // Replace with the correct column name or data source
                var totalScoreValue = parseFloat(data['total_score']); // Convert total_score to a number

                // Check if the 'afd' value is 'EST' and set background color to blue
                if (afdValue === 'EST') {
                  $(row).css('background-color', '#b0d48c');
                }


                if (totalScoreValue <= 65) {
                  $(row).find('td:eq(82)').css('background-color', 'red');
                  $(row).find('td:eq(83)').css('background-color', 'red');
                }

                if (totalScoreValue >= 65 && totalScoreValue <= 75) {
                  $(row).find('td:eq(82)').css('background-color', '#ffc404');
                  $(row).find('td:eq(83)').css('background-color', '#ffc404');
                }

                if (totalScoreValue >= 75 && totalScoreValue <= 85) {
                  $(row).find('td:eq(82)').css('background-color', '#fffc04');
                  $(row).find('td:eq(83)').css('background-color', '#fffc04');
                }

                if (totalScoreValue >= 85 && totalScoreValue <= 95) {
                  $(row).find('td:eq(82)').css('background-color', '#08b454');
                  $(row).find('td:eq(83)').css('background-color', '#08b454');
                }

                if (totalScoreValue >= 95) {
                  $(row).find('td:eq(82)').css('background-color', '#609cd4');
                  $(row).find('td:eq(83)').css('background-color', '#609cd4');
                }
              },
              fixedColumns: true,
              paging: false,
              scrollCollapse: true,
              scrollX: true,
              scrollY: 500
            });
            datatableweek2.clear().rows.add(parseResult['week2']).draw();


            var datatableweek3 = $('#newweek3').DataTable({

              columns: [{
                  data: 'est'
                },
                {
                  data: 'afd',
                  render: function(data, type, row) {
                    if (row.afd === 'EST') {



                      var linkText = 'Link';
                      var linkUrl = 'BaSidakTPH/' + row.est + '/' + row.start + '/' + row.end + '/' + row.reg;
                      return '<a href="' + linkUrl + '">' + row.afd + '</a>';
                    } else {

                      return row.afd;
                    }
                  }
                },
                {
                  data: 'tph1'
                },
                {
                  data: 'jalan1'
                },
                {
                  data: 'bin1'
                },
                {
                  data: 'karung1'
                },

                {
                  data: 'tot_brd1'
                },
                {
                  data: 'skor_brd1'
                },
                {
                  data: 'buah1'
                },
                {
                  data: 'restan1'
                },
                {
                  data: 'tod_jjg1'
                },
                {
                  data: 'skor_janjang1'
                },


                {
                  data: 'tph2'
                },
                {
                  data: 'jalan2'
                },
                {
                  data: 'bin2'
                },
                {
                  data: 'karung2'
                },

                {
                  data: 'tot_brd2'
                },
                {
                  data: 'skor_brd2'
                },
                {
                  data: 'buah2'
                },
                {
                  data: 'restan2'
                },
                {
                  data: 'tod_jjg2'
                },
                {
                  data: 'skor_janjang2'
                },



                {
                  data: 'tph3'
                },
                {
                  data: 'jalan3'
                },
                {
                  data: 'bin3'
                },
                {
                  data: 'karung3'
                },

                {
                  data: 'tot_brd3'
                },
                {
                  data: 'skor_brd3'
                },
                {
                  data: 'buah3'
                },
                {
                  data: 'restan3'
                },
                {
                  data: 'tod_jjg3'
                },
                {
                  data: 'skor_janjang3'
                },


                {
                  data: 'tph4'
                },
                {
                  data: 'jalan4'
                },
                {
                  data: 'bin4'
                },
                {
                  data: 'karung4'
                },

                {
                  data: 'tot_brd4'
                },
                {
                  data: 'skor_brd4'
                },
                {
                  data: 'buah4'
                },
                {
                  data: 'restan4'
                },
                {
                  data: 'tod_jjg4'
                },
                {
                  data: 'skor_janjang4'
                },


                {
                  data: 'tph5'
                },
                {
                  data: 'jalan5'
                },
                {
                  data: 'bin5'
                },
                {
                  data: 'karung5'
                },

                {
                  data: 'tot_brd5'
                },
                {
                  data: 'skor_brd5'
                },
                {
                  data: 'buah5'
                },
                {
                  data: 'restan5'
                },
                {
                  data: 'tod_jjg5'
                },
                {
                  data: 'skor_janjang5'
                },


                {
                  data: 'tph6'
                },
                {
                  data: 'jalan6'
                },
                {
                  data: 'bin6'
                },
                {
                  data: 'karung6'
                },

                {
                  data: 'tot_brd6'
                },
                {
                  data: 'skor_brd6'
                },
                {
                  data: 'buah6'
                },
                {
                  data: 'restan6'
                },
                {
                  data: 'tod_jjg6'
                },
                {
                  data: 'skor_janjang6'
                },


                {
                  data: 'tph7'
                },
                {
                  data: 'jalan7'
                },
                {
                  data: 'bin7'
                },
                {
                  data: 'karung7'
                },

                {
                  data: 'tot_brd7'
                },
                {
                  data: 'skor_brd7'
                },
                {
                  data: 'buah7'
                },
                {
                  data: 'restan7'
                },
                {
                  data: 'tod_jjg7'
                },
                {
                  data: 'skor_janjang7'
                },

                {
                  data: 'tph8'
                },
                {
                  data: 'jalan8'
                },
                {
                  data: 'bin8'
                },
                {
                  data: 'karung8'
                },

                {
                  data: 'tot_brd8'
                },
                {
                  data: 'skor_brd8'
                },
                {
                  data: 'buah8'
                },
                {
                  data: 'restan8'
                },
                {
                  data: 'tod_jjg8'
                },
                {
                  data: 'skor_janjang8'
                },

                {
                  data: 'total_score',
                  render: function(data, type, row) {
                    var ispeksi = row.inspek; // Assuming 'inspek' is a property in the 'row' object
                    var totalScoreValue = row.total_score; // Assuming 'total_score' is a property in the 'row' object
                    var kategori = '-';
                    if ('inspek' in row) {
                      if (ispeksi === 'ada') {
                        kategori = (totalScoreValue < 0) ? 0 : totalScoreValue;
                      } else if (totalScoreValue === 'kosong') {
                        kategori = '-';
                      }
                    } else {
                      if (totalScoreValue < 0) {
                        kategori = 0;
                      } else {
                        kategori = totalScoreValue;
                      }
                    }

                    return kategori;
                  }
                },
                {
                  data: 'total_score',
                  render: function(data, type, row) {
                    var totalScoreValue = parseFloat(data);
                    var kategori = '';
                    var ispeksi = row.inspek;
                    if ('inspek' in row) {
                      if (ispeksi === 'ada') {
                        if (totalScoreValue >= 95) {
                          kategori = 'EXCELLENT';
                        } else if (totalScoreValue >= 85) {
                          kategori = 'GOOD';
                        } else if (totalScoreValue >= 75) {
                          kategori = 'SATISFACTORY';
                        } else if (totalScoreValue >= 65) {
                          kategori = 'FAIR';
                        } else if (totalScoreValue < 65) {
                          kategori = 'POOR';
                        } else {
                          kategori = '-';
                        }
                      } else if (ispeksi === 'kosong') {
                        kategori = '-';
                      }
                    } else {
                      if (totalScoreValue >= 95) {
                        kategori = 'EXCELLENT';
                      } else if (totalScoreValue >= 85) {
                        kategori = 'GOOD';
                      } else if (totalScoreValue >= 75) {
                        kategori = 'SATISFACTORY';
                      } else if (totalScoreValue >= 65) {
                        kategori = 'FAIR';
                      } else if (totalScoreValue < 65) {
                        kategori = 'POOR';
                      } else {
                        kategori = '-';
                      }
                    }
                    return kategori;
                  }
                }

              ],
              "createdRow": function(row, data, dataIndex) {
                // Assuming 'data' contains the data for each row
                var afdValue = data['afd']; // Replace with the correct column name or data source
                var totalScoreValue = parseFloat(data['total_score']); // Convert total_score to a number

                // Check if the 'afd' value is 'EST' and set background color to blue
                if (afdValue === 'EST') {
                  $(row).css('background-color', '#b0d48c');
                }


                if (totalScoreValue <= 65) {
                  $(row).find('td:eq(82)').css('background-color', 'red');
                  $(row).find('td:eq(83)').css('background-color', 'red');
                }

                if (totalScoreValue >= 65 && totalScoreValue <= 75) {
                  $(row).find('td:eq(82)').css('background-color', '#ffc404');
                  $(row).find('td:eq(83)').css('background-color', '#ffc404');
                }

                if (totalScoreValue >= 75 && totalScoreValue <= 85) {
                  $(row).find('td:eq(82)').css('background-color', '#fffc04');
                  $(row).find('td:eq(83)').css('background-color', '#fffc04');
                }

                if (totalScoreValue >= 85 && totalScoreValue <= 95) {
                  $(row).find('td:eq(82)').css('background-color', '#08b454');
                  $(row).find('td:eq(83)').css('background-color', '#08b454');
                }

                if (totalScoreValue >= 95) {
                  $(row).find('td:eq(82)').css('background-color', '#609cd4');
                  $(row).find('td:eq(83)').css('background-color', '#609cd4');
                }
              },
              fixedColumns: true,
              paging: false,
              scrollCollapse: true,
              scrollX: true,
              scrollY: 500
            });
            datatableweek3.clear().rows.add(parseResult['week3']).draw();

            var datatableweek4 = $('#newweek4').DataTable({

              columns: [{
                  data: 'est'
                },
                {
                  data: 'afd',
                  render: function(data, type, row) {
                    if (row.afd === 'EST') {



                      var linkText = 'Link';
                      var linkUrl = 'BaSidakTPH/' + row.est + '/' + row.start + '/' + row.end + '/' + row.reg;
                      return '<a href="' + linkUrl + '">' + row.afd + '</a>';
                    } else {

                      return row.afd;
                    }
                  }
                },
                {
                  data: 'tph1'
                },
                {
                  data: 'jalan1'
                },
                {
                  data: 'bin1'
                },
                {
                  data: 'karung1'
                },

                {
                  data: 'tot_brd1'
                },
                {
                  data: 'skor_brd1'
                },
                {
                  data: 'buah1'
                },
                {
                  data: 'restan1'
                },
                {
                  data: 'tod_jjg1'
                },
                {
                  data: 'skor_janjang1'
                },


                {
                  data: 'tph2'
                },
                {
                  data: 'jalan2'
                },
                {
                  data: 'bin2'
                },
                {
                  data: 'karung2'
                },

                {
                  data: 'tot_brd2'
                },
                {
                  data: 'skor_brd2'
                },
                {
                  data: 'buah2'
                },
                {
                  data: 'restan2'
                },
                {
                  data: 'tod_jjg2'
                },
                {
                  data: 'skor_janjang2'
                },



                {
                  data: 'tph3'
                },
                {
                  data: 'jalan3'
                },
                {
                  data: 'bin3'
                },
                {
                  data: 'karung3'
                },

                {
                  data: 'tot_brd3'
                },
                {
                  data: 'skor_brd3'
                },
                {
                  data: 'buah3'
                },
                {
                  data: 'restan3'
                },
                {
                  data: 'tod_jjg3'
                },
                {
                  data: 'skor_janjang3'
                },


                {
                  data: 'tph4'
                },
                {
                  data: 'jalan4'
                },
                {
                  data: 'bin4'
                },
                {
                  data: 'karung4'
                },

                {
                  data: 'tot_brd4'
                },
                {
                  data: 'skor_brd4'
                },
                {
                  data: 'buah4'
                },
                {
                  data: 'restan4'
                },
                {
                  data: 'tod_jjg4'
                },
                {
                  data: 'skor_janjang4'
                },


                {
                  data: 'tph5'
                },
                {
                  data: 'jalan5'
                },
                {
                  data: 'bin5'
                },
                {
                  data: 'karung5'
                },

                {
                  data: 'tot_brd5'
                },
                {
                  data: 'skor_brd5'
                },
                {
                  data: 'buah5'
                },
                {
                  data: 'restan5'
                },
                {
                  data: 'tod_jjg5'
                },
                {
                  data: 'skor_janjang5'
                },


                {
                  data: 'tph6'
                },
                {
                  data: 'jalan6'
                },
                {
                  data: 'bin6'
                },
                {
                  data: 'karung6'
                },

                {
                  data: 'tot_brd6'
                },
                {
                  data: 'skor_brd6'
                },
                {
                  data: 'buah6'
                },
                {
                  data: 'restan6'
                },
                {
                  data: 'tod_jjg6'
                },
                {
                  data: 'skor_janjang6'
                },


                {
                  data: 'tph7'
                },
                {
                  data: 'jalan7'
                },
                {
                  data: 'bin7'
                },
                {
                  data: 'karung7'
                },

                {
                  data: 'tot_brd7'
                },
                {
                  data: 'skor_brd7'
                },
                {
                  data: 'buah7'
                },
                {
                  data: 'restan7'
                },
                {
                  data: 'tod_jjg7'
                },
                {
                  data: 'skor_janjang7'
                },

                {
                  data: 'tph8'
                },
                {
                  data: 'jalan8'
                },
                {
                  data: 'bin8'
                },
                {
                  data: 'karung8'
                },

                {
                  data: 'tot_brd8'
                },
                {
                  data: 'skor_brd8'
                },
                {
                  data: 'buah8'
                },
                {
                  data: 'restan8'
                },
                {
                  data: 'tod_jjg8'
                },
                {
                  data: 'skor_janjang8'
                },
                {
                  data: 'total_score',
                  render: function(data, type, row) {
                    var ispeksi = row.inspek; // Assuming 'inspek' is a property in the 'row' object
                    var totalScoreValue = row.total_score; // Assuming 'total_score' is a property in the 'row' object
                    var kategori = '-';

                    if ('inspek' in row) {
                      if (ispeksi === 'ada') {
                        kategori = (totalScoreValue < 0) ? 0 : totalScoreValue;
                      } else if (totalScoreValue === 'kosong') {
                        kategori = '-';
                      }
                    } else {
                      if (totalScoreValue < 0) {
                        kategori = 0;
                      } else {
                        kategori = totalScoreValue;
                      }
                    }

                    return kategori;
                  }
                },
                {
                  data: 'total_score',
                  render: function(data, type, row) {
                    var totalScoreValue = parseFloat(data);
                    var kategori = '';
                    var ispeksi = row.inspek;
                    if ('inspek' in row) {
                      if (ispeksi === 'ada') {
                        if (totalScoreValue >= 95) {
                          kategori = 'EXCELLENT';
                        } else if (totalScoreValue >= 85) {
                          kategori = 'GOOD';
                        } else if (totalScoreValue >= 75) {
                          kategori = 'SATISFACTORY';
                        } else if (totalScoreValue >= 65) {
                          kategori = 'FAIR';
                        } else if (totalScoreValue < 65) {
                          kategori = 'POOR';
                        } else {
                          kategori = '-';
                        }
                      } else if (ispeksi === 'kosong') {
                        kategori = '-';
                      }
                    } else {
                      if (totalScoreValue >= 95) {
                        kategori = 'EXCELLENT';
                      } else if (totalScoreValue >= 85) {
                        kategori = 'GOOD';
                      } else if (totalScoreValue >= 75) {
                        kategori = 'SATISFACTORY';
                      } else if (totalScoreValue >= 65) {
                        kategori = 'FAIR';
                      } else if (totalScoreValue < 65) {
                        kategori = 'POOR';
                      } else {
                        kategori = '-';
                      }
                    }
                    return kategori;
                  }
                }

              ],
              "createdRow": function(row, data, dataIndex) {
                // Assuming 'data' contains the data for each row
                var afdValue = data['afd']; // Replace with the correct column name or data source
                var totalScoreValue = parseFloat(data['total_score']); // Convert total_score to a number

                // Check if the 'afd' value is 'EST' and set background color to blue
                if (afdValue === 'EST') {
                  $(row).css('background-color', '#b0d48c');
                }

                if (totalScoreValue <= 65) {
                  $(row).find('td:eq(82)').css('background-color', 'red');
                  $(row).find('td:eq(83)').css('background-color', 'red');
                }

                if (totalScoreValue >= 65 && totalScoreValue <= 75) {
                  $(row).find('td:eq(82)').css('background-color', '#ffc404');
                  $(row).find('td:eq(83)').css('background-color', '#ffc404');
                }

                if (totalScoreValue >= 75 && totalScoreValue <= 85) {
                  $(row).find('td:eq(82)').css('background-color', '#fffc04');
                  $(row).find('td:eq(83)').css('background-color', '#fffc04');
                }

                if (totalScoreValue >= 85 && totalScoreValue <= 95) {
                  $(row).find('td:eq(82)').css('background-color', '#08b454');
                  $(row).find('td:eq(83)').css('background-color', '#08b454');
                }

                if (totalScoreValue >= 95) {
                  $(row).find('td:eq(82)').css('background-color', '#609cd4');
                  $(row).find('td:eq(83)').css('background-color', '#609cd4');
                }
              },
              fixedColumns: true,
              paging: false,
              scrollCollapse: true,
              scrollX: true,
              scrollY: 500
            });
            datatableweek4.clear().rows.add(parseResult['week4']).draw();

            var datatableweek5 = $('#newweek5').DataTable({

              columns: [{
                  data: 'est'
                },
                {
                  data: 'afd',
                  render: function(data, type, row) {
                    if (row.afd === 'EST') {



                      var linkText = 'Link';
                      var linkUrl = 'BaSidakTPH/' + row.est + '/' + row.start + '/' + row.end + '/' + row.reg;
                      return '<a href="' + linkUrl + '">' + row.afd + '</a>';
                    } else {

                      return row.afd;
                    }
                  }
                },
                {
                  data: 'tph1'
                },
                {
                  data: 'jalan1'
                },
                {
                  data: 'bin1'
                },
                {
                  data: 'karung1'
                },

                {
                  data: 'tot_brd1'
                },
                {
                  data: 'skor_brd1'
                },
                {
                  data: 'buah1'
                },
                {
                  data: 'restan1'
                },
                {
                  data: 'tod_jjg1'
                },
                {
                  data: 'skor_janjang1'
                },


                {
                  data: 'tph2'
                },
                {
                  data: 'jalan2'
                },
                {
                  data: 'bin2'
                },
                {
                  data: 'karung2'
                },

                {
                  data: 'tot_brd2'
                },
                {
                  data: 'skor_brd2'
                },
                {
                  data: 'buah2'
                },
                {
                  data: 'restan2'
                },
                {
                  data: 'tod_jjg2'
                },
                {
                  data: 'skor_janjang2'
                },



                {
                  data: 'tph3'
                },
                {
                  data: 'jalan3'
                },
                {
                  data: 'bin3'
                },
                {
                  data: 'karung3'
                },

                {
                  data: 'tot_brd3'
                },
                {
                  data: 'skor_brd3'
                },
                {
                  data: 'buah3'
                },
                {
                  data: 'restan3'
                },
                {
                  data: 'tod_jjg3'
                },
                {
                  data: 'skor_janjang3'
                },


                {
                  data: 'tph4'
                },
                {
                  data: 'jalan4'
                },
                {
                  data: 'bin4'
                },
                {
                  data: 'karung4'
                },

                {
                  data: 'tot_brd4'
                },
                {
                  data: 'skor_brd4'
                },
                {
                  data: 'buah4'
                },
                {
                  data: 'restan4'
                },
                {
                  data: 'tod_jjg4'
                },
                {
                  data: 'skor_janjang4'
                },


                {
                  data: 'tph5'
                },
                {
                  data: 'jalan5'
                },
                {
                  data: 'bin5'
                },
                {
                  data: 'karung5'
                },

                {
                  data: 'tot_brd5'
                },
                {
                  data: 'skor_brd5'
                },
                {
                  data: 'buah5'
                },
                {
                  data: 'restan5'
                },
                {
                  data: 'tod_jjg5'
                },
                {
                  data: 'skor_janjang5'
                },


                {
                  data: 'tph6'
                },
                {
                  data: 'jalan6'
                },
                {
                  data: 'bin6'
                },
                {
                  data: 'karung6'
                },

                {
                  data: 'tot_brd6'
                },
                {
                  data: 'skor_brd6'
                },
                {
                  data: 'buah6'
                },
                {
                  data: 'restan6'
                },
                {
                  data: 'tod_jjg6'
                },
                {
                  data: 'skor_janjang6'
                },


                {
                  data: 'tph7'
                },
                {
                  data: 'jalan7'
                },
                {
                  data: 'bin7'
                },
                {
                  data: 'karung7'
                },

                {
                  data: 'tot_brd7'
                },
                {
                  data: 'skor_brd7'
                },
                {
                  data: 'buah7'
                },
                {
                  data: 'restan7'
                },
                {
                  data: 'tod_jjg7'
                },
                {
                  data: 'skor_janjang7'
                },

                {
                  data: 'tph8'
                },
                {
                  data: 'jalan8'
                },
                {
                  data: 'bin8'
                },
                {
                  data: 'karung8'
                },

                {
                  data: 'tot_brd8'
                },
                {
                  data: 'skor_brd8'
                },
                {
                  data: 'buah8'
                },
                {
                  data: 'restan8'
                },
                {
                  data: 'tod_jjg8'
                },
                {
                  data: 'skor_janjang8'
                },

                {
                  data: 'total_score',
                  render: function(data, type, row) {
                    var ispeksi = row.inspek; // Assuming 'inspek' is a property in the 'row' object
                    var totalScoreValue = row.total_score; // Assuming 'total_score' is a property in the 'row' object
                    var kategori = '-';
                    if ('inspek' in row) {
                      if (ispeksi === 'ada') {
                        kategori = (totalScoreValue < 0) ? 0 : totalScoreValue;
                      } else if (totalScoreValue === 'kosong') {
                        kategori = '-';
                      }
                    } else {
                      if (totalScoreValue < 0) {
                        kategori = 0;
                      } else {
                        kategori = totalScoreValue;
                      }
                    }

                    return kategori;
                  }
                },
                {
                  data: 'total_score',
                  render: function(data, type, row) {
                    var totalScoreValue = parseFloat(data);
                    var kategori = '';
                    var ispeksi = row.inspek;
                    if ('inspek' in row) {
                      if (ispeksi === 'ada') {
                        if (totalScoreValue >= 95) {
                          kategori = 'EXCELLENT';
                        } else if (totalScoreValue >= 85) {
                          kategori = 'GOOD';
                        } else if (totalScoreValue >= 75) {
                          kategori = 'SATISFACTORY';
                        } else if (totalScoreValue >= 65) {
                          kategori = 'FAIR';
                        } else if (totalScoreValue < 65) {
                          kategori = 'POOR';
                        } else {
                          kategori = '-';
                        }
                      } else if (ispeksi === 'kosong') {
                        kategori = '-';
                      }
                    } else {
                      if (totalScoreValue >= 95) {
                        kategori = 'EXCELLENT';
                      } else if (totalScoreValue >= 85) {
                        kategori = 'GOOD';
                      } else if (totalScoreValue >= 75) {
                        kategori = 'SATISFACTORY';
                      } else if (totalScoreValue >= 65) {
                        kategori = 'FAIR';
                      } else if (totalScoreValue < 65) {
                        kategori = 'POOR';
                      } else {
                        kategori = '-';
                      }
                    }
                    return kategori;
                  }
                }

              ],
              "createdRow": function(row, data, dataIndex) {
                // Assuming 'data' contains the data for each row
                var afdValue = data['afd']; // Replace with the correct column name or data source
                var totalScoreValue = parseFloat(data['total_score']); // Convert total_score to a number

                // Check if the 'afd' value is 'EST' and set background color to blue
                if (afdValue === 'EST') {
                  $(row).css('background-color', '#b0d48c');
                }
                if (totalScoreValue == '-') {
                  $(row).find('td:eq(82)').css('background-color', 'white');
                  $(row).find('td:eq(83)').css('background-color', 'white');
                }


                if (totalScoreValue <= 65) {
                  $(row).find('td:eq(82)').css('background-color', 'red');
                  $(row).find('td:eq(83)').css('background-color', 'red');
                }

                if (totalScoreValue >= 65 && totalScoreValue <= 75) {
                  $(row).find('td:eq(82)').css('background-color', '#ffc404');
                  $(row).find('td:eq(83)').css('background-color', '#ffc404');
                }

                if (totalScoreValue >= 75 && totalScoreValue <= 85) {
                  $(row).find('td:eq(82)').css('background-color', '#fffc04');
                  $(row).find('td:eq(83)').css('background-color', '#fffc04');
                }

                if (totalScoreValue >= 85 && totalScoreValue <= 95) {
                  $(row).find('td:eq(82)').css('background-color', '#08b454');
                  $(row).find('td:eq(83)').css('background-color', '#08b454');
                }

                if (totalScoreValue >= 95) {
                  $(row).find('td:eq(82)').css('background-color', '#609cd4');
                  $(row).find('td:eq(83)').css('background-color', '#609cd4');
                }
              },
              fixedColumns: true,
              paging: false,
              scrollCollapse: true,
              scrollX: true,
              scrollY: 500
            });
            datatableweek5.clear().rows.add(parseResult['week5']).draw();
          }
        });
      }



      function changeClassMonth() {
        var regSidak = document.getElementById('regionalSidakMonth').value
        const element1 = document.getElementById('table1Month');
        const element2 = document.getElementById('classTwoMonth');
        const element3 = document.getElementById('classThreeMonth');
        const element4 = document.getElementById('classFourMonth');
        const thElement1 = document.getElementById('thWilOneMonth');
        const thElement2 = document.getElementById('thWilTwoMonth');
        const thElement3 = document.getElementById('thWilThreeMonth');
        const thElement4 = document.getElementById('thPlasmamonth');
        if (regSidak == '3') {
          element1.style.display = "";
          element2.style.display = "";
          element3.style.display = "none";
          element4.style.display = "none";
          thElement1.textContent = 'WILAYAH VII';
          thElement2.textContent = 'WILAYAH VIII';
          thElement4.textContent = 'Plasma3';
          thElement1.classList.add("text-center");
          thElement2.classList.add("text-center");
          thElement3.classList.add("text-center");
          thElement4.classList.add("text-center");
          element1.classList.remove("col-md-6", "col-lg-3", "col-lg-6");
          element1.classList.add("col-md-6", "col-lg-6");
          element2.classList.remove("col-md-6", "col-lg-3", "col-lg-6");
          element2.classList.add("col-md-6", "col-lg-6");

        } else if (regSidak === '1') {
          element1.style.display = "";
          element2.style.display = "";
          element3.style.display = "";
          element4.style.display = "none";
          thElement1.textContent = 'WILAYAH I';
          thElement2.textContent = 'WILAYAH II';
          thElement3.textContent = 'WILAYAH III';
          thElement4.textContent = 'Plasma1';
          thElement1.classList.add("text-center");
          thElement2.classList.add("text-center");
          thElement3.classList.add("text-center");
          thElement4.classList.add("text-center");
          element1.classList.remove("col-md-6", "col-lg-4", "col-lg-6");
          element1.classList.add("col-md-4", "col-lg-4");
          element2.classList.remove("col-md-6", "col-lg-4", "col-lg-6");
          element2.classList.add("col-md-4", "col-lg-4");
          element3.classList.remove("col-md-6", "col-lg-4");
          element3.classList.add("col-md-4", "col-lg-4");

        } else if (regSidak === '2') {
          element1.style.display = "";
          element2.style.display = "";
          element3.style.display = "";
          element4.style.display = "none";
          thElement1.textContent = 'WILAYAH IV';
          thElement2.textContent = 'WILAYAH V';
          thElement3.textContent = 'WILAYAH VI';
          thElement4.textContent = 'Plasma2';
          thElement1.classList.add("text-center");
          thElement2.classList.add("text-center");
          thElement3.classList.add("text-center");
          thElement4.classList.add("text-center");
          element1.classList.remove("col-md-6", "col-lg-4", "col-lg-6");
          element1.classList.add("col-md-6", "col-lg-4");
          element2.classList.remove("col-md-6", "col-lg-4", "col-lg-6");
          element2.classList.add("col-md-6", "col-lg-4");
          element3.classList.remove("col-md-6", "col-lg-4");
          element3.classList.add("col-md-6", "col-lg-4");

        } else if (regSidak === '4') {
          element1.style.display = "";
          element2.style.display = "";
          element3.style.display = "none";
          element4.style.display = "none";
          thElement1.textContent = 'WILAYAH Inti';
          thElement2.textContent = 'WILAYAH Plasma';
          // thElement3.textContent = 'WILAYAH VI';
          // thElement4.textContent = 'Plasma2';
          thElement1.classList.add("text-center");
          thElement2.classList.add("text-center");
          // thElement3.classList.add("text-center");
          // thElement4.classList.add("text-center");
          element1.classList.remove("col-md-6", "col-lg-4", "col-lg-6");
          element1.classList.add("col-md-6", "col-lg-6");
          element2.classList.remove("col-md-6", "col-lg-4", "col-lg-6");
          element2.classList.add("col-md-6", "col-lg-6");

        }
      }



      function changeClassYear() {
        var regSidak = document.getElementById('regionalSidakYear').value;

        const element1 = document.getElementById('Tab1');
        const element2 = document.getElementById('Tab2');
        const element3 = document.getElementById('Tab3');

        const thElement1 = document.getElementById('thead1');
        const thElement2 = document.getElementById('thead2');
        const thElement3 = document.getElementById('thead3');

        if (regSidak == '3') {
          element1.style.display = "";
          element2.style.display = "";
          element3.style.display = "none";

          thElement1.textContent = 'WILAYAH VII';
          thElement2.textContent = 'WILAYAH VIII';

          thElement1.classList.add("text-center");
          thElement2.classList.add("text-center");
          thElement3.classList.add("text-center");

          element1.classList.remove("col-md-6", "col-lg-3", "col-lg-6");
          element1.classList.add("col-md-6", "col-lg-6");
          element2.classList.remove("col-md-6", "col-lg-3", "col-lg-6");
          element2.classList.add("col-md-6", "col-lg-6");

        } else if (regSidak === '1') {
          element1.style.display = "";
          element2.style.display = "";
          element3.style.display = "";

          thElement1.textContent = 'WILAYAH I';
          thElement2.textContent = 'WILAYAH II';
          thElement3.textContent = 'WILAYAH III';

          thElement1.classList.add("text-center");
          thElement2.classList.add("text-center");
          thElement3.classList.add("text-center");

          element1.classList.remove("col-md-6", "col-lg-4", "col-lg-6");
          element1.classList.add("col-md-4", "col-lg-4");
          element2.classList.remove("col-md-6", "col-lg-4", "col-lg-6");
          element2.classList.add("col-md-4", "col-lg-4");
          element3.classList.remove("col-md-6", "col-lg-4");
          element3.classList.add("col-md-4", "col-lg-4");

        } else if (regSidak === '2') {
          element1.style.display = "";
          element2.style.display = "";
          element3.style.display = "";

          thElement1.textContent = 'WILAYAH IV';
          thElement2.textContent = 'WILAYAH V';
          thElement3.textContent = 'WILAYAH VI';

          thElement1.classList.add("text-center");
          thElement2.classList.add("text-center");
          thElement3.classList.add("text-center");

          element1.classList.remove("col-md-6", "col-lg-4", "col-lg-6");
          element1.classList.add("col-md-6", "col-lg-4");
          element2.classList.remove("col-md-6", "col-lg-4", "col-lg-6");
          element2.classList.add("col-md-6", "col-lg-4");
          element3.classList.remove("col-md-6", "col-lg-4");
          element3.classList.add("col-md-6", "col-lg-4");

        } else if (regSidak === '4') {
          element1.style.display = "";
          element2.style.display = "";
          element3.style.display = "none";
          thElement1.classList.add("text-center");
          thElement2.classList.add("text-center");
          element1.classList.remove("col-md-6", "col-lg-4", "col-lg-6");
          element1.classList.add("col-md-6", "col-lg-6");
          element2.classList.remove("col-md-6", "col-lg-4", "col-lg-6");
          element2.classList.add("col-md-6", "col-lg-6");

        }
      }


      function getDataTphMonth() {
        changeClassMonth()
        var _token = $('input[name="_token"]').val();
        var monthSidak = document.getElementById('inputDateMonth').value
        var regSidak = document.getElementById('regionalSidakMonth').value
        $.ajax({
          url: "{{ route('getBtTphMonth') }}",
          method: "get",
          data: {
            month: monthSidak,
            reg: regSidak,
            _token: _token
          },
          success: function(result) {
            Swal.close();
            //parsing result ke json untuk dalam estate
            var rekapafd = result['rekap_perafdeling_groupwil']
            var rekap_per_estate = result['rekap_perestate_groupwill']
            var rekap_per_wil = result['rekap_perwill_groupwil']
            var rekap_per_reg = result['rekap_rh']
            var datachart = result['datachart_est']
            // console.log(rekap_per_reg);
            let table1 = rekapafd[1] ?? rekapafd[4] ?? rekapafd[7] ?? rekapafd[10]
            let table2 = rekapafd[2] ?? rekapafd[5] ?? rekapafd[8] ?? rekapafd[11]
            let table3 = rekapafd[3] ?? rekapafd[6] ?? []
            let tbody1 = document.getElementById('tbody1Month');
            let tbody2 = document.getElementById('tbody2Month');
            let tbody3 = document.getElementById('tbody3Month');
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

            // charto anone 
            let list_estate = [];
            let brdchart = [];
            let chartrst = [];
            let mentahbuah = [];
            let masakbuah = [];
            let overbuah = [];
            let abrbuah = [];
            let emptybuah = [];
            let vcutbuah = [];
            let brdtrans = [];
            let buahtrans = [];
            let keys = Object.keys(datachart);

            for (let index = 0; index < keys.length; index++) {
              let key = keys[index];
              list_estate.push(key);
              brdchart.push(datachart[key].brd);
              chartrst.push(datachart[key].buah);
            }

            renderChartTphMonth.updateSeries([{
              name: 'Brondolan Tinggal',
              data: brdchart
            }]);

            // If ktg is an array, you can use it for x-axis categories
            renderChartTphMonth.updateOptions({
              xaxis: {
                categories: list_estate
              }
            });
            renderChartKarungMonth.updateSeries([{
              name: 'Buah Tinggal',
              data: chartrst
            }]);

            // If ktg is an array, you can use it for x-axis categories
            renderChartKarungMonth.updateOptions({
              xaxis: {
                categories: list_estate
              }
            });

            let list_wil = [];
            let cakbrdwil = [];
            let cakbuahwil = [];
            let keyss = Object.keys(rekap_per_wil);
            // console.log(rekap_per_wil);
            for (let index = 0; index < keyss.length; index++) {
              let key = keyss[index];
              list_wil.push(key);
              cakbrdwil.push(rekap_per_wil[key].brd);
              cakbuahwil.push(rekap_per_wil[key].buah);
            }

            will_bttMonth.updateSeries([{
              name: 'Brondolan Tinggal',
              data: cakbrdwil
            }]);

            // If ktg is an array, you can use it for x-axis categories
            will_bttMonth.updateOptions({
              xaxis: {
                categories: list_wil
              }
            });
            renderChartKarungWilMonth.updateSeries([{
              name: 'Buah Tinggal',
              data: cakbuahwil
            }]);

            // If ktg is an array, you can use it for x-axis categories
            renderChartKarungWilMonth.updateOptions({
              xaxis: {
                categories: list_wil
              }
            });





            if (table1_wil && table1_wil.length > 0) {
              TableForWilReg(table1_wil, tbody1);
            }

            if (table2_wil && table2_wil.length > 0) {
              TableForWilReg(table2_wil, tbody2);
            }
            if (table3_wil && table3_wil.length > 0) {
              TableForWilReg(table3_wil, tbody3);
            }
            let theadreg = document.getElementById('tbodySkorRHMonth');
            if (rekap_per_reg && rekap_per_reg.length > 0) {
              TableForWilReg(rekap_per_reg, theadreg);
            }


          },
          error: function(xhr, status, error) {
            Swal.close();
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Data Kosong.'
            });
            // Handle the error here
            console.log("An error occurred:", error);
          }
        })
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
      document.addEventListener('click', function(event) {
        if (event.target.matches('#pills-home-tab')) {
          enableFields(true); // Enable year fields
        } else if (event.target.matches('#pills-profile-tab')) {
          enableFields(false); // Enable month fields
        }
      });

      function enableFields(isYear) {
        document.getElementById('inputYear').disabled = !isYear;
        document.getElementById('start_date').disabled = isYear;
        document.getElementById('end_date').disabled = isYear;
      }

      function getDataTphYear() {
        changeClassYear()
        if (document.getElementById('inputYear').disabled) {
          var _token = $('input[name="_token"]').val();
          var start_date = document.getElementById('start_date').value;
          var end_date = document.getElementById('end_date').value;
          var regSidak = document.getElementById('regionalSidakYear').value;

          // Use the start_date and end_date values here
          // console.log('Start Date:', start_date);
          // console.log('End Date:', end_date);
        } else {
          // Year tab is selected, handle year-related logic here
          var _token = $('input[name="_token"]').val();
          var yearSidak = document.getElementById('inputYear').value;
          var regSidak = document.getElementById('regionalSidakYear').value;

          // Use the yearSidak and regSidak values here
          // console.log('Year:', yearSidak);
          // console.log('Region:', regSidak);
        }
        $.ajax({
          url: "{{ route('getBtTphYear') }}",
          method: "get",
          data: {
            year: yearSidak,
            start_date: start_date,
            end_date: end_date,
            reg: regSidak,
            _token: _token
          },
          success: function(result) {
            Swal.close();
            //parsing result ke json untuk dalam estate
            var parseResult = JSON.parse(result)
            var newsidakend = Object.entries(parseResult['newsidakend'])
            var rhdata = Object.entries(parseResult['rhdata'])
            var rekapmua = parseResult['rekapmua']

            // console.log(newsidakend);

            let table1 = newsidakend[0]
            let table2 = newsidakend[1]
            let table3 = newsidakend[2]

            var theadreg = document.getElementById('tbodySkorRHYear');

            // console.log(regional);

            let tr = document.createElement('tr')
            let reg1 = rhdata[1][1]
            let reg2 = rhdata[3][1]
            let reg3 = '-'
            let reg4 = rhdata[0][1]
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
            //     $('#tbody1Year').empty()
            // $('#tbody2Year').empty()
            // $('#tbody3Year').empty()
            var trekap1 = document.getElementById('tbody1Year');
            Object.keys(table1[1]).forEach(key => {
              Object.keys(table1[1][key]).forEach(subKey => {
                let item1 = table1[1][key][subKey]['est'];
                let item2 = table1[1][key][subKey]['afd'];
                let item3 = table1[1][key][subKey]['nama']
                let item4 = table1[1][key][subKey]['total_score'];

                // Check if item4 is less than 0, and set it to 0 if true
                item4 = (item4 < 0) ? 0 : item4;

                let item5 = table1[1][key][subKey]['rank'] ?? '-';

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

            var trekap2 = document.getElementById('tbody2Year');
            Object.keys(table2[1]).forEach(key => {
              Object.keys(table2[1][key]).forEach(subKey => {
                let item1 = table2[1][key][subKey]['est'];
                let item2 = table2[1][key][subKey]['afd'];
                let item3 = table2[1][key][subKey]['nama'] ?? '-'
                let item4 = table2[1][key][subKey]['total_score'];

                // Check if item4 is less than 0, and set it to 0 if true
                item4 = (item4 < 0) ? 0 : item4;

                let item5 = table2[1][key][subKey]['rank'] ?? '-';

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

            var trekap3 = document.getElementById('tbody3Year');
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
              itemElement4.innerText = value['score_estate'];
              itemElement5.innerText = '-'

              setBackgroundColor(itemElement4, value['score_estate']);
              if (key === 'PT.MUA') {
                tr.style.backgroundColor = '#A0978D';
              }

              tr.appendChild(itemElement1);
              tr.appendChild(itemElement2);
              tr.appendChild(itemElement3);
              tr.appendChild(itemElement4);
              tr.appendChild(itemElement5);

              trekap3.appendChild(tr);
            });
            Object.keys(table3[1]).forEach(key => {
              Object.keys(table3[1][key]).forEach(subKey => {
                let item1 = table3[1][key][subKey]['est'];
                let item2 = table3[1][key][subKey]['afd'];
                let item3 = table3[1][key][subKey]['nama']
                let item4 = table3[1][key][subKey]['total_score'];

                // Check if item4 is less than 0, and set it to 0 if true
                item4 = (item4 < 0) ? 0 : item4;

                let item5 = table3[1][key][subKey]['rank'] ?? '-';

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




          },
          error: function() {
            Swal.close();
          }
        })
      }

      function graphFilterYear() {
        var reg = document.getElementById('regionalSidakYear').value
        var est = document.getElementById('estSidakYear').value
        var yearGraph = document.getElementById('inputYear').value
        var _token = $('input[name="_token"]').val()
        $.ajax({
          url: "{{ route('graphFilterYear') }}",
          method: "get",
          data: {
            est: est,
            yearGraph: yearGraph,
            reg: reg,
            _token: _token
          },
          success: function(result) {
            Swal.close();
            var parseResult = JSON.parse(result);
            var chart_brd = parseResult['brdgraph'];
            var chart_krg = parseResult['graphbuah'];
            var ktg = parseResult['ktg'];

            // Assuming chart_brd is an array of arrays, you can directly use it for data in Apex chart
            renderChartTphYear.updateSeries([{
              name: 'Brondolan Tinggal',
              data: chart_brd
            }]);

            // If ktg is an array, you can use it for x-axis categories
            renderChartTphYear.updateOptions({
              xaxis: {
                categories: ktg
              }
            });

            // renderChartTphYear.updateSeries([{
            //   name: 'Brondolan Tinggal',
            //   data: chart_brd
            // }])

            renderChartKarungYear.updateSeries([{
              name: 'Buah Tinggal',
              data: chart_krg
            }])


            renderChartKarungYear.updateOptions({
              xaxis: {
                categories: ktg
              }
            })


            // renderChartBuahTglTphYear.updateSeries([{
            //   name: 'Buah/Blok  Tinggal TPH',
            //   data: chart_krg
            // }])


          },
          error: function() {
            Swal.close();
          }
        });
      }
    });


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
      // const estBtn = document.getElementById('sort-est-btn');
      const rankBtn = document.getElementById('sort-rank-btn');
      const showBtn = document.getElementById('btnShowMonth');
      const regionalSelect = document.getElementById('regionalSidakMonth');

      let currentRegion = regionalSelect.value;

      let firstClick = true; // Add a flag to indicate the first click

      // estBtn.addEventListener('click', () => {
      //   if (firstClick) {
      //     showBtn.click();
      //     firstClick = false; // Set the flag to false after the first click
      //   }
      //   handleSort('est');
      // });
      // rankBtn.addEventListener('click', () => {
      //   if (firstClick) {
      //     showBtn.click();
      //     firstClick = false; // Set the flag to false after the first click
      //   }
      //   handleSort('rank');
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
            est: [16, 18, 16, 3],
            rank: [16, 18, 16, 3]
          },
          '2': {
            est: [17, 13, 10, 5],
            rank: [17, 13, 10, 5]
          },
          '3': {
            est: [20, 11, 10, 3],
            rank: [20, 11, 10, 3]
          }
        };

        const tbodies = ['tbody1Month', 'tbody2Month', 'tbody3Month', 'plasmaMonth'];
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
      // const estBtn = document.getElementById('sort-afd-btnWeek');
      const rankBtn = document.getElementById('sort-est-btnWeek');
      const showBtn = document.getElementById('btnShow');
      const regionalSelect = document.getElementById('regionalSidakMonth');

      let currentRegion = regionalSelect.value;

      let firstClick = true; // Add a flag to indicate the first click

      // estBtn.addEventListener('click', () => {
      //   if (firstClick) {
      //     showBtn.click();
      //     firstClick = false; // Set the flag to false after the first click
      //   }
      //   handleSort('est');
      // });
      // rankBtn.addEventListener('click', () => {
      //   if (firstClick) {
      //     showBtn.click();
      //     firstClick = false; // Set the flag to false after the first click
      //   }
      //   handleSort('rank');
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
            est: [16, 18, 16, 3],
            rank: [16, 18, 16, 3]
          },
          '2': {
            est: [17, 13, 10, 5],
            rank: [17, 13, 10, 5]
          },
          '3': {
            est: [20, 11, 10, 3],
            rank: [20, 11, 10, 3]
          }
        };

        const tbodies = ['tbody1', 'tbody2', 'tbody3', 'plasma'];
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


    document.getElementById('exportForm').addEventListener('submit', function(event) {
      // Prevent the default form submission
      event.preventDefault();

      // Get the selected value from regDataIns select element
      var regDataInsValue = document.getElementById('regDataTph').value;

      // Get the value from dateDataIns input element
      var dateDataInsValue = document.getElementById('dateDataTph').value;

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