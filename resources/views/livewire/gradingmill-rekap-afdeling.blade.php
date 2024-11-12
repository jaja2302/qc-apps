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
    <div class="mt-4 ml-3 mr-3 mb-10 text-center">
        <table class="table table-responsive table-striped table-bordered">
            <thead>
                <tr>
                    <th style="background-color: #f0ecec;" class="align-middle" rowspan="3">Estate</th>
                    <th style="background-color: #f0ecec;" class="align-middle" rowspan="3">Afdeling</th>
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
            <tbody id="rekap_afdeling_data">
                @if($resultdata == [])
                <tr>
                    <td colspan="36" style="color: #88e48c;">Tidak ada data tersedia</td>
                </tr>
                @else
                @foreach ($resultdata as $key => $items)
                @foreach ($items as $key1 => $items1)
                @foreach ($items1 as $key2 => $items2)
                <tr>
                    <td>{{$key1}}</td>
                    <td style="cursor: pointer; text-decoration: underline;color:blue;" wire:click="openModal('{{$key1}}', '{{$key2}}')">{{$key2}}</td>
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

                </tr>
                @endforeach
                @endforeach
                @endforeach
                @endif
            </tbody>
        </table>
    </div>

    <!-- Add this modal at the bottom of the div -->
    <div class="modal fade" id="detailModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Data Afdeling</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Add Card for Date Selection -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <label for="modalDate" class="form-label">Select Date:</label>
                                    <select class="form-select" id="modalDate" wire:model.live="selectedDate">
                                        <option value="">Choose a date</option>
                                        @if(is_array($availableDates) && count($availableDates) > 0)
                                        @foreach ($availableDates as $date)
                                        <option value="{{ $date['date'] }}">{{ \Carbon\Carbon::parse($date['date'])->format('d F Y') }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class="table table-responsive table-striped table-bordered">
                        <thead>
                            <tr>
                                <th style="background-color: #f0ecec;" class="align-middle" rowspan="3" colspan="2">Action</th>
                                <th style="background-color: #f0ecec;" class="align-middle" rowspan="3">Estate</th>
                                <th style="background-color: #f0ecec;" class="align-middle" rowspan="3">Afdeling</th>
                                <th style="background-color: #f0ecec;" colspan="6">UNIT SORTASI</th>
                                <th style="background-color: #88e48c;" colspan="20">HASIL GRADING</th>
                                <th style="background-color: #f8c4ac;" colspan="6">KELAS JANJANG</th>
                            </tr>
                            <tr>
                                <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">NO POLISI</th>
                                <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">WAKTU GRADING</th>
                                <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">JUMLAH JANJANG SPB</th>
                                <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">JUMLAH JANJANG GRADING</th>
                                <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">TONASE (KG)</th>
                                <th style="background-color: #f0ecec;" class="align-middle" rowspan="2">BJR(KG)</th>
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
                        <tbody id="rekap_perhari_data">
                            @forelse($modal_data as $data)
                            <tr>
                                <td>
                                    <button class="btn btn-sm btn-success" wire:click="downloadPDF('{{ $data['id'] }}')" wire:loading.attr="disabled" wire:target="downloadPDF('{{ $data['id'] }}')">
                                        <span wire:loading.remove wire:target="downloadPDF('{{ $data['id'] }}')">
                                            <i class="fas fa-download"></i> PDF
                                        </span>
                                        <span wire:loading wire:target="downloadPDF('{{ $data['id'] }}')">
                                            <i class="fas fa-spinner fa-spin"></i> Downloading...
                                        </span>
                                    </button>
                                </td>
                                <!-- downlaod the image only  -->
                                <td>
                                    <button class="btn btn-sm btn-primary"
                                        wire:click="downloadImage('{{ $data['id'] }}')"
                                        wire:loading.attr="disabled"
                                        wire:target="downloadImage('{{ $data['id'] }}')">
                                        @if(isset($isDownloadingImage[$data['id']]) && $isDownloadingImage[$data['id']])
                                        <span>
                                            <i class="fas fa-spinner fa-spin"></i> Downloading...
                                        </span>
                                        @else
                                        <span>
                                            <i class="fas fa-download"></i> Image
                                        </span>
                                        @endif
                                    </button>
                                </td>
                                <td>{{$data['estate']}}</td>
                                <td>{{$data['afdeling']}}</td>
                                <td>{{$data['no_plat']}}</td>
                                <td>{{$data['datetime']}}</td>
                                <td>{{$data['jjg_spb']}}</td>
                                <td>{{$data['jjg_grading']}}</td>
                                <td>{{$data['tonase']}}</td>
                                <td>{{round($data['bjr'],2)}}</td>
                                <td>{{$data['Ripeness']}}</td>
                                <td>{{$data['percentase_ripenes']}}</td>
                                <td>{{$data['Unripe']}}</td>
                                <td>{{$data['persenstase_unripe']}}</td>
                                <td>{{$data['Overripe']}}</td>
                                <td>{{$data['persentase_overripe']}}</td>
                                <td>{{$data['empty_bunch']}}</td>
                                <td>{{$data['persentase_empty_bunch']}}</td>
                                <td>{{$data['rotten_bunch']}}</td>
                                <td>{{$data['persentase_rotten_bunce']}}</td>
                                <td>{{$data['Abnormal']}}</td>
                                <td>{{$data['persentase_abnormal']}}</td>
                                <td>{{$data['stalk']}}</td>
                                <td>{{$data['persentase_stalk']}}</td>
                                <td>{{$data['vcut']}}</td>
                                <td>{{$data['persentase_vcut']}}</td>
                                <td>{{$data['loose_fruit']}}</td>
                                <td>{{$data['persentase_lose_fruit']}}</td>
                                <td>{{$data['Dirt']}}</td>
                                <td>{{$data['persentase']}}</td>
                                <td>{{$data['kelas_a']}}</td>
                                <td>{{$data['persentase_kelas_a']}}</td>
                                <td>{{$data['kelas_b']}}</td>
                                <td>{{$data['persentase_kelas_b']}}</td>
                                <td>{{$data['kelas_c']}}</td>
                                <td>{{$data['persentase_kelas_c']}}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="35" class="text-center">No data available for selected date</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
    <script type="module">
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('show-modal', () => {
                const modalElement = document.getElementById('detailModal');
                const myModal = new bootstrap.Modal(modalElement);

                // Show modal programmatically
                myModal.show();
            });

            Livewire.on('closeModal', (data) => {
                const modalElement = document.getElementById('detailModal');
                const myModal = bootstrap.Modal.getInstance(modalElement);

                // Hide modal programmatically
                if (myModal) {
                    myModal.hide();
                }
            });
        });
    </script>
    <!-- root liveware  -->
</div>