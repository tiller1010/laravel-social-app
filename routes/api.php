<?php

use Illuminate\Http\Request;
use App\Events\PingUser;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:api']], function() {
	Route::post('messages/store', 'Api\MessageController@store');
	// Route::get('tasks', 'Api\TaskController@index');
	// Route::post('tasks/{task}/comments/store', 'Api\CommentController@store');
	// Route::get('tasks/{task}/comments', 'Api\CommentController@index');
	Route::get('activities', 'Api\ActivityController@index');
});

Route::post('/ping-user', function(Request $request){
	broadcast(new PingUser($request->pingedUserId, $request->fromUserID));
});