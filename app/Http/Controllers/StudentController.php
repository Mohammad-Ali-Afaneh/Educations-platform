<?php

namespace App\Http\Controllers;

use App\Models\Mark;
use App\Models\Material;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        if (!Session::has('user_type') || Session::get('user_type') !== 'teacher') {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        $search = $request->query('search');
        $query = Student::select('id', 'first_name', 'last_name', 'email', 'gender', 'birthdate');

        if ($search) {
            $query->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('gender', 'LIKE', "%{$search}%")
                  ->orWhere('birthdate', 'LIKE', "%{$search}%");
        }

        $students = $query->get();
        return view('view_students', compact('students'));
    }

    public function dashboard()
    {
        // Verify that the user is a student
        if (!Session::has('user_type') || Session::get('user_type') !== 'student') {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        $student_id = Session::get('user_id');

        // Fetch the student's courses with the course name from the Courses table
        $materials = Material::select('materials.id', 'courses.name as name', 'materials.created_at')
            ->join('courses', 'materials.course_id', '=', 'courses.id')
            ->where('materials.student_id', $student_id)
            ->get();

        // Fetch the student's marks linked to the courses
        $marks = Mark::select('marks.material_id', 'marks.mark', 'marks.created_at as mark_created_at')
            ->where('marks.student_id', $student_id)
            ->get();

        // Fetch all teachers for chat list
        $teachers = Teacher::select('id', 'first_name', 'last_name')->get();

        return view('student_dashboard', compact('materials', 'marks', 'teachers'));
    }
}