@extends('layouts.app')

@section('title', 'Payments')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between
                align-items-md-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">Payments</h2>

            <p class="text-muted mb-0">
                Manage student fee payments and transactions.
            </p>
        </div>

        <a href="{{ route('payments.create') }}"
           class="btn school-primary mt-3 mt-md-0">

            <i class="bi bi-plus-circle me-1"></i>
            Record Payment

        </a>

    </div>


    {{-- Summary Cards --}}
    <div class="row g-4 mb-4">

        {{-- Total Collected --}}
        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between
                                align-items-center">

                        <div>
                            <small class="text-muted">
                                Total Collected
                            </small>

                            <h3 class="fw-bold mb-0">
                                KSh {{ number_format($totalCollected, 2) }}
                            </h3>
                        </div>

                        <i class="bi bi-cash-stack fs-1 text-success"></i>

                    </div>

                </div>

            </div>

        </div>


        {{-- This Month --}}
        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between
                                align-items-center">

                        <div>
                            <small class="text-muted">
                                This Month
                            </small>

                            <h3 class="fw-bold mb-0">
                                KSh {{ number_format($thisMonth, 2) }}
                            </h3>
                        </div>

                        <i class="bi bi-calendar-check fs-1 text-primary"></i>

                    </div>

                </div>

            </div>

        </div>


        {{-- Pending M-Pesa --}}
        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between
                                align-items-center">

                        <div>
                            <small class="text-muted">
                                Pending M-Pesa
                            </small>

                            <h3 class="fw-bold mb-0">
                                KSh {{ number_format($pendingMpesa, 2) }}
                            </h3>
                        </div>

                        <i class="bi bi-phone fs-1 text-warning"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">

        <form method="GET" action="{{ route('payments.index') }}">

            <div class="row g-3">

                {{-- Search --}}
                <div class="col-md-5">

                    <label for="search" class="form-label">
                        Search
                    </label>

                    <input type="text"
                           name="search"
                           id="search"
                           class="form-control"
                           value="{{ request('search') }}"
                           placeholder="Student name or student number">

                </div>


                {{-- Status --}}
                <div class="col-md-3">

                    <label for="status" class="form-label">
                        Status
                    </label>

                    <select name="status"
                            id="status"
                            class="form-select">

                        <option value="">
                            All Statuses
                        </option>

                        <option value="completed"
                            {{ request('status') === 'completed' ? 'selected' : '' }}>
                            Completed
                        </option>

                        <option value="pending"
                            {{ request('status') === 'pending' ? 'selected' : '' }}>
                            Pending
                        </option>

                        <option value="failed"
                            {{ request('status') === 'failed' ? 'selected' : '' }}>
                            Failed
                        </option>

                    </select>

                </div>


                {{-- Payment Method --}}
                <div class="col-md-3">

                    <label for="payment_method" class="form-label">
                        Payment Method
                    </label>

                    <select name="payment_method"
                            id="payment_method"
                            class="form-select">

                        <option value="">
                            All Methods
                        </option>

                        <option value="mpesa"
                            {{ request('payment_method') === 'mpesa' ? 'selected' : '' }}>
                            M-Pesa
                        </option>

                        <option value="cash"
                            {{ request('payment_method') === 'cash' ? 'selected' : '' }}>
                            Cash
                        </option>

                        <option value="bank"
                            {{ request('payment_method') === 'bank' ? 'selected' : '' }}>
                            Bank
                        </option>

                        <option value="cheque"
                            {{ request('payment_method') === 'cheque' ? 'selected' : '' }}>
                            Cheque
                        </option>

                    </select>

                </div>


                {{-- Buttons --}}
                <div class="col-md-1 d-flex align-items-end">

                    <button type="submit"
                            class="btn btn-primary w-100">
                        <i class="bi bi-search"></i>
                    </button>

                </div>

            </div>

        </form>

    </div>
</div>
    {{-- Payments Table --}}
   {{-- Payments Table --}}
