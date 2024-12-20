<div>
    <!-- Add Flash Messages Section -->
    <div class="mx-3">
        @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Gagal!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if (session()->has('message'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
    </div>

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
                    <th style="background-color: #B1A1C6;" colspan="4">BUAH MENTAH</th>
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
                @if ($key1 !== 'Total')
                @foreach ($items1 as $key2 => $items2)
                @if($key2 == 'afdeling')
                <tr>
                    <td>{{$key}}</td>
                    <td style="cursor: pointer; text-decoration: underline;color:blue;" wire:click="openModal('{{$key}}', '{{$key1}}')">{{$key1}}</td>
                    <td>{{$items2['jjg_spb']}}</td>
                    <td>{{$items2['jjg_grading']}}</td>
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
                    <td>{{$items2['dirt']}}</td>
                    <td>{{round($items2['percentage_dirt'],2)}}</td>
                    <td>{{$items2['loose_fruit']}}</td>
                    <td>{{round($items2['percentage_loose_fruit'],2)}}</td>
                    <td>{{$items2['kelas_c']}}</td>
                    <td>{{round($items2['percentage_kelas_c'],2)}}</td>
                    <td>{{$items2['kelas_b']}}</td>
                    <td>{{round($items2['percentage_kelas_b'],2)}}</td>
                    <td>{{$items2['kelas_a']}}</td>
                    <td>{{round($items2['percentage_kelas_a'],2)}}</td>
                    <td>{{round($items2['unripe_tanpa_brondol'],2)}}</td>
                    <td>{{round($items2['persentase_unripe_tanpa_brondol'],2)}}</td>
                    <td>{{round($items2['unripe_kurang_brondol'],2)}}</td>
                    <td>{{round($items2['persentase_unripe_kurang_brondol'],2)}}</td>

                </tr>
                @endif
                @endforeach
                @else
                <tr>
                    <td style="background-color: #9DCED4;">{{$key}}</td>
                    <td style="background-color: #9DCED4;">{{$key1}}</td>
                    <td style="background-color: #9DCED4;">{{$items1['jjg_spb']}}</td>
                    <td style="background-color: #9DCED4;">{{$items1['jjg_grading']}}</td>
                    <td style="background-color: #9DCED4;">{{$items1['tonase']}}</td>
                    <td style="background-color: #9DCED4;">{{round($items1['bjr'],2)}}</td>
                    <td style="background-color: #9DCED4;">{{$items1['ripeness']}}</td>
                    <td style="background-color: #9DCED4;">{{round($items1['percentage_ripeness'],2)}}</td>
                    <td style="background-color: #9DCED4;">{{$items1['unripe']}}</td>
                    <td style="background-color: #9DCED4;">{{round($items1['percentage_unripe'],2)}}</td>
                    <td style="background-color: #9DCED4;">{{$items1['overripe']}}</td>
                    <td style="background-color: #9DCED4;">{{round($items1['percentage_overripe'],2)}}</td>
                    <td style="background-color: #9DCED4;">{{$items1['empty_bunch']}}</td>
                    <td style="background-color: #9DCED4;">{{round($items1['percentage_empty_bunch'],2)}}</td>
                    <td style="background-color: #9DCED4;">{{$items1['rotten_bunch']}}</td>
                    <td style="background-color: #9DCED4;">{{round($items1['percentage_rotten_bunch'],2)}}</td>
                    <td style="background-color: #9DCED4;">{{$items1['abnormal']}}</td>
                    <td style="background-color: #9DCED4;">{{round($items1['percentage_abnormal'],2)}}</td>
                    <td style="background-color: #9DCED4;">{{$items1['longstalk']}}</td>
                    <td style="background-color: #9DCED4;">{{round($items1['percentage_longstalk'],2)}}</td>
                    <td style="background-color: #9DCED4;">{{$items1['vcut']}}</td>
                    <td style="background-color: #9DCED4;">{{round($items1['percentage_vcut'],2)}}</td>
                    <td style="background-color: #9DCED4;">{{$items1['dirt']}}</td>
                    <td style="background-color: #9DCED4;">{{round($items1['percentage_dirt'],2)}}</td>
                    <td style="background-color: #9DCED4;">{{$items1['loose_fruit']}}</td>
                    <td style="background-color: #9DCED4;">{{round($items1['percentage_loose_fruit'],2)}}</td>
                    <td style="background-color: #9DCED4;">{{$items1['kelas_c']}}</td>
                    <td style="background-color: #9DCED4;">{{round($items1['percentage_kelas_c'],2)}}</td>
                    <td style="background-color: #9DCED4;">{{$items1['kelas_b']}}</td>
                    <td style="background-color: #9DCED4;">{{round($items1['percentage_kelas_b'],2)}}</td>
                    <td style="background-color: #9DCED4;">{{$items1['kelas_a']}}</td>
                    <td style="background-color: #9DCED4;">{{round($items1['percentage_kelas_a'],2)}}</td>
                    <td style="background-color: #9DCED4;">{{round($items1['unripe_tanpa_brondol'],2)}}</td>
                    <td style="background-color: #9DCED4;">{{round($items1['persentase_unripe_tanpa_brondol'],2)}}</td>
                    <td style="background-color: #9DCED4;">{{round($items1['unripe_kurang_brondol'],2)}}</td>
                    <td style="background-color: #9DCED4;">{{round($items1['persentase_unripe_kurang_brondol'],2)}}</td>
                </tr>
                @endif
                @endforeach
                @endforeach


                @endif
            </tbody>
        </table>
    </div>

    <!-- Add this modal at the bottom of the div -->
    <div class="modal fade" id="detailModal" tabindex="-1" wire:ignore.self
        aria-labelledby="detailModalLabel" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Data Afdeling</h5>
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
                                <th style="background-color: #B1A1C6;" colspan="4">BUAH MENTAH</th>
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
                                {{--
                                    <td>
                                    <table>
                                        <tr>
                                            @php
                                            $status_bot = $data['status_bot'];
                                            if ($status_bot == 0) {
                                            $status_bot = 'Pending';
                                            $bgcol = 'bg-warning';
                                            } else if ($status_bot == 1) {
                                            $status_bot = 'Sent';
                                            $bgcol = 'bg-success';
                                            }
                                            @endphp
                                            <td class="{{ $bgcol }}">{{ $status_bot }}</td>
                            </tr>
                            <tr>
                                <td>
                                    <button class="btn btn-sm btn-primary"
                                        wire:click="confirmResend('{{ $data['id'] }}')"
                                        wire:loading.attr="disabled"
                                        wire:target="confirmResend('{{ $data['id'] }}')">
                                        @if(isset($isresendingWhatsapp[$data['id']]) && $isresendingWhatsapp[$data['id']])
                                        <span>
                                            <i class="fas fa-spinner fa-spin"></i> Resending...
                                        </span>
                                        @else
                                        <span>
                                            <i class="fas fa-paper-plane"></i> Resend
                                        </span>
                                        @endif
                                    </button>
                                </td>
                            </tr>
                    </table>
                    </td>

                    --}}

                    <td>{{$data['estate']}}</td>
                    <td>{{$data['afdeling']}}</td>
                    <td>{{$data['no_plat']}}</td>
                    <td>{{$data['datetime']}}</td>
                    <td>{{$data['jjg_spb']}}</td>
                    <td>{{$data['jjg_grading']}}</td>
                    <td>{{$data['tonase']}}</td>
                    <td>{{round($data['bjr'],2)}}</td>
                    <td>{{$data['ripeness']}}</td>
                    <td>{{$data['percentage_ripeness']}}</td>
                    <td>{{$data['unripe']}}</td>
                    <td>{{$data['percentage_unripe']}}</td>
                    <td>{{$data['overripe']}}</td>
                    <td>{{$data['percentage_overripe']}}</td>
                    <td>{{$data['empty_bunch']}}</td>
                    <td>{{$data['percentage_empty_bunch']}}</td>
                    <td>{{$data['rotten_bunch']}}</td>
                    <td>{{$data['percentage_rotten_bunch']}}</td>
                    <td>{{$data['abnormal']}}</td>
                    <td>{{$data['percentage_abnormal']}}</td>
                    <td>{{$data['longstalk']}}</td>
                    <td>{{$data['percentage_longstalk']}}</td>
                    <td>{{$data['vcut']}}</td>
                    <td>{{$data['percentage_vcut']}}</td>
                    <td>{{$data['dirt']}}</td>
                    <td>{{$data['percentage_dirt']}}</td>
                    <td>{{$data['loose_fruit']}}</td>
                    <td>{{$data['percentage_loose_fruit']}}</td>
                    <td>{{$data['kelas_c']}}</td>
                    <td>{{$data['percentage_kelas_c']}}</td>
                    <td>{{$data['kelas_b']}}</td>
                    <td>{{$data['percentage_kelas_b']}}</td>
                    <td>{{$data['kelas_a']}}</td>
                    <td>{{$data['percentage_kelas_a']}}</td>
                    <td>{{$data['unripe_tanpa_brondol']}}</td>
                    <td>{{$data['persentase_unripe_tanpa_brondol']}}</td>
                    <td>{{$data['unripe_kurang_brondol']}}</td>
                    <td>{{$data['persentase_unripe_kurang_brondol']}}</td>
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
            let currentModal = null;
            let lastFocusedElement = null;

            // Show modal with proper focus management
            Livewire.on('show-modal', () => {
                const modalElement = document.getElementById('detailModal');
                lastFocusedElement = document.activeElement;

                currentModal = new bootstrap.Modal(modalElement);
                currentModal.show();

                // Set focus to first focusable element in modal
                const firstFocusable = modalElement.querySelector('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
                if (firstFocusable) {
                    firstFocusable.focus();
                }
            });

            // Close modal with focus restoration
            Livewire.on('closeModal', () => {
                if (currentModal) {
                    currentModal.hide();
                    if (lastFocusedElement) {
                        lastFocusedElement.focus();
                    }
                    currentModal = null;
                    lastFocusedElement = null;
                }
            });

            // Handle modal close via escape key
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && currentModal) {
                    currentModal.hide();
                    if (lastFocusedElement) {
                        lastFocusedElement.focus();
                    }
                    currentModal = null;
                    lastFocusedElement = null;
                }
            });

            // Handle session flash messages
            Livewire.on('flashMessage', (message) => {
                if (message.type === 'error') {
                    if (currentModal) {
                        currentModal.hide();
                        currentModal = null;
                    }
                }
            });

            // Handle resend confirmation
            Livewire.on('confirm-resend', () => {
                Swal.fire({
                    title: 'Peringatan!',
                    html: `
                        <p>Mohon perhatikan:</p>
                        <ul style="text-align: left;">
                            <li>Anda harus menunggu 10 menit sebelum mengirim ulang untuk menghindari pesan duplikat</li>
                            <li>Jika bot masih belum terkirim setelah 10 menit, silakan hubungi tim DA Support di grup WhatsApp</li>
                        </ul>
                        <p>Apakah Anda ingin melanjutkan pengiriman ulang?</p>
                    `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, kirim ulang',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('perform-resend');
                    }
                });
            });

            // Handle modal close button
            document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(button => {
                button.addEventListener('click', () => {
                    if (currentModal) {
                        currentModal.hide();
                        currentModal = null;
                    }
                });
            });

            // Handle modal backdrop click
            document.getElementById('detailModal').addEventListener('click', (e) => {
                if (e.target.id === 'detailModal') {
                    if (currentModal) {
                        currentModal.hide();
                        currentModal = null;
                    }
                }
            });
        });
    </script>
    <!-- root liveware  -->
</div>