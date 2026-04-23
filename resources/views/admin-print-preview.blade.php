@extends('layout.layout')

@section('title', 'Print Preview')

@section('page-styles')
    <style>
        /* Print Preview: match site styles (app.css) while staying print-correct */
        .print-preview-wrap {
            max-width: 1200px;
            margin: 18px auto 24px auto;
            padding: 0 14px;record-checkbox-transmit
        }

        .print-preview-topbar {
            margin-bottom: 14px;
        }

        .print-preview-card {
            background: rgba(255,255,255,0.92);
            border: 1px solid rgba(15, 23, 42, 0.12);
            border-radius: 16px;
            box-shadow: 0 10px 24px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .print-preview-card .meta {
            padding: 12px 14px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: space-between;
            border-bottom: 1px solid rgba(0,0,0,0.06);
            background: linear-gradient(180deg, rgba(25, 118, 210, 0.06), rgba(255, 255, 255, 0));
            font-weight: 700;
            color: rgba(15, 23, 42, 0.86);
            font-size: 12px;
        }

        .print-preview-card .meta strong {
            color: #0f172a;
            font-weight: 900;
        }

        .print-preview-card .table-wrap {
            padding: 0 10px 10px 10px;
        }

        .print-preview-table {
            margin: 12px auto 10px auto;
            width: 100%;
            max-width: none;
            border-collapse: separate;
            border-spacing: 0;
            border: 2px solid rgba(15, 23, 42, 0.2);
            border-radius: 8px;
            overflow: hidden;
        }

        .print-preview-table th {
            padding: 12px 14px;
            background: linear-gradient(180deg, rgba(25, 118, 210, 0.12), rgba(25, 118, 210, 0.08));
            border-bottom: 2px solid rgba(15, 23, 42, 0.3);
            font-weight: 700;
            color: #0f172a;
            text-align: left;
        }

        .print-preview-table td {
            padding: 10px 12px;
            border-bottom: 1px solid rgba(15, 23, 42, 0.1);
            vertical-align: top;
        }

        /* Alternating row colors for screen */
        .print-preview-table tbody tr:nth-child(even) {
            background-color: rgba(248, 250, 252, 0.8);
        }

        .print-preview-table tbody tr:nth-child(odd) {
            background-color: rgba(255, 255, 255, 0.9);
        }

        .print-preview-table tbody tr:hover {
            background-color: rgba(25, 118, 210, 0.06);
        }

        .print-preview-table .address-cell {
            white-space: normal;
        }

        .received-by {
            padding: 10px 14px 14px 14px;
            font-size: 12px;
            font-weight: 800;
            color: rgba(15, 23, 42, 0.85);
        }

        .page-section {
            margin-bottom: 14px;
        }

        /* Keep "Assign" visible while scrolling (screen only) */
        .assign-form {
            position: fixed;
            right: 14px;
            bottom: 14px;
            z-index: 9999;
            background: rgba(255, 255, 255, 0.94);
            border: 1px solid rgba(15, 23, 42, 0.14);
            padding: 10px 12px;
            border-radius: 14px;
            box-shadow: 0 10px 24px rgba(0,0,0,0.15);
            display: flex;
            gap: 10px;
            align-items: center;
        }

        @media print {
            @page {
                size: 8.5in 13in landscape;
                margin: 8mm;
            }

            /* Hide screen-only elements */
            .assign-form,
            .no-print {
                display: none !important;
            }

            /* Reset body for print */
            body {
                background: #fff !important;
                color: #000 !important;
                font-family: Cambria, "Times New Roman", Times, serif !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            /* Adjust wrapper for print */
            .print-preview-wrap {
                max-width: none !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            /* Card styling for print - optimized for 8.5x13 */
            .print-preview-card {
                box-shadow: none !important;
                border: 1px solid #000 !important;
                border-radius: 0 !important;
                background: #fff !important;
                page-break-inside: avoid;
                margin: 0 !important;
                overflow: visible !important;
                height: calc(100vh - 16mm) !important;
            }

            .print-preview-card .meta {
                background: #fff !important;
                border-bottom: 1px solid #000 !important;
                color: #000 !important;
                padding: 8px 12px !important;
                font-weight: 700 !important;
                font-size: 11px !important;
                display: flex !important;
                gap: 10px !important;
                flex-wrap: wrap !important;
                justify-content: space-between !important;
            }

            .print-preview-card .meta strong {
                color: #000 !important;
                font-weight: 900 !important;
            }

            /* Table wrapper */
            .print-preview-card .table-wrap {
                padding: 0 10px 10px 10px !important;
            }

            /* Received by section */
            .received-by {
                padding: 10px 14px 14px 14px !important;
                font-size: 12px !important;
                font-weight: 800 !important;
                color: #000 !important;
            }

            /* Page breaks - simple and effective */
            .page-section {
                page-break-after: always !important;
                break-after: page !important;
                margin-bottom: 0 !important;
            }

            .page-section:last-child {
                page-break-after: auto !important;
                break-after: auto !important;
            }

            /* Prevent content cutoff */
            .print-preview-card {
                page-break-inside: avoid !important;
            }

            .print-preview-table thead {
                display: table-header-group !important;
            }

            .print-preview-table tbody {
                display: table-row-group !important;
            }

            .print-preview-table tr {
                display: table-row !important;
            }

            .print-preview-table {
                display: table !important;
                width: 100% !important;
                border-collapse: collapse !important;
                border: 2px solid #000 !important;
                margin: 12px auto 10px auto !important;
                page-break-inside: avoid !important;
                table-layout: fixed !important;
            }

            .print-preview-table tr {
                page-break-inside: avoid !important;
            }

            .print-preview-table th:nth-child(1),
            .print-preview-table td:nth-child(1) {
                width: 5% !important;
                text-align: center !important;
            }

            .print-preview-table th:nth-child(2),
            .print-preview-table td:nth-child(2) {
                width: 18% !important;
            }

            .print-preview-table th:nth-child(3),
            .print-preview-table td:nth-child(3) {
                width: 23% !important;
            }

            .print-preview-table th:nth-child(4),
            .print-preview-table td:nth-child(4) {
                width: 14% !important;
            }

            .print-preview-table th:nth-child(5),
            .print-preview-table td:nth-child(5) {
                width: 9% !important;
            }

            .print-preview-table th:nth-child(6),
            .print-preview-table td:nth-child(6) {
                width: 18% !important;
            }

            .print-preview-table th:nth-child(7),
            .print-preview-table td:nth-child(7) {
                width: 13% !important;
            }

            .print-preview-table th {
                padding: 6px 8px !important;
                border: 1px solid #000 !important;
                background-color: #f5f5f5 !important;
                font-weight: 700 !important;
                text-align: left !important;
                font-size: 10px !important;
                display: table-cell !important;
                height: auto !important;
                margin: 0 !important;
                color: #000 !important;
                vertical-align: top !important;
            }

            .print-preview-table td {
                padding: 4px 6px !important;
                border: 1px solid #000 !important;
                font-size: 9px !important;
                display: table-cell !important;
                height: auto !important;
                margin: 0 !important;
                color: #000 !important;
                vertical-align: top !important;
                word-wrap: break-word !important;
            }

            .print-preview-table .address-cell {
                white-space: normal !important;
            }

            /* Alternating row colors - match screen exactly */
            .print-preview-table tbody tr:nth-child(even) {
                background-color: rgba(248, 250, 252, 0.8) !important;
            }

            .print-preview-table tbody tr:nth-child(odd) {
                background-color: rgba(255, 255, 255, 0.9) !important;
            }

            /* Ensure headers repeat on each printed page */
            thead { 
                display: table-header-group !important; 
            }

            /* Hide any remaining elements */
            dialog,
            .odHeader,
            .addRecordButton {
                display: none !important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="print-preview-wrap">
        <div class="admin-topbar print-preview-topbar no-print">
            <div class="heading">
                <h1>Transmittal Print Preview</h1>
                <p>Review pages, then assign transmittal numbers</p>
            </div>
            <div class="actions">
                <button type="button" class="btn btn-outline btn-sm" onclick="window.print()">Print</button>
                <button type="button" class="btn btn-sm" onclick="window.close();">Back to Admin</button>
            </div>
        </div>

        @if($records->isEmpty())
            <div class="app-alert app-alert--warning">No records found for this preview.</div>
        @else
            @php
                $chunks = $records->chunk($perPage);
            @endphp

            @foreach($chunks as $pageIndex => $pageRecords)
                <section class="page-section">
                    <div class="print-preview-card">
                        <div class="meta">
                            <div><strong>Date Encoded:</strong> {{ $encodedDate }}</div>
                            <div><strong>Transmittal #:</strong> {{ $pageTransmittalNumbers[$pageIndex + 1] ?? '____________________' }}</div>
                            <div><strong>Records:</strong> {{ $pageRecords->count() }} of {{ $totalRecords }} (Page {{ $pageIndex + 1 }} of {{ $totalPages }})</div>
                        </div>

                        <div class="table-wrap">
                            <table class="print-preview-table">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Farmer Name</th>
                                        <th>Address</th>
                                        <th>Program</th>
                                        <th>Line</th>
                                        <th>Cause of Damage</th>
                                        <th>Date of Occurrence</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pageRecords as $index => $record)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
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
                        </div>

                        <div class="received-by">Received By: ____________________</div>
                    </div>
                </section>
            @endforeach
        @endif
    </div>

    <form class="assign-form no-print" method="POST" action="{{ route('admin.assign-transmittals') }}" onsubmit="handleAssignTransmittal(event)">
        @csrf
        @foreach($query as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
        <button type="submit" class="btn btn-success">Assign Transmittal Number</button>
    </form>
    
    <script>
        function handleAssignTransmittal(event) {
            event.preventDefault();
            
            const form = event.target;
            const formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    alert('Transmittal numbers assigned successfully!');
                    
                    // Refresh the page to update the transmittal numbers in headers
                    window.location.reload();
                } else {
                    // Show error message
                    alert('Error: ' + (data.message || 'Failed to assign transmittal numbers'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while assigning transmittal numbers');
            });
        }
    </script>
@endsection
