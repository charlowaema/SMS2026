@extends('layouts.app')

@section('title', 'Enrollment Details')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Enrollment Details
            </h2>

            <p class="text-muted mb-0">
                View the student's enrollment and academic information.
            </p>
        </div>

        <div class="d-flex gap-2 mt-3 mt-md-0">

            <a href="{{ route('enrollments.edit', $enrollment) }}"
               class="btn btn-warning">
                <i class="bi bi-pencil me-1"></i>
                Edit Enrollment
            </a>

            <a href="{{ route('enrollments.index') }}"
               class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                Back
            </a>

        </div>

    </div>


    {{-- Student Information --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-person text-primary me-2"></i>
                Student Information
            </h5>
        </div>

        <div class="card-body">

            <div class="row g-4">

                {{-- Student Number --}}
                <div class="col-md-4">

                    <small class="text-muted d-block mb-1">
                        Student Number
                    </small>

                    <div class="fw-semibold">
                        {{ $enrollment->student->student_number }}
                    </div>

                </div>


                {{-- Student Name --}}
                <div class="col-md-4">

                    <small class="text-muted d-block mb-1">
                        Student Name
                    </small>

                    <div class="fw-semibold">
                        {{ $enrollment->student->first_name }}
                        {{ $enrollment->student->last_name }}
                    </div>

                </div>


                {{-- Phone --}}
                <div class="col-md-4">

                    <small class="text-muted d-block mb-1">
                        Phone
                    </small>

                    <div>
                        {{ $enrollment->student->phone ?? '—' }}
                    </div>

                </div>

            </div>

        </div>

    </div>

   {{-- Academic Information --}}
<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white border-bottom py-3">
        <h5 class="mb-0 fw-bold">
            <i class="bi bi-mortarboard text-primary me-2"></i>
            Academic Information
        </h5>
    </div>

    <div class="card-body">

        <div class="row g-4">

            {{-- Course --}}
            <div class="col-md-4">
                <small class="text-muted d-block mb-1">
                    Course of Study
                </small>

                <div class="fw-semibold">
                    {{ $enrollment->course->course_name }}
                </div>

                <small class="text-muted">
                    {{ $enrollment->course->course_code }}
                </small>
            </div>

            {{-- Semester --}}
            <div class="col-md-4">
                <small class="text-muted d-block mb-1">
                    Semester
                </small>

                <div class="fw-semibold">
                    {{ $enrollment->semester->name }}
                </div>
            </div>

            {{-- Academic Year --}}
            <div class="col-md-4">
                <small class="text-muted d-block mb-1">
                    Academic Year
                </small>

                <div class="fw-semibold">
                    {{ $enrollment->academicYear->name }}
                </div>
            </div>

        </div>

    </div>
</div>


    {{-- Enrollment Information --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-info-circle text-primary me-2"></i>
                Enrollment Information
            </h5>
        </div>

        <div class="card-body">

            <div class="row g-4">

                {{-- Enrollment ID --}}
                <div class="col-md-6">

                    <small class="text-muted d-block mb-1">
                        Enrollment ID
                    </small>

                    <div class="fw-semibold">
                        #{{ $enrollment->id }}
                    </div>

                </div>


                {{-- Date Enrolled --}}
                <div class="col-md-6">

                    <small class="text-muted d-block mb-1">
                        Date Enrolled
                    </small>

                    <div class="fw-semibold">
                        {{ $enrollment->created_at?->format('d M Y, h:i A') ?? '—' }}
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection