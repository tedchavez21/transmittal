@extends('layout.layout')

@section('title', 'Facebook')

@section('content')
    <div class="odHeader">
        <section>
            <h3>Facebook</h3>
            <p>NL Entry Module</p>
        </section>
        @if($isLoggedIn)
        <section>
            <form action="{{ route('facebook.logout') }}" method="POST">
                @csrf
                <button class="logoutButton" type="submit">Logout</button>
            </form>
        </section>
        @endif
    </div>
    <div class="contentContainer">
    @if(!$isLoggedIn)
        <form action="{{ route('facebook.login') }}" method="POST" class="officerOfTheDayNames">
            @csrf
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Enter</button>
        </form>
        @if(session('error'))
            <p style="color: red;">{{ session('error') }}</p>
        @endif
    @else
        <br/>
        <button class="addRecordButton">Add Record</button>
        <dialog class="addRecordDialog">
            <h3>Add Record</h3>
            <form action="{{ route('records') }}" method="POST">
                @csrf
                <input type="hidden" name="source" value="Facebook">
            <label for="farmerName">Farmer Name:</label>
            <input type="text" id="farmerName" name="farmerName" required>
            <label for="province">Province:</label>
            <select name="province" id="province" required>
                <option value="">Select Province</option>
                <option value="Aurora">Aurora</option>
                <option value="Nueva Ecija">Nueva Ecija</option>
            </select>
            <label for="municipality">Municipality:</label>
            <select name="municipality" id="municipality" required disabled>
                <option value="">Select Municipality</option>
            </select>
            <label for="barangay">Barangay:</label>
            <select name="barangay" id="barangay" required disabled>
                <option value="">Select Barangay</option>
            </select>
            <input type="hidden" name="address" id="addRecordAddress">
            <label for="line">Line:</label>
            <select name="line" id="line" required>
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
            <select name="program" id="program" required>
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
            <label for="causeOfDamage">Cause of Damage:</label>
            <input type="text" id="causeOfDamage" name="causeOfDamage" required>
            <label for="modeOfPayment">Mode of payment:</label>
            <select name="modeOfPayment" id="modeOfPayment" required>
                <option value="">Select Mode of payment</option>
                <option value="check">Check</option>
                <option value="palawan">Palawan Pay</option>
            </select>
            <label for="remarks">Remarks - Care of:</label>
            <input type="text" id="remarks" name="remarks">
            <button type="submit">Add Record</button>
            <button type="button" class="closeAddRecordModal">Close</button>
        </form>
    </dialog>
    <x-table :records="$records" :showDelete="false" :showCheckbox="false" :showSortableHeaders="false" />
    <dialog class="editRecordDialog">
        <form class="editRecordform" method="POST">
            @csrf
            @method('PUT')
            <label for="farmerName">Farmer Name:</label>
            <input type="text" id="farmerName" name="farmerName">
            <label for="province">Province:</label>
            <select name="province" id="editProvince" required>
                <option value="">Select Province</option>
                <option value="Aurora">Aurora</option>
                <option value="Nueva Ecija">Nueva Ecija</option>
            </select>
            <label for="municipality">Municipality:</label>
            <select name="municipality" id="editMunicipality" required disabled>
                <option value="">Select Municipality</option>
            </select>
            <label for="barangay">Barangay:</label>
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
            <button type="button" class="closeEditRecordDialog">Close</button>
        </form>
    </dialog>
    @endif
    </div>
@endsection