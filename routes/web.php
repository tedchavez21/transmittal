<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoutesController;
use App\Http\Controllers\RecordsController;
use App\Http\Controllers\AuthController;

Route::get('/', [RoutesController::class, 'showWelcome'])->name('welcome');

// Unified Authentication Routes
Route::get('/auth/login', [AuthController::class, 'showLogin'])->name('auth.login');
Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login.submit');
Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');

// Protected Channel Routes
Route::get('/email-handler', [RoutesController::class, 'showEmailHandler'])->name('email-handler');
Route::post('/email/logout', [RoutesController::class, 'logoutEmail'])->name('email.logout');
Route::get('/facebook-handler', [RoutesController::class, 'showFacebookHandler'])->name('facebook-handler');
Route::post('/facebook/logout', [RoutesController::class, 'logoutFacebook'])->name('facebook.logout');
Route::get('/officer-of-the-day', [RoutesController::class, 'showOfficerOfTheDay'])->name('officer-of-the-day');
Route::post('/officer/logout', [RoutesController::class, 'logoutOfficer'])->name('officer.logout');

Route::get('/all-records', [RoutesController::class, 'showAllRecords'])->name('all-records');
Route::get('/api/records/{id}', [RoutesController::class, 'getRecordDetails'])->name('api.records.show');
Route::get('/admin', [RoutesController::class, 'showAdmin'])->name('admin');
Route::post('/admin/login', [RoutesController::class, 'loginAdmin'])->name('admin.login');

Route::post('/admin/logout', [RoutesController::class, 'logoutAdmin'])->name('admin.logout');
Route::get('/admin/active-users', [RoutesController::class, 'getActiveUsers'])->name('admin.active-users');
Route::put('/admin/users/{id}', [RoutesController::class, 'updateAdmin'])->name('admin.users.update');
Route::post('/admin/users', [RoutesController::class, 'createAdmin'])->name('admin.users.create');
Route::get('/admin/records/{id}/approve', [RoutesController::class, 'approveRecord'])->name('admin.records.approve');
Route::post('/admin/officers/{id}/approve', [RoutesController::class, 'approveOfficer'])->name('admin.officers.approve');
Route::post('/admin/email-handlers/{id}/approve', [RoutesController::class, 'approveEmailHandler'])->name('admin.email-handlers.approve');
Route::delete('/admin/bulk-delete', [RoutesController::class, 'bulkDelete'])->name('admin.bulk-delete');
Route::get('/admin/export-excel', [RoutesController::class, 'exportExcel'])->name('admin.export-excel');
Route::get('/admin/export-pdf', [RoutesController::class, 'exportPdf'])->name('admin.export-pdf');
Route::get('/admin/print-preview', [RoutesController::class, 'printPreview'])->name('admin.print-preview');
Route::post('/admin/add-to-print-preview', [RoutesController::class, 'addToPrintPreview'])->name('admin.add-to-print-preview');
Route::post('/admin/clear-print-preview', [RoutesController::class, 'clearPrintPreview'])->name('admin.clear-print-preview');
Route::get('/admin/export-preview-csv', [RoutesController::class, 'exportPreviewCsv'])->name('admin.export-preview-csv');
Route::post('/admin/assign-transmittals', [RoutesController::class, 'assignTransmittals'])->name('admin.assign-transmittals');
Route::get('/admin/api/pending-approvals', [RoutesController::class, 'pendingApprovalsApi'])->name('admin.api.pending-approvals');

Route::post('/records', [RecordsController::class, 'storeRecord'])->name('records');
Route::post('/records/submit-transmittal', [RoutesController::class, 'submitTransmittal'])->name('records.submit-transmittal');
Route::get('/officer/export-csv', [RoutesController::class, 'exportOfficerCsv'])->name('officer.export-csv');
Route::get('/email/export-csv', [RoutesController::class, 'exportEmailCsv'])->name('email.export-csv');
Route::get('/facebook/export-csv', [RoutesController::class, 'exportFacebookCsv'])->name('facebook.export-csv');

Route::put('/records/{id}', [RecordsController::class, 'updateRecord'])->name('records.update');
Route::delete('/records/{id}', [RecordsController::class, 'destroyRecord'])->name('records.destroy');
Route::post('/records/check-duplicates', [RecordsController::class, 'checkDuplicates'])->name('records.check-duplicates');

// User Maintenance API Routes
Route::get('/api/officers', [RoutesController::class, 'getOfficers'])->name('api.officers.index');
Route::post('/api/officers', [RoutesController::class, 'createOfficer'])->name('api.officers.store');
Route::get('/api/officers/{id}', [RoutesController::class, 'getOfficer'])->name('api.officers.show');
Route::put('/api/officers/{id}', [RoutesController::class, 'updateOfficer'])->name('api.officers.update');
Route::delete('/api/officers/{id}', [RoutesController::class, 'deleteOfficer'])->name('api.officers.destroy');

Route::get('/api/email-handlers', [RoutesController::class, 'getEmailHandlers'])->name('api.email-handlers.index');
Route::post('/api/email-handlers', [RoutesController::class, 'createEmailHandler'])->name('api.email-handlers.store');
Route::get('/api/email-handlers/{id}', [RoutesController::class, 'getEmailHandler'])->name('api.email-handlers.show');
Route::put('/api/email-handlers/{id}', [RoutesController::class, 'updateEmailHandler'])->name('api.email-handlers.update');
Route::delete('/api/email-handlers/{id}', [RoutesController::class, 'deleteEmailHandler'])->name('api.email-handlers.destroy');

// Debug route for testing session
Route::get('/debug-session', function(Request $request) {
    return response()->json([
        'session_id' => session()->getId(),
        'admin_logged_in' => session()->get('admin_logged_in'),
        'admin_username' => session()->get('admin_username'),
        'all_data' => session()->all()
    ]);
});

// Simple admin login test
Route::get('/test-admin-login', function(Request $request) {
    // Test admin credentials directly
    $admin = \App\Models\Admin::where('username', 'UZZIEL')->first();
    
    if ($admin && \Illuminate\Support\Facades\Hash::check('U.MARTINEZ1114', $admin->password)) {
        // Set session manually
        session()->put('admin_logged_in', true);
        session()->put('admin_username', $admin->username);
        
        return response()->json([
            'success' => true,
            'message' => 'Admin login test successful',
            'admin' => $admin->username,
            'session_data' => session()->all()
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'Admin login test failed',
            'admin_found' => $admin !== null,
            'password_check' => $admin ? \Illuminate\Support\Facades\Hash::check('U.MARTINEZ1114', $admin->password) : false
        ]);
    }
});

// Direct admin access (bypass login for testing)
Route::get('/direct-admin', function(Request $request) {
    // Temporarily set session
    session()->put('admin_logged_in', true);
    session()->put('admin_username', 'UZZIEL');
    session()->save();
    
    // Redirect to admin page
    return redirect()->route('admin');
});

// Test admin session
Route::get('/test-admin-session', function(Request $request) {
    return response()->json([
        'admin_logged_in' => session()->get('admin_logged_in'),
        'admin_username' => session()->get('admin_username'),
        'session_id' => session()->getId(),
        'all_session_data' => session()->all()
    ]);
});

// Test admin login directly
Route::post('/test-admin-login', function(Request $request) {
    $admin = \App\Models\Admin::where('username', $request->username)->first();
    
    if ($admin && \Illuminate\Support\Facades\Hash::check($request->password, $admin->password)) {
        session()->put('admin_logged_in', true);
        session()->put('admin_username', $admin->username);
        session()->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'redirect' => route('admin')
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials'
        ]);
    }
});
