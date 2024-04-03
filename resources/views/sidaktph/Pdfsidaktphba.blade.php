<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>

<style>
    .my-table {
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
    }

    .my-table th {
        white-space: normal;
        overflow: hidden;
        text-overflow: ellipsis;
        font-size: 14px;
        /* Adjust the font size as needed */
    }

    .my-table td {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }


    /* Repeat the pattern for other columns as needed */

    th,
    td {
        border: 1px solid black;
        text-align: center;
        padding: 2px;
    }


    .header {
        display: flex;
        align-items: center;
    }

    .text-container {
        display: flex;
        flex-direction: column;
        justify-content: center;
        margin-left: 5px;
    }

    .logo-container {
        display: flex;
        align-items: center;
    }


    .logo {
        height: 60px;
        width: auto;
        align-items: flex-start;
    }

    .pt-name,
    .qc-name {
        margin: 0;
        padding-left: 1px;
    }

    .text-container {
        margin-left: 15px;
    }

    .right-container {
        text-align: right;

    }

    .form-inline {
        display: flex;
        align-items: center;
    }

    .custom-tables-container {
        display: flex;
        justify-content: space-between;
    }

    .custom-table {
        border-collapse: collapse;
        width: 45%;
    }

    .table-center {
        margin-left: auto;
        margin-right: auto;
    }


    .custom-table,
    .custom-table th,
    .custom-table td {
        border: 1px solid black;
        text-align: left;
        padding: 8px;
    }

    .my-custom-table {
        margin-right: 100px;
        margin-left: 10px;
    }


    .table-1-no-border td {
        border: none;
    }

    .hide-row {
        visibility: collapse;
    }

    .signature-cell {
        vertical-align: bottom;
        text-align: center;
        border: 1px solid black;
    }
</style>

