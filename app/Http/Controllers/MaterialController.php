<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MaterialController extends Controller
{
    public function create($student_id)
    {
        if (!Session::has('user_type') || Session::get('user_type') !== 'teacher') {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        $assignedCourseIds = Material::where('student_id', $student_id)->pluck('course_id');
        $courses = Course::whereNotIn('id', $assignedCourseIds)->get();

        return view('add_material', compact('student_id', 'courses'));
    }

    public function store(Request $request, $student_id)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        if (Material::where('student_id', $student_id)->where('course_id', $request->course_id)->exists()) {
            return redirect()->back()->with('error', 'This course is already assigned to this student.');
        }

        Material::create([
            'student_id' => $student_id,
            'course_id' => $request->course_id,
        ]);

        return redirect()->route('students.index')->with('success', 'Course added successfully');
    }

    public function index($student_id)
    {
        if (!Session::has('user_type') || Session::get('user_type') !== 'teacher') {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        $materials = Material::where('student_id', $student_id)
            ->with('course')
            ->get(['id', 'course_id', 'created_at']);

        return view('view_materials', compact('materials', 'student_id'));
    }

    public function destroy($material_id)
    {
        if (!Session::has('user_type') || Session::get('user_type') !== 'teacher') {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        $material = Material::findOrFail($material_id);
        $student_id = $material->student_id;
        $material->delete();

        return redirect()->route('materials.index', $student_id)->with('success', 'Course deleted successfully');
    }
}