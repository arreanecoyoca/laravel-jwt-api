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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', 'UsersController@register');
Route::post('login', 'UsersController@authenticate');
Route::get('user', 'UsersController@getAuthenticatedUser')->middleware('jwt.verify');

Route::apiResource('articles', 'ArticlesController')->middleware('jwt.verify');

Route::group(['middleware' => ['jwt.verify']], function(){
	Route::get('article/{article}/comments/', 'CommentsController@index');
	Route::post('article/{article}/comments/', 'CommentsController@store');
	Route::patch('article/{article}/comments/{comment}', 'CommentsController@update')->middleware('comment.auth');
	Route::delete('article/{article}/comments/{comment}', 'CommentsController@destroy')->middleware('comment.auth');
});