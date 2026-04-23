@props(['records', 'showDelete' => true, 'showEncoder' => false, 'showApproval' => false, 'showAction' => false, 'showCheckbox' => true, 'showFilters' => false, 'showSortableHeaders' => true, 'showAdminTransmittal' => false, 'hideAccountsColumn' => false, 'hideSourceColumn' => false, 'hideProvinceColumn' => false, 'allPrograms' => [], 'allLines' => [], 'allSources' => [], 'allModes' => []])

@php
$currentSort = request('sort_by', 'created_at');
$currentOrder = request('sort_order', 'asc');
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
<div class="table-filters" style="margin-bottom: 15px; padding: 15px; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px;">
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
                <button type="submit" style="padding: 8px 16px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">Apply Filters</button>
                <a href="{{ request()->url() }}" style="padding: 8px 16px; background: #6c757d; color: white; text-decoration: none; border-radius: 4px;">Clear</a>
            </div>
        </div>
    </form>
</div>
@endif

<table class="records-table" style="width: 100%; border-collapse: collapse; border: 1px solid #ccc;">
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
            <th class="col-municipality" class="col-municipality">
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('municipality', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Municipality{{ getSortIndicator('municipality', $currentSort, $currentOrder) }}</a>
                @else
                Municipality
                @endif
            </th>
            @endif
            
            <!-- 5. Barangay -->
            <th class="col-barangay" class="col-barangay">
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('barangay', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">{{ $hideProvinceColumn ? 'Address' : 'Barangay' }}{{ getSortIndicator('barangay', $currentSort, $currentOrder) }}</a>
                @else
                {{ $hideProvinceColumn ? 'Address' : 'Barangay' }}
                @endif
            </th>
            
            <!-- 6. Province -->
            @if(!$hideProvinceColumn)
            <th class="col-province" class="col-province">
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('province', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Province{{ getSortIndicator('province', $currentSort, $currentOrder) }}</a>
                @else
                Province
                @endif
            </th>
            @endif
            
            <!-- 7. Line -->
            <th class="col-line" class="col-line">
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('line', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Line{{ getSortIndicator('line', $currentSort, $currentOrder) }}</a>
                @else
                Line
                @endif
            </th>
            
            <!-- 8. Program -->
            <th class="col-program" class="col-program">
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
            <th class="col-causeOfDamage" class="col-causeOfDamage">
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('causeOfDamage', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Cause of Damage{{ getSortIndicator('causeOfDamage', $currentSort, $currentOrder) }}</a>
                @else
                Cause of Damage
                @endif
            </th>
            
            <!-- 11. Date Received -->
            <th class="col-date-received">
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('date_received', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Date Received{{ getSortIndicator('date_received', $currentSort, $currentOrder) }}</a>
                @else
                Date Received
                @endif
            </th>
            
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
            <th class="col-remarks" class="col-remarks">
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('remarks', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Remarks{{ getSortIndicator('remarks', $currentSort, $currentOrder) }}</a>
                @else
                Remarks
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
            
            <!-- 11. Date Received -->
            <td class="col-date-received">{{ $record->date_received ? $record->date_received : '—' }}</td>
            
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
                    <a href="{{ e($href) }}" target="_blank" rel="noopener noreferrer">{{ e($accountLabel) }}</a>
                @elseif($accountLabel)
                    {{ $accountLabel }}
                @else
                    —
                @endif
            </td>
            @endif
            
            <!-- 13. Mode of Payment -->
            <td class="col-modeOfPayment">{{ $record->modeOfPayment ?: '—' }}</td>
            
            <!-- 14. Remarks -->
            <td class="col-remarks">{{ $record->remarks }}</td>
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
        @if($loop->iteration % 40 == 0 && !$loop->last)
        <tr class="page-break"><td colspan="{{ 14 + ($showCheckbox ? 1 : 0) + ($showDelete ? 1 : 0) + ($showEncoder ? 1 : 0) + ($showAdminTransmittal ? 1 : 0) + ($showApproval ? 1 : 0) + ($showAction ? 1 : 0) - ($hideAccountsColumn ? 1 : 0) }}" style="border: none; height: 50px;"></td></tr>
        @endif
    @endforeach
    </tbody>
</table>

<style>
.record-row {
    cursor: pointer;
    transition: all 0.2s ease;
}

.record-row:hover {
    background-color: #f5f5f5;
}

.record-row.highlighted {
    background-color: #1976d2 !important;
    border: 3px solid #0d47a1 !important;
    box-shadow: 0 4px 8px rgba(13, 71, 161, 0.4);
    color: white !important;
    font-weight: 600;
}

.record-row.highlighted td {
    border-top: 1px solid #0d47a1 !important;
    border-bottom: 1px solid #0d47a1 !important;
    color: white !important;
}

.record-row.highlighted td:first-child {
    border-left: 3px solid #0d47a1 !important;
}

.record-row.highlighted td:last-child {
    border-right: 3px solid #0d47a1 !important;
}

/* Button styling in highlighted rows */
.record-row.highlighted .editButton {
    background-color: white !important;
    color: #1976d2 !important;
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
            
            // Remove highlight from all rows
            recordRows.forEach(r => r.classList.remove('highlighted'));
            
            // Add highlight to clicked row
            this.classList.add('highlighted');
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