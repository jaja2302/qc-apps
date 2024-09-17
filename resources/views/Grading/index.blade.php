<x-layout.app>
    <div class="card">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link active" id="nav-utama-tab" data-toggle="tab" href="#nav-utama" role="tab" aria-controls="nav-utama" aria-selected="true">Rekap Regional</a>
                <a class="nav-item nav-link" id="nav-mill-tab" data-toggle="tab" href="#nav-mill" role="tab" aria-controls="nav-mill" aria-selected="false">Rekap Mill</a>
                <a class="nav-item nav-link" id="nav-perhari-tab" data-toggle="tab" href="#nav-perhari" role="tab" aria-controls="nav-perhari" aria-selected="false">Rekap Perhari</a>
                <a class="nav-item nav-link" id="nav-afdeling-tab" data-toggle="tab" href="#nav-afdeling" role="tab" aria-controls="nav-afdeling" aria-selected="false">Rekap Afdeling</a>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <!-- rekap regional tab  -->
            <div class="tab-pane fade show active" id="nav-utama" role="tabpanel" aria-labelledby="nav-utama-tab">
                <div class="text-center border border-3 mt-4 ml-3 mr-3 mb-10">
                    <h1>REKAPITULASI LAPORAN GRADING PKS</h1>
                </div>

                <div class="d-flex justify-content-end mr-3 mt-4">
                    <div class="margin g-2">
                        <div class="row align-items-center">
                            <div class="col-md">
                                {{ csrf_field() }}
                                <input class="form-control" value="{{ date('Y-m') }}" type="month" name="bulan" id="inputbulan"> <!-- Changed name to 'bulan' -->
                            </div>
                            <div class="col-md">
                                <select class="form-select mb-2 mb-md-0" name="reg" id="regional_select" aria-label="Default select example"> <!-- Changed name to 'reg' -->
                                    @foreach ($regional as $items)
                                    <option value="{{ $items['id'] }}">{{ $items['nama'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary ml-2" id="rekapregional">Show</button>
                            </div>
                            <div class="col-auto">
                                <form id="exportForm" action="{{ route('exportgrading') }}" method="POST">
                                    @csrf
                                    <input type="hidden" id="getregional" name="getregional">
                                    <input type="hidden" id="getdate" name="getdate">
                                    <input type="hidden" name="tipedata" value="rekapsatu">
                                    <button type="submit" class="btn btn-primary">Export</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="mt-4 ml-3 mr-3 mb-10 text-center">

                    <table class="table table-responsive table-striped table-bordered">
                        <thead>
                            <tr>
                                <th colspan="31" style="background-color: #c8e4f4;">BERDASARKAN ESTATE</th>
                            </tr>
                            <tr>
                                <th rowspan="3" class="align-middle" style="background-color: #f0ecec;">Estate</th>
                                <th style="background-color: #f0ecec;" colspan="2">UNIT SORTASI</th>
                                <th style="background-color: #88e48c;" colspan="20">HASIL GRADING</th>
                                <th style="background-color: #f8c4ac;" colspan="6">KELAS JANJANG</th>
                            </tr>
                            <tr>
                                <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">JUMLAH JANJANG GRADING</th>
                                <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">TONASE(KG)</th>
                                <th style="background-color: #88e48c;" colspan="2">RIPENESS</th>
                                <th style="background-color: #88e48c;" colspan="2">UNRIPE</th>
                                <th style="background-color: #88e48c;" colspan="2">OVERRIPE</th>
                                <th style="background-color: #88e48c;" colspan="2">EMPTY BUNCH</th>
                                <th style="background-color: #88e48c;" colspan="2">ROTTEN BUNCH</th>
                                <th style="background-color: #88e48c;" colspan="2">ABNORMAL</th>
                                <th style="background-color: #88e48c;" colspan="2">LONG STALK</th>
                                <th style="background-color: #88e48c;" colspan="2">V-CUT</th>
                                <th style="background-color: #88e48c;" colspan="2">DIRT</th>
                                <th style="background-color: #88e48c;" colspan="2">LOOSE FRUIT</th>
                                <th style="background-color: #f8c4ac;" colspan="2">KELAS C</th>
                                <th style="background-color: #f8c4ac;" colspan="2">KELAS B</th>
                                <th style="background-color: #f8c4ac;" colspan="2">KELAS A</th>
                            </tr>
                            <tr>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #f8c4ac;">JJG</th>
                                <th style="background-color: #f8c4ac;">%</th>
                                <th style="background-color: #f8c4ac;">JJG</th>
                                <th style="background-color: #f8c4ac;">%</th>
                                <th style="background-color: #f8c4ac;">JJG</th>
                                <th style="background-color: #f8c4ac;">%</th>
                            </tr>
                        </thead>
                        <tbody id="regional_estate">

                        </tbody>
                    </table>

                    <table class="mt-5 table table-responsive table-striped table-bordered">
                        <thead>
                            <tr>
                                <th colspan="31" style="background-color: #c8e4f4;">BERDASARKAN WILAYAH</th>
                            </tr>
                            <tr>
                                <th rowspan="3" class="align-middle" style="background-color: #f0ecec;">Wilayah</th>
                                <th style="background-color: #f0ecec;" colspan="2">UNIT SORTASI</th>
                                <th style="background-color: #88e48c;" colspan="20">HASIL GRADING</th>
                                <th style="background-color: #f8c4ac;" colspan="6">KELAS JANJANG</th>
                            </tr>
                            <tr>
                                <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">JUMLAH JANJANG GRADING</th>
                                <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">TONASE(KG)</th>
                                <th style="background-color: #88e48c;" colspan="2">RIPENESS</th>
                                <th style="background-color: #88e48c;" colspan="2">UNRIPE</th>
                                <th style="background-color: #88e48c;" colspan="2">OVERRIPE</th>
                                <th style="background-color: #88e48c;" colspan="2">EMPTY BUNCH</th>
                                <th style="background-color: #88e48c;" colspan="2">ROTTEN BUNCH</th>
                                <th style="background-color: #88e48c;" colspan="2">ABNORMAL</th>
                                <th style="background-color: #88e48c;" colspan="2">LONG STALK</th>
                                <th style="background-color: #88e48c;" colspan="2">V-CUT</th>
                                <th style="background-color: #88e48c;" colspan="2">DIRT</th>
                                <th style="background-color: #88e48c;" colspan="2">LOOSE FRUIT</th>
                                <th style="background-color: #f8c4ac;" colspan="2">KELAS C</th>
                                <th style="background-color: #f8c4ac;" colspan="2">KELAS B</th>
                                <th style="background-color: #f8c4ac;" colspan="2">KELAS A</th>
                            </tr>
                            <tr>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #f8c4ac;">JJG</th>
                                <th style="background-color: #f8c4ac;">%</th>
                                <th style="background-color: #f8c4ac;">JJG</th>
                                <th style="background-color: #f8c4ac;">%</th>
                                <th style="background-color: #f8c4ac;">JJG</th>
                                <th style="background-color: #f8c4ac;">%</th>
                            </tr>
                        </thead>
                        <tbody id="data_wil"></tbody>
                    </table>

                    <table class="mt-5  table table-responsive table-striped table-bordered">
                        <thead>
                            <tr>
                                <th colspan="32" style="background-color: #c8e4f4;">BERDASARKAN MILL</th>
                            </tr>
                            <tr>
                                <th rowspan="3" colspan="2" class="align-middle" style="background-color: #f0ecec;">MILL</th>
                                <th style="background-color: #f0ecec;" colspan="2">UNIT SORTASI</th>
                                <th style="background-color: #88e48c;" colspan="20">HASIL GRADING</th>
                                <th style="background-color: #f8c4ac;" colspan="6">KELAS JANJANG</th>
                            </tr>
                            <tr>
                                <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">JUMLAH JANJANG GRADING</th>
                                <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">TONASE(KG)</th>
                                <th style="background-color: #88e48c;" colspan="2">RIPENESS</th>
                                <th style="background-color: #88e48c;" colspan="2">UNRIPE</th>
                                <th style="background-color: #88e48c;" colspan="2">OVERRIPE</th>
                                <th style="background-color: #88e48c;" colspan="2">EMPTY BUNCH</th>
                                <th style="background-color: #88e48c;" colspan="2">ROTTEN BUNCH</th>
                                <th style="background-color: #88e48c;" colspan="2">ABNORMAL</th>
                                <th style="background-color: #88e48c;" colspan="2">LONG STALK</th>
                                <th style="background-color: #88e48c;" colspan="2">V-CUT</th>
                                <th style="background-color: #88e48c;" colspan="2">DIRT</th>
                                <th style="background-color: #88e48c;" colspan="2">LOOSE FRUIT</th>
                                <th style="background-color: #f8c4ac;" colspan="2">KELAS C</th>
                                <th style="background-color: #f8c4ac;" colspan="2">KELAS B</th>
                                <th style="background-color: #f8c4ac;" colspan="2">KELAS A</th>
                            </tr>
                            <tr>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #f8c4ac;">JJG</th>
                                <th style="background-color: #f8c4ac;">%</th>
                                <th style="background-color: #f8c4ac;">JJG</th>
                                <th style="background-color: #f8c4ac;">%</th>
                                <th style="background-color: #f8c4ac;">JJG</th>
                                <th style="background-color: #f8c4ac;">%</th>
                            </tr>
                        </thead>
                        <tbody id="data_mill"></tbody>

                    </table>

                </div>
            </div>
            <!-- rekap mill  -->
            <div class="tab-pane fade" id="nav-mill" role="tabpanel" aria-labelledby="nav-mill-tab">
                <div class="text-center border border-3 mt-4 ml-3 mr-3 mb-10">
                    <h1>REKAPITULASI LAPORAN GRADING PKS</h1>
                </div>

                <div class="d-flex justify-content-end mr-3 mt-4">
                    <div class="margin g-2">
                        <div class="row align-items-center">
                            <div class="col-md">
                                {{csrf_field()}}
                                <input class="form-control" value="{{ date('Y-m') }}" type="month" name="inputbulan" id="inputbulan_mill">
                            </div>
                            <div class="col-md">
                                <select class="form-select mb-2 mb-md-0" name="regional_id" id="regional_select_mill" aria-label="Default select example">

                                    @foreach ($regional as $items)
                                    <option value="{{$items['id']}}">{{$items['nama']}}</option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary ml-2" id="rekapmill">Show</button>
                            </div>
                            <div class="col-auto">
                                <form id="exportFormdua" action="{{ route('exportgrading') }}" method="POST">
                                    @csrf
                                    <input type="hidden" id="getregionaldua" name="getregionaldua">
                                    <input type="hidden" id="getdatedua" name="getdatedua">
                                    <input type="hidden" name="tipedata" value="rekapdua">
                                    <button type="submit" class="btn btn-primary">Export</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 ml-3 mr-3 mb-10 text-center">
                    <table class="table table-responsive table-striped table-bordered">
                        <thead>
                            <tr>
                                <th colspan="31" style="background-color: #c8e4f4;">BERDASARKAN ESTATE</th>
                            </tr>
                            <tr>
                                <th rowspan="3" class="align-middle" style="background-color: #f0ecec;">Estate</th>
                                <th style="background-color: #f0ecec;" colspan="2">UNIT SORTASI</th>
                                <th style="background-color: #88e48c;" colspan="20">HASIL GRADING</th>
                                <th style="background-color: #f8c4ac;" colspan="6">KELAS JANJANG</th>
                            </tr>
                            <tr>
                                <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">JUMLAH JANJANG GRADING</th>
                                <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">TONASE(KG)</th>
                                <th style="background-color: #88e48c;" colspan="2">RIPENESS</th>
                                <th style="background-color: #88e48c;" colspan="2">UNRIPE</th>
                                <th style="background-color: #88e48c;" colspan="2">OVERRIPE</th>
                                <th style="background-color: #88e48c;" colspan="2">EMPTY BUNCH</th>
                                <th style="background-color: #88e48c;" colspan="2">ROTTEN BUNCH</th>
                                <th style="background-color: #88e48c;" colspan="2">ABNORMAL</th>
                                <th style="background-color: #88e48c;" colspan="2">LONG STALK</th>
                                <th style="background-color: #88e48c;" colspan="2">V-CUT</th>
                                <th style="background-color: #88e48c;" colspan="2">DIRT</th>
                                <th style="background-color: #88e48c;" colspan="2">LOOSE FRUIT</th>
                                <th style="background-color: #f8c4ac;" colspan="2">KELAS C</th>
                                <th style="background-color: #f8c4ac;" colspan="2">KELAS B</th>
                                <th style="background-color: #f8c4ac;" colspan="2">KELAS A</th>
                            </tr>
                            <tr>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #f8c4ac;">JJG</th>
                                <th style="background-color: #f8c4ac;">%</th>
                                <th style="background-color: #f8c4ac;">JJG</th>
                                <th style="background-color: #f8c4ac;">%</th>
                                <th style="background-color: #f8c4ac;">JJG</th>
                                <th style="background-color: #f8c4ac;">%</th>
                            </tr>
                        </thead>
                        <tbody id="rekap_mill"></tbody>

                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="nav-perhari" role="tabpanel" aria-labelledby="nav-perhari-tab">
                <div class="text-center border border-3 mt-4 ml-3 mr-3 mb-10">
                    <h1>REKAPITULASI LAPORAN GRADING PKS</h1>
                </div>

                <div class="d-flex justify-content-end mr-3 mt-4">
                    <div class="margin g-2">
                        <div class="row align-items-center">
                            <div class="col-md">
                                {{csrf_field()}}
                                <input class="form-control" value="{{ date('Y-m-d') }}" type="date" name="inputbulan" id="input_rekap_perhari">
                            </div>
                            <div class="col-md">
                                <select class="form-select mb-2 mb-md-0" name="regional_id" id="rekap_perhari_reg" aria-label="Default select example">
                                    <option value="">Select Regional</option>
                                </select>
                            </div>
                            <div class="col-md">
                                <select class="form-select mb-2 mb-md-0" name="mill_id" id="mill_id" aria-label="Default select example">
                                    <option value="">Select Mill</option>
                                </select>
                            </div>

                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary ml-2" id="rekap_perhari">Show</button>
                            </div>
                            <div class="col-auto">
                                <form id="exportFormtiga" action="{{ route('exportgrading') }}" method="POST">
                                    @csrf
                                    <input type="hidden" id="getregionaltiga" name="getregionaltiga">
                                    <input type="hidden" id="getdatetiga" name="getdatetiga">
                                    <input type="hidden" name="tipedata" value="rekaptiga">
                                    <button type="submit" class="btn btn-primary">Export</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 ml-3 mr-3 mb-10 text-center">
                    <table class="table table-responsive table-striped table-bordered">
                        <thead>
                            <tr>
                                <th colspan="33" style="background-color: #c8e4f4;">BERDASARKAN ESTATE</th>
                            </tr>
                            <tr>
                                <th rowspan="3" class="align-middle" style="background-color: #f0ecec;">Estate</th>
                                <th rowspan="3" class="align-middle" style="background-color: #f0ecec;">Afdeling</th>
                                <th style="background-color: #f0ecec;" colspan="4">UNIT SORTASI</th>
                                <th style="background-color: #88e48c;" colspan="20">HASIL GRADING</th>
                                <th style="background-color: #f8c4ac;" colspan="6">KELAS JANJANG</th>
                            </tr>
                            <tr>
                                <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">JUMLAH JANJANG SPD</th>
                                <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">JUMLAH JANJANG GRADING</th>
                                <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">TONASE(KG)</th>
                                <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">BJR(KG)</th>
                                <th style="background-color: #88e48c;" colspan="2">RIPENESS</th>
                                <th style="background-color: #88e48c;" colspan="2">UNRIPE</th>
                                <th style="background-color: #88e48c;" colspan="2">OVERRIPE</th>
                                <th style="background-color: #88e48c;" colspan="2">EMPTY BUNCH</th>
                                <th style="background-color: #88e48c;" colspan="2">ROTTEN BUNCH</th>
                                <th style="background-color: #88e48c;" colspan="2">ABNORMAL</th>
                                <th style="background-color: #88e48c;" colspan="2">LONG STALK</th>
                                <th style="background-color: #88e48c;" colspan="2">V-CUT</th>
                                <th style="background-color: #88e48c;" colspan="2">DIRT</th>
                                <th style="background-color: #88e48c;" colspan="2">LOOSE FRUIT</th>
                                <th style="background-color: #f8c4ac;" colspan="2">KELAS C</th>
                                <th style="background-color: #f8c4ac;" colspan="2">KELAS B</th>
                                <th style="background-color: #f8c4ac;" colspan="2">KELAS A</th>
                            </tr>
                            <tr>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #f8c4ac;">JJG</th>
                                <th style="background-color: #f8c4ac;">%</th>
                                <th style="background-color: #f8c4ac;">JJG</th>
                                <th style="background-color: #f8c4ac;">%</th>
                                <th style="background-color: #f8c4ac;">JJG</th>
                                <th style="background-color: #f8c4ac;">%</th>
                            </tr>
                        </thead>
                        <tbody id="rekap_perhari_data">

                    </table>
                </div>
            </div>
            <!-- rekap afdeling  -->
            <div class="tab-pane fade" id="nav-afdeling" role="tabpanel" aria-labelledby="nav-afdeling-tab">
                <div class="text-center border border-3 mt-4 ml-3 mr-3 mb-10">
                    <h1>REKAPITULASI LAPORAN GRADING PKS</h1>
                </div>

                <div class="d-flex justify-content-end mr-3 mt-4">
                    <div class="margin g-2">
                        <div class="row align-items-center">
                            <div class="col-md">
                                {{csrf_field()}}
                                <input class="form-control" value="{{ date('Y-m') }}" type="month" name="inputbulan" id="input_rekap_perfadeling">
                            </div>
                            <div class="col-md">
                                <select class="form-select mb-2 mb-md-0" name="regional_id" id="rekap_perfadeling_reg" aria-label="Default select example">

                                    @foreach ($regional as $items)
                                    <option value="{{$items['id']}}">{{$items['nama']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary ml-2" id="rekap_perfadeling">Show</button>
                            </div>
                            <div class="col-auto">
                                <form id="exportFormempat" action="{{ route('exportgrading') }}" method="POST">
                                    @csrf
                                    <input type="hidden" id="getregionalempat" name="getregionalempat">
                                    <input type="hidden" id="getdateempat" name="getdateempat">
                                    <input type="hidden" name="tipedata" value="rekapempat">
                                    <button type="submit" class="btn btn-primary">Export</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 ml-3 mr-3 mb-10 text-center">
                    <table class="table table-responsive table-striped table-bordered">
                        <thead>
                            <tr>
                                <th style="background-color: #f0ecec;" class="align-middle" rowspan="3">Estate</th>
                                <th style="background-color: #f0ecec;" class="align-middle" rowspan="3">Afdeling</th>
                                <th style="background-color: #f0ecec;" colspan="4">UNIT SORTASI</th>
                                <th style="background-color: #88e48c;" colspan="20">HASIL GRADING</th>
                                <th style="background-color: #f8c4ac;" colspan="6">KELAS JANJANG</th>
                            </tr>
                            <tr>
                                <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">JUMLAH JANJANG SPB</th>
                                <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">JUMLAH JANJANG GRADING</th>
                                <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">TONASE (KG)</th>
                                <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">BJR (KG)</th>
                                <th style="background-color: #88e48c;" colspan="2">RIPENESS</th>
                                <th style="background-color: #88e48c;" colspan="2">UNRIPE</th>
                                <th style="background-color: #88e48c;" colspan="2">OVERRIPE</th>
                                <th style="background-color: #88e48c;" colspan="2">EMPTY BUNCH</th>
                                <th style="background-color: #88e48c;" colspan="2">ROTTEN BUNCH</th>
                                <th style="background-color: #88e48c;" colspan="2">ABNORMAL</th>
                                <th style="background-color: #88e48c;" colspan="2">LONG STALK</th>
                                <th style="background-color: #88e48c;" colspan="2">V-CUT</th>
                                <th style="background-color: #88e48c;" colspan="2">DIRT</th>
                                <th style="background-color: #88e48c;" colspan="2">LOOSE FRUIT</th>
                                <th style="background-color: #f8c4ac;" colspan="2">KELAS C</th>
                                <th style="background-color: #f8c4ac;" colspan="2">KELAS B</th>
                                <th style="background-color: #f8c4ac;" colspan="2">KELAS A</th>
                            </tr>
                            <tr>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #88e48c;">JJG</th>
                                <th style="background-color: #88e48c;">%</th>
                                <th style="background-color: #f8c4ac;">JJG</th>
                                <th style="background-color: #f8c4ac;">%</th>
                                <th style="background-color: #f8c4ac;">JJG</th>
                                <th style="background-color: #f8c4ac;">%</th>
                                <th style="background-color: #f8c4ac;">JJG</th>
                                <th style="background-color: #f8c4ac;">%</th>
                            </tr>
                        </thead>
                        <tbody id="rekap_afdeling_data"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script type="module">
        $(document).ready(function() {
            const reg = @json($regional); // Assuming $regional contains your data

            // Populate regional select
            $.each(reg, function(index, regional) {
                $('#rekap_perhari_reg').append(
                    `<option value="${regional.id}">${regional.nama}</option>`
                );
            });

            // When regional select changes, populate mill select
            $('#rekap_perhari_reg').on('change', function() {
                const selectedRegId = $(this).val();
                $('#mill_id').empty().append('<option value="">Select Mill</option>'); // Clear previous options

                const selectedRegional = reg.find(region => region.id == selectedRegId);

                if (selectedRegional && selectedRegional.mill.length > 0) {
                    $.each(selectedRegional.mill, function(index, mill) {
                        $('#mill_id').append(
                            `<option value="${mill.id}">${mill.nama_mill}</option>`
                        );
                    });
                }
            });
        });

        document.getElementById('rekapregional').onclick = function() {
            // console.log('pepek');

            Swal.fire({
                title: 'Loading',
                html: '<span class="loading-text">Mohon Tunggu...</span>',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });
            getregional();
        }
        document.getElementById('rekapmill').onclick = function() {
            Swal.fire({
                title: 'Loading',
                html: '<span class="loading-text">Mohon Tunggu...</span>',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });
            getrekapmill();
        }


        document.getElementById('rekap_perfadeling').onclick = function() {
            Swal.fire({
                title: 'Loading',
                html: '<span class="loading-text">Mohon Tunggu...</span>',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });
            getrekapperafdeling();
        }

        function getregional() {
            let reg = document.getElementById('regional_select').value;
            let bulan = document.getElementById('inputbulan').value;
            // let estate = document.getElementById('estate_select').value;
            let _token = $('input[name="_token"]').val();
            $('#regional_estate').empty()
            $('#data_wil').empty()
            $('#data_mill').empty()

            $.ajax({
                url: "{{ route('gradingregional') }}",
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
                    let parseResult = JSON.parse(result)
                    let regionaldata = parseResult['data_regional']
                    let wil_data = parseResult['data_wil']
                    let mill_data = parseResult['data_mill']
                    let tbody = document.getElementById('regional_estate');
                    let tbodywill = document.getElementById('data_wil');
                    let tbodymill = document.getElementById('data_mill');
                    // console.log(wil_data);
                    // table regional 
                    Object.entries(regionaldata).forEach(([key, value]) => {
                        let tr = document.createElement('tr');

                        // Create an array to hold all the itemElements
                        let itemElements = [];

                        // Initialize itemElements array with 'td' elements
                        for (let index = 0; index < 29; index++) {
                            itemElements[index] = document.createElement('td');
                        }

                        // Assign text values to each itemElement
                        itemElements[0].innerText = key;
                        itemElements[1].innerText = value['regional']['jumlah_janjang_grading'].toLocaleString('id-ID');
                        itemElements[2].innerText = value['regional']['tonase'].toLocaleString('id-ID');
                        itemElements[3].innerText = value['regional']['ripeness'].toLocaleString('id-ID');
                        itemElements[4].innerText = value['regional']['percentage_ripeness'].toFixed(2);
                        itemElements[5].innerText = value['regional']['unripe'].toLocaleString('id-ID');
                        itemElements[6].innerText = value['regional']['percentage_unripe'].toFixed(2);
                        itemElements[7].innerText = value['regional']['overripe'].toLocaleString('id-ID');
                        itemElements[8].innerText = value['regional']['percentage_overripe'].toFixed(2);
                        itemElements[9].innerText = value['regional']['empty_bunch'].toLocaleString('id-ID');
                        itemElements[10].innerText = value['regional']['percentage_empty_bunch'].toFixed(2);
                        itemElements[11].innerText = value['regional']['rotten_bunch'].toLocaleString('id-ID');
                        itemElements[12].innerText = value['regional']['percentage_rotten_bunch'].toFixed(2);
                        itemElements[13].innerText = value['regional']['abnormal'].toLocaleString('id-ID');
                        itemElements[14].innerText = value['regional']['percentage_abnormal'].toFixed(2);
                        itemElements[15].innerText = value['regional']['longstalk'].toLocaleString('id-ID');
                        itemElements[16].innerText = value['regional']['percentage_longstalk'].toFixed(2);
                        itemElements[17].innerText = value['regional']['vcut'];
                        itemElements[18].innerText = value['regional']['percentage_vcut'].toFixed(2);
                        itemElements[19].innerText = value['regional']['dirt_kg'].toLocaleString('id-ID');
                        itemElements[20].innerText = value['regional']['percentage_dirt'].toFixed(2);
                        itemElements[21].innerText = value['regional']['loose_fruit_kg'].toLocaleString('id-ID');
                        itemElements[22].innerText = value['regional']['percentage_loose_fruit'].toFixed(2);
                        itemElements[23].innerText = value['regional']['kelas_c'].toLocaleString('id-ID');
                        itemElements[24].innerText = value['regional']['percentage_kelas_c'].toFixed(2);
                        itemElements[25].innerText = value['regional']['kelas_b'].toLocaleString('id-ID');
                        itemElements[26].innerText = value['regional']['percentage_kelas_b'].toFixed(2);
                        itemElements[27].innerText = value['regional']['kelas_a'].toLocaleString('id-ID')
                        itemElements[28].innerText = value['regional']['percentage_kelas_a'].toFixed(2);

                        // Append each itemElement to the tr
                        itemElements.forEach(itemElement => tr.appendChild(itemElement));

                        // Append the tr to the tbody
                        tbody.appendChild(tr);
                    });
                    //tabel wilayah 
                    Object.entries(wil_data).forEach(([key, value]) => {
                        let tr = document.createElement('tr');

                        // Create an array to hold all the itemElements
                        let itemElements = [];

                        // Initialize itemElements array with 'td' elements
                        for (let index = 0; index < 29; index++) {
                            itemElements[index] = document.createElement('td');
                        }

                        // Assign text values to each itemElement
                        itemElements[0].innerText = key;
                        itemElements[1].innerText = value['wil']['jumlah_janjang_grading'].toLocaleString('id-ID');
                        itemElements[2].innerText = value['wil']['tonase'].toLocaleString('id-ID');
                        itemElements[3].innerText = value['wil']['ripeness'].toLocaleString('id-ID');
                        itemElements[4].innerText = value['wil']['percentage_ripeness'].toFixed(2);
                        itemElements[5].innerText = value['wil']['unripe'].toLocaleString('id-ID');
                        itemElements[6].innerText = value['wil']['percentage_unripe'].toFixed(2);
                        itemElements[7].innerText = value['wil']['overripe'].toLocaleString('id-ID');
                        itemElements[8].innerText = value['wil']['percentage_overripe'].toFixed(2);
                        itemElements[9].innerText = value['wil']['empty_bunch'].toLocaleString('id-ID');
                        itemElements[10].innerText = value['wil']['percentage_empty_bunch'].toFixed(2);
                        itemElements[11].innerText = value['wil']['rotten_bunch'].toLocaleString('id-ID');
                        itemElements[12].innerText = value['wil']['percentage_rotten_bunch'].toFixed(2);
                        itemElements[13].innerText = value['wil']['abnormal'].toLocaleString('id-ID');
                        itemElements[14].innerText = value['wil']['percentage_abnormal'].toFixed(2);
                        itemElements[15].innerText = value['wil']['longstalk'].toLocaleString('id-ID');
                        itemElements[16].innerText = value['wil']['percentage_longstalk'].toFixed(2);
                        itemElements[17].innerText = value['wil']['vcut'].toLocaleString('id-ID');
                        itemElements[18].innerText = value['wil']['percentage_vcut'].toFixed(2);
                        itemElements[19].innerText = value['wil']['dirt_kg'].toLocaleString('id-ID');
                        itemElements[20].innerText = value['wil']['percentage_dirt'].toFixed(2);
                        itemElements[21].innerText = value['wil']['loose_fruit_kg'].toLocaleString('id-ID');
                        itemElements[22].innerText = value['wil']['percentage_loose_fruit'].toFixed(2);
                        itemElements[23].innerText = value['wil']['kelas_c'];
                        itemElements[24].innerText = value['wil']['percentage_kelas_c'].toFixed(2);
                        itemElements[25].innerText = value['wil']['kelas_b'];
                        itemElements[26].innerText = value['wil']['percentage_kelas_b'].toFixed(2);
                        itemElements[27].innerText = value['wil']['kelas_a'];
                        itemElements[28].innerText = value['wil']['percentage_kelas_a'].toFixed(2);

                        // Append each itemElement to the tr
                        itemElements.forEach(itemElement => tr.appendChild(itemElement));

                        // Append the tr to the tbody
                        tbodywill.appendChild(tr);
                    });
                    Object.entries(mill_data).forEach(([key, value]) => {
                        let tr = document.createElement('tr');

                        // Create an array to hold all the itemElements
                        let itemElements = [];

                        // Initialize itemElements array with 'td' elements
                        for (let index = 0; index < 29; index++) {
                            itemElements[index] = document.createElement('td');
                        }

                        // Assign text values to each itemElement
                        itemElements[0].innerText = key;
                        itemElements[0].colSpan = 2; // Sets the colspan attribute to 2
                        itemElements[1].innerText = value['mil']['jumlah_janjang_grading'].toLocaleString('id-ID');
                        itemElements[2].innerText = value['mil']['tonase'].toLocaleString('id-ID');
                        itemElements[3].innerText = value['mil']['ripeness'].toLocaleString('id-ID');
                        itemElements[4].innerText = value['mil']['percentage_ripeness'].toFixed(2);
                        itemElements[5].innerText = value['mil']['unripe'].toLocaleString('id-ID');
                        itemElements[6].innerText = value['mil']['percentage_unripe'].toFixed(2);
                        itemElements[7].innerText = value['mil']['overripe'].toLocaleString('id-ID');
                        itemElements[8].innerText = value['mil']['percentage_overripe'].toFixed(2);
                        itemElements[9].innerText = value['mil']['empty_bunch'].toLocaleString('id-ID');
                        itemElements[10].innerText = value['mil']['percentage_empty_bunch'].toFixed(2);
                        itemElements[11].innerText = value['mil']['rotten_bunch'].toLocaleString('id-ID');
                        itemElements[12].innerText = value['mil']['percentage_rotten_bunch'].toFixed(2);
                        itemElements[13].innerText = value['mil']['abnormal'].toLocaleString('id-ID');
                        itemElements[14].innerText = value['mil']['percentage_abnormal'].toFixed(2);
                        itemElements[15].innerText = value['mil']['longstalk'].toLocaleString('id-ID');
                        itemElements[16].innerText = value['mil']['percentage_longstalk'].toFixed(2);
                        itemElements[17].innerText = value['mil']['vcut'].toLocaleString('id-ID');
                        itemElements[18].innerText = value['mil']['percentage_vcut'].toFixed(2);
                        itemElements[19].innerText = value['mil']['dirt_kg'].toLocaleString('id-ID');
                        itemElements[20].innerText = value['mil']['percentage_dirt'].toFixed(2);
                        itemElements[21].innerText = value['mil']['loose_fruit_kg'].toLocaleString('id-ID');
                        itemElements[22].innerText = value['mil']['percentage_loose_fruit'].toFixed(2);
                        itemElements[23].innerText = value['mil']['kelas_c'];
                        itemElements[24].innerText = value['mil']['percentage_kelas_c'].toFixed(2);
                        itemElements[25].innerText = value['mil']['kelas_b'];
                        itemElements[26].innerText = value['mil']['percentage_kelas_b'].toFixed(2);
                        itemElements[27].innerText = value['mil']['kelas_a'];
                        itemElements[28].innerText = value['mil']['percentage_kelas_a'].toFixed(2);

                        // Append each itemElement to the tr
                        itemElements.forEach(itemElement => tr.appendChild(itemElement));

                        // Append the tr to the tbody
                        tbodymill.appendChild(tr);
                    });

                    Swal.close();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('AJAX Error:', textStatus, errorThrown);
                }
            });


        }

        function getrekapmill() {
            let reg = document.getElementById('regional_select_mill').value;
            let bulan = document.getElementById('inputbulan_mill').value;
            // let estate = document.getElementById('estate_select').value;
            let _token = $('input[name="_token"]').val();
            $('#rekap_mill').empty()

            $.ajax({
                url: "{{ route('gradingrekapmill') }}",
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
                    let parseResult = JSON.parse(result)
                    let mill_data = parseResult['data_mill']

                    let tbodymill = document.getElementById('rekap_mill');
                    Object.entries(mill_data).forEach(([key, value]) => {
                        let tr = document.createElement('tr');

                        // Create an array to hold all the itemElements
                        let itemElements = [];

                        // Initialize itemElements array with 'td' elements
                        for (let index = 0; index < 29; index++) {
                            itemElements[index] = document.createElement('td');
                        }

                        // Assign text values to each itemElement
                        itemElements[0].innerText = key;
                        // itemElements[0].colSpan = 2; // Sets the colspan attribute to 2
                        itemElements[1].innerText = value['mil']['jumlah_janjang_grading'].toLocaleString('id-ID');
                        itemElements[2].innerText = value['mil']['tonase'].toLocaleString('id-ID');
                        itemElements[3].innerText = value['mil']['ripeness'].toLocaleString('id-ID');
                        itemElements[4].innerText = value['mil']['percentage_ripeness'].toFixed(2);
                        itemElements[5].innerText = value['mil']['unripe'].toLocaleString('id-ID');
                        itemElements[6].innerText = value['mil']['percentage_unripe'].toFixed(2);
                        itemElements[7].innerText = value['mil']['overripe'].toLocaleString('id-ID');
                        itemElements[8].innerText = value['mil']['percentage_overripe'].toFixed(2);
                        itemElements[9].innerText = value['mil']['empty_bunch'].toLocaleString('id-ID');
                        itemElements[10].innerText = value['mil']['percentage_empty_bunch'].toFixed(2);
                        itemElements[11].innerText = value['mil']['rotten_bunch'].toLocaleString('id-ID');
                        itemElements[12].innerText = value['mil']['percentage_rotten_bunch'].toFixed(2);
                        itemElements[13].innerText = value['mil']['abnormal'].toLocaleString('id-ID');
                        itemElements[14].innerText = value['mil']['percentage_abnormal'].toFixed(2);
                        itemElements[15].innerText = value['mil']['longstalk'].toLocaleString('id-ID');
                        itemElements[16].innerText = value['mil']['percentage_longstalk'].toFixed(2);
                        itemElements[17].innerText = value['mil']['vcut'];
                        itemElements[18].innerText = value['mil']['percentage_vcut'].toFixed(2);
                        itemElements[19].innerText = value['mil']['dirt_kg'].toLocaleString('id-ID');
                        itemElements[20].innerText = value['mil']['percentage_dirt'].toFixed(2);
                        itemElements[21].innerText = value['mil']['loose_fruit_kg'].toLocaleString('id-ID');
                        itemElements[22].innerText = value['mil']['percentage_loose_fruit'].toFixed(2);
                        itemElements[23].innerText = value['mil']['kelas_c'].toLocaleString('id-ID');
                        itemElements[24].innerText = value['mil']['percentage_kelas_c'].toFixed(2);
                        itemElements[25].innerText = value['mil']['kelas_b'].toLocaleString('id-ID');
                        itemElements[26].innerText = value['mil']['percentage_kelas_b'].toFixed(2);
                        itemElements[27].innerText = value['mil']['kelas_a'].toLocaleString('id-ID');
                        itemElements[28].innerText = value['mil']['percentage_kelas_a'].toFixed(2);

                        // Append each itemElement to the tr
                        itemElements.forEach(itemElement => tr.appendChild(itemElement));

                        // Append the tr to the tbody
                        tbodymill.appendChild(tr);
                    });

                    Swal.close();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('AJAX Error:', textStatus, errorThrown);
                }
            });


        }

        document.getElementById('rekap_perhari').onclick = function() {
            Swal.fire({
                title: 'Loading',
                html: '<span class="loading-text">Mohon Tunggu...</span>',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });
            getrekapperhari();
        }

        function getrekapperhari() {
            let reg = document.getElementById('rekap_perhari_reg').value;
            let bulan = document.getElementById('input_rekap_perhari').value;
            let mill_id = document.getElementById('mill_id').value;
            // let estate = document.getElementById('estate_select').value;
            // console.log(reg);
            let _token = $('input[name="_token"]').val();
            $('#rekap_perhari_data').empty()
            console.log('rekap_perhari_data');
            $.ajax({
                url: "{{ route('getrekapperhari_dashboard') }}",
                method: "GET",
                data: {
                    reg: reg,
                    bulan: bulan,
                    mill_id: mill_id,
                    _token: _token
                },
                headers: {
                    'X-CSRF-TOKEN': _token
                },
                success: function(result) {
                    let parseResult = JSON.parse(result)

                    let tbodymill = document.getElementById('rekap_perhari_data');
                    Object.keys(parseResult).forEach(category => {
                        Object.keys(parseResult[category]).forEach(subcategory => {
                            const record = parseResult[category][subcategory];

                            // Create a new row
                            let tr = document.createElement('tr');

                            // If subcategory is 'Total', set background color to blue

                            // Create an array to hold all the itemElements
                            let itemElements = [];

                            // Initialize itemElements array with 'td' elements
                            for (let index = 0; index < 32; index++) {
                                itemElements[index] = document.createElement('td');
                            }
                            itemElements[0].innerText = category;
                            itemElements[1].innerText = subcategory;
                            itemElements[2].innerText = record['jumlah_janjang_spb'];
                            itemElements[3].innerText = record['jumlah_janjang_grading'];
                            itemElements[4].innerText = record['tonase'];
                            itemElements[5].innerText = record['bjr'].toFixed(2);
                            itemElements[6].innerText = record['ripeness'].toLocaleString('id-ID');
                            itemElements[7].innerText = record['percentage_ripeness'].toFixed(2);
                            itemElements[8].innerText = record['unripe'].toLocaleString('id-ID');
                            itemElements[9].innerText = record['percentage_unripe'].toFixed(2);
                            itemElements[10].innerText = record['overripe'].toLocaleString('id-ID');
                            itemElements[11].innerText = record['percentage_overripe'].toFixed(2);
                            itemElements[12].innerText = record['empty_bunch'].toLocaleString('id-ID');
                            itemElements[13].innerText = record['percentage_empty_bunch'].toFixed(2);
                            itemElements[14].innerText = record['rotten_bunch'].toLocaleString('id-ID');
                            itemElements[15].innerText = record['percentage_rotten_bunch'].toFixed(2);
                            itemElements[16].innerText = record['abnormal'].toLocaleString('id-ID');
                            itemElements[17].innerText = record['percentage_abnormal'].toFixed(2);
                            itemElements[18].innerText = record['longstalk'].toLocaleString('id-ID');
                            itemElements[19].innerText = record['percentage_longstalk'].toFixed(2);
                            itemElements[20].innerText = record['vcut'];
                            itemElements[21].innerText = record['percentage_vcut'].toFixed(2);
                            itemElements[22].innerText = record['dirt_kg'].toLocaleString('id-ID');
                            itemElements[23].innerText = record['percentage_dirt'].toFixed(2);
                            itemElements[24].innerText = record['loose_fruit_kg'].toLocaleString('id-ID');
                            itemElements[25].innerText = record['percentage_loose_fruit'].toFixed(2);
                            itemElements[26].innerText = record['kelas_c'].toLocaleString('id-ID');
                            itemElements[27].innerText = record['percentage_kelas_c'].toFixed(2);
                            itemElements[28].innerText = record['kelas_b'].toLocaleString('id-ID');
                            itemElements[29].innerText = record['percentage_kelas_b'].toFixed(2);
                            itemElements[30].innerText = record['kelas_a'].toLocaleString('id-ID');
                            itemElements[31].innerText = record['percentage_kelas_a'].toFixed(2);
                            itemElements.forEach(itemElement => tr.appendChild(itemElement));
                            if (subcategory === 'Total') {
                                itemElements.forEach(itemElement => {
                                    itemElement.style.backgroundColor = "#609cd4"; // Apply background color
                                    itemElement.style.color = "white"; // Optionally, make the text white for better contrast
                                });
                            }
                            // Append row to the table body
                            tbodymill.appendChild(tr);
                        });
                    });


                    Swal.close();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('AJAX Error:', textStatus, errorThrown);
                }
            });


        }

        function getrekapperafdeling() {
            let reg = document.getElementById('rekap_perfadeling_reg').value;
            let bulan = document.getElementById('input_rekap_perfadeling').value;
            // let estate = document.getElementById('estate_select').value;
            let _token = $('input[name="_token"]').val();
            $('#rekap_afdeling_data').empty()

            $.ajax({
                url: "{{ route('getrekapperafdeling') }}",
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
                    let parseResult = JSON.parse(result)
                    let rekap = parseResult['data_pperafd']
                    let tbody = document.getElementById('rekap_afdeling_data');
                    // console.log(rekap);
                    Object.entries(rekap).forEach(([key, value]) => {
                        Object.entries(value).forEach(([key1, value1]) => {
                            let tr = document.createElement('tr');

                            let itemElements = [];

                            // Initialize itemElements array with 'td' elements
                            for (let index = 0; index < 32; index++) {
                                itemElements[index] = document.createElement('td');
                            }
                            let link;
                            link = document.createElement('a');
                            link.href = 'detailgradingmill/' + key + '/' + key1 + '/' + bulan;
                            link.target = '_blank';
                            link.innerText = key1;
                            // Assign text values to each itemElement
                            itemElements[0].innerText = key
                            itemElements[1].appendChild(link); // Append link element to itemElements[1]
                            itemElements[2].innerText = value1['jumlah_janjang_spb'].toLocaleString('id-ID')
                            itemElements[3].innerText = value1['jumlah_janjang_grading'].toLocaleString('id-ID');
                            itemElements[4].innerText = value1['tonase'].toLocaleString('id-ID');
                            itemElements[5].innerText = value1['bjr'].toFixed(2).toLocaleString('id-ID');
                            itemElements[6].innerText = value1['ripeness'].toLocaleString('id-ID')
                            itemElements[7].innerText = value1['percentage_ripeness'].toFixed(2)
                            itemElements[8].innerText = value1['unripe'].toLocaleString('id-ID')
                            itemElements[9].innerText = value1['percentage_unripe'].toFixed(2)
                            itemElements[10].innerText = value1['overripe'].toLocaleString('id-ID')
                            itemElements[11].innerText = value1['percentage_overripe'].toFixed(2)
                            itemElements[12].innerText = value1['empty_bunch'].toLocaleString('id-ID')
                            itemElements[13].innerText = value1['percentage_empty_bunch'].toFixed(2)
                            itemElements[14].innerText = value1['rotten_bunch'].toLocaleString('id-ID')
                            itemElements[15].innerText = value1['percentage_rotten_bunch'].toFixed(2)
                            itemElements[16].innerText = value1['abnormal'].toLocaleString('id-ID')
                            itemElements[17].innerText = value1['percentage_abnormal'].toFixed(2)
                            itemElements[18].innerText = value1['longstalk'].toLocaleString('id-ID')
                            itemElements[19].innerText = value1['percentage_longstalk'].toFixed(2)
                            itemElements[20].innerText = value1['vcut'].toLocaleString('id-ID')
                            itemElements[21].innerText = value1['percentage_vcut'].toFixed(2)
                            itemElements[22].innerText = value1['dirt_kg'].toLocaleString('id-ID')
                            itemElements[23].innerText = value1['percentage_dirt'].toFixed(2)
                            itemElements[24].innerText = value1['loose_fruit_kg'].toLocaleString('id-ID')
                            itemElements[25].innerText = value1['percentage_loose_fruit'].toFixed(2)
                            itemElements[26].innerText = value1['kelas_c'].toLocaleString('id-ID')
                            itemElements[27].innerText = value1['percentage_kelas_c'].toFixed(2)
                            itemElements[28].innerText = value1['kelas_b'].toLocaleString('id-ID')
                            itemElements[29].innerText = value1['percentage_kelas_b'].toFixed(2)
                            itemElements[30].innerText = value1['kelas_a'].toLocaleString('id-ID')
                            itemElements[31].innerText = value1['percentage_kelas_a'].toFixed(2)

                            // Append each itemElement to the tr
                            itemElements.forEach(itemElement => tr.appendChild(itemElement));

                            // Append the tr to the tbody
                            tbody.appendChild(tr);

                        });
                    });

                    Swal.close();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('AJAX Error:', textStatus, errorThrown);
                }
            });


        }

        function handleExportSubmit(event, formId, regionalSelectId, dateInputId, hiddenRegionalId, hiddenDateId) {
            event.preventDefault();

            const regionalValue = document.getElementById(regionalSelectId).value;
            const dateValue = document.getElementById(dateInputId).value;

            document.getElementById(hiddenRegionalId).value = regionalValue;
            document.getElementById(hiddenDateId).value = dateValue;

            const newWindow = window.open('', '_blank');
            document.getElementById(formId).target = '_blank';
            document.getElementById(formId).submit();
            newWindow.close();
        }

        document.getElementById('exportForm').addEventListener('submit', function(event) {
            handleExportSubmit(event, 'exportForm', 'regional_select', 'inputbulan', 'getregional', 'getdate');
        });

        document.getElementById('exportFormdua').addEventListener('submit', function(event) {
            handleExportSubmit(event, 'exportFormdua', 'regional_select_mill', 'inputbulan_mill', 'getregionaldua', 'getdatedua');
        });
        document.getElementById('exportFormtiga').addEventListener('submit', function(event) {
            handleExportSubmit(event, 'exportFormtiga', 'rekap_perhari_reg', 'input_rekap_perhari', 'getregionaltiga', 'getdatetiga');
        });
        document.getElementById('exportFormempat').addEventListener('submit', function(event) {
            handleExportSubmit(event, 'exportFormempat', 'rekap_perfadeling_reg', 'input_rekap_perfadeling', 'getregionalempat', 'getdateempat');
        });
    </script>
</x-layout.app>