<x-layout.app>
    @if (strpos(session('departemen'), 'QC') !== false && session('jabatan') == 'Manager' || session('jabatan') == 'Askep' || session('jabatan') == 'Asisten' || session('jabatan') == 'Admin' || auth()->user()->id_departement == '43' && in_array(auth()->user()->id_jabatan, ['10', '15', '20', '4', '5', '6']))
    <div class="jumbotron">
        <h1 class="display-4">Perhatian</h1>
        <p class="lead">Untuk meningkatkan sistematisasi dan konsistensi sistem, kami mohon agar Anda menggunakan situs web manajemen pengguna yang telah disediakan. Hal ini bertujuan untuk menghindari kesalahan data atau kerusakan.</p>
        <p>Harap dicatat bahwa halaman ini akan dinonaktifkan dalam waktu dekat. Terima kasih atas perhatian dan kerjasamanya.</p>
        <hr class="my-4">
        <p>Kunjungi situs web kami di sini:</p>
        <p class="lead">
            <a class="btn btn-primary btn-lg" href="https://management.srs-ssms.com/" target="_blank" role="button">Pelajari lebih lanjut</a>
        </p>
    </div>


    <div class="container-fluid">

        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <h1 style="text-align: center;">List User QC</h1>
                        <!-- Add the "Tambah User" button with a style and icon -->
                        <button class="btn btn-primary" id="tambahUserBtn">
                            <i class="fas fa-plus"></i> Tambah User
                        </button>
                        <table class="table table-striped table-bordered" id="user_qc">
                            <thead>
                                <!-- Table header content -->
                            </thead>
                            <tbody>
                                <!-- Table body content will be dynamically generated -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <h1 style="text-align: center;">List Manager Estate</h1>
                        <table class="table table-striped table-bordered" id="user_manager">
                            <thead>
                                <!-- Table header content -->
                            </thead>
                            <tbody>
                                <!-- Table body content will be dynamically generated -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <h1 style="text-align: center;">List Asisten Estate</h1>
                        <table class="table table-striped table-bordered" id="user_asisten">
                            <thead>
                                <!-- Table header content -->
                            </thead>
                            <tbody>
                                <!-- Table body content will be dynamically generated -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark">
                        <h5><b>Informasi User QC
                            </b></h5>
                    </div>

                    @if(session('success'))
                    <div class="alert alert-success mt-2">
                        {{ session('success') }}
                    </div>
                    @endif

                    <div class="row mt-3 mb-2 ml-3 mr-3">
                        <div class="col-md-12 col-md-offset-2">
                            <div class="card">
                                <div class="card-header text-center">PROFILE</div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Nama :</label>
                                                <p id="nama_lengkap"></p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Email:</label>
                                                <p id="email"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Departemen:</label>
                                                <p id="departemen"></p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Jabatan:</label>
                                                <p id="jabatan"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Afdeling:</label>
                                                <p id="afdeling"></p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>No HP:</label>
                                                <p id="no_hp"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Other fields -->



                                    @if (session('jabatan') == 'Manager' || session('jabatan') == 'Askep' || session('jabatan') == 'Asisten' || session('jabatan') == 'Admin')
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editProfileModal">
                                        Edit Profile
                                    </button>
                                    <div class="d-flex justify-content-center mt-3 mb-2 border border-dark">
                                        <h5><b>Akses Khusus QC
                                            </b></h5>
                                    </div>

                                    <button class="btn btn-primary mb-2" style="float: right;" data-toggle="modal" data-target="#addDataModal">Tambah Data</button>


                                    <table id="listAsisten" class="table-striped text-center" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>Estate</th>
                                                <th>Afdeling</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($asisten as $value)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $value->nama }}</td>
                                                <td>{{ $value->est }}</td>
                                                <td>{{ $value->afd }}</td>
                                                <td style="display: inline-flex">
                                                    <button class="btn btn-success mr-2" data-toggle="modal" data-target="#updateModal-{{$value->id}}"><i class="bi bi-pencil-square"></i></button>


                                                    <form action="{{ route('deleteAsisten') }}" method="POST">{{ csrf_field() }}
                                                        <input type="hidden" name="id" value="{{ $value->id }}"><button type="submit" class="btn btn-danger" onclick="return confirm('Yakin menghapus data?')"><i class="bi bi-trash"></i></button>
                                                    </form>
                                                </td>

                                                <div class="modal fade" id="updateModal-{{$value->id}}" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <form action="{{ route('updateAsisten') }}" method="POST">
                                                                {{ csrf_field() }}
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="updateModalLabel">Update Asisten</h5>
                                                                    <button type="button" class="close btn btn-warning" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="id" value="{{ $value->id }}">
                                                                    <div class="form-group">
                                                                        <label for="nama">Nama</label>
                                                                        <input type="text" class="form-control" id="nama" name="nama" value="{{ $value->nama }}" required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="est">Estate</label>
                                                                        <input type="text" class="form-control" id="est" name="est" value="{{ $value->est }}" required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="afd">Afdeling</label>
                                                                        <input type="text" class="form-control" id="afd" name="afd" value="{{ $value->afd }}" required>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Add Data Modal -->
                                                <!-- Add Data Modal -->
                                                <div class="modal fade" id="addDataModal" tabindex="-1" role="dialog" aria-labelledby="addDataModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <form action="{{ route('storeAsisten') }}" method="POST">
                                                                {{ csrf_field() }}
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="addDataModalLabel">Tambah Data Asisten</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <label for="nama">Nama</label>
                                                                        <input type="text" class="form-control" id="nama" name="nama" value="" required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="est">Estate</label>
                                                                        <input type="text" class="form-control" id="est" name="est" value="" required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="afd">Afdeling</label>
                                                                        <input type="text" class="form-control" id="afd" name="afd" value="" required>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-primary">Tambah Data</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End of Add Data Modal -->

                                                <!-- End of Add Data Modal -->


                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>




        <div class=" modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form id="editProfileForm" action="{{ route('update_user') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="user_id" class="form-control" id="userqc_id" value="" required>

                            <div class="form-group">
                                <label for="edit-name">Name</label>
                                <input type="text" name="name" class="form-control" id="edit-name" value="" required>
                            </div>
                            <div class="form-group">
                                <label for="edit-email">Email</label>
                                <input type="text" name="email" class="form-control" id="edit-email" value="" required>
                            </div>
                            <div class="form-group">
                                <label for="edit-dept">Departemen</label>
                                <input type="text" name="departemen" class="form-control" id="edit-dept" value="">
                            </div>
                            <div class="form-group">
                                <label for="edit-pass">Password</label>
                                <input type="text" name="pass" class="form-control" id="edit-pass" value="">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>



    <script type="module">
        var currentUserName = "{{ session('jabatan') }}";
        var departemen = "{{ session('departemen') }}";
        $(document).ready(function() {
            // Get the session values
            var user_name = "{{ session('user_name') }}";
            var lok = "{{ session('lok') }}";

            // Prepare the data to send to your controller
            var data = {
                user_name: user_name,
                lok: lok
            };
            if ($.fn.DataTable.isDataTable('#user_qc')) {
                $('#user_qc').DataTable().destroy();
            }
            if ($.fn.DataTable.isDataTable('#user_manager')) {
                $('#user_manager').DataTable().destroy();
            }
            // Send the data to your controller using Ajax
            $.ajax({
                type: "get", // Change to the appropriate HTTP method (GET or POST) if needed
                url: "{{route('listqc')}}", // Replace with the actual URL of your controller
                data: data,
                success: function(response) {

                    var parseResult = JSON.parse(response);
                    var listQC = $('#user_qc').DataTable({
                        columns: [{
                                title: 'ID',
                                data: 'user_id',
                            },
                            {
                                title: 'Email',
                                data: 'email',
                            },
                            {
                                title: 'Password',
                                data: 'password',
                            },
                            {
                                title: 'Nama lengkap',
                                data: 'nama_lengkap',
                            },
                            {
                                title: 'Departemen',
                                data: 'departemen',
                            },
                            {
                                title: 'Afdeling ',
                                data: 'afdeling',
                            },
                            {
                                title: 'Nomor Handphone',
                                data: 'no_hp',
                            },
                            {
                                title: 'Lokasi Kerja',
                                data: 'lokasi_kerja',
                            },

                            {
                                title: 'Jabatan',
                                data: 'jabatan',
                            },

                            {
                                // -1 targets the last column
                                title: 'Actions',
                                visible: (currentUserName === 'Askep' || currentUserName === 'Manager' || (currentUserName === 'Admin' && departemen === 'QC')),
                                render: function(data, type, row, meta) {
                                    var buttons =
                                        '<button  class="btn btn-primary"><span><i class="bi bi-pencil"></i></span>Edit</button>' +
                                        '<button class="btn btn-danger"><span><i class="bi bi-trash3"></i></span>Delete</button>';
                                    return buttons;
                                }
                            }
                        ],

                    });

                    // Populate DataTable with data
                    listQC.clear().rows.add(parseResult['list_qc']).draw();
                    var list_emg = $('#user_manager').DataTable({
                        columns: [{
                                title: 'ID',
                                data: 'user_id',
                            },
                            {
                                title: 'Email',
                                data: 'email',
                            },
                            {
                                title: 'Nama lengkap',
                                data: 'nama_lengkap',
                            },
                            {
                                title: 'Departemen',
                                data: 'departemen',
                            },
                            {
                                title: 'Afdeling ',
                                data: 'afdeling',
                            },
                            {
                                title: 'Nomor Handphone',
                                data: 'no_hp',
                            },
                            {
                                title: 'Lokasi Kerja',
                                data: 'lokasi_kerja',
                            },

                            {
                                title: 'Jabatan',
                                data: 'jabatan',
                            },

                            {
                                // -1 targets the last column
                                title: 'Actions',
                                visible: (currentUserName === 'Askep' || currentUserName === 'Manager' || (currentUserName === 'Admin' && departemen === 'QC')),
                                render: function(data, type, row, meta) {
                                    var buttons =
                                        '<button  class="btn btn-primary"><span><i class="bi bi-pencil"></i></span>Edit</button>'
                                    return buttons;
                                }
                            }
                        ],

                    });

                    // Populate DataTable with data
                    list_emg.clear().rows.add(parseResult['list_gm']).draw();


                    $('#user_qc').on('click', '.btn.btn-primary', function() {
                        var rowData = listQC.row($(this).closest('tr')).data();
                        var rowIndex = listQC.row($(this).closest('tr')).index();
                        editqc(rowData);
                    });
                    $('#user_manager').on('click', '.btn.btn-primary', function() {
                        var rowData = list_emg.row($(this).closest('tr')).data();
                        editqc(rowData);
                    });
                    var list_asisten = $('#user_asisten').DataTable({
                        columns: [{
                                title: 'ID',
                                data: 'user_id',
                            },
                            {
                                title: 'Email',
                                data: 'email',
                            },
                            {
                                title: 'Password',
                                data: 'password',
                            },
                            {
                                title: 'Nama lengkap',
                                data: 'nama_lengkap',
                            },
                            {
                                title: 'Departemen',
                                data: 'departemen',
                            },
                            {
                                title: 'Afdeling ',
                                data: 'afdeling',
                            },
                            {
                                title: 'Nomor Handphone',
                                data: 'no_hp',
                            },
                            {
                                title: 'Lokasi Kerja',
                                data: 'lokasi_kerja',
                            },

                            {
                                title: 'Jabatan',
                                data: 'jabatan',
                            },

                            {
                                // -1 targets the last column
                                title: 'Actions',
                                visible: (currentUserName === 'Askep' || currentUserName === 'Manager' || (currentUserName === 'Admin' && departemen === 'QC')),
                                render: function(data, type, row, meta) {
                                    var buttons =
                                        '<button  class="btn btn-primary"><span><i class="bi bi-pencil"></i></span>Edit</button>'
                                    return buttons;
                                }
                            }
                        ],

                    });

                    list_asisten.clear().rows.add(parseResult['list_asisten']).draw();

                    $('#user_asisten').on('click', '.btn.btn-primary', function() {
                        var rowData = list_asisten.row($(this).closest('tr')).data();
                        editqc(rowData);
                    });

                    function editqc(rowData) {

                        // console.log(rowData);
                        var emailValue = rowData.email;
                        var passwordValue = rowData.password;
                        var namaLengkapValue = rowData.nama_lengkap;
                        var nomorHP = rowData.no_hp;
                        var jabatan = rowData.jabatan;
                        var _token = $('input[name="_token"]').val();
                        const actionType = "update";


                        Swal.fire({
                            title: 'Masukan Email',
                            input: 'text',
                            inputLabel: 'Email minimal 20 huruf',
                            inputValue: emailValue,
                            inputAttributes: {
                                maxlength: 50,
                                autocapitalize: 'off',
                                autocorrect: 'off'
                            },
                            showCancelButton: true,
                            confirmButtonText: 'Next',
                        }).then((emailResult) => {
                            if (emailResult.isDismissed) {

                                return;
                            }


                            emailValue = emailResult.value;

                            Swal.fire({
                                title: 'Masukan Password',
                                input: 'password',
                                inputLabel: 'Password Maksimal 10 huruf ',
                                inputValue: passwordValue,
                                inputAttributes: {
                                    maxlength: 10,
                                    autocapitalize: 'off',
                                    autocorrect: 'off'
                                },
                                showCancelButton: true,
                                confirmButtonText: 'Next',
                                inputValidator: (value) => {

                                    if (!value) {
                                        return 'PASSWORD TIDAK BISA KOSONG!.';
                                    }

                                },
                            }).then((passwordResult) => {
                                if (passwordResult.isDismissed) {

                                    return;
                                }


                                passwordValue = passwordResult.value;

                                Swal.fire({
                                    title: 'Masukan Nama',
                                    input: 'text',
                                    inputLabel: 'Nama harus di isi',
                                    inputValue: namaLengkapValue,
                                    inputAttributes: {
                                        maxlength: 50,
                                        autocapitalize: 'off',
                                        autocorrect: 'off'
                                    },
                                    showCancelButton: true,
                                    confirmButtonText: 'Save',
                                }).then((namaResult) => {
                                    if (namaResult.isDismissed) {

                                        return;
                                    }


                                    namaLengkapValue = namaResult.value;
                                    Swal.fire({
                                        title: 'Masukan Nomor HP',
                                        input: 'text',
                                        inputLabel: 'Nomor HP bisa di kosongkan',
                                        inputValue: nomorHP,
                                        inputAttributes: {
                                            maxlength: 50,
                                            autocapitalize: 'off',
                                            autocorrect: 'off'
                                        },
                                        showCancelButton: true,
                                        confirmButtonText: 'Save',
                                    }).then((nomorHpresult) => {
                                        if (nomorHpresult.isDismissed) {

                                            return;
                                        }


                                        nomorHP = nomorHpresult.value;
                                        Swal.fire({
                                            title: 'Pilih Jabatan',
                                            input: 'select',
                                            inputOptions: {
                                                'jabatan': {
                                                    Asisten: 'Asisten',
                                                    Admin: 'Admin',
                                                    Askep: 'Askep',
                                                    Manager: 'Manager',
                                                    Asisafd: 'Asisten Afdeling',
                                                    Kosong: 'Tidak Ada'
                                                },
                                            },
                                            showCancelButton: true,
                                            confirmButtonText: 'Save',
                                        }).then((jabatanresult) => {
                                            if (jabatanresult.isDismissed) {

                                                return;
                                            }


                                            jabatan = jabatanresult.value;

                                            $.ajax({
                                                type: 'POST',
                                                url: '{{ route("updateUserqc") }}',
                                                data: {
                                                    id: rowData.user_id,
                                                    email: emailValue,
                                                    password: passwordValue,
                                                    nama_lengkap: namaLengkapValue,
                                                    no_hp: nomorHP,
                                                    jabatan: jabatan,
                                                    actionType: actionType
                                                },
                                                headers: {
                                                    'X-CSRF-TOKEN': _token
                                                },
                                                success: function(response) {

                                                    Swal.fire('Disimpan!', 'User  sudah di update!.', 'success');
                                                    setTimeout(function() {
                                                        location.reload();
                                                    }, 3000); // 3000 milliseconds = 3 seconds
                                                },
                                                error: function(error) {

                                                    Swal.fire('Gagal', 'An error occurred while updating user data.', 'error');
                                                    setTimeout(function() {
                                                        // location.reload();
                                                    }, 3000); // 3000 milliseconds = 3 seconds
                                                }
                                            });

                                        });
                                    });
                                });
                            });
                        });
                    }

                    $('#user_qc').on('click', '.btn.btn-danger', function() {
                        var rowIndex = listQC.row($(this).closest('tr')).data();
                        deleteqc(rowIndex);
                    });

                    function deleteqc(rowIndex) {

                        // selectedRowIndex = id;


                        var rowData = rowIndex
                        var _token = $('input[name="_token"]').val();
                        const actionType = "delete";


                        Swal.fire({
                            title: 'Peringantan Hapus!',
                            text: 'Anda Yakin Ingin Menghapus user ini?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Hapus',
                            cancelButtonText: 'Batal',
                        }).then((result) => {
                            if (result.isConfirmed) {

                                var delete_id = rowData.user_id;

                                $.ajax({
                                    url: '{{ route("updateUserqc") }}',
                                    method: 'POST',
                                    data: {
                                        id_delete: delete_id,
                                        actionType: actionType
                                    },
                                    headers: {
                                        'X-CSRF-TOKEN': _token
                                    },
                                    success: function(response) {

                                        Swal.fire('Deleted!', 'User Sudah terhapus.', 'success');
                                        setTimeout(function() {
                                            location.reload();
                                        }, 3000); // 3000 milliseconds = 3 seconds
                                    },
                                    error: function(xhr, status, error) {

                                        Swal.fire('Error', 'An error occurred while deleting the item.', 'error');
                                        setTimeout(function() {
                                            // location.reload();
                                        }, 3000); // 3000 milliseconds = 3 seconds
                                    }
                                });
                            }
                        });
                    }



                },
                error: function(error) {

                }
            });
        });

        document.getElementById("tambahUserBtn").addEventListener("click", function() {

            var _token = $('input[name="_token"]').val();
            var lokasi = "{{ session('lok') }}";

            const actionType = "add";

            Swal.mixin({
                input: 'text',
                confirmButtonText: 'Next →',
                showCancelButton: true,
                progressSteps: ['1', '2', '3', '4'] // Add as many steps as needed
            }).queue([{
                    title: 'Nama',
                    text: 'Masukan Nama user'
                },
                {
                    title: 'Email',
                    text: 'Masukan Email user'
                },
                {
                    title: 'Jabatan',
                    input: 'select', // Use 'select' input type for dropdown
                    text: 'Pilih Jabatan User',
                    inputOptions: {
                        'Admin': 'Admin',
                        'Askep': 'Askep',
                        'Manager': 'Manager',
                        'Asisten': 'Asisten',
                        'Kosong': 'Tidak Ada'
                    }
                },
                {
                    title: 'Password User',
                    input: 'password',
                    text: 'Masukan Password'
                },
            ]).then((result) => {
                if (result.value) {
                    const answers = result.value;
                    const username = answers[0];
                    const email = answers[1];
                    const jabatan = answers[2];
                    const password = answers[3];


                    // You can now use these values to perform your desired actions (e.g., AJAX request to add the user)
                    // console.log("Username:", username);
                    // console.log("Email:", email);
                    // console.log("Jabatan:", jabatan);
                    // console.log("Password:", password);

                    // Swal.fire({
                    //     title: 'All done!',
                    //     html: `
                    //     Your answers:
                    //     <pre><code>${JSON.stringify(answers)}</code></pre>
                    // `,
                    //     confirmButtonText: 'Lovely!'
                    // });

                    // Assuming you have _token and rowData defined elsewhere in your code
                    $.ajax({
                        type: 'POST',
                        url: '{{ route("updateUserqc") }}',
                        data: {
                            _token: _token,
                            emailValue: email,
                            passwordValue: password,
                            namaLengkapValue: username,
                            jabatan_input: jabatan,
                            lokasi: lokasi,
                            statusAkun: 1,
                            actionType: actionType
                        },
                        success: function(response) {
                            Swal.fire('Disimpan!', 'User QC sudah diupdate!', 'success');
                            setTimeout(function() {
                                location.reload();
                            }, 3000); // 3000 milliseconds = 3 seconds
                        },
                        error: function(error) {
                            Swal.fire('Gagal', 'An error occurred while updating user data.', 'error');
                            setTimeout(function() {
                                // location.reload();
                            }, 3000); // 3000 milliseconds = 3 seconds
                        }
                    });
                }
            });
        });

        $(document).ready(function() {
            if ($('.alert-success').length) {
                setTimeout(function() {
                    $('.alert-success').fadeOut('slow');
                }, 500);

            }
            $('#listAsisten').DataTable();
        });

        function getUserProfile(user_id) {
            $.ajax({
                url: '/getuser',
                type: 'post',
                data: {
                    user_id: user_id,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    var user = Object.entries(response['user']);
                    // console.log(user);
                    // Loop through the user data array
                    user.forEach(function(entry) {
                        var key = entry[0];
                        var value = entry[1];

                        // Check if there's an element with the ID equal to the key
                        var element = document.getElementById(key);

                        if (element) {
                            // Populate the element with the corresponding value
                            element.textContent = value;
                        }
                    });

                    // Set the user_id in the hidden input field
                    $('#userqc_id').val(user_id);
                    $('#edit-name').val(response['user']['nama_lengkap']); // Replace 'nama_lengkap' with the correct key for the user's name
                    $('#edit-email').val(response['user']['email']);
                    $('#edit-dept').val(response['user']['departemen']);
                    $('#edit-jabatan').val(response['user']['jabatan']);
                    $('#edit-afdeling').val(response['user']['afdeling']);
                    $('#edit-nohp').val(response['user']['no_hp']);
                    $('#edit-pass').val(response['user']['password']);
                },
                error: function() {
                    // Handle errors
                    console.error('Error fetching user profile');
                }
            });
        }


        var user_id = "{{ session('user_id') }}";
        getUserProfile(user_id);

        // console.log(user_id);
    </script>
    @else
    <div class="content-wrapper">
        <h1>Anda tidak punya izin mengakses halaman ini!</h1>

        <p> User ID anda akan terecord di database mencoba akses ke halaman ini</p>
    </div>
    <script type="module">
        // Redirect to the home page after a delay
        setTimeout(function() {
            window.location.href = "{{ route('dashboard_inspeksi') }}";
        }, 2000); // Redirect after 3 seconds (adjust the delay as needed)
    </script>
    @endif
</x-layout.app>