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

            </div>
        </div>
    </div>
    <div class="mt-4 mx-3 mb-10">
        <!-- Modern legend with hover effects -->
        <div class="legend-container mx-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Legend</h5>
                    <div class="d-flex gap-3 flex-wrap">
                        <div class="legend-item">
                            <span class="badge rounded-pill px-4 py-2 legend-badge data-entry">
                                <i class="fas fa-keyboard me-2"></i>Data Entry
                            </span>
                        </div>
                        <div class="legend-item">
                            <span class="badge rounded-pill px-4 py-2 legend-badge estate-summary">
                                <i class="fas fa-chart-bar me-2"></i>Estate Summary
                            </span>
                        </div>
                        <div class="legend-item">
                            <span class="badge rounded-pill px-4 py-2 legend-badge daily-total">
                                <i class="fas fa-calendar-day me-2"></i>Daily Total
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive" wire:ignore>
            <table id="gradingTable" class="table table-bordered table-striped">
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
            </table>
        </div>

    </div>
    <!-- Add this style section -->
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #1e88e5 0%, #1565c0 100%);
        }

        .legend-badge {
            transition: all 0.3s ease;
            cursor: default;
        }

        .legend-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .data-entry {
            background-color: #ffeb3b;
            color: #000;
        }

        .estate-summary {
            background-color: #2196f3;
            color: white;
        }

        .daily-total {
            background-color: #f44336;
            color: white;
        }

        .dataTables_wrapper {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            margin: 0 15px;
        }
    </style>
    <script type="module">
        let dataTable;

        document.addEventListener('livewire:initialized', () => {
            initializeDataTable();

            @this.on('dataUpdated', (event) => {
                if (dataTable) {
                    dataTable.destroy();
                }
                initializeDataTable(event.data);
            });
        });

        function initializeDataTable(data = []) {
            dataTable = $('#gradingTable').DataTable({
                data: data,
                pageLength: 25,
                ordering: true,
                responsive: false,
                scrollX: true,
                scrollY: '500px',
                scrollCollapse: true,
                fixedColumns: {
                    left: 3
                },
                dom: 'Bfrtip',
                // buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
                order: [
                    [0, 'asc']
                ],
                columns: [
                    // UNIT SORTASI columns
                    {
                        data: 'date'
                    },
                    {
                        data: 'estate'
                    },
                    {
                        data: 'afdeling'
                    },
                    {
                        data: 'mill'
                    },
                    {
                        data: 'datetime'
                    },
                    {
                        data: 'no_plat'
                    },
                    {
                        data: 'driver'
                    },
                    {
                        data: 'blok'
                    },
                    {
                        data: 'jjg_spb'
                    },
                    {
                        data: 'jjg_grading'
                    },
                    {
                        data: 'tonase'
                    },
                    {
                        data: 'bjr'
                    },

                    // HASIL GRADING columns
                    {
                        data: 'ripeness'
                    },
                    {
                        data: 'percentage_ripeness'
                    },
                    {
                        data: 'unripe'
                    },
                    {
                        data: 'percentage_unripe'
                    },
                    {
                        data: 'overripe'
                    },
                    {
                        data: 'percentage_overripe'
                    },
                    {
                        data: 'empty'
                    },
                    {
                        data: 'percentage_empty_bunch'
                    },
                    {
                        data: 'rotten'
                    },
                    {
                        data: 'percentage_rotten_bunch'
                    },
                    {
                        data: 'abnormal'
                    },
                    {
                        data: 'percentage_abnormal'
                    },
                    {
                        data: 'tangkai_panjang'
                    },
                    {
                        data: 'percentage_tangkai_panjang'
                    },
                    {
                        data: 'vcuts'
                    },
                    {
                        data: 'percentage_vcut'
                    },
                    {
                        data: 'dirt'
                    },
                    {
                        data: 'percentage_dirt_kg'
                    },
                    {
                        data: 'loose_fruit'
                    },
                    {
                        data: 'percentage_loose_fruit_kg'
                    },

                    // KELAS JANJANG columns
                    {
                        data: 'kelas_c'
                    },
                    {
                        data: 'percentage_kelas_c'
                    },
                    {
                        data: 'kelas_b'
                    },
                    {
                        data: 'percentage_kelas_b'
                    },
                    {
                        data: 'kelas_a'
                    },
                    {
                        data: 'percentage_kelas_a'
                    }
                ],
                columnDefs: [{
                    targets: '_all',
                    defaultContent: '-',
                    render: function(data, type, row, meta) {
                        if (meta.col >= 12) { // For percentage columns
                            return data ? Number(data).toFixed(2) : '0.00';
                        }
                        return data || '-';
                    }
                }],
                createdRow: function(row, data, dataIndex) {
                    if (data.type === 'afdeling_total') {
                        $(row).addClass('table-warning fw-bold');
                    } else if (data.type === 'estate_total') {
                        $(row).addClass('table-primary text-white fw-bold');
                    } else if (data.type === 'date_total') {
                        $(row).addClass('table-danger text-white fw-bold');
                    }
                }
            });

            // Adjust table after initialization
            $(window).on('resize', function() {
                dataTable.columns.adjust();
            });
        }
    </script>
</div>