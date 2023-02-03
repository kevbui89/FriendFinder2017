<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('api/allfriends', 'ApiController@allfriends');
Route::get('api/coursefriends', 'ApiController@coursefriends');
Route::get('api/friendbreaks', 'ApiController@friendbreaks');
Route::get('api/whereisfriend', 'ApiController@whereisfriend');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
