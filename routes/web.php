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

use Illuminate\Support\Facades\Route;

Route::prefix('user')->group(function () {
    Route::get('/list', 'UserController@list');
    Route::get('/{id}', 'UserController@get')->where('id', '[0-9]+');
    Route::post('create', 'UserController@create');
    Route::put('update/{id}', 'UserController@update')->where('id', '[0-9]+');
    Route::delete('delete/{id}', 'UserController@delete')->where('id', '[0-9]+');
    Route::post('/{id}/attach/role/{roleId}', 'UserController@attach')
        ->where('id', '[0-9]+')
        ->where('roleId', '[0-9]+');
    Route::delete('/{id}/detach/role/{roleId}', 'UserController@detach')
        ->where('id', '[0-9]+')
        ->where('roleId', '[0-9]+');
});

Route::prefix('role')->group(function () {
    Route::get('/list', 'RoleController@list');
    Route::get('/{id}', 'RoleController@get')->where('id', '[0-9]+');
    Route::post('create', 'RoleController@create');
    Route::put('update/{id}', 'RoleController@update');
    Route::delete('delete/{id}', 'RoleController@delete');
    Route::post('/{id}/attach/ability/{abilityId}', 'RoleController@attach')
        ->where('id', '[0-9]+')
        ->where('abilityId', '[0-9]+');
    Route::delete('/{id}/detach/ability/{abilityId}', 'RoleController@detach')
        ->where('id', '[0-9]+')
        ->where('abilityId', '[0-9]+');
});

Route::prefix('ability')->group(function () {
    Route::get('/list', 'AbilityController@list');
    Route::get('/{id}', 'AbilityController@get')->where('id', '[0-9]+');
    Route::post('create', 'AbilityController@create');
    Route::put('update/{id}', 'AbilityController@update');
    Route::delete('delete/{id}', 'AbilityController@delete');
});
