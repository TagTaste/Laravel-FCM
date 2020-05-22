<?php

namespace App\Http\Controllers\Api\V2;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\ReviewInterfaceDesign;
use App\ReviewCollection;
use App\ReviewCollectionElement;
use App\Collaborate;
use Illuminate\Support\Facades\Redis;
use \Carbon\Carbon;
use App\PublicReviewProduct\Review;
use App\Profile;

class ExploreController extends Controller
{
   	//aliases added for frontend
    private $models = [
        'product' => \App\PublicReviewProduct::class
    ];

    public function exploreForReview(Request $request)
    {
        $this->errors['status'] = 0;
        $loggedInProfileId = $request->user()->profile->id;
        
        $data = [];
        
        $review_interface_design = ReviewInterfaceDesign::whereNull('deleted_at')
            ->where('is_active',1)
            ->orderBy('position')
            ->get();  
        
        if (count($review_interface_design)) {
        	foreach ($review_interface_design as $key => $interface) {
        		$data[$interface->id] = array(
        			"ui_type" => $interface->ui_type,
        			"ui_style_meta" => $interface->ui_style_meta,
        		);
                
        		if (isset($interface->collections)) {
        			$collection = $interface->collections;
        			$data[$interface->id]['collection_id'] = $collection->id;
        			$data[$interface->id]['title'] = $collection->title;
        			$data[$interface->id]['subtitle'] = $collection->subtitle;
        			$data[$interface->id]['description'] = $collection->description;
        			$data[$interface->id]['images_meta'] = $collection->images_meta;
                    $data[$interface->id]['backend'] = $collection->backend;
                    $data[$interface->id]['category_type'] = $collection->category_type;
                    $data[$interface->id]['elements'] = array();
                    $data[$interface->id]['see_more'] = false;
        			if (isset($collection->elements) && count($collection->elements)) {
        				$elements = $collection->elements->take(20);
        				if ("filter" === $collection->type) {
                            foreach ($elements as $key => $element) {
                                if ("filter" === $element->type && "product" === $element->filter_on) {
                                    $data[$interface->id]['filter_meta'] = $element->filter_meta;
                                    $data[$interface->id]['see_more'] = true;
                                    $data[$interface->id]['elements'] = $this->elementsByProductFilter($element, $loggedInProfileId);
                                }
                            }
                        } else if ("collection" == $collection->type) {
                            foreach ($elements as $key => $element) {
                                if ("product" === $element->type && "product" === $element->data_type) {
                                    $data[$interface->id]['elements'][] = $this->elementsByProductId($element, $loggedInProfileId);
                                    $data[$interface->id]['see_more'] = true;
                                } else if ("collection" === $element->type && "collection" === $element->data_type) {
                                    $data[$interface->id]['elements'][] = $this->elementsByCollectionId($element, $loggedInProfileId);
                                } else if ("profile" === $element->type && "profile" === $element->data_type) {
                                    $profile_data = $this->elementsByProfileId($element, $loggedInProfileId);
                                    if (!is_null($profile_data)) {
                                        $data[$interface->id]['elements'][] = $profile_data;
                                        $data[$interface->id]['see_more'] = true;
                                    }
                                } else if ("filter" === $element->type && is_null($element->data_type)) {
                                    $data[$interface->id]['elements'][] = $this->elementsByFilterId($element, $loggedInProfileId, $data[$interface->id]["ui_type"]);
                                }
                            }
                        }
        			} else {
        				if (isset($collection->type) && "campus_connect" === $collection->type && isset($collection->category_type) && "campus_connect" === $collection->category_type) {
                            $data[$interface->id]['blog_url'] = "https://blog.tagtaste.com/tagtaste-introduces-campus-connect-program-for-students-69c6199eeb11";
                            unset($data[$interface->id]['elements']);
                        } else if (isset($collection->type) && "collaborate" === $collection->type && isset($collection->category_type) && "collaborate" === $collection->category_type) {
                            $data[$interface->id]['elements'] = $this->exploreCollaboration($loggedInProfileId, 0, 2);
                            $data[$interface->id]['see_more'] = true;
                        } else if (isset($collection->type) && "top_taster" === $collection->type && isset($collection->category_type) && "profile" === $collection->category_type) {
                            $data[$interface->id]['elements'] = $this->exploreTopTaster($loggedInProfileId, 0, 20);
                        } else {
                            unset($data[$interface->id]);
                        }  
        			}
        		} else {
        			unset($data[$interface->id]);
        		}
        	}
        }

        $this->model = array_values($data);
        return $this->sendResponse();
    }

