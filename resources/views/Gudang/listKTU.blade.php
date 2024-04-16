<x-layout.app>


 @if (session('user_name') == 'Dennis Irawan' || session('user_name') == 'Ferry Suhada')
<div class="content-wrapper">
    @if(session('status'))
    <div class="mr-3 ml-3 alert alert-success">
        {{ session('status') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mr-3 ml-3 alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <section class="content">
        <div class="container-fluid pt-3">
            <a href="{{ route('dashboard') }}" class="btn btn-dark"> <i class="bi bi-skip-backward"></i></a>
            <div class="card mt-2">
                <div class="card-body">
                    <button class="btn btn-primary mb-2" style="float: right;" data-bs-toggle="modal" data-bs-target="#tambahData">Tambah Data</button>
                    <table id="listAsisten" class="table-striped text-center" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Jabatan</th>
                                <th>Afdeling</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pekerja as $value)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $value['nama'] }}</td>
                                <td>{{ $value['jabatan']}}</td>
                                <td>{{ $value['unit'] }}</td>
                                <td style="display: inline-flex">
                                    <button class="btn btn-success mr-2" data-bs-toggle="modal" data-bs-target="#perbarui{{$value['id']}}"><i class="bi bi-pencil-square"></i></button>
                                    <form action="{{ route('hapusKTU') }}" method="POST">{{ csrf_field() }}
                                        <input type="hidden" name="id" value="{{$value['id']}}"><button type="submit" class="btn btn-danger" onclick="return confirm('Yakin menghapus data?')"><i class="bi bi-trash3"></i></button>
                                    </form>
                                </td>
                            </tr>
                            <div class="modal fade" id="perbarui{{$value['id']}}" tabindex="-1" aria-labelledby="perbaruiDataLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="perbaruiDataLabel">Perbarui Data KTU
                                            </h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('updateKTU') }}" method="POST">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="id" value="{{ $value['id'] }}">
                                                <div class="mb-3">
                                                    <label for="nama" class="col-form-label">Nama</label>
                                                    <input type="text" class="form-control" id="nama" name="nama" value="{{$value['nama']}}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="jabatan" class="col-form-label">Jabatan</label>
                                                    <input type="text" class="form-control" id="jabatan" name="jabatan" value="{{ $value['jabatan']}}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="est" class="col-form-label">Estate</label>
                                                    <select name="est" class="form-control" required>
                                                        <option value="" disabled selected>SILAKAN PILIH</option>
                                                        <!-- <option value="REG-I">REG-I</option>
                                                        <option value="WIL-I">WIL-I</option>
                                                        <option value="WIL-II">WIL-II</option>
                                                        <option value="WIL-III">WIL-III</option> -->
                                                        @foreach ($estate as $value)
                                                        <option value="{{$value->est}}">{{$value->est}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mt-2 mb-2" style="float: right">
                                                    <button type="submit" class="btn btn-primary" onclick="return confirm('Yakin perbarui data?')">Submit</button>
                                                </div>
                                            </form>


                                        </div>
                                    </div>
                                </div>
                            </div>

                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal fade" id="tambahData" tabindex="-1" aria-labelledby="tambahDataLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="tambahDataLabel">Tambah Data KTU</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('tambahKTU') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="mb-3">
                        <label for="name" class="col-form-label">Nama</label>
                        <input type="text" class="form-control" id="name" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="jabatan" class="col-form-label">Jabatan</label>
                        <input type="text" class="form-control" id="jabatan_id" name="jabatan" value="KTU " required>
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">Regional</label>
                        <select id="regional-dropdown" name="reg" class="form-control" required>
                            <option value="" disabled selected>SILAKAN PILIH</option>
                            @foreach ($regional as $key => $value)
                            <option value="{{$key}}">{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">Estate</label>
                        <select id="estate-dropdown" name="est" class="form-control" required>
                            <option value="" disabled selected>SILAKAN PILIH</option>
                            @foreach ($estate as $value)
                            <option value="{{$value->est}}">{{$value->est}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mt-2 mb-2" style="float: right">
                        <button id="submitData" type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endif

<script type="module">
    $(document).ready(function() {
        $('#listAsisten').DataTable();
    });
    
    const regionalDropdown = document.getElementById('regional-dropdown');
    const estateDropdown = document.getElementById('estate-dropdown');

    // Listen for changes on the regional dropdown
    regionalDropdown.addEventListener('change', () => {
        // Clear existing options in the estate dropdown
        estateDropdown.innerHTML = '<option value="" disabled selected>SILAKAN PILIH</option>';

        // Get the selected regional value
        const selectedRegional = regionalDropdown.value;
        var _token = $('input[name="_token"]').val();
        
        $.ajax({
        url: 'get-estate-list-ktu',
        method: "POST",
            data: {
                _token: _token,
                regional: selectedRegional
            },
            success: function(result) {
                var parseResult = JSON.parse(result)
                
                console.log(parseResult)
                const estateDropdown = $('#estate-dropdown');

                // Clear the existing options
                estateDropdown.empty();

                // Add the default option
                estateDropdown.append($('<option>').val('').text('SILAKAN PILIH'));

                // Add the options for each estate
                // $.each(parseResult, function(item) {
                //     console.log(item)
                //     // estateDropdown.append($('<option>').val(estate.est).text(estate.est));
                // });

                for (let i = 0; i < parseResult.length; i++) {
                const option = $('<option>').val(parseResult[i]).text(parseResult[i]);
                estateDropdown.append(option);
                }
     } });

    });

        $('#estate-dropdown').on('change', function() {
        const selectedValue = $(this).val();
        console.log(selectedValue);
        
        });

        document.getElementById('submitData').onclick = function() {
            var est = document.getElementById('estate-dropdown').value;

            console.log(est)
    }
</script>

</x-layout.app>
