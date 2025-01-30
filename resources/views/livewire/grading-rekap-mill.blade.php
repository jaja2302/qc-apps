<div>
    <div class="d-flex justify-content-end mr-3 mt-4">
        <div class="margin g-2">
            <div class="row align-items-center">
                <!-- Date Input -->
                <div class="col-md">
                    {{csrf_field()}}
                    <input class="form-control" type="month" wire:model="inputbulan">
                </div>
                <div class="col-md">
                    <!-- Regional Select with wire:change -->
                    <select class="form-select mb-2 mb-md-0" wire:change="getdatamill($event.target.value)" wire:model="inputregional" id="rekap_perhari_reg">
                        <option value="">Select Regional</option>
                        @foreach ($regional_id as $regional)
                        <option value="{{ $regional['id'] }}">{{ $regional['nama'] }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Mill Select -->
                <div class="col-md">
                    <select class="form-select mb-2 mb-md-0" wire:model="mill_id" id="mill_id">
                        <option value="">Select Mill</option>
                        @foreach ($mills as $mill)
                        <option value="{{ $mill['id'] }}">{{ $mill['nama_mill'] }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Show Button -->
                <div class="col-auto">
                    <button type="button" class="btn btn-primary ml-2" wire:click="showResults">Show</button>
                </div>


                <!-- Export Form -->
                <div class="col-auto">
                    <form wire:submit.prevent="exportData">
                        <button type="submit" class="btn btn-primary">Export</button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <div class="mt-4 ml-3 mr-3 mb-10 text-center">
        <table class="table table-responsive table-striped table-bordered">
            <thead>
                <tr>
                    <th colspan="35" style="background-color: #c8e4f4;">BERDASARKAN ESTATE</th>
                </tr>
                <tr>
                    <th rowspan="3" class="align-middle" style="background-color: #f0ecec;">Estate</th>
                    <th style="background-color: #f0ecec;" colspan="2">UNIT SORTASI</th>
                    <th style="background-color: #88e48c;" colspan="20">HASIL GRADING</th>
                    <th style="background-color: #f8c4ac;" colspan="6">KELAS JANJANG</th>
                    <th style="background-color: #B1A1C6;" colspan="4">BUAH MENTAH</th>
                </tr>
                <tr>
                    <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">JUMLAH JANJANG GRADING</th>
                    <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">TONASE(KG)</th>
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
                    <th style="background-color: #B1A1C6;" colspan="2">TIDAK BRONDOL</th>
                    <th style="background-color: #B1A1C6;" colspan="2">KURANG BRONDOL</th>
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
                    <th style="background-color: #B1A1C6;">JJG</th>
                    <th style="background-color: #B1A1C6;">%</th>
                    <th style="background-color: #B1A1C6;">JJG</th>
                    <th style="background-color: #B1A1C6;">%</th>
                </tr>
            </thead>
            <tbody id="rekap_mill">
                @if($resultdata == [])
                <tr>
                    <td colspan="36" style="color: #88e48c;">Tidak ada data tersedia</td>
                </tr>
                @else
                @foreach ($resultdata as $key => $items)
                @foreach ($items as $key2 => $items2)
                <tr>
                    <td>{{ $key }}</td>
                    <td>{{ $items2['jjg_grading'] }}</td>
                    <td>{{ $items2['tonase'] }}</td>
                    <td>{{ $items2['ripeness'] }}</td>
                    <td>{{ $items2['percentage_ripeness'] }}</td>
                    <td>{{ $items2['unripe'] }}</td>
                    <td>{{ $items2['percentage_unripe'] }}</td>
                    <td>{{ $items2['overripe'] }}</td>
                    <td>{{ $items2['percentage_overripe'] }}</td>
                    <td>{{ $items2['empty_bunch'] }}</td>
                    <td>{{ $items2['percentage_empty_bunch'] }}</td>
                    <td>{{ $items2['rotten_bunch'] }}</td>
                    <td>{{ $items2['percentage_rotten_bunch'] }}</td>
                    <td>{{ $items2['abnormal'] }}</td>
                    <td>{{ $items2['percentage_abnormal'] }}</td>
                    <td>{{ $items2['longstalk'] }}</td>
                    <td>{{ $items2['percentage_longstalk'] }}</td>
                    <td>{{ $items2['vcut'] }}</td>
                    <td>{{ $items2['percentage_vcut'] }}</td>
                    <td>{{ $items2['dirt'] }}</td>
                    <td>{{ $items2['percentage_dirt'] }}</td>
                    <td>{{ $items2['loose_fruit'] }}</td>
                    <td>{{ $items2['percentage_loose_fruit'] }}</td>
                    <td>{{ $items2['kelas_a'] }}</td>
                    <td>{{ $items2['percentage_kelas_a'] }}</td>
                    <td>{{ $items2['kelas_b'] }}</td>
                    <td>{{ $items2['percentage_kelas_b'] }}</td>
                    <td>{{ $items2['kelas_c'] }}</td>
                    <td>{{ $items2['percentage_kelas_c'] }}</td>
                    <td>{{ $items2['unripe_tanpa_brondol'] }}</td>
                    <td>{{ $items2['persentase_unripe_tanpa_brondol'] }}</td>
                    <td>{{ $items2['unripe_kurang_brondol'] }}</td>
                    <td>{{ $items2['persentase_unripe_kurang_brondol'] }}</td>
                </tr>
                @endforeach
                @endforeach

                @foreach ($resulttotal as $key => $items)
                @foreach ($items as $key2 => $items2)
                <tr>
                    <td style="background-color: #88e48c;">{{ $key }}</td>
                    <td style="background-color: #88e48c;">{{ $items2['jjg_grading'] }}</td>
                    <td style="background-color: #88e48c;">{{ $items2['tonase'] }}</td>
                    <td style="background-color: #88e48c;">{{ $items2['ripeness'] }}</td>
                    <td style="background-color: #88e48c;">{{ $items2['percentage_ripeness'] }}</td>
                    <td style="background-color: #88e48c;">{{ $items2['unripe'] }}</td>
                    <td style="background-color: #88e48c;">{{ $items2['percentage_unripe'] }}</td>
                    <td style="background-color: #88e48c;">{{ $items2['overripe'] }}</td>
                    <td style="background-color: #88e48c;">{{ $items2['percentage_overripe'] }}</td>
                    <td style="background-color: #88e48c;">{{ $items2['empty_bunch'] }}</td>
                    <td style="background-color: #88e48c;">{{ $items2['percentage_empty_bunch'] }}</td>
                    <td style="background-color: #88e48c;">{{ $items2['rotten_bunch'] }}</td>
                    <td style="background-color: #88e48c;">{{ $items2['percentage_rotten_bunch'] }}</td>
                    <td style="background-color: #88e48c;">{{ $items2['abnormal'] }}</td>
                    <td style="background-color: #88e48c;">{{ $items2['percentage_abnormal'] }}</td>
                    <td style="background-color: #88e48c;">{{ $items2['longstalk'] }}</td>
                    <td style="background-color: #88e48c;">{{ $items2['percentage_longstalk'] }}</td>
                    <td style="background-color: #88e48c;">{{ $items2['vcut'] }}</td>
                    <td style="background-color: #88e48c;">{{ $items2['percentage_vcut'] }}</td>
                    <td style="background-color: #88e48c;">{{ $items2['dirt'] }}</td>
                    <td style="background-color: #88e48c;">{{ $items2['percentage_dirt'] }}</td>
                    <td style="background-color: #88e48c;">{{ $items2['loose_fruit'] }}</td>
                    <td style="background-color: #88e48c;">{{ $items2['percentage_loose_fruit'] }}</td>
                    <td style="background-color: #88e48c;">{{ $items2['kelas_a'] }}</td>
                    <td style="background-color: #88e48c;">{{ $items2['percentage_kelas_a'] }}</td>
                    <td style="background-color: #88e48c;">{{ $items2['kelas_b'] }}</td>
                    <td style="background-color: #88e48c;">{{ $items2['percentage_kelas_b'] }}</td>
                    <td style="background-color: #88e48c;">{{ $items2['kelas_c'] }}</td>
                    <td style="background-color: #88e48c;">{{ $items2['percentage_kelas_c'] }}</td>
                    <td style="background-color: #88e48c;">{{ $items2['unripe_tanpa_brondol'] }}</td>
                    <td style="background-color: #88e48c;">{{ $items2['persentase_unripe_tanpa_brondol'] }}</td>
                    <td style="background-color: #88e48c;">{{ $items2['unripe_kurang_brondol'] }}</td>
                    <td style="background-color: #88e48c;">{{ $items2['persentase_unripe_kurang_brondol'] }}</td>

                </tr>
                @endforeach
                @endforeach
                @endif
            </tbody>

        </table>
    </div>
</div>