<?php

use Illuminate\Database\Seeder;

use App\User_course;

class courseUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      User_course::create(['email' => 'Maximo@gmail.com',
      'course_id' => '2']);

      User_course::create(['email' => 'Maximo@gmail.com',
      'course_id' => '3']);

      User_course::create(['email' => 'Maximo@gmail.com',
      'course_id' => '55']);

      User_course::create(['email' => 'Maximo@gmail.com',
      'course_id' => '2000']);
    }
}
