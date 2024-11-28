<table>
    <thead>
        <tr>
            <th rowspan="3">EST</th>
            <th rowspan="3">AFD</th>
            <th colspan="10"> H+1</th>
            <th colspan="10"> H+2</th>
            <th colspan="10"> H+3</th>
            <th colspan="10"> H+4</th>
            <th colspan="10"> H+5</th>
            <th colspan="10"> H+6</th>
            <th colspan="10"> H+7</th>
            <th colspan="10"> >H+7 </th>
            <th rowspan="3"> All Skor</th>
            <th rowspan="3"> Kategori</th>
        </tr>
        <tr>
            <th colspan="6">Brondolan Tinggal</th>
            <th colspan="4">Buah Tinggal</th>
            <th colspan="6">Brondolan Tinggal</th>
            <th colspan="4">Buah Tinggal</th>
            <th colspan="6">Brondolan Tinggal</th>
            <th colspan="4">Buah Tinggal</th>
            <th colspan="6">Brondolan Tinggal</th>
            <th colspan="4">Buah Tinggal</th>
            <th colspan="6">Brondolan Tinggal</th>
            <th colspan="4">Buah Tinggal</th>
            <th colspan="6">Brondolan Tinggal</th>
            <th colspan="4">Buah Tinggal</th>
            <th colspan="6">Brondolan Tinggal</th>
            <th colspan="4">Buah Tinggal</th>
            <th colspan="6">Brondolan Tinggal</th>
            <th colspan="4">Buah Tinggal</th>
        </tr>
        <tr>
            <th> Di TPH</th>
            <th> Di Jalan</th>
            <th> Di Bin</th>
            <th> Di Karung</th>
            <th> Total Brd</th>
            <th> Skor</th>
            <th> Buah Sortiran / Buah Jatuh </th>
            <th>Restan Tidak Dilaporkan </th>
            <th>Total Jjg </th>
            <th>Skor</th>
            <th> Di TPH</th>
            <th> Di Jalan</th>
            <th> Di Bin</th>
            <th> Di Karung</th>
            <th> Total Brd</th>
            <th> Skor</th>
            <th> Buah Sortiran / Buah Jatuh </th>
            <th>Restan Tidak Dilaporkan </th>
            <th>Total Jjg </th>
            <th>Skor</th>
            <th> Di TPH</th>
            <th> Di Jalan</th>
            <th> Di Bin</th>
            <th> Di Karung</th>
            <th> Total Brd</th>
            <th> Skor</th>
            <th> Buah Sortiran / Buah Jatuh </th>
            <th>Restan Tidak Dilaporkan </th>
            <th>Total Jjg </th>
            <th>Skor</th>
            <th> Di TPH</th>
            <th> Di Jalan</th>
            <th> Di Bin</th>
            <th> Di Karung</th>
            <th> Total Brd</th>
            <th> Skor</th>
            <th> Buah Sortiran / Buah Jatuh </th>
            <th>Restan Tidak Dilaporkan </th>
            <th>Total Jjg </th>
            <th>Skor</th>
            <th> Di TPH</th>
            <th> Di Jalan</th>
            <th> Di Bin</th>
            <th> Di Karung</th>
            <th> Total Brd</th>
            <th> Skor</th>
            <th> Buah Sortiran / Buah Jatuh </th>
            <th>Restan Tidak Dilaporkan </th>
            <th>Total Jjg </th>
            <th>Skor</th>
            <th> Di TPH</th>
            <th> Di Jalan</th>
            <th> Di Bin</th>
            <th> Di Karung</th>
            <th> Total Brd</th>
            <th> Skor</th>
            <th> Buah Sortiran / Buah Jatuh </th>
            <th>Restan Tidak Dilaporkan </th>
            <th>Total Jjg </th>
            <th>Skor</th>
            <th> Di TPH</th>
            <th> Di Jalan</th>
            <th> Di Bin</th>
            <th> Di Karung</th>
            <th> Total Brd</th>
            <th> Skor</th>
            <th> Buah Sortiran / Buah Jatuh </th>
            <th>Restan Tidak Dilaporkan </th>
            <th>Total Jjg </th>
            <th>Skor</th>
            <th> Di TPH</th>
            <th> Di Jalan</th>
            <th> Di Bin</th>
            <th> Di Karung</th>
            <th> Total Brd</th>
            <th> Skor</th>
            <th> Buah Sortiran / Buah Jatuh </th>
            <th>Restan Tidak Dilaporkan </th>
            <th>Total Jjg </th>
            <th>Skor</th>

        </tr>
    </thead>

    <tbody>

        @foreach ($data as $items2)
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


    </tbody>
</table>