@extends('layouts.app')

@section('title', 'Payment Report')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
 <div class="report-page-header d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2 class="fw-bold mb-1">
            Payment Report
        </h2>

        <p class="text-muted mb-0">
            View and analyze completed student payments.
        </p>
    </div>

  <div class="d-flex gap-2">
    <button type="button"
            class="btn btn-outline-secondary"
            onclick="window.print()">
        <i class="bi bi-printer me-1"></i>
        Print Report
    </button>

    <a href="{{ route('reports.payments.export', request()->query()) }}"
       class="btn btn-success">
        <i class="bi bi-download me-1"></i>
        Export CSV
    </a>
</div>

</div>

  @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


<div class="report-print-header text-center mb-4">
    <h3 class="fw-bold mb-1">
        STUDENT MANAGEMENT SYSTEM
    </h3>

    <h5 class="mb-1">
        PAYMENT REPORT
    </h5>

    <small class="text-muted">
        Generated on {{ now()->format('d M Y H:i') }}
    </small>
</div>

    {{-- Filters --}}
  <div class="report-filters card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-bottom py-3">

            <h5 class="fw-bold mb-0">
                <i class="bi bi-funnel me-2 text-primary"></i>
                Report Filters
            </h5>

        </div>

        <div class="card-body">

            <form method="GET"
                  action="{{ route('reports.payments') }}">

                <div class="row g-3 align-items-end">

                    {{-- From Date --}}
                    <div class="col-md-3">

                        <label for="from"
                               class="form-label fw-semibold">
                            From Date
                        </label>

                        <input type="date"
                               name="from"
                               id="from"
                               class="form-control"
                               value="{{ request('from') }}">

                    </div>


                    {{-- To Date --}}
                    <div class="col-md-3">

                        <label for="to"
                               class="form-label fw-semibold">
                            To Date
                        </label>

                        <input type="date"
                               name="to"
                               id="to"
                               class="form-control"
                               value="{{ request('to') }}">

                    </div>


                    {{-- Payment Method --}}
                    <div class="col-md-3">

                        <label for="payment_method"
                               class="form-label fw-semibold">
                            Payment Method
                        </label>

                        <select name="payment_method"
                                id="payment_method"
                                class="form-select">

                            <option value="">
                                All Methods
                            </option>

                            <option value="mpesa"
                                @selected(request('payment_method') === 'mpesa')>
                                M-Pesa
                            </option>

                            <option value="cash"
                                @selected(request('payment_method') === 'cash')>
                                Cash
                            </option>

                            <option value="bank"
                                @selected(request('payment_method') === 'bank')>
                                Bank
                            </option>

                            <option value="cheque"
                                @selected(request('payment_method') === 'cheque')>
                                Cheque
                            </option>

                        </select>

                    </div>


                    {{-- Buttons --}}
                    <div class="col-md-3">

                        <div class="d-flex gap-2">

                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="bi bi-search me-1"></i>
                                Generate Report

                            </button>

                            <a href="{{ route('reports.payments') }}"
                               class="btn btn-outline-secondary">

                                <i class="bi bi-arrow-clockwise"></i>

                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Report Summary --}}
<div class="row g-3 mb-4">

    {{-- Total Collected --}}
    <div class="col-md-3">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <small class="text-muted">
                    Total Collected
                </small>

                <h4 class="fw-bold text-success mt-2 mb-0">
                    KSh {{ number_format($totalCollected, 2) }}
                </h4>

            </div>

        </div>

    </div>


    {{-- M-Pesa --}}
    <div class="col-md-3">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <small class="text-muted">
                    M-Pesa
                </small>

                <h4 class="fw-bold mt-2 mb-0">
                    KSh
                    {{ number_format($totalsByMethod->get('mpesa', 0), 2) }}
                </h4>

            </div>

        </div>

    </div>


    {{-- Cash --}}
    <div class="col-md-3">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <small class="text-muted">
                    Cash
                </small>

                <h4 class="fw-bold mt-2 mb-0">
                    KSh
                    {{ number_format($totalsByMethod->get('cash', 0), 2) }}
                </h4>

            </div>

        </div>

    </div>


    {{-- Bank --}}
    <div class="col-md-3">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <small class="text-muted">
                    Bank
                </small>

                <h4 class="fw-bold mt-2 mb-0">
                    KSh
                    {{ number_format($totalsByMethod->get('bank', 0), 2) }}
                </h4>

            </div>

        </div>

    </div>

   {{--Cheque--}}
    <div class="col-md-6 col-xl-3">
    <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
            <small class="text-muted">
                Cheque
            </small>

            <h4 class="fw-bold mb-0">
                KSh {{ number_format(
                    $totalsByMethod->get('cheque', 0),
                    2
                ) }}
            </h4>
        </div>
    </div>
</div>

 {{--Total Payments--}}
    <div class="col-md-6 col-xl-3">
    <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
            <small class="text-muted">
                Total Payments
            </small>

            <h4 class="fw-bold mb-0">
                {{ number_format($totalPayments) }}
            </h4>
        </div>
    </div>
</div>

</div>

<div class="card border-0 shadow-sm">

    <div class="card-header bg-white border-bottom py-3">

        <h5 class="fw-bold mb-0">
            <i class="bi bi-table me-2 text-primary"></i>
            Payment Details
        </h5>

    </div>

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th>Receipt</th>

                        <th>Date</th>

                        <th>Student</th>

                        <th>Course</th>

                        <th>Method</th>

                        <th>Reference</th>

                        <th class="text-end">
                            Amount
                        </th>

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

                                <div class="fw-semibold">
                                    {{ $payment->student->first_name }}
                                    {{ $payment->student->last_name }}
                                </div>

                                <small class="text-muted">
                                    {{ $payment->student->student_number }}
                                </small>

                            </td>

                            <td>

                                {{ $payment->feeStructure->course->course_name }}

                                <small class="text-muted d-block">
                                    {{ $payment->feeStructure->course->course_code }}
                                </small>

                            </td>

                            <td>
                                {{ ucfirst($payment->payment_method) }}
                            </td>

                            <td>
                                {{ $payment->transaction_reference ?? '—' }}
                            </td>

                            <td class="text-end fw-semibold">

                                KSh
                                {{ number_format($payment->amount, 2) }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7"
                                class="text-center py-5">

                                <i class="bi bi-receipt fs-1 text-muted"></i>

                                <h6 class="mt-3">
                                    No payments found
                                </h6>

                                <p class="text-muted mb-0">
                                    No completed payments match the selected filters.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@if($payments->hasPages())

    <div class="mt-4">

        {{ $payments->links() }}

    </div>

@endif

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
        .navbar,
        .offcanvas,
        .btn,
        .report-page-header,
        .report-filters,
        .pagination {
            display: none !important;
        }

        .container-fluid {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .report-print-header {
            display: block !important;
        }

        .card {
            box-shadow: none !important;
            border: 1px solid #dee2e6 !important;
            break-inside: avoid;
        }

        table {
            width: 100% !important;
        }
    }
</style>
@endpush