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

Route::get('/home', ['as'=>'home','uses'=>'HomeController@index']);

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
//
Route::group(['middlewareGroups' => ['web', 'auth', 'role:admin'], 'prefix' => 'admin',
    'as'=>'admin.'], //do notice the dot!
    function () {
	Route::get('dashboard', ['uses'=>'DashboardController@index','as'=>'dashboard']);

	Route::get('role/add', 'RoleController@create');
	Route::post('role/store', 'RoleController@store');
	Route::get('role/view', 'RoleController@index');
	Route::get('role/destroy/{id}', 'RoleController@destroy');
	Route::get('role/edit/{id}', 'RoleController@show');
	Route::post('role/update/{id}', 'RoleController@update');

	Route::get('permission/add', 'PermissionController@create');
	Route::post('permission/store', 'PermissionController@store');
	Route::get('permission/view', 'PermissionController@index');
	Route::get('permission/destroy/{id}', 'PermissionController@destroy');
	Route::get('permission/edit/{id}', 'PermissionController@show');
	Route::post('permission/update/{id}', 'PermissionController@update');
});


Route::group(['middleware'=>'auth'],function(){
    Route::get('/images/{file}',function($file){

        $f = storage_path('app/files/'.$file);
        if(file_exists($f)){
            return response()->file($f);
        }
    });

	Route::resource("profile_types","ProfileTypeController");


	Route::resource("template_types","TemplateTypeController");
	Route::resource("templates","TemplateController");
	Route::get("profile_attributes/create/{parentId}",['as'=>'profile_attributes.addChild','uses'=>'ProfileAttributeController@create']);
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
	Route::get("/product/image/{filename}",function($filename){
		return response()->file(storage_path(\App\Product::$imagePath . $filename));
	});
	Route::get('products/user/{userId}',['as'=>'products.user','uses'=>'ProductController@showForUser']);
	Route::resource("products","ProductController");
	Route::get("blogs/images/{filename}",['as'=>'blogs.image','uses'=>'BlogArticleController@image']);
	Route::resource("blog_articles","BlogArticleController");

    Route::resource("ideabooks","IdeabookController");


});