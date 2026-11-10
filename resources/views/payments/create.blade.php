@extends('layouts.app')

@section('title', 'Record Payment')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="mb-4">
        <h2 class="fw-bold mb-1">Record Payment</h2>

        <p class="text-muted mb-0">
            Record a student fee payment.
        </p>
    </div>


    {{-- Validation Errors --}}
    @if ($errors->any())

        <div class="alert alert-danger">

            <h6 class="fw-bold">
                Please correct the following errors:
            </h6>

            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>

    @endif

<div id="mpesaMessage"></div>

   <form method="POST" action="{{ route('payments.store') }}" id="paymentForm">

        @csrf

        <div class="row g-4">

            {{-- Payment Information --}}
            <div class="col-lg-8">

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white border-bottom py-3">

                        <h5 class="fw-bold mb-0">
                            <i class="bi bi-cash-stack text-primary me-2"></i>
                            Payment Information
                        </h5>

                    </div>

                    <div class="card-body">

                        {{-- Student --}}
                        <div class="mb-4">

                            <label for="student_id"
                                   class="form-label fw-semibold">
                                Student
                            </label>

                            <select name="student_id"
                                    id="student_id"
                                    class="form-select @error('student_id') is-invalid @enderror">

                                <option value="">
                                    Select Student
                                </option>

                                @foreach($students as $student)

                                    <option value="{{ $student->id }}"
                                        {{ old('student_id') == $student->id ? 'selected' : '' }}>

                                        {{ $student->student_number }}
                                        -
                                        {{ $student->first_name }}
                                        {{ $student->last_name }}

                                    </option>

                                @endforeach

                            </select>

                            @error('student_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Fee Structure --}}
                        <div class="mb-4">

                            <label for="fee_structure_id"
                                   class="form-label fw-semibold">

                                Fee Structure

                            </label>

                            <select name="fee_structure_id"
                                    id="fee_structure_id"
                                    class="form-select @error('fee_structure_id') is-invalid @enderror">

                                <option value="">
                                    Select Fee Structure
                                </option>

                                @foreach($feeStructures as $feeStructure)

                                    <option value="{{ $feeStructure->id }}"
                                        {{ old('fee_structure_id') == $feeStructure->id ? 'selected' : '' }}>

                                        {{ $feeStructure->course->course_code }}
                                        -
                                        {{ $feeStructure->semester->name }}
                                        -
                                        {{ $feeStructure->academicYear->name }}
                                        -
                                        KSh {{ number_format($feeStructure->amount, 2) }}

                                    </option>

                                @endforeach

                            </select>

                            @error('fee_structure_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                          {{-- Fee Summary --}}
        <div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="bi bi-calculator me-2"></i>
            Fee Summary
        </h5>
    </div>

    <div class="card-body">
        <div class="row g-3">

            <div class="col-md-4">
                <div class="border rounded p-3">
                    <small class="text-muted">Total Fee</small>
                    <h4 class="mb-0" id="totalFee">
                        KSh 0.00
                    </h4>
                </div>
            </div>

            <div class="col-md-4">
                <div class="border rounded p-3">
                    <small class="text-muted">Paid</small>
                    <h4 class="mb-0 text-success" id="paidAmount">
                        KSh 0.00
                    </h4>
                </div>
            </div>

            <div class="col-md-4">
                <div class="border rounded p-3">
                    <small class="text-muted">Balance</small>
                    <h4 class="mb-0 text-danger" id="balanceAmount">
                        KSh 0.00
                    </h4>
                </div>
            </div>

        </div>
    </div>
</div>


                        {{-- Amount --}}
                        <div class="mb-4">

                            <label for="amount"
                                   class="form-label fw-semibold">

                                Amount

                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    KSh
                                </span>

                               <input type="number"
                                name="amount"
                                id="amount"
                                step="0.01"
                                min="0.01"
                                value="{{ old('amount') }}"
                                class="form-control @error('amount') is-invalid @enderror"
                                placeholder="Enter amount">

                            <div class="form-text" id="amountHelp">
                                Select a student and fee structure to see the outstanding balance.
                            </div>

                            </div>

                            @error('amount')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Payment Method --}}
                        <div class="mb-4">

                            <label for="payment_method"
                                   class="form-label fw-semibold">

                                Payment Method

                            </label>

                    <select name="payment_method"
                        id="payment_method"
                        class="form-select"
                        required>

                    <option value="">Select payment method</option>

                    <option value="mpesa"
                        {{ old('payment_method') === 'mpesa' ? 'selected' : '' }}>
                        M-Pesa
                    </option>

                    <option value="cash"
                        {{ old('payment_method') === 'cash' ? 'selected' : '' }}>
                        Cash
                    </option>

                    <option value="bank"
                        {{ old('payment_method') === 'bank' ? 'selected' : '' }}>
                        Bank
                    </option>

                    <option value="cheque"
                        {{ old('payment_method') === 'cheque' ? 'selected' : '' }}>
                        Cheque
                    </option>

                    </select>

                            @error('payment_method')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="col-md-6">
    <label for="phone" class="form-label">
        M-Pesa Phone Number
    </label>

    <input
        type="text"
        name="phone"
        id="phone"
        class="form-control"
        placeholder="2547XXXXXXXX"
        value="{{ old('phone') }}"
    >

    <div class="form-text">
        Required when paying through M-Pesa.
    </div>

    @error('phone')
        <div class="text-danger small mt-1">
            {{ $message }}
        </div>
    @enderror
