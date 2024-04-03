<x-layout.app>


    <style>
        table.dataTable thead tr th {
            border: 1px solid black
        }

        .dataTables_scrollBody thead tr[role="row"] {
            visibility: collapse !important;
        }

        .dataTables_scrollHeaderInner table {
            margin-bottom: 0px !important;
        }

        div.scroll {
            margin: 4px, 4px;
            padding: 4px;

            width: 100%;
            overflow-x: auto;
            overflow-y: hidden;
            white-space: nowrap;
        }

        .pagenumbers {

            margin-top: 30px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
        }

        .pagenumbers button {
            width: 50px;
            height: 50px;

            appearance: none;
            border-radius: 5px;
            border: 1px solid white;
            outline: none;
            cursor: pointer;

            background-color: white;

            margin: 5px;
            transition: 0.4s;

            color: black;
            font-size: 18px;
            text-shadow: 0px 0px 4px rgba(0, 0, 0, 0.2);
            box-shadow: 0px 0px 4px rgba(0, 0, 0, 0.2);
        }

        .pagenumbers button:hover {
            background-color: #013c5e;
            color: white
        }

        .pagenumbers button.active {
            background-color: #013c5e;
            color: white;
            box-shadow: inset 0px 0px 4px rgba(0, 0, 0, 0.2);
        }

        .pagenumbers button.active:hover {
            background-color: #353e44;
            color: white;
            box-shadow: inset 0px 0px 4px rgba(0, 0, 0, 0.2);
        }

        .table_wrapper {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }

        td:first-child,
        th:first-child {
            position: sticky;
            left: 0;
            background-color: white;
        }

        .Good {
            background-color: green;
            color: white;
        }

        .Satisfactory {
            background-color: yellow;
        }

        .Excellent {
            background-color: lightskyblue;
        }

        .Fair {
            background-color: orange;
        }

        .Poor {
            background-color: red
        }

        td:nth-child(2),
        th:nth-child(2) {
            position: sticky;
            left: 4.5%;
            background-color: white;
        }


        td:nth-child(3),
        th:nth-child(3) {
            position: sticky;
            left: 10%;
            background-color: white;
        }
    </style>
    <div class="content-wrapper">
        <section class="content">
            <br>
            <div class="row">

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
                <div class="col-md-3 col-12">
                    @if (session('jabatan') == 'Manager' || session('jabatan') == 'Askep' || session('jabatan') ==
                    'Asisten')

                    <a href="{{ route('listktu') }}" class="btn btn-success mr-2">List KTU</a>
                    <a href="{{ route('user_qc', ['lokasi_kerja' => session('lok')]) }}" class="btn btn-success mr-2">List
                        All
                        User
                        QC</a>
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

    <script type="text/javascript">
        var lokasiKerja = "{{ session('lok') }}";
        // console.log(lokasiKerja);
        if (lokasiKerja == 'Regional II' || lokasiKerja == 'Regional 2') {
            $('#regionalData').val('2');

        } else if (lokasiKerja == 'Regional III' || lokasiKerja == 'Regional 3') {
            $('#regionalData').val('3');

        } else if (lokasiKerja == 'Regional IV' || lokasiKerja == 'Regional 4') {
            $('#regionalData').val('4');

        }
        $(document).ready(function() {
            year = $('#yearData option:selected').val();
            regional = $('#regionalData option:selected').val();

            getData(year, regional)

        });


        $('#regionalData').change(function() {
            regional = $(this).val();
            year = $('#yearData option:selected').val();

            getData(year, regional)
        });

        $('#yearData').change(function() {
            year = $(this).val();
            regional = $('#regionalData option:selected').val();

            getData(year, regional)
        });


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
                    // table.appendChild(thead);

                    // for (var i = 0; i < parserowHeader.length; i++) {
                    //     thead.appendChild(document.createElement("th")).
                    //     appendChild(document.createTextNode(parserowHeader[i]));
                    // }

                    var header = table.createTHead();
                    var row = header.insertRow();
                    var row2 = header.insertRow();
                    var row3 = header.insertRow();

                    for (let i = 0; i < 1; i++) {

                        reg = ''
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

                    // var yourTable = document.querySelector('table'); // select your table
                    // var row = document.createElement('tr');
                    // for (var i=0; i<parserowHeader.length; i++) {
                    //     var cell = document.createElement("th");
                    //     cell.innerHTML = parserowHeader[i];
                    //     row.appendChild(cell);
                    // }
                    // thead.appendChild(row);
                    // yourTable.insertBefore(thead, yourTable.children[0]);

                    const pagination_element = document.getElementById('pagination')
                    var list_element = document.getElementById('list')

                    let current_page = 1;
                    let rows = 12;


                    // function DisplayList(items, wrapper, rows_per_page, page){
                    // wrapper.innerHTML = "";
                    // page--;

                    // let start = rows_per_page * page;
                    // let end = start + rows_per_page;

                    // var item = 'askdfksd'
                    // var tr = document.createElement('tr');
                    // var item_element = document.createElement('td')
                    // item_element.innerText = item
                    // tr.appendChild(item_element)
                    // list_element.appendChild(tr)

                    var arrId = Object.entries(result['arrId'])

                    // var arrIdLink = new Array()
                    // arrId.forEach(element => {
                    //     var childArr = Object.entries(element[1])
                    //     for (let i = 0; i < childArr.length; i++) {

                    //         // console.log(childArr)
                    //         arrIdLink.push(childArr[i][1])
                    //     }
                    // });


                    // console.log(arrIdLink)

                    var arrResult = Object.entries(result['arrView'])
                    var arrId = Object.entries(result['arrId'])
                    var tableData = document.getElementById('tableData');
                    if ($("#tableData > tbody > tr").length != 0) {
                        for (let i = 0; i < arrResult.length; i++) {
                            $("#list").empty();
                        }
                    }



                    // var tb = document.querySelectorAll('tbody');
                    // for (var i = 0; i < tb.length; i++) {
                    //     if (tb[i].children.length === 0) {
                    //     tb[i].parentNode.removeChild(tb[i]);
                    //     }
                    // }


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



                    // function DisplayList(items, wrapper, rows_per_page, page){
                    //     wrapper.innerHTML = "";
                    //     page--;

                    //     let start = rows_per_page * page;
                    //     let end = start + rows_per_page;
                    //     let paginatedItems = items.slice(start, end);



                    //     console.log(paginatedItems)
                    //     let inc = 1;
                    //     for (let i = 0; i < paginatedItems.length; i++) {
                    //         let item = inc
                    //         let item2 = paginatedItems[i]['tanggal_formatted']
                    //         let item3 = paginatedItems[i]['lokasi_kerja']
                    //         let item4 = paginatedItems[i]['afdeling']
                    //         let item5 = paginatedItems[i]['blok']
                    //         let item6 = paginatedItems[i]['akp']
                    //         let item7 = paginatedItems[i]['taksasi']
                    //         let item8 = paginatedItems[i]['ritase']
                    //         let item9 = paginatedItems[i]['pemanen']
                    //         let item10 = paginatedItems[i]['luas']
                    //         let item11 = paginatedItems[i]['sph']
                    //         let item12 = paginatedItems[i]['bjr']
                    //         let item13 = paginatedItems[i]['jumlah_path']
                    //         let item14 = paginatedItems[i]['jumlah_janjang']
                    //         let item15 = paginatedItems[i]['jumlah_pokok']
                    //         let item16 = paginatedItems[i]['tanggal_formatted']

                    //         var tr = document.createElement('tr');
                    //         let item_element = document.createElement('td')
                    //         let item_element2 = document.createElement('td')
                    //         let item_element3 = document.createElement('td')
                    //         let item_element4 = document.createElement('td')
                    //         let item_element5 = document.createElement('td')
                    //         let item_element6 = document.createElement('td')
                    //         let item_element7 = document.createElement('td')
                    //         let item_element8 = document.createElement('td')
                    //         let item_element9 = document.createElement('td')
                    //         let item_element10 = document.createElement('td')
                    //         let item_element11 = document.createElement('td')
                    //         let item_element12 = document.createElement('td')
                    //         let item_element13 = document.createElement('td')
                    //         let item_element14 = document.createElement('td')
                    //         let item_element15 = document.createElement('td')
                    //         let item_element16 = document.createElement('td')

                    //         // item_element.classList.add('item')
                    //         item_element.innerText = item
                    //         item_element2.innerText = item2
                    //         item_element3.innerText = item3
                    //         item_element4.innerText = item4
                    //         item_element5.innerText = item5
                    //         item_element6.innerText = item6
                    //         item_element7.innerText = item7
                    //         item_element7.innerText = item7
                    //         item_element8.innerText = item8
                    //         item_element9.innerText = item9
                    //         item_element10.innerText = item10
                    //         item_element11.innerText = item11
                    //         item_element12.innerText = item12
                    //         item_element13.innerText = item13
                    //         item_element14.innerText = item14
                    //         item_element15.innerText = item15
                    //         item_element16.innerText = item16

                    //         tr.appendChild(item_element);
                    //         tr.appendChild(item_element2);
                    //         tr.appendChild(item_element3);
                    //         tr.appendChild(item_element4);
                    //         tr.appendChild(item_element5);
                    //         tr.appendChild(item_element6);
                    //         tr.appendChild(item_element7);
                    //         tr.appendChild(item_element8);
                    //         tr.appendChild(item_element9);
                    //         tr.appendChild(item_element10);
                    //         tr.appendChild(item_element11);
                    //         tr.appendChild(item_element12);
                    //         tr.appendChild(item_element13);
                    //         tr.appendChild(item_element14);
                    //         tr.appendChild(item_element15);
                    //         wrapper.appendChild(tr)
                    //         inc++
                    //     }
                    // }




                    // t.row.add(parserowHeader).draw()
                    // $('#tableData').DataTable( {
                    //     data: data,
                    //     columns: [
                    //         { data: 'name' },
                    //         { data: 'position' },
                    //         { data: 'salary' },
                    //         { data: 'office' }
                    //     ]
                    // } );

                    // var arrResult = Object.entries(result)

                    // // console.log(arrResult);
                    // var parseRowVal = ''
                    // arrResult.forEach(element => {
                    //     var childArr = Object.entries(element[1])
                    //     var rowVal = '['
                    //     childArr.forEach(val => {
                    //         rowVal += '"' + val[1]+ '",'
                    //     });
                    //     rowVal = rowVal.substring(0, rowVal.length -1)
                    //     rowVal += ']'
                    //     var parseRowVal = JSON.parse(rowVal)

                    //     t.row.add(parseRowVal).draw()

                    // });
                    // t.row.add([10,2]).draw()

                    // arrResult.forEach(element => {
                    //     console.log(element)
                    //     var childArr = Object.entries(element[1])

                    //     childArr.forEach(val => {
                    //         if (val[0] == 'December') {
                    //             if (val[1] != '-') {
                    //                 var valPerMonth = Object.entries(val[1])
                    //                 valPerMonth.forEach(valMonth => {
                    //                     // console.log(valMonth[1])
                    //                 });
                    //             }
                    //         }
                    //     });
                    //     t.row.add([element[1]['wil'], element[1]['estate'], element[1]['est'], element[1]['wil'], element[1]['wil'],element[1]['skor_bulan_January']]).draw();
                    // });

                    // t.row.add([]).draw();
                    // t.row.add([]).draw();
                    // t.row.add([]).draw();
                    // t.row.add([]).draw();
                    // t.row.add([]).draw();
                    // t.row.add([]).draw();
                    // t.row.add([]).draw();
                    // t.row.add([]).draw();
                    // t.row.add([]).draw();
                    // t.row.add([]).draw();
                    // t.row.add([]).draw();
                    // t.row.add([]).draw();
                    // t.row.add( [ result, 32, 'Edinburgh' , result, 32, 'Edinburgh', result, 32, 'Edinburgh', result, 32, 'Edinburgh', result, 32, 'Edinburgh', result, 32, 'Edinburgh', result, 32, 'Edinburgh', result, 32, 'Edinburgh', result, 32, 'Edinburgh', result, 32, 'Edinburgh', result, 32, 'Edinburgh', result, 32, 'Edinburgh', result, 32, 'Edinburgh']).draw();

                }
            })
        }
    </script>
</x-layout.app>