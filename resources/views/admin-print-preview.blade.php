@extends('layout.layout')

@section('title', 'Print Preview')

@section('page-styles')
    <style>
        /* Print Preview: PCIC-themed screen + print styles */
        .print-preview-wrap {
            max-width: 1200px;
            margin: 18px auto 24px auto;
            padding: 0 14px;
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
            background: linear-gradient(180deg, rgba(0, 108, 53, 0.08), rgba(255, 255, 255, 0));
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
            border: 2px solid rgba(0, 108, 53, 0.25);
            border-radius: 8px;
            overflow: hidden;
        }

        .print-preview-table th {
            padding: 8px 10px;
            background: #006c35;
            border-bottom: 2px solid #005428;
            font-weight: 700;
            color: #fff;
            text-align: left;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .print-preview-table td {
            padding: 6px 8px;
            border-bottom: 1px solid rgba(15, 23, 42, 0.1);
            vertical-align: top;
            font-size: 14px;
            line-height: 1.3;
        }

        /* Alternating row colors for screen */
        .print-preview-table tbody tr:nth-child(even) {
            background-color: rgba(240, 253, 244, 0.6);
        }

        .print-preview-table tbody tr:nth-child(odd) {
            background-color: rgba(255, 255, 255, 0.9);
        }

        .print-preview-table tbody tr:hover {
            background-color: rgba(0, 108, 53, 0.06);
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
                height: auto !important;
                max-height: none !important;
            }

            .print-preview-card .meta {
                background: #006c35 !important;
                border-bottom: 2px solid #005428 !important;
                color: #fff !important;
                padding: 2px 6px !important;
                font-weight: 700 !important;
                font-size: 10px !important;
                display: flex !important;
                gap: 6px !important;
                flex-wrap: wrap !important;
                justify-content: space-between !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                margin-bottom: 0 !important;
                line-height: 1.0 !important;
            }

            .print-preview-card .meta strong {
                color: #fff !important;
                font-weight: 900 !important;
            }

            .page-section {
                page-break-after: always;
                margin-bottom: 0 !important;
                padding-bottom: 0 !important;
            }

            .page-section:last-child {
                page-break-after: auto;
            }

            /* Table wrapper */
            .print-preview-card .table-wrap {
                padding: 1px 2px 2px 2px !important;
                margin: 0 !important;
            }

            /* Received by section */
            .received-by {
                padding: 3px 6px 4px 6px !important;
                font-size: 9px !important;
                font-weight: 800 !important;
                color: #000 !important;
                margin: 0 !important;
            }

            /* Page breaks - keep headings and table together */
            .page-section {
                page-break-after: always !important;
                margin-bottom: 0 !important;
                padding: 0 !important;
            }

            .page-section:last-child {
                page-break-after: auto !important;
            }

            /* Keep card content together - headings and table */
            .print-preview-card {
                page-break-inside: avoid !important;
                margin: 0 !important;
                padding: 0 !important;
                display: block !important;
            }

            /* Ensure meta section stays with table */
            .print-preview-card .meta {
                page-break-after: avoid !important;
                page-break-inside: avoid !important;
            }

            /* Ensure table stays with meta */
            .print-preview-card .table-wrap {
                page-break-before: avoid !important;
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
                border: 1px solid #000 !important;
                margin: 0 !important;
                page-break-inside: avoid !important;
                table-layout: auto !important;
            }

            .print-preview-table tr {
                page-break-inside: avoid !important;
            }

            /* Dynamic table layout - respect inline styles for column widths */
            .print-preview-table {
                table-layout: fixed !important;
                width: 100% !important;
                border-collapse: collapse !important;
            }

            /* Don't override inline width styles */
            .print-preview-table th[style],
            .print-preview-table td[style] {
                /* Width controlled by inline styles */
            }

            .print-preview-table th {
                padding: 2px 3px !important;
                border: 1px solid #000 !important;
                background-color: #006c35 !important;
                font-weight: 700 !important;
                text-align: left !important;
                font-size: 10px !important;
                display: table-cell !important;
                height: 12px !important;
                margin: 0 !important;
                color: #fff !important;
                vertical-align: middle !important;
                text-transform: uppercase !important;
                letter-spacing: 0.1px !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                line-height: 1.0 !important;
                white-space: nowrap !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
            }

            .print-preview-table th:nth-child(1) {
                text-align: center !important;
            }

            .print-preview-table td {
                padding: 1px 3px !important;
                border: 1px solid #000 !important;
                font-size: 10px !important;
                display: table-cell !important;
                height: 10px !important;
                margin: 0 !important;
                color: #000 !important;
                vertical-align: middle !important;
                word-wrap: break-word !important;
                line-height: 1.0 !important;
            }

            .print-preview-table .address-cell {
                white-space: normal !important;
            }

            /* Alternating row colors - match screen */
            .print-preview-table tbody tr:nth-child(even) {
                background-color: rgba(240, 253, 244, 0.6) !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .print-preview-table tbody tr:nth-child(odd) {
                background-color: #fff !important;
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
                // Dynamic column width calculation based on content
                $maxTextLengths = [
                    'no' => 2,
                    'farmerName' => strlen('Farmer Name'),
                    'address' => strlen('Address'),
                    'program' => strlen('Program'),
                    'line' => strlen('Line'),
                    'causeOfDamage' => strlen('Cause of Damage'),
                    'remarks' => strlen('Remarks')
                ];
                
                // Calculate maximum text length for each column
                foreach ($records as $index => $record) {
                    $maxTextLengths['no'] = max($maxTextLengths['no'], strlen((string)($index + 1)));
                    $maxTextLengths['farmerName'] = max($maxTextLengths['farmerName'], strlen($record->farmerName));
                    $maxTextLengths['address'] = max($maxTextLengths['address'], strlen($record->barangay . ', ' . $record->municipality));
                    $maxTextLengths['program'] = max($maxTextLengths['program'], strlen($record->program));
                    $maxTextLengths['line'] = max($maxTextLengths['line'], strlen($record->line));
                    $maxTextLengths['causeOfDamage'] = max($maxTextLengths['causeOfDamage'], strlen($record->causeOfDamage));
                    $maxTextLengths['remarks'] = max($maxTextLengths['remarks'], strlen($record->remarks ?: ''));
                }
                
                // Calculate character-based widths (rough approximation)
                $charWidths = [
                    'no' => $maxTextLengths['no'] * 0.6, // Narrow numbers
                    'farmerName' => $maxTextLengths['farmerName'] * 0.5, // Names
                    'address' => $maxTextLengths['address'] * 0.45, // Addresses
                    'program' => $maxTextLengths['program'] * 0.55, // Programs
                    'line' => $maxTextLengths['line'] * 0.6, // Lines
                    'causeOfDamage' => $maxTextLengths['causeOfDamage'] * 0.4, // Causes
                    'remarks' => $maxTextLengths['remarks'] * 0.5 // Remarks
                ];
                
                // Set minimum widths for readability
                $minWidths = [
                    'no' => 4,
                    'farmerName' => 15,
                    'address' => 20,
                    'program' => 8,
                    'line' => 6,
                    'causeOfDamage' => 10,
                    'remarks' => 8
                ];
                
                // Apply minimum widths
                foreach ($charWidths as $col => $width) {
                    $charWidths[$col] = max($width, $minWidths[$col]);
                }
                
                // Calculate total and normalize to 100%
                $totalWidth = array_sum($charWidths);
                $columnWidths = [];
                foreach ($charWidths as $col => $width) {
                    $columnWidths[$col] = round(($width / $totalWidth) * 100, 1);
                }
                
                // Adjust to ensure exactly 100%
                $currentTotal = array_sum($columnWidths);
                $diff = 100 - $currentTotal;
                if ($diff != 0) {
                    $columnWidths['farmerName'] += $diff; // Adjust largest column
                }
                
                // Calculate dynamic font size based on content density
                $avgTextLength = array_sum($maxTextLengths) / count($maxTextLengths);
                $baseFontSize = 10;
                $fontSize = max(8, min(12, $baseFontSize - ($avgTextLength * 0.05)));
                
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
                                        <th style="width: {{ $columnWidths['no'] }}%; font-size: {{ $fontSize }}px; height: {{ $fontSize + 2 }}px;">No.</th>
                                        <th style="width: {{ $columnWidths['farmerName'] }}%; font-size: {{ $fontSize }}px; height: {{ $fontSize + 2 }}px;">Farmer Name</th>
                                        <th style="width: {{ $columnWidths['address'] }}%; font-size: {{ $fontSize }}px; height: {{ $fontSize + 2 }}px;">Address</th>
                                        <th style="width: {{ $columnWidths['program'] }}%; font-size: {{ $fontSize }}px; height: {{ $fontSize + 2 }}px;">Program</th>
                                        <th style="width: {{ $columnWidths['line'] }}%; font-size: {{ $fontSize }}px; height: {{ $fontSize + 2 }}px;">Line</th>
                                        <th style="width: {{ $columnWidths['causeOfDamage'] }}%; font-size: {{ $fontSize }}px; height: {{ $fontSize + 2 }}px;">Cause of Damage</th>
                                        <th style="width: {{ $columnWidths['remarks'] }}%; font-size: {{ $fontSize }}px; height: {{ $fontSize + 2 }}px;">Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pageRecords as $index => $record)
                                        <tr>
                                            <td style="font-size: {{ $fontSize }}px; height: {{ $fontSize }}px;">{{ $index + 1 }}</td>
                                            <td style="font-size: {{ $fontSize }}px; height: {{ $fontSize }}px;">{{ $record->farmerName }}</td>
                                            <td class="address-cell" style="font-size: {{ $fontSize }}px; height: {{ $fontSize }}px;">
                                                {{ trim(implode(', ', array_filter([$record->barangay, $record->municipality]))) ?: '—' }}
                                            </td>
                                            <td style="font-size: {{ $fontSize }}px; height: {{ $fontSize }}px;">{{ $record->program }}</td>
                                            <td style="font-size: {{ $fontSize }}px; height: {{ $fontSize }}px;">{{ $record->line }}</td>
                                            <td style="font-size: {{ $fontSize }}px; height: {{ $fontSize }}px;">{{ $record->causeOfDamage }}</td>
                                            <td style="font-size: {{ $fontSize }}px; height: {{ $fontSize }}px;">{{ $record->remarks ?: '—' }}</td>
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
                const assignModal = document.getElementById('assignTransmittalModal');
                const messageElement = document.getElementById('assignTransmittalMessage');
                
                if (data.success) {
                    // Show success message in modal
                    if (assignModal && messageElement) {
                        messageElement.textContent = 'Transmittal numbers assigned successfully!';
                        messageElement.className = 'text-sm text-green-600 mb-4';
                        assignModal.showModal();
                        
                        // Handle modal buttons
                        const continueBtn = document.getElementById('assignTransmittalContinue');
                        const cancelBtn = document.getElementById('assignTransmittalCancel');
                        
                        // Remove existing event listeners
                        const newContinueBtn = continueBtn.cloneNode(true);
                        const newCancelBtn = cancelBtn.cloneNode(true);
                        continueBtn.parentNode.replaceChild(newContinueBtn, continueBtn);
                        cancelBtn.parentNode.replaceChild(newCancelBtn, cancelBtn);
                        
                        // Add event listeners
                        newContinueBtn.addEventListener('click', function() {
                            assignModal.close();
                            // Refresh the page to update the transmittal numbers in headers
                            window.location.reload();
                        });
                        
                        newCancelBtn.addEventListener('click', function() {
                            assignModal.close();
                        });
                    }
                } else {
                    // Show error message in modal
                    if (assignModal && messageElement) {
                        messageElement.textContent = 'Error: ' + (data.message || 'Failed to assign transmittal numbers');
                        messageElement.className = 'text-sm text-red-600 mb-4';
                        assignModal.showModal();
                        
                        // Handle modal buttons
                        const continueBtn = document.getElementById('assignTransmittalContinue');
                        const cancelBtn = document.getElementById('assignTransmittalCancel');
                        
                        // Remove existing event listeners
                        const newContinueBtn = continueBtn.cloneNode(true);
                        const newCancelBtn = cancelBtn.cloneNode(true);
                        continueBtn.parentNode.replaceChild(newContinueBtn, continueBtn);
                        cancelBtn.parentNode.replaceChild(newCancelBtn, cancelBtn);
                        
                        // Add event listeners
                        newContinueBtn.addEventListener('click', function() {
                            assignModal.close();
                        });
                        
                        newCancelBtn.addEventListener('click', function() {
                            assignModal.close();
                        });
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const assignModal = document.getElementById('assignTransmittalModal');
                const messageElement = document.getElementById('assignTransmittalMessage');
                
                if (assignModal && messageElement) {
                    messageElement.textContent = 'An error occurred while assigning transmittal numbers';
                    messageElement.className = 'text-sm text-red-600 mb-4';
                    assignModal.showModal();
                    
                    // Handle modal buttons
                    const continueBtn = document.getElementById('assignTransmittalContinue');
                    const cancelBtn = document.getElementById('assignTransmittalCancel');
                    
                    // Remove existing event listeners
                    const newContinueBtn = continueBtn.cloneNode(true);
                    const newCancelBtn = cancelBtn.cloneNode(true);
                    continueBtn.parentNode.replaceChild(newContinueBtn, continueBtn);
                    cancelBtn.parentNode.replaceChild(newCancelBtn, cancelBtn);
                    
                    // Add event listeners
                    newContinueBtn.addEventListener('click', function() {
                        assignModal.close();
                    });
                    
                    newCancelBtn.addEventListener('click', function() {
                        assignModal.close();
                    });
                }
            });
        }
    </script>
@endsection
