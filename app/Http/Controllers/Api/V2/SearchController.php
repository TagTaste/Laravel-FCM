<?php

namespace App\Http\Controllers\Api\V2;

use App\Company;
use App\Education;
use App\ProductCategory;
use App\Profile;
use App\Profile\Experience;
use App\PublicReviewProduct;
use App\Recipe\Collaborate;
use App\SearchClient;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\V2\FeedController;
use App\ReviewCollection;

class SearchController extends Controller
{
	public function newExplore(Request $request)
    {
        $loggedInProfileId = $request->user()->profile->id;

        $profile = $request->user()->profile;
        $profile_id = $profile->id;
        
        $model = [];

        /* ui type = 1 is start */
        $search_filter_detail = array(
        	"search_filter" => array(),
	        "count" => 0,
	        "type" => "search_filter"
        );

        array_push(
        	$search_filter_detail['search_filter'],
        	array(
        		"name" => "Everything",
        		"key" => null,
        		"value" =>  null
        	)
        );

        array_push(
        	$search_filter_detail['search_filter'],
        	array(
        		"name" => "Products",
    			"key" => "type",
    			"value" => "products"
        	)
        );

        array_push(
        	$search_filter_detail['search_filter'],
        	array(
        		"name" => "People",
    			"key" => "type",
    			"value" => "profile"
        	)
        );

        array_push(
        	$search_filter_detail['search_filter'],
        	array(
        		"name" => "Collaborations",
    			"key" => "type",
    			"value" => "collaborations"
        	)
        );

        array_push(
        	$search_filter_detail['search_filter'],
        	array(
        		"name" => "Companies",
    			"key" => "type",
    			"value" => "companies"
        	)
        );
        $search_filter_detail['count'] = count($search_filter_detail['search_filter']);
        

        $model[] = [
            "position" => 1,
        	"ui_type" => 1,
        	"ui_style_meta" => (object)[],
        	"title" => "Search Tagtaste", 
        	"subtitle" => null,
        	"description" => null,
        	"images_meta" => null,
      		"type" => "collection",
            "sub_type" => "search_filter",
        	"see_more" => false,
		    "filter_meta" => (object)[],
		    "elements" => $search_filter_detail
        ];
        /* ui type = 1 is end */

        /* ui type = 2 is start */
        $client = config('database.neo4j_uri_client');
		$products_suggestion = FeedController::suggestion_products($client, $profile, $profile_id);
        
        $products_suggestion_detail = array(
            "product" => $products_suggestion["suggestion"],
            "count" => $products_suggestion["meta"]["count"],
            "type" => "product"
        );

		$model[] = [
        	"position" => 2,
            "ui_type" => 2,
        	"ui_style_meta" => (object)[],
        	"title" => "Products you can Review", 
        	"subtitle" => "This is coming from suggestion engine",
        	"description" => null,
        	"images_meta" => null,
        	"type" => "collection",
            "sub_type" => "product",
        	"see_more" => true,
		    "filter_meta" => (object)[],
		    "elements" => $products_suggestion_detail
        ];
        /* ui type = 2 is end */

        /* ui type = 3 is start */
        $collections = ReviewCollection::where("type","collection")
        	->where("category_type","product")
        	->where("is_active",1)
        	->whereNull("deleted_at")
        	->inRandomOrder()
        	->get()
        	->makeHidden(['elements'])
        	->take(10)
        	->toArray();
        
        $handpicked_collection_detail = array(
        	"collection" => array(),
            "count" => 0,
	        "type" => "collection"
        );

        foreach ($collections as $key => $collection) {
        	array_push($handpicked_collection_detail['collection'], $collection);
        	$handpicked_collection_detail['count'] += 1;
        }
        
        $model[] = [
            "position" => 3,
        	"ui_type" => 3,
        	"ui_style_meta" => (object)[],
        	"title" => "Collections for you", 
        	"subtitle" => null,
        	"description" => null,
        	"images_meta" => null,
        	"type" => "collection",
            "sub_type" => "collection",
        	"see_more" => false,
		    "filter_meta" => (object)[],
		    "elements" => $handpicked_collection_detail
        ];
        /* ui type = 3 is end */

        
        /* ui type = 4 is start */
        $client = config('database.neo4j_uri_client');
		$people_suggestion = FeedController::suggestion_of_follower($client, $profile, $profile_id);

        $people_suggestion_detail = array(
            "profile" => $people_suggestion["suggestion"],
            "count" => $people_suggestion["meta"]["count"],
            "type" => "profile"
        );

		$model[] = [
        	"position" => 4,
            "ui_type" => 4,
            "ui_style_meta" => (object)[],
            "title" => "People you may know", 
            "subtitle" => "This is coming from suggestion engine",
            "description" => null,
            "images_meta" => null,
            "type" => "collection",
            "sub_type" => "profile",
            "see_more" => true,
            "filter_meta" => (object)[],
            "elements" => $people_suggestion_detail
        ];
        /* ui type = 4 is end */

        /* ui type = 5 is start */
        $specializations = \DB::table('specializations')->get()->toArray();
        
        $specialization_detail = array(
            "specialization" => array(),
            "count" => 0,
            "type" => "specialization"
        );

        foreach ($specializations as $key => $specialization) {
            array_push($specialization_detail["specialization"], $specialization);
            $specialization_detail["count"] += 1;
        }

        $model[] = [
            "position" => 5,
            "ui_type" => 5,
            "ui_style_meta" => (object)[],
            "title" => "People from the industry", 
            "subtitle" => "Discover people through different lenses from food industry",
            "description" => null,
            "images_meta" => null,
            "type" => "collection",
            "sub_type" => "profile",
            "backend" => "specialization",
            "see_more" => false,
            "filter_meta" => (object)[],
            "elements" => $specialization_detail
        ];
        /* ui type = 5 is end */

        /* ui type = 6 is start */
        $client = config('database.neo4j_uri_client');
        $collaborations_suggestion = FeedController::suggestion_collaboration($client, $profile, $profile_id);
        $collaboration_suggestion_detail = array(
            "collaborate" => $collaborations_suggestion["suggestion"],
            "count" => $collaborations_suggestion["meta"]["count"],
            "type" => "collaborate"
        );

        $model[] = [
            "position" => 6,
            "ui_type" => 6,
            "ui_style_meta" => (object)[],
            "title" => "Collaborations for you", 
            "subtitle" => null,
            "description" => null,
            "images_meta" => null,
            "type" => "collection",
            "sub_type" => "collaborate",
            "see_more" => true,
            "filter_meta" => (object)[],
            "elements" => $collaboration_suggestion_detail
        ];
        /* ui type = 6 is end */

        $this->model = $model;
        return $this->sendResponse();
    }
}