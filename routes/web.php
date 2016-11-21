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

/**
 * Login Authencating Routes
 */
Route::get('login', ['middleware' => ['web'], 'as' => 'login', 'uses' => 'Auth\LoginController@showLoginForm']);
Route::post('verifyLogin', ['middleware' => ['web'], 'as' => 'login', 'uses' => 'Auth\LoginController@doLogin']);
Route::get('logout', ['middleware' => ['web'], 'as' => 'logout', 'uses' => 'Auth\LoginController@logout']);

Route::resource("profile_types","ProfileTypeController");

Route::resource("template_types","TemplateTypeController");
Route::resource("templates","TemplateController");
Route::resource("profile_attributes","ProfileAttributeController");

Route::get("profile/file/{filename}",['as'=>'profile.fileDownload','uses'=>'ProfileController@fileDownload']);
Route::get("profile/form/{typeId}",['as'=>'profile.form','uses'=>"ProfileAttributeController@form"]);
Route::resource("profiles","ProfileController");

Route::get("follow/{chefId}", ['as'=>'chef.follow','uses'=>'FollowerController@follow']);
Route::get("unfollow/{chefId}", ['as' => 'chef.unfollow', 'uses'=>'FollowerController@unfollow']);
Route::resource("followers","FollowerController");
