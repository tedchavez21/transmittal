@props(['records', 'showDelete' => true, 'showEncoder' => false, 'showApproval' => false, 'showAction' => false, 'showCheckbox' => true, 'showFilters' => false, 'showSortableHeaders' => true, 'showAdminTransmittal' => false, 'allPrograms' => [], 'allLines' => [], 'allSources' => [], 'allModes' => []])

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
            @if($showCheckbox)
            <th class="no-print"><input type="checkbox" id="select-all"></th>
            @endif
            <th class="no-print">Edit</th>
            @if($showDelete)
            <th class="no-print">Delete</th>
            @endif
            @if($showEncoder)
            <th>
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('encoderName', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Encoder{{ getSortIndicator('encoderName', $currentSort, $currentOrder) }}</a>
                @else
                Encoder
                @endif
            </th>
            @endif
            <th>
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('farmerName', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Farmer Name{{ getSortIndicator('farmerName', $currentSort, $currentOrder) }}</a>
                @else
                Farmer Name
                @endif
            </th>
            <th>
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('province', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Province{{ getSortIndicator('province', $currentSort, $currentOrder) }}</a>
                @else
                Province
                @endif
            </th>
            <th>
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('municipality', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Municipality{{ getSortIndicator('municipality', $currentSort, $currentOrder) }}</a>
                @else
                Municipality
                @endif
            </th>
            <th>
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('barangay', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Barangay{{ getSortIndicator('barangay', $currentSort, $currentOrder) }}</a>
                @else
                Barangay
                @endif
            </th>
            <th>
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('program', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Program{{ getSortIndicator('program', $currentSort, $currentOrder) }}</a>
                @else
                Program
                @endif
            </th>
            <th>
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('line', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Line{{ getSortIndicator('line', $currentSort, $currentOrder) }}</a>
                @else
                Line
                @endif
            </th>
            <th>
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('causeOfDamage', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Cause of Damage{{ getSortIndicator('causeOfDamage', $currentSort, $currentOrder) }}</a>
                @else
                Cause of Damage
                @endif
            </th>
            <th>
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('remarks', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Remarks{{ getSortIndicator('remarks', $currentSort, $currentOrder) }}</a>
                @else
                Remarks
                @endif
            </th>
            <th>
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('source', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Source{{ getSortIndicator('source', $currentSort, $currentOrder) }}</a>
                @else
                Source
                @endif
            </th>
            <th>
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('transmittal_number', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Control #{{ getSortIndicator('transmittal_number', $currentSort, $currentOrder) }}</a>
                @else
                Control Num
                @endif
            </th>
            @if($showAdminTransmittal)
            <th>
                @if($showSortableHeaders)
                <a href="{{ getSortUrl('admin_transmittal_number', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Transmittal Num{{ getSortIndicator('admin_transmittal_number', $currentSort, $currentOrder) }}</a>
                @else
                Transmittal Num
                @endif
            </th>
            @endif
            @if($showApproval)
            <th>
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
            <th><input type="text" placeholder="Remarks" name="remarks" value="{{ request('remarks') }}"></th>
            <th>
                <select name="source">
                    <option value="">All Sources</option>
                    @foreach($allSources as $source)
                    <option value="{{ $source }}" {{ request('source') == $source ? 'selected' : '' }}>{{ $source }}</option>
                    @endforeach
                </select>
            </th>
            <th><input type="text" placeholder="Transmittal" name="transmittal_number" value="{{ request('transmittal_number') }}" style="width: calc(100% - 50px); box-sizing: border-box;"></th>
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
            @if($showCheckbox)
            <td class="no-print"><input type="checkbox" name="record_ids[]" value="{{ $record->id }}" class="record-checkbox" data-farmer-name="{{ $record->farmerName }}"></td>
            @endif
            <td class="no-print">
                <button type="button" class="editButton"
                data-id="{{ $record->id }}"
                data-farmerName="{{ $record->farmerName }}"
                data-province="{{ $record->province }}"
                data-municipality="{{ $record->municipality }}"
                data-barangay="{{ $record->barangay }}"
                data-address="{{ $record->address }}"
                data-program="{{ $record->program }}"
                data-line="{{ $record->line }}"
                data-causeOfDamage="{{ $record->causeOfDamage }}"
                data-modeOfPayment="{{ $record->modeOfPayment }}"
                data-remarks="{{ $record->remarks }}"
                data-source="{{ $record->source }}"
                data-admin_transmittal_number="{{ $record->admin_transmittal_number }}"
                >edit</button>
            </td>
            @if($showDelete)
            <td class="no-print">
                <button type="button" class="deleteButton" data-id="{{ $record->id }}" data-farmer-name="{{ $record->farmerName }}">
                    delete
                </button>
            </td>
            @endif
            @if($showEncoder)
            <td>{{ $record->encoderName ?? 'Unknown' }}</td>
            @endif
            <td>{{ $record->farmerName }}</td>
            <td>{{ $record->province ?? '—' }}</td>
            <td>{{ $record->municipality ?? '—' }}</td>
            <td>{{ $record->barangay ?? '—' }}</td>
            <td>{{ $record->program }}</td>
            <td>{{ $record->line }}</td>
            <td>{{ $record->causeOfDamage }}</td>
            <td>{{ $record->remarks }}</td>
            <td>{{ $record->source }}</td>
            <td>{{ $record->transmittal_number ?? '—' }}</td>
            @if($showAdminTransmittal)
            <td>{{ $record->admin_transmittal_number ?? '—' }}</td>
            @endif
            @if($showApproval)
            <td>{{ $record->approved ? 'Approved' : 'Pending' }}</td>
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
        <tr class="page-break"><td colspan="{{ 11 + ($showCheckbox ? 1 : 0) + ($showDelete ? 1 : 0) + ($showEncoder ? 1 : 0) + ($showAdminTransmittal ? 1 : 0) + ($showApproval ? 1 : 0) + ($showAction ? 1 : 0) }}" style="border: none; height: 50px;"></td></tr>
        @endif
    @endforeach
    </tbody>
</table>