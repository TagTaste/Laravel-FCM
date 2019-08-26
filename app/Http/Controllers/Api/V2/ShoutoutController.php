<?php

namespace App\Http\Controllers\Api\V2;

use App\Company;
use App\CompanyUser;
use App\Events\Actions\Tag;
use App\Events\Model\Subscriber\Create;
use App\V2\Shoutout;
use App\Traits\CheckTags;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ShoutoutController as BaseController;

class ShoutoutController extends BaseController
{
    use CheckTags;
    /**
     * Variable to model
     *
     * @var shoutout
     */
    protected $model;

    /**
     * Create instance of controller with Model
     *
     * @return void
     */
    public function __construct(Shoutout $model)
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
        $loggedInProfileId = $request->user()->profile->id;
        $shoutout = $this->model->where('id',$id)->whereNull('deleted_at')->first();
        
        if (!$shoutout) {
            return $this->sendError("Shoutout not found.");
        }
        $owner = $shoutout->getOwnerAttribute();
        $meta = $shoutout->getMetaForV2($loggedInProfileId);
        $shoutout = $shoutout->toArray();
        
        foreach ($shoutout as $key => $value) {
            if (is_null($value) || $value == '')
                unset($shoutout[$key]);
        }
        $this->model = [
            'shoutout'=>$shoutout,
            'meta'=>$meta
        ];

        if (isset($shoutout['profile_id'])) {
            $this->model['profile'] = $owner;
        }

        if (isset($shoutout['company_id'])) {
            $this->model['company'] = $owner;
        }

        return $this->sendResponse();
    }
}