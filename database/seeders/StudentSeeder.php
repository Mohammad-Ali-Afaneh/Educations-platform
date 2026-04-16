<?php

  namespace Database\Seeders;

  use Illuminate\Database\Seeder;
  use App\Models\Student;

  class StudentSeeder extends Seeder
  {
      public function run()
      {
          Student::create([
              'first_name' => 'أحمد',
              'last_name' => 'محمد',
              'email' => 'ahmed@example.com',
              'password' => bcrypt('password123'),
              'gender' => 'male',
              'hobbies' => 'reading,sports',
              'birthdate' => '2000-01-01',
          ]);
      }
  }
  ?>