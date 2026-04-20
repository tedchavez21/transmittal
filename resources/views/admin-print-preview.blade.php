<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transmittal Print Preview</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; color: #000; }
        .header { margin-bottom: 20px; }
        .header h1 { margin: 0 0 10px 0; font-size: 22px; }
        .header p { margin: 2px 0; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; font-size: 13px; }
        th { background: #f2f2f2; }
        .address-cell { white-space: pre-wrap; }
        .received-by { margin-top: 20px; font-size: 14px; font-weight: 600; }
        .assign-form { margin-top: 40px; }
        .assign-form button { padding: 10px 16px; background-color: #1976D2; color: #fff; border: none; border-radius: 3px; cursor: pointer; font-size: 14px; }
        .no-data { padding: 16px; border: 1px solid #000; background: #fff5f5; }
        @media print {
            .assign-form { display: none; }
            .no-data { border: none; background: transparent; }
            body { margin: 12mm; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Transmittal Print Preview</h1>
        <p><strong>Date Encoded:</strong> {{ $encodedDate }}</p>
        <p><strong>Transmittal Number:</strong> {{ $transmittalNumber ?? 'Not assigned' }}</p>
        <p><strong>Records:</strong> {{ $records->count() }}</p>
    </div>

    @if($records->isEmpty())
        <div class="no-data">No records found for this preview.</div>
    @else
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Line</th>
                    <th>Program</th>
                    <th>Cause of Damage</th>
                    <th>Transmittal Number</th>
                </tr>
            </thead>
            <tbody>
                @foreach($records as $record)
                    <tr>
                        <td>{{ $record->farmerName }}</td>
                        <td class="address-cell">{{ trim($record->barangay . ', ' . $record->municipality . ', ' . $record->province, ', ') }}</td>
                        <td>{{ $record->line }}</td>
                        <td>{{ $record->program }}</td>
                        <td>{{ $record->causeOfDamage }}</td>
                        <td>{{ $record->transmittal_number ?: '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="received-by">Received By: ____________________</div>

    <form class="assign-form" method="POST" action="{{ route('admin.assign-transmittals') }}">
        @csrf
        @foreach($query as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
        <button type="submit">Assign Transmittal Number</button>
    </form>
</body>
</html>
