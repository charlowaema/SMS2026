
@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="mb-4">
        <h3 class="fw-bold">Edit Academic Year</h3>
        <p class="text-muted">Update academic year information.</p>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">

            <form action="{{ route('academic-years.update', $academicYear->id) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Academic Year</label>

                    <input
                        type="text"
                        name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $academicYear->name) }}"
                    >

                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Start Date</label>

                        <input
                            type="date"
                            name="start_date"
                            class="form-control @error('start_date') is-invalid @enderror"
                            value="{{ old('start_date', $academicYear->start_date) }}"
                        >

                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">End Date</label>

                        <input
                            type="date"
                            name="end_date"
                            class="form-control @error('end_date') is-invalid @enderror"
                            value="{{ old('end_date', $academicYear->end_date) }}"
                        >

                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="form-check mb-4">
                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        class="form-check-input"
                        id="is_active"
                        {{ old('is_active', $academicYear->is_active) ? 'checked' : '' }}
                    >

                    <label class="form-check-label" for="is_active">
                        Active Academic Year
                    </label>
                </div>

                <button type="submit" class="btn btn-primary">
                    Update Academic Year
                </button>

                <a href="{{ route('academic-years.index') }}"
                   class="btn btn-secondary">
                    Cancel
                </a>

            </form>

        </div>
    </div>

</div>

@endsection
