<x-layout.app>

    <style>
        .table-wrapper {
            overflow-x: auto;
            /* margin: 0 auto; */
            margin-left: 10px;
            margin-right: 10px;
        }

        .my-table {
            border-collapse: collapse;
            width: 100%;
            text-align: center;
            margin-bottom: 2rem;
        }

        .my-table th,
        .my-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .my-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
    </style>
    <div class="content-wrapper">
        <section class="content"><br>
            <div class="container-fluid">
                <div class="card table_wrapper">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active" id="nav-utama-tab" data-toggle="tab" href="#nav-utama" role="tab" aria-controls="nav-utama" aria-selected="true">Perumahan Estate</a>
                            <a class="nav-item nav-link" id="nav-data-tab" data-toggle="tab" href="#nav-data" role="tab" aria-controls="nav-data" aria-selected="false">Perumahan Afdeling</a>

                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">


                        <div class="tab-pane fade show active" id="nav-utama" role="tabpanel" aria-labelledby="nav-utama-tab">
                            <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark">
                                <h5><b>SUMMARY SCORE PERUMAHAN ESTATE REGIONAL - I</b></h5>
                            </div>
                            <div class="d-flex justify-content-end mt-3 mb-2 ml-3 mr-3" style="padding-top: 20px;">
                                <div class="row w-100">
                                    <div class="col-md-2 offset-md-8">
                                        {{csrf_field()}}
                                        <select class="form-control" id="estreg">
                                            @foreach($option_reg as $key => $item)
                                            <option value="{{ $item['id'] }}">{{ $item['nama'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                        {{csrf_field()}}
                                        <select class="form-control" id="tahunest">
                                            @foreach($list_tahun as $item)
                                            <option value="{{$item}}">{{$item}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <button class="btn btn-primary mb-3" style="float: right" id="btnShoWEst">Show</button>
                            </div>

                            <div class="table-wrapper">
                                <table class="my-table" id="test">
                                    <thead>
                                        <tr>
                                            <th rowspan="3">No</th>
                                            <th rowspan="3">UNIT KERJA</th>
                                            <th rowspan="3">KODE</th>
                                            <th rowspan="3">PIC</th>
                                            <th colspan="14" id="yearHeader2" style="text-align: center;">2023</th>

                                        </tr>
                                        <tr id="month_header2">
                                            <td id="Jan">Jan</td>
                                            <td id="Feb">Feb</td>
                                            <td id="Mar">Mar</td>
                                            <td id="Apr">Apr</td>
                                            <td id="Maye">May</td>
                                            <td id="Jun">Jun</td>
                                            <td id="Jul">Jul</td>
                                            <td id="Aug">Aug</td>
                                            <td id="Sept">Sep</td>
                                            <td id="Oct">October</td>
                                            <td id="Nov">Nov</td>
                                            <td id="Dec">December</td>
                                            <td rowspan="2" id="Ave">Ave</td>
                                            <td rowspan="2" id="Status">Status</td>
                                        </tr>
                                        <tr id="visit">

                                        </tr>
                                    </thead>
                                    <tbody id="data_est">

                                    </tbody>
                                </table>
                            </div>
                        </div>


                        <div class=" tab-pane fade" id="nav-data" role="tabpanel" aria-labelledby="nav-data-tab">
                            <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark">
                                <h5><b>SUMMARY SCORE PERUMAHAN AFDELING REGIONAL - I

                                    </b></h5>
                            </div>
                            <div class="content">
                                <div class="d-flex justify-content-end mt-3 mb-2 ml-3 mr-3" style="padding-top: 20px;">
                                    <div class="row w-100">
                                        <div class="col-md-2 offset-md-8">
                                            {{csrf_field()}}
                                            <select class="form-control" id="afdreg">
                                                @foreach($option_reg as $key => $item)
                                                <option value="{{ $item['id'] }}">{{ $item['nama'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                            {{csrf_field()}}
                                            <select class="form-control" id="tahunafd">
                                                @foreach($list_tahun as $item)
                                                <option value="{{$item}}">{{$item}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary mb-3" style="float: right" id="btnShow">Show</button>

                                </div>
                            </div>



                            <div class="table-wrapper">
                                <table class="my-table">
                                    <thead>
                                        <tr>
                                            <th rowspan="2">No</th>
                                            <th rowspan="2">EST</th>
                                            <th rowspan="2">AFDELING</th>
                                            <th rowspan="2">Asisten</th>

                                            <th colspan="14" id="yearHeader" style="text-align: center;">2023</th>

                                        </tr>
                                        <tr id="month_header">
                                            <td id="January">Jan</td>
                                            <td id="February">Feb</td>
                                            <td id="March">Mar</td>
                                            <td id="April">Apr</td>
                                            <td id="May">May</td>
                                            <td id="June">Jun</td>
                                            <td id="July">Jul</td>
                                            <td id="August">Aug</td>
                                            <td id="September">Sep</td>
                                            <td id="October">October</td>
                                            <td id="November">Nov</td>
                                            <td id="December">December</td>
                                            <td id="Ave">Ave</td>
                                            <td id="Status">Status</td>
                                        </tr>

                                    </thead>
                                    <tbody id="data_afd">


                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {

            var lokasiKerja = "{{ session('lok') }}";
            // console.log(lokasiKerja);
            if (lokasiKerja == 'Regional II' || lokasiKerja == 'Regional 2') {
                $('#estreg').val('2');
                $('#afdreg').val('2');

            } else if (lokasiKerja == 'Regional III' || lokasiKerja == 'Regional 3') {
                $('#estreg').val('3');
                $('#afdreg').val('3');
            } else if (lokasiKerja == 'Regional IV' || lokasiKerja == 'Regional 4') {
                $('#estreg').val('4');
                $('#afdreg').val('4');
            } else if (lokasiKerja == 'Regional I' || lokasiKerja == 'Regional 1') {
                $('#estreg').val('1');
                $('#afdreg').val('1');
            }


            //untuk table etc
            getAFD()
            getEST()

        });

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
            getAFD();
        }
        document.getElementById('btnShoWEst').onclick = function() {
            Swal.fire({
                title: 'Loading',
                html: '<span class="loading-text">Mohon Tunggu...</span>',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });
            getEST();
        }

        function getAFD() {
            $('#data_afd').empty()
            var reg = '';
            var tahun = '';

            var reg = document.getElementById('afdreg').value;
            var tahun = document.getElementById('tahunafd').value;
            var _token = $('input[name="_token"]').val();
            document.getElementById('yearHeader').innerHTML = tahun;

            $.ajax({
                url: "{{ route('getAFD') }}",
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
                    Swal.close();

                    var parseResult = JSON.parse(result)
                    var bulan = Object.entries(parseResult['bulan'])
                    var rekap = Object.entries(parseResult['afd_rekap'])
                    var avarage = Object.entries(parseResult['avg'])


                    // console.log(rekap);
                    var afd_bulan = rekap
                    // var tbody1 = document.getElementById('data_afd');
                    var tbody1 = document.getElementById('data_afd');
                    //         $('#thead1').empty()
                    const header = Object.entries(parseResult['header_cell']);
                    const header_head = Object.entries(parseResult['header_head']);
                    // console.log(header_head);

                    const head_year = document.getElementById('yearHeader');
                    const colspanValue = header_head.find(entry => entry[0] === "head")[1];

                    // Update the colspan attribute of the "yearHeader" element
                    head_year.setAttribute("colspan", colspanValue);
                    // Function to set the colspan for the month header cells
                    function setColspanForMonths() {
                        const jan = document.getElementById('January');
                        const feb = document.getElementById('February');
                        const mar = document.getElementById('March');
                        const apr = document.getElementById('April');
                        const may = document.getElementById('May');
                        const June = document.getElementById('June');
                        const July = document.getElementById('July');
                        const August = document.getElementById('August');
                        const September = document.getElementById('September');
                        const October = document.getElementById('October');
                        const November = document.getElementById('November');
                        const December = document.getElementById('December');
                        // ... add the rest of the months

                        const monthHeaders = [jan, feb, mar, apr, may, June, July, August, September, October, November, December];

                        header.forEach((monthData, index) => {
                            const [monthId, colspanValue] = monthData;
                            const headerCell = monthHeaders[index];

                            if (headerCell) {
                                headerCell.colSpan = colspanValue > 1 ? colspanValue : 1;
                            } else {
                                console.error(`Header cell for month ${monthId} not found.`);
                            }
                        });
                    }


                    // Call the function to set the colspans
                    setColspanForMonths();
                    var item1x = 1;
                    // console.log(afd_bulan);
                    afd_bulan.forEach((element, index) => {

                        let estate = element[0];
                        let namaAFD = Object.keys(element[1]);

                        let allMonths = Object.keys(element[1][namaAFD[0]]); // Assuming all AFDs have the same months
                        var num = 0
                        namaAFD.forEach((key) => {
                            tr = document.createElement('tr');
                            let item0 = item1x++
                            let item1 = estate;
                            let item2 = key;
                            let item3 = '-';

                            // let item4 =
                            // console.log(item4);
                            let items = [item0, item1, item2, item3, ];

                            // ... Your previous code ...

                            allMonths.forEach((month) => {
                                let monthData = element[1][key][month];

                                if (monthData) {
                                    for (let visit in monthData) {
                                        let skor_total = monthData[visit].skor_total;
                                        let est = monthData[visit].est;
                                        let afd = monthData[visit].afd;

                                        items.push(skor_total);
                                        // items.push(avg);
                                    }
                                }

                            });

                            let column = 1; // Start column after the first three items
                            for (let j = 0; j < items.length; j++) {
                                let item = items[j];
                                let td = document.createElement('td');

                                column++;
                                if (item instanceof Node) { // Check if the item is a Node (e.g., a <td> element)
                                    td.appendChild(item); // Append the item (which is a Node) to the table cell
                                } else {
                                    td.innerText = item; // Otherwise, treat it as a regular string and set its text content
                                }
                                tr.appendChild(td); // Append the table cell to the table row
                            }


                            let td4 = document.createElement('td');
                            let td5 = document.createElement('td');

                            // Access 'avg' property from rata_rata array

                            let itemavg = element[1][key].afd
                            // console.log(itemavg);
                            td4.innerText = itemavg;


                            if (itemavg >= 95) {
                                td5.style.backgroundColor = "#609cd4";
                                td5.innerText = 'EXCELLENT';
                            } else if (itemavg >= 85 && itemavg < 95) {
                                td5.style.backgroundColor = "#08b454";
                                td5.innerText = 'GOOD';
                            } else if (itemavg >= 75 && itemavg < 85) {
                                td5.style.backgroundColor = "#fffc04";
                                td5.innerText = 'SATISFACTORY';
                            } else if (itemavg >= 65 && itemavg < 75) {
                                td5.style.backgroundColor = "#ffc404";
                                td5.innerText = 'FAIR';
                            } else {
                                td5.style.backgroundColor = "red";
                                td5.innerText = 'POOR';
                            }




                            tr.appendChild(td4);
                            tr.appendChild(td5);

                            tbody1.appendChild(tr);
                        });
                    });

                    var avarageArray = avarage;



                },
                error: function(jqXHR, textStatus, errorThrown) {

                }
            });
        }

        function getEST() {
            $('#data_est').empty()
            // $('#month_header2').empty()
            $('#visit').empty()
            var reg = '';
            var tahun = '';

            var reg = document.getElementById('estreg').value;
            var tahun = document.getElementById('tahunest').value;
            var _token = $('input[name="_token"]').val();

            document.getElementById('yearHeader2').innerHTML = tahun;

            $.ajax({
                url: "{{ route('estAFD') }}",
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
                    Swal.close();

                    var parseResult = JSON.parse(result)
                    var bulan = Object.entries(parseResult['bulan'])
                    var rekap = Object.entries(parseResult['afd_rekap'])
                    var rata_rata = Object.entries(parseResult['rata_rata'])
                    var visitArray = Object.entries(parseResult['visit'])
                    var skoring = Object.entries(parseResult['skoring'])
                    var new_afd = Object.entries(parseResult['new_afd'])
                    // console.log(new_afd);
                    // console.log(visitArray);

                    if (visitArray !== null) {
                        function createTableHeader(content) {
                            var th = document.createElement("th");
                            th.textContent = content;
                            return th;
                        }

                        // Get the existing tr element with ID "visit"
                        var visitTr = document.getElementById("visit");

                        // Append the TH elements to the existing tr
                        for (var i = 0; i < visitArray.length; i++) {
                            var content = visitArray[i][1];
                            visitTr.appendChild(createTableHeader(content));
                        }
                    }




                    var afd_bulan = rekap


                    //         $('#thead1').empty()
                    const header = Object.entries(parseResult['header_cell']);
                    const header_head = Object.entries(parseResult['header_head']);
                    // console.log(header_head);

                    const head_year = document.getElementById('yearHeader2');
                    const colspanValue = header_head.find(entry => entry[0] === "head")[1];

                    // console.log(visit);
                    // Update the colspan attribute of the "yearHeader" element
                    head_year.setAttribute("colspan", colspanValue);

                    function setColspanForMonths() {
                        const jan = document.getElementById('Jan');
                        const feb = document.getElementById('Feb');
                        const mar = document.getElementById('Mar');
                        const apr = document.getElementById('Apr');
                        const may = document.getElementById('Maye');
                        const June = document.getElementById('Jun');
                        const July = document.getElementById('Jul');
                        const August = document.getElementById('Aug');
                        const September = document.getElementById('Sept');
                        const October = document.getElementById('Oct');
                        const November = document.getElementById('Nov');
                        const December = document.getElementById('Dec');
                        // ... add the rest of the months

                        const monthHeaders = [jan, feb, mar, apr, may, June, July, August, September, October, November, December];

                        header.forEach((monthData, index) => {
                            const [monthId, colspanValue] = monthData;
                            const headerCell = monthHeaders[index];

                            if (headerCell) {
                                headerCell.colSpan = colspanValue > 1 ? colspanValue : 1;
                            } else {
                                console.error(`Header cell for month ${monthId} not found.`);
                            }
                        });
                    }

                    setColspanForMonths();
                    // console.log(rata_rata);




                    var table1 = new_afd;
                    var tbody1 = document.getElementById('data_est');

                    // console.log(table1);
                    inc = 1
                    table1.forEach((element, index) => {
                        let tr = document.createElement('tr');
                        let item1 = inc++
                        let item2 = element[1].unit_kerja;
                        let item3 = element[1].kode;
                        let item4 = element[1].pic;
                        let januaryArray = element[1].January;
                        let Feb = element[1].February;
                        let March = element[1].March;
                        let April = element[1].April;
                        let May = element[1].May;
                        let June = element[1].June;
                        let Jully = element[1].July;
                        let Aug = element[1].August;
                        let Sept = element[1].September;
                        let Oct = element[1].October;
                        let Nov = element[1].November;
                        let Dec = element[1].December;
                        let item6 = element[1].January_avg;
                        let item7 = element[1].February_avg;
                        let item8 = element[1].March_avg
                        let item9 = element[1].April_avg
                        let item10 = element[1].May_avg
                        let item11 = element[1].June_avg
                        let item12 = element[1].July_avg
                        let item13 = element[1].August_avg
                        let item14 = element[1].September_avg
                        let item15 = element[1].October_avg
                        let item16 = element[1].November_avg
                        let item17 = element[1].December_avg


                        let JanDate = element[1].January_dates[0];
                        let FebDate = element[1].February_dates[0];
                        let MarchDate = element[1].March_dates[0];
                        let AprDate = element[1].April_dates[0];
                        let MayDate = element[1].May_dates[0];
                        let JuneDate = element[1].June_dates[0];
                        let JulyDate = element[1].July_dates[0];
                        let AugDate = element[1].August_dates[0];
                        let SepDate = element[1].September_dates[0];
                        let OctDate = element[1].October_dates[0];
                        let NovDate = element[1].November_dates[0];
                        let DecDate = element[1].December_dates[0];


                        let url = element[1].kode;

                        if (url.includes('EST')) {
                            url = url.replace('-EST', '').trim();
                        }
                        // ... your existing code ...

                        let januaryLink = `<a href="/detailEmplashmend/${url}/${JanDate}">${item6}</a>`
                        let februaryLink = `<a href="/detailEmplashmend/${url}/${FebDate}">${item7}</a>`
                        let marchLink = `<a href="/detailEmplashmend/${url}/${MarchDate}">${item8}</a>`
                        let aprilLink = `<a href="/detailEmplashmend/${url}/${AprDate}">${item9}</a>`
                        let mayLink = `<a href="/detailEmplashmend/${url}/${MayDate}">${item10}</a>`
                        let juneLink = `<a href="/detailEmplashmend/${url}/${JuneDate}">${item11}</a>`
                        let julyLink = `<a href="/detailEmplashmend/${url}/${JulyDate}">${item12}</a>`
                        let augustLink = `<a href="/detailEmplashmend/${url}/${AugDate}">${item13}</a>`
                        let septemberLink = `<a href="/detailEmplashmend/${url}/${SepDate}">${item14}</a>`
                        let octoberLink = `<a href="/detailEmplashmend/${url}/${OctDate}">${item15}</a>`
                        let novemberLink = `<a href="/detailEmplashmend/${url}/${NovDate}">${item16}</a>`
                        let decemberLink = `<a href="/detailEmplashmend/${url}/${DecDate}">${item17}</a>`

                        let items = [item1, item2, item3, item4,
                            ...januaryArray, januaryLink,
                            ...Feb, februaryLink,
                            ...March, marchLink,
                            ...April, aprilLink,
                            ...May, mayLink,
                            ...June, juneLink,
                            ...Jully, julyLink,
                            ...Aug, augustLink,
                            ...Sept, septemberLink,
                            ...Oct, octoberLink,
                            ...Nov, novemberLink,
                            ...Dec, decemberLink,
                        ];

                        for (let j = 0; j < items.length; j++) {
                            let item = items[j];

                            let td = document.createElement('td');
                            // Set innerHTML instead of innerText to allow HTML content
                            td.innerHTML = item;
                            tr.appendChild(td);
                        }

                        let td4 = document.createElement('td');
                        let td5 = document.createElement('td');

                        // Access 'avg' property from rata_rata array
                        let rataRataElement = rata_rata[index];
                        let itemavg = rataRataElement[1].avg;

                        td4.innerText = itemavg;


                        if (itemavg >= 95) {
                            td5.style.backgroundColor = "#609cd4";
                            td5.innerText = 'EXCELLENT';
                        } else if (itemavg >= 85 && itemavg < 95) {
                            td5.style.backgroundColor = "#08b454";
                            td5.innerText = 'GOOD';
                        } else if (itemavg >= 75 && itemavg < 85) {
                            td5.style.backgroundColor = "#fffc04";
                            td5.innerText = 'SATISFACTORY';
                        } else if (itemavg >= 65 && itemavg < 75) {
                            td5.style.backgroundColor = "#ffc404";
                            td5.innerText = 'FAIR';
                        } else {
                            td5.style.backgroundColor = "red";
                            td5.innerText = 'POOR';
                        }




                        tr.appendChild(td4);
                        tr.appendChild(td5);

                        tbody1.appendChild(tr);
                    });







                },
                error: function(jqXHR, textStatus, errorThrown) {

                }
            });
        }
    </script>

</x-layout.app>