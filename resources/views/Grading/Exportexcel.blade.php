<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excel Grading</title>
</head>

<body>
    @if($type == 'rekap_regional')
    <table class="table table-responsive table-striped table-bordered">
        <thead>
            <tr>
                <th colspan="29" style="border: 1px solid black;background-color: #c8e4f4;text-align:center">BERDASARKAN ESTATE</th>
            </tr>
            <tr>
                <th rowspan="3" class="align-middle" style="border: 1px solid black;background-color: #f0ecec;vertical-align:center">Estate</th>
                <th style="border: 1px solid black;background-color: #f0ecec;text-align:center" colspan="2">UNIT SORTASI</th>
                <th style="border: 1px solid black;background-color: #88e48c;text-align:center" colspan="20">HASIL GRADING</th>
                <th style="border: 1px solid black;background-color: #f8c4ac;text-align:center" colspan="6">KELAS JANJANG</th>
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
            </tr>
        </thead>
        <tbody id="regional_estate">
            @foreach($data as $key => $value)
            @foreach($value as $key2 => $value2)
            <tr>
                <td style="border: 1px solid black;">{{$key}}</td>
                <td style="border: 1px solid black;">{{$value2['jumlah_janjang_grading']}}</td>
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
                <td style="border: 1px solid black;">{{$value2['dirt_kg']}}</td>
                <td style="border: 1px solid black;">{{round($value2['percentage_dirt'],2)}}</td>
                <td style="border: 1px solid black;">{{$value2['loose_fruit_kg']}}</td>
                <td style="border: 1px solid black;">{{round($value2['percentage_loose_fruit'],2)}}</td>
                <td style="border: 1px solid black;">{{$value2['kelas_c']}}</td>
                <td style="border: 1px solid black;">{{round($value2['percentage_kelas_c'],2)}}</td>
                <td style="border: 1px solid black;">{{$value2['kelas_b']}}</td>
                <td style="border: 1px solid black;">{{round($value2['percentage_kelas_b'],2)}}</td>
                <td style="border: 1px solid black;">{{$value2['kelas_a']}}</td>
                <td style="border: 1px solid black;">{{round($value2['percentage_kelas_a'],2)}}</td>
            </tr>
            @endforeach
            @endforeach
        </tbody>
    </table>

    {{--
    @php
    dd('yas');
    @endphp
--}}

    @else
    <h1>haha</h1>
    @endif
</body>

</html>