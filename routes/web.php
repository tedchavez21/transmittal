<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoutesController;
use App\Http\Controllers\RecordsController;

Route::get('/', [RoutesController::class, 'showWelcome'])->name('welcome');
Route::get('/email-handler', [RoutesController::class, 'showEmailHandler'])->name('email-handler');
Route::post('/email/login', [RoutesController::class, 'loginEmail'])->name('email.login');
Route::post('/email/logout', [RoutesController::class, 'logoutEmail'])->name('email.logout');
Route::get('/facebook-handler', [RoutesController::class, 'showFacebookHandler'])->name('facebook-handler');
Route::post('/facebook/login', [RoutesController::class, 'loginFacebook'])->name('facebook.login');
Route::get('/all-records', [RoutesController::class, 'showAllRecords'])->name('all-records');
Route::get('/api/records/{id}', [RoutesController::class, 'getRecordDetails'])->name('api.records.show');
Route::post('/facebook/logout', [RoutesController::class, 'logoutFacebook'])->name('facebook.logout');
Route::get('/officer-of-the-day', [RoutesController::class, 'showOfficerOfTheDay'])->name('officer-of-the-day');
Route::post('/officer/login', [RoutesController::class, 'loginOfficer'])->name('officer.login');
Route::post('/officer/logout', [RoutesController::class, 'logoutOfficer'])->name('officer.logout');
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