    public function elementsByProductFilter($element, $loggedInProfileId)
    {
    	$response = array();
        $field_processable = ["is_newly_launched","company_id","brand_id","product_category_id","product_sub_category_id"];
        $field_unprocessable = ["By Company","By Brand","Category","Sub Category","is_newly_launched"];
    	if (isset($element->filter_model) && !is_null($element->filter_model)) {
    		$filters = json_decode($element->filter, true);
        	$fetched_data = $element->filter_model;
        	if (count($filters)) {
				foreach ($filters as $key => $criteria) {
                    if (in_array($criteria['key'], $field_processable)) {
                        $fetched_data = $fetched_data::where($criteria['key'], $criteria['value']);
                    }
				}
			}
			$data_fetched = $fetched_data->inRandomOrder()->take(20)->get();
			foreach ($data_fetched as $key => $data) {
				$response[] = [
					'product' => $data,
					'meta' => $data->getMetaFor($loggedInProfileId),
                    'element_type' => 'product',
                ];
			}
    	}
    	return $response;
    }

    public function elementsByProductId($element, $loggedInProfileId)
    {
        $response = array();
        if (isset($element->data_model) && !is_null($element->data_model)) {
            $product_id = $element->data_id;
            $model = $element->data_model;
            $data_fetched = $model::where('id',$product_id)->first();
            if (!is_null($data_fetched)) {
               $response = [
                    'product' => $data_fetched->toArray(),
                    'meta' => $data_fetched->getMetaFor($loggedInProfileId),
                    'element_type' => 'product',
                ]; 
            }
        }
        return $response;
    }

    public function elementsByCollectionId($element, $loggedInProfileId)
    {
        $response = array();
        if (isset($element->data_model) && !is_null($element->data_model)) {
            $collection_id = (int)$element->data_id;
            $model = $element->data_model;
            $data_fetched = $model::where('id',$collection_id)->first()->makeHidden(['elements']);
            $response = $data_fetched;
        }
        return $response;
    }

    public function elementsByProfileId($element, $loggedInProfileId)
    {
        $response = null;
        if (isset($element->data_model) && !is_null($element->data_model)) {
            $profile_id = (int)$element->data_id;
            $model = $element->data_model;
            $data_fetched = $model::where('id',$profile_id)->first();
            if (!is_null($data_fetched)) {
                $response = $data_fetched->toArray();
                $response['isFollowing'] = Profile::isFollowing($loggedInProfileId, $profile_id);
                $response['element_type'] = 'profile';
            }
        }
        return $response;
    }

    public function elementsByFilterId($element, $loggedInProfileId, $ui_type)
    {
        $response = array();
        if (!is_null($element)) {
            $response['id'] = $element->id;
            $response['category_type'] = $element->type;
            $response['backend'] = $element->type;
            $response['filter_meta'] = $element->filter_meta;
            $response['title'] = $element->title;
            if (is_null($element->title) || "" === $element->title) {
                $response['title'] = $element->filter_name;
            }
            $response['subtitle'] = $element->subtitle;
            $response['description'] = $element->description;
            $response['images_meta'] = $element->images_meta;
            // $response['filter_id'] = $element->filter_id;
            // $response['filter_name'] = $element->filter_name;
            // $response['filter_on'] = $element->filter_on;
            // $response['collection_id'] = $element->collection_id;
        } else {
            $response = (object)array();
        }
        return $response;
    }

    public function getCollection(Request $request, int $collectionId)
    {
        $this->errors['status'] = 0;
        $loggedInProfileId = $request->user()->profile->id;
        $sort_flag = 0;
        $data = [];

        $skip = (int)$request->input('skip', 0);
        $take = (int)$request->input('take', 10);

        $collection = ReviewCollection::where('id',$collectionId)
            ->whereNull('deleted_at')
            ->first();

        if (is_null($collection)) {
            return $this->sendError("Collection not found.");
        }

        $this->model = $collection->toArray();

        $collection_elements = ReviewCollectionElement::where('collection_id',$collectionId)
            ->whereNull('deleted_at')
            ->get();
        
        if (count($collection_elements)) {
             foreach ($collection_elements as $key => $element) {
                if ("product" === $element->type && "product" === $element->data_type) {
                    $product_detail = $this->elementsByProductId($element, $loggedInProfileId);
                    if (!is_null($product_detail) && count($product_detail)) {
                        $data[] = $product_detail;
                    }
                } else if ("collection" === $element->type && "collection" === $element->data_type) {
                    $collection_detail = $this->elementsByCollectionId($element, $loggedInProfileId);
                    if (!is_null($collection_detail) && count($collection_detail)) {
                        $data[] = $collection_detail;
                        $sort_flag = $sort_flag + 1;
                    }
                } else if ("profile" === $element->type && "profile" === $element->data_type) {
                    $profile_data = $this->elementsByProfileId($element, $loggedInProfileId);
                    if (!is_null($profile_data)) {
                        $data[] = $profile_data;
                        $sort_flag = $sort_flag + 1;
                    }
                }
            }
        }

        if (0 == $sort_flag) {
            usort($data, function($a, $b) {return $a['product']['review_count'] < $b['product']['review_count'];});
        }

        $this->model['elements'] = array_slice($data, $skip, $take);
        $this->model['count'] = ReviewCollectionElement::where('collection_id',$collectionId)
            ->whereNull('deleted_at')
            ->count();
        return $this->sendResponse();
    }

