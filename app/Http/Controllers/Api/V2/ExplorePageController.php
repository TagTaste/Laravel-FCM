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
use App\ElasticHelper;
use App\Traits\HashtagFactory;

class ExplorePageController extends Controller
{
    use HashtagFactory;

    public function exploreTest(Request $request)
    {
        $search_value = null !== $request->input('q') ? $request->input('q') : null;
        $profile = $request->user()->profile;
        $profile_id = $profile->id;
        $this->model = array(
            "profile" => $this->getSearchProfileElastic($profile, $profile_id, $search_value, 20),
            "product" => $this->getSearchProductElastic($profile, $profile_id, $search_value, 20),
            "collaboration" => $this->getSearchCollaborationElastic($profile, $profile_id, $search_value, 20),
            "company" => $this->getSearchCompanyElastic($profile, $profile_id, $search_value, 20)
        );
        return $this->sendResponse();
    }

    public function explore(Request $request)
    {
        $this->errors['status'] = 0;
        $loggedInProfileId = $request->user()->profile->id;

        $profile = $request->user()->profile;
        $profile_id = $profile->id;
        
        $search_filter = null !== $request->input('search_filter') ? $request->input('search_filter') : null;

        $search_value = null !== $request->input('q') ? $request->input('q') : null;
        
        $model = [];

        /* ui type = 1 is start */
        $model[] = [
            "position" => 1,
            "ui_type" => 1,
            "ui_style_meta" => (object)[],
            "title" => "Search people, collaborations, etc...", 
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

        if (is_null($search_value)) {
            if ($search_filter === "everything" || $search_filter == null) {
                $model[] = [
                    "position" => 2,
                    "ui_type" => 8,
                    "ui_style_meta" => (object)[],
                    "title" => "Trending Hashtags", 
                    "subtitle" => null,
                    "description" => null,
                    "images_meta" => null,
                    "type" => "hashtag",
                    "sub_type" => "trending",
                    "see_more" => false,
                    "filter_meta" => (object)[],
                    "elements" => $this->getTrendingHashtags()
                ];
                /* ui type = 2 is start */
                $model[] = [
                    "position" => 3,
                    "ui_type" => 2,
                    "ui_style_meta" => (object)[],
                    "title" => "Products for Review", 
                    "subtitle" => null,
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
                    "position" => 4,
                    "ui_type" => 3,
                    "ui_style_meta" => (object)[],
                    "title" => "Product Collections", 
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
                    "position" => 5,
                    "ui_type" => 4,
                    "ui_style_meta" => (object)[],
                    "title" => "Suggested People", 
                    "subtitle" => "Based on your background & interests",
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
                    "position" => 6,
                    "ui_type" => 5,
                    "ui_style_meta" => (object)[],
                    "title" => "Explore by Specialization", 
                    "subtitle" => null,
                    "description" => null,
                    "images_meta" => null,
                    "type" => "collection",
                    "sub_type" => "specialization",
                    "see_more" => false,
                    "filter_meta" => (object)[],
                    "elements" => $this->getSpecialization($profile, $profile_id)
                ];
                /* ui type = 5 is end */

                /* ui type = 6 is start */
                $model[] = [
                    "position" => 7,
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
                    "title" => "Products for Review", 
                    "subtitle" => null,
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
                    "title" => "Product Collections", 
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
                    "title" => "Recently Reviewed Products", 
                    "subtitle" => null,
                    "description" => null,
                    "images_meta" => null,
                    "type" => "collection",
                    "sub_type" => "product",
                    "see_more" => false,
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
                    "title" => "Suggested People", 
                    "subtitle" => "Based on your network",
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
                    "title" => "Active & Influential", 
                    "subtitle" => null,
                    "description" => null,
                    "images_meta" => null,
                    "type" => "collection",
                    "sub_type" => "profile",
                    "see_more" => false,
                    "filter_meta" => (object)[],
                    "elements" => $this->getActiveAndInfluentialProfileSuggestion($profile, $profile_id)
                ];
                /* ui type = 4 is end */

                /* ui type = 5 is start */
                $model[] = [
                    "position" => 4,
                    "ui_type" => 5,
                    "ui_style_meta" => (object)[],
                    "title" => "Explore by Specialization", 
                    "subtitle" => null,
                    "description" => null,
                    "images_meta" => null,
                    "type" => "collection",
                    "sub_type" => "specialization",
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
                    "title" => "Be a part of a Tasting Session", 
                    "subtitle" => null,
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
                    "title" => "Interesting Opportunities for you", 
                    "subtitle" => null,
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
                    "subtitle" => null,
                    "description" => null,
                    "images_meta" => null,
                    "type" => "collection",
                    "sub_type" => "company",
                    "see_more" => true,
                    "filter_meta" => (object)[],
                    "elements" => $this->getUpcomingCompanySuggestion($profile, $profile_id)
                ];
                /* ui type = 7 is end */

                /* ui type = 7 is start */
                $model[] = [
                    "position" => 3,
                    "ui_type" => 7,
                    "ui_style_meta" => (object)[],
                    "title" => "Suggested Companies", 
                    "subtitle" => "Based on your network",
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
        } else {
            if ($search_filter === "everything" || $search_filter == null) {
                $profile_elastic_data = $this->getSearchProfileElastic($profile, $profile_id, $search_value, 20);

                $model[] = [
                    "position" => 2,
                    "ui_type" => 8,
                    "ui_style_meta" => (object)[],
                    "title" => "Hashtag Suggestions", 
                    "subtitle" => null,
                    "description" => null,
                    "images_meta" => null,
                    "type" => "hashtag",
                    "sub_type" => "suggestions",
                    "see_more" => false,
                    "filter_meta" => (object)[],
                    "elements" => $this->getHashtagSuggestions($search_value)
                ];
                /* ui type = 4 is start */
                $model[] = [
                    "position" => 3,
                    "ui_type" => 4,
                    "ui_style_meta" => (object)[],
                    "title" => "Top ".str_plural("Result", $profile_elastic_data['top_result']['count'])." in People", 
                    "subtitle" => null,
                    "description" => null,
                    "images_meta" => null,
                    "type" => "collection",
                    "sub_type" => "profile",
                    "see_more" => false,
                    "filter_meta" => (object)[],
                    "elements" => $profile_elastic_data['top_result']
                ];

                $model[] = [
                    "position" => 4,
                    "ui_type" => 4,
                    "ui_style_meta" => (object)[],
                    "title" => '"'.$search_value.'"'.' in People', 
                    "subtitle" => null,
                    "description" => null,
                    "images_meta" => null,
                    "type" => "collection",
                    "sub_type" => "profile",
                    "see_more" => true,
                    "filter_meta" => (object)[],
                    "elements" => $profile_elastic_data['match']
                ];
                /* ui type = 4 is end */
               
                $product_elastic_data = $this->getSearchProductElastic($profile, $profile_id, $search_value, 20);
                // /* ui type = 2 is start */
                $model[] = [
                    "position" => 5,
                    "ui_type" => 2,
                    "ui_style_meta" => (object)[],
                    "title" => "Top ".str_plural("Result", $product_elastic_data['top_result']['count'])." in Products",
                    "subtitle" => null,
                    "description" => null,
                    "images_meta" => null,
                    "type" => "collection",
                    "sub_type" => "product",
                    "see_more" => false,
                    "filter_meta" => (object)[],
                    "elements" => $product_elastic_data['top_result']
                ];

                $model[] = [
                    "position" => 6,
                    "ui_type" => 2,
                    "ui_style_meta" => (object)[],
                    "title" => '"'.$search_value.'"'.' in Products', 
                    "subtitle" => null,
                    "description" => null,
                    "images_meta" => null,
                    "type" => "collection",
                    "sub_type" => "product",
                    "see_more" => true,
                    "filter_meta" => (object)[],
                    "elements" => $product_elastic_data['match']
                ];
                // /* ui type = 2 is end */

                $collaborate_elastic_data = $this->getSearchCollaborationElastic($profile, $profile_id, $search_value, 20);
                // /* ui type = 6 is start */
                $model[] = [
                    "position" => 7,
                    "ui_type" => 6,
                    "ui_style_meta" => (object)[],
                    "title" => "Top ".str_plural("Result", $collaborate_elastic_data['top_result']['count'])." in Collaborations", 
                    "subtitle" => null,
                    "description" => null,
                    "images_meta" => null,
                    "type" => "collection",
                    "sub_type" => "collaborate",
                    "see_more" => false,
                    "filter_meta" => (object)[],
                    "elements" => $collaborate_elastic_data['top_result']
                ];

                 $model[] = [
                    "position" => 8,
                    "ui_type" => 6,
                    "ui_style_meta" => (object)[],
                    "title" => '"'.$search_value.'"'.' in Collaborations', 
                    "subtitle" => null,
                    "description" => null,
                    "images_meta" => null,
                    "type" => "collection",
                    "sub_type" => "collaborate",
                    "see_more" => true,
                    "filter_meta" => (object)[],
                    "elements" => $collaborate_elastic_data['match']
                ];
                // /* ui type = 6 is end */

                $company_elastic_data = $this->getSearchCompanyElastic($profile, $profile_id, $search_value, 20);
                /* ui type = 7 is start */
                $model[] = [
                    "position" => 9,
                    "ui_type" => 7,
                    "ui_style_meta" => (object)[],
                    "title" => "Top ".str_plural("Result", $company_elastic_data['top_result']['count'])." in Companies", 
                    "subtitle" => null,
                    "description" => null,
                    "images_meta" => null,
                    "type" => "collection",
                    "sub_type" => "company",
                    "see_more" => false,
                    "filter_meta" => (object)[],
                    "elements" => $company_elastic_data['top_result']
                ];
                $model[] = [
                    "position" => 10,
                    "ui_type" => 7,
                    "ui_style_meta" => (object)[],
                    "title" => '"'.$search_value.'"'.' in Companies', 
                    "subtitle" => null,
                    "description" => null,
                    "images_meta" => null,
                    "type" => "collection",
                    "sub_type" => "company",
                    "see_more" => true,
                    "filter_meta" => (object)[],
                    "elements" => $company_elastic_data['match']
                ];
                /* ui type = 7 is end */
            }

            if ($search_filter === "product") {
                $product_elastic_data = $this->getSearchProductElastic($profile, $profile_id, $search_value, 20);
                // /* ui type = 2 is start */
                $model[] = [
                    "position" => 2,
                    "ui_type" => 2,
                    "ui_style_meta" => (object)[],
                    "title" => "Top ".str_plural("Result", $product_elastic_data['top_result']['count'])." in Products", 
                    "subtitle" => null,
                    "description" => null,
                    "images_meta" => null,
                    "type" => "collection",
                    "sub_type" => "product",
                    "see_more" => false,
                    "filter_meta" => (object)[],
                    "elements" => $product_elastic_data['top_result']
                ];

                $model[] = [
                    "position" => 3,
                    "ui_type" => 2,
                    "ui_style_meta" => (object)[],
                    "title" => '"'.$search_value.'"'.' in Products', 
                    "subtitle" => null,
                    "description" => null,
                    "images_meta" => null,
                    "type" => "collection",
                    "sub_type" => "product",
                    "see_more" => true,
                    "filter_meta" => (object)[],
                    "elements" => $product_elastic_data['match']
                ];
                // /* ui type = 2 is end */
            }
            if ($search_filter === "profile") {
                $profile_elastic_data = $this->getSearchProfileElastic($profile, $profile_id, $search_value, 20);

                /* ui type = 4 is start */
                $model[] = [
                    "position" => 2,
                    "ui_type" => 4,
                    "ui_style_meta" => (object)[],
                    "title" => "Top ".str_plural("Result", $profile_elastic_data['top_result']['count'])." in People", 
                    "subtitle" => null,
                    "description" => null,
                    "images_meta" => null,
                    "type" => "collection",
                    "sub_type" => "profile",
                    "see_more" => false,
                    "filter_meta" => (object)[],
                    "elements" => $profile_elastic_data['top_result']
                ];

                $model[] = [
                    "position" => 3,
                    "ui_type" => 4,
                    "ui_style_meta" => (object)[],
                    "title" => '"'.$search_value.'"'.' in People', 
                    "subtitle" => null,
                    "description" => null,
                    "images_meta" => null,
                    "type" => "collection",
                    "sub_type" => "profile",
                    "see_more" => true,
                    "filter_meta" => (object)[],
                    "elements" => $profile_elastic_data['match']
                ];
                /* ui type = 4 is end */
            }
            if ($search_filter === "collaborate") {
                $collaborate_elastic_data = $this->getSearchCollaborationElastic($profile, $profile_id, $search_value, 20);
                // /* ui type = 6 is start */
                $model[] = [
                    "position" => 2,
                    "ui_type" => 6,
                    "ui_style_meta" => (object)[],
                    "title" => "Top ".str_plural("Result", $collaborate_elastic_data['top_result']['count'])." in Collaborations", 
                    "subtitle" => null,
                    "description" => null,
                    "images_meta" => null,
                    "type" => "collection",
                    "sub_type" => "collaborate",
                    "see_more" => false,
                    "filter_meta" => (object)[],
                    "elements" => $collaborate_elastic_data['top_result']
                ];

                 $model[] = [
                    "position" => 3,
                    "ui_type" => 6,
                    "ui_style_meta" => (object)[],
                    "title" => '"'.$search_value.'"'.' in Collaborations', 
                    "subtitle" => null,
                    "description" => null,
                    "images_meta" => null,
                    "type" => "collection",
                    "sub_type" => "collaborate",
                    "see_more" => true,
                    "filter_meta" => (object)[],
                    "elements" => $collaborate_elastic_data['match']
                ];
                // /* ui type = 6 is end */
            }
            if ($search_filter === "company") {
                $company_elastic_data = $this->getSearchCompanyElastic($profile, $profile_id, $search_value, 20);
                /* ui type = 7 is start */
                $model[] = [
                    "position" => 2,
                    "ui_type" => 7,
                    "ui_style_meta" => (object)[],
                    "title" => "Top ".str_plural("Result", $company_elastic_data['top_result']['count'])." in Companies", 
                    "subtitle" => null,
                    "description" => null,
                    "images_meta" => null,
                    "type" => "collection",
                    "sub_type" => "company",
                    "see_more" => false,
                    "filter_meta" => (object)[],
                    "elements" => $company_elastic_data['top_result']
                ];
                $model[] = [
                    "position" => 3,
                    "ui_type" => 7,
                    "ui_style_meta" => (object)[],
                    "title" => '"'.$search_value.'"'.' in Companies', 
                    "subtitle" => null,
                    "description" => null,
                    "images_meta" => null,
                    "type" => "collection",
                    "sub_type" => "company",
                    "see_more" => true,
                    "filter_meta" => (object)[],
                    "elements" => $company_elastic_data['match']
                ];
                /* ui type = 7 is end */
            }
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
        $products_suggestion = FeedController::suggestionProducts($client, $profile, $profile_id);
        
        $products_suggestion_detail = array(
            "product" => $products_suggestion["suggestion"],
            "count" => $products_suggestion["meta"]["count"],
            "type" => "product"
        );
        return $products_suggestion_detail;     
    }

    public function getTrendingHashtags()
    {
        $hashtags = $this->trendingHashtags();
        return array(
            "hashtag"=>$hashtags,
            "count"=>count($hashtags),
            "type"=>"hashtag"
        );
    }

    public function getHashtagSuggestions($key)
    {
        $hashtags = $this->hashtagSuggestions($key);
        return array(
            "hashtag"=>$hashtags,
            "count"=>count($hashtags),
            "type"=>"hashtag"
        );
    }

    public function getRecentReviewedProductsUserCanReview($profile, $profile_id)
    {
        $client = config('database.neo4j_uri_client');
        $products_suggestion = FeedController::suggestionProductsRecentReviewed($client, $profile, $profile_id);
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
        $profile_suggestion = FeedController::suggestionOfFollower($client, $profile, $profile_id);
        
        foreach ($profile_suggestion["suggestion"] as $key => $value) {
            $profile_suggestion["suggestion"][$key]["isFollowing"] = false;
        }

        $profile_suggestion_detail = array(
            "profile" => $profile_suggestion["suggestion"],
            "count" => $profile_suggestion["meta"]["count"],
            "type" => "profile"
        );
        return $profile_suggestion_detail;     
    }

    public function getActiveAndInfluentialProfileSuggestion($profile, $profile_id)
    {
        $profile_suggestion = FeedController::suggestionOfActiveInfluentialProfile($profile, $profile_id);
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
        $company_suggestion = FeedController::suggestionCompany($client, $profile, $profile_id);

        foreach ($company_suggestion["suggestion"] as $key => $value) {
            $company_suggestion["suggestion"][$key]["isFollowing"] = false;
        }

        $company_suggestion_detail = array(
            "company" => $company_suggestion["suggestion"],
            "count" => $company_suggestion["meta"]["count"],
            "type" => "company"
        );
        return $company_suggestion_detail;     
    }

    public function getUpcomingCompanySuggestion($profile, $profile_id)
    {
        $company_suggestion = FeedController::suggestionUpcomingCompany($profile, $profile_id);

        $company_suggestion_detail = array(
            "company" => $company_suggestion["suggestion"],
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
        $collaborations_suggestion = FeedController::suggestionCollaborationDetailed($client, $profile, $profile_id, 3);
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
        $collaborations_suggestion = FeedController::suggestionPublicReviewCollaboration($client, $profile, $profile_id, $count);
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
        $collaborations_suggestion = FeedController::suggestionGeneralCollaboration($client, $profile, $profile_id, $count);
        $collaboration_suggestion_detail = array(
            "collaborate" => $collaborations_suggestion["suggestion"],
            "count" => $collaborations_suggestion["meta"]["count"],
            "type" => "collaborate"
        );
        return $collaboration_suggestion_detail;     
    }

    public function getSearchProfileElastic($profile, $profile_id, $query, $count)
    {
        $elastic_profile = array(
            "top_result" => array(
                "profile" => array(),
                "count" => 0,
                "type" => "profile"
            ),
            "match" => array(
                "profile" => array(),
                "count" => 0,
                "type" => "profile"
            )
        );
        $elastic_profile_details = ElasticHelper::suggestedSearch($query,"profile",0,1);
        if (isset($elastic_profile_details['hits']) && isset($elastic_profile_details['hits']['total']) && $elastic_profile_details['hits']['total'] > 0) {
            foreach ($elastic_profile_details['hits']['hits'] as $key => $hit) {
                if ($hit["_type"] == "profile") {
                    if ($count == $elastic_profile['top_result']["count"] && $count == $elastic_profile['match']["count"]) {
                        break;
                    } else {
                        $profile = \App\V2\Profile::where("id", (int)$hit["_id"])
                            ->whereNull('deleted_at')
                            ->get()
                            ->first();
                        if (!is_null($profile)) {
                            $profile = $profile->toArray();
                            $profile["isFollowing"] = \App\Profile::isFollowing((int)$profile_id, (int)$hit["_id"]);
                            if ($hit["_score"] > 9) {
                                if ($count == $elastic_profile['top_result']["count"]) {
                                    continue;
                                } else {
                                    $elastic_profile['top_result']["count"]++;
                                    array_push($elastic_profile['top_result']["profile"], $profile);
                                }
                            } else {
                                if ($count == $elastic_profile['match']["count"]) {
                                    continue;
                                } else {
                                    $elastic_profile['match']["count"]++;
                                    array_push($elastic_profile['match']["profile"], $profile);
                                }
                            }
                        }   
                    }
                }
            }
        }
        return $elastic_profile;
    }

    public function getSearchProductElastic($profile, $profile_id, $query, $count)
    {
        $elastic_product = array(
            "top_result" => array(
                "product" => array(),
                "count" => 0,
                "type" => "product"
            ),
            "match" => array(
                "product" => array(),
                "count" => 0,
                "type" => "product"
            )
        );
        $elastic_product_details = ElasticHelper::suggestedSearch($query,"product",0,1);
        if (isset($elastic_product_details['hits']) && isset($elastic_product_details['hits']['total']) && $elastic_product_details['hits']['total'] > 0) {
            foreach ($elastic_product_details['hits']['hits'] as $key => $hit) {
                if ($hit["_type"] == "product") {
                    if ($count == $elastic_product['top_result']["count"] && $count == $elastic_product['match']["count"]) {
                        break;
                    } else {
                        $public_review_product = PublicReviewProduct::where('id',$hit["_id"])
                            ->where('is_active',1)
                            ->whereNull('deleted_at')
                            ->get()
                            ->first();
                        if (!is_null($public_review_product)) {
                            $data = array();
                            $data['product'] = $public_review_product->toArray();
                            $data['meta'] = $public_review_product->getMetaFor((int)$profile_id);
                            if ($hit["_score"] > 9) {
                                if ($count == $elastic_product['top_result']["count"]) {
                                    continue;
                                } else {
                                    $elastic_product['top_result']["count"]++;
                                    array_push($elastic_product['top_result']["product"], $data);
                                }
                            } else {
                                if ($count == $elastic_product['match']["count"]) {
                                    continue;
                                } else {
                                    $elastic_product['match']["count"]++;
                                    array_push($elastic_product['match']["product"], $data);
                                }
                            }
                        }
                    }
                }
            }
        }
        return $elastic_product;
    }

    public function getSearchCollaborationElastic($profile, $profile_id, $query, $count)
    {
        $elastic_collaborate = array(
            "top_result" => array(
                "collaborate" => array(),
                "count" => 0,
                "type" => "collaborate"
            ),
            "match" => array(
                "collaborate" => array(),
                "count" => 0,
                "type" => "collaborate"
            )
        );
        $elastic_collaborate_details = ElasticHelper::suggestedSearch($query,"collaborate",0,1);
        if (isset($elastic_collaborate_details['hits']) && isset($elastic_collaborate_details['hits']['total']) && $elastic_collaborate_details['hits']['total'] > 0) {
            foreach ($elastic_collaborate_details['hits']['hits'] as $key => $hit) {
                if ($hit["_type"] == "collaborate") {
                    if ($count == $elastic_collaborate['top_result']["count"] && $count == $elastic_collaborate['match']["count"]) {
                        break;
                    } else {
                        $collaborate = \App\V2\Detailed\Collaborate::where('id', (int)$hit["_id"])
                            ->where('collaborates.state',Collaborate::$state[0])
                            ->whereNull('deleted_at')
                            ->first();

                        if (!is_null($collaborate)) {
                            $data = $collaborate->toArray();
                            if ($hit["_score"] > 9) {
                                if ($count == $elastic_collaborate['top_result']["count"]) {
                                    continue;
                                } else {
                                    $elastic_collaborate['top_result']["count"]++;
                                    array_push($elastic_collaborate['top_result']["collaborate"], $data);
                                }
                            } else {
                                if ($count == $elastic_collaborate['match']["count"]) {
                                    continue;
                                } else {
                                    $elastic_collaborate['match']["count"]++;
                                    array_push($elastic_collaborate['match']["collaborate"], $data);
                                }
                            }
                        }
                    }
                }
            }
        }
        return $elastic_collaborate;
    }

    public function getSearchCompanyElastic($profile, $profile_id, $query, $count)
    {
        $elastic_company = array(
            "top_result" => array(
                "company" => array(),
                "count" => 0,
                "type" => "company"
            ),
            "match" => array(
                "company" => array(),
                "count" => 0,
                "type" => "company"
            )
        );
        $elastic_company_details = ElasticHelper::suggestedSearch($query,"company",0,1);
        if (isset($elastic_company_details['hits']) && isset($elastic_company_details['hits']['total']) && $elastic_company_details['hits']['total'] > 0) {
            foreach ($elastic_company_details['hits']['hits'] as $key => $hit) {
                if ($hit["_type"] == "company") {
                    if ($count == $elastic_company['top_result']["count"] && $count == $elastic_company['match']["count"]) {
                        break;
                    } else {
                        $company = \App\V2\Company::where("id", (int)$hit["_id"])
                            ->whereNull('deleted_at')
                            ->get()
                            ->first();
                        if (!is_null($company)) {
                            $company = $company->toArray();
                            $company["company_id"] = (int)$hit["_id"];
                            $company["isFollowing"] = \App\Company::checkFollowing((int)$profile_id, (int)$hit["_id"]);
                            
                            if ($hit["_score"] > 9) {
                                if ($count == $elastic_company['top_result']["count"]) {
                                    continue;
                                } else {
                                    $elastic_company['top_result']["count"]++;
                                    array_push($elastic_company['top_result']["company"], $company);
                                }
                            } else {
                                if ($count == $elastic_company['match']["count"]) {
                                    continue;
                                } else {
                                    $elastic_company['match']["count"]++;
                                    array_push($elastic_company['match']["company"], $company);
                                }
                            }
                        }
                    }
                }
            }
        }
        return $elastic_company;
    }
}