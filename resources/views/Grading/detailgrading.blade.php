<x-layout.app>
    <link rel="stylesheet" href="{{ asset('qc_css/grading/grading.css') }}">

    <div class="container-fluid">

        <div class="card table_wrapper">
            <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark ">
                <h2>REKAP HARIAN </h2>
            </div>
            <div class="alert alert-danger d-none d-flex flex-column align-items-start justify-content-between" role="alert" id="notverif">
                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:">
                    <use xlink:href="#exclamation-triangle-fill" />
                </svg>
                <div>
                    Data belum Tervertifikasi oleh Manager/Askep
                </div>
                @if (session('jabatan') == 'Manager' || session('jabatan') == 'Askep' )
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
                        <div class="row align-items-center">
                            <div class="col-md">
                                <select class="form-select mb-2 mb-md-0" name="date" id="date" aria-label="Default select example">
                                    @foreach ($get_date as $items)
                                    <option value="{{$items}}">{{$items}}</option>
                                    @endforeach

                                </select>
                            </div>

                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary ml-2" id="rekap_perhari">Show</button>
                            </div>
                            <div class="col-auto">
                                <form id="pdf-form" action="{{ route('exportpdfgrading') }}" method="POST" class="form-inline" style="display: inline;" target="_blank">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="estBA" id="estpdf" value="{{$est}}">
                                    <input type="hidden" name="afdBA" id="afdpdf" value="{{$afd}}">
                                    <input type="hidden" name="datepdf" id="datepdf" value="">
                                    <button type="submit" class="btn btn-primary ml-2" id="download-button">
                                        Download PDF
                                    </button>
                                </form>
                            </div>
                        </div>
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


        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <h1 style="text-align: center;">REKAPITULASI LAPORAN GRADING PKS</h1>
                        <table class="table table-responsive table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style="background-color: #f0ecec;" class="align-middle" rowspan="3">Estate</th>
                                    <th style="background-color: #f0ecec;" class="align-middle" rowspan="3">Afdeling</th>
                                    <th style="background-color: #f0ecec;" colspan="6">UNIT SORTASI</th>
                                    <th style="background-color: #88e48c;" colspan="20">HASIL GRADING</th>
                                    <th style="background-color: #f8c4ac;" colspan="6">KELAS JANJANG</th>
                                </tr>
                                <tr>
                                    <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">NO POLISI</th>
                                    <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">WAKTU GRADING</th>
                                    <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">JUMLAH JANJANG SPB</th>
                                    <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">JUMLAH JANJANG GRADING</th>
                                    <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">TONASE (KG)</th>
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
            </div>
        </div>

        <script type="module">
            $(document).ready(function() {
                $('#date').on('change', function() {
                    var selectedDate = $(this).val();
                    $('#datepdf').val(selectedDate);
                });

                // Initialize the datepdf input value on page load
                $('#datepdf').val($('#date').val());

            });

            let estate = @json($est);
            let afdeling = @json($afd);
            // console.log(est);
            $('#rekap_perhari').on('click', function(e) {
                e.preventDefault(); // Prevent the default form submission behavior, if needed
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
            });


            function getrekapperhari() {
                let date = document.getElementById('date').value;
                // let estate = document.getElementById('estate_select').value;
                let _token = $('input[name="_token"]').val();
                $('#rekap_perhari_data').empty()

                $.ajax({
                    url: "{{ route('getrekapperhari') }}",
                    method: "GET",
                    data: {
                        estate: estate,
                        afd: afdeling,
                        date: date,
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
                            for (let index = 0; index < 34; index++) {
                                itemElements[index] = document.createElement('td');
                            }

                            // Assign text values to each itemElement
                            itemElements[0].innerText = value['estate'];
                            itemElements[1].innerText = value['afdeling'];
                            itemElements[2].innerText = value['no_plat'];
                            itemElements[3].innerText = value['datetime'];
                            itemElements[4].innerText = value['jjg_spb'].toLocaleString('id-ID');
                            itemElements[5].innerText = value['jjg_grading'].toLocaleString('id-ID')
                            itemElements[6].innerText = value['tonase'].toLocaleString('id-ID')
                            itemElements[7].innerText = value['bjr'].toFixed(2)
                            itemElements[8].innerText = value['Ripeness'].toLocaleString('id-ID');
                            itemElements[9].innerText = value['percentase_ripenes']
                            itemElements[10].innerText = value['Unripe'].toLocaleString('id-ID');
                            itemElements[11].innerText = value['persenstase_unripe']
                            itemElements[12].innerText = value['Overripe'].toLocaleString('id-ID');
                            itemElements[13].innerText = value['persentase_overripe']
                            itemElements[14].innerText = value['empty_bunch'].toLocaleString('id-ID');
                            itemElements[15].innerText = value['persentase_empty_bunch']
                            itemElements[16].innerText = value['rotten_bunch'].toLocaleString('id-ID');
                            itemElements[17].innerText = value['persentase_rotten_bunce']
                            itemElements[18].innerText = value['Abnormal'].toLocaleString('id-ID');
                            itemElements[19].innerText = value['persentase_abnormal']
                            itemElements[20].innerText = value['stalk'].toLocaleString('id-ID');
                            itemElements[21].innerText = value['persentase_stalk']
                            itemElements[22].innerText = value['vcut'].toLocaleString('id-ID');
                            itemElements[23].innerText = value['persentase_vcut']
                            itemElements[24].innerText = value['Dirt'].toLocaleString('id-ID');
                            itemElements[25].innerText = value['persentase']
                            itemElements[26].innerText = value['loose_fruit'].toLocaleString('id-ID');
                            itemElements[27].innerText = value['persentase_lose_fruit']
                            itemElements[28].innerText = value['kelas_c']
                            itemElements[29].innerText = value['persentase_kelas_c']
                            itemElements[30].innerText = value['kelas_b']
                            itemElements[31].innerText = value['persentase_kelas_b']
                            itemElements[32].innerText = value['kelas_a']
                            itemElements[33].innerText = value['persentase_kelas_a']

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
        </script>


    </div>
</x-layout.app>