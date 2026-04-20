<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Record;

class RecordsController extends Controller
{
    public function storeRecord(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'farmerName' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'municipality' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'line' => 'required|string|max:255',
            'program' => 'required|string|max:255',
            'causeOfDamage' => 'required|string|max:255',
            'modeOfPayment' => 'required|string|max:255',
            'remarks' => 'nullable|string|max:255',
            'source' => 'nullable|string|in:OD,Email,Facebook',
        ]);

        $source = $request->source ?? 'OD';

        // Check authentication based on source
        if ($source === 'OD') {
            if (!$request->session()->has('officer_name')) {
                return redirect()->back()->with('error', 'Please log in as Officer of the Day first.');
            }
        } elseif ($source === 'Email') {
            if (!$request->session()->has('email_logged_in')) {
                return redirect()->back()->with('error', 'Please log in to Email handler first.');
            }
        } elseif ($source === 'Facebook') {
            if (!$request->session()->has('facebook_logged_in')) {
                return redirect()->back()->with('error', 'Please log in to Facebook handler first.');
            }
        }

        $encoderName = $request->session()->get('officer_name');

        if (!$encoderName) {
            if ($source === 'Email') {
                $encoderName = 'Email';
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
            'causeOfDamage' => 'required|string|max:255',
            'modeOfPayment' => 'required|string|max:255',
            'remarks' => 'nullable|string|max:255',
        ]);

        $address = trim(implode(', ', array_filter([
            $request->barangay,
            $request->municipality,
            $request->province,
        ])));

        $record->update(array_merge($validatedData, ['address' => $address]));

        return redirect()->back()->with('success', 'Record updated successfully!');
    }

    public function destroyRecord($id)
    {
        $record = Record::findOrFail($id);
        $record->delete();

        return redirect()->back()->with('success', 'Record deleted successfully!');
    }
}
