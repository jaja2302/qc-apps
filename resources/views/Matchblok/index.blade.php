<x-layout.app>
    <div class="card">
        <div class="d-flex justify-content-end mt-3 mb-2 ml-3 mr-3" style="padding-top: 20px;">
            <div class="row w-100">
                <div class="col-md-2 offset-md-8">
                    {{csrf_field()}}
                    <select class="form-control" id="regDataMap">
                        <option value="" disabled>Pilih REG</option>
                        <option value="1,2,3" selected>Region 1</option>
                        <option value="4,5,6">Region 2</option>
                        <option value="7,8">Region 3</option>
                        <option value="10,11">Region 4</option>
                    </select>
                </div>

                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    {{ csrf_field() }}
                    <select class="form-control" id="estDataMap" disabled>
                        <option value="" disabled>Pilih EST</option>
                    </select>

                </div>
            </div>
            <button class="btn btn-primary mb-3 ml-3" id="showEstMap">Show</button>
        </div>
        <div class="table-responsive">
            <h1 class="text-center">Perbedaan Data Perblok Bagian Satu</h1>
            <table class="table table-bordered" style="font-size: 13px;background-color:white">
                <thead>
                    <tr>
                        <td>Est</td>
                        <td>Afdeling </td>
                        <td>Blok Sidak</td>
                        <td>Blok Database</td>
                        <td>Action</td>
                    </tr>
                </thead>
                <tbody id="tbody1">
                </tbody>
            </table>
            <h1 class="text-center">Perbedaan Data Perblok Bagian Dua</h1>
            <table class="table table-bordered" style="font-size: 13px;background-color:white">
                <thead>
                    <tr>
                        <td>Est</td>
                        <td>Afdeling </td>
                        <td>Blok Sidak</td>
                        <td>Blok Database</td>
                        <td>Action</td>
                    </tr>
                </thead>
                <tbody id="tbody2">
                </tbody>
            </table>
            <h1 class="text-center">Perbedaan Data Perblok Bagian Tiga</h1>
            <table class="table table-bordered" style="font-size: 13px;background-color:white">
                <thead>
                    <tr>
                        <td>Est</td>
                        <td>Afdeling </td>
                        <td>Blok Sidak</td>
                        <td>Blok Database</td>
                        <td>Action</td>
                    </tr>
                </thead>
                <tbody id="tbody3">
                </tbody>
            </table>
        </div>

        <!-- <div class="table-responsive">
            <h1 class="text-center">Data Tabel Yang sudah di ubah</h1>
            <table class="table table-bordered" style="font-size: 13px;background-color:white" id="tabblan1">
                <thead>
                    <tr>
                        <td>Est</td>
                        <td>Afdeling </td>
                        <td>Blok Sidak</td>
                        <td>Blok Database</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($table as $item)
                    <tr>
                        <td>{{$item->est}}</td>
                        <td>{{$item->afd}}</td>
                        <td>{{$item->blok_asli}}</td>
                        <td>{{$item->blok}}</td>
                    </tr>

                    @endforeach
                </tbody>
            </table>
        </div> -->

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
                            blok: blok
                        });
                    }
                    console.log(items);
                    if (items.length > 0) {
                        $.ajax({
                            url: "{{ route('addmatchblok') }}",
                            method: "POST",
                            data: {
                                _token: _token,
                                items: items,
                            },
                            success: function(response) {
                                console.log(response);
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
                    getPlotBlok();
                });

            });


            function getPlotBlok() {
                $('#tbody1').empty()
                $('#tbody2').empty()
                $('#tbody3').empty()
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
                        Swal.close(); // Close SweetAlert if it's open
                        // console.log(result);
                        let datatable = result['table'];
                        let masterblok = result['master_blok'];
                        var collect = datatable['collect']
                        var not_match = datatable['not_match']
                        var fix_no_coordintes = datatable['fix_no_coordintes']
                        // console.log(masterblok);

                        let tbody2 = document.getElementById('tbody2');
                        let tbody3 = document.getElementById('tbody3');
                        let editModal = new bootstrap.Modal(document.getElementById('editModal')); // Initialize Bootstrap modal
                        let tbody1 = document.getElementById('tbody1');

                        // untuk perbadeling 

                        Object.keys(not_match).forEach((key) => {
                            let item = not_match[key];

                            let item1 = item['estate']
                            let item2 = item['afdeling'];
                            let item3 = item['blok_asli'];
                            let item4 = item['similar_blok'];

                            // Creating table row and cells
                            var tr = document.createElement('tr');
                            tr.setAttribute('data-row-id', key); // Add unique identifier

                            let itemElement1 = document.createElement('td');
                            let itemElement2 = document.createElement('td');
                            let itemElement3 = document.createElement('td');
                            let itemElement4 = document.createElement('td');
                            let editButton = document.createElement('button'); // Create Edit button

                            itemElement1.classList.add("text-center");
                            itemElement1.innerText = item1;
                            itemElement2.innerText = item2;
                            itemElement3.innerText = item3;
                            itemElement4.innerText = item4;

                            editButton.innerText = 'Edit'; // Button text
                            editButton.classList.add('btn', 'btn-primary', 'btn-sm'); // Bootstrap classes

                            editButton.addEventListener('click', function() {
                                // Populate form fields with row data
                                document.getElementById('edit_row_id').value = key; // Store row index for identification
                                document.getElementById('edit_estate').value = item1;
                                document.getElementById('edit_afdeling').value = item2;
                                document.getElementById('edit_blok_asli').value = item3;
                                document.getElementById('edit_blok_database').value = item4;

                                // Populate select dropdown dynamically based on edit_afdeling
                                let selectOptions = document.getElementById('edit_similar_blok');
                                selectOptions.innerHTML = ''; // Clear existing options

                                // Filter masterblok based on edit_afdeling
                                Object.keys(masterblok).forEach((blokKey) => {
                                    if (masterblok[blokKey].afd === item2) { // Check if afd matches edit_afdeling
                                        let option = document.createElement('option');
                                        option.value = blokKey;
                                        option.innerText = masterblok[blokKey].blok;
                                        selectOptions.appendChild(option);
                                    }
                                });

                                editModal.show(); // Show the modal
                            });

                            tr.appendChild(itemElement1);
                            tr.appendChild(itemElement2);
                            tr.appendChild(itemElement3);
                            tr.appendChild(itemElement4);
                            tr.appendChild(editButton); // Append Edit button to row

                            tbody2.appendChild(tr);
                        });

                        Object.keys(collect).forEach((key) => {
                            collect[key].forEach((item, index) => { // Assuming index is available for unique identification
                                let item1 = item['estate']
                                let item2 = item['afdeling'];
                                let item3 = item['blok_asli'];
                                let item4 = item['similar_blok'];

                                // Creating table row and cells
                                var tr = document.createElement('tr');
                                tr.setAttribute('data-row-id', `${key}-${index}`); // Add unique identifier

                                let itemElement1 = document.createElement('td');
                                let itemElement2 = document.createElement('td');
                                let itemElement3 = document.createElement('td');
                                let itemElement4 = document.createElement('td');
                                let editButton = document.createElement('button'); // Create Edit button

                                itemElement1.classList.add("text-center");
                                itemElement1.innerText = item1;
                                itemElement2.innerText = item2;
                                itemElement3.innerText = item3;
                                itemElement4.innerText = item4;

                                editButton.innerText = 'Edit'; // Button text
                                editButton.classList.add('btn', 'btn-primary', 'btn-sm'); // Bootstrap classes

                                editButton.addEventListener('click', function() {
                                    // Populate form fields with row data
                                    document.getElementById('edit_row_id').value = index; // Store row index for identification
                                    document.getElementById('edit_estate').value = item1;
                                    document.getElementById('edit_afdeling').value = item2;
                                    document.getElementById('edit_blok_asli').value = item3;
                                    document.getElementById('edit_blok_database').value = item4;

                                    // Populate select dropdown dynamically based on edit_afdeling
                                    let selectOptions = document.getElementById('edit_similar_blok');
                                    selectOptions.innerHTML = ''; // Clear existing options

                                    // Filter masterblok based on edit_afdeling
                                    Object.keys(masterblok).forEach((blokKey) => {
                                        if (masterblok[blokKey].afd === item2) { // Check if afd matches edit_afdeling
                                            let option = document.createElement('option');
                                            option.value = blokKey;
                                            option.innerText = masterblok[blokKey].blok;
                                            selectOptions.appendChild(option);
                                        }
                                    });

                                    editModal.show(); // Show the modal
                                });

                                // Append elements to row
                                tr.appendChild(itemElement1);
                                tr.appendChild(itemElement2);
                                tr.appendChild(itemElement3);
                                tr.appendChild(itemElement4);
                                tr.appendChild(editButton); // Append Edit button to row

                                tbody1.appendChild(tr); // Append row to table body
                            });
                        });

                        // Handle form submission for editing
                        document.getElementById('editFormModal').addEventListener('submit', function(event) {
                            event.preventDefault();

                            // Get form values
                            let rowIndex = document.getElementById('edit_row_id').value;
                            let editedEstate = document.getElementById('edit_estate').value;
                            let editedAfdeling = document.getElementById('edit_afdeling').value;
                            let editedBlokAsli = document.getElementById('edit_blok_asli').value;
                            let editedSimilarBlok = document.getElementById('edit_similar_blok').value;

                            // Determine which tbody to update
                            let row = document.querySelector(`tr[data-row-id="${rowIndex}"]`);
                            if (row) {
                                // Update the table cells
                                row.children[0].innerText = editedEstate;
                                row.children[1].innerText = editedAfdeling;
                                row.children[2].innerText = editedBlokAsli;
                                row.children[3].innerText = editedSimilarBlok;

                                // Highlight edited cells
                                row.children[0].classList.add('highlight');
                                row.children[1].classList.add('highlight');
                                row.children[2].classList.add('highlight');
                                row.children[3].classList.add('highlight');
                            } else {
                                console.error('Row not found');
                            }

                            // Show the reminder text
                            let reminder = document.getElementById('reminder');
                            if (!reminder) {
                                reminder = document.createElement('div');
                                reminder.id = 'reminder';
                                reminder.innerText = 'Please reload to see the changes.';
                                document.body.appendChild(reminder);
                            }

                            // Close modal
                            editModal.hide();
                        });

                    },
                    error: function(xhr, status, error) {
                        // Handle error situations if needed
                        console.error(xhr.responseText);
                    }
                });
            }
        </script>

</x-layout.app>