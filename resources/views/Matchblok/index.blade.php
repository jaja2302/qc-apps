<x-layout.app>
    <style>
        .card-header {
            background-color: #007bff;
            color: #fff;
        }

        .table-responsive {
            margin-bottom: 2rem;
        }

        .table thead {
            background-color: #f8f9fa;
        }

        .form-label {
            font-weight: 600;
        }

        .mb-4 {
            margin-bottom: 1.5rem;
        }

        .text-center {
            text-align: center;
        }

        h1,
        h2,
        h5 {
            font-weight: 600;
        }
    </style>

    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Perbaikan Blok Berbeda dengan Database</h5>
            <button class="btn btn-light btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapseInstructions" aria-expanded="false" aria-controls="collapseInstructions">
                <i class="bi bi-info-circle"></i> Petunjuk
            </button>
        </div>

        <div class="collapse" id="collapseInstructions">
            <div class="card-body bg-light">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item bg-transparent">Harap Perhatikan untuk Mengubah Blok Sidak Semirip Mungkin dengan Blok yang Ada di Database Kami</li>
                    <li class="list-group-item bg-transparent">Harap untuk Merefresh Halaman Jika Sudah Selesai Mengedit Semua untuk Menerapkan Perubahan</li>
                    <li class="list-group-item bg-transparent">Jika di temukan ada blok sidak yang namanya sama atau blok sidak yang terdouble namanya, silahkan sampaikan ke kami untuk pengecekan</li>
                    <li class="list-group-item bg-transparent">Untuk Pertanyaan lebih lanjut terkait silahkan hubungi tim kami.</li>
                </ul>
            </div>
        </div>

        <div class="card-body">
            <div class="row g-3 mb-4">
                <div class="col-md-6 col-lg-4">
                    <label for="regDataMap" class="form-label">Pilih REG:</label>
                    <select class="form-select" id="regDataMap">
                        <option value="" disabled>Pilih REG</option>
                        <option value="1,2,3" selected>Region 1</option>
                        <option value="4,5,6">Region 2</option>
                        <option value="7,8">Region 3</option>
                        <option value="10,11">Region 4</option>
                    </select>
                </div>
                <div class="col-md-6 col-lg-4">
                    <label for="estDataMap" class="form-label">Pilih EST:</label>
                    <select class="form-select" id="estDataMap" disabled>
                        <option value="" disabled>Pilih EST</option>
                    </select>
                </div>
                <div class="col-md-12 col-lg-4 d-flex align-items-end">
                    <button type="button" class="btn btn-primary w-100" id="showEstMap">Show</button>
                </div>
            </div>

            <h2 class="text-center mb-4">Perbedaan Data Perblok</h2>

            <div class="table-responsive mb-4">
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Est</th>
                            <th>Afdeling</th>
                            <th>Blok Sidak</th>
                            <th>Blok Database</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="tbody1"></tbody>
                </table>
            </div>

            <div class="table-responsive mb-4">
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Est</th>
                            <th>Afdeling</th>
                            <th>Blok Sidak</th>
                            <th>Blok Database</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="tbody2"></tbody>
                </table>
            </div>

            <div class="table-responsive mb-4">
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Est</th>
                            <th>Afdeling</th>
                            <th>Blok Sidak</th>
                            <th>Blok Database</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="tbody3"></tbody>
                </table>
            </div>

            <h1 class="text-center mb-4">Table Untuk Detail perblok dalam satu tahun</h1>
            <div class="row g-3 mb-4">
                <div class="col-md-6 col-lg-4">
                    <label for="key-filter" class="form-label">Filter by Blok Group:</label>
                    <select id="key-filter" class="form-select">
                        <option value="">All</option>
                    </select>
                </div>
            </div>

            <h2 class="text-center mb-4">Table mutu ancak</h2>
            <div class="table-responsive mb-5">
                <table class="table table-striped table-hover" id="mutuancak">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Estate</th>
                            <th>Afdeling</th>
                            <th>Blok</th>
                            <th>Petugas</th>
                            <th>Datetime</th>
                            <th>Lat Awal</th>
                            <th>Lon Awal</th>
                            <th>Lat Akhir</th>
                            <th>Lon Akhir</th>
                            <th>SPH</th>
                            <th>Luas Blok</th>
                            <th>BR1</th>
                            <th>BR2</th>
                            <th>Jalur Masuk</th>
                            <th>Status Panen</th>
                            <th>Kemandoran</th>
                            <th>Ancak Pemanen</th>
                            <th>Sample</th>
                            <th>Pokok Kuning</th>
                            <th>Piringan Semak</th>
                            <th>Underpruning</th>
                            <th>Overpruning</th>
                            <th>JJG</th>
                            <th>BRTP</th>
                            <th>BRTK</th>
                            <th>BRTGL</th>
                            <th>BHTS</th>
                            <th>BHTM1</th>
                            <th>BHTM2</th>
                            <th>BHTM3</th>
                            <th>PS</th>
                            <th>SP</th>
                            <th>Pokok Panen</th>
                            <th>App Version</th>
                            <th>Jenis Input</th>
                            <th>Skor Akhir</th>
                            <th>Wil</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Table rows will be populated dynamically -->
                    </tbody>
                </table>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6 col-lg-4">
                    <label for="key-filtertrans" class="form-label">Filter by Blok Group:</label>
                    <select id="key-filtertrans" class="form-select">
                        <option value="">All</option>
                        <!-- Options will be populated dynamically -->
                    </select>
                </div>
            </div>

            <h2 class="text-center mb-4">Table mutu Transport</h2>
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="Transport">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Estate</th>
                            <th>Afdeling</th>
                            <th>Blok</th>
                            <th>Status Panen</th>
                            <th>Luas Blok</th>
                            <th>BT</th>
                            <th>RST</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Table rows will be populated dynamically -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Row</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editFormModal">
                    <div class="modal-body">
                        <input type="hidden" id="edit_row_id">
                        <div class="mb-3">
                            <label for="edit_estate" class="form-label">Estate</label>
                            <input type="text" class="form-control" id="edit_estate" name="estate" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="edit_afdeling" class="form-label">Afdeling</label>
                            <input type="text" class="form-control" id="edit_afdeling" name="afdeling" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="edit_blok_asli" class="form-label">Blok Sidak</label>
                            <input type="text" class="form-control" id="edit_blok_asli" name="blok_asli" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="edit_blok_database" class="form-label">Blok Database</label>
                            <input type="text" class="form-control" id="edit_blok_database" name="blok_database" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="edit_similar_blok" class="form-label">Ubah Blok Database</label>
                            <label for="edit_similar_blok" class="form-label">Pastikan Nama Blok Sidak dan Blok Database semirip mungkin</label>
                            <select class="form-select" id="edit_similar_blok" name="similar_blok">
                                <!-- Options will be populated dynamically -->
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="submitItems">Save changes</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script type="module">
        const estDataMapSelect = document.querySelector('#estDataMap');
        const regDataMapSelect = document.querySelector('#regDataMap');
        const buttonimg = document.getElementById('downloadimgmap');
        // Add an event listener to the select element
        // Add an event listener to the select element
        function fetchEstates(region) {
            var _token = $('input[name="_token"]').val();

            // Fetch the estates for the selected region and update the estate filter
            $.ajax({
                url: "{{ route('fetchEstatesByRegion') }}",
                method: "POST",
                data: {
                    region: region,
                    _token: _token
                },
                success: function(result) {
                    // Update the estate filter with the fetched estates
                    estDataMapSelect.innerHTML = '<option value="" disabled>Pilih EST</option>';
                    result.estates.forEach(function(estate) {
                        const option = document.createElement('option');
                        option.value = estate;
                        option.textContent = estate;
                        estDataMapSelect.appendChild(option);
                    });
                    estDataMapSelect.disabled = false;
                }
            });
        }
        regDataMapSelect.addEventListener('change', function() {
            // Get the selected option value
            const selectedOptionValue = this.value;

            // Fetch the estates for the selected region
            fetchEstates(selectedOptionValue);
        });


        $(document).ready(function() {


            $('#submitItems').click(function() {
                var items = [];
                var _token = $('input[name="_token"]').val();
                var estate = $('#edit_estate').val(); // Correctly select by ID
                var afdeling = $('#edit_afdeling').val(); // Correctly select by ID
                var blokasli = $('#edit_blok_asli').val(); // Correctly select by ID
                var blok = $('#edit_similar_blok').val(); // Correctly select by ID

                if (estate && afdeling && blokasli && blok) {
                    items.push({
                        estate: estate,
                        afdeling: afdeling,
                        blokasli: blokasli,
                        blok: blok,
                        user: "{{ auth()->user()->user_id }}",
                    });
                }
                // console.log(items);
                if (items.length > 0) {
                    $.ajax({
                        url: "{{ route('addmatchblok') }}",
                        method: "POST",
                        data: {
                            _token: _token,
                            items: items,
                        },
                        success: function(response) {
                            // console.log(response);
                            if (response.success) {
                                // Clear the form or do any other success action
                                // location.reload();
                            } else {
                                // Show alert for error message
                                alert("Error: " + response.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            // Show alert for AJAX error
                            alert("AJAX request failed: " + status + " - " + error);
                        }
                    });
                }
            });


            $('#showEstMap').click(function() {
                Swal.fire({
                    title: 'Loading',
                    html: '<span class="loading-text">Mohon Tunggu...</span>',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });
                getPlotBlok();
            });

        });


        function getPlotBlok() {
            $('#tbody1').empty();
            $('#tbody2').empty();
            $('#tbody3').empty();
            var _token = $('input[name="_token"]').val();
            var estData = $("#estDataMap").val();
            var regData = $("#regDataMap").val();
            var date = new Date().getFullYear(); // Get the current year

            $.ajax({
                url: "{{ route('tabledatamaps') }}",
                method: "get",
                data: {
                    est: estData,
                    regData: regData,
                    date: date,
                    _token: _token
                },
                success: function(result) {
                    Swal.close();
                    let datatable = result['table'];
                    let masterblok = result['master_blok'];
                    var collect = datatable['collect'];
                    var not_match = datatable['not_match'];
                    var fix_no_coordintes = datatable['fix_no_coordintes'];

                    let tbody1 = document.getElementById('tbody1');
                    let tbody2 = document.getElementById('tbody2');
                    let tbody3 = document.getElementById('tbody3');
                    let editModal = new bootstrap.Modal(document.getElementById('editModal')); // Initialize Bootstrap modal

                    function createTableRow(key, item, tbody) {
                        let item1 = item['estate'];
                        let item2 = item['afdeling'];
                        let item3 = item['blok_asli'];
                        let item4 = item['similar_blok'];

                        var tr = document.createElement('tr');
                        tr.setAttribute('data-row-id', key);
                        let itemElement1 = document.createElement('td');
                        let itemElement2 = document.createElement('td');
                        let itemElement3 = document.createElement('td');
                        let itemElement4 = document.createElement('td');
                        let editButton = document.createElement('button');

                        itemElement1.classList.add("text-center");
                        itemElement1.innerText = item1;
                        itemElement2.innerText = item2;
                        itemElement3.innerText = item3;
                        itemElement4.innerText = item4;

                        editButton.innerText = 'Edit';
                        editButton.classList.add('btn', 'btn-primary', 'btn-sm');

                        editButton.addEventListener('click', function() {
                            document.getElementById('edit_row_id').value = key;
                            document.getElementById('edit_estate').value = item1;
                            document.getElementById('edit_afdeling').value = item2;
                            document.getElementById('edit_blok_asli').value = item3;
                            document.getElementById('edit_blok_database').value = item4;

                            let selectOptions = document.getElementById('edit_similar_blok');
                            selectOptions.innerHTML = '';

                            Object.keys(masterblok).forEach((blokKey) => {
                                if (masterblok[blokKey].afd === item2) {
                                    let option = document.createElement('option');
                                    option.value = blokKey;
                                    option.innerText = masterblok[blokKey].blok;
                                    selectOptions.appendChild(option);
                                }
                            });

                            editModal.show();
                        });

                        tr.appendChild(itemElement1);
                        tr.appendChild(itemElement2);
                        tr.appendChild(itemElement3);
                        tr.appendChild(itemElement4);
                        tr.appendChild(editButton);

                        tbody.appendChild(tr);
                    }

                    Object.keys(collect).forEach((key) => {
                        let items = collect[key];
                        if (!Array.isArray(items)) {
                            items = Object.values(items); // Convert object to array
                        }
                        items.forEach((item, index) => {
                            createTableRow(key + '-' + index, item, tbody1); // Add unique identifier
                        });
                    });

                    Object.keys(not_match).forEach((key) => {
                        let item = not_match[key];
                        createTableRow('not-match-' + key, item, tbody2); // Add unique identifier
                    });

                    function updateRowContent(row, editedData) {
                        row.children[0].innerText = editedData.estate;
                        row.children[1].innerText = editedData.afdeling;
                        row.children[2].innerText = editedData.blokAsli;
                        row.children[3].innerText = editedData.similarBlok;

                        // Highlight edited cells
                        for (let i = 0; i < 4; i++) {
                            row.children[i].classList.add('highlight');
                        }
                    }

                    // Handle form submission for editing
                    document.getElementById('editFormModal').addEventListener('submit', function(event) {
                        event.preventDefault();

                        let rowId = document.getElementById('edit_row_id').value;
                        let editedData = {
                            estate: document.getElementById('edit_estate').value,
                            afdeling: document.getElementById('edit_afdeling').value,
                            blokAsli: document.getElementById('edit_blok_asli').value,
                            similarBlok: document.getElementById('edit_similar_blok').value
                        };

                        let row = document.querySelector(`tr[data-row-id="${rowId}"]`);
                        if (row) {
                            updateRowContent(row, editedData);
                        } else {
                            console.error('Row not found');
                        }

                        editModal.hide();
                    });


                    let data_ancak_bydate = result['data_ancak_bydate'];
                    let data_trans_bydate = result['data_trans_bydate'];
                    datatableblok(data_ancak_bydate, 'mutuancak', 'key-filter')
                    datatableblok(data_trans_bydate, 'Transport', 'key-filtertrans')

                    console.log(data_trans_bydate);

                },
                error: function(xhr, status, error) {
                    // Handle error situations if needed
                    console.error(xhr.responseText);
                }
            });
        }

        function datatableblok(sourcedata, tbody, filter) {
            var combinedData = [];
            var keyOptions = new Set();

            // Collect unique options and combine data
            $.each(sourcedata, function(key, value) {
                combinedData = combinedData.concat(value);
                keyOptions.add(key);
            });

            // Convert Set to array and sort in descending order
            var sortedKeyOptions = Array.from(keyOptions).sort((a, b) => b.localeCompare(a));

            // Populate dropdown with sorted key options
            var $keyFilter = $(`#${filter}`);
            sortedKeyOptions.forEach(function(key) {
                $keyFilter.append('<option value="' + key + '">' + key + '</option>');
            });

            // Initialize DataTable

            // Filter table based on selected key
            if (tbody === 'mutuancak') {
                var table = $(`#${tbody}`).DataTable({
                    data: combinedData,
                    columns: [{
                            data: 'id'
                        },
                        {
                            data: 'estate'
                        },
                        {
                            data: 'afdeling'
                        },
                        {
                            data: 'blok'
                        },
                        {
                            data: 'petugas'
                        },
                        {
                            data: 'datetime'
                        },
                        {
                            data: 'lat_awal'
                        },
                        {
                            data: 'lon_awal'
                        },
                        {
                            data: 'lat_akhir'
                        },
                        {
                            data: 'lon_akhir'
                        },
                        {
                            data: 'sph'
                        },
                        {
                            data: 'luas_blok'
                        },
                        {
                            data: 'br1'
                        },
                        {
                            data: 'br2'
                        },
                        {
                            data: 'jalur_masuk'
                        },
                        {
                            data: 'status_panen'
                        },
                        {
                            data: 'kemandoran'
                        },
                        {
                            data: 'ancak_pemanen'
                        },
                        {
                            data: 'sample'
                        },
                        {
                            data: 'pokok_kuning'
                        },
                        {
                            data: 'piringan_semak'
                        },
                        {
                            data: 'underpruning'
                        },
                        {
                            data: 'overpruning'
                        },
                        {
                            data: 'jjg'
                        },
                        {
                            data: 'brtp'
                        },
                        {
                            data: 'brtk'
                        },
                        {
                            data: 'brtgl'
                        },
                        {
                            data: 'bhts'
                        },
                        {
                            data: 'bhtm1'
                        },
                        {
                            data: 'bhtm2'
                        },
                        {
                            data: 'bhtm3'
                        },
                        {
                            data: 'ps'
                        },
                        {
                            data: 'sp'
                        },
                        {
                            data: 'pokok_panen'
                        },
                        {
                            data: 'app_version'
                        },
                        {
                            data: 'jenis_input'
                        },
                        {
                            data: 'skor_akhir'
                        },
                        {
                            data: 'wil'
                        },
                        {
                            data: 'date'
                        }
                    ]
                });
            } else {
                var table = $(`#${tbody}`).DataTable({
                    data: combinedData,
                    columns: [{
                            data: 'id'
                        },
                        {
                            data: 'estate'
                        },
                        {
                            data: 'afdeling'
                        },
                        {
                            data: 'blok'
                        },
                        {
                            data: 'status_panen'
                        },
                        {
                            data: 'luas_blok'
                        },
                        {
                            data: 'bt'
                        },
                        {
                            data: 'rst'
                        },
                        {
                            data: 'date'
                        }
                    ]
                });
            }

            $(`#${filter}`).on('change', function() {
                var selectedKey = $(this).val();
                table.clear().rows.add(combinedData.filter(function(item) {
                    return selectedKey === "" || item.blok.includes(selectedKey);
                })).draw();
            });
        }
    </script>
</x-layout.app>