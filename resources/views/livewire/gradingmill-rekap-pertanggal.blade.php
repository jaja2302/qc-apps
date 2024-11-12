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
                <label class="me-2">Search Mill:</label>
                <input type="search" class="form-control form-control-sm" id="searchMill" placeholder="Search Mill...">
            </div>
            <div class="d-flex align-items-center">
                <label class="me-2">Search Estate:</label>
                <input type="search" class="form-control form-control-sm" id="searchEstate" placeholder="Search Estate...">
            </div>
            <div class="d-flex align-items-center">
                <label class="me-2">Search Afdeling:</label>
                <input type="search" class="form-control form-control-sm" id="searchAfdeling" placeholder="Search Afdeling...">
            </div>
            <div class="d-flex align-items-center">
                <label class="me-2">Filter Data Type:</label>
                <select class="form-select form-select-sm" id="filterDataType">
                    <option value="all">Show All</option>
                    <option value="block_data">Block Data Only</option>
                    <option value="afdeling_total">Afdeling Total Only</option>
                    <option value="estate_total">Estate Total Only</option>
                    <option value="date_total">Date Total Only</option>
                </select>
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

        /* Updated table styles */
        #gradingTable {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
        }

        #gradingTable th,
        #gradingTable td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        #gradingTable thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
            background-color: #f8f9fa;
            color: #495057;
            font-weight: bold;
        }

        #gradingTable tbody tr:hover {
            background-color: #f1f3f5;
        }

        #gradingTable tbody tr.table-warning {
            background-color: #fff3cd;
        }

        #gradingTable tbody tr.table-primary {
            background-color: #cce5ff;
        }

        #gradingTable tbody tr.table-danger {
            background-color: #f8d7da;
        }

        .pagination .page-link {
            color: #007bff;
        }

        .pagination .page-link:hover {
            color: #0056b3;
        }

        .pagination .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
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
        let filteredData = [];
        let currentPage = 1;
        const rowsPerPage = 25;

        document.addEventListener('livewire:initialized', () => {
            initializeTable();

            @this.on('dataUpdated', (event) => {
                tableData = event.data;
                filteredData = [...tableData];
                renderTable();
            });
        });

        function renderTable() {
            const tbody = document.createElement('tbody');
            const startIndex = (currentPage - 1) * rowsPerPage;
            const endIndex = Math.min(startIndex + rowsPerPage, filteredData.length);

            for (let i = startIndex; i < endIndex; i++) {
                const row = document.createElement('tr');
                const item = filteredData[i];

                if (item.type === 'afdeling_total') {
                    row.classList.add('table-warning', 'fw-bold');
                } else if (item.type === 'estate_total') {
                    row.classList.add('table-primary', 'text-white', 'fw-bold');
                } else if (item.type === 'date_total') {
                    row.classList.add('table-danger', 'text-white', 'fw-bold');
                }

                columns.forEach(column => {
                    const cell = document.createElement('td');
                    const value = item[column];

                    if (column.startsWith('percentage_')) {
                        cell.textContent = value ? Number(value).toFixed(2) : '0.00';
                    } else {
                        cell.textContent = value || '-';
                    }
                    row.appendChild(cell);
                });

                tbody.appendChild(row);
            }

            const table = document.getElementById('gradingTable');
            const existingTbody = table.querySelector('tbody');
            if (existingTbody) {
                existingTbody.remove();
            }
            table.appendChild(tbody);

            document.getElementById('startIndex').textContent = startIndex + 1;
            document.getElementById('endIndex').textContent = endIndex;
            document.getElementById('totalEntries').textContent = filteredData.length;

            document.getElementById('prevPage').disabled = currentPage === 1;
            document.getElementById('nextPage').disabled = endIndex >= filteredData.length;
        }

        function initializeTable() {
            document.getElementById('searchMill').addEventListener('input', handleSearch);
            document.getElementById('searchEstate').addEventListener('input', handleSearch);
            document.getElementById('searchAfdeling').addEventListener('input', handleSearch);
            document.getElementById('filterDataType').addEventListener('change', handleSearch);

            document.getElementById('entriesPerPage').addEventListener('change', (e) => {
                rowsPerPage = parseInt(e.target.value);
                currentPage = 1;
                renderTable();
            });

            document.getElementById('prevPage').addEventListener('click', () => {
                if (currentPage > 1) {
                    currentPage--;
                    renderTable();
                }
            });

            document.getElementById('nextPage').addEventListener('click', () => {
                const maxPages = Math.ceil(filteredData.length / rowsPerPage);
                if (currentPage < maxPages) {
                    currentPage++;
                    renderTable();
                }
            });
        }

        function handleSearch() {
            const millSearch = document.getElementById('searchMill').value.toLowerCase();
            const estateSearch = document.getElementById('searchEstate').value.toLowerCase();
            const afdelingSearch = document.getElementById('searchAfdeling').value.toLowerCase();
            const dataTypeFilter = document.getElementById('filterDataType').value;

            filteredData = tableData.filter(item => {
                const matchMill = item.mill?.toLowerCase().includes(millSearch);
                const matchEstate = item.estate?.toLowerCase().includes(estateSearch);
                const matchAfdeling = item.afdeling?.toLowerCase().includes(afdelingSearch);

                let matchDataType = true;
                switch (dataTypeFilter) {
                    case 'block_data':
                        matchDataType = item.type !== 'afdeling_total' && item.type !== 'estate_total' && item.type !== 'date_total';
                        break;
                    case 'afdeling_total':
                        matchDataType = item.type === 'afdeling_total';
                        break;
                    case 'estate_total':
                        matchDataType = item.type === 'estate_total';
                        break;
                    case 'date_total':
                        matchDataType = item.type === 'date_total';
                        break;
                    default:
                        matchDataType = true;
                }

                return matchMill && matchEstate && matchAfdeling && matchDataType;
            });

            currentPage = 1;
            renderTable();
        }
    </script>
</div>