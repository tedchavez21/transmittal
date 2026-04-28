@props(['records', 'showDelete' => true, 'showEncoder' => false, 'showApproval' => false, 'showAction' => false, 'showCheckbox' => true, 'showFilters' => false, 'showSortableHeaders' => true, 'showAdminTransmittal' => false, 'hideAccountsColumn' => false, 'hideSourceColumn' => false, 'hideProvinceColumn' => false, 'hideDateReceivedColumn' => false, 'useDateEncodedAsDateReceived' => false, 'allPrograms' => [], 'allLines' => [], 'allSources' => [], 'allModes' => []])

<style>
/* Default account field color - blue */
.account-field {
    color: #0066CC !important;
}

/* Sticky table header using CSS */
.records-table thead th {
    position: sticky;
    top: 0;
    z-index: 10;
    background-color: #006c35;
    color: white;
    font-weight: bold;
    padding: 8px 12px;
    text-align: left;
    white-space: nowrap;
}

/* Make account text bold only when it's a link */
.account-field:link,
.account-field:visited {
    font-weight: bold !important;
}

/* Style account field when row is selected with green background */
tr.selected .account-field {
    color: white !important;
    font-weight: bold !important;
}

/* Also target rows with green background (any selected state) */
tr[style*="background-color: rgb(0, 108, 53)"] .account-field,
tr[style*="background-color: #006c35"] .account-field,
tr.bg-green-600 .account-field,
tr.bg-green-700 .account-field {
    color: white !important;
    font-weight: bold !important;
}

/* Table wrapper with scroll */
.table-wrapper {
    overflow-x: auto;
    overflow-y: auto;
    max-height: calc(100vh - 250px);
    border: none;
}

.table-wrapper table {
    border-collapse: separate;
    border-spacing: 0;
    width: 100%;
    table-layout: auto;
}

/* JavaScript-based sticky headers with improved alignment */
.fixed-table-header {
    position: sticky;
    top: 0;
    z-index: 100;
    background-color: #006c35;
    overflow-x: auto;
    overflow-y: hidden;
    border: none;
    scrollbar-width: none; /* Firefox */
    -ms-overflow-style: none; /* IE and Edge */
}

.fixed-table-header::-webkit-scrollbar {
    display: none; /* Chrome, Safari, Opera */
}

.fixed-table-header table {
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
    width: 100%;
    table-layout: fixed;
}

.fixed-table-header th {
    background-color: #006c35 !important;
    color: white !important;
    font-weight: bold !important;
    padding: 8px 12px;
    text-align: left;
    white-space: nowrap;
    border: none;
    box-sizing: border-box;
    min-width: 80px;
}

/* Allow header columns to adjust dynamically based on content */
.fixed-table-header .col-remarks {
    max-width: 300px;
}

.fixed-table-header th:last-child {
    border-right: none;
}

.table-wrapper td {
    padding: 8px 12px;
    border-bottom: 1px solid #dee2e6;
    border-right: 1px solid #f0f0f0;
    box-sizing: border-box;
    white-space: nowrap;
}

.table-wrapper .col-remarks {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 300px;
}

/* Allow columns to adjust dynamically based on content */

.table-wrapper td:last-child {
    border-right: none;
}

/* Ensure checkbox columns have proper width in fixed header */
.fixed-table-header .col-checkbox,
.fixed-table-header .col-checkbox-transmit {
    width: 50px;
    min-width: 50px;
    text-align: center;
    padding: 8px 4px;
}

/* Ensure checkbox columns are visible when they should be */
.fixed-table-header .col-checkbox[style*="display: none"],
.fixed-table-header .col-checkbox-transmit[style*="display: none"] {
    display: none !important;
}

.fixed-table-header .col-checkbox:not([style*="display: none"]),
.fixed-table-header .col-checkbox-transmit:not([style*="display: none"]) {
    display: table-cell !important;
    width: 50px !important;
    min-width: 50px !important;
}

.table-wrapper table {
    border-collapse: separate;
    border-spacing: 0;
    margin-top: -1px; /* Prevent double border */
}

