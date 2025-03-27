<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountabilityRecordsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TechnicianController;
use App\Http\Controllers\TurnOverController;
use App\Http\Controllers\ToolsRequestController;
use App\Http\Controllers\GingoogController;
use App\Http\Controllers\CdoController;
use App\Http\Controllers\ResignRecordController;
use App\Http\Controllers\AwolRecordController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\SessionAuth;
use Illuminate\Support\Facades\Auth; // Import Auth Facade


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
    Route::delete('/technician/{id}', [TechnicianController::class, 'destroy'])->name('technician.destroy');
        Route::get('/technician/{id}/edit', [TechnicianController::class, 'edit'])->name('technician.edit');
Route::put('/technician-update/{id}', [TechnicianController::class, 'update'])->name('technician.update');
    Route::get('/technician/create', [TechnicianController::class, 'create'])->name('technician.create');
    Route::post('/technician/store', [TechnicianController::class, 'store'])->name('technician.store');
    Route::get('/export-technicians', [TechnicianController::class, 'exportExcel'])->name('export.technicians');
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
    Route::get('/turnover/{id}/edit', [TurnOverController::class, 'edit'])->name('turnover.edit');
    Route::put('/turnover/{id}', [TurnOverController::class, 'update'])->name('turnover.update');
Route::get('/turnover/create', [TurnOverController::class, 'create'])->name('turnover.create');
Route::post('/turnover/store', [TurnOverController::class, 'store'])->name('turnover.store');
Route::get('/export-turnovers', [TurnOverController::class, 'exportExcel'])->name('export.turnovers');
//AWOL
Route::get('/awol/records', [AwolRecordController::class, 'records'])->name('awol.records');
Route::post('/awol/import', [AwolRecordController::class, 'import'])->name('awol.import');
Route::delete('/awol/delete-all', [AwolRecordController::class, 'deleteAll'])->name('awol.deleteAll');
Route::get('/awol/create', [AwolRecordController::class, 'create'])->name('awol.create');
Route::delete('/awol/{id}', [AwolRecordController::class, 'destroy'])->name('awol.destroy');
Route::post('/awol/store', [AwolRecordController::class, 'store'])->name('awol.store');
Route::get('/awol/{awolRecord}/edit', [AwolRecordController::class, 'edit'])->name('awol.edit');
Route::put('/awol/{awolRecord}', [AwolRecordController::class, 'update'])->name('awol.update');
Route::get('/awol/check-data', [AwolRecordController::class, 'checkData'])->name('awol.checkData');
Route::get('/export-awol', [AwolRecordController::class, 'exportExcel'])->name('export.awol');
//Resign
Route::get('/resign/records', [ResignRecordController::class, 'records'])->name('resign.records');
Route::post('/resign/import', [ResignRecordController::class, 'importExcel'])->name('resign.import');
Route::delete('/resign/delete-all', [ResignRecordController::class, 'deleteAll'])->name('resign.deleteAll');
Route::get('/resign/create', [ResignRecordController::class, 'create'])->name('resign.create');
Route::post('/resign/store', [ResignRecordController::class, 'store'])->name('resign.store');
Route::delete('/resign/{id}', [ResignRecordController::class, 'destroy'])->name('resign.destroy');
Route::get('/resign/{resignRecord}/edit', [ResignRecordController::class, 'edit'])->name('resign.edit');
Route::put('/resign/{resignRecord}', [ResignRecordController::class, 'update'])->name('resign.update');
Route::get('/resign/check-data', [ResignRecordController::class, 'checkData'])->name('resign.checkData');
Route::get('/export-resign', [ResignRecordController::class, 'exportExcel'])->name('export.resign');
//Tools Request
        Route::get('/toolsrequest/records', [ToolsRequestController::class, 'records'])->name('toolsrequest.records');
        Route::post('/toolsrequest/import', [ToolsRequestController::class, 'importExcel'])->name('toolsrequest.import');
        Route::delete('/toolsrequest/delete-all', [ToolsRequestController::class, 'deleteAll'])->name('toolsrequest.deleteAll');
        Route::delete('/toolsrequest/{toolsrequest}', [ToolsRequestController::class, 'destroy'])
            ->name('toolsrequest.destroy');
        Route::get('/toolsrequest/create', [ToolsRequestController::class, 'create'])->name('toolsrequest.create');
        Route::post('/toolsrequest/store', [ToolsRequestController::class, 'store'])->name('toolsrequest.store');
        Route::get('/toolsrequest/{toolsrequest}/edit', [ToolsRequestController::class, 'edit'])->name('toolsrequest.edit');
        Route::put('/toolsrequest/{toolsrequest}', [ToolsRequestController::class, 'update'])->name('toolsrequest.update');
        Route::get('/toolsrequest/check-data', [ToolsRequestController::class, 'checkData'])->name('toolsrequest.checkData');
        Route::get('/export-toolsrequests', [ToolsRequestController::class, 'exportExcel'])->name('export.toolsrequests');});





    // Import Route
    Route::post('/import-excel', [AccountabilityRecordsController::class, 'importExcel'])->name('accountability.import');
    Route::post('/technician/import', [TechnicianController::class, 'importExcel'])->name('technician.import');




