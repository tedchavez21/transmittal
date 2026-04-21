<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title','transmittal')</title>
    @yield('page-styles')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html, body {
            max-width: 100%;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: auto;
        }
        
        th, td {
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        
        th {
            white-space: normal;
            font-weight: bold;
        }
        
        td {
            white-space: normal;
        }
        
        th input, th select {
            width: 100%;
            box-sizing: border-box;
            padding: 4px;
            font-size: 12px;
        }
        
        .col-checkbox { width: 50px; }
        .col-edit { width: 50px; }
        .col-delete { width: 50px; }
        .col-encoder { min-width: 80px; }
        .col-farmer-name { min-width: 240px; white-space: nowrap; }
        .col-province { min-width: 90px; }
        .col-municipality { min-width: 120px; }
        .col-barangay { min-width: 120px; }
        .col-program { min-width: 80px; white-space: nowrap; }
        .col-line { min-width: 70px; white-space: nowrap; }
        .col-causeOfDamage { min-width: 100px; }
        .col-remarks { min-width: 100px; }
        .col-source { min-width: 70px; white-space: nowrap; }
        .col-transmittal-number { min-width: 100px; white-space: nowrap; }
        .col-admin-transmittal-number { min-width: 100px; white-space: nowrap; }
        .col-status { min-width: 70px; white-space: nowrap; }
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