    public function getCollectionElements(Request $request, int $collectionId)
    {
        $this->errors['status'] = 0;
        $loggedInProfileId = $request->user()->profile->id;
        $sort_flag = 0;
        $data = [];
        $this->model = [];

        $skip = (int)$request->input('skip', 0);
        $take = (int)$request->input('take', 10);

        $collection_elements = ReviewCollectionElement::where('collection_id',$collectionId)
            ->whereNull('deleted_at')
            ->get();
        
        if (count($collection_elements)) {
             foreach ($collection_elements as $key => $element) {
                if ("product" === $element->type && "product" === $element->data_type) {
                    $product_detail = $this->elementsByProductId($element, $loggedInProfileId);
                    if (!is_null($product_detail) && count($product_detail)) {
                        $data[] = $product_detail;
                    }
                } else if ("collection" === $element->type && "collection" === $element->data_type) {
                    $collection_detail = $this->elementsByCollectionId($element, $loggedInProfileId);
                    if (!is_null($collection_detail) && count($collection_detail)) {
                        $data[] = $collection_detail;
                        $sort_flag = $sort_flag + 1;
                    }
                } else if ("profile" === $element->type && "profile" === $element->data_type) {
                    $profile_data = $this->elementsByProfileId($element, $loggedInProfileId);
                    if (!is_null($profile_data)) {
                        $data[] = $profile_data;
                        $sort_flag = $sort_flag + 1;
                    }
                }
            }
        }
        if (0 == $sort_flag) {
            usort($data, function($a, $b) {return $a['product']['review_count'] < $b['product']['review_count'];});
        }

        $this->model = array_slice($data, $skip, $take);

        return $this->sendResponse();
    }

    public static function exploreCollaboration($profileId, $skip, $limit) 
    {
        $collaborate_data = [];

        $applied_collaborate = \DB::table('collaborate_applicants')
            ->where('profile_id',$profileId)
            ->where('is_invited',0)
            ->whereNull('rejected_at')
            ->pluck('collaborate_id')
            ->toArray();

        $collaborate = Collaborate::where('collaborates.state',Collaborate::$state[0])
            ->whereNotIn('id',$applied_collaborate)
            ->whereNull('deleted_at')
            ->inRandomOrder()
            ->skip($skip)
            ->take($limit)
            ->pluck('id')
            ->toArray();

        if (count($collaborate)) {
            foreach ($collaborate as $key => $id) {
                $cached_data = \App\V2\Detailed\Collaborate::where('id', $id)->first();
                if (!is_null($cached_data)) {
                    $data = $cached_data->toArray(); 
                    $data['element_type'] = 'collaborate';
                    array_push($collaborate_data, $data); 
                }
            }
        }
        return $collaborate_data;
    }

     public static function exploreTopTaster($profileId, $skip, $limit) 
    {
        $profile_data = [];
        $first_day_of_month = date('Y-m-d 00:00:00', strtotime('first day of -3 months'));
        $last_day_of_month = date('Y-m-d 00:00:00', strtotime('last day of last month'));
        
        $reviewers = DB::select(
                    DB::raw(
                        "SELECT result.profile_id, count(result.product_id) as count
                        FROM 
                            (
                                SELECT product_id, profile_id
                                FROM public_product_user_review 
                                WHERE updated_at >= '$first_day_of_month' 
                                    && updated_at < '$last_day_of_month' 
                                    && current_status = 2
                                Group BY profile_id, product_id
                            ) as result 
                        GROUP BY result.profile_id
                        ORDER BY count desc
                        LIMIT $skip, $limit"
                    )
                );

        if (count($reviewers)) {
            foreach ($reviewers as $key => $reviewer) {
                $data_fetched = \App\V2\Profile::where('id',$reviewer->profile_id)->first();
                if (!is_null($data_fetched)) {
                    $response = $data_fetched->toArray();
                    $response['isFollowing'] = Profile::isFollowing($profileId, $reviewer->profile_id);
                    $response['element_type'] = 'profile';
                    $profile_data[] = $response;
                }
            }
        }
        return $profile_data;
    }
}