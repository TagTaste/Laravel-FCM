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
use Illuminate\Support\Facades\Redis;

Route::get('/redis',function(){
    //Redis::publish('notifications', json_encode(['foo' => 'bar']));
    event(new \App\Events\TestEvent(str_random(32)));
});
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

Route::get('mail', [ 'uses' => 'Admin\SendWelComeMailController@showMailForm']);
Route::post('mail', [ 'uses' => 'Admin\SendWelComeMailController@doMail']);

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

	Route::get("profile/form/{typeId}",['as'=>'profile.form','uses'=>"ProfileAttributeController@form"]);
	Route::get("profile/form/{typeId}/edit",['as'=>'profile.formEdit','uses'=>"ProfileAttributeController@formEdit"]);

	Route::get("attribute_values/create/{attributeId}",['as'=>'attribute_values.add','uses'=>"AttributeValueController@create"]);
	Route::resource("attribute_values","AttributeValueController");

	Route::get("follow/{chefId}", ['as'=>'chef.follow','uses'=>'FollowerController@follow']);
	Route::get("unfollow/{chefId}", ['as' => 'chef.unfollow', 'uses'=>'FollowerController@unfollow']);
	Route::resource("followers","FollowerController");
	Route::resource("privacies","PrivacyController");

	Route::get("articles/create/{type}", ['as'=>'articles.new','uses'=>'ArticleController@create']);
	Route::resource("articles","ArticleController");
	Route::get("articles/edit/{id}/{type}", ['as'=>'articles.edit','uses'=>'ArticleController@edit']);
	Route::resource("dish_articles","DishArticleController");

	Route::resource("recipe_articles","RecipeArticleController");
	Route::get("recipe/create/{id}", ['as'=>'recipe_articles.create','uses'=>'RecipeArticleController@create']);
	Route::post("recipe/delete", ['uses'=>'RecipeArticleController@delete']);

	Route::get("/product/image/{filename}",function($filename){
		return response()->file(storage_path(\App\Product::$imagePath . $filename));
	});
	Route::get('products/user/{userId}',['as'=>'products.user','uses'=>'ProductController@showForUser']);
	Route::resource("products","ProductController");
	Route::get("blogs/images/{filename}",['as'=>'blogs.image','uses'=>'BlogArticleController@image']);
	Route::resource("blog_articles","BlogArticleController");

	Route::get("dishes/images/{filename}",['as'=>'dishes.image','uses'=>'DishArticleController@image']);

	Route::get('ideas',['as'=>'ideas','uses'=>'IdeabookController@ideas']);
    Route::resource("ideabooks","IdeabookController");
    Route::post("ideabook/add/{articleId}",['as'=>'ideabooks.addArticle','uses'=>'IdeabookArticleController@store']);
    Route::get('ideabook/remove/{articleId}',['as'=>'ideas.remove','uses'=>'IdeabookArticleController@destroy']);

    //Route::resource("ideabook_articles","IdeabookArticleController");
    Route::get("profiles/image/{id}.jpg",['as'=>'profile.image','uses'=>'ProfileController@image']);
    Route::get("profiles/hero/{id}.jpg",['as'=>'profile.heroImage','uses'=>'ProfileController@heroImage']);
    Route::resource("profiles","ProfileController");
    Route::resource("professionals","ProfessionalController");
    //Route::resource("profile_books","ProfileBookController");
    //Route::resource("profile_shows","ProfileShowController");
    Route::resource("projects","ProjectController");
    Route::resource("experiences","ExperienceController");
    Route::resource("awards","AwardController");
    Route::resource("certifications","CertificationController");
    Route::resource("cuisines","CuisineController");
    Route::resource("establishment_types","EstablishmentTypeController");
    Route::post("albums/tag",['uses'=>'AlbumController@tag','as'=>'albums.tag']);
    Route::resource("albums","AlbumController");
    Route::resource("education","EducationController");
    Route::get("photos/{id}.jpg",function($id){
        $file = \App\Photo::find($id);
        return response()->file(storage_path("app/" . $file->file));
    });
    Route::post("photos/tag",['uses'=>'PhotoController@tag','as'=>'photos.tag']);
    Route::resource("photos","PhotoController");

    Route::resource("companies","CompanyController");

        Route::group(['namespace'=>'Company','prefix'=>'company','as'=>'company.'],function(){
            Route::resource("status","StatusController");
            Route::resource("types","TypeController");
        });

        Route::group(['namespace'=>'Company','prefix'=>"companies/{companyId}",'as'=>'companies.'],function(){
            Route::resource("websites","WebsiteController");
            Route::resource("blogs","BlogController");
            Route::resource("advertisements","AdvertisementController");
            Route::resource("addresses","AddressController");
            //Route::resource("portfolios","PortfolioController");
        });

    Route::resource("designations", "DesignationController");


});

Route::get("built",function(){
    echo "Yo!";
});
//
//Route::get('testmail', function(){
//
//    $data = [
//        'subject' => 'Following collaboration has expired',
//        'title' => 'Following collaboration has expired',
//        'owner' => 'Sonika',
//        'msg' => 'Burger King India Pvt. Ltd’s collaboration expires today.',
//
//        'job' => [
//            'id' => 100,
//            'title' => 'Event manager',
//            'owner_id' => 25,
//            'owner_name' => 'Dr\'s Organic citrus farm',
//            'location' => 'New Delhi, Delhi, India',
//            'imageUrl' => 'https://www.tagtaste.com/images/emails/profile-circle.png',
//            'btn_text' => 'View',
//            'btn_url' => env('APP_URL').'/collaborate/100/applications',
//        ],
//
//        'msg2' => 'Here are some interested parties:',
//        'profile_count' => 5,
//        'profiles' => [
//            [
//                'id' => 2,
//                'imageUrl' => 'https://www.tagtaste.com/images/emails/profile-circle.png',
//                'name' => 'Arun Tangri',
//                'tagline' => 'New Delhi, Delhi, India',
//            ],
//            [
//                'id' => 3,
//                'imageUrl' => 'https://www.tagtaste.com/images/emails/profile-circle.png',
//                'name' => 'Jaspal Sabharwal',
//                'tagline' => 'Gurugram, India',
//            ],
//            [
//                'id' => 4,
//                'imageUrl' => 'https://www.tagtaste.com/images/emails/profile-circle.png',
//                'name' => 'Varun Tangri',
//                'tagline' => 'New Delhi, Delhi, India',
//            ],
//        ],
//
//        'master_btn_text' => 'VIEW ON TAGTASTE',
//        'master_btn_url' => env('APP_URL').'/collaborate/100/applications',
//
//    ];
//
//    return view('emails.expire-job', compact('data'));
//});
