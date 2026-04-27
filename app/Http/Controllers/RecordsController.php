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
        // Always return JSON for this endpoint since it's used by AJAX forms
        $isAjax = true;
        
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
                $message = 'Please log in as Officer of the Day first.';
                return $isAjax ? response()->json(['success' => false, 'message' => $message], 401) 
                              : redirect()->back()->with('error', $message);
            }
        } elseif ($source === 'Email') {
            if (!$request->session()->has('email_logged_in') || !$request->session()->has('email_user_name')) {
                $message = 'Please log in to Email handler first.';
                return $isAjax ? response()->json(['success' => false, 'message' => $message], 401) 
                              : redirect()->back()->with('error', $message);
            }
            $emailName = $request->session()->get('email_user_name');
            $approvedHandler = EmailHandler::where('name', $emailName)->where('approved', true)->exists();
            if (!$approvedHandler) {
                $message = 'Your email handler account is not approved yet.';
                return $isAjax ? response()->json(['success' => false, 'message' => $message], 403) 
                              : redirect()->back()->with('error', $message);
            }
        } elseif ($source === 'Facebook') {
            if (!$request->session()->has('facebook_logged_in')) {
                $message = 'Please log in to Facebook handler first.';
                return $isAjax ? response()->json(['success' => false, 'message' => $message], 401) 
                              : redirect()->back()->with('error', $message);
            }
        }

        $encoderName = $request->session()->get('officer_name');

        if (!$encoderName) {
            if ($source === 'Email') {
                $encoderName = $request->session()->get('email_user_name');
                if (!$encoderName) {
                    $message = 'Unauthorized access.';
                    return $isAjax ? response()->json(['success' => false, 'message' => $message], 401) 
                                  : redirect()->back()->with('error', $message);
                }
            } elseif ($source === 'Facebook') {
                $encoderName = 'Facebook';
            } else {
                $message = 'Unauthorized access.';
                return $isAjax ? response()->json(['success' => false, 'message' => $message], 401) 
                              : redirect()->back()->with('error', $message);
            }
        }

        $address = trim(implode(', ', array_filter([
            $request->barangay,
            $request->municipality,
            $request->province,
        ])));

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
            
            Log::info('Record created successfully', ['record_id' => $record->id, 'isAjax' => $isAjax]);
            
            // Return success response
            $successMessage = 'Record stored successfully.';
            if ($isAjax) {
                $response = response()->json([
                    'success' => true,
                    'message' => $successMessage,
                    'record' => $record
                ]);
                Log::info('Returning JSON response', ['response' => $response->getContent()]);
                return $response;
            }
            
            return redirect()->back()->with('success', $successMessage);
            
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
            
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed. Please check your input.',
                    'errors' => $e->errors()
                ], 422);
            }
            
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
            
            $errorMessage = 'Database error occurred. Please try again. If the problem persists, contact an administrator.';
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', $errorMessage)
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
            $errorMessage = 'Unable to save record. Please try again. If the problem persists, contact an administrator.';
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', $errorMessage)
                ->withInput();
        }
    }

    public function updateRecord(Request $request, $id)
    {
        Log::info('=== UPDATE RECORD METHOD STARTED ===', [
            'id' => $id, 
            'data' => $request->all(),
            'clear_admin_transmittal_number' => $request->input('clear_admin_transmittal_number'),
            'has_clear_checkbox' => $request->has('clear_admin_transmittal_number'),
            'admin_transmittal_number' => $request->input('admin_transmittal_number'),
            'request_method' => $request->method(),
            'request_url' => $request->fullUrl()
        ]);
        
        $record = Record::findOrFail($id);
        Log::info('Record found', ['id' => $id, 'current_admin_transmittal' => $record->admin_transmittal_number]);

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

        // Handle admin transmittal number clearing or setting
        if ($request->has('clear_admin_transmittal_number') && $request->input('clear_admin_transmittal_number') == '1') {
            $updateData['admin_transmittal_number'] = null;
            $updateData['admin_transmittal_assigned_at'] = null;
            Log::info('Clearing admin transmittal number', ['record_id' => $id]);
        } elseif ($request->filled('admin_transmittal_number')) {
            $updateData['admin_transmittal_number'] = $request->input('admin_transmittal_number');
            $updateData['admin_transmittal_assigned_at'] = now();
            Log::info('Setting admin transmittal number', ['record_id' => $id, 'transmittal_number' => $request->input('admin_transmittal_number')]);
        } else {
            // Don't change admin transmittal number if not specified
            unset($updateData['admin_transmittal_number']);
            unset($updateData['admin_transmittal_assigned_at']);
        }

        try {
            // Use database transaction to ensure data consistency
            DB::beginTransaction();
            
            Log::info('About to update record', ['id' => $id, 'updateData' => $updateData]);
            
            $record->update($updateData);
            
            Log::info('Record updated successfully', ['id' => $id, 'updated_record' => $record->fresh()]);
            
            DB::commit();
            
            Log::info('Transaction committed, returning success');
            
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
