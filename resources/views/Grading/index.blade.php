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
                                {{csrf_field()}}
                                <input class="form-control" value="{{ date('Y-m') }}" type="month" name="inputbulan" id="inputbulan">
                            </div>
                            <div class="col-md">
                                <select class="form-select mb-2 mb-md-0" name="regional_id" id="regional_select" aria-label="Default select example">
                                    <option value="">Reg</option>
                                    @foreach ($regional as $items)
                                    <option value="{{$items['id']}}">{{$items['nama']}}</option>
                                    @endforeach
                                </select>

                            </div>
                            {{--<div class="col-md">
                                <form method="GET" action="{{ route('gradingdahsboard') }}" class="d-flex align-items-center">
                            <select class="form-select mb-2 mb-md-0" name="regional_id" id="regional_select" aria-label="Default select example" onchange="this.form.submit()">
                                <option value="">Reg</option>
                                @foreach ($regional as $items)
                                <option value="{{$items['id']}}" {{ $selectedRegionalId == $items['id'] ? 'selected' : '' }}>{{$items['nama']}}</option>
                                @endforeach
                            </select>

                            </form>

                        </div>--}}
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary ml-2" id="rekapregional">Show</button>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-primary">Excel</button>
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
                                <option value="">Reg</option>
                                @foreach ($regional as $items)
                                <option value="{{$items['id']}}">{{$items['nama']}}</option>
                                @endforeach
                            </select>

                        </div>
                        {{--<<div class="col-md">
                            <select class="form-select mb-2 mb-md-0" aria-label="Default select example">
                                <option selected>Est</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                        </div>>--}}
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary ml-2" id="rekapmill">Show</button>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-primary">Excel</button>
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

        <!-- rekap perhari  -->
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
                                <option value="">Reg</option>
                                @foreach ($regional as $items)
                                <option value="{{$items['id']}}">{{$items['nama']}}</option>
                                @endforeach
                            </select>
                        </div>
                        {{--<<div class="col-md">
                            <select class="form-select mb-2 mb-md-0" aria-label="Default select example">
                                <option selected>Est</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                        </div>>--}}
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary ml-2" id="rekap_perhari">Show</button>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-primary">Excel</button>
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
                            <th style="background-color: #f0ecec;" colspan="7">UNIT SORTASI</th>
                            <th style="background-color: #88e48c;" colspan="20">HASIL GRADING</th>
                            <th style="background-color: #f8c4ac;" colspan="6">KELAS JANJANG</th>
                        </tr>
                        <tr>
                            <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">NO POLISI</th>
                            <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">WAKTU GRADING</th>
                            <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">TONASE TIMBANGAN</th>
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
                                <option value="">Reg</option>
                                @foreach ($regional as $items)
                                <option value="{{$items['id']}}">{{$items['nama']}}</option>
                                @endforeach
                            </select>
                        </div>
                        {{--<<div class="col-md">
                            <select class="form-select mb-2 mb-md-0" aria-label="Default select example">
                                <option selected>Est</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                        </div>>--}}
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary ml-2" id="rekap_perfadeling">Show</button>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-primary">Excel</button>
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
                            <th style="background-color: #f0ecec;" colspan="5">UNIT SORTASI</th>
                            <th style="background-color: #88e48c;" colspan="20">HASIL GRADING</th>
                            <th style="background-color: #f8c4ac;" colspan="6">KELAS JANJANG</th>
                        </tr>
                        <tr>
                            <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">TONASE TIMBANGAN</th>
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
        document.getElementById('rekapregional').onclick = function() {
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
                        itemElements[1].innerText = value['regional']['jumlah_janjang_grading'];
                        itemElements[2].innerText = value['regional']['tonase'];
                        itemElements[3].innerText = value['regional']['ripeness'];
                        itemElements[4].innerText = value['regional']['percentage_ripeness'].toFixed(2);
                        itemElements[5].innerText = value['regional']['unripe'];
                        itemElements[6].innerText = value['regional']['percentage_unripe'].toFixed(2);
                        itemElements[7].innerText = value['regional']['overripe'];
                        itemElements[8].innerText = value['regional']['percentage_overripe'].toFixed(2);
                        itemElements[9].innerText = value['regional']['empty_bunch'];
                        itemElements[10].innerText = value['regional']['percentage_empty_bunch'].toFixed(2);
                        itemElements[11].innerText = value['regional']['rotten_bunch'];
                        itemElements[12].innerText = value['regional']['percentage_rotten_bunch'].toFixed(2);
                        itemElements[13].innerText = value['regional']['abnormal'];
                        itemElements[14].innerText = value['regional']['percentage_abnormal'].toFixed(2);
                        itemElements[15].innerText = value['regional']['longstalk'];
                        itemElements[16].innerText = value['regional']['percentage_longstalk'].toFixed(2);
                        itemElements[17].innerText = value['regional']['vcut'];
                        itemElements[18].innerText = value['regional']['percentage_vcut'].toFixed(2);
                        itemElements[19].innerText = value['regional']['dirt_kg'];
                        itemElements[20].innerText = value['regional']['percentage_dirt'].toFixed(2);
                        itemElements[21].innerText = value['regional']['loose_fruit_kg'];
                        itemElements[22].innerText = value['regional']['percentage_loose_fruit'].toFixed(2);
                        itemElements[23].innerText = value['regional']['kelas_a'];
                        itemElements[24].innerText = value['regional']['percentage_kelas_a'].toFixed(2);
                        itemElements[25].innerText = value['regional']['kelas_b'];
                        itemElements[26].innerText = value['regional']['percentage_kelas_a'].toFixed(2);
                        itemElements[27].innerText = value['regional']['kelas_c'];
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
                        itemElements[1].innerText = value['wil']['jumlah_janjang_grading'];
                        itemElements[2].innerText = value['wil']['tonase'];
                        itemElements[3].innerText = value['wil']['ripeness'];
                        itemElements[4].innerText = value['wil']['percentage_ripeness'].toFixed(2);
                        itemElements[5].innerText = value['wil']['unripe'];
                        itemElements[6].innerText = value['wil']['percentage_unripe'].toFixed(2);
                        itemElements[7].innerText = value['wil']['overripe'];
                        itemElements[8].innerText = value['wil']['percentage_overripe'].toFixed(2);
                        itemElements[9].innerText = value['wil']['empty_bunch'];
                        itemElements[10].innerText = value['wil']['percentage_empty_bunch'].toFixed(2);
                        itemElements[11].innerText = value['wil']['rotten_bunch'];
                        itemElements[12].innerText = value['wil']['percentage_rotten_bunch'].toFixed(2);
                        itemElements[13].innerText = value['wil']['abnormal'];
                        itemElements[14].innerText = value['wil']['percentage_abnormal'].toFixed(2);
                        itemElements[15].innerText = value['wil']['longstalk'];
                        itemElements[16].innerText = value['wil']['percentage_longstalk'].toFixed(2);
                        itemElements[17].innerText = value['wil']['vcut'];
                        itemElements[18].innerText = value['wil']['percentage_vcut'].toFixed(2);
                        itemElements[19].innerText = value['wil']['dirt_kg'];
                        itemElements[20].innerText = value['wil']['percentage_dirt'].toFixed(2);
                        itemElements[21].innerText = value['wil']['loose_fruit_kg'];
                        itemElements[22].innerText = value['wil']['percentage_loose_fruit'].toFixed(2);
                        itemElements[23].innerText = value['wil']['kelas_a'];
                        itemElements[24].innerText = value['wil']['percentage_kelas_a'].toFixed(2);
                        itemElements[25].innerText = value['wil']['kelas_b'];
                        itemElements[26].innerText = value['wil']['percentage_kelas_a'].toFixed(2);
                        itemElements[27].innerText = value['wil']['kelas_c'];
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
                        itemElements[1].innerText = value['mil']['jumlah_janjang_grading'];
                        itemElements[2].innerText = value['mil']['tonase'];
                        itemElements[3].innerText = value['mil']['ripeness'];
                        itemElements[4].innerText = value['mil']['percentage_ripeness'].toFixed(2);
                        itemElements[5].innerText = value['mil']['unripe'];
                        itemElements[6].innerText = value['mil']['percentage_unripe'].toFixed(2);
                        itemElements[7].innerText = value['mil']['overripe'];
                        itemElements[8].innerText = value['mil']['percentage_overripe'].toFixed(2);
                        itemElements[9].innerText = value['mil']['empty_bunch'];
                        itemElements[10].innerText = value['mil']['percentage_empty_bunch'].toFixed(2);
                        itemElements[11].innerText = value['mil']['rotten_bunch'];
                        itemElements[12].innerText = value['mil']['percentage_rotten_bunch'].toFixed(2);
                        itemElements[13].innerText = value['mil']['abnormal'];
                        itemElements[14].innerText = value['mil']['percentage_abnormal'].toFixed(2);
                        itemElements[15].innerText = value['mil']['longstalk'];
                        itemElements[16].innerText = value['mil']['percentage_longstalk'].toFixed(2);
                        itemElements[17].innerText = value['mil']['vcut'];
                        itemElements[18].innerText = value['mil']['percentage_vcut'].toFixed(2);
                        itemElements[19].innerText = value['mil']['dirt_kg'];
                        itemElements[20].innerText = value['mil']['percentage_dirt'].toFixed(2);
                        itemElements[21].innerText = value['mil']['loose_fruit_kg'];
                        itemElements[22].innerText = value['mil']['percentage_loose_fruit'].toFixed(2);
                        itemElements[23].innerText = value['mil']['kelas_a'];
                        itemElements[24].innerText = value['mil']['percentage_kelas_a'].toFixed(2);
                        itemElements[25].innerText = value['mil']['kelas_b'];
                        itemElements[26].innerText = value['mil']['percentage_kelas_a'].toFixed(2);
                        itemElements[27].innerText = value['mil']['kelas_c'];
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
                        itemElements[1].innerText = value['mil']['jumlah_janjang_grading'];
                        itemElements[2].innerText = value['mil']['tonase'];
                        itemElements[3].innerText = value['mil']['ripeness'];
                        itemElements[4].innerText = value['mil']['percentage_ripeness'].toFixed(2);
                        itemElements[5].innerText = value['mil']['unripe'];
                        itemElements[6].innerText = value['mil']['percentage_unripe'].toFixed(2);
                        itemElements[7].innerText = value['mil']['overripe'];
                        itemElements[8].innerText = value['mil']['percentage_overripe'].toFixed(2);
                        itemElements[9].innerText = value['mil']['empty_bunch'];
                        itemElements[10].innerText = value['mil']['percentage_empty_bunch'].toFixed(2);
                        itemElements[11].innerText = value['mil']['rotten_bunch'];
                        itemElements[12].innerText = value['mil']['percentage_rotten_bunch'].toFixed(2);
                        itemElements[13].innerText = value['mil']['abnormal'];
                        itemElements[14].innerText = value['mil']['percentage_abnormal'].toFixed(2);
                        itemElements[15].innerText = value['mil']['longstalk'];
                        itemElements[16].innerText = value['mil']['percentage_longstalk'].toFixed(2);
                        itemElements[17].innerText = value['mil']['vcut'];
                        itemElements[18].innerText = value['mil']['percentage_vcut'].toFixed(2);
                        itemElements[19].innerText = value['mil']['dirt_kg'];
                        itemElements[20].innerText = value['mil']['percentage_dirt'].toFixed(2);
                        itemElements[21].innerText = value['mil']['loose_fruit_kg'];
                        itemElements[22].innerText = value['mil']['percentage_loose_fruit'].toFixed(2);
                        itemElements[23].innerText = value['mil']['kelas_a'];
                        itemElements[24].innerText = value['mil']['percentage_kelas_a'].toFixed(2);
                        itemElements[25].innerText = value['mil']['kelas_b'];
                        itemElements[26].innerText = value['mil']['percentage_kelas_a'].toFixed(2);
                        itemElements[27].innerText = value['mil']['kelas_c'];
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

        function getrekapperhari() {
            let reg = document.getElementById('rekap_perhari_reg').value;
            let bulan = document.getElementById('input_rekap_perhari').value;
            // let estate = document.getElementById('estate_select').value;
            let _token = $('input[name="_token"]').val();
            $('#rekap_perhari_data').empty()

            $.ajax({
                url: "{{ route('getrekapperhari') }}",
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
                    let rekap = parseResult['data_perhari']
                    let tbody = document.getElementById('rekap_perhari_data');
                    // console.log(rekap);
                    Object.entries(rekap).forEach(([key, value]) => {
                        let tr = document.createElement('tr');

                        // Create an array to hold all the itemElements
                        let itemElements = [];

                        // Initialize itemElements array with 'td' elements
                        for (let index = 0; index < 35; index++) {
                            itemElements[index] = document.createElement('td');
                        }

                        // Assign text values to each itemElement
                        itemElements[0].innerText = value['estate'];
                        itemElements[1].innerText = value['afdeling'];
                        itemElements[2].innerText = value['no_plat'];
                        itemElements[3].innerText = value['datetime'];
                        itemElements[4].innerText = value['tonase']
                        itemElements[5].innerText = value['jjg_spb'];
                        itemElements[6].innerText = value['jjg_grading']
                        itemElements[7].innerText = value['tonase'];
                        itemElements[8].innerText = value['bjr']
                        itemElements[9].innerText = value['Ripeness'];
                        itemElements[10].innerText = value['percentase_ripenes']
                        itemElements[11].innerText = value['Unripe'];
                        itemElements[12].innerText = value['persenstase_unripe']
                        itemElements[13].innerText = value['Overripe'];
                        itemElements[14].innerText = value['persentase_overripe']
                        itemElements[15].innerText = value['empty_bunch'];
                        itemElements[16].innerText = value['persentase_empty_bunch']
                        itemElements[17].innerText = value['rotten_bunch'];
                        itemElements[18].innerText = value['persentase_rotten_bunce']
                        itemElements[19].innerText = value['Abnormal'];
                        itemElements[20].innerText = value['persentase_abnormal']
                        itemElements[21].innerText = value['stalk'];
                        itemElements[22].innerText = value['persentase_stalk']
                        itemElements[23].innerText = value['vcut'];
                        itemElements[24].innerText = value['persentase_vcut']
                        itemElements[25].innerText = value['Dirt'];
                        itemElements[26].innerText = value['persentase']
                        itemElements[27].innerText = value['loose_fruit'];
                        itemElements[28].innerText = value['persentase_lose_fruit']
                        itemElements[29].innerText = value['kelas_a']
                        itemElements[30].innerText = value['persentase_kelas_a']
                        itemElements[31].innerText = value['kelas_b']
                        itemElements[32].innerText = value['persentase_kelas_b']
                        itemElements[33].innerText = value['kelas_c']
                        itemElements[34].innerText = value['persentase_kelas_c']

                        // Append each itemElement to the tr
                        itemElements.forEach(itemElement => tr.appendChild(itemElement));

                        // Append the tr to the tbody
                        tbody.appendChild(tr);
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
                    console.log(rekap);
                    Object.entries(rekap).forEach(([key, value]) => {
                        Object.entries(value).forEach(([key1, value1]) => {
                            let tr = document.createElement('tr');

                            let itemElements = [];

                            // Initialize itemElements array with 'td' elements
                            for (let index = 0; index < 33; index++) {
                                itemElements[index] = document.createElement('td');
                            }

                            // Assign text values to each itemElement
                            itemElements[0].innerText = key
                            itemElements[1].innerText = key1;
                            itemElements[2].innerText = value1['tonase'];
                            itemElements[3].innerText = value1['jumlah_janjang_spb']
                            itemElements[4].innerText = value1['jumlah_janjang_grading'];
                            itemElements[5].innerText = value1['tonase']
                            itemElements[6].innerText = value1['bjr'];
                            itemElements[7].innerText = value1['ripeness']
                            itemElements[8].innerText = value1['percentage_ripeness'].toFixed(2)
                            itemElements[9].innerText = value1['unripe']
                            itemElements[10].innerText = value1['percentage_unripe'].toFixed(2)
                            itemElements[11].innerText = value1['overripe']
                            itemElements[12].innerText = value1['percentage_overripe'].toFixed(2)
                            itemElements[13].innerText = value1['empty_bunch']
                            itemElements[14].innerText = value1['percentage_empty_bunch'].toFixed(2)
                            itemElements[15].innerText = value1['rotten_bunch']
                            itemElements[16].innerText = value1['percentage_rotten_bunch'].toFixed(2)
                            itemElements[17].innerText = value1['abnormal']
                            itemElements[18].innerText = value1['percentage_abnormal'].toFixed(2)
                            itemElements[19].innerText = value1['longstalk']
                            itemElements[20].innerText = value1['percentage_longstalk'].toFixed(2)
                            itemElements[21].innerText = value1['vcut']
                            itemElements[22].innerText = value1['percentage_vcut'].toFixed(2)
                            itemElements[23].innerText = value1['dirt_kg']
                            itemElements[24].innerText = value1['percentage_dirt'].toFixed(2)
                            itemElements[25].innerText = value1['loose_fruit_kg']
                            itemElements[27].innerText = value1['percentage_loose_fruit'].toFixed(2)
                            itemElements[27].innerText = value1['kelas_c']
                            itemElements[28].innerText = value1['percentage_kelas_c'].toFixed(2)
                            itemElements[29].innerText = value1['kelas_b']
                            itemElements[30].innerText = value1['percentage_kelas_b'].toFixed(2)
                            itemElements[31].innerText = value1['kelas_a']
                            itemElements[32].innerText = value1['percentage_kelas_a'].toFixed(2)

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
    </script>
</x-layout.app>