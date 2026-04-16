<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\Teacher;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'user_type', 'message'];

    public function getUserNameAttribute()
    {
        if ($this->user_type === 'student') {
            $student = Student::find($this->user_id);
            return $student ? $student->first_name . ' ' . $student->last_name : 'Unknown Student';
        } elseif ($this->user_type === 'teacher') {
            $teacher = Teacher::find($this->user_id);
            return $teacher ? $teacher->first_name . ' ' . $teacher->last_name : 'Unknown Teacher';
        }
        return 'Unknown';
    }
}