<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountabilityRecordsController;
use Illuminate\Support\Facades\Auth;

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login'); // Redirect to login after logout
})->name('logout');


// Remove the conflicting Route::get('/warehouse') function

// Resource route (handles all CRUD operations)

// Explicit individual routes for clarity (optional, since resource already includes them)
Route::get('/accountability', [AccountabilityRecordsController::class, 'index'])->name('accountability.index');
Route::post('/accountability', [AccountabilityRecordsController::class, 'store'])->name('accountability.store');
Route::get('/accountability/{id}/edit', [AccountabilityRecordsController::class, 'edit'])->name('accountability.edit');
Route::put('/accountability/{id}', [AccountabilityRecordsController::class, 'update'])->name('accountability.update');
Route::delete('/accountability/{id}', [AccountabilityRecordsController::class, 'destroy'])->name('accountability.destroy');



