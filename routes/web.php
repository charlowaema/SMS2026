<?php

use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\FeeStructureController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SemesterController;
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



Route::get('/academic-years', [AcademicYearController::class, 'index'])
    ->name('academic-years.index');

Route::get('/academic-years/create', [AcademicYearController::class, 'create'])
    ->name('academic-years.create');

Route::post('/academic-years', [AcademicYearController::class, 'store'])
    ->name('academic-years.store');

Route::get('/academic-years/{academicYear}/edit',
    [AcademicYearController::class, 'edit'])
    ->name('academic-years.edit');

Route::put('/academic-years/{academicYear}',
    [AcademicYearController::class, 'update'])
    ->name('academic-years.update');

    Route::delete('/academic-years/{academicYear}',[AcademicYearController::class, 'destroy'])
    ->name('academic-years.destroy');



Route::get('/semesters', [SemesterController::class, 'index'])
    ->name('semesters.index');

Route::get('/semesters/create', [SemesterController::class, 'create'])
    ->name('semesters.create');

Route::post('/semesters', [SemesterController::class, 'store'])
    ->name('semesters.store');

Route::get('/semesters/{semester}/edit', [SemesterController::class, 'edit'])
    ->name('semesters.edit');

Route::put('/semesters/{semester}', [SemesterController::class, 'update'])
    ->name('semesters.update');

Route::delete('/semesters/{semester}', [SemesterController::class, 'destroy'])
    ->name('semesters.destroy');
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