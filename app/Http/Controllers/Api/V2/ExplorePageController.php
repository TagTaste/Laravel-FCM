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

class ExplorePageController extends Controller
{
	public function explore(Request $request)
    {
        $loggedInProfileId = $request->user()->profile->id;

        $profile = $request->user()->profile;
        $profile_id = $profile->id;
        
        $search_filter = null !== $request->input('search_filter') ? $request->input('search_filter') : null;
        
        $model = [];

        /* ui type = 1 is start */
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
            "elements" => $this->getSearchFilter($search_filter)
        ];
        /* ui type = 1 is end */

        if ($search_filter === "everything" || $search_filter == null) {
            /* ui type = 2 is start */
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
                "elements" => $this->getProductsUserCanReview($profile, $profile_id)
            ];
            /* ui type = 2 is end */
           
            /* ui type = 3 is start */
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
                "elements" => $this->getHandpickedCollection($profile, $profile_id)
            ];
            /* ui type = 3 is end */
            
            /* ui type = 4 is start */
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
                "elements" => $this->getProfileSuggestion($profile, $profile_id)
            ];
            /* ui type = 4 is end */

            /* ui type = 5 is start */
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
                "see_more" => false,
                "filter_meta" => (object)[],
                "elements" => $this->getSpecialization($profile, $profile_id)
            ];
            /* ui type = 5 is end */

            /* ui type = 6 is start */
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
                "elements" => $this->getCollaborationSuggestion($profile, $profile_id)
            ];
            /* ui type = 6 is end */
        }

        if ($search_filter === "product") {
            /* ui type = 2 is start */
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
                "elements" => $this->getProductsUserCanReview($profile, $profile_id)
            ];
            /* ui type = 2 is end */

            /* ui type = 3 is start */
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
                "elements" => $this->getHandpickedCollection($profile, $profile_id)
            ];
            /* ui type = 3 is end */

            /* ui type = 2 is start */
            $model[] = [
                "position" => 4,
                "ui_type" => 2,
                "ui_style_meta" => (object)[],
                "title" => "Recently reviewed products", 
                "subtitle" => "This is coming from suggestion engine",
                "description" => null,
                "images_meta" => null,
                "type" => "collection",
                "sub_type" => "product",
                "see_more" => true,
                "filter_meta" => (object)[],
                "elements" => $this->getRecentReviewedProductsUserCanReview($profile, $profile_id)
            ];
            /* ui type = 2 is end */
        }

        if ($search_filter === "profile") {
            /* ui type = 4 is start */
            $model[] = [
                "position" => 2,
                "ui_type" => 4,
                "ui_style_meta" => (object)[],
                "title" => "From your network", 
                "subtitle" => "This is coming from suggestion engine",
                "description" => null,
                "images_meta" => null,
                "type" => "collection",
                "sub_type" => "profile",
                "see_more" => true,
                "filter_meta" => (object)[],
                "elements" => $this->getProfileSuggestion($profile, $profile_id)
            ];
            /* ui type = 4 is end */

            /* ui type = 24 is start */
            $model[] = [
                "position" => 3,
                "ui_type" => 4,
                "ui_style_meta" => (object)[],
                "title" => "Active & Influentials", 
                "subtitle" => "This is coming from suggestion engine",
                "description" => null,
                "images_meta" => null,
                "type" => "collection",
                "sub_type" => "profile",
                "see_more" => true,
                "filter_meta" => (object)[],
                "elements" => $this->getProfileSuggestion($profile, $profile_id)
            ];
            /* ui type = 4 is end */

            /* ui type = 5 is start */
            $model[] = [
                "position" => 4,
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
                "elements" => $this->getSpecialization($profile, $profile_id, 4)
            ];
            /* ui type = 5 is end */
        }

        if ($search_filter === "collaborate") {
            /* ui type = 7 is start */
            $model[] = [
                "position" => 2,
                "ui_type" => 6,
                "ui_style_meta" => (object)[],
                "title" => "Take part in a Tasting Session", 
                "subtitle" => "Experiance professional Sensorial Analysis in these projects",
                "description" => null,
                "images_meta" => null,
                "type" => "collection",
                "sub_type" => "collaborate",
                "see_more" => true,
                "filter_meta" => (object)[],
                "elements" => $this->getPublicReviewCollaborationSuggestion($profile, $profile_id, 4)
            ];
            /* ui type = 6 is end */

            /* ui type = 7 is start */
            $model[] = [
                "position" => 3,
                "ui_type" => 6,
                "ui_style_meta" => (object)[],
                "title" => "Your next Buisness Opportunity", 
                "subtitle" => "Collaborate with Food Professionals in ways never done before",
                "description" => null,
                "images_meta" => null,
                "type" => "collection",
                "sub_type" => "collaborate",
                "see_more" => true,
                "filter_meta" => (object)[],
                "elements" => $this->getGeneralCollaborationSuggestion($profile, $profile_id, 4)
            ];
            /* ui type = 6 is end */
        }

        if ($search_filter === "company") {
            /* ui type = 7 is start */
            $model[] = [
                "position" => 2,
                "ui_type" => 7,
                "ui_style_meta" => (object)[],
                "title" => "Brands & Companies", 
                "subtitle" => "Upcoming Companies with latest products",
                "description" => null,
                "images_meta" => null,
                "type" => "collection",
                "sub_type" => "company",
                "see_more" => true,
                "filter_meta" => (object)[],
                "elements" => $this->getCompanySuggestion($profile, $profile_id)
            ];
            /* ui type = 7 is end */

            /* ui type = 7 is start */
            $model[] = [
                "position" => 3,
                "ui_type" => 7,
                "ui_style_meta" => (object)[],
                "title" => "In your Network", 
                "subtitle" => "Companies followed by people you follow",
                "description" => null,
                "images_meta" => null,
                "type" => "collection",
                "sub_type" => "company",
                "see_more" => true,
                "filter_meta" => (object)[],
                "elements" => $this->getCompanySuggestion($profile, $profile_id)
            ];
            /* ui type = 7 is end */
        }



        $this->model = $model;
        return $this->sendResponse();
    }

    public function getSearchFilter($filter_type)
    {
        $search_filter_detail = array(
            "search_filter" => array(),
            "count" => 0,
            "type" => "search_filter"
        );

        // everything search filter
        $everything_search_filter = array(
            "name" => "Everything",
            "key" => null,
            "value" =>  null,
            "is_selected" => false
        );

        if ($filter_type === "everything" || $filter_type == null) {
            $everything_search_filter["is_selected"] = true;
        }

        array_push(
            $search_filter_detail['search_filter'],
            $everything_search_filter
        );

        // people search filter
        $people_search_filter = array(
            "name" => "People",
            "key" => "type",
            "value" => "profile",
            "is_selected" => false
        );

        if ($filter_type === "profile") {
            $people_search_filter["is_selected"] = true;
        }

        array_push(
            $search_filter_detail['search_filter'],
            $people_search_filter
        );

        // collaborate search filter
        $collaborate_search_filter = array(
            "name" => "Collaborations",
            "key" => "type",
            "value" => "collaborate",
            "is_selected" => false
        );

        if ($filter_type === "collaborate") {
            $collaborate_search_filter["is_selected"] = true;
        }

        array_push(
            $search_filter_detail['search_filter'],
            $collaborate_search_filter
        );

        // product search filter
        $product_search_filter = array(
            "name" => "Products",
            "key" => "type",
            "value" => "product",
            "is_selected" => false
        );

        if ($filter_type === "product") {
            $product_search_filter["is_selected"] = true;
        }

        array_push(
            $search_filter_detail['search_filter'],
            $product_search_filter
        );

        // company search filter
        $company_search_filter = array(
            "name" => "Companies",
            "key" => "type",
            "value" => "company",
            "is_selected" => false
        );

        if ($filter_type === "company") {
            $company_search_filter["is_selected"] = true;
        }

        array_push(
            $search_filter_detail['search_filter'],
            $company_search_filter
        );

        $search_filter_detail['count'] = count($search_filter_detail['search_filter']);
        return $search_filter_detail;        
    }

    public function getProductsUserCanReview($profile, $profile_id)
    {
        $client = config('database.neo4j_uri_client');
        $products_suggestion = FeedController::suggestion_products($client, $profile, $profile_id);
        
        $products_suggestion_detail = array(
            "product" => $products_suggestion["suggestion"],
            "count" => $products_suggestion["meta"]["count"],
            "type" => "product"
        );
        return $products_suggestion_detail;     
    }

    public function getRecentReviewedProductsUserCanReview($profile, $profile_id)
    {
        $client = config('database.neo4j_uri_client');
        $products_suggestion = FeedController::suggestion_products_recent_reviewed($client, $profile, $profile_id);
        $products_suggestion_detail = array(
            "product" => $products_suggestion["suggestion"],
            "count" => $products_suggestion["meta"]["count"],
            "type" => "product"
        );
        return $products_suggestion_detail;     
    }

    public function getHandpickedCollection($profile, $profile_id)
    {
        $collections = ReviewCollection::where("type","collection")
            ->where("category_type","product")
            ->where("is_active",1)
            ->whereNull("deleted_at")
            ->inRandomOrder()
            ->get()
            ->makeHidden(['elements', 'backend'])
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
        return $handpicked_collection_detail;     
    }

    public function getProfileSuggestion($profile, $profile_id)
    {
        $client = config('database.neo4j_uri_client');
        $profile_suggestion = FeedController::suggestion_of_follower($client, $profile, $profile_id);

        $profile_suggestion_detail = array(
            "profile" => $profile_suggestion["suggestion"],
            "count" => $profile_suggestion["meta"]["count"],
            "type" => "profile"
        );
        return $profile_suggestion_detail;     
    }

    public function getCompanySuggestion($profile, $profile_id)
    {
        $client = config('database.neo4j_uri_client');
        $company_suggestion = FeedController::suggestion_company($client, $profile, $profile_id);

        $company_suggestion_detail = array(
            "profile" => $company_suggestion["suggestion"],
            "count" => $company_suggestion["meta"]["count"],
            "type" => "company"
        );
        return $company_suggestion_detail;     
    }

    public function getSpecialization($profile, $profile_id)
    {
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
        return $specialization_detail;     
    }

    public function getCollaborationSuggestion($profile, $profile_id)
    {
        $client = config('database.neo4j_uri_client');
        $collaborations_suggestion = FeedController::suggestion_collaboration($client, $profile, $profile_id);
        $collaboration_suggestion_detail = array(
            "collaborate" => $collaborations_suggestion["suggestion"],
            "count" => $collaborations_suggestion["meta"]["count"],
            "type" => "collaborate"
        );
        return $collaboration_suggestion_detail;     
    }

    public function getPublicReviewCollaborationSuggestion($profile, $profile_id, $count)
    {
        $client = config('database.neo4j_uri_client');
        $collaborations_suggestion = FeedController::suggestion_public_review_collaboration($client, $profile, $profile_id, $count);
        $collaboration_suggestion_detail = array(
            "collaborate" => $collaborations_suggestion["suggestion"],
            "count" => $collaborations_suggestion["meta"]["count"],
            "type" => "collaborate"
        );
        return $collaboration_suggestion_detail;     
    }

    public function getGeneralCollaborationSuggestion($profile, $profile_id, $count)
    {
        $client = config('database.neo4j_uri_client');
        $collaborations_suggestion = FeedController::suggestion_general_collaboration($client, $profile, $profile_id, $count);
        $collaboration_suggestion_detail = array(
            "collaborate" => $collaborations_suggestion["suggestion"],
            "count" => $collaborations_suggestion["meta"]["count"],
            "type" => "collaborate"
        );
        return $collaboration_suggestion_detail;     
    }

}