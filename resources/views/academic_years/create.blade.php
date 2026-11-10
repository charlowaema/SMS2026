
@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="mb-4">
        <h3 class="fw-bold">Create Academic Year</h3>
        <p class="text-muted">Register a new academic year.</p>
    </div>

    <div class="card shadow-sm border-0">

        <div class="card-body p-4">

            <form action="{{ route('academic-years.store') }}" method="POST">

                @csrf

                <div class="mb-3">
                    <label class="form-label">Academic Year</label>

                    <input
                        type="text"
                        name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        placeholder="e.g. 2026/2027"
                        value="{{ old('name') }}"
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
                            value="{{ old('start_date') }}"
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
                            value="{{ old('end_date') }}"
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
                        {{ old('is_active') ? 'checked' : '' }}
                    >

                    <label class="form-check-label" for="is_active">
                        Set as active academic year
                    </label>
                </div>

                <button type="submit" class="btn btn-primary">
                    Save Academic Year
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
