<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .stamp {
                            display: flex;
                            flex-direction: column;
                            justify-content: center;
                            align-items: center;
                            width: 50%;
                            height: 100px;
                            margin-left: 10%;
                            /* margin-top: 25%; */
                            border: 2px dashed gray; /* Change border style here */
                            border-radius: 20px; /* Adjust border-radius for slightly rounded corners */
                            transform: rotate(-10deg);
                            overflow: hidden; /* Ensure stamp-text stays inside the border */
                        }
                        
                        .stamp-logo {
                            width: 35px; /* Adjust logo size */
                            height: 35px;
                            margin-top: 4px;
                            object-fit: contain;
                            z-index: 1;
                        }
                        
                        .stamp-text {
                            text-align: center;
                            font-size: 16px;
                            font-weight: bold;
                            margin: 0;
                            z-index: 2; /* Ensure stamp-text appears above the logo */
                        }
                        
                        /* Details styles */
                        .details {
                            background-color: #f0f0f0;
                            border-radius: 5px;
                            font-size: 14px;
                        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table td {
        border: 1px solid black;
        text-align: center;
         }
         table th {
        border: 1px solid black;
        text-align: center;
         }
      
         .custom-tables-container {
        display: flex;
        justify-content: space-between;
    }

    .custom-table {
        border-collapse: collapse;
        width: 45%;
    }

    .custom-table,
    .custom-table th,
    .custom-table td {
        border: 1px solid black;
        text-align: left;
        padding: 8px;
    }



    .table-1-no-border td {
        border: none;
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

    <br>
  

   @if ($data['sidak_buah']['statusarr'] !== 'multiarray')
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
    <tbody id="tab2" style="font-size: 17px;">
                @foreach ($data['sidak_buah']['table1'] as $items)
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

     @else
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
    <tbody id="tab2" style="font-size: 17px;">
        @foreach ($data['sidak_buah']['table1'] as $items)
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
        <div style="page-break-before: always;"></div>
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
            <tbody id="tab2" style="font-size: 17px;">
                @foreach ($data['sidak_buah']['table2'] as $items)
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
   @endif

  
   <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3  border border-dark" style="padding: 10px;">

    <table class="custom-table table-1-no-border" style="float: left; width: 30%;">
        <thead>
            <tr>
                <th colspan="12" class="text-center">Catatan Lainnya(%)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="12">-</td>
            </tr>
            <tr>
                <td colspan="12">-</td>
            </tr>
            <tr>
                <td colspan="12">-</td>
            </tr>
            <tr>
                <td colspan="12">-</td>
            </tr>
            <tr>
                <td colspan="12">-</td>
            </tr>
            <tr>
                <td colspan="12">-</td>
            </tr>
            <tr>
                <td colspan="12">-</td>
            </tr>
      
        </tbody>
    </table>
    <!-- Table 2 -->
    <table class="custom-table" style="float: right; width: 70%; border-collapse: collapse;" border="1">
        <thead>
            <tr>
                <th colspan="12" class="text-center">Demikian hasil pemeriksaan ini dibuat dengan sebenar-benarnya tanpa rekayasa dan paksaan dari Siapapun,</th>
            </tr>
            <tr>
                <th colspan="8" class="text-center">Dibuat</th>
                <th colspan="2" class="text-center">Diterima</th>
                <th colspan="2" class="text-center">Diketahui</th>
            </tr>
        </thead>
        <tbody>
            <tr>
               
                @foreach ($data['finalpetugas'] as $item)
                  <td colspan="{{$item['row']}}" style="vertical-align: bottom;text-align:center;padding-top:30px;">
                    <div class="stamp-container">
                        <div class="stamp">
                            <img src="{{ asset('img/CBIpreview.png') }}" alt="Logo" class="stamp-logo">
                            <div class="stamp-text">Created</div>
                            <div class="stamp-text">{{$data['tanggal']}}</div>
                        </div>
                        <div class="details">
                            <div> {{$item['nama']}}</div>
                            <div>Petugas Quality Control </div>
                        </div>
                    </div>
                </td>  
                @endforeach
              
                
                @if ($data['statusdata']['status'] === 'not_approved')
                <td colspan="2" style="vertical-align: bottom; text-align: center">Asisten  Tidak Terverifikasi Secara Digital</td>
                <td colspan="2" style="vertical-align: bottom; text-align: center">Manajer/Askep  Tidak Terverifikasi Secara Digital</td>
                @else
                <td colspan="2" style="vertical-align: bottom; text-align: center">
                    @if ($data['statusdata']['nama_asisten'] != null)
                        <div class="stamp-container">
                            <div class="stamp">
                                <img src="{{ asset('img/CBIpreview.png') }}" alt="Logo" class="stamp-logo">
                                <div class="stamp-text">APPROVED</div>
                                <div class="stamp-text">{{$data['statusdata']['approve_asisten']}}</div>
                            </div>
                            <div class="details">
                                <div>{{$data['statusdata']['nama_asisten']}}</div>
                                <div>Asisten {{$data['statusdata']['detail_asisten']}} <span>{{$data['statusdata']['lok_asisten']}}</span> </div>
                            </div>
                        </div> 
                    @else
                        Asisten Tidak Terverifikasi Secara Digital
                    @endif
                </td>
                <td colspan="2" style="vertical-align: bottom; text-align: center">
                    @if ($data['statusdata']['nama_askep'] != null)
                        <div class="stamp-container">
                            <div class="stamp">
                                <img src="{{ asset('img/CBIpreview.png') }}" alt="Logo" class="stamp-logo">
                                <div class="stamp-text">APPROVED</div>
                                <div class="stamp-text">{{$data['statusdata']['approve_askep']}}</div>
                            </div>
                            <div class="details">
                                <div>{{$data['statusdata']['nama_askep']}}</div>
                                <div>Askep {{$data['statusdata']['detail_askep']}} <span>{{$data['statusdata']['lok_askep']}}</span> </div>
                            </div>
                        </div>
                    @elseif ($data['statusdata']['nama_maneger'] != null)
                    <div class="stamp-container">
                        <div class="stamp">
                            <img src="{{ asset('img/CBIpreview.png') }}" alt="Logo" class="stamp-logo">
                            <div class="stamp-text">APPROVED</div>
                            <div class="stamp-text">{{$data['statusdata']['approve_maneger']}}</div>
                        </div>
                        <div class="details">
                            <div>{{$data['statusdata']['nama_maneger']}}</div>
                            <div>Manager {{$data['statusdata']['detail_manager']}} <span>{{$data['statusdata']['lok_manager']}}</span> </div>
                        </div>
                    </div>
                    @else
                        Manager/Askep Tidak Terverifikasi Secara Digital
                    @endif
                    
                </td>
                @endif   
            </tr>
        </tbody>
    </table>
    <div style="clear:both;"></div>
</div>

</body>

</html>