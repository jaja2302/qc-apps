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
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center">
                <label class="me-2">Show entries:</label>
                <select class="form-select form-select-sm" id="entriesPerPage">
                    <option value="10">10</option>
                    <option value="25" selected>25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
            <div class="d-flex align-items-center">
                <label class="me-2">Search:</label>
                <input type="search" class="form-control form-control-sm" id="tableSearch" placeholder="Search...">
            </div>
        </div>
        <div class="table-responsive" style="position: relative;" wire:ignore>
            <div class="table-container">
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
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="datatable-info">
                Showing <span id="startIndex">0</span> to <span id="endIndex">0</span> of <span id="totalEntries">0</span> entries
            </div>
            <div class="datatable-pagination">
                <nav>
                    <ul class="pagination">
                        <li class="page-item">
                            <button class="page-link" id="prevPage">Previous</button>
                        </li>
                        <li class="page-item">
                            <button class="page-link" id="nextPage">Next</button>
                        </li>
                    </ul>
                </nav>
            </div>
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


        .table-container {
            overflow-x: auto;
            position: relative;
        }
    </style>
    <script type="module">
        const columns = [
            'date', 'estate', 'afdeling', 'mill', 'datetime', 'no_plat', 'driver', 'blok',
            'jjg_spb', 'jjg_grading', 'tonase', 'bjr',
            'ripeness', 'percentage_ripeness', 'unripe', 'percentage_unripe',
            'overripe', 'percentage_overripe', 'empty', 'percentage_empty_bunch',
            'rotten', 'percentage_rotten_bunch', 'abnormal', 'percentage_abnormal',
            'tangkai_panjang', 'percentage_tangkai_panjang', 'vcuts', 'percentage_vcut',
            'dirt', 'percentage_dirt_kg', 'loose_fruit', 'percentage_loose_fruit_kg',
            'kelas_c', 'percentage_kelas_c', 'kelas_b', 'percentage_kelas_b',
            'kelas_a', 'percentage_kelas_a'
        ];

        let tableData = [];
        let currentPage = 1;
        const rowsPerPage = 25;
        let sortColumn = 0;
        let sortDirection = 'asc';

        document.addEventListener('livewire:initialized', () => {
            initializeTable();

            @this.on('dataUpdated', (event) => {
                tableData = event.data;
                renderTable();
            });
        });

        function renderTable() {
            const tbody = document.createElement('tbody');
            const startIndex = (currentPage - 1) * rowsPerPage;
            const endIndex = Math.min(startIndex + rowsPerPage, tableData.length);

            // Sort data
            const sortedData = [...tableData].sort((a, b) => {
                const aVal = a[columns[sortColumn]];
                const bVal = b[columns[sortColumn]];
                return sortDirection === 'asc' ?
                    (aVal > bVal ? 1 : -1) :
                    (aVal < bVal ? 1 : -1);
            });

            // Render rows
            for (let i = startIndex; i < endIndex; i++) {
                const row = document.createElement('tr');
                const item = sortedData[i];

                // Add row classes based on type
                if (item.type === 'afdeling_total') {
                    row.classList.add('table-warning', 'fw-bold');
                } else if (item.type === 'estate_total') {
                    row.classList.add('table-primary', 'text-white', 'fw-bold');
                } else if (item.type === 'date_total') {
                    row.classList.add('table-danger', 'text-white', 'fw-bold');
                }

                // Add cells using the columns array
                columns.forEach(column => {
                    const cell = document.createElement('td');
                    const value = item[column];

                    // Format percentages
                    if (column.startsWith('percentage_')) {
                        cell.textContent = value ? Number(value).toFixed(2) : '0.00';
                    } else {
                        cell.textContent = value || '-';
                    }
                    row.appendChild(cell);
                });

                tbody.appendChild(row);
            }

            // Update table
            const table = document.getElementById('gradingTable');
            const existingTbody = table.querySelector('tbody');
            if (existingTbody) {
                existingTbody.remove();
            }
            table.appendChild(tbody);

            // Update pagination info
            document.getElementById('startIndex').textContent = startIndex + 1;
            document.getElementById('endIndex').textContent = endIndex;
            document.getElementById('totalEntries').textContent = tableData.length;

            // Update pagination buttons
            document.getElementById('prevPage').disabled = currentPage === 1;
            document.getElementById('nextPage').disabled = endIndex >= tableData.length;
        }

        function initializeTable() {
            // Add click handlers for pagination
            document.getElementById('prevPage').addEventListener('click', () => {
                if (currentPage > 1) {
                    currentPage--;
                    renderTable();
                }
            });

            document.getElementById('nextPage').addEventListener('click', () => {
                const maxPages = Math.ceil(tableData.length / rowsPerPage);
                if (currentPage < maxPages) {
                    currentPage++;
                    renderTable();
                }
            });

            // Add click handlers for sorting
            const headers = document.querySelectorAll('#gradingTable th');
            headers.forEach((header, index) => {
                header.style.cursor = 'pointer';
                header.addEventListener('click', () => {
                    if (sortColumn === index) {
                        sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
                    } else {
                        sortColumn = index;
                        sortDirection = 'asc';
                    }
                    renderTable();
                });
            });
        }
    </script>
</div>