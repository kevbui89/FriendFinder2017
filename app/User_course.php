<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_course extends Model
{
  protected $table = 'user_course';

  public $timestamps = false;

  protected $fillable = ['email', 'course_id',];

}
