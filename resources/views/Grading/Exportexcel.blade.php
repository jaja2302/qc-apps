<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    @if($type !== 'perafdeling')
    <table class="table table-responsive table-striped table-bordered">
        <thead>
            <tr>
                <th colspan="30" style="border: 1px solid black;background-color: #c8e4f4;text-align:center">BERDASARKAN ESTATE</th>
            </tr>
            <tr>
                <th rowspan="3" class="align-middle" style="border: 1px solid black;background-color: #f0ecec;vertical-align:center">Estate</th>
                <th style="border: 1px solid black;background-color: #f0ecec;text-align:center" colspan="2">UNIT SORTASI</th>
                <th style="border: 1px solid black;background-color: #88e48c;text-align:center" colspan="20">HASIL GRADING</th>
                <th style="border: 1px solid black;background-color: #f8c4ac;text-align:center" colspan="6">KELAS JANJANG</th>
                <th style="border: 1px solid black;background-color: #B1A1C6;text-align:center" colspan="4">BUAH MENTAH</th>
            </tr>
            <tr>
                <th style="border: 1px solid black;background-color: #f0ecec; vertical-align: center; word-wrap: break-word;" class="align-middle" rowspan="2">JUMLAH JANJANG GRADING</th>
                <th style="border: 1px solid black;background-color: #f0ecec;vertical-align:center;word-wrap: break-word" class="align-middle" rowspan="2">TONASE(KG)</th>
                <th style="border: 1px solid black;background-color: #88e48c;text-align:center" colspan="2">RIPENESS</th>
                <th style="border: 1px solid black;background-color: #88e48c;text-align:center" colspan="2">UNRIPE</th>
                <th style="border: 1px solid black;background-color: #88e48c;text-align:center" colspan="2">OVERRIPE</th>
                <th style="border: 1px solid black;background-color: #88e48c;text-align:center" colspan="2">EMPTY BUNCH</th>
                <th style="border: 1px solid black;background-color: #88e48c;text-align:center" colspan="2">ROTTEN BUNCH</th>
                <th style="border: 1px solid black;background-color: #88e48c;text-align:center" colspan="2">ABNORMAL</th>
                <th style="border: 1px solid black;background-color: #88e48c;text-align:center" colspan="2">LONG STALK</th>
                <th style="border: 1px solid black;background-color: #88e48c;text-align:center" colspan="2">V-CUT</th>
                <th style="border: 1px solid black;background-color: #88e48c;text-align:center" colspan="2">DIRT</th>
                <th style="border: 1px solid black;background-color: #88e48c;text-align:center" colspan="2">LOOSE FRUIT</th>
                <th style="border: 1px solid black;background-color: #f8c4ac;text-align:center" colspan="2">KELAS C</th>
                <th style="border: 1px solid black;background-color: #f8c4ac;text-align:center" colspan="2">KELAS B</th>
                <th style="border: 1px solid black;background-color: #f8c4ac;text-align:center" colspan="2">KELAS A</th>
                <th style="border: 1px solid black;background-color: #B1A1C6;text-align:center" colspan="2">TIDAK BRONDOL</th>
                <th style="border: 1px solid black;background-color: #B1A1C6;text-align:center" colspan="2">KURANG BRONDOL</th>
            </tr>
            <tr>
                <th style="border: 1px solid black;background-color: #88e48c;text-align:center">JJG</th>
                <th style="border: 1px solid black;background-color: #88e48c;text-align:center">%</th>
                <th style="border: 1px solid black;background-color: #88e48c;text-align:center">JJG</th>
                <th style="border: 1px solid black;background-color: #88e48c;text-align:center">%</th>
                <th style="border: 1px solid black;background-color: #88e48c;text-align:center">JJG</th>
                <th style="border: 1px solid black;background-color: #88e48c;text-align:center">%</th>
                <th style="border: 1px solid black;background-color: #88e48c;text-align:center">JJG</th>
                <th style="border: 1px solid black;background-color: #88e48c;text-align:center">%</th>
                <th style="border: 1px solid black;background-color: #88e48c;text-align:center">JJG</th>
                <th style="border: 1px solid black;background-color: #88e48c;text-align:center">%</th>
                <th style="border: 1px solid black;background-color: #88e48c;text-align:center">JJG</th>
                <th style="border: 1px solid black;background-color: #88e48c;text-align:center">%</th>
                <th style="border: 1px solid black;background-color: #88e48c;text-align:center">JJG</th>
                <th style="border: 1px solid black;background-color: #88e48c;text-align:center">%</th>
                <th style="border: 1px solid black;background-color: #88e48c;text-align:center">JJG</th>
                <th style="border: 1px solid black;background-color: #88e48c;text-align:center">%</th>
                <th style="border: 1px solid black;background-color: #88e48c;text-align:center">JJG</th>
                <th style="border: 1px solid black;background-color: #88e48c;text-align:center">%</th>
                <th style="border: 1px solid black;background-color: #88e48c;text-align:center">JJG</th>
                <th style="border: 1px solid black;background-color: #88e48c;text-align:center">%</th>
                <th style="border: 1px solid black;background-color: #f8c4ac;text-align:center">JJG</th>
                <th style="border: 1px solid black;background-color: #f8c4ac;text-align:center">%</th>
                <th style="border: 1px solid black;background-color: #f8c4ac;text-align:center">JJG</th>
                <th style="border: 1px solid black;background-color: #f8c4ac;text-align:center">%</th>
                <th style="border: 1px solid black;background-color: #f8c4ac;text-align:center">JJG</th>
                <th style="border: 1px solid black;background-color: #f8c4ac;text-align:center">%</th>
                <th style="border: 1px solid black;background-color: #f8c4ac;text-align:center">JJG</th>
                <th style="border: 1px solid black;background-color: #f8c4ac;text-align:center">%</th>
                <th style="border: 1px solid black;background-color: #f8c4ac;text-align:center">JJG</th>
                <th style="border: 1px solid black;background-color: #f8c4ac;text-align:center">%</th
                    </tr>
        </thead>
        <tbody id="regional_estate">
            @foreach($data as $key => $value)
            @foreach($value as $key2 => $value2)
            <tr>
                <td style="border: 1px solid black;">{{$key}}</td>
                <td style="border: 1px solid black;">{{$value2['jjg_grading']}}</td>
                <td style="border: 1px solid black;">{{$value2['tonase']}}</td>
                <td style="border: 1px solid black;">{{$value2['ripeness']}}</td>
                <td style="border: 1px solid black;">{{ round($value2['percentage_ripeness'], 2) }}</td>
                <td style="border: 1px solid black;">{{$value2['unripe']}}</td>
                <td style="border: 1px solid black;">{{round($value2['percentage_unripe'],2)}}</td>
                <td style="border: 1px solid black;">{{$value2['overripe']}}</td>
                <td style="border: 1px solid black;">{{round($value2['percentage_overripe'],2)}}</td>
                <td style="border: 1px solid black;">{{$value2['empty_bunch']}}</td>
                <td style="border: 1px solid black;">{{round($value2['percentage_empty_bunch'],2)}}</td>
                <td style="border: 1px solid black;">{{$value2['rotten_bunch']}}</td>
                <td style="border: 1px solid black;">{{round($value2['percentage_rotten_bunch'],2)}}</td>
                <td style="border: 1px solid black;">{{$value2['abnormal']}}</td>
                <td style="border: 1px solid black;">{{round($value2['percentage_abnormal'],2)}}</td>
                <td style="border: 1px solid black;">{{$value2['longstalk']}}</td>
                <td style="border: 1px solid black;">{{round($value2['percentage_longstalk'],2)}}</td>
                <td style="border: 1px solid black;">{{$value2['vcut']}}</td>
                <td style="border: 1px solid black;">{{round($value2['percentage_vcut'],2)}}</td>
                <td style="border: 1px solid black;">{{$value2['dirt']}}</td>
                <td style="border: 1px solid black;">{{round($value2['percentage_dirt'],2)}}</td>
                <td style="border: 1px solid black;">{{$value2['loose_fruit']}}</td>
                <td style="border: 1px solid black;">{{round($value2['percentage_loose_fruit'],2)}}</td>
                <td style="border: 1px solid black;">{{$value2['kelas_c']}}</td>
                <td style="border: 1px solid black;">{{round($value2['percentage_kelas_c'],2)}}</td>
                <td style="border: 1px solid black;">{{$value2['kelas_b']}}</td>
                <td style="border: 1px solid black;">{{round($value2['percentage_kelas_b'],2)}}</td>
                <td style="border: 1px solid black;">{{$value2['kelas_a']}}</td>
                <td style="border: 1px solid black;">{{round($value2['percentage_kelas_a'],2)}}</td>
                <td style="border: 1px solid black;">{{$value2['unripe_tanpa_brondol']}}</td>
                <td style="border: 1px solid black;">{{round($value2['persentase_unripe_tanpa_brondol'],2)}}</td>
                <td style="border: 1px solid black;">{{$value2['unripe_kurang_brondol']}}</td>
                <td style="border: 1px solid black;">{{round($value2['persentase_unripe_kurang_brondol'],2)}}</td>
            </tr>
            @endforeach
            @endforeach
        </tbody>
    </table>

    @else
    <table class="table table-responsive table-striped table-bordered">
        <thead>
            <tr>
                <th style="border:1px solid black;text-align:center;background-color: #f0ecec;vertical-align:center" class="align-middle" rowspan="3">Estate</th>
                <th style="border:1px solid black;text-align:center;background-color: #f0ecec;vertical-align:center" class="align-middle" rowspan="3">Afdeling</th>
                <th style="border:1px solid black;text-align:center;background-color: #f0ecec;" colspan="4">UNIT SORTASI</th>
                <th style="border:1px solid black;text-align:center;background-color: #88e48c;" colspan="20">HASIL GRADING</th>
                <th style="border:1px solid black;text-align:center;background-color: #f8c4ac;" colspan="6">KELAS JANJANG</th>
                <th style="border:1px solid black;text-align:center;background-color: #B1A1C6;" colspan="4">BUAH MENTAH</th>
            </tr>
            <tr>
                <th style="border:1px solid black;text-align:center;background-color: #f0ecec; word-wrap: break-word;" class="align-middle" rowspan="2">JUMLAH JANJANG SPB</th>
                <th style="border:1px solid black;text-align:center;background-color: #f0ecec; word-wrap: break-word;" class="align-middle" rowspan="2">JUMLAH JANJANG GRADING</th>
                <th style="border:1px solid black;text-align:center;background-color: #f0ecec; word-wrap: break-word;" class="align-middle" rowspan="2">TONASE (KG)</th>
                <th style="border:1px solid black;text-align:center;background-color: #f0ecec;vertical-align:center" class="align-middle" rowspan="2">BJR (KG)</th>
                <th style="border:1px solid black;text-align:center;background-color: #88e48c;" colspan="2">RIPENESS</th>
                <th style="border:1px solid black;text-align:center;background-color: #88e48c;" colspan="2">UNRIPE</th>
                <th style="border:1px solid black;text-align:center;background-color: #88e48c;" colspan="2">OVERRIPE</th>
                <th style="border:1px solid black;text-align:center;background-color: #88e48c;" colspan="2">EMPTY BUNCH</th>
                <th style="border:1px solid black;text-align:center;background-color: #88e48c;" colspan="2">ROTTEN BUNCH</th>
                <th style="border:1px solid black;text-align:center;background-color: #88e48c;" colspan="2">ABNORMAL</th>
                <th style="border:1px solid black;text-align:center;background-color: #88e48c;" colspan="2">LONG STALK</th>
                <th style="border:1px solid black;text-align:center;background-color: #88e48c;" colspan="2">V-CUT</th>
                <th style="border:1px solid black;text-align:center;background-color: #88e48c;" colspan="2">DIRT</th>
                <th style="border:1px solid black;text-align:center;background-color: #88e48c;" colspan="2">LOOSE FRUIT</th>
                <th style="border:1px solid black;text-align:center;background-color: #f8c4ac;" colspan="2">KELAS C</th>
                <th style="border:1px solid black;text-align:center;background-color: #f8c4ac;" colspan="2">KELAS B</th>
                <th style="border:1px solid black;text-align:center;background-color: #f8c4ac;" colspan="2">KELAS A</th>
                <th style="border:1px solid black;text-align:center;background-color: #B1A1C6;" colspan="2">TIDAK BRONDOL</th>
                <th style="border:1px solid black;text-align:center;background-color: #B1A1C6;" colspan="2">KURANG BRONDOL</th>
            </tr>
            <tr>
                <th style="border:1px solid black;text-align:center;background-color: #88e48c;">JJG</th>
                <th style="border:1px solid black;text-align:center;background-color: #88e48c;">%</th>
                <th style="border:1px solid black;text-align:center;background-color: #88e48c;">JJG</th>
                <th style="border:1px solid black;text-align:center;background-color: #88e48c;">%</th>
                <th style="border:1px solid black;text-align:center;background-color: #88e48c;">JJG</th>
                <th style="border:1px solid black;text-align:center;background-color: #88e48c;">%</th>
                <th style="border:1px solid black;text-align:center;background-color: #88e48c;">JJG</th>
                <th style="border:1px solid black;text-align:center;background-color: #88e48c;">%</th>
                <th style="border:1px solid black;text-align:center;background-color: #88e48c;">JJG</th>
                <th style="border:1px solid black;text-align:center;background-color: #88e48c;">%</th>
                <th style="border:1px solid black;text-align:center;background-color: #88e48c;">JJG</th>
                <th style="border:1px solid black;text-align:center;background-color: #88e48c;">%</th>
                <th style="border:1px solid black;text-align:center;background-color: #88e48c;">JJG</th>
                <th style="border:1px solid black;text-align:center;background-color: #88e48c;">%</th>
                <th style="border:1px solid black;text-align:center;background-color: #88e48c;">JJG</th>
                <th style="border:1px solid black;text-align:center;background-color: #88e48c;">%</th>
                <th style="border:1px solid black;text-align:center;background-color: #88e48c;">JJG</th>
                <th style="border:1px solid black;text-align:center;background-color: #88e48c;">%</th>
                <th style="border:1px solid black;text-align:center;background-color: #88e48c;">JJG</th>
                <th style="border:1px solid black;text-align:center;background-color: #88e48c;">%</th>
                <th style="border:1px solid black;text-align:center;background-color: #f8c4ac;">JJG</th>
                <th style="border:1px solid black;text-align:center;background-color: #f8c4ac;">%</th>
                <th style="border:1px solid black;text-align:center;background-color: #f8c4ac;">JJG</th>
                <th style="border:1px solid black;text-align:center;background-color: #f8c4ac;">%</th>
                <th style="border:1px solid black;text-align:center;background-color: #f8c4ac;">JJG</th>
                <th style="border:1px solid black;text-align:center;background-color: #f8c4ac;">%</th>
                <th style="border:1px solid black;text-align:center;background-color: #f8c4ac;">JJG</th>
                <th style="border:1px solid black;text-align:center;background-color: #f8c4ac;">%</th>
                <th style="border:1px solid black;text-align:center;background-color: #f8c4ac;">JJG</th>
                <th style="border:1px solid black;text-align:center;background-color: #f8c4ac;">%</th>
            </tr>
        </thead>
        <tbody id="rekap_afdeling_data">
            @if($data == [])
            <tr>
                <td colspan="36" style="color: #88e48c;">Tidak ada data tersedia</td>
            </tr>
            @else



            @foreach ($data as $key1 => $items1)
            @if ($key1 !== 'Total')
            @foreach ($items1 as $key2 => $items2)
            @if($key2 == 'afdeling')
            <tr>
                <td>{{$key1}}</td>
                <td>{{$items2['afdeling']}}</td>
                <td>{{$items2['jjg_spb']}}</td>
                <td>{{$items2['jjg_grading']}}</td>
                <td>{{$items2['tonase']}}</td>
                <td>{{round($items2['bjr'],2)}}</td>
                <td>{{$items2['ripeness']}}</td>
                <td>{{round($items2['percentage_ripeness'],2)}}</td>
                <td>{{$items2['unripe']}}</td>
                <td>{{round($items2['percentage_unripe'],2)}}</td>
                <td>{{$items2['overripe']}}</td>
                <td>{{round($items2['percentage_overripe'],2)}}</td>
                <td>{{$items2['empty_bunch']}}</td>
                <td>{{round($items2['percentage_empty_bunch'],2)}}</td>
                <td>{{$items2['rotten_bunch']}}</td>
                <td>{{round($items2['percentage_rotten_bunch'],2)}}</td>
                <td>{{$items2['abnormal']}}</td>
                <td>{{round($items2['percentage_abnormal'],2)}}</td>
                <td>{{$items2['longstalk']}}</td>
                <td>{{round($items2['percentage_longstalk'],2)}}</td>
                <td>{{$items2['vcut']}}</td>
                <td>{{round($items2['percentage_vcut'],2)}}</td>
                <td>{{$items2['dirt']}}</td>
                <td>{{round($items2['percentage_dirt'],2)}}</td>
                <td>{{$items2['loose_fruit']}}</td>
                <td>{{round($items2['percentage_loose_fruit'],2)}}</td>
                <td>{{$items2['kelas_c']}}</td>
                <td>{{round($items2['percentage_kelas_c'],2)}}</td>
                <td>{{$items2['kelas_b']}}</td>
                <td>{{round($items2['percentage_kelas_b'],2)}}</td>
                <td>{{$items2['kelas_a']}}</td>
                <td>{{round($items2['percentage_kelas_a'],2)}}</td>
                <td>{{round($items2['unripe_tanpa_brondol'],2)}}</td>
                <td>{{round($items2['persentase_unripe_tanpa_brondol'],2)}}</td>
                <td>{{round($items2['unripe_kurang_brondol'],2)}}</td>
                <td>{{round($items2['persentase_unripe_kurang_brondol'],2)}}</td>

            </tr>
            @endif
            @endforeach
            @else
            <tr>
                <td style="background-color: #9DCED4;">{{$key1}}</td>
                <td style="background-color: #9DCED4;">Total</td>
                <td style="background-color: #9DCED4;">{{$items1['jjg_spb']}}</td>
                <td style="background-color: #9DCED4;">{{$items1['jjg_grading']}}</td>
                <td style="background-color: #9DCED4;">{{$items1['tonase']}}</td>
                <td style="background-color: #9DCED4;">{{round($items1['bjr'],2)}}</td>
                <td style="background-color: #9DCED4;">{{$items1['ripeness']}}</td>
                <td style="background-color: #9DCED4;">{{round($items1['percentage_ripeness'],2)}}</td>
                <td style="background-color: #9DCED4;">{{$items1['unripe']}}</td>
                <td style="background-color: #9DCED4;">{{round($items1['percentage_unripe'],2)}}</td>
                <td style="background-color: #9DCED4;">{{$items1['overripe']}}</td>
                <td style="background-color: #9DCED4;">{{round($items1['percentage_overripe'],2)}}</td>
                <td style="background-color: #9DCED4;">{{$items1['empty_bunch']}}</td>
                <td style="background-color: #9DCED4;">{{round($items1['percentage_empty_bunch'],2)}}</td>
                <td style="background-color: #9DCED4;">{{$items1['rotten_bunch']}}</td>
                <td style="background-color: #9DCED4;">{{round($items1['percentage_rotten_bunch'],2)}}</td>
                <td style="background-color: #9DCED4;">{{$items1['abnormal']}}</td>
                <td style="background-color: #9DCED4;">{{round($items1['percentage_abnormal'],2)}}</td>
                <td style="background-color: #9DCED4;">{{$items1['longstalk']}}</td>
                <td style="background-color: #9DCED4;">{{round($items1['percentage_longstalk'],2)}}</td>
                <td style="background-color: #9DCED4;">{{$items1['vcut']}}</td>
                <td style="background-color: #9DCED4;">{{round($items1['percentage_vcut'],2)}}</td>
                <td style="background-color: #9DCED4;">{{$items1['dirt']}}</td>
                <td style="background-color: #9DCED4;">{{round($items1['percentage_dirt'],2)}}</td>
                <td style="background-color: #9DCED4;">{{$items1['loose_fruit']}}</td>
                <td style="background-color: #9DCED4;">{{round($items1['percentage_loose_fruit'],2)}}</td>
                <td style="background-color: #9DCED4;">{{$items1['kelas_c']}}</td>
                <td style="background-color: #9DCED4;">{{round($items1['percentage_kelas_c'],2)}}</td>
                <td style="background-color: #9DCED4;">{{$items1['kelas_b']}}</td>
                <td style="background-color: #9DCED4;">{{round($items1['percentage_kelas_b'],2)}}</td>
                <td style="background-color: #9DCED4;">{{$items1['kelas_a']}}</td>
                <td style="background-color: #9DCED4;">{{round($items1['percentage_kelas_a'],2)}}</td>
                <td style="background-color: #9DCED4;">{{round($items1['unripe_tanpa_brondol'],2)}}</td>
                <td style="background-color: #9DCED4;">{{round($items1['persentase_unripe_tanpa_brondol'],2)}}</td>
                <td style="background-color: #9DCED4;">{{round($items1['unripe_kurang_brondol'],2)}}</td>
                <td style="background-color: #9DCED4;">{{round($items1['persentase_unripe_kurang_brondol'],2)}}</td>
            </tr>
            @endif
            @endforeach



            @endif
        </tbody>
    </table>
    @endif
</body>

</html>