<?php

namespace App\Http\Controllers\Api\V2;

use App\V2\Detailed\Collaborate;
use App\CompanyUser;
use App\Events\Actions\Like;
use App\PeopleLike;
use App\Profile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Api\CollaborateController as BaseController;

class CollaborateController extends BaseController
{
   /**
     * Variable to model
     *
     * @var collaborate
     */
    protected $model;

    /**
     * Create instance of controller with Model
     *
     * @return void
     */
    public function __construct(Collaborate $model)
    {
        $this->model = $model;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Request $request, $id)
    {
        $collaboration = $this->model->where('id',$id)->where('state','!=',Collaborate::$state[1])->first();
        if ($collaboration === null) {
            return $this->sendError("Invalid Collaboration Project.");
        }

        $profileId = $request->user()->profile->id;
        if ($collaboration->state == 'Active' || $collaboration->state == 'Close' || $collaboration->state == 'Expired') {
            $meta = $collaboration->getMetaForV2($profileId);
            $collaboration_detail = $collaboration->toArray();
            $this->model = ['collaboration'=>$collaboration_detail,'meta'=>$meta];
            return $this->sendResponse();
        }

        if ($collaboration->company_id != null) {
            $checkUser = CompanyUser::where('company_id',$collaboration->company_id)->where('profile_id',$profileId)->exists();
            if(!$checkUser){
                return $this->sendError("Invalid Collaboration Project.");
            }
        } else if($collaboration->profile_id != $profileId) {
            return $this->sendError("Invalid Collaboration Project.");
        }


        $meta = $collaboration->getMetaForV2($profileId);
        $this->model = [
            'collaboration'=>$collaboration, 
            'meta'=>$meta
        ];
        return $this->sendResponse();
        
    }
}