<div>

    <!-- Flash message -->
    @if (session()->has('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="d-flex justify-content-end mr-3 mt-4">
        <div class="margin g-2">
            <div class="row align-items-center">
                <!-- Date Input -->
                <div class="col-md">
                    <!-- Date Input -->
                    <input class="form-control" type="date" wire:model="inputbulan" id="input_rekap_perhari">
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
                    @if(can_edit())
                    <th style="background-color: #f8c4ac;vertical-align:middle" rowspan="4"> Action</th>
                    @endif
                </tr>
                <tr>
                    <th rowspan="3" style="background-color: #f0ecec;">ESTATE</th>
                    <th rowspan="3" style="background-color: #f0ecec;">AFDELING</th>
                    <th style="background-color: #f0ecec;" colspan="7">UNIT SORTASI</th>
                    <th style="background-color: #88e48c;" colspan="20">HASIL GRADING</th>
                    <th style="background-color: #f8c4ac;" colspan="6">KELAS JANJANG</th>
                </tr>
                <tr>
                    <th class="no-polisi" style="background-color: #f0ecec;vertical-align: middle;" rowspan="2">NO POLISI</th>
                    <th style="background-color: #f0ecec;vertical-align: middle;" rowspan="2">WAKTU GRADING</th>
                    <th style="background-color: #f0ecec;vertical-align: middle;" rowspan="2">TONASE TIMBANGAN (KG)</th>
                    <th style="background-color: #f0ecec;vertical-align: middle;" rowspan="2">JUMLAH JANJANG SPB</th>
                    <th style="background-color: #f0ecec;vertical-align: middle;" rowspan="2">JUMLAH JANJANG GRADING</th>
                    <th style="background-color: #f0ecec;vertical-align: middle;" rowspan="2">TONASE GRADING (KG)</th>
                    <th style="background-color: #f0ecec;vertical-align: middle;" rowspan="2">BJR (KG)</th>
                    <th style="background-color: #88e48c;vertical-align: middle;" colspan="2">RIPENESS</th>
                    <th style="background-color: #88e48c;vertical-align: middle;" colspan="2">UNRIPE</th>
                    <th style="background-color: #88e48c;vertical-align: middle;" colspan="2">OVER-RIPE</th>
                    <th style="background-color: #88e48c;vertical-align: middle;" colspan="2">EMPTY BUNCH</th>
                    <th style="background-color: #88e48c;vertical-align: middle;" colspan="2">ROTTEN BUNCH</th>
                    <th style="background-color: #88e48c;vertical-align: middle;" colspan="2">ABNORMAL</th>
                    <th style="background-color: #88e48c;vertical-align: middle;" colspan="2">LONG STALK</th>
                    <th style="background-color: #88e48c;vertical-align: middle;" colspan="2">V-CUT</th>
                    <th style="background-color: #88e48c;vertical-align: middle;" colspan="2">DIRT</th>
                    <th style="background-color: #88e48c;vertical-align: middle;" colspan="2">LOOSE FRUIT</th>
                    <th style="background-color: #f8c4ac;vertical-align: middle;" colspan="2">KELAS C</th>
                    <th style="background-color: #f8c4ac;vertical-align: middle;" colspan="2">KELAS B</th>
                    <th style="background-color: #f8c4ac;vertical-align: middle;" colspan="2">KELAS A</th>
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
                @if($resultdata == [])
                <tr>
                    <td colspan="36" style="color: #88e48c;">Tidak ada data tersedia</td>
                </tr>
                @else
                @foreach ($resultdata as $key => $items)
                @foreach ($items as $key2 => $items2)
                <tr>
                    <td>{{$key}}</td>
                    <td>{{$key2}}</td>
                    <td>{{$items2['no_plat']}}</td>
                    <td>{{$items2['unit']}}</td>
                    <td>{{$items2['tonase']}}</td>
                    <td>{{$items2['jumlah_janjang_spb']}}</td>
                    <td>{{$items2['jumlah_janjang_grading']}}</td>
                    <td>{{$items2['tonase']}}</td>
                    <td>{{round($items2['bjr'],2)}}</td>
                    <td>{{$items2['ripeness']}}</td>
                    <td>{{round($items2['percentage_ripeness'],2)}}</td>
                    <td>{{$items2['unripe']}}</td>
                    <td>{{round($items2['percentage_unripe'],2)}}</td>
                    <td>{{$items2['overripe']}}</td>
                    <td>{{round($items2['percentage_overripe'],2)}}</td>
                    <td>{{$items2['empty_bunch']}}</td>
                    <td>{{round($items2['percentage_empty_bunch'],2)}}</td>
                    <td>{{$items2['rotten_bunch']}}</td>
                    <td>{{round($items2['percentage_rotten_bunch'],2)}}</td>
                    <td>{{$items2['abnormal']}}</td>
                    <td>{{round($items2['percentage_abnormal'],2)}}</td>
                    <td>{{$items2['longstalk']}}</td>
                    <td>{{round($items2['percentage_longstalk'],2)}}</td>
                    <td>{{$items2['vcut']}}</td>
                    <td>{{round($items2['percentage_vcut'],2)}}</td>
                    <td>{{$items2['dirt_kg']}}</td>
                    <td>{{round($items2['percentage_dirt'],2)}}</td>
                    <td>{{$items2['loose_fruit_kg']}}</td>
                    <td>{{round($items2['percentage_loose_fruit'],2)}}</td>
                    <td>{{$items2['kelas_c']}}</td>
                    <td>{{round($items2['percentage_kelas_c'],2)}}</td>
                    <td>{{$items2['kelas_b']}}</td>
                    <td>{{round($items2['percentage_kelas_b'],2)}}</td>
                    <td>{{$items2['kelas_a']}}</td>
                    <td>{{round($items2['percentage_kelas_a'],2)}}</td>
                    @if(can_edit())
                    <td>
                        <button type="button" class="btn btn-warning btn-sm" wire:click="formdata('{{ $key }}', '{{ $key2 }}')">
                            Edit
                        </button>

                    </td>
                    @endif
                </tr>
                @endforeach
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @foreach ($modal_data as $key => $items)
                    <form wire:submit.prevent="editgradingmill({{ $items['id'] }})" class="mb-4 p-4 border rounded shadow-sm">
                        <h5 class="mb-3">Edit Grading Mill</h5>

                        <!-- Row for ID and Estate -->
                        <div class="row mb-3">
                            <div class="col-md">
                                <label for="id" class="form-label">ID</label>
                                <input type="text" class="form-control" wire:model="modal_data.{{ $key }}.id" readonly>
                            </div>
                            <div class="col-md">
                                <label for="estate" class="form-label">Estate</label>
                                <input type="text" class="form-control" wire:model="modal_data.{{ $key }}.estate" readonly>
                            </div>
                            <div class="col-md">
                                <label for="afdeling" class="form-label">Afdeling</label>
                                <input type="text" class="form-control" wire:model="modal_data.{{ $key }}.afdeling" readonly>
                            </div>
                            <div class="col-md">
                                <label for="blok" class="form-label">Blok</label>
                                <input type="text" class="form-control" wire:model="modal_data.{{ $key }}.blok" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md">
                                <label for="datetime" class="form-label">Date and Time</label>
                                <input type="datetime-local" class="form-control" wire:model="modal_data.{{ $key }}.datetime" readonly>
                            </div>
                            <div class="col-md">
                                <label for="app_version" class="form-label">App Version</label>
                                <input type="text" class="form-control" wire:model="modal_data.{{ $key }}.app_version" readonly>
                            </div>

                        </div>
                        <!-- Row for Tonase and Petugas -->
                        <div class="row mb-3">
                            <div class="col-md">
                                <label for="status_bot" class="form-label">Status Bot WA</label>
                                <input type="number" class="form-control" wire:model="modal_data.{{ $key }}.status_bot">
                            </div>
                            <div class="col-md">
                                <label for="petugas" class="form-label">Petugas</label>
                                <input type="number" class="form-control" wire:model="modal_data.{{ $key }}.petugas" readonly>
                            </div>

                            <div class="col-md">
                                <label for="no_plat" class="form-label">No Plat</label>
                                <input type="text" class="form-control" wire:model="modal_data.{{ $key }}.no_plat">
                            </div>
                            <div class="col-md">
                                <label for="mill" class="form-label">Mill</label>
                                <input type="text" class="form-control" wire:model="modal_data.{{ $key }}.mill" readonly>
                            </div>



                        </div>
                        <div class="row mb-3">
                            <div class="col-md">
                                <label for="jjg_spb" class="form-label">JJG SPB</label>
                                <input type="number" class="form-control" wire:model="modal_data.{{ $key }}.jjg_spb">
                            </div>
                            <div class="col-md">
                                <label for="jjg_grading" class="form-label">JJG Grading</label>
                                <input type="number" class="form-control" wire:model="modal_data.{{ $key }}.jjg_grading">
                            </div>
                            <div class="col-md">
                                <label for="overripe" class="form-label">Overripe</label>
                                <input type="number" class="form-control" wire:model="modal_data.{{ $key }}.overripe">
                            </div>
                            <div class="col-md">
                                <label for="empty" class="form-label">Empty</label>
                                <input type="number" class="form-control" wire:model="modal_data.{{ $key }}.empty">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md">
                                <label for="rotten" class="form-label">Rotten</label>
                                <input type="number" class="form-control" wire:model="modal_data.{{ $key }}.rotten">
                            </div>
                            <div class="col-md">
                                <label for="abn_partheno" class="form-label">ABN Partheno</label>
                                <input type="number" class="form-control" wire:model="modal_data.{{ $key }}.abn_partheno">
                            </div>
                            <div class="col-md">
                                <label for="abn_hard" class="form-label">ABN Hard</label>
                                <input type="number" class="form-control" wire:model="modal_data.{{ $key }}.abn_hard">
                            </div>
                            <div class="col-md">
                                <label for="abn_sakit" class="form-label">ABN Sakit</label>
                                <input type="number" class="form-control" wire:model="modal_data.{{ $key }}.abn_sakit">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md">
                                <label for="abn_kastrasi" class="form-label">ABN Kastrasi</label>
                                <input type="number" class="form-control" wire:model="modal_data.{{ $key }}.abn_kastrasi">
                            </div>
                            <div class="col-md">
                                <label for="tangkai_panjang" class="form-label">Tangkai Panjang</label>
                                <input type="number" class="form-control" wire:model="modal_data.{{ $key }}.tangkai_panjang">
                            </div>
                            <div class="col-md">
                                <label for="vcut" class="form-label">V Cut</label>
                                <input type="number" class="form-control" wire:model="modal_data.{{ $key }}.vcut">
                            </div>

                            <div class="col-md">
                                <label for="dirt" class="form-label">Dirt</label>
                                <input type="number" class="form-control" wire:model="modal_data.{{ $key }}.dirt">
                            </div>

                        </div>
                        <div class="row mb-3">
                            <div class="col-md">
                                <label for="karung" class="form-label">Karung</label>
                                <input type="number" class="form-control" wire:model="modal_data.{{ $key }}.karung">
                            </div>
                            <div class="col-md">
                                <label for="loose_fruit" class="form-label">Loose Fruit</label>
                                <input type="number" class="form-control" wire:model="modal_data.{{ $key }}.loose_fruit">
                            </div>
                            <div class="col-md">
                                <label for="tonase" class="form-label">Tonase</label>
                                <input type="number" class="form-control" wire:model="modal_data.{{ $key }}.tonase">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md">
                                <label for="kelas_c" class="form-label">Kelas C</label>
                                <input type="number" class="form-control" wire:model="modal_data.{{ $key }}.kelas_c">
                            </div>

                            <div class="col-md">
                                <label for="kelas_b" class="form-label">Kelas B</label>
                                <input type="number" class="form-control" wire:model="modal_data.{{ $key }}.kelas_b">
                            </div>
                            <div class="col-md">
                                <label for="kelas_a" class="form-label">Kelas A</label>
                                <input type="number" class="form-control" wire:model="modal_data.{{ $key }}.kelas_a">
                            </div>
                        </div>
                        <div class="row mb-3">

                            <div class="col-md">
                                <label for="unripe_tanpa_brondol" class="form-label">Unripe Tanpa Brondol</label>
                                <input type="number" class="form-control" wire:model="modal_data.{{ $key }}.unripe_tanpa_brondol">
                            </div>

                            <div class="col-md">
                                <label for="unripe_kurang_brondol" class="form-label">Unripe Kurang Brondol</label>
                                <input type="number" class="form-control" wire:model="modal_data.{{ $key }}.unripe_kurang_brondol">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control" wire:model="modal_data.{{ $key }}.keterangan"></textarea>
                        </div>
                        @php

                        // Fetch the user's full name based on the user_id from the items array
                        $pengguna = App\Models\Pengguna::where('user_id', $items['update_by'])->first()->nama_lengkap ?? '-';
                        @endphp

                        <div class="col-md">
                            <label for="updated" class="form-label">Updated by: {{ $pengguna }}</label>

                        </div>
                        <label for="update_date" class="form-label">Update date: {{ $items['update_date'] ?? '-' }}</label>
                        <div class="d-flex justify-content-end">
                            <!-- <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button> -->
                            <button type="submit" class="btn btn-primary">Update</button>
                            <button type="button" class="btn btn-danger btn-sm" wire:click="delete({{ $items['id'] }})">
                                Delete
                            </button>
                        </div>

                    </form>
                    @endforeach



                </div>
            </div>
        </div>
    </div>

    <!-- Button trigger modal -->

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Understood</button>
                </div>
            </div>
        </div>
    </div>
    <script type="module">
        document.addEventListener('livewire:init', () => {
            Livewire.on('showModal', (data) => {
                const modalElement = document.getElementById('exampleModal');
                const myModal = new bootstrap.Modal(modalElement);

                // Show modal programmatically
                myModal.show();
                // $('#exampleModal').modal('show');
            });
            Livewire.on('closeModal', (data) => {
                const modalElement = document.getElementById('exampleModal');
                const myModal = new bootstrap.Modal(modalElement);

                // Show modal programmatically
                myModal.hide();
                // $('#exampleModal').modal('hide');
            });
            Livewire.on('refreshComponent', (data) => {
                location.reload();
            });
        });
    </script>

</div>