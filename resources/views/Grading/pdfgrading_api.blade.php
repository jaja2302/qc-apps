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
            max-width: 70%;
            height: auto;
            display: block;
            margin: 0 auto;
        }
    </style>
</head>

<body>

    @foreach ($data as $items)
    <div style="text-align: center;border: 1px solid black;background-color:#D6E6F4;margin-top: 0; padding-top: 0;">
        <h5>BERITA ACARA GRADING TANDAN BUAH SEGAR DI PKS</h5>
    </div>
    <p style="text-align: center;">NO:{{$items['mill']}}/QC-BAGTBS/30 Desember 2023</p>


    <p style="margin: 0; padding: 0;">Telah dilakukan grading tandan Buah Segar (TBS) yang di lakukan secara random(sampling) terhadap satu truk dengan data grading sebagai berikut</p>
    <h3 style="margin: 0; padding: 0; text-transform: capitalize;">DATA PKS</h3>
    <p style="margin: 0; padding: 0;">Hari Tanggal <span>: {{$items['Tanggal']}}</span></p>
    <p style="margin: 0; padding: 0;">Lokasi <span>: {{$items['mill']}}</span></p>
    <p style="margin: 0; padding: 0;">Waktu GRADING <span>: {{$items['waktu_grading']}}</span></p>
    <h3 style="margin: 0; padding: 0; text-transform: capitalize;">DATA KEBUN</h3>

    <table>
        <tr>
            <td style="text-align: left;border:none;padding: 0;font-size:15px">
                ESTATE: {{$items['estate']}}<br>
                AFDELING: {{$items['afdeling']}}<br>
                BLOK: {{$items['list_blok']}}<br>
                NO. POLISI: {{$items['no_plat']}}<br>
                NAMA SUPIR: {{$items['supir']}}<br>
            </td>
            <td style="text-align: left;border:none;padding: 0;font-size:15px">
                JUMLAH TANDAN SPB: {{$items['jjg_spb']}}<br>
                JUMLAH TANDAN GRADING: {{$items['jjg_grading']}}<br>
                JUMLAH SEUSAI JANGJANG: {{$items['jjg_selisih']}} ( {{$items['persentase_selisih']}}%)<br>
                TOTAL TONASE: {{$items['tonase']}}<br>
                BERAT RATA-RATA TBS (BRR): {{$items['bjr']}} Kg<br>
            </td>
        </tr>
    </table>
    <h4>DATA HASIL GRADING</h4>
    <table style="font-size: 12px;">
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
                <td>{{$items['Ripeness']}}</td>
                <td>{{$items['percentase_ripenes']}}</td>
                <td>{{$items['Unripe']}}</td>
                <td>{{$items['persenstase_unripe']}}</td>
                <td>{{$items['Overripe']}}</td>
                <td>{{$items['persentase_overripe']}}</td>
                <td>{{$items['empty_bunch']}}</td>
                <td>{{$items['persentase_empty_bunch']}}</td>
                <td>{{$items['rotten_bunch']}}</td>
                <td>{{$items['persentase_rotten_bunce']}}</td>
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
                <td>{{$items['abn_partheno']}}</td>
                <td>{{$items['abn_partheno_percen']}}</td>
                <td>{{$items['abn_hard']}}</td>
                <td>{{$items['abn_hard_percen']}}</td>
                <td>{{$items['abn_sakit']}}</td>
                <td>{{$items['abn_sakit_percen']}}</td>
                <td>{{$items['abn_kastrasi']}}</td>
                <td>{{$items['abn_kastrasi_percen']}}</td>
                <td>{{$items['loose_fruit']}}</td>
                <td>{{$items['persentase_lose_fruit']}}</td>
                <td>{{$items['Dirt']}}</td>
                <td>{{$items['persentase']}}</td>
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
                <td>{{$items['stalk']}}</td>
                <td>{{$items['persentase_stalk']}}</td>
                <td>92.94</td>
                <td>18</td>
                <td>7.06</td>
                <td>218</td>
                <td>85.49</td>
                <td>20</td>
                <td>7.84</td>
                <td>17</td>
                <td>6.67</td>
                <td>6.67</td>
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
                <td style="text-align: left;">{{$items['resultKurangBrondol']}}</td>
                <td>{{$items['kurang_brondol']}}</td>
            </tr>
            <tr>
                <td>Kurang Brondol</td>
                <td>No.Pemanen(Jumlah)</td>
                <td style="text-align: left;">{{$items['resultTanpaBrondol']}}</td>
                <td>{{$items['nol_brondol']}}</td>
            </tr>
        </tbody>
    </table>

    <div style="page-break-after: always;"></div>

    <div style="text-align: center;border: 1px solid black;background-color:#D6E6F4;margin-top: 0; padding-top: 0;">
        <h5>BERITA ACARA GRADING TANDAN BUAH SEGAR DI PKS</h5>
    </div>
    <div class="table-container">
        <h4>Dokumentasi Hasil Grading</h4>
        <table class="image-table">
            <tr>
                <td style="border: none;">
                    <img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/grading_mill/{{$items['foto'][0]}}" alt="Image 1">
                </td>
                <td style="border: none;">
                    @if(isset($items['foto'][1]))
                    <img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/grading_mill/{{$items['foto'][1]}}" alt="Image 2">
                    @endif

                </td>
            </tr>
            <tr>
                <td style="border: none;">
                    @if(isset($items['foto'][2]))
                    <img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/grading_mill/{{$items['foto'][2]}}" alt="Image 3">
                    @endif

                </td>
                <td style="border: none;">
                    @if(isset($items['foto'][3]))
                    <img src="https://mobilepro.srs-ssms.com/storage/app/public/qc/grading_mill/{{$items['foto'][3]}}" alt="Image 4">
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
                <td style="height: 15%;">Created</td>
            </tr>
            <tr>
                <th style="border:none;background-color: white"></th>
                <th>Asisten Grading</th>
            </tr>
        </table>
    </div>
    <div style="page-break-after: always;"></div>
    @endforeach
</body>

</html>