<body>

    <!-- ganti sessuai kebutuhan landsacpe/potrait -->

    <!-- potrait -->
    <!-- <div class="content-wrapper" style="border: 1px solid #000; padding: 50px; min-height: calc(594mm - 100px); width: 420mm; margin: 0 auto;"> -->
    <!-- --- -->
    <!-- landscape -->
    <div class="content-wrapper" style="border: 1px solid #000; padding: 30px;">
        <!-- -- -->

        <style>
            .custom-border {
                border: 1px solid #000;
                padding: 20px;
                margin-top: 50px;
                margin-bottom: 50px;
            }
        </style>

        <div class="d-flex justify-content-center custom-border">
            <h2 class="text-center">REKAPITULASI SIDAK PEMERIKSAAN TPH, JALAN & BIN</h2>
        </div>

        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="vertical-align: middle; padding-left: 0; width: 10%;border:0;">
                    <div>
                        <img src="{{ asset('img/Logo-SSS.png') }}" style="height:60px">
                    </div>
                </td>
                <td style="width:30%;border:0;">

                    <p style="text-align: left;">PT. SAWIT SUMBERMAS SARANA,TBK</p>
                    <p style="text-align: left;">QUALITY CONTROL</p>

                </td>
                <td style=" width: 20%;border:0;">
                </td>
                <td style="vertical-align: middle; text-align: right;width:40%;border:0;">
                    <div class="right-container">
                        <div class="text-container">

                            <div class="afd">ESTATE :{{$data['est']}} </div>
                            <div class="afd">TANGGAL/BULAN: {{$data['awal']}}</div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>


        <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark ">
            <div class="Wraping">
                <table class="my-table">
                    <thead>
                        <tr>
                            <th colspan="3">Data Blok Sample</th>
                            <th colspan="16">Data Blok Temuan</th>
                        </tr>
                        <tr>
                            <th rowspan="2">Afdling</th>
                            <th rowspan="2">Blok Sample</th>
                            <th rowspan="2">Luas HA Sample</th>
                            <th colspan="2">H+1</th>
                            <th colspan="2">H+2</th>
                            <th colspan="2">H+3</th>
                            <th colspan="2">H+4</th>
                            <th colspan="2">H+5</th>
                            <th colspan="2">H+6</th>
                            <th colspan="2">H+7</th>
                            <th colspan="2">>H+7</th>
                        </tr>
                        <tr>
                            <th style="font-size: 14px">Brondolan(Butir)</th>
                            <th style="font-size: 14px">Buah Tinggal(Janjang)</th>
                            <th style="font-size: 14px">Brondolan(Butir)</th>
                            <th style="font-size: 14px">Buah Tinggal(Janjang)</th>
                            <th style="font-size: 14px">Brondolan(Butir)</th>
                            <th style="font-size: 14px">Buah Tinggal(Janjang)</th>
                            <th style="font-size: 14px">Brondolan(Butir)</th>
                            <th style="font-size: 14px">Buah Tinggal(Janjang)</th>
                            <th style="font-size: 14px">Brondolan(Butir)</th>
                            <th style="font-size: 14px">Buah Tinggal(Janjang)</th>
                            <th style="font-size: 14px">Brondolan(Butir)</th>
                            <th style="font-size: 14px">Buah Tinggal(Janjang)</th>
                            <th style="font-size: 14px">Brondolan(Butir)</th>
                            <th style="font-size: 14px">Buah Tinggal(Janjang)</th>
                            <th style="font-size: 14px">Brondolan(Butir)</th>
                            <th style="font-size: 14px">Buah Tinggal(Janjang)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['hitung'] as $key => $items)
                        @foreach ($items as $key1 => $items1)

                        @if ($key1 !== '') <!-- Add this condition to exclude the key EST -->
                        <tr>
                            <td>{{$key1}}</td>
                            <td style="white-space: pre-wrap;font-size: 12px">{{$items1['blok']}}</td>
                            <td>{{$items1['luas'] ?? ''}}</td>
                            <td>{{$items1['1']['brd'] ?? ''}}</td>
                            <td>{{$items1['1']['janjang'] ?? ''}}</td>
                            <td>{{$items1['2']['brd'] ?? ''}}</td>
                            <td>{{$items1['2']['janjang'] ?? ''}}</td>
                            <td>{{$items1['3']['brd'] ?? ''}}</td>
                            <td>{{$items1['3']['janjang'] ?? ''}}</td>
                            <td>{{$items1['4']['brd'] ?? ''}}</td>
                            <td>{{$items1['4']['janjang'] ?? ''}}</td>
                            <td>{{$items1['5']['brd'] ?? ''}}</td>
                            <td>{{$items1['5']['janjang'] ?? ''}}</td>
                            <td>{{$items1['6']['brd'] ?? ''}}</td>
                            <td>{{$items1['6']['janjang'] ?? ''}}</td>
                            <td>{{$items1['7']['brd'] ?? ''}}</td>
                            <td>{{$items1['7']['janjang'] ?? ''}}</td>
                            <td>{{$items1['8']['brd'] ?? ''}}</td>
                            <td>{{$items1['8']['janjang'] ?? ''}}</td>
                        </tr>
                        @endif

                        @endforeach
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark ">
            <div class="Wraping">
                <table class="my-table">
                    <thead>
                        <tr>
                            <th colspan="19">Rekapitulasi Penilaian Sidak Pemeriksaan TPH (Mutu Transport) </th>
                        </tr>
                        <tr>
                            <th rowspan="2">Afdeling</th>
                            <th colspan="2">H+1</th>
                            <th colspan="2">H+2</th>
                            <th colspan="2">H+3</th>
                            <th colspan="2">H+4</th>
                            <th colspan="2">H+5</th>
                            <th colspan="2">H+6</th>
                            <th colspan="2">H+7</th>
                            <th colspan="2">>H+7</th>
                            <th rowspan="2">Total (x)</th>
                            <th rowspan="2">Skor Akhir(100 - x)</th>
                        </tr>
                        <tr>
                            <th style="white-space: pre-wrap;font-size: 14px">Brondolan(Butir)</th>
                            <th style="white-space: pre-wrap;font-size: 14px">Buah Tinggal(Janjang)</th>
                            <th style="white-space: pre-wrap;font-size: 14px">Brondolan(Butir)</th>
                            <th style="white-space: pre-wrap;font-size: 14px">Buah Tinggal(Janjang)</th>
                            <th style="white-space: pre-wrap;font-size: 14px">Brondolan(Butir)</th>
                            <th style="white-space: pre-wrap;font-size: 14px">Buah Tinggal(Janjang)</th>
                            <th style="white-space: pre-wrap;font-size: 14px">Brondolan(Butir)</th>
                            <th style="white-space: pre-wrap;font-size: 14px">Buah Tinggal(Janjang)</th>
                            <th style="white-space: pre-wrap;font-size: 14px">Brondolan(Butir)</th>
                            <th style="white-space: pre-wrap;font-size: 14px">Buah Tinggal(Janjang)</th>
                            <th style="white-space: pre-wrap;font-size: 14px">Brondolan(Butir)</th>
                            <th style="white-space: pre-wrap;font-size: 14px">Buah Tinggal(Janjang)</th>
                            <th style="white-space: pre-wrap;font-size: 14px">Brondolan(Butir)</th>
                            <th style="white-space: pre-wrap;font-size: 14px">Buah Tinggal(Janjang)</th>
                            <th style="white-space: pre-wrap;font-size: 14px">Brondolan(Butir)</th>
                            <th style="white-space: pre-wrap;font-size: 14px">Buah Tinggal(Janjang)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['hitung'] as $key => $items)
                        @foreach ($items as $key1 => $items1)
                        @php

                        @endphp

                        <tr>
                            <td>{{$key1}}</td>

                            <td>{{$items1['1']['skor_brd'] ?? ''}}</td>
                            <td>{{$items1['1']['skor_luas'] ?? ''}}</td>
                            <td>{{$items1['2']['skor_brd'] ?? ''}}</td>
                            <td>{{$items1['2']['skor_luas'] ?? ''}}</td>
                            <td>{{$items1['3']['skor_brd'] ?? ''}}</td>
                            <td>{{$items1['3']['skor_luas'] ?? ''}}</td>
                            <td>{{$items1['4']['skor_brd'] ?? ''}}</td>
                            <td>{{$items1['4']['skor_luas'] ?? ''}}</td>
                            <td>{{$items1['5']['skor_brd'] ?? ''}}</td>
                            <td>{{$items1['5']['skor_luas'] ?? ''}}</td>
                            <td>{{$items1['6']['skor_brd'] ?? ''}}</td>
                            <td>{{$items1['6']['skor_luas'] ?? ''}}</td>
                            <td>{{$items1['7']['skor_brd'] ?? ''}}</td>
                            <td>{{$items1['7']['skor_luas'] ?? ''}}</td>
                            <td>{{$items1['8']['skor_brd'] ?? ''}}</td>
                            <td>{{$items1['8']['skor_luas'] ?? ''}}</td>
                            <td>{{$items1['total_skor'] ?? ''}}</td>
                            <td>{{$items1['skor_akhir'] ?? ''}}</td>


                        </tr>

                        @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>


        <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3  border border-dark" style="padding: 10px;">

            <table class=" custom-table table-1-no-border" style="float: left; width: 40%;">
                <thead>
                    <tr>
                        <th class="text-center">Catatan Lainnya</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            -
                        </td>
                    </tr>
                    <tr>
                        <td>
                            -
                        </td>
                    </tr>
                    <tr>
                        <td>
                            -
                        </td>
                    </tr>
                    <tr>
                        <td>
                            -
                        </td>
                    </tr>
                    <tr>
                        <td>
                            -
                        </td>
                    </tr>
                    <tr>
                        <td>
                            -
                        </td>
                    </tr>
                    <tr>
                        <td>
                            -
                        </td>
                    </tr>
                    <tr>
                        <td>
                            -
                        </td>
                    </tr>
                </tbody>

            </table>
            <!-- Table 2 -->
            <table class="custom-table" style="float: right; width: 60%; border-collapse: collapse;" border="1">
                <thead>

                    <tr>
                        <th colspan="6" class="text-center">Dibuat</th>
                        <th colspan="3" class="text-center">Diterima</th>
                    </tr>
                </thead>
                <tbody>
                    <tr></tr>
                    <tr></tr>
                    <tr></tr>
                    <tr></tr>

                    <tr>
                        <td colspan="2" style="vertical-align: bottom;padding-top: 244px;text-align:center">__________</td>
                        <td colspan="2" style="vertical-align: bottom;padding-top: 244px;text-align:center">__________</td>
                        <td colspan="2" style="vertical-align: bottom;padding-top: 244px;text-align:center">__________</td>
                        <td colspan="3" style="vertical-align: bottom;text-align:center">__________</td>

                    </tr>
                    <tr>
                        <td colspan="6" style="text-align: center;">Quality Control</td>
                        <td colspan="3" style="text-align: center;">Estate Manager</td>
                    </tr>

                </tbody>
            </table>
            <div style="clear:both;"></div>
        </div>
        <div style="clear:both;"></div>
    </div>


</body>

</html>