<div class="card border-0 shadow-sm">

    <div class="card-header bg-white border-bottom py-3">

        <div class="d-flex flex-column flex-md-row
                    justify-content-between align-items-md-center">

            <div>
                <h5 class="fw-bold mb-1">
                    Payment Transactions
                </h5>

                <small class="text-muted">
                    Recent student fee payments
                </small>
            </div>

        </div>

    </div>


    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-light">

                    <tr>
                        <th>Student</th>
                        <th>Receipt</th>
                        <th>Fee Structure</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Reference</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>

                </thead>

                <tbody id="paymentsTableBody">

                    @forelse($payments as $payment)

                       <tr
                            data-payment-id="{{ $payment->id }}"
                            data-payment-status="{{ $payment->status }}"
                            @if(request('payment') == $payment->id)
                                class="table-warning"
                            @endif
                        >

                            {{-- Student --}}
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
                                <span class="fw-semibold payment-receipt">
                                        {{ $payment->receipt_number }}
                                </span>
                            </td>

                            {{-- Fee Structure --}}
                            <td>

                                <div class="fw-semibold">
                                    {{ $payment->feeStructure->course->course_code }}
                                </div>

                                <small class="text-muted">
                                    {{ $payment->feeStructure->semester->name }}
                                    -
                                    {{ $payment->feeStructure->academicYear->name }}
                                </small>

                            </td>


                            {{-- Amount --}}
                            <td>
                                <span class="fw-semibold">
                                    KSh {{ number_format($payment->amount, 2) }}
                                </span>
                            </td>


                            {{-- Payment Method --}}
                            <td>

                                @if($payment->payment_method === 'mpesa')

                                    <span class="badge bg-success">
                                        <i class="bi bi-phone me-1"></i>
                                        M-Pesa
                                    </span>

                                @elseif($payment->payment_method === 'cash')

                                    <span class="badge bg-secondary">
                                        <i class="bi bi-cash me-1"></i>
                                        Cash
                                    </span>

                                @elseif($payment->payment_method === 'bank')

                                    <span class="badge bg-primary">
                                        <i class="bi bi-bank me-1"></i>
                                        Bank
                                    </span>

                                @elseif($payment->payment_method === 'cheque')

                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-receipt me-1"></i>
                                        Cheque
                                    </span>

                                @else

                                    <span class="badge bg-dark">
                                        {{ ucfirst($payment->payment_method) }}
                                    </span>

                                @endif

                            </td>


                            {{-- Reference --}}
                            <td class="payment-reference">
                                {{ $payment->transaction_reference ?? '—' }}
                            </td>


                            {{-- Date --}}
                            <td>
                                {{ $payment->payment_date?->format('d M Y') ?? '—' }}
                            </td>


                            {{-- Status --}}
                            <td class="payment-status">

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

                            </td>


                            {{-- Action --}}
                            <td class="text-end">

                                <div class="d-flex gap-1">

                                    <a href="{{ route('payments.show', $payment) }}"
                                    class="btn btn-sm btn-outline-primary"
                                    title="View Payment">
                                        <i class="bi bi-eye me-1"></i>
                                        View
                                    </a>

                                    <a href="{{ route('payments.show', $payment) }}"
                                    class="btn btn-sm btn-outline-secondary"
                                    title="Print Receipt">
                                        <i class="bi bi-printer"></i>
                                    </a>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                           <td colspan="9" class="text-center py-5">

                                <i class="bi bi-receipt fs-1 text-muted"></i>

                                <p class="text-muted mt-2 mb-0">
                                    No payments have been recorded yet.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        <div class="mt-3">

            {{ $payments->links() }}

        </div>

    </div>

</div>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const pendingRows = document.querySelectorAll(
        '[data-payment-status="pending"]'
    );

    pendingRows.forEach(function (row) {

        const paymentId = row.dataset.paymentId;

        if (paymentId) {
            pollPaymentStatus(paymentId, row);
        }

    });


    function pollPaymentStatus(paymentId, row) {

        let attempts = 0;
        const maxAttempts = 60;

        const interval = setInterval(function () {

            attempts++;

            fetch(`{{ url('/payments') }}/${paymentId}/status`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {

                if (!response.ok) {
                    throw new Error('Unable to retrieve payment status.');
                }

                return response.json();

            })
            .then(data => {

                /*
                 * Payment completed
                 */
                if (data.status === 'completed') {

                    clearInterval(interval);

                    updatePaymentRow(row, data);

                    row.dataset.paymentStatus = 'completed';

                    row.classList.remove('table-warning');

                    row.classList.add('table-success');

                    setTimeout(function () {
                        row.classList.remove('table-success');
                    }, 3000);

                    return;
                }


                /*
                 * Payment failed
                 */
                if (data.status === 'failed') {

                    clearInterval(interval);

                    updatePaymentRow(row, data);

                    row.dataset.paymentStatus = 'failed';

                    return;
                }


                /*
                 * Stop polling after 3 minutes
                 */
                if (attempts >= maxAttempts) {

                    clearInterval(interval);

                }

            })
            .catch(error => {

                console.error(
                    'Unable to check payment status:',
                    error
                );

            });

        }, 3000);
    }


    function updatePaymentRow(row, data) {

        /*
         * Update reference
         */
        const referenceCell =
            row.querySelector('.payment-reference');

        if (referenceCell) {

            referenceCell.textContent =
                data.transaction_reference ?? '—';

        }


        /*
         * Update receipt
         */
        const receiptCell =
            row.querySelector('.payment-receipt');

        if (receiptCell) {

            receiptCell.textContent =
                data.receipt_number ?? '—';

        }


        /*
         * Update status
         */
        const statusCell =
            row.querySelector('.payment-status');

        if (statusCell) {

            if (data.status === 'completed') {

                statusCell.innerHTML = `
                    <span class="badge bg-success">
                        Completed
                    </span>
                `;

            } else if (data.status === 'failed') {

                statusCell.innerHTML = `
                    <span class="badge bg-danger">
                        Failed
                    </span>
                `;

            } else {

                statusCell.innerHTML = `
                    <span class="badge bg-warning text-dark">
                        Pending
                    </span>
                `;
            }
        }
    }

});
</script>
@endpush

@endsection