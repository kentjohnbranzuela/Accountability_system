<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountabilityRecordsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TechnicianController;
use App\Http\Controllers\GingoogController;
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
    Route::get('/export-BRO', [AccountabilityRecordsController::class, 'exportExcel'])->name('export.BRO');
    Route::delete('/Bro/delete-all', [AccountabilityRecordsController::class, 'deleteAll'])->name('bro.deleteAll');
    
    //auth
    Route::get('/account-info', [AuthController::class, 'accountInfo'])->name('account.info');
    Route::get('/account-info', [AuthController::class, 'accountInfo'])->name('account.info');
    Route::post('/account-update', [AuthController::class, 'updateAccount'])->name('account.update');
    Route::post('/update-password', [AuthController::class, 'updatePassword'])->name('update.password');

    //Technician
    Route::delete('/technician/delete-all', [TechnicianController::class, 'deleteAll'])->name('technician.deleteAll');
    Route::get('/technician/records', [TechnicianController::class, 'records'])->name('technician.records');
    Route::post('/technician-update', [TechnicianController::class, 'updateAccount'])->name('technician.update');
    Route::get('/technician/create', [TechnicianController::class, 'create'])->name('technician.create');
    Route::post('/technician/store', [TechnicianController::class, 'store'])->name('technician.store');
    Route::get('/export-technicians', [TechnicianController::class, 'exportExcel'])->name('export.technicians');
    Route::resource('technician', TechnicianController::class);

    //gingoog
    Route::get('/gingoogs/export', [GingoogController::class, 'export'])->name('gingoogs.export');
    Route::delete('/gingoogs/delete-all', [GingoogController::class, 'deleteAll'])->name('gingoogs.deleteAll');
    Route::get('/gingoogs/create', [GingoogController::class, 'create'])->name('gingoogs.create');
    Route::get('/gingoogs', [GingoogController::class, 'records'])->name('gingoogs.records');
    Route::get('/gingoogs/{id}', [GingoogController::class, 'show'])->name('gingoogs.show');
    Route::get('/gingoogs/{id}/edit', [GingoogController::class, 'edit'])->name('gingoogs.edit');
    Route::put('/gingoogs/{id}', [GingoogController::class, 'update'])->name('gingoogs.update');
    Route::post('/gingoogs/store', [GingoogController::class, 'store'])->name('gingoogs.store');
    Route::delete('/gingoogs/{id}', [GingoogController::class, 'destroy'])->name('gingoogs.destroy');
    Route::post('/gingoogs/import', [GingoogController::class, 'import'])->name('gingoogs.import');
});
  
   
   

    // Import Route
    Route::post('/import-excel', [AccountabilityRecordsController::class, 'importExcel'])->name('accountability.import');
    Route::post('/technician/import', [TechnicianController::class, 'importExcel'])->name('technician.import');


   

