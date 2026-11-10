@extends('layouts.app')

@section('title', 'Add Fee Structure')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Add Fee Structure
            </h2>

            <p class="text-muted mb-0">
                Define the fee amount for a course, semester and academic year.
            </p>
        </div>

        <div class="mt-3 mt-md-0">

            <a href="{{ route('fee-structures.index') }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left me-1"></i>
                Back

            </a>

        </div>

    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <div class="fw-bold mb-2">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Please correct the following errors:
            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Fee Structure Form --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-bottom py-3">

            <h5 class="mb-0 fw-bold">

                <i class="bi bi-cash-stack text-primary me-2"></i>

                Fee Structure Information

            </h5>

        </div>


        <div class="card-body">

            <form action="{{ route('fee-structures.store') }}"
                  method="POST">

                @csrf


                <div class="row g-4">


                    {{-- Course --}}
                    <div class="col-md-6">

                        <label for="course_id"
                               class="form-label fw-semibold">

                            Course

                        </label>

                        <select name="course_id"
                                id="course_id"
                                class="form-select @error('course_id') is-invalid @enderror">

                            <option value="">
                                -- Select Course --
                            </option>

                            @foreach($courses as $course)

                                <option value="{{ $course->id }}"
                                    {{ old('course_id') == $course->id ? 'selected' : '' }}>

                                    {{ $course->course_name }}
                                    ({{ $course->course_code }})

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

                        <label for="semester_id"
                               class="form-label fw-semibold">

                            Semester

                        </label>

                        <select name="semester_id"
                                id="semester_id"
                                class="form-select @error('semester_id') is-invalid @enderror">

                            <option value="">
                                -- Select Semester --
                            </option>

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

                        <label for="academic_year_id"
                               class="form-label fw-semibold">

                            Academic Year

                        </label>

                        <select name="academic_year_id"
                                id="academic_year_id"
                                class="form-select @error('academic_year_id') is-invalid @enderror">

                            <option value="">
                                -- Select Academic Year --
                            </option>

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


                    {{-- Amount --}}
                    <div class="col-md-6">

                        <label for="amount"
                               class="form-label fw-semibold">

                            Fee Amount

                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                KES
                            </span>

                            <input type="number"
                                   name="amount"
                                   id="amount"
                                   step="0.01"
                                   min="0"
                                   value="{{ old('amount') }}"
                                   class="form-control @error('amount') is-invalid @enderror"
                                   placeholder="Enter fee amount">

                            @error('amount')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>


                </div>


                {{-- Form Actions --}}
                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">

                    <a href="{{ route('fee-structures.index') }}"
                       class="btn btn-outline-secondary">

                        Cancel

                    </a>

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-check-circle me-1"></i>

                        Save Fee Structure

                    </button>

                </div>


            </form>

        </div>

    </div>

</div>

@endsection