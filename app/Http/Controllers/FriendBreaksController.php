<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Friend;
use App\Course;
use Auth;

class FriendBreaksController extends Controller
{
    /**
     * FriendBreaksController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('findFriendBreaks');
    }

    /**
     * Finds the users friend that have overlapping breaks
     */
    public function findBreakFriends(Request $request)
    {
        $startBreak = $request->get('startName');
        $endBreak = $request->get('endName');
        $breaksFriend = 0;
        $j = 0;

        // Gets each friend once
        $friendStatus = Friend::where('email','=', Auth::user()->email)->get();
        foreach ($friendStatus as $friend)
            $friendObjs[] = User::where('email', '=', $friend->friendEmail)->orderby('email')->first();

        for ($i = 0; $i < count($friendObjs); $i++)
        {
            $friendCourse = Course::where('day', '=', $this->getDay($request))->
            whereIn('id', function ($q) use ($friendObjs, $i)
            {
                $q->select('course_id')->from('user_course')->
                where('email', '=', $friendObjs[$i]->email)->orderby('email');
            }
            )->orderby('startTime')->get();

            // Compare the start time and end time to the start break and end break the user input of each course
            for ($course = 0; $course < count($friendCourse) - 1; $course++)
            {
                $j++;
                if($friendCourse[$course]->endTime <= $startBreak && $friendCourse[$j]->startTime >= $endBreak)
                {
                    $name[] = $friendObjs[$i]->name;
                    $breaksFriend++;
                }
                else if($friendCourse[$course]->endTime > $startBreak && $friendCourse[$course]->endTime < $endBreak)
                {
                    $name[] = $friendObjs[$i]->name;
                    $breaksFriend++;
                }
                else if($friendCourse[$j]->startTime < $endBreak && $friendCourse[$j]->startTime > $startBreak)
                {
                    $name[] = $friendObjs[$i]->name;
                    $breaksFriend++;
                }
            }
            $j = 0;

        }

        // Check if friends have break
        if($breaksFriend > 0)
            return view('findFriendBreaks', ["friendNames" => $name]);
        return view('findFriendBreaks');
    }

    /**
     * Gets the day from the user and puts it as an integer
     */
    private function getDay(Request $request)
    {
        $value = 0;
        $day = $request->get('dayName');
        if (isset($day) && ctype_alpha($day)) {
            $day = trim(strtolower($day));
            switch ($day) {
                case "monday":
                    $value = 1;
                    break;
                case "tuesday":
                    $value = 2;
                    break;
                case "wednesday":
                    $value = 3;
                    break;
                case "thursday":
                    $value = 4;
                    break;
                case "friday":
                    $value = 5;
                    break;
                default:
                    $value = 0;
                    break;
            }
        }
        return $value;
    }
}
