@extends('layout.layout')

@section('title', 'Officer of the day')

@section('page-styles')
@endsection

@section('content')
    <div class="odHeader">
        <section>
            <h3>Officer of the Day</h3>
            <p>NL Entry Module</p>
        </section>
        @if($officerName)
        <section>
            <p>OD: <strong>{{ $officerName }}</strong></p>
            <form action="{{ route('officer.logout') }}" method="POST">
                @csrf
                <button class="logoutButton" type="submit">Logout</button>
            </form>
        </section>
        @endif
    </div>
    <div class="contentContainer">
    @if(!$officerName)
        <div class="app-card" style="max-width: 560px;">
            <div class="app-card-header" style="padding: 18px 18px 14px 18px;">
                <div>
                    <h1 class="app-card-title" style="font-size: 18px;">Officer of the Day</h1>
                    <p class="app-card-subtitle" style="margin-top: 6px;">Select your name to continue.</p>
                </div>
            </div>
            <div class="app-card-body" style="padding: 16px 18px 18px 18px;">
        <form action="{{ route('officer.login') }}" method="POST" class="officerOfTheDayNames">
            @csrf
            <select id="officerName" name="officerName" required>
                <option value="">Select Officer of the day</option>
                <option value="Gemmary Eiden Chavez">Gemmary Eiden Chavez</option>
                <option value="John Vincent Chico">John Vincent Chico</option>
                <option value="John Daryl Cruz">John Daryl Cruz</option>
                <option value="Shaila Jade Santos">Shaila Jade Santos</option>
                <option value="Lorena Jane Policarpio">Lorena Jane Policarpio</option>
                <option value="Bernadette Santiago">Bernadette Santiago</option>
                <option value="Carol Lumibao">Carol Lumibao</option>
                <option value="Romellyn Pornuevo">Romellyn Pornuevo</option>
                <option value="Glen Bondoc">Glen Bondoc</option>
                <option value="John Patrick Aceron">John Patrick Aceron</option>
                <option value="Ted Eiden Chavez">Ted Eiden Chavez</option>
                <option value="Uzziel Martinez">Uzziel Martinez</option>
                <option value="Hanna Marie Lorica">Hanna Marie Lorica</option>
                <option value="Jessica Rose Flores">Jessica Rose Flores</option>
                <option value="Jenica Atchico">Jenica Atchico</option>
                <option value="Julie Ann Espejo">Julie Ann Espejo</option>
                <option value="Ian Marvic Lumibao">Ian Marvic Lumibao</option>
                <option value="Nelson Alvaro">Nelson Alvaro</option>
                <option value="Jia Joanna Paler">Jia Joanna Paler</option>
                <option value="Jammie Padilla">Jammie Padilla</option>
                <option value="Nicole Ann Carlos">Nicole Ann Carlos</option>
                <option value="Myleen Concepcion">Myleen Concepcion</option>
                <option value="Raven Guingon">Raven Guingon</option>
                <option value="Melody Returban">Melody Returban</option>
                <option value="Clarissa Centeno">Clarissa Centeno</option>
            </select>
            <button type="submit">Enter</button>
        </form>
            </div>
        </div>
    @else
        <br/>
        @if($officerApproved)
            <div class="app-alert app-alert--success" style="max-width: 980px;">Your login is approved. You may add records.</div>
            <button class="addRecordButton">Add Record</button>
        @else
            <div class="app-alert app-alert--warning" style="max-width: 980px;">Your login is pending admin approval. You cannot add records until it is approved.</div>
        @endif
        
        <dialog class="addRecordDialog">
            <h3>Add Record</h3>
            <form action="{{ route('records') }}" method="POST">
                @csrf
                <input type="hidden" name="source" value="OD">
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
                <option value="CFITF-CIP">CFITF-CIP</option>
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
            <label for="date_occurrence">Date occurrence:</label>
            <input type="text" id="date_occurrence" name="date_occurrence">
            <label for="remarks">Remarks - Care of:</label>
            <input type="text" id="remarks" name="remarks">
            <button type="submit">Add Record</button>
            <button type="button" class="closeAddRecordModal">Close</button>
        </form>
    </dialog>
    <x-table :records="$records" :showDelete="false" :showCheckbox="false" :showSortableHeaders="false" :hideAccountsColumn="true" :hideSourceColumn="true" :hideProvinceColumn="true" :showFilters="true" />
    @if($officerApproved && $records->count() > 0)
    <div style="margin-top: 20px;">
        <form id="submitTransmittalForm" action="{{ route('records.submit-transmittal') }}" method="POST" style="display: inline;">
            @csrf
            <input type="hidden" name="source" value="OD">
            <input type="hidden" name="custom_transmittal_suffix" id="customTransmittalSuffix">
            <button type="button" id="submitTransmittalBtn" style="padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Submit Transmittal</button>
        </form>
    </div>
    @endif

    <dialog id="transmittalDialog">
        <h3>Submit Transmittal</h3>
        <p style="margin-bottom: 16px;">Enter transmittal number suffix:</p>
        <div style="display: flex; gap: 8px; align-items: center; margin-bottom: 20px;">
            <input type="text" id="transmittalPrefix" readonly style="width: 120px; padding: 8px; background: #f0f0f0; border: 1px solid #ccc; text-align: center; font-weight: bold;">
            <span style="font-size: 18px;">-</span>
            <input type="number" id="transmittalSuffix" min="1" max="999" maxlength="3" style="width: 80px; padding: 8px; border: 1px solid #ccc; text-align: center; font-weight: bold;">
        </div>
        <div style="display: flex; gap: 10px; justify-content: flex-end;">
            <button type="button" id="cancelTransmittalBtn" style="padding: 8px 16px;">Cancel</button>
            <button type="button" id="confirmTransmittalBtn" style="padding: 8px 16px; background-color: #4CAF50; color: white; border: none; border-radius: 4px;">Submit</button>
        </div>
    </dialog>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const submitBtn = document.getElementById('submitTransmittalBtn');
            const dialog = document.getElementById('transmittalDialog');
            const prefixInput = document.getElementById('transmittalPrefix');
            const suffixInput = document.getElementById('transmittalSuffix');
            const cancelBtn = document.getElementById('cancelTransmittalBtn');
            const confirmBtn = document.getElementById('confirmTransmittalBtn');
            const form = document.getElementById('submitTransmittalForm');
            const suffixHidden = document.getElementById('customTransmittalSuffix');

            submitBtn.addEventListener('click', function() {
                const today = new Date();
                const year = today.getFullYear();
                const month = String(today.getMonth() + 1).padStart(2, '0');
                const day = String(today.getDate()).padStart(2, '0');
                prefixInput.value = `${year}-${month}-${day}`;
                
                suffixInput.value = '';
                suffixInput.focus();
                dialog.showModal();
            });

            cancelBtn.addEventListener('click', function() {
                dialog.close();
            });

            confirmBtn.addEventListener('click', function() {
                const suffix = suffixInput.value.trim();
                
                if (!suffix || suffix.length < 1 || suffix.length > 3 || isNaN(suffix)) {
                    alert('Please enter a valid number between 1 and 999');
                    suffixInput.focus();
                    return;
                }

                suffixHidden.value = suffix.padStart(3, '0');
                dialog.close();
                form.submit();
            });

            suffixInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    confirmBtn.click();
                }
            });
        });
    </script>
    <dialog class="editRecordDialog" id="recordEditDialog">
        <form class="editRecordform" id="recordEditForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="source" value="OD" id="editRecordSourceOd">
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
            <label for="accounts">Account (if recorded):</label>
            <input type="text" id="accounts" name="accounts" placeholder="Optional">
            <input type="hidden" name="facebook_page_url" value="">
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