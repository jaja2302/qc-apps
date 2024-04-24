<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weeks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <!-- <script src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script> -->

    <!-- <script src="{{asset('sheetjs/dist/xlsx.bundle.js')}}"></script> -->

    <script type="text/javascript" src="{{ asset('table-to-excel-master/dist/tableToExcel.js') }}"></script>
</head>


<body>

    <table>
        <thead>
            <tr>
                <th style="text-align:center;vertical-align:center;border:1px solid black" rowspan="3">EST</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black" rowspan="3">AFD</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black" colspan="10"> H+1</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black" colspan="10"> H+2</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black" colspan="10"> H+3</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black" colspan="10"> H+4</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black" colspan="10"> H+5</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black" colspan="10"> H+6</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black" colspan="10"> H+7</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black" colspan="10"> >H+7 </th>
                <th style="text-align:center;vertical-align:center;border:1px solid black" rowspan="3"> All Skor</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black" rowspan="3"> Kategori</th>
            </tr>
            <tr>
                <th style="text-align:center;vertical-align:center;border:1px solid black" colspan="6">Brondolan Tinggal</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black" colspan="4">Buah Tinggal</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black" colspan="6">Brondolan Tinggal</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black" colspan="4">Buah Tinggal</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black" colspan="6">Brondolan Tinggal</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black" colspan="4">Buah Tinggal</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black" colspan="6">Brondolan Tinggal</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black" colspan="4">Buah Tinggal</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black" colspan="6">Brondolan Tinggal</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black" colspan="4">Buah Tinggal</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black" colspan="6">Brondolan Tinggal</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black" colspan="4">Buah Tinggal</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black" colspan="6">Brondolan Tinggal</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black" colspan="4">Buah Tinggal</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black" colspan="6">Brondolan Tinggal</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black" colspan="4">Buah Tinggal</th>
            </tr>
            <tr>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Di TPH</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Di Jalan</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Di Bin</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Di Karung</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Total Brd</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Skor</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Buah Sortiran / Buah Jatuh </th>
                <th style="text-align:center;vertical-align:center;border:1px solid black">Restan Tidak Dilaporkan </th>
                <th style="text-align:center;vertical-align:center;border:1px solid black">Total Jjg </th>
                <th style="text-align:center;vertical-align:center;border:1px solid black">Skor</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Di TPH</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Di Jalan</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Di Bin</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Di Karung</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Total Brd</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Skor</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Buah Sortiran / Buah Jatuh </th>
                <th style="text-align:center;vertical-align:center;border:1px solid black">Restan Tidak Dilaporkan </th>
                <th style="text-align:center;vertical-align:center;border:1px solid black">Total Jjg </th>
                <th style="text-align:center;vertical-align:center;border:1px solid black">Skor</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Di TPH</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Di Jalan</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Di Bin</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Di Karung</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Total Brd</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Skor</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Buah Sortiran / Buah Jatuh </th>
                <th style="text-align:center;vertical-align:center;border:1px solid black">Restan Tidak Dilaporkan </th>
                <th style="text-align:center;vertical-align:center;border:1px solid black">Total Jjg </th>
                <th style="text-align:center;vertical-align:center;border:1px solid black">Skor</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Di TPH</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Di Jalan</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Di Bin</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Di Karung</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Total Brd</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Skor</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Buah Sortiran / Buah Jatuh </th>
                <th style="text-align:center;vertical-align:center;border:1px solid black">Restan Tidak Dilaporkan </th>
                <th style="text-align:center;vertical-align:center;border:1px solid black">Total Jjg </th>
                <th style="text-align:center;vertical-align:center;border:1px solid black">Skor</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Di TPH</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Di Jalan</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Di Bin</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Di Karung</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Total Brd</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Skor</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Buah Sortiran / Buah Jatuh </th>
                <th style="text-align:center;vertical-align:center;border:1px solid black">Restan Tidak Dilaporkan </th>
                <th style="text-align:center;vertical-align:center;border:1px solid black">Total Jjg </th>
                <th style="text-align:center;vertical-align:center;border:1px solid black">Skor</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Di TPH</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Di Jalan</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Di Bin</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Di Karung</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Total Brd</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Skor</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Buah Sortiran / Buah Jatuh </th>
                <th style="text-align:center;vertical-align:center;border:1px solid black">Restan Tidak Dilaporkan </th>
                <th style="text-align:center;vertical-align:center;border:1px solid black">Total Jjg </th>
                <th style="text-align:center;vertical-align:center;border:1px solid black">Skor</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Di TPH</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Di Jalan</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Di Bin</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Di Karung</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Total Brd</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Skor</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Buah Sortiran / Buah Jatuh </th>
                <th style="text-align:center;vertical-align:center;border:1px solid black">Restan Tidak Dilaporkan </th>
                <th style="text-align:center;vertical-align:center;border:1px solid black">Total Jjg </th>
                <th style="text-align:center;vertical-align:center;border:1px solid black">Skor</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Di TPH</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Di Jalan</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Di Bin</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Di Karung</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Total Brd</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Skor</th>
                <th style="text-align:center;vertical-align:center;border:1px solid black"> Buah Sortiran / Buah Jatuh </th>
                <th style="text-align:center;vertical-align:center;border:1px solid black">Restan Tidak Dilaporkan </th>
                <th style="text-align:center;vertical-align:center;border:1px solid black">Total Jjg </th>
                <th style="text-align:center;vertical-align:center;border:1px solid black">Skor</th>

            </tr>
        </thead>

        <tbody>

            @foreach ($data as $items1)
            @foreach ($items1 as $items2)

            @php

            if($items2['afd'] === 'EST'){
            $color = '#76C5E8';
            }else if ($items2['afd'] === 'Reg'){
            $color = '#FF7043';
            }else if ($items2['afd'] === 'WIL'){
            $color = '#B8AE5B';
            }
            else{
            $color = '#EBEBEB';
            };

            if ($items2['total_score'] >= 95) {
            $newktg = "EXCELLENT";
            $color2 = '#5074c4';
            } elseif ($items2['total_score'] >= 85) {
            $newktg = "GOOD";
            $color2 = '#08fc2c';
            } elseif ($items2['total_score'] >= 75) {
            $newktg = "SATISFACTORY";
            $color2 = '#ffdc04';
            } elseif ($items2['total_score'] >= 65) {
            $newktg = "FAIR";
            $color2 = '#ffa404';
            } else {
            $newktg = "POOR";
            $color2 = '#ff0404';
            }

            @endphp
            <tr>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['est']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['afd']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['tph1']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['jalan1']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['bin1']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['karung1']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['tot_brd1']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['skor_brd1']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['buah1']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['restan1']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['tod_jjg1']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['skor_janjang1']}}</td>

                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['tph2']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['jalan2']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['bin2']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['karung2']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['tot_brd2']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['skor_brd2']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['buah2']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['restan2']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['tod_jjg2']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['skor_janjang2']}}</td>

                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['tph3']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['jalan3']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['bin3']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['karung3']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['tot_brd3']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['skor_brd3']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['buah3']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['restan3']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['tod_jjg3']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['skor_janjang3']}}</td>

                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['tph4']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['jalan4']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['bin4']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['karung4']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['tot_brd4']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['skor_brd4']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['buah4']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['restan4']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['tod_jjg4']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['skor_janjang4']}}</td>

                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['tph5']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['jalan5']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['bin5']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['karung5']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['tot_brd5']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['skor_brd5']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['buah5']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['restan5']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['tod_jjg5']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['skor_janjang5']}}</td>

                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['tph6']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['jalan6']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['bin6']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['karung6']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['tot_brd6']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['skor_brd6']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['buah6']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['restan6']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['tod_jjg6']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['skor_janjang6']}}</td>

                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['tph7']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['jalan7']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['bin7']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['karung7']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['tot_brd7']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['skor_brd7']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['buah7']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['restan7']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['tod_jjg7']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['skor_janjang7']}}</td>

                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['tph8']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['jalan8']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['bin8']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['karung8']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['tot_brd8']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['skor_brd8']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['buah8']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['restan8']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['tod_jjg8']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['skor_janjang8']}}</td>
                <td style="background-color:{{$color}};text-align:center;vertical-align:center;border: 1px solid black">{{$items2['total_score']}}</td>
                <td>{{$newktg}}</td>

            </tr>

            @endforeach
            @endforeach


        </tbody>
    </table>


</body>

</html>