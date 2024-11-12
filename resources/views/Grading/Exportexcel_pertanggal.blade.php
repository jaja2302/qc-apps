<table class="table table-responsive table-striped table-bordered">
    <thead>
        <tr>
            <th style="background-color: #f0ecec;" colspan="12">UNIT SORTASI</th>
            <th style="background-color: #88e48c;" colspan="20">HASIL GRADING</th>
            <th style="background-color: #f8c4ac;" colspan="6">KELAS JANJANG</th>
        </tr>
        <tr>
            <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">Tanggal</th>
            <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">Estate</th>
            <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">Afdeling</th>
            <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">Mill</th>
            <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">Jam Grading</th>
            <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">Nomor Plat</th>
            <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">Driver</th>
            <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">Blok</th>
            <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">JUMLAH JANJANG SPB</th>
            <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">JUMLAH JANJANG GRADING</th>
            <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">TONASE (KG)</th>
            <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">BJR (KG)</th>
            <th style="background-color: #88e48c;" colspan="2">RIPENESS</th>
            <th style="background-color: #88e48c;" colspan="2">UNRIPE</th>
            <th style="background-color: #88e48c;" colspan="2">OVERRIPE</th>
            <th style="background-color: #88e48c;" colspan="2">EMPTY BUNCH</th>
            <th style="background-color: #88e48c;" colspan="2">ROTTEN BUNCH</th>
            <th style="background-color: #88e48c;" colspan="2">ABNORMAL</th>
            <th style="background-color: #88e48c;" colspan="2">LONG STALK</th>
            <th style="background-color: #88e48c;" colspan="2">V-CUT</th>
            <th style="background-color: #88e48c;" colspan="2">DIRT</th>
            <th style="background-color: #88e48c;" colspan="2">LOOSE FRUIT</th>
            <th style="background-color: #f8c4ac;" colspan="2">KELAS C</th>
            <th style="background-color: #f8c4ac;" colspan="2">KELAS B</th>
            <th style="background-color: #f8c4ac;" colspan="2">KELAS A</th>
        </tr>
        <tr>
            <th style="background-color: #88e48c;">JJG</th>
            <th style="background-color: #88e48c;">%</th>
            <th style="background-color: #88e48c;">JJG</th>
            <th style="background-color: #88e48c;">%</th>
            <th style="background-color: #88e48c;">JJG</th>
            <th style="background-color: #88e48c;">%</th>
            <th style="background-color: #88e48c;">JJG</th>
            <th style="background-color: #88e48c;">%</th>
            <th style="background-color: #88e48c;">JJG</th>
            <th style="background-color: #88e48c;">%</th>
            <th style="background-color: #88e48c;">JJG</th>
            <th style="background-color: #88e48c;">%</th>
            <th style="background-color: #88e48c;">JJG</th>
            <th style="background-color: #88e48c;">%</th>
            <th style="background-color: #88e48c;">JJG</th>
            <th style="background-color: #88e48c;">%</th>
            <th style="background-color: #88e48c;">JJG</th>
            <th style="background-color: #88e48c;">%</th>
            <th style="background-color: #88e48c;">JJG</th>
            <th style="background-color: #88e48c;">%</th>
            <th style="background-color: #f8c4ac;">JJG</th>
            <th style="background-color: #f8c4ac;">%</th>
            <th style="background-color: #f8c4ac;">JJG</th>
            <th style="background-color: #f8c4ac;">%</th>
            <th style="background-color: #f8c4ac;">JJG</th>
            <th style="background-color: #f8c4ac;">%</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $key => $value)
        @php
        $color = 'white';
        if ($value['type'] == 'afdeling_total') {
        $color = '#fff3cd'; // table-warning color
        } elseif ($value['type'] == 'estate_total') {
        $color = '#0d6efd'; // table-primary color
        } elseif ($value['type'] == 'date_total') {
        $color = '#dc3545'; // table-danger color
        }
        @endphp
        <tr>
            <td style="background-color: {{$color}}">{{$value['date']}}</td>
            <td style="background-color: {{$color}}">{{$value['estate']}}</td>
            <td style="background-color: {{$color}}">{{$value['afdeling']}}</td>
            <td style="background-color: {{$color}}">{{$value['mill']}}</td>
            <td style="background-color: {{$color}}">{{$value['datetime']}}</td>
            <td style="background-color: {{$color}}">{{$value['no_plat']}}</td>
            <td style="background-color: {{$color}}">{{$value['driver']}}</td>
            <td style="background-color: {{$color}}">{{$value['blok']}}</td>
            <td style="background-color: {{$color}}">{{$value['jjg_spb']}}</td>
            <td style="background-color: {{$color}}">{{$value['jjg_grading']}}</td>
            <td style="background-color: {{$color}}">{{$value['tonase']}}</td>
            <td style="background-color: {{$color}}">{{$value['bjr']}}</td>
            <td style="background-color: {{$color}}">{{$value['ripeness']}}</td>
            <td style="background-color: {{$color}}">{{$value['percentage_ripeness']}}</td>
            <td style="background-color: {{$color}}">{{$value['unripe']}}</td>
            <td style="background-color: {{$color}}">{{$value['percentage_unripe']}}</td>
            <td style="background-color: {{$color}}">{{$value['overripe']}}</td>
            <td style="background-color: {{$color}}">{{$value['percentage_overripe']}}</td>
            <td style="background-color: {{$color}}">{{$value['empty']}}</td>
            <td style="background-color: {{$color}}">{{$value['percentage_empty_bunch']}}</td>
            <td style="background-color: {{$color}}">{{$value['rotten']}}</td>
            <td style="background-color: {{$color}}">{{$value['percentage_rotten_bunch']}}</td>
            <td style="background-color: {{$color}}">{{$value['abnormal']}}</td>
            <td style="background-color: {{$color}}">{{$value['percentage_abnormal']}}</td>
            <td style="background-color: {{$color}}">{{$value['tangkai_panjang']}}</td>
            <td style="background-color: {{$color}}">{{$value['percentage_tangkai_panjang']}}</td>
            <td style="background-color: {{$color}}">{{$value['vcuts']}}</td>
            <td style="background-color: {{$color}}">{{$value['percentage_vcut']}}</td>
            <td style="background-color: {{$color}}">{{$value['dirt']}}</td>
            <td style="background-color: {{$color}}">{{$value['percentage_dirt_kg']}}</td>
            <td style="background-color: {{$color}}">{{$value['loose_fruit']}}</td>
            <td style="background-color: {{$color}}">{{$value['percentage_loose_fruit_kg']}}</td>
            <td style="background-color: {{$color}}">{{$value['kelas_c']}}</td>
            <td style="background-color: {{$color}}">{{$value['percentage_kelas_c']}}</td>
            <td style="background-color: {{$color}}">{{$value['kelas_b']}}</td>
            <td style="background-color: {{$color}}">{{$value['percentage_kelas_b']}}</td>
            <td style="background-color: {{$color}}">{{$value['kelas_a']}}</td>
            <td style="background-color: {{$color}}">{{$value['percentage_kelas_a']}}</td>
        </tr>
        @endforeach
    </tbody>
</table>