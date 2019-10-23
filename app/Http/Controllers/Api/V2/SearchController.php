<?php

namespace App\Http\Controllers\Api\V2;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\ReviewInterfaceDesign;

class SearchController extends Controller
{
    public function exploreForReview(Request $request)
    {
        $loggedInProfileId = $request->user()->profile->id;
        
        $data = [];
        
        $review_interface_design = ReviewInterfaceDesign::whereNull('deleted_at')->where('is_active',1)->get();
        
        dd($review_interface_design->toArray());

        return $this->sendResponse();
    }

}