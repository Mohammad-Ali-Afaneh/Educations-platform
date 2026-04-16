<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id',
        'student_id',
        'mark',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}