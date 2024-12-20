<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excel Grading</title>
    <style>
        .table {
            table-layout: auto;
            /* Allow the table to adjust based on content */
            width: 100%;
            /* Ensure the table takes up the full available width */
        }

        th,
        td {
            white-space: nowrap;
            /* Prevent wrapping text in the cells */
        }

        th {
            text-align: center;
            /* Center align the headers */
        }

        td {
            padding: 5px;
            /* Add some padding for better readability */
        }

        td:nth-child(3),
        th:nth-child(3) {
            width: auto;
            /* Specifically target the 'NO POLISI' column */
            min-width: 150px;
            /* Set a minimum width for 'NO POLISI' */
        }
    </style>

</head>

<body>
    <table class="table table-responsive table-striped table-bordered">
        <thead>
            <tr>
                <td colspan="35">{{$pt_name}}</td>
            </tr>
            <tr>
                <td colspan="35">{{$mill_name}}</td>
            </tr>
            <tr>
                <td colspan="35">LAPORAN GRADING PKS</td>
            </tr>
            <tr>
                <td colspan="2">TANGGAL</td>
                <td colspan="33">{{$tanggal}}</td>
            </tr>
            <tr>
                <th colspan="39" style="background-color: #c8e4f4;border:1px solid black;text-align:center">BERDASARKAN ESTATE</th>
            </tr>
            <tr>
                <th rowspan="3" class="align-middle" style="background-color: #f0ecec;vertical-align: middle;text-align:center;border:1px solid black">Estate</th>
                <th rowspan="3" class="align-middle" style="background-color: #f0ecec;vertical-align: middle;text-align:center;border:1px solid black">Afdeling</th>
                <th style="border:1px solid black;background-color: #f0ecec;text-align:center" colspan="7">UNIT SORTASI</th>
                <th style="border:1px solid black;background-color: #88e48c;text-align:center" colspan="20">HASIL GRADING</th>
                <th style="border:1px solid black;background-color: #f8c4ac;text-align:center" colspan="6">KELAS JANJANG</th>
                <th style="border:1px solid black;background-color: #B1A1C6;text-align:center" colspan="4">BUAH MENTAH</th>
            </tr>
            <tr>
                <th style="border:1px solid black;background-color: #f0ecec;text-align:center;vertical-align:middle;color:red" rowspan="2">No Polisi</th>
                <th style="border:1px solid black;background-color: #f0ecec;text-align:center;vertical-align:middle;color:red" rowspan="2">
                    Waktu Grading
                </th>

                <th style="border:1px solid black;background-color: #f0ecec;text-align:center;vertical-align:middle;color:red" rowspan="2">TONASE Timbangan(KG)</th>
                <th style="border:1px solid black;background-color: #f0ecec;text-align:center;vertical-align:middle;color:red" rowspan="2">JUMLAH JANJANG SPB</th>
                <th style="border:1px solid black;background-color: #f0ecec;text-align:center;vertical-align:middle;" rowspan="2">JUMLAH JANJANG GRADING</th>
                <th style="border:1px solid black;background-color: #f0ecec;text-align:center;vertical-align:middle;color:red" rowspan="2">TONASE Grading(KG)</th>
                <th style="border:1px solid black;background-color: #f0ecec;text-align:center;vertical-align:middle;" rowspan="2">BJR(KG)</th>
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
                <th style="background-color: #B1A1C6;text-align:center;border:1px solid black" colspan="2">TIDAK BRONDOL</th>
                <th style="background-color: #B1A1C6;text-align:center;border:1px solid black" colspan="2">KURANG BRONDOL</th>
            </tr>
            <tr>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black">JJG</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black;color:red">%</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black">JJG</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black;color:red">%</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black">JJG</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black;color:red">%</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black">JJG</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black;color:red">%</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black">JJG</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black;color:red">%</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black">JJG</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black;color:red">%</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black">JJG</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black;color:red">%</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black">JJG</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black;color:red">%</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black">JJG</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black;color:red">%</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black">JJG</th>
                <th style="background-color: #88e48c;text-align:center;border:1px solid black;color:red">%</th>
                <th style="background-color: #f8c4ac;text-align:center;border:1px solid black">JJG</th>
                <th style="background-color: #f8c4ac;text-align:center;border:1px solid black;color:red">%</th>
                <th style="background-color: #f8c4ac;text-align:center;border:1px solid black">JJG</th>
                <th style="background-color: #f8c4ac;text-align:center;border:1px solid black;color:red">%</th>
                <th style="background-color: #f8c4ac;text-align:center;border:1px solid black">JJG</th>
                <th style="background-color: #f8c4ac;text-align:center;border:1px solid black;color:red">%</th>
                <th style="background-color: #f8c4ac;text-align:center;border:1px solid black">JJG</th>
                <th style="background-color: #f8c4ac;text-align:center;border:1px solid black;color:red">%</th>
                <th style="background-color: #f8c4ac;text-align:center;border:1px solid black">JJG</th>
                <th style="background-color: #f8c4ac;text-align:center;border:1px solid black;color:red">%</th>
            </tr>
        </thead>
        <tbody>
            @if($result == [])
            <tr>
                <td colspan="36" style="color: #88e48c;">Tidak ada data tersedia</td>
            </tr>
            @else
            @foreach ($result as $key => $items)
            @foreach ($items as $key2 => $items2)
            @if ($key2 !== 'Total')
            @foreach ($items2 as $key3 => $items3)
            @if($key3 == 'data')
            @If(is_array($items3))
            @foreach ($items3 as $key4 => $items4)
            <tr>
                <td>{{$key}}</td>
                <td>{{$key2}}</td>
                <td>{{$items4['no_plat']}}</td>
                <td>{{$items4['datetime']}}</td>
                <td>{{$items4['tonase']}}</td>
                <td>{{$items4['jjg_spb']}}</td>
                <td>{{$items4['jjg_grading']}}</td>
                <td>{{$items4['tonase']}}</td>
                <td>{{round($items4['bjr'],2)}}</td>
                <td>{{$items4['ripeness']}}</td>
                <td>{{round($items4['percentage_ripeness'],2)}}</td>
                <td>{{$items4['unripe']}}</td>
                <td>{{round($items4['percentage_unripe'],2)}}</td>
                <td>{{$items4['overripe']}}</td>
                <td>{{round($items4['percentage_overripe'],2)}}</td>
                <td>{{$items4['empty_bunch']}}</td>
                <td>{{round($items4['percentage_empty_bunch'],2)}}</td>
                <td>{{$items4['rotten_bunch']}}</td>
                <td>{{round($items4['percentage_rotten_bunch'],2)}}</td>
                <td>{{$items4['abnormal']}}</td>
                <td>{{round($items4['percentage_abnormal'],2)}}</td>
                <td>{{$items4['longstalk']}}</td>
                <td>{{round($items4['percentage_longstalk'],2)}}</td>
                <td>{{$items4['vcut']}}</td>
                <td>{{round($items4['percentage_vcut'],2)}}</td>
                <td>{{$items4['dirt']}}</td>
                <td>{{round($items4['percentage_dirt'],2)}}</td>
                <td>{{$items4['loose_fruit']}}</td>
                <td>{{round($items4['percentage_loose_fruit'],2)}}</td>
                <td>{{$items4['kelas_c']}}</td>
                <td>{{round($items4['percentage_kelas_c'],2)}}</td>
                <td>{{$items4['kelas_b']}}</td>
                <td>{{round($items4['percentage_kelas_b'],2)}}</td>
                <td>{{$items4['kelas_a']}}</td>
                <td>{{round($items4['percentage_kelas_a'],2)}}</td>
                <td>{{$items4['unripe_tanpa_brondol']}}</td>
                <td>{{round($items4['persentase_unripe_tanpa_brondol'],2)}}</td>
                <td>{{$items4['unripe_kurang_brondol']}}</td>
                <td>{{round($items4['persentase_unripe_kurang_brondol'],2)}}</td>
            </tr>
            @endforeach
            @endif
            @endif
            @endforeach
            @else
            <tr>
                <td style="background-color:#80DEEA">{{$key}}</td>
                <td style="background-color:#80DEEA">{{$key2}}</td>
                <td style="background-color:#80DEEA">{{$items2['no_plat']}}</td>
                <td style="background-color:#80DEEA">{{$items2['unit']}}</td>
                <td style="background-color:#80DEEA">{{$items2['tonase']}}</td>
                <td style="background-color:#80DEEA">{{$items2['jjg_spb']}}</td>
                <td style="background-color:#80DEEA">{{$items2['jjg_grading']}}</td>
                <td style="background-color:#80DEEA">{{$items2['tonase']}}</td>
                <td style="background-color:#80DEEA">{{round($items2['bjr'],2)}}</td>
                <td style="background-color:#80DEEA">{{$items2['ripeness']}}</td>
                <td style="background-color:#80DEEA">{{round($items2['percentage_ripeness'],2)}}</td>
                <td style="background-color:#80DEEA">{{$items2['unripe']}}</td>
                <td style="background-color:#80DEEA">{{round($items2['percentage_unripe'],2)}}</td>
                <td style="background-color:#80DEEA">{{$items2['overripe']}}</td>
                <td style="background-color:#80DEEA">{{round($items2['percentage_overripe'],2)}}</td>
                <td style="background-color:#80DEEA">{{$items2['empty_bunch']}}</td>
                <td style="background-color:#80DEEA">{{round($items2['percentage_empty_bunch'],2)}}</td>
                <td style="background-color:#80DEEA">{{$items2['rotten_bunch']}}</td>
                <td style="background-color:#80DEEA">{{round($items2['percentage_rotten_bunch'],2)}}</td>
                <td style="background-color:#80DEEA">{{$items2['abnormal']}}</td>
                <td style="background-color:#80DEEA">{{round($items2['percentage_abnormal'],2)}}</td>
                <td style="background-color:#80DEEA">{{$items2['longstalk']}}</td>
                <td style="background-color:#80DEEA">{{round($items2['percentage_longstalk'],2)}}</td>
                <td style="background-color:#80DEEA">{{$items2['vcut']}}</td>
                <td style="background-color:#80DEEA">{{round($items2['percentage_vcut'],2)}}</td>
                <td style="background-color:#80DEEA">{{$items2['dirt']}}</td>
                <td style="background-color:#80DEEA">{{round($items2['percentage_dirt'],2)}}</td>
                <td style="background-color:#80DEEA">{{$items2['loose_fruit']}}</td>
                <td style="background-color:#80DEEA">{{round($items2['percentage_loose_fruit'],2)}}</td>
                <td style="background-color:#80DEEA">{{$items2['kelas_c']}}</td>
                <td style="background-color:#80DEEA">{{round($items2['percentage_kelas_c'],2)}}</td>
                <td style="background-color:#80DEEA">{{$items2['kelas_b']}}</td>
                <td style="background-color:#80DEEA">{{round($items2['percentage_kelas_b'],2)}}</td>
                <td style="background-color:#80DEEA">{{$items2['kelas_a']}}</td>
                <td style="background-color:#80DEEA">{{round($items2['percentage_kelas_a'],2)}}</td>
                <td style="background-color:#80DEEA">{{$items2['unripe_tanpa_brondol']}}</td>
                <td style="background-color:#80DEEA">{{round($items2['persentase_unripe_tanpa_brondol'],2)}}</td>
                <td style="background-color:#80DEEA">{{$items2['unripe_kurang_brondol']}}</td>
                <td style="background-color:#80DEEA">{{round($items2['persentase_unripe_kurang_brondol'],2)}}</td>
            </tr>
            @endif
            @endforeach
            @endforeach

            <tr>
                <td style="background-color: #FFE0B2" colspan="4">Total Estate</td>
                <td style="background-color: #FFE0B2">{{$final['tonase']}}</td>
                <td style="background-color: #FFE0B2">{{$final['jjg_spb']}}</td>
                <td style="background-color: #FFE0B2">{{$final['jjg_grading']}}</td>
                <td style="background-color: #FFE0B2">{{$final['tonase']}}</td>
                <td style="background-color: #FFE0B2">{{round($final['bjr'],2)}}</td>
                <td style="background-color: #FFE0B2">{{$final['ripeness']}}</td>
                <td style="background-color: #FFE0B2">{{round($final['percentage_ripeness'],2)}}</td>
                <td style="background-color: #FFE0B2">{{$final['unripe']}}</td>
                <td style="background-color: #FFE0B2">{{round($final['percentage_unripe'],2)}}</td>
                <td style="background-color: #FFE0B2">{{$final['overripe']}}</td>
                <td style="background-color: #FFE0B2">{{round($final['percentage_overripe'],2)}}</td>
                <td style="background-color: #FFE0B2">{{$final['empty_bunch']}}</td>
                <td style="background-color: #FFE0B2">{{round($final['percentage_empty_bunch'],2)}}</td>
                <td style="background-color: #FFE0B2">{{$final['rotten_bunch']}}</td>
                <td style="background-color: #FFE0B2">{{round($final['percentage_rotten_bunch'],2)}}</td>
                <td style="background-color: #FFE0B2">{{$final['abnormal']}}</td>
                <td style="background-color: #FFE0B2">{{round($final['percentage_abnormal'],2)}}</td>
                <td style="background-color: #FFE0B2">{{$final['longstalk']}}</td>
                <td style="background-color: #FFE0B2">{{round($final['percentage_longstalk'],2)}}</td>
                <td style="background-color: #FFE0B2">{{$final['vcut']}}</td>
                <td style="background-color: #FFE0B2">{{round($final['percentage_vcut'],2)}}</td>
                <td style="background-color: #FFE0B2">{{$final['dirt']}}</td>
                <td style="background-color: #FFE0B2">{{round($final['percentage_dirt'],2)}}</td>
                <td style="background-color: #FFE0B2">{{$final['loose_fruit']}}</td>
                <td style="background-color: #FFE0B2">{{round($final['percentage_loose_fruit'],2)}}</td>
                <td style="background-color: #FFE0B2">{{$final['kelas_c']}}</td>
                <td style="background-color: #FFE0B2">{{round($final['percentage_kelas_c'],2)}}</td>
                <td style="background-color: #FFE0B2">{{$final['kelas_b']}}</td>
                <td style="background-color: #FFE0B2">{{round($final['percentage_kelas_b'],2)}}</td>
                <td style="background-color: #FFE0B2">{{$final['kelas_a']}}</td>
                <td style="background-color: #FFE0B2">{{round($final['percentage_kelas_a'],2)}}</td>
                <td style="background-color: #FFE0B2">{{$final['unripe_tanpa_brondol']}}</td>
                <td style="background-color: #FFE0B2">{{round($final['persentase_unripe_tanpa_brondol'],2)}}</td>
                <td style="background-color: #FFE0B2">{{$final['unripe_kurang_brondol']}}</td>
                <td style="background-color: #FFE0B2">{{round($final['persentase_unripe_kurang_brondol'],2)}}</td>
            </tr>

            @endif
        </tbody>

    </table>

</body>

</html>