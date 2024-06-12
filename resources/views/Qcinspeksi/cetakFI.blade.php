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
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        max-width: 100%;
    }

    .my-table {
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
    }

    .my-table th {
        white-space: normal;
        overflow: hidden;
        text-overflow: ellipsis;
        font-size: 15px;
    }

    .my-table td {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    th,
    td {
        border: 1px solid black;
        text-align: center;
        padding: 2px;
    }

    /* The rest of your CSS */

    .sticky-footer {
        margin-top: auto;
        /* Push the footer to the bottom */
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

    .custom-table,
    .custom-table th,
    .custom-table td {
        border: 1px solid black;
        text-align: left;
        padding: 8px;
    }



    .table-1-no-border td {
        border: none;
    }

    .content-wrapper {

        padding: 30px;
        box-sizing: border-box;
    }
</style>

<body>
    <?php
    function checkImageExists($imageUrl)
    {
        $headers = @get_headers($imageUrl);
        return (is_array($headers) && strpos($headers[0], '200 OK') !== false);
    }
    ?>

    <div class="content-wrapper">

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
            <h2 class="text-center">PEMERIKSAAN KUALITAS PANEN</h2>
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
                            @if($id == 4)
                            <div class="afd">VISIT : Gabungan</div>
                            @else
                            <div class="afd">VISIT : {{$id}}</div>
                            @endif
                            <div class="afd">ESTATE :{{$est}} </div>
                            <div class="afd">TANGGAL: {{$date}} </div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
        <style>
            .Wraping {
                width: 100%;
                overflow-x: auto;
            }

            .my-table {
                width: 100%;
                border-collapse: collapse;
                font-family: Arial, sans-serif;
            }

            .my-table th,
            .my-table td {
                border: 1px solid #e0e0e0;
                padding: 8px;
                text-align: center;
            }

            .my-table th {
                background-color: #f5f5f5;
                font-weight: bold;
            }

            .my-table tr:nth-child(even) {
                background-color: #f9f9f9;
            }

            .my-table tr:hover {
                background-color: #f0f0f0;
            }

            .my-table img {
                width: 100px;
                height: 100px;
                object-fit: contain;
                border-radius: 5px;
            }

            .status-cell {
                font-weight: bold;
                color: white;
            }

            .status-tuntas {
                background-color: #4caf50;
            }

            .status-berkelanjutan {
                background-color: #ffc107;
            }

            .status-belum-tuntas {
                background-color: #f44336;
            }
        </style>


        <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark ">
            <div class="Wraping">
                <table class="my-table">
                    <thead>
                        <tr>
                            <th colspan="10">PEMERIKSAAN KUALITAS PANEN</th>
                        </tr>
                        <tr bgcolor="#e8ecdc">
                            <th class="align-middle" rowspan="2">No</th>
                            <th class="align-middle" rowspan="2">Blok</th>
                            <th class="align-middle" rowspan="2">EST</th>
                            <th class="align-middle" rowspan="2">AFD</th>
                            <th class="align-middle" rowspan="2">ISSUE</th>
                            <th colspan="4">FOTO</th>
                            <th class="align-middle" rowspan="2">STATUS</th>
                        </tr>
                        <tr bgcolor="#e8ecdc">
                            <th colspan="2">BEFORE</th>
                            <th colspan="2">AFTER</th>
                        </tr>

                        </tr>
                    </thead>
                    <tbody>
                        @if($id == 1 || $id == 2 || $id == 3 || $id == '1' || $id == '2' || $id == '3' )
                        <?php $counter = 1; ?>
                        @foreach ($newResult as $keyx => $items)
                        @foreach($items as $key => $itemsx)
                        <?php
                        $key_parts = explode(' ', $key);
                        $estate = $key_parts[0];
                        $afdeling = $key_parts[1];
                        $blok = $key_parts[2];
                        ?>
                        @foreach ($itemsx as $category => $category_items)
                        @foreach ($category_items as $mutu)
                        <?php
                        $komentar = $mutu['komentar'] ?? '';
                        $foto_temuan = $category === 'mutu_ancak' ? ($mutu['foto_temuan1'] ?? '') : ($mutu['foto_temuan'] ?? '');
                        $foto_temuan2 = $mutu['foto_temuan2'] ?? '';
                        $foto_fu = $category === 'mutu_ancak' ? ($mutu['foto_fu1'] ?? '') : ($mutu['foto_fu'] ?? '');
                        $foto_fu2 = $mutu['foto_fu2'] ?? '';
                        ?>


                        @if(!empty($komentar) || !empty($foto_temuan) || !empty($foto_temuan2) || !empty($foto_fu) || !empty($foto_fu2))
                        @if(isset($mutu['visit']) && $mutu['visit'] == $id)
                        <tr>
                            <td class="align-middle" width="5%">{{ $counter++ }}</td>
                            <td class="align-middle" width="5%">{{ $blok }}</td>
                            <td class="align-middle" width="5%">{{ $estate }}</td>
                            <td class="align-middle" width="5%">{{ $afdeling }}</td>
                            <td class="align-middle" style="white-space: normal; max-width: 150px;">{{ $komentar }}</td>

                            <!-- <td class="align-middle">{{ $foto_temuan }}</td> -->
                            <td class="align-middle" width="15%" style="position: relative;">
                                <?php
                                $imgUrl = '';
                                if (!empty($foto_temuan)) {
                                    if ($category === 'mutu_ancak') {
                                        $imgUrl = "https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_ma/{$foto_temuan}";
                                    } elseif ($category === 'mutu_transport') {
                                        $imgUrl = "https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_mt/{$foto_temuan}";
                                    } else { // Assuming the remaining category is mutu_buah
                                        $imgUrl = "https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_mb/{$foto_temuan}";
                                    }
                                }
                                ?>
                                @if(!empty($imgUrl) && checkImageExists($imgUrl))
                                <img src="{{ $imgUrl }}" style="width: 150pt; height: 150pt; object-fit: contain;">
                                @endif
                            </td>


                            <td class="align-middle" width="15%">
                                <?php
                                $imgUrl1 = '';
                                if (!empty($foto_temuan2)) {

                                    $imgUrl1 = "https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_ma/{{$foto_temuan2}}";
                                }
                                ?>
                                @if(!empty($imgUrl1) && checkImageExists($imgUrl1))
                                <img src="{{ $imgUrl1 }}" style="width:150pt;height:150pt">
                                @endif
                            </td>

                            <td class="align-middle" width="15%">
                                <?php
                                $imgUrl3 = '';
                                if (!empty($foto_fu)) {
                                    if ($category === 'mutu_ancak') {
                                        $imgUrl3 = "https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_ma/{$foto_fu}";
                                    } elseif ($category === 'mutu_transport') {
                                        $imgUrl3 = "https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_mt/{$foto_fu}";
                                    } else { // Assuming the remaining category is mutu_buah
                                        $imgUrl3 = "https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_mb/{$foto_fu}";
                                    }
                                }
                                ?>

                                @if(!empty($imgUrl3) && checkImageExists($imgUrl3))
                                <img src="{{ $imgUrl3 }}" style="width: 150pt; height: 150pt; object-fit: contain;">
                                @endif

                            </td>

                            <td class="align-middle" width="15%">
                                <?php
                                $imgUrl4 = '';
                                if (!empty($foto_fu2)) {

                                    $imgUrl4 = "https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_ma/{{$foto_fu2}}";
                                }
                                ?>
                                @if(!empty($imgUrl4) && checkImageExists($imgUrl4))
                                <img src="{{ $imgUrl4 }}" style="width:150pt;height:150pt">
                                @endif

                            </td>

                            @if(($foto_temuan !== '' && $foto_temuan2 !== '' && ($foto_fu !== '' || $foto_fu2 !== '')) || ($foto_temuan !== '' && $foto_fu !== ''))
                            <td class="align-middle status-cell status-tuntas" width="10%">
                                TUNTAS
                            </td>

                            @elseif($category === 'mutu_buah' && $foto_temuan !== '' && $foto_temuan2 === '' && $foto_fu === '' && $foto_fu2 === '')
                            <td class="align-middle status-cell status-berkelanjutan" width="10%">
                                BERKELANJUTAN
                            </td>
                            @elseif(($foto_temuan !== '' || $foto_temuan2 !== '') && ($foto_fu === '' || $foto_fu2 === '' || ($foto_temuan !== '' && $foto_fu === '')))
                            <td class="align-middle status-cell status-belum-tuntas" width="10%">
                                BELUM TUNTAS
                            </td>
                            @endif
                        </tr>
                        @endif
                        @endif
                        @endforeach
                        @endforeach
                        @endforeach
                        @endforeach
                        @else
                        <?php $counter = 1; ?>
                        @foreach ($newResult as $key => $items)
                        @foreach($items as $key => $itemsx)
                        <?php
                        $key_parts = explode(' ', $key);
                        $estate = $key_parts[0];
                        $afdeling = $key_parts[1];
                        $blok = $key_parts[2];
                        ?>
                        @foreach ($itemsx as $category => $category_items)
                        @foreach ($category_items as $mutu)
                        <?php
                        $komentar = $mutu['komentar'] ?? '';
                        $foto_temuan = $category === 'mutu_ancak' ? ($mutu['foto_temuan1'] ?? '') : ($mutu['foto_temuan'] ?? '');
                        $foto_temuan2 = $mutu['foto_temuan2'] ?? '';
                        $foto_fu = $category === 'mutu_ancak' ? ($mutu['foto_fu1'] ?? '') : ($mutu['foto_fu'] ?? '');
                        $foto_fu2 = $mutu['foto_fu2'] ?? '';
                        ?>


                        @if(!empty($komentar) || !empty($foto_temuan) || !empty($foto_temuan2) || !empty($foto_fu) || !empty($foto_fu2))

                        <tr>
                            <td class="align-middle" width="5%">{{ $counter++ }}</td>
                            <td class="align-middle" width="5%">{{ $blok }}</td>
                            <td class="align-middle" width="5%">{{ $estate }}</td>
                            <td class="align-middle" width="5%">{{ $afdeling }}</td>
                            <td class="align-middle" style="white-space: normal; max-width: 150px;">{{ $komentar }}</td>

                            <!-- <td class="align-middle">{{ $foto_temuan }}</td> -->
                            <td class="align-middle" width="15%" style="position: relative;">
                                <?php
                                $imgUrl = '';
                                if (!empty($foto_temuan)) {
                                    if ($category === 'mutu_ancak') {
                                        $imgUrl = "https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_ma/{$foto_temuan}";
                                    } elseif ($category === 'mutu_transport') {
                                        $imgUrl = "https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_mt/{$foto_temuan}";
                                    } else { // Assuming the remaining category is mutu_buah
                                        $imgUrl = "https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_mb/{$foto_temuan}";
                                    }
                                }
                                ?>
                                @if(!empty($imgUrl) && checkImageExists($imgUrl))
                                <img src="{{ $imgUrl }}" style="width: 150pt; height: 150pt; object-fit: contain;">
                                @endif
                            </td>


                            <td class="align-middle" width="15%">
                                <?php
                                $imgUrl1 = '';
                                if (!empty($foto_temuan2)) {

                                    $imgUrl1 = "https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_ma/{{$foto_temuan2}}";
                                }
                                ?>
                                @if(!empty($imgUrl1) && checkImageExists($imgUrl1))
                                <img src="{{ $imgUrl1 }}" style="width:150pt;height:150pt">
                                @endif
                            </td>

                            <td class="align-middle" width="15%">
                                <?php
                                $imgUrl3 = '';
                                if (!empty($foto_fu)) {
                                    if ($category === 'mutu_ancak') {
                                        $imgUrl3 = "https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_ma/{$foto_fu}";
                                    } elseif ($category === 'mutu_transport') {
                                        $imgUrl3 = "https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_mt/{$foto_fu}";
                                    } else { // Assuming the remaining category is mutu_buah
                                        $imgUrl3 = "https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_mb/{$foto_fu}";
                                    }
                                }
                                ?>

                                @if(!empty($imgUrl3) && checkImageExists($imgUrl3))
                                <img src="{{ $imgUrl3 }}" style="width: 150pt; height: 150pt; object-fit: contain;">
                                @endif

                            </td>

                            <td class="align-middle" width="15%">
                                <?php
                                $imgUrl4 = '';
                                if (!empty($foto_fu2)) {

                                    $imgUrl4 = "https://mobilepro.srs-ssms.com/storage/app/public/qc/inspeksi_ma/{{$foto_fu2}}";
                                }
                                ?>
                                @if(!empty($imgUrl4) && checkImageExists($imgUrl4))
                                <img src="{{ $imgUrl4 }}" style="width:150pt;height:150pt">
                                @endif

                            </td>

                            @if(($foto_temuan !== '' && $foto_temuan2 !== '' && ($foto_fu !== '' || $foto_fu2 !== '')) || ($foto_temuan !== '' && $foto_fu !== ''))
                            <td class="align-middle status-cell status-tuntas" width="10%">
                                TUNTAS
                            </td>

                            @elseif($category === 'mutu_buah' && $foto_temuan !== '' && $foto_temuan2 === '' && $foto_fu === '' && $foto_fu2 === '')
                            <td class="align-middle status-cell status-berkelanjutan" width="10%">
                                BERKELANJUTAN
                            </td>
                            @elseif(($foto_temuan !== '' || $foto_temuan2 !== '') && ($foto_fu === '' || $foto_fu2 === '' || ($foto_temuan !== '' && $foto_fu === '')))
                            <td class="align-middle status-cell status-belum-tuntas" width="10%">
                                BELUM TUNTAS
                            </td>
                            @endif
                        </tr>

                        @endif
                        @endforeach
                        @endforeach
                        @endforeach
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div style="clear:both;"></div>
    </div>


</body>

</html>