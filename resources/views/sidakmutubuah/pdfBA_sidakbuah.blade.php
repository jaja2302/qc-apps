<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        .left-table {
            width: 80%;
            float: left;
        }

        .right-table {
            width: 20%;
            float: left;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        .table-container {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>
    <div style="border: 1px solid black;text-align:center">
        <h2 class="text-center">REKAPITULASI SIDAK PEMERIKSAAN MUTU BUAH
        </h2>
    </div>

    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="vertical-align: middle; padding-left: 0; width: 10%;border:0;">
                <div>
                    <img src="{{ asset('img/Logo-SSS.png') }}" style="height:60px">
                </div>
            </td>
            <td style="width:30%;border:0;">

                <p style="text-align: left;">PT. SAWIT SUMBERMAS SARANA,TBK</p>
                <p style="text-align: left;">QUALITY CONTROL</p>

            </td>
            <td style=" width: 20%;border:0;">
            </td>
            <td style="vertical-align: middle; text-align: right;width:40%;border:0;">
                <div class="right-container">
                    <div class="text-container">

                        <div class="afd">ESTATE : {{$data['est']}} </div>
                        <div class="afd">TANGGAL: {{$data['tanggal']}}</div>
                    </div>
                </div>
            </td>
        </tr>
    </table>


    <div class="left-table">
        <table>
            <thead>
                <tr>
                    <th style="text-align: center;" colspan="23"> SIDAK MUTU BUAH</th>

                </tr>
                <tr>
                    <th rowspan="3">Afdeling</th>
                    <th rowspan="3">Blok</th>
                    <th rowspan="3">Total
                        Janjang
                        Sampel</th>
                    <th colspan="6" style="text-align: center;">Mentah (A)</th>
                    <th colspan="2" rowspan="2">Matang (N)</th>
                    <th colspan="2" rowspan="2">Lewat Matang
                        (O)</th>
                    <th colspan="2" rowspan="2">Janjang Kosong
                        (E)</th>
                    <th colspan="2" rowspan="2">Abnormal</th>
                    <th colspan="2" rowspan="2">Tidak Standar V-Cut</th>
                    <th colspan="2" rowspan="2">Rat Damage</th>
                    <th colspan="2" rowspan="2">Alas Brondol</th>

                </tr>
                <tr>
                    <th colspan="2">0 Brondol</th>
                    <th colspan="2">Kurang Brondol</th>
                    <th colspan="2">Total</th>

                </tr>
                <tr>
                    <th>JJG</th>
                    <th>%</th>
                    <th>JJG</th>
                    <th>%</th>
                    <th>JJG</th>
                    <th>%</th>
                    <th>JJG</th>
                    <th>%</th>
                    <th>JJG</th>
                    <th>%</th>
                    <th>JJG</th>
                    <th>%</th>
                    <th>JJG</th>
                    <th>%</th>
                    <th>JJG</th>
                    <th>%</th>
                    <th>JJG</th>
                    <th>%</th>
                    <th>TPH</th>
                    <th>%</th>

                </tr>
            </thead>
            <tbody id="tab2" style="font-size: 13px;">
                @foreach ($data['sidak_buah'] as $items)
                @foreach ($items as $item)
                <tr style="text-align: center;">
                    @if ($item['afd'] === 'TOTAL')

                    <td style="background-color: #80A29E;">{{$item['estate']}}</td>
                    <td style="background-color: #80A29E;">{{$item['est']}}</td>
                    <td style="background-color: #80A29E;">{{$item['Jumlah_janjang']}}</td>
                    <td style="background-color: #80A29E;">{{$item['tnp_brd']}}</td>
                    <td style="background-color: #80A29E;">{{round($item['persenTNP_brd'],2)}}</td>
                    <td style="background-color: #80A29E;">{{$item['krg_brd']}}</td>
                    <td style="background-color: #80A29E;">{{round($item['persenKRG_brd'],2)}}</td>
                    <td style="background-color: #80A29E;">{{$item['total_jjg']}}</td>
                    <td style="background-color: #80A29E;">{{round($item['persen_totalJjg'],2)}}</td>
                    <td style="background-color: #80A29E;">{{$item['jjg_matang']}}</td>
                    <td style="background-color: #80A29E;">{{round($item['persen_jjgMtang'],2)}}</td>
                    <td style="background-color: #80A29E;">{{$item['lewat_matang']}}</td>
                    <td style="background-color: #80A29E;">{{round($item['persen_lwtMtng'],2)}}</td>
                    <td style="background-color: #80A29E;">{{$item['janjang_kosong']}}</td>
                    <td style="background-color: #80A29E;">{{round($item['persen_kosong'],2)}}</td>
                    <td style="background-color: #80A29E;">{{$item['abnormal']}}</td>
                    <td style="background-color: #80A29E;">{{round($item['abnormal_persen'],2)}}</td>
                    <td style="background-color: #80A29E;">{{$item['vcut']}}</td>
                    <td style="background-color: #80A29E;">{{round($item['vcut_persen'],2)}}</td>
                    <td style="background-color: #80A29E;">{{$item['rat_dmg']}}</td>
                    <td style="background-color: #80A29E;">{{$item['rd_persen']}}</td>
                    <td style="background-color: #80A29E;">{{$item['jumkarung'] ?? $item['karung']}}/{{$item['blok']}}</td>
                    <td style="background-color: #80A29E;">{{$item['persen_krg']}}</td>
                    @else
                    <td>{{$item['estate']}}</td>
                    <td>{{$item['est']}}</td>
                    <td>{{$item['Jumlah_janjang']}}</td>
                    <td>{{$item['tnp_brd']}}</td>
                    <td>{{round($item['persenTNP_brd'],2)}}</td>
                    <td>{{$item['krg_brd']}}</td>
                    <td>{{round($item['persenKRG_brd'],2)}}</td>
                    <td>{{$item['total_jjg']}}</td>
                    <td>{{round($item['persen_totalJjg'],2)}}</td>
                    <td>{{$item['jjg_matang']}}</td>
                    <td>{{round($item['persen_jjgMtang'],2)}}</td>
                    <td>{{$item['lewat_matang']}}</td>
                    <td>{{round($item['persen_lwtMtng'],2)}}</td>
                    <td>{{$item['janjang_kosong']}}</td>
                    <td>{{round($item['persen_kosong'],2)}}</td>
                    <td>{{$item['abnormal']}}</td>
                    <td>{{round($item['abnormal_persen'],2)}}</td>
                    <td>{{$item['vcut']}}</td>
                    <td>{{round($item['vcut_persen'],2)}}</td>
                    <td>{{$item['rat_dmg']}}</td>
                    <td>{{$item['rd_persen']}}</td>
                    <td>{{$item['jumkarung'] ?? $item['karung']}}/{{$item['blok']}}</td>
                    <td>{{$item['persen_krg']}}</td>
                    @endif



                </tr>

                @endforeach
                @endforeach

                @php

                $estdata = $data['estdata'];
                @endphp
                <tr>
                    <td colspan="2" style="text-align:center;background-color: #FFE082;">{{$estdata['estate']}}</td>
                    <td style="background-color: #FFE082;">{{$estdata['Jumlah_janjang']}}</td>
                    <td style="background-color: #FFE082;">{{$estdata['tnp_brd']}}</td>
                    <td style="background-color: #FFE082;">{{round($estdata['persenTNP_brd'],2)}}</td>
                    <td style="background-color: #FFE082;">{{$estdata['krg_brd']}}</td>
                    <td style="background-color: #FFE082;">{{round($estdata['persenKRG_brd'],2)}}</td>
                    <td style="background-color: #FFE082;">{{$estdata['total_jjg']}}</td>
                    <td style="background-color: #FFE082;">{{round($estdata['persen_totalJjg'],2)}}</td>
                    <td style="background-color: #FFE082;">{{$estdata['jjg_matang']}}</td>
                    <td style="background-color: #FFE082;">{{round($estdata['persen_jjgMtang'],2)}}</td>
                    <td style="background-color: #FFE082;">{{$estdata['lewat_matang']}}</td>
                    <td style="background-color: #FFE082;">{{round($estdata['persen_lwtMtng'],2)}}</td>
                    <td style="background-color: #FFE082;">{{$estdata['janjang_kosong']}}</td>
                    <td style="background-color: #FFE082;">{{round($estdata['persen_kosong'],2)}}</td>
                    <td style="background-color: #FFE082;">{{$estdata['abnormal']}}</td>
                    <td style="background-color: #FFE082;">{{round($estdata['abnormal_persen'],2)}}</td>
                    <td style="background-color: #FFE082;">{{$estdata['vcut']}}</td>
                    <td style="background-color: #FFE082;">{{round($estdata['vcut_persen'],2)}}</td>
                    <td style="background-color: #FFE082;">{{$estdata['rat_dmg']}}</td>
                    <td style="background-color: #FFE082;">{{$estdata['rd_persen']}}</td>
                    <td style="background-color: #FFE082;">{{$estdata['jumkarung'] ?? $estdata['karung']}}/{{$estdata['blok']}}</td>
                    <td style="background-color: #FFE082;">{{$estdata['persen_krg']}}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="right-table">
        <table>
            <thead>
                <tr>
                    <th style="text-align: center;">KETERANGAN</th>
                </tr>


            </thead>
            <tbody>
                <tr>
                    <td rowspan="{{$data['rowspan']['a']}}"></td>
                </tr>
            </tbody>
        </table>
        <table>
            <thead>

                <tr>
                    <th style="text-align: center;">DiBuat Oleh</th>
                </tr>


            </thead>
            <tbody>
                <tr>
                    <td rowspan="{{$data['rowspan']['b'] +4}}"></td>
                </tr>
            </tbody>
        </table>
        <table>
            <thead>
                <tr>
                    <th style="text-align: center; border-bottom: 1px solid black;">Diterima Oleh</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border-bottom: 1px solid red;" rowspan="{{$data['rowspan']['c'] + 3 }}"></td>
                </tr>
                <!-- Add more rows as needed -->
            </tbody>
        </table>
        <table>
            <th style="border-top: 1px solid white;"></th>
        </table>
    </div>

</body>

</html>