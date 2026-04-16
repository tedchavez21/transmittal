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
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Handle sorting
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'asc');
        
        // Validate sort parameters to prevent injection
        $allowedSortColumns = ['id', 'farmerName', 'province', 'municipality', 'barangay', 'program', 'line', 'causeOfDamage', 'remarks', 'encoderName', 'approved', 'created_at'];
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

        return view('admin', [
            'records' => $records,
            'totalRecords' => $totalRecords,
            'recordsByProgram' => $recordsByProgram,
            'recentRecords' => $recentRecords,
            'pendingOfficers' => $pendingOfficers,
            'activeOfficers' => $activeOfficers,
            'admins' => $admins,
        ]);
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
        if ($request->filled('causeOfDamage')) {
            $query->where('causeOfDamage', 'like', '%' . $request->causeOfDamage . '%');
        }
        if ($request->filled('modeOfPayment')) {
            $query->where('modeOfPayment', $request->modeOfPayment);
        }
        if ($request->filled('remarks')) {
            $query->where('remarks', 'like', '%' . $request->remarks . '%');
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
        if ($request->filled('causeOfDamage')) {
            $query->where('causeOfDamage', 'like', '%' . $request->causeOfDamage . '%');
        }
        if ($request->filled('modeOfPayment')) {
            $query->where('modeOfPayment', $request->modeOfPayment);
        }
        if ($request->filled('remarks')) {
            $query->where('remarks', 'like', '%' . $request->remarks . '%');
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
