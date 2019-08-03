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

Route::group([
    'prefix' => 'backend',
    'namespace' => 'Backend'
], function () {

    Route::group([
        'prefix' => 'animals'
    ], function () {

        Route::post('save', 'AnimalsController@save');
        Route::post('get-list', 'AnimalsController@getList');
        Route::get('get-one/{id', 'AnimalsController@getOne');
        Route::get('delete/{id', 'AnimalsController@delete');

    });

    Route::group([
        'prefix' => 'places'
    ], function () {
        Route::post('save', 'PlacesController@save');
        Route::post('get-list', 'PlacesController@getList');
        Route::get('get-one/{id}', 'PlacesController@getOne');
        Route::get('delete/{id}', 'PlacesController@delete');
    });

    Route::group([
      'users'
    ], function() {

    });

    Route::group([
      'visitors-types'
    ], function() {
          Route::post('save', 'VisitorsTypesController@save');
          Route::post('get-list', 'VisitorsTypesController@getList');
          Route::get('get-one/{id}', 'VisitorsTypesController@getList');
    });
});

Route::group([
    'prefix' => 'users',
], function () {
    Route::post('regist', 'UsersController@regist');
    Route::post('login', 'UsersController@login');
    Route::post('update-one', 'UsersController@updateOne');
    Route::get('activate-user/{token}', 'UsersController@activateUser');
    Route::get('get-one/{id}', 'UsersController@getOne');
    Route::get('get-all-roles', 'UsersController@getAllRole');
    Route::get('is-used-email/{email}', 'UsersController@isUsedEmail');
});

Route::group([
    'prefix' => 'visitors'
], function () {
    Route::post('save', 'VisitorsController@save');
    Route::post('get-list', 'VisitorsController@getList');
    Route::get('get-one/{id}', 'VisitorsController@getOne');
    Route::get('delete/{id}', 'VisitorsController@delete');
});
