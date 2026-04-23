<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title','transmittal')</title>
    @yield('page-styles')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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