<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountabilityRecordsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TechnicianController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\SessionAuth;
use Illuminate\Support\Facades\Auth;

// Public Routes

Route::get('/', function () {
    return redirect()->route('dashboard'); // Redirect to dashboard if logged in
});
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Logout Route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// Protected Routes - Only logged-in users can access
Route::middleware([SessionAuth::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/accountability', [AccountabilityRecordsController::class, 'index'])->name('accountability.index');
     Route::get('/accountability-records', [AccountabilityRecordsController::class, 'accountability_records'])->name('accountability.accountability_records');
    Route::post('/accountability', [AccountabilityRecordsController::class, 'store'])->name('accountability.store');
    Route::get('/accountability/{id}/edit', [AccountabilityRecordsController::class, 'edit'])->name('accountability.edit');
    Route::put('/accountability/{id}', [AccountabilityRecordsController::class, 'update'])->name('accountability.update');
    Route::delete('/accountability/{id}', [AccountabilityRecordsController::class, 'destroy'])->name('accountability.destroy');
    Route::get('/account-info', [AuthController::class, 'accountInfo'])->name('account.info');
    Route::get('/account-info', [AuthController::class, 'accountInfo'])->name('account.info');
Route::post('/account-update', [AuthController::class, 'updateAccount'])->name('account.update');
Route::post('/update-password', [AuthController::class, 'updatePassword'])->name('update.password');

Route::get('/technician/records', [TechnicianController::class, 'records'])->name('technician.records');
Route::post('/technician-update', [TechnicianController::class, 'updateAccount'])->name('technician.update');
Route::get('/technician/create', [TechnicianController::class, 'create'])->name('technician.create');
Route::post('/technician/store', [TechnicianController::class, 'store'])->name('technician.store');
Route::resource('technician', TechnicianController::class);
});
    // Accountability Routes
   
   

    // Import Route
    Route::post('/import-excel', [AccountabilityRecordsController::class, 'importExcel'])->name('accountability.import');
    Route::post('/technician/import', [TechnicianController::class, 'importExcel'])->name('technician.import');


   

