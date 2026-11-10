@extends('layouts.app')

@section('title', 'Fee Structures')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Fee Structures
            </h2>

            <p class="text-muted mb-0">
                Manage course fees by semester and academic year.
            </p>
        </div>

        <div class="mt-3 mt-md-0">
            <a href="{{ route('fee-structures.create') }}"
               class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>
                Add Fee Structure
            </a>
        </div>

    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show" role="alert">

            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Fee Structures Table --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-bottom py-3">

            <h5 class="mb-0 fw-bold">
                <i class="bi bi-cash-stack text-primary me-2"></i>
                Fee Structure List
            </h5>

        </div>


        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">

                        <tr>
                            <th>#</th>
                            <th>Course</th>
                            <th>Semester</th>
                            <th>Academic Year</th>
                            <th>Amount</th>
                            <th class="text-end">Actions</th>
                        </tr>

                    </thead>


                    <tbody>

                        @forelse($feeStructures as $feeStructure)

                            <tr>

                                {{-- Number --}}
                                <td>
                                    {{ $loop->iteration }}
                                </td>


                                {{-- Course --}}
                                <td>

                                    <div class="fw-semibold">
                                        {{ $feeStructure->course->course_name }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $feeStructure->course->course_code }}
                                    </small>

                                </td>


                                {{-- Semester --}}
                                <td>
                                    {{ $feeStructure->semester->name }}
                                </td>


                                {{-- Academic Year --}}
                                <td>
                                    {{ $feeStructure->academicYear->name }}
                                </td>


                                {{-- Amount --}}
                                <td>

                                    <span class="fw-semibold">
                                        KES {{ number_format($feeStructure->amount, 2) }}
                                    </span>

                                </td>


                                {{-- Actions --}}
                                <td class="text-end">

                                    <div class="d-inline-flex gap-1">

                                        {{-- View --}}
                                        <a href="{{ route('fee-structures.show', $feeStructure) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           title="View">

                                            <i class="bi bi-eye"></i>

                                        </a>


                                        {{-- Edit --}}
                                        <a href="{{ route('fee-structures.edit', $feeStructure) }}"
                                           class="btn btn-sm btn-outline-warning"
                                           title="Edit">

                                            <i class="bi bi-pencil"></i>

                                        </a>


                                        {{-- Delete --}}
                                        <form action="{{ route('fee-structures.destroy', $feeStructure) }}"
                                              method="POST"
                                              class="d-inline">

                                            @csrf

                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Delete"
                                                    onclick="return confirm('Are you sure you want to delete this fee structure?')">

                                                <i class="bi bi-trash"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6" class="text-center py-5">

                                    <div class="text-muted">

                                        <i class="bi bi-cash-stack fs-1 d-block mb-3"></i>

                                        <h5>No Fee Structures Found</h5>

                                        <p class="mb-3">
                                            There are no fee structures in the system yet.
                                        </p>

                                        <a href="{{ route('fee-structures.create') }}"
                                           class="btn btn-primary">

                                            <i class="bi bi-plus-circle me-1"></i>
                                            Add Fee Structure

                                        </a>

                                    </div>

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