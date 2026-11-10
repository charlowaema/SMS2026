@extends('layouts.app')
@section('title', 'Students')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Students</h2>
            <p class="text-muted mb-0">
                Manage registered students.
            </p>
        </div>
        <a href="{{ route('students.create') }}"
           class="btn school-primary">
            <i class="bi bi-plus-lg me-1"></i>
            Add Student
        </a>
    </div>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                <thead>
                <tr>
                    <th>Student No.</th>
                    <th>Name</th>
                    <th>Course</th>
                    <th>Semester</th>
                    <th>Academic Year</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
                    <tbody>
                        @forelse($students as $student)
                          @php
                            $enrollment = $student->enrollments->sortByDesc('created_at')->first();
                        @endphp
                            <tr>
                                <td>
                                    {{ $student->student_number }}
                                </td>
                                <td>
                                    {{ $student->first_name }}
                                    {{ $student->last_name }}
                                </td>

                                  <td>
                                    {{ $enrollment?->course?->course_name ?? 'Not enrolled' }}
                                </td>

                                <td>
                                    {{ $enrollment?->semester?->name ?? 'Not assigned' }}
                                </td>

                                <td>
                                    {{ $enrollment?->academicYear?->name ?? 'Not assigned' }}
                                </td>
                                <td>
                                    {{ $student->gender ?? '-' }}
                                </td>
                                <td>
                                    {{ $student->phone ?? '-' }}
                                </td>
                                <td>
                                    {{ $student->email ?? '-' }}
                                </td>
                                <td>
                                    @if($student->status === 'active')
                                        <span class="badge bg-success">
                                            Active
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('students.show', $student) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('students.edit', $student) }}"
                                       class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <a href="{{ route('payments.student.statement', ['student' => $student->id]) }}"
                                    class="btn btn-sm btn-outline-success"
                                    title="Fee Statement">
                                        <i class="bi bi-receipt"></i>
                                    </a>
                                    
                                    <form action="{{ route('students.destroy', $student) }}"
                                          method="POST"
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Are you sure you want to delete this student?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7"
                                    class="text-center text-muted py-4">
                                    No students found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
   </table>
            </div>
            <div class="mt-3">
   {{ $students->links() }}
            </div>
        </div>
    </div>
</div>
@endsection