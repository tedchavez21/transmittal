<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transmittal Print Preview</title>
    <style>
        @page {
            size: 8.5in 13in landscape;
            margin: 12mm;
        }

        html, body {
            width: 13in;
            min-height: 8.5in;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            color: #000;
        }

        body {
            padding: 5mm;
        }

        .page-section {
            page-break-after: always;
            min-height: 8.5in;
            width: 100%;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px dashed #ccc;
        }

        .page-section:last-child {
            page-break-after: auto;
            border-bottom: none;
            margin-bottom: 0;
        }

        .header {
            display: flex;
            flex-direction: row;
            justify-content: space-evenly;
            margin-bottom: 16px;
        }

        .header h1 {
            margin: 0 0 8px 0;
            font-size: 20px;
        }

        .header p {
            margin: 2px 0;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            table-layout: auto;
            font-size: 10px;
        }

        th, td {
            padding: 4px 6px;
            text-align: left;
            vertical-align: top;
        }

        th {
            font-weight: bold;
        }

        .address-cell {
            white-space: pre-wrap;
        }

        .received-by {
            margin-top: 16px;
            font-size: 13px;
            font-weight: 600;
        }

        .no-data {
            padding: 16px;
            border: 1px solid #000;
            background: #fff5f5;
            font-size: 13px;
        }

        .pagination {
            display: none;
        }

        /* Keep "Assign" visible while scrolling (screen only) */
        .assign-form {
            position: fixed;
            right: 14px;
            bottom: 14px;
            z-index: 9999;
            background: rgba(255, 255, 255, 0.96);
            border: 1px solid #ddd;
            padding: 10px 12px;
            border-radius: 10px;
            box-shadow: 0 10px 24px rgba(0,0,0,0.15);
        }

        .assign-form button {
            padding: 10px 14px;
            font-weight: 700;
            background: #1e7e34;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .assign-form button:hover {
            background: #16692a;
        }

        @media print {
            .assign-form,
            .no-data {
                display: none;
            }

            body {
                padding: 0;
            }

            table {
                font-size: 10px;
            }

            .page-section {
                page-break-after: always;
            }

            thead {
                display: table-header-group;
            }

            tfoot {
                display: table-footer-group;
            }
        }
    </style>
</head>
<body>
    @if($records->isEmpty())
        <div class="no-data">No records found for this preview.</div>
    @else
        @php
            $chunks = $records->chunk($perPage);
        @endphp

        @foreach($chunks as $pageIndex => $pageRecords)
            <div class="page-section">
                <div class="header">
                    <p><strong>Date Encoded:</strong> {{ $encodedDate }}</p>
                    <p><strong>Transmittal Number:</strong> {{ $pageTransmittalNumbers[$pageIndex + 1] ?? 'Not assigned' }}</p>
                    <p><strong>Records:</strong> {{ $pageRecords->count() }} of {{ $totalRecords }} (Page {{ $pageIndex + 1 }} of {{ $totalPages }})</p>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Farmer Name</th>
                            <th>Address</th>
                            <th>Program</th>
                            <th>Line</th>
                            <th>Cause of Damage</th>
                            <th>Date of Occurrence</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pageRecords as $record)
                            <tr>
                                <td>{{ $record->farmerName }}</td>
                                <td class="address-cell">
                                    {{ trim(implode(', ', array_filter([$record->barangay, $record->municipality]))) ?: '—' }}
                                </td>
                                <td>{{ $record->program }}</td>
                                <td>{{ $record->line }}</td>
                                <td>{{ $record->causeOfDamage }}</td>
                                <td>{{ $record->date_occurrence ? $record->date_occurrence : '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="received-by">Received By: ____________________</div>
            </div>
        @endforeach
    @endif

    <form class="assign-form" method="POST" action="{{ route('admin.assign-transmittals') }}">
        @csrf
        @foreach($query as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
        <button type="submit">Assign Transmittal Number</button>
    </form>
</body>
</html>
