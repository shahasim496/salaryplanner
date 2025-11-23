<?php

use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiSalaryPlanController;
use App\Http\Controllers\Api\ApiLoanController;
use App\Http\Controllers\Api\ApiInvestmentController;
use App\Http\Controllers\Api\ApiDailySpendingController;
use App\Http\Controllers\Api\ApiDashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes (no authentication required)
Route::prefix('v1')->group(function () {
    // Authentication routes
    Route::post('/register', [ApiAuthController::class, 'register'])->name('api.register');
    Route::post('/login', [ApiAuthController::class, 'login'])->name('api.login');
    Route::post('/forgot-password', [ApiAuthController::class, 'forgotPassword'])->name('api.forgot-password');
    Route::post('/reset-password', [ApiAuthController::class, 'resetPassword'])->name('api.reset-password');
    
    // QR Code routes (public for mobile app)
    Route::post('/qr/verify', [\App\Http\Controllers\Api\ApiQrCodeController::class, 'verify'])->name('api.qr.verify');
    Route::get('/qr/check-status', [\App\Http\Controllers\Api\ApiQrCodeController::class, 'checkStatus'])->name('api.qr.check-status');
});

// Protected routes (authentication required)
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    // User profile routes
    Route::get('/user', [ApiAuthController::class, 'user'])->name('api.user');
    Route::put('/user', [ApiAuthController::class, 'updateProfile'])->name('api.update-profile');
    Route::post('/logout', [ApiAuthController::class, 'logout'])->name('api.logout');
    Route::post('/change-password', [ApiAuthController::class, 'changePassword'])->name('api.change-password');

    // Dashboard
    Route::get('/dashboard', [ApiDashboardController::class, 'index'])->name('api.dashboard');

    // Salary Plans
    Route::get('/salary-plans', [ApiSalaryPlanController::class, 'index'])->name('api.salary-plans.index');
    Route::post('/salary-plans', [ApiSalaryPlanController::class, 'store'])->name('api.salary-plans.store');
    Route::get('/salary-plans/{id}', [ApiSalaryPlanController::class, 'show'])->name('api.salary-plans.show');
    Route::put('/salary-plans/{id}', [ApiSalaryPlanController::class, 'update'])->name('api.salary-plans.update');
    Route::delete('/salary-plans/{id}', [ApiSalaryPlanController::class, 'destroy'])->name('api.salary-plans.destroy');

    // Salary Plan Items
    Route::post('/salary-plans/{id}/salary-items', [ApiSalaryPlanController::class, 'addSalaryItem'])->name('api.salary-plans.add-item');
    Route::delete('/salary-plans/{planId}/salary-items/{itemId}', [ApiSalaryPlanController::class, 'deleteSalaryItem'])->name('api.salary-plans.delete-item');

    // Salary Plan Expenses
    Route::post('/salary-plans/{id}/expenses', [ApiSalaryPlanController::class, 'addExpense'])->name('api.salary-plans.add-expense');
    Route::put('/salary-plans/{planId}/expenses/{expenseId}', [ApiSalaryPlanController::class, 'updateExpense'])->name('api.salary-plans.update-expense');
    Route::delete('/salary-plans/{planId}/expenses/{expenseId}', [ApiSalaryPlanController::class, 'deleteExpense'])->name('api.salary-plans.delete-expense');

    // Salary Plan Savings
    Route::post('/salary-plans/{id}/savings', [ApiSalaryPlanController::class, 'addSaving'])->name('api.salary-plans.add-saving');
    Route::put('/salary-plans/{planId}/savings/{savingId}', [ApiSalaryPlanController::class, 'updateSaving'])->name('api.salary-plans.update-saving');
    Route::delete('/salary-plans/{planId}/savings/{savingId}', [ApiSalaryPlanController::class, 'deleteSaving'])->name('api.salary-plans.delete-saving');

    // Loans
    Route::get('/loans', [ApiLoanController::class, 'index'])->name('api.loans.index');
    Route::post('/loans', [ApiLoanController::class, 'store'])->name('api.loans.store');
    Route::get('/loans/{id}', [ApiLoanController::class, 'show'])->name('api.loans.show');
    Route::put('/loans/{id}', [ApiLoanController::class, 'update'])->name('api.loans.update');
    Route::delete('/loans/{id}', [ApiLoanController::class, 'destroy'])->name('api.loans.destroy');

    // Loan Entries and Payments
    Route::post('/loans/{id}/entries', [ApiLoanController::class, 'addEntry'])->name('api.loans.add-entry');
    Route::post('/loans/{id}/payments', [ApiLoanController::class, 'addPayment'])->name('api.loans.add-payment');
    Route::delete('/loans/{loanId}/entries/{entryId}', [ApiLoanController::class, 'deleteEntry'])->name('api.loans.delete-entry');
    Route::delete('/loans/{loanId}/payments/{paymentId}', [ApiLoanController::class, 'deletePayment'])->name('api.loans.delete-payment');

    // Investments
    Route::get('/investments', [ApiInvestmentController::class, 'index'])->name('api.investments.index');
    Route::post('/investments', [ApiInvestmentController::class, 'store'])->name('api.investments.store');
    Route::get('/investments/{id}', [ApiInvestmentController::class, 'show'])->name('api.investments.show');
    Route::put('/investments/{id}', [ApiInvestmentController::class, 'update'])->name('api.investments.update');
    Route::delete('/investments/{id}', [ApiInvestmentController::class, 'destroy'])->name('api.investments.destroy');

    // Investment Entries and Profits
    Route::post('/investments/{id}/entries', [ApiInvestmentController::class, 'addEntry'])->name('api.investments.add-entry');
    Route::post('/investments/{id}/profits', [ApiInvestmentController::class, 'addProfit'])->name('api.investments.add-profit');
    Route::delete('/investments/{investmentId}/entries/{entryId}', [ApiInvestmentController::class, 'deleteEntry'])->name('api.investments.delete-entry');
    Route::delete('/investments/{investmentId}/profits/{profitId}', [ApiInvestmentController::class, 'deleteProfit'])->name('api.investments.delete-profit');

    // Daily Spendings
    Route::get('/daily-spendings', [ApiDailySpendingController::class, 'index'])->name('api.daily-spendings.index');
    Route::post('/daily-spendings', [ApiDailySpendingController::class, 'store'])->name('api.daily-spendings.store');
    Route::get('/daily-spendings/{id}', [ApiDailySpendingController::class, 'show'])->name('api.daily-spendings.show');
    Route::put('/daily-spendings/{id}', [ApiDailySpendingController::class, 'update'])->name('api.daily-spendings.update');
    Route::delete('/daily-spendings/{id}', [ApiDailySpendingController::class, 'destroy'])->name('api.daily-spendings.destroy');
});

