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
Route::post('/facebook/logout', [RoutesController::class, 'logoutFacebook'])->name('facebook.logout');
Route::get('/officer-of-the-day', [RoutesController::class, 'showOfficerOfTheDay'])->name('officer-of-the-day');
Route::post('/officer/login', [RoutesController::class, 'loginOfficer'])->name('officer.login');
Route::post('/officer/logout', [RoutesController::class, 'logoutOfficer'])->name('officer.logout');
Route::get('/admin', [RoutesController::class, 'showAdmin'])->name('admin');
Route::post('/admin/login', [RoutesController::class, 'loginAdmin'])->name('admin.login');
Route::put('/admin/users/{id}', [RoutesController::class, 'updateAdmin'])->name('admin.users.update');
Route::post('/admin/users', [RoutesController::class, 'createAdmin'])->name('admin.users.create');
Route::get('/admin/records/{id}/approve', [RoutesController::class, 'approveRecord'])->name('admin.records.approve');
Route::post('/admin/officers/{id}/approve', [RoutesController::class, 'approveOfficer'])->name('admin.officers.approve');
Route::post('/admin/bulk-delete', [RoutesController::class, 'bulkDelete'])->name('admin.bulk-delete');
Route::get('/admin/export-excel', [RoutesController::class, 'exportExcel'])->name('admin.export-excel');
Route::get('/admin/export-pdf', [RoutesController::class, 'exportPdf'])->name('admin.export-pdf');
Route::get('/admin/print-preview', [RoutesController::class, 'printPreview'])->name('admin.print-preview');
Route::post('/admin/assign-transmittals', [RoutesController::class, 'assignTransmittals'])->name('admin.assign-transmittals');

Route::post('/records', [RecordsController::class, 'storeRecord'])->name('records');

Route::put('/records/{id}', [RecordsController::class, 'updateRecord'])->name('records.update');
Route::delete('/records/{id}', [RecordsController::class, 'destroyRecord'])->name('records.destroy');