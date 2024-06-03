<x-layout.app>


    <style>
        td.my-cell {
            border: 2px solid #ddd;
        }

        th.my-cell {
            border: 2px solid #ddd;
        }

        */ .modal {
            display: none;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.8);
        }

        .modal-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
            position: relative;
        }

        .modal-image:hover {
            cursor: pointer;
        }

        .modal-image {
            max-width: 100%;
            max-height: 90%;
            transition: transform 0.3s ease-out;
        }

        .close {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 40px;
            font-weight: bold;
            color: #fff;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }

        .button-group {
            display: flex;
            justify-content: center;
            align-items: center;
            position: absolute;
            bottom: 10px;
            width: 100%;
        }

        .rotate {
            margin-right: 5px;
        }

        /* Added media query to adjust button position for small screens */
        @media only screen and (max-width: 600px) {
            .button-group {
                flex-direction: column;
                align-items: center;
                width: 80%;
            }

            .rotate {
                margin-right: 0;
                margin-bottom: 5px;
            }
        }
    </style>

    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid pt-3">
                <a href="{{ route('dashboard_gudang') }}" class="btn btn-dark"> <i class="nav-icon fa-solid fa-arrow-left "></i></a>
                <a href="{{ route('cetakpdf', ['id' => $data->id]) }}" class="btn btn-primary mb-3 mt-3">
                    <i class="fa-solid fa-file-pdf"></i> Unduh File
                </a>


                <?php

                if (session('user_name') == 'Dennis Irawan' || session('user_name') == 'Ferry Suhada' || session('user_name') == 'Andri Mursalim') {
                ?>
                    <a onclick="return confirm('Anda yakin untuk mengahpus record ini?')" href="/hapusRecord/{{ $data->id }}" class=" btn btn-danger mb-3 mt-3"><i class="fa-solid fa-trash"></i>
                        Hapus Record</a>
                <?php
                }
                ?>

                <div class="card">
                    <div class="card-body">
                        <table class="table table-primary col-xs-1 text-center mb-3">
                            <thead>
                                <tr>
                                    <th>III.PEMERIKSAAN GUDANG</th>
                                </tr>
                            </thead>
                        </table>
                        <div class="row g-3">
                            <div class="col-md-6 col-lg-3">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th>ESTATE</th>
                                            <td>{{ $data->nama }}</td>
                                        </tr>
                                        <tr>
                                            <th>TANGGAL</th>
                                            <td>{{ $data->tanggal_formatted }}</td>
                                        </tr>
                                        <tr>
                                            <th>KTU</th>
                                            <td>{{{ $data->nama_ktu }}}</td>
                                        </tr>
                                        <tr>
                                            <th>KEPALA GUDANG</th>
                                            <td>{{ $data->kpl_gudang }}</td>
                                        </tr>
                                        @php
                                        $splitQC = explode(";", $data->qc);
                                        @endphp
                                        <tr>
                                            <th>DIPERIKSA OLEH</th>
                                            <td>@if (!empty($splitQC[1])) 1. @endif {{ $splitQC[0] }}</td>
                                        </tr>
                                        @if (!empty($splitQC[1]))
                                        <tr>
                                            <th></th>
                                            <td>2. {{ $splitQC[1] }}</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6 col-lg-3 offset-lg-6">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th class="table text-center">SKOR</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">{{ $data->skor_total }}</th>
                                        </tr>
                                        <tr>
                                            @if ($data->skor_total >= 95)
                                            <th class="table-primary text-center">EXCELLENT</th>
                                            @elseif($data->skor_total >= 85 && $data->skor_total <95) <th class="table-success text-center">Good</th>
                                                @elseif($data->skor_total >= 75 && $data->skor_total <85) <th class="table text-center" style="background-color: yellow">Satisfactory
                                                    </th>
                                                    @elseif($data->skor_total >= 65 && $data->skor_total <75) <th class="table-warning text-center">Fair</th>
                                                        @elseif($data->skor_total <75) <th class="table text-center" style="background-color: red;color:white">Poor
                                                            </th>
                                                            @endif
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            @if ($data->unit == 'CWS1' || $data->unit == 'CWS2' || $data->unit == 'CWS3')
                            <table class="table">


                                <thead>
                                    <tr class="table-primary">
                                        <th class="my-cell text-center" colspan="2">1.KESESUAIAN FISIK VS BINCARD</th>
                                        <th class="my-cell text-center" colspan="2">2.KESESUAIAN FISIK VS PPRO</th>
                                        <th class="my-cell text-center" colspan="2">3.BARANG NON-STOCK</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="my-cell">HASIL</td>
                                        <td class="my-cell" style="text-align: center;">FOTO</td>
                                        <td class="my-cell">HASIL</td>
                                        <td class="my-cell" style="text-align: center;">FOTO</td>
                                        <td class="my-cell">HASIL</td>
                                        <td class="my-cell" style="text-align: center;">FOTO</td>
                                    </tr>


                                    <!-- The Modal -->

                                    <div class="modal">
                                        <div class="modal-content">
                                            <img src="" alt="" class="modal-image">
                                            <div class="button-group">
                                                <button class="rotate btn btn-primary">Rotate</button>
                                                <button id="save-btn" class="btn btn-success">Save</button>
                                            </div>
                                            <span class="close">&times;</span>
                                        </div>
                                    </div>


                                    <tr>


                                        <td class="my-cell" rowspan="2">
                                            {{ $data->kesesuaian_bincard == 25 ? 'Tidak ditemukan selisih'
                                        : ($data->kesesuaian_bincard == 22 ? 'Ditemukan Selisih >0 s.d ≤0,5% dari total
                                        sample'
                                        : ($data->kesesuaian_bincard == 17 ? 'Ditemukan Selisih >0,5 s.d ≤1% dari total
                                        sample'
                                        : ($data->kesesuaian_bincard == 12 ? 'Ditemukan Selisih >0,5 s.d ≤1% dari total
                                        sample'
                                        : ($data->kesesuaian_bincard == 7 ? 'Ditemukan Selisih >2 s.d ≤3% dari total
                                        sample'
                                        : ($data->kesesuaian_bincard == 0 ? 'Ditemukan Selisih >3% dari total sample' :
                                        ''))))) }}
                                        </td>

                                        <td class="my-cell col-md-4 "><span><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_kesesuaian_bincard_1}}" class="img-fluid modal-image"></span></td>

                                        <td class="my-cell" rowspan="2">
                                            {{ $data->kesesuaian_ppro == 25 ? 'Tidak ditemukan selisih'
                                        : ($data->kesesuaian_ppro == 22 ? 'Ditemukan Selisih >0 s.d ≤0,5% dari total
                                        sample'
                                        : ($data->kesesuaian_ppro == 17 ? 'Ditemukan Selisih >0,5 s.d ≤1% dari total
                                        sample'
                                        : ($data->kesesuaian_ppro == 12 ? 'Ditemukan Selisih >0,5 s.d ≤1% dari total
                                        sample'
                                        : ($data->kesesuaian_ppro == 7 ? 'Ditemukan Selisih >2 s.d ≤3% dari total
                                        sample'
                                        : ($data->kesesuaian_ppro == 0 ? 'Ditemukan Selisih >3% dari total sample' :
                                        ''))))) }}
                                        </td>
                                        <td class="my-cell col-md-4"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_kesesuaian_ppro_1}}" class="img-fluid  modal-image"></td>

                                        <td class="my-cell" rowspan="2">
                                            {{ $data->barang_nonstok == 5 ? 'Tidak ada barang non-stock'
                                        : ($data->barang_nonstok == 0 ? 'Ada barang non-stock' : '') }}
                                        </td>
                                        <td class="my-cell col-md-4"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_barang_nonstok_1}}" class="img-fluid modal-image"></td>
                                    </tr>

                                    <tr>
                                        <td class="my-cell col-md-4"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_kesesuaian_bincard_2}}" class="img-fluid modal-image"></td>

                                        <td class="my-cell col-md-4"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_kesesuaian_ppro_2}}" class="img-fluid modal-image"></td>

                                        <td class="my-cell col-md-4"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_barang_nonstok_2}}" class="img-fluid modal-image"></td>
                                    </tr>

                                    <tr>
                                        <td colspan="2" class="text-center my-cell">{{ $data->komentar_kesesuaian_bincard }}
                                        </td>
                                        <td colspan="2" class="text-center my-cell">{{ $data->komentar_kesesuaian_ppro }}
                                        </td>
                                        <td colspan="2" class="text-center my-cell">
                                            {{ $data->barang_nonstok == 5 ? 'Tidak ada barang non-stock'
                                        : ($data->barang_nonstok == 0 ? 'Ada barang non-stock' : '') }}
                                        </td>
                                    </tr>
                                    <tr class="table-primary">
                                        {{-- <th class="my-cell" scope="col"></th> --}}
                                        <th class="my-cell text-center" colspan="2">4.SELURUH MR DITANDATANGANI MANAGER CWS
                                        </th>
                                        {{-- <th class="my-cell" scope="col"></th> --}}
                                        <th class="my-cell text-center" colspan="2">5.KEBERSIHAN DAN KERAPIHAN GUDANG</th>
                                        {{-- <th class="my-cell" scope="col"></th> --}}
                                        <th class="my-cell text-center" colspan="2">6.BUKU INSPEKSI KTU (LOGBOOK KTU)</th>

                                    </tr>
                                    <tr>
                                        <td class="my-cell">HASIL</td>
                                        <td class="my-cell">FOTO</td>
                                        <td class="my-cell">HASIL</td>
                                        <td class="my-cell">FOTO</td>
                                        <td class="my-cell">HASIL</td>
                                        <td class="my-cell">FOTO</td>
                                    </tr>
                                    <tr>
                                        <td class="my-cell" rowspan="2">
                                            {{
                                        $data->mr_ditandatangani == 15 ? 'MR Ditandatangani oleh EM Seluruhnya' :
                                        ($data->mr_ditandatangani == 10 ? 'Ditemukan MR (H+2) yang tidak ditandatangani
                                        EM' :
                                        ($data->mr_ditandatangani == 5 ? 'Ditemukan MR (H+3) yang tidak ditandatangani
                                        EM' :
                                        ($data->mr_ditandatangani == 0 ? 'Ditemukan MR (>H+3) yang tidak ditandatangani
                                        EM' : '')))
                                        }}
                                        </td>



                                        @if ($data->foto_mr_ditandatangani_1)
                                        <td class="my-cell col-md-4"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_mr_ditandatangani_1}}" class="img-fluid modal-image"></td>
                                        @else
                                        <td><img src="{{asset('noimage.png')}}" style="weight:75pt;height:150pt"></td>
                                        @endif




                                        <td class="my-cell" rowspan="2">
                                            {{$data->kebersihan_gudang + $data->bincard_terbungkus +
                                        $data->peletakan_bincard + $data->rak_ditutup + $data->cat_sesuai}}
                                        </td>





                                        @if ($data->foto_kebersihan_gudang_1)
                                        <td class="my-cell col-md-4"><img data-original-src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_kebersihan_gudang_1}}" src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_kebersihan_gudang_1}}" class="img-fluid modal-image"></td>
                                        @else
                                        <td><img src="{{asset('noimage.png')}}" style="weight:75pt;height:150pt"></td>
                                        @endif



                                        <td class="my-cell" rowspan="2">
                                            {{ $data->inspeksi_ktu == 5 ? ' Logbook todate & lengkap'
                                        : ($data->inspeksi_ktu == 0 ? ') Logbook tidak todate ' : '') }}
                                        </td>
                                        @if ($data->foto_inspeksi_ktu_1)
                                        <td class="my-cell col-md-4"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_inspeksi_ktu_1}}" class="img-fluid modal-image"></td>
                                        @else
                                        <td><img src="{{asset('noimage.png')}}" style="weight:75pt;height:150pt"></td>
                                        @endif
                                    </tr>
                                    <tr>
                                        @if ($data->foto_mr_ditandatangani_2)
                                        <td class="my-cell col-md-4"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_mr_ditandatangani_2}}" class="img-fluid modal-image"></td>
                                        @else
                                        <td><img src="{{asset('noimage.png')}}" style="weight:75pt;height:150pt"></td>
                                        @endif
                                        @if ($data->foto_kebersihan_gudang_2)
                                        <td class="my-cell col-md-4"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_kebersihan_gudang_2}}" class="img-fluid modal-image"></td>
                                        @else
                                        <td><img src="{{asset('noimage.png')}}" style="weight:75pt;height:150pt"></td>
                                        @endif
                                        @if ($data->foto_inspeksi_ktu_2)
                                        <td class="my-cell col-md-4"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_inspeksi_ktu_2}}" class="img-fluid modal-image"></td>
                                        @else
                                        <td><img src="{{asset('noimage.png')}}" style="weight:75pt;height:150pt"></td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-center my-cell">{{ $data->komentar_mr_ditandatangani }}
                                        </td>
                                        <td colspan="2" class="text-center my-cell">{{ $data->komentar_kebersihan_gudang }}
                                        </td>
                                        <td colspan="2" class="text-center my-cell">{{ $data->komentar_inspeksi_ktu }}
                                        </td>
                                    </tr>

                                </tbody>
                            </table>



                            @else
                            <table class="table">
                                <thead>
                                    <tr class="table-primary">
                                        <th class="my-cell text-center" colspan="2">1.KESESUAIAN FISIK VS BINCARD</th>
                                        <th class="my-cell text-center" colspan="2">2.KESESUAIAN FISIK VS PPRO</th>
                                        <th class="my-cell text-center" colspan="2">3.BARANG CHEMICAL EXPIRED</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="my-cell">HASIL</td>
                                        <td class="my-cell" style="text-align: center;">FOTO</td>
                                        <td class="my-cell">HASIL</td>
                                        <td class="my-cell" style="text-align: center;">FOTO</td>
                                        <td class="my-cell">HASIL</td>
                                        <td class="my-cell" style="text-align: center;">FOTO</td>
                                    </tr>

                                    <tr>
                                        <td class="my-cell" rowspan="2">
                                            {{$data->kesesuaian_bincard == 15 ? 'Sesuai'
                                        :($data->kesesuaian_bincard == 10 ? 'Selisih 1 Item Barang'
                                        : ($data->kesesuaian_bincard == 5 ? 'Tidak Sesuai / Selisih >1 item barang'
                                        :''))}}
                                        </td>

                                        <td class="my-cell col-md-4 "><span><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_kesesuaian_bincard_1}}" class="img-fluid modal-image"></span></td>

                                        <td class="my-cell" rowspan="2">
                                            {{$data->kesesuaian_ppro == 20 ? 'Sesuai'
                                        :($data->kesesuaian_ppro == 15 ? 'Selisih 1 Item Barang'
                                        : ($data->kesesuaian_ppro == 5 ? 'Tidak Sesuai / Selisih >1 item barang'
                                        :''))}}
                                        </td>
                                        <td class="my-cell col-md-4"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_kesesuaian_ppro_1}}" class="img-fluid  modal-image"></td>

                                        <td class="my-cell" rowspan="2">{{$data->chemical_expired == 15 ? 'Tidak ada
                                        chemical expired'
                                        :($data->chemical_expired == 10 ? '< 10% jenis chemical expired '
                                            : ($data->chemical_expired == 5 ? '>= 10% jenis chemical expired' :''))}}
                                        </td>
                                        <td class="my-cell col-md-4"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_chemical_expired_1}}" class="img-fluid modal-image"></td>
                                    </tr>

                                    <tr>
                                        <td class="my-cell col-md-4"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_kesesuaian_bincard_2}}" class="img-fluid modal-image"></td>

                                        <td class="my-cell col-md-4"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_kesesuaian_ppro_2}}" class="img-fluid modal-image"></td>

                                        <td class="my-cell col-md-4"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_chemical_expired_2}}" class="img-fluid modal-image"></td>
                                    </tr>

                                    <tr>
                                        <td colspan="2" class="text-center my-cell">{{ $data->komentar_kesesuaian_bincard }}
                                        </td>
                                        <td colspan="2" class="text-center my-cell">{{ $data->komentar_kesesuaian_ppro }}
                                        </td>
                                        <td colspan="2" class="text-center my-cell">{{ $data->komentar_chemical_expired }}
                                        </td>
                                    </tr>
                                    <tr class="table-primary">
                                        {{-- <th class="my-cell" scope="col"></th> --}}
                                        <th class="my-cell text-center" colspan="2">4.Barang Non-Stock</th>
                                        {{-- <th class="my-cell" scope="col"></th> --}}
                                        <th class="my-cell text-center" colspan="2">5.SELURUH MR DITANDATANGANI EM</th>
                                        {{-- <th class="my-cell" scope="col"></th> --}}
                                        <th class="my-cell text-center" colspan="2">6.KEBERSIHAN DAN KERAPIHAN GUDANG</th>

                                    </tr>
                                    <tr>
                                        <td class="my-cell">HASIL</td>
                                        <td class="my-cell" style="text-align: center;">FOTO</td>
                                        <td class="my-cell">HASIL</td>
                                        <td class="my-cell" style="text-align: center;">FOTO</td>
                                        <td class="my-cell">HASIL</td>
                                        <td class="my-cell" style="text-align: center;">FOTO</td>
                                    </tr>
                                    <tr>
                                        <td class="my-cell" rowspan="2">
                                            {{
                                        $data->barang_nonstok == 5 ? 'Ya' :
                                        ($data->barang_nonstok == 0 ? 'Tidak Ada' : '')
                                        }}
                                        </td>
                                        @if ($data->foto_barang_nonstok_1)
                                        <td class="my-cell col-md-4"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_barang_nonstok_1}}" class="img-fluid modal-image"></td>
                                        @else
                                        <td><img src="{{asset('noimage.png')}}" style="weight:75pt;height:150pt"></td>
                                        @endif
                                        <td class="my-cell" rowspan="2">
                                            {{($data->mr_ditandatangani == 10) ? 'MR Ditandatangani oleh EM Seluruhnya ' :
                                        (($data->mr_ditandatangani ==
                                        7) ?
                                        ' Ditemukan MR (H+2) yang tidak ditandatangani EM' : (($data->mr_ditandatangani
                                        == 4) ? ' Ditemukan MR (>H+2) yang tidak ditandatangani EM' :
                                        ''))}}
                                        </td>
                                        @if ($data->foto_mr_ditandatangani_1)
                                        <td class="my-cell col-md-4"><img data-original-src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_mr_ditandatangani_1}}" src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_mr_ditandatangani_1}}" class="img-fluid modal-image"></td>
                                        @else
                                        <td><img src="{{asset('noimage.png')}}" style="weight:75pt;height:150pt"></td>
                                        @endif
                                        @php
                                        $total_gudang = $data->kebersihan_gudang + $data->gudang_pupuk + $data->bincard_terbungkus +
                                        $data->peletakan_bincard + $data->rak_ditutup + $data->cat_sesuai;

                                        $kondisigd = "-";

                                        if ($total_gudang >= 30) {
                                        $kondisigd = "Cukup Baik";
                                        } else {
                                        $kondisigd = "Kurang Baik";
                                        }

                                        @endphp

                                        <td class="my-cell" rowspan="2">
                                            {{$kondisigd}}
                                        </td>
                                        @if ($data->foto_kebersihan_gudang_1)
                                        <td class="my-cell col-md-4"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_kebersihan_gudang_1}}" class="img-fluid modal-image"></td>
                                        @else
                                        <td><img src="{{asset('noimage.png')}}" style="weight:75pt;height:150pt"></td>
                                        @endif
                                    </tr>


                                    <tr>
                                        @if ($data->foto_barang_nonstok_2)
                                        <td class="my-cell col-md-4"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_barang_nonstok_2}}" class="img-fluid modal-image"></td>
                                        @else
                                        <td><img src="{{asset('noimage.png')}}" style="weight:75pt;height:150pt"></td>
                                        @endif
                                        @if ($data->foto_mr_ditandatangani_2)
                                        <td class="my-cell col-md-4"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_mr_ditandatangani_2}}" class="img-fluid modal-image"></td>
                                        @else
                                        <td><img src="{{asset('noimage.png')}}" style="weight:75pt;height:150pt"></td>
                                        @endif
                                        @if ($data->foto_kebersihan_gudang_2)
                                        <td class="my-cell col-md-4"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_kebersihan_gudang_2}}" class="img-fluid modal-image"></td>
                                        @else
                                        <td><img src="{{asset('noimage.png')}}" style="weight:75pt;height:150pt"></td>
                                        @endif

                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-center my-cell">
                                            {{-- {{
                                        $data->barang_nonstok == 5 ? 'Ya Barang Non-Stock' :
                                        ($data->barang_nonstok == 0 ? 'Tidak Ada Barang Non-Stock' : '')
                                        }} --}}
                                            {{ $data->komentar_barang_nonstok }}
                                        </td>
                                        <td colspan="2" class="text-center my-cell">{{ $data->komentar_mr_ditandatangani }}
                                        </td>
                                        <td colspan="2" class="text-center my-cell">{{ $data->komentar_kebersihan_gudang }}
                                        </td>
                                    </tr>


                                    @if ($data->foto_kebersihan_gudang_count > 2 )
                                    <tr class="table-primary">

                                        <th class="my-cell text-center" colspan="2">7. BUKU INSPEKSI KTU</th>
                                        <th class="my-cell text-center" colspan="2">Dokumentasi Lainnya</th>
                                        @if (isset($data->foto_kebersihan_gudang_4))
                                        <th class="my-cell text-center" colspan="2">Dokumentasi Lainnya</th>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td class="my-cell">HASIL</td>
                                        <td class="my-cell" style="text-align: center;">FOTO</td>
                                        <td class="my-cell">HASIL</td>
                                        <td class="my-cell" style="text-align: center;">FOTO</td>\
                                        @if (isset($data->foto_kebersihan_gudang_4))
                                        <td class="my-cell">HASIL</td>
                                        <td class="my-cell" style="text-align: center;">FOTO</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td class="my-cell" rowspan="2">
                                            {{($data->inspeksi_ktu == 5) ? 'Logbook todate & lengkap ' :
                                     (($data->inspeksi_ktu == 0) ? ' Logbook tidak todate' : '')}}
                                        </td>
                                        @if ($data->foto_inspeksi_ktu_1)
                                        <td class="my-cell col-md-4"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_inspeksi_ktu_1}}" class="img-fluid modal-image"></td>
                                        @else
                                        <td><img src="{{asset('noimage.png')}}" style="weight:75pt;height:150pt"></td>
                                        @endif

                                        <!-- GUDANG  -->
                                        <td class="my-cell" rowspan="2"> {{$kondisigd}} </td>
                                        @if ($data->foto_kebersihan_gudang_3)
                                        <td class="my-cell col-md-4"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_kebersihan_gudang_3}}" class="img-fluid modal-image"></td>
                                        @else
                                        <td><img src="{{asset('noimage.png')}}" style="weight:75pt;height:150pt"></td>
                                        @endif
                                        @if (isset($data->foto_kebersihan_gudang_4))
                                        <td class="my-cell" rowspan="2"> {{$kondisigd}} </td>
                                        </td>

                                        <td class="my-cell col-md-4"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_kebersihan_gudang_4}}" class="img-fluid modal-image"></td>
                                        @else
                                        <td></td>
                                        @endif
                                    </tr>
                                    <tr>
                                        @if ($data->foto_inspeksi_ktu_2)
                                        <td class="my-cell col-md-4"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_inspeksi_ktu_2}}" class="img-fluid modal-image"></td>
                                        @else
                                        <td><img src="{{asset('noimage.png')}}" style="weight:75pt;height:150pt"></td>
                                        @endif

                                        <!-- GUDANG  -->
                                        @if (isset($data->foto_kebersihan_gudang_5))
                                        <td class="my-cell col-md-4"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_kebersihan_gudang_5}}" class="img-fluid modal-image"></td>
                                        @else
                                        <td></td>
                                        @endif


                                        @if (isset($data->foto_kebersihan_gudang_6))
                                        <td class="my-cell col-md-4"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_kebersihan_gudang_6}}" class="img-fluid modal-image"></td>
                                        @else
                                        <td></td>
                                        @endif

                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-center my-cell">{{ $data->komentar_inspeksi_ktu }}
                                        </td>

                                    </tr>


                                    @else
                                    <tr class="table-primary">

                                        <th class="my-cell text-center" colspan="2">7. BUKU INSPEKSI KTU</th>

                                    </tr>
                                    <tr>
                                        <td class="my-cell">HASIL</td>
                                        <td class="my-cell" style="text-align: center;">FOTO</td>


                                    </tr>
                                    <tr>
                                        <td class="my-cell" rowspan="2">
                                            {{($data->inspeksi_ktu == 5) ? 'Logbook todate & lengkap ' :
                                        (($data->inspeksi_ktu == 0) ? ' Logbook tidak todate' : '')}}
                                        </td>
                                        @if ($data->foto_inspeksi_ktu_1)
                                        <td class="my-cell col-md-4"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_inspeksi_ktu_1}}" class="img-fluid modal-image"></td>
                                        @else
                                        <td><img src="{{asset('noimage.png')}}" style="weight:75pt;height:150pt"></td>
                                        @endif


                                    </tr>
                                    <tr>
                                        @if ($data->foto_inspeksi_ktu_2)
                                        <td class="my-cell col-md-4"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_inspeksi_ktu_2}}" class="img-fluid modal-image"></td>
                                        @else
                                        <td><img src="{{asset('noimage.png')}}" style="weight:75pt;height:150pt"></td>
                                        @endif

                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-center my-cell">{{ $data->komentar_inspeksi_ktu }}
                                        </td>

                                    </tr>

                                    @endif



                                </tbody>
                            </table>
                            @endif



                        </div>
                    </div>
                </div>
        </section>


        <!-- Main Modal -->
        @if (session('jabatan') == 'Manager' || session('jabatan') == 'Askep' || session('jabatan') == 'Asisten')
        <div class="modal">
            <div class="modal-content">
                <img src="" alt="" class="modal-image">
                <div class="button-group">
                    <button class="rotate btn btn-primary">Rotate</button>

                    <button id="uploadFoto" class="btn btn-info">Upload Foto</button>
                    <button id="deleteMod" class="btn btn-danger">Delete Foto</button>

                    <button id="save-btn" class="btn btn-success">Save</button>
                </div>
                <span class="close">&times;</span>
            </div>
        </div>
        <!-- Nested Modal -->
        <div id="nestedModal" class="modal">
            <div class="modal-content">
                <!-- Header -->
                <div class="modal-header">
                    <!-- <h2>Upload Foto</h2> -->
                    <h2>Upload Foto jika hanya foto tidak tersedia!</h2>
                    <!-- <button id="closeNestedModalBtn" class="close">&times;</button> -->
                </div>
                <!-- Body -->
                <div class="modal-body">
                    <p><strong>Image Source:</strong> <span id="nestedSrc"></span></p>
                    <p><strong>Image Alt:</strong> <span id="nestedAlt"></span></p>
                    <div class="file-upload">
                        <label for="uploadInput" class="btn btn-primary">Choose Foto</label>
                        <input type="file" id="uploadInput" accept="image/*" style="display: none;">
                    </div>
                </div>
                <!-- Footer -->
                <div class="modal-footer">
                    <button id="uploadBtn" class="btn btn-primary">Upload</button>
                    <button id="closeNestedModalBtn" class="btn btn-danger">Close</button>
                </div>
            </div>
        </div>


        @else

        <div class="modal">
            <div class="modal-content">
                <img src="" alt="" class="modal-image">
                <div class="button-group">
                    <button class="rotate btn btn-primary">Rotate</button>
                    <button id="save-btn" class="btn btn-success">Save</button>
                </div>
                <span class="close">&times;</span>
            </div>
        </div>
        <!-- Nested Modal -->
        <div id="nestedModal" class="modal">
            <div class="modal-content">
                <!-- Header -->
                <div class="modal-header">
                    <!-- <h2>Upload Foto</h2> -->
                    <h2>Upload Foto jika hanya foto tidak tersedia!</h2>
                    <!-- <button id="closeNestedModalBtn" class="close">&times;</button> -->
                </div>
                <!-- Body -->
                <div class="modal-body">
                    <p><strong>Image Source:</strong> <span id="nestedSrc"></span></p>
                    <p><strong>Image Alt:</strong> <span id="nestedAlt"></span></p>
                    <div class="file-upload">
                        <label for="uploadInput" class="btn btn-primary">Choose Foto</label>
                        <input type="file" id="uploadInput" accept="image/*" style="display: none;">
                    </div>
                </div>
                <!-- Footer -->
                <div class="modal-footer">
                    <button id="uploadBtn" class="btn btn-primary">Upload</button>
                    <button id="closeNestedModalBtn" class="btn btn-danger">Close</button>
                </div>
            </div>
        </div>

        @endif

    </div>


    <script type="module">
        var currentUserName = "{{ session('jabatan') }}";
        const openNestedModalBtn = document.getElementById("openNestedModalBtn");
        const closeNestedModalBtn = document.getElementById("closeNestedModalBtn");
        const mainModal = document.querySelector(".modal");
        const nestedModal = document.getElementById("nestedModal");

        // Declare src and alt variables in a higher scope
        let src = '';
        let alt = '';

        // Get all the images
        const images = document.querySelectorAll('.modal-image');

        // Get the modal element
        const modal = document.querySelector('.modal');

        // Get the modal content element
        const modalContent = document.querySelector('.modal-content');

        // Loop through each image
        images.forEach((image) => {
            // Add a click event listener to the image
            image.addEventListener('click', () => {
                // Get the clicked image source and alt attributes
                src = image.getAttribute('src');
                alt = image.getAttribute('alt');

                // Update the modal image source and alt attributes
                const modalImage = modal.querySelector('.modal-image');
                modalImage.setAttribute('src', src);
                modalImage.setAttribute('alt', alt);
                resetRotation();
                // Show the modal
                modal.style.display = 'block';
            });
        });


        document.getElementById('uploadFoto').addEventListener('click', function() {
            if (currentUserName === 'Askep' || currentUserName === 'Manager') {
                Swal.fire({
                    title: 'Select Image',
                    input: 'file',
                    inputAttributes: {
                        accept: 'image/*',
                        'aria-label': 'Upload your profile picture'
                    },
                    confirmButtonText: 'Upload',
                    showCancelButton: true,
                    cancelButtonText: 'Cancel',
                    inputValidator: (value) => {
                        if (!value) {
                            return 'You need to select an image!';
                        }
                    }
                }).then((file) => {
                    if (file.value) {
                        const uploadedFile = file.value;


                        const srcParts = src.split('/');
                        const lastPart = srcParts[srcParts.length - 1];


                        const formData = new FormData();
                        formData.append('image', uploadedFile);
                        formData.append('filename', lastPart);
                        formData.append('action', 'upload'); // Add action

                        // Create a new XMLHttpRequest object
                        const xhr = new XMLHttpRequest();
                        const url = 'https://srs-ssms.com/qc_inspeksi/upGudang.php';

                        xhr.open('POST', url, true);
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState === 4) {
                                if (xhr.status === 200) {
                                    const response = JSON.parse(xhr.responseText);

                                    if (response.status === 'success') {
                                        Swal.fire('Uploaded!', response.message, 'success');
                                        // Optionally, you can use response.file_path if you need the file path.
                                    } else {
                                        Swal.fire('Error', response.message, 'error');
                                    }
                                } else {
                                    Swal.fire('Error', 'Failed to upload the file.', 'error');
                                }
                            }
                        };


                        xhr.send(formData);

                    }
                });
            } else {
                Swal.fire({
                    title: 'Anda tidak punya Akses untuk fungsi Ini',
                    text: 'Berlaku Hanya untuk Manager/Askep/Asisten!',
                    icon: 'warning',
                    showCancelButton: true,
                    // confirmButtonText: 'Yes, delete it!',

                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire('Terima Kasih');
                    }
                });
            }
        });


        // Add an event listener for the delete button
        document.getElementById('deleteMod').addEventListener('click', function() {
            if (currentUserName === 'Askep' || currentUserName === 'Manager') {
                const srcParts = src.split('/');
                const lastPart = srcParts[srcParts.length - 1];


                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You won\'t be able to revert this!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Log the image file name before deleting
                        const xhr = new XMLHttpRequest();
                        const url = 'https://srs-ssms.com/qc_inspeksi/upGudang.php';

                        xhr.open('POST', url, true);
                        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState === 4) {
                                if (xhr.status === 200) {
                                    Swal.fire('Deleted!', 'Your file has been deleted.', 'success');
                                } else {
                                    Swal.fire('Error', 'Failed to delete the file.', 'error');
                                }
                            }
                        };

                        // Send both the image file name and the action as parameters to your PHP script
                        const params = `imageFileName=${lastPart}&action=delete`; // Set action to 'delete'
                        xhr.send(params);
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // The user canceled the action
                        Swal.fire('Cancelled', 'Your file is safe :)', 'error');
                    }
                });
            } else {
                Swal.fire({
                    title: 'Anda tidak punya Akses untuk fungsi Ini',
                    text: 'Berlaku Hanya untuk Manager/Askep/Asisten!',
                    icon: 'warning',
                    showCancelButton: true,
                    // confirmButtonText: 'Yes, delete it!',

                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire('Terima Kasih');
                    }
                });
            }


        });


        closeNestedModalBtn.addEventListener("click", () => {
            mainModal.style.display = "block"; // Show the main modal
            nestedModal.style.display = "none"; // Hide the nested modal
        });


        // Get the close button
        const closeButton = modal.querySelector('.close');

        // Add a click event listener to the close button
        closeButton.addEventListener('click', () => {
            // Hide the modal
            modal.style.display = 'none';
            resetRotation();
        });

        // Add a click event listener to the modal content element
        modalContent.addEventListener('click', (event) => {
            // If the clicked element is the modal content itself
            if (event.target === modalContent) {
                // Hide the modal
                modal.style.display = 'none';
            }
        });

        function resetRotation() {
            // Reset the total rotation angle
            totalRotation = 0;

            // Get the modal image and reset the rotation angle and style
            const modalImage = modal.querySelector('.modal-image');
            modalImage.style.transform = 'rotate(0deg)';
            modalImage.removeAttribute('data-rotation');
        }
        // Get the rotate button
        const rotateButton = modal.querySelector('.rotate');
        let totalRotation = 0;
        // Add a click event listener to the rotate button
        rotateButton.addEventListener('click', () => {
            // Get the modal image
            const modalImage = modal.querySelector('.modal-image');

            // Calculate the new rotation angle
            const newRotation = totalRotation + 90;

            // Update the rotation angle and style
            modalImage.style.transform = `rotate(${newRotation}deg)`;

            // Update the total rotation angle
            totalRotation = newRotation;
        });
        // Get the save button
        const saveButton = modal.querySelector('#save-btn');

        function sendRequest() {
            // Get the modal image source, file name, and degree of rotation
            const modalImage = modal.querySelector('.modal-image');
            const src = modalImage.getAttribute('src');
            const fileName = src.substring(src.lastIndexOf('/') + 1);

            // Get the degree of rotation
            let rotation = totalRotation;
            if (rotation === undefined) {
                rotation = 0;
            }

            // Send the data to the server using Ajax
            const xhr = new XMLHttpRequest();
            const url = 'https://srs-ssms.com/gudang/rotateImage.php';
            const params = `fileName=${fileName}&rotation=${rotation}`;
            xhr.open('POST', url, true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    console.log(xhr.responseText);
                    // Hide the modal and reset the rotation
                    modal.style.display = 'none';
                    resetRotation();

                    // Remove the event listener to prevent multiple requests
                    saveButton.removeEventListener('click', sendRequest);
                    alert('Foto baru sudah tersimpan');
                    location.reload();
                } else if (xhr.readyState === 4 && xhr.status !== 200) {
                    console.log('Error:', xhr.statusText);
                }
            };
            xhr.send(params);
        }

        // Add a click event listener to the save button
        saveButton.addEventListener('click', sendRequest, {
            once: true
        });
    </script>

</x-layout.app>