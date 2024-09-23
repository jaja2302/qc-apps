<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excel Grading</title>


</head>

<body>
    <table class="table table-responsive table-striped table-bordered">
        <thead>
            <tr>
                <td colspan="35">{{$data['pt_name']}}</td>
            </tr>
            <tr>
                <td colspan="35">{{$data['mill_name']}}</td>
            </tr>
            <tr>
                <td colspan="35">LAPORAN GRADING PKS</td>
            </tr>
            <tr>
                <td colspan="2">TANGGAL</td>
                <td colspan="33">{{$data['tanggal']}}</td>
            </tr>
            <tr>
                <th colspan="35" style="background-color: #c8e4f4;border:1px solid black;text-align:center">BERDASARKAN ESTATE</th>
            </tr>
            <tr>
                <th rowspan="3" class="align-middle" style="background-color: #f0ecec;vertical-align: middle;border:1px solid black">Estate</th>
                <th rowspan="3" class="align-middle" style="background-color: #f0ecec;vertical-align: middle;border:1px solid black">Afdeling</th>
                <th style="border:1px solid black;background-color: #f0ecec;text-align:center" colspan="7">UNIT SORTASI</th>
                <th style="border:1px solid black;background-color: #88e48c;text-align:center" colspan="20">HASIL GRADING</th>
                <th style="border:1px solid black;background-color: #f8c4ac;text-align:center" colspan="6">KELAS JANJANG</th>
            </tr>
            <tr>
                <th style="border:1px solid black;background-color: #f0ecec; word-wrap: break-word;vertical-align: middle;" rowspan="2">No Polisi</th>
                <th style="border:1px solid black;background-color: #f0ecec; word-wrap: break-word;vertical-align: middle;" rowspan="2">
                    Waktu Grading
                </th>

                <th style="border:1px solid black;background-color: #f0ecec; word-wrap: break-word;vertical-align: middle;" rowspan="2">TONASE Timbangan(KG)</th>
                <th style="border:1px solid black;background-color: #f0ecec; word-wrap: break-word;vertical-align: middle;" rowspan="2">JUMLAH JANJANG SPD</th>
                <th style="border:1px solid black;background-color: #f0ecec; word-wrap: break-word;vertical-align: middle;" rowspan="2">JUMLAH JANJANG GRADING</th>
                <th style="border:1px solid black;background-color: #f0ecec; word-wrap: break-word;vertical-align: middle;" rowspan="2">TONASE Grading(KG)</th>
                <th style="border:1px solid black;background-color: #f0ecec; word-wrap: break-word;vertical-align: middle;" rowspan="2">BJR(KG)</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black" colspan="2">RIPENESS</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black" colspan="2">UNRIPE</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black" colspan="2">OVERRIPE</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black" colspan="2">EMPTY BUNCH</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black" colspan="2">ROTTEN BUNCH</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black" colspan="2">ABNORMAL</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black" colspan="2">LONG STALK</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black" colspan="2">V-CUT</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black" colspan="2">DIRT</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black" colspan="2">LOOSE FRUIT</th>
                <th style="background-color: #f8c4ac;text-align:center;border:1px solid black" colspan="2">KELAS C</th>
                <th style="background-color: #f8c4ac;text-align:center;border:1px solid black" colspan="2">KELAS B</th>
                <th style="background-color: #f8c4ac;text-align:center;border:1px solid black" colspan="2">KELAS A</th>
            </tr>
            <tr>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black">JJG</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black">%</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black">JJG</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black">%</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black">JJG</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black">%</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black">JJG</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black">%</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black">JJG</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black">%</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black">JJG</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black">%</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black">JJG</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black">%</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black">JJG</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black">%</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black">JJG</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black">%</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black">JJG</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black">%</th>
                <th style="background-color: #f8c4ac;text-align:center;border:1px solid black">JJG</th>
                <th style="background-color: #f8c4ac;text-align:center;border:1px solid black">%</th>
                <th style="background-color: #f8c4ac;text-align:center;border:1px solid black">JJG</th>
                <th style="background-color: #f8c4ac;text-align:center;border:1px solid black">%</th>
                <th style="background-color: #f8c4ac;text-align:center;border:1px solid black">JJG</th>
                <th style="background-color: #f8c4ac;text-align:center;border:1px solid black">%</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data['result'] as $key => $value)
            @foreach ($value as $key2 => $value2)
            @php
            if ($key2 === 'Total') {
            $bgcolor = '#94DCF8';
            }else{
            $bgcolor = 'white';
            }
            @endphp
            <tr>
                <td style="background-color:{{$bgcolor}};border:1px solid black;text-align:center">{{$key}}</td>
                <td style="background-color:{{$bgcolor}};border:1px solid black;text-align:center">{{$key2}}</td>
                <td style="background-color:{{$bgcolor}};border:1px solid black;text-align:center">{{$value2['no_plat']}}</td>
                <td style="background-color:{{$bgcolor}};border:1px solid black;text-align:center">{{$value2['unit']}}</td>
                <td style="background-color:{{$bgcolor}};border:1px solid black;text-align:center">{{$value2['tonase']}}</td>
                <td style="background-color:{{$bgcolor}};border:1px solid black;text-align:center">{{$value2['jumlah_janjang_spb']}}</td>
                <td style="background-color:{{$bgcolor}};border:1px solid black;text-align:center">{{$value2['jumlah_janjang_grading']}}</td>
                <td style="background-color:{{$bgcolor}};border:1px solid black;text-align:center">{{$value2['tonase']}}</td>
                <td style="background-color:{{$bgcolor}};border:1px solid black;text-align:center">{{$value2['bjr']}}</td>
                <td style="background-color:{{$bgcolor}};border:1px solid black;text-align:center">{{$value2['ripeness']}}</td>
                <td style="background-color:{{$bgcolor}};border:1px solid black;text-align:center">{{ round($value2['percentage_ripeness'],2)}}</td>
                <td style="background-color:{{$bgcolor}};border:1px solid black;text-align:center">{{$value2['unripe']}}</td>
                <td style="background-color:{{$bgcolor}};border:1px solid black;text-align:center">{{ round($value2['percentage_unripe'],2)}}</td>
                <td style="background-color:{{$bgcolor}};border:1px solid black;text-align:center">{{$value2['overripe']}}</td>
                <td style="background-color:{{$bgcolor}};border:1px solid black;text-align:center">{{ round($value2['percentage_overripe'],2)}}</td>
                <td style="background-color:{{$bgcolor}};border:1px solid black;text-align:center">{{$value2['empty_bunch']}}</td>
                <td style="background-color:{{$bgcolor}};border:1px solid black;text-align:center">{{ round($value2['percentage_empty_bunch'],2)}}</td>
                <td style="background-color:{{$bgcolor}};border:1px solid black;text-align:center">{{$value2['rotten_bunch']}}</td>
                <td style="background-color:{{$bgcolor}};border:1px solid black;text-align:center">{{ round($value2['percentage_rotten_bunch'],2)}}</td>
                <td style="background-color:{{$bgcolor}};border:1px solid black;text-align:center">{{$value2['abnormal']}}</td>
                <td style="background-color:{{$bgcolor}};border:1px solid black;text-align:center">{{ round($value2['percentage_abnormal'],2)}}</td>
                <td style="background-color:{{$bgcolor}};border:1px solid black;text-align:center">{{$value2['longstalk']}}</td>
                <td style="background-color:{{$bgcolor}};border:1px solid black;text-align:center">{{ round($value2['percentage_longstalk'],2)}}</td>
                <td style="background-color:{{$bgcolor}};border:1px solid black;text-align:center">{{$value2['vcut']}}</td>
                <td style="background-color:{{$bgcolor}};border:1px solid black;text-align:center">{{ round($value2['percentage_vcut'],2)}}</td>
                <td style="background-color:{{$bgcolor}};border:1px solid black;text-align:center">{{$value2['dirt_kg']}}</td>
                <td style="background-color:{{$bgcolor}};border:1px solid black;text-align:center">{{ round($value2['percentage_dirt'],2)}}</td>
                <td style="background-color:{{$bgcolor}};border:1px solid black;text-align:center">{{$value2['loose_fruit_kg']}}</td>
                <td style="background-color:{{$bgcolor}};border:1px solid black;text-align:center">{{ round($value2['percentage_loose_fruit'],2)}}</td>
                <td style="background-color:{{$bgcolor}};border:1px solid black;text-align:center">{{$value2['kelas_c']}}</td>
                <td style="background-color:{{$bgcolor}};border:1px solid black;text-align:center">{{ round($value2['percentage_kelas_c'],2)}}</td>
                <td style="background-color:{{$bgcolor}};border:1px solid black;text-align:center">{{$value2['kelas_b']}}</td>
                <td style="background-color:{{$bgcolor}};border:1px solid black;text-align:center">{{ round($value2['percentage_kelas_b'],2)}}</td>
                <td style="background-color:{{$bgcolor}};border:1px solid black;text-align:center">{{$value2['kelas_a']}}</td>
                <td style="background-color:{{$bgcolor}};border:1px solid black;text-align:center">{{ round($value2['percentage_kelas_a'],2)}}</td>
            </tr>
            @endforeach
            @endforeach
            <tr>
                <td style="background-color:#4FB7C4;text-align: center;border:1px solid black" colspan="2">Total</td>
                <td style="background-color:#4FB7C4;text-align: center;border:1px solid black"> {{$data['final']['unit']}}</td>
                <td style="background-color:#4FB7C4;text-align: center;border:1px solid black"></td>
                <td style="background-color:#4FB7C4;text-align: center;border:1px solid black">{{$data['final']['tonase']}}</td>
                <td style="background-color:#4FB7C4;text-align: center;border:1px solid black">{{$data['final']['jumlah_janjang_spb']}}</td>
                <td style="background-color:#4FB7C4;text-align: center;border:1px solid black">{{$data['final']['jumlah_janjang_grading']}}</td>
                <td style="background-color:#4FB7C4;text-align: center;border:1px solid black">{{$data['final']['tonase']}}</td>
                <td style="background-color:#4FB7C4;text-align: center;border:1px solid black">{{$data['final']['bjr']}}</td>
                <td style="background-color:#4FB7C4;text-align: center;border:1px solid black">{{$data['final']['ripeness']}}</td>
                <td style="background-color:#4FB7C4;text-align: center;border:1px solid black">{{ round($data['final']['percentage_ripeness'],2)}}</td>
                <td style="background-color:#4FB7C4;text-align: center;border:1px solid black">{{$data['final']['unripe']}}</td>
                <td style="background-color:#4FB7C4;text-align: center;border:1px solid black">{{ round($data['final']['percentage_unripe'],2)}}</td>
                <td style="background-color:#4FB7C4;text-align: center;border:1px solid black">{{$data['final']['overripe']}}</td>
                <td style="background-color:#4FB7C4;text-align: center;border:1px solid black">{{ round($data['final']['percentage_overripe'],2)}}</td>
                <td style="background-color:#4FB7C4;text-align: center;border:1px solid black">{{$data['final']['empty_bunch']}}</td>
                <td style="background-color:#4FB7C4;text-align: center;border:1px solid black">{{ round($data['final']['percentage_empty_bunch'],2)}}</td>
                <td style="background-color:#4FB7C4;text-align: center;border:1px solid black">{{$data['final']['rotten_bunch']}}</td>
                <td style="background-color:#4FB7C4;text-align: center;border:1px solid black">{{ round($data['final']['percentage_rotten_bunch'],2)}}</td>
                <td style="background-color:#4FB7C4;text-align: center;border:1px solid black">{{$data['final']['abnormal']}}</td>
                <td style="background-color:#4FB7C4;text-align: center;border:1px solid black">{{ round($data['final']['percentage_abnormal'],2)}}</td>
                <td style="background-color:#4FB7C4;text-align: center;border:1px solid black">{{$data['final']['longstalk']}}</td>
                <td style="background-color:#4FB7C4;text-align: center;border:1px solid black">{{ round($data['final']['percentage_longstalk'],2)}}</td>
                <td style="background-color:#4FB7C4;text-align: center;border:1px solid black">{{$data['final']['vcut']}}</td>
                <td style="background-color:#4FB7C4;text-align: center;border:1px solid black">{{ round($data['final']['percentage_vcut'],2)}}</td>
                <td style="background-color:#4FB7C4;text-align: center;border:1px solid black">{{$data['final']['dirt_kg']}}</td>
                <td style="background-color:#4FB7C4;text-align: center;border:1px solid black">{{ round($data['final']['percentage_dirt'],2)}}</td>
                <td style="background-color:#4FB7C4;text-align: center;border:1px solid black">{{$data['final']['loose_fruit_kg']}}</td>
                <td style="background-color:#4FB7C4;text-align: center;border:1px solid black">{{ round($data['final']['percentage_loose_fruit'],2)}}</td>
                <td style="background-color:#4FB7C4;text-align: center;border:1px solid black">{{$data['final']['kelas_c']}}</td>
                <td style="background-color:#4FB7C4;text-align: center;border:1px solid black">{{ round($data['final']['percentage_kelas_c'],2)}}</td>
                <td style="background-color:#4FB7C4;text-align: center;border:1px solid black">{{$data['final']['kelas_b']}}</td>
                <td style="background-color:#4FB7C4;text-align: center;border:1px solid black">{{ round($data['final']['percentage_kelas_b'],2)}}</td>
                <td style="background-color:#4FB7C4;text-align: center;border:1px solid black">{{$data['final']['kelas_a']}}</td>
                <td style="background-color:#4FB7C4;text-align: center;border:1px solid black">{{ round($data['final']['percentage_kelas_a'],2)}}</td>
            </tr>
        </tbody>

    </table>

</body>

</html>