@extends('layouts.app')

@section('title', 'Student Details')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Student Details
            </h2>

            <p class="text-muted mb-0">
                View student personal and academic information.
            </p>
        </div>

        <div class="d-flex gap-2 mt-3 mt-md-0">

                <a href="{{ route('students.edit', ['student' => $student->id]) }}"
                class="btn btn-primary">
                    <i class="bi bi-pencil me-1"></i>
                    Edit Student
                </a>

                <a href="{{ route('payments.student.statement', ['student' => $student->id]) }}"
                class="btn btn-success">
                    <i class="bi bi-receipt me-1"></i>
                    Fee Statement
                </a>

            <a href="{{ route('students.index') }}"
               class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                Back
            </a>

        </div>

    </div>


    {{-- Personal Information --}}
   {{-- Personal Information --}}
<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white border-bottom py-3">
        <h5 class="mb-0 fw-bold">
            <i class="bi bi-person text-primary me-2"></i>
            Personal Information
        </h5>
    </div>

    <div class="card-body">

        <div class="row g-4">

            {{-- Student Number --}}
            <div class="col-md-4">
                <div class="student-detail">
                    <small class="text-muted d-block mb-1">
                        Student Number
                    </small>

                    <div class="fw-semibold">
                        {{ $student->student_number }}
                    </div>
                </div>
            </div>

            {{-- Full Name --}}
            <div class="col-md-4">
                <div class="student-detail">
                    <small class="text-muted d-block mb-1">
                        Full Name
                    </small>

                    <div class="fw-semibold">
                        {{ $student->first_name }}
                        {{ $student->last_name }}
                    </div>
                </div>
            </div>

            {{-- Gender --}}
            <div class="col-md-4">
                <div class="student-detail">
                    <small class="text-muted d-block mb-1">
                        Gender
                    </small>

                    <div>
                        {{ $student->gender ?? '—' }}
                    </div>
                </div>
            </div>


            {{-- Date of Birth --}}
            <div class="col-md-4">
                <div class="student-detail">
                    <small class="text-muted d-block mb-1">
                        Date of Birth
                    </small>

                    <div>
                        {{ $student->date_of_birth?->format('d M Y') ?? '—' }}
                    </div>
                </div>
            </div>

            {{-- Phone --}}
            <div class="col-md-4">
                <div class="student-detail">
                    <small class="text-muted d-block mb-1">
                        Phone
                    </small>

                    <div>
                        {{ $student->phone ?? '—' }}
                    </div>
                </div>
            </div>

            {{-- Email --}}
            <div class="col-md-4">
                <div class="student-detail">
                    <small class="text-muted d-block mb-1">
                        Email
                    </small>

                    <div>
                        {{ $student->email ?? '—' }}
                    </div>
                </div>
            </div>


            {{-- Status --}}
            <div class="col-md-4">
                <div class="student-detail">
                    <small class="text-muted d-block mb-1">
                        Status
                    </small>

                    @if($student->status === 'active')
                        <span class="badge bg-success">
                            Active
                        </span>
                    @else
                        <span class="badge bg-secondary">
                            Inactive
                        </span>
                    @endif
                </div>
            </div>

            {{-- Address --}}
            <div class="col-md-8">
                <div class="student-detail">
                    <small class="text-muted d-block mb-1">
                        Address
                    </small>

                    <div>
                        {{ $student->address ?? '—' }}
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>


    {{-- Academic Information --}}
   {{-- Academic Information --}}
<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white border-bottom py-3">
        <h5 class="mb-0 fw-bold">
            <i class="bi bi-mortarboard text-primary me-2"></i>
            Academic Information
        </h5>
    </div>

    <div class="card-body">

        @if($enrollment)

            <div class="row g-4">

                {{-- Course --}}
                <div class="col-md-4">
                    <div class="student-detail">

                        <small class="text-muted d-block mb-1">
                            Course of Study
                        </small>

                        <div class="fw-semibold">
                            {{ $enrollment->course?->course_name ?? '—' }}
                        </div>

                    </div>
                </div>

                {{-- Semester --}}
                <div class="col-md-4">
                    <div class="student-detail">

                        <small class="text-muted d-block mb-1">
                            Semester
                        </small>

                        <div class="fw-semibold">
                            {{ $enrollment->semester?->name ?? '—' }}
                        </div>

                    </div>
                </div>

                {{-- Academic Year --}}
                <div class="col-md-4">
                    <div class="student-detail">

                        <small class="text-muted d-block mb-1">
                            Academic Year
                        </small>

                        <div class="fw-semibold">
                            {{ $enrollment->academicYear?->name ?? '—' }}
                        </div>

                    </div>
                </div>

            </div>

        @else

            <div class="alert alert-warning mb-0">
                <i class="bi bi-exclamation-triangle me-2"></i>
                This student does not have an enrollment record.
            </div>

        @endif

    </div>

</div>


</div>

@endsection