<x-layout.app>


    <nav>
        <div class="nav nav-tabs px-4" id="nav-tab" role="tablist">
            <a class="nav-item nav-link active" id="nav-utama-tab" data-toggle="tab" href="#nav-utama" role="tab" aria-controls="nav-utama" aria-selected="true">Rekap</a>
            <a class="nav-item nav-link" id="nav-data-tab" data-toggle="tab" href="#nav-data" role="tab" aria-controls="nav-data" aria-selected="false">Data Perwilayah</a>

        </div>
    </nav>


    <div class="tab-content" id="nav-tabContent">
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
            <button class="btn btn-primary mb-4" style="float: right" id="btnShow">Show</button>
        </div>

        <div class="tab-pane fade show active" id="nav-utama" role="tabpanel" aria-labelledby="nav-utama-tab">
            <div class="card  px-4">
                <div class="card header">
                </div>
                <div class="card body">
                    <p class="text-center mt-5">REKAPITULASI SKOR QC PANEN, SIDAK TPH (MUTU TRANSPORT) & SIDAK MUTU BUAH <span id="judtahun"> </span> </p>
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-6 col-lg-4" data-regional="1" id="Tab1s">
                            <div class="table-responsive">
                                <table class=" table table-bordered" style="font-size: 13px;background-color:white" id="table1">
                                    <thead>
                                        <tr>
                                            <th id="wil1" colspan="5" class="text-center bg-gradient-primary">Wilayah</th>
                                        </tr>
                                        <tr class="text-center">
                                            <th>Estate</th>
                                            <th>Afdeling</th>
                                            <th>Nama</th>
                                            <th>Skor</th>
                                            <th>Rank</th>
                                        </tr>
                                    </thead>
                                    <tbody id="week1">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4" data-regional="1" id="Tab2s">
                            <div class="table-responsive">
                                <table class=" table table-bordered" style="font-size: 13px;background-color:white" id="table2">
                                    <thead>
                                        <tr>
                                            <th id="wil2" colspan="5" class="text-center bg-gradient-primary">Wilayah</th>
                                        </tr>
                                        <tr class="text-center">
                                            <th>Estate</th>
                                            <th>Afdeling</th>
                                            <th>Nama</th>
                                            <th>Skor</th>
                                            <th>Rank</th>
                                        </tr>
                                    </thead>
                                    <tbody id="week2">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4" data-regional="1" id="Tab3s">
                            <div class="table-responsive">
                                <table class="table table-bordered" style="font-size: 13px;background-color:white" id="table3">
                                    <thead>
                                        <tr>
                                            <th id="wil3" colspan="5" class="text-center bg-gradient-primary">Wilayah</th>
                                        </tr>
                                        <tr class="text-center">
                                            <th>Estate</th>
                                            <th>Afdeling</th>
                                            <th>Nama</th>
                                            <th>Skor</th>
                                            <th>Rank</th>
                                        </tr>
                                    </thead>
                                    <tbody id="week3">

                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <table class="table table-bordered">
                                <thead id="tbodySkorRHYear">
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card  px-4">
                <p class="text-center">Data Perminggu</p>
            </div>


            <div class="card body">
                <p class="text-center mt-5">REKAPITULASI SKOR QC PANEN, SIDAK TPH (MUTU TRANSPORT) & SIDAK MUTU BUAH Dalam Minggu <span id="rekapweektititle"> </span> </p>
                <div class="d-flex justify-content-end mt-3 mb-2 ml-3 mr-3" style="padding-top: 20px;">
                    <div class="row w-100 mobile-view">
                        <div class="col-lg-2 col-md-4 col-sm-6 col-6 offset-lg-8 offset-md-4 offset-sm-0 offset-3 form-container">
                            {{csrf_field()}}
                            <select class="form-control" name="regrekapweek" id="regrekapweek">
                                @foreach($option_reg as $key => $item)
                                <option value="{{ $item['id'] }}">{{ $item['nama'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-4 col-sm-6 col-6 form-container">
                            {{ csrf_field() }}
                            <input class="form-control" type="week" name="rekapweek" id="rekapweek" value="{{ date('Y') . '-W' . date('W') }}">
                        </div>
                    </div>
                    <button class="btn btn-primary mb-3 ml-3 custom-btn-height" id="btnrekapweek">Show</button>

                </div>
                <div class="row justify-content-center">
                    <div class="col-12 col-md-6 col-lg-4" data-regional="1" id="weekrekap1">
                        <div class="table-responsive">
                            <table class=" table table-bordered" style="font-size: 13px;background-color:white" id="table1">
                                <thead>
                                    <tr>
                                        <th id="rekapwil1" colspan="5" class="text-center bg-gradient-primary">Wilayah</th>
                                    </tr>
                                    <tr class="text-center">
                                        <th>Estate</th>
                                        <th>Afdeling</th>
                                        <th>Nama</th>
                                        <th>Skor</th>
                                        <th>Rank</th>
                                    </tr>
                                </thead>
                                <tbody id="rekapweek1">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4" data-regional="1" id="weekrekap2">
                        <div class="table-responsive">
                            <table class=" table table-bordered" style="font-size: 13px;background-color:white" id="table2">
                                <thead>
                                    <tr>
                                        <th id="rekapwil2" colspan="5" class="text-center bg-gradient-primary">Wilayah</th>
                                    </tr>
                                    <tr class="text-center">
                                        <th>Estate</th>
                                        <th>Afdeling</th>
                                        <th>Nama</th>
                                        <th>Skor</th>
                                        <th>Rank</th>
                                    </tr>
                                </thead>
                                <tbody id="rekapweek2">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4" data-regional="1" id="weekrekap3">
                        <div class="table-responsive">
                            <table class="table table-bordered" style="font-size: 13px;background-color:white" id="table3">
                                <thead>
                                    <tr>
                                        <th id="rekapwil3" colspan="5" class="text-center bg-gradient-primary">Wilayah</th>
                                    </tr>
                                    <tr class="text-center">
                                        <th>Estate</th>
                                        <th>Afdeling</th>
                                        <th>Nama</th>
                                        <th>Skor</th>
                                        <th>Rank</th>
                                    </tr>
                                </thead>
                                <tbody id="rekapweek3">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card  px-4">
                <p class="text-center">Data Pertahun</p>
            </div>

            <div class="card body">
                <p class="text-center mt-5">REKAPITULASI SKOR QC PANEN, SIDAK TPH (MUTU TRANSPORT) & SIDAK MUTU BUAH <span id="tahunest"> </span> </p>
                <div class="row justify-content-center">
                    <div class="col-12 col-md-6 col-lg-4" data-regional="1" id="yearTabs1">
                        <div class="table-responsive">
                            <table class=" table table-bordered" style="font-size: 13px;background-color:white" id="table1">
                                <thead>
                                    <tr>
                                        <th id="yearwil1" colspan="5" class="text-center bg-gradient-primary">Wilayah</th>
                                    </tr>
                                    <tr class="text-center">
                                        <th>Estate</th>
                                        <th>Afdeling</th>
                                        <th>Nama</th>
                                        <th>Skor</th>
                                        <th>Rank</th>
                                    </tr>
                                </thead>
                                <tbody id="yearest1">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4" data-regional="1" id="yearTabs2">
                        <div class="table-responsive">
                            <table class=" table table-bordered" style="font-size: 13px;background-color:white" id="table2">
                                <thead>
                                    <tr>
                                        <th id="yearwil2" colspan="5" class="text-center bg-gradient-primary">Wilayah</th>
                                    </tr>
                                    <tr class="text-center">
                                        <th>Estate</th>
                                        <th>Afdeling</th>
                                        <th>Nama</th>
                                        <th>Skor</th>
                                        <th>Rank</th>
                                    </tr>
                                </thead>
                                <tbody id="yearest2">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4" data-regional="1" id="yearTabs3">
                        <div class="table-responsive">
                            <table class="table table-bordered" style="font-size: 13px;background-color:white" id="table3">
                                <thead>
                                    <tr>
                                        <th id="yearwil3" colspan="5" class="text-center bg-gradient-primary">Wilayah</th>
                                    </tr>
                                    <tr class="text-center">
                                        <th>Estate</th>
                                        <th>Afdeling</th>
                                        <th>Nama</th>
                                        <th>Skor</th>
                                        <th>Rank</th>
                                    </tr>
                                </thead>
                                <tbody id="yearest3">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class=" tab-pane fade" id="nav-data" role="tabpanel" aria-labelledby="nav-data-tab">
            <div class="card  px-4">
                <div class="card header">
                </div>
                <div class="card body">
                    <p class="text-center mt-5">REKAPITULASI SKOR QC PANEN, SIDAK TPH (MUTU TRANSPORT) & SIDAK MUTU BUAH <span id="judtahunx"> </span> </p>

                    <div class="row justify-content-center">
                        <div class="col-12 col-md-6 col-lg-4" data-regional="1" id="Tab1">
                            <div class="table-responsive">
                                <table class=" table table-bordered" style="font-size: 13px;background-color:white" id="table1">
                                    <thead class="text-center">
                                        <tr>
                                            <th rowspan="3">Estate</th>
                                            <th rowspan="3">Afdeling</th>
                                            <th>Skor QC Panen</th>
                                            <th>Skor QC Sidak TPH</th>
                                            <th>Skor QC Mutu Buah</th>
                                        </tr>
                                        <tr id="thead1">
                                            <th>January</th>
                                            <th>January</th>
                                            <th>January</th>
                                        </tr>
                                    </thead>
                                    <tbody id="afd1">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4" data-regional="1" id="Tab2">
                            <div class="table-responsive">
                                <table class=" table table-bordered" style="font-size: 13px;background-color:white" id="table2">
                                    <thead class="text-center">
                                        <tr>
                                            <th rowspan="3">Estate</th>
                                            <th rowspan="3">Afdeling</th>
                                            <th>Skor QC Panen</th>
                                            <th>Skor QC Sidak TPH</th>
                                            <th>Skor QC Mutu Buah</th>
                                        </tr>
                                        <tr id="thead2">
                                            <th>January</th>
                                            <th>January</th>
                                            <th>January</th>
                                        </tr>
                                    </thead>
                                    <tbody id="afd2">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4" data-regional="1" id="Tab3">
                            <div class="table-responsive">
                                <table class="table table-bordered" style="font-size: 13px;background-color:white" id="table3">
                                    <thead class="text-center">
                                        <tr>
                                            <th rowspan="3">Estate</th>
                                            <th rowspan="3">Afdeling</th>
                                            <th>Skor QC Panen</th>
                                            <th>Skor QC Sidak TPH</th>
                                            <th>Skor QC Mutu Buah</th>
                                        </tr>
                                        <tr id="thead3">
                                            <th>January</th>
                                            <th>January</th>
                                            <th>January</th>
                                        </tr>
                                    </thead>
                                    <tbody id="afd3">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <table class="table table-bordered">
                                <thead id="rhqcinspeksi">
                                </thead>
                            </table>
                        </div>
                        <div class="col-sm-12">
                            <table class="table table-bordered">
                                <thead id="rhsidaktph">
                                </thead>
                            </table>
                        </div>
                        <div class="col-sm-12">
                            <table class="table table-bordered">
                                <thead id="rhmutubuah">
                                </thead>
                            </table>
                        </div>



                    </div>
                </div>
            </div>


            <div class="card  px-4">
                <p class="text-center">Data Perminggu</p>

            </div>
            <div class="card body">
                <p class="text-center mt-5">REKAPITULASI SKOR QC PANEN, SIDAK TPH (MUTU TRANSPORT) & SIDAK MUTU BUAH DALAM Minggu <span id="Tahunyear"> </span> </p>

                <div class="row justify-content-center">
                    <div class="col-12 col-md-6 col-lg-4" data-regional="1" id="weektabafd1">
                        <div class="table-responsive">
                            <table class=" table table-bordered" style="font-size: 13px;background-color:white" id="table1">
                                <thead class="text-center">
                                    <tr>
                                        <th rowspan="3">Estate</th>
                                        <th rowspan="3">Afdeling</th>
                                        <th>Skor QC Panen</th>
                                        <th>Skor QC Sidak TPH</th>
                                        <th>Skor QC Mutu Buah</th>
                                    </tr>
                                    <tr id="weekafdtr1">
                                        <th>January</th>
                                        <th>January</th>
                                        <th>January</th>
                                    </tr>
                                </thead>
                                <tbody id="weekafd1">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4" data-regional="1" id="weektabafd2">
                        <div class="table-responsive">
                            <table class=" table table-bordered" style="font-size: 13px;background-color:white" id="table2">
                                <thead class="text-center">
                                    <tr>
                                        <th rowspan="3">Estate</th>
                                        <th rowspan="3">Afdeling</th>
                                        <th>Skor QC Panen</th>
                                        <th>Skor QC Sidak TPH</th>
                                        <th>Skor QC Mutu Buah</th>
                                    </tr>
                                    <tr id="weekafdtr2">
                                        <th>January</th>
                                        <th>January</th>
                                        <th>January</th>
                                    </tr>
                                </thead>
                                <tbody id="weekafd2">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4" data-regional="1" id="weektabafd3">
                        <div class="table-responsive">
                            <table class="table table-bordered" style="font-size: 13px;background-color:white" id="table3">
                                <thead class="text-center">
                                    <tr>
                                        <th rowspan="3">Estate</th>
                                        <th rowspan="3">Afdeling</th>
                                        <th>Skor QC Panen</th>
                                        <th>Skor QC Sidak TPH</th>
                                        <th>Skor QC Mutu Buah</th>
                                    </tr>
                                    <tr id="weekafdtr3">
                                        <th>January</th>
                                        <th>January</th>
                                        <th>January</th>
                                    </tr>
                                </thead>
                                <tbody id="weekafd3">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card  px-4">
                <p class="text-center">Data Pertahun</p>
            </div>

            <div class="card body">
                <p class="text-center mt-5">REKAPITULASI SKOR QC PANEN, SIDAK TPH (MUTU TRANSPORT) & SIDAK MUTU BUAH DALAM <span id="Tahunyear"> 2024 </span> </p>

                <div class="row justify-content-center">
                    <div class="col-12 col-md-6 col-lg-4" data-regional="1" id="yearTab1">
                        <div class="table-responsive">
                            <table class=" table table-bordered" style="font-size: 13px;background-color:white" id="table1">
                                <thead class="text-center">
                                    <tr>
                                        <th rowspan="3">Estate</th>
                                        <th rowspan="3">Afdeling</th>
                                        <th>Skor QC Panen</th>
                                        <th>Skor QC Sidak TPH</th>
                                        <th>Skor QC Mutu Buah</th>
                                    </tr>
                                    <tr id="yearthead1">
                                        <th>January</th>
                                        <th>January</th>
                                        <th>January</th>
                                    </tr>
                                </thead>
                                <tbody id="year1">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4" data-regional="1" id="yearTab2">
                        <div class="table-responsive">
                            <table class=" table table-bordered" style="font-size: 13px;background-color:white" id="table2">
                                <thead class="text-center">
                                    <tr>
                                        <th rowspan="3">Estate</th>
                                        <th rowspan="3">Afdeling</th>
                                        <th>Skor QC Panen</th>
                                        <th>Skor QC Sidak TPH</th>
                                        <th>Skor QC Mutu Buah</th>
                                    </tr>
                                    <tr id="yearthead2">
                                        <th>January</th>
                                        <th>January</th>
                                        <th>January</th>
                                    </tr>
                                </thead>
                                <tbody id="year2">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4" data-regional="1" id="yearTab3">
                        <div class="table-responsive">
                            <table class="table table-bordered" style="font-size: 13px;background-color:white" id="table3">
                                <thead class="text-center">
                                    <tr>
                                        <th rowspan="3">Estate</th>
                                        <th rowspan="3">Afdeling</th>
                                        <th>Skor QC Panen</th>
                                        <th>Skor QC Sidak TPH</th>
                                        <th>Skor QC Mutu Buah</th>
                                    </tr>
                                    <tr id="yearthead3">
                                        <th>January</th>
                                        <th>January</th>
                                        <th>January</th>
                                    </tr>
                                </thead>
                                <tbody id="year3">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>





    <script type="module">
        var lokasiKerja = "{{ session('lok') }}";
        let regs
        $(document).ready(function() {

            // console.log(lokasiKerja);
            if (lokasiKerja == 'Regional II' || lokasiKerja == 'Regional 2') {
                $('#regionalPanen').val('2');
                $('#regrekapweek').val('2');

                regs = 2
            } else if (lokasiKerja == 'Regional III' || lokasiKerja == 'Regional 3') {
                $('#regionalPanen').val('3');
                $('#regrekapweek').val('3');
                regs = 3
            } else if (lokasiKerja == 'Regional IV' || lokasiKerja == 'Regional 4') {
                $('#regionalPanen').val('4');
                $('#regrekapweek').val('4');
                regs = 4
            } else if (lokasiKerja == 'Regional I' || lokasiKerja == 'Regional 1') {
                $('#regionalPanen').val('1');
                $('#regrekapweek').val('1');
                regs = 1
            }
            getdata()
            getdatayear()
            getdataweek()
            fixtable(regs)

            getdatarh()
        });

        function resetClassList(element) {
            element.classList.remove("col-md-6", "col-lg-3", "col-lg-4", "col-lg-6");
            element.classList.add("col-md-6");
        }

        function fixtable(regs) {

            // console.log(regs);
            const s = document.getElementById("Tab1");
            const m = document.getElementById("Tab2");
            const l = document.getElementById("Tab3");
            const satu = document.getElementById("Tab1s");
            const dua = document.getElementById("Tab2s");
            const tiga = document.getElementById("Tab3s");
            const yearTab1 = document.getElementById("yearTab1");
            const yearTab2 = document.getElementById("yearTab2");
            const yearTab3 = document.getElementById("yearTab3");
            const yearTabs1 = document.getElementById("yearTabs1");
            const yearTabs2 = document.getElementById("yearTabs2");
            const yearTabs3 = document.getElementById("yearTabs3");
            const weekrekap1 = document.getElementById('weekrekap1');
            const weekrekap2 = document.getElementById('weekrekap2');
            const weekrekap3 = document.getElementById('weekrekap3');
            const weektabafd1 = document.getElementById('weektabafd1');
            const weektabafd2 = document.getElementById('weektabafd2');
            const weektabafd3 = document.getElementById('weektabafd3');

            // const c = regs; // reg is already the number, no need for reg.value

            if (regs == 1 || regs == 2) {
                // console.log('Testing 1');
                s.style.display = "";
                m.style.display = "";
                l.style.display = "";
                resetClassList(s);
                resetClassList(m);
                resetClassList(l);
                s.classList.add("col-lg-4");
                m.classList.add("col-lg-4");
                l.classList.add("col-lg-4");

                satu.style.display = "";
                dua.style.display = "";
                tiga.style.display = "";
                resetClassList(satu);
                resetClassList(dua);
                resetClassList(tiga);
                satu.classList.add("col-lg-4");
                dua.classList.add("col-lg-4");
                tiga.classList.add("col-lg-4");

                yearTab1.style.display = "";
                yearTab2.style.display = "";
                yearTab3.style.display = "";
                resetClassList(yearTab1);
                resetClassList(yearTab2);
                resetClassList(yearTab3);
                yearTab1.classList.add("col-lg-4");
                yearTab2.classList.add("col-lg-4");
                yearTab3.classList.add("col-lg-4");

                yearTabs1.style.display = "";
                yearTabs2.style.display = "";
                yearTabs3.style.display = "";
                resetClassList(yearTabs1);
                resetClassList(yearTabs2);
                resetClassList(yearTabs3);
                yearTabs1.classList.add("col-lg-4");
                yearTabs2.classList.add("col-lg-4");
                yearTabs3.classList.add("col-lg-4");


                weekrekap1.style.display = "";
                weekrekap2.style.display = "";
                weekrekap3.style.display = "";
                resetClassList(weekrekap1);
                resetClassList(weekrekap2);
                resetClassList(weekrekap3);
                weekrekap1.classList.add("col-lg-4");
                weekrekap2.classList.add("col-lg-4");
                weekrekap3.classList.add("col-lg-4");

                weektabafd1.style.display = "";
                weektabafd2.style.display = "";
                weektabafd3.style.display = "";
                resetClassList(weektabafd1);
                resetClassList(weektabafd2);
                resetClassList(weektabafd3);
                weektabafd1.classList.add("col-lg-4");
                weektabafd2.classList.add("col-lg-4");
                weektabafd3.classList.add("col-lg-4");
            } else if (regs == 3 || regs == 4) {
                // console.log('Testing 2');
                s.style.display = "";
                m.style.display = "";
                l.style.display = "none";
                resetClassList(s);
                resetClassList(m);
                s.classList.add("col-lg-6");
                m.classList.add("col-lg-6");

                satu.style.display = "";
                dua.style.display = "";
                tiga.style.display = "none";
                resetClassList(satu);
                resetClassList(dua);
                satu.classList.add("col-lg-6");
                dua.classList.add("col-lg-6");
                yearTab1.style.display = "";
                yearTab2.style.display = "";
                yearTab3.style.display = "none";
                resetClassList(yearTab1);
                resetClassList(yearTab2);
                yearTab1.classList.add("col-lg-6");
                yearTab2.classList.add("col-lg-6");
                yearTabs1.style.display = "";
                yearTabs2.style.display = "";
                yearTabs3.style.display = "none";
                resetClassList(yearTabs1);
                resetClassList(yearTabs2);
                yearTabs1.classList.add("col-lg-6");
                yearTabs2.classList.add("col-lg-6");

                weekrekap1.style.display = "";
                weekrekap2.style.display = "";
                weekrekap3.style.display = "none";
                resetClassList(weekrekap1);
                resetClassList(weekrekap2);
                weekrekap1.classList.add("col-lg-6");
                weekrekap2.classList.add("col-lg-6");

                weektabafd1.style.display = "";
                weektabafd2.style.display = "";
                weektabafd3.style.display = "none";
                resetClassList(weektabafd1);
                resetClassList(weektabafd2);
                weektabafd1.classList.add("col-lg-6");
                weektabafd2.classList.add("col-lg-6");
            }
        }

        function showloading() {
            Swal.fire({
                title: 'Loading',
                html: '<span class="loading-text">Mohon Tunggu...</span>',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });
        }


        document.getElementById('btnShow').onclick = function() {
            var regs = document.getElementById('regionalPanen').value;
            fixtable(regs)
            getdata();
            getdatayear();
            getdatarh()
        }

        document.getElementById('btnrekapweek').onclick = function() {
            var regs = document.getElementById('regrekapweek').value;
            fixtable(regs)
            getdataweek();
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


        function getdataweek() {
            var reg = document.getElementById('regrekapweek').value;
            var week = document.getElementById('rekapweek').value;
            var _token = $('input[name="_token"]').val();

            $('#rekapweek1').empty()
            $('#rekapweek2').empty()
            $('#rekapweek3').empty()
            $('#weekafd1').empty()
            $('#weekafd2').empty()
            $('#weekafd3').empty()


            const tableRows = ['weekafdtr1', 'weekafdtr2', 'weekafdtr3'];

            tableRows.forEach(rowId => {
                const tableRow = document.getElementById(rowId);
                const tableHeaders = tableRow.querySelectorAll('th');

                tableHeaders.forEach(header => {
                    header.textContent = week;
                });
            });
            $.ajax({
                url: "{{ route('getdataweek') }}",
                method: "GET",
                data: {
                    reg: reg,
                    week: week,
                    _token: _token
                },
                headers: {
                    'X-CSRF-TOKEN': _token
                },
                success: function(result) {
                    var parseResult = JSON.parse(result)
                    var rekapafd = Object.entries(parseResult['rekapafd'])
                    var rekapmua = parseResult['rekapmua']
                    let table1 = rekapafd[0]
                    let table2 = rekapafd[1]
                    let table3 = rekapafd[2]

                    function assignValue(checkValue, compareValue, assignIfEqual, assignIfNotEqual) {
                        return checkValue === compareValue ? assignIfEqual : assignIfNotEqual;
                    }

                    // untk perestate 
                    var title1 = document.getElementById('rekapwil1');
                    let key1 = table1[0];


                    var trekap1 = document.getElementById('rekapweek1');
                    Object.keys(table1[1]).forEach(key => {
                        Object.keys(table1[1][key]).forEach(subKey => {
                            let item1 = table1[1][key][subKey]['est'];
                            let item2 = table1[1][key][subKey]['afd'];
                            let item3 = table1[1][key][subKey]['nama']
                            let item4 = table1[1][key][subKey]['total'];
                            // item4 = (item4 < 0) ? 0 : item4;
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


                    var title2 = document.getElementById('rekapwil2');
                    let key2 = table2[0];
                    if (reg == 4) {
                        title1.textContent = 'Wilayah Inti'
                        title2.textContent = 'Wilayah Plasma'
                    } else {
                        title2.textContent = 'Wilayah ' + key2;
                        title1.textContent = 'Wilayah ' + key1;
                    }

                    var trekap2 = document.getElementById('rekapweek2');
                    Object.keys(table2[1]).forEach(key => {
                        Object.keys(table2[1][key]).forEach(subKey => {
                            let item1 = table2[1][key][subKey]['est'];
                            let item2 = table2[1][key][subKey]['afd'];
                            let item3 = table2[1][key][subKey]['nama'] ?? '-'
                            let item4 = table2[1][key][subKey]['total'];
                            // item4 = (item4 < 0) ? 0 : item4;
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



                    //   perwilayah 

                    var tbody1 = document.getElementById('weekafd1');
                    Object.keys(table1[1]).forEach(key => {
                        // Iterate through the nested objects (OA, OB, etc.) within each main key
                        Object.keys(table1[1][key]).forEach(subKey => {
                            let item0 = table1[1][key][subKey]['est'];
                            let item1 = table1[1][key][subKey]['afd'];


                            const kosong = 'kosong';

                            let item2 = assignValue(
                                table1[1][key][subKey]['qc_check'],
                                kosong,
                                '-',
                                table1[1][key][subKey]['skor_qc']
                            );

                            let item3 = assignValue(
                                table1[1][key][subKey]['tph_check'],
                                kosong,
                                '-',
                                // table1[1][key][subKey]['skor_tph']
                                table1[1][key][subKey]['skor_tph']
                            );

                            let item4 = assignValue(
                                table1[1][key][subKey]['buah_check'],
                                kosong,
                                '-',
                                // table1[1][key][subKey]['skor_buah']
                                table1[1][key][subKey]['skor_buah']
                            );





                            let bg = table1[1][key][subKey]['bgcolor'];

                            // Create table row and cell for each 'total' value
                            let tr = document.createElement('tr');
                            let itemElement0 = document.createElement('td');
                            let itemElement1 = document.createElement('td');
                            let itemElement2 = document.createElement('td');
                            let itemElement3 = document.createElement('td');
                            let itemElement4 = document.createElement('td');

                            itemElement1.classList.add("text-center");
                            itemElement0.innerText = item0;
                            itemElement1.innerText = item1;
                            itemElement2.innerText = item2;
                            itemElement3.innerText = item3;
                            itemElement4.innerText = item4;

                            // Set background color style to the table row
                            tr.style.backgroundColor = bg;
                            setBackgroundColor(itemElement2, item2);
                            setBackgroundColor(itemElement3, item3);
                            setBackgroundColor(itemElement4, item4);
                            tr.appendChild(itemElement0)
                            tr.appendChild(itemElement1)
                            tr.appendChild(itemElement2)
                            tr.appendChild(itemElement3)
                            tr.appendChild(itemElement4)
                            tbody1.appendChild(tr);
                        });

                    });
                    var tbody2 = document.getElementById('weekafd2');
                    Object.keys(table2[1]).forEach(key => {
                        // Iterate through the nested objects (OA, OB, etc.) within each main key
                        Object.keys(table2[1][key]).forEach(subKey => {
                            let item0 = table2[1][key][subKey]['est'];
                            let item1 = table2[1][key][subKey]['afd'];


                            const kosong = 'kosong';

                            let item2 = assignValue(
                                table2[1][key][subKey]['qc_check'],
                                kosong,
                                '-',
                                table2[1][key][subKey]['skor_qc']
                            );

                            let item3 = assignValue(
                                table2[1][key][subKey]['tph_check'],
                                kosong,
                                '-',
                                // table2[1][key][subKey]['skor_tph']
                                table2[1][key][subKey]['skor_tph']
                            );

                            let item4 = assignValue(
                                table2[1][key][subKey]['buah_check'],
                                kosong,
                                '-',
                                // table2[1][key][subKey]['skor_buah']
                                table2[1][key][subKey]['skor_buah']
                            );







                            let bg = table2[1][key][subKey]['bgcolor'];

                            // Create table row and cell for each 'total' value
                            let tr = document.createElement('tr');
                            let itemElement0 = document.createElement('td');
                            let itemElement1 = document.createElement('td');
                            let itemElement2 = document.createElement('td');
                            let itemElement3 = document.createElement('td');
                            let itemElement4 = document.createElement('td');

                            itemElement1.classList.add("text-center");
                            itemElement0.innerText = item0;
                            itemElement1.innerText = item1;
                            itemElement2.innerText = item2;
                            itemElement3.innerText = item3;
                            itemElement4.innerText = item4;

                            // Set background color style to the table row
                            tr.style.backgroundColor = bg;
                            setBackgroundColor(itemElement2, item2);
                            setBackgroundColor(itemElement3, item3);
                            setBackgroundColor(itemElement4, item4);
                            tr.appendChild(itemElement0)
                            tr.appendChild(itemElement1)
                            tr.appendChild(itemElement2)
                            tr.appendChild(itemElement3)
                            tr.appendChild(itemElement4)
                            tbody2.appendChild(tr);
                        });
                    });

                    var tbody3 = document.getElementById('weekafd3');
                    Object.keys(table3[1]).forEach(key => {
                        // Iterate through the nested objects (OA, OB, etc.) within each main key
                        Object.keys(table3[1][key]).forEach(subKey => {
                            let item0 = table3[1][key][subKey]['est'];
                            let item1 = table3[1][key][subKey]['afd'];


                            const kosong = 'kosong';

                            let item2 = assignValue(
                                table3[1][key][subKey]['qc_check'],
                                kosong,
                                '-',
                                table3[1][key][subKey]['skor_qc']
                            );

                            let item3 = assignValue(
                                table3[1][key][subKey]['tph_check'],
                                kosong,
                                '-',
                                table3[1][key][subKey]['skor_tph']
                            );

                            let item4 = assignValue(
                                table3[1][key][subKey]['buah_check'],
                                kosong,
                                '-',
                                table3[1][key][subKey]['skor_buah']
                            );






                            let bg = table3[1][key][subKey]['bgcolor'];

                            // Create table row and cell for each 'total' value
                            let tr = document.createElement('tr');
                            let itemElement0 = document.createElement('td');
                            let itemElement1 = document.createElement('td');
                            let itemElement2 = document.createElement('td');
                            let itemElement3 = document.createElement('td');
                            let itemElement4 = document.createElement('td');

                            itemElement1.classList.add("text-center");
                            itemElement0.innerText = item0;
                            itemElement1.innerText = item1;
                            itemElement2.innerText = item2;
                            itemElement3.innerText = item3;
                            itemElement4.innerText = item4;

                            // Set background color style to the table row
                            tr.style.backgroundColor = bg;
                            setBackgroundColor(itemElement2, item2);
                            setBackgroundColor(itemElement3, item3);
                            setBackgroundColor(itemElement4, item4);
                            tr.appendChild(itemElement0)
                            tr.appendChild(itemElement1)
                            tr.appendChild(itemElement2)
                            tr.appendChild(itemElement3)
                            tr.appendChild(itemElement4)
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

                        itemElement1.innerText = key;
                        itemElement2.innerText = key;
                        itemElement3.innerText = value['skorqc'];
                        itemElement4.innerText = value['skortph'];
                        itemElement5.innerText = value['skor_mutubuah'];
                        setBackgroundColor(itemElement3, value['skorqc']);
                        setBackgroundColor(itemElement4, value['skortph']);
                        setBackgroundColor(itemElement5, value['skor_mutubuah']);
                        tr.appendChild(itemElement1);
                        tr.appendChild(itemElement2);
                        tr.appendChild(itemElement3);
                        tr.appendChild(itemElement4);
                        tr.appendChild(itemElement5);

                        tbody3.appendChild(tr);
                    });

                    var title3 = document.getElementById('rekapwil3');
                    let key = table3[0] ?? 'INTI';

                    title3.textContent = 'Wilayah ' + key;

                    var trekap3 = document.getElementById('rekapweek3');
                    Object.keys(table3[1]).forEach(key => {
                        Object.keys(table3[1][key]).forEach(subKey => {
                            let item1 = table3[1][key][subKey]['est'];
                            let item2 = table3[1][key][subKey]['afd'];
                            let item3 = table3[1][key][subKey]['nama']
                            let item4 = table3[1][key][subKey]['total'];
                            // item4 = (item4 < 0) ? 0 : item4;
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
                    Object.entries(rekapmua).forEach(([key, value]) => {
                        let tr = document.createElement('tr');

                        let itemElement1 = document.createElement('td');
                        let itemElement2 = document.createElement('td');
                        let itemElement3 = document.createElement('td');
                        let itemElement4 = document.createElement('td');
                        let itemElement5 = document.createElement('td');

                        itemElement1.innerText = key;
                        itemElement2.innerText = key;
                        itemElement3.innerText = value['asistenafd'] ?? value['manager'] ?? '-'
                        itemElement4.innerText = value['skorestate'];
                        itemElement5.innerText = '-'

                        setBackgroundColor(itemElement4, value['skorestate']);

                        tr.appendChild(itemElement1);
                        tr.appendChild(itemElement2);
                        tr.appendChild(itemElement3);
                        tr.appendChild(itemElement4);
                        tr.appendChild(itemElement5);

                        trekap3.appendChild(tr);
                    });


                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error Mengambil Data Pertahun.',
                    });
                    console.error(xhr.responseText);
                }

            });

        }


        function getdatayear() {
            showloading()

            var reg = document.getElementById('regionalPanen').value;
            var bulan = document.getElementById('inputbulan').value;
            var _token = $('input[name="_token"]').val();

            const dateParts = bulan.split('-'); // Split the string into parts
            const year = parseInt(dateParts[0]); // Extract the year
            const month = parseInt(dateParts[1]); // Extract the month
            $('#year1').empty()
            $('#year2').empty()
            $('#year3').empty()
            $('#yearest1').empty()
            $('#yearest2').empty()
            $('#yearest3').empty()

            const tableRows = ['yearthead1', 'yearthead2', 'yearthead3'];

            tableRows.forEach(rowId => {
                const tableRow = document.getElementById(rowId);
                const tableHeaders = tableRow.querySelectorAll('th');

                tableHeaders.forEach(header => {
                    header.textContent = year;
                });
            });
            // console.log(year);
            $.ajax({
                url: "{{ route('allskoreyear') }}",
                method: "GET",
                data: {
                    reg: reg,
                    bulan: year,
                    _token: _token
                },
                headers: {
                    'X-CSRF-TOKEN': _token
                },
                success: function(result) {
                    var parseResult = JSON.parse(result)
                    var rekapafd = Object.entries(parseResult['rekapafd'])
                    var rekapmua = parseResult['rekapmua']
                    Swal.close();

                    // console.log(rekapafd);
                    let table1 = rekapafd[0]
                    let table2 = rekapafd[1]
                    let table3 = rekapafd[2]

                    function assignValue(checkValue, compareValue, assignIfEqual, assignIfNotEqual) {
                        return checkValue === compareValue ? assignIfEqual : assignIfNotEqual;
                    }

                    // untk perestate 
                    var title1 = document.getElementById('yearwil1');
                    let key1 = table1[0];


                    var trekap1 = document.getElementById('yearest1');
                    Object.keys(table1[1]).forEach(key => {
                        Object.keys(table1[1][key]).forEach(subKey => {
                            let item1 = table1[1][key][subKey]['est'];
                            let item2 = table1[1][key][subKey]['afd'];
                            let item3 = table1[1][key][subKey]['nama']
                            let item4 = table1[1][key][subKey]['total'];
                            // item4 = (item4 < 0) ? 0 : item4;
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


                    var title2 = document.getElementById('yearwil2');
                    let key2 = table2[0];
                    if (reg == 4) {
                        title1.textContent = 'Wilayah Inti'
                        title2.textContent = 'Wilayah Plasma'
                    } else {
                        title2.textContent = 'Wilayah ' + key2;
                        title1.textContent = 'Wilayah ' + key1;
                    }

                    var trekap2 = document.getElementById('yearest2');
                    Object.keys(table2[1]).forEach(key => {
                        Object.keys(table2[1][key]).forEach(subKey => {
                            let item1 = table2[1][key][subKey]['est'];
                            let item2 = table2[1][key][subKey]['afd'];
                            let item3 = table2[1][key][subKey]['nama'] ?? '-'
                            let item4 = table2[1][key][subKey]['total'];
                            // item4 = (item4 < 0) ? 0 : item4;
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



                    //   perwilayah 

                    var tbody1 = document.getElementById('year1');
                    // console.log(table1);
                    // Iterate through the main object keys (KNE, PLE, etc.)
                    Object.keys(table1[1]).forEach(key => {
                        // Iterate through the nested objects (OA, OB, etc.) within each main key
                        Object.keys(table1[1][key]).forEach(subKey => {
                            let item0 = table1[1][key][subKey]['est'];
                            let item1 = table1[1][key][subKey]['afd'];


                            const kosong = 'kosong';
                            let item2 = assignValue(
                                table1[1][key][subKey]['qc_check'],
                                kosong,
                                '-',
                                table1[1][key][subKey]['skor_qc']
                            );

                            let item3 = assignValue(
                                table1[1][key][subKey]['tph_check'],
                                kosong,
                                '-',
                                table1[1][key][subKey]['skor_tph']
                            );

                            let item4 = assignValue(
                                table1[1][key][subKey]['buah_check'],
                                kosong,
                                '-',
                                table1[1][key][subKey]['skor_buah']
                            );




                            let bg = table1[1][key][subKey]['bgcolor'];

                            // Create table row and cell for each 'total' value
                            let tr = document.createElement('tr');
                            let itemElement0 = document.createElement('td');
                            let itemElement1 = document.createElement('td');
                            let itemElement2 = document.createElement('td');
                            let itemElement3 = document.createElement('td');
                            let itemElement4 = document.createElement('td');

                            itemElement1.classList.add("text-center");
                            itemElement0.innerText = item0;
                            itemElement1.innerText = item1;
                            itemElement2.innerText = item2;
                            itemElement3.innerText = item3;
                            itemElement4.innerText = item4;

                            // Set background color style to the table row
                            tr.style.backgroundColor = bg;
                            setBackgroundColor(itemElement2, item2);
                            setBackgroundColor(itemElement3, item3);
                            setBackgroundColor(itemElement4, item4);
                            tr.appendChild(itemElement0)
                            tr.appendChild(itemElement1)
                            tr.appendChild(itemElement2)
                            tr.appendChild(itemElement3)
                            tr.appendChild(itemElement4)
                            tbody1.appendChild(tr);
                        });

                    });
                    var tbody2 = document.getElementById('year2');
                    // Iterate through the main object keys (KNE, PLE, etc.)
                    Object.keys(table2[1]).forEach(key => {
                        // Iterate through the nested objects (OA, OB, etc.) within each main key
                        Object.keys(table2[1][key]).forEach(subKey => {
                            let item0 = table2[1][key][subKey]['est'];
                            let item1 = table2[1][key][subKey]['afd'];


                            const kosong = 'kosong';

                            let item2 = assignValue(
                                table2[1][key][subKey]['qc_check'],
                                kosong,
                                '-',
                                table2[1][key][subKey]['skor_qc']
                            );

                            let item3 = assignValue(
                                table2[1][key][subKey]['tph_check'],
                                kosong,
                                '-',
                                table2[1][key][subKey]['skor_tph']
                            );

                            let item4 = assignValue(
                                table2[1][key][subKey]['buah_check'],
                                kosong,
                                '-',
                                table2[1][key][subKey]['skor_buah']
                            );







                            let bg = table2[1][key][subKey]['bgcolor'];

                            // Create table row and cell for each 'total' value
                            let tr = document.createElement('tr');
                            let itemElement0 = document.createElement('td');
                            let itemElement1 = document.createElement('td');
                            let itemElement2 = document.createElement('td');
                            let itemElement3 = document.createElement('td');
                            let itemElement4 = document.createElement('td');

                            itemElement1.classList.add("text-center");
                            itemElement0.innerText = item0;
                            itemElement1.innerText = item1;
                            itemElement2.innerText = item2;
                            itemElement3.innerText = item3;
                            itemElement4.innerText = item4;

                            // Set background color style to the table row
                            tr.style.backgroundColor = bg;
                            setBackgroundColor(itemElement2, item2);
                            setBackgroundColor(itemElement3, item3);
                            setBackgroundColor(itemElement4, item4);
                            tr.appendChild(itemElement0)
                            tr.appendChild(itemElement1)
                            tr.appendChild(itemElement2)
                            tr.appendChild(itemElement3)
                            tr.appendChild(itemElement4)
                            tbody2.appendChild(tr);
                        });
                    });

                    var tbody3 = document.getElementById('year3');
                    Object.keys(table3[1]).forEach(key => {
                        // Iterate through the nested objects (OA, OB, etc.) within each main key
                        Object.keys(table3[1][key]).forEach(subKey => {
                            let item0 = table3[1][key][subKey]['est'];
                            let item1 = table3[1][key][subKey]['afd'];


                            const kosong = 'kosong';

                            let item2 = assignValue(
                                table3[1][key][subKey]['qc_check'],
                                kosong,
                                '-',
                                table3[1][key][subKey]['skor_qc']
                            );

                            let item3 = assignValue(
                                table3[1][key][subKey]['tph_check'],
                                kosong,
                                '-',
                                table3[1][key][subKey]['skor_tph']
                            );

                            let item4 = assignValue(
                                table3[1][key][subKey]['buah_check'],
                                kosong,
                                '-',
                                table3[1][key][subKey]['skor_buah']
                            );






                            let bg = table3[1][key][subKey]['bgcolor'];

                            // Create table row and cell for each 'total' value
                            let tr = document.createElement('tr');
                            let itemElement0 = document.createElement('td');
                            let itemElement1 = document.createElement('td');
                            let itemElement2 = document.createElement('td');
                            let itemElement3 = document.createElement('td');
                            let itemElement4 = document.createElement('td');

                            itemElement1.classList.add("text-center");
                            itemElement0.innerText = item0;
                            itemElement1.innerText = item1;
                            itemElement2.innerText = item2;
                            itemElement3.innerText = item3;
                            itemElement4.innerText = item4;

                            // Set background color style to the table row
                            tr.style.backgroundColor = bg;
                            setBackgroundColor(itemElement2, item2);
                            setBackgroundColor(itemElement3, item3);
                            setBackgroundColor(itemElement4, item4);
                            tr.appendChild(itemElement0)
                            tr.appendChild(itemElement1)
                            tr.appendChild(itemElement2)
                            tr.appendChild(itemElement3)
                            tr.appendChild(itemElement4)
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

                        itemElement1.innerText = key;
                        itemElement2.innerText = key;
                        itemElement3.innerText = value['skorqc'];
                        itemElement4.innerText = value['skortph'];
                        itemElement5.innerText = value['skor_mutubuah'];
                        setBackgroundColor(itemElement3, value['skorqc']);
                        setBackgroundColor(itemElement4, value['skortph']);
                        setBackgroundColor(itemElement5, value['skor_mutubuah']);
                        tr.appendChild(itemElement1);
                        tr.appendChild(itemElement2);
                        tr.appendChild(itemElement3);
                        tr.appendChild(itemElement4);
                        tr.appendChild(itemElement5);

                        tbody3.appendChild(tr);
                    });

                    var title3 = document.getElementById('yearwil3');
                    let key = table3[0] ?? 'INTI';

                    title3.textContent = 'Wilayah ' + key;

                    var trekap3 = document.getElementById('yearest3');
                    Object.keys(table3[1]).forEach(key => {
                        Object.keys(table3[1][key]).forEach(subKey => {
                            let item1 = table3[1][key][subKey]['est'];
                            let item2 = table3[1][key][subKey]['afd'];
                            let item3 = table3[1][key][subKey]['nama']
                            let item4 = table3[1][key][subKey]['total'];
                            // item4 = (item4 < 0) ? 0 : item4;
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
                    Object.entries(rekapmua).forEach(([key, value]) => {
                        let tr = document.createElement('tr');

                        let itemElement1 = document.createElement('td');
                        let itemElement2 = document.createElement('td');
                        let itemElement3 = document.createElement('td');
                        let itemElement4 = document.createElement('td');
                        let itemElement5 = document.createElement('td');

                        itemElement1.innerText = key;
                        itemElement2.innerText = key;
                        itemElement3.innerText = value['asistenafd'] ?? value['manager'] ?? '-'
                        itemElement4.innerText = value['skorestate'];
                        itemElement5.innerText = '-'

                        setBackgroundColor(itemElement4, value['skorestate']);

                        tr.appendChild(itemElement1);
                        tr.appendChild(itemElement2);
                        tr.appendChild(itemElement3);
                        tr.appendChild(itemElement4);
                        tr.appendChild(itemElement5);

                        trekap3.appendChild(tr);
                    });



                },
                error: function(xhr, status, error) {
                    // Handle the error, if any
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error Mengambil Data Pertahun.',
                    });
                    console.error(xhr.responseText);
                }

            });
        }



        function getdata() {
            // showloading()
            $('#afd1').empty()
            $('#afd2').empty()
            $('#afd3').empty()
            $('#week1').empty()
            $('#week2').empty()
            $('#week3').empty()
            var reg = document.getElementById('regionalPanen').value;
            var bulan = document.getElementById('inputbulan').value;
            var _token = $('input[name="_token"]').val();

            const dateParts = bulan.split('-'); // Split the string into parts
            const year = parseInt(dateParts[0]); // Extract the year
            const month = parseInt(dateParts[1]); // Extract the month

            // Creating a date object using the extracted year and month (assuming day is 01)
            const date = new Date(year, month - 1, 1);

            const monthName = date.toLocaleString('default', {
                month: 'long'
            });
            // console.log(monthName);

            const tableRows = ['thead1', 'thead2', 'thead3'];

            tableRows.forEach(rowId => {
                const tableRow = document.getElementById(rowId);
                const tableHeaders = tableRow.querySelectorAll('th');

                tableHeaders.forEach(header => {
                    header.textContent = monthName;
                });
            });

            const inputYearMonth = bulan + '-01'; // Adding '-01' to make it 'YYYY-MM-01' for parsing as a date
            const inputDate = new Date(inputYearMonth);
            const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

            const formattedDate = monthNames[inputDate.getMonth()] + ' ' + inputDate.getFullYear();

            var judul1 = document.getElementById('judtahun');
            var judul2 = document.getElementById('judtahunx');

            judul1.textContent = formattedDate;
            judul2.textContent = formattedDate;
            $.ajax({
                url: "{{ route('olahdata') }}",
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
                    // Swal.close();

                    var parseResult = JSON.parse(result)
                    var rekapafd = Object.entries(parseResult['rekapafd'])
                    var rekapmua = parseResult['rekapmua']
                    var rekapwil = Object.entries(parseResult['rekapwil'])
                    // console.log(rekapwil);
                    // console.log(rekapafd);
                    let table1 = rekapafd[0]
                    let table2 = rekapafd[1]
                    let table3 = rekapafd[2]
                    let tabwil = rekapwil[0]
                    let tabwi2 = rekapwil[1]
                    let tabwi3 = rekapwil[2]

                    // console.log(tabwil);
                    // console.log(tabwi2);
                    // console.log(tabwi3);

                    function assignValue(checkValue, compareValue, assignIfEqual, assignIfNotEqual) {
                        return checkValue === compareValue ? assignIfEqual : assignIfNotEqual;
                    }

                    // console.log(table1);
                    // untuk rekap 
                    var title1 = document.getElementById('wil1');
                    let key1 = table1[0];


                    var trekap1 = document.getElementById('week1');
                    Object.keys(table1[1]).forEach(key => {
                        Object.keys(table1[1][key]).forEach(subKey => {
                            let item1 = table1[1][key][subKey]['est'];
                            let item2 = table1[1][key][subKey]['afd'];
                            let item3 = table1[1][key][subKey]['nama']
                            let item4 = table1[1][key][subKey]['total'].toFixed(2);
                            // item4 = (item4 < 0) ? 0 : item4;
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



                    var title2 = document.getElementById('wil2');
                    let key2 = table2[0];

                    if (reg == 4) {
                        title1.textContent = 'Wilayah Inti'
                        title2.textContent = 'Wilayah Plasma'
                    } else {
                        title2.textContent = 'Wilayah ' + key2;
                        title1.textContent = 'Wilayah ' + key1;
                    }

                    var trekap2 = document.getElementById('week2');
                    Object.keys(table2[1]).forEach(key => {
                        Object.keys(table2[1][key]).forEach(subKey => {
                            let item1 = table2[1][key][subKey]['est'];
                            let item2 = table2[1][key][subKey]['afd'];
                            let item3 = table2[1][key][subKey]['nama'] ?? '-'
                            let item4 = table2[1][key][subKey]['total'].toFixed(2);
                            // item4 = (item4 < 0) ? 0 : item4;
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



                    // untuk perwilayah 
                    var tbody1 = document.getElementById('afd1');
                    // console.log(table1);
                    // Iterate through the main object keys (KNE, PLE, etc.)
                    Object.keys(table1[1]).forEach(key => {
                        // Iterate through the nested objects (OA, OB, etc.) within each main key
                        Object.keys(table1[1][key]).forEach(subKey => {
                            let item0 = table1[1][key][subKey]['est'];
                            let item1 = table1[1][key][subKey]['afd'];


                            const kosong = 'kosong';
                            let item2 = assignValue(
                                table1[1][key][subKey]['qc_check'],
                                kosong,
                                '-',
                                table1[1][key][subKey]['skor_qc'].toFixed(2)
                            );

                            let item3 = assignValue(
                                table1[1][key][subKey]['tph_check'],
                                kosong,
                                '-',
                                // table1[1][key][subKey]['skor_tph']
                                table1[1][key][subKey]['skor_tph'].toFixed(2)
                            );

                            let item4 = assignValue(
                                table1[1][key][subKey]['buah_check'],
                                kosong,
                                '-',
                                table1[1][key][subKey]['skor_buah'].toFixed(2)
                            );






                            let bg = table1[1][key][subKey]['bgcolor'];

                            // Create table row and cell for each 'total' value
                            let tr = document.createElement('tr');
                            let itemElement0 = document.createElement('td');
                            let itemElement1 = document.createElement('td');
                            let itemElement2 = document.createElement('td');
                            let itemElement3 = document.createElement('td');
                            let itemElement4 = document.createElement('td');

                            itemElement1.classList.add("text-center");
                            itemElement0.innerText = item0;
                            itemElement1.innerText = item1;
                            itemElement2.innerText = item2;
                            itemElement3.innerText = item3;
                            itemElement4.innerText = item4;

                            // Set background color style to the table row
                            tr.style.backgroundColor = bg;
                            setBackgroundColor(itemElement2, item2);
                            setBackgroundColor(itemElement3, item3);
                            setBackgroundColor(itemElement4, item4);
                            tr.appendChild(itemElement0)
                            tr.appendChild(itemElement1)
                            tr.appendChild(itemElement2)
                            tr.appendChild(itemElement3)
                            tr.appendChild(itemElement4)
                            tbody1.appendChild(tr);
                        });

                    });

                    // Assuming rekapmua is your array


                    var tbody2 = document.getElementById('afd2');
                    // Iterate through the main object keys (KNE, PLE, etc.)
                    Object.keys(table2[1]).forEach(key => {
                        // Iterate through the nested objects (OA, OB, etc.) within each main key
                        Object.keys(table2[1][key]).forEach(subKey => {
                            let item0 = table2[1][key][subKey]['est'];
                            let item1 = table2[1][key][subKey]['afd'];


                            const kosong = 'kosong';

                            let item2 = assignValue(
                                table2[1][key][subKey]['qc_check'],
                                kosong,
                                '-',
                                table2[1][key][subKey]['skor_qc'].toFixed(2)
                            );

                            let item3 = assignValue(
                                table2[1][key][subKey]['tph_check'],
                                kosong,
                                '-',
                                // table2[1][key][subKey]['skor_tph']
                                table2[1][key][subKey]['skor_tph'].toFixed(2)
                            );

                            let item4 = assignValue(
                                table2[1][key][subKey]['buah_check'],
                                kosong,
                                '-',
                                table2[1][key][subKey]['skor_buah'].toFixed(2)
                            );






                            let bg = table2[1][key][subKey]['bgcolor'];

                            // Create table row and cell for each 'total' value
                            let tr = document.createElement('tr');
                            let itemElement0 = document.createElement('td');
                            let itemElement1 = document.createElement('td');
                            let itemElement2 = document.createElement('td');
                            let itemElement3 = document.createElement('td');
                            let itemElement4 = document.createElement('td');

                            itemElement1.classList.add("text-center");
                            itemElement0.innerText = item0;
                            itemElement1.innerText = item1;
                            itemElement2.innerText = item2;
                            itemElement3.innerText = item3;
                            itemElement4.innerText = item4;

                            // Set background color style to the table row
                            tr.style.backgroundColor = bg;
                            setBackgroundColor(itemElement2, item2);
                            setBackgroundColor(itemElement3, item3);
                            setBackgroundColor(itemElement4, item4);
                            tr.appendChild(itemElement0)
                            tr.appendChild(itemElement1)
                            tr.appendChild(itemElement2)
                            tr.appendChild(itemElement3)
                            tr.appendChild(itemElement4)
                            tbody2.appendChild(tr);
                        });
                    });
                    getwil1(tabwil)
                    getwil2(tabwi2)
                    // console.log(tabwil);

                    var tbody3 = document.getElementById('afd3');
                    var title3 = document.getElementById('wil3');
                    let key = table3[0] ?? 'INTI';

                    title3.textContent = 'Wilayah ' + key;
                    // console.log(key);
                    // Iterate through the main object keys (KNE, PLE, etc.)
                    Object.keys(table3[1]).forEach(key => {
                        // Iterate through the nested objects (OA, OB, etc.) within each main key
                        Object.keys(table3[1][key]).forEach(subKey => {
                            let item0 = table3[1][key][subKey]['est'];
                            let item1 = table3[1][key][subKey]['afd'];


                            const kosong = 'kosong';

                            let item2 = assignValue(
                                table3[1][key][subKey]['qc_check'],
                                kosong,
                                '-',
                                table3[1][key][subKey]['skor_qc'].toFixed(2)
                            );

                            let item3 = assignValue(
                                table3[1][key][subKey]['tph_check'],
                                kosong,
                                '-',
                                table3[1][key][subKey]['skor_tph'].toFixed(2)
                            );

                            let item4 = assignValue(
                                table3[1][key][subKey]['buah_check'],
                                kosong,
                                '-',
                                table3[1][key][subKey]['skor_buah'].toFixed(2)
                            );





                            let bg = table3[1][key][subKey]['bgcolor'];

                            // Create table row and cell for each 'total' value
                            let tr = document.createElement('tr');
                            let itemElement0 = document.createElement('td');
                            let itemElement1 = document.createElement('td');
                            let itemElement2 = document.createElement('td');
                            let itemElement3 = document.createElement('td');
                            let itemElement4 = document.createElement('td');

                            itemElement1.classList.add("text-center");
                            itemElement0.innerText = item0;
                            itemElement1.innerText = item1;
                            itemElement2.innerText = item2;
                            itemElement3.innerText = item3;
                            itemElement4.innerText = item4;

                            // Set background color style to the table row
                            tr.style.backgroundColor = bg;
                            setBackgroundColor(itemElement2, item2);
                            setBackgroundColor(itemElement3, item3);
                            setBackgroundColor(itemElement4, item4);
                            tr.appendChild(itemElement0)
                            tr.appendChild(itemElement1)
                            tr.appendChild(itemElement2)
                            tr.appendChild(itemElement3)
                            tr.appendChild(itemElement4)
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

                        itemElement1.innerText = key;
                        itemElement2.innerText = key;
                        itemElement3.innerText = value['skorqc'];
                        itemElement4.innerText = value['skortph'];
                        itemElement5.innerText = value['skor_mutubuah'];
                        setBackgroundColor(itemElement3, value['skorqc']);
                        setBackgroundColor(itemElement4, value['skortph']);
                        setBackgroundColor(itemElement5, value['skor_mutubuah']);
                        tr.appendChild(itemElement1);
                        tr.appendChild(itemElement2);
                        tr.appendChild(itemElement3);
                        tr.appendChild(itemElement4);
                        tr.appendChild(itemElement5);

                        tbody3.appendChild(tr);
                    });



                    // untuk perestate 
                    var trekap3 = document.getElementById('week3');
                    Object.keys(table3[1]).forEach(key => {
                        Object.keys(table3[1][key]).forEach(subKey => {
                            let item1 = table3[1][key][subKey]['est'];
                            let item2 = table3[1][key][subKey]['afd'];
                            let item3 = table3[1][key][subKey]['nama']
                            let item4 = table3[1][key][subKey]['total'];
                            // item4 = (item4 < 0) ? 0 : item4;
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
                    Object.entries(rekapmua).forEach(([key, value]) => {
                        let tr = document.createElement('tr');

                        let itemElement1 = document.createElement('td');
                        let itemElement2 = document.createElement('td');
                        let itemElement3 = document.createElement('td');
                        let itemElement4 = document.createElement('td');
                        let itemElement5 = document.createElement('td');

                        itemElement1.innerText = key;
                        itemElement2.innerText = key;
                        itemElement3.innerText = value['asistenafd'] ?? value['manager'] ?? '-'
                        itemElement4.innerText = value['skorestate'];
                        itemElement5.innerText = '-'

                        setBackgroundColor(itemElement4, value['skorestate']);

                        tr.appendChild(itemElement1);
                        tr.appendChild(itemElement2);
                        tr.appendChild(itemElement3);
                        tr.appendChild(itemElement4);
                        tr.appendChild(itemElement5);

                        trekap3.appendChild(tr);
                    });

                    getwil3(tabwi3)



                },
                error: function(xhr, status, error) {
                    // Handle the error, if any
                    console.error(xhr.responseText);
                }
            });

            function getwil1(tabwil) {

                // console.log(tabwil);
                var tbody1 = document.getElementById('afd1');
                let item1 = 'WIL'
                let item2 = 'GM'
                let item3 = tabwil[1]['qcinspeks']
                let item4 = tabwil[1]['sidaktph']
                let item5 = tabwil[1]['sidakmutubuah']

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

                setBackgroundColor(itemElement3, item3);
                setBackgroundColor(itemElement4, item4);
                setBackgroundColor(itemElement5, item5);


                tr.appendChild(itemElement1)
                tr.appendChild(itemElement2)
                tr.appendChild(itemElement3)
                tr.appendChild(itemElement4)
                tr.appendChild(itemElement5)

                tbody1.appendChild(tr);

                var trekap1 = document.getElementById('week1');
                let item1a = 'WIL'
                let item2a = 'GM'
                let item3a = tabwil[1]['gmnama']
                let item4a = tabwil[1]['gmrekap']
                let item5a = '-'

                // Create table row and cell for each 'total' value
                let tra = document.createElement('tr');
                let itemElement1a = document.createElement('td');
                let itemElement2a = document.createElement('td');
                let itemElement3a = document.createElement('td');
                let itemElement4a = document.createElement('td');
                let itemElement5a = document.createElement('td');



                itemElement1a.classList.add("text-center");
                itemElement1a.innerText = item1a;
                itemElement2a.innerText = item2a;
                itemElement3a.innerText = item3a;
                itemElement4a.innerText = item4a;
                itemElement5a.innerText = item5a

                setBackgroundColor(itemElement4a, item4a);


                tra.appendChild(itemElement1a)
                tra.appendChild(itemElement2a)
                tra.appendChild(itemElement3a)
                tra.appendChild(itemElement4a)
                tra.appendChild(itemElement5a)

                trekap1.appendChild(tra);
            }

            function getwil2(tabwi2) {
                var tbody1 = document.getElementById('afd2');
                let item1 = 'WIL'
                let item2 = 'GM'
                let item3 = tabwi2[1]['qcinspeks']
                let item4 = tabwi2[1]['sidaktph']
                let item5 = tabwi2[1]['sidakmutubuah']

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

                setBackgroundColor(itemElement3, item3);
                setBackgroundColor(itemElement4, item4);
                setBackgroundColor(itemElement5, item5);


                tr.appendChild(itemElement1)
                tr.appendChild(itemElement2)
                tr.appendChild(itemElement3)
                tr.appendChild(itemElement4)
                tr.appendChild(itemElement5)
                tbody1.appendChild(tr);

                var trekap1 = document.getElementById('week2');
                let item1a = 'WIL'
                let item2a = 'GM'
                let item3a = tabwi2[1]['gmnama']
                let item4a = tabwi2[1]['gmrekap']
                let item5a = '-'

                // Create table row and cell for each 'total' value
                let tra = document.createElement('tr');
                let itemElement1a = document.createElement('td');
                let itemElement2a = document.createElement('td');
                let itemElement3a = document.createElement('td');
                let itemElement4a = document.createElement('td');
                let itemElement5a = document.createElement('td');



                itemElement1a.classList.add("text-center");
                itemElement1a.innerText = item1a;
                itemElement2a.innerText = item2a;
                itemElement3a.innerText = item3a;
                itemElement4a.innerText = item4a;
                itemElement5a.innerText = item5a

                setBackgroundColor(itemElement4a, item4a);


                tra.appendChild(itemElement1a)
                tra.appendChild(itemElement2a)
                tra.appendChild(itemElement3a)
                tra.appendChild(itemElement4a)
                tra.appendChild(itemElement5a)

                trekap1.appendChild(tra);
            }

            function getwil3(tabwi3) {
                var tbody1 = document.getElementById('afd3');
                let item1 = 'WIL'
                let item2 = 'GM'
                let item3 = tabwi3[1]['qcinspeks']
                let item4 = tabwi3[1]['sidaktph']
                let item5 = tabwi3[1]['sidakmutubuah']

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

                setBackgroundColor(itemElement3, item3);
                setBackgroundColor(itemElement4, item4);
                setBackgroundColor(itemElement5, item5);


                tr.appendChild(itemElement1)
                tr.appendChild(itemElement2)
                tr.appendChild(itemElement3)
                tr.appendChild(itemElement4)
                tr.appendChild(itemElement5)
                tbody1.appendChild(tr);

                var trekap1 = document.getElementById('week3');
                let item1a = 'WIL'
                let item2a = 'GM'
                let item3a = tabwi3[1]['gmnama']
                let item4a = tabwi3[1]['gmrekap']
                let item5a = '-'

                // Create table row and cell for each 'total' value
                let tra = document.createElement('tr');
                let itemElement1a = document.createElement('td');
                let itemElement2a = document.createElement('td');
                let itemElement3a = document.createElement('td');
                let itemElement4a = document.createElement('td');
                let itemElement5a = document.createElement('td');



                itemElement1a.classList.add("text-center");
                itemElement1a.innerText = item1a;
                itemElement2a.innerText = item2a;
                itemElement3a.innerText = item3a;
                itemElement4a.innerText = item4a;
                itemElement5a.innerText = item5a

                setBackgroundColor(itemElement4a, item4a);


                tra.appendChild(itemElement1a)
                tra.appendChild(itemElement2a)
                tra.appendChild(itemElement3a)
                tra.appendChild(itemElement4a)
                tra.appendChild(itemElement5a)

                trekap1.appendChild(tra);
            }

        }

        function getdatarh() {
            var reg = document.getElementById('regionalPanen').value;
            var bulan = document.getElementById('inputbulan').value;
            var _token = $('input[name="_token"]').val();
            $('#tbodySkorRHYear').empty()
            $('#rhmutubuah').empty()
            $('#rhsidaktph').empty()
            $('#rhqcinspeksi').empty()
            $.ajax({
                url: "{{ route('getmonthrh') }}",
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
                    var parseResult = JSON.parse(result)
                    var rhresult = parseResult['rhresult']
                    var rhsidakbuah = parseResult['rhsidakbuah']
                    var rhsidaktphx = parseResult['rhsidaktph']
                    var rhqcinspeksix = parseResult['rhqcinspeksi']

                    // console.log(rhresult);

                    getrhrekap(rhresult)
                    getrhsidakmtb(rhsidakbuah)
                    getrhsidaktph(rhsidaktphx)
                    getrhqcinspeksi(rhqcinspeksix)

                    // // console.log(regional);





                },
                error: function(xhr, status, error) {
                    // Handle the error, if any
                    console.error(xhr.responseText);
                }
            });

            function getrhrekap(rhresult) {
                var theadreg = document.getElementById('tbodySkorRHYear');
                tr = document.createElement('tr')
                let reg1 = rhresult[0]['est']
                let reg2 = rhresult[0]['jab']
                let reg3 = rhresult[0]['nama']
                let reg4 = rhresult[0]['skor']
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
            }

            function getrhsidakmtb(rhsidakbuah) {
                var theadreg = document.getElementById('rhmutubuah');
                tr = document.createElement('tr')
                let reg1 = rhsidakbuah[0]['est']
                let reg2 = rhsidakbuah[0]['jab']
                let reg3 = 'Mutu buah'
                let reg4 = rhsidakbuah[0]['nama']
                let reg5 = rhsidakbuah[0]['skor']
                // let reg4 = '-'
                let regElement1 = document.createElement('td')
                let regElement2 = document.createElement('td')
                let regElement3 = document.createElement('td')
                let regElement4 = document.createElement('td')
                let regElement5 = document.createElement('td')

                regElement1.classList.add("text-center")
                regElement2.classList.add("text-center")
                regElement3.classList.add("text-center")
                regElement4.classList.add("text-center")
                regElement5.classList.add("text-center")

                regElement1.innerText = reg1;
                regElement2.innerText = reg2;
                regElement3.innerText = reg3;
                regElement4.innerText = reg4;
                regElement5.innerText = reg5;
                setBackgroundColor(regElement5, reg5);
                tr.appendChild(regElement1)
                tr.appendChild(regElement2)
                tr.appendChild(regElement3)
                tr.appendChild(regElement4)
                tr.appendChild(regElement5)

                theadreg.appendChild(tr)
            }

            function getrhsidaktph(rhsidaktphx) {
                var theadreg = document.getElementById('rhsidaktph');
                tr = document.createElement('tr')
                let reg1 = rhsidaktphx[0]['est']
                let reg2 = rhsidaktphx[0]['jab']
                let reg3 = 'Mutu transport'
                let reg4 = rhsidaktphx[0]['nama']
                let reg5 = rhsidaktphx[0]['skor']
                // let reg4 = '-'
                let regElement1 = document.createElement('td')
                let regElement2 = document.createElement('td')
                let regElement3 = document.createElement('td')
                let regElement4 = document.createElement('td')
                let regElement5 = document.createElement('td')

                regElement1.classList.add("text-center")
                regElement2.classList.add("text-center")
                regElement3.classList.add("text-center")
                regElement4.classList.add("text-center")
                regElement5.classList.add("text-center")

                regElement1.innerText = reg1;
                regElement2.innerText = reg2;
                regElement3.innerText = reg3;
                regElement4.innerText = reg4;
                regElement5.innerText = reg5;
                setBackgroundColor(regElement5, reg5);
                tr.appendChild(regElement1)
                tr.appendChild(regElement2)
                tr.appendChild(regElement3)
                tr.appendChild(regElement4)
                tr.appendChild(regElement5)


                theadreg.appendChild(tr)
            }

            function getrhqcinspeksi(rhqcinspeksix) {
                var theadreg = document.getElementById('rhqcinspeksi');
                tr = document.createElement('tr')
                let reg1 = rhqcinspeksix[0]['est']
                let reg2 = rhqcinspeksix[0]['jab']
                let reg3 = 'Panen Reguler'
                let reg4 = rhqcinspeksix[0]['nama']
                let reg5 = rhqcinspeksix[0]['skor']
                // let reg4 = '-'
                let regElement1 = document.createElement('td')
                let regElement2 = document.createElement('td')
                let regElement3 = document.createElement('td')
                let regElement4 = document.createElement('td')
                let regElement5 = document.createElement('td')

                regElement1.classList.add("text-center")
                regElement2.classList.add("text-center")
                regElement3.classList.add("text-center")
                regElement4.classList.add("text-center")
                regElement5.classList.add("text-center")

                regElement1.innerText = reg1;
                regElement2.innerText = reg2;
                regElement3.innerText = reg3;
                regElement4.innerText = reg4;
                regElement5.innerText = reg5;
                setBackgroundColor(regElement5, reg5);
                tr.appendChild(regElement1)
                tr.appendChild(regElement2)
                tr.appendChild(regElement3)
                tr.appendChild(regElement4)
                tr.appendChild(regElement5)
                theadreg.appendChild(tr)
            }
        }
    </script>

</x-layout.app>