.table-wrapper th {
    visibility: visible;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Using CSS sticky positioning for table headers - no JavaScript needed
    // The CSS .records-table thead th { position: sticky; top: 0; } handles both vertical and horizontal scrolling
    // Handle checkbox changes to style account fields
    function updateAccountStyling() {
        const checkboxes = document.querySelectorAll('input[type="checkbox"].record-checkbox, input[type="checkbox"].record-checkbox-transmit');
        
        checkboxes.forEach(checkbox => {
            const row = checkbox.closest('tr');
            if (row) {
                if (checkbox.checked) {
                    row.classList.add('selected');
                } else {
                    row.classList.remove('selected');
                }
            }
        });
    }
    
    // Initial styling
    updateAccountStyling();
    
    // Add event listeners to checkboxes
    document.addEventListener('change', function(e) {
        if (e.target.type === 'checkbox' && (e.target.classList.contains('record-checkbox') || e.target.classList.contains('record-checkbox-transmit'))) {
            const row = e.target.closest('tr');
            if (row) {
                if (e.target.checked) {
                    row.classList.add('selected');
                } else {
                    row.classList.remove('selected');
                }
            }
        }
    });
    
    // Handle select all checkboxes
    document.addEventListener('change', function(e) {
        if (e.target.id === 'select-all' || e.target.id === 'select-all-transmit') {
            setTimeout(updateAccountStyling, 10);
        }
    });
});
</script>

@php
$currentSort = request('sort_by', 'id');
$currentOrder = request('sort_order', 'desc');
$oppositeOrder = $currentOrder === 'asc' ? 'desc' : 'asc';

// Helper to generate sort URL
function getSortUrl($column, $currentSort, $currentOrder, $oppositeOrder) {
    $newOrder = $currentSort === $column ? $oppositeOrder : 'asc';
    return url()->current() . '?' . http_build_query(array_merge(request()->query(), ['sort_by' => $column, 'sort_order' => $newOrder]));
}

// Helper to get sort indicator
function getSortIndicator($column, $currentSort, $currentOrder) {
    if ($currentSort === $column) {
        return $currentOrder === 'asc' ? ' ▲' : ' ▼';
    }
    return '';
}
@endphp

