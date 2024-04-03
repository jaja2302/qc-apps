<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div class="container">
        @foreach ($data['total'] as $key => $item)
        @foreach ($item as $item2)

        <div style="display: flex; justify-content: center; margin-top: 1px; margin-bottom: 2px; margin-left: 3px; margin-right: 3px; border: 1px solid black; background-color: #f8f4f4">
            <h2 style="text-align: center;">FORM PEMERIKSAAN PERUMAHAN</h2>
        </div>


        <table style="width: 100%; border-collapse: collapse;">
            <tr>

                <td style="width:30%;border:0;">

                    <p style="text-align: left; font-size: 20px;">ESTATE-AFDELING : {{$item2['est'] ?? 0}}</p>
                    <p style="text-align: left; font-size: 20px;">TIPE-PERUMAHAN : {{$item2['tipe_rumah'] ?? 0}}</p>

                </td>
                <td style=" width: 20%;border:0;">
                </td>
                <td style="vertical-align: middle; text-align: right;width:40%;border:0;">
                    <div class="right-container">
                        <div class="text-container">

                            <p style="text-align: right; font-size: 20px;">TANGGAL : {{$item2['tanggal'] ?? 0}}</p>
                            <p style="text-align: right; font-size: 20px;">Rumah DIPERIKSA : {{$item2['penghuni'] ?? 0}}</p>

                        </div>
                    </div>
                </td>
            </tr>
        </table>
        <table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size : 18px">
            <tr>
                <th style="background-color: #e8ecdc;border: 1px solid black;" colspan="1">NO</th>
                <th style="background-color: #e8ecdc;border: 1px solid black;" colspan="2">PARAMETER</th>
                <TH style="background-color: #e8ecdc;border: 1px solid black;" colspan="2">CRITERIA</TH>
                <TH style="background-color: #e8ecdc;border: 1px solid black;" colspan="1">MAX POINT</TH>
                <TH style="background-color: #e8ecdc;border: 1px solid black;" colspan="1">REAL POINT</TH>
                <TH style="background-color: #e8ecdc;border: 1px solid black;" colspan="5">REMARKS</TH>
            </tr>

            <body>
                <!-- head + 1  -->
                <tr>
                    <th rowspan="14" style="background-color: #f8f4f4;border: 1px solid black;">I</th>
                    <th colspan="11" style="text-align: center;background-color: #f8f4f4;border: 1px solid black;">Rumah</th>
                </tr>
                <!-- endhead -->
                <tr>
                    <td rowspan="3" colspan="2" style="text-align: left;border: 1px solid black;">Instalasi Air</td>
                    <td colspan="2" style="text-align: left;border: 1px solid black;">Lancar Masuk (air dapat mengalir dengan lancar 24 jam ke dalam rumah)</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">4</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">{{$item2['perumahan_nilai1']?? 0 }}</td>
                    <td colspan="5" style="text-align: left;border: 1px solid black;">{{$item2['perumahan_komen1']?? 0 }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: left;border: 1px solid black;">Kontrol (Setiap bak mandi menggunakan pelampung)</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">4</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">{{$item2['perumahan_nilai2']?? 0 }}</td>
                    <td colspan="5" style="text-align: left;border: 1px solid black;">{{$item2['perumahan_komen2']?? 0 }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: left;border: 1px solid black;">Lancar Keluar (saluran pembuangan air lancar)</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">4</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">{{$item2['perumahan_nilai3']?? 0 }}</td>
                    <td colspan="5" style="text-align: left;border: 1px solid black;">{{$item2['perumahan_komen3']?? 0 }}</td>
                </tr>
                <!-- kebersihan dalam rumah  -->
                <tr>
                    <td rowspan="5" colspan="2" style="text-align: left;border: 1px solid black;">Kebersihan di dalam rumah</td>
                    <td colspan="2" style="text-align: left;border: 1px solid black;">Kaca & ventilasi (bersih dan tidak rusak)</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">2</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">{{$item2['perumahan_nilai4']?? 0 }}</td>
                    <td colspan="5" style="text-align: left;border: 1px solid black;">{{$item2['perumahan_komen4']?? 0 }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: left;border: 1px solid black;">Plafon (bersih dan tidak rusak)</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">2</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">{{$item2['perumahan_nilai5']?? 0 }}</td>
                    <td colspan="5" style="text-align: left;border: 1px solid black;">{{$item2['perumahan_komen5']?? 0 }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: left;border: 1px solid black;">Dinding (bersih dan tidak rusak)</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">2</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">{{$item2['perumahan_nilai6']?? 0 }}</td>
                    <td colspan="5" style="text-align: left;border: 1px solid black;">{{$item2['perumahan_komen6']?? 0 }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: left;border: 1px solid black;">Lantai (bersih dan tidak rusak)</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">2</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">{{$item2['perumahan_nilai7']?? 0 }}</td>
                    <td colspan="5" style="text-align: left;border: 1px solid black;">{{$item2['perumahan_komen7']?? 0 }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: left;border: 1px solid black;">Kamar Mandi (bersih dan tidak rusak)</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">2</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">{{$item2['perumahan_nilai8']?? 0 }}</td>
                    <td colspan="5" style="text-align: left;border: 1px solid black;">{{$item2['perumahan_komen8']?? 0 }}</td>
                </tr>
                <!-- listrik -->
                <tr>
                    <td rowspan="1" colspan="2" style="text-align: left;border: 1px solid black;">Listrik</td>
                    <td colspan="2" style="text-align: left;border: 1px solid black;">Instalasi (aman dan rapi)</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">4</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">{{$item2['perumahan_nilai9']?? 0 }}</td>
                    <td colspan="5" style="text-align: left;border: 1px solid black;">{{$item2['perumahan_komen9']?? 0 }}</td>
                </tr>
                <!-- alat pemadam kebakaran  -->
                <tr>
                    <td rowspan="1" colspan="2" style="text-align: left;border: 1px solid black;">Alat Pemadam Api Ringan (APAR)</td>
                    <td colspan="2" style="text-align: left;border: 1px solid black;">G2 -> 1 Unit; G4 -> 1 Unit; G6 -> 2 Unit; G10 -> 3 Unit</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">4</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">{{$item2['perumahan_nilai10']?? 0 }}</td>
                    <td colspan="5" style="text-align: left;border: 1px solid black;">{{$item2['perumahan_komen10']?? 0 }}</td>
                </tr>

                <!-- halaman rumah  -->
                <tr>
                    <td rowspan="1" colspan="2" style="text-align: left;border: 1px solid black;">Halaman Rumah</td>
                    <td colspan="2" style="text-align: left;border: 1px solid black;">Bersih</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">4</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">{{$item2['perumahan_nilai11']?? 0 }}</td>
                    <td colspan="5" style="text-align: left;border: 1px solid black;">{{$item2['perumahan_komen11']?? 0 }}</td>
                </tr>
                <!-- jemuran  -->
                <tr>
                    <td rowspan="1" colspan="2" style="text-align: left;border: 1px solid black;">Jemuran</td>
                    <td colspan="2" style="text-align: left;border: 1px solid black;">Rapi dan seragam</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">3</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">{{$item2['perumahan_nilai12']?? 0 }}</td>
                    <td colspan="5" style="text-align: left;border: 1px solid black;">{{$item2['perumahan_komen12']?? 0 }}</td>
                </tr>
                <!-- Estetika  -->
                <tr>
                    <td rowspan="1" colspan="2" style="text-align: left;border: 1px solid black;">Estetika</td>
                    <td colspan="2" style="text-align: left;border: 1px solid black;">Ditanami Bunga, Buah & sayur mayur</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">3</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">{{$item2['perumahan_nilai13']?? 0 }}</td>
                    <td colspan="5" style="text-align: left;border: 1px solid black;">{{$item2['perumahan_komen13']?? 0 }}</td>
                </tr>


                <!-- TH LINGKUNGA +1 -->
                <tr>
                    <th rowspan="15" style="background-color: #f8f4f4;border: 1px solid black;">II</th>
                    <th colspan="11" style="text-align: center;background-color: #f8f4f4;border: 1px solid black;">Lingkungan</th>
                </tr>
                <!-- FILTER AIR  -->
                <tr>
                    <td rowspan="1" colspan="2" style="text-align: left;border: 1px solid black;">Filter Air</td>
                    <td colspan="2" style="text-align: left;border: 1px solid black;">Tersedia 1 Unit untuk afdeling/estate yang tidak dialiri air bersih dari Pabrik</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">5</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">{{$item2['lingkungan_nilai1']?? 0 }}</td>
                    <td colspan="5" style="text-align: left;border: 1px solid black;">{{$item2['lingkungan_komen1']?? 0 }}</td>
                </tr>
                <!-- prroflie tank  -->
                <tr>
                    <td rowspan="1" colspan="2" style="text-align: left;border: 1px solid black;">Profil Tank</td>
                    <td colspan="2" style="text-align: left;border: 1px solid black;">Kapasitas minimal setara 200 Liter untuk 1 pintu</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">5</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">{{$item2['lingkungan_nilai2']?? 0 }}</td>
                    <td colspan="5" style="text-align: left;border: 1px solid black;">{{$item2['lingkungan_komen2']?? 0 }}</td>
                </tr>
                <!-- TPA  -->
                <tr>
                    <td rowspan="3" colspan="2" style="text-align: left;border: 1px solid black;">Tempat Penitipan Anak</td>
                    <td colspan="2" style="text-align: left;border: 1px solid black;">Karpet (Kecuali TPA dengan Keramik)</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">2</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">{{$item2['lingkungan_nilai3']?? 0 }}</td>
                    <td colspan="5" style="text-align: left;border: 1px solid black;">{{$item2['lingkungan_komen3']?? 0 }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: left;border: 1px solid black;">Mainan</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">1</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">{{$item2['lingkungan_nilai4']?? 0 }}</td>
                    <td colspan="5" style="text-align: left;border: 1px solid black;">{{$item2['lingkungan_komen4']?? 0 }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: left;border: 1px solid black;">Pagar</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">2</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">{{$item2['lingkungan_nilai5']?? 0 }}</td>
                    <td colspan="5" style="text-align: left;border: 1px solid black;">{{$item2['lingkungan_komen5']?? 0 }}</td>
                </tr>
                <!-- mushola  -->
                <tr>
                    <td rowspan="3" colspan="2" style="text-align: left;border: 1px solid black;"> Musholla/Masjid</td>
                    <td colspan="2" style="text-align: left;border: 1px solid black;">Ketersediaan air</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">2</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">{{$item2['lingkungan_nilai6']?? 0 }}</td>
                    <td colspan="5" style="text-align: left;border: 1px solid black;">{{$item2['lingkungan_komen6']?? 0 }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: left;border: 1px solid black;">Saluran Pembuangan air</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">1</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">{{$item2['lingkungan_nilai7']?? 0 }}</td>
                    <td colspan="5" style="text-align: left;border: 1px solid black;">{{$item2['lingkungan_komen7']?? 0 }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: left;border: 1px solid black;">Kebersihan lingkungan</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">2</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">{{$item2['lingkungan_nilai8']?? 0 }}</td>
                    <td colspan="5" style="text-align: left;border: 1px solid black;">{{$item2['lingkungan_komen8']?? 0 }}</td>
                </tr>
                <!-- drainase  -->
                <tr>
                    <td rowspan="2" colspan="2" style="text-align: left;border: 1px solid black;">Drainase</td>
                    <td colspan="2" style="text-align: left;border: 1px solid black;">Bersih</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">3</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">{{$item2['lingkungan_nilai9']?? 0 }}</td>
                    <td colspan="5" style="text-align: left;border: 1px solid black;">{{$item2['lingkungan_komen9']?? 0 }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: left;border: 1px solid black;">Lancar</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">2</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">{{$item2['lingkungan_nilai10']?? 0 }}</td>
                    <td colspan="5" style="text-align: left;border: 1px solid black;">{{$item2['lingkungan_komen10']?? 0 }}</td>
                </tr>
                <!-- truk sampah  -->
                <tr>
                    <td rowspan="1" colspan="2" style="text-align: left;border: 1px solid black;">Ketersediaan Tong Sampah</td>
                    <td colspan="2" style="text-align: left;border: 1px solid black;">Sedikitnya tersedia 1 tong sampah untuk 1 kopel rumah</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">4</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">{{$item2['lingkungan_nilai11']?? 0 }}</td>
                    <td colspan="5" style="text-align: left;border: 1px solid black;">{{$item2['lingkungan_komen11']?? 0 }}</td>
                </tr>
                <!-- Tmpat pmbuangan AKhir  -->
                <tr>
                    <td rowspan="2" colspan="2" style="text-align: left;border: 1px solid black;">Tempat Pembuangan Akhir</td>
                    <td colspan="2" style="text-align: left;border: 1px solid black;">Tidak ada ceceran sampah</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">2</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">{{$item2['lingkungan_nilai12']?? 0 }}</td>
                    <td colspan="5" style="text-align: left;border: 1px solid black;">{{$item2['lingkungan_komen12']?? 0 }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: left;border: 1px solid black;">Tidak ada penumpukan sampah</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">1</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">{{$item2['lingkungan_nilai13']?? 0 }}</td>
                    <td colspan="5" style="text-align: left;border: 1px solid black;">{{$item2['lingkungan_komen13']?? 0 }}</td>
                </tr>
                <!-- parkir motor  -->
                <tr>
                    <td rowspan="1" colspan="2" style="text-align: left;border: 1px solid black;">Parkir Motor</td>
                    <td colspan="2" style="text-align: left;border: 1px solid black;">Tersedia bangunan khusus</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">3</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">{{$item2['lingkungan_nilai14']?? 0 }}</td>
                    <td colspan="5" style="text-align: left;border: 1px solid black;">{{$item2['lingkungan_komen14']?? 0 }}</td>
                </tr>

                <!-- TH LandScape +1 -->
                <tr>
                    <th rowspan="6" style="background-color: #f8f4f4;border: 1px solid black;">III</th>
                    <th colspan="11" style="text-align: center;background-color: #f8f4f4;border: 1px solid black;">Landscape</th>
                </tr>
                <!-- materail  -->
                <tr>
                    <td rowspan="1" colspan="2" style="text-align: left;border: 1px solid black;">Material</td>
                    <td colspan="2" style="text-align: left;border: 1px solid black;">Ornamen-ornamen penghias taman</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">8</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">{{$item2['landscape_nilai1']?? 0 }}</td>
                    <td colspan="5" style="text-align: left;border: 1px solid black;">{{$item2['landscape_komen1']?? 0 }}</td>
                </tr>
                <!-- komposisi tanaman  -->
                <tr>
                    <td rowspan="1" colspan="2" style="text-align: left;border: 1px solid black;">Komposisi Tanaman</td>
                    <td colspan="2" style="text-align: left;border: 1px solid black;">Jumlah Tanaman Hias (diharapkan >5 jenis tanaman hias)</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">5</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">{{$item2['landscape_nilai2']?? 0 }}</td>
                    <td colspan="5" style="text-align: left;border: 1px solid black;">{{$item2['landscape_komen2']?? 0 }}</td>
                </tr>
                <!-- pemilharaaan  -->
                <tr>
                    <td rowspan="2" colspan="2" style="text-align: left;border: 1px solid black;">Kondisi Fisik Tanaman</td>
                    <td colspan="2" style="text-align: left;border: 1px solid black;">Kondisi Fisik Tanaman</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">5</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">{{$item2['landscape_nilai3']?? 0 }}</td>
                    <td colspan="5" style="text-align: left;border: 1px solid black;">{{$item2['landscape_komen3']?? 0 }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: left;border: 1px solid black;">Kebersihan Lingkungan</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">4</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">{{$item2['landscape_nilai4']?? 0 }}</td>
                    <td colspan="5" style="text-align: left;border: 1px solid black;">{{$item2['landscape_komen4']?? 0 }}</td>
                </tr>
                <!-- design  -->
                <tr>
                    <td rowspan="1" colspan="2" style="text-align: left;border: 1px solid black;">Design</td>
                    <td colspan="2" style="text-align: left;border: 1px solid black;">Bentuk Taman: Simetris (1), Lengkung (2), Vertikal (3)</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">3</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;">{{$item2['landscape_nilai5']?? 0 }}</td>
                    <td colspan="5" style="text-align: left;border: 1px solid black;">{{$item2['landscape_komen5']?? 0 }}</td>
                </tr>

                <!-- total  -->
                @php
                $total = 0;

                $total = ($item2['total_nilailcp'] ?? 0) + ($item2['total_nilailkng'] ?? 0) + ($item2['total_nilairmh'] ?? 0);
                @endphp
                <tr>
                    <td colspan="5" style="text-align: center;border: 1px solid black;background-color: #e8ecdc;">TOTAL</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;background-color: #e8ecdc;">100</td>
                    <td colspan="1" style="text-align: center;border: 1px solid black;background-color: #e8ecdc;">{{$total}}</td>
                    <td colspan="5" style="text-align: center;border: 1px solid black;background-color: #e8ecdc;">&nbsp;</td>
                </tr>


            </body>

        </table>



        <div style="padding: 10px;border: 1px solid black; border-radius: 10px;margin-top: 20px;">

            <table class=" custom-table table-1-no-border" style="float: left; width: 20%; border: 1px solid black; border-radius: 10px;margin-top : 20px;margin-bottom : 40px;margin-left : 10px;">
                <thead>
                    <tr>
                        <th colspan=" 2" class="text-center">DiBuat oleh,</th>
                    </tr>
                </thead>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <thead>
                    <tr>
                        <th colspan="2">{{$item2['petugas'] ?? 0}} </th>
                    </tr>
                    <tr>
                        <th colspan=" 2" style="border-bottom: 15px;">___________________</th>
                    </tr>
                    <tr>
                        <th colspan="2" style="border-bottom: 15px;">QC</th>
                    </tr>
                </thead>


            </table>
            <!-- Table 2 -->
            <table class=" custom-table table-1-no-border" style="float: left; width: 60%;">
                <thead>
                    <tr>
                        <th colspan="2" class="text-center">&nbsp;</th>
                    </tr>
                </thead>

            </table>


            <table class=" custom-table table-1-no-border" style="float: right; width: 20%;border: 1px solid black;border-radius: 10px;margin-top : 20px;margin-bottom : 40px;margin-right : 10px;">
                <thead>
                    <tr>
                        <th colspan=" 2" class="text-center">Diterima oleh,</th>
                    </tr>
                </thead>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <thead>
                    <tr>
                        <th colspan="2" style="border-bottom: 15px;">___________________</th>
                    </tr>
                    <tr>
                        <th colspan="2" style="border-bottom: 15px;"> Manager</th>
                    </tr>
                </thead>

            </table>



            <div style="clear:both;"></div>
        </div>
        <!-- Table 1 -->

        <div style="clear:both;"></div>
    </div>



    @if (!$loop->last) <!-- Check if it's not the last iteration -->
    <div style="page-break-before: always;"></div>
    @endif
    @endforeach
    @endforeach

    </div>

</body>

</html>