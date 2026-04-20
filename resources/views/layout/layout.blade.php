<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title','transmittal')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html, body {
            max-width: 100%;
            overflow-x: hidden;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        
        th, td {
            padding: 8px;
            text-align: left;
            word-wrap: break-word;
            overflow-wrap: break-word;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        th input, th select {
            width: 100%;
            box-sizing: border-box;
            padding: 4px;
            font-size: 12px;
        }
        
        /* Specific column widths */
        th:nth-child(1), td:nth-child(1) { width: 50px; } /* Checkbox/Edit */
        th:nth-child(2), td:nth-child(2) { width: 50px; } /* Edit */
        th:nth-child(3), td:nth-child(3) { width: 50px; } /* Delete */
        th:nth-child(4), td:nth-child(4) { width: 100px; } /* Encoder */
        th:nth-child(5), td:nth-child(5) { width: 110px; } /* Farmer Name */
        th:nth-child(6), td:nth-child(6) { width: 90px; } /* Province */
        th:nth-child(7), td:nth-child(7) { width: 100px; } /* Municipality */
        th:nth-child(8), td:nth-child(8) { width: 90px; } /* Barangay */
        th:nth-child(9), td:nth-child(9) { width: 90px; } /* Program */
        th:nth-child(10), td:nth-child(10) { width: 70px; } /* Line */
        th:nth-child(11), td:nth-child(11) { width: 100px; } /* Cause of Damage */
        th:nth-child(12), td:nth-child(12) { width: 100px; } /* Remarks */
        th:nth-child(13), td:nth-child(13) { width: 70px; } /* Source */
        th:nth-child(14), td:nth-child(14) { width: 100px; } /* Transmittal Number */
        th:nth-child(15), td:nth-child(15) { width: 70px; } /* Status */
    </style>
    <style media="print">
        @page { size: landscape; margin: 10mm; }
        body { margin: 0; padding: 0; }
        .odHeader { display: none !important; }
        .addRecordButton { display: none !important; }
        .no-print { display: none !important; }
        table { display: table !important; width: 100%; border-collapse: collapse; }
        table, tbody, thead, tr, td, th { display: table !important; width: auto; height: auto; margin: 0; padding: 0; border: 1px solid #000; }
        tr { page-break-inside: avoid; }
        .page-break { page-break-after: always; }
        dialog { display: none !important; }
    </style>
</head>
<body>
    @yield('content')
</body>
</html>