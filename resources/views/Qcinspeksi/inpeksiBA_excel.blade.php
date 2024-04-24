<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ba Excel</title>
</head>

<body>

    <table>
        <thead>
            <tr>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;" rowspan="3">No.</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;" rowspan="3">Blok.</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;" colspan="4" rowspan="2">DATA BLOK SAMPEL</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;" colspan="17">Mutu Ancak (MA)</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;" colspan="8">Mutu Transport (MT)</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;" colspan="23">Mutu Buah (MB)
                <th style="text-align: center;vertical-align:center;border: 1px solid black;" rowspan="3">All Skor</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;" rowspan="3">Kategori</th>
            </tr>
            <tr>
                {{-- Table Mutu Ancak --}}
                <th style="text-align: center;vertical-align:center;border: 1px solid black;" colspan="6">Brondolan Tinggal</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;" colspan="7">Buah Tinggal</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;" colspan="3">Pelepah Sengkleh</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;" rowspan="2">Total Skor</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;" rowspan="2">TPH Sampel</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;" colspan="3">Brd Tinggal</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;" colspan="3">Buah Tinggal</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;" rowspan="2">Total Skor</th>
                {{-- Table Mutu Buah --}}
                <th style="text-align: center;vertical-align:center;border: 1px solid black;" rowspan="2">TPH Sampel</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;" rowspan="2">Total Janjang Sampel</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;" colspan="3">Mentah (A)</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;" colspan="3">Matang (N)</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;" colspan="3">Lewat Matang(O)</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;" colspan="3">Janjang Kosong (E)</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;" colspan="3">Tidak Standar V-Cut</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;" colspan="2">Abnormal</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;" colspan="3">Penggunaan Karung Brondolan</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;" rowspan="2">Total Skor</th>
            </tr>
            <tr>
                {{-- Table Mutu Ancak --}}
                <th style="text-align: center;vertical-align:center;border: 1px solid black;">Jumlah Pokok Sampel</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;">Luas Ha Sampel</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;">Jumlah Jjg Panen</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;">AKP Realisasi</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;">P</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;">K</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;">GL</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;">Total Brd</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;">Brd/JJG</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;">Skor</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;">S</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;">M1</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;">M2</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;">M3</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;">Total JJG</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;">%</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;">Skor</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;">Pokok </th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;">%</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;">Skor</th>

                <th style="text-align: center;vertical-align:center;border: 1px solid black;">Butir</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;">Butir/TPH</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;">Skor</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;">Jjg</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;">Jjg/TPH</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;">Skor</th>
                {{-- table mutu Buah --}}
                <th style="text-align: center;vertical-align:center;border: 1px solid black;">Jjg</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;">%</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;">Skor</th>

                <th style="text-align: center;vertical-align:center;border: 1px solid black;">Jjg</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;">%</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;">Skor</th>

                <th style="text-align: center;vertical-align:center;border: 1px solid black;">Jjg</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;">%</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;">Skor</th>

                <th style="text-align: center;vertical-align:center;border: 1px solid black;">Jjg</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;">%</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;">Skor</th>

                <th style="text-align: center;vertical-align:center;border: 1px solid black;">Jjg</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;">%</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;">Skor</th>

                <th style="text-align: center;vertical-align:center;border: 1px solid black;">Jjg</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;">%</th>

                <th style="text-align: center;vertical-align:center;border: 1px solid black;">Ya</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;">%</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;">Skor</th>
            </tr>
        </thead>
        <tbody>
            @php
            $data_ancak = $data['mutuAncak_total'];
            $data_trans = $data['mutuTransport_total'];
            $data_buah = $data['mutuBuah_total'];
            $inc = 0;
            @endphp
            @foreach($data_ancak as $key => $items)

            @foreach($items as $key1 => $items1)
            @if(is_array($items1))
            <tr>
                <td>{{$inc++}}</td>
                <td>{{$key1}}</td>
                <td>{{$items1['jml_pokok_sampel']}}</td>
                <td>{{$items1['luas_ha']}}</td>
                <td>{{$items1['jml_jjg_panen']}}</td>
                <td>{{$items1['akp_real']}}</td>
                <td>{{$items1['p_ma']}}</td>
                <td>{{$items1['k_ma']}}</td>
                <td>{{$items1['gl_ma']}}</td>
                <td>{{$items1['total_brd_ma']}}</td>
                <td>{{$items1['btr_jjg_ma']}}</td>
                <td>{{$items1['skor_brd']}}</td>
                <td>{{$items1['bhts_ma']}}</td>
                <td>{{$items1['bhtm1_ma']}}</td>
                <td>{{$items1['bhtm2_ma']}}</td>
                <td>{{$items1['bhtm3_ma']}}</td>
                <td>{{$items1['tot_jjg_ma']}}</td>
                <td>{{$items1['jjg_tgl_ma']}}</td>
                <td>{{$items1['skor_buah']}}</td>
                <td>{{$items1['ps_ma']}}</td>
                <td>{{$items1['PerPSMA']}}</td>
                <td>{{$items1['skor_palepah']}}</td>
                <td>total skor ancak</td>
            </tr>
            @endif
            @endforeach
            @endforeach
        </tbody>

    </table>

</body>

</html>