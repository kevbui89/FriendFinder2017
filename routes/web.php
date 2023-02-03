<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index');

Route::get('/manageFriends', 'FriendController@index');

Route::get('/findFriendBreaks', function () {
    return view('findFriendBreaks');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post('/', 'HomeController@searchSaveUpdateFriends');

Route::post('/manageFriends', 'FriendController@searchSaveUpdateFriends');

Route::get('/manageCourses', 'CourseController@index');

Route::post('/manageCourses', 'CourseController@courses');

//Route::delete('/manageCourses','CourseController@delete');

Route::get('/findFriendBreaks', 'FriendBreaksController@index');

Route::post('/findFriendBreaks', 'FriendBreaksController@findBreakFriends');