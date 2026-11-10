
@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="mb-4">
        <h3 class="fw-bold">Add Semester</h3>
        <p class="text-muted">Create a new semester and assign it to an academic year.</p>
    </div>

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Semester Details</h5>
        </div>

        <div class="card-body">

            <form action="{{ route('semesters.store') }}" method="POST">

                @csrf

                <div class="mb-3">
                    <label class="form-label">Semester Name</label>

                    <input type="text"
                           name="name"
                           class="form-control"
                           value="{{ old('name') }}"
                           placeholder="e.g. Semester 1">

                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Academic Year</label>

                    <select name="academic_year_id" class="form-select">

                        <option value="">Select Academic Year</option>

                        @foreach($academicYears as $year)

                            <option value="{{ $year->id }}"
                                {{ old('academic_year_id') == $year->id ? 'selected' : '' }}>
                                {{ $year->name }}
                            </option>

                        @endforeach

                    </select>

                    @error('academic_year_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Start Date</label>

                        <input type="date"
                               name="start_date"
                               class="form-control"
                               value="{{ old('start_date') }}">

                        @error('start_date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">End Date</label>

                        <input type="date"
                               name="end_date"
                               class="form-control"
                               value="{{ old('end_date') }}">

                        @error('end_date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                </div>

                <div class="form-check mb-4">
                    <input type="checkbox"
                           name="is_active"
                           value="1"
                           class="form-check-input"
                           id="is_active"
                           {{ old('is_active') ? 'checked' : '' }}>

                    <label class="form-check-label" for="is_active">
                        Set as Active Semester
                    </label>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i>
                    Save Semester
                </button>

                <a href="{{ route('semesters.index') }}"
                   class="btn btn-secondary">
                    Cancel
                </a>

            </form>

        </div>
    </div>

</div>

@endsection
