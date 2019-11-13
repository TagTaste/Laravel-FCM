<?php

namespace App\Http\Controllers\Api\V2;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\ReviewInterfaceDesign;

class SearchController extends Controller
{
   	//aliases added for frontend
    private $models = [
        'product' => \App\PublicReviewProduct::class
    ];

    public function exploreForReview(Request $request)
    {
        $loggedInProfileId = $request->user()->profile->id;
        
        $data = [];
        
        $review_interface_design = ReviewInterfaceDesign::whereNull('deleted_at')->where('is_active',1)->get();
        
        if (count($review_interface_design)) {
        	foreach ($review_interface_design as $key => $interface) {
        		$data[$interface->id] = array(
        			"postion" => $interface->postion,
        			"ui_type" => $interface->ui_type,
        			"ui_style" => $interface->ui_style,
        		);
                
        		if (isset($interface->collections)) {
        			$collection = $interface->collections;
        			$data[$interface->id]['collection_id'] = $collection->id;
        			$data[$interface->id]['title'] = $collection->title;
        			$data[$interface->id]['subtitle'] = $collection->subtitle;
        			$data[$interface->id]['description'] = $collection->description;
        			$data[$interface->id]['image'] = $collection->image;
                    $data[$interface->id]['category_type'] = $collection->category_type;
                    $data[$interface->id]['elements'] = array();
        			if (isset($collection->elements) && count($collection->elements)) {
        				$elements = $collection->elements->take(20);;
        				if ("filter" === $collection->type) {
                            foreach ($elements as $key => $element) {
                                if ("filter" === $element->type && "product" === $element->filter_on) {
                                    $data[$interface->id]['elements'] = $this->elementsByProductFilter($element, $loggedInProfileId);
                                }
                            }
                        } else if ("collection" == $collection->type) {
                            foreach ($elements as $key => $element) {
                                if ("product" === $element->type && "product" === $element->data_type) {
                                    $data[$interface->id]['elements'][] = $this->elementsByProductId($element, $loggedInProfileId);
                                } else if ("collection" === $element->type && "collection" === $element->data_type) {
                                    $data[$interface->id]['elements'][] = $this->elementsByCollectionId($element, $loggedInProfileId);
                                } else if ("profile" === $element->type && "profile" === $element->data_type) {
                                    $profile_data = $this->elementsByProfileId($element, $loggedInProfileId);
                                    if (!is_null($profile_data)) {
                                        $data[$interface->id]['elements'][] = $profile_data;
                                    }
                                } else if ("filter" === $element->type && is_null($element->data_type)) {
                                    $data[$interface->id]['elements'][] = $this->elementsByFilterId($element, $loggedInProfileId);
                                }
                            }
                        }
        			} else {
        				unset($data[$interface->id]);
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
    	if (isset($element->filter_model) && !is_null($element->filter_model)) {
    		$filters = json_decode($element->filter, true);
        	$fetched_data = $element->filter_model;
        	if (count($filters)) {
				foreach ($filters as $key => $criteria) {
					$fetched_data = $fetched_data::where($key, $criteria);
				}
			}
			$data_fetched = $fetched_data->inRandomOrder()->take(20)->get();
			foreach ($data_fetched as $key => $data) {
				$response[] = [
					'product' => $data,
					'meta' => $data->getMetaFor($loggedInProfileId),
                    'type' => 'product',
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
            $response = [
                'product' => $data_fetched,
                'meta' => $data_fetched->getMetaFor($loggedInProfileId),
                'type' => 'product',
            ];
        }
        return $response;
    }

    public function elementsByCollectionId($element, $loggedInProfileId)
    {
        $response = array();
        if (isset($element->data_model) && !is_null($element->data_model)) {
            $product_id = (int)$element->data_id;
            $model = $element->data_model;
            $data_fetched = $model::where('id',$product_id)->first()->makeHidden(['elements']);
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
                $response['isFollowing'] = $data_fetched->isFollowing($profile_id, $loggedInProfileId);
                $response['type'] = 'profile';
            }
        }
        return $response;
    }

    public function elementsByFilterId($element, $loggedInProfileId)
    {
        $response = array();
        if (!is_null($element)) {
            $response['id'] = $element->id;
            $response['type'] = $element->type;
            $response['filter_name'] = $element->filter_name;
            $response['filter_on'] = $element->filter_on;
            $response['filter'] = $element->filter;
            $response['title'] = $element->title;
            $response['subtitle'] = $element->subtitle;
            $response['description'] = $element->description;
             $response['image'] = $element->image;

        } else {
            $response = (object)array();
        }
        return $response;
    }
}