<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\FeeStructure;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\MpesaService;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
    $query = Payment::with([
    'student',
    'feeStructure.course',
    'feeStructure.semester',
    'feeStructure.academicYear',
    'receivedBy',
]);

if ($request->filled('search')) {

    $search = $request->search;

    $query->whereHas('student', function ($studentQuery) use ($search) {

        $studentQuery->where('student_number', 'like', "%{$search}%")
            ->orWhere('first_name', 'like', "%{$search}%")
            ->orWhere('last_name', 'like', "%{$search}%");

    });
}

if ($request->filled('status')) {

    $query->where('status', $request->status);
}

if ($request->filled('payment_method')) {

    $query->where('payment_method', $request->payment_method);
}

$payments = $query
    ->latest()
    ->paginate(10)
    ->withQueryString();

    $totalCollected = Payment::where('status', 'completed')
        ->sum('amount');

    $thisMonth = Payment::where('status', 'completed')
        ->whereMonth('payment_date', now()->month)
        ->whereYear('payment_date', now()->year)
        ->sum('amount');

    $pendingMpesa = Payment::where('status', 'pending')
        ->where('payment_method', 'mpesa')
        ->sum('amount');

    return view('payments.index', compact(
        'payments',
        'totalCollected',
        'thisMonth',
        'pendingMpesa'
    ));
}
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    $students = Student::where('status', 'active')
        ->orderBy('first_name')
        ->get();


    $feeStructures = FeeStructure::with([
        'course',
        'semester',
        'academicYear',
    ])
    ->latest()
    ->get();

    return view('payments.create', compact(
        'students',
        'feeStructures'
    ));
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $validated = $request->validate([
        'student_id' => ['required', 'exists:students,id'],
        'fee_structure_id' => ['required', 'exists:fee_structures,id'],
        'amount' => ['required', 'numeric', 'min:0.01'],
        'payment_method' => ['required', 'in:mpesa,cash,bank,cheque'],
        'transaction_reference' => [
        'nullable',
        'string',
        'max:255',
        'unique:payments,transaction_reference',
        'required_unless:payment_method,cash',
    ],
        'payment_date' => ['required', 'date'],
    ]);

    /*
    |--------------------------------------------------------------------------
    | Get the selected fee structure
    |--------------------------------------------------------------------------
    */

    $feeStructure = FeeStructure::findOrFail(
        $validated['fee_structure_id']
    );


    /*
    |--------------------------------------------------------------------------
    | Verify that the student is enrolled for this fee structure
    |--------------------------------------------------------------------------
    */

    $enrolled = Enrollment::where('student_id', $validated['student_id'])
        ->where('course_id', $feeStructure->course_id)
        ->where('semester_id', $feeStructure->semester_id)
        ->where('academic_year_id', $feeStructure->academic_year_id)
        ->exists();

    if (!$enrolled) {

        return back()
            ->withInput()
            ->withErrors([
                'student_id' =>
                    'The selected student is not enrolled in the course, semester and academic year associated with this fee structure.'
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Calculate amount already paid
    |--------------------------------------------------------------------------
    */

    $paidAmount = Payment::where('student_id', $validated['student_id'])
        ->where('fee_structure_id', $feeStructure->id)
        ->where('status', 'completed')
        ->sum('amount');


    /*
    |--------------------------------------------------------------------------
    | Calculate outstanding balance
    |--------------------------------------------------------------------------
    */

    $balance = $feeStructure->amount - $paidAmount;


    /*
    |--------------------------------------------------------------------------
    | Prevent overpayment
    |--------------------------------------------------------------------------
    */

    if ($validated['amount'] > $balance) {

        return back()
            ->withInput()
            ->withErrors([
                'amount' =>
                    'The payment amount cannot exceed the outstanding balance of KSh '
                    . number_format($balance, 2)
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Record the payment
    |--------------------------------------------------------------------------
    */

    DB::transaction(function () use ($validated) {

        $payment = Payment::create([
            'student_id' => $validated['student_id'],
            'fee_structure_id' => $validated['fee_structure_id'],
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'transaction_reference' => $validated['transaction_reference'] ?? null,
            'payment_date' => $validated['payment_date'],
            'status' => 'completed',
            'received_by' => Auth::id(),
            'receipt_number' => 'TEMP-' . uniqid(),
        ]);

        $payment->update([
            'receipt_number' =>
                'RCP' . str_pad($payment->id, 6, '0', STR_PAD_LEFT),
        ]);
    });


    return redirect()
        ->route('payments.index')
        ->with('success', 'Payment recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
{
   
    $payment->load([
        'student',
        'feeStructure.course',
        'feeStructure.semester',
        'feeStructure.academicYear',
        'receivedBy',
    ]);

    $totalFee = $payment->feeStructure->amount;

    $paidAmount = Payment::where('student_id', $payment->student_id)
        ->where('fee_structure_id', $payment->fee_structure_id)
        ->where('status', 'completed')
        ->sum('amount');

    $balance = max($totalFee - $paidAmount, 0);

    return view('payments.show', compact(
        'payment',
        'totalFee',
        'paidAmount',
        'balance'
    ));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }



    public function getPaymentSummary(Request $request)
{
    $validated = $request->validate([
        'student_id' => ['required', 'exists:students,id'],
        'fee_structure_id' => ['required', 'exists:fee_structures,id'],
    ]);

    $feeStructure = FeeStructure::findOrFail(
        $validated['fee_structure_id']
    );

    $paidAmount = Payment::where('student_id', $validated['student_id'])
        ->where('fee_structure_id', $feeStructure->id)
        ->where('status', 'completed')
        ->sum('amount');

    $balance = $feeStructure->amount - $paidAmount;

    return response()->json([
        'total_fee' => $feeStructure->amount,
        'paid' => $paidAmount,
        'balance' => max($balance, 0),
    ]);
}

public function studentStatement(Student $student)
{
    $student->load([
        'enrollments.course',
        'enrollments.semester',
        'enrollments.academicYear',
    ]);

    $feeStructures = FeeStructure::where(function ($query) use ($student) {

    foreach ($student->enrollments as $enrollment) {

        $query->orWhere(function ($subQuery) use ($enrollment) {

            $subQuery->where('course_id', $enrollment->course_id)
                ->where('semester_id', $enrollment->semester_id)
                ->where(
                    'academic_year_id',
                    $enrollment->academic_year_id
                );

        });

    }

})->get();

    $payments = Payment::with([
        'feeStructure.course',
        'feeStructure.semester',
        'feeStructure.academicYear',
        'receivedBy',
    ])
    ->where('student_id', $student->id)
    ->where('status', 'completed')
    ->latest('payment_date')
    ->get();

    $statementItems = $feeStructures->map(function ($feeStructure) use ($payments) {

    $paid = $payments
        ->where('fee_structure_id', $feeStructure->id)
        ->sum('amount');

    return [
        'feeStructure' => $feeStructure,
        'totalFee' => $feeStructure->amount,
        'paid' => $paid,
        'balance' => max($feeStructure->amount - $paid, 0),
    ];
});

$statementItems = $statementItems->map(function ($item) use ($payments) {

    $item['payments'] = $payments
        ->where('fee_structure_id', $item['feeStructure']->id)
        ->values();

    return $item;
});


    $totalFees = $statementItems->sum('totalFee');

    $totalPaid = $statementItems->sum('paid');

    $balance = $statementItems->sum('balance');

    return view('payments.student-statement', compact(
        'student',
        'payments',
        'statementItems',
        'totalFees',
        'totalPaid',
        'balance'
    ));
}




public function initiateMpesa(Request $request, MpesaService $mpesa)
{
    $validated = $request->validate([
        'student_id' => ['required', 'exists:students,id'],
        'fee_structure_id' => ['required', 'exists:fee_structures,id'],
        'amount' => ['required', 'numeric', 'min:1'],
        'phone' => ['required', 'string', 'max:20'],
    ]);

    $feeStructure = FeeStructure::findOrFail(
        $validated['fee_structure_id']
    );

    // Check whether the student is enrolled
    // in this particular course, semester and academic year.
    $enrolled = Enrollment::where('student_id', $validated['student_id'])
        ->where('course_id', $feeStructure->course_id)
        ->where('semester_id', $feeStructure->semester_id)
        ->where('academic_year_id', $feeStructure->academic_year_id)
        ->exists();

    if (!$enrolled) {
        return back()
            ->withInput()
            ->withErrors([
                'student_id' =>
                    'The selected student is not enrolled in this fee structure.'
            ]);
    }

    // Calculate how much has already been paid.
    $paidAmount = Payment::where('student_id', $validated['student_id'])
        ->where('fee_structure_id', $feeStructure->id)
        ->where('status', 'completed')
        ->sum('amount');

    $balance = $feeStructure->amount - $paidAmount;

    if ($validated['amount'] > $balance) {
        return back()
            ->withInput()
            ->withErrors([
                'amount' =>
                    'The payment cannot exceed the outstanding balance of KSh '
                    . number_format($balance, 2)
            ]);
    }

    // Send STK Push request to M-Pesa.
    $response = $mpesa->stkPush(
        $validated['phone'],
        $validated['amount'],
        'STUDENT-' . $validated['student_id'],
        'Student fee payment'
    );

    // Check whether Safaricom accepted the request.
    if (($response['ResponseCode'] ?? null) !== '0') {
        return back()
            ->withInput()
            ->withErrors([
                'mpesa' =>
                    $response['ResponseDescription']
                    ?? 'M-Pesa STK Push could not be initiated.'
            ]);
    }

    // Store the payment as pending.
 $payment = Payment::create([
    'student_id' => $validated['student_id'],
    'fee_structure_id' => $validated['fee_structure_id'],
    'amount' => $validated['amount'],
    'payment_method' => 'mpesa',
    'transaction_reference' => null,
    'merchant_request_id' => $response['MerchantRequestID'] ?? null,
    'checkout_request_id' => $response['CheckoutRequestID'] ?? null,
    'receipt_number' => 'TEMP-' . uniqid(),
    'payment_date' => now()->toDateString(),
    'status' => 'pending',
    'received_by' => Auth::id(),
]);

return response()->json([
    'success' => true,
    'message' => 'M-Pesa payment request sent. Please complete the payment on your phone.',
    'payment_id' => $payment->id,
    'status' => $payment->status,
]);

}

public function status(Payment $payment)
{
    return response()->json([
        'id' => $payment->id,
        'status' => $payment->status,
        'transaction_reference' => $payment->transaction_reference,
        'receipt_number' => $payment->receipt_number,
        'payment_date' => $payment->payment_date?->format('d M Y'),
    ]);
}
}
