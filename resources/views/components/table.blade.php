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

<table>
    <thead>
        <tr>
            <th class="no-print col-checkbox" style="display: none;">
                <input type="checkbox" id="select-all">
            </th>
            <th class="no-print col-checkbox-transmit" style="display: none;">
                <input type="checkbox" id="select-all-transmit">
            </th>
            <th class="no-print col-edit">Edit</th>
            @if($showDelete)
            <th class="no-print col-delete">Delete</th>
            @endif
            @if($showEncoder)
            <th class="col-encoder">
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('encoderName', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Encoder{{ getSortIndicator('encoderName', $currentSort, $currentOrder) }}</a>
                @else
                Encoder
                @endif
            </th>
            @endif
            <th class="col-farmer-name">
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('farmerName', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Farmer Name{{ getSortIndicator('farmerName', $currentSort, $currentOrder) }}</a>
                @else
                Farmer Name
                @endif
            </th>
            @if(!$hideProvinceColumn)
            <th class="col-province" class="col-province">
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('province', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Province{{ getSortIndicator('province', $currentSort, $currentOrder) }}</a>
                @else
                Province
                @endif
            </th>
            @endif
            @if(!$hideProvinceColumn)
            <th class="col-municipality" class="col-municipality">
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('municipality', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Municipality{{ getSortIndicator('municipality', $currentSort, $currentOrder) }}</a>
                @else
                Municipality
                @endif
            </th>
            @endif
            <th class="col-barangay" class="col-barangay">
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('barangay', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">{{ $hideProvinceColumn ? 'Address' : 'Barangay' }}{{ getSortIndicator('barangay', $currentSort, $currentOrder) }}</a>
                @else
                {{ $hideProvinceColumn ? 'Address' : 'Barangay' }}
                @endif
            </th>
            <th class="col-program" class="col-program">
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('program', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Program{{ getSortIndicator('program', $currentSort, $currentOrder) }}</a>
                @else
                Program
                @endif
            </th>
            @if(!$hideSourceColumn)
            <th class="col-source">
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('source', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Source{{ getSortIndicator('source', $currentSort, $currentOrder) }}</a>
                @else
                Source
                @endif
            </th>
            @endif
            @if($showAdminTransmittal)
            <th class="col-date-received">
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('date_received', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Date Received{{ getSortIndicator('date_received', $currentSort, $currentOrder) }}</a>
                @else
                Date Received
                @endif
            </th>
            @endif
            <th class="col-control-number">
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('transmittal_number', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Control Number{{ getSortIndicator('transmittal_number', $currentSort, $currentOrder) }}</a>
                @else
                Control Number
                @endif
            </th>
            <th class="col-line" class="col-line">
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('line', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Line{{ getSortIndicator('line', $currentSort, $currentOrder) }}</a>
                @else
                Line
                @endif
            </th>
            <th class="col-causeOfDamage" class="col-causeOfDamage">
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('causeOfDamage', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Cause of Damage{{ getSortIndicator('causeOfDamage', $currentSort, $currentOrder) }}</a>
                @else
                Cause of Damage
                @endif
            </th>
            <th class="col-modeOfPayment">
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('modeOfPayment', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Mode of Payment{{ getSortIndicator('modeOfPayment', $currentSort, $currentOrder) }}</a>
                @else
                Mode of Payment
                @endif
            </th>
            <th class="col-date-occurrence">
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('date_occurrence', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Date of Occurrence{{ getSortIndicator('date_occurrence', $currentSort, $currentOrder) }}</a>
                @else
                Date of Occurrence
                @endif
            </th>
            @if(!$hideAccountsColumn)
            <th class="col-accounts">
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('accounts', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Account{{ getSortIndicator('accounts', $currentSort, $currentOrder) }}</a>
                @else
                Account
                @endif
            </th>
            @endif
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
        @if($showFilters)
        <tr class="filter-row">
            @if($showCheckbox)
            <th class="no-print"></th>
            @endif
            <th class="no-print"></th>
            @if($showDelete)
            <th class="no-print"></th>
            @endif
            @if($showEncoder)
            <th><input type="text" placeholder="Search Encoder" name="encoderName" value="{{ request('encoderName') }}"></th>
            @endif
            <th><input type="text" placeholder="Search Farmer" name="farmerName" value="{{ request('farmerName') }}"></th>
            <th><input type="text" placeholder="Province" name="province" value="{{ request('province') }}"></th>
            <th><input type="text" placeholder="Municipality" name="municipality" value="{{ request('municipality') }}"></th>
            <th><input type="text" placeholder="Barangay" name="barangay" value="{{ request('barangay') }}"></th>
            <th>
                <select name="program">
                    <option value="">All Programs</option>
                    @foreach($allPrograms as $program)
                    <option value="{{ $program }}" {{ request('program') == $program ? 'selected' : '' }}>{{ $program }}</option>
                    @endforeach
                </select>
            </th>
            <th>
                <select name="line">
                    <option value="">All Lines</option>
                    @foreach($allLines as $line)
                    <option value="{{ $line }}" {{ request('line') == $line ? 'selected' : '' }}>{{ $line }}</option>
                    @endforeach
                </select>
            </th>
            <th><input type="text" placeholder="Damage" name="causeOfDamage" value="{{ request('causeOfDamage') }}"></th>
            <th>
                <select name="modeOfPayment">
                    <option value="">All Modes</option>
                    @foreach($allModes as $mode)
                    <option value="{{ $mode }}" {{ request('modeOfPayment') == $mode ? 'selected' : '' }}>{{ $mode }}</option>
                    @endforeach
                </select>
            </th>
            <th><input type="text" placeholder="Date of occurrence" name="date_occurrence" value="{{ request('date_occurrence') }}"></th>
            @if(!$hideAccountsColumn)
            <th><input type="text" placeholder="Account" name="accounts" value="{{ request('accounts') }}"></th>
            @endif
            <th><input type="text" placeholder="Remarks" name="remarks" value="{{ request('remarks') }}"></th>
            @if($showAdminTransmittal)
            <th><input type="text" placeholder="Admin Transmittal" name="admin_transmittal_number" value="{{ request('admin_transmittal_number') }}" style="width: calc(100% - 50px); box-sizing: border-box;"></th>
            @endif
            @if($showApproval)
            <th>
                <select name="approved">
                    <option value="">All Status</option>
                    <option value="1" {{ request('approved') == '1' ? 'selected' : '' }}>Approved</option>
                    <option value="0" {{ request('approved') == '0' ? 'selected' : '' }}>Pending</option>
                </select>
            </th>
            @endif
            @if($showAction)
            <th class="no-print"></th>
            @endif
        </tr>
        @endif
    </thead>
    <tbody>
    @foreach($records as $record)
        <tr class="{{ !$record->approved ? 'pending' : '' }}">
            <td class="no-print col-checkbox" style="display: none;">
                <input type="checkbox" name="record_ids[]" value="{{ $record->id }}" class="record-checkbox">
            </td>
            <td class="no-print col-checkbox-transmit" style="display: none;">
                <input type="checkbox" name="record_ids_transmit[]" value="{{ $record->id }}" class="record-checkbox-transmit">
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
            @if($showEncoder)
            <td class="col-encoder">{{ $record->encoderName ?? 'Unknown' }}</td>
            @endif
            <td class="col-farmer-name">{{ $record->farmerName }}</td>
            @if(!$hideProvinceColumn)
            <td class="col-province">{{ $record->province ?? '—' }}</td>
            @endif
            @if(!$hideProvinceColumn)
            <td class="col-municipality">{{ $record->municipality ?? '—' }}</td>
            @endif
            <td class="col-barangay">
                @if($hideProvinceColumn)
                    {{ trim(implode(', ', array_filter([$record->barangay, $record->municipality]))) ?: '—' }}
                @else
                    {{ $record->barangay ?? '—' }}
                @endif
            </td>
            <td class="col-program">{{ $record->program }}</td>
            @if(!$hideSourceColumn)
            <td class="col-source">{{ $record->source ?? '—' }}</td>
            @endif
            <td class="col-control-number">{{ $record->transmittal_number ?? '—' }}</td>
            <td class="col-line">{{ $record->line }}</td>
            <td class="col-causeOfDamage">{{ $record->causeOfDamage }}</td>
            <td class="col-modeOfPayment">{{ $record->modeOfPayment ?: '—' }}</td>
            <td class="col-date-occurrence">{{ $record->date_occurrence ? $record->date_occurrence : '—' }}</td>
            @if($showAdminTransmittal)
            <td class="col-date-received">{{ $record->date_received ? $record->date_received : '—' }}</td>
            @endif
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
        <tr class="page-break"><td colspan="{{ 11 + ($showCheckbox ? 1 : 0) + ($showDelete ? 1 : 0) + ($showEncoder ? 1 : 0) + ($showAdminTransmittal ? 1 : 0) + ($showApproval ? 1 : 0) + ($showAction ? 1 : 0) - ($hideAccountsColumn ? 1 : 0) }}" style="border: none; height: 50px;"></td></tr>
        @endif
    @endforeach
    </tbody>
</table>