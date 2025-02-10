<div>

    <style>
        .loading-spinner {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- Notifikasi -->
            @if (session()->has('message'))
            <div class="alert alert-{{ session('type') }} alert-dismissible fade show mb-3" role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pemindai Data Duplikat</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tipe Pemindaian</label>
                                <select wire:model.live="scanType" class="form-control">
                                    <option value="today">Hari Ini</option>
                                    <option value="range">Rentang Tanggal</option>
                                </select>
                            </div>
                        </div>

                        @if($scanType === 'range')
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tanggal Mulai</label>
                                <input type="date" wire:model="startDate" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tanggal Selesai</label>
                                <input type="date" wire:model="endDate" class="form-control">
                            </div>
                        </div>
                        @endif
                    </div>

                    <button wire:click="scanDuplicates" class="btn btn-primary mb-4" wire:loading.attr="disabled">
                        <span wire:loading wire:target="scanDuplicates">
                            <span class="loading-spinner"></span>
                            Memindai...
                        </span>
                        <span wire:loading.remove wire:target="scanDuplicates">
                            Mulai Pemindaian
                        </span>
                    </button>

                    @if(!empty($duplicateData))
                    <div class="accordion mb-3" id="duplicateAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#duplicateCollapse">
                                    <i class="bi bi-exclamation-triangle text-danger me-2"></i> Data Duplikat Ditemukan!
                                </button>
                            </h2>
                            <div id="duplicateCollapse" class="accordion-collapse collapse show" data-bs-parent="#duplicateAccordion">
                                <div class="accordion-body">
                                    @foreach($duplicateData as $type => $duplicates)
                                    <h6 class="mt-2">{{ $type }}</h6>
                                    <div class="card-container" style="max-height: 400px; overflow-y: auto;">
                                        <div class="d-flex flex-nowrap overflow-auto" style="gap: 10px; padding-bottom: 10px;">
                                            @foreach($duplicates as $index => $duplicate)
                                            <div class="card" style="min-width: 350px; max-width: 350px;">
                                                <div class="card-body p-2">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <h6 class="mb-0">Grup {{ $loop->iteration }} - {{ count($duplicate) }} data identik</h6>
                                                        <div>
                                                            <button type="button" class="btn btn-info btn-sm me-2"
                                                                wire:click="showGroupDetail('{{ $type }}', {{ json_encode(collect($duplicate)->pluck('id')) }})"
                                                                wire:loading.attr="disabled">
                                                                <span wire:loading wire:target="showGroupDetail('{{ $type }}', {{ json_encode(collect($duplicate)->pluck('id')) }})">
                                                                    <span class="loading-spinner"></span>
                                                                    Loading...
                                                                </span>
                                                                <span wire:loading.remove wire:target="showGroupDetail('{{ $type }}', {{ json_encode(collect($duplicate)->pluck('id')) }})">
                                                                    <i class="bi bi-search"></i> Detail
                                                                </span>
                                                            </button>
                                                            <button x-data
                                                                @click.prevent="if (confirm('Yakin ingin menghapus semua data duplikat ini?')) { $wire.deleteAllDuplicates('{{ $type }}', {{ json_encode(collect($duplicate)->pluck('id')) }}) }"
                                                                class="btn btn-danger btn-sm"
                                                                wire:loading.attr="disabled">
                                                                <span wire:loading wire:target="deleteAllDuplicates('{{ $type }}', {{ json_encode(collect($duplicate)->pluck('id')) }})">
                                                                    <span class="loading-spinner"></span>
                                                                    Menghapus...
                                                                </span>
                                                                <span wire:loading.remove wire:target="deleteAllDuplicates('{{ $type }}', {{ json_encode(collect($duplicate)->pluck('id')) }})">
                                                                    <i class="bi bi-trash"></i> Hapus
                                                                </span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="table-responsive" style="max-height: 150px; overflow-y: auto;">
                                                        <table class="table table-sm table-bordered mb-0">
                                                            <thead style="position: sticky; top: 0; background: white; z-index: 1;">
                                                                <tr>
                                                                    <th>Waktu</th>
                                                                    <th>Estate</th>
                                                                    <th>Afdeling</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($duplicate as $record)
                                                                <tr>
                                                                    <td>{{ $record['datetime'] }}</td>
                                                                    <td>{{ $record['est'] ?? $record['estate'] ?? $record['unit'] }}</td>
                                                                    <td>{{ $record['afdeling']  ?? $record['afd'] ?? '-' }}</td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(!empty($indicationData))
                    <div class="accordion" id="indicationAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#indicationCollapse">
                                    <i class="bi bi-exclamation-circle text-warning me-2"></i> Data Terindikasi Duplikat!
                                </button>
                            </h2>
                            <div id="indicationCollapse" class="accordion-collapse collapse show" data-bs-parent="#indicationAccordion">
                                <div class="accordion-body">
                                    <p class="small text-muted">(Data sama tetapi berbeda waktu/lokasi), Harap Cek dahulu sebelum menghapus, data dihapus tidak dapat dikembalikan!!</p>
                                    @foreach($indicationData as $type => $duplicates)
                                    <h6 class="mt-2">{{ $type }}</h6>
                                    <div class="card-container" style="max-height: 400px; overflow-y: auto;">
                                        <div class="d-flex flex-nowrap overflow-auto" style="gap: 10px; padding-bottom: 10px;">
                                            @foreach($duplicates as $index => $duplicate)
                                            <div class="card" style="min-width: 350px; max-width: 350px;">
                                                <div class="card-body p-2">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <h6 class="mb-0">Grup {{ $loop->iteration }} - {{ count($duplicate) }} data serupa</h6>
                                                        <button type="button" class="btn btn-info btn-sm"
                                                            wire:click="showGroupDetail('{{ $type }}', {{ json_encode(collect($duplicate)->pluck('id')) }})"
                                                            wire:loading.attr="disabled">
                                                            <span wire:loading wire:target="showGroupDetail('{{ $type }}', {{ json_encode(collect($duplicate)->pluck('id')) }})">
                                                                <span class="loading-spinner"></span>
                                                                Loading...
                                                            </span>
                                                            <span wire:loading.remove wire:target="showGroupDetail('{{ $type }}', {{ json_encode(collect($duplicate)->pluck('id')) }})">
                                                                <i class="bi bi-search"></i> Detail
                                                            </span>
                                                        </button>
                                                    </div>
                                                    <div class="table-responsive" style="max-height: 150px; overflow-y: auto;">
                                                        <table class="table table-sm table-bordered mb-0">
                                                            <thead style="position: sticky; top: 0; background: white; z-index: 1;">
                                                                <tr>
                                                                    <th>Waktu</th>
                                                                    <th>Estate</th>
                                                                    <th>Afdeling</th>
                                                                    <th>Aksi</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($duplicate as $record)
                                                                <tr>
                                                                    <td>{{ $record['datetime'] }}</td>
                                                                    <td>{{ $record['est'] ?? $record['estate'] ?? $record['unit'] }}</td>
                                                                    <td>{{ $record['afdeling']  ?? $record['afd'] ?? '-' }}</td>
                                                                    <td>
                                                                        <button x-data
                                                                            @click.prevent="if (confirm('Yakin ingin menghapus data ini?')) { $wire.deleteSingleRecord('{{ $type }}', {{ $record['id'] }}) }"
                                                                            class="btn btn-danger btn-sm"
                                                                            wire:loading.attr="disabled">
                                                                            <span wire:loading wire:target="deleteSingleRecord('{{ $type }}', {{ $record['id'] }})">
                                                                                <span class="loading-spinner"></span>
                                                                                Menghapus...
                                                                            </span>
                                                                            <span wire:loading.remove wire:target="deleteSingleRecord('{{ $type }}', {{ $record['id'] }})">
                                                                                <i class="bi bi-trash"></i> Hapus
                                                                            </span>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($isScanning)
    <div class="position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center"
        style="background: rgba(0,0,0,0.5); z-index: 1050;">
        <div class="card p-4">
            <div class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Memuat...</span>
                </div>
                <h5 class="mt-2">Sedang memindai data duplikat...</h5>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Detail Grup -->
    @if($detailRecords)
    <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Grup Data</h5>
                    <button type="button" class="btn-close" wire:click="closeDetail" wire:loading.attr="disabled"></button>
                </div>
                <div class="modal-body">
                    <!-- Loading indicator yang lebih baik -->
                    <div wire:loading wire:target="showGroupDetail" class="text-center py-4">
                        <div class="d-flex justify-content-center align-items-center gap-2">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Mohon Tunggu...</span>
                            </div>
                            <span class="h6 mb-0">Memuat data...</span>
                        </div>
                    </div>

                    <!-- Content -->
                    <div wire:loading.remove wire:target="showGroupDetail">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead class="table-light">
                                    <tr>
                                        @foreach(array_keys(array_diff_key($detailRecords[0], array_flip(['id', 'created_at', 'updated_at']))) as $header)
                                        <th>{{ ucwords(str_replace('_', ' ', $header)) }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($detailRecords as $record)
                                    <tr>
                                        @foreach(array_diff_key($record, array_flip(['id', 'created_at', 'updated_at'])) as $value)
                                        <td>{{ $value }}</td>
                                        @endforeach
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        wire:click="closeDetail"
                        wire:loading.attr="disabled">
                        <span wire:loading wire:target="closeDetail">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Loading...
                        </span>
                        <span wire:loading.remove wire:target="closeDetail">
                            <i class="bi bi-x-lg"></i> Tutup
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif


</div>