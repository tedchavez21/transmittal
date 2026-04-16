@props(['records', 'showDelete' => true, 'showEncoder' => false, 'showApproval' => false, 'showAction' => false, 'showCheckbox' => true, 'showFilters' => false, 'showSortableHeaders' => true])

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
                <a href="{{ getSortUrl('remarks', $currentSort, $currentOrder, $oppositeOrder) }}" style="color: inherit; text-decoration: none; cursor: pointer;">Remarks - Care of{{ getSortIndicator('remarks', $currentSort, $currentOrder) }}</a>
                @else
                Remarks - Care of
                @endif
            </th>
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
            <th class="no-print"></th>
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
                    <option value="RSBSA" {{ request('program') == 'RSBSA' ? 'selected' : '' }}>RSBSA</option>
                    <option value="AGRI-SENSO" {{ request('program') == 'AGRI-SENSO' ? 'selected' : '' }}>AGRI-SENSO</option>
                    <option value="ACEF" {{ request('program') == 'ACEF' ? 'selected' : '' }}>ACEF</option>
                    <option value="ANYO" {{ request('program') == 'ANYO' ? 'selected' : '' }}>ANYO</option>
                </select>
            </th>
            <th>
                <select name="line">
                    <option value="">All Lines</option>
                    <option value="rice" {{ request('line') == 'rice' ? 'selected' : '' }}>Rice</option>
                    <option value="corn" {{ request('line') == 'corn' ? 'selected' : '' }}>Corn</option>
                    <option value="high-value" {{ request('line') == 'high-value' ? 'selected' : '' }}>High-Value</option>
                    <option value="clti" {{ request('line') == 'clti' ? 'selected' : '' }}>CLTI</option>
                    <option value="livestock" {{ request('line') == 'livestock' ? 'selected' : '' }}>Livestock</option>
                    <option value="non-crop" {{ request('line') == 'non-crop' ? 'selected' : '' }}>Non-Crop</option>
                    <option value="fisheries" {{ request('line') == 'fisheries' ? 'selected' : '' }}>Fisheries</option>
                </select>
            </th>
            <th><input type="text" placeholder="Damage" name="causeOfDamage" value="{{ request('causeOfDamage') }}"></th>
            <th><input type="text" placeholder="Remarks" name="remarks" value="{{ request('remarks') }}"></th>
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
            <th class="no-print" style="text-align: center;"><button type="button" class="table-search-btn" id="table-search-btn" style="padding: 6px 12px; background-color: #1976D2; color: white; border: none; border-radius: 3px; cursor: pointer; font-weight: 600; font-size: 12px;">Search</button></th>
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
                <button class="editButton"
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
        <tr class="page-break"><td colspan="{{ 9 + ($showCheckbox ? 1 : 0) + ($showDelete ? 1 : 0) + ($showEncoder ? 1 : 0) + ($showApproval ? 1 : 0) + ($showAction ? 1 : 0) }}" style="border: none; height: 50px;"></td></tr>
        @endif
    @endforeach
    </tbody>
</table>