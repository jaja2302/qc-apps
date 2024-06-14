<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gradingmill</title>
    <style>
        body {
            font-family: Arial, sans-serif;

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
        }

        .image-table {
            width: 100%;
            border-collapse: collapse;
        }

        .image-table td {
            padding: 10px;
            /* border: 1px solid #ccc; */
            text-align: center;
        }

        .image-table img {
            max-width: 100%;
            height: auto;
            display: flex;
            margin: 0 auto;
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
            <td style="text-align: left;border:none;padding: 0;font-size:15px">
                Estate<br>
                Afdeling<br>
                Blok<br>
                No Polisi<br>
                Nama Supir<br>
            </td>
            <td style="text-align: left;border:none;padding: 0;font-size:15px">
                : {{$data['estate']}}<br>
                : {{$data['afdeling']}}<br>
                : {{$data['list_blok']}}<br>
                : {{$data['no_plat']}}<br>
                : {{$data['supir']}}<br>
            </td>
            <td style="text-align: left;border:none;padding: 0;font-size:15px">
                Jumlah Tandan SPB<br>
                Jumlah Tandan GRADING<br>
                Jumlah Selisih Janjang<br>
                Total Tonase<br>
                Berat Rata-rata TBS (BJR)<br>
            </td>
            <td style="text-align: left;border:none;padding: 0;font-size:15px">
                : {{$data['jjg_spb']}}<br>
                : {{$data['jjg_grading']}}<br>
                : {{ abs($data['jjg_selisih']) }} ({{ abs($data['persentase_selisih']) }}%)<br>
                : {{$data['tonase']}}<br>
                : {{$data['bjr']}} Kg<br>
            </td>
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
                <th>JJG</th>
                <th>%</th>
                <th>JJG</th>
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
    <table style="font-size: 12px;padding-top:10px">
        <thead>
            <tr class="header">
                <th style="width: 10%;">Jenis Mentah</th>
                <th style="width: 10%;">Kode</th>
                <th>Rincian</th>
                <th style="width: 10%;">Total</th>
            </tr>

        </thead>
        <tbody>
            <tr>
                <td>Tanpa Brondol</td>
                <td>No.Pemanen(Jumlah)</td>
                <td style="text-align: left;">{{$data['resultKurangBrondol']}}</td>
                <td>{{$data['kurang_brondol']}}</td>
            </tr>
            <tr>
                <td>Kurang Brondol</td>
                <td>No.Pemanen(Jumlah)</td>
                <td style="text-align: left;">{{$data['resultTanpaBrondol']}}</td>
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

</body>

</html>