@if($showFilters)
<div class="table-filters" style="margin-bottom: 15px; padding: 15px; background: #f0fdf4; border: 1px solid rgba(0, 108, 53, 0.18); border-radius: 16px;">
    <form method="GET" style="display: contents;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; align-items: end;">
            <!-- 1. Transmittal Number Filter -->
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Control Number</label>
                <input type="text" placeholder="Control Number" name="transmittal_number" value="{{ request('transmittal_number') }}" style="width: 100%; padding: 5px;">
            </div>
            
            <!-- 2. Encoder Filter -->
            @if($showEncoder)
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Encoder</label>
                <input type="text" placeholder="Search Encoder" name="encoderName" value="{{ request('encoderName') }}" style="width: 100%; padding: 5px;">
            </div>
            @endif
            
            <!-- 3. Farmer Name Filter -->
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Farmer Name</label>
                <input type="text" placeholder="Search Farmer" name="farmerName" value="{{ request('farmerName') }}" style="width: 100%; padding: 5px;">
            </div>
            
            <!-- 4. Municipality Filter -->
            @if(!$hideProvinceColumn)
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Municipality</label>
                <input type="text" placeholder="Municipality" name="municipality" value="{{ request('municipality') }}" style="width: 100%; padding: 5px;">
            </div>
            @endif
            
            <!-- 5. Barangay Filter -->
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Barangay</label>
                <input type="text" placeholder="Barangay" name="barangay" value="{{ request('barangay') }}" style="width: 100%; padding: 5px;">
            </div>
            
            <!-- 6. Province Filter -->
            @if(!$hideProvinceColumn)
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Province</label>
                <input type="text" placeholder="Province" name="province" value="{{ request('province') }}" style="width: 100%; padding: 5px;">
            </div>
            @endif
            
            <!-- 7. Line Filter -->
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Line</label>
                <select name="line" style="width: 100%; padding: 5px;">
                    <option value="">All Lines</option>
                    @foreach($allLines as $line)
                    <option value="{{ $line }}" {{ request('line') == $line ? 'selected' : '' }}>{{ $line }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- 8. Program Filter -->
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Program</label>
                <select name="program" style="width: 100%; padding: 5px;">
                    <option value="">All Programs</option>
                    @foreach($allPrograms as $program)
                    <option value="{{ $program }}" {{ request('program') == $program ? 'selected' : '' }}>{{ $program }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- 9. Date of Occurrence Filter -->
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Date of Occurrence</label>
                <select name="date_occurrence_filter_type" style="width: 100%; padding: 5px; margin-bottom: 5px;">
                    <option value="">Filter Type</option>
                    <option value="single" {{ request('date_occurrence_filter_type') == 'single' ? 'selected' : '' }}>Single Date</option>
                    <option value="range" {{ request('date_occurrence_filter_type') == 'range' ? 'selected' : '' }}>Date Range</option>
                </select>
                <div id="date_occurrence_single" style="display: {{ request('date_occurrence_filter_type') == 'single' ? 'block' : 'none' }};">
                    <input type="date" name="date_occurrence" value="{{ request('date_occurrence') }}" style="width: 100%; padding: 5px;">
                </div>
                <div id="date_occurrence_range" style="display: {{ request('date_occurrence_filter_type') == 'range' ? 'block' : 'none' }};">
                    <input type="date" name="date_occurrence_from" value="{{ request('date_occurrence_from') }}" placeholder="From" style="width: 100%; padding: 5px; margin-bottom: 5px;">
                    <input type="date" name="date_occurrence_to" value="{{ request('date_occurrence_to') }}" placeholder="To" style="width: 100%; padding: 5px;">
                </div>
            </div>
            
            <!-- 10. Cause of Damage Filter -->
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Cause of Damage</label>
                <input type="text" placeholder="Damage" name="causeOfDamage" value="{{ request('causeOfDamage') }}" style="width: 100%; padding: 5px;">
            </div>
            
            <!-- 11. Date Received Filter -->
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Date Received</label>
                <select name="date_received_filter_type" style="width: 100%; padding: 5px; margin-bottom: 5px;">
                    <option value="">Filter Type</option>
                    <option value="single" {{ request('date_received_filter_type') == 'single' ? 'selected' : '' }}>Single Date</option>
                    <option value="range" {{ request('date_received_filter_type') == 'range' ? 'selected' : '' }}>Date Range</option>
                </select>
                <div id="date_received_single" style="display: {{ request('date_received_filter_type') == 'single' ? 'block' : 'none' }};">
                    <input type="date" name="date_received" value="{{ request('date_received') }}" style="width: 100%; padding: 5px;">
                </div>
                <div id="date_received_range" style="display: {{ request('date_received_filter_type') == 'range' ? 'block' : 'none' }};">
                    <input type="date" name="date_received_from" value="{{ request('date_received_from') }}" placeholder="From" style="width: 100%; padding: 5px; margin-bottom: 5px;">
                    <input type="date" name="date_received_to" value="{{ request('date_received_to') }}" placeholder="To" style="width: 100%; padding: 5px;">
                </div>
            </div>
            
            <!-- 12. Account Filter -->
            @if(!$hideAccountsColumn)
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Account</label>
                <input type="text" placeholder="Account" name="accounts" value="{{ request('accounts') }}" style="width: 100%; padding: 5px;">
            </div>
            @endif
            
            <!-- 13. Mode of Payment Filter -->
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Mode of Payment</label>
                <select name="modeOfPayment" style="width: 100%; padding: 5px;">
                    <option value="">All Modes</option>
                    @foreach($allModes as $mode)
                    <option value="{{ $mode }}" {{ request('modeOfPayment') == $mode ? 'selected' : '' }}>{{ $mode }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- 14. Remarks Filter -->
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Remarks</label>
                <input type="text" placeholder="Remarks" name="remarks" value="{{ request('remarks') }}" style="width: 100%; padding: 5px;">
            </div>
            
            @if($showAdminTransmittal)
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Admin Transmittal</label>
                <input type="text" placeholder="Admin Transmittal" name="admin_transmittal_number" value="{{ request('admin_transmittal_number') }}" style="width: 100%; padding: 5px;">
            </div>
            @endif
            
            @if($showApproval)
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Status</label>
                <select name="approved" style="width: 100%; padding: 5px;">
                    <option value="">All Status</option>
                    <option value="1" {{ request('approved') == '1' ? 'selected' : '' }}>Approved</option>
                    <option value="0" {{ request('approved') == '0' ? 'selected' : '' }}>Pending</option>
                </select>
            </div>
            @endif
            
            <!-- Submit Button -->
            <div style="display: flex; gap: 10px;">
                <button type="submit" style="padding: 8px 16px; background: #006c35; color: white; border: none; border-radius: 10px; cursor: pointer; font-weight: 800; font-size: 13px; letter-spacing: 0.2px; transition: background 0.15s ease;">Apply Filters</button>
                <a href="{{ request()->url() }}" style="padding: 8px 16px; background: #64748b; color: white; text-decoration: none; border-radius: 10px; font-weight: 800; font-size: 13px; letter-spacing: 0.2px;">Clear</a>
            </div>
        </div>
    </form>
</div>
@endif

<div class="table-wrapper">
<table class="records-table" style="width: 100%; border-collapse: separate; border-spacing: 0;">
    <thead>
        <tr>
            @if($showCheckbox)
            <th class="no-print col-checkbox" style="display: none;">
                <input type="checkbox" id="select-all">
            </th>
            @endif
            @if(!$hideAccountsColumn && $showAdminTransmittal)
            <th class="no-print col-checkbox-transmit" style="display: none;">
                <input type="checkbox" id="select-all-transmit" style="display: none;">
            </th>
            @endif
            <th class="no-print col-edit">Edit</th>
            @if($showDelete)
            <th class="no-print col-delete">Delete</th>
            @endif
            <!-- 1. Transmittal Number -->
            <th class="col-control-number">
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('transmittal_number', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Control Number{{ getSortIndicator('transmittal_number', $currentSort, $currentOrder) }}</a>
                @else
                Control Number
                @endif
            </th>
            
            <!-- 2. Encoder -->
            @if($showEncoder)
            <th class="col-encoder">
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('encoderName', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Encoder{{ getSortIndicator('encoderName', $currentSort, $currentOrder) }}</a>
                @else
                Encoder
                @endif
            </th>
            @endif
            
            <!-- 3. Farmer Name -->
            <th class="col-farmer-name">
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('farmerName', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Farmer Name{{ getSortIndicator('farmerName', $currentSort, $currentOrder) }}</a>
                @else
                Farmer Name
                @endif
            </th>
            
            <!-- 4. Municipality -->
            @if(!$hideProvinceColumn)
            <th class="col-municipality">
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('municipality', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Municipality{{ getSortIndicator('municipality', $currentSort, $currentOrder) }}</a>
                @else
                Municipality
                @endif
            </th>
            @endif
            
            <!-- 5. Barangay -->
            <th class="col-barangay">
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('barangay', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">{{ $hideProvinceColumn ? 'Address' : 'Barangay' }}{{ getSortIndicator('barangay', $currentSort, $currentOrder) }}</a>
                @else
                {{ $hideProvinceColumn ? 'Address' : 'Barangay' }}
                @endif
            </th>
            
            <!-- 6. Province -->
            @if(!$hideProvinceColumn)
            <th class="col-province">
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('province', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Province{{ getSortIndicator('province', $currentSort, $currentOrder) }}</a>
                @else
                Province
                @endif
            </th>
            @endif
            
            <!-- 7. Line -->
            <th class="col-line">
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('line', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Line{{ getSortIndicator('line', $currentSort, $currentOrder) }}</a>
                @else
                Line
                @endif
            </th>
            
            <!-- 8. Program -->
            <th class="col-program">
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('program', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Program{{ getSortIndicator('program', $currentSort, $currentOrder) }}</a>
                @else
                Program
                @endif
            </th>
            
            <!-- 9. Date of Occurrence -->
            <th class="col-date-occurrence">
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('date_occurrence', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Date of Occurrence{{ getSortIndicator('date_occurrence', $currentSort, $currentOrder) }}</a>
                @else
                Date of Occurrence
                @endif
            </th>
            
            <!-- 10. Cause of Damage -->
            <th class="col-causeOfDamage">
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('causeOfDamage', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Cause of Damage{{ getSortIndicator('causeOfDamage', $currentSort, $currentOrder) }}</a>
                @else
                Cause of Damage
                @endif
            </th>
            
            @if(!$hideDateReceivedColumn)
            <!-- 11. Date Received -->
            <th class="col-date-received">
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('date_received', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Date Received{{ getSortIndicator('date_received', $currentSort, $currentOrder) }}</a>
                @else
                Date Received
                @endif
            </th>
            @endif
            
            <!-- 12. Account -->
            @if(!$hideAccountsColumn)
            <th class="col-accounts">
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('accounts', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Account{{ getSortIndicator('accounts', $currentSort, $currentOrder) }}</a>
                @else
                Account
                @endif
            </th>
            @endif
            
            <!-- 13. Mode of Payment -->
            <th class="col-modeOfPayment">
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('modeOfPayment', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Mode of Payment{{ getSortIndicator('modeOfPayment', $currentSort, $currentOrder) }}</a>
                @else
                Mode of Payment
                @endif
            </th>
            
            <!-- 14. Remarks -->
            <th class="col-remarks">
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('remarks', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Remarks{{ getSortIndicator('remarks', $currentSort, $currentOrder) }}</a>
                @else
                Remarks
                @endif
            </th>
            
            <!-- 15. Date Encoded -->
            <th class="col-date-encoded">
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('created_at', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Date Encoded{{ getSortIndicator('created_at', $currentSort, $currentOrder) }}</a>
                @else
                Date Encoded
                @endif
            </th>
            @if($showAdminTransmittal)
            <th class="col-admin-transmittal-number">
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('admin_transmittal_number', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Transmittal Num{{ getSortIndicator('admin_transmittal_number', $currentSort, $currentOrder) }}</a>
                @else
                Transmittal Num
                @endif
            </th>
            @endif
            @if($showApproval)
            <th class="col-status">
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('approved', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Status{{ getSortIndicator('approved', $currentSort, $currentOrder) }}</a>
                @else
                Status
                @endif
            </th>
            @endif
            @if($showAction)
            <th class="no-print">Action</th>
            @endif
        </tr>
    </thead>
    <tbody>
    @foreach($records as $record)
        <tr class="{{ !$record->approved ? 'pending' : '' }} record-row" data-record-id="{{ $record->id }}">
            <td class="no-print col-checkbox" style="display: none;">
                <input type="checkbox" name="record_ids[]" value="{{ $record->id }}" class="record-checkbox">
            </td>
            <td class="no-print col-checkbox-transmit" style="display: none;">
                <input type="checkbox" name="record_ids_transmit[]" value="{{ $record->id }}" class="record-checkbox-transmit" style="display: none;">
            </td>
            <td class="no-print col-edit">
                <button type="button" class="editButton"
                data-id="{{ $record->id }}"
                data-farmer-name="{{ e($record->farmerName) }}"
                data-province="{{ e($record->province) }}"
                data-municipality="{{ e($record->municipality) }}"
                data-barangay="{{ e($record->barangay) }}"
                data-address="{{ e($record->address) }}"
                data-program="{{ e($record->program) }}"
                data-line="{{ e($record->line) }}"
                data-cause-of-damage="{{ e($record->causeOfDamage) }}"
                data-mode-of-payment="{{ e($record->modeOfPayment) }}"
                data-accounts="{{ e($record->accounts) }}"
                data-fb-page-url="{{ e($record->facebook_page_url ?? '') }}"
                data-date-occurrence="{{ e($record->date_occurrence ?? '') }}"
                data-date-received="{{ e($record->date_received ? $record->date_received->format('Y-m-d') : '') }}"
                data-remarks="{{ e($record->remarks) }}"
                data-source="{{ e($record->source) }}"
                data-transmittal-number="{{ e($record->transmittal_number) }}"
                data-admin-transmittal-number="{{ e($record->admin_transmittal_number) }}"
                >edit</button>
            </td>
            @if($showDelete)
            <td class="no-print col-delete">
                <button type="button" class="deleteButton" data-id="{{ $record->id }}" data-farmer-name="{{ $record->farmerName }}">
                    delete
                </button>
            </td>
            @endif
            <!-- 1. Transmittal Number -->
            <td class="col-control-number">{{ $record->transmittal_number ?? '—' }}</td>
            
            <!-- 2. Encoder -->
            @if($showEncoder)
            <td class="col-encoder">{{ $record->encoderName ?? 'Unknown' }}</td>
            @endif
            
            <!-- 3. Farmer Name -->
            <td class="col-farmer-name">{{ $record->farmerName }}</td>
            
            <!-- 4. Municipality -->
            @if(!$hideProvinceColumn)
            <td class="col-municipality">{{ $record->municipality ?? '—' }}</td>
            @endif
            
            <!-- 5. Barangay -->
            <td class="col-barangay">
                @if($hideProvinceColumn)
                    {{ trim(implode(', ', array_filter([$record->barangay, $record->municipality]))) ?: '—' }}
                @else
                    {{ $record->barangay ?? '—' }}
                @endif
            </td>
            
            <!-- 6. Province -->
            @if(!$hideProvinceColumn)
            <td class="col-province">{{ $record->province ?? '—' }}</td>
            @endif
            
            <!-- 7. Line -->
            <td class="col-line">{{ $record->line }}</td>
            
            <!-- 8. Program -->
            <td class="col-program">{{ $record->program }}</td>
            
            <!-- 9. Date of Occurrence -->
            <td class="col-date-occurrence">{{ $record->date_occurrence ? $record->date_occurrence : '—' }}</td>
            
            <!-- 10. Cause of Damage -->
            <td class="col-causeOfDamage">{{ $record->causeOfDamage }}</td>
            
            @if(!$hideDateReceivedColumn)
            <!-- 11. Date Received -->
            <td class="col-date-received">
                @if($useDateEncodedAsDateReceived)
                    {{ $record->created_at ? $record->created_at->format('M d, Y') : '—' }}
                @else
                    {{ $record->date_received ? (is_string($record->date_received) ? $record->date_received : $record->date_received->format('M d, Y')) : '—' }}
                @endif
            </td>
            @endif
            
            <!-- 12. Account -->
            @if(!$hideAccountsColumn)
            <td class="col-accounts">
                @php
                    $accountLabel = $record->accounts;
                    $fbUrl = $record->facebook_page_url ?? '';
                    $isFacebook = ($record->source ?? '') === 'Facebook';
                    $href = $fbUrl && filter_var($fbUrl, FILTER_VALIDATE_URL) ? $fbUrl : null;
                @endphp
                @if($isFacebook && $href && $accountLabel)
                    <a href="{{ e($href) }}" target="_blank" rel="noopener noreferrer" class="account-field">{{ e($accountLabel) }}</a>
                @elseif($accountLabel)
                    <span class="account-field">{{ $accountLabel }}</span>
                @else
                    <span class="account-field">—</span>
                @endif
            </td>
            @endif
            
            <!-- 13. Mode of Payment -->
            <td class="col-modeOfPayment">{{ $record->modeOfPayment ?: '—' }}</td>
            
            <!-- 14. Remarks -->
            <td class="col-remarks">{{ $record->remarks }}</td>
            
            <!-- 15. Date Encoded -->
            <td class="col-date-encoded">{{ $record->created_at ? $record->created_at->format('M d, Y') : '—' }}</td>
            @if($showAdminTransmittal)
            <td class="col-admin-transmittal-number">{{ empty($record->admin_transmittal_number) ? '—' : $record->admin_transmittal_number }}</td>
            @endif
            @if($showApproval)
            <td class="col-status">{{ $record->approved ? 'Approved' : 'Pending' }}</td>
            @endif
            @if($showAction)
            <td class="no-print">
                @if(!$record->approved)
                    <a href="{{ route('admin.records.approve', $record->id) }}" class="no-print">Approve</a>
                @else
                    &mdash;
                @endif
            </td>
            @endif
        </tr>
            @endforeach
    @if($records->isEmpty())
    </tbody>
</table>
</div>
<div class="empty-state" style="display:flex;flex-direction:column;align-items:center;justify-content:center;padding:48px 20px;text-align:center;">
    <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-bottom:16px;opacity:0.5;">
        <rect x="18" y="8" width="44" height="56" rx="6" stroke="#006c35" stroke-width="2.5" fill="#f0fdf4"/>
        <rect x="28" y="4" width="24" height="12" rx="4" stroke="#006c35" stroke-width="2.5" fill="#fff"/>
        <line x1="28" y1="28" x2="52" y2="28" stroke="#86efac" stroke-width="2.5" stroke-linecap="round"/>
        <line x1="28" y1="38" x2="48" y2="38" stroke="#86efac" stroke-width="2.5" stroke-linecap="round"/>
        <line x1="28" y1="48" x2="42" y2="48" stroke="#86efac" stroke-width="2.5" stroke-linecap="round"/>
        <circle cx="58" cy="58" r="14" fill="#006c35"/>
        <path d="M51 58l4 4 9-9" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
    <div style="font-size:16px;font-weight:900;color:#0f172a;">No records yet</div>
    <div style="font-size:13px;color:#64748b;margin-top:4px;font-weight:600;">Records will appear here once they are added.</div>
</div>
    @else
    </tbody>
</table>
</div>
    @endif

<style>
/* Sticky table headers */
.records-table thead th {
    position: sticky;
    top: 0;
    z-index: 2;
    background: #006c35;
    color: #fff;
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #005428;
}
.records-table thead th a {
    color: #fff !important;
    text-decoration: none;
}
.records-table thead th a:hover {
    text-decoration: underline;
}

/* Table filter inputs & selects — PCIC theme */
.table-filters label {
    font-size: 12px;
    font-weight: 900;
    color: #334155;
    letter-spacing: 0.2px;
}
.table-filters input,
.table-filters select {
    width: 100%;
    height: 36px;
    padding: 0 10px;
    font-size: 13px;
    border: 1px solid rgba(15, 23, 42, 0.16);
    border-radius: 10px;
    background: #fff;
    outline: none;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
    box-sizing: border-box;
}
.table-filters input:focus,
.table-filters select:focus {
    border-color: rgba(0, 108, 53, 0.55);
    box-shadow: 0 0 0 3px rgba(0, 108, 53, 0.15);
}
.table-filters select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748b' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    padding-right: 34px;
}

/* Record row interaction */
.record-row {
    cursor: pointer;
    transition: all 0.2s ease;
}

.record-row:hover {
    background-color: #e8e8e8;
}

.record-row.highlighted {
    background-color: #006c35 !important;
    border: 3px solid #003d1c !important;
    box-shadow: 0 4px 8px rgba(0, 61, 28, 0.4);
    color: white !important;
    font-weight: 600;
}

.record-row.highlighted td {
    border-top: 1px solid #003d1c !important;
    border-bottom: 1px solid #003d1c !important;
    color: white !important;
}

.record-row.highlighted td:first-child {
    border-left: 3px solid #003d1c !important;
}

.record-row.highlighted td:last-child {
    border-right: 3px solid #003d1c !important;
}

/* Button styling in highlighted rows */
.record-row.highlighted .editButton {
    background-color: white !important;
    color: #006c35 !important;
    border: 1px solid white !important;
    font-weight: 600;
}

.record-row.highlighted .deleteButton {
    background-color: #f44336 !important;
    color: white !important;
    border: 1px solid #f44336 !important;
    font-weight: 600;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Row highlighting functionality
    const recordRows = document.querySelectorAll('.record-row');
    
    recordRows.forEach(row => {
        row.addEventListener('click', function(e) {
            // Don't highlight if clicking on buttons, inputs, or links
            if (e.target.tagName === 'BUTTON' || e.target.tagName === 'INPUT' || e.target.tagName === 'A' || e.target.closest('button, input, a')) {
                return;
            }

            const isAlreadyHighlighted = this.classList.contains('highlighted');
            
            // Remove highlight from all rows
            recordRows.forEach(r => r.classList.remove('highlighted'));

            // Toggle highlight for clicked row
            if (!isAlreadyHighlighted) {
                this.classList.add('highlighted');
            }
        });
    });
    
    // Date filter type switching functionality
    const dateOccurrenceFilterType = document.querySelector('select[name="date_occurrence_filter_type"]');
    const dateReceivedFilterType = document.querySelector('select[name="date_received_filter_type"]');
    
    if (dateOccurrenceFilterType) {
        dateOccurrenceFilterType.addEventListener('change', function() {
            const singleDiv = document.getElementById('date_occurrence_single');
            const rangeDiv = document.getElementById('date_occurrence_range');
            
            if (this.value === 'single') {
                singleDiv.style.display = 'block';
                rangeDiv.style.display = 'none';
            } else if (this.value === 'range') {
                singleDiv.style.display = 'none';
                rangeDiv.style.display = 'block';
            } else {
                singleDiv.style.display = 'none';
                rangeDiv.style.display = 'none';
            }
        });
    }
    
    if (dateReceivedFilterType) {
        dateReceivedFilterType.addEventListener('change', function() {
            const singleDiv = document.getElementById('date_received_single');
            const rangeDiv = document.getElementById('date_received_range');
            
            if (this.value === 'single') {
                singleDiv.style.display = 'block';
                rangeDiv.style.display = 'none';
            } else if (this.value === 'range') {
                singleDiv.style.display = 'none';
                rangeDiv.style.display = 'block';
            } else {
                singleDiv.style.display = 'none';
                rangeDiv.style.display = 'none';
            }
        });
    }
});
</script>