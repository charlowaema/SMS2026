@extends('layouts.app')

@section('title', 'Edit Enrollment')

@section('content')

<div class="container-fluid">

    <div class="mb-4">
        <h2 class="fw-bold mb-1">Edit Enrollment</h2>

        <p class="text-muted mb-0">
            Update the student's academic enrollment information.
        </p>
    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-mortarboard text-primary me-2"></i>
                Enrollment Information
            </h5>
        </div>

        <div class="card-body">

            <form method="POST"
                  action="{{ route('enrollments.update', $enrollment) }}">

                @csrf
                @method('PUT')

                <div class="row g-4">

                    {{-- Student --}}
                    <div class="col-md-6">

                        <label for="student_id" class="form-label">
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
                                    {{ old('student_id', $enrollment->student_id) == $student->id ? 'selected' : '' }}>

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


                    {{-- Course --}}
                    <div class="col-md-6">

                        <label for="course_id" class="form-label">
                            Course
                        </label>

                        <select name="course_id"
                                id="course_id"
                                class="form-select @error('course_id') is-invalid @enderror">

                            <option value="">
                                Select Course
                            </option>

                            @foreach($courses as $course)

                                <option value="{{ $course->id }}"
                                    {{ old('course_id', $enrollment->course_id) == $course->id ? 'selected' : '' }}>

                                    {{ $course->course_code }}
                                    -
                                    {{ $course->course_name }}

                                </option>

                            @endforeach

                        </select>

                        @error('course_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Semester --}}
                    <div class="col-md-6">

                        <label for="semester_id" class="form-label">
                            Semester
                        </label>

                        <select name="semester_id"
                                id="semester_id"
                                class="form-select @error('semester_id') is-invalid @enderror">

                            <option value="">
                                Select Semester
                            </option>

                            @foreach($semesters as $semester)

                                <option value="{{ $semester->id }}"
                                    {{ old('semester_id', $enrollment->semester_id) == $semester->id ? 'selected' : '' }}>

                                    {{ $semester->name }}

                                </option>

                            @endforeach

                        </select>

                        @error('semester_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Academic Year --}}
                    <div class="col-md-6">

                        <label for="academic_year_id" class="form-label">
                            Academic Year
                        </label>

                        <select name="academic_year_id"
                                id="academic_year_id"
                                class="form-select @error('academic_year_id') is-invalid @enderror">

                            <option value="">
                                Select Academic Year
                            </option>

                            @foreach($academicYears as $academicYear)

                                <option value="{{ $academicYear->id }}"
                                    {{ old('academic_year_id', $enrollment->academic_year_id) == $academicYear->id ? 'selected' : '' }}>

                                    {{ $academicYear->name }}

                                </option>

                            @endforeach

                        </select>

                        @error('academic_year_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>


                <div class="d-flex justify-content-end gap-2 mt-4">

                    <a href="{{ route('enrollments.index') }}"
                       class="btn btn-outline-secondary">

                        <i class="bi bi-arrow-left me-1"></i>
                        Cancel

                    </a>

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-check-lg me-1"></i>
                        Update Enrollment

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection