<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Friend;
use App\Course;
use App\User_course;
use App\Course_teacher;
use App\Classes;
use Auth;

/**
* This class is the controller for the 'manage courses'.
* The index() manages whenever the user first goes onto the manage courses section of the website.
* -> Receives all the courses that the authenticated user has and displays it.
* The courses() manages the searches that the user does.
* -> Teacher/Course title/Course number search will display results depending on the search.
* -> Teacher/Course title can be approximently the same as the result. ex: Kari will give Karissa.
* -> Course number needs to be a direct search. ex 101-MQ will ONLY give 101-MQ
* -> This method also takes care of the removing and adding of the courses when the user clicks the buttons.
* -> Add simply adds the new course to the users' database and will also display it in his new list.
* -> Remove simply removes the course and will display his new list without that course.
* The getUserCourses() is a method that simply grabs the courses that the users has.
* which is called on Index(), and whenever you add/remove a course (to refresh it).
* @author Maxime Lacasse
* @version 1.0
**/
class CourseController extends Controller
{

    /**
     * CourseController constructor.
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Returns the courses of the user
     * @param String $req
     * @return array|null
     */
    public function getCourses(Request $request) {
        $session = $request->session();

        $couseArray = array();
    }

    public function getUserCourses(String $req){
      //Select all Course ids that the user has.
      $courseIdsThatUserHas = User_course::where('email','=', Auth::user()->email)->get();

      //This will grab the courseTitles from the course ids that the user has.
      if (isset($courseIdsThatUserHas)){
        foreach ($courseIdsThatUserHas as $value) {
          $courseTimeDaySection[] = Course::where('id','=',$value->course_id)->first();

          foreach($courseTimeDaySection as $value) {
            $courseTitleTeacher[] = Course_teacher::where('courseID', '=', $value->courseID)->first();
          }

            if ($req == '1' ){
              return $courseTitleTeacher;
            } else {
              return $courseTimeDaySection;
            }

        }

      } else {
        return null;
      }

      //return $courseTitleTeacher,$courseTimeDaySection;

    } // end of getUserCourses

    public function index(){

          //Get user courses
          $courseTitleTeacher = $this->getUserCourses('1');
          $courseTimeDaySection = $this->getUserCourses('2');

          if ($courseTitleTeacher == null || $courseTimeDaySection == null){
            return view('manageCourses',
                        ['errorMessage' => 'Find your courses here!']);
          } else {
          return view('manageCourses',
                     ['courseTitleTeacher' => $courseTitleTeacher,
                      'courseTimeDaySection' => $courseTimeDaySection,
                      'errorMessage' => 'Find your courses here']);
         }


    } // end of index()

