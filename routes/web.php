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


/**
 * Social site authentication routes
 */
Route::get('social/login/redirect/{provider}', ['uses' => 'Auth\LoginController@redirectToProvider', 'as' => 'social.login']);
Route::get('social/login/{provider}', 'Auth\LoginController@handleProviderCallback');

/**
 * admin routes
 */
Route::group(['middlewareGroups' => ['web', 'auth', 'role:admin'], 'prefix' => 'admin'], function () {
	Route::get('dashboard', 'DashboardController@index');
});


Route::group(['middleware'=>'auth'],function(){
	Route::resource("profile_types","ProfileTypeController");


	Route::resource("template_types","TemplateTypeController");
	Route::resource("templates","TemplateController");
	Route::resource("profile_attributes","ProfileAttributeController");

	Route::get("profile/file/{filename}",['as'=>'profile.fileDownload','uses'=>'ProfileController@fileDownload']);
	Route::get("profile/form/{typeId}",['as'=>'profile.form','uses'=>"ProfileAttributeController@form"]);
	Route::get("profile/form/{typeId}/edit",['as'=>'profile.formEdit','uses'=>"ProfileAttributeController@formEdit"]);

	Route::post("profiles/update",['as'=>'profiles.updateIndividual','uses'=>'ProfileController@update']);

	Route::get("profiles/{typeId}",['as'=>'profiles.show','uses'=>'ProfileController@show']);
	Route::get("profiles/{typeId}/edit",['as'=>'profiles.editSingle','uses'=>'ProfileController@edit']);
	Route::resource("profiles","ProfileController");

	Route::get("attribute_values/create/{attributeId}",['as'=>'attribute_values.add','uses'=>"AttributeValueController@create"]);
	Route::resource("attribute_values","AttributeValueController");

	Route::get("follow/{chefId}", ['as'=>'chef.follow','uses'=>'FollowerController@follow']);
	Route::get("unfollow/{chefId}", ['as' => 'chef.unfollow', 'uses'=>'FollowerController@unfollow']);
	Route::resource("followers","FollowerController");
	Route::resource("privacies","PrivacyController");

	Route::get("articles/create/{type}", ['as'=>'articles.new','uses'=>'ArticleController@create']);
	Route::resource("articles","ArticleController");
	Route::resource("dish_articles","DishArticleController");
	
	Route::resource("recipe_articles","RecipeArticleController");
});

