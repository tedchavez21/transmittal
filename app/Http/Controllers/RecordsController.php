<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
            'accounts' => ['required_if:source,Facebook', 'nullable', 'string', 'max:255'],
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

        Record::create(array_merge($validatedData, [
            'address' => $address,
            'encoderName' => $encoderName,
            'source' => $request->source ?? 'OD',
            'approved' => true,
            'approved_at' => now(),
        ]));

        return redirect()->back()->with('success', 'Record stored successfully.');
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

        $record->update($updateData);

        return redirect()->back()->with('success', 'Record updated successfully!');
    }

    public function destroyRecord($id)
    {
        $record = Record::findOrFail($id);
        $record->delete();

        return redirect()->back()->with('success', 'Record deleted successfully!');
    }
}
