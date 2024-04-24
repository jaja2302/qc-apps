<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>


<body>
    <table>
        <thead>
            <tr>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;" rowspan="3">Est.</th>
                <th style="text-align: center;vertical-align:center;border: 1px solid black;" rowspan="3">Afd.</th>
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
            @foreach ($data as $item)
            @foreach ($item as $items)
            @foreach ($items as $item1)
            @php

            if($item1['afd'] === 'est'){
            $color = '#76C5E8';
            }else if ($item1['afd'] === 'wil'){
            $color = '#FF7043';
            }
            else{
            $color = '#EBEBEB';
            };


            $allskor = 0;


            if($item1['check_databh'] === 'ada' || $item1['check_datacak'] === 'ada' || $item1['check_datatrans'] === 'ada'){
            $allskor = $item1['skor_akhircak'] + $item1['totalSkortrans'] + $item1['TOTAL_SKORbh'];

            if ($allskor >= 95) {
            $newktg = "EXCELLENT";
            $color2 = '#5074c4';
            } elseif ($allskor >= 85) {
            $newktg = "GOOD";
            $color2 = '#08fc2c';
            } elseif ($allskor >= 75) {
            $newktg = "SATISFACTORY";
            $color2 = '#ffdc04';
            } elseif ($allskor >= 65) {
            $newktg = "FAIR";
            $color2 = '#ffa404';
            } else {
            $newktg = "POOR";
            $color2 = '#ff0404';
            }

            }else{
            $allskor = '-';
            $newktg = "-";
            $color2 = '#E2E2E2';
            }


            @endphp
            <tr>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{$item1['est']}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{$item1['afd']}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['pokok_samplecak'] : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['ha_samplecak']  : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['jumlah_panencak']  : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['akp_rlcak']  : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['pcak']  : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['kcak']  : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['tglcak']  : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['total_brdcak']  : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['brd/jjgcak']  : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['skor_brdcak']  : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['bhts_scak']  : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['bhtm1cak']  : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['bhtm2cak']  : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['bhtm3cak']  : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['total_buahcak']  : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['buah/jjgcak']  : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['skor_bhcak']  : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['palepah_pokokcak']  : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['palepah_percak']  : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['skor_pscak']  : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['skor_akhircak']  : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">
                    {{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'?  $item1['tph_sampleNew'] : '-' }}
                </td>

                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['total_brdtrans'] : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['total_brdperTPHtrans'] : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['skor_brdPertphtrans'] : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['total_buahtrans'] : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['total_buahPerTPHtrans'] : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['skor_buahPerTPHtrans'] : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['totalSkortrans'] : '-'}}</td>

                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['tph_baris_bloksbh'] : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['sampleJJG_totalbh'] : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['total_mentahbh'] : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['total_perMentahbh'] : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['skor_mentahbh'] : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['total_masakbh'] : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['total_perMasakbh'] : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['skor_masakbh'] : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['total_overbh'] : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['total_perOverbh'] : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['skor_overbh'] : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['total_jjgKosongbh'] : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['total_perKosongjjgbh'] : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['skor_jjgKosongbh'] : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['total_vcutbh'] : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['perVcutbh'] : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['skor_vcutbh'] : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['total_abnormalbh'] : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['perAbnormalbh'] : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['jum_krbh'] : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['persen_krbh'] : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['skor_krbh'] : '-'}}</td>
                <td style="vertical-align:center;text-align:center;background-color:{{$color}}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['TOTAL_SKORbh'] : '-'}}</td>

                <td style="vertical-align:center;text-align:center;background-color:{{$color2}}">{{$allskor}}</td>
                <td style="text-align:center;background-color:{{$color2}}">{{$newktg}}</td>
            </tr>

            @endforeach
            @endforeach
            @endforeach
        </tbody>
    </table>

</body>

</html>