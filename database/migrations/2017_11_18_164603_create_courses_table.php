<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->integer('classID');
            $table->integer('sectionID');
			      $table->integer('courseID');
            $table->integer('day');
			      $table->string('startTime');
			      $table->string('endTime');
        });

        $file = fopen(storage_path('../database/migrations/FakeTeachersListW2017.csv'), 'r');
        while(!feof($file)){
            $column = fgetcsv($file, ",");

            $class[] = $column[0];
            $section[] = (int)$column[1];
            $title[] = $column[2];
            $teacher[] = $column[3];
            $day[] = (int)$column[4];
            $start[] = $column[5];
            $end[] = $column[6];
        }
        fclose($file);

        $classID = 1;
        $classNumTemp = "";
        for($i = 0; $i < count($class) - 1; $i++){
            if($i + 1 == 1){
                $classNumTemp = $class[$i + 1];
                $classIDs[] = $classID;
                //$classNum[] = $class[$i];
            }
            else{
                if($classNumTemp == $class[$i + 1]){
                    $classIDs[] = $classID;
                    //$classNum[] = $class[$i];
                }
                else{
                    $classNumTemp = $class[$i + 1];
                    $classIDs[] = $classID;
                    //$classNum[] = $class[$i];
                    $classID++;
                }
            }
        }

        $courseID = 1;
        $titleTemp = "";
        $teachTemp = "";
        for($j = 0; $j < count($title) - 1; $j++){
            if($j + 1 == 1){
                $titleTemp = $title[$j + 1];
                $courseIDs[] = $courseID;
                //$titles[] = $title[$j];
                $teachTemp = $teacher[$j + 1];
                //$teachers[] = $teacher[$j];
            }
            else{
                if($titleTemp == $title[$j + 1]){
                    if($teachTemp == $teacher[$j + 1]){
                        $courseIDs[] = $courseID;
                        //$titles[] = $title[$j];
                        //$teachers[] = $teacher[$j];
                    }
                    else{
                        $courseID++;
                        $courseIDs[] = $courseID;
                        //$titles[] = $title[$j];
                        $teachTemp = $teacher[$j + 1];
                        //$teachers[] = $teacher[$j];
                    }
                }
                else{
                    $courseID++;
                    $titleTemp = $title[$j + 1];
                    $courseIDs[] = $courseID;
                    //$titles[] = $title[$j];
                    $teachTemp = $teacher[$j + 1];
                    //$teachers[] = $teacher[$j];
                }
            }
        }


        for($x = 1; $x < count($day) - 1; $x++){
           DB::table('courses')->insert(array('classID'=>$classIDs[$x-1], 'sectionID'=>$section[$x], 'courseID'=>$courseIDs[$x-1],
               'day'=>$day[$x], 'startTime'=>$start[$x], 'endTime'=>$end[$x]));
        }

    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
}
