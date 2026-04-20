<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Records Report</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        @page { size: landscape; }
        .received-by { position: fixed; bottom: 20px; right: 20px; }
    </style>
</head>
<body>
    <h1>Records Report</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Encoder</th>
                <th>Farmer Name</th>
                <th>Province</th>
                <th>Municipality</th>
                <th>Barangay</th>
                <th>Address</th>
                <th>Program</th>
                <th>Line</th>
                <th>Cause of Damage</th>
                <th>Mode of Payment</th>
                <th>Remarks</th>
                <th>Source</th>
                <th>Transmittal Number</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
            <tr>
                <td>{{ $record->id }}</td>
                <td>{{ $record->encoderName }}</td>
                <td>{{ $record->farmerName }}</td>
                <td>{{ $record->province ?? '—' }}</td>
                <td>{{ $record->municipality ?? '—' }}</td>
                <td>{{ $record->barangay ?? '—' }}</td>
                <td>{{ $record->address }}</td>
                <td>{{ $record->program }}</td>
                <td>{{ $record->line }}</td>
                <td>{{ $record->causeOfDamage }}</td>
                <td>{{ $record->modeOfPayment }}</td>
                <td>{{ $record->remarks }}</td>
                <td>{{ $record->source }}</td>
                <td>{{ $record->transmittal_number }}</td>
                <td>{{ $record->created_at }}</td>
            </tr>
            @if($loop->iteration % 40 == 0 && !$loop->last)
            <tr class="page-break"><td colspan="10" style="border: none; height: 50px;"></td></tr>
            @endif
            @endforeach
        </tbody>
    </table>
    <div class="received-by">Received By: ____________________</div>
</body>
</html>