<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        if (!Session::has('user_type') || Session::get('user_type') !== 'teacher') {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        $search = $request->query('search');
        $query = Course::query();

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('base_mark', 'LIKE', "%{$search}%");
        }

        $courses = $query->get();
        return view('dashboard', compact('courses'));
    }

    public function allCourses(Request $request)
    {
        if (!Session::has('user_type') || Session::get('user_type') !== 'student') {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        $search = $request->query('search');
        $query = Course::query();

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('base_mark', 'LIKE', "%{$search}%");
        }

        $courses = $query->get();
        return view('all_courses', compact('courses'));
    }

    public function create()
    {
        if (!Session::has('user_type') || Session::get('user_type') !== 'teacher') {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        return view('add_course');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'base_mark' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048', // Validate image upload
        ], [
            'name.required' => 'Course name is required',
            'base_mark.required' => 'Base mark is required',
        ]);

        $data = $request->only(['name', 'description', 'base_mark']);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('courses', 'public');
        }

        Course::create($data);

        return redirect()->route('dashboard')->with('success', 'Course added successfully');
    }

    public function edit(Course $course)
    {
        if (!Session::has('user_type') || Session::get('user_type') !== 'teacher') {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        return view('edit_course', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'base_mark' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048', // Validate image upload
        ]);

        $data = $request->only(['name', 'description', 'base_mark']);
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($course->image) {
                Storage::disk('public')->delete($course->image);
            }
            $data['image'] = $request->file('image')->store('courses', 'public');
        }

        $course->update($data);

        return redirect()->route('dashboard')->with('success', 'Course updated successfully');
    }

    public function destroy(Course $course)
    {
        if (!Session::has('user_type') || Session::get('user_type') !== 'teacher') {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        if ($course->image) {
            Storage::disk('public')->delete($course->image);
        }
        $course->delete();
        return redirect()->route('dashboard')->with('success', 'Course deleted successfully');
    }
}