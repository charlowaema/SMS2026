<?php

namespace App\Http\Controllers;
use App\Models\Payment;
use Illuminate\Http\Request;



class ReportController extends Controller
{

public function payments(Request $request)
{
    $request->validate([
        'from' => ['nullable', 'date'],
        'to' => ['nullable', 'date', 'after_or_equal:from'],
        'payment_method' => [
            'nullable',
            'in:mpesa,cash,bank,cheque'
        ],
    ]);

    $query = $this->paymentReportQuery($request);

    $payments = $query
        ->latest('payment_date')
        ->paginate(15)
        ->withQueryString();

    $totalCollected = (clone $query)->sum('amount');

    $totalPayments = (clone $query)->count();

    $totalsByMethod = (clone $query)
        ->selectRaw(
            'payment_method, SUM(amount) as total'
        )
        ->groupBy('payment_method')
        ->pluck('total', 'payment_method');

    return view('reports.payments', compact(
        'payments',
        'totalCollected',
        'totalPayments',
        'totalsByMethod'
    ));
}


public function exportPayments(Request $request)
{
    $request->validate([
        'from' => ['nullable', 'date'],
        'to' => ['nullable', 'date', 'after_or_equal:from'],
        'payment_method' => [
            'nullable',
            'in:mpesa,cash,bank,cheque'
        ],
    ]);

    $query = $this->paymentReportQuery($request);

    $payments = $query
        ->latest('payment_date')
        ->get();

    $filename = 'payment-report-' .
        now()->format('Y-m-d') .
        '.csv';

    return response()->streamDownload(
        function () use ($payments) {

            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Receipt Number',
                'Payment Date',
                'Student Number',
                'Student Name',
                'Course',
                'Semester',
                'Academic Year',
                'Payment Method',
                'Transaction Reference',
                'Amount',
            ]);

            foreach ($payments as $payment) {

                fputcsv($file, [
                    $payment->receipt_number,
                    $payment->payment_date?->format('Y-m-d'),
                    $payment->student->student_number,
                    $payment->student->first_name . ' ' .
                        $payment->student->last_name,
                    $payment->feeStructure->course->course_name,
                    $payment->feeStructure->semester->name,
                    $payment->feeStructure->academicYear->name,
                    ucfirst($payment->payment_method),
                    $payment->transaction_reference ?? '',
                    number_format(
                        $payment->amount,
                        2,
                        '.',
                        ''
                    ),
                ]);
            }

            fclose($file);
        },
        $filename
    );
}


private function paymentReportQuery(Request $request)
{
    $query = Payment::with([
        'student',
        'feeStructure.course',
        'feeStructure.semester',
        'feeStructure.academicYear',
        'receivedBy',
    ])
    ->where('status', 'completed');

    if ($request->filled('from')) {
        $query->whereDate(
            'payment_date',
            '>=',
            $request->from
        );
    }

    if ($request->filled('to')) {
        $query->whereDate(
            'payment_date',
            '<=',
            $request->to
        );
    }

    if ($request->filled('payment_method')) {
        $query->where(
            'payment_method',
            $request->payment_method
        );
    }

    return $query;
}
}
