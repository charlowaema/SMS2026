@extends('layouts.app')

@section('title', 'Payment Details')

@section('content')

<div class="container-fluid payment-receipt">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Payment Details
            </h2>

            <p class="text-muted mb-0">
                Receipt: {{ $payment->receipt_number }}
            </p>
        </div>

            <div class="d-flex gap-2">

            <button type="button"
                    class="btn btn-primary"
                    onclick="window.print()">
                <i class="bi bi-printer me-1"></i>
                Print Receipt
            </button>

            <a href="{{ route('payments.index') }}"
            class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                Back
            </a>

           </div>

    </div>


    {{-- Payment Summary --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-bottom py-3">

            <h5 class="fw-bold mb-0">
                <i class="bi bi-receipt text-primary me-2"></i>
                Payment Information
            </h5>

        </div>

        <div class="card-body">

            <div class="row g-4">

                {{-- Receipt --}}
                <div class="col-md-4">

                    <small class="text-muted d-block mb-1">
                        Receipt Number
                    </small>

                    <div class="fw-bold">
                        {{ $payment->receipt_number ?? '—' }}
                    </div>

                </div>


                {{-- Amount --}}
                <div class="col-md-4">

                    <small class="text-muted d-block mb-1">
                        Amount Paid
                    </small>

                    <div class="fw-bold fs-5">
                        KSh {{ number_format($payment->amount, 2) }}
                    </div>

                </div>


                {{-- Status --}}
                <div class="col-md-4">

                    <small class="text-muted d-block mb-1">
                        Status
                    </small>

                    @if($payment->status === 'completed')

                        <span class="badge bg-success">
                            Completed
                        </span>

                    @elseif($payment->status === 'pending')

                        <span class="badge bg-warning text-dark">
                            Pending
                        </span>

                    @elseif($payment->status === 'failed')

                        <span class="badge bg-danger">
                            Failed
                        </span>

                    @else

                        <span class="badge bg-secondary">
                            {{ ucfirst($payment->status) }}
                        </span>

                    @endif

                </div>


                {{-- Payment Method --}}
                <div class="col-md-4">

                    <small class="text-muted d-block mb-1">
                        Payment Method
                    </small>

                    <div class="fw-semibold">
                        {{ ucfirst($payment->payment_method) }}
                    </div>

                </div>


                {{-- Transaction Reference --}}
                <div class="col-md-4">

                    <small class="text-muted d-block mb-1">
                        Transaction Reference
                    </small>

                    <div class="fw-semibold">
                        {{ $payment->transaction_reference ?? '—' }}
                    </div>

                </div>


                {{-- Payment Date --}}
                <div class="col-md-4">

                    <small class="text-muted d-block mb-1">
                        Payment Date
                    </small>

                    <div class="fw-semibold">
                        {{ $payment->payment_date?->format('d M Y') ?? '—' }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Student Information --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-bottom py-3">

            <h5 class="fw-bold mb-0">
                <i class="bi bi-person text-primary me-2"></i>
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
                        {{ $payment->student->student_number }}
                    </div>

                </div>

                <div class="col-md-4">

                    <small class="text-muted d-block mb-1">
                        Student Name
                    </small>

                    <div class="fw-semibold">
                        {{ $payment->student->first_name }}
                        {{ $payment->student->last_name }}
                    </div>

                </div>

                <div class="col-md-4">

                    <small class="text-muted d-block mb-1">
                        Phone
                    </small>

                    <div>
                        {{ $payment->student->phone ?? '—' }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Fee Information --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-bottom py-3">

            <h5 class="fw-bold mb-0">
                <i class="bi bi-cash-stack text-primary me-2"></i>
                Fee Information
            </h5>

        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <small class="text-muted d-block mb-1">
                        Course
                    </small>

                    <div class="fw-semibold">
                        {{ $payment->feeStructure->course->course_name }}
                    </div>

                    <small class="text-muted">
                        {{ $payment->feeStructure->course->course_code }}
                    </small>

                </div>

                <div class="col-md-4">

                    <small class="text-muted d-block mb-1">
                        Semester
                    </small>

                    <div class="fw-semibold">
                        {{ $payment->feeStructure->semester->name }}
                    </div>

                </div>

                <div class="col-md-4">

                    <small class="text-muted d-block mb-1">
                        Academic Year
                    </small>

                    <div class="fw-semibold">
                        {{ $payment->feeStructure->academicYear->name }}
                    </div>

                </div>

                <div class="col-md-4">

                    <small class="text-muted d-block mb-1">
                        Total Fee
                    </small>

                    <div class="fw-semibold">
                        KSh
                        {{ number_format($payment->feeStructure->amount, 2) }}
                    </div>

                </div>

                <div class="col-md-4">

                    <small class="text-muted d-block mb-1">
                        Total Paid
                    </small>

                    <div class="fw-semibold text-success">
                        KSh {{ number_format($paidAmount, 2) }}
                    </div>

                </div>

                <div class="col-md-4">

                    <small class="text-muted d-block mb-1">
                        Outstanding Balance
                    </small>

                    <div class="fw-bold text-danger">
                        KSh {{ number_format($balance, 2) }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Received By --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <small class="text-muted d-block mb-1">
                Received By
            </small>

            <div class="fw-semibold">
                {{ $payment->receivedBy->name ?? '—' }}
            </div>

        </div>

    </div>

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

        .payment-receipt {
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

        .card-header {
            background: #ffffff !important;
        }

        .text-primary {
            color: #000000 !important;
        }
    }
</style>
@endpush