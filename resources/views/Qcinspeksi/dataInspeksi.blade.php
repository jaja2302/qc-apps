<body>
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
        {{--
            <td> <a href="dataDetail/{{$key}}/{{$key2}}/{{$tanggal}}/{{$regional}}"> {{$key2}}</a></td>
        --}}
        @if ($item1['afd'] === 'wil')
        <td style="background-color: {{ $color }}">WIL-{{ $item1['est'] }}</td>

        @else
        <td style="background-color: {{ $color }}">{{ $item1['est'] }}</td>
        @endif

        @if ($item1['afd'] != 'est' && $item1['afd'] != 'wil')

        <td> <a href="dataDetail/{{$item1['est']}}/{{$item1['afd']}}/{{$bulan}}/{{$reg}}"> {{$item1['afd']}}</a></td>
        @else
        <td style="background-color: {{ $color }}">{{$item1['afd']}} </td>
        @endif

        <td style="background-color: {{ $color }}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['pokok_samplecak'] : '-'}}</td>
        <td style="background-color: {{ $color }}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['ha_samplecak']  : '-'}}</td>
        <td style="background-color: {{ $color }}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['jumlah_panencak']  : '-'}}</td>
        <td style="background-color: {{ $color }}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['akp_rlcak']  : '-'}}</td>
        <td style="background-color: {{ $color }}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['pcak']  : '-'}}</td>
        <td style="background-color: {{ $color }}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['kcak']  : '-'}}</td>
        <td style="background-color: {{ $color }}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['tglcak']  : '-'}}</td>
        <td style="background-color: {{ $color }}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['total_brdcak']  : '-'}}</td>
        <td style="background-color: {{ $color }}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['brd/jjgcak']  : '-'}}</td>
        <td style="background-color: {{ $color }}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['skor_brdcak']  : '-'}}</td>
        <td style="background-color: {{ $color }}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['bhts_scak']  : '-'}}</td>
        <td style="background-color: {{ $color }}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['bhtm1cak']  : '-'}}</td>
        <td style="background-color: {{ $color }}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['bhtm2cak']  : '-'}}</td>
        <td style="background-color: {{ $color }}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['bhtm3cak']  : '-'}}</td>
        <td style="background-color: {{ $color }}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['total_buahcak']  : '-'}}</td>
        <td style="background-color: {{ $color }}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['buah/jjgcak']  : '-'}}</td>
        <td style="background-color: {{ $color }}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['skor_bhcak']  : '-'}}</td>
        <td style="background-color: {{ $color }}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['palepah_pokokcak']  : '-'}}</td>
        <td style="background-color: {{ $color }}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['palepah_percak']  : '-'}}</td>
        <td style="background-color: {{ $color }}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['skor_pscak']  : '-'}}</td>
        <td style="background-color: {{ $color }}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['skor_akhircak']  : '-'}}</td>
        <td style="background-color: {{ $color }}">
            {{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'?  $item1['tph_sampleNew'] : '-' }}
        </td>
        <td style="background-color: {{ $color }}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['total_brdtrans'] : '-'}}</td>
        <td style="background-color: {{ $color }}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['total_brdperTPHtrans'] : '-'}}</td>
        <td style="background-color: {{ $color }}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['skor_brdPertphtrans'] : '-'}}</td>
        <td style="background-color: {{ $color }}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['total_buahtrans'] : '-'}}</td>
        <td style="background-color: {{ $color }}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['total_buahPerTPHtrans'] : '-'}}</td>
        <td style="background-color: {{ $color }}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['skor_buahPerTPHtrans'] : '-'}}</td>
        <td style="background-color: {{ $color }}">{{ $item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['totalSkortrans'] : '-'}}</td>

        <td style="background-color: {{ $color }}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['tph_baris_bloksbh'] : '-'}}</td>
        <td style="background-color: {{ $color }}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['sampleJJG_totalbh'] : '-'}}</td>
        <td style="background-color: {{ $color }}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['total_mentahbh'] : '-'}}</td>
        <td style="background-color: {{ $color }}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['total_perMentahbh'] : '-'}}</td>
        <td style="background-color: {{ $color }}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['skor_mentahbh'] : '-'}}</td>
        <td style="background-color: {{ $color }}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['total_masakbh'] : '-'}}</td>
        <td style="background-color: {{ $color }}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['total_perMasakbh'] : '-'}}</td>
        <td style="background-color: {{ $color }}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['skor_masakbh'] : '-'}}</td>
        <td style="background-color: {{ $color }}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['total_overbh'] : '-'}}</td>
        <td style="background-color: {{ $color }}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['total_perOverbh'] : '-'}}</td>
        <td style="background-color: {{ $color }}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['skor_overbh'] : '-'}}</td>
        <td style="background-color: {{ $color }}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['total_jjgKosongbh'] : '-'}}</td>
        <td style="background-color: {{ $color }}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['total_perKosongjjgbh'] : '-'}}</td>
        <td style="background-color: {{ $color }}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['skor_jjgKosongbh'] : '-'}}</td>
        <td style="background-color: {{ $color }}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['total_vcutbh'] : '-'}}</td>
        <td style="background-color: {{ $color }}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['perVcutbh'] : '-'}}</td>
        <td style="background-color: {{ $color }}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['skor_vcutbh'] : '-'}}</td>
        <td style="background-color: {{ $color }}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['total_abnormalbh'] : '-'}}</td>
        <td style="background-color: {{ $color }}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['perAbnormalbh'] : '-'}}</td>
        <td style="background-color: {{ $color }}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['jum_krbh'] : '-'}}</td>
        <td style="background-color: {{ $color }}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['persen_krbh'] : '-'}}</td>
        <td style="background-color: {{ $color }}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['skor_krbh'] : '-'}}</td>
        <td style="background-color: {{ $color }}">{{$item1['check_databh'] === 'ada' ||  $item1['check_datacak'] === 'ada'   ||  $item1['check_datatrans'] === 'ada'? $item1['TOTAL_SKORbh'] : '-'}}</td>

        <td style="background-color: {{ $color }}">{{$allskor}}</td>
        <td style="background-color: {{ $color2 }}" data-b-a-s="medium" data-a-h="center">{{$newktg}}</td>
    </tr>

    @endforeach


    @endforeach
    <tr style="border: none;">
        <td colspan="32" style="background-color : #fff;">&nbsp;</td>
    </tr>
    @endforeach

    <tr>
        @php

        if($datareg['afd'] === 'est'){
        $colorreg = '#76C5E8';
        }else if ($datareg['afd'] === 'wil'){
        $colorreg = '#FF7043';
        }
        else{
        $colorreg = '#EBEBEB';
        };


        $allskor = 0;


        if($datareg['check_databh'] === 'ada' || $datareg['check_datacak'] === 'ada' || $datareg['check_datatrans'] === 'ada'){
        $allskorreg = $datareg['skor_akhircak'] + $datareg['totalSkortrans'] + $datareg['TOTAL_SKORbh'];

        if ($allskorreg >= 95) {
        $newktgreg = "EXCELLENT";
        $color2reg = '#5074c4';
        } elseif ($allskorreg >= 85) {
        $newktgreg = "GOOD";
        $color2reg = '#08fc2c';
        } elseif ($allskorreg >= 75) {
        $newktgreg = "SATISFACTORY";
        $color2reg = '#ffdc04';
        } elseif ($allskorreg >= 65) {
        $newktgreg = "FAIR";
        $color2reg = '#ffa404';
        } else {
        $newktgreg = "POOR";
        $color2reg = '#ff0404';
        }

        }else{
        $allskorreg = '-';
        $newktgreg = "-";
        $color2reg = '#E2E2E2';
        }


        @endphp
        <td style="background-color: {{ $colorreg }}">{{ $datareg['est'] }}</td>
        <td style="background-color: {{ $colorreg }}">{{ $datareg['afd'] }}</td>
        <td style="background-color: {{ $colorreg }}">{{ $datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['pokok_samplecak'] : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{ $datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['ha_samplecak']  : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{ $datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['jumlah_panencak']  : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{ $datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['akp_rlcak']  : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{ $datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['pcak']  : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{ $datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['kcak']  : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{ $datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['tglcak']  : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{ $datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['total_brdcak']  : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{ $datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['brd/jjgcak']  : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{ $datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['skor_brdcak']  : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{ $datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['bhts_scak']  : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{ $datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['bhtm1cak']  : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{ $datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['bhtm2cak']  : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{ $datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['bhtm3cak']  : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{ $datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['total_buahcak']  : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{ $datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['buah/jjgcak']  : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{ $datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['skor_bhcak']  : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{ $datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['palepah_pokokcak']  : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{ $datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['palepah_percak']  : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{ $datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['skor_pscak']  : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{ $datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['skor_akhircak']  : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">
            {{$datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'?  $datareg['tph_sampleNew'] : '-' }}
        </td>
        <td style="background-color: {{ $colorreg }}">{{ $datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['total_brdtrans'] : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{ $datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['total_brdperTPHtrans'] : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{ $datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['skor_brdPertphtrans'] : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{ $datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['total_buahtrans'] : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{ $datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['total_buahPerTPHtrans'] : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{ $datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['skor_buahPerTPHtrans'] : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{ $datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['totalSkortrans'] : '-'}}</td>

        <td style="background-color: {{ $colorreg }}">{{$datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['tph_baris_bloksbh'] : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{$datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['sampleJJG_totalbh'] : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{$datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['total_mentahbh'] : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{$datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['total_perMentahbh'] : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{$datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['skor_mentahbh'] : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{$datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['total_masakbh'] : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{$datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['total_perMasakbh'] : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{$datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['skor_masakbh'] : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{$datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['total_overbh'] : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{$datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['total_perOverbh'] : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{$datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['skor_overbh'] : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{$datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['total_jjgKosongbh'] : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{$datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['total_perKosongjjgbh'] : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{$datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['skor_jjgKosongbh'] : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{$datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['total_vcutbh'] : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{$datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['perVcutbh'] : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{$datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['skor_vcutbh'] : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{$datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['total_abnormalbh'] : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{$datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['perAbnormalbh'] : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{$datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['jum_krbh'] : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{$datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['persen_krbh'] : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{$datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['skor_krbh'] : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{$datareg['check_databh'] === 'ada' ||  $datareg['check_datacak'] === 'ada'   ||  $datareg['check_datatrans'] === 'ada'? $datareg['TOTAL_SKORbh'] : '-'}}</td>
        <td style="background-color: {{ $colorreg }}">{{$allskorreg}}</td>
        <td style="background-color: {{ $color2reg }}" data-b-a-s="medium" data-a-h="center">{{$newktgreg}}</td>
    </tr>

</body>