@extends('layout.layout')

@section('title', 'Admin')

@section('content')
    <style>
        .received-by { display: none; }
    </style>
    <style media="print">
        @page { size: landscape; }
        h1, p, form, dialog, .no-print { display: none !important; }
        .print-table { display: block !important; }
        table, table * { display: table !important; }
        .page-break { page-break-after: always; }
        .received-by { display: block; position: fixed; bottom: 20px; right: 20px; font-size: 14px; }
    </style>
    <h1>Admin Page</h1>
    <p>USHEL admin page</p>

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

    <a href="{{ route('welcome') }}">
        <button>Go back</button>
    </a>

    <form method="GET" action="{{ route('admin') }}" style="margin-bottom: 20px;">
        <p>FILTER INFO:</p>
        <label>Farmer Name: <input type="text" name="farmerName" value="{{ request('farmerName') }}"></label>
        <label>Address: <input type="text" name="address" value="{{ request('address') }}"></label>
        <label>Encoder Name: <input type="text" name="encoderName" value="{{ request('encoderName') }}"></label>
        <label>Program:
            <select name="program">
                <option value="">All</option>
                <option value="program1" {{ request('program') == 'program1' ? 'selected' : '' }}>RSBSA</option>
                <option value="program2" {{ request('program') == 'program2' ? 'selected' : '' }}>LBP</option>
                <option value="program3" {{ request('program') == 'program3' ? 'selected' : '' }}>Program 3</option>
            </select>
        </label>
        <label>Line:
            <select name="line">
                <option value="">All</option>
                <option value="rice" {{ request('line') == 'rice' ? 'selected' : '' }}>rice</option>
                <option value="corn" {{ request('line') == 'corn' ? 'selected' : '' }}>corn</option>
                <option value="high-value" {{ request('line') == 'high-value' ? 'selected' : '' }}>High-Value Crops</option>
                <option value="clti" {{ request('line') == 'clti' ? 'selected' : '' }}>CLTI</option>
                <option value="livestock" {{ request('line') == 'livestock' ? 'selected' : '' }}>Livestock</option>
                <option value="non-crop" {{ request('line') == 'non-crop' ? 'selected' : '' }}>Non-Crop</option>
                <option value="fisheries" {{ request('line') == 'fisheries' ? 'selected' : '' }}>Fisheries</option>
            </select>
        </label>
        <label>Cause of Damage: <input type="text" name="causeOfDamage" value="{{ request('causeOfDamage') }}"></label>
        <label>Mode of Payment:
            <select name="modeOfPayment">
                <option value="">All</option>
                <option value="check" {{ request('modeOfPayment') == 'check' ? 'selected' : '' }}>Check</option>
                <option value="palawan" {{ request('modeOfPayment') == 'palawan' ? 'selected' : '' }}>Palawan Pay</option>
            </select>
        </label>
        <label>Remarks: <input type="text" name="remarks" value="{{ request('remarks') }}"></label>
        <label>Date Added: <input type="date" name="date" value="{{ request('date') }}"></label>
        <button type="submit">Filter</button>
        <a href="{{ route('admin') }}">Clear Filters</a>
    </form>
    <p>NL RECORDS:</p>
    <form id="bulk-form" method="POST" action="{{ route('admin.bulk-delete') }}">
        @csrf
        @method('DELETE')
        <div class="no-print">
            <button type="button" id="bulk-delete-btn">Delete Selected</button>
            <a href="{{ route('admin.export-excel', request()->query()) }}" target="_blank">Export to CSV</a>
            <a href="{{ route('admin.export-pdf', request()->query()) }}" target="_blank">Export to PDF</a>
        </div>
        <div class="print-table">
            <x-table :records="$records" :showEncoder="true" />
        </div>
    </form>
    <div class="received-by">Received By: ____________________</div>
    <button onclick="window.print()">Print Table</button>
    <dialog class="editRecordDialog">
        <form class="editRecordform" method="POST">
            @csrf
            @method('PUT')
            <label for="farmerName">Farmer Name:</label>
            <input type="text" id="farmerName" name="farmerName">
            <label for="address">Address:</label>
            <input type="text" id="address" name="address">
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
                <option value="program1">RSBSA</option>
                <option value="program2">LBP</option>
                <option value="program3">Program 3</option>
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
@endsection