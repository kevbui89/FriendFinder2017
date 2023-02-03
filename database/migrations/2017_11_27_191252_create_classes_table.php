<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->increments('classID');
            $table->string('classNumber');
        });

        $file = fopen(storage_path('../database/migrations/FakeTeachersListW2017.csv'), 'r');
        while(!feof($file)){
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

        $classID = 1;
        $classNumTemp = 0;
        for($i = 0; $i < count($class) - 1; $i++){
            if($i + 1 == 1){
                $classNumTemp = $class[$i + 1];
                $classIDs[] = $classID;
                $classNums[] = $class[$i + 1];
                $classID++;
            }
            else{
                if($classNumTemp != $class[$i + 1]){
                    $classNumTemp = $class[$i + 1];
                    $classIDs[] = $classID;
                    $classNums[] = $class[$i + 1];
                    $classID++;
                }
            }
        }

        for($x = 0; $x < count($classIDs) - 1; $x++){
            DB::table('classes')->insert(array('classID'=>$classIDs[$x], 'classNumber'=>$classNums[$x]));
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classes');
    }
}
