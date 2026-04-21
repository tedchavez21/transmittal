@extends('layout.layout')

@section('title', 'Admin')

@section('page-styles')
<style>
    html, body {
        max-width: 100%;
    }
</style>
@endsection

@section('content')
    <style>
        .received-by { display: none; }
    </style>
    <style media="print">
        @page { size: landscape; }
        h1, p, form, .no-print { display: none !important; }
        table { display: table !important; width: 100%; border-collapse: collapse; }
        tbody, thead, tr, td, th { display: table-cell !important; width: auto; height: auto; margin: 0; padding: 8px; border: 1px solid #000; }
        thead, tbody { display: table-row-group !important; }
        tr { display: table-row !important; page-break-inside: avoid; }
        .page-break { page-break-after: always; }
        .received-by { display: block; position: fixed; bottom: 20px; right: 20px; font-size: 14px; }
    </style>
    <h1>Admin Page</h1>
    <p>USHEL admin page</p>

    @if(session('success'))
        <div style="background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; border: 1px solid #c3e6cb; border-radius: 4px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 20px; border: 1px solid #f5c6cb; border-radius: 4px;">
            {{ session('error') }}
        </div>
    @endif

    @if(session('warning'))
        <div style="background-color: #fff3cd; color: #856404; padding: 10px; margin-bottom: 20px; border: 1px solid #ffeaa7; border-radius: 4px;">
            {{ session('warning') }}
        </div>
    @endif

    @if(session('info'))
        <div style="background-color: #d1ecf1; color: #0c5460; padding: 10px; margin-bottom: 20px; border: 1px solid #bee5eb; border-radius: 4px;">
            {{ session('info') }}
        </div>
    @endif

    <!-- DashboardDD -->
    <div class="no-print" style="margin-bottom: 20px; padding: 15px; border: 1px solid #ccc;">
        <h2>Dashboard</h2>
        
        <!-- Filters -->
        <form method="GET" action="{{ route('admin') }}" style="margin: 15px 0; padding: 15px; background: #f5f5f5; border-radius: 4px;">
            <p style="font-weight: bold; margin-bottom: 10px;">DASHBOARD FILTERS:</p>
            <div style="display: flex; flex-wrap: wrap; gap: 10px; align-items: end;">
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Line</label>
                    <select name="dash_line" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                        <option value="">All Lines</option>
                        @foreach($allLines as $line)
                            <option value="{{ $line }}" {{ request('dash_line') == $line ? 'selected' : '' }}>{{ $line }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Program</label>
                    <select name="dash_program" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                        <option value="">All Programs</option>
                        @foreach($allPrograms as $program)
                            <option value="{{ $program }}" {{ request('dash_program') == $program ? 'selected' : '' }}>{{ $program }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Province</label>
                    <select name="dash_province" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                        <option value="">All Provinces</option>
                        @foreach($allProvinces as $province)
                            <option value="{{ $province }}" {{ request('dash_province') == $province ? 'selected' : '' }}>{{ $province }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Municipality</label>
                    <select name="dash_municipality" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                        <option value="">All Municipalities</option>
                        @foreach($allMunicipalities as $municipality)
                            <option value="{{ $municipality }}" {{ request('dash_municipality') == $municipality ? 'selected' : '' }}>{{ $municipality }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Barangay</label>
                    <select name="dash_barangay" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                        <option value="">All Barangays</option>
                        @foreach($allBarangays as $barangay)
                            <option value="{{ $barangay }}" {{ request('dash_barangay') == $barangay ? 'selected' : '' }}>{{ $barangay }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Source</label>
                    <select name="dash_source" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                        <option value="">All Sources</option>
                        @foreach($allSources as $source)
                            <option value="{{ $source }}" {{ request('dash_source') == $source ? 'selected' : '' }}>{{ $source }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="date-range-filter" style="display: {{ request('dash_date_type') == 'range' ? 'flex' : 'none' }}; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">To</label>
                    <input type="date" name="dash_date_to" value="{{ request('dash_date_to') }}" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                </div>
                <div class="date-month-filter" style="display: {{ request('dash_date_type') == 'month' ? 'flex' : 'none' }}; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Month</label>
                    <input type="month" name="dash_date_month" value="{{ request('dash_date_month') }}" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                </div>
                <button type="submit" style="padding: 8px 16px; background-color: #1976D2; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Filter</button>
                <a href="{{ route('admin') }}" style="padding: 8px 16px; background-color: #757575; color: white; text-decoration: none; border-radius: 4px; font-weight: bold; display: inline-block; line-height: 20px;">Clear</a>
            </div>
        </form>
        <script>
            function toggleDateFilters() {
                var dateType = document.getElementById('dateTypeFilter').value;
                document.querySelectorAll('.date-single-filter, .date-range-filter, .date-month-filter').forEach(function(el) {
                    el.style.display = 'none';
                });
                if (dateType === 'single') {
                    document.querySelector('.date-single-filter').style.display = 'flex';
                } else if (dateType === 'range') {
                    document.querySelectorAll('.date-range-filter').forEach(function(el) {
                        el.style.display = 'flex';
                    });
                } else if (dateType === 'month') {
                    document.querySelector('.date-month-filter').style.display = 'flex';
                }
            }
        </script>
        
        {{-- Filtered Display Summary --}}
        @php
            $summaryParts = [];
            
            if(request('dash_barangay')) $summaryParts[] = 'Barangay ' . request('dash_barangay');
            if(request('dash_municipality')) $summaryParts[] = request('dash_municipality');
            if(request('dash_province')) $summaryParts[] = request('dash_province');
            if(request('dash_program')) $summaryParts[] = request('dash_program') . ' program';
            if(request('dash_line')) $summaryParts[] = request('dash_line') . ' line';
            if(request('dash_source')) $summaryParts[] = request('dash_source') . ' source';
            
            $dateText = '';
            if(request('dash_date_type') == 'single' && request('dash_date_single')) {
                $dateText = 'on ' . date('F j, Y', strtotime(request('dash_date_single')));
            } elseif(request('dash_date_type') == 'range' && request('dash_date_from') && request('dash_date_to')) {
                $dateText = 'from ' . date('F j, Y', strtotime(request('dash_date_from'))) . ' to ' . date('F j, Y', strtotime(request('dash_date_to')));
            } elseif(request('dash_date_type') == 'month' && request('dash_date_month')) {
                $dateText = 'for ' . date('F Y', strtotime(request('dash_date_month') . '-01'));
            }
            
            $summary = 'Displaying summary';
            if(count($summaryParts) > 0) {
                $summary .= ' for ' . implode(', ', $summaryParts);
            }
            if($dateText) {
                $summary .= ' ' . $dateText;
            }
        @endphp
        
        <div style="background: #e3f2fd; padding: 12px 15px; border-radius: 4px; margin: 15px 0; font-weight: 500; border-left: 4px solid #1976D2;">
            {{ $summary }}
        </div>

        <table style="width: 100%; border-collapse: collapse; margin: 15px 0;">
            <thead>
                <tr style="background-color: #f0f0f0;">
                    <th style="border: 1px solid #ccc; padding: 12px; text-align: left; width: 60%;">Metric</th>
                    <th style="border: 1px solid #ccc; padding: 12px; text-align: center; width: 40%;">Value</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border: 1px solid #ccc; padding: 10px; font-weight: 500;">Total Records</td>
                    <td style="border: 1px solid #ccc; padding: 10px; text-align: center; font-weight: bold; font-size: 16px;">{{ $totalRecords }}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #ccc; padding: 10px; font-weight: 500;">Recent Records (Last 7 days)</td>
                    <td style="border: 1px solid #ccc; padding: 10px; text-align: center; font-weight: bold; font-size: 16px;">{{ $recentRecords }}</td>
                </tr>
            </tbody>
        </table>

        <h4 style="margin: 20px 0 10px 0;">Records by Program</h4>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #f0f0f0;">
                    <th style="border: 1px solid #ccc; padding: 10px; text-align: left; width: 70%;">Program Name</th>
                    <th style="border: 1px solid #ccc; padding: 10px; text-align: center; width: 30%;">Record Count</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recordsByProgram as $program => $count)
                <tr>
                    <td style="border: 1px solid #ccc; padding: 8px;">{{ $program }}</td>
                    <td style="border: 1px solid #ccc; padding: 8px; text-align: center; font-weight: 500;">{{ $count }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="no-print" style="margin-bottom: 20px; padding: 15px; border: 1px solid #ccc;">
        <h2>Active Officers of the Day</h2>
        @if($activeOfficers->isEmpty())
            <p style="padding: 15px; text-align: center; color: #757575;">No active officers currently logged in.</p>
        @else
            <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                <thead>
                    <tr style="background-color: #f0f0f0;">
                        <th style="border: 1px solid #ccc; padding: 10px; text-align: left;">#</th>
                        <th style="border: 1px solid #ccc; padding: 10px; text-align: left;">Officer Name</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activeOfficers as $index => $officer)
                    <tr>
                        <td style="border: 1px solid #ccc; padding: 8px; width: 50px; text-align: center;">{{ $index + 1 }}</td>
                        <td style="border: 1px solid #ccc; padding: 8px;">{{ $officer->name }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- button ofr ad -->
    <div style="position: fixed; bottom: 5px; right: 5px; display: flex; flex-direction: column; gap: 5px; z-index: 999;">
        <button type="button" class="fab-main-button" id="openPendingODModal" style="
            min-width: 140px;
            height: 30px;
            border-radius: 10px;
            padding: 5px 10px;
            text-align: center;
            border: none;
            background-color: #2E7D32;
            color: white;
            font-size: 15px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            transition: all 0.25s ease;
        " title="Pending OD Approvals">
            👤 OD APPROVAL
        </button>
        
        <button type="button" class="fab-main-button" id="openAdminUsersModal" style="
            min-width: 140px;
            height: 30px;
            border-radius: 10px;
            padding: 5px 10px;
            text-align: center;
            border-radius: 10px;
            border: none;
            background-color: #1565C0;
            color: white;
            font-size: 15px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            transition: all 0.25s ease;
        " title="Admin Users">
            ⚙️ ADMINS
        </button>
    </div>

    <!-- Pending OD Approvals Modal -->
    <dialog class="largeModal" id="pendingODModal">
        <h3 style="margin-bottom: 20px;">Pending OD Approvals</h3>
        @if($pendingOfficers->isEmpty())
            <p style="text-align: center; padding: 30px; color: #757575;">No pending officer approvals.</p>
        @else
            <ul style="max-height: 400px; overflow-y: auto;">
                @foreach($pendingOfficers as $officer)
                    <li style="padding: 12px 0; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #eee;">
                        {{ $officer->name }}
                        <form action="{{ route('admin.officers.approve', $officer->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="fab-button fab-approve">
                                Approve
                            </button>
                        </form>
                    </li>
                @endforeach
            </ul>
        @endif
        <div class="dialog-actions" style="margin-top: 20px;">
            <button type="button" class="cancel-btn closePendingODModal">Close</button>
        </div>
    </dialog>

    <!-- Admin Users Modal -->
    <dialog class="largeModal" id="adminUsersModal">
        <h3 style="margin-bottom: 20px;">Admin Users</h3>
        <div style="margin-bottom: 15px; text-align: right;">
            <button type="button" class="fab-button fab-approve addAdminButton">Add New Admin</button>
        </div>
        @if($admins->isEmpty())
            <p style="text-align: center; padding: 30px; color: #757575;">No admin users found.</p>
        @else
            <table style="width: 100%; border-collapse: collapse; max-height: 350px; overflow-y: auto; display: block;">
                <thead>
                    <tr style="border-bottom: 1px solid #ccc;">
                        <th style="text-align: left; padding: 8px; width: 40%;">Username</th>
                        <th style="text-align: left; padding: 8px; width: 30%;">Password</th>
                        <th style="text-align: left; padding: 8px; width: 30%;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($admins as $admin)
                        <tr style="border-bottom: 1px solid #ddd;">
                            <td style="padding: 8px;">{{ $admin->username }}</td>
                            <td style="padding: 8px;">••••••••</td>
                            <td style="padding: 8px;">
                                <button type="button" class="fab-button fab-edit editAdminButton" data-id="{{ $admin->id }}" data-username="{{ $admin->username }}">Edit</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        <div class="dialog-actions" style="margin-top: 20px;">
            <button type="button" class="cancel-btn closeAdminUsersModal">Close</button>
        </div>
    </dialog>

    <form action="{{ route('admin.logout') }}" method="POST" style="display: inline; margin-bottom: 20px;">
        @csrf
        <button type="submit" style="padding: 8px 16px; background-color: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer;">Go back</button>
    </form>

    <!-- Transmittal Management -->
    <div class="no-print" style="margin-bottom: 20px; padding: 15px; border: 1px solid #ccc; background-color: #f9f9f9;">
        <label>
            <input type="checkbox" id="unassigned-toggle" {{ request('unassigned_only') ? 'checked' : '' }}>
            Show only records without admin transmittal numbers
        </label>
    </div>

    <!-- TABLE FILTERS (SEPARATE FROM DASHBOARD FILTERS) -->
    <div class="no-print" style="margin-bottom: 20px; padding: 15px; border: 1px solid #ccc; background: #fff;">
        <h3 style="margin-top: 0; margin-bottom: 15px;">TABLE FILTERS</h3>
        <form method="GET" action="{{ route('admin') }}" style="margin: 0;">
            <div style="display: flex; flex-wrap: wrap; gap: 10px; align-items: end;">
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Search Farmer</label>
                    <input type="text" name="farmerName" value="{{ request('farmerName') }}" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                </div>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Search Encoder</label>
                    <input type="text" name="encoderName" value="{{ request('encoderName') }}" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                </div>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Program</label>
                    <select name="program" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                        <option value="">All Programs</option>
                        @foreach($allPrograms as $program)
                        <option value="{{ $program }}" {{ request('program') == $program ? 'selected' : '' }}>{{ $program }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Line</label>
                    <select name="line" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                        <option value="">All Lines</option>
                        @foreach($allLines as $line)
                        <option value="{{ $line }}" {{ request('line') == $line ? 'selected' : '' }}>{{ $line }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Province</label>
                    <select name="province" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                        <option value="">All Provinces</option>
                        @foreach($allProvinces as $province)
                        <option value="{{ $province }}" {{ request('province') == $province ? 'selected' : '' }}>{{ $province }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Municipality</label>
                    <select name="municipality" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                        <option value="">All Municipalities</option>
                        @foreach($allMunicipalities as $municipality)
                        <option value="{{ $municipality }}" {{ request('municipality') == $municipality ? 'selected' : '' }}>{{ $municipality }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Barangay</label>
                    <select name="barangay" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                        <option value="">All Barangays</option>
                        @foreach($allBarangays as $barangay)
                        <option value="{{ $barangay }}" {{ request('barangay') == $barangay ? 'selected' : '' }}>{{ $barangay }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Source</label>
                    <select name="source" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                        <option value="">All Sources</option>
                        @foreach($allSources as $source)
                        <option value="{{ $source }}" {{ request('source') == $source ? 'selected' : '' }}>{{ $source }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Control #</label>
                    <input type="text" name="transmittal_number" value="{{ request('transmittal_number') }}" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                </div>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Admin Transmittal</label>
                    <input type="text" name="admin_transmittal_number" value="{{ request('admin_transmittal_number') }}" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                </div>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Date Encoded</label>
                    <input type="date" name="date_encoded" value="{{ request('date_encoded') }}" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                </div>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Cause of Damage</label>
                    <input type="text" name="causeOfDamage" value="{{ request('causeOfDamage') }}" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                </div>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label style="font-size: 12px; font-weight: bold;">Remarks</label>
                    <input type="text" name="remarks" value="{{ request('remarks') }}" style="padding: 6px; font-size: 12px; border: 1px solid #ccc; border-radius: 3px;">
                </div>
                <button type="submit" style="padding: 8px 16px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Filter Table</button>
                <a href="{{ route('admin') }}" style="padding: 8px 16px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 4px; font-weight: bold; display: inline-block; line-height: 20px;">Clear</a>
            </div>
        </form>
    </div>

    <p>NL RECORDS:</p>
    <form id="bulk-form" method="POST" action="{{ route('admin.bulk-delete') }}">
        @csrf
        @method('DELETE')
        <input type="hidden" name="record_ids" id="selected-record-ids">
        <div class="no-print" style="margin-bottom: 15px; display: flex; gap: 10px; flex-wrap: wrap;">
            <button type="button" id="delete-multiple-btn" style="padding: 8px 16px; background-color: #ffc107; color: #212529; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Delete multiple</button>
            <button type="button" id="delete-selected-btn" style="padding: 8px 16px; background-color: #6c757d; color: white; border: none; border-radius: 4px; cursor: not-allowed; font-weight: bold;" disabled>Delete Selected</button>
            <button type="button" id="select-transmit-btn" style="padding: 8px 16px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Select Records for transmit</button>
            <button type="button" id="transmit-selected-btn" style="padding: 8px 16px; background-color: #6c757d; color: white; border: none; border-radius: 4px; cursor: not-allowed; font-weight: bold;" disabled>Transmit Selected Records</button>
            <a href="{{ route('admin.export-excel', request()->query()) }}" target="_blank" style="padding: 8px 16px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; text-decoration: none;">Export to CSV</a>
        </div>
        <div style="overflow-x: auto; width: 100%; margin-bottom: 20px; border: 1px solid #ccc; position: relative;">
            <x-table :records="$records" :showEncoder="true" :showFilters="false" :showAdminTransmittal="true" :allPrograms="$allPrograms" :allLines="$allLines" :allSources="$allSources" :allModes="$allModes" :showCheckbox="false" />
        </div>
    </form>
    <div class="received-by">Received By: ____________________</div>
    <dialog class="editRecordDialog">
        <form class="editRecordform" method="POST">
            @csrf
            @method('PUT')
            <label for="farmerName">Farmer Name:</label>
            <input type="text" id="farmerName" name="farmerName">
            
            <label for="editProvince">Province:</label>
            <select name="province" id="editProvince" required>
                <option value="">Select Province</option>
                <option value="Aurora">Aurora</option>
                <option value="Nueva Ecija">Nueva Ecija</option>
            </select>
            
            <label for="editMunicipality">Municipality:</label>
            <select name="municipality" id="editMunicipality" required disabled>
                <option value="">Select Municipality</option>
            </select>
            
            <label for="editBarangay">Barangay:</label>
            <select name="barangay" id="editBarangay" required disabled>
                <option value="">Select Barangay</option>
            </select>
            
            <input type="hidden" name="address" id="editRecordAddress">
            
            <label for="line">Line:</label>
            <select name="line" id="line">
                <option value="">Select Line</option>
                <option value="rice">rice</option>
                <option value="corn">corn</option>
                <option value="high-value">High-Value Crops</option>
                <option value="clti">CLTI</option>
                <option value="livestock">Livestock</option>
                <option value="non-crop">Non-Crop</option>
                <option value="fisheries">Fisheries</option>
            </select>
            
            <label for="program">Program:</label>
            <select name="program" id="program">
                <option value="">Select Program</option>
                <option value="RSBSA">RSBSA</option>
                <option value="AGRI-SENSO">AGRI-SENSO</option>
                <option value="ACEF">ACEF</option>
                <option value="ANYO">ANYO</option>
                <option value="OTHER-LI LC">OTHER-LI LC</option>
                <option value="OTHER-LBP ACP">OTHER-LBP ACP</option>
                <option value="REGULAR">REGULAR</option>
                <option value="SELF-FINANCED">SELF-FINANCED</option>
            </select>
            
            <label for="source">Source:</label>
            <select name="source" id="source">
                <option value="">Select Source</option>
                <option value="OD">OD</option>
                <option value="Email">Email</option>
                <option value="Facebook">Facebook</option>
            </select>
            
            <label for="causeOfDamage">Cause of Damage:</label>
            <input type="text" id="causeOfDamage" name="causeOfDamage">
            
            <label for="modeOfPayment">Mode of payment:</label>
            <select name="modeOfPayment" id="modeOfPayment">
                <option value="">Select Mode of payment</option>
                <option value="check">Check</option>
                <option value="palawan">Palawan Pay</option>
            </select>
            
            <label for="remarks">Remarks - Care of:</label>
            <input type="text" id="remarks" name="remarks">
            
            <label for="transmittal_number">Control Number:</label>
            <input type="text" id="transmittal_number" name="transmittal_number" placeholder="e.g., 2026-0420-001...">
            
            <label for="admin_transmittal_number">Admin Transmittal Number:</label>
            <input type="text" id="admin_transmittal_number" name="admin_transmittal_number" placeholder="e.g., 001, 002, 003...">

            <label for="clear_admin_transmittal_number" style="display: block; margin-top: 8px;">
                <input type="checkbox" id="clear_admin_transmittal_number" name="clear_admin_transmittal_number" value="1">
                Clear Admin Transmittal Number
            </label>
            
            <button type="submit">Update Record</button>
        </form>
        <button class="closeEditRecordDialog">Close</button>
    </dialog>
    <dialog class="deleteRecordDialog">
        <form class="deleteRecordForm" method="POST">
            @csrf
            @method('DELETE')
            <p>Delete this record?</p>
            <p class="deleteRecordMessage"></p>
            <button type="submit">Confirm Delete</button>
            <button type="button" class="cancelDeleteRecord">Cancel</button>
        </form>
    </dialog>

    <!-- Bulk Delete Confirmation Dialog -->
    <dialog class="bulkDeleteDialog">
        <h3>Confirm Bulk Delete</h3>
        <p>The following records will be deleted:</p>
        <ul class="bulk-delete-list"></ul>
        <p>Are you sure you want to proceed?</p>
        <button type="button" id="confirm-bulk-delete">Confirm Delete</button>
        <button type="button" class="cancelBulkDelete">Cancel</button>
    </dialog>

    <!-- Edit Admin Dialog -->
    <dialog class="editAdminDialog">
        <form class="editAdminForm" method="POST">
            @csrf
            @method('PUT')
            <h3>Edit Admin Credentials</h3>
            <label for="adminUsername">Username:</label>
            <input type="text" id="adminUsername" name="username" required>
            <label for="adminPassword">Password:</label>
            <input type="password" id="adminPassword" name="password" required placeholder="Enter new password">
            <button type="submit">Update</button>
            <button type="button" class="closeEditAdminDialog">Cancel</button>
        </form>
    </dialog>

    <!-- Approve Officer Dialog -->
    <dialog class="approveOfficerDialog">
        <h3>Confirm Officer Approval</h3>
        <p>Are you sure you want to approve <strong id="approveOfficerName"></strong>?</p>
        <form id="approveOfficerForm" method="POST">
            @csrf
            <div class="dialog-actions">
                <button type="submit" class="confirm-btn">Confirm Approve</button>
                <button type="button" class="cancel-btn closeApproveDialog">Cancel</button>
            </div>
        </form>
    </dialog>

    <!-- Add Admin Dialog -->
    <dialog class="addAdminDialog">
        <form action="{{ route('admin.users.create') }}" method="POST">
            @csrf
            <h3>Add New Admin User</h3>
            <label for="newAdminUsername">Username:</label>
            <input type="text" id="newAdminUsername" name="username" required>
            <label for="newAdminPassword">Password:</label>
            <input type="password" id="newAdminPassword" name="password" required placeholder="Minimum 6 characters">
            <div class="dialog-actions">
                <button type="submit" class="confirm-btn">Create Admin</button>
                <button type="button" class="cancel-btn closeAddAdminDialog">Cancel</button>
            </div>
        </form>
    </dialog>
<script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Select all potential elements
            const openPendingODModal = document.getElementById('openPendingODModal');
            const openAdminUsersModal = document.getElementById('openAdminUsersModal');
            const pendingODModal = document.getElementById('pendingODModal');
            const adminUsersModal = document.getElementById('adminUsersModal');
            const deleteSelectedBtn = document.getElementById('delete-selected');
            const transmitSelectedBtn = document.getElementById('transmit-selected');
            const bulkDeleteDialog = document.querySelector('.bulkDeleteDialog');

            // 2. Modal Open Logic
            openPendingODModal?.addEventListener('click', () => pendingODModal?.showModal());
            openAdminUsersModal?.addEventListener('click', () => adminUsersModal?.showModal());

            // 3. Modal Close Logic
            document.querySelectorAll('.closePendingODModal').forEach(btn => {
                btn.addEventListener('click', () => pendingODModal?.close());
            });
            document.querySelectorAll('.closeAdminUsersModal').forEach(btn => {
                btn.addEventListener('click', () => adminUsersModal?.close());
            });

            // 4. Bulk Delete Logic
            deleteSelectedBtn?.addEventListener('click', function() {
                if (!this.disabled) bulkDeleteDialog?.showModal();
            });

            document.getElementById('confirmBulkDelete')?.addEventListener('click', () => {
                document.getElementById('bulk-form')?.submit();
            });

            document.querySelector('.cancelBulkDelete')?.addEventListener('click', () => {
                bulkDeleteDialog?.close();
            });

            // 5. Transmit Selected Records
            transmitSelectedBtn?.addEventListener('click', function() {
                if (this.disabled) return;
                
                const selectedIds = Array.from(document.querySelectorAll('.record-checkbox:checked')).map(cb => cb.value);
                if (selectedIds.length === 0) return;

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('admin.add-to-print-preview') }}";
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = "{{ csrf_token() }}";
                form.appendChild(csrfInput);
                
                const idsInput = document.createElement('input');
                idsInput.type = 'hidden';
                idsInput.name = 'record_ids';
                idsInput.value = JSON.stringify(selectedIds);
                form.appendChild(idsInput);
                
                document.body.appendChild(form);
                form.submit();
            });
        });
    </script>

@endsection
