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

Route::resource('/api/user', 'UserController')->middleware('api-auth');
Route::post('/api/user/login', 'UserController@login'); 

Route::resource('/api/collaborator', 'CollaboratorController')->middleware('api-auth');
Route::get('/api/collaborator/document/{document}', 'CollaboratorController@getByDocument')->middleware('api-auth');
Route::put('/api/collaborator/related/{affected}/{origin}', 'CollaboratorController@updateRelated')->middleware('api-auth');