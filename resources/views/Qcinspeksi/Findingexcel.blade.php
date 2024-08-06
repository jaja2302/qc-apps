<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <table class="my-table">
        <thead>
            <tr>
                <th colspan="10" style="text-align: center;border:1px solid black">PEMERIKSAAN KUALITAS PANEN</th>
            </tr>
            <tr>
                <th style="text-align: center;border:1px solid black" rowspan="2">No</th>
                <th style="text-align: center;border:1px solid black" rowspan="2">Blok</th>
                <th style="text-align: center;border:1px solid black" rowspan="2">EST</th>
                <th style="text-align: center;border:1px solid black" rowspan="2">AFD</th>
                <th style="text-align: center;border:1px solid black" rowspan="2">ISSUE</th>
                <th style="text-align: center;border:1px solid black" colspan="4">FOTO</th>
                <th style="text-align: center;border:1px solid black" rowspan="2">STATUS</th>
            </tr>
            <tr>
                <th style="text-align: center;border:1px solid black" colspan="2">BEFORE</th>
                <th style="text-align: center;border:1px solid black" colspan="2">AFTER</th>
            </tr>
        </thead>
        <tbody>
            @php
            $inc = 1;
            @endphp
            @foreach ($data as $item)
            <tr>
                <td style="text-align: center; vertical-align: center;">{{$inc++}}</td>
                <td style="text-align: center; vertical-align: center;">{{$item['blok']}}</td>
                <td style="text-align: center; vertical-align: center;">{{$item['estate']}}</td>
                <td style="text-align: center; vertical-align: center;">{{$item['afdeling']}}</td>
                <td style="text-align: center; vertical-align: center;">{{$item['komentar']}}</td>
                <td style="text-align: center; vertical-align: center;">-</td>
                <td style="text-align: center; vertical-align: center;">-</td>
                <td style="text-align: center; vertical-align: center;">-</td>
                <td style="text-align: center; vertical-align: center;">-</td>

                @if($item['category'] === 'mutu_transport')
                @if($item['foto_temuan'] !== '' && $item['foto_fu'] !== '')
                <td>
                    Tuntas
                </td>
                @else
                <td>
                    Belum Tuntas
                </td>
                @endif
                @elseif($item['category'] === 'mutu_buah')
                <td>
                    Berkelanjutan
                </td>
                @elseif($item['category'] === 'mutu_ancak')
                @if($item['foto_temuan1'] !== '' && $item['foto_fu1'] !== '' || $item['foto_temuan1'] !== '' && $item['foto_fu2'] !== '')
                <td>
                    Tuntas
                </td>
                @elseif($item['foto_temuan2'] !== '' && $item['foto_fu1'] !== '' || $item['foto_temuan2'] !== '' && $item['foto_fu2'] !== '')
                <td>
                    Tuntas
                </td>
                @elseif($item['foto_temuan1'] !== '' && $item['foto_fu1'] !== '' && $item['foto_temuan2'] !== '' && $item['foto_fu2'] !== '')
                <td>
                    Tuntas
                </td>
                @else
                <td>
                    Tidak Tuntas
                </td>
                @endif
                @else
                <td>
                    kategori : lainnya
                </td>
                @endif
            </tr>
            @endforeach

        </tbody>
    </table>
</body>

</html>