<?php

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

Route::group([
    'prefix' => '/auth',
    'namespace' => 'Auth'
], function () {
    Route::post('/login', 'LoginController@login');
    Route::post('/register', 'RegisterController@register');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/account', 'AccountController@index');

    Route::prefix('image')->group(function () {
        Route::get('/', 'ImageController@index');
        Route::post('/', 'ImageController@store');
        Route::put('/{id}', 'ImageController@update');
        Route::delete('/{id}', 'ImageController@delete');
    });
});

