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

// Rutas para el controlador de usuarios
Route::resource('/api/user', 'UserController')->middleware('api-auth');
Route::post('/api/user/login', 'UserController@login');

// Rutas para el controlador de roles
Route::resource('/api/role', 'RoleController')->middleware([ 'api-auth', 'admin-auth' ]);

// =================================================================================
// ===========================Módulo Sala Situacional===============================
// =================================================================================
	// Rutas para el controlador de colaboradores
	Route::resource('/api/collaborator', 'CollaboratorController')->middleware('api-auth');
	Route::get('/api/collaborator/document/{document}', 'CollaboratorController@getByDocument')->middleware('api-auth');
	Route::put('/api/collaborator/related/{affected}/{origin}', 'CollaboratorController@updateRelated')->middleware('api-auth');

	// Rutas de administrador para las areas
	Route::resource('/api/area', 'AreaController')->middleware([ 'api-auth', 'admin-auth' ]);

// =================================================================================
// =============================Módulo Contratación=================================
// =================================================================================
	// Rutas para el controlador de contratistas
	Route::resource('/api/contractor', 'ContractorController')->middleware('api-auth');
	Route::get('/api/contractor/document/{document}', 'ContractorController@showByDocument')->middleware('api-auth');

	// Rutas para el controlador de contratos
	Route::resource('/api/contract', 'ContractController')->middleware('api-auth');