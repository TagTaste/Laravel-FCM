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
        			"postion" => $interface->id,
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
        			if (isset($collection->elements) && count($collection->elements)) {
        				$elements = $collection->elements;
        				$data[$interface->id]['elements'] = array();
        				foreach ($elements as $key => $element) {
							if ("filter" == $element->type && "product" == $element->filter_on) {
								$data[$interface->id]['elements'] = $this->productFilter($element, $loggedInProfileId);
								$data[$interface->id]['category'] = $element->filter_on;
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

        $this->model = $data;
        return $this->sendResponse();
    }

    public function productFilter($element, $loggedInProfileId)
    {
    	$response = array();
    	if (isset($element->actual_model) && !is_null($element->actual_model)) {
    		$filters = json_decode($element->filter, true);
        	$fetched_data = $element->actual_model;
        	if (count($filters)) {
				foreach ($filters as $key => $criteria) {
					$fetched_data = $fetched_data::where($key, $criteria);
				}
			}
			$data_fetched = $fetched_data->inRandomOrder()->limit(20)->get();
			foreach ($data_fetched as $key => $data) {
				$response[] = [
					'product' => $data,
					'meta' => $data->getMetaFor($loggedInProfileId)];
			}
    	}
    	return $response;
    }

}