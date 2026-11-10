@extends('layouts.app')

@section('title', 'Student Fee Statement')

@section('content')

<div class="container-fluid">


<div class="statement-header mb-4">

    <div class="text-center">

        <h3 class="fw-bold mb-1">
            STUDENT MANAGEMENT SYSTEM
        </h3>

        <p class="mb-1">
            STUDENT FEE STATEMENT
        </p>

        <small class="text-muted">
            Generated on {{ now()->format('d M Y H:i') }}
        </small>

    </div>

</div>



    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Student Fee Statement
            </h2>

            <p class="text-muted mb-0">
                Complete payment history
            </p>
        </div>

        <div class="d-flex gap-2">

            <button type="button"
                    class="btn btn-primary"
                    onclick="window.print()">
                <i class="bi bi-printer me-1"></i>
                Print Statement
            </button>

            <a href="{{ route('payments.index') }}"
               class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                Back
            </a>

        </div>

    </div>

    <div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white border-bottom py-3">

        <h5 class="fw-bold mb-0">
            <i class="bi bi-person me-2 text-primary"></i>
            Student Information
        </h5>

    </div>

    <div class="card-body">

        <div class="row g-4">

            <div class="col-md-4">

                <small class="text-muted d-block mb-1">
                    Student Number
                </small>

                <div class="fw-semibold">
                    {{ $student->student_number }}
                </div>

            </div>

            <div class="col-md-4">

                <small class="text-muted d-block mb-1">
                    Student Name
                </small>

                <div class="fw-semibold">
                    {{ $student->first_name }}
                    {{ $student->last_name }}
                </div>

            </div>

            <div class="col-md-4">

                <small class="text-muted d-block mb-1">
                    Phone
                </small>

                <div class="fw-semibold">
                    {{ $student->phone ?? '—' }}
                </div>

            </div>

        </div>

    </div>

</div>

<div class="row g-4 mb-4">

    <div class="col-md-4">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <small class="text-muted">
                    Total Fees
                </small>

                <h4 class="fw-bold mt-2">
                    KSh {{ number_format($totalFees, 2) }}
                </h4>

            </div>

        </div>

    </div>


    <div class="col-md-4">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <small class="text-muted">
                    Total Paid
                </small>

                <h4 class="fw-bold text-success mt-2">
                    KSh {{ number_format($totalPaid, 2) }}
                </h4>

            </div>

        </div>

    </div>


    <div class="col-md-4">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <small class="text-muted">
                    Outstanding Balance
                </small>

                <h4 class="fw-bold text-danger mt-2">
                    KSh {{ number_format($balance, 2) }}
                </h4>

            </div>

        </div>

    </div>

</div>


