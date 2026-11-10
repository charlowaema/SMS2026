@extends('layouts.app')

@section('title', 'Enrollments')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Enrollments
            </h2>

            <p class="text-muted mb-0">
                Manage student course enrollments.
            </p>
        </div>

        <div class="mt-3 mt-md-0">
            <a href="{{ route('enrollments.create') }}"
               class="btn btn-primary">

                <i class="bi bi-plus-lg me-1"></i>
                Add Enrollment

            </a>
        </div>

    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show"
             role="alert">

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Enrollment Table --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student</th>
                            <th>Course</th>
                            <th>Semester</th>
                            <th>Academic Year</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($enrollments as $enrollment)

                            <tr>

                                <td>
                                    {{ $enrollments->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    <div class="fw-semibold">
                                        {{ $enrollment->student->first_name }}
                                        {{ $enrollment->student->last_name }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $enrollment->student->student_number }}
                                    </small>
                                </td>

                                <td>
                                    {{ $enrollment->course->course_name }}
                                </td>

                                <td>
                                    {{ $enrollment->semester->name }}
                                </td>

                                <td>
                                    {{ $enrollment->academicYear->name }}
                                </td>

                                <td class="text-end">

                                    <a href="{{ route('enrollments.show', $enrollment) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       title="View">

                                        <i class="bi bi-eye"></i>

                                    </a>

                                    <a href="{{ route('enrollments.edit', $enrollment) }}"
                                       class="btn btn-sm btn-outline-warning"
                                       title="Edit">

                                        <i class="bi bi-pencil"></i>

                                    </a>

                                    <form action="{{ route('enrollments.destroy', $enrollment) }}"
                                          method="POST"
                                          class="d-inline">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Delete"
                                                onclick="return confirm('Are you sure you want to delete this enrollment?')">

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6"
                                    class="text-center py-4 text-muted">

                                    No enrollments found.

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}
            <div class="mt-3">
                {{ $enrollments->links() }}
            </div>

        </div>

    </div>

</div>

@endsection