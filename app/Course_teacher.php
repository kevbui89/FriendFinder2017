<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course_teacher extends Model
{
  protected $table = 'course_teacher';

  public $timestamps = false;

  protected $fillable = ['courseID', 'title', 'teacher'];
}