<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white border-bottom py-3">

        <h5 class="fw-bold mb-0">
            <i class="bi bi-list-check text-primary me-2"></i>
            Fee Breakdown
        </h5>

    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-light">

                    <tr>
                        <th>Course</th>
                        <th>Semester</th>
                        <th>Academic Year</th>
                        <th class="text-end">Total Fee</th>
                        <th class="text-end">Paid</th>
                        <th class="text-end">Balance</th>
                        <th>Status</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($statementItems as $item)

                        <tr>

                            <td>
                                <div class="fw-semibold">
                                    {{ $item['feeStructure']->course->course_name }}
                                </div>

                                <small class="text-muted">
                                    {{ $item['feeStructure']->course->course_code }}
                                </small>
                            </td>

                            <td>
                                {{ $item['feeStructure']->semester->name }}
                            </td>

                            <td>
                                {{ $item['feeStructure']->academicYear->name }}
                            </td>

                            <td class="text-end">
                                KSh {{ number_format($item['totalFee'], 2) }}
                            </td>

                            <td class="text-end text-success">
                                KSh {{ number_format($item['paid'], 2) }}
                            </td>

                            <td class="text-end text-danger">
                                KSh {{ number_format($item['balance'], 2) }}
                            </td>

                            <td>

                                @if($item['balance'] <= 0)

                                    <span class="badge bg-success">
                                        Paid
                                    </span>

                                @elseif($item['paid'] > 0)

                                    <span class="badge bg-warning text-dark">
                                        Partially Paid
                                    </span>

                                @else

                                    <span class="badge bg-danger">
                                        Unpaid
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                No fee structures found for this student.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white border-bottom py-3">

        <h5 class="fw-bold mb-0">
            <i class="bi bi-clock-history text-primary me-2"></i>
            Payment History by Fee
        </h5>

    </div>

    <div class="card-body">

        @forelse($statementItems as $item)

            <div class="border rounded p-3 mb-3">

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <div>
                        <h6 class="fw-bold mb-1">
                            {{ $item['feeStructure']->course->course_name }}
                        </h6>

                        <small class="text-muted">
                            {{ $item['feeStructure']->semester->name }}
                            —
                            {{ $item['feeStructure']->academicYear->name }}
                        </small>
                    </div>

                    <div class="text-end">
                        <small class="text-muted d-block">
                            Balance
                        </small>

                        <strong class="text-danger">
                            KSh {{ number_format($item['balance'], 2) }}
                        </strong>
                    </div>

                </div>

                @if($item['payments']->count())

                    <div class="table-responsive">

                        <table class="table table-sm align-middle mb-0">

                            <thead class="table-light">

                                <tr>
                                    <th>Date</th>
                                    <th>Receipt</th>
                                    <th>Method</th>
                                    <th>Reference</th>
                                    <th class="text-end">Amount</th>
                                </tr>

                            </thead>

                            <tbody>

                                @foreach($item['payments'] as $payment)

                                    <tr>

                                        <td>
                                            {{ $payment->payment_date?->format('d M Y') }}
                                        </td>

                                        <td>
                                            {{ $payment->receipt_number }}
                                        </td>

                                        <td>
                                            {{ ucfirst($payment->payment_method) }}
                                        </td>

                                        <td>
                                            {{ $payment->transaction_reference ?? '—' }}
                                        </td>

                                        <td class="text-end text-success fw-semibold">
                                            KSh {{ number_format($payment->amount, 2) }}
                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    <div class="alert alert-light border mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        No payments have been made toward this fee.
                    </div>

                @endif

            </div>

        @empty

            <p class="text-muted mb-0">
                No fee information is available.
            </p>

        @endforelse

    </div>

</div>



<!-- <div class="card border-0 shadow-sm">

    <div class="card-header bg-white border-bottom py-3">

        <h5 class="fw-bold mb-0">
            <i class="bi bi-receipt me-2 text-primary"></i>
            Payment History
        </h5>

    </div>

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>
                        <th>Receipt</th>
                        <th>Date</th>
                        <th>Fee Structure</th>
                        <th>Method</th>
                        <th>Reference</th>
                        <th class="text-end">Amount</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($payments as $payment)

                        <tr>

                            <td class="fw-semibold">
                                {{ $payment->receipt_number }}
                            </td>

                            <td>
                                {{ $payment->payment_date?->format('d M Y') }}
                            </td>

                            <td>
                                {{ $payment->feeStructure->course->course_name }}

                                <small class="text-muted d-block">
                                    {{ $payment->feeStructure->semester->name }}
                                    -
                                    {{ $payment->feeStructure->academicYear->name }}
                                </small>
                            </td>

                            <td>
                                {{ ucfirst($payment->payment_method) }}
                            </td>

                            <td>
                                {{ $payment->transaction_reference ?? '—' }}
                            </td>

                            <td class="text-end fw-semibold">
                                KSh {{ number_format($payment->amount, 2) }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6"
                                class="text-center py-5">

                                <i class="bi bi-receipt fs-1 text-muted"></i>

                                <h6 class="mt-3">
                                    No completed payments
                                </h6>

                                <p class="text-muted mb-0">
                                    This student has no completed payments.
                                </p>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div> -->

</div>

@endsection

@push('styles')

<style>

    @media print {

        body {
            background: #ffffff !important;
        }

        .school-navbar,
        .school-sidebar,
        .btn,
        .navbar,
        .offcanvas {
            display: none !important;
        }

        .container-fluid {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .card {
            box-shadow: none !important;
            border: 1px solid #dee2e6 !important;
            break-inside: avoid;
        }

        .statement-header {
            margin-bottom: 25px !important;
        }

        table {
            width: 100% !important;
        }

    }

</style>

@endpush