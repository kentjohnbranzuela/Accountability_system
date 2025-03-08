<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountabilityRecordsController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

// Main page (Data Entry + List of Records)
Route::get('/accountability', [AccountabilityRecordsController::class, 'index'])->name('accountability.index');
Route::get('/accountability-records', [AccountabilityRecordsController::class, 'accountability_records'])
    ->name('accountability.accountability_records');

// Store new record
Route::post('/accountability', [AccountabilityRecordsController::class, 'store'])->name('accountability.store');

// Edit and Update
Route::get('/accountability/{id}/edit', [AccountabilityRecordsController::class, 'edit'])->name('accountability.edit');
Route::put('/accountability/{id}', [AccountabilityRecordsController::class, 'update'])->name('accountability.update');

// Delete
Route::delete('/accountability/{id}', [AccountabilityRecordsController::class, 'destroy'])->name('accountability.destroy');

//Dashboard
Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

//import
Route::post('/import-excel', [AccountabilityRecordsController::class, 'importExcel'])->name('accountability.import');
