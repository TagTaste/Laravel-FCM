<?php

namespace App\Http\Controllers\Api;

use App\Events\Model\Subscriber\Create;
use App\Events\NewFeedable;
use App\Polling;
use App\PollingOption;
use Illuminate\Http\Request;

class PollingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $model;

    /**
     * Create instance of controller with Model
     *
     * @return void
     */
    public function __construct(Polling $model)
    {
        $this->model = $model;
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function createPole(Request $request)
    {
        $profileId = $request->user()->profile->id;
        $data = [];
        if($request->has('company_id'))
        {
            $data['company_id'] = $request->input('company_id');
        }
        else
        {
            $data['profile_id'] = $profileId;
        }
        if(!$request->has('title') || !$request->has('options'))
        {
            return $this->sendError("Please select options");
        }
        $data['title'] = $request->input('title');
        $poll = Polling::create($data);
        $options = $request->input('options');
        $data = [];
        foreach ($options as $option)
        {
            $data[] = ['text'=>$option,'poll_id',$poll->id];
        }
        PollingOption::create($data);
        $poll = $poll->refresh();
        //add to feed
        event(new NewFeedable($poll, $request->user()->profile));

        //add model subscriber
        event(new Create($poll,$request->user()->profile));

        $this->model = $poll;
        return $this->sendResponse();
    }
}
