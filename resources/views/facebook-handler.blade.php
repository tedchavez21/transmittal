@extends('layout.layout')

@section('title', 'Facebook')

@push('styles')
<style>
</style>
@endpush

@section('page-styles')
<style>
    html, body {
        overflow-x: hidden;
    }
</style>
@endsection

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-pcic-100 via-white to-pcic-100">
        {{-- Top Header Bar --}}
        <div class="odHeader sticky top-0 z-20 w-full bg-white/90 backdrop-blur-md border-b border-gray-200/60">
            <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="flex flex-col">
                        <h3 class="text-base font-black text-gray-900">Facebook</h3>
                        <p class="text-xs text-gray-500 font-semibold">NL Entry Module</p>
                    </div>
                </div>
                @if($isLoggedIn)
                <div class="flex items-center gap-3">
                    <div class="flex flex-col items-end">
                        <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">FB</div>
                        <div class="text-xs font-black text-gray-900">{{ $facebookUserName }}</div>
                    </div>
                    <form action="{{ route('facebook.logout') }}" method="POST">
                        @csrf
                        <button class="logoutButton h-8 px-3 rounded-lg border border-gray-200 bg-white text-xs font-bold text-gray-600 hover:bg-red-50 hover:border-red-200 hover:text-red-600 transition-colors cursor-pointer" type="submit">Logout</button>
                    </form>
                </div>
                @endif
            </div>
        </div>
        <div class="contentContainer">
    @if(!$isLoggedIn)
        <div class="w-full max-w-md bg-white rounded-2xl shadow-lg border border-gray-100/80 overflow-hidden">
            <div class="px-6 pt-6 pb-4 border-b border-gray-100 bg-gradient-to-b from-pcic-50/60 to-white text-center">
                <div class="w-12 h-12 rounded-xl bg-pcic-50 text-pcic-600 flex items-center justify-center text-sm font-black border border-pcic-100 mx-auto mb-3">FB</div>
                <h1 class="text-xl font-black text-gray-900">Facebook Login</h1>
                <p class="text-sm text-gray-500 font-semibold mt-1">Enter your credentials to continue.</p>
            </div>
            <div class="px-6 py-5">
        <form action="{{ route('facebook.login') }}" method="POST" class="officerOfTheDayNames flex flex-col gap-3" id="facebookLoginForm">
            @csrf
            <select name="facebook_user" id="facebook_user" required class="h-11 px-3 rounded-xl border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full bg-white">
                <option value="">Select user</option>
                @php
                    $officers = \App\Models\Officer::orderBy('name')->get();
                @endphp
                @foreach($officers as $officer)
                    <option value="{{ $officer->username ?? $officer->name }}">{{ $officer->name }}</option>
                @endforeach
            </select>
            <input type="password" name="facebook_password" id="facebook_password" placeholder="Password" required class="h-11 px-3 rounded-xl border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full">
            <button type="submit" class="h-10 rounded-xl bg-pcic-700 text-white text-sm font-bold hover:bg-pcic-800 transition-colors cursor-pointer">Login</button>
        </form>
            </div>
        </div>
        @else
        @if(session('error'))
            <div class="mb-4 mx-auto max-w-2xl">
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            </div>
        @endif
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 w-full">
            <div class="no-print bg-white rounded-2xl shadow-lg border border-gray-100/80 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-b from-pcic-50/60 to-white">
                    <h3 class="text-sm font-black text-gray-900">Session</h3>
                    <p class="text-xs text-gray-500 font-semibold mt-0.5">Actions</p>
                </div>
                <div class="px-5 py-4 flex flex-col gap-3">
                    <div class="filter-container">
                        <form action="{{ route('facebook-handler') }}" method="GET">
                            <div class="date-received-container border border-gray-200 bg-gray-50 rounded-lg p-3">
                                <div class="flex flex-col gap-2">
                                    <label class="text-xs font-bold text-gray-700 mb-1">Date Received</label>
                                    <div class="flex items-center gap-2">
                                        <input type="date" name="date_received" value="{{ request('date_received', now()->format('Y-m-d')) }}" class="h-10 px-3 rounded-lg border border-gray-300 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 bg-white outline-none text-sm shadow-sm w-full">
                                    </div>
                                </div>
                            </div>
                            <div class="filter-actions-container border border-gray-200 rounded-lg p-3 bg-gray-50">
                                <div class="flex gap-2">
                                    <button type="submit" class="h-10 px-4 rounded-lg bg-green-600 text-white text-xs font-semibold hover:bg-green-700 transition-colors cursor-pointer shadow-sm flex items-center justify-center">Filter Date</button>
                                    <a href="{{ route('facebook-handler') }}" class="h-10 px-4 rounded-lg bg-white text-gray-700 text-xs font-semibold hover:bg-gray-50 transition-colors cursor-pointer shadow-sm flex items-center justify-center">Clear Filters</a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <button type="button" class="addRecordButton h-10 rounded-xl bg-pcic-700 text-white text-sm font-bold hover:bg-pcic-800 transition-colors cursor-pointer">Add Record</button>
                    @if($records->count() > 0)
                    <a href="{{ route('facebook.export-csv') }}" class="h-10 rounded-xl bg-white border border-gray-200 text-gray-700 text-sm font-bold hover:bg-gray-50 transition-colors cursor-pointer flex items-center justify-center gap-2">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Export CSV
                    </a>
                    @endif
                </div>
            </div>

            <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg border border-gray-100/80 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-b from-pcic-50/60 to-white">
                    <h3 class="text-sm font-black text-gray-900">Records</h3>
                    <p class="text-xs text-gray-500 font-semibold mt-0.5">Latest encoded NLs</p>
                </div>
                <div class="p-4 overflow-x-auto">
                    <x-table :records="$records" :showDelete="false" :showCheckbox="false" :showSortableHeaders="false" :hideSourceColumn="true" :hideProvinceColumn="true" />
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
                <input type="hidden" name="source" value="Facebook">
            <label for="farmerName" class="text-xs font-bold text-gray-600 text-right">Farmer Name:</label>
            <input type="text" id="farmerName" name="farmerName" required class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full">
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
            </select>
            <label for="date_occurrence" class="text-xs font-bold text-gray-600 text-right">Date occurrence:</label>
            <input type="text" id="date_occurrence" name="date_occurrence" class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full">
            <label for="date_received" class="text-xs font-bold text-gray-600 text-right">Date received:</label>
            <input type="date" id="date_received" name="date_received" value="{{ now()->format('Y-m-d') }}" class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full">
            <label for="causeOfDamage" class="text-xs font-bold text-gray-600 text-right">Cause of Damage:</label>
            <input type="text" id="causeOfDamage" name="causeOfDamage" required class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full">
            <label for="modeOfPayment" class="text-xs font-bold text-gray-600 text-right">Mode of payment:</label>
            <select name="modeOfPayment" id="modeOfPayment" required class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full bg-white">
                <option value="">Select Mode of payment</option>
                <option value="check">Check</option>
                <option value="palawan">Palawan Pay</option>
                <option value="gcash">GCash</option>
                <option value="not_indicated">Not indicated</option>
            </select>
            <label for="remarks" class="text-xs font-bold text-gray-600 text-right">Remarks - Care of:</label>
            <input type="text" id="remarks" name="remarks" class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full ">
            <label for="accounts" class="text-xs font-bold text-gray-600 text-right">Account / page:</label>
            <input type="text" id="accounts" name="accounts" required placeholder="Name of Facebook page or account" class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full">
            <label for="facebook_page_url" class="text-xs font-bold text-gray-600 text-right">FB page link:</label>
            <input type="url" id="facebook_page_url" name="facebook_page_url" placeholder="https://www.facebook.com/..." class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full">
            <div></div>
            <div class="flex gap-2 pt-1">
                <button type="submit" class="h-9 px-4 rounded-lg bg-pcic-700 text-white text-xs font-bold hover:bg-pcic-800 transition-colors cursor-pointer">Add Record</button>
                <button type="button" class="closeAddRecordModal h-9 px-4 rounded-lg border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50 transition-colors cursor-pointer">Close</button>
            </div>
        </form>
    </dialog>
    <dialog class="editRecordDialog rounded-2xl shadow-2xl bg-white backdrop:bg-black/40 p-0 w-[min(640px,calc(100vw-2rem))]" id="recordEditDialog">
        <div class="px-5 pt-5 pb-3 border-b border-gray-100">
            <h3 class="text-base font-black text-gray-900">Edit Record</h3>
        </div>
        <form class="editRecordform grid grid-cols-[auto_1fr] gap-x-4 gap-y-3 px-5 py-4 items-center" id="recordEditForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="source" value="Facebook" id="editRecordSourceFacebook">
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
            <label for="date_received" class="text-xs font-bold text-gray-600 text-right">Date received:</label>
            <input type="date" id="date_received" name="date_received" class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full">
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
            <label for="accounts" class="text-xs font-bold text-gray-600 text-right">Account / page:</label>
            <input type="text" id="accounts" name="accounts" class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full">
            <label for="facebook_page_url" class="text-xs font-bold text-gray-600 text-right">FB page link:</label>
            <input type="url" id="facebook_page_url" name="facebook_page_url" placeholder="https://www.facebook.com/..." class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full">
            <label for="remarks" class="text-xs font-bold text-gray-600 text-right">Remarks - Care of:</label>
            <input type="text" id="remarks" name="remarks" class="h-9 px-3 rounded-lg border border-gray-200 focus:border-pcic-500 focus:ring-2 focus:ring-pcic-100 outline-none text-sm w-full ">
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

