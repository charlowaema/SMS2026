@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid">

    <h1 class="mb-4">
        Dashboard
    </h1>

    <div class="alert alert-primary">
        Welcome to the Student Management System.
    </div>

    <div class="row">

        {{-- Total Students --}}
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card shadow-sm border-0 h-100 dashboard-card card-blue">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <h6 class="text-muted mb-2">
                                Total Students
                            </h6>

                            <h3 class="fw-bold mb-0">
                               {{ $totalStudents }}
                            </h3>
                        </div>

                       <i class="bi bi-people fs-1"></i>

                    </div>
                </div>
            </div>
        </div>

        {{-- Total Courses --}}
        <div class="col-md-6 col-lg-3 mb-4">
           <div class="card shadow-sm border-0 h-100 dashboard-card card-purple">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <h6 class="text-muted mb-2">
                                Total Courses
                            </h6>

                            <h3 class="fw-bold mb-0">
                               {{ $totalCourses }}
                            </h3>
                        </div>

                        <i class="bi bi-book fs-1"></i>

                    </div>
                </div>
            </div>
        </div>

{{-- Total Payments --}}
        <div class="col-md-6 col-lg-3 mb-4">
    <div class="card dashboard-card card-purple border-0 shadow-sm h-100">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">
                        Total Payments
                    </small>

                    <h3 class="fw-bold mb-0">
                        {{ $totalPayments }}
                    </h3>
                </div>

                <i class="bi bi-credit-card fs-1"></i>
            </div>
        </div>
    </div>
</div>

        {{-- Fees Collected --}}
        <div class="col-md-6 col-lg-3 mb-4">
    <div class="card dashboard-card card-green border-0 shadow-sm h-100">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">
                        Total Collected
                    </small>

                    <h3 class="fw-bold mb-0">
                        KSh {{ number_format($totalCollected, 2) }}
                    </h3>
                </div>

                <i class="bi bi-cash-stack fs-1"></i>
            </div>
        </div>
    </div>
</div>

        {{-- Outstanding Fees --}}
        <div class="col-md-6 col-lg-3 mb-4">
    <div class="card dashboard-card card-red border-0 shadow-sm h-100">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">
                        Outstanding Fees
                    </small>

                    <h3 class="fw-bold mb-0">
                        KSh {{ number_format($outstandingFees, 2) }}
                    </h3>
                </div>

                <i class="bi bi-exclamation-circle fs-1"></i>
            </div>
        </div>
    </div>
</div>

    </div>

    <div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="bi bi-bar-chart me-2"></i>
            Payment Collection by Method
        </h5>
    </div>

    <div class="card-body">
        <div class="row g-3">

            <div class="col-md-6">
                <div class="border rounded p-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>M-Pesa</span>
                        <strong>
                            KSh {{ number_format(
                                $paymentTotals->get('mpesa', 0),
                                2
                            ) }}
                        </strong>
                    </div>

                    <div class="progress">
                        <div class="progress-bar"
                             role="progressbar"
                             style="width:
                                {{ $totalCollected > 0
                                    ? ($paymentTotals->get('mpesa', 0) / $totalCollected) * 100
                                    : 0
                                }}%">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="border rounded p-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Cash</span>
                        <strong>
                            KSh {{ number_format(
                                $paymentTotals->get('cash', 0),
                                2
                            ) }}
                        </strong>
                    </div>

                    <div class="progress">
                        <div class="progress-bar bg-success"
                             role="progressbar"
                             style="width:
                                {{ $totalCollected > 0
                                    ? ($paymentTotals->get('cash', 0) / $totalCollected) * 100
                                    : 0
                                }}%">
                        </div>
                    </div>
                </div>
            </div>

                    <div class="col-md-6">
                <div class="border rounded p-3">

                    <div class="d-flex justify-content-between mb-2">
                        <span>Bank</span>

                        <strong>
                            KSh {{ number_format(
                                $paymentTotals->get('bank', 0),
                                2
                            ) }}
                        </strong>
                    </div>

                    <div class="progress">
                        <div class="progress-bar bg-info"
                            role="progressbar"
                            style="width:
                                {{ $totalCollected > 0
                                    ? ($paymentTotals->get('bank', 0) / $totalCollected) * 100
                                    : 0
                                }}%">
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-md-6">
                <div class="border rounded p-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Cheque</span>
                        <strong>
                            KSh {{ number_format(
                                $paymentTotals->get('cheque', 0),
                                2
                            ) }}
                        </strong>
                    </div>

                    <div class="progress">
                        <div class="progress-bar bg-warning"
                             role="progressbar"
                             style="width:
                                {{ $totalCollected > 0
                                    ? ($paymentTotals->get('cheque', 0) / $totalCollected) * 100
                                    : 0
                                }}%">
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="bi bi-bar-chart-fill me-2"></i>
            Payment Collection Chart
        </h5>
    </div>

    <div class="card-body">

        <div style="position: relative; height: 350px;">
            <canvas
                id="paymentCollectionChart"

                data-mpesa="{{ $paymentTotals->get('mpesa', 0) }}"
                data-cash="{{ $paymentTotals->get('cash', 0) }}"
                data-bank="{{ $paymentTotals->get('bank', 0) }}"
                data-cheque="{{ $paymentTotals->get('cheque', 0) }}"
            ></canvas>
        </div>

    </div>
</div>


<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="bi bi-credit-card me-2"></i>
            Recent Payments
        </h5>

        <a href="{{ route('payments.index') }}"
           class="btn btn-sm btn-outline-primary">
            View All
        </a>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Receipt</th>
                        <th>Student</th>
                        <th>Course</th>
                        <th>Amount</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($recentPayments as $payment)
                        <tr>
                            <td>
                                {{ $payment->receipt_number }}
                            </td>

                            <td>
                                {{ $payment->student->first_name }}
                                {{ $payment->student->last_name }}
                            </td>

                            <td>
                                {{ $payment->feeStructure->course->course_name }}
                            </td>

                            <td class="fw-semibold">
                                KSh {{ number_format($payment->amount, 2) }}
                            </td>

                            <td>
                                {{ $payment->payment_date->format('d M Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5"
                                class="text-center text-muted py-4">
                                No completed payments found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

</div>

@endsection