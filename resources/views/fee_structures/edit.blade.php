@extends('layouts.app')

@section('title', 'Edit Fee Structure')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="mb-1">Edit Fee Structure</h2>
            <p class="text-muted mb-0">
                Update fee structure information
            </p>
        </div>

        <a href="{{ route('fee-structures.index') }}"
           class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Back
        </a>

    </div>


    {{-- Validation Errors --}}
    @if ($errors->any())

        <div class="alert alert-danger">

            <strong>Please correct the following errors:</strong>

            <ul class="mb-0 mt-2">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Edit Form --}}
    <div class="card shadow-sm">

        <div class="card-header bg-primary text-white">
            <i class="bi bi-pencil-square me-2"></i>
            Fee Structure Information
        </div>

        <div class="card-body">

            <form action="{{ route('fee-structures.update', $feeStructure) }}"
                  method="POST">

                @csrf
                @method('PUT')


                <div class="row">

                    {{-- Course --}}
                    <div class="col-md-6 mb-3">

                        <label for="course_id" class="form-label">
                            Course
                        </label>

                        <select name="course_id"
                                id="course_id"
                                class="form-select">

                            <option value="">
                                -- Select Course --
                            </option>

                            @foreach ($courses as $course)

                                <option value="{{ $course->id }}"
                                    {{ old('course_id', $feeStructure->course_id) == $course->id ? 'selected' : '' }}>

                                    {{ $course->course_name }}
                                    ({{ $course->course_code }})

                                </option>

                            @endforeach

                        </select>

                        @error('course_id')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Semester --}}
                    <div class="col-md-6 mb-3">

                        <label for="semester_id" class="form-label">
                            Semester
                        </label>

                        <select name="semester_id"
                                id="semester_id"
                                class="form-select">

                            <option value="">
                                -- Select Semester --
                            </option>

                            @foreach ($semesters as $semester)

                                <option value="{{ $semester->id }}"
                                    {{ old('semester_id', $feeStructure->semester_id) == $semester->id ? 'selected' : '' }}>

                                    {{ $semester->name }}

                                </option>

                            @endforeach

                        </select>

                        @error('semester_id')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Academic Year --}}
                    <div class="col-md-6 mb-3">

                        <label for="academic_year_id" class="form-label">
                            Academic Year
                        </label>

                        <select name="academic_year_id"
                                id="academic_year_id"
                                class="form-select">

                            <option value="">
                                -- Select Academic Year --
                            </option>

                            @foreach ($academicYears as $academicYear)

                                <option value="{{ $academicYear->id }}"
                                    {{ old('academic_year_id', $feeStructure->academic_year_id) == $academicYear->id ? 'selected' : '' }}>

                                    {{ $academicYear->name }}

                                </option>

                            @endforeach

                        </select>

                        @error('academic_year_id')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Amount --}}
                    <div class="col-md-6 mb-3">

                        <label for="amount" class="form-label">
                            Amount
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                KES
                            </span>

                            <input type="number"
                                   name="amount"
                                   id="amount"
                                   class="form-control"
                                   value="{{ old('amount', $feeStructure->amount) }}"
                                   min="0"
                                   step="0.01"
                                   placeholder="Enter fee amount">

                        </div>

                        @error('amount')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>


                {{-- Buttons --}}
                <div class="d-flex justify-content-end gap-2 mt-3">

                    <a href="{{ route('fee-structures.index') }}"
                       class="btn btn-secondary">

                        <i class="bi bi-x-circle me-1"></i>
                        Cancel

                    </a>

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-check-circle me-1"></i>
                        Update Fee Structure

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection