<?php

namespace App\Http\Controllers;

use App\Models\Mark;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class MarkController extends Controller
{
    public function create($material_id, $student_id)
    {
        if (!Session::has('user_type') || Session::get('user_type') !== 'teacher') {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        $material = Material::findOrFail($material_id);
        if ($material->student_id != $student_id) {
            return redirect()->back()->with('error', 'Invalid ID');
        }

        $existing_mark = Mark::where('material_id', $material_id)->where('student_id', $student_id)->first();
        $mark = $existing_mark ? $existing_mark->mark : null;

        $is_update = $mark !== null;
        return view('add_mark', compact('material_id', 'student_id', 'mark', 'is_update'));
    }

    public function store(Request $request, $material_id, $student_id)
    {
        $request->validate([
            'mark' => 'required|integer|min:0|max:100',
        ], [
            'mark.required' => 'Mark is required',
            'mark.integer' => 'Mark must be a number',
            'mark.min' => 'Mark must be 0 or higher',
            'mark.max' => 'Mark must be 100 or lower',
        ]);

        DB::transaction(function () use ($material_id, $student_id, $request) {
            Mark::where('material_id', $material_id)->where('student_id', $student_id)->delete();
            Mark::create([
                'material_id' => $material_id,
                'student_id' => $student_id,
                'mark' => $request->mark,
            ]);
        });

        $action = Mark::where('material_id', $material_id)->where('student_id', $student_id)->exists() ? 'Mark updated' : 'Mark added';
        return redirect()->route('materials.index', $student_id)->with('success', $action . ' successfully');
    }
}