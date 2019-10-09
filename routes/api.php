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
Route::group(['prefix' => 'auth'], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});

Route::group(['middleware' => 'jwt.auth'], function ($router) {

    Route::group(['prefix' => 'client'], function ($router) {
        Route::get('/all', 'ClientController@index');
        Route::get('/{name}', 'ClientController@get');
        Route::post('create', 'ClientController@store');
        Route::post('/update/{client_id}', 'ClientController@update');
    });

    Route::group(['prefix' => 'params'], function ($router) {
        Route::post('/update/{id}', 'QueryParamsController@update');
        Route::post('/delete/{id}', 'QueryParamsController@delete');
    });

    Route::group(['prefix' => 'variable'], function ($router) {
        Route::post('/create', 'VariableParamController@create');
        Route::post('/delete/{id}', 'VariableParamController@delete');
    });

    Route::group(['prefix' => 'patterns'], function ($router) {
        Route::get('/{client_id}', 'PatternsController@get');

        Route::get('/activeRec/{client_id}', 'PatternsController@getActiveRecord');

        Route::post('/', 'PatternsController@store');
        Route::post('/update/{link_id}', 'PatternsController@update');
        Route::post('/delete/{id}', 'PatternsController@delete');
    });

    Route::group(['prefix' => 'analytics'], function ($router) {
        Route::get('/excel', 'AnalyticsController@getExcel');
    });

    Route::group(['prefix' => 'uid'], function ($router) {
        Route::post('/create', 'UidController@create');
    });

    Route::group(['prefix' => 'record'], function ($router) {
        Route::get('/{client_id}', 'RecordController@get');
        Route::post('/create', 'RecordController@create');
        Route::post('/delete/{record_id}', 'RecordController@delete');
        Route::post('/enable/{record_id}', 'RecordController@enable');
    });

});

