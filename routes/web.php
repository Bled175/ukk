<?php

use App\Http\Controllers\PayrollSlipController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SalaryPeriodController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/shared/payroll-slips/{payrollSlip}/pdf', [PayrollSlipController::class, 'sharedPdf'])
    ->middleware('signed')
    ->name('payroll-slips.shared-pdf');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [PayrollSlipController::class, 'index'])
        ->middleware('verified')->name('dashboard');
    Route::resource('payroll-slips', PayrollSlipController::class)->except(['index', 'show'])->names('payroll-slips');
    Route::get('/payroll-slips/{payrollSlip}/pdf', [PayrollSlipController::class, 'pdf'])->name('payroll-slips.pdf');
    Route::post('/payroll-slips/{payrollSlip}/email', [PayrollSlipController::class, 'sendEmail'])->name('payroll-slips.email');
    Route::post('/payroll-slips/{payrollSlip}/whatsapp', [PayrollSlipController::class, 'whatsapp'])->name('payroll-slips.whatsapp');
    Route::resource('salary-periods', SalaryPeriodController::class)->except('show')->names('salary-periods');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
