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


Route::prefix('user')->group(function () {
    Route::post('create', 'UserController@create');
    Route::put('update/{id}', 'UserController@update');
    Route::delete('delete/{id}', 'UserController@delete');
    Route::post('attach/{userId}/{roleId}', 'UserController@attach');
    Route::post('detach/{userId}/{roleId}', 'UserController@detach');
});

Route::prefix('role')->group(function () {
    Route::post('create', 'RoleController@create');
    Route::put('update/{id}', 'RoleController@update');
    Route::delete('delete/{id}', 'RoleController@delete');
    Route::post('attach/{roleId}/{abilityId}', 'RoleController@attach');
    Route::post('detach/{roleId}/{abilityId}', 'RoleController@detach');
});

Route::prefix('ability')->group(function () {
    Route::post('create', 'AbilityController@create');
    Route::put('update/{id}', 'AbilityController@update');
    Route::delete('delete/{id}', 'AbilityController@delete');
});
