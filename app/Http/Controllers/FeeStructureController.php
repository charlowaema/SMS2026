<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Course;
use App\Models\FeeStructure;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FeeStructureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $feeStructures = FeeStructure::with([
            'course',
            'semester',
            'academicYear',
        ])->latest()->get();

        return view('fee_structures.index', compact('feeStructures'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = Course::orderBy('course_name')->get();
        $semesters = Semester::orderBy('name')->get();
        $academicYears = AcademicYear::orderBy('name')->get();

        return view('fee_structures.create', compact(
            'courses',
            'semesters',
            'academicYears'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',

            'semester_id' => 'required|exists:semesters,id',

            'academic_year_id' => [
                'required',
                'exists:academic_years,id',

                Rule::unique('fee_structures')->where(function ($query) use ($request) {
                    return $query
                        ->where('course_id', $request->course_id)
                        ->where('semester_id', $request->semester_id);
                }),
            ],

            'amount' => 'required|numeric|min:0',
        ]);

        FeeStructure::create($validated);

        return redirect()
            ->route('fee-structures.index')
            ->with('success', 'Fee structure created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(FeeStructure $feeStructure)
    {
        $feeStructure->load([
            'course',
            'semester',
            'academicYear',
        ]);

        return view('fee_structures.show', compact('feeStructure'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FeeStructure $feeStructure)
    {
        $courses = Course::orderBy('course_name')->get();
        $semesters = Semester::orderBy('name')->get();
        $academicYears = AcademicYear::orderBy('name')->get();

        return view('fee_structures.edit', compact(
            'feeStructure',
            'courses',
            'semesters',
            'academicYears'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FeeStructure $feeStructure)
    {
        $validated = $request->validate(
            [
                'course_id' => 'required|exists:courses,id',

                'semester_id' => 'required|exists:semesters,id',

                'academic_year_id' => [
                    'required',
                    'exists:academic_years,id',

                    Rule::unique('fee_structures')
                        ->ignore($feeStructure->id)
                        ->where(function ($query) use ($request) {
                            return $query
                                ->where('course_id', $request->course_id)
                                ->where('semester_id', $request->semester_id);
                        }),
                ],

                'amount' => 'required|numeric|min:0',
            ],
            [
                'academic_year_id.unique' =>
                    'A fee structure already exists for this course, semester and academic year.',
            ]
        );

        $feeStructure->update($validated);

        return redirect()
            ->route('fee-structures.index')
            ->with('success', 'Fee structure updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FeeStructure $feeStructure)
    {
        $feeStructure->delete();

        return redirect()
            ->route('fee-structures.index')
            ->with('success', 'Fee structure deleted successfully.');
    }
}