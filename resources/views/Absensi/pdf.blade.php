<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div class="container">

        <div style="display: flex; justify-content: center; margin-top: 3px; margin-bottom: 2px; margin-left: 3px; margin-right: 3px; border: 1px solid black; background-color: #fff4cc">
            <h2 style="text-align: center;">ABSENSI QC</h2>
        </div>


        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="vertical-align: middle; padding-left: 0; width: 10%;border:0;">
                    <div>
                        <img src="{{ asset('img/Logo-SSS.png') }}" style="height:90px;margin-top : 10px;margin-left: 10px">
                    </div>
                </td>
                <td style="width:30%;border:0;">

                    <p style="text-align: left; font-size: 20px;">PT. SAWIT SUMBERMAS SARANA,TBK</p>
                    <p style="text-align: left;">QUALITY CONTROL</p>

                </td>
                <td style=" width: 40%;border:0;">
                </td>
                <td style="vertical-align: middle; text-align: right;width:40%;border:0;">
                    <div class="right-container">
                        <div class="text-container" style="border:1px solid black">

                            <div style="font-size: 20px;border:1px solid black">ESTATE: {{$data['Reg']}} </div>
                            <div style="font-size: 20px;border:1px solid black">TANGGAL: {{$data['bulan']}}</div>


                        </div>
                    </div>
                </td>
            </tr>
        </table>

        <table style="width: 100%; border-collapse: collapse; border: 1px solid black; font-size : 15px;">
            <thead>
                <tr>
                    <th style="background-color: #e8ecdc;border: 1px solid black;" colspan="{{$data['JumlahBulan'] + 2}}">Absensi User QC</th>

                </tr>
                <tr>
                    <th style="background-color: #e8ecdc;border: 1px solid black;" rowspan="2">NAMA</th>
                    <TH style="background-color: #e8ecdc;border: 1px solid black;" colspan="{{$data['JumlahBulan']}}">{{$data['header_month']}}</TH>
                    <TH style="background-color: #e8ecdc;border: 1px solid black;" rowspan="2">Total</TH>
                </tr>
                <tr>
                    @for ($i = 1; $i <= $data['JumlahBulan']; $i++) <th style="background-color: #e8ecdc; border: 1px solid black;">{{ $i }}</th>
                        @endfor
                </tr>


            </thead>
            <tbody>
                @foreach ($data['Dataabsensi'] as $items)
                <tr>
                    <td style="border-right: 1px solid black;border-bottom: 1px solid black;width:8%">{{ $items['nama'] }}</td>
                    @php
                    $dateFields = collect($items)->filter(function ($value, $key) {
                    return strpos($key, 'date') === 0;
                    });
                    @endphp
                    @foreach ($dateFields as $dateKey => $dateValue)
                    @if ($dateValue === 'minggu')
                    <td style="border-bottom: 1px solid black;text-align: center;border-right: 1px solid black;background-color: red;width:auto"></td>
                    @else
                    <td style="border-bottom: 1px solid black;text-align: center;border-right: 1px solid black;width:auto;">{{ $dateValue }}</td>
                    @endif
                    @endforeach
                    <td style="border-bottom: 1px solid black;text-align: center;border-right: 1px solid black;">{{ $items['total'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <table style="margin-top: 10px;">
            <th style="background-color: red;width:50px"></th>
            <th>Minggu</th>
        </table>

        @if ($data['Reg'] === 'Regional I')
        <table style="width: 50%;border:solid 1px black; border-collapse: collapse;margin-top: 10%;">
            <thead>
                <tr>
                    <th colspan="4" style="border: solid 1px black; text-align: left;">
                        Tambahkan kolom Ttd
                    </th>
                </tr>

                <tr>
                    <th style="border: solid 1px black;" colspan="4">
                        Mengetahui
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border: solid 1px black;height:300px">

                    </td>
                    <td style="border: solid 1px black;">

                    </td>
                    <td style="border: solid 1px black;">

                    </td>
                    <td style="border: solid 1px black;">

                    </td>
                </tr>
                <tr>
                    <td style="border: solid 1px black;text-align: center;">
                        Asisten QC-1

                    </td>
                    <td style="border: solid 1px black;text-align: center;">
                        Asisten QC-2

                    </td>
                    <td style="border: solid 1px black;text-align: center;">
                        Asisten QC-3

                    </td>
                    <td style="border: solid 1px black;text-align: center;">
                        Askep QC
                    </td>
                </tr>

            </tbody>

        </table>
        @elseif ($data['Reg'] === 'Regional II')
        <table style="width: 50%;border:solid 1px black; border-collapse: collapse;margin-top: 10%;">
            <thead>
                <tr>
                    <th colspan="4" style="border: solid 1px black; text-align: left;">
                        Tambahkan kolom Ttd
                    </th>
                </tr>

                <tr>
                    <th style="border: solid 1px black;" colspan="4">
                        Mengetahui
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border: solid 1px black;height:300px">

                    </td>
                    <td style="border: solid 1px black;">

                    </td>
                    <td style="border: solid 1px black;">

                    </td>
                    <td style="border: solid 1px black;">

                    </td>

                </tr>
                <tr>
                    <td style="border: solid 1px black;text-align: center;">
                        Asisten QC-1

                    </td>
                    <td style="border: solid 1px black;text-align: center;">
                        Asisten QC-2

                    </td>
                    <td style="border: solid 1px black;text-align: center;">
                        Asisten QC-3

                    </td>
                    <td style="border: solid 1px black;text-align: center;">
                        Manager QC
                    </td>
                </tr>

            </tbody>

        </table>
        @elseif ($data['Reg'] === 'Regional III')
        <table style="width: 50%;border:solid 1px black; border-collapse: collapse;margin-top: 10%;">
            <thead>
                <tr>
                    <th colspan="3" style="border: solid 1px black; text-align: left;">
                        Tambahkan kolom Ttd
                    </th>
                </tr>

                <tr>
                    <th style="border: solid 1px black;" colspan="3">
                        Mengetahui
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border: solid 1px black;height:300px">

                    </td>
                    <td style="border: solid 1px black;">

                    </td>
                    <td style="border: solid 1px black;">

                    </td>

                </tr>
                <tr>
                    <td style="border: solid 1px black;text-align: center;">
                        Asisten QC-1

                    </td>
                    <td style="border: solid 1px black;text-align: center;">
                        Asisten QC-2

                    </td>

                    <td style="border: solid 1px black;text-align: center;">
                        Askep QC
                    </td>
                </tr>

            </tbody>

        </table>
        @else
        <table style="width: 50%;border:solid 1px black; border-collapse: collapse;margin-top: 10%;">
            <thead>
                <tr>
                    <th colspan="2" style="border: solid 1px black; text-align: left;">
                        Tambahkan kolom Ttd
                    </th>
                </tr>

                <tr>
                    <th style="border: solid 1px black;" colspan="2">
                        Mengetahui
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border: solid 1px black;height:300px">

                    </td>
                    <td style="border: solid 1px black;">

                    </td>

                </tr>
                <tr>
                    <td style="border: solid 1px black;text-align: center;">
                        Asisten QC-1

                    </td>


                    <td style="border: solid 1px black;text-align: center;">
                        Askep QC
                    </td>
                </tr>

            </tbody>

        </table>
        @endif


    </div>
</body>

</html>