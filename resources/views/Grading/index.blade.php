<x-layout.app>
    <style>
        .table {
            table-layout: auto;
            /* Allow the table to adjust based on content */
            width: 100%;
            /* Ensure the table takes up the full available width */
        }

        /* th,
        td {
            white-space: nowrap;
        } */

        th,
        td {
            word-wrap: break-word;
            /* Breaks long text into multiple lines */
        }

        th {
            text-align: center;
            /* Center align the headers */
        }

        td {
            padding: 5px;
            /* Add some padding for better readability */
        }

        /* td:nth-child(3),
        th:nth-child(3) {
            width: auto;
            color: red;
            min-width: 150px;
        } */

        td.no-polisi,
        th.no-polisi {
            width: auto;
            min-width: 150px;
        }
    </style>
    <div class="card">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link active" id="nav-utama-tab" data-toggle="tab" href="#nav-utama" role="tab" aria-controls="nav-utama" aria-selected="true">Rekap Regional</a>
                <a class="nav-item nav-link" id="nav-mill-tab" data-toggle="tab" href="#nav-mill" role="tab" aria-controls="nav-mill" aria-selected="false">Rekap Mill</a>
                <a class="nav-item nav-link" id="nav-perhari-tab" data-toggle="tab" href="#nav-perhari" role="tab" aria-controls="nav-perhari" aria-selected="false">Rekap Perhari</a>
                <a class="nav-item nav-link" id="nav-afdeling-tab" data-toggle="tab" href="#nav-afdeling" role="tab" aria-controls="nav-afdeling" aria-selected="false">Rekap Afdeling</a>
                {{--

                <a class="nav-item nav-link" id="nav-pertanggal-tab" data-toggle="tab" href="#nav-pertanggal" role="tab" aria-controls="nav-pertanggal" aria-selected="false">Data</a>
          --}}
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
                                <th colspan="35" style="background-color: #c8e4f4;">BERDASARKAN ESTATE</th>
                            </tr>
                            <tr>
                                <th rowspan="3" class="align-middle" style="background-color: #f0ecec;">Estate</th>
                                <th style="background-color: #f0ecec;" colspan="2">UNIT SORTASI</th>
                                <th style="background-color: #88e48c;" colspan="20">HASIL GRADING</th>
                                <th style="background-color: #f8c4ac;" colspan="6">KELAS JANJANG</th>
                                <th style="background-color: #B1A1C6;" colspan="4">BUAH MENTAH</th>
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
                                <th style="background-color: #B1A1C6;" colspan="2">TIDAK BRONDOL</th>
                                <th style="background-color: #B1A1C6;" colspan="2">KURANG BRONDOL</th>
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
                                <th style="background-color: #B1A1C6;">JJG</th>
                                <th style="background-color: #B1A1C6;">%</th>
                                <th style="background-color: #B1A1C6;">JJG</th>
                                <th style="background-color: #B1A1C6;">%</th>
                            </tr>
                        </thead>
                        <tbody id="regional_estate">

                        </tbody>
                    </table>

                    <table class="mt-5 table table-responsive table-striped table-bordered">
                        <thead>
                            <tr>
                                <th colspan="35" style="background-color: #c8e4f4;">BERDASARKAN WILAYAH</th>
                            </tr>
                            <tr>
                                <th rowspan="3" class="align-middle" style="background-color: #f0ecec;">Wilayah</th>
                                <th style="background-color: #f0ecec;" colspan="2">UNIT SORTASI</th>
                                <th style="background-color: #88e48c;" colspan="20">HASIL GRADING</th>
                                <th style="background-color: #f8c4ac;" colspan="6">KELAS JANJANG</th>
                                <th style="background-color: #B1A1C6;" colspan="4">BUAH MENTAH</th>
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
                                <th style="background-color: #B1A1C6;" colspan="2">TIDAK BRONDOL</th>
                                <th style="background-color: #B1A1C6;" colspan="2">KURANG BRONDOL</th>
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
                                <th style="background-color: #B1A1C6;">JJG</th>
                                <th style="background-color: #B1A1C6;">%</th>
                                <th style="background-color: #B1A1C6;">JJG</th>
                                <th style="background-color: #B1A1C6;">%</th>
                            </tr>
                        </thead>
                        <tbody id="data_wil"></tbody>
                    </table>

                    <table class="mt-5  table table-responsive table-striped table-bordered">
                        <thead>
                            <tr>
                                <th colspan="35" style="background-color: #c8e4f4;">BERDASARKAN MILL</th>
                            </tr>
                            <tr>
                                <th rowspan="3" colspan="2" class="align-middle" style="background-color: #f0ecec;">MILL</th>
                                <th style="background-color: #f0ecec;" colspan="2">UNIT SORTASI</th>
                                <th style="background-color: #88e48c;" colspan="20">HASIL GRADING</th>
                                <th style="background-color: #f8c4ac;" colspan="6">KELAS JANJANG</th>
                                <th style="background-color: #B1A1C6;" colspan="4">BUAH MENTAH</th>
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
                                <th style="background-color: #B1A1C6;" colspan="2">TIDAK BRONDOL</th>
                                <th style="background-color: #B1A1C6;" colspan="2">KURANG BRONDOL</th>
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
                                <th style="background-color: #B1A1C6;">JJG</th>
                                <th style="background-color: #B1A1C6;">%</th>
                                <th style="background-color: #B1A1C6;">JJG</th>
                                <th style="background-color: #B1A1C6;">%</th>
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

                @livewire('GradingRekapMill')
            </div>
            <div class="tab-pane fade" id="nav-perhari" role="tabpanel" aria-labelledby="nav-perhari-tab">
                <div class="text-center border border-3 mt-4 ml-3 mr-3 mb-10">
                    <h1>REKAPITULASI LAPORAN GRADING PKS</h1>
                </div>

                @livewire('gradingmill')

            </div>
            <!-- rekap afdeling  -->
            <div class="tab-pane fade" id="nav-afdeling" role="tabpanel" aria-labelledby="nav-afdeling-tab">
                <div class="text-center border border-3 mt-4 ml-3 mr-3 mb-10">
                    <h1>REKAPITULASI LAPORAN GRADING PKS</h1>
                </div>
                @livewire('gradingmill-rekap-afdeling')

            </div>
            <div class="tab-pane fade" id="nav-pertanggal" role="tabpanel" aria-labelledby="nav-pertanggal-tab">
                <div class="text-center border border-3 mt-4 ml-3 mr-3 mb-10">
                    <h1>REKAPITULASI LAPORAN GRADING PKS</h1>
                </div>
                @livewire('gradingmill-rekap-pertanggal')
            </div>
        </div>
    </div>

    <!-- modal  -->

    <!-- Modal -->
    <div class="modal fade" id="dataModal" tabindex="-1" role="dialog" aria-labelledby="dataModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dataModalLabel">Data Table</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table id="data-table" class="table table-striped table-bordered">

                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
                        for (let index = 0; index < 33; index++) {
                            itemElements[index] = document.createElement('td');
                        }

                        // console.log(value);

                        // Assign text values to each itemElement
                        itemElements[0].innerText = key;
                        itemElements[1].innerText = value['data']['jjg_grading'].toLocaleString('id-ID');
                        itemElements[2].innerText = value['data']['tonase'].toLocaleString('id-ID');
                        itemElements[3].innerText = value['data']['ripeness'].toLocaleString('id-ID');
                        itemElements[4].innerText = value['data']['percentage_ripeness'].toFixed(2);
                        itemElements[5].innerText = value['data']['unripe'].toLocaleString('id-ID');
                        itemElements[6].innerText = value['data']['percentage_unripe'].toFixed(2);
                        itemElements[7].innerText = value['data']['overripe'].toLocaleString('id-ID');
                        itemElements[8].innerText = value['data']['percentage_overripe'].toFixed(2);
                        itemElements[9].innerText = value['data']['empty_bunch'].toLocaleString('id-ID');
                        itemElements[10].innerText = value['data']['percentage_empty_bunch'].toFixed(2);
                        itemElements[11].innerText = value['data']['rotten_bunch'].toLocaleString('id-ID');
                        itemElements[12].innerText = value['data']['percentage_rotten_bunch'].toFixed(2);
                        itemElements[13].innerText = value['data']['abnormal'].toLocaleString('id-ID');
                        itemElements[14].innerText = value['data']['percentage_abnormal'].toFixed(2);
                        itemElements[15].innerText = value['data']['longstalk'].toLocaleString('id-ID');
                        itemElements[16].innerText = value['data']['percentage_longstalk'].toFixed(2);
                        itemElements[17].innerText = value['data']['vcut'];
                        itemElements[18].innerText = value['data']['percentage_vcut'].toFixed(2);
                        itemElements[19].innerText = value['data']['dirt'].toLocaleString('id-ID');
                        itemElements[20].innerText = value['data']['percentage_dirt'].toFixed(2);
                        itemElements[21].innerText = value['data']['loose_fruit'].toLocaleString('id-ID');
                        itemElements[22].innerText = value['data']['percentage_loose_fruit'].toFixed(2);
                        itemElements[23].innerText = value['data']['kelas_c'].toLocaleString('id-ID');
                        itemElements[24].innerText = value['data']['percentage_kelas_c'].toFixed(2);
                        itemElements[25].innerText = value['data']['kelas_b'].toLocaleString('id-ID');
                        itemElements[26].innerText = value['data']['percentage_kelas_b'].toFixed(2);
                        itemElements[27].innerText = value['data']['kelas_a'].toLocaleString('id-ID')
                        itemElements[28].innerText = value['data']['percentage_kelas_a'].toFixed(2);
                        itemElements[29].innerText = value['data']['unripe_tanpa_brondol']
                        itemElements[30].innerText = value['data']['persentase_unripe_tanpa_brondol'].toFixed(2);
                        itemElements[31].innerText = value['data']['unripe_kurang_brondol']
                        itemElements[32].innerText = value['data']['persentase_unripe_kurang_brondol'].toFixed(2);

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
                        for (let index = 0; index < 33; index++) {
                            itemElements[index] = document.createElement('td');
                        }

                        // Assign text values to each itemElement
                        itemElements[0].innerText = key;
                        itemElements[1].innerText = value['data']['jjg_grading'].toLocaleString('id-ID');
                        itemElements[2].innerText = value['data']['tonase'].toLocaleString('id-ID');
                        itemElements[3].innerText = value['data']['ripeness'].toLocaleString('id-ID');
                        itemElements[4].innerText = value['data']['percentage_ripeness'].toFixed(2);
                        itemElements[5].innerText = value['data']['unripe'].toLocaleString('id-ID');
                        itemElements[6].innerText = value['data']['percentage_unripe'].toFixed(2);
                        itemElements[7].innerText = value['data']['overripe'].toLocaleString('id-ID');
                        itemElements[8].innerText = value['data']['percentage_overripe'].toFixed(2);
                        itemElements[9].innerText = value['data']['empty_bunch'].toLocaleString('id-ID');
                        itemElements[10].innerText = value['data']['percentage_empty_bunch'].toFixed(2);
                        itemElements[11].innerText = value['data']['rotten_bunch'].toLocaleString('id-ID');
                        itemElements[12].innerText = value['data']['percentage_rotten_bunch'].toFixed(2);
                        itemElements[13].innerText = value['data']['abnormal'].toLocaleString('id-ID');
                        itemElements[14].innerText = value['data']['percentage_abnormal'].toFixed(2);
                        itemElements[15].innerText = value['data']['longstalk'].toLocaleString('id-ID');
                        itemElements[16].innerText = value['data']['percentage_longstalk'].toFixed(2);
                        itemElements[17].innerText = value['data']['vcut'].toLocaleString('id-ID');
                        itemElements[18].innerText = value['data']['percentage_vcut'].toFixed(2);
                        itemElements[19].innerText = value['data']['dirt'].toLocaleString('id-ID');
                        itemElements[20].innerText = value['data']['percentage_dirt'].toFixed(2);
                        itemElements[21].innerText = value['data']['loose_fruit'].toLocaleString('id-ID');
                        itemElements[22].innerText = value['data']['percentage_loose_fruit'].toFixed(2);
                        itemElements[23].innerText = value['data']['kelas_c'];
                        itemElements[24].innerText = value['data']['percentage_kelas_c'].toFixed(2);
                        itemElements[25].innerText = value['data']['kelas_b'];
                        itemElements[26].innerText = value['data']['percentage_kelas_b'].toFixed(2);
                        itemElements[27].innerText = value['data']['kelas_a'];
                        itemElements[28].innerText = value['data']['percentage_kelas_a'].toFixed(2);
                        itemElements[29].innerText = value['data']['unripe_tanpa_brondol']
                        itemElements[30].innerText = value['data']['persentase_unripe_tanpa_brondol'].toFixed(2);
                        itemElements[31].innerText = value['data']['unripe_kurang_brondol']
                        itemElements[32].innerText = value['data']['persentase_unripe_kurang_brondol'].toFixed(2);

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
                        for (let index = 0; index < 33; index++) {
                            itemElements[index] = document.createElement('td');
                        }

                        // Assign text values to each itemElement
                        itemElements[0].innerText = key;
                        itemElements[0].colSpan = 2; // Sets the colspan attribute to 2
                        itemElements[1].innerText = value['data']['jjg_grading'].toLocaleString('id-ID');
                        itemElements[2].innerText = value['data']['tonase'].toLocaleString('id-ID');
                        itemElements[3].innerText = value['data']['ripeness'].toLocaleString('id-ID');
                        itemElements[4].innerText = value['data']['percentage_ripeness'].toFixed(2);
                        itemElements[5].innerText = value['data']['unripe'].toLocaleString('id-ID');
                        itemElements[6].innerText = value['data']['percentage_unripe'].toFixed(2);
                        itemElements[7].innerText = value['data']['overripe'].toLocaleString('id-ID');
                        itemElements[8].innerText = value['data']['percentage_overripe'].toFixed(2);
                        itemElements[9].innerText = value['data']['empty_bunch'].toLocaleString('id-ID');
                        itemElements[10].innerText = value['data']['percentage_empty_bunch'].toFixed(2);
                        itemElements[11].innerText = value['data']['rotten_bunch'].toLocaleString('id-ID');
                        itemElements[12].innerText = value['data']['percentage_rotten_bunch'].toFixed(2);
                        itemElements[13].innerText = value['data']['abnormal'].toLocaleString('id-ID');
                        itemElements[14].innerText = value['data']['percentage_abnormal'].toFixed(2);
                        itemElements[15].innerText = value['data']['longstalk'].toLocaleString('id-ID');
                        itemElements[16].innerText = value['data']['percentage_longstalk'].toFixed(2);
                        itemElements[17].innerText = value['data']['vcut'].toLocaleString('id-ID');
                        itemElements[18].innerText = value['data']['percentage_vcut'].toFixed(2);
                        itemElements[19].innerText = value['data']['dirt'].toLocaleString('id-ID');
                        itemElements[20].innerText = value['data']['percentage_dirt'].toFixed(2);
                        itemElements[21].innerText = value['data']['loose_fruit'].toLocaleString('id-ID');
                        itemElements[22].innerText = value['data']['percentage_loose_fruit'].toFixed(2);
                        itemElements[23].innerText = value['data']['kelas_c'];
                        itemElements[24].innerText = value['data']['percentage_kelas_c'].toFixed(2);
                        itemElements[25].innerText = value['data']['kelas_b'];
                        itemElements[26].innerText = value['data']['percentage_kelas_b'].toFixed(2);
                        itemElements[27].innerText = value['data']['kelas_a'];
                        itemElements[28].innerText = value['data']['percentage_kelas_a'].toFixed(2);
                        itemElements[29].innerText = value['data']['unripe_tanpa_brondol']
                        itemElements[30].innerText = value['data']['persentase_unripe_tanpa_brondol'].toFixed(2);
                        itemElements[31].innerText = value['data']['unripe_kurang_brondol']
                        itemElements[32].innerText = value['data']['persentase_unripe_kurang_brondol'].toFixed(2);

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


        async function getdata_form(estate, afdeling, date) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: "{{ route('getdataforform') }}",
                    method: "GET",
                    data: {
                        estate: estate,
                        afdeling: afdeling,
                        date: date,
                        _token: $('input[name="_token"]').val() // Moved here to ensure it's always the latest
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    },
                    success: function(result) {
                        resolve(result); // Resolve the promise with the result
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        reject(errorThrown); // Reject the promise with the error
                    }
                });
            });
        }

        async function getform_edit(estate, afdeling, date) {
            try {
                let form_data = await getdata_form(estate, afdeling, date);
                // console.log(form_data);

                // Check if data is returned
                if (form_data.data && form_data.data.length > 0) {
                    // If DataTable instance already exists, destroy it before re-initializing
                    if ($.fn.DataTable.isDataTable('#data-table')) {
                        $('#data-table').DataTable().clear().destroy();
                    }

                    // Initialize DataTable with specific columns
                    $('#data-table').DataTable({
                        data: form_data.data, // Use fetched data
                        columns: [{
                                data: 'id',
                                title: 'ID'
                            },
                            {
                                data: 'estate',
                                title: 'Estate'
                            },
                            {
                                data: 'afdeling',
                                title: 'Afdeling'
                            },
                            {
                                data: 'blok',
                                title: 'Blok'
                            },
                            {
                                data: 'tonase',
                                title: 'Tonase'
                            },
                            {
                                data: 'petugas',
                                title: 'Petugas'
                            },
                            {
                                data: 'datetime',
                                title: 'Datetime'
                            },
                            {
                                data: 'no_plat',
                                title: 'No. Plat'
                            },
                            {
                                data: 'jjg_spb',
                                title: 'Jjg SPB'
                            },
                            {
                                data: 'jjg_grading',
                                title: 'Jjg Grading'
                            },
                            {
                                data: 'overripe',
                                title: 'Overripe'
                            },
                            {
                                data: 'empty',
                                title: 'Empty'
                            },
                            {
                                data: 'rotten',
                                title: 'Rotten'
                            },
                            {
                                data: 'abn_partheno',
                                title: 'Abnormal Partheno'
                            },
                            {
                                data: 'abn_hard',
                                title: 'Abnormal Hard'
                            },
                            {
                                data: 'abn_sakit',
                                title: 'Abnormal Sakit'
                            },
                            {
                                data: 'tangkai_panjang',
                                title: 'Tangkai Panjang'
                            },
                            {
                                data: 'vcut',
                                title: 'V-Cut'
                            },
                            {
                                data: 'dirt',
                                title: 'Dirt'
                            },
                            {
                                data: 'karung',
                                title: 'Karung'
                            },
                            {
                                data: 'loose_fruit',
                                title: 'Loose Fruit'
                            },
                            {
                                data: 'kelas_c',
                                title: 'Kelas C'
                            },
                            {
                                data: 'kelas_b',
                                title: 'Kelas B'
                            },
                            {
                                data: 'kelas_a',
                                title: 'Kelas A'
                            },
                            {
                                data: 'unripe_tanpa_brondol',
                                title: 'Unripe Without Brondol'
                            },
                            {
                                data: 'unripe_kurang_brondol',
                                title: 'Unripe Less Brondol'
                            },
                            {
                                data: 'mill',
                                title: 'Mill'
                            },
                            {
                                data: 'no_pemanen',
                                title: 'No Pemanen'
                            },
                            {
                                data: 'app_version',
                                title: 'App Version'
                            },
                            {
                                data: 'status_bot',
                                title: 'Status Bot'
                            },
                            {
                                data: 'driver',
                                title: 'Driver'
                            },
                            {
                                title: 'Actions',
                                render: function(data, type, row, meta) {
                                    return `
                                <button class="btn btn-warning" onclick="editRecord(${row.id})">Edit</button>
                                <button class="btn btn-danger" onclick="deleteRecord(${row.id})">Delete</button>`;
                                }
                            }
                        ],
                        scrollX: true
                    });

                    // Show the modal
                    const myModal = new bootstrap.Modal(document.getElementById('dataModal'));
                    myModal.show();
                } else {
                    console.log('No data found.');
                }
            } catch (error) {
                console.error("Error fetching data:", error);
            }
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

        function handleExportSubmit2(event, formId, regionalSelectId, dateInputId, hiddenRegionalId, hiddenDateId, millID) {
            event.preventDefault();

            const regionalValue = document.getElementById(regionalSelectId).value;
            const dateValue = document.getElementById(dateInputId).value;
            const millValue = document.getElementById('mill_id').value;

            document.getElementById(hiddenRegionalId).value = regionalValue;
            document.getElementById(hiddenDateId).value = dateValue;
            document.getElementById(millID).value = millValue;

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
    </script>
</x-layout.app>