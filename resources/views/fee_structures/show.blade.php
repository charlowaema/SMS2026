@extends('layouts.app')

@section('title', 'Fee Structure Details')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="mb-1">Fee Structure Details</h2>
            <p class="text-muted mb-0">
                View fee structure information
            </p>
        </div>

        <div>
            <a href="{{ route('fee-structures.edit', $feeStructure) }}"
               class="btn btn-primary">
                <i class="bi bi-pencil me-1"></i>
                Edit
            </a>

            <a href="{{ route('fee-structures.index') }}"
               class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                Back
            </a>
        </div>

    </div>


    {{-- Fee Structure Information --}}
    <div class="row">

        {{-- Course Information --}}
        <div class="col-md-6 mb-4">

            <div class="card shadow-sm h-100">

                <div class="card-header bg-primary text-white">
                    <i class="bi bi-book me-2"></i>
                    Course Information
                </div>

                <div class="card-body">

                    <div class="mb-3">
                        <strong>Course Name:</strong>

                        <div>
                            {{ $feeStructure->course->course_name }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>Course Code:</strong>

                        <div>
                            {{ $feeStructure->course->course_code }}
                        </div>
                    </div>

                </div>

            </div>

        </div>


        {{-- Academic Information --}}
        <div class="col-md-6 mb-4">

            <div class="card shadow-sm h-100">

                <div class="card-header bg-info text-white">
                    <i class="bi bi-calendar3 me-2"></i>
                    Academic Information
                </div>

                <div class="card-body">

                    <div class="mb-3">
                        <strong>Semester:</strong>

                        <div>
                            {{ $feeStructure->semester->name }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>Academic Year:</strong>

                        <div>
                            {{ $feeStructure->academicYear->name }}
                        </div>
                    </div>

                </div>

            </div>

        </div>


        {{-- Fee Information --}}
        <div class="col-md-12 mb-4">

            <div class="card shadow-sm">

                <div class="card-header bg-success text-white">
                    <i class="bi bi-cash-stack me-2"></i>
                    Fee Information
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6">

                            <strong>Fee Structure ID:</strong>

                            <div>
                                {{ $feeStructure->id }}
                            </div>

                        </div>

                        <div class="col-md-6">

                            <strong>Amount:</strong>

                            <div class="fs-4 fw-bold text-success">
                                KES {{ number_format($feeStructure->amount, 2) }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection