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
                                <select class="form-select mb-2 mb-md-0" aria-label="Default select example">
                                    <option selected>Month</option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                </select>
                            </div>
                            <div class="col-md">
                                <select class="form-select mb-2 mb-md-0" aria-label="Default select example">
                                    <option selected>Reg</option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                </select>
                            </div>
                            <div class="col-md">
                                <select class="form-select mb-2 mb-md-0" aria-label="Default select example">
                                    <option selected>Est</option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-primary">Show</button>
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
                    </table>

                    <table class="mt-5 table table-responsive table-striped table-bordered">
                        <thead>
                            <tr>
                                <th colspan="31" style="background-color: #c8e4f4;">BERDASARKAN WILAYAH</th>
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
                    </table>

                    <table class="mt-5  table table-responsive table-striped table-bordered">
                        <thead>
                            <tr>
                                <th colspan="31" style="background-color: #c8e4f4;">BERDASARKAN MILL</th>
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
                                <select class="form-select mb-2 mb-md-0" aria-label="Default select example">
                                    <option selected>Month</option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                </select>
                            </div>
                            <div class="col-md">
                                <select class="form-select mb-2 mb-md-0" aria-label="Default select example">
                                    <option selected>Reg</option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                </select>
                            </div>
                            <div class="col-md">
                                <select class="form-select mb-2 mb-md-0" aria-label="Default select example">
                                    <option selected>Est</option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-primary">Show</button>
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
                                <select class="form-select mb-2 mb-md-0" aria-label="Default select example">
                                    <option selected>Month</option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                </select>
                            </div>
                            <div class="col-md">
                                <select class="form-select mb-2 mb-md-0" aria-label="Default select example">
                                    <option selected>Reg</option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                </select>
                            </div>
                            <div class="col-md">
                                <select class="form-select mb-2 mb-md-0" aria-label="Default select example">
                                    <option selected>Est</option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-primary">Show</button>
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
                                <select class="form-select mb-2 mb-md-0" aria-label="Default select example">
                                    <option selected>Month</option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                </select>
                            </div>
                            <div class="col-md">
                                <select class="form-select mb-2 mb-md-0" aria-label="Default select example">
                                    <option selected>Reg</option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                </select>
                            </div>
                            <div class="col-md">
                                <select class="form-select mb-2 mb-md-0" aria-label="Default select example">
                                    <option selected>Est</option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-primary">Show</button>
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
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layout.app>