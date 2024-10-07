<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>Document</title>

</head>
<style>
    td.my-cell {
        border: 2px solid black;
    }

    th.my-cell {
        border: 2px solid black;
    }
</style>

<body>



    <table class="table">

        <thead>
            <tr>
                <th style="border:2px solid black" colspan="6" class="text-center">III.PEMERIKSAAN GUDANG</th>
            </tr>
            <tr>
                <td></td>
                <td style="border-top:2px solid black" colspan="5"></td>
            </tr>
            <tr>
                <td class="font-weight-bold" style="width:200px;border:2px solid black">ESTATE</td>
                <td style="width:300px;border:2px solid black">{{$data->nama}}</td>
                <td style="border: 1px solid white"></td>
                <td style="border: 1px solid white"></td>
                <td style="border: 1px solid white"></td>
                <td class="font-weight-bold text-center" style="width:200px;border:2px solid black">SKOR</td>
            </tr>
            <tr>
                <td class="font-weight-bold" style="border:2px solid black">TANGGAL</td>
                <td style="border:2px solid black"> {{ $data->tanggal_formatted }}</td>
                <td style="border: 1px solid white"></td>
                <td style="border: 1px solid white"></td>
                <td style="border: 1px solid white"></td>
                <td class="font-weight-bold text-center" style="border:2px solid black">{{$data->skor_total}}</td>
            </tr>
            <tr>
                <td class="font-weight-bold" style="border:2px solid black">KTU</td>
                <td style="border:2px solid black"> {{ $data->nama_ktu }}</td>
                <td style="border: 1px solid white"></td>
                <td style="border: 1px solid white"></td>
                <td style="border: 1px solid white"></td>
                @if ($data->skor_total >= 95)
                <td class="table-primary text-center" style="border:2px solid black">EXCELLENT</td>
                @elseif($data->skor_total >= 85 && $data->skor_total <95) <td class="table-success text-center" style="border:2px solid black">
                    Good</td>
                    @elseif($data->skor_total >= 75 && $data->skor_total <85) <td class="table text-center" style="background-color: yellow;border:2px solid black">Satisfactory</td>
                        @elseif($data->skor_total >= 65 && $data->skor_total <75) <td class="table-warning text-center" style="border:2px solid black">
                            Fair</td>
                            @elseif($data->skor_total <75) <td class="table text-center" style="background-color: red;border:2px solid black">
                                Poor
                                </td>
                                @endif
            </tr>
            <tr>
                <td class="font-weight-bold" style="border:2px solid black">KEPALA GUDANG</td>
                <td style="border:2px solid black">{{$data->kpl_gudang}}</td>
                <td style="border: 1px solid white"></td>
                <td style="border: 1px solid white"></td>
                <td style="border: 1px solid white"></td>
                <td style="border: 1px solid white"></td>
            </tr>
            @php
            $petugas = explode(";", $data->qc);
            @endphp
            <tr>
                <th class="font-weight-bold" style="border:2px solid black">DIPERIKSA OLEH</th>
                <td style="border:2px solid black">@if (isset($petugas[1])) 1. @endif {{ $petugas[0] }}</td>
                <td style="border-bottom:1px solid white"></td>
            </tr>

            @if (isset($petugas[1]))
            <tr>
                <td style="border:2px solid black"></td>
                <td style="border:2px solid black">2. {{ $petugas[1] }}</td>
                <td style="border-bottom:1px solid black"></td>
            </tr>

            @endif
            <tr>
                <td></td>
                <td style="border-top:1px solid black"></td>
            </tr>

        </thead>
    </table>

    @if ($data->unit == 'CWS1' || $data->unit == 'CWS2' || $data->unit == 'CWS3')
    <table class="table">
        <thead>
            <tr>
                <th class=" text-center table-primary" style="border: 2px solid black" colspan="2">
                    1.KESESUAIAN FISIK VS
                    BINCARD
                </th>
                <th class=" text-center table-primary" style="border: 2px solid black" colspan="2">
                    2.KESESUAIAN FISIK VS
                    PPRO
                </th>
                <th class=" text-center table-primary" style="border: 2px solid black" colspan="2">3.BARANG NON-STOCK
                </th>

            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="my-cell text-center" style="width:130px">HASIL</td>
                <td class="my-cell text-center" style="width: 350px;text-align: center;">FOTO</td>
                <td class="my-cell text-center" style="width:130px">HASIL</td>
                <td class="my-cell text-center" style="width: 350px;text-align: center;">FOTO</td>
                <td class="my-cell text-center" style="width:130px">HASIL</td>
                <td class="my-cell text-center" style="width: 350px;text-align: center;">FOTO</td>
            </tr>
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

                <td class="my-cell " style="width:150px"><span><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_kesesuaian_bincard_1}}" class="img-fluid modal-image"></span></td>

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
                <td class="my-cell" style="width:150px"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_kesesuaian_ppro_1}}" class="img-fluid  modal-image"></td>

                <td class="my-cell" rowspan="2">
                    {{ $data->barang_nonstok == 5 ? 'Tidak ada barang non-stock'
                    : ($data->barang_nonstok == 0 ? 'Ada barang non-stock' : '') }}
                </td>
                <td class="my-cell" style="width:150px"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_barang_nonstok_1}}" class="img-fluid modal-image"></td>

            </tr>

            <tr>
                <td class="my-cell "><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_kesesuaian_bincard_2}}" class="img-fluid modal-image"></td>

                <td class="my-cell"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_kesesuaian_ppro_2}}" class="img-fluid modal-image"></td>

                <td class="my-cell"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_barang_nonstok_2}}" class="img-fluid modal-image"></td>

            </tr>

            <tr>
                <td colspan="2" class="text-center my-cell">{{ $data->komentar_kesesuaian_bincard }}
                </td>
                <td colspan="2" class="text-center my-cell">{{ $data->komentar_kesesuaian_ppro }}
                </td>
                <td colspan="2" class="text-center my-cell">{{
                                        $data->barang_nonstok == 5 ? 'Ya Barang Non-Stock' :
                                        ($data->barang_nonstok == 0 ? 'Tidak Ada Barang Non-Stock' : '')
                                        }}
                </td>

            </tr>
            <tr class="">
                {{-- <th class="my-cell" scope="col"></th> --}}
                <th class="my-cell text-center table-primary" colspan="2">4.SELURUH MR DITANDATANGANI MANAGER CWS</th>
                {{-- <th class="my-cell" scope="col"></th> --}}
                <th class="my-cell text-center table-primary" colspan="2">5.KEBERSIHAN DAN KERAPIHAN GUDANG</th>
                {{-- <th class="my-cell" scope="col"></th> --}}
                <th class="my-cell text-center table-primary" colspan="2">6.BUKU INSPEKSI KTU (LOGBOOK KTU)</th>

            </tr>
            <tr>
                <td class="my-cell text-center">HASIL</td>
                <td class="my-cell text-center" style="width: 350px;text-align: center;">FOTO</td>
                <td class="my-cell text-center">HASIL</td>
                <td class="my-cell text-center" style="width: 350px;text-align: center;">FOTO</td>
                <td class="my-cell text-center">HASIL</td>
                <td class="my-cell text-center" style="width: 350px;text-align: center;">FOTO</td>

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
                <td class="my-cell"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_mr_ditandatangani_1}}" class="img-fluid modal-image"></td>
                @else
                <td><img src="{{asset('noimage.png')}}" style="weight:75pt;height:150pt"></td>
                @endif
                <td class="my-cell" rowspan="2">
                    {{$data->kebersihan_gudang + $data->bincard_terbungkus +
                    $data->peletakan_bincard + $data->rak_ditutup + $data->cat_sesuai}}
                </td>
                @if ($data->foto_kebersihan_gudang_1)
                <td class="my-cell"><img data-original-src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_kebersihan_gudang_1}}" src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_kebersihan_gudang_1}}" class="img-fluid modal-image"></td>
                @else
                <td><img src="{{asset('noimage.png')}}" style="weight:75pt;height:150pt"></td>
                @endif
                <td class="my-cell" rowspan="2">
                    {{ $data->inspeksi_ktu == 5 ? ' Logbook todate & lengkap'
                    : ($data->inspeksi_ktu == 0 ? ') Logbook tidak todate ' : '') }}
                </td>
                @if ($data->foto_inspeksi_ktu_1)
                <td class="my-cell"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_inspeksi_ktu_1}}" class="img-fluid modal-image"></td>
                @else
                <td><img src="{{asset('noimage.png')}}" style="weight:75pt;height:150pt"></td>
                @endif

            </tr>
            <tr>
                @if ($data->foto_mr_ditandatangani_2)
                <td class="my-cell"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_mr_ditandatangani_2}}" class="img-fluid modal-image"></td>
                @else
                <td><img src="{{asset('noimage.png')}}" style="weight:75pt;height:150pt"></td>
                @endif
                @if (isset($data->foto_kebersihan_gudang_2))
                <td class="my-cell"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_kebersihan_gudang_2}}" class="img-fluid modal-image"></td>
                @else
                <td><img src="{{asset('noimage.png')}}" style="weight:75pt;height:150pt"></td>
                @endif
                @if ($data->foto_inspeksi_ktu_2)
                <td class="my-cell"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_inspeksi_ktu_2}}" class="img-fluid modal-image"></td>
                @else
                <td><img src="{{asset('noimage.png')}}" style="weight:75pt;height:150pt"></td>
                @endif

            </tr>
            <tr>
                <td colspan="2" class="text-center my-cell">{{ $data->komentar_mr_ditandatangani }}
                </td>
                <td colspan="2" class="text-center my-cell" style="border-bottom:1px solid black">{{
                    $data->komentar_kebersihan_gudang }}
                </td>
                <td colspan="2" class="text-center my-cell">{{ $data->komentar_inspeksi_ktu }}
                </td>

            </tr>


        </tbody>
    </table>
    @else

    <table class="table">
        <thead>
            <tr>
                <th class=" text-center table-primary" style="border: 2px solid black" colspan="2">
                    1.KESESUAIAN FISIK VS
                    BINCARD
                </th>
                <th class=" text-center table-primary" style="border: 2px solid black" colspan="2">
                    2.KESESUAIAN FISIK VS
                    PPRO
                </th>
                <th class=" text-center table-primary" style="border: 2px solid black" colspan="2">3.BARANG
                    CHEMICAL
                    EXPIRED
                </th>

            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="my-cell text-center" style="width:130px">HASIL</td>
                <td class="my-cell text-center" style="width: 350px;text-align: center;">FOTO</td>
                <td class="my-cell text-center" style="width:130px">HASIL</td>
                <td class="my-cell text-center" style="width: 350px;text-align: center;">FOTO</td>
                <td class="my-cell text-center" style="width:130px">HASIL</td>
                <td class="my-cell text-center" style="width: 350px;text-align: center;">FOTO</td>
            </tr>
            <tr>
                <td class="my-cell" rowspan="2">
                    {{$data->kesesuaian_bincard == 15 ? 'Sesuai'
                    :($data->kesesuaian_bincard == 10 ? 'Selisih 1 Item Barang'
                    : ($data->kesesuaian_bincard == 5 ? 'Tidak Sesuai / Selisih >1 item barang'
                    :''))}}
                </td>

                <td class="my-cell " style="width:150px"><span><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_kesesuaian_bincard_1}}" class="img-fluid modal-image"></span></td>

                <td class="my-cell" rowspan="2">
                    {{$data->kesesuaian_ppro == 20 ? 'Sesuai'
                    :($data->kesesuaian_ppro == 15 ? 'Selisih 1 Item Barang'
                    : ($data->kesesuaian_ppro == 5 ? 'Tidak Sesuai / Selisih >1 item barang'
                    :''))}}
                </td>
                <td class="my-cell" style="width:150px"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_kesesuaian_ppro_1}}" class="img-fluid  modal-image"></td>

                <td class="my-cell" rowspan="2">{{$data->chemical_expired == 15 ? 'Tidak ada
                    chemical expired'
                    :($data->chemical_expired == 10 ? '< 10% jenis chemical expired '
                                            : ($data->chemical_expired == 5 ? '>= 10% jenis chemical expired' :''))}}
                </td>
                <td class="my-cell" style="width:150px"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_chemical_expired_1}}" class="img-fluid modal-image"></td>

            </tr>

            <tr>
                <td class="my-cell "><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_kesesuaian_bincard_2}}" class="img-fluid modal-image"></td>

                <td class="my-cell"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_kesesuaian_ppro_2}}" class="img-fluid modal-image"></td>

                <td class="my-cell"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_chemical_expired_2}}" class="img-fluid modal-image"></td>

            </tr>

            <tr>
                <td colspan="2" class="text-center my-cell">{{ $data->komentar_kesesuaian_bincard }}
                </td>
                <td colspan="2" class="text-center my-cell">{{ $data->komentar_kesesuaian_ppro }}
                </td>
                <td colspan="2" class="text-center my-cell">{{ $data->komentar_chemical_expired }}
                </td>

            </tr>
            <tr class="">
                {{-- <th class="my-cell" scope="col"></th> --}}
                <th class="my-cell text-center table-primary" colspan="2">4.BARANG NON-STOCK</th>
                {{-- <th class="my-cell" scope="col"></th> --}}
                <th class="my-cell text-center table-primary" colspan="2">5.SELURUH MR DITANDATANGANI EM</th>
                {{-- <th class="my-cell" scope="col"></th> --}}
                <th class="my-cell text-center table-primary" colspan="2">6.KEBERSIHAN DAN KERAPIHAN GUDANG</th>

            </tr>
            <tr>
                <td class="my-cell text-center">HASIL</td>
                <td class="my-cell text-center">FOTO</td>
                <td class="my-cell text-center">HASIL</td>
                <td class="my-cell text-center">FOTO</td>
                <td class="my-cell text-center">HASIL</td>
                <td class="my-cell text-center">FOTO</td>

            </tr>
            <tr>
                <td class="my-cell" rowspan="2">
                    {{
                    $data->barang_nonstok == 5 ? 'Ya' :
                    ($data->barang_nonstok == 0 ? 'Tidak Ada' : '')
                    }}
                </td>
                @if ($data->foto_barang_nonstok_1)
                <td class="my-cell"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_barang_nonstok_1}}" class="img-fluid modal-image"></td>
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
                <td class="my-cell"><img data-original-src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_mr_ditandatangani_1}}" src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_mr_ditandatangani_1}}" class="img-fluid modal-image"></td>
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
                <td class="my-cell"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_kebersihan_gudang_1}}" class="img-fluid modal-image"></td>
                @else
                <td><img src="{{asset('noimage.png')}}" style="weight:75pt;height:150pt"></td>
                @endif

            </tr>
            <tr>
                @if ($data->foto_barang_nonstok_2)
                <td class="my-cell"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_barang_nonstok_2}}" class="img-fluid modal-image"></td>
                @else
                <td><img src="{{asset('noimage.png')}}" style="weight:75pt;height:150pt"></td>
                @endif
                @if ($data->foto_mr_ditandatangani_2)
                <td class="my-cell"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_mr_ditandatangani_2}}" class="img-fluid modal-image"></td>
                @else
                <td><img src="{{asset('noimage.png')}}" style="weight:75pt;height:150pt"></td>
                @endif
                @if (isset($data->foto_kebersihan_gudang_2))
                <td class="my-cell"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_kebersihan_gudang_2}}" class="img-fluid modal-image"></td>
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
                <td colspan="2" class="text-center my-cell" style="border-bottom:1px solid black">{{
                    $data->komentar_mr_ditandatangani }}
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

                <!-- GUDANG  -->
                <td class="my-cell">HASIL</td>
                <td class="my-cell" style="text-align: center;">FOTO</td>
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

                <td class="my-cell" rowspan="2">{{$kondisigd}}</td>
                @if (isset($data->foto_kebersihan_gudang_3))
                <td class="my-cell col-md-4"><img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_gudang/{{$data->foto_kebersihan_gudang_3}}" class="img-fluid modal-image"></td>
                @else
                <td><img src="{{asset('noimage.png')}}" style="weight:75pt;height:150pt"></td>
                @endif
                @if (isset($data->foto_kebersihan_gudang_4))
                <td class="my-cell" rowspan="2">{{$kondisigd}}</td>
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
                <td style="border: 1px solid black"></td>
                @endif
            </tr>
            <tr>
                <td colspan="2" class="text-center my-cell">{{ $data->komentar_inspeksi_ktu }}
                </td>
                <td colspan="2" class="text-center my-cell">Dokumentasi Lainnya
                </td>
                @if (isset($data->foto_kebersihan_gudang_4))

                <td colspan="2" class="text-center my-cell">Dokumentasi Lainnya
                </td>
                @endif

            </tr>



            @else
            <tr class="table-primary">

                <th class="my-cell text-center" colspan="2">7. BUKU INSPEKSI KTU</th>

            </tr>
            <tr>
                <td class="my-cell">HASIL</td>
                <td class="my-cell text-center" style="width: 350px;text-align: center;">FOTO</td>


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

</body>

</html>