<?php

namespace App\Http\Controllers;

use App\Models\Officer;
use App\Models\EmailHandler;
use App\Models\Admin;
use App\Models\Record;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RoutesController extends Controller
{
    public function showWelcome() {
        return view('welcome');
    }

    public function showEmailHandler(Request $request)
    {
        $emailUserName = $request->session()->get('email_user_name');
        $emailUserApproved = false;
        $records = collect();

        if ($emailUserName) {
            $handler = EmailHandler::where('name', $emailUserName)->first();
            $emailUserApproved = $handler && $handler->approved;

            if ($emailUserApproved) {
                $query = Record::where('source', 'Email')
                    ->where('encoderName', $emailUserName);
                
                // Apply date filter if provided
                if ($request->filled('date_encoded')) {
                    $query->whereDate('created_at', $request->date_encoded);
                }
                
                $records = $query->orderBy('id', 'desc')
                    ->paginate(25)
                    ->withQueryString();
            }
        }

        return view('email-handler', [
            'records' => $records,
            'isLoggedIn' => (bool) $emailUserName,
            'emailUserName' => $emailUserName,
            'emailUserApproved' => $emailUserApproved,
        ]);
    }

    public function loginEmail(Request $request)
    {
        $request->validate([
            'email_user' => 'required|string|in:juvielyn,hanna,other',
            'email_user_other' => 'nullable|string|max:255',
        ]);

        $presetNames = [
            'juvielyn' => 'Juvielyn Fiesta',
            'hanna' => 'Hanna Marie Lorica',
        ];

        $name = $presetNames[$request->input('email_user')] ?? null;
        if ($request->input('email_user') === 'other') {
            $name = trim((string) $request->input('email_user_other', ''));
            if ($name === '') {
                return redirect()->route('email-handler')->with('error', 'Please enter your name when selecting Other.');
            }
        }

        EmailHandler::updateOrCreate(
            ['name' => $name],
            [
                'approved' => false,
                'approved_at' => null,
                'active' => true,
            ]
        );

        $request->session()->put('email_user_name', $name);
        $request->session()->put('email_logged_in', true);

        return redirect()->route('email-handler')->with('success', 'You are signed in. An admin must approve your account before you can add records.');
    }

    public function logoutEmail(Request $request)
    {
        $emailUserName = $request->session()->get('email_user_name');

        if ($emailUserName) {
            EmailHandler::where('name', $emailUserName)->update([
                'approved' => false,
                'approved_at' => null,
                'active' => false,
            ]);
        }

        $request->session()->forget('email_logged_in');
        $request->session()->forget('email_user_name');

        return redirect()->route('welcome');
    }

    public function showFacebookHandler(Request $request)
    {
        $isLoggedIn = $request->session()->get('facebook_logged_in', false);
        $records = collect();

        if ($isLoggedIn) {
            $records = Record::where('source', 'Facebook')
                ->whereDate('created_at', today())
                ->orderBy('id', 'desc')
                ->paginate(25)
                ->withQueryString();
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
            
            $query = Record::where('encoderName', $officerName)
                ->where('source', 'OD');
            
            // Apply date filter if provided
            if ($request->filled('date_encoded')) {
                $query->whereDate('created_at', $request->date_encoded);
            }
            
            $records = $query->orderBy('id', 'desc')
                ->paginate(25)
                ->withQueryString();
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

    public function exportOfficerCsv(Request $request)
    {
        $officerName = $request->session()->get('officer_name');
        if (!$officerName) { return redirect()->route('officer-of-the-day'); }

        $query = Record::where('encoderName', $officerName)
            ->where('source', 'OD')
            ->whereDate('created_at', today())
            ->orderBy('id', 'asc');

        $export = new \App\Exports\RecordsExport($query);
        return response($export->toCsv())
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="od-records-' . date('Y-m-d') . '.csv"');
    }

    public function exportEmailCsv(Request $request)
    {
        $emailUserName = $request->session()->get('email_user_name');
        if (!$emailUserName) { return redirect()->route('email-handler'); }

        $query = Record::where('source', 'Email')
            ->where('encoderName', $emailUserName)
            ->whereDate('created_at', today())
            ->orderBy('id', 'asc');

        $export = new \App\Exports\RecordsExport($query);
        return response($export->toCsv())
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="email-records-' . date('Y-m-d') . '.csv"');
    }

    public function exportFacebookCsv(Request $request)
    {
        if (!$request->session()->get('facebook_logged_in')) { return redirect()->route('facebook-handler'); }

        $query = Record::where('source', 'Facebook')
            ->whereDate('created_at', today())
            ->orderBy('id', 'asc');

        $export = new \App\Exports\RecordsExport($query);
        return response($export->toCsv())
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="facebook-records-' . date('Y-m-d') . '.csv"');
    }

    public function loginAdmin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $admin = Admin::where('username', $request->username)->first();
        
        if ($admin && !Hash::needsRehash($admin->password)) {
            // Modern hashed password
            if (!Hash::check($request->password, $admin->password)) {
                $admin = null;
            }
        } elseif ($admin) {
            // Legacy plain text password - automatically upgrade to hash
            if ($admin->password === $request->password) {
                $admin->update([
                    'password' => Hash::make($request->password)
                ]);
            } else {
                $admin = null;
            }
        }

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
        if ($request->filled('date_occurrence')) {
            $query->where('date_occurrence', 'like', '%' . $request->date_occurrence . '%');
        }
        if ($request->filled('accounts')) {
            $query->where('accounts', 'like', '%' . $request->accounts . '%');
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
        if ($request->filled('date_encoded')) {
            $query->whereDate('created_at', $request->date_encoded);
        }
        
        // Date filters
        if ($request->filled('date_single')) {
            $query->whereDate('created_at', $request->date_single);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('date_month')) {
            $query->whereMonth('created_at', $request->date_month)
                  ->whereYear('created_at', $request->filled('date_year') ? $request->date_year : now()->year);
        }

        // Handle sorting
        $sortBy = $request->input('sort_by', 'id');
        $sortOrder = $request->input('sort_order', 'desc');
        $perPage = (int) $request->input('per_page', 50);
        if (!in_array($perPage, [25, 50, 100], true)) {
            $perPage = 50;
        }
        
        // Validate sort parameters to prevent injection
        $allowedSortColumns = ['id', 'farmerName', 'province', 'municipality', 'barangay', 'program', 'line', 'causeOfDamage', 'modeOfPayment', 'date_occurrence', 'remarks', 'accounts', 'source', 'transmittal_number', 'admin_transmittal_number', 'encoderName', 'approved', 'created_at'];
        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'id';
        }
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }
        
        $records = $query->orderBy($sortBy, $sortOrder)->paginate($perPage)->withQueryString();

        // Dashboard stats - use ONLY dashboard filter parameters (dash_ prefix)
        $statsQuery = Record::query();
        
        // Apply DASHBOARD ONLY filters (do NOT affect main records table)
        if ($request->filled('dash_program')) {
            $statsQuery->where('program', $request->dash_program);
        }
        // Note: dash_line filter is NOT applied to statsQuery so that the By Line card shows all lines
        if ($request->filled('dash_province')) {
            $statsQuery->where('province', 'like', '%' . $request->dash_province . '%');
        }
        if ($request->filled('dash_municipality')) {
            $statsQuery->where('municipality', 'like', '%' . $request->dash_municipality . '%');
        }
        if ($request->filled('dash_barangay')) {
            $statsQuery->where('barangay', 'like', '%' . $request->dash_barangay . '%');
        }
        if ($request->filled('dash_source')) {
            $sources = $request->input('dash_source');
            if (is_array($sources) && count($sources) > 0) {
                $statsQuery->whereIn('source', $sources);
            } elseif (is_string($sources) && !empty($sources)) {
                $statsQuery->where('source', $sources);
            }
        }
        if ($request->filled('dash_date_type')) {
            $dateType = $request->dash_date_type;
            if ($dateType === 'single' && $request->filled('dash_date_single')) {
                $statsQuery->whereDate('created_at', $request->dash_date_single);
            } elseif ($dateType === 'range') {
                if ($request->filled('dash_date_from')) {
                    $statsQuery->whereDate('created_at', '>=', $request->dash_date_from);
                }
                if ($request->filled('dash_date_to')) {
                    $statsQuery->whereDate('created_at', '<=', $request->dash_date_to);
                }
            } elseif ($dateType === 'month' && $request->filled('dash_date_month')) {
                $statsQuery->whereMonth('created_at', substr($request->dash_date_month, 5, 2))
                      ->whereYear('created_at', substr($request->dash_date_month, 0, 4));
            }
        }

        // Dashboard (new) - only date + program + line filters
        $dashQuery = Record::query();
        if ($request->filled('dash_program')) {
            $dashQuery->where('program', $request->dash_program);
        }
        if ($request->filled('dash_line')) {
            $dashQuery->where('line', $request->dash_line);
        }
        if ($request->filled('dash_date_type')) {
            $dashDateType = $request->dash_date_type;
            if ($dashDateType === 'single' && $request->filled('dash_date_single')) {
                $dashQuery->whereDate('created_at', $request->dash_date_single);
            } elseif ($dashDateType === 'range') {
                if ($request->filled('dash_date_from')) {
                    $dashQuery->whereDate('created_at', '>=', $request->dash_date_from);
                }
                if ($request->filled('dash_date_to')) {
                    $dashQuery->whereDate('created_at', '<=', $request->dash_date_to);
                }
            }
        }

        $dashProvinces = ['Nueva Ecija', 'Aurora'];

        $dashMunicipalitiesByProvince = Record::query()
            ->select('province', 'municipality')
            ->whereIn('province', $dashProvinces)
            ->whereNotNull('municipality')
            ->where('municipality', '!=', '')
            ->distinct()
            ->orderBy('municipality', 'asc')
            ->get()
            ->groupBy('province')
            ->map(function ($rows) {
                return $rows->pluck('municipality')->values()->all();
            })
            ->all();

        $dashMunicipalityRows = (clone $dashQuery)
            ->selectRaw('province, municipality, count(*) as count')
            ->whereIn('province', $dashProvinces)
            ->whereNotNull('municipality')
            ->where('municipality', '!=', '')
            ->groupBy('province', 'municipality')
            ->orderByRaw('count(*) desc')
            ->get();

        $dashCountMap = [];
        foreach ($dashMunicipalityRows as $row) {
            $dashCountMap[$row->province] ??= [];
            $dashCountMap[$row->province][$row->municipality] = (int) $row->count;
        }

        $dashCountsByProvince = [
            'Nueva Ecija' => [],
            'Aurora' => [],
        ];
        foreach ($dashProvinces as $province) {
            $municipalities = $dashMunicipalitiesByProvince[$province] ?? [];
            foreach ($municipalities as $municipality) {
                $dashCountsByProvince[$province][] = [
                    'municipality' => $municipality,
                    'count' => $dashCountMap[$province][$municipality] ?? 0,
                ];
            }
            // Sort each province's municipalities by count in descending order
            usort($dashCountsByProvince[$province], function($a, $b) {
                return $b['count'] - $a['count'];
            });
        }

        $dashBarangayRows = (clone $dashQuery)
            ->selectRaw('province, municipality, barangay, count(*) as count')
            ->whereIn('province', $dashProvinces)
            ->whereNotNull('municipality')
            ->where('municipality', '!=', '')
            ->whereNotNull('barangay')
            ->where('barangay', '!=', '')
            ->groupBy('province', 'municipality', 'barangay')
            ->orderByRaw('count(*) desc')
            ->get();

        $dashBarangayBreakdown = [];
        foreach ($dashBarangayRows as $row) {
            $dashBarangayBreakdown[$row->province] ??= [];
            $dashBarangayBreakdown[$row->province][$row->municipality] ??= [];
            $dashBarangayBreakdown[$row->province][$row->municipality][] = [
                'barangay' => $row->barangay,
                'count' => (int) $row->count,
            ];
        }

        // Total records should be unfiltered to show accurate count
        $totalRecords = Record::count();
        $recordsByProgram = $statsQuery->selectRaw('program, count(*) as count')->groupBy('program')->orderByRaw('count(*) desc')->pluck('count', 'program');
        $recordsByLine = $statsQuery->selectRaw('line, count(*) as count')->groupBy('line')->orderByRaw('count(*) desc')->pluck('count', 'line');
        
        // Source counts should be unfiltered to show total records by source
        $recordsBySource = Record::selectRaw('source, count(*) as count')->groupBy('source')->orderByRaw('count(*) desc')->pluck('count', 'source');
        $recordsByMunicipality = $statsQuery
            ->selectRaw('municipality, count(*) as count')
            ->whereNotNull('municipality')
            ->where('municipality', '!=', '')
            ->groupBy('municipality')
            ->orderByRaw('count(*) desc')
            ->pluck('count', 'municipality');

        $municipalityProgramCounts = (clone $statsQuery)
            ->selectRaw('municipality, program, count(*) as count')
            ->whereNotNull('municipality')
            ->where('municipality', '!=', '')
            ->whereNotNull('program')
            ->where('program', '!=', '')
            ->groupBy('municipality', 'program')
            ->get()
            ->groupBy('municipality');
        $recentRecords = (clone $statsQuery)->where('created_at', '>=', now()->subDays(7))->count();
        
        // Unfiltered stats for reference
        $pendingOfficers = Officer::where('approved', false)->where('active', true)->orderBy('created_at')->get();
        $pendingEmailHandlers = EmailHandler::where('approved', false)->where('active', true)->orderBy('created_at')->get();
        $activeOfficers = Officer::where('active', true)->orderBy('name')->get();
        $admins = Admin::all();

        // All available programs (9 total)
        $allPrograms = [
            'RSBSA',
            'AGRI-SENSO',
            'ACEF',
            'ANYO',
            'OTHER-LI LC',
            'OTHER-LBP ACP',
            'REGULAR',
            'SELF-FINANCED',
            'CFITF'
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

        // All available provinces - Hardcoded to match modal options exactly
        $allProvinces = [
            'Aurora',
            'Nueva Ecija'
        ];

        // All available provinces, municipalities, barangays
        $allMunicipalities = Record::distinct()->pluck('municipality')->filter()->sort()->values();
        $allBarangays = Record::distinct()->pluck('barangay')->filter()->sort()->values();

        // All available modes of payment - Hardcoded to match modal options exactly
        $allModes = [
            'check',
            'palawan',
            'gcash',
            'not_indicated'
        ];

        return view('admin', [
            'records' => $records,
            'totalRecords' => $totalRecords,
            'recordsByProgram' => $recordsByProgram,
            'recordsByLine' => $recordsByLine,
            'recordsBySource' => $recordsBySource,
            'recordsByMunicipality' => $recordsByMunicipality,
            'municipalityProgramCounts' => $municipalityProgramCounts,
            'dashCountsByProvince' => $dashCountsByProvince,
            'dashBarangayBreakdown' => $dashBarangayBreakdown,
            'recentRecords' => $recentRecords,
            'pendingOfficers' => $pendingOfficers,
            'pendingEmailHandlers' => $pendingEmailHandlers,
            'activeOfficers' => $activeOfficers,
            'admins' => $admins,
            'allPrograms' => $allPrograms,
            'allLines' => $allLines,
            'allSources' => $allSources,
            'allModes' => $allModes,
            'allProvinces' => $allProvinces,
            'allMunicipalities' => $allMunicipalities,
            'allBarangays' => $allBarangays,
        ]);
    }

    public function printPreview(Request $request)
    {
        // Check if admin is logged in
        if (!$request->session()->has('admin_logged_in') || !$request->session()->get('admin_logged_in')) {
            return redirect()->route('welcome');
        }

        $sessionRecordIds = $request->session()->get('admin_print_preview_record_ids', []);

        // ALSO support ids from URL query parameter
        if ($request->filled('ids')) {
            $urlIds = explode(',', $request->input('ids'));
            $urlIds = array_filter(array_map('intval', $urlIds));
            if (!empty($urlIds)) {
                $sessionRecordIds = $urlIds;
            }
        }

        if (!empty($sessionRecordIds)) {
            $recordsQuery = Record::whereIn('id', $sessionRecordIds)
                ->orderBy('id', 'desc');
        } else {
            $query = Record::query();
            $this->applyFilters($request, $query);
            $recordsQuery = $query->orderBy('id', 'desc');
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

        // If a page has no assigned number yet, show the next numbers that would be assigned.
        // This keeps numbering consistent across pages (40 records per page).
        if ($totalPages > 0) {
            $maxExisting = Record::whereNotNull('admin_transmittal_number')
                ->get()
                ->map(function ($record) {
                    $transmittal = $record->admin_transmittal_number;
                    preg_match('/(\d+)$/', $transmittal, $matches);
                    return isset($matches[1]) ? (int) $matches[1] : 0;
                })
                ->max() ?? 0;

            for ($p = 1; $p <= $totalPages; $p++) {
                if (!isset($pageTransmittalNumbers[$p]) || $pageTransmittalNumbers[$p] === null || $pageTransmittalNumbers[$p] === '') {
                    $pageTransmittalNumbers[$p] = (string) ($maxExisting + $p);
                }
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
        $recordIds = [];
        
        if ($request->filled('record_ids')) {
            $recordIds = json_decode($request->input('record_ids'), true);
        } else {
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
        }
        
        if (!is_array($recordIds)) {
            $recordIds = [];
        }

        // Clear existing preview and start fresh with selected records
        $request->session()->put('admin_print_preview_record_ids', $recordIds);

        return redirect()->route('admin.print-preview')->with('success', 'Added '.count($recordIds).' records to print preview.');
    }

    public function exportPreviewCsv(Request $request)
    {
        // Check if admin is logged in
        if (!$request->session()->has('admin_logged_in') || !$request->session()->get('admin_logged_in')) {
            return redirect()->route('welcome');
        }

        $sessionRecordIds = $request->session()->get('admin_print_preview_record_ids', []);

        // ALSO support ids from URL query parameter
        if ($request->filled('ids')) {
            $urlIds = explode(',', $request->input('ids'));
            $urlIds = array_filter(array_map('intval', $urlIds));
            if (!empty($urlIds)) {
                $sessionRecordIds = $urlIds;
            }
        }

        if (!empty($sessionRecordIds)) {
            $recordsQuery = Record::whereIn('id', $sessionRecordIds)->orderBy('id', 'desc');
        } else {
            $query = Record::query();
            $this->applyFilters($request, $query);
            $recordsQuery = $query->orderBy('id', 'desc');
        }

        // Get all records with all columns
        $records = $recordsQuery->get();

        // Get all column names from the records model
        $columns = array_keys($records->first()?->getAttributes() ?? []);

        // Generate CSV headers
        $csv = fopen('php://temp', 'r+');
        fputcsv($csv, $columns);

        // Add data rows
        foreach ($records as $record) {
            $row = [];
            foreach ($columns as $column) {
                $value = $record->$column;
                // Handle null values and format dates
                if ($value === null) {
                    $row[] = '';
                } elseif ($value instanceof \Carbon\Carbon) {
                    $row[] = $value->format('Y-m-d H:i:s');
                } else {
                    $row[] = (string)$value;
                }
            }
            fputcsv($csv, $row);
        }

        rewind($csv);
        $csvContent = stream_get_contents($csv);
        fclose($csv);

        $filename = 'transmittal_export_' . date('Y-m-d_H-i-s') . '.csv';

        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function assignTransmittals(Request $request)
    {
        // Check if admin is logged in
        if (!$request->session()->has('admin_logged_in') || !$request->session()->get('admin_logged_in')) {
            return redirect()->route('welcome');
        }

        $perPage = 40;

        // Get record IDs from URL parameter
        $urlIds = $request->input('ids', '');
        $recordIds = [];
        
        if (!empty($urlIds)) {
            // Parse comma-separated IDs from URL
            $recordIds = explode(',', $urlIds);
            // Convert to integers and filter out invalid values
            $recordIds = array_filter(array_map('intval', $recordIds));
        }

        if (!empty($recordIds)) {
            // Use records from URL parameter
            $recordsQuery = Record::whereIn('id', $recordIds)
                ->orderBy('id', 'desc');
        } else {
            // If no URL IDs, return error
            return response()->json([
                'success' => false,
                'message' => 'No record IDs found in URL. Please provide valid record IDs.'
            ]);
        }

        $totalRecords = $recordsQuery->count();

        if ($totalRecords === 0) {
            return response()->json([
                'success' => false,
                'message' => 'No records found for assigning admin transmittal number.'
            ]);
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

        return response()->json([
            'success' => true,
            'message' => "Admin transmittal numbers assigned successfully to {$totalAssigned} records across {$totalPages} pages."
        ]);
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
        
        // Date occurrence filtering
        if ($request->filled('date_occurrence_filter_type') && $request->date_occurrence_filter_type === 'single') {
            if ($request->filled('date_occurrence')) {
                $query->where('date_occurrence', $request->date_occurrence);
            }
        } elseif ($request->filled('date_occurrence_filter_type') && $request->date_occurrence_filter_type === 'range') {
            if ($request->filled('date_occurrence_from')) {
                $query->where('date_occurrence', '>=', $request->date_occurrence_from);
            }
            if ($request->filled('date_occurrence_to')) {
                $query->where('date_occurrence', '<=', $request->date_occurrence_to);
            }
        }
        
        // Date received filtering
        if ($request->filled('date_received_filter_type') && $request->date_received_filter_type === 'single') {
            if ($request->filled('date_received')) {
                $query->where('date_received', $request->date_received);
            }
        } elseif ($request->filled('date_received_filter_type') && $request->date_received_filter_type === 'range') {
            if ($request->filled('date_received_from')) {
                $query->where('date_received', '>=', $request->date_received_from);
            }
            if ($request->filled('date_received_to')) {
                $query->where('date_received', '<=', $request->date_received_to);
            }
        }
        
        if ($request->filled('accounts')) {
            $query->where('accounts', 'like', '%' . $request->accounts . '%');
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
        if ($request->filled('date_encoded')) {
            $query->whereDate('created_at', $request->date_encoded);
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

        $recordIdsInput = $request->input('record_ids', []);
        $ids = is_array($recordIdsInput)
            ? $recordIdsInput
            : array_filter(array_map('intval', explode(',', (string) $recordIdsInput)));

        $request->merge(['record_ids' => $ids]);
        $request->validate([
            'record_ids' => 'required|array|max:100',
            'record_ids.*' => 'integer|exists:records,id'
        ]);

        $count = Record::whereIn('id', $ids)->delete();
        
        return redirect()->back()->with('success', "{$count} records deleted successfully!");
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

    public function approveEmailHandler($id)
    {
        $handler = EmailHandler::findOrFail($id);
        $handler->update([
            'approved' => true,
            'approved_at' => Carbon::now(),
        ]);

        return redirect()->back()->with('success', 'Email handler approved successfully.');
    }

    public function exportExcel(Request $request)
    {
        // Check if admin is logged in
        if (!$request->session()->has('admin_logged_in') || !$request->session()->get('admin_logged_in')) {
            return redirect()->route('welcome');
        }

        $query = Record::query();
        $this->applyFilters($request, $query);
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
        $this->applyFilters($request, $query);
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
            'password' => Hash::make($request->password),
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
            'password' => Hash::make($request->password),
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

        if ($source === 'Email' && (!$request->session()->has('email_logged_in') || !$request->session()->has('email_user_name'))) {
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

        if ($source === 'Email' && $request->session()->has('email_user_name')) {
            $query->where('encoderName', $request->session()->get('email_user_name'));
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
        
        // Check for custom suffix from request (Officer of the Day only)
        if ($source === 'OD' && $request->filled('custom_transmittal_suffix')) {
            $nextNumber = $request->input('custom_transmittal_suffix');
            $nextNumber = str_pad(substr($nextNumber, 0, 3), 3, '0', STR_PAD_LEFT);
        } else {
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
        }
        $transmittalNumber = $today . '-' . $prefix . $nextNumber;

        // Update all records with the new transmittal number
        Record::whereIn('id', $recordsToSubmit->pluck('id'))->update([
            'transmittal_number' => $transmittalNumber,
        ]);

        $count = $recordsToSubmit->count();
        return redirect()->back()->with('success', "Transmittal $transmittalNumber created successfully with $count records.");
    }

    public function pendingApprovalsApi(Request $request)
    {
        if (!$request->session()->has('admin_logged_in') || !$request->session()->get('admin_logged_in')) {
            return response()->json(['error' => 'unauthorized'], 401);
        }

        $pendingOfficers = Officer::where('approved', false)
            ->where('active', true)
            ->orderBy('created_at', 'desc')
            ->get(['id', 'name', 'created_at']);

        $pendingEmailHandlers = EmailHandler::where('approved', false)
            ->where('active', true)
            ->orderBy('created_at', 'desc')
            ->get(['id', 'name', 'created_at']);

        return response()->json([
            'officers' => $pendingOfficers,
            'emailHandlers' => $pendingEmailHandlers,
            'officerCount' => $pendingOfficers->count(),
            'emailHandlerCount' => $pendingEmailHandlers->count(),
            'totalPending' => $pendingOfficers->count() + $pendingEmailHandlers->count(),
        ]);
    }

    public function getActiveUsers(Request $request)
    {
        try {
            if (!$request->session()->has('admin_logged_in') || !$request->session()->get('admin_logged_in')) {
                return response()->json(['error' => 'unauthorized'], 401);
            }

            $activeUsers = [];
            $now = Carbon::now();

            // Get active admin users
            try {
                $admins = Admin::where('active', true)->get();
                foreach ($admins as $admin) {
                    $lastActivity = $this->getUserLastActivity($admin->id, 'admin');
                    $status = $this->getUserStatus($lastActivity, $now);
                    
                    if ($status !== 'away') {
                        $activeUsers[] = [
                            'id' => $admin->id,
                            'name' => $admin->username ?? 'Admin User',
                            'email' => ($admin->username ?? 'admin') . '@admin.com',
                            'channel' => 'Admin',
                            'last_activity' => $lastActivity,
                            'status' => $status
                        ];
                    }
                }
            } catch (\Exception $e) {
                // Log error but continue
                \Log::error('Error getting admin users: ' . $e->getMessage());
            }

            // Get active Facebook users (logged in)
            if ($request->session()->has('facebook_logged_in') && $request->session()->get('facebook_logged_in')) {
                $activeUsers[] = [
                    'id' => 'facebook_session',
                    'name' => 'Facebook Handler',
                    'email' => 'facebook@handler.com',
                    'channel' => 'Facebook',
                    'last_activity' => $request->session()->get('facebook_last_activity', $now),
                    'status' => 'active'
                ];
            }

            // Get active Email users (logged in)
            if ($request->session()->has('email_logged_in') && $request->session()->get('email_logged_in')) {
                $activeUsers[] = [
                    'id' => 'email_session',
                    'name' => 'Email Handler',
                    'email' => 'email@handler.com',
                    'channel' => 'Email',
                    'last_activity' => $request->session()->get('email_last_activity', $now),
                    'status' => 'active'
                ];
            }

            // Get active Officer of the Day users (logged in)
            if ($request->session()->has('officer_logged_in') && $request->session()->get('officer_logged_in')) {
                $activeUsers[] = [
                    'id' => 'officer_session',
                    'name' => 'Officer of the Day',
                    'email' => 'officer@handler.com',
                    'channel' => 'Officer of the Day',
                    'last_activity' => $request->session()->get('officer_last_activity', $now),
                    'status' => 'active'
                ];
            }

            // Get recently approved officers and email handlers
            try {
                $recentOfficers = Officer::where('approved', true)
                    ->where('active', true)
                    ->where('updated_at', '>=', $now->subMinutes(30))
                    ->get();

                foreach ($recentOfficers as $officer) {
                    $lastActivity = $this->getUserLastActivity($officer->id, 'officer');
                    $status = $this->getUserStatus($lastActivity, $now);
                    
                    if ($status !== 'away') {
                        $activeUsers[] = [
                            'id' => $officer->id,
                            'name' => $officer->name ?? 'Officer',
                            'email' => 'officer@example.com',
                            'channel' => 'Officer of the Day',
                            'last_activity' => $lastActivity,
                            'status' => $status
                        ];
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Error getting officers: ' . $e->getMessage());
            }

            try {
                $recentEmailHandlers = EmailHandler::where('approved', true)
                    ->where('active', true)
                    ->where('updated_at', '>=', $now->subMinutes(30))
                    ->get();

                foreach ($recentEmailHandlers as $emailHandler) {
                    $lastActivity = $this->getUserLastActivity($emailHandler->id, 'email');
                    $status = $this->getUserStatus($lastActivity, $now);
                    
                    if ($status !== 'away') {
                        $activeUsers[] = [
                            'id' => $emailHandler->id,
                            'name' => $emailHandler->name ?? 'Email Handler',
                            'email' => 'email@example.com',
                            'channel' => 'Email',
                            'last_activity' => $lastActivity,
                            'status' => $status
                        ];
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Error getting email handlers: ' . $e->getMessage());
            }

            // Sort by last activity (most recent first)
            usort($activeUsers, function ($a, $b) {
                return strtotime($b['last_activity']) - strtotime($a['last_activity']);
            });

            return response()->json([
                'success' => true,
                'activeUsers' => $activeUsers,
                'totalActive' => count($activeUsers)
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in getActiveUsers: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to load active users',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function getUserLastActivity($userId, $type)
    {
        // For now, return recent activity. In a real implementation,
        // you would track this in a separate activity log table
        return Carbon::now()->subMinutes(rand(1, 30));
    }

    private function getUserStatus($lastActivity, $now)
    {
        $diffInMinutes = $now->diffInMinutes($lastActivity);

        if ($diffInMinutes < 5) {
            return 'online';
        } elseif ($diffInMinutes < 15) {
            return 'active';
        } elseif ($diffInMinutes < 30) {
            return 'idle';
        } else {
            return 'away';
        }
    }
}
