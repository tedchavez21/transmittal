@extends('layout.layout')

@section('title', 'Admin')

@section('content')
    <style>
        .received-by { display: none; }
    </style>
    <style media="print">
        @page { size: landscape; }
        h1, p, form, dialog, .no-print { display: none !important; }
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

    <!-- Dashboard Statistics -->
    <div class="no-print" style="margin-bottom: 20px; padding: 10px; border: 1px solid #ccc;">
        <h2>Dashboard</h2>
        <p>Total Records: {{ $totalRecords }}</p>
        <p>Records by Program:</p>
        <ul>
            @foreach($recordsByProgram as $program => $count)
            <li>{{ $program }}: {{ $count }}</li>
            @endforeach
        </ul>
        <p>Recent Records (Last 7 days): {{ $recentRecords }}</p>
    </div>
    <div class="no-print" style="margin-bottom: 20px; padding: 10px; border: 1px solid #ccc;">
        <h2>Active Officers of the Day</h2>
        @if($activeOfficers->isEmpty())
            <p>No active officers currently logged in.</p>
        @else
            <ul>
                @foreach($activeOfficers as $officer)
                    <li>{{ $officer->name }}</li>
                @endforeach
            </ul>
        @endif
    </div>
    <div class="no-print" style="margin-bottom: 20px; padding: 10px; border: 1px solid #ccc;">
        <h2>Pending OD Approvals</h2>
        @if($pendingOfficers->isEmpty())
            <p>No pending officer approvals.</p>
        @else
            <ul>
                @foreach($pendingOfficers as $officer)
                    <li>
                        {{ $officer->name }}
                        <form action="{{ route('admin.officers.approve', $officer->id) }}" method="POST" style="display: inline-block; margin-left: 10px;">
                            @csrf
                            <button type="submit">Approve</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
    <div class="no-print" style="margin-bottom: 20px; padding: 10px; border: 1px solid #ccc;">
        <h2>Admin Users</h2>
        <div style="margin-bottom: 15px;">
            <button type="button" class="addAdminButton">Add New Admin</button>
        </div>
        @if($admins->isEmpty())
            <p>No admin users found.</p>
        @else
            <table style="width: 100%; border-collapse: collapse;">
                <tr style="border-bottom: 1px solid #ccc;">
                    <th style="text-align: left; padding: 8px;">Username</th>
                    <th style="text-align: left; padding: 8px;">Password</th>
                    <th style="text-align: left; padding: 8px;">Actions</th>
                </tr>
                @foreach($admins as $admin)
                    <tr style="border-bottom: 1px solid #ddd;">
                        <td style="padding: 8px;">{{ $admin->username }}</td>
                        <td style="padding: 8px;">••••••••</td>
                        <td style="padding: 8px;">
                            <button type="button" class="editAdminButton" data-id="{{ $admin->id }}" data-username="{{ $admin->username }}">Edit</button>
                        </td>
                    </tr>
                @endforeach
            </table>
        @endif
    </div>

    <form action="{{ route('admin.logout') }}" method="POST" style="display: inline; margin-bottom: 20px;">
        @csrf
        <button type="submit" style="padding: 8px 16px; background-color: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer;">Go back</button>
    </form>

    <form method="GET" action="{{ route('admin') }}" style="margin-bottom: 20px;">
        <p>FILTER BY DATE:</p>
        <label>Date Added: <input type="date" name="date" value="{{ request('date') }}"></label>
        <button type="submit">Filter by Date</button>
        <a href="{{ route('admin') }}">Clear All Filters</a>
    </form>

    <!-- Transmittal Management -->
    <div class="no-print" style="margin-bottom: 20px; padding: 15px; border: 1px solid #ccc; background-color: #f9f9f9;">
        <label>
            <input type="checkbox" id="unassigned-toggle" {{ request('unassigned_only') ? 'checked' : '' }}>
            Show only records without admin transmittal numbers
        </label>
    </div>
    <p>NL RECORDS:</p>
    <form id="bulk-form" method="POST" action="{{ route('admin.bulk-delete') }}">
        @csrf
        @method('DELETE')
        <div class="no-print">
            <button type="button" id="bulk-delete-btn">Delete Selected</button>
            <a href="{{ route('admin.export-excel', request()->query()) }}" target="_blank">Export to CSV</a>
        </div>
                <div style="overflow-x: auto; width: 100%; margin-bottom: 20px; border: 1px solid #ccc; position: relative;">
                <x-table :records="$records" :showEncoder="true" :showFilters="true" :showAdminTransmittal="true" :allPrograms="$allPrograms" :allLines="$allLines" :allSources="$allSources" :allModes="$allModes" />
            </div>
            <button type="button" class="table-search-btn" id="table-search-btn" style="position: fixed; bottom: 20px; right: 20px; padding: 8px 16px; background-color: #1976D2; color: white; border: none; border-radius: 3px; cursor: pointer; font-weight: 600; font-size: 14px; z-index: 100;">Apply Filters</button>
        </div>
    </form>
    <div class="received-by">Received By: ____________________</div>
    @php $previewRecordIds = session('admin_print_preview_record_ids', []); @endphp
    <form id="add-to-transmittal-form" method="POST" action="{{ route('admin.add-to-print-preview') }}" style="display: inline; margin-right: 10px;">
        @csrf
        <input type="hidden" name="query" id="add-to-transmittal-query" value="{{ request()->getQueryString() ? ('?' . request()->getQueryString()) : '' }}">
        <button type="submit" style="padding: 8px 16px; background-color: #28a745; color: white; border: none; border-radius: 3px; cursor: pointer;">Add Current Records to Transmittal</button>
    </form>
    @if(count($previewRecordIds) > 0)
    <form method="POST" action="{{ route('admin.clear-print-preview') }}" style="display: inline; margin-right: 10px;">
        @csrf
        <button type="submit" style="padding: 8px 16px; background-color: #dc3545; color: white; border: none; border-radius: 3px; cursor: pointer;">Clear Preview</button>
    </form>
    @endif
    <button type="button" id="open-print-preview">Print Table</button>
    <span style="margin-left: 20px; font-weight: 600;">Preview bucket: {{ count($previewRecordIds) }} record{{ count($previewRecordIds) === 1 ? '' : 's' }}</span>
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
            
            <label for="admin_transmittal_number">Admin Transmittal Number:</label>
            <input type="text" id="admin_transmittal_number" name="admin_transmittal_number" placeholder="e.g., 001, 002, 003...">
            
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

    <!-- Add Admin Dialog -->
    <dialog class="addAdminDialog">
        <form action="{{ route('admin.users.create') }}" method="POST">
            @csrf
            <h3>Add New Admin User</h3>
            <label for="newAdminUsername">Username:</label>
            <input type="text" id="newAdminUsername" name="username" required>
            <label for="newAdminPassword">Password:</label>
            <input type="password" id="newAdminPassword" name="password" required placeholder="Minimum 6 characters">
            <button type="submit">Create Admin</button>
            <button type="button" class="closeAddAdminDialog">Cancel</button>
        </form>
    </dialog>
    <script>
        document.getElementById('open-print-preview').addEventListener('click', function() {
            const url = new URL("{{ route('admin.print-preview') }}", window.location.origin);
            const params = new URLSearchParams(window.location.search);
            params.forEach((value, key) => {
                if (value) {
                    url.searchParams.set(key, value);
                }
            });
            window.open(url.toString(), '_blank');
        });

        const addToTransmittalForm = document.getElementById('add-to-transmittal-form');
        if (addToTransmittalForm) {
            addToTransmittalForm.addEventListener('submit', function() {
                document.getElementById('add-to-transmittal-query').value = window.location.search || '';
            });
        }

        // Handle unassigned records toggle
        document.getElementById('unassigned-toggle').addEventListener('change', function() {
            const url = new URL(window.location);
            if (this.checked) {
                url.searchParams.set('unassigned_only', '1');
            } else {
                url.searchParams.delete('unassigned_only');
            }
            window.location.href = url.toString();
        });
    </script>
@endsection