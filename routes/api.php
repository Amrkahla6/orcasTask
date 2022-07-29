<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => ['api']], function () {

    Route::post('login', 'UserController@login');
    route::post('store-users','UserController@store');

    Route::group(['middleware' => ['auth.guard:api']], function () {
            route::get('get-users','UserController@getUsers');
            route::get('search','UserController@search');
    });
});
