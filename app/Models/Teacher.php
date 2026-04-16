<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model // تغيير من Teachers إلى Teacher
{
    use HasFactory;

    protected $table = 'teachers';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'gender',
        'hobbies',
        'birthdate',
    ];

    protected $hidden = ['password'];
}
?>