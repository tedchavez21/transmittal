@extends('layout.layout')

@section('title', 'Officer of the day')

@push('styles')
<style>
.auto-caps {
    text-transform: capitalize;
}
</style>
@endpush

@section('page-styles')
@endsection

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-pcic-50 via-white to-pcic-50">
        {{-- Top Header Bar --}}
        <div class="odHeader sticky top-0 z-20 w-full bg-white/90 backdrop-blur-md border-b border-gray-200/60">
            <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <a href="{{ route('welcome') }}" class="inline-flex items-center gap-1.5 h-8 px-3 rounded-lg border border-gray-200 bg-white text-xs font-bold text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition-colors">← Back</a>
                    <div class="flex flex-col">
                        <h3 class="text-base font-black text-gray-900">Officer of the Day</h3>
                        <p class="text-xs text-gray-500 font-semibold">NL Entry Module</p>
                    </div>
                </div>
                @if($officerName)
                <div class="flex items-center gap-3">
                    <div class="flex flex-col items-end">
                        <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">OD</div>
                        <div class="text-xs font-black text-gray-900">{{ $officerName }}</div>
                    </div>
                    <form action="{{ route('officer.logout') }}" method="POST">
                        @csrf
                        <button class="logoutButton h-8 px-3 rounded-lg border border-gray-200 bg-white text-xs font-bold text-gray-600 hover:bg-red-50 hover:border-red-200 hover:text-red-600 transition-colors cursor-pointer" type="submit">Logout</button>
                    </form>
                </div>
                @endif
            </div>
        </div>
        <div class="contentContainer">
    @if(!$officerName)
        <div class="w-full max-w-md bg-white rounded-2xl shadow-lg border border-gray-100/80 overflow-hidden">
            <div class="px-6 pt-6 pb-4 border-b border-gray-100 bg-gradient-to-b from-pcic-50/60 to-white text-center">
                <div class="w-12 h-12 rounded-xl bg-pcic-100 text-pcic-700 flex items-center justify-center text-sm font-black border border-pcic-200 mx-auto mb-3">OD</div>
                <h1 class="text-xl font-black text-gray-900">Officer of the Day</h1>
                <p class="text-sm text-gray-500 font-semibold mt-1">Select your name to continue.</p>
            </div>
            <div class="px-6 py-5">
        <form action="{{ route('officer.login') }}" method="POST" class="officerOfTheDayNames flex flex-col gap-3">
            @csrf
            <select id="officerName" name="officerName" required
                class="h-11 px-3 rounded-xl border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full bg-white">
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
            <button type="submit" class="h-10 rounded-xl bg-pcic-700 text-white text-sm font-bold hover:bg-pcic-800 transition-colors cursor-pointer">Enter</button>
        </form>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 w-full">
            <div class="no-print bg-white rounded-2xl shadow-lg border border-gray-100/80 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-b from-pcic-50/60 to-white">
                    <h3 class="text-sm font-black text-gray-900">Session</h3>
                    <p class="text-xs text-gray-500 font-semibold mt-0.5">Status and actions</p>
                </div>
                <div class="px-5 py-4 flex flex-col gap-3">
                    @if($officerApproved)
                        <div class="px-3 py-2.5 rounded-lg bg-green-50 border border-green-200 text-green-800 text-xs font-semibold">Your login is approved. You may add records.</div>
                        <button type="button" class="addRecordButton h-10 rounded-xl bg-pcic-700 text-white text-sm font-bold hover:bg-pcic-800 transition-colors cursor-pointer">Add Record</button>
                        @if($records->count() > 0)
                        <a href="{{ route('officer.export-csv') }}" class="h-10 rounded-xl bg-white border border-gray-200 text-gray-700 text-sm font-bold hover:bg-gray-50 transition-colors cursor-pointer flex items-center justify-center gap-2">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            Export CSV
                        </a>
                        @endif
                    @else
                        <div class="px-3 py-2.5 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 text-xs font-semibold">Your login is pending admin approval. You cannot add records until it is approved.</div>
                    @endif

                    @if($officerApproved && $records->count() > 0)
                            <form id="submitTransmittalForm" action="{{ route('records.submit-transmittal') }}" method="POST" style="display: inline;">
                                @csrf
                                <input type="hidden" name="source" value="OD">
                                <input type="hidden" name="custom_transmittal_suffix" id="customTransmittalSuffix">
                                <button type="button" id="submitTransmittalBtn" class="h-10 rounded-xl bg-harvest-500 text-white text-sm font-bold hover:bg-harvest-600 transition-colors cursor-pointer w-full">Submit Transmittal</button>
                            </form>
                    @endif
                </div>
            </div>

            <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg border border-gray-100/80 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-b from-pcic-50/60 to-white">
                    <h3 class="text-sm font-black text-gray-900">Records</h3>
                    <p class="text-xs text-gray-500 font-semibold mt-0.5">Latest encoded NLs</p>
                </div>
                <div class="p-4 overflow-x-auto">
                    <x-table :records="$records" :showDelete="false" :showCheckbox="false" :showSortableHeaders="false" :hideAccountsColumn="true" :hideSourceColumn="true" :hideProvinceColumn="true" :useDateEncodedAsDateReceived="true" :showFilters="false" />
                    @if(method_exists($records, 'links'))
                        <div class="pagination mt-3 flex justify-center">{{ $records->links() }}</div>
                    @endif
                </div>
            </div>
        </div>

        <dialog class="addRecordDialog rounded-2xl shadow-2xl bg-white backdrop:bg-black/40 p-0 w-[min(640px,calc(100vw-2rem))]">
            <div class="px-5 pt-5 pb-3 border-b border-gray-100">
                <h3 class="text-base font-black text-gray-900">Add Record</h3>
            </div>
            <form action="{{ route('records') }}" method="POST" class="grid grid-cols-[auto_1fr] gap-x-4 gap-y-3 px-5 py-4 items-center">
                @csrf
                <input type="hidden" name="source" value="OD">
            <label for="farmerName" class="text-xs font-bold text-gray-600 text-right">Farmer Name:</label>
            <input type="text" id="farmerName" name="farmerName" required class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full auto-caps">
            <label for="province" class="text-xs font-bold text-gray-600 text-right">Province:</label>
            <select name="province" id="province" required class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full bg-white">
                <option value="">Select Province</option>
                <option value="Aurora">Aurora</option>
                <option value="Nueva Ecija">Nueva Ecija</option>
            </select>
            <label for="municipality" class="text-xs font-bold text-gray-600 text-right">Municipality:</label>
            <select name="municipality" id="municipality" required disabled class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full bg-gray-50">
                <option value="">Select Municipality</option>
            </select>
            <label for="barangay" class="text-xs font-bold text-gray-600 text-right">Barangay:</label>
            <select name="barangay" id="barangay" required disabled class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full bg-gray-50">
                <option value="">Select Barangay</option>
            </select>
            <input type="hidden" name="address" id="addRecordAddress">
            <label for="line" class="text-xs font-bold text-gray-600 text-right">Line:</label>
            <select name="line" id="line" required class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full bg-white">
                <option value="">Select Line</option>
                <option value="rice">rice</option>
                <option value="corn">corn</option>
                <option value="high-value">High-Value Crops</option>
                <option value="clti">CLTI</option>
                <option value="livestock">Livestock</option>
                <option value="non-crop">Non-Crop</option>
                <option value="fisheries">Fisheries</option>
            </select>
            <label for="program" class="text-xs font-bold text-gray-600 text-right">Program:</label>
            <select name="program" id="program" required class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full bg-white">
                <option value="">Select Program</option>
                <option value="RSBSA">RSBSA</option>
                <option value="AGRI-SENSO">AGRI-SENSO</option>
                <option value="ACEF">ACEF</option>
                <option value="ANYO">ANYO</option>
                <option value="OTHER-LI LC">OTHER-LI LC</option>
                <option value="OTHER-LBP ACP">OTHER-LBP ACP</option>
                <option value="REGULAR">REGULAR</option>
                <option value="SELF-FINANCED">SELF-FINANCED</option>
                <option value="CFITF">CFITF</option>
                <option value="CFITF-CIP">CFITF-CIP</option>
            </select>
            <label for="date_occurrence" class="text-xs font-bold text-gray-600 text-right">Date occurrence:</label>
            <input type="text" id="date_occurrence" name="date_occurrence" class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full">
            <label for="causeOfDamage" class="text-xs font-bold text-gray-600 text-right">Cause of Damage:</label>
            <input type="text" id="causeOfDamage" name="causeOfDamage" required class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full auto-caps">
            <label for="modeOfPayment" class="text-xs font-bold text-gray-600 text-right">Mode of payment:</label>
            <select name="modeOfPayment" id="modeOfPayment" required class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full bg-white">
                <option value="">Select Mode of payment</option>
                <option value="check">Check</option>
                <option value="palawan">Palawan Pay</option>
                <option value="gcash">GCash</option>
                <option value="not_indicated">Not indicated</option>
            </select>
            <label for="remarks" class="text-xs font-bold text-gray-600 text-right">Remarks - Care of:</label>
            <input type="text" id="remarks" name="remarks" class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full auto-caps">
            <div></div>
            <div class="flex gap-2 pt-1">
                <button type="submit" class="h-9 px-4 rounded-lg bg-pcic-700 text-white text-xs font-bold hover:bg-pcic-800 transition-colors cursor-pointer">Add Record</button>
                <button type="button" class="closeAddRecordModal h-9 px-4 rounded-lg border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50 transition-colors cursor-pointer">Close</button>
            </div>
        </form>
    </dialog>

    <dialog id="transmittalDialog" class="rounded-2xl shadow-2xl bg-white backdrop:bg-black/40 p-0 w-[min(400px,calc(100vw-2rem))]">
        <div class="px-5 pt-5 pb-3 border-b border-gray-100">
            <h3 class="text-base font-black text-gray-900">Submit Transmittal</h3>
        </div>
        <div class="px-5 py-4">
            <div class="grid grid-cols-[auto_1fr] gap-x-4 gap-y-3 items-center">
                <span class="text-xs font-bold text-gray-600 text-right">Prefix:</span>
                <input type="text" id="transmittalPrefix" readonly class="h-9 px-3 rounded-lg bg-gray-100 border border-gray-200 text-sm font-bold text-gray-700 w-full">
                <span class="text-xs font-bold text-gray-600 text-right">Suffix:</span>
                <input type="number" id="transmittalSuffix" min="1" max="999" maxlength="3" class="h-9 px-3 rounded-lg border border-gray-200 focus:border-harvest-500 focus:ring-2 focus:ring-harvest-100 outline-none text-sm font-bold w-full">
            </div>
            <div class="flex gap-2 justify-end mt-4">
                <button type="button" id="cancelTransmittalBtn" class="h-9 px-4 rounded-lg border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50 transition-colors cursor-pointer">Cancel</button>
                <button type="button" id="confirmTransmittalBtn" class="h-9 px-4 rounded-lg bg-harvest-500 text-white text-xs font-bold hover:bg-harvest-600 transition-colors cursor-pointer">Submit</button>
            </div>
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
                prefixInput.value = `${year}-${month}${day}`;
                
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
    <dialog class="editRecordDialog rounded-2xl shadow-2xl bg-white backdrop:bg-black/40 p-0 w-[min(640px,calc(100vw-2rem))]" id="recordEditDialog">
        <div class="px-5 pt-5 pb-3 border-b border-gray-100">
            <h3 class="text-base font-black text-gray-900">Edit Record</h3>
        </div>
        <form class="editRecordform grid grid-cols-[auto_1fr] gap-x-4 gap-y-3 px-5 py-4 items-center" id="recordEditForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="source" value="OD" id="editRecordSourceOd">
            <label for="farmerName" class="text-xs font-bold text-gray-600 text-right">Farmer Name:</label>
            <input type="text" id="farmerName" name="farmerName" class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full">
            <label for="province" class="text-xs font-bold text-gray-600 text-right">Province:</label>
            <select name="province" id="editProvince" required class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full bg-white">
                <option value="">Select Province</option>
                <option value="Aurora">Aurora</option>
                <option value="Nueva Ecija">Nueva Ecija</option>
            </select>
            <label for="municipality" class="text-xs font-bold text-gray-600 text-right">Municipality:</label>
            <select name="municipality" id="editMunicipality" required disabled class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full bg-gray-50">
                <option value="">Select Municipality</option>
            </select>
            <label for="barangay" class="text-xs font-bold text-gray-600 text-right">Barangay:</label>
            <select name="barangay" id="editBarangay" required disabled class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full bg-gray-50">
                <option value="">Select Barangay</option>
            </select>
            <input type="hidden" name="address" id="editRecordAddress">
            <label for="line" class="text-xs font-bold text-gray-600 text-right">Line:</label>
            <select name="line" id="line" class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full bg-white">
                <option value="">Select Line</option>
                <option value="rice">rice</option>
                <option value="corn">corn</option>
                <option value="high-value">High-Value Crops</option>
                <option value="clti">CLTI</option>
                <option value="livestock">Livestock</option>
                <option value="non-crop">Non-Crop</option>
                <option value="fisheries">Fisheries</option>
            </select>
            <label for="program" class="text-xs font-bold text-gray-600 text-right">Program:</label>
            <select name="program" id="program" class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full bg-white">
                <option value="">Select Program</option>
                <option value="RSBSA">RSBSA</option>
                <option value="AGRI-SENSO">AGRI-SENSO</option>
                <option value="ACEF">ACEF</option>
                <option value="ANYO">ANYO</option>
                <option value="OTHER-LI LC">OTHER-LI LC</option>
                <option value="OTHER-LBP ACP">OTHER-LBP ACP</option>
                <option value="REGULAR">REGULAR</option>
                <option value="SELF-FINANCED">SELF-FINANCED</option>
                <option value="CFITF">CFITF</option>
            </select>
            <label for="date_occurrence" class="text-xs font-bold text-gray-600 text-right">Date occurrence:</label>
            <input type="text" id="date_occurrence" name="date_occurrence" class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full">
            <label for="causeOfDamage" class="text-xs font-bold text-gray-600 text-right">Cause of Damage:</label>
            <input type="text" id="causeOfDamage" name="causeOfDamage" class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full">
            <label for="modeOfPayment" class="text-xs font-bold text-gray-600 text-right">Mode of payment:</label>
            <select name="modeOfPayment" id="modeOfPayment" class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full bg-white">
                <option value="">Select Mode of payment</option>
                <option value="check">Check</option>
                <option value="palawan">Palawan Pay</option>
                <option value="gcash">GCash</option>
                <option value="not_indicated">Not indicated</option>
            </select>
            <label for="accounts" class="text-xs font-bold text-gray-600 text-right">Account (if recorded):</label>
            <input type="text" id="accounts" name="accounts" placeholder="Optional" class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full">
            <input type="hidden" name="facebook_page_url" value="">
            <label for="remarks" class="text-xs font-bold text-gray-600 text-right">Remarks - Care of:</label>
            <input type="text" id="remarks" name="remarks" class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full">
            <label for="transmittal_number" class="text-xs font-bold text-gray-600 text-right">Control number:</label>
            <input type="text" id="transmittal_number" name="transmittal_number" readonly class="h-9 px-3 rounded-lg border border-gray-200 bg-gray-50 text-sm w-full">
            <label for="admin_transmittal_number" class="text-xs font-bold text-gray-600 text-right">Admin transmittal #:</label>
            <input type="text" id="admin_transmittal_number" name="admin_transmittal_number" readonly class="h-9 px-3 rounded-lg border border-gray-200 bg-gray-50 text-sm w-full">
            <div></div>
            <div class="flex gap-2 pt-1">
                <button type="submit" class="h-9 px-4 rounded-lg bg-pcic-700 text-white text-xs font-bold hover:bg-pcic-800 transition-colors cursor-pointer">Update Record</button>
                <button type="button" class="closeEditRecordDialog h-9 px-4 rounded-lg border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50 transition-colors cursor-pointer">Close</button>
            </div>
        </form>
    </dialog>
    @endif
        </div>
    </div>
@endsection