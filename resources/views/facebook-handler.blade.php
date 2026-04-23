@extends('layout.layout')

@section('title', 'Facebook')

@section('page-styles')
<style>
    html, body {
        overflow-x: hidden;
    }
</style>
@endsection

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
        <div class="app-card" style="max-width: 560px;">
            <div class="app-card-header" style="padding: 18px 18px 14px 18px;">
                <div>
                    <h1 class="app-card-title" style="font-size: 18px;">Facebook entry</h1>
                    <p class="app-card-subtitle" style="margin-top: 6px;">Enter the access password to continue.</p>
                </div>
            </div>
            <div class="app-card-body" style="padding: 16px 18px 18px 18px;">
        <form action="{{ route('facebook.login') }}" method="POST" class="officerOfTheDayNames">
            @csrf
            <input type="password" id="password" name="password" required placeholder="Password" aria-label="Password">
            <button type="submit">Enter</button>
        </form>
            </div>
        </div>
        @if(session('error'))
            <div class="app-alert app-alert--error" style="max-width: 560px;">{{ session('error') }}</div>
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
                <option value="not_indicated">Not indicated</option>
            </select>
            <label for="accounts">Account / page name (sender):</label>
            <input type="text" id="accounts" name="accounts" required placeholder="Name of Facebook page or account">
            <label for="facebook_page_url">Facebook page link:</label>
            <input type="url" id="facebook_page_url" name="facebook_page_url" required placeholder="https://www.facebook.com/...">
            <label for="date_occurrence">Date occurrence:</label>
            <input type="text" id="date_occurrence" name="date_occurrence">
            <label for="date_received">Date received:</label>
            <input type="text" id="date_received" name="date_received">
            <label for="remarks">Remarks - Care of:</label>
            <input type="text" id="remarks" name="remarks">
            <button type="submit">Add Record</button>
            <button type="button" class="closeAddRecordModal">Close</button>
        </form>
    </dialog>
    <x-table :records="$records" :showDelete="false" :showCheckbox="false" :showSortableHeaders="false" :hideSourceColumn="true" :hideProvinceColumn="true" />
    <dialog class="editRecordDialog" id="recordEditDialog">
        <form class="editRecordform" id="recordEditForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="source" value="Facebook" id="editRecordSourceFacebook">
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
                <option value="not_indicated">Not indicated</option>
            </select>
            <label for="accounts">Account / page name (sender):</label>
            <input type="text" id="accounts" name="accounts">
            <label for="facebook_page_url">Facebook page link:</label>
            <input type="url" id="facebook_page_url" name="facebook_page_url" placeholder="https://www.facebook.com/...">
            <label for="date_occurrence">Date occurrence:</label>
            <input type="text" id="date_occurrence" name="date_occurrence">
            <label for="remarks">Remarks - Care of:</label>
            <input type="text" id="remarks" name="remarks">
            <label for="transmittal_number">Control number:</label>
            <input type="text" id="transmittal_number" name="transmittal_number" readonly style="background:#f5f5f5;">
            <label for="admin_transmittal_number">Admin transmittal # (read-only):</label>
            <input type="text" id="admin_transmittal_number" name="admin_transmittal_number" readonly style="background:#f5f5f5;">
            <button type="submit">Update Record</button>
            <button type="button" class="closeEditRecordDialog">Close</button>
        </form>
    </dialog>
    @endif
    </div>

@endsection