@push('scripts')
<script>
// Automatic logout on browser/tab close
window.addEventListener('beforeunload', function(e) {
    // Send logout request using navigator.sendBeacon for reliable delivery
    navigator.sendBeacon('{{ route('facebook.logout') }}', new FormData());
});

// Also handle page visibility change (user switches tabs)
document.addEventListener('visibilitychange', function() {
    if (document.visibilityState === 'hidden') {
        // User switched away from the tab, mark as away after a delay
        setTimeout(function() {
            if (document.visibilityState === 'hidden') {
                navigator.sendBeacon('{{ route('facebook.logout') }}', new FormData());
            }
        }, 30000); // 30 seconds delay
    }
});

// Add Record Modal
document.addEventListener('DOMContentLoaded', function() {
    var addRecordButton = document.querySelector('.addRecordButton');
    var addRecordDialog = document.querySelector('.addRecordDialog');
    var closeAddRecordModal = document.querySelector('.closeAddRecordModal');

    if (addRecordButton && addRecordDialog) {
        addRecordButton.addEventListener('click', function() {
            addRecordDialog.showModal();
        });
    }

    if (closeAddRecordModal && addRecordDialog) {
        closeAddRecordModal.addEventListener('click', function() {
            addRecordDialog.close();
        });
    }

    // Edit Record Modal - Use event delegation to handle dynamically loaded buttons
    var editRecordDialog = document.getElementById('recordEditDialog');
    var closeEditRecordModal = document.querySelector('.closeEditRecordDialog');
    var editRecordForm = document.getElementById('recordEditForm');

    // Use event delegation on the document to handle edit button clicks
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('editButton') || e.target.closest('.editButton')) {
            var button = e.target.classList.contains('editButton') ? e.target : e.target.closest('.editButton');
            
            if (!editRecordDialog || !editRecordForm) {
                console.error('Edit dialog or form not found');
                return;
            }

            try {
                var recordId = button.getAttribute('data-id');
                var farmerName = button.getAttribute('data-farmer-name');
                var province = button.getAttribute('data-province');
                var municipality = button.getAttribute('data-municipality');
                var barangay = button.getAttribute('data-barangay');
                var address = button.getAttribute('data-address');
                var program = button.getAttribute('data-program');
                var line = button.getAttribute('data-line');
                var causeOfDamage = button.getAttribute('data-cause-of-damage');
                var modeOfPayment = button.getAttribute('data-mode-of-payment');
                var accounts = button.getAttribute('data-accounts');
                var fbPageUrl = button.getAttribute('data-fb-page-url');
                var dateOccurrence = button.getAttribute('data-date-occurrence');
                var dateReceived = button.getAttribute('data-date-received');
                var remarks = button.getAttribute('data-remarks');
                var source = button.getAttribute('data-source');
                var transmittalNumber = button.getAttribute('data-transmittal-number');
                var adminTransmittalNumber = button.getAttribute('data-admin-transmittal-number');

                // Populate form fields
                var farmerNameField = editRecordForm.querySelector('#farmerName');
                var editProvinceField = editRecordForm.querySelector('#editProvince');
                var editMunicipalityField = editRecordForm.querySelector('#editMunicipality');
                var editBarangayField = editRecordForm.querySelector('#editBarangay');
                var addressField = editRecordForm.querySelector('#editRecordAddress');
                var programField = editRecordForm.querySelector('#program');
                var lineField = editRecordForm.querySelector('#line');
                var causeOfDamageField = editRecordForm.querySelector('#causeOfDamage');
                var modeOfPaymentField = editRecordForm.querySelector('#modeOfPayment');
                var accountsField = editRecordForm.querySelector('#accounts');
                var fbPageUrlField = editRecordForm.querySelector('#facebook_page_url');
                var dateOccurrenceField = editRecordForm.querySelector('#date_occurrence');
                var dateReceivedField = editRecordForm.querySelector('#date_received');
                var remarksField = editRecordForm.querySelector('#remarks');
                var transmittalNumberField = editRecordForm.querySelector('#transmittal_number');
                var adminTransmittalNumberField = editRecordForm.querySelector('#admin_transmittal_number');

                if (farmerNameField) farmerNameField.value = farmerName || '';
                if (editProvinceField) editProvinceField.value = province || '';
                if (editMunicipalityField) editMunicipalityField.value = municipality || '';
                if (editBarangayField) editBarangayField.value = barangay || '';
                if (addressField) addressField.value = address || '';
                if (programField) programField.value = program || '';
                if (lineField) lineField.value = line || '';
                if (causeOfDamageField) causeOfDamageField.value = causeOfDamage || '';
                if (modeOfPaymentField) modeOfPaymentField.value = modeOfPayment || '';
                if (accountsField) accountsField.value = accounts || '';
                if (fbPageUrlField) fbPageUrlField.value = fbPageUrl || '';
                if (dateOccurrenceField) dateOccurrenceField.value = dateOccurrence || '';
                if (dateReceivedField) dateReceivedField.value = dateReceived || '';
                if (remarksField) remarksField.value = remarks || '';
                if (transmittalNumberField) transmittalNumberField.value = transmittalNumber || '';
                if (adminTransmittalNumberField) adminTransmittalNumberField.value = adminTransmittalNumber || '';

                // Set form action
                editRecordForm.action = '/records/' + recordId;
                console.log('Setting form action to:', editRecordForm.action);
                
                // Check if CSRF token is available
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                console.log('CSRF token found:', !!csrfToken);
                if (!csrfToken) {
                    console.error('CSRF token not found');
                    return;
                }

                // Enable municipality and barangay selects based on province
                if (editProvinceField && editMunicipalityField && editBarangayField) {
                    if (editProvinceField.value) {
                        editMunicipalityField.disabled = false;
                        // Trigger municipality update
                        var event = new Event('change');
                        editProvinceField.dispatchEvent(event);
                        
                        if (editMunicipalityField.value) {
                            editBarangayField.disabled = false;
                            // Trigger barangay update
                            var municipalityEvent = new Event('change');
                            editMunicipalityField.dispatchEvent(municipalityEvent);
                        }
                    }
                }

                editRecordDialog.showModal();
            } catch (error) {
                console.error('Error opening edit dialog:', error);
            }
        }
    });

    if (closeEditRecordModal && editRecordDialog) {
        closeEditRecordModal.addEventListener('click', function() {
            editRecordDialog.close();
        });
    }

    // Handle form submission for edit record
    if (editRecordForm) {
        editRecordForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Update hidden address field before submission
            var editProvinceField = editRecordForm.querySelector('#editProvince');
            var editMunicipalityField = editRecordForm.querySelector('#editMunicipality');
            var editBarangayField = editRecordForm.querySelector('#editBarangay');
            var addressField = editRecordForm.querySelector('#editRecordAddress');
            if (editProvinceField && editMunicipalityField && editBarangayField && addressField) {
                addressField.value = [editBarangayField.value, editMunicipalityField.value, editProvinceField.value]
                    .filter(Boolean)
                    .join(', ');
            }

            console.log('Edit form submit triggered');

            var formData = new FormData(editRecordForm);
            // Ensure _method parameter is included for PUT request
            if (!formData.has('_method')) {
                formData.append('_method', 'PUT');
            }
            var formAction = editRecordForm.action;

            console.log('Form action:', formAction);
            console.log('Form data:', Array.from(formData.entries()));
            
            // Show loading state
            var submitButton = editRecordForm.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.textContent = 'Updating...';
            }
            
            fetch(formAction, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(function(response) {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                console.log('Response ok:', response.ok);
                
                if (!response.ok) {
                    throw new Error('HTTP ' + response.status + ': ' + response.statusText);
                }
                
                return response.text().then(function(text) {
                    console.log('Response text:', text);
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('Failed to parse JSON:', e);
                        throw new Error('Invalid JSON response');
                    }
                });
            })
            .then(function(data) {
                console.log('Parsed data:', data);
                if (data.success) {
                    // Close modal
                    editRecordDialog.close();
                    // Reload page to show updated data
                    window.location.reload();
                } else {
                    alert('Error updating record: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(function(error) {
                console.error('Fetch error:', error);
                alert('Error updating record. Please try again.');
            })
            .finally(function() {
                // Reset button state
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.textContent = 'Update Record';
                }
            });
        });
    }

    // Cascading dropdowns for add and edit forms
    var locationCsv = `BARANGAY,MUNICIPALITY,PROVINCE
Betes,Aliaga,Nueva Ecija
Bibiclat,Aliaga,Nueva Ecija
Bucot,Aliaga,Nueva Ecija
La Purisima,Aliaga,Nueva Ecija
Magsaysay,Aliaga,Nueva Ecija
Macabucod,Aliaga,Nueva Ecija
Pantoc,Aliaga,Nueva Ecija
Poblacion Centro,Aliaga,Nueva Ecija
Poblacion East I,Aliaga,Nueva Ecija
Poblacion East II,Aliaga,Nueva Ecija
Poblacion West III,Aliaga,Nueva Ecija
Poblacion West IV,Aliaga,Nueva Ecija
San Carlos,Aliaga,Nueva Ecija
San Emiliano,Aliaga,Nueva Ecija
San Eustacio,Aliaga,Nueva Ecija
San Felipe Bata,Aliaga,Nueva Ecija
San Felipe Matanda,Aliaga,Nueva Ecija
San Juan,Aliaga,Nueva Ecija
San Pablo Bata,Aliaga,Nueva Ecija
San Pablo Matanda,Aliaga,Nueva Ecija
Santa Monica,Aliaga,Nueva Ecija
Santiago,Aliaga,Nueva Ecija
Santo Rosario,Aliaga,Nueva Ecija
Santo Tomas,Aliaga,Nueva Ecija
Sunson,Aliaga,Nueva Ecija
Umangan,Aliaga,Nueva Ecija
Antipolo,Bongabon,Nueva Ecija
Ariendo,Bongabon,Nueva Ecija
Bantug,Bongabon,Nueva Ecija
Calaanan,Bongabon,Nueva Ecija
Commercial,Bongabon,Nueva Ecija
Cruz,Bongabon,Nueva Ecija
Digmala,Bongabon,Nueva Ecija
Curva,Bongabon,Nueva Ecija
Kaingin,Bongabon,Nueva Ecija
Labi,Bongabon,Nueva Ecija
Larcon,Bongabon,Nueva Ecija
Lusok,Bongabon,Nueva Ecija
Macabaclay,Bongabon,Nueva Ecija
Magtanggol,Bongabon,Nueva Ecija
Mantile,Bongabon,Nueva Ecija
Olivete,Bongabon,Nueva Ecija
Palo Maria,Bongabon,Nueva Ecija
Pesa,Bongabon,Nueva Ecija
Rizal,Bongabon,Nueva Ecija
Sampalucan,Bongabon,Nueva Ecija
San Roque,Bongabon,Nueva Ecija
Santor,Bongabon,Nueva Ecija
Sinipit,Bongabon,Nueva Ecija
Sisilang na Ligaya,Bongabon,Nueva Ecija
Social,Bongabon,Nueva Ecija
Tugatug,Bongabon,Nueva Ecija
Tulay na Bato,Bongabon,Nueva Ecija
Vega,Bongabon,Nueva Ecija
Aduas Centro,City of Cabanatuan,Nueva Ecija
Bagong Sikat,City of Cabanatuan,Nueva Ecija
Bagong Buhay,City of Cabanatuan,Nueva Ecija
Bakero,City of Cabanatuan,Nueva Ecija
Bakod Bayan,City of Cabanatuan,Nueva Ecija
Balite,City of Cabanatuan,Nueva Ecija
Bangad,City of Cabanatuan,Nueva Ecija
Bantug Bulalo,City of Cabanatuan,Nueva Ecija
Bantug Norte,City of Cabanatuan,Nueva Ecija
Barlis,City of Cabanatuan,Nueva Ecija
Barrera District,City of Cabanatuan,Nueva Ecija
Bernardo District,City of Cabanatuan,Nueva Ecija
Bitas,City of Cabanatuan,Nueva Ecija
Bonifacio District,City of Cabanatuan,Nueva Ecija
Buliran,City of Cabanatuan,Nueva Ecija
Caalibangbangan,City of Cabanatuan,Nueva Ecija
Cabu,City of Cabanatuan,Nueva Ecija
Campo Tinio,City of Cabanatuan,Nueva Ecija
Kapitan Pepe,City of Cabanatuan,Nueva Ecija
Cinco-Cinco,City of Cabanatuan,Nueva Ecija
City Supermarket,City of Cabanatuan,Nueva Ecija
Caudillo,City of Cabanatuan,Nueva Ecija
Communal,City of Cabanatuan,Nueva Ecija
Cruz Roja,City of Cabanatuan,Nueva Ecija
Daang Sarile,City of Cabanatuan,Nueva Ecija
Dalampang,City of Cabanatuan,Nueva Ecija
Dicarma,City of Cabanatuan,Nueva Ecija
Dimasalang,City of Cabanatuan,Nueva Ecija
Dionisio S. Garcia,City of Cabanatuan,Nueva Ecija
Fatima,City of Cabanatuan,Nueva Ecija
General Luna,City of Cabanatuan,Nueva Ecija
Ibabao Bana,City of Cabanatuan,Nueva Ecija
Imelda District,City of Cabanatuan,Nueva Ecija
Isla,City of Cabanatuan,Nueva Ecija
Calawagan,City of Cabanatuan,Nueva Ecija
Kalikid Norte,City of Cabanatuan,Nueva Ecija
Kalikid Sur,City of Cabanatuan,Nueva Ecija
Lagare,City of Cabanatuan,Nueva Ecija
M. S. Garcia,City of Cabanatuan,Nueva Ecija
Mabini Extension,City of Cabanatuan,Nueva Ecija
Mabini Homesite,City of Cabanatuan,Nueva Ecija
Macatbong,City of Cabanatuan,Nueva Ecija
Magsaysay District,City of Cabanatuan,Nueva Ecija
Matadero,City of Cabanatuan,Nueva Ecija
Lourdes,City of Cabanatuan,Nueva Ecija
Poblacion,Cabiao,Nueva Ecija
Bagong Sikat,Cabiao,Nueva Ecija
Buenavista,Cabiao,Nueva Ecija
San Isidro,Cabiao,Nueva Ecija
Sinipit,Cabiao,Nueva Ecija
Huma,Cabiao,Nueva Ecija
Entablado,Cabiao,Nueva Ecija
Nieves,Cabiao,Nueva Ecija
San Roque,Cabiao,Nueva Ecija
Santa Ines,Cabiao,Nueva Ecija
Santo Cristo,Cabiao,Nueva Ecija
Santa Rita,Cabiao,Nueva Ecija
San Gregorio,Cabiao,Nueva Ecija
Baluarte,Cabiao,Nueva Ecija
Bangkal,Cabiao,Nueva Ecija
Caalibangbangan,Cabiao,Nueva Ecija
Cabisuculan,Cabiao,Nueva Ecija
Calabasa,Cabiao,Nueva Ecija
Calumpang,Cabiao,Nueva Ecija
Caniogan,Cabiao,Nueva Ecija
Casile,Cabiao,Nueva Ecija
Castañeda,Cabiao,Nueva Ecija
Maligaya,Cabiao,Nueva Ecija
Pantoc Bula,Cabiao,Nueva Ecija
Pulo,Cabiao,Nueva Ecija
Sapa,Cabiao,Nueva Ecija
Tibag,Cabiao,Nueva Ecija
Tula,Cabiao,Nueva Ecija
Bagong Buhay,Carranglan,Nueva Ecija
Barangay 1,Carranglan,Nueva Ecija
Barangay 2,Carranglan,Nueva Ecija
Barangay 3,Carranglan,Nueva Ecija
Barangay 4,Carranglan,Nueva Ecija
Barangay 5,Carranglan,Nueva Ecija
Barangay 6,Carranglan,Nueva Ecija
Barangay 7,Carranglan,Nueva Ecija
Barangay 8,Carranglan,Nueva Ecija
Burgos,Carranglan,Nueva Ecija
Cabitucanan,Carranglan,Nueva Ecija
Calipatan,Carranglan,Nueva Ecija
Canaway,Carranglan,Nueva Ecija
D.D. Edwards,Carranglan,Nueva Ecija
Gen. Luna,Carranglan,Nueva Ecija
Larion,Carranglan,Nueva Ecija
Pob.,Carranglan,Nueva Ecija
Pudoc,Carranglan,Nueva Ecija
San Felipe,Carranglan,Nueva Ecija
Santo Rosario,Carranglan,Nueva Ecija
Udarb,Carranglan,Nueva Ecija
Yanac,Carranglan,Nueva Ecija
Alangalang,Cuyapo,Nueva Ecija
Bacao,Cuyapo,Nueva Ecija
Bucana,Cuyapo,Nueva Ecija
Bungot,Cuyapo,Nueva Ecija
Calaba,Cuyapo,Nueva Ecija
Calabasa,Cuyapo,Nueva Ecija
Calitlitan,Cuyapo,Nueva Ecija
Calsib,Cuyapo,Nueva Ecija
Canaan,Cuyapo,Nueva Ecija
Casilagan,Cuyapo,Nueva Ecija
Curva,Cuyapo,Nueva Ecija
Dulong Bayan,Cuyapo,Nueva Ecija
Guimba,Cuyapo,Nueva Ecija
Labney,Cuyapo,Nueva Ecija
Lagula,Cuyapo,Nueva Ecija
Langka,Cuyapo,Nueva Ecija
Licos,Cuyapo,Nueva Ecija
Lourdes,Cuyapo,Nueva Ecija
Mabini,Cuyapo,Nueva Ecija
Malapit,Cuyapo,Nueva Ecija
Nagpandayan,Cuyapo,Nueva Ecija
Poblacion,Cuyapo,Nueva Ecija
Pulo,Cuyapo,Nueva Ecija
San Roque,Cuyapo,Nueva Ecija
Santa Cruz,Cuyapo,Nueva Ecija
Santo Niño,Cuyapo,Nueva Ecija
Santo Tomas,Cuyapo,Nueva Ecija
Sinipit,Cuyapo,Nueva Ecija
Tagumpay,Cuyapo,Nueva Ecija
Villa Flores,Cuyapo,Nueva Ecija
Villa Peña,Cuyapo,Nueva Ecija
Balsing,City of Gapan,Nueva Ecija
Bayabas,City of Gapan,Nueva Ecija
Bungot,City of Gapan,Nueva Ecija
Buray,City of Gapan,Nueva Ecija
Cacarawan,City of Gapan,Nueva Ecija
Calaacan,City of Gapan,Nueva Ecija
Cayungan,City of Gapan,Nueva Ecija
Dona Carmen,City of Gapan,Nueva Ecija
Luzviminda,City of Gapan,Nueva Ecija
Mabini,City of Gapan,Nueva Ecija
Macabud,City of Gapan,Nueva Ecija
Mahipon,City of Gapan,Nueva Ecija
Malingling,City of Gapan,Nueva Ecija
Manlag,City of Gapan,Nueva Ecija
Panggol,City of Gapan,Nueva Ecija
Poblacion,City of Gapan,Nueva Ecija
San Antonio,City of Gapan,Nueva Ecija
San Isidro,City of Gapan,Nueva Ecija
San Lorenzo,City of Gapan,Nueva Ecija
San Nicolas,City of Gapan,Nueva Ecija
San Roque,City of Gapan,Nueva Ecija
Santa Cruz,City of Gapan,Nueva Ecija
Santo Cristo,City of Gapan,Nueva Ecija
Santo Niño,City of Gapan,Nueva Ecija
Santo Rosario,City of Gapan,Nueva Ecija
Sinipit,City of Gapan,Nueva Ecija
Tagumpay,City of Gapan,Nueva Ecija
Agtipalo,General Mamerto Natividad,Nueva Ecija
Bangkal,General Mamerto Natividad,Nueva Ecija
Bigte,General Mamerto Natividad,Nueva Ecija
Cabiao,General Mamerto Natividad,Nueva Ecija
Castañeda,General Mamerto Natividad,Nueva Ecija
Kabay,General Mamerto Natividad,Nueva Ecija
Mag-asawang Sampaga,General Mamerto Natividad,Nueva Ecija
Maligaya,General Mamerto Natividad,Nueva Ecija
Matingkis,General Mamerto Natividad,Nueva Ecija
Poblacion,General Mamerto Natividad,Nueva Ecija
Pula,General Mamerto Natividad,Nueva Ecija
Santo Rosario,General Mamerto Natividad,Nueva Ecija
Singalat,General Mamerto Natividad,Nueva Ecija
Pulong Gubat,General Tinio,Nueva Ecija
Rio Chico,General Tinio,Nueva Ecija
Poblacion,General Tinio,Nueva Ecija
Papaya,General Tinio,Nueva Ecija
Baret,General Tinio,Nueva Ecija
Brgy. 1,General Tinio,Nueva Ecija
Brgy. 2,General Tinio,Nueva Ecija
Brgy. 3,General Tinio,Nueva Ecija
Brgy. 4,General Tinio,Nueva Ecija
Brgy. 5,General Tinio,Nueva Ecija
Brgy. 6,General Tinio,Nueva Ecija
Brgy. 7,General Tinio,Nueva Ecija
Brgy. 8,General Tinio,Nueva Ecija
Aduas Centro,Guimba,Nueva Ecija
Aduas Norte,Guimba,Nueva Ecija
Aduas Sur,Guimba,Nueva Ecija
Bangan 1st,Guimba,Nueva Ecija
Bangan 2nd,Guimba,Nueva Ecija
Calaba,Guimba,Nueva Ecija
Cawayan,Guimba,Nueva Ecija
District 1,Guimba,Nueva Ecija
District 2,Guimba,Nueva Ecija
District 3,Guimba,Nueva Ecija
Gapan,Guimba,Nueva Ecija
Mariano,Guimba,Nueva Ecija
Narvaez,Guimba,Nueva Ecija
Rizal,Guimba,Nueva Ecija
San Agustin,Guimba,Nueva Ecija
San Andres,Guimba,Nueva Ecija
San Bernardino,Guimba,Nueva Ecija
San Cristobal,Guimba,Nueva Ecija
San Francisco,Guimba,Nueva Ecija
San Isidro,Guimba,Nueva Ecija
San Jose,Guimba,Nueva Ecija
San Juan,Guimba,Nueva Ecija
San Leonardo,Guimba,Nueva Ecija
San Marcos,Guimba,Nueva Ecija
San Miguel,Guimba,Nueva Ecija
San Pedro,Guimba,Nueva Ecija
San Roque,Guimba,Nueva Ecija
San Vicente,Guimba,Nueva Ecija
Santa Cruz,Guimba,Nueva Ecija
Santa Maria,Guimba,Nueva Ecija
Santa Rita,Guimba,Nueva Ecija
Santo Cristo,Guimba,Nueva Ecija
Santo Niño,Guimba,Nueva Ecija
Santo Rosario,Guimba,Nueva Ecija
Sapang,Guimba,Nueva Ecija
Tabi,Guimba,Nueva Ecija
Tangcarang,Guimba,Nueva Ecija
Tayok,Guimba,Nueva Ecija
Tulay,Guimba,Nueva Ecija
Villa Natividad,Guimba,Nueva Ecija
Agapito,Jaen,Nueva Ecija
Aguinaldo,Jaen,Nueva Ecija
Bayani,Jaen,Nueva Ecija
Binon,Jaen,Nueva Ecija
Bucal,Jaen,Nueva Ecija
Cabayao,Jaen,Nueva Ecija
Cabcab,Jaen,Nueva Ecija
Cacarawan,Jaen,Nueva Ecija
Calaacan,Jaen,Nueva Ecija
Calitlitan,Jaen,Nueva Ecija
Calsib,Jaen,Nueva Ecija
Canaan,Jaen,Nueva Ecija
Casilagan,Jaen,Nueva Ecija
Curva,Jaen,Nueva Ecija
Dulong Bayan,Jaen,Nueva Ecija
Lambakin,Jaen,Nueva Ecija
Lanas,Jaen,Nueva Ecija
Langka,Jaen,Nueva Ecija
Licos,Jaen,Nueva Ecija
Lourdes,Jaen,Nueva Ecija
Mabini,Jaen,Nueva Ecija
Malapit,Jaen,Nueva Ecija
Nagpandayan,Jaen,Nueva Ecija
Poblacion,Jaen,Nueva Ecija
Pulo,Jaen,Nueva Ecija
San Agustin,Jaen,Nueva Ecija
San Jose,Jaen,Nueva Ecija
San Pablo,Jaen,Nueva Ecija
San Pedro,Jaen,Nueva Ecija
San Roque,Jaen,Nueva Ecija
Santa Cruz,Jaen,Nueva Ecija
Santo Niño,Jaen,Nueva Ecija
Santo Tomas,Jaen,Nueva Ecija
Sinipit,Jaen,Nueva Ecija
Tagumpay,Jaen,Nueva Ecija
Villa Flores,Jaen,Nueva Ecija
Villa Peña,Jaen,Nueva Ecija
Agtipalo,Laur,Nueva Ecija
Baluarte,Laur,Nueva Ecija
Bangkalis,Laur,Nueva Ecija
Canaway,Laur,Nueva Ecija
Castañeda,Laur,Nueva Ecija
Natividad,Laur,Nueva Ecija
Poblacion,Laur,Nueva Ecija
San Fernando,Laur,Nueva Ecija
San Isidro,Laur,Nueva Ecija
San Jose,Laur,Nueva Ecija
San Juan,Laur,Nueva Ecija
San Miguel,Laur,Nueva Ecija
San Pedro,Laur,Nueva Ecija
San Roque,Laur,Nueva Ecija
Santa Cruz,Laur,Nueva Ecija
Santo Cristo,Laur,Nueva Ecija
Santo Niño,Laur,Nueva Ecija
Santo Rosario,Laur,Nueva Ecija
Sinipit,Laur,Nueva Ecija
Tagumpay,Laur,Nueva Ecija
Villa Flores,Laur,Nueva Ecija
Villa Peña,Laur,Nueva Ecija
Pulong Gubat,Licab,Nueva Ecija
Licaong,Licab,Nueva Ecija
Lombac,Licab,Nueva Ecija
San Cristobal,Licab,Nueva Ecija
San Jose,Licab,Nueva Ecija
San Juan,Licab,Nueva Ecija
Santa Maria,Licab,Nueva Ecija
Santo Tomas,Licab,Nueva Ecija
Sinipit,Licab,Nueva Ecija
Agtipalo,Llanera,Nueva Ecija
Baluarte,Llanera,Nueva Ecija
Bangkalis,Llanera,Nueva Ecija
Canaway,Llanera,Nueva Ecija
Castañeda,Llanera,Nueva Ecija
Natividad,Llanera,Nueva Ecija
Poblacion,Llanera,Nueva Ecija
San Fernando,Llanera,Nueva Ecija
San Isidro,Llanera,Nueva Ecija
San Jose,Llanera,Nueva Ecija
San Juan,Llanera,Nueva Ecija
San Miguel,Llanera,Nueva Ecija
San Pedro,Llanera,Nueva Ecija
San Roque,Llanera,Nueva Ecija
Santa Cruz,Llanera,Nueva Ecija
Santo Cristo,Llanera,Nueva Ecija
Santo Niño,Llanera,Nueva Ecija
Santo Rosario,Llanera,Nueva Ecija
Sinipit,Llanera,Nueva Ecija
Tagumpay,Llanera,Nueva Ecija
Villa Flores,Llanera,Nueva Ecija
Villa Peña,Llanera,Nueva Ecija
Bagong Silang,Lupao,Nueva Ecija
Cagayan,Lupao,Nueva Ecija
Calaba,Lupao,Nueva Ecija
Calitlitan,Lupao,Nueva Ecija
Calsib,Lupao,Nueva Ecija
Canaan,Lupao,Nueva Ecija
Casilagan,Lupao,Nueva Ecija
Curva,Lupao,Nueva Ecija
Dulong Bayan,Lupao,Nueva Ecija
Lambakin,Lupao,Nueva Ecija
Lanas,Lupao,Nueva Ecija
Langka,Lupao,Nueva Ecija
Licos,Lupao,Nueva Ecija
Lourdes,Lupao,Nueva Ecija
Mabini,Lupao,Nueva Ecija
Malapit,Lupao,Nueva Ecija
Nagpandayan,Lupao,Nueva Ecija
Poblacion,Lupao,Nueva Ecija
Pulo,Lupao,Nueva Ecija
San Agustin,Lupao,Nueva Ecija
San Jose,Lupao,Nueva Ecija
San Pablo,Lupao,Nueva Ecija
San Pedro,Lupao,Nueva Ecija
San Roque,Lupao,Nueva Ecija
Santa Cruz,Lupao,Nueva Ecija
Santo Niño,Lupao,Nueva Ecija
Santo Tomas,Lupao,Nueva Ecija
Sinipit,Lupao,Nueva Ecija
Tagumpay,Lupao,Nueva Ecija
Villa Flores,Lupao,Nueva Ecija
Villa Peña,Lupao,Nueva Ecija
Agtipalo,Science City of Muñoz,Nueva Ecija
Baluarte,Science City of Muñoz,Nueva Ecija
Bangkalis,Science City of Muñoz,Nueva Ecija
Canaway,Science City of Muñoz,Nueva Ecija
Castañeda,Science City of Muñoz,Nueva Ecija
Natividad,Science City of Muñoz,Nueva Ecija
Poblacion,Science City of Muñoz,Nueva Ecija
San Fernando,Science City of Muñoz,Nueva Ecija
San Isidro,Science City of Muñoz,Nueva Ecija
San Jose,Science City of Muñoz,Nueva Ecija
San Juan,Science City of Muñoz,Nueva Ecija
San Miguel,Science City of Muñoz,Nueva Ecija
San Pedro,Science City of Muñoz,Nueva Ecija
San Roque,Science City of Muñoz,Nueva Ecija
Santa Cruz,Science City of Muñoz,Nueva Ecija
Santo Cristo,Science City of Muñoz,Nueva Ecija
Santo Niño,Science City of Muñoz,Nueva Ecija
Santo Rosario,Science City of Muñoz,Nueva Ecija
Sinipit,Science City of Muñoz,Nueva Ecija
Tagumpay,Science City of Muñoz,Nueva Ecija
Villa Flores,Science City of Muñoz,Nueva Ecija
Villa Peña,Science City of Muñoz,Nueva Ecija
Agtipalo,Nampicuan,Nueva Ecija
Baluarte,Nampicuan,Nueva Ecija
Bangkalis,Nampicuan,Nueva Ecija
Canaway,Nampicuan,Nueva Ecija
Castañeda,Nampicuan,Nueva Ecija
Natividad,Nampicuan,Nueva Ecija
Poblacion,Nampicuan,Nueva Ecija
San Fernando,Nampicuan,Nueva Ecija
San Isidro,Nampicuan,Nueva Ecija
San Jose,Nampicuan,Nueva Ecija
San Juan,Nampicuan,Nueva Ecija
San Miguel,Nampicuan,Nueva Ecija
San Pedro,Nampicuan,Nueva Ecija
San Roque,Nampicuan,Nueva Ecija
Santa Cruz,Nampicuan,Nueva Ecija
Santo Cristo,Nampicuan,Nueva Ecija
Santo Niño,Nampicuan,Nueva Ecija
Santo Rosario,Nampicuan,Nueva Ecija
Sinipit,Nampicuan,Nueva Ecija
Tagumpay,Nampicuan,Nueva Ecija
Villa Flores,Nampicuan,Nueva Ecija
Villa Peña,Nampicuan,Nueva Ecija
Agtipalo,City of Palayan,Nueva Ecija
Baluarte,City of Palayan,Nueva Ecija
Bangkalis,City of Palayan,Nueva Ecija
Canaway,City of Palayan,Nueva Ecija
Castañeda,City of Palayan,Nueva Ecija
Natividad,City of Palayan,Nueva Ecija
Poblacion,City of Palayan,Nueva Ecija
San Fernando,City of Palayan,Nueva Ecija
San Isidro,City of Palayan,Nueva Ecija
San Jose,City of Palayan,Nueva Ecija
San Juan,City of Palayan,Nueva Ecija
San Miguel,City of Palayan,Nueva Ecija
San Pedro,City of Palayan,Nueva Ecija
San Roque,City of Palayan,Nueva Ecija
Santa Cruz,City of Palayan,Nueva Ecija
Santo Cristo,City of Palayan,Nueva Ecija
Santo Niño,City of Palayan,Nueva Ecija
Santo Rosario,City of Palayan,Nueva Ecija
Sinipit,City of Palayan,Nueva Ecija
Tagumpay,City of Palayan,Nueva Ecija
Villa Flores,City of Palayan,Nueva Ecija
Villa Peña,City of Palayan,Nueva Ecija
Agtipalo,Pantabangan,Nueva Ecija
Baluarte,Pantabangan,Nueva Ecija
Bangkalis,Pantabangan,Nueva Ecija
Canaway,Pantabangan,Nueva Ecija
Castañeda,Pantabangan,Nueva Ecija
Natividad,Pantabangan,Nueva Ecija
Poblacion,Pantabangan,Nueva Ecija
San Fernando,Pantabangan,Nueva Ecija
San Isidro,Pantabangan,Nueva Ecija
San Jose,Pantabangan,Nueva Ecija
San Juan,Pantabangan,Nueva Ecija
San Miguel,Pantabangan,Nueva Ecija
San Pedro,Pantabangan,Nueva Ecija
San Roque,Pantabangan,Nueva Ecija
Santa Cruz,Pantabangan,Nueva Ecija
Santo Cristo,Pantabangan,Nueva Ecija
Santo Niño,Pantabangan,Nueva Ecija
Santo Rosario,Pantabangan,Nueva Ecija
Sinipit,Pantabangan,Nueva Ecija
Tagumpay,Pantabangan,Nueva Ecija
Villa Flores,Pantabangan,Nueva Ecija
Villa Peña,Pantabangan,Nueva Ecija
Agtipalo,Peñaranda,Nueva Ecija
Baluarte,Peñaranda,Nueva Ecija
Bangkalis,Peñaranda,Nueva Ecija
Canaway,Peñaranda,Nueva Ecija
Castañeda,Peñaranda,Nueva Ecija
Natividad,Peñaranda,Nueva Ecija
Poblacion,Peñaranda,Nueva Ecija
San Fernando,Peñaranda,Nueva Ecija
San Isidro,Peñaranda,Nueva Ecija
San Jose,Peñaranda,Nueva Ecija
San Juan,Peñaranda,Nueva Ecija
San Miguel,Peñaranda,Nueva Ecija
San Pedro,Peñaranda,Nueva Ecija
San Roque,Peñaranda,Nueva Ecija
Santa Cruz,Peñaranda,Nueva Ecija
Santo Cristo,Peñaranda,Nueva Ecija
Santo Niño,Peñaranda,Nueva Ecija
Santo Rosario,Peñaranda,Nueva Ecija
Sinipit,Peñaranda,Nueva Ecija
Tagumpay,Peñaranda,Nueva Ecija
Villa Flores,Peñaranda,Nueva Ecija
Villa Peña,Peñaranda,Nueva Ecija
Agtipalo,Quezon,Nueva Ecija
Baluarte,Quezon,Nueva Ecija
Bangkalis,Quezon,Nueva Ecija
Canaway,Quezon,Nueva Ecija
Castañeda,Quezon,Nueva Ecija
Natividad,Quezon,Nueva Ecija
Poblacion,Quezon,Nueva Ecija
San Fernando,Quezon,Nueva Ecija
San Isidro,Quezon,Nueva Ecija
San Jose,Quezon,Nueva Ecija
San Juan,Quezon,Nueva Ecija
San Miguel,Quezon,Nueva Ecija
San Pedro,Quezon,Nueva Ecija
San Roque,Quezon,Nueva Ecija
Santa Cruz,Quezon,Nueva Ecija
Santo Cristo,Quezon,Nueva Ecija
Santo Niño,Quezon,Nueva Ecija
Santo Rosario,Quezon,Nueva Ecija
Sinipit,Quezon,Nueva Ecija
Tagumpay,Quezon,Nueva Ecija
Villa Flores,Quezon,Nueva Ecija
Villa Peña,Quezon,Nueva Ecija
San Alejandro,Quezon,Nueva Ecija
San Andres I,Quezon,Nueva Ecija
San Andres II,Quezon,Nueva Ecija
San Manuel,Quezon,Nueva Ecija
Santa Clara,Quezon,Nueva Ecija
Santa Rita,Quezon,Nueva Ecija
Santo Cristo,Quezon,Nueva Ecija
Santo Tomas Feria,Quezon,Nueva Ecija
San Miguel,Quezon,Nueva Ecija
Agbannawag,Rizal,Nueva Ecija
Bicos,Rizal,Nueva Ecija
Cabucbucan,Rizal,Nueva Ecija
Calaocan District,Rizal,Nueva Ecija
Canaan East,Rizal,Nueva Ecija
Canaan West,Rizal,Nueva Ecija
Casilagan,Rizal,Nueva Ecija
Aglipay,Rizal,Nueva Ecija
Del Pilar,Rizal,Nueva Ecija
Estrella,Rizal,Nueva Ecija
General Luna,Rizal,Nueva Ecija
Macapsing,Rizal,Nueva Ecija
Maligaya,Rizal,Nueva Ecija
Paco Roman,Rizal,Nueva Ecija
Pag-asa,Rizal,Nueva Ecija
Poblacion Central,Rizal,Nueva Ecija
Poblacion East,Rizal,Nueva Ecija
Poblacion Norte,Rizal,Nueva Ecija
Poblacion Sur,Rizal,Nueva Ecija
Poblacion West,Rizal,Nueva Ecija
Portal,Rizal,Nueva Ecija
San Esteban,Rizal,Nueva Ecija
Santa Monica,Rizal,Nueva Ecija
Villa Labrador,Rizal,Nueva Ecija
Villa Paraiso,Rizal,Nueva Ecija
San Gregorio,Rizal,Nueva Ecija
Buliran,San Antonio,Nueva Ecija
Cama Juan,San Antonio,Nueva Ecija
Julo,San Antonio,Nueva Ecija
Lawang Kupang,San Antonio,Nueva Ecija
Luyos,San Antonio,Nueva Ecija
Maugat,San Antonio,Nueva Ecija
Panabingan,San Antonio,Nueva Ecija
Papaya,San Antonio,Nueva Ecija
Poblacion,San Antonio,Nueva Ecija
San Francisco,San Antonio,Nueva Ecija
San Jose,San Antonio,Nueva Ecija
San Mariano,San Antonio,Nueva Ecija
Santa Cruz,San Antonio,Nueva Ecija
Santo Cristo,San Antonio,Nueva Ecija
Santa Barbara,San Antonio,Nueva Ecija
Tikiw,San Antonio,Nueva Ecija
Alua,San Isidro,Nueva Ecija
Calaba,San Isidro,Nueva Ecija
Malapit,San Isidro,Nueva Ecija
Mangga,San Isidro,Nueva Ecija
Poblacion,San Isidro,Nueva Ecija
Pulo,San Isidro,Nueva Ecija
San Roque,San Isidro,Nueva Ecija
Sto. Cristo,San Isidro,Nueva Ecija
Tabon,San Isidro,Nueva Ecija
A. Pascual,San Jose City,Nueva Ecija
Abar Ist,San Jose City,Nueva Ecija
Abar 2nd,San Jose City,Nueva Ecija
Bagong Sikat,San Jose City,Nueva Ecija
Caanawan,San Jose City,Nueva Ecija
Calaocan,San Jose City,Nueva Ecija
Camanacsacan,San Jose City,Nueva Ecija
Culaylay,San Jose City,Nueva Ecija
Dizol,San Jose City,Nueva Ecija
Kaliwanagan,San Jose City,Nueva Ecija
Kita-Kita,San Jose City,Nueva Ecija
Malasin,San Jose City,Nueva Ecija
Manicla,San Jose City,Nueva Ecija
Palestina,San Jose City,Nueva Ecija
Parang Mangga,San Jose City,Nueva Ecija
Villa Joson,San Jose City,Nueva Ecija
Pinili,San Jose City,Nueva Ecija
Ferdinand E. Marcos Pob.,San Jose City,Nueva Ecija
Canuto Ramos Pob.,San Jose City,Nueva Ecija
Raymundo Eugenio Pob.,San Jose City,Nueva Ecija
Crisanto Sanchez Pob.,San Jose City,Nueva Ecija
Porais,San Jose City,Nueva Ecija
San Agustin,San Jose City,Nueva Ecija
San Juan,San Jose City,Nueva Ecija
San Mauricio,San Jose City,Nueva Ecija
Santo Niño 1st,San Jose City,Nueva Ecija
Santo Niño 2nd,San Jose City,Nueva Ecija
Santo Tomas,San Jose City,Nueva Ecija
Sibut,San Jose City,Nueva Ecija
Sinipit Bubon,San Jose City,Nueva Ecija
Santo Niño 3rd,San Jose City,Nueva Ecija
Tabulac,San Jose City,Nueva Ecija
Tayabo,San Jose City,Nueva Ecija
Tondod,San Jose City,Nueva Ecija
Tulat,San Jose City,Nueva Ecija
Villa Floresca,San Jose City,Nueva Ecija
Villa Marina,San Jose City,Nueva Ecija
Bonifacio District,San Leonardo,Nueva Ecija
Burgos District,San Leonardo,Nueva Ecija
Castellano,San Leonardo,Nueva Ecija
Diversion,San Leonardo,Nueva Ecija
Magpapalayoc,San Leonardo,Nueva Ecija
Mallorca,San Leonardo,Nueva Ecija
Mambangnan,San Leonardo,Nueva Ecija
Nieves,San Leonardo,Nueva Ecija
San Bartolome,San Leonardo,Nueva Ecija
Rizal District,San Leonardo,Nueva Ecija
San Anton,San Leonardo,Nueva Ecija
San Roque,San Leonardo,Nueva Ecija
Tabuating,San Leonardo,Nueva Ecija
Tagumpay,San Leonardo,Nueva Ecija
Tambo Adorable,San Leonardo,Nueva Ecija
Cojuangco,Santa Rosa,Nueva Ecija
La Fuente,Santa Rosa,Nueva Ecija
Liwayway,Santa Rosa,Nueva Ecija
Malacañang,Santa Rosa,Nueva Ecija
Maliolio,Santa Rosa,Nueva Ecija
Mapalad,Santa Rosa,Nueva Ecija
Rizal,Santa Rosa,Nueva Ecija
Rajal Centro,Santa Rosa,Nueva Ecija
Rajal Norte,Santa Rosa,Nueva Ecija
Rajal Sur,Santa Rosa,Nueva Ecija
San Gregorio,Santa Rosa,Nueva Ecija
San Mariano,Santa Rosa,Nueva Ecija
San Pedro,Santa Rosa,Nueva Ecija
Santo Rosario,Santa Rosa,Nueva Ecija
Soledad,Santa Rosa,Nueva Ecija
Valenzuela,Santa Rosa,Nueva Ecija
Zamora,Santa Rosa,Nueva Ecija
Aguinaldo,Santa Rosa,Nueva Ecija
Berang,Santa Rosa,Nueva Ecija
Burgos,Santa Rosa,Nueva Ecija
Del Pilar,Santa Rosa,Nueva Ecija
Gomez,Santa Rosa,Nueva Ecija
Inspector,Santa Rosa,Nueva Ecija
Isla,Santa Rosa,Nueva Ecija
Lourdes,Santa Rosa,Nueva Ecija
Luna,Santa Rosa,Nueva Ecija
Mabini,Santa Rosa,Nueva Ecija
San Isidro,Santa Rosa,Nueva Ecija
San Josep,Santa Rosa,Nueva Ecija
Santa Teresita,Santa Rosa,Nueva Ecija
Sapsap,Santa Rosa,Nueva Ecija
Tagpos,Santa Rosa,Nueva Ecija
Tramo,Santa Rosa,Nueva Ecija
Baloc,Santo Domingo,Nueva Ecija
Buasao,Santo Domingo,Nueva Ecija
Burgos,Santo Domingo,Nueva Ecija
Cabugao,Santo Domingo,Nueva Ecija
Casulucan,Santo Domingo,Nueva Ecija
Comitang,Santo Domingo,Nueva Ecija
Concepcion,Santo Domingo,Nueva Ecija
Dolores,Santo Domingo,Nueva Ecija
General Luna,Santo Domingo,Nueva Ecija
Hulo,Santo Domingo,Nueva Ecija
Mabini,Santo Domingo,Nueva Ecija
Malasin,Santo Domingo,Nueva Ecija
Malayantoc,Santo Domingo,Nueva Ecija
Mambarao,Santo Domingo,Nueva Ecija
Poblacion,Santo Domingo,Nueva Ecija
Malaya,Santo Domingo,Nueva Ecija
Pulong Buli,Santo Domingo,Nueva Ecija
Sagaba,Santo Domingo,Nueva Ecija
San Agustin,Santo Domingo,Nueva Ecija
San Fabian,Santo Domingo,Nueva Ecija
San Francisco,Santo Domingo,Nueva Ecija
San Pascual,Santo Domingo,Nueva Ecija
Santa Rita,Santo Domingo,Nueva Ecija
Santo Rosario,Santo Domingo,Nueva Ecija
Andal Alino,Talavera,Nueva Ecija
Bagong Sikat,Talavera,Nueva Ecija
Bagong Silang,Talavera,Nueva Ecija
Bakal I,Talavera,Nueva Ecija
Bakal II,Talavera,Nueva Ecija
Bakal III,Talavera,Nueva Ecija
Baluga,Talavera,Nueva Ecija
Bantug,Talavera,Nueva Ecija
Bantug Hacienda,Talavera,Nueva Ecija
Bantug Hamog,Talavera,Nueva Ecija
Bugtong na Buli,Talavera,Nueva Ecija
Bulac,Talavera,Nueva Ecija
Burnay,Talavera,Nueva Ecija
Calipahan,Talavera,Nueva Ecija
Campos,Talavera,Nueva Ecija
Casulucan Este,Talavera,Nueva Ecija
Collado,Talavera,Nueva Ecija
Dimasalang Norte,Talavera,Nueva Ecija
Dimasalang Sur,Talavera,Nueva Ecija
Dinarayat,Talavera,Nueva Ecija
Esguerra District,Talavera,Nueva Ecija
Gulod,Talavera,Nueva Ecija
Homestead I,Talavera,Nueva Ecija
Homestead II,Talavera,Nueva Ecija
Cabubulaonan,Talavera,Nueva Ecija
Caaniplahan,Talavera,Nueva Ecija
Caputican,Talavera,Nueva Ecija
Kinalanguyan,Talavera,Nueva Ecija
La Torre,Talavera,Nueva Ecija
Lomboy,Talavera,Nueva Ecija
Mabuhay,Talavera,Nueva Ecija
Maestrang Kikay,Talavera,Nueva Ecija
Mamandil,Talavera,Nueva Ecija
Marcos District,Talavera,Nueva Ecija
Purok Matias,Talavera,Nueva Ecija
Matingkis,Talavera,Nueva Ecija
Minabuyoc,Talavera,Nueva Ecija
Pag-asa,Talavera,Nueva Ecija
Paludpod,Talavera,Nueva Ecija
Pantoc Bulac,Talavera,Nueva Ecija
Pinagpanaan,Talavera,Nueva Ecija
Poblacion Sur,Talavera,Nueva Ecija
Pula,Talavera,Nueva Ecija
Pulong San Miguel,Talavera,Nueva Ecija
Sampaloc,Talavera,Nueva Ecija
San Miguel na Munti,Talavera,Nueva Ecija
San Pascual,Talavera,Nueva Ecija
San Ricardo,Talavera,Nueva Ecija
Sibul,Talavera,Nueva Ecija
Sicsican Matanda,Talavera,Nueva Ecija
Tabacao,Talavera,Nueva Ecija
Tagaytay,Talavera,Nueva Ecija
Valle,Talavera,Nueva Ecija
Alula,Talugtug,Nueva Ecija
Baybayabas,Talugtug,Nueva Ecija
Buted,Talugtug,Nueva Ecija
Cabiangan,Talugtug,Nueva Ecija
Calisitan,Talugtug,Nueva Ecija
Cinense,Talugtug,Nueva Ecija
Culiat,Talugtug,Nueva Ecija
Maasin,Talugtug,Nueva Ecija
Magsaysay,Talugtug,Nueva Ecija
Mayamot I,Talugtug,Nueva Ecija
Mayamot II,Talugtug,Nueva Ecija
Nangabulan,Talugtug,Nueva Ecija
Osmeña,Talugtug,Nueva Ecija
Pangit,Talugtug,Nueva Ecija
Patola,Talugtug,Nueva Ecija
Quezon,Talugtug,Nueva Ecija
Quirino,Talugtug,Nueva Ecija
Roxas,Talugtug,Nueva Ecija
Saguing,Talugtug,Nueva Ecija
Sampaloc,Talugtug,Nueva Ecija
Santa Catalina,Talugtug,Nueva Ecija
Santo Domingo,Talugtug,Nueva Ecija
Saringaya,Talugtug,Nueva Ecija
Saverona,Talugtug,Nueva Ecija
Tandoc,Talugtug,Nueva Ecija
Tibag,Talugtug,Nueva Ecija
Villa Rosario,Talugtug,Nueva Ecija
Villa Boado,Talugtug,Nueva Ecija
Batitang,Zaragoza,Nueva Ecija
Carmen,Zaragoza,Nueva Ecija
Concepcion,Zaragoza,Nueva Ecija
Del Pilar,Zaragoza,Nueva Ecija
General Luna,Zaragoza,Nueva Ecija
H. Romero,Zaragoza,Nueva Ecija
Macarse,Zaragoza,Nueva Ecija
Manaul,Zaragoza,Nueva Ecija
Mayamot,Zaragoza,Nueva Ecija
Pantoc,Zaragoza,Nueva Ecija
San Vicente,Zaragoza,Nueva Ecija
San Isidro,Zaragoza,Nueva Ecija
San Rafael,Zaragoza,Nueva Ecija
Santa Cruz,Zaragoza,Nueva Ecija
Santa Lucia Old,Zaragoza,Nueva Ecija
Santa Lucia Young,Zaragoza,Nueva Ecija
Santo Rosario Old,Zaragoza,Nueva Ecija
Santo Rosario Young,Zaragoza,Nueva Ecija
Valeriana,Zaragoza,Nueva Ecija
Agtipalo,Baler,Aurora
Babat,Baler,Aurora
Bacong,Baler,Aurora
Baliag,Baler,Aurora
Bansaan,Baler,Aurora
Bantay,Baler,Aurora
Bukal,Baler,Aurora
Buru-Buru,Baler,Aurora
Calabuanan,Baler,Aurora
Calantas,Baler,Aurora
Calungayan,Baler,Aurora
Caniogan,Baler,Aurora
Colongcolong,Baler,Aurora
Dibalo,Baler,Aurora
Dibut,Baler,Aurora
Dimalangat,Baler,Aurora
Ditumabo,Baler,Aurora
Duongan,Baler,Aurora
Fulgador,Baler,Aurora
Gumabat,Baler,Aurora
Poblacion,Baler,Aurora
Simbahan,Baler,Aurora
Umiray,Baler,Aurora
Yapara,Baler,Aurora`;

    function parseCsvLine(line) {
        var values = [];
        var current = '';
        var inQuotes = false;

        for (var i = 0; i < line.length; i++) {
            var char = line[i];
            if (char === '"') {
                inQuotes = !inQuotes;
            } else if (char === ',' && !inQuotes) {
                values.push(current);
                current = '';
            } else {
                current += char;
            }
        }

        values.push(current);
        return values;
    }

    function parseLocationData(csv) {
        var data = {};
        var lines = csv.split(/\r?\n/).filter(function(line) { return line.trim().length > 0; });

        for (var i = 1; i < lines.length; i++) {
            var parts = parseCsvLine(lines[i]);
            var barangay = parts[0];
            var municipality = parts[1];
            var province = parts[2];

            if (!province || !municipality || !barangay) {
                continue;
            }

            if (!data[province]) {
                data[province] = {};
            }
            if (!data[province][municipality]) {
                data[province][municipality] = [];
            }
            if (data[province][municipality].indexOf(barangay) === -1) {
                data[province][municipality].push(barangay);
            }
        }

        for (var province in data) {
            for (var municipality in data[province]) {
                data[province][municipality].sort(function(a, b) { return a.localeCompare(b, 'en', { sensitivity: 'base' }); });
            }
        }

        return data;
    }

    var locationData = parseLocationData(locationCsv);

    function populateSelect(selectElement, options, placeholder) {
        selectElement.innerHTML = '';
        var defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = placeholder;
        selectElement.appendChild(defaultOption);

        options.forEach(function (option) {
            var optionItem = document.createElement('option');
            optionItem.value = option;
            optionItem.textContent = option;
            selectElement.appendChild(optionItem);
        });
    }

    // Add record form cascading dropdowns
    var addProvince = document.getElementById('province');
    var addMunicipality = document.getElementById('municipality');
    var addBarangay = document.getElementById('barangay');

    if (addProvince && addMunicipality && addBarangay) {
        addProvince.addEventListener('change', function() {
            if (!addProvince.value) {
                populateSelect(addMunicipality, [], 'Select Municipality');
                populateSelect(addBarangay, [], 'Select Barangay');
                addMunicipality.disabled = true;
                addBarangay.disabled = true;
                return;
            }
            var municipalities = Object.keys(locationData[addProvince.value] || {});
            populateSelect(addMunicipality, municipalities, 'Select Municipality');
            addMunicipality.disabled = false;
            populateSelect(addBarangay, [], 'Select Barangay');
            addBarangay.disabled = true;
        });

        addMunicipality.addEventListener('change', function() {
            if (!addProvince.value || !addMunicipality.value) {
                populateSelect(addBarangay, [], 'Select Barangay');
                addBarangay.disabled = true;
                return;
            }
            var barangays = locationData[addProvince.value]?.[addMunicipality.value] || [];
            populateSelect(addBarangay, barangays, 'Select Barangay');
            addBarangay.disabled = false;
        });
    }

    // Edit record form cascading dropdowns
    var editProvince = document.getElementById('editProvince');
    var editMunicipality = document.getElementById('editMunicipality');
    var editBarangay = document.getElementById('editBarangay');

    if (editProvince && editMunicipality && editBarangay) {
        editProvince.addEventListener('change', function() {
            if (!editProvince.value) {
                populateSelect(editMunicipality, [], 'Select Municipality');
                populateSelect(editBarangay, [], 'Select Barangay');
                editMunicipality.disabled = true;
                editBarangay.disabled = true;
                return;
            }
            var municipalities = Object.keys(locationData[editProvince.value] || {});
            populateSelect(editMunicipality, municipalities, 'Select Municipality');
            editMunicipality.disabled = false;
            populateSelect(editBarangay, [], 'Select Barangay');
            editBarangay.disabled = true;
        });

        editMunicipality.addEventListener('change', function() {
            if (!editProvince.value || !editMunicipality.value) {
                populateSelect(editBarangay, [], 'Select Barangay');
                editBarangay.disabled = true;
                return;
            }
            var barangays = locationData[editProvince.value]?.[editMunicipality.value] || [];
            populateSelect(editBarangay, barangays, 'Select Barangay');
            editBarangay.disabled = false;
        });
    }
});
</script>
@endpush

@endsection