@props(['records', 'showDelete' => true, 'showEncoder' => false, 'showApproval' => false, 'showAction' => false, 'showCheckbox' => true])

<table>
    <tr>
        @if($showCheckbox)
        <th class="no-print"><input type="checkbox" id="select-all"></th>
        @endif
        <th class="no-print">Edit</th>
        @if($showDelete)
        <th class="no-print">Delete</th>
        @endif
        @if($showEncoder)
        <th>Encoder</th>
        @endif
        <th>Farmer Name</th>
        <th>Address</th>
        <th>Program</th>
        <th>Line</th>
        <th>Cause of Damage</th>
        <th>Remarks - Care of</th>
        @if($showApproval)
        <th>Status</th>
        @endif
        @if($showAction)
        <th class="no-print">Action</th>
        @endif
    </tr>
    @foreach($records as $record)
        <tr class="{{ !$record->approved ? 'pending' : '' }}">
            @if($showCheckbox)
            <td class="no-print"><input type="checkbox" name="record_ids[]" value="{{ $record->id }}" class="record-checkbox" data-farmer-name="{{ $record->farmerName }}"></td>
            @endif
            <td class="no-print">
                <button class="editButton"
                data-id="{{ $record->id }}"
                data-farmeName="{{ $record->farmerName }}"
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
            <td>{{ $record->address }}</td>
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
        <tr class="page-break"><td colspan="{{ 7 + ($showCheckbox ? 1 : 0) + ($showDelete ? 1 : 0) + ($showEncoder ? 1 : 0) + ($showApproval ? 1 : 0) + ($showAction ? 1 : 0) }}" style="border: none; height: 50px;"></td></tr>
        @endif
    @endforeach
</table>