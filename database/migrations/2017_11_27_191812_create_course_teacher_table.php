<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateCourseTeacherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_teacher', function (Blueprint $table) {
            $table->integer('courseID');
            $table->string('title');
            $table->string('teacher');
        });
        $file = fopen(storage_path('../database/migrations/FakeTeachersListW2017.csv'), 'r');
        while (!feof($file)) {
            $column = fgetcsv($file, ",");
            $class[] = $column[0];
            $section[] = $column[1];
            $title[] = $column[2];
            $teacher[] = $column[3];
            $day[] = $column[4];
            $start[] = $column[5];
            $end[] = $column[6];
        }
        fclose($file);
        $courseID = 1;
        $titleTemp = "";
        $teachTemp = "";
        for ($j = 0; $j < count($title) - 1; $j++) {
            if ($j + 1 == 1) {
                $courseIDs[] = $courseID;
                $titleTemp = $title[$j + 1];
                $titles[] = $title[$j + 1];
                $teachTemp = $teacher[$j + 1];
                $teachers[] = $teacher[$j + 1];
            } else {
                if ($titleTemp == $title[$j + 1]) {
                    if ($teachTemp == $teacher[$j + 1]) {
                        //do nothing, this combination is already in the arrays
                    } else {
                        $courseID++;
                        $courseIDs[] = $courseID;
                        $titles[] = $title[$j + 1];
                        $teachTemp = $teacher[$j + 1];
                        $teachers[] = $teacher[$j + 1];
                    }
                } else {
                    $courseID++;
                    $courseIDs[] = $courseID;
                    $titleTemp = $title[$j + 1];
                    $titles[] = $title[$j + 1];
                    $teachTemp = $teacher[$j + 1];
                    $teachers[] = $teacher[$j + 1];
                }
            }
        }
        for ($x = 0; $x < count($courseIDs) - 1; $x++) {
            DB::table('course_teacher')->insert(array('courseID' => $courseIDs[$x], 'title' => $titles[$x], 'teacher' => $teachers[$x]));
        }
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_teacher');
    }
}
