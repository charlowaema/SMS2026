<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\FeeStructure;
use App\Models\Payment;
use App\Services\MpesaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MpesaController extends Controller
{
    
 
public function callback(Request $request)
{
    $data = $request->all();

    Log::info('M-Pesa Callback Received', $data);

    $callback = $data['Body']['stkCallback'] ?? null;

    if (!$callback) {
        Log::warning('Invalid M-Pesa callback structure');

        return response()->json([
            'ResultCode' => 1,
            'ResultDesc' => 'Invalid callback data',
        ]);
    }

    $checkoutRequestId = $callback['CheckoutRequestID'] ?? null;
    $resultCode = $callback['ResultCode'] ?? null;

    if (!$checkoutRequestId || $resultCode === null) {
        return response()->json([
            'ResultCode' => 1,
            'ResultDesc' => 'Missing callback information',
        ]);
    }

    try {
        DB::transaction(function () use (
            $callback,
            $checkoutRequestId,
            $resultCode
        ) {
            $payment = Payment::where(
                'checkout_request_id',
                $checkoutRequestId
            )
            ->lockForUpdate()
            ->first();

            if (!$payment) {
                Log::warning('M-Pesa payment not found', [
                    'checkout_request_id' => $checkoutRequestId,
                ]);

                return;
            }

            // Prevent processing an already completed payment.
            if ($payment->status === 'completed') {
                Log::info('Duplicate M-Pesa callback ignored', [
                    'payment_id' => $payment->id,
                ]);

                return;
            }

            // Successful STK Push
            if ((int) $resultCode === 0) {

                $metadata = collect(
                    $callback['CallbackMetadata']['Item'] ?? []
                )->keyBy('Name');

                $amount = $metadata->get('Amount')['Value'] ?? null;

                $receipt = $metadata->get('MpesaReceiptNumber')['Value'] ?? null;

                $transactionDate = $metadata->get('TransactionDate')['Value'] ?? null;

                $phone = $metadata->get('PhoneNumber')['Value'] ?? null;

                // Validate essential callback information.
                if (
                    $amount === null ||
                    !$receipt ||
                    !$transactionDate
                ) {
                    Log::error('Incomplete successful M-Pesa callback', [
                        'payment_id' => $payment->id,
                    ]);

                    return;
                }

                // Confirm Safaricom returned the requested amount.
                if (
                    round((float) $amount, 2) !==
                    round((float) $payment->amount, 2)
                ) {
                    Log::error('M-Pesa amount mismatch', [
                        'payment_id' => $payment->id,
                        'expected_amount' => $payment->amount,
                        'received_amount' => $amount,
                    ]);

                    $payment->update([
                        'status' => 'failed',
                    ]);

                    return;
                }

                // Convert Safaricom date: YYYYMMDDHHMMSS
                $paymentDate = Carbon::createFromFormat(
                    'YmdHis',
                    (string) $transactionDate
                )->toDateString();

                $payment->update([
                    'status' => 'completed',
                    'transaction_reference' => $receipt,
                    'receipt_number' => 'RCP' . str_pad(
                        $payment->id,
                        6,
                        '0',
                        STR_PAD_LEFT
                    ),
                    'payment_date' => $paymentDate,
                ]);

                Log::info('M-Pesa payment completed', [
                    'payment_id' => $payment->id,
                    'receipt' => $receipt,
                    'phone' => $phone,
                    'amount' => $amount,
                ]);

            } else {

                // Failed or cancelled STK Push
                $payment->update([
                    'status' => 'failed',
                ]);

                Log::warning('M-Pesa payment failed', [
                    'payment_id' => $payment->id,
                    'result_code' => $resultCode,
                    'description' => $callback['ResultDesc'] ?? null,
                ]);
            }
        });

    } catch (\Throwable $e) {

        Log::error('M-Pesa callback processing error', [
            'checkout_request_id' => $checkoutRequestId,
            'error' => $e->getMessage(),
        ]);

        return response()->json([
            'ResultCode' => 1,
            'ResultDesc' => 'Callback processing failed',
        ]);
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

    'receipt_number' => null,

    'payment_date' => null,

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
