<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\FeeStructure;
use App\Models\Payment;
use App\Services\MpesaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MpesaController extends Controller
{
    
 public function callback(Request $request)
{
    $data = $request->all();

    // Log::info('M-Pesa Callback Received', $data);

    $callback = $data['Body']['stkCallback'] ?? null;

    if (!$callback) {
        return response()->json([
            'ResultCode' => 1,
            'ResultDesc' => 'Invalid callback data',
        ]);
    }

    $checkoutRequestId = $callback['CheckoutRequestID'] ?? null;

    $resultCode = $callback['ResultCode'] ?? null;

    $payment = \App\Models\Payment::where(
        'checkout_request_id',
        $checkoutRequestId
    )->first();

    if (!$payment) {
        Log::warning(
            'M-Pesa payment not found',
            [
                'checkout_request_id' => $checkoutRequestId,
            ]
        );

        return response()->json([
            'ResultCode' => 0,
            'ResultDesc' => 'Callback received',
        ]);
    }

    if ((int) $resultCode === 0) {

        $metadata = collect(
            $callback['CallbackMetadata']['Item'] ?? []
        )->keyBy('Name');

        $receiptNumber =
            $metadata->get('MpesaReceiptNumber')['Value']
            ?? null;

        $transactionDate =
            $metadata->get('TransactionDate')['Value']
            ?? null;

        $phoneNumber =
            $metadata->get('PhoneNumber')['Value']
            ?? null;

        $payment->update([
            'status' => 'completed',

            'transaction_reference' =>
                $receiptNumber,

            'receipt_number' =>
                'RCP' .
                str_pad(
                    $payment->id,
                    6,
                    '0',
                    STR_PAD_LEFT
                ),

            'payment_date' =>
                now()->toDateString(),
        ]);

        Log::info(
            'M-Pesa payment completed',
            [
                'payment_id' => $payment->id,
                'receipt' => $receiptNumber,
                'phone' => $phoneNumber,
                'transaction_date' => $transactionDate,
            ]
        );

    } else {

        $payment->update([
            'status' => 'failed',
        ]);

        Log::warning(
            'M-Pesa payment failed',
            [
                'payment_id' => $payment->id,
                'result_code' => $resultCode,
                'result_description' =>
                    $callback['ResultDesc'] ?? null,
            ]
        );
    }

    return response()->json([
        'ResultCode' => 0,
        'ResultDesc' => 'Callback processed successfully',
    ]);
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

    /*
    |--------------------------------------------------------------------------
    | Check student enrollment
    |--------------------------------------------------------------------------
    */

    $enrolled = Enrollment::where(
        'student_id',
        $validated['student_id']
    )
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

    /*
    |--------------------------------------------------------------------------
    | Check existing payments
    |--------------------------------------------------------------------------
    */

    $paidAmount = Payment::where(
        'student_id',
        $validated['student_id']
    )
    ->where(
        'fee_structure_id',
        $feeStructure->id
    )
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

    /*
    |--------------------------------------------------------------------------
    | Send STK Push
    |--------------------------------------------------------------------------
    */

    $response = $mpesa->stkPush(
        $validated['phone'],
        $validated['amount'],
        'STUDENT-' . $validated['student_id'],
        'Student fee payment'
    );

    /*
    |--------------------------------------------------------------------------
    | Check Daraja response
    |--------------------------------------------------------------------------
    */

    if (($response['ResponseCode'] ?? null) !== '0') {
        return back()
            ->withInput()
            ->withErrors([
                'mpesa' =>
                    $response['ResponseDescription']
                    ?? 'M-Pesa STK Push could not be initiated.'
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Save pending payment
    |--------------------------------------------------------------------------
    */

  Payment::create([
    'student_id' => $validated['student_id'],
    'fee_structure_id' => $validated['fee_structure_id'],
    'amount' => $validated['amount'],
    'payment_method' => 'mpesa',
    'transaction_reference' => null,

    'merchant_request_id' =>
        $response['MerchantRequestID'] ?? null,

    'checkout_request_id' =>
        $response['CheckoutRequestID'] ?? null,

    'receipt_number' => 'TEMP-' . uniqid(),

    'payment_date' => now()->toDateString(),

    'status' => 'pending',

    'received_by' => Auth::id(),
]);

    return redirect()
        ->route('payments.index')
        ->with(
            'success',
            'M-Pesa payment request sent. Please complete the payment on the phone.'
        );
}
}
