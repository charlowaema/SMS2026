
@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold">Semesters</h3>
            <p class="text-muted">Manage institution semesters.</p>
        </div>
    </div>

    <div class="card shadow-sm border-0">

                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
               @endif

               @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="card-header bg-white py-3">
            <a href="{{ route('semesters.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i>
                Add Semester
            </a>
            <h5 class="mb-0">Semester List</h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Semester</th>
                            <th>Academic Year</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($semesters as $semester)

                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>{{ $semester->name }}</td>

                                <td>
                                    {{ $semester->academicYear->name ?? 'Not assigned' }}
                                </td>

                                <td>{{ $semester->start_date ?? 'Not set' }}</td>

                                <td>{{ $semester->end_date ?? 'Not set' }}</td>

                                <td>
                                    @if($semester->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>

                                <td>
                                    <a href="{{ route('semesters.edit', $semester->id) }}"
                                    class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil-square"></i>
                                        Edit
                                    </a>

                                    <form action="{{ route('semesters.destroy', $semester->id) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this semester?')">

                                        @csrf

                                        @method('DELETE')

                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                            Delete
                                        </button>

                                    </form>
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    No semesters found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

</div>

@endsection
