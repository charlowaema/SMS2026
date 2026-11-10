<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Semester;
use App\Models\Student;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $enrollments = Enrollment::with([
        'student',
        'course',
        'semester',
        'academicYear',
    ])
    ->latest()
    ->paginate(10);

    return view('enrollments.index', compact('enrollments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    $students = Student::where('status', 'active')
        ->orderBy('first_name')
        ->get();

    $courses = Course::where('status', 'active')
        ->orderBy('course_name')
        ->get();

    $semesters = Semester::orderBy('name')->get();

    $academicYears = AcademicYear::orderByDesc('name')->get();

    return view('enrollments.create', compact(
        'students',
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
        'student_id' => ['required', 'exists:students,id'],
        'course_id' => ['required', 'exists:courses,id'],
        'semester_id' => ['required', 'exists:semesters,id'],
        'academic_year_id' => ['required', 'exists:academic_years,id'],
    ]);

    $exists = Enrollment::where('student_id', $validated['student_id'])
        ->where('course_id', $validated['course_id'])
        ->where('semester_id', $validated['semester_id'])
        ->where('academic_year_id', $validated['academic_year_id'])
        ->exists();

    if ($exists) {
        return back()
            ->withInput()
            ->withErrors([
                'student_id' => 'This student is already enrolled in this course, semester, and academic year.',
            ]);
    }

    Enrollment::create($validated);

    return redirect()
        ->route('enrollments.index')
        ->with('success', 'Enrollment created successfully.');
}
    

    /**
     * Display the specified resource.
     */
  public function show(Enrollment $enrollment)
{
    $enrollment->load([
        'student',
        'course',
        'semester',
        'academicYear',
    ]);

    return view('enrollments.show', compact('enrollment'));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Enrollment $enrollment)
    {
           $students = Student::where('status', 'active')
        ->orderBy('first_name')
        ->get();

    $courses = Course::where('status', 'active')
        ->orderBy('course_name')
        ->get();

    $semesters = Semester::orderBy('name')->get();

    $academicYears = AcademicYear::orderByDesc('name')->get();

    $enrollment->load([
        'student',
        'course',
        'semester',
        'academicYear',
    ]);

    return view('enrollments.edit', compact(
        'enrollment',
        'students',
        'courses',
        'semesters',
        'academicYears'
    ));
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, Enrollment $enrollment)
{
    $validated = $request->validate([
        'student_id' => ['required', 'exists:students,id'],
        'course_id' => ['required', 'exists:courses,id'],
        'semester_id' => ['required', 'exists:semesters,id'],
        'academic_year_id' => ['required', 'exists:academic_years,id'],
    ]);

    $exists = Enrollment::where('student_id', $validated['student_id'])
        ->where('course_id', $validated['course_id'])
        ->where('semester_id', $validated['semester_id'])
        ->where('academic_year_id', $validated['academic_year_id'])
        ->where('id', '!=', $enrollment->id)
        ->exists();

    if ($exists) {
        return back()
            ->withInput()
            ->withErrors([
                'student_id' => 'This student is already enrolled in this course, semester, and academic year.',
            ]);
    }

    $enrollment->update($validated);

    return redirect()
        ->route('enrollments.index')
        ->with('success', 'Enrollment updated successfully.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Enrollment $enrollment)
    {
        // Prevent deletion if payments are already associated
    if ($enrollment->student->payments()->exists()) {
        return redirect()
            ->route('enrollments.index')
            ->with('error', 'This enrollment cannot be deleted because the student has payment records.');
    }

    $enrollment->delete();

    return redirect()
        ->route('enrollments.index')
        ->with('success', 'Enrollment deleted successfully.');
    }
}
