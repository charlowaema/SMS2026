<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
public function index(){
       $totalStudents = Student::count(); 

        $totalCourses = Course::count();

        $totalPayments = Payment::where('status', 'completed')
            ->count();

        $totalCollected = Payment::where('status', 'completed')
            ->sum('amount');

       $totalFees = DB::table('enrollments')
    ->join('fee_structures', function ($join) {
        $join->on(
            'enrollments.course_id',
            '=',
            'fee_structures.course_id'
        )
        ->on(
            'enrollments.semester_id',
            '=',
            'fee_structures.semester_id'
        )
        ->on(
            'enrollments.academic_year_id',
            '=',
            'fee_structures.academic_year_id'
        );
    })
    ->sum('fee_structures.amount');

$totalPaid = Payment::where('status', 'completed')
    ->sum('amount');

$outstandingFees = max($totalFees - $totalPaid, 0);

$recentPayments = Payment::with([
    'student',
    'feeStructure.course',
])
->where('status', 'completed')
->latest('payment_date')
->take(5)
->get();

$paymentTotals = Payment::where('status', 'completed')
    ->selectRaw('payment_method, SUM(amount) as total')
    ->groupBy('payment_method')
    ->pluck('total', 'payment_method');


        return view('dashboard', compact(
            'totalStudents',
            'totalCourses',
            'totalPayments',
            'totalCollected',
            'outstandingFees',
            'recentPayments',
            'paymentTotals'
        ));
}


}
