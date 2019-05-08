<?php

namespace App\Http\Controllers\Api;

use App\Company;
use App\Events\Model\Subscriber\Create;
use App\Events\NewFeedable;
use App\Events\UpdateFeedable;
use App\Polling;
use App\PollingOption;
use App\PollingVote;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PollingController extends Controller
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
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $profileId = $request->user()->profile->id;
        $data = [];
        if($request->has('company_id'))
        {
            $companyId = $request->input('company_id');
            $userId = $request->user()->id;
            $company = Company::find($companyId);
            $userBelongsToCompany = $company->checkCompanyUser($userId);
            if(!$userBelongsToCompany){
                return $this->sendError("User does not belong to this company");
            }
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
            $data[] = ['text'=>$option,'poll_id'=>$poll->id,'created_at'=>$this->now,'updated_at'=>$this->now,'count'=>0];
        }
        PollingOption::insert($data);
        $poll = $poll->refresh();
        //add to feed
        event(new NewFeedable($poll, $request->user()->profile));

        //add model subscriber
        event(new Create($poll,$request->user()->profile));

        $this->model = $poll;
        return $this->sendResponse();
    }

    public function userPollVote(Request $request,$pollId)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $poll = Polling::where('id',$pollId)->where('is_expired',1)->first();
        if($poll == null)
        {
            $this->model = [];
            return $this->sendError('Poll is not available');
        }
        $pollOptionId = $request->input('poll_option_id');
        $pollOptionCheck = PollingOption::where('poll_id',$pollId)->where('id',$pollOptionId)->first();
        if($pollOptionCheck == null)
        {
            $this->model = [];
            return $this->sendError('Please select poll option');
        }

        $checkVote = PollingVote::where('poll_id',$pollId)->where('profile_id',$loggedInProfileId)->first();
        if($checkVote != null)
        {
            PollingOption::where('poll_id',$pollId)->where('id',$checkVote->poll_option_id)
                ->update(['count'=>$pollOptionCheck->count - 1]);
            $pollOptionCheck->update(['count'=>$pollOptionCheck->count + 1]);
            $this->model = PollingVote::where('poll_id',$pollId)->where('profile_id',$loggedInProfileId)
                ->update(['poll_option_id'=>$pollOptionId]);
        }
        else
        {
            $data = ['profile_id'=>$loggedInProfileId,'poll_id'=>$pollId,'poll_option_id'=>$pollOptionId,
                'ip_address'=>$request->ip(),'created_at'=>$this->now,'updated_at'=>$this->now];
            $this->model = PollingVote::insert($data);
            if($this->model)
            {
                PollingOption::where('poll_id',$pollId)->where('id',$pollOptionId)->update(['count'=>$pollOptionCheck->count + 1]);
            }
        }
        return $this->sendResponse();

    }

    public function update(Request $request,$pollId)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $poll = Polling::where('id',$pollId)->first();
        if($poll == null)
        {
            $this->model = [];
            return $this->sendError('Poll is not available');
        }
        if(isset($poll->company_id) && !is_null($poll->company_id))
        {
            $companyId = $poll->company_id;
            $userId = $request->user()->id;
            $company = Company::find($companyId);
            $userBelongsToCompany = $company->checkCompanyUser($userId);
            if(!$userBelongsToCompany){
                $this->model = [];
                return $this->sendError("User does not belong to this company");
            }
        }
        else if($poll->profile_id != $loggedInProfileId)
        {
            $this->model = [];
            return $this->sendError("Poll is not related to login user");
        }
        $checkVote = PollingVote::where('poll_id',$pollId)->exists();
        if($checkVote)
        {
            $this->model = [];
            return $this->sendError("Poll can not be editable");
        }
        $data = $request->only('title');
        $this->model = $this->model->update($data);
        $poll = $poll->refresh();
        $poll->addToCache();
        $this->model = $poll;
        event(new UpdateFeedable($this->model));
        return $this->sendResponse();
    }

    public function show(Request $request,$pollId)
    {
        $this->model = $this->model->where('id',$pollId)->first();
        return $this->sendResponse();
    }

    public function delete(Request $request,$pollId)
    {
        $poll = $this->model->where('id',$pollId)->first();
        $poll->removeFromCache();
        $poll = $poll->options()->delete();
        $this->model = $poll->delete();
        return $this->sendResponse();
    }

    public function updateOptions(Request $request,$pollId,$optionId)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $poll = Polling::where('id',$pollId)->first();
        if($poll == null)
        {
            $this->model = [];
            return $this->sendError('Poll is not available');
        }
        if(isset($poll->company_id) && !is_null($poll->company_id))
        {
            $companyId = $poll->company_id;
            $userId = $request->user()->id;
            $company = Company::find($companyId);
            $userBelongsToCompany = $company->checkCompanyUser($userId);
            if(!$userBelongsToCompany){
                $this->model = [];
                return $this->sendError("User does not belong to this company");
            }
        }
        else if($poll->profile_id != $loggedInProfileId)
        {
            $this->model = [];
            return $this->sendError("Poll is not related to login user");
        }
        $checkVote = PollingVote::where('poll_id',$pollId)->exists();
        if($checkVote)
        {
            $this->model = [];
            return $this->sendError("Poll can not be editable");
        }
        $this->model = $poll->options()->where('id',$optionId)->update(['text'=>$request->input('text')]);
        $poll = $poll->refresh();
        $poll->addToCache();
        $this->model = $poll;
        event(new UpdateFeedable($this->model));
        return $this->sendResponse();
    }

    public function addOption(Request $request,$pollId)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $poll = Polling::where('id',$pollId)->first();
        if($poll == null)
        {
            $this->model = [];
            return $this->sendError('Poll is not available');
        }
        if(isset($poll->company_id) && !is_null($poll->company_id))
        {
            $companyId = $poll->company_id;
            $userId = $request->user()->id;
            $company = Company::find($companyId);
            $userBelongsToCompany = $company->checkCompanyUser($userId);
            if(!$userBelongsToCompany){
                $this->model = [];
                return $this->sendError("User does not belong to this company");
            }
        }
        else if($poll->profile_id != $loggedInProfileId)
        {
            $this->model = [];
            return $this->sendError("Poll is not related to login user");
        }
        $checkVote = PollingVote::where('poll_id',$pollId)->exists();
        if($checkVote)
        {
            $this->model = [];
            return $this->sendError("Poll can not be editable");
        }
        $options = $request->input('options');
        foreach ($options as $option)
        {
            $data[] = ['text'=>$option,'poll_id'=>$poll->id,'created_at'=>$this->now,'updated_at'=>$this->now,'count'=>0];
        }
        PollingOption::insert($data);
        $poll = $poll->refresh();
        $poll = $poll->addToCache();
        $this->model = $poll;
        event(new UpdateFeedable($this->model));
        return $this->sendResponse();
    }


}
