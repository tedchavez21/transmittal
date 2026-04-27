<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Record;
use App\Models\EmailHandler;

class RecordsController extends Controller
{
    public function storeRecord(Request $request)
    {
        // Validate the incoming request data
        $source = $request->input('source', 'OD');

        $validatedData = $request->validate([
            'farmerName' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'municipality' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'line' => 'required|string|max:255',
            'program' => 'required|string|max:255',
            'causeOfDamage' => 'required|string|max:255',
            'modeOfPayment' => 'required|string|max:255',
            'accounts' => ['required_if:source,Facebook', 'string', 'max:255'],
            'facebook_page_url' => [
                'nullable',
                'string',
                'max:5000',
                Rule::when($request->filled('facebook_page_url'), ['regex:/^https?:\/\/.+/i']),
            ],
            'date_occurrence' => 'nullable|string|max:500',
            'date_received' => 'nullable|date',
            'remarks' => 'nullable|string|max:255',
            'source' => 'nullable|string|in:OD,Email,Facebook',
        ]);

        if ($source !== 'Facebook') {
            $validatedData['facebook_page_url'] = null;
        }

        // Check authentication based on source
        if ($source === 'OD') {
            if (!$request->session()->has('officer_name')) {
                return redirect()->back()->with('error', 'Please log in as Officer of the Day first.');
            }
        } elseif ($source === 'Email') {
            if (!$request->session()->has('email_logged_in') || !$request->session()->has('email_user_name')) {
                return redirect()->back()->with('error', 'Please log in to Email handler first.');
            }
            $emailName = $request->session()->get('email_user_name');
            $approvedHandler = EmailHandler::where('name', $emailName)->where('approved', true)->exists();
            if (!$approvedHandler) {
                return redirect()->back()->with('error', 'Your email handler account is not approved yet.');
            }
        } elseif ($source === 'Facebook') {
            if (!$request->session()->has('facebook_logged_in')) {
                return redirect()->back()->with('error', 'Please log in to Facebook handler first.');
            }
        }

        $encoderName = $request->session()->get('officer_name');

        if (!$encoderName) {
            if ($source === 'Email') {
                $encoderName = $request->session()->get('email_user_name');
                if (!$encoderName) {
                    return redirect()->back()->with('error', 'Unauthorized access.');
                }
            } elseif ($source === 'Facebook') {
                $encoderName = 'Facebook';
            } else {
                return redirect()->back()->with('error', 'Unauthorized access.');
            }
        }

        $address = trim(implode(', ', array_filter([
            $request->barangay,
            $request->municipality,
            $request->province,
        ])));

        // Check for potential duplicate records
        $potentialDuplicates = Record::where('farmerName', 'LIKE', '%' . $request->farmerName . '%')
            ->where('municipality', $request->municipality)
            ->where('barangay', $request->barangay)
            ->where('causeOfDamage', $request->causeOfDamage)
            ->where('line', $request->line)
            ->when($request->date_occurrence, function ($query) use ($request) {
                return $query->where('date_occurrence', $request->date_occurrence);
            })
            ->get();

        if ($potentialDuplicates->isNotEmpty()) {
            // Create a detailed error message with duplicate information
            $duplicateInfo = [];
            foreach ($potentialDuplicates as $duplicate) {
                $duplicateInfo[] = sprintf(
                    "Name: %s, Address: %s, Cause: %s, Line: %s, Date: %s",
                    $duplicate->farmerName,
                    $duplicate->address,
                    $duplicate->causeOfDamage,
                    $duplicate->line,
                    $duplicate->date_occurrence ?: 'Not specified'
                );
            }
            
            $errorMessage = "Potential duplicate record(s) found:\n\n" . implode("\n", $duplicateInfo) . 
                           "\n\nPlease review the existing records before submitting a new one.";
            
            return redirect()->back()
                ->with('error', $errorMessage)
                ->withInput();
        }

        try {
            // Validate address before proceeding
            if (empty(trim($address))) {
                throw new \Exception('Address cannot be empty. Please select valid municipality and barangay.');
            }
            
            // Use database transaction to ensure data consistency
            DB::beginTransaction();
            
            $record = Record::create(array_merge($validatedData, [
                'address' => $address,
                'encoderName' => $encoderName,
                'source' => $request->source ?? 'OD',
                'approved' => true,
                'approved_at' => now(),
            ]));
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Record stored successfully.');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation exceptions specifically
            DB::rollBack();
            Log::error('Validation failed during record creation', [
                'error' => $e->getMessage(),
                'errors' => $e->errors(),
                'user' => $encoderName,
                'source' => $request->source ?? 'OD',
                'data' => $request->all()
            ]);
            
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
                
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database query exceptions specifically
            DB::rollBack();
            Log::error('Database error during record creation', [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'user' => $encoderName,
                'source' => $request->source ?? 'OD',
                'data' => $validatedData
            ]);
            
            return redirect()->back()
                ->with('error', 'Database error occurred. Please try again. If the problem persists, contact an administrator.')
                ->withInput();
                
        } catch (\Exception $e) {
            // Handle all other exceptions
            DB::rollBack();
            
            // Log the error for debugging
            Log::error('Record creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user' => $encoderName,
                'source' => $request->source ?? 'OD',
                'data' => $validatedData
            ]);
            
            // Return user-friendly error message
            return redirect()->back()
                ->with('error', 'Unable to save record. Please try again. If the problem persists, contact an administrator.')
                ->withInput();
        }
    }

    public function updateRecord(Request $request, $id)
    {
        Log::info('Update record called', ['id' => $id, 'data' => $request->all()]);
        
        $record = Record::findOrFail($id);

        // Validate the incoming request data
        $validatedData = $request->validate([
            'farmerName' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'municipality' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'line' => 'required|string|max:255',
            'program' => 'required|string|max:255',
            'source' => 'required|string|max:255',
            'causeOfDamage' => 'required|string|max:255',
            'modeOfPayment' => 'required|string|max:255',
            'accounts' => ['required_if:source,Facebook', 'nullable', 'string', 'max:255'],
            'facebook_page_url' => [
                'nullable',
                'string',
                'max:5000',
                Rule::requiredIf(function () use ($request) {
                    return $request->input('source') === 'Facebook' && $request->has('facebook_page_url');
                }),
                Rule::when($request->filled('facebook_page_url'), ['regex:/^https?:\/\/.+/i']),
            ],
            'date_occurrence' => 'nullable|string|max:500',
            'date_received' => 'nullable|date',
            'remarks' => 'nullable|string|max:255',
            'transmittal_number' => 'nullable|string|max:255',
            'admin_transmittal_number' => 'nullable|string|max:255',
        ]);

        if (($validatedData['source'] ?? '') !== 'Facebook') {
            $validatedData['facebook_page_url'] = null;
        } elseif (!$request->has('facebook_page_url')) {
            unset($validatedData['facebook_page_url']);
        }

        $address = trim(implode(', ', array_filter([
            $request->barangay,
            $request->municipality,
            $request->province,
        ])));

        $updateData = array_merge($validatedData, ['address' => $address]);

        if (!$request->filled('transmittal_number')) {
            unset($updateData['transmittal_number']);
        }

        if ($request->filled('clear_admin_transmittal_number')) {
            $updateData['admin_transmittal_number'] = null;
            $updateData['admin_transmittal_assigned_at'] = null;
        } elseif ($request->filled('admin_transmittal_number')) {
            $updateData['admin_transmittal_number'] = $request->input('admin_transmittal_number');
            $updateData['admin_transmittal_assigned_at'] = now();
        } else {
            unset($updateData['admin_transmittal_number']);
        }

        // Check for potential duplicate records (excluding current record)
        $potentialDuplicates = Record::where('farmerName', 'LIKE', '%' . $request->farmerName . '%')
            ->where('municipality', $request->municipality)
            ->where('barangay', $request->barangay)
            ->where('causeOfDamage', $request->causeOfDamage)
            ->where('line', $request->line)
            ->when($request->date_occurrence, function ($query) use ($request) {
                return $query->where('date_occurrence', $request->date_occurrence);
            })
            ->where('id', '!=', $record->id) // Exclude current record
            ->get();

        if ($potentialDuplicates->isNotEmpty()) {
            // Create a detailed error message with duplicate information
            $duplicateInfo = [];
            foreach ($potentialDuplicates as $duplicate) {
                $duplicateInfo[] = sprintf(
                    "Name: %s, Address: %s, Cause: %s, Line: %s, Date: %s",
                    $duplicate->farmerName,
                    $duplicate->address,
                    $duplicate->causeOfDamage,
                    $duplicate->line,
                    $duplicate->date_occurrence ?: 'Not specified'
                );
            }
            
            $errorMessage = "Potential duplicate record(s) found:\n\n" . implode("\n", $duplicateInfo) . 
                           "\n\nPlease review the existing records before updating.";
            
            return redirect()->back()
                ->with('error', $errorMessage)
                ->withInput();
        }

        try {
            // Use database transaction to ensure data consistency
            DB::beginTransaction();
            
            $record->update($updateData);
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Record updated successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log the error for debugging
            Log::error('Record update failed', [
                'error' => $e->getMessage(),
                'record_id' => $id,
                'user' => $request->session()->get('email_user_name') ?? $request->session()->get('facebook_user_name') ?? $request->session()->get('officer_name') ?? 'admin',
                'data' => $updateData
            ]);
            
            // Return user-friendly error message
            return redirect()->back()
                ->with('error', 'Unable to update record. Please try again. If the problem persists, contact an administrator.')
                ->withInput();
        }
    }

    public function destroyRecord($id)
    {
        $record = Record::findOrFail($id);
        $record->delete();

        return redirect()->back()->with('success', 'Record deleted successfully!');
    }

    public function checkDuplicates(Request $request)
    {
        $validatedData = $request->validate([
            'farmerName' => 'required|string|max:255',
            'municipality' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'causeOfDamage' => 'required|string|max:255',
            'line' => 'required|string|max:255',
            'date_occurrence' => 'nullable|string|max:500',
        ]);

        // Check for potential duplicate records
        $potentialDuplicates = Record::where('farmerName', 'LIKE', '%' . $request->farmerName . '%')
            ->where('municipality', $request->municipality)
            ->where('barangay', $request->barangay)
            ->where('causeOfDamage', $request->causeOfDamage)
            ->where('line', $request->line)
            ->when($request->date_occurrence, function ($query) use ($request) {
                return $query->where('date_occurrence', $request->date_occurrence);
            })
            ->get();

        if ($potentialDuplicates->isEmpty()) {
            return response()->json([
                'success' => true,
                'duplicates' => []
            ]);
        }

        // Format duplicate records for display
        $duplicateRecords = [];
        foreach ($potentialDuplicates as $duplicate) {
            $duplicateRecords[] = [
                'id' => $duplicate->id,
                'farmerName' => $duplicate->farmerName,
                'address' => $duplicate->address,
                'causeOfDamage' => $duplicate->causeOfDamage,
                'line' => $duplicate->line,
                'date_occurrence' => $duplicate->date_occurrence ?: 'Not specified',
                'program' => $duplicate->program,
                'source' => $duplicate->source,
                'created_at' => $duplicate->created_at->format('M d, Y'),
            ];
        }

        return response()->json([
            'success' => true,
            'duplicates' => $duplicateRecords,
            'message' => 'Potential duplicate records found'
        ]);
    }
}
