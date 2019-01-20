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