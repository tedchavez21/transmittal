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
        }

        .page-section:last-child {
            page-break-after: auto;
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
            table-layout: fixed;
            font-size: 11px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #f2f2f2;
            font-weight: 700;
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
                            <th>Province</th>
                            <th>Municipality</th>
                            <th>Barangay</th>
                            <th>Program</th>
                            <th>Line</th>
                            <th>Cause of Damage</th>
                            <th>Remarks</th>
                            <th>Source</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pageRecords as $record)
                            <tr>
                                <td>{{ $record->farmerName }}</td>
                                <td>{{ $record->province ?? '—' }}</td>
                                <td>{{ $record->municipality ?? '—' }}</td>
                                <td>{{ $record->barangay ?? '—' }}</td>
                                <td>{{ $record->program }}</td>
                                <td>{{ $record->line }}</td>
                                <td>{{ $record->causeOfDamage }}</td>
                                <td>{{ $record->remarks }}</td>
                                <td>{{ $record->source }}</td>
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
