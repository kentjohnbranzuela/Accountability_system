<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountabilityRecordsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TechnicianController;
use App\Http\Controllers\TurnOverController;
use App\Http\Controllers\GingoogController;
use App\Http\Controllers\CdoController;
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

    //CDO
    Route::post('/cdos/import', [CdoController::class, 'importExcel'])->name('cdos.import');
    Route::get('/cdos/export', [CdoController::class, 'export'])->name('cdos.export');
    Route::match(['post', 'delete'], '/cdos/delete-all', [CdoController::class, 'deleteAll'])->name('cdos.deleteAll');
    Route::get('/cdos', [CdoController::class, 'records'])->name('cdos.records');
    Route::get('/cdos/create', [CdoController::class, 'create'])->name('cdos.create');
    Route::post('/cdos/store', [CdoController::class, 'store'])->name('cdos.store');
    Route::get('/cdos/{id}/edit', [CdoController::class, 'edit'])->name('cdos.edit');
    Route::put('/cdos/{id}', [CdoController::class, 'update'])->name('cdos.update');
    Route::delete('/cdos/{id}', [CdoController::class, 'destroy'])->name('cdos.destroy');
    Route::get('/cdos/check-data', [CdoController::class, 'checkData'])->name('cdos.checkData');

    //Turn=Over
    Route::post('/import-turnovers', [TurnOverController::class, 'importExcel'])->name('import.turnovers');
    Route::delete('/turnover/delete-all', [TurnOverController::class, 'deleteAll'])->name('turnover.deleteAll');
Route::get('/turnover/records', [TurnOverController::class, 'records'])->name('turnover.records');
route::delete('/turnover-destroy', [TurnOverController::class, 'destroy'])->name('turnover.destroy');
Route::post('/turnover-update', [TurnOverController::class, 'updateAccount'])->name('turnover.update');
Route::get('/turnover/create', [TurnOverController::class, 'create'])->name('turnover.create');
Route::post('/turnover/store', [TurnOverController::class, 'store'])->name('turnover.store');
Route::get('/export-turnovers', [TurnOverController::class, 'exportExcel'])->name('export.turnovers');
Route::resource('turnover', TurnOverController::class);
});




    // Import Route
    Route::post('/import-excel', [AccountabilityRecordsController::class, 'importExcel'])->name('accountability.import');
    Route::post('/technician/import', [TechnicianController::class, 'importExcel'])->name('technician.import');




