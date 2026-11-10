<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\FeeStructureController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Root URL
Route::get('/', function () {

    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
});


/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');


    /*
    |--------------------------------------------------------------------------
    | Students
    |--------------------------------------------------------------------------
    */

    Route::resource('students', StudentController::class);


    /*
    |--------------------------------------------------------------------------
    | Courses
    |--------------------------------------------------------------------------
    */

    Route::resource('courses', CourseController::class);


    /*
    |--------------------------------------------------------------------------
    | Enrollments
    |--------------------------------------------------------------------------
    */

    Route::resource('enrollments', EnrollmentController::class);


    /*
    |--------------------------------------------------------------------------
    | Fee Structures
    |--------------------------------------------------------------------------
    */

    Route::resource('fee-structures', FeeStructureController::class);


    /*
    |--------------------------------------------------------------------------
    | Payments
    |--------------------------------------------------------------------------
    */

    // Payment summary
    Route::get('/payments/summary', [
        PaymentController::class,
        'getPaymentSummary'
    ])->name('payments.summary');


    // Initiate M-Pesa payment
    Route::post('/payments/mpesa', [
        PaymentController::class,
        'initiateMpesa'
    ])->name('payments.mpesa');


    // Student fee statement
    Route::get('/payments/student/{student}/statement', [
        PaymentController::class,
        'studentStatement'
    ])->name('payments.student.statement');


    // Check payment status
    Route::get('/payments/{payment}/status', [
        PaymentController::class,
        'status'
    ])->name('payments.status');


    // Payment CRUD
    Route::resource('payments', PaymentController::class);


    /*
    |--------------------------------------------------------------------------
    | Reports
    |--------------------------------------------------------------------------
    */

    // Payment report
    Route::get('/reports/payments', [
        ReportController::class,
        'payments'
    ])->name('reports.payments');


    // Export payment report
    Route::get('/reports/payments/export', [
        ReportController::class,
        'exportPayments'
    ])->name('reports.payments.export');

});


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';