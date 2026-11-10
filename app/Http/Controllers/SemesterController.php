<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SemesterController extends Controller
{
    public function index()
    {
        $semesters = Semester::with('academicYear')
                             ->latest()
                             ->get();

        return view('semesters.index', compact('semesters'));
    }


    public function create()
{
    $academicYears = AcademicYear::orderBy('name')->get();

    return view('semesters.create', compact('academicYears'));
}

public function store(Request $request)
{
    $validatedData = $request->validate([
        'name' => [
            'required',
            'string',
            'max:255',
            Rule::unique('semesters', 'name')
                ->where('academic_year_id', $request->academic_year_id)
        ],
        'academic_year_id' => 'required|exists:academic_years,id',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
        'is_active' => 'nullable|boolean'
    ]);

    $validatedData['is_active'] = $request->boolean('is_active');

    Semester::create($validatedData);

    return redirect()
        ->route('semesters.index')
        ->with('success', 'Semester created successfully.');
}

public function edit(Semester $semester)
{
    $academicYears = AcademicYear::orderBy('name')->get();

    return view('semesters.edit', compact('semester', 'academicYears'));
}

public function update(Request $request, Semester $semester)
{
    $validatedData = $request->validate([
        'name' => [
            'required',
            'string',
            'max:255',
            Rule::unique('semesters', 'name')
                ->where('academic_year_id', $request->academic_year_id)
                ->ignore($semester->id)
        ],

        'academic_year_id' => 'required|exists:academic_years,id',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
        'is_active' => 'nullable|boolean'
    ]);

    $validatedData['is_active'] = $request->boolean('is_active');

    $semester->update($validatedData);

    return redirect()
        ->route('semesters.index')
        ->with('success', 'Semester updated successfully.');
}


public function destroy(Semester $semester)
{
    if (
        $semester->enrollments()->exists() ||
        $semester->feeStructures()->exists()
    ) {
        return redirect()
            ->route('semesters.index')
            ->with('error', 'Cannot delete semester because it is already in use.');
    }

    $semester->delete();

    return redirect()
        ->route('semesters.index')
        ->with('success', 'Semester deleted successfully.');
}
}