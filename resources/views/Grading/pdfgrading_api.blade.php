<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gradingmill</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .highlight {
            background-color: #f9f9f9;
            font-weight: bold;
        }

        .table-container {
            page-break-inside: avoid;
            flex: 1;
        }

        .image-table {
            width: 100%;
            border-collapse: collapse;
        }

        .image-table td {
            padding: 10px;
            text-align: center;
        }

        .image-table img {
            max-width: 100%;
            height: auto;
            display: flex;
            margin: 0 auto;
        }

        footer {
            position: fixed;
            bottom: 30;
            width: 100%;
            background-color: #f2f2f2;
            text-align: center;
            padding: 10px 0;
            font-size: 10px;
            border-top: 1px solid #000;
        }
    </style>
</head>

<body>

    <div style="text-align: center;border: 1px solid black;background-color:#D6E6F4;margin-top: 0; padding-top: 0;">
        <h3>BERITA ACARA GRADING TANDAN BUAH SEGAR DI PKS</h3>
    </div>
    <p style="text-align: center;">NO:{{$data['id']}}{{$data['mill']}}/QC-BAGTBS/{{$data['tanggal_titel']}}</p>


    <p style="margin: 0; padding: 0;">Telah dilakukan grading tandan Buah Segar (TBS) yang di lakukan secara random(sampling) terhadap satu truk dengan data grading sebagai berikut</p>
    <h3 style="margin: 0; padding-top: 10px; text-transform: capitalize;">DATA PKS</h3>
    <table>
        <tr>
            <td style="text-align: left;border:none;padding: 0;font-size:15px;width:15%">
                Hari Tanggal<br>
                Lokasi<br>
                Waktu Grading<br>
            </td>
            <td style="text-align: left;border:none;padding: 0;font-size:15px;">
                : {{$data['Tanggal']}}<br>
                : {{$data['mill']}}<br>
                : {{$data['waktu_grading']}}<br>
            </td>
        </tr>
    </table>
    <h3 style="margin: 0; padding-top: 10px; text-transform: capitalize;">DATA KEBUN</h3>
    <table style="margin-top: 0; padding-top: 0;">
        <tr>
            <td style="text-align: left;border:none;padding: 0;font-size:15px;width:12%">Estate</td>
            <td style="text-align: right;border:none;padding: 0;font-size:15px">:</td>
            <td style="text-align: left;border:none;padding: 0;font-size:15px">{{$data['estate']}}</td>
            <td style="text-align: left;border:none;padding: 0;font-size:15px">Jumlah Tandan SPB</td>
            <td style="text-align: left;border:none;padding: 0;font-size:15px">: {{$data['jjg_spb']}}</td>
        </tr>
        <tr>
            <td style="text-align: left;border:none;padding: 0;font-size:15px">Afdeling</td>
            <td style="text-align: right;border:none;padding: 0;font-size:15px">:</td>
            <td style="text-align: left;border:none;padding: 0;font-size:15px">{{$data['afdeling']}}</td>
            <td style="text-align: left;border:none;padding: 0;font-size:15px">Jumlah Tandan GRADING</td>
            <td style="text-align: left;border:none;padding: 0;font-size:15px">: {{$data['jjg_grading']}}</td>
        </tr>
        <tr>
            <td style="text-align: left;border:none;padding: 0;font-size:15px">Blok</td>
            <td style="text-align: right;border:none;padding: 0;font-size:15px">:</td>
            <td style="text-align: left;border:none;padding: 0;font-size:15px;width:45%">{{$data['list_blok']}}</td>
            <td style="text-align: left;border:none;padding: 0;font-size:15px">Jumlah Selisih Janjang</td>
            <td style="text-align: left;border:none;padding: 0;font-size:15px">: {{ abs($data['jjg_selisih']) }} ({{ abs($data['persentase_selisih']) }}%)</td>
        </tr>
        <tr>
            <td style="text-align: left;border:none;padding: 0;font-size:15px">No Polisi</td>
            <td style="text-align: right;border:none;padding: 0;font-size:15px">:</td>
            <td style="text-align: left;border:none;padding: 0;font-size:15px">{{$data['no_plat']}}</td>
            <td style="text-align: left;border:none;padding: 0;font-size:15px">Total Tonase</td>
            <td style="text-align: left;border:none;padding: 0;font-size:15px">: {{$data['tonase']}}</td>
        </tr>
        <tr>
            <td style="text-align: left;border:none;padding: 0;font-size:15px">Nama Supir</td>
            <td style="text-align: right;border:none;padding: 0;font-size:15px">:</td>
            <td style="text-align: left;border:none;padding: 0;font-size:15px">{{$data['supir']}}</td>
            <td style="text-align: left;border:none;padding: 0;font-size:15px;width:26%">Berat Rata-rata TBS (BJR)</td>
            <td style="text-align: left;border:none;padding: 0;font-size:15px">: {{$data['bjr']}}</td>
        </tr>

    </table>
    <h3 style="margin: 0; padding-top: 10px; text-transform: capitalize;">DATA HASIL GRADING</h3>
    <table style="font-size: 12px;padding-top: 10px;">
        <thead>
            <tr class="header">
                <th colspan="2">TBS MATANG RIPE</th>
                <th colspan="2">TBS Mentah Unripe</th>
                <th colspan="2">TBS Lewat Matang Overripe</th>
                <th colspan="2">Tandan Kosong Empty Bunch</th>
                <th colspan="2">TBS Busuk Rotten Bunch</th>
            </tr>
            <tr class="header">
                <th>JJG</th>
                <th>%</th>
                <th>JJG</th>
                <th>%</th>
                <th>JJG</th>
                <th>%</th>
                <th>JJG</th>
                <th>%</th>
                <th>JJG</th>
                <th>%</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{$data['Ripeness']}}</td>
                <td>{{$data['percentase_ripenes']}}</td>
                <td>{{$data['Unripe']}}</td>
                <td>{{$data['persenstase_unripe']}}</td>
                <td>{{$data['Overripe']}}</td>
                <td>{{$data['persentase_overripe']}}</td>
                <td>{{$data['empty_bunch']}}</td>
                <td>{{$data['persentase_empty_bunch']}}</td>
                <td>{{$data['rotten_bunch']}}</td>
                <td>{{$data['persentase_rotten_bunce']}}</td>
            </tr>
        </tbody>
    </table>

    <table style="font-size: 12px;padding-top:10px">
        <thead>
            <tr class="header">
                <th colspan="8">Tidak Normal / Abnormal</th>
                <th colspan="2">Brondolan</th>
                <th colspan="2">Kotoran / Sampah</th>
            </tr>
            <tr class="header">
                <th colspan="2">TBS Parthenocarp</th>
                <th colspan="2">TBS Hard Bunch</th>
                <th colspan="2">TBS Hitam / Sakit</th>
                <th colspan="2">TBS Kastrasi (&lt; 2.5 Kg)</th>
                <th colspan="2">Loose Fruit</th>
                <th colspan="2">Dirt</th>
            </tr>
            <tr class="header">
                <th>JJG</th>
                <th>%</th>
                <th>JJG</th>
                <th>%</th>
                <th>JJG</th>
                <th>%</th>
                <th>JJG</th>
                <th>%</th>
                <th>KG</th>
                <th>%</th>
                <th>KG</th>
                <th>%</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{$data['abn_partheno']}}</td>
                <td>{{$data['abn_partheno_percen']}}</td>
                <td>{{$data['abn_hard']}}</td>
                <td>{{$data['abn_hard_percen']}}</td>
                <td>{{$data['abn_sakit']}}</td>
                <td>{{$data['abn_sakit_percen']}}</td>
                <td>{{$data['abn_kastrasi']}}</td>
                <td>{{$data['abn_kastrasi_percen']}}</td>
                <td>{{$data['loose_fruit']}}</td>
                <td>{{$data['persentase_lose_fruit']}}</td>
                <td>{{$data['Dirt']}}</td>
                <td>{{$data['persentase']}}</td>
            </tr>
        </tbody>
    </table>

    <table style="font-size: 12px;padding-top:10px">
        <thead>
            <tr class="header">
                <th colspan="2">Tangkai Panjang</th>
                <th colspan="4">Tandan Buah Segar</th>
                <th colspan="6">Kelas TBS</th>
            </tr>
            <tr class="header">
                <th colspan="2">Long Stalk</th>
                <th colspan="2">Tandan Sudah Dipotong V</th>
                <th colspan="2">Tandan Belum Dipotong V</th>
                <th colspan="2">C (76 - 100% Buah Jadi)</th>
                <th colspan="2">B (52 - 75% Buah Jadi)</th>
                <th colspan="2">A (0 - 50% Buah Jadi)</th>
            </tr>
            <tr class="header">
                <th>JJG</th>
                <th>%</th>
                <th>JJG</th>
                <th>%</th>
                <th>JJG</th>
                <th>%</th>
                <th>JJG</th>
                <th>%</th>
                <th>JJG</th>
                <th>%</th>
                <th>JJG</th>
                <th>%</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{$data['stalk']}}</td>
                <td>{{$data['persentase_stalk']}}</td>
                <td>{{$data['vcut']}}</td>
                <td>{{$data['percentage_vcut']}}</td>
                <td>{{$data['not_vcut']}}</td>
                <td>{{$data['percentage_not_vcut']}}</td>
                <td>{{$data['kelas_c']}}</td>
                <td>{{$data['percentage_kelas_c']}}</td>
                <td>{{$data['kelas_b']}}</td>
                <td>{{$data['percentage_kelas_b']}}</td>
                <td>{{$data['kelas_a']}}</td>
                <td>{{$data['percentage_kelas_a']}}</td>
            </tr>
        </tbody>
    </table>
    @php
    $krg_brd = $data['resultKurangBrondol'];
    $krg_brd = explode(',', $krg_brd);
    $newArray = [];
    $currentPart = [];

    foreach ($krg_brd as $item) {
    $currentPart[] = $item;
    if (count($currentPart) == 14) {
    $newArray[] = implode('.', $currentPart);
    $currentPart = [];
    }
    }

    // Add the remaining part if it's not empty
    if (!empty($currentPart)) {
    $newArray[] = implode('.', $currentPart);
    }

    $new_krg_brd = '-';
    $fontsize_new_krg_brd = '10px'; // Default font size

    if (count($krg_brd) >= 10) { // Use count() to check array size
    $new_krg_brd = $newArray;
    } else {
    $new_krg_brd = $data['resultKurangBrondol'];
    }

    $tnp_brd = $data['resultTanpaBrondol'];
    $tnp_brd = explode(',', $tnp_brd);
    $newArray_tnp = [];
    $currentPart_tnp = [];

    foreach ($tnp_brd as $item) {
    $currentPart_tnp[] = $item;
    if (count($currentPart_tnp) == 14) {
    $newArray_tnp[] = implode('.', $currentPart_tnp);
    $currentPart_tnp = [];
    }
    }

    // Add the remaining part if it's not empty
    if (!empty($currentPart_tnp)) {
    $newArray_tnp[] = implode('.', $currentPart_tnp);
    }

    $new_tnp_brd = '-';
    $fontsize_newArray_tnp = '10px'; // Default font size

    if (count($tnp_brd) >= 10) { // Use count() to check array size
    $new_tnp_brd = $newArray_tnp;
    } else {
    $new_tnp_brd = $data['resultTanpaBrondol'];
    $fontsize_newArray_tnp = '10px'; // Change font size conditionally
    }
    @endphp

    <table style="font-size: 12px; padding-top: 10px;">
        <thead>
            <tr class="header">
                <th>Jenis Mentah</th>
                <th>Kode</th>
                <th>Rincian</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Tanpa Brondol</td>
                <td>No.Pemanen(Jumlah)</td>
                <td style="text-align: left; font-size: {{$fontsize_new_krg_brd}}">
                    @if(is_array($new_krg_brd))
                    @foreach($new_krg_brd as $dataitems)
                    {{$dataitems}}<br>
                    @endforeach
                    @else
                    {{$new_krg_brd}}
                    @endif
                </td>
                <td>{{$data['kurang_brondol']}}</td>
            </tr>
            <tr>
                <td>Kurang Brondol</td>
                <td>No.Pemanen(Jumlah)</td>
                <td style="text-align: left; font-size: {{$fontsize_newArray_tnp}}">
                    @if(is_array($new_tnp_brd))
                    @foreach($new_tnp_brd as $dataitems)
                    {{$dataitems}}<br>
                    @endforeach
                    @else
                    {{$new_tnp_brd}}
                    @endif
                </td>
                <td>{{$data['nol_brondol']}}</td>
            </tr>
        </tbody>
    </table>


    <div style="page-break-after: always;"></div>

    <div style="text-align: center;border: 1px solid black;background-color:#D6E6F4;margin-top: 0; padding-top: 0;">
        <h3>BERITA ACARA GRADING TANDAN BUAH SEGAR DI PKS</h3>
    </div>
    <div class="table-container">
        <h4>Dokumentasi Hasil Grading</h4>
        <table class="image-table">
            <tr>
                <td style="border: none;">
                    <img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/grading_mill/{{$data['foto'][0]}}" alt="Image 1">
                </td>
                <td style="border: none;">
                    @if(isset($data['foto'][1]))
                    <img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/grading_mill/{{$data['foto'][1]}}" alt="Image 2">
                    @endif

                </td>
            </tr>
            <tr>
                <td style="border: none;">
                    @if(isset($data['foto'][2]))
                    <img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/grading_mill/{{$data['foto'][2]}}" alt="Image 3">
                    @endif

                </td>
                <td style="border: none;">
                    @if(isset($data['foto'][3]))
                    <img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/grading_mill/{{$data['foto'][3]}}" alt="Image 4">
                    @endif

                </td>
            </tr>
        </table>
        <p>Demikian hasil dari grading kami lakukan dengan sebenar benarnya</p>

        <table>
            <tr>
                <th style="border:none;background-color: white"></th>
                <th>Dibuat Oleh</th>
            </tr>
            <tr>
                <td style="height: 15%;width:60%;border:none"></td>
                <td style="vertical-align: bottom;text-align:center;padding-top:2px">
                    <div class="stamp-container">
                        <div class="stamp">
                            <img src="{{ asset('img/CBIpreview.png') }}" alt="Logo" style="height: auto;width:69px">
                            <div class="stamp-text">Created</div>
                            <div class="stamp-text">{{$data['Tanggal']}}</div>
                        </div>

                    </div>
                </td>
            </tr>
            <tr>
                <th style="border:none;background-color: white"></th>
                <th>Asisten Grading</th>
            </tr>
        </table>
    </div>
    <footer>
        <p><strong>App Versi</strong> :{{$data['appvers']}} <strong>OS Versi</strong> {{$data['appvers']}} <strong>Tipe Hp</strong> {{$data['phone_version']}}</p>
    </footer>
</body>

</html>