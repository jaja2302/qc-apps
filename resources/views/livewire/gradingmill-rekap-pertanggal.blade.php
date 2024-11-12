<div>
    <div class="d-flex justify-content-end mr-3 mt-4">
        <div class="margin g-2">
            <div class="row align-items-center">
                <div class="col-md">
                    {{csrf_field()}}
                    <input class="form-control" type="month" wire:model="inputbulan">
                </div>
                <div class="col-md">
                    <select class="form-select mb-2 mb-md-0" wire:model="inputregional" id="rekap_perhari_reg">
                        <option value="">Select Regional</option>
                        @foreach ($regional_id as $regional)
                        <option value="{{ $regional['id'] }}">{{ $regional['nama'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-primary ml-2" wire:click="showResults">Show</button>
                </div>
                <div class="col-auto">
                    <form wire:submit.prevent="exportData">
                        <button type="submit" class="btn btn-primary">Export</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-4 mx-3 mb-10">
        <!-- Updated Legend Styling -->
        <div class="legend mb-4 p-3 bg-light rounded shadow-sm">
            <h5 class="mb-3 font-weight-bold">Legend:</h5>
            <div class="d-flex gap-4 flex-wrap">
                <div class="d-flex align-items-center">
                    <span class="badge rounded-pill px-3 py-2 me-2" style="background-color: #ffeb3b">Data Entry</span>
                </div>
                <div class="d-flex align-items-center">
                    <span class="badge rounded-pill px-3 py-2 me-2" style="background-color: #2196f3; color: white">Estate Summary</span>
                </div>
                <div class="d-flex align-items-center">
                    <span class="badge rounded-pill px-3 py-2 me-2" style="background-color: #f44336; color: white">Daily Total</span>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle text-nowrap">
                <thead>
                    <tr>
                        <th style="background-color: #f0ecec;" class="align-middle" rowspan="3">Tanggal</th>
                        <th style="background-color: #f0ecec;" class="align-middle" rowspan="3">Estate</th>
                        <th style="background-color: #f0ecec;" class="align-middle" rowspan="3">Afdeling</th>
                        <th style="background-color: #f0ecec;" class="align-middle" rowspan="3">Mill</th>
                        <th style="background-color: #f0ecec;" class="align-middle" rowspan="3">Jam Grading</th>
                        <th style="background-color: #f0ecec;" class="align-middle" rowspan="3">Nomor Plat</th>
                        <th style="background-color: #f0ecec;" class="align-middle" rowspan="3">Driver</th>
                        <th style="background-color: #f0ecec;" class="align-middle" rowspan="3">Blok</th>
                        <th style="background-color: #f0ecec;" colspan="4">UNIT SORTASI</th>
                        <th style="background-color: #88e48c;" colspan="20">HASIL GRADING</th>
                        <th style="background-color: #f8c4ac;" colspan="6">KELAS JANJANG</th>
                    </tr>
                    <tr>
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
                <tbody id="rekap_afdeling_data" class="text-center">
                    @if (!empty($resultdata))
                    @foreach ($resultdata as $date => $estates)
                    @foreach ($estates as $estate => $afdelings)
                    @if($estate !== 'total_date')
                    @foreach ($afdelings as $afdeling => $data)
                    @if($afdeling !== 'total_estate')
                    @foreach ($data as $key => $value)
                    @if($key !== 'total_blok')
                    @php
                    $loose_fruit_kg = round(($value['loose_fruit'] / $value['tonase']) * 100, 2);
                    $dirt_kg = round(($value['dirt'] / $value['tonase']) * 100, 2);
                    $abnormal = $value['abn_partheno'] + $value['abn_hard'] + $value['abn_sakit'] + $value['abn_kastrasi'];
                    $unripe = $value['unripe_tanpa_brondol'] + $value['unripe_kurang_brondol'];
                    $ripeness = $value['jjg_grading'] - ($value['overripe'] + $value['empty'] + $value['rotten'] + $abnormal + $unripe);

                    // Calculate percentages
                    $percentage_ripeness = ($ripeness / $value['jjg_grading']) * 100;
                    $percentage_unripe = ($unripe / $value['jjg_grading']) * 100;
                    $percentage_brondol_0 = ($value['unripe_tanpa_brondol'] / $value['jjg_grading']) * 100;
                    $percentage_brondol_less = ($value['unripe_kurang_brondol'] / $value['jjg_grading']) * 100;
                    $percentage_overripe = ($value['overripe'] / $value['jjg_grading']) * 100;
                    $percentage_empty_bunch = ($value['empty'] / $value['jjg_grading']) * 100;
                    $percentage_rotten_bunch = ($value['rotten'] / $value['jjg_grading']) * 100;
                    $percentage_abnormal = ($abnormal / $value['jjg_grading']) * 100;
                    $percentage_tangkai_panjang = ($value['tangkai_panjang'] / $value['jjg_grading']) * 100;
                    $percentage_vcuts = ($value['vcut'] / $value['jjg_grading']) * 100;
                    $percentage_kelas_a = ($value['kelas_a'] / $value['jjg_grading']) * 100;
                    $percentage_kelas_b = ($value['kelas_b'] / $value['jjg_grading']) * 100;
                    $percentage_kelas_c = ($value['kelas_c'] / $value['jjg_grading']) * 100;

                    $jumlah_selisih_janjang = $value['jjg_grading'] - $value['jjg_spb'];
                    $percentage_selisih_janjang = ($jumlah_selisih_janjang / $value['jjg_spb']) * 100;
                    $bjr = round(($value['jjg_spb'] / $value['tonase']) * 100, 2);
                    $jam = date('H:i', strtotime($value['datetime']));
                    @endphp

                    <tr class="align-middle">
                        <td class="fw-semibold">{{$date}}</td>
                        <td>{{$estate}}</td>
                        <td>{{$value['afdeling']}}</td>
                        <td>{{$value['mill']}}</td>
                        <td>{{$jam}}</td>
                        <td>{{$value['no_plat']}}</td>
                        <td>{{$value['driver']}}</td>
                        <td>{{$value['blok']}}</td>
                        <td class="fw-bold">{{$value['jjg_spb']}}</td>
                        <td class="fw-bold">{{$value['jjg_grading']}}</td>
                        <td class="fw-bold">{{$value['tonase']}}</td>
                        <td class="fw-bold">{{$bjr}}</td>
                        <td>{{$ripeness}}</td>
                        <td>{{round($percentage_ripeness, 2)}}</td>
                        <td>{{$unripe}}</td>
                        <td>{{round($percentage_unripe, 2)}}</td>
                        <td>{{$value['overripe']}}</td>
                        <td>{{round($percentage_overripe, 2)}}</td>
                        <td>{{$value['empty']}}</td>
                        <td>{{round($percentage_empty_bunch, 2)}}</td>
                        <td>{{$value['rotten']}}</td>
                        <td>{{round($percentage_rotten_bunch, 2)}}</td>
                        <td>{{$abnormal}}</td>
                        <td>{{round($percentage_abnormal, 2)}}</td>
                        <td>{{$value['tangkai_panjang']}}</td>
                        <td>{{round($percentage_tangkai_panjang, 2)}}</td>
                        <td>{{$value['vcut']}}</td>
                        <td>{{round($percentage_vcuts, 2)}}</td>
                        <td>{{$value['loose_fruit']}}</td>
                        <td>{{round($loose_fruit_kg, 2)}}</td>
                        <td>{{$value['dirt']}}</td>
                        <td>{{round($dirt_kg, 2)}}</td>
                        <td>{{$value['kelas_c']}}</td>
                        <td>{{round($percentage_kelas_c, 2)}}</td>
                        <td>{{$value['kelas_b']}}</td>
                        <td>{{round($percentage_kelas_b, 2)}}</td>
                        <td>{{$value['kelas_a']}}</td>
                        <td>{{round($percentage_kelas_a, 2)}}</td>
                    </tr>
                    @endif
                    @endforeach

                    <!-- Afdeling Total Row -->
                    <tr class="table-warning fw-bold">
                        <td>{{$date}}</td>
                        <td colspan="4">{{$estate}}</td>
                        <td colspan="3">{{$value['afdeling']}}</td>
                        <td>{{$value['jjg_spb']}}</td>
                        <td>{{$value['jjg_grading']}}</td>
                        <td>{{$value['tonase']}}</td>
                        <td>{{$value['bjr']}}</td>
                        <td>{{$value['Ripeness']}}</td>
                        <td>{{$value['percentase_ripenes']}}</td>
                        <td>{{$value['Unripe']}}</td>
                        <td>{{$value['persenstase_unripe']}}</td>
                        <td>{{$value['overripe']}}</td>
                        <td>{{$value['persentase_overripe']}}</td>
                        <td>{{$value['empty']}}</td>
                        <td>{{$value['persentase_empty_bunch']}}</td>
                        <td>{{$value['rotten']}}</td>
                        <td>{{$value['persentase_rotten_bunce']}}</td>
                        <td>{{$value['Abnormal']}}</td>
                        <td>{{$value['persentase_abnormal']}}</td>
                        <td>{{$value['tangkai_panjang']}}</td>
                        <td>{{$value['persentase_stalk']}}</td>
                        <td>{{$value['vcuts']}}</td>
                        <td>{{$value['persentase_vcut']}}</td>
                        <td>{{$value['loose_fruit']}}</td>
                        <td>{{$value['persentase_lose_fruit']}}</td>
                        <td>{{$value['dirt']}}</td>
                        <td>{{$value['persentase']}}</td>
                        <td>{{$value['kelas_c']}}</td>
                        <td>{{$value['persentase_kelas_c']}}</td>
                        <td>{{$value['kelas_b']}}</td>
                        <td>{{$value['persentase_kelas_b']}}</td>
                        <td>{{$value['kelas_a']}}</td>
                        <td>{{$value['persentase_kelas_a']}}</td>
                    </tr>
                    @endif
                    @endforeach

                    <!-- Estate Total Row -->
                    <tr class="table-primary text-white fw-bold">
                        <td>{{$date}}</td>
                        <td colspan="7">{{$estate}}</td>
                        <td>{{$data['jjg_spb']}}</td>
                        <td>{{$data['jjg_grading']}}</td>
                        <td>{{$data['tonase']}}</td>
                        <td>{{$data['bjr']}}</td>
                        <td>{{$data['Ripeness']}}</td>
                        <td>{{$data['percentase_ripenes']}}</td>
                        <td>{{$data['Unripe']}}</td>
                        <td>{{$data['persenstase_unripe']}}</td>
                        <td>{{$data['overripe']}}</td>
                        <td>{{$data['persentase_overripe']}}</td>
                        <td>{{$data['empty']}}</td>
                        <td>{{$data['persentase_empty_bunch']}}</td>
                        <td>{{$data['rotten']}}</td>
                        <td>{{$data['persentase_rotten_bunce']}}</td>
                        <td>{{$data['Abnormal']}}</td>
                        <td>{{$data['persentase_abnormal']}}</td>
                        <td>{{$data['tangkai_panjang']}}</td>
                        <td>{{$data['persentase_stalk']}}</td>
                        <td>{{$data['vcuts']}}</td>
                        <td>{{$data['persentase_vcut']}}</td>
                        <td>{{$data['loose_fruit']}}</td>
                        <td>{{$data['persentase_lose_fruit']}}</td>
                        <td>{{$data['dirt']}}</td>
                        <td>{{$data['persentase']}}</td>
                        <td>{{$data['kelas_c']}}</td>
                        <td>{{$data['persentase_kelas_c']}}</td>
                        <td>{{$data['kelas_b']}}</td>
                        <td>{{$data['persentase_kelas_b']}}</td>
                        <td>{{$data['kelas_a']}}</td>
                        <td>{{$data['persentase_kelas_a']}}</td>
                    </tr>
                    @endif
                    @endforeach

                    <!-- Date Total Row -->
                    <tr class="table-danger text-white fw-bold">
                        <td colspan="8">{{$date}}</td>
                        <td>{{$afdelings['jjg_spb']}}</td>
                        <td>{{$afdelings['jjg_grading']}}</td>
                        <td>{{$afdelings['tonase']}}</td>
                        <td>{{$afdelings['bjr']}}</td>
                        <td>{{$afdelings['Ripeness']}}</td>
                        <td>{{$afdelings['percentase_ripenes']}}</td>
                        <td>{{$afdelings['Unripe']}}</td>
                        <td>{{$afdelings['persenstase_unripe']}}</td>
                        <td>{{$afdelings['overripe']}}</td>
                        <td>{{$afdelings['persentase_overripe']}}</td>
                        <td>{{$afdelings['empty']}}</td>
                        <td>{{$afdelings['persentase_empty_bunch']}}</td>
                        <td>{{$afdelings['rotten']}}</td>
                        <td>{{$afdelings['persentase_rotten_bunce']}}</td>
                        <td>{{$afdelings['Abnormal']}}</td>
                        <td>{{$afdelings['persentase_abnormal']}}</td>
                        <td>{{$afdelings['tangkai_panjang']}}</td>
                        <td>{{$afdelings['persentase_stalk']}}</td>
                        <td>{{$afdelings['vcuts']}}</td>
                        <td>{{$afdelings['persentase_vcut']}}</td>
                        <td>{{$afdelings['loose_fruit']}}</td>
                        <td>{{$afdelings['persentase_lose_fruit']}}</td>
                        <td>{{$afdelings['dirt']}}</td>
                        <td>{{$afdelings['persentase']}}</td>
                        <td>{{$afdelings['kelas_c']}}</td>
                        <td>{{$afdelings['persentase_kelas_c']}}</td>
                        <td>{{$afdelings['kelas_b']}}</td>
                        <td>{{$afdelings['persentase_kelas_b']}}</td>
                        <td>{{$afdelings['kelas_a']}}</td>
                        <td>{{$afdelings['persentase_kelas_a']}}</td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="100" class="text-center fw-bold text-muted py-4">No data found</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

    </div>
</div>