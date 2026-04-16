<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher; // تغيير من Student إلى Teacher

class TeacherSeeder extends Seeder // تغيير من StudentSeeder إلى TeacherSeeder
{
    public function run()
    {
        Teacher::create([
            'first_name' => 'أحمد',
            'last_name' => 'محمد',
            'email' => 'teacher@example.com', // بريد إلكتروني مختلف
            'password' => bcrypt('password123'),
            'gender' => 'male',
            'hobbies' => 'reading,sports',
            'birthdate' => '1980-01-01',
        ]);
    }
}
?>