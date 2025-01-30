<x-layout.app>
    <link rel="stylesheet" href="{{ asset('qc_css/gudang/gudang.css') }}">

    <div class="container-fluid">
        <section class="content">
            <br>
            <div class="row align-items-center">
                <div class="col-md-2 col-12">
                    {{csrf_field()}}
                    <select name="" class="form-control" id="regionalData">
                        <option value="1" selected>Regional I</option>
                        <option value="2">Regional II</option>
                        <option value="3">Regional III</option>
                        <option value="4">Regional IV</option>
                    </select>
                </div>
                <div class="col-md-2 col-12">
                    <select name="" class="form-control" id="yearData">
                        @foreach ($years_list as $year)
                        <option value="{{ $year }}" {{ $year==$curr_year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 col-12">
                    <button id="sortByScore" class="btn btn-primary w-100">
                        <i class="fas fa-sort-amount-down"></i> Sort by Rank
                    </button>
                </div>
                <div class="col-md-3 col-12">
                    @if (can_edit())
                    <a href="{{ route('listktu') }}" class="btn btn-success">List KTU</a>
                    @endif
                </div>
            </div>
            <br>
            <div class="container-fluid">
                <div class="scroll">

                    <div class="card table_wrapper">

                        <table id="tableData" class="table table-bordered text-center" class="display" style="width:100%">
                            <tbody id="list" class="list">
                            </tbody>
                        </table>

                    </div>
                    <div class="pagenumbers" id="pagination"></div>

                </div>
            </div>
        </section>
    </div>

    <script type="module">
        var lokasiKerja = "{{ session('lok') }}";
        if (lokasiKerja == 'Regional II' || lokasiKerja == 'Regional 2') {
            $('#regionalData').val('2');
        } else if (lokasiKerja == 'Regional III' || lokasiKerja == 'Regional 3') {
            $('#regionalData').val('3');
        } else if (lokasiKerja == 'Regional IV' || lokasiKerja == 'Regional 4') {
            $('#regionalData').val('4');
        }

        var year, regional;

        $(document).ready(function() {
            year = $('#yearData option:selected').val();
            regional = $('#regionalData option:selected').val();

            getData(year, regional);

            $('#sortByScore').click(function() {
                sortTableByScore();
            });

            $('#sortByMonth').click(function() {
                sortTableByMonth();
            });
        });

        $('#regionalData').change(function() {
            regional = $(this).val();
            year = $('#yearData option:selected').val();
            getData(year, regional);
        });

        $('#yearData').change(function() {
            year = $(this).val();
            regional = $('#regionalData option:selected').val();
            getData(year, regional);
        });

        function sortTableByScore() {
            var table = $('#tableData');
            var tbody = table.find('tbody');
            var rows = tbody.find('tr').toArray();

            rows.sort(function(a, b) {
                var rankA = parseInt($(a).find('td:last').text()) || 0;
                var rankB = parseInt($(b).find('td:last').text()) || 0;
                return rankA - rankB; // Sort by rank (low to high since rank 1 is best)
            });

            tbody.empty();
            tbody.append(rows);
        }

        function sortTableByMonth() {
            var table = $('#tableData');
            var tbody = table.find('tbody');
            var rows = tbody.find('tr').toArray();

            rows.sort(function(a, b) {
                var rankA = parseInt($(a).find('td:last').text()) || 0;
                var rankB = parseInt($(b).find('td:last').text()) || 0;
                return rankA - rankB; // Sort by rank (low to high)
            });

            tbody.empty();
            tbody.append(rows);
        }

        function getData(year, regional) {

            var value = year;
            var value2 = regional;
            var _token = $('input[name="_token"]').val();

            $.ajax({
                url: "{{ route('getDataByYear') }}",
                method: "GET",
                data: {
                    year: value,
                    _token: _token,
                    regional: value2
                },
                success: function(result) {
                    var result = JSON.parse(result);



                    // $('#tableData').dataTable().fnClearTable();
                    //delete thead
                    document.getElementById("tableData").deleteTHead();

                    var arrHeader = Object.entries(result['arrHeader'])
                    var arrMonth = Object.entries(result['arrMonth'])
                    var arrCount = Object.entries(result['arrCount'])
                    var arrReg = Object.entries(result['arrReg'])

                    var rowHeader = '['
                    for (let i = 0; i < arrHeader.length; i++) {
                        rowHeader += '"' + result['arrHeader'][i] + '",'
                    }


                    rowHeader = rowHeader.substring(0, rowHeader.length - 1)
                    rowHeader += ']'

                    var rowMonth = '['
                    for (let i = 0; i < arrMonth.length; i++) {
                        rowMonth += '"' + result['arrMonth'][i] + '",'
                    }
                    rowMonth = rowMonth.substring(0, rowMonth.length - 1)
                    rowMonth += ']'

                    var rowCount = '['
                    for (let i = 0; i < arrCount.length; i++) {
                        rowCount += result['arrCount'][i] + ','
                    }
                    rowCount = rowCount.substring(0, rowCount.length - 1)
                    rowCount += ']'

                    var rowReg = '['
                    for (let i = 0; i < arrReg.length; i++) {
                        rowReg += result['arrReg'][i] + ','
                    }
                    rowReg = rowReg.substring(0, rowReg.length - 1)
                    rowReg += ']'

                    var parserowHeader = JSON.parse(rowHeader)
                    var parserowMonth = JSON.parse(rowMonth)
                    var parserowCount = JSON.parse(rowCount)
                    var parserowReg = JSON.parse(rowReg)

                    var thead = document.createElement('thead');
                    var table = document.getElementById('tableData')
                    var header = table.createTHead();
                    var row = header.insertRow();
                    var row2 = header.insertRow();
                    var row3 = header.insertRow();

                    for (let i = 0; i < 1; i++) {

                        let reg = ''
                        var cell3 = row.insertCell(i);
                        if (parserowReg[0] == 1) {
                            reg = 'I'
                        } else if (parserowReg[0] == 2) {
                            reg = 'II'
                        } else if (parserowReg[0] == 3) {
                            reg = 'III'
                        } else if (parserowReg[0] == 4) {
                            reg = 'IV'
                        }
                        cell3.innerHTML = "<b>SUMMARY SCORE GUDANG REGIONAL - " + reg + "</b>";
                        cell3.colSpan = parserowHeader.length;
                    }

                    let k = 1;
                    for (let j = 0; j < 1; j++) {
                        var cell2 = row2.insertCell(j);
                        cell2.innerHTML = "<b>BULAN</b>";
                        cell2.colSpan = 3;
                    }

                    for (let i = 0; i < parserowMonth.length; i++) {
                        var cell2 = row2.insertCell(k);
                        cell2.innerHTML = "<b>" + parserowMonth[i] + "</b>";
                        cell2.colSpan = parserowCount[i] + 1;
                        cell2.style.textAlign = "center";
                        k++;
                    }

                    let m = 13;
                    var last = parserowHeader.slice(-3);
                    for (let i = 0; i < last.length; i++) {
                        var cell3 = row2.insertCell(m);
                        cell3.innerHTML = "<b>" + last[i] + "</b>";
                        cell3.rowSpan = 2;
                        cell3.style.textAlign = "center";
                        m++;
                    }

                    for (let i = 0; i < parserowHeader.length - 3; i++) {
                        var cell = row3.insertCell(i);
                        cell.innerHTML = "<b>" + parserowHeader[i] + "</b>";
                    }


                    const pagination_element = document.getElementById('pagination')
                    var list_element = document.getElementById('list')

                    let current_page = 1;
                    let rows = 12;



                    var arrId = Object.entries(result['arrId'])



                    var arrResult = Object.entries(result['arrView'])
                    var arrId = Object.entries(result['arrId'])
                    var tableData = document.getElementById('tableData');
                    if ($("#tableData > tbody > tr").length != 0) {
                        for (let i = 0; i < arrResult.length; i++) {
                            $("#list").empty();
                        }
                    }



                    // arrResult.forEach(element => {
                    for (let i = 0; i < arrResult.length; i++) {


                        var childArrView = Object.entries(arrResult[i][1])
                        var childArrId = Object.entries(arrId[i][1])

                        var tr = document.createElement('tr');

                        tr.setAttribute("id", "testing" + childArrView[0]);

                        // childArrView.forEach(element => {


                        for (let j = 0; j < childArrView.length; j++) {

                            // var item = 'item' + arrResult[i][0]

                            if (childArrView[j][1] == '-') {
                                // console.log(childArrId);
                                if (childArrView[j][1] == '-') {
                                    var dt = '0'
                                }

                                var item_element = document.createElement('td')
                                // item_element.innerHTML = '<a href="detailInspeksi/' + childArrId[j][1] + '">' + dt + ' </a>'
                                item_element.innerHTML = dt
                                tr.appendChild(item_element);
                            } else if (childArrId[j][1] != '-') {
                                var item_element = document.createElement('td')
                                item_element.innerHTML = '<a href="detailInspeksi/' + childArrId[j][1] + '">' + childArrView[j][1] + ' </a>'
                                tr.appendChild(item_element);
                            } else {
                                var item = childArrView[j][1]
                                var item_element = 'item_element' + arrResult[i][0]

                                var item_element = document.createElement('td')

                                if (item == 'Excellent') {
                                    item_element.setAttribute("class", "Excellent");
                                } else if (item == 'Good') {
                                    item_element.setAttribute("class", "Good");
                                } else if (item == 'Satisfactory') {
                                    item_element.setAttribute("class", "Satisfactory");
                                } else if (item == 'Fair') {
                                    item_element.setAttribute("class", "Fair");
                                } else if (item == 'Poor') {
                                    item_element.setAttribute("class", "Poor");
                                }
                                item_element.innerText = item

                                tr.appendChild(item_element);
                            }


                        }
                        // });
                        list_element.appendChild(tr)


                        // });
                    }
                }
            })
        }
    </script>
</x-layout.app>