    /**
     * Fetches all the courses by teacher, day and teacher
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function courses(Request $request){

    //Get user courses
    $courseTitleTeacher = $this->getUserCourses('1');
    $courseTimeDaySection = $this->getUserCourses('2');


      //SEARCH------------------------------------------------------------------
      if ($request->get('submitCourseSearch')){

        //TEACHER SEARCH-------------------------
        if ($_POST['searchOption'] == 'teacher'){
          //This will grab the courseName that the user has searched for.
          $teacherSearch = Course_teacher::where('teacher', 'ilike', '%' .
          $request->get('searchedContent') . '%')->get();
          //If there aren't any teachers name that match the search, no results to display.
          if (isset($teacherSearch)){
            foreach($teacherSearch as $search){
            $timeDaySectionSearch[] = Course::where('courseID', '=', $search->courseID)->first();
          }

            return view('manageCourses',
                       ['teacherSearch' => $teacherSearch,
                        'teacherTimeDaySectionSearch' => $timeDaySectionSearch,
                        'courseTitleTeacher' => $courseTitleTeacher,
                        'courseTimeDaySection' => $courseTimeDaySection]);
          } else {
            //No results
            return view('manageCourses',
                       ['courseTitleTeacher' => $courseTitleTeacher,
                        'courseTimeDaySection' => $courseTimeDaySection,
                        'errorMessage' => 'No teacher found with that name!']);
          }

        } // end of the TEACHER SEARCH------------------------------------------


        //COURSE NUMBER SEARCH------------------------
        if ($_POST['searchOption'] == 'courseNumber'){

          //Grabbing the class ID for the course number.
          $classIdSearch = Classes::where('classNumber', '=', $request->get('searchedContent'))->get();

          //If there are not classIDs, then there will be no results.
          if(isset($classIdSearch)){
            //Search for the courseID depending on the classID.
            $courseNumberTimeDaySection = Course::where('courseID', '=', $classIdSearch[0]->classID)->get();
            //Iterate through the courseIdSearch array to make an array of all the course titles/teacher names
            //with the same courseID.
            foreach($courseNumberTimeDaySection as $courseIds){
            $courseNumberTitleTeacher[] = Course_teacher::where('courseID', '=', $courseIds->courseID)->first();
            }

            return view('manageCourses',
                       ['courseNumberTimeDaySection' => $courseNumberTimeDaySection,
                        'courseNumberTitleTeacher' => $courseNumberTitleTeacher,
                        'courseTitleTeacher' => $courseTitleTeacher,
                        'courseTimeDaySection' => $courseTimeDaySection]);
          } else {
            return view('manageCourses',
                       ['courseTitleTeacher' => $courseTitleTeacher,
                        'courseTimeDaySection' => $courseTimeDaySection,
                        'errorMessage' => 'No course found with that course number!']);
          }

        } // end of the COURSE NUMBER SEARCH------------------------------------

        //COURSE TITLE SEARCH------------------------
        if ($_POST['searchOption'] == 'courseTitle'){

          $titleSearch = Course_teacher::where('title', 'ilike', '%' .
          $request->get('searchedContent') . '%')->get();
          //If there aren't any teachers name that match the search, no results to display.
          if (isset($titleSearch)){
            foreach($titleSearch as $search){
            $timeDaySectionSearch[] = Course::where('courseID', '=', $search->courseID)->first();
          }

            return view('manageCourses',
                       ['titleSearch' => $titleSearch,
                        'titleTimeDaySectionSearch' => $timeDaySectionSearch,
                        'courseTitleTeacher' => $courseTitleTeacher,
                        'courseTimeDaySection' => $courseTimeDaySection]);
          } else {
            //No results
            return view('manageCourses',
                       ['courseTitleTeacher' => $courseTitleTeacher,
                        'courseTimeDaySection' => $courseTimeDaySection,
                        'errorMessage' => 'No course found with that title!']);
          }

        } // END OF COURSE TITLE SEARCH-----------------------------------------

        //ADD BUTTON-------------------------------
      } else if ($request->get('removeCourseBtn')){
            //REMOVE && ADD BUTTONS
                //Remove button clicked on a specific course.
                User_course::where('course_id','=', $request->get('removeCourseBtn'))
                ->where('email', '=', Auth::user()->email)->delete();

                //Get user courses
                $courseTitleTeacher = $this->getUserCourses('1');
                $courseTimeDaySection = $this->getUserCourses('2');

                return view('manageCourses',
                           ['courseTitleTeacher' => $courseTitleTeacher,
                            'courseTimeDaySection' => $courseTimeDaySection,
                            'errorMessage' => 'Course have been removed!']);
              // end of the remove button---------------------------------------

              //ADD BUTTON----------------------------
            } else if ($request->get('addCourseBtn')){

              $courseTimeDaySection = self::getUserCourses('2');
              $userHasCourse = true;

              //Check if user doesn't already have this course.
              if (isset($courseTimeDaySection)){

              for($i = 0; $i < count($courseTimeDaySection); $i++){

                if ($request->get('addCourseBtn') == $courseTimeDaySection[$i]->id){
                  //User already have the course.
                  return view('manageCourses',
                             ['courseTitleTeacher' => $courseTitleTeacher,
                              'courseTimeDaySection' => $courseTimeDaySection,
                              'errorMessage' => 'Cannot add two of the same courses!']);
                }

                }
              }


            }

          
            //User doesn't have the course, add it!

              //Add user to database
              $courseAdd = new User_course();
              $courseAdd->email = Auth::user()->email;
              $courseAdd->course_id = $request->get('addCourseBtn');
              $courseAdd->save();

              //Get user courses -> update the display.
              $courseTitleTeacher = $this->getUserCourses('1');
              $courseTimeDaySection = $this->getUserCourses('2');

              return view('manageCourses',
                         ['courseTitleTeacher' => $courseTitleTeacher,
                          'courseTimeDaySection' => $courseTimeDaySection,
                          'errorMessage' => 'Course has been added!']);

    }// end of Course()

    /**
     * Shows a debug message
     * @param $msg
     */
    function debug($msg) {
       $msg = str_replace('"', '\\"', $msg); // Escaping double quotes
        echo "<script>console.log(\"$msg\")</script>";
}

  }// end of CourseController
