<x-layout.app>
    @if (session('jabatan') == 'Manager' || session('jabatan') == 'Askep')
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
        </div>



    </div>



    <script type="text/javascript">
        var currentUserName = "{{ session('jabatan') }}";
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
                                visible: (currentUserName === 'Askep' || currentUserName === 'Manager'),
                                render: function(data, type, row, meta) {
                                    var buttons =
                                        '<button class="edit-btn">Edit</button>' +
                                        '<button class="delete-btn">Delete</button>';
                                    return buttons;
                                }
                            }
                        ],

                    });

                    // Populate DataTable with data
                    listQC.clear().rows.add(parseResult['list_qc']).draw();


                    $('#user_qc').on('click', '.edit-btn', function() {
                        var rowData = listQC.row($(this).closest('tr')).data();
                        var rowIndex = listQC.row($(this).closest('tr')).index();
                        editqc(rowIndex);
                    });

                    function editqc(id) {

                        selectedRowIndex = id;


                        var rowData = listQC.row(id).data();


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
                                inputLabel: 'Password Minimal 10 huruf ',
                                inputValue: '',
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

                                                    Swal.fire('Disimpan!', 'User QC sudah di update!.', 'success');
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

                    $('#user_qc').on('click', '.delete-btn', function() {
                        var rowIndex = listQC.row($(this).closest('tr')).index();
                        deleteqc(rowIndex);
                    });

                    function deleteqc(id) {

                        selectedRowIndex = id;


                        var rowData = listQC.row(id).data();
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
    </script>
    @else
    <div class="content-wrapper">
        <h1>Anda tidak punya izin mengakses halaman ini!</h1>

        <p> User ID anda akan terecord di database mencoba akses ke halaman ini</p>
    </div>
    <script>
        // Redirect to the home page after a delay
        setTimeout(function() {
            window.location.href = "{{ route('dashboard_inspeksi') }}";
        }, 2000); // Redirect after 3 seconds (adjust the delay as needed)
    </script>
    @endif
</x-layout.app>