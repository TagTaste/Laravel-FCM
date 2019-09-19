<?php

namespace App\Http\Controllers\Api\V2;

use App\Channel\Payload;
use App\Company;
use App\Events\Actions\Like;
use App\Events\Model\Subscriber\Create;
use App\Events\NewFeedable;
use App\Events\UpdateFeedable;
use App\Events\DeleteFeedable;
use App\PeopleLike;
use App\V2\Polling;
use App\PollingLike;
use App\PollingOption;
use App\PollingVote;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Api\PollingController as BaseController;

class PollingController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $model;
    protected $now;

    /**
     * Create instance of controller with Model
     *
     * @return void
     */
    public function __construct(Polling $model)
    {
        $this->now = Carbon::now()->toDateTimeString();
        $this->model = $model;
    }
    

    public function show(Request $request,$pollId)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $poll = $this->model->where('id',$pollId)->whereNull('deleted_at')->first();
        if (!$poll) {
            return $this->sendError("Poll not found.");
        }
        $owner = $poll->getOwnerAttribute();
        $meta = $poll->getMetaForV2($loggedInProfileId);
        $poll = $poll->toArray();
        
        $this->model = [
            'polling'=>$poll,
            'meta'=>$meta
        ];

        if (isset($poll['profile_id'])) {
            $this->model['profile'] = $owner;
        }

        if (isset($poll['company_id'])) {
            $this->model['company'] = $owner;
        }

        return $this->sendResponse();
    }
}
