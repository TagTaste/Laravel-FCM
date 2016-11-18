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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');
Route::resource("profile_types","ProfileTypeController");

Route::resource("template_types","TemplateTypeController");
Route::resource("templates","TemplateController");
Route::resource("profile_attributes","ProfileAttributeController");

Route::get("profile/form/{typeId}",['as'=>'profile.form','uses'=>"ProfileAttributeController@form"]);
Route::resource("profile","ProfileAttributeController");