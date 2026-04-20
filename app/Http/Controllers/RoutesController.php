<?php

namespace App\Http\Controllers;

use App\Models\Officer;
use App\Models\Admin;
use App\Models\Record;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RoutesController extends Controller
{
    public function showWelcome() {
        return view('welcome');
    }

    public function showEmailHandler(Request $request)
    {
        $isLoggedIn = $request->session()->get('email_logged_in', false);
        $records = collect();

        if ($isLoggedIn) {
            $records = Record::where('source', 'Email')
                ->orderBy('id', 'asc')
                ->get();
        }

        return view('email-handler', [
            'records' => $records,
            'isLoggedIn' => $isLoggedIn,
        ]);
    }

    public function loginEmail(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $password = $request->password;
        $correctPassword = env('EMAIL_HANDLER_PASSWORD', 'email2026'); // Default password, can be set in .env

        if ($password === $correctPassword) {
            $request->session()->put('email_logged_in', true);
            return redirect()->route('email-handler');
        } else {
            return redirect()->route('email-handler')->with('error', 'Invalid password.');
        }
    }

    public function logoutEmail(Request $request)
    {
        $request->session()->forget('email_logged_in');
        return redirect()->route('welcome');
    }

    public function showFacebookHandler(Request $request)
    {
        $isLoggedIn = $request->session()->get('facebook_logged_in', false);
        $records = collect();

        if ($isLoggedIn) {
            $records = Record::where('source', 'Facebook')
                ->orderBy('id', 'asc')
                ->get();
        }

        return view('facebook-handler', [
            'records' => $records,
            'isLoggedIn' => $isLoggedIn,
        ]);
    }

    public function loginFacebook(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $password = $request->password;
        $correctPassword = env('FACEBOOK_HANDLER_PASSWORD', 'facebook2026'); // Default password, can be set in .env

        if ($password === $correctPassword) {
            $request->session()->put('facebook_logged_in', true);
            return redirect()->route('facebook-handler');
        } else {
            return redirect()->route('facebook-handler')->with('error', 'Invalid password.');
        }
    }

    public function logoutFacebook(Request $request)
    {
        $request->session()->forget('facebook_logged_in');
        return redirect()->route('welcome');
    }

    public function showOfficerOfTheDay(Request $request)
    {
        $officerName = $request->session()->get('officer_name');
        $officerApproved = false;
        $records = collect();

        if ($officerName) {
            $officer = Officer::firstOrCreate(
                ['name' => trim($officerName)],
                ['approved' => false]
            );
            $officerApproved = $officer->approved;
            $records = Record::where('encoderName', $officerName)
                ->where('source', 'OD')
                ->orderBy('id', 'asc')
                ->get();
        }

        return view('officer-of-the-day', [
            'records' => $records,
            'officerName' => $officerName,
            'officerApproved' => $officerApproved,
        ]);
    }

    public function loginOfficer(Request $request)
    {
        $request->validate([
            'officerName' => 'required|string|max:255',
        ]);

        $officerName = trim($request->officerName);
        Officer::updateOrCreate(
            ['name' => $officerName],
            [
                'approved' => false,
                'active' => true,
            ]
        );

        $request->session()->put('officer_name', $officerName);

        return redirect()->route('officer-of-the-day')->with('success', 'Officer name submitted. Waiting for admin approval.');
    }

    public function logoutOfficer(Request $request)
    {
        $officerName = $request->session()->get('officer_name');

        if ($officerName) {
            Officer::where('name', $officerName)
                ->update([
                    'approved' => false,
                    'approved_at' => null,
                    'active' => false,
                ]);
        }

        $request->session()->forget('officer_name');

        return redirect()->route('welcome');
    }

    public function loginAdmin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $admin = Admin::where('username', $request->username)
            ->where('password', $request->password)
            ->first();

        if ($admin) {
            $request->session()->put('admin_logged_in', true);
            $request->session()->put('admin_username', $admin->username);
            return redirect()->route('admin');
        }

        return redirect()->back()->with('error', 'Invalid credentials');
    }

    public function logoutAdmin(Request $request)
    {
        $request->session()->forget('admin_logged_in');
        $request->session()->forget('admin_username');
        return redirect()->route('welcome');
    }

    public function showAdmin(Request $request)
    {
        // Check if admin is logged in
        if (!$request->session()->has('admin_logged_in') || !$request->session()->get('admin_logged_in')) {
            return redirect()->route('welcome');
        }

        $query = Record::query();

        if ($request->filled('farmerName')) {
            $query->where('farmerName', 'like', '%' . $request->farmerName . '%');
        }
        if ($request->filled('address')) {
            $query->where('address', 'like', '%' . $request->address . '%');
        }
        if ($request->filled('encoderName')) {
            $query->where('encoderName', 'like', '%' . $request->encoderName . '%');
        }
        if ($request->filled('program')) {
            $query->where('program', $request->program);
        }
        if ($request->filled('line')) {
            $query->where('line', $request->line);
        }
        if ($request->filled('province')) {
            $query->where('province', 'like', '%' . $request->province . '%');
        }
        if ($request->filled('municipality')) {
            $query->where('municipality', 'like', '%' . $request->municipality . '%');
        }
        if ($request->filled('barangay')) {
            $query->where('barangay', 'like', '%' . $request->barangay . '%');
        }
        if ($request->filled('causeOfDamage')) {
            $query->where('causeOfDamage', 'like', '%' . $request->causeOfDamage . '%');
        }
        if ($request->filled('modeOfPayment')) {
            $query->where('modeOfPayment', $request->modeOfPayment);
        }
        if ($request->filled('remarks')) {
            $query->where('remarks', 'like', '%' . $request->remarks . '%');
        }
        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }
        if ($request->filled('transmittal_number')) {
            $query->where('transmittal_number', 'like', '%' . $request->transmittal_number . '%');
        }
        if ($request->filled('admin_transmittal_number')) {
            $query->where('admin_transmittal_number', 'like', '%' . $request->admin_transmittal_number . '%');
        }
        if ($request->filled('unassigned_only')) {
            $query->whereNull('admin_transmittal_number');
        }
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Handle sorting
        $sortBy = $request->input('sort_by', 'id');
        $sortOrder = $request->input('sort_order', 'asc');
        
        // Validate sort parameters to prevent injection
        $allowedSortColumns = ['id', 'farmerName', 'province', 'municipality', 'barangay', 'program', 'line', 'causeOfDamage', 'remarks', 'source', 'transmittal_number', 'admin_transmittal_number', 'encoderName', 'approved', 'created_at'];
        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'created_at';
        }
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'asc';
        }
        
        $records = $query->orderBy($sortBy, $sortOrder)->get();

        // Dashboard stats
        $totalRecords = Record::count();
        $recordsByProgram = Record::selectRaw('program, count(*) as count')->groupBy('program')->pluck('count', 'program');
        $recentRecords = Record::where('created_at', '>=', now()->subDays(7))->count();
        $pendingOfficers = Officer::where('approved', false)->where('active', true)->orderBy('created_at')->get();
        $activeOfficers = Officer::where('active', true)->orderBy('name')->get();
        $admins = Admin::all();

        // All available programs (8 total)
        $allPrograms = [
            'RSBSA',
            'AGRI-SENSO',
            'ACEF',
            'ANYO',
            'OTHER-LI LC',
            'OTHER-LBP ACP',
            'REGULAR',
            'SELF-FINANCED'
        ];

        // All available lines (7 total)
        $allLines = [
            'rice',
            'corn',
            'high-value',
            'clti',
            'livestock',
            'non-crop',
            'fisheries'
        ];

        // All available sources
        $allSources = [
            'OD',
            'Email',
            'Facebook'
        ];

        // All available modes of payment (collect from database or use defaults)
        $allModes = Record::whereNotNull('modeOfPayment')->distinct()->pluck('modeOfPayment')->sort()->values();
        if ($allModes->isEmpty()) {
            $allModes = ['check', 'palawan'];
        }

        return view('admin', [
            'records' => $records,
            'totalRecords' => $totalRecords,
            'recordsByProgram' => $recordsByProgram,
            'recentRecords' => $recentRecords,
            'pendingOfficers' => $pendingOfficers,
            'activeOfficers' => $activeOfficers,
            'admins' => $admins,
            'allPrograms' => $allPrograms,
            'allLines' => $allLines,
            'allSources' => $allSources,
            'allModes' => $allModes,
        ]);
    }

    public function printPreview(Request $request)
    {
        // Check if admin is logged in
        if (!$request->session()->has('admin_logged_in') || !$request->session()->get('admin_logged_in')) {
            return redirect()->route('welcome');
        }

        $sessionRecordIds = $request->session()->get('admin_print_preview_record_ids', []);

        if (!empty($sessionRecordIds)) {
            $recordsQuery = Record::whereIn('id', $sessionRecordIds)
                ->orderBy('id', 'asc');
        } else {
            $query = Record::query();
            $this->applyFilters($request, $query);
            $recordsQuery = $query->orderBy('id', 'asc');
        }

        // Get all records (not paginated) - will be split in view
        $records = $recordsQuery->get();
        $totalRecords = $records->count();
        $perPage = 40;

        $encodedDate = $records->first()?->created_at?->format('Y-m-d') ?? now()->format('Y-m-d');
        if ($request->filled('date')) {
            $encodedDate = Carbon::parse($request->date)->format('Y-m-d');
        }

        // Calculate page assignments for each record (for transmittal numbering)
        $recordPageAssignments = [];
        foreach ($records as $index => $record) {
            $pageNumber = floor($index / $perPage) + 1;
            $recordPageAssignments[$record->id] = $pageNumber;
        }

        // Calculate total pages needed
        $totalPages = ceil($totalRecords / $perPage);

        // Get existing transmittal numbers per page (if records already have them)
        $pageTransmittalNumbers = [];
        foreach ($records as $index => $record) {
            $pageNum = floor($index / $perPage) + 1;
            if ($record->admin_transmittal_number) {
                $pageTransmittalNumbers[$pageNum] = $record->admin_transmittal_number;
            }
        }

        return view('admin-print-preview', [
            'records' => $records,
            'encodedDate' => $encodedDate,
            'query' => $request->query(),
            'totalPages' => $totalPages,
            'totalRecords' => $totalRecords,
            'perPage' => $perPage,
            'recordPageAssignments' => $recordPageAssignments,
            'pageTransmittalNumbers' => $pageTransmittalNumbers,
        ]);
    }

    public function addToPrintPreview(Request $request)
    {
        $query = Record::query();
        $queryParams = [];

        if ($request->filled('query')) {
            parse_str(ltrim($request->input('query'), '?'), $queryParams);
        }

        $filterRequest = new Request($queryParams);
        $this->applyFilters($filterRequest, $query);

        // Check if any of the filtered records already have transmittal numbers
        $totalFilteredRecords = $query->count();
        $recordsWithTransmittal = $query->where(function($q) {
            $q->whereNotNull('transmittal_number')->orWhereNotNull('admin_transmittal_number');
        })->count();

        if ($recordsWithTransmittal > 0) {
            return redirect()->back()->with('error', 'Cannot add records to print preview. ' . $recordsWithTransmittal . ' of the ' . $totalFilteredRecords . ' filtered records already have transmittal numbers. Please use the "Show only records without admin transmittal numbers" filter to view eligible records.');
        }

        $recordIds = $query->pluck('id')->toArray();

        // Clear existing preview and start fresh with current filtered records
        $request->session()->put('admin_print_preview_record_ids', $recordIds);

        return redirect()->back()->with('success', 'Added '.count($recordIds).' records to print preview.');
    }

    public function assignTransmittals(Request $request)
    {
        // Check if admin is logged in
        if (!$request->session()->has('admin_logged_in') || !$request->session()->get('admin_logged_in')) {
            return redirect()->route('welcome');
        }

        $sessionRecordIds = $request->session()->get('admin_print_preview_record_ids', []);
        $perPage = 40;

        if (!empty($sessionRecordIds)) {
            $recordsQuery = Record::whereIn('id', $sessionRecordIds)
                ->orderBy('id', 'asc');
        } else {
            $query = Record::query();
            $this->applyFilters($request, $query);
            $recordsQuery = $query->orderBy('id', 'asc');
        }

        $totalRecords = $recordsQuery->count();

        if ($totalRecords === 0) {
            return redirect()->back()->with('error', 'No records found for assigning admin transmittal number.');
        }

        // Get all records to process
        $allRecords = $recordsQuery->get();
        $totalPages = ceil($totalRecords / $perPage);

        // Get the next starting transmittal number
        $maxExisting = Record::whereNotNull('admin_transmittal_number')
            ->get()
            ->map(function ($record) {
                $transmittal = $record->admin_transmittal_number;
                preg_match('/(\d+)$/', $transmittal, $matches);
                return isset($matches[1]) ? (int) $matches[1] : 0;
            })
            ->max() ?? 0;

        $currentTransmittalNumber = $maxExisting + 1;
        $totalAssigned = 0;

        // Process records in batches of 40 - each batch gets a unique transmittal number
        foreach ($allRecords as $index => $record) {
            $pageNumber = floor($index / $perPage) + 1;
            $transmittalNumber = $maxExisting + $pageNumber;

            $record->update([
                'admin_transmittal_number' => (string) $transmittalNumber,
                'admin_transmittal_assigned_at' => now(),
            ]);
            $totalAssigned++;
        }

        // Clear the print preview session after assigning
        $request->session()->forget('admin_print_preview_record_ids');

        return redirect()->back()->with('success', "Admin transmittal numbers assigned successfully to {$totalAssigned} records across {$totalPages} pages.");
    }

    public function clearPrintPreview(Request $request)
    {
        $request->session()->forget('admin_print_preview_record_ids');
        return redirect()->back()->with('success', 'Print preview cleared.');
    }

    private function applyFilters(Request $request, $query)
    {
        if ($request->filled('farmerName')) {
            $query->where('farmerName', 'like', '%' . $request->farmerName . '%');
        }
        if ($request->filled('address')) {
            $query->where('address', 'like', '%' . $request->address . '%');
        }
        if ($request->filled('encoderName')) {
            $query->where('encoderName', 'like', '%' . $request->encoderName . '%');
        }
        if ($request->filled('program')) {
            $query->where('program', $request->program);
        }
        if ($request->filled('line')) {
            $query->where('line', $request->line);
        }
        if ($request->filled('province')) {
            $query->where('province', 'like', '%' . $request->province . '%');
        }
        if ($request->filled('municipality')) {
            $query->where('municipality', 'like', '%' . $request->municipality . '%');
        }
        if ($request->filled('barangay')) {
            $query->where('barangay', 'like', '%' . $request->barangay . '%');
        }
        if ($request->filled('causeOfDamage')) {
            $query->where('causeOfDamage', 'like', '%' . $request->causeOfDamage . '%');
        }
        if ($request->filled('modeOfPayment')) {
            $query->where('modeOfPayment', $request->modeOfPayment);
        }
        if ($request->filled('remarks')) {
            $query->where('remarks', 'like', '%' . $request->remarks . '%');
        }
        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }
        if ($request->filled('transmittal_number')) {
            $query->where('transmittal_number', 'like', '%' . $request->transmittal_number . '%');
        }
        if ($request->filled('admin_transmittal_number')) {
            $query->where('admin_transmittal_number', 'like', '%' . $request->admin_transmittal_number . '%');
        }
        if ($request->filled('unassigned_only')) {
            $query->whereNull('admin_transmittal_number');
        }
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }
    }

    public function bulkDelete(Request $request)
    {
        // Check if admin is logged in
        if (!$request->session()->has('admin_logged_in') || !$request->session()->get('admin_logged_in')) {
            return redirect()->route('welcome');
        }

        $ids = $request->input('record_ids', []);
        Record::whereIn('id', $ids)->delete();
        return redirect()->back()->with('success', 'Selected records deleted successfully!');
    }

    public function approveRecord($id)
    {
        $record = Record::findOrFail($id);
        $record->update([
            'approved' => true,
            'approved_at' => Carbon::now(),
        ]);

        return redirect()->back()->with('success', 'Record approved successfully.');
    }

    public function approveOfficer($id)
    {
        $officer = Officer::findOrFail($id);
        $officer->update([
            'approved' => true,
            'approved_at' => Carbon::now(),
        ]);

        return redirect()->back()->with('success', 'Officer approved successfully.');
    }

    public function exportExcel(Request $request)
    {
        // Check if admin is logged in
        if (!$request->session()->has('admin_logged_in') || !$request->session()->get('admin_logged_in')) {
            return redirect()->route('welcome');
        }

        $query = Record::query();

        // Apply same filters as showAdmin
        if ($request->filled('farmerName')) {
            $query->where('farmerName', 'like', '%' . $request->farmerName . '%');
        }
        if ($request->filled('address')) {
            $query->where('address', 'like', '%' . $request->address . '%');
        }
        if ($request->filled('encoderName')) {
            $query->where('encoderName', 'like', '%' . $request->encoderName . '%');
        }
        if ($request->filled('program')) {
            $query->where('program', $request->program);
        }
        if ($request->filled('line')) {
            $query->where('line', $request->line);
        }
        if ($request->filled('province')) {
            $query->where('province', 'like', '%' . $request->province . '%');
        }
        if ($request->filled('municipality')) {
            $query->where('municipality', 'like', '%' . $request->municipality . '%');
        }
        if ($request->filled('barangay')) {
            $query->where('barangay', 'like', '%' . $request->barangay . '%');
        }
        if ($request->filled('causeOfDamage')) {
            $query->where('causeOfDamage', 'like', '%' . $request->causeOfDamage . '%');
        }
        if ($request->filled('modeOfPayment')) {
            $query->where('modeOfPayment', $request->modeOfPayment);
        }
        if ($request->filled('remarks')) {
            $query->where('remarks', 'like', '%' . $request->remarks . '%');
        }
        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }
        if ($request->filled('transmittal_number')) {
            $query->where('transmittal_number', 'like', '%' . $request->transmittal_number . '%');
        }
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $export = new \App\Exports\RecordsExport($query->orderBy('id', 'asc'));
        $csv = $export->toCsv();

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="records.csv"');
    }

    public function exportPdf(Request $request)
    {
        // Check if admin is logged in
        if (!$request->session()->has('admin_logged_in') || !$request->session()->get('admin_logged_in')) {
            return redirect()->route('welcome');
        }

        $query = Record::query();

        // Apply same filters
        if ($request->filled('farmerName')) {
            $query->where('farmerName', 'like', '%' . $request->farmerName . '%');
        }
        if ($request->filled('address')) {
            $query->where('address', 'like', '%' . $request->address . '%');
        }
        if ($request->filled('encoderName')) {
            $query->where('encoderName', 'like', '%' . $request->encoderName . '%');
        }
        if ($request->filled('program')) {
            $query->where('program', $request->program);
        }
        if ($request->filled('line')) {
            $query->where('line', $request->line);
        }
        if ($request->filled('province')) {
            $query->where('province', 'like', '%' . $request->province . '%');
        }
        if ($request->filled('municipality')) {
            $query->where('municipality', 'like', '%' . $request->municipality . '%');
        }
        if ($request->filled('barangay')) {
            $query->where('barangay', 'like', '%' . $request->barangay . '%');
        }
        if ($request->filled('causeOfDamage')) {
            $query->where('causeOfDamage', 'like', '%' . $request->causeOfDamage . '%');
        }
        if ($request->filled('modeOfPayment')) {
            $query->where('modeOfPayment', $request->modeOfPayment);
        }
        if ($request->filled('remarks')) {
            $query->where('remarks', 'like', '%' . $request->remarks . '%');
        }
        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }
        if ($request->filled('transmittal_number')) {
            $query->where('transmittal_number', 'like', '%' . $request->transmittal_number . '%');
        }
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $records = $query->orderBy('id', 'asc')->get();
        $pdf = app('dompdf.wrapper')->loadView('pdf.records', compact('records'));
        $pdf->setPaper('a4', 'landscape');
        return $pdf->download('records.pdf');
    }

    public function updateAdmin(Request $request, $id)
    {
        $request->validate([
            'username' => 'required|string|unique:admins,username,' . $id,
            'password' => 'required|string|min:6',
        ]);

        $admin = Admin::findOrFail($id);
        $admin->update([
            'username' => $request->username,
            'password' => $request->password,
        ]);

        return redirect()->back()->with('success', 'Admin credentials updated successfully.');
    }

    public function createAdmin(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:admins',
            'password' => 'required|string|min:6',
        ]);

        Admin::create([
            'username' => $request->username,
            'password' => $request->password,
        ]);

        return redirect()->back()->with('success', 'Admin user created successfully.');
    }

    public function submitTransmittal(Request $request)
    {
        $source = $request->input('source');
        $conditions = ['source' => null, 'encoderName' => null];

        if (!in_array($source, ['OD', 'Email', 'Facebook'], true)) {
            $source = null;
        }

        if (!$source) {
            if ($request->session()->has('officer_name')) {
                $source = 'OD';
                $conditions['source'] = 'OD';
                $conditions['encoderName'] = $request->session()->get('officer_name');
            } elseif ($request->session()->has('email_logged_in') && $request->session()->get('email_logged_in')) {
                $source = 'Email';
                $conditions['source'] = 'Email';
            } elseif ($request->session()->has('facebook_logged_in') && $request->session()->get('facebook_logged_in')) {
                $source = 'Facebook';
                $conditions['source'] = 'Facebook';
            }
        } else {
            $conditions['source'] = $source;
        }

        if ($source === 'OD' && !$request->session()->has('officer_name')) {
            return redirect()->back()->with('error', 'Please log in as Officer of the Day first.');
        }

        if ($source === 'Email' && !$request->session()->has('email_logged_in')) {
            return redirect()->back()->with('error', 'Please log in to Email handler first.');
        }

        if ($source === 'Facebook' && !$request->session()->has('facebook_logged_in')) {
            return redirect()->back()->with('error', 'Please log in to Facebook handler first.');
        }

        if (!$source) {
            return redirect()->back()->with('error', 'You must be logged in to submit a transmittal.');
        }

        if ($source === 'OD' && $request->session()->has('officer_name')) {
            $conditions['encoderName'] = $request->session()->get('officer_name');
        }

        // Get records without transmittal_number
        $query = Record::where('source', $conditions['source'])
            ->whereNull('transmittal_number');

        if ($source === 'OD' && $conditions['encoderName']) {
            $query->where('encoderName', $conditions['encoderName']);
        }

        $recordsToSubmit = $query->get();

        if ($recordsToSubmit->isEmpty()) {
            return redirect()->back()->with('info', 'No pending records to submit.');
        }

        // Generate transmittal number: yyyy-mmdd-PNNN where P is prefix (F/E/none)
        $today = now()->format('Y-md');
        
        // Determine prefix based on source
        $prefix = '';
        if ($source === 'Facebook') {
            $prefix = 'F';
        } elseif ($source === 'Email') {
            $prefix = 'E';
        }
        // OD has no prefix
        
        // Find the latest transmittal for today and this source
        $searchPattern = $today . '-' . ($prefix ? $prefix : '') . '%';
        $latestTransmittal = Record::where('source', $source)
            ->whereNotNull('transmittal_number')
            ->where('transmittal_number', 'like', $searchPattern);

        if ($source === 'OD') {
            $latestTransmittal = $latestTransmittal->where('encoderName', $conditions['encoderName']);
        }

        $latestNumber = 0;
        $latest = $latestTransmittal->first();

        if ($latest) {
            // Extract number from format like "2026-0420-001", "2026-0420-F001", or "2026-0420-E001"
            $parts = explode('-', $latest->transmittal_number);
            if (count($parts) === 3) {
                $lastPart = $parts[2];
                // Remove prefix if present and get the numeric part
                if ($prefix && str_starts_with($lastPart, $prefix)) {
                    $numericPart = substr($lastPart, 1);
                } elseif (!$prefix) {
                    $numericPart = $lastPart;
                } else {
                    $numericPart = '0';
                }
                $latestNumber = (int)$numericPart;
            }
        }

        $nextNumber = str_pad($latestNumber + 1, 3, '0', STR_PAD_LEFT);
        $transmittalNumber = $today . '-' . $prefix . $nextNumber;

        // Update all records with the new transmittal number
        Record::whereIn('id', $recordsToSubmit->pluck('id'))->update([
            'transmittal_number' => $transmittalNumber,
        ]);

        $count = $recordsToSubmit->count();
        return redirect()->back()->with('success', "Transmittal $transmittalNumber created successfully with $count records.");
    }
}
