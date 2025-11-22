<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SalaryPlanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('salary-plans', SalaryPlanController::class);
    
    // Additional routes for salary plan items
    Route::post('salary-plans/{id}/salary-items', [SalaryPlanController::class, 'addSalaryItem'])->name('salary-plans.add-item');
    Route::post('salary-plans/{id}/expenses', [SalaryPlanController::class, 'addExpense'])->name('salary-plans.add-expense');
    Route::post('salary-plans/{id}/savings', [SalaryPlanController::class, 'addSaving'])->name('salary-plans.add-saving');
    
    Route::delete('salary-plans/{planId}/salary-items/{itemId}', [SalaryPlanController::class, 'deleteSalaryItem'])->name('salary-plans.delete-item');
    Route::delete('salary-plans/{planId}/expenses/{expenseId}', [SalaryPlanController::class, 'deleteExpense'])->name('salary-plans.delete-expense');
    Route::delete('salary-plans/{planId}/savings/{savingId}', [SalaryPlanController::class, 'deleteSaving'])->name('salary-plans.delete-saving');
    
    Route::put('salary-plans/{planId}/expenses/{expenseId}', [SalaryPlanController::class, 'updateExpense'])->name('salary-plans.update-expense');
    Route::put('salary-plans/{planId}/savings/{savingId}', [SalaryPlanController::class, 'updateSaving'])->name('salary-plans.update-saving');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
