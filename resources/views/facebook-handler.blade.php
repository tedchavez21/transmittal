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
                <option value="not_indicated">Not indicated</option>
            </select>
            <label for="accounts">Account (sender email/username):</label>
            <input type="text" id="accounts" name="accounts" placeholder="Paste FB profile link or type name">
            <p style="margin: 4px 0 10px 0; font-size: 12px; color: #666;">
                Tip: paste a Facebook profile link to auto-fill the name (best-effort).
            </p>
            <label for="date_occurrence">Date occurrence:</label>
            <input type="date" id="date_occurrence" name="date_occurrence">
            <label for="remarks">Remarks - Care of:</label>
            <input type="text" id="remarks" name="remarks">
            <button type="submit">Add Record</button>
            <button type="button" class="closeAddRecordModal">Close</button>
        </form>
    </dialog>
    <x-table :records="$records" :showDelete="false" :showCheckbox="false" :showSortableHeaders="false" />
    @if($records->count() > 0)
    <div style="margin-top: 20px;">
        <form action="{{ route('records.submit-transmittal') }}" method="POST" style="display: inline;">
            @csrf
            <input type="hidden" name="source" value="Facebook">
            <button type="submit" style="padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Submit Transmittal</button>
        </form>
    </div>
    @endif
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
                <option value="not_indicated">Not indicated</option>
            </select>
            <label for="accounts">Account (sender email/username):</label>
            <input type="text" id="accounts" name="accounts">
            <label for="date_occurrence">Date occurrence:</label>
            <input type="date" id="date_occurrence" name="date_occurrence">
            <label for="remarks">Remarks - Care of:</label>
            <input type="text" id="remarks" name="remarks">
            <button type="submit">Update Record</button>
            <button type="button" class="closeEditRecordDialog">Close</button>
        </form>
    </dialog>
    @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || "{{ csrf_token() }}";
            const inputs = document.querySelectorAll('input[name="accounts"]');

            function extractFacebookSlug(url) {
                try {
                    const u = new URL(url);
                    const host = u.hostname.replace(/^www\./, '');
                    if (!host.includes('facebook.com')) return null;

                    const idParam = u.searchParams.get('id');
                    if (idParam) return idParam;

                    const parts = u.pathname.split('/').filter(Boolean);
                    if (parts.length === 0) return null;
                    if (parts[0] === 'profile.php') return idParam;
                    return parts[0];
                } catch {
                    return null;
                }
            }

            async function resolveNameFromUrl(url) {
                const response = await fetch("{{ route('facebook.resolve-name') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ url })
                });

                if (!response.ok) {
                    return null;
                }

                const data = await response.json();
                return data?.name || null;
            }

            inputs.forEach(input => {
                input.addEventListener('blur', async function () {
                    const raw = (this.value || '').trim();
                    if (!raw) return;
                    if (!raw.includes('facebook.com')) return;

                    this.disabled = true;
                    const original = raw;

                    try {
                        const resolved = await resolveNameFromUrl(original);
                        if (resolved) {
                            this.value = resolved;
                            return;
                        }

                        const slug = extractFacebookSlug(original);
                        if (slug) {
                            this.value = slug;
                        }
                    } finally {
                        this.disabled = false;
                    }
                });
            });
        });
    </script>
@endsection