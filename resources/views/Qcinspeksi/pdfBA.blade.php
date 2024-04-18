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
                        
                        .stamp {
                            display: flex;
                            flex-direction: column;
                            justify-content: center;
                            align-items: center;
                            width: 50%;
                            height: 140px;
                            margin-left: 25%;
                            border: 2px dashed gray; /* Change border style here */
                            border-radius: 20px; /* Adjust border-radius for slightly rounded corners */
                            transform: rotate(-10deg);
                            overflow: hidden; /* Ensure stamp-text stays inside the border */
                        }
                        
                        .stamp-logo {
                            width: 80px; /* Adjust logo size */
                            height: 80px;
                            margin-top: 4px;
                            object-fit: contain;
                            z-index: 1;
                        }
                        
                        .stamp-text {
                            text-align: center;
                            font-size: 16px;
                            font-weight: bold;
                            margin: 0;
                            z-index: 2; /* Ensure stamp-text appears above the logo */
                        }
                        
                        /* Details styles */
                        .details {
                            margin-top: 20px;
                            padding: 10px;
                            background-color: #f0f0f0;
                            border-radius: 5px;
                            font-size: 14px;
                        }
                    
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
        font-size: 17px;
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
                padding: 8px;
                /* margin-top: 50px;
                margin-bottom: 50px; */
            }
        </style>

        <div class="d-flex justify-content-center custom-border">
            <h2 class="text-center">BERITA ACARA REKAPITULASI PEMERIKSAAN KUALITAS PANEN QUALITY CONTROL</h2>
        </div>

        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="vertical-align: middle; padding-left: 0; width: 10%;border:0;">
                    <div>
                        <img src="{{ asset('img/Logo-SSS.png') }}" style="height:60px">
                    </div>
                </td>
                <td style="width:30%;border:0;">

                    <p style="text-align: left; font-size: 20px;">PT. SAWIT SUMBERMAS SARANA,TBK</p>
                    <p style="text-align: left;">QUALITY CONTROL</p>

                </td>
                <td style=" width: 20%;border:0;">
                </td>
                <td style="vertical-align: middle; text-align: right;width:40%;border:0;">
                    <div class="right-container">
                        <div class="text-container">
                            <div class="afd" style="font-size: 20px;">Periode pemeriksaan ke: _______________</div>
                            <div class="afd" style="font-size: 20px;">ESTATE/ AFD: {{$data['est']}} {{$data['afd']}}</div>
                            <div class="afd" style="font-size: 20px;">TANGGAL: {{$data['tanggal']}}</div>
                            <!--<div class="afd" style="font-size: 20px;">REG: {{$data['reg']}}</div>-->
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        @if ($data['reg'] === '1' || $data['reg'] === '3' || $data['reg'] === '4')
        <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark ">
            <div class="table-responsive">
                <table class="my-table">
                    <table class="my-table">
                        <thead>
                            <tr>
                                <th rowspan="4">Status Panen (H+…)</th>
                                <th colspan="8">Data Blok Sample</th>
                                <th colspan="13" rowspan="2">MUTU ANCAK (MA)</th>
                                <th colspan="5" rowspan="2">MUTU TRANSPORT (MT)</th>
                            </tr>
                            <tr>
                                <th rowspan="3">Nomor Blok</th>
                                <th rowspan="3">Luas Blok (Ha)</th>
                                <th rowspan="3">SPH</th>
                                <th rowspan="3">Jumlah Pokok Sampel</th>
                                <th rowspan="3">Luas Sampel (Ha)</th>
                                <th rowspan="3">Persen Sampel (%)</th>
                                <th rowspan="3">Jumlah Janjang Panen</th>
                                <th rowspan="3">AKP Realisasi</th>

                            </tr>
                            <tr>
                                <th colspan="5">Brondolan Tinggal</th>

                                <th colspan="6">Buah Tinggal</th>
                                <th colspan="2">Pelepah Sengklek</th>
                                <th rowspan="2">TPH Sample</th>
                                <th colspan="2">Brondolan Tinggal</th>
                                <th colspan="2">Buah Tinggal</th>

                            </tr>
                            <tr>
                                <!-- brondolan tinggal -->
                                <th>P</th>
                                <th>K</th>
                                <th>GL</th>
                                <th>Total</th>
                                <th>Butir / Jjg</th>
                                <!-- buah tinggal -->
                                <th>S</th>
                                <th>M1</th>
                                <th>M2</th>
                                <th>M3</th>
                                <th>Total</th>
                                <th>Persen (%)</th>
                                <!-- palepah -->
                                <th>Pokok</th>
                                <th> Persen (%)</th>
                                <!-- Transport -->
                                <th> Butir </th>
                                <th> Butir / TPH </th>
                                <th> Jjg </th>
                                <th> Jjg / TPH</th>
                            </tr>

                        </thead>
                        <tbody id="tab1">

                            @php
                            $mergedKeys = array_unique(array_merge(array_keys($data['mutuAncak']), array_keys($data['mutuTransport'])));
                            $rowCount = count($mergedKeys);
                            $emptyRows = 8 - $rowCount;
                            $totalPokokSample = 0;
                            $totalLuasHa =0;

                            $totaljumPanen =0;
                            $akp_real =0;
                            $total_p =0;
                            $total_k =0;
                            $total_gl =0;
                            $total_brdMA =0;
                            $brd_jjg =0;
                            $total_s =0;
                            $total_m1 =0;
                            $total_m2 =0;
                            $total_m3 =0;
                            $total_bh =0;
                            $buah_brd =0;
                            $total_ps =0;
                            $ps_persen =0;
                            $total_tph =0;
                            $total_brd =0;
                            $brd_tph =0;
                            $total_buah =0;
                            $buah_tph =0;
                            @endphp

                            @foreach ($mergedKeys as $key)
                            <tr>
                                @php
                                $totalPokokSample = 0;
                                $totalLuasHa = 0;
                                $totaljumPanen = 0;
                                $akp_real =0;
                                $total_p = 0;
                                $total_k = 0;
                                $total_gl = 0;
                                $total_brdMA =0;
                                $total_s = 0;
                                $total_m1 = 0;
                                $total_m2 = 0;
                                $total_m3 = 0;
                                $total_bh = 0;
                                $brd_jjg =0;
                                $buah_brd = 0;
                                $total_ps = 0;
                                $ps_persen=0;
                                $TotLuasSam =0;
                                $TotPersenSam =0;
                                $luasBloks =0;
                                $persen_sampNew =0;
                                foreach ($data['mutuAncak'] as $mutuAncak) {
                                $totalPokokSample += $mutuAncak['pokok_sample'] ?? 0;

                                $luasBloks += (float) number_format($mutuAncak['luas_blok'] ?? 0, 2);


                                $totaljumPanen += $mutuAncak['jml_jjg_panen'] ?? 0;
                                $akp_real = count_percent($totaljumPanen, $totalPokokSample);
                                $total_p += $mutuAncak['p_ma'] ?? 0;
                                $total_k += $mutuAncak['k_ma'] ?? 0;
                                $total_gl += $mutuAncak['gl_ma'] ?? 0;
                                $total_brdMA += $mutuAncak ['total_brd_ma'] ?? 0;
                                $brd_jjg = $totaljumPanen != 0 ? round(($total_brdMA / $totaljumPanen), 2) : 0;


                                $total_s += $mutuAncak['bhts_ma'] ?? 0;
                                $total_m1 += $mutuAncak['bhtm1_ma'] ?? 0;
                                $total_m2 += $mutuAncak['bhtm2_ma'] ?? 0;
                                $total_m3 += $mutuAncak['bhtm3_ma'] ?? 0;
                                $total_bh += $mutuAncak['tot_jjg_ma'] ?? 0;
                                $buah_brd = ($totaljumPanen + $total_bh) != 0 ? round(($total_bh / ($totaljumPanen + $total_bh)) * 100, 2) : 0;


                                $total_ps += $mutuAncak['ps_ma'] ?? 0;
                                $ps_persen =count_percent($total_ps, $totalPokokSample);
                                $avg = $data['avg']['average'];
                                $TotLuasSam += $mutuAncak['luas_ha'] ?? 0;

                                $persen_sampNew = $luasBloks !=0 ? round ($TotLuasSam / $luasBloks * 100,2) : '-';

                                $TotPersenSam = $avg != 0 ? round(($TotLuasSam / $avg), 2) : '-';



                                }

                                @endphp


                                @php
                                $total_tph =0;
                                $total_brd =0;
                                $brd_tph = 0;
                                $buah_tph = 0;
                                $total_buah =0;
                                foreach($data['mutuTransport'] as $transport){
                                $total_tph += $transport['tph_sample'] ?? 0;
                                $total_brd += $transport['bt_total'] ?? 0;
                                $brd_tph = round($total_brd / $total_tph, 2);
                                $total_buah += $transport['restan_total'] ?? 0;
                                $buah_tph = round($total_buah / $total_tph, 2);
                                }
                                @endphp
                                <td>{{ $data['mutuAncak'][$key]['status_panen'] ?? '-' }}</td>
                                <td>{{ $key }}</td>
                                <td>{{ $data['mutuAncak'][$key]['luas_blok'] ?? '-' }} </td>
                                <td>{{ $data['mutuAncak'][$key]['sph'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['pokok_sample'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['luas_ha'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['persenSamp'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['jml_jjg_panen'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['akp_real'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['p_ma'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['k_ma'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['gl_ma'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['total_brd_ma'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['btr_jjg_ma'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['bhts_ma'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['bhtm1_ma'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['bhtm2_ma'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['bhtm3_ma'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['tot_jjg_ma'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['jjg_tgl_ma'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['ps_ma'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['PerPSMA'] ?? '-' }}</td>
                                <td>{{ $data['mutuTransport'][$key]['tph_sample'] ?? '-' }}</td>
                                <td>{{ $data['mutuTransport'][$key]['bt_total'] ?? '-' }}</td>
                                <td>{{ $data['mutuTransport'][$key]['skor'] ?? '-' }}</td>
                                <td>{{ $data['mutuTransport'][$key]['restan_total'] ?? '-' }}</td>
                                <td>{{ $data['mutuTransport'][$key]['skor_restan'] ?? '-' }}</td>
                            </tr>
                            @endforeach
                            @for ($i = 0; $i < $emptyRows; $i++) <tr>
                                <td>&nbsp;</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                </tr>
                                @endfor

                                <tr>
                                    <td colspan="2">Total</td>
                                    <td>{{ isset($luasBloks) ? $luasBloks : '-' }}</td>

                                    <td>{{$data['sph_avg']}}</td>
                                    <td>{{ $totalPokokSample }}</td>
                                    <td>{{ isset($TotLuasSam) ? $TotLuasSam : '-' }}</td>

                                    <td>{{ isset($persen_sampNew) ? $persen_sampNew : '-' }}</td>
                                    <td>{{ isset($totaljumPanen) ? $totaljumPanen : '-' }}</td>
                                    <td>{{ isset($akp_real) ? $akp_real : '-' }}</td>
                                    <td>{{ isset($total_p) ? $total_p : '-' }}</td>
                                    <td>{{ isset($total_k) ? $total_k : '-' }}</td>
                                    <td>{{ isset($total_gl) ? $total_gl : '-' }}</td>
                                    <td>{{ isset($total_brdMA) ? $total_brdMA : '-' }}</td>
                                    <td>{{ isset($brd_jjg) ? $brd_jjg : '-' }}</td>
                                    <td>{{ isset($total_s) ? $total_s : '-' }}</td>
                                    <td>{{ isset($total_m1) ? $total_m1 : '-' }}</td>
                                    <td>{{ isset($total_m2) ? $total_m2 : '-' }}</td>
                                    <td>{{ isset($total_m3) ? $total_m3 : '-' }}</td>
                                    <td>{{ isset($total_bh) ? $total_bh : '-' }}</td>
                                    <td>{{ isset($buah_brd) ? $buah_brd : '-' }}</td>
                                    <td>{{ isset($total_ps) ? $total_ps : '-' }}</td>
                                    <td>{{ isset($ps_persen) ? $ps_persen : '-' }}</td>
                                    <td>{{ isset($total_tph) ? $total_tph : '-' }}</td>
                                    <td>{{ isset($total_brd) ? $total_brd : '-' }}</td>
                                    <td>{{ isset($brd_tph) ? $brd_tph : '-' }}</td>
                                    <td>{{ isset($total_buah) ? $total_buah : '-' }}</td>
                                    <td>{{ isset($buah_tph) ? $buah_tph : '-' }}</td>

                                </tr>
                        </tbody>
                    </table>
                </table>
            </div>
        </div>


        <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark ">
            <div class="Wraping">
                <table class="my-table">
                    <thead>
                        <tr>

                            <th colspan="16">Mutu Buah</th>
                            <th colspan="12">Keterangan</th>
                        </tr>
                        <tr>
                            <th rowspan="2">Nomor Blok</th>
                            <th rowspan="2">Total Janjang Sample</th>
                            <th colspan="2">Mentah</th>
                            <th colspan="2">Matang</th>
                            <th colspan="2">Lewat matang</th>
                            <th colspan="2">Janjang Kosong</th>
                            <th colspan="2">Abnormal</th>
                            <th colspan="2">Tidak Standar Vcut</th>
                            <th colspan="2">Alas Brondol</th>
                            <th colspan="12" rowspan="11"></th>

                        </tr>
                        <tr>
                            <th>jjg</th>
                            <th>%</th>
                            <th>jjg</th>
                            <th>%</th>
                            <th>jjg</th>
                            <th>%</th>
                            <th>jjg</th>
                            <th>%</th>
                            <th>jjg</th>
                            <th>%</th>
                            <th>jjg</th>
                            <th>%</th>
                            <th>Ya</th>
                            <th>%</th>
                        </tr>
                    </thead>
                    <tbody id="tab2">
                        @php
                        $mergedKeys = array_keys($data['mutuBuah']);
                        $rowCount = count($mergedKeys);
                        $emptyRows = 8 - $rowCount;
                        @endphp


                        @php
                        $total_jjg =0;
                        $bh_mntah = 0;
                        $bh_abnromal =0;
                        $perMnth = 0;
                        $bh_matang =0;
                        $perMasak =0;
                        $bh_over =0;
                        $bh_emoty =0;
                        $bh_vcut =0;
                        $alasTot = 0;
                        $blokJm =0;
                        $perMnth = 0;
                        $perMasak = 0;
                        $perOver =0;
                        $perAbr= 0;
                        $preVcut = 0;
                        $mempt =0;
                        $perKrg = 0;
                        foreach($data['mutuBuah'] as $buah){
                        $total_jjg += $buah['jml_janjang'] ?? 0;
                        $bh_mntah += $buah['jml_mentah'] ?? 0;
                        $bh_abnromal += $buah['jml_abnormal'] ?? 0;
                        $bh_matang += $buah['jml_masak'] ?? 0;
                        $bh_over += $buah['jml_over'] ?? 0;
                        $bh_vcut += $buah['jml_vcut'] ?? 0;
                        $bh_emoty += $buah ['jml_empty'] ?? 0;
                        $alasTot += $buah['count_alas_br_1'] ?? 0;
                        $blokJm += $buah['blok_mb'] ?? 0;

                        $denom = ($total_jjg - $bh_abnromal) != 0 ? ($total_jjg - $bh_abnromal) : 1;
                        $perMnth = $denom != 0 ? round(($bh_mntah / $denom) * 100, 2) : 0;
                        $perMasak = $denom != 0 ? round(($bh_matang / $denom) * 100, 2) : 0;
                        $perOver = $denom != 0 ? round(($bh_over / $denom) * 100, 2) : 0;
                        $perAbr= $denom != 0 ? round(($bh_abnromal / $denom) * 100, 2) : 0;
                        $preVcut = count_percent($bh_vcut, $total_jjg);
                        $mempt = $denom != 0 ? round(($bh_emoty / $denom) * 100, 2) : 0;
                        $perKrg = count_percent($alasTot, $blokJm);

                        }
                        @endphp

                        @foreach ($data['mutuBuah'] as $key =>$item)
                        <tr>
                            <td>{{$key}}</td>
                            <td>{{$item['jml_janjang']}}</td>
                            <td>{{$item['jml_mentah']}}</td>
                            <td>{{$item['PersenBuahMentah']}}</td>
                            <td>{{$item['jml_masak']}}</td>
                            <td>{{$item['PersenBuahMasak']}}</td>
                            <td>{{$item['jml_over']}}</td>
                            <td>{{$item['PersenBuahOver']}}</td>
                            <td>{{$item['jml_empty']}}</td>
                            <td>{{$item['PersenPerJanjang']}}</td>
                            <td>{{$item['jml_abnormal']}}</td>
                            <td>{{$item['PersenAbr']}}</td>
                            <td>{{$item['jml_vcut']}}</td>
                            <td>{{$item['PersenVcut']}}</td>
                            <td>{{$item['count_alas_br_1']}} / {{$item['blok_mb']}}</td>
                            <td>{{$item['PersenKrgBrd']}}</td>
                        </tr>
                        @endforeach
                        @for ($i = 0; $i < $emptyRows; $i++) <tr>
                            <td>&nbsp;</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>

                            </tr>
                            @endfor

                            <tr>
                                <td>Total</td>
                                <td>{{$total_jjg}}</td>
                                <td>{{$bh_mntah}}</td>
                                <td>{{$perMnth}}</td>
                                <td>{{$bh_matang}}</td>
                                <td>{{$perMasak}}</td>
                                <td>{{$bh_over}}</td>
                                <td>{{$perOver}}</td>
                                <td>{{$bh_emoty}}</td>
                                <td>{{$mempt}}</td>
                                <td>{{$bh_abnromal}}</td>
                                <td>{{$perAbr}}</td>
                                <td>{{$bh_vcut}}</td>
                                <td>{{$preVcut}}</td>
                                <td>{{$alasTot}}/{{$blokJm}}</td>
                                <td>{{$perKrg}}</td>
                            </tr>
                    </tbody>
                </table>
            </div>
        </div>

        @endif

        @if ($data['reg'] === '2' )

        <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark ">
            <div class="table-responsive">
                <table class="my-table">
                    <table class="my-table">
                        <thead>
                            <tr>
                                <th rowspan="4">Status Panen (H+…)</th>
                                <th colspan="10">Data Blok Sample</th>
                                <th colspan="13" rowspan="2">MUTU ANCAK (MA)</th>
                                <th colspan="5" rowspan="2">MUTU TRANSPORT (MT)</th>
                            </tr>
                            <tr>
                                <th rowspan="3">Nomor Blok</th>
                                <th colspan="2" rowspan="3">Ancak Pemanen</th>
                                <th rowspan="3">Luas Blok (Ha)</th>
                                <th rowspan="3">SPH</th>
                                <th rowspan="3">Jumlah Pokok Sampel</th>
                                <th rowspan="3">Luas Sampel (Ha)</th>
                                <th rowspan="3">Persen Sampel (%)</th>
                                <th rowspan="3">Jumlah Janjang Panen</th>
                                <th rowspan="3">AKP Realisasi</th>


                            </tr>
                            <tr>
                                <th colspan="5">Brondolan Tinggal</th>

                                <th colspan="6">Buah Tinggal</th>
                                <th colspan="2">Palepah Sengklek</th>
                                <th rowspan="2">TPH Sample</th>
                                <th colspan="2">Brondolan Tinggal</th>
                                <th colspan="2">Buah Tinggal</th>

                            </tr>
                            <tr>
                                <!-- brondolan tinggal -->
                                <th>P</th>
                                <th>K</th>
                                <th>GL</th>
                                <th>Total</th>
                                <th>Butir / Jjg</th>
                                <!-- buah tinggal -->
                                <th>S</th>
                                <th>M1</th>
                                <th>M2</th>
                                <th>M3</th>
                                <th>Total</th>
                                <th>Persen (%)</th>
                                <!-- palepah -->
                                <th>Pokok</th>
                                <th> Persen (%)</th>
                                <!-- Transport -->
                                <th> Butir </th>
                                <th> Butir / TPH </th>
                                <th> Jjg </th>
                                <th> Jjg / TPH</th>
                            </tr>

                        </thead>
                        <tbody id="tab1">

                            @php
                            $mergedKeys = array_unique(array_merge(array_keys($data['mutuAncak']), array_keys($data['mutuTransport'])));
                            $rowCount = count($mergedKeys);
                            $emptyRows = 8 - $rowCount;
                            $totalPokokSample = 0;
                            $totalLuasHa =0;

                            $totaljumPanen =0;
                            $akp_real =0;
                            $total_p =0;
                            $total_k =0;
                            $total_gl =0;
                            $total_brdMA =0;
                            $brd_jjg =0;
                            $total_s =0;
                            $total_m1 =0;
                            $total_m2 =0;
                            $total_m3 =0;
                            $total_bh =0;
                            $buah_brd =0;
                            $total_ps =0;
                            $ps_persen =0;
                            $total_tph =0;
                            $total_brd =0;
                            $brd_tph =0;
                            $total_buah =0;
                            $buah_tph =0;
                            @endphp

                            @foreach ($mergedKeys as $key)
                            <tr>
                                @php
                                $totalPokokSample = 0;
                                $totalLuasHa = 0;
                                $totaljumPanen = 0;
                                $akp_real =0;
                                $total_p = 0;
                                $total_k = 0;
                                $total_gl = 0;
                                $total_brdMA =0;
                                $total_s = 0;
                                $total_m1 = 0;
                                $total_m2 = 0;
                                $total_m3 = 0;
                                $total_bh = 0;
                                $brd_jjg =0;
                                $buah_brd = 0;
                                $total_ps = 0;
                                $ps_persen=0;
                                $TotLuasSam =0;
                                $TotPersenSam =0;
                                $luasBloks =0;
                                $persen_sampNew =0;
                                $TotLuasBlok =0;
                                foreach ($data['mutuAncak'] as $mutuAncak) {
                                $totalPokokSample += $mutuAncak['pokok_sample'] ?? 0;

                                $luasBloks += (float) number_format($mutuAncak['luas_blok'] ?? 0, 2);



                                $totaljumPanen += $mutuAncak['jml_jjg_panen'] ?? 0;


                                $total_p += $mutuAncak['p_ma'] ?? 0;
                                $total_k += $mutuAncak['k_ma'] ?? 0;
                                $total_gl += $mutuAncak['gl_ma'] ?? 0;
                                $total_brdMA += $mutuAncak ['total_brd_ma'] ?? 0;
                                $brd_jjg = $totaljumPanen != 0 ? round(($total_brdMA / $totaljumPanen), 2) : 0;


                                $total_s += $mutuAncak['bhts_ma'] ?? 0;
                                $total_m1 += $mutuAncak['bhtm1_ma'] ?? 0;
                                $total_m2 += $mutuAncak['bhtm2_ma'] ?? 0;
                                $total_m3 += $mutuAncak['bhtm3_ma'] ?? 0;
                                $total_bh += $mutuAncak['tot_jjg_ma'] ?? 0;
                                $buah_brd = ($totaljumPanen + $total_bh) != 0 ? round(($total_bh / ($totaljumPanen + $total_bh)) * 100, 2) : 0;
                                $akp_real = round( (($totaljumPanen + $total_bh) /$totalPokokSample *100),2);
                                $total_ps += $mutuAncak['ps_ma'] ?? 0;
                                $ps_persen =count_percent($total_ps, $totalPokokSample);
                                $avg = $data['avg']['average'];
                                $TotLuasSam += $mutuAncak['luas_ha'] ?? 0;
                                $TotLuasBlok += $mutuAncak['luas_blok'] ?? 0;

                                $persen_sampNew = $luasBloks !=0 ? round ($TotLuasSam / $luasBloks * 100,2) : '-';

                                $TotPersenSam = $avg != 0 ? round(($totalPokokSample / $data['sph_avg']), 2) : '-';

                                $newtodpersn = round (($TotPersenSam / $TotLuasBlok) * 100,2);

                                }

                                @endphp


                                @php
                                $total_tph = 0;
                                $total_brd = 0;
                                $brd_tph = 0;
                                $buah_tph = 0;
                                $total_buah = 0;
                                foreach($data['mutuTransport'] as $transport){
                                $total_tph += $transport['tph_sample'] ?? 0;
                                $total_brd += $transport['bt_total'] ?? 0;
                                $brd_tph = $total_tph != 0 ? round($total_brd / $total_tph, 2) : 0;
                                $total_buah += $transport['restan_total'] ?? 0;
                                $buah_tph = $total_tph != 0 ? round($total_buah / $total_tph, 2) : 0;
                                }
                                @endphp


                                <td>{{ $data['mutuAncak'][$key]['status_panen'] ?? $data['mutuTransport'][$key]['status_panen'] }}</td>
                                <td>{{ $key }}</td>
                                <td colspan="2" style="white-space: pre-wrap;font-size: 11px">{{ $data['mutuAncak'][$key]['ancak_panenReg2'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['luas_blok'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['sph'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['pokok_sample'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['luas_ha'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['persenSamp'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['jml_jjg_panen'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['akp_real'] ?? '-' }}</td>

                                <td>{{ $data['mutuAncak'][$key]['p_ma'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['k_ma'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['gl_ma'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['total_brd_ma'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['btr_jjg_ma'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['bhts_ma'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['bhtm1_ma'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['bhtm2_ma'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['bhtm3_ma'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['tot_jjg_ma'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['jjg_tgl_ma'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['ps_ma'] ?? '-' }}</td>
                                <td>{{ $data['mutuAncak'][$key]['PerPSMA'] ?? '-' }}</td>

                                <td>{{ $data['mutuTransport'][$key]['tph_sample'] ?? '-' }}</td>
                                <td>{{ $data['mutuTransport'][$key]['bt_total'] ?? '-' }}</td>
                                <td>{{ $data['mutuTransport'][$key]['skor'] ?? '-' }}</td>
                                <td>{{ $data['mutuTransport'][$key]['restan_total'] ?? '-' }}</td>
                                <td>{{ $data['mutuTransport'][$key]['skor_restan'] ?? '-' }}</td>
                                <!-- <td>{{ $data['mutuTransport'][$key]['panes_status'] ?? '-' }}</td> -->
                            </tr>
                            @endforeach
                            @for ($i = 0; $i < $emptyRows; $i++) <tr>
                                <td>&nbsp;</td>
                                <td></td>
                                <td colspan="2"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>

                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>

                                </tr>
                                @endfor

                                <tr>
                                    <td colspan="4">Total</td>
                                    <td>{{ $TotLuasBlok }}</td>
                                    <td>{{$data['sph_avg']}}</td>

                                    <td>{{ $totalPokokSample }}</td>
                                    <td>{{$TotLuasSam}}</td>
                                    <!--<td>oke</td>-->
                                    <td>{{$newtodpersn}}</td>
                                    <td>{{$totaljumPanen}}</td>
                                    <td>{{$akp_real}}</td>

                                    <td>{{$total_p}}</td>
                                    <td>{{$total_k}}</td>
                                    <td>{{$total_gl}}</td>
                                    <td>{{$total_brdMA}}</td>
                                    <td>{{$brd_jjg}}</td>
                                    <td>{{ $total_s}}</td>
                                    <td>{{ $total_m1}}</td>
                                    <td>{{ $total_m2}}</td>
                                    <td>{{ $total_m3}}</td>
                                    <td>{{ $total_bh}}</td>
                                    <td>{{$buah_brd}}</td>
                                    <td>{{$total_ps}}</td>
                                    <td>{{$ps_persen}}</td>
                                    <td>{{$total_tph}}</td>
                                    <td>{{$total_brd}}</td>
                                    <td>{{$brd_tph}}</td>
                                    <td>{{$total_buah}}</td>
                                    <td>{{$buah_tph}}</td>
                                </tr>
                        </tbody>
                    </table>
                </table>
            </div>
        </div>


        <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark ">
            <div class="Wraping">
                <table class="my-table">
                    <thead>
                        <tr>

                            <th colspan="17">Mutu Buah</th>
                            <th colspan="12">Keterangan</th>
                        </tr>
                        <tr>
                            <th rowspan="2">Status
                                Panen
                                (H+…)</th>
                            <th rowspan="2">Nomor Blok</th>

                            <th rowspan="2">Total Janjang Sample</th>
                            <th colspan="2">Mentah</th>
                            <th colspan="2">Matang</th>
                            <th colspan="2">Lewat matang</th>
                            <th colspan="2">Janjang Kosong</th>
                            <th colspan="2">Abnormal</th>
                            <th colspan="2">Tidak Standar Vcut</th>
                            <th colspan="2">Alas Brondol</th>
                            <th colspan="14" rowspan="13"></th>



                        </tr>
                        <tr>
                            <th>jjg</th>
                            <th>%</th>
                            <th>jjg</th>
                            <th>%</th>
                            <th>jjg</th>
                            <th>%</th>
                            <th>jjg</th>
                            <th>%</th>
                            <th>jjg</th>
                            <th>%</th>
                            <th>jjg</th>
                            <th>%</th>
                            <th>Ya</th>
                            <th>%</th>
                        </tr>
                    </thead>
                    <tbody id="tab2">
                        @php
                        $mergedKeys = array_keys($data['mutuBuah']);
                        $rowCount = count($mergedKeys);
                        $emptyRows = 8 - $rowCount;
                        @endphp


                        @php
                        $total_jjg =0;
                        $bh_mntah = 0;
                        $bh_abnromal =0;
                        $perMnth = 0;
                        $bh_matang =0;
                        $perMasak =0;
                        $bh_over =0;
                        $bh_emoty =0;
                        $bh_vcut =0;
                        $alasTot = 0;
                        $blokJm =0;
                        $perMnth = 0;
                        $perMasak = 0;
                        $perOver =0;
                        $perAbr= 0;
                        $preVcut = 0;
                        $mempt =0;
                        $perKrg = 0;
                        foreach($data['mutuBuah'] as $buah){
                        $total_jjg += $buah['jml_janjang'] ?? 0;
                        $bh_mntah += $buah['jml_mentah'] ?? 0;
                        $bh_abnromal += $buah['jml_abnormal'] ?? 0;
                        $bh_matang += $buah['jml_masak'] ?? 0;
                        $bh_over += $buah['jml_over'] ?? 0;
                        $bh_vcut += $buah['jml_vcut'] ?? 0;
                        $bh_emoty += $buah ['jml_empty'] ?? 0;
                        $alasTot += $buah['count_alas_br_1'] ?? 0;
                        $blokJm += $buah['blok_mb'] ?? 0;

                        $denom = ($total_jjg - $bh_abnromal) != 0 ? ($total_jjg - $bh_abnromal) : 1;
                        $perMnth = $denom != 0 ? round(($bh_mntah / $denom) * 100, 2) : 0;
                        $perMasak = $denom != 0 ? round(($bh_matang / $denom) * 100, 2) : 0;
                        $perOver = $denom != 0 ? round(($bh_over / $denom) * 100, 2) : 0;
                        $perAbr= $denom != 0 ? round(($bh_abnromal / $denom) * 100, 2) : 0;
                        $preVcut = count_percent($bh_vcut, $total_jjg);
                        $mempt = $denom != 0 ? round(($bh_emoty / $denom) * 100, 2) : 0;
                        $perKrg = count_percent($alasTot, $blokJm);

                        }
                        @endphp

                        @foreach ($data['mutuBuah'] as $key =>$item)
                        <tr>
                            <td>{{$item['status_panen']}}</td>
                            <td>{{$key}}</td>
                            <td>{{$item['jml_janjang']}}</td>
                            <td>{{$item['jml_mentah']}}</td>
                            <td>{{$item['PersenBuahMentah']}}</td>
                            <td>{{$item['jml_masak']}}</td>
                            <td>{{$item['PersenBuahMasak']}}</td>
                            <td>{{$item['jml_over']}}</td>
                            <td>{{$item['PersenBuahOver']}}</td>
                            <td>{{$item['jml_empty']}}</td>
                            <td>{{$item['PersenPerJanjang']}}</td>
                            <td>{{$item['jml_abnormal']}}</td>
                            <td>{{$item['PersenAbr']}}</td>
                            <td>{{$item['jml_vcut']}}</td>
                            <td>{{$item['PersenVcut']}}</td>
                            <td>{{$item['count_alas_br_1']}} / {{$item['blok_mb']}}</td>
                            <td>{{$item['PersenKrgBrd']}}</td>
                        </tr>
                        @endforeach
                        @for ($i = 0; $i < $emptyRows; $i++) <tr>
                            <td>&nbsp;</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>

                            </tr>
                            @endfor

                            <tr>
                                <!-- <td>-</td> -->
                                <td colspan="2">Total</td>

                                <td>{{$total_jjg}}</td>
                                <td>{{$bh_mntah}}</td>
                                <td>{{$perMnth}}</td>
                                <td>{{$bh_matang}}</td>
                                <td>{{$perMasak}}</td>
                                <td>{{$bh_over}}</td>
                                <td>{{$perOver}}</td>
                                <td>{{$bh_emoty}}</td>
                                <td>{{$mempt}}</td>
                                <td>{{$bh_abnromal}}</td>
                                <td>{{$perAbr}}</td>
                                <td>{{$bh_vcut}}</td>
                                <td>{{$preVcut}}</td>
                                <td>{{$alasTot}}/{{$blokJm}}</td>
                                <td>{{$perKrg}}</td>
                            </tr>
                    </tbody>
                </table>
            </div>
        </div>

        @endif


        <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3  border border-dark" style="padding: 10px;">

            <table class="custom-table table-1-no-border" style="float: left; width: 20%;">
                <thead>
                    <tr>
                        <th colspan="2" class="text-center">Catatan Lainnya(%)</th>
                    </tr>
                </thead>
                <tr>
                    <td>Frond Stacking</td>
                    <td>:{{$data['hitung']['frontstack'] }}</td>
                </tr>
                <!-- Add other rows for the remaining calculated values -->
                <tr>
                    <td>Pokok Kuning</td>
                    <td>:{{$data['hitung']['pokok_kuning'] }}</td>
                </tr>
                <tr>
                    <td>Piringan Semak</td>
                    <td>:{{$data['hitung']['piringansmk'] }}</td>
                </tr>
                <tr>
                    <td>Under Pruning</td>
                    <td>:{{$data['hitung']['under'] }}</td>
                </tr>
                <tr>
                    <td>Over Pruning</td>
                    <td>:{{$data['hitung']['overprun'] }}</td>
                </tr>
                <tr>
                    <td>Mentah Tanpa Brondol</td>
                    <td>:{{$data['hitung']['mentah_tpBrd'] }}</td>
                </tr>
                <tr>
                    <td>Mentah Kurang Brondol</td>
                    <td>:{{$data['hitung']['mentah_krngBRD'] }}</td>
                </tr>
                <tr>
                    <td>V-Cut</td>
                    <td>:{{$data['hitung']['vcutStack'] }}</td>
                </tr>
            </table>
            <!-- Table 2 -->
            <table class="custom-table" style="float: right; width: 80%; border-collapse: collapse;" border="1">
                <thead>
                    <tr>
                        <th colspan="12" class="text-center">Demikian hasil pemeriksaan ini dibuat dengan sebenar-benarnya tanpa rekayasa dan paksaan dari Siapapun,</th>
                    </tr>
                    <tr>
                        <th colspan="6" class="text-center">Dibuat</th>
                        <th colspan="2" class="text-center">Diterima</th>
                        <th colspan="4" class="text-center">Diketahui</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @php
                            $totalpetugas= count($data['finalpetugas']);
                        @endphp
                        @if ($totalpetugas == 1)
                        <td colspan="6" style="vertical-align: bottom;text-align:center;padding-top:45px">
                            <div class="stamp-container">
                                <div class="stamp">
                                    <img src="{{ asset('img/CBIpreview.png') }}" alt="Logo" class="stamp-logo">
                                    <div class="stamp-text">Created</div>
                                    <div class="stamp-text">{{$data['tanggal']}}</div>
                                </div>
                                <div class="details">
                                    <div> {{$data['finalpetugas'][0]}}</div>
                                    <div>Petugas Quality Control </div>
                                </div>
                            </div>
                        </td>
                        @elseif ($totalpetugas == 2)
                        <td colspan="3" style="vertical-align: bottom;text-align:center;padding-top:45px">
                            <div class="stamp-container">
                                <div class="stamp">
                                    <img src="{{ asset('img/CBIpreview.png') }}" alt="Logo" class="stamp-logo">
                                    <div class="stamp-text">Created</div>
                                    <div class="stamp-text">{{$data['tanggal']}}</div>
                                </div>
                                <div class="details">
                                    <div> {{$data['finalpetugas'][0]}}</div>
                                    <div>Petugas Quality Control </div>
                                </div>
                            </div>
                        </td>
                        <td colspan="3" style="vertical-align: bottom;text-align:center;padding-top:45px">
                            <div class="stamp-container">
                                <div class="stamp">
                                    <img src="{{ asset('img/CBIpreview.png') }}" alt="Logo" class="stamp-logo">
                                    <div class="stamp-text">Created</div>
                                    <div class="stamp-text">{{$data['tanggal']}}</div>
                                </div>
                                <div class="details">
                                    <div> {{$data['finalpetugas'][1]}}</div>
                                    <div>Petugas Quality Control </div>
                                </div>
                            </div>
                        </td>
                        @elseif ($totalpetugas == 3)
                        <td colspan="2" style="vertical-align: bottom;text-align:center">
                            <div class="stamp-container">
                                <div class="stamp">
                                    <img src="{{ asset('img/CBIpreview.png') }}" alt="Logo" class="stamp-logo">
                                    <div class="stamp-text">Created</div>
                                    <div class="stamp-text">{{$data['tanggal']}}</div>
                                </div>
                                <div class="details">
                                    <div> {{$data['finalpetugas'][0]}}</div>
                                    <div>Petugas Quality Control </div>
                                </div>
                            </div>
                        </td>
                        <td colspan="2" style="vertical-align: bottom;text-align:center">
                            <div class="stamp-container">
                                <div class="stamp">
                                    <img src="{{ asset('img/CBIpreview.png') }}" alt="Logo" class="stamp-logo">
                                    <div class="stamp-text">Created</div>
                                    <div class="stamp-text">{{$data['tanggal']}}</div>
                                </div>
                                <div class="details">
                                    <div> {{$data['finalpetugas'][1]}}</div>
                                    <div>Petugas Quality Control </div>
                                </div>
                            </div>
                        </td>
                        <td colspan="2" style="vertical-align: bottom;text-align:center">
                            <div class="stamp-container">
                                <div class="stamp">
                                    <img src="{{ asset('img/CBIpreview.png') }}" alt="Logo" class="stamp-logo">
                                    <div class="stamp-text">Created</div>
                                    <div class="stamp-text">{{$data['tanggal']}}</div>
                                </div>
                                <div class="details">
                                    <div> {{$data['finalpetugas'][2]}}</div>
                                    <div>Petugas Quality Control </div>
                                </div>
                            </div>
                        </td>
                        @endif
                        
                        @if ($data['statusdata']['status'] === 'not_approved')
                        <td colspan="2" style="vertical-align: bottom; text-align: center">Asisten Estate Tidak Terverifikasi Secara Digital</td>
                        <td colspan="4" style="vertical-align: bottom; text-align: center">Manajer/Askep Estate Tidak Terverifikasi Secara Digital</td>
                        @else
                        <td colspan="2" style="vertical-align: bottom; text-align: center">
                            @if ($data['statusdata']['nama_asisten'] != null)
                                <div class="stamp-container">
                                    <div class="stamp">
                                        <img src="{{ asset('img/CBIpreview.png') }}" alt="Logo" class="stamp-logo">
                                        <div class="stamp-text">APPROVED</div>
                                        <div class="stamp-text">{{$data['statusdata']['approve_asisten']}}</div>
                                    </div>
                                    <div class="details">
                                        <div>{{$data['statusdata']['nama_asisten']}}</div>
                                        <div>Asisten {{$data['statusdata']['detail_asisten']}} <span>{{$data['statusdata']['lok_asisten']}}</span> </div>
                                    </div>
                                </div> 
                            @else
                                Asisten Tidak Terverifikasi Secara Digital
                            @endif
                        </td>
                        <td colspan="4" style="vertical-align: bottom; text-align: center">
                            @if ($data['statusdata']['nama_askep'] != null)
                                <div class="stamp-container">
                                    <div class="stamp">
                                        <img src="{{ asset('img/CBIpreview.png') }}" alt="Logo" class="stamp-logo">
                                        <div class="stamp-text">APPROVED</div>
                                        <div class="stamp-text">{{$data['statusdata']['approve_askep']}}</div>
                                    </div>
                                    <div class="details">
                                        <div>{{$data['statusdata']['nama_askep']}}</div>
                                        <div>Askep  {{$data['statusdata']['detail_askep']}} <span>{{$data['statusdata']['lok_askep']}}</span> </div>
                                    </div>
                                </div>
                            @elseif($data['statusdata']['nama_maneger'] != null)
                                <div class="stamp-container">
                                    <div class="stamp">
                                        <img src="{{ asset('img/CBIpreview.png') }}" alt="Logo" class="stamp-logo">
                                        <div class="stamp-text">APPROVED</div>
                                        <div class="stamp-text">{{$data['statusdata']['approve_maneger']}}</div>
                                    </div>
                                    <div class="details">
                                        <div>{{$data['statusdata']['nama_maneger']}}</div>
                                        <div>Manager {{$data['statusdata']['detail_manager']}} <span>{{$data['statusdata']['lok_manager']}}</span> </div>
                                    </div>
                                </div>
                            @else
                                Maneger/Askep Tidak Terverifikasi Secara Digital
                            @endif
                            
                        </td>
                        @endif   
                    </tr>

                </tbody>
            </table>
            <div style="clear:both;"></div>
        </div>
        <!-- Table 1 -->

        <div style="clear:both;"></div>
    </div>


</body>

</html>