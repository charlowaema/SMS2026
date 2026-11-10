@extends('layouts.app')

@section('title', 'Register Student')

@section('content')

<div class="container-fluid">

    <div class="mb-4">
        <h2 class="fw-bold">Register Student</h2>

        <p class="text-muted mb-0">
            Register a student and assign their academic information.
        </p>
    </div>


    <form method="POST"
          action="{{ route('students.store') }}">

        @csrf


        {{-- ========================= --}}
        {{-- PERSONAL INFORMATION --}}
        {{-- ========================= --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 pt-4 px-4">

                <h5 class="fw-bold mb-1">
                    <i class="bi bi-person me-2"></i>
                    Personal Information
                </h5>

                <p class="text-muted small mb-0">
                    Enter the student's personal details.
                </p>

            </div>


            <div class="card-body px-4">

                <div class="row g-3">


                    {{-- Student Number --}}
                    <div class="col-md-6">

                        <label for="student_number"
                               class="form-label">
                            Student Number
                        </label>

                        <input type="text"
                               id="student_number"
                               name="student_number"
                               value="{{ old('student_number') }}"
                               class="form-control @error('student_number') is-invalid @enderror">

                        @error('student_number')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- First Name --}}
                    <div class="col-md-6">

                        <label for="first_name"
                               class="form-label">
                            First Name
                        </label>

                        <input type="text"
                               id="first_name"
                               name="first_name"
                               value="{{ old('first_name') }}"
                               class="form-control @error('first_name') is-invalid @enderror">

                        @error('first_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Last Name --}}
                    <div class="col-md-6">

                        <label for="last_name"
                               class="form-label">
                            Last Name
                        </label>

                        <input type="text"
                               id="last_name"
                               name="last_name"
                               value="{{ old('last_name') }}"
                               class="form-control @error('last_name') is-invalid @enderror">

                        @error('last_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Gender --}}
                    <div class="col-md-6">

                        <label for="gender"
                               class="form-label">
                            Gender
                        </label>

                        <select id="gender"
                                name="gender"
                                class="form-select @error('gender') is-invalid @enderror">

                            <option value="">
                                Select Gender
                            </option>

                            <option value="Male"
                                {{ old('gender') === 'Male' ? 'selected' : '' }}>
                                Male
                            </option>

                            <option value="Female"
                                {{ old('gender') === 'Female' ? 'selected' : '' }}>
                                Female
                            </option>

                            <option value="Other"
                                {{ old('gender') === 'Other' ? 'selected' : '' }}>
                                Other
                            </option>

                        </select>

                        @error('gender')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Date of Birth --}}
                    <div class="col-md-6">

                        <label for="date_of_birth"
                               class="form-label">
                            Date of Birth
                        </label>

                        <input type="date"
                               id="date_of_birth"
                               name="date_of_birth"
                               value="{{ old('date_of_birth') }}"
                               class="form-control @error('date_of_birth') is-invalid @enderror">

                        @error('date_of_birth')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Phone --}}
                    <div class="col-md-6">

                        <label for="phone"
                               class="form-label">
                            Phone
                        </label>

                        <input type="text"
                               id="phone"
                               name="phone"
                               value="{{ old('phone') }}"
                               class="form-control @error('phone') is-invalid @enderror">

                        @error('phone')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Email --}}
                    <div class="col-md-6">

                        <label for="email"
                               class="form-label">
                            Email
                        </label>

                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               class="form-control @error('email') is-invalid @enderror">

                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Status --}}
                    <div class="col-md-6">

                        <label for="status"
                               class="form-label">
                            Status
                        </label>

                        <select id="status"
                                name="status"
                                class="form-select @error('status') is-invalid @enderror">

                            <option value="active"
                                {{ old('status', 'active') === 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="inactive"
                                {{ old('status') === 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                        @error('status')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Address --}}
                    <div class="col-12">

                        <label for="address"
                               class="form-label">
                            Address
                        </label>

                        <textarea id="address"
                                  name="address"
                                  rows="3"
                                  class="form-control @error('address') is-invalid @enderror">{{ old('address') }}</textarea>

                        @error('address')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================= --}}
        {{-- ACADEMIC INFORMATION --}}
        {{-- ========================= --}}

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 pt-4 px-4">

                <h5 class="fw-bold mb-1">
                    <i class="bi bi-mortarboard me-2"></i>
                    Academic Information
                </h5>

                <p class="text-muted small mb-0">
                    Assign the student's course, semester and academic year.
                </p>

            </div>


            <div class="card-body px-4">

                <div class="row g-3">


                    {{-- Course --}}
                    <div class="col-md-4">

                        <label for="course_id"
                               class="form-label">
                            Course of Study
                        </label>

                        <select id="course_id"
                                name="course_id"
                                class="form-select @error('course_id') is-invalid @enderror">

                            <option value="">
                                Select Course
                            </option>

                            @foreach($courses as $course)

                                <option value="{{ $course->id }}"
                                    {{ old('course_id') == $course->id ? 'selected' : '' }}>

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
    <label for="semester_id" class="form-label">Semester</label>

    <select name="semester_id" id="semester_id"
            class="form-select @error('semester_id') is-invalid @enderror">

        <option value="">Select Semester</option>

        @foreach($semesters as $semester)
            <option value="{{ $semester->id }}"
                {{ old('semester_id') == $semester->id ? 'selected' : '' }}>
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
    <label for="academic_year_id" class="form-label">Academic Year</label>

    <select name="academic_year_id" id="academic_year_id"
            class="form-select @error('academic_year_id') is-invalid @enderror">

        <option value="">Select Academic Year</option>

        @foreach($academicYears as $academicYear)
            <option value="{{ $academicYear->id }}"
                {{ old('academic_year_id') == $academicYear->id ? 'selected' : '' }}>
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

            </div>

        </div>


        {{-- Buttons --}}
        <div class="mb-4">

            <a href="{{ route('students.index') }}"
               class="btn btn-secondary">

                <i class="bi bi-arrow-left me-1"></i>
                Cancel

            </a>

            <button type="submit"
                    class="btn school-primary">

                <i class="bi bi-person-plus me-1"></i>
                Register Student

            </button>

        </div>

    </form>

</div>

@endsection