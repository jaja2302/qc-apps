<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export To Excel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <!-- Import table-to-excel-master library -->
    <script src="{{ asset('table-to-excel-master/dist/tableToExcel.js') }}"></script>
</head>

<body>
    <!-- Your HTML content here -->

    <button onclick="exportToExcel()">Export to Excel</button>

    <script>
        // Define exportToExcel function
        function exportToExcel() {
            // Replace 'headshot' with the correct ID of the table you want to export
            var table = document.getElementById("headshot");

            TableToExcel.convert(table, {
                name: "Rekap BA QC Panen Reguler Reg {{$data['est']}} - {{$data['afd']}} - {{$data['tanggal']}}.xlsx",
                sheet: {
                    name: "Rekap BA QC Panen Reguler Reg  {{$data['est']}} - {{$data['afd']}}  - {{$data['tanggal']}}"
                }
            });

            // Close the window after exporting
            setTimeout(function() {
                window.close();
            }, 500);
        }
    </script>
</body>

</html>