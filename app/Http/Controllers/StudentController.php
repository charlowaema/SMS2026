<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Semester;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        $students = Student::with([
        'enrollments.course',
        'enrollments.semester',
        'enrollments.academicYear',
    ])
    ->latest()
    ->paginate(10);

    return view('students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
  public function create()
{
    $courses = Course::where('status', 'active')
        ->orderBy('course_name')
        ->get();

    $semesters = Semester::orderBy('name')->get();

    $academicYears = AcademicYear::orderByDesc('name')->get();

    return view('students.create', compact(
        'courses',
        'semesters',
        'academicYears'
    ));
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Student $student)
    {
       $validated = $request->validate([

        // Student information
        'student_number' => [
            'required',
            'string',
            'max:50',
            'unique:students,student_number',
        ],

        'first_name' => [
            'required',
            'string',
            'max:100',
        ],

        'last_name' => [
            'required',
            'string',
            'max:100',
        ],

        'gender' => [
            'nullable',
            'in:Male,Female,Other',
        ],

        'date_of_birth' => [
            'nullable',
            'date',
        ],

        'phone' => [
            'nullable',
            'string',
            'max:20',
        ],

        'email' => [
            'nullable',
            'email',
            'max:255',
        ],

        'address' => [
            'nullable',
            'string',
        ],

        'status' => [
            'required',
            'in:active,inactive',
        ],

        // Academic information
        'course_id' => [
            'required',
            'exists:courses,id',
        ],

        'semester_id' => [
            'required',
            'exists:semesters,id',
        ],

        'academic_year_id' => [
            'required',
            'exists:academic_years,id',
        ],

    ]);


    DB::transaction(function () use ($validated) {
        $student = Student::create([
            'student_number' => $validated['student_number'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'gender' => $validated['gender'] ?? null,
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'address' => $validated['address'] ?? null,
            'status' => $validated['status'],
        ]);


        Enrollment::create([
            'student_id' => $student->id,
            'course_id' => $validated['course_id'],
            'semester_id' => $validated['semester_id'],
            'academic_year_id' => $validated['academic_year_id'],
        ]);

    });


    return redirect()
        ->route('students.index')
        ->with('success', 'Student registered successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        $student->load([
        'enrollments.course',
        'enrollments.semester',
        'enrollments.academicYear',
    ]);

    $enrollment = $student->enrollments
        ->sortByDesc('created_at')
        ->first();

    return view('students.show', compact(
        'student',
        'enrollment'
    ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        $courses = Course::where('status', 'active')
        ->orderBy('course_name')
        ->get();

    $semesters = Semester::orderBy('name')->get();

    $academicYears = AcademicYear::orderByDesc('name')->get();

    $enrollment = $student->enrollments()
        ->latest()
        ->first();

    return view('students.edit', compact(
        'student',
        'courses',
        'semesters',
        'academicYears',
        'enrollment'
    ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
         $validated = $request->validate([

        // Student information
        'student_number' => [
            'required',
            'string',
            'max:50',
            'unique:students,student_number,' . $student->id,
        ],

        'first_name' => [
            'required',
            'string',
            'max:100',
        ],

        'last_name' => [
            'required',
            'string',
            'max:100',
        ],

        'gender' => [
            'nullable',
            'in:Male,Female,Other',
        ],

        'date_of_birth' => [
            'nullable',
            'date',
        ],

        'phone' => [
            'nullable',
            'string',
            'max:20',
        ],

        'email' => [
            'nullable',
            'email',
            'max:255',
        ],

        'address' => [
            'nullable',
            'string',
        ],

        'status' => [
            'required',
            'in:active,inactive',
        ],

        // Academic information
        'course_id' => [
            'required',
            'exists:courses,id',
        ],

        'semester_id' => [
            'required',
            'exists:semesters,id',
        ],

        'academic_year_id' => [
            'required',
            'exists:academic_years,id',
        ],

    ]);


    DB::transaction(function () use ($validated, $student) {

        // Update student
        $student->update([
            'student_number' => $validated['student_number'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'gender' => $validated['gender'] ?? null,
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'address' => $validated['address'] ?? null,
            'status' => $validated['status'],
        ]);


        // Find current/latest enrollment
        $enrollment = $student->enrollments()
            ->latest()
            ->first();


        if ($enrollment) {

            $enrollment->update([
                'course_id' => $validated['course_id'],
                'semester_id' => $validated['semester_id'],
                'academic_year_id' => $validated['academic_year_id'],
            ]);

        } else {

            Enrollment::create([
                'student_id' => $student->id,
                'course_id' => $validated['course_id'],
                'semester_id' => $validated['semester_id'],
                'academic_year_id' => $validated['academic_year_id'],
            ]);

        }

    });


    return redirect()
        ->route('students.index')
        ->with('success', 'Student updated successfully.');
    }

 

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
