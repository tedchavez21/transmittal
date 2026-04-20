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
                ->orderBy('created_at', 'desc')
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
                ->orderBy('created_at', 'desc')
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
                ->orderBy('created_at', 'desc')
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
            return redirect()->route('admin');
        }

        return redirect()->back()->with('error', 'Invalid credentials');
    }

    public function showAdmin(Request $request)
    {
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
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Handle sorting
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'asc');
        
        // Validate sort parameters to prevent injection
        $allowedSortColumns = ['id', 'farmerName', 'province', 'municipality', 'barangay', 'program', 'line', 'causeOfDamage', 'remarks', 'source', 'transmittal_number', 'encoderName', 'approved', 'created_at'];
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
        $query = Record::query();
        $this->applyFilters($request, $query);

        $records = $query->orderBy('created_at')->get();

        $encodedDate = $records->first()?->created_at?->format('Y-m-d') ?? now()->format('Y-m-d');
        if ($request->filled('date')) {
            $encodedDate = Carbon::parse($request->date)->format('Y-m-d');
        }

        $transmittalNumber = null;
        if ($records->isNotEmpty()) {
            $uniqueNumbers = $records->pluck('transmittal_number')->filter()->unique();
            if ($uniqueNumbers->count() === 1) {
                $transmittalNumber = $uniqueNumbers->first();
            }
        }

        return view('admin-print-preview', [
            'records' => $records,
            'encodedDate' => $encodedDate,
            'transmittalNumber' => $transmittalNumber,
            'query' => $request->query(),
        ]);
    }

    public function assignTransmittals(Request $request)
    {
        $query = Record::query();
        $this->applyFilters($request, $query);

        $records = $query->orderBy('created_at')->get();

        if ($records->isEmpty()) {
            return redirect()->back()->with('error', 'No records found for assigning transmittal number.');
        }

        $maxExisting = Record::whereNotNull('transmittal_number')
            ->get()
            ->map(function ($record) {
                $transmittal = $record->transmittal_number;
                preg_match('/(\d+)$/', $transmittal, $matches);
                return isset($matches[1]) ? (int) $matches[1] : 0;
            })
            ->max();

        $nextTransmittal = $maxExisting + 1;
        $formattedNumber = (string) $nextTransmittal;

        foreach ($records as $record) {
            $record->update(['transmittal_number' => $formattedNumber]);
        }

        return redirect()->back()->with('success', 'Transmittal number assigned successfully.');
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
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
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

        $export = new \App\Exports\RecordsExport($query->orderBy('encoderName')->orderBy('created_at'));
        $csv = $export->toCsv();

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="records.csv"');
    }

    public function exportPdf(Request $request)
    {
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

        $records = $query->orderBy('encoderName')->orderBy('created_at')->get();
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
}
