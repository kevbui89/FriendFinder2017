<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Friend;
use App\User;
use App\Course;
use App\User_course;
use App\Course_teacher;
use App\Classes;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $friendNames = null;
      $friendStatus = Friend::where(('email'), '=', Auth::user()->email)->get();
        foreach ($friendStatus as $friend)
          $friendNames[] = User::where('email', '=', $friend->friendEmail)->first();
            $courseTitleTeacher = $this->getUserCourses('1');
              $courseTimeDaySection = $this->getUserCourses('2');
                return view('home',
                  ['friendNames' => $friendNames, 'friendStatus' => $friendStatus, 'courseTitleTeacher' => $courseTitleTeacher,
                    'courseTimeDaySection' => $courseTimeDaySection]);

    }

    /**
     * Searches, updates or save friend requests
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function searchSaveUpdateFriends(Request $request)
   {
       $courseTitleTeacher = $this->getUserCourses('1');
       $courseTimeDaySection = $this->getUserCourses('2');

       if ($request->get('submitFriendSearch'))
       {
           $friendStatus = Friend::where(('email'), '=', Auth::user()->email)->get();
           foreach ($friendStatus as $friend)
               $friendNames[] = User::where('email', '=', $friend->friendEmail)->first();
           return view('home',
                   ['friendNames' => $friendNames, 'friendStatus' => $friendStatus, 'courseTitleTeacher' => $courseTitleTeacher,
                       'courseTimeDaySection' => $courseTimeDaySection]);
       }
       else if ($request->get('addFriendBtn'))
       {
           $this->saveFriend($request);
           $friendStatus = Friend::where(('email'), '=', Auth::user()->email)->get();
           foreach ($friendStatus as $friend)
               $friendNames[] = User::where('email', '=', $friend->friendEmail)->first();
           return view('home',
               ['friendNames' => $friendNames, 'friendStatus' => $friendStatus, 'courseTitleTeacher' => $courseTitleTeacher,
                   'courseTimeDaySection' => $courseTimeDaySection]);
       }
       else if ($request->get('acceptRequest'))
       {
           $this->acceptFriendRequest($request);
           $friendStatus = Friend::where(('email'), '=', Auth::user()->email)->get();
           foreach ($friendStatus as $friend)
               $friendNames[] = User::where('email', '=', $friend->friendEmail)->first();
           return view('home',
               ['friendNames' => $friendNames, 'friendStatus' => $friendStatus, 'courseTitleTeacher' => $courseTitleTeacher,
                   'courseTimeDaySection' => $courseTimeDaySection]);
       }
       else if ($request->get('declineRequest'))
       {
           $this->declineFriendRequest($request);
           $friendStatus = Friend::where(('email'), '=', Auth::user()->email)->get();
           foreach ($friendStatus as $friend)
               $friendNames[] = User::where('email', '=', $friend->friendEmail)->first();
           return view('home',
               ['friendNames' => $friendNames, 'friendStatus' => $friendStatus, 'courseTitleTeacher' => $courseTitleTeacher,
                   'courseTimeDaySection' => $courseTimeDaySection]);
       }
   }

    /**
     * Saves a friend request to the database
     * @param Request $request
     */
    private function saveFriend(Request $request)
    {
        $checkExists = Friend::where('email', '=', Auth::user()->email)->
            where('friendEmail', '=', $request->get('addFriendBtn'))->get();

        if (count($checkExists) <= 0)
        {
            $friend = new Friend();
            $friend->email = Auth::user()->email;
            $friend->user_id = Auth::user()->id;
            $friend->status = 'Request Sent';
            $friend->friendEmail = $request->get('addFriendBtn');
            $friend->save();

            $friend = new Friend();
            $friend->email = $request->get('addFriendBtn');
            $friend->user_id = Auth::user()->id;
            $friend->status = 'Request Received';
            $friend->friendEmail = Auth::user()->email;
            $friend->save();
        }
    }

    /**
     * Adds a friend to the database (confirmed)
     * @param Request $request
     */
    private function acceptFriendRequest(Request $request)
    {
        Friend::where('email', '=', Auth::user()->email)->
        where('friendEmail', '=', $request->get('acceptRequest'))->update(['status' => 'Confirmed']);

        Friend::where('email', '=', $request->get('acceptRequest'))->
        where('friendEmail', '=', Auth::user()->email)->update(['status' => 'Confirmed']);
    }

    /**
     * Removes a friend request from the database
     * @param Request $request
     */
    private function declineFriendRequest(Request $request)
    {
        Friend::where('email', '=', Auth::user()->email)->
            where('friendEmail', '=', $request->get('declineRequest'))->delete();

        Friend::where('email', '=', $request->get('declineRequest'))->
            where('friendEmail', '=', Auth::user()->email)->delete();
    }

    /**
     * Gets the user courses
     * @param String $req
     * @return array|null
     */
    public function getUserCourses(String $req){
        //Select all Course ids that the user has.
        $courseIdsThatUserHas = User_course::where('email','=', Auth::user()->email)->get();

        //This will grab the courseTitles from the course ids that the user has.
        if (count($courseIdsThatUserHas)){
            foreach ($courseIdsThatUserHas as $value) {
                $courseTimeDaySection[] = Course::where('id','=',$value->course_id)->first();
            }

            foreach($courseTimeDaySection as $value) {
                $courseTitleTeacher[] = Course_teacher::where('courseID', '=', $value->courseID)->first();
            }

            if ($req == '1' ){
                return $courseTitleTeacher;
            } else {
                return $courseTimeDaySection;
            }

        } else {
            return null;
        }

    } // end of getUserCourses
}