</div>


                        {{-- Transaction Reference --}}
                       <div class="mb-4" id="transactionReferenceWrapper">

                            <label for="transaction_reference"
                                   class="form-label fw-semibold">

                                Transaction / Reference Number

                            </label>

                            <input type="text"
                                   name="transaction_reference"
                                   id="transaction_reference"
                                   value="{{ old('transaction_reference') }}"
                                   class="form-control @error('transaction_reference') is-invalid @enderror"
                                   placeholder="e.g. M-Pesa code, bank reference or cheque number">

                            <div class="form-text">
                                Optional for cash payments.
                            </div>

                            @error('transaction_reference')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Payment Date --}}
                        <div class="mb-4">

                            <label for="payment_date"
                                   class="form-label fw-semibold">

                                Payment Date

                            </label>

                            <input type="date"
                                   name="payment_date"
                                   id="payment_date"
                                   value="{{ old('payment_date', now()->format('Y-m-d')) }}"
                                   class="form-control @error('payment_date') is-invalid @enderror">

                            @error('payment_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Buttons --}}
                        <div class="d-flex gap-2">

                            <button type="submit"
                                    class="btn school-primary">

                                <i class="bi bi-check-circle me-1"></i>
                                Record Payment

                            </button>

                            <a href="{{ route('payments.index') }}"
                               class="btn btn-outline-secondary">

                                Cancel

                            </a>

                        </div>

                    </div>

                </div>

            </div>


          

        </div>

    </form>

</div>



@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Form Elements
    |--------------------------------------------------------------------------
    */

    const form = document.getElementById('paymentForm');

    const studentSelect =
        document.getElementById('student_id');

    const feeStructureSelect =
        document.getElementById('fee_structure_id');

    const totalFee =
        document.getElementById('totalFee');

    const paidAmount =
        document.getElementById('paidAmount');

    const balanceAmount =
        document.getElementById('balanceAmount');

    const amountInput =
        document.getElementById('amount');

    const amountHelp =
        document.getElementById('amountHelp');

    const paymentMethod =
        document.getElementById('payment_method');

    const phoneInput =
        document.getElementById('phone');

    const phoneWrapper =
        phoneInput?.closest('.col-md-6');

    const referenceInput =
        document.getElementById('transaction_reference');

    const referenceWrapper =
        document.getElementById('transactionReferenceWrapper');

    const referenceHelp =
        document.getElementById('transactionReferenceHelp');


    /*
    |--------------------------------------------------------------------------
    | Load Fee Summary
    |--------------------------------------------------------------------------
    */

    function loadPaymentSummary() {

        const studentId =
            studentSelect.value;

        const feeStructureId =
            feeStructureSelect.value;

        if (!studentId || !feeStructureId) {

            totalFee.textContent =
                'KSh 0.00';

            paidAmount.textContent =
                'KSh 0.00';

            balanceAmount.textContent =
                'KSh 0.00';

            amountInput.removeAttribute('max');

            amountHelp.textContent =
                'Select a student and fee structure to see the outstanding balance.';

            return;
        }

        fetch(
            `{{ route('payments.summary') }}?student_id=${studentId}&fee_structure_id=${feeStructureId}`
        )
        .then(response => response.json())
        .then(data => {

            totalFee.textContent =
                'KSh ' +
                Number(data.total_fee).toLocaleString(
                    'en-KE',
                    {
                        minimumFractionDigits: 2
                    }
                );

            paidAmount.textContent =
                'KSh ' +
                Number(data.paid).toLocaleString(
                    'en-KE',
                    {
                        minimumFractionDigits: 2
                    }
                );

            balanceAmount.textContent =
                'KSh ' +
                Number(data.balance).toLocaleString(
                    'en-KE',
                    {
                        minimumFractionDigits: 2
                    }
                );

            amountInput.max =
                data.balance;

            amountHelp.textContent =
                'Maximum payment: KSh ' +
                Number(data.balance).toLocaleString(
                    'en-KE',
                    {
                        minimumFractionDigits: 2
                    }
                );
        })
        .catch(error => {

            console.error(
                'Error loading payment summary:',
                error
            );
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Payment Method
    |--------------------------------------------------------------------------
    */

    function updatePaymentMethod() {

        const method =
            paymentMethod.value;


        /*
        |--------------------------------------------------------------------------
        | M-Pesa
        |--------------------------------------------------------------------------
        */

        if (method === 'mpesa') {

            // Submit to M-Pesa STK Push
            form.action =
                "{{ route('payments.mpesa') }}";

            // Show phone number
            phoneWrapper?.classList.remove('d-none');

            if (phoneInput) {
                phoneInput.required = true;
            }

            // Hide manual transaction/reference number
            referenceWrapper?.classList.add('d-none');

            if (referenceInput) {

                referenceInput.required = false;

                referenceInput.disabled = true;

                referenceInput.value = '';
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Cash, Bank and Cheque
        |--------------------------------------------------------------------------
        */

        else {

            // Submit to normal payment endpoint
            form.action =
                "{{ route('payments.store') }}";

            // Hide M-Pesa phone number
            phoneWrapper?.classList.add('d-none');

            if (phoneInput) {

                phoneInput.required = false;

                phoneInput.value = '';
            }

            // Show transaction/reference field
            referenceWrapper?.classList.remove('d-none');

            if (referenceInput) {
                referenceInput.disabled = false;
            }


            /*
            |--------------------------------------------------------------------------
            | Bank
            |--------------------------------------------------------------------------
            */

            if (method === 'bank') {

                referenceInput.required = true;

                referenceInput.placeholder =
                    'Enter bank transaction reference';

                if (referenceHelp) {

                    referenceHelp.textContent =
                        'Required for bank payments.';
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Cheque
            |--------------------------------------------------------------------------
            */

            else if (method === 'cheque') {

                referenceInput.required = true;

                referenceInput.placeholder =
                    'Enter cheque number';

                if (referenceHelp) {

                    referenceHelp.textContent =
                        'Required for cheque payments.';
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Cash
            |--------------------------------------------------------------------------
            */

            else {

                referenceInput.required = false;

                referenceInput.placeholder =
                    'Optional reference';

                if (referenceHelp) {

                    referenceHelp.textContent =
                        'Optional for cash payments.';
                }
            }
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Event Listeners
    |--------------------------------------------------------------------------
    */

    studentSelect.addEventListener(
        'change',
        loadPaymentSummary
    );

    feeStructureSelect.addEventListener(
        'change',
        loadPaymentSummary
    );

    paymentMethod.addEventListener(
        'change',
        updatePaymentMethod
    );


    /*
    |--------------------------------------------------------------------------
    | Initial State
    |--------------------------------------------------------------------------
    */

    updatePaymentMethod();



        // AJAX M-Pesa payment submission
    form.addEventListener('submit', function (event) {

        if (paymentMethod.value !== 'mpesa') {
            return;
        }

        event.preventDefault();

        const submitButton = form.querySelector('button[type="submit"]');
        const messageBox = document.getElementById('mpesaMessage');

        submitButton.disabled = true;

        submitButton.innerHTML =
            '<span class="spinner-border spinner-border-sm me-1"></span>' +
            'Sending...';

        messageBox.innerHTML = '';

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(async response => {

            const data = await response.json();

            if (!response.ok) {
                throw data;
            }

            return data;
        })
                .then(data => {

                messageBox.innerHTML = `
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle me-2"></i>
                        ${data.message}
                    </div>
                `;

                submitButton.innerHTML =
                    '<i class="bi bi-check-circle me-1"></i>' +
                    'Payment Request Sent';

                if (data.payment_id) {

                    setTimeout(function () {

                        window.location.href =
                            "{{ route('payments.index') }}" +
                            "?payment=" + data.payment_id;

                    }, 1000);
                }
            })
        .catch(error => {

            let message = 'Unable to initiate M-Pesa payment.';

            if (error.message) {
                message = error.message;
            }

            if (error.errors) {
                const firstError =
                    Object.values(error.errors)[0];

                if (firstError && firstError[0]) {
                    message = firstError[0];
                }
            }

            messageBox.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    ${message}
                </div>
            `;

            submitButton.disabled = false;

            submitButton.innerHTML =
                '<i class="bi bi-phone me-1"></i>' +
                'Send M-Pesa Request';
        });
    });



        function pollPaymentStatus(paymentId) {

        let attempts = 0;

        const maxAttempts = 60;

        const interval = setInterval(() => {

            attempts++;

            fetch(`{{ url('/payments') }}/${paymentId}/status`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {

                if (data.status === 'completed') {

                    clearInterval(interval);

                    const messageBox =
                        document.getElementById('mpesaMessage');

                    messageBox.innerHTML = `
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle-fill me-2"></i>

                            <strong>Payment completed successfully.</strong>

                            <br>

                            M-Pesa Reference:
                            <strong>${data.transaction_reference ?? '—'}</strong>

                            <br>

                            Receipt:
                            <strong>${data.receipt_number ?? '—'}</strong>
                        </div>
                    `;

                    return;
                }

                if (data.status === 'failed') {

                    clearInterval(interval);

                    const messageBox =
                        document.getElementById('mpesaMessage');

                    messageBox.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="bi bi-x-circle-fill me-2"></i>

                            <strong>M-Pesa payment failed.</strong>

                            Please try again.
                        </div>
                    `;

                    return;
                }

                if (attempts >= maxAttempts) {

                    clearInterval(interval);

                    const messageBox =
                        document.getElementById('mpesaMessage');

                    messageBox.innerHTML = `
                        <div class="alert alert-warning">
                            <i class="bi bi-clock me-2"></i>

                            Payment is still pending.
                            Please check the Payments page later.
                        </div>
                    `;
                }

            })
            .catch(error => {

                console.error(
                    'Error checking payment status:',
                    error
                );

            });

        }, 3000);
    }

});
</script>
@endpush