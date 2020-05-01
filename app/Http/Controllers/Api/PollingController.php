<?php

namespace App\Http\Controllers\Api;

use App\Channel\Payload;
use App\Company;
use App\Events\Actions\Like;
use App\Events\Model\Subscriber\Create;
use App\Events\NewFeedable;
use App\Events\UpdateFeedable;
use App\Events\DeleteFeedable;
use App\PeopleLike;
use App\Polling;
use App\PollingLike;
use App\PollingOption;
use App\PollingVote;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class PollingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $model;
    protected $now;
    protected $type = 3;
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
        $options = $request->input('options');
        if ($request->has('company_id')) {
            $companyId = $request->input('company_id');
            $userId = $request->user()->id;
            $company = Company::find($companyId);
            $userBelongsToCompany = $company->checkCompanyUser($userId);
            if(!$userBelongsToCompany){
                return $this->sendError("User does not belong to this company");
            }
            $data['company_id'] = $request->input('company_id');
        }
            $data['profile_id'] = $profileId;


        // if (!$request->has('title') ) {
        //     return $this->sendError("Please enter poll title");
        // }
        $image = $request->question_image != null ? $request->question_image : null;
        $optionImages = $request->option_images != null ? $request->option_images : null;
        $this->type = $optionImages != null ? 2 : ($image != null ? 1 : 3);
        if (!$request->has('options') || count($options) < 2 || count($options) > 4 || (isset($optionImages) && (count($options) != count($optionImages)))) {
            return $this->sendError("Please enter valid options");
        }
        $data['title'] = $request->input('title');
        $data['image_meta'] = $image;
        $data['type'] = $this->type;
        $poll = Polling::create($data);
        $data = [];
        $i = 0 ;
        foreach ($options as $option) {
            $opImg = isset($optionImages[$i]) ? $optionImages[$i] : null;
            $i++;
            if (strlen($option)!=0) {
                $data[] = [
                    'text'=>$option,
                    'poll_id'=>$poll->id,
                    'created_at'=>$this->now,
                    'updated_at'=>$this->now,
                    'count'=>0,
                    'image_meta'=> $opImg
                ];
            } else {
                return $this->sendError("Please enter valid options");
            }
        }

        PollingOption::insert($data);
        $poll = Polling::find($poll->id);
        $poll->addToCache();

        $this->model = [
            'polling'=>$poll,
            'meta'=>$poll->getMetaFor($profileId)
        ];


        //add to feed
        if ($request->has('company_id')) {
            event(new NewFeedable($poll, $company));
        } else {
            event(new NewFeedable($poll, $request->user()->profile));
        }

        //add model subscriber
        event(new Create($poll,$request->user()->profile));
        return $this->sendResponse();
    }

    public function userPollVote(Request $request,$pollId)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $poll = Polling::where('id',$pollId)->where('is_expired',0)->whereNull('deleted_at')->first();

        if ($poll == null || $poll->profile->id == $loggedInProfileId) {
            $this->model = [];
            return $this->sendError('Poll is not available');
        }

        $pollOptionId = $request->input('poll_option_id');
        $pollOptionCheck = PollingOption::where('poll_id',$pollId)->where('id',$pollOptionId)->first();

        if($pollOptionCheck == null) {
            $this->model = [];
            return $this->sendError('Please select poll option');
        }

        $checkVote = PollingVote::where('poll_id',$pollId)->where('profile_id',$loggedInProfileId)->exists();
        if($checkVote)
        {
            $this->model = [];
            return $this->sendError('You have already voted.');
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
        $poll = Polling::find($pollId);
        $poll->addToCache();
        $this->model = ['polling'=>$poll,'meta'=>$poll->getMetaFor($loggedInProfileId)];
        return $this->sendResponse();

    }

    public function update(Request $request,$pollId)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $poll = Polling::where('id',$pollId)->whereNull('deleted_at')->first();
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
        } else if($poll->profile_id != $loggedInProfileId) {
            $this->model = [];
            return $this->sendError("Poll is not related to login user");
        }

        $checkVote = PollingVote::where('poll_id',$pollId)->exists();

        if ($checkVote) {
            $this->model = [];
            return $this->sendError("Poll can not be editable");
        }

        $data = $request->input(['title']) != null ? $request->input(['title']) : null;
        $options = $request->input(['options']);

        if (!is_array($options))
            $options = [$options];
        if (count($options)>0) {
            $count = PollingOption::where('poll_id',$pollId)->count();
            foreach ($options as $value) {

                if (isset($value['id'])) {
                   $pollOptions = PollingOption::where('poll_id',$pollId)->where('id',$value['id']);
                    if ($pollOptions->exists()) {
                        $imageMeta = !isset($value['image_meta']) || $value['image_meta'] == null ? null : $value['image_meta'];
                        $pollOptions->update(['text'=>$value['text'],'image_meta'=>$imageMeta]);
                    }
                } else if($count<4){
                    $imageMeta = isset($value['image_meta']) ? $value['image_meta'] : null;
                    PollingOption::insert(['text'=>$value['text'],'poll_id'=>$pollId,'image_meta'=>$imageMeta]);
                    $count++;
                }
            }
        }
        $imageQuestion = $request->image_meta != null ? $request->image_meta : null;
        $this->model = $poll->update(['title'=>$data,'image_meta'=>$imageQuestion]);
        $poll = Polling::find($pollId);
        $poll->addToCache();
        $this->model = $poll;
        event(new UpdateFeedable($this->model));
        $this->model = ['polling'=>$poll,'meta'=>$poll->getMetaFor($loggedInProfileId)];

        return $this->sendResponse();
    }

    public function show(Request $request,$pollId)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $this->model = $this->model->where('id',$pollId)->whereNull('deleted_at')->first();
        if ($this->model)
            $this->model = [
                'polling'=>$this->model,
                'meta'=>$this->model->getMetaFor($loggedInProfileId)
            ];

        return $this->sendResponse();
    }

    public function destroy(Request $request,$pollId)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $poll = $this->model->where('id',$pollId)->whereNull('deleted_at')->first();
        if ($poll == null) {
            $this->model = [];
            return $this->sendError('Poll is not available');
        }
        if (isset($poll->company_id) && !is_null($poll->company_id)) {
            $companyId = $poll->company_id;
            $userId = $request->user()->id;
            $company = Company::find($companyId);
            $userBelongsToCompany = $company->checkCompanyUser($userId);

            if (!$userBelongsToCompany) {
                $this->model = [];
                return $this->sendError("User does not belong to this company");
            }
        } else if ($poll->profile_id != $loggedInProfileId) {
            $this->model = [];
            return $this->sendError("Poll is not related to login user");
        }

        event(new DeleteFeedable($poll));
        $poll->removeFromCache();
        $poll->options()->delete();
        $this->model = $poll->delete();
        return $this->sendResponse();
    }

    public function updateOptions(Request $request,$pollId,$optionId)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $poll = Polling::where('id',$pollId)->whereNull('deleted_at')->first();
        if ($poll == null) {
            $this->model = [];
            return $this->sendError('Poll is not available');
        }
        if (isset($poll->company_id) && !is_null($poll->company_id)) {
            $companyId = $poll->company_id;
            $userId = $request->user()->id;
            $company = Company::find($companyId);
            $userBelongsToCompany = $company->checkCompanyUser($userId);

            if (!$userBelongsToCompany) {
                $this->model = [];
                return $this->sendError("User does not belong to this company");
            }
        } else if ($poll->profile_id != $loggedInProfileId) {
            $this->model = [];
            return $this->sendError("Poll is not related to login user");
        }
        $checkVote = PollingVote::where('poll_id',$pollId)->exists();

        if ($checkVote) {
            $this->model = [];
            return $this->sendError("Poll can not be editable");
        }
        $this->model = $poll->options()->where('id',$optionId)->update(['text'=>$request->input('text')]);
        $poll = Polling::find($pollId);
        $poll->addToCache();
        $this->model = $poll;
        event(new UpdateFeedable($this->model));
        $this->model = ['polling'=>$poll,'meta'=>$poll->getMetaFor($loggedInProfileId)];
        return $this->sendResponse();
    }

    public function addOption(Request $request,$pollId)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $poll = Polling::where('id',$pollId)->whereNull('deleted_at')->first();
        if ($poll == null) {
            $this->model = [];
            return $this->sendError('Poll is not available');
        }
        if (isset($poll->company_id) && !is_null($poll->company_id)) {
            $companyId = $poll->company_id;
            $userId = $request->user()->id;
            $company = Company::find($companyId);
            $userBelongsToCompany = $company->checkCompanyUser($userId);
            if (!$userBelongsToCompany) {
                $this->model = [];
                return $this->sendError("User does not belong to this company");
            }
        } else if ($poll->profile_id != $loggedInProfileId) {
            $this->model = [];
            return $this->sendError("Poll is not related to login user");
        }
        $checkVote = PollingVote::where('poll_id',$pollId)->exists();

        if ($checkVote) {
            $this->model = [];
            return $this->sendError("Poll can not be editable");
        }

        $options = $request->input('options');

        if (!$request->has('options') || count($options) < 2 || count($options) > 4) {
            return $this->sendError("Please enter valid options");
        }

        foreach ($options as $option) {
            $data[] = ['text'=>$option,'poll_id'=>$poll->id,'created_at'=>$this->now,'updated_at'=>$this->now,'count'=>0];
        }
        PollingOption::insert($data);
        $poll = Polling::find($pollId);
        $poll->addToCache();
        $this->model = $poll;
        event(new UpdateFeedable($this->model));
        $this->model = [
            'polling'=>$poll,
            'meta'=>$poll->getMetaFor($loggedInProfileId)
        ];
        return $this->sendResponse();
    }

    public function deleteOptions(Request $request,$pollId)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $poll = Polling::where('id',$pollId)->whereNull('deleted_at')->first();
        $count = $poll->options()->count();
        $options = $request->get('optionId');

        if (!is_array($options))
            $options = [$options];

        if ($poll == null || $count - count($options) < 2) {
            $this->model = [];
            return $this->sendError('Poll is not available');
        }

        if (isset($poll->company_id) && !is_null($poll->company_id)) {
            $companyId = $poll->company_id;
            $userId = $request->user()->id;
            $company = Company::find($companyId);
            $userBelongsToCompany = $company->checkCompanyUser($userId);
            if (!$userBelongsToCompany) {
                $this->model = [];
                return $this->sendError("User does not belong to this company");
            }
        } else if($poll->profile_id != $loggedInProfileId) {
            $this->model = [];
            return $this->sendError("Poll is not related to login user");
        }
        $checkVote = PollingVote::where('poll_id',$pollId)->exists();
        if ($checkVote) {
            $this->model = [];
            return $this->sendError("Poll can not be editable");
        }
        $this->model = $poll->options()->where('poll_id', $pollId)->whereIn('id',$options)->delete();
        $poll = Polling::find($pollId);
        $poll->addToCache();
        $this->model = $poll;
        event(new UpdateFeedable($this->model));
        $this->model = [
            'polling'=>$poll,
            'meta'=>$poll->getMetaFor($loggedInProfileId)
        ];
        return $this->sendResponse();
    }

    public function like(Request $request, $pollId)
    {
        $profileId = $request->user()->profile->id;
        $key = "meta:polling:likes:" . $pollId;
        $pollLike = Redis::sIsMember($key,$profileId);
        $this->model = [];

        if ($pollLike) {
            PollingLike::where('profile_id', $profileId)->where('poll_id', $pollId)->delete();
            Redis::sRem($key,$profileId);
            $this->model['liked'] = false;
        } else {
            PollingLike::insert(['profile_id' => $profileId, 'poll_id' => $pollId]);
            Redis::sAdd($key,$profileId);
            $this->model['liked'] = true;
            $recipe = Polling::find($pollId);
            event(new Like($recipe, $request->user()->profile));
        }
        $this->model['likeCount'] = Redis::sCard($key);

        $peopleLike = new PeopleLike();
        $this->model['peopleLiked'] = $peopleLike->peopleLike($pollId, "polling",request()->user()->profile->id);

        return $this->sendResponse();
    }

    public function renew(Request $request,$pollId)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $poll = Polling::where('id',$pollId)->where('is_expired',1)->first();
        //return $poll;
        if ($poll == null) {
            $this->model = [];
            return $this->sendError('Poll is not available');
        }

        if(isset($poll->company_id) && !is_null($poll->company_id)) {
            $companyId = $poll->company_id;
            $userId = $request->user()->id;
            $company = Company::find($companyId);
            $userBelongsToCompany = $company->checkCompanyUser($userId);

            if (!$userBelongsToCompany) {
                $this->model = [];
                return $this->sendError("User does not belong to this company");
            }

        } else if ($poll->profile_id != $loggedInProfileId) {
            $this->model = [];
            return $this->sendError("Poll is not related to login user");
        }

        $this->model = $poll->update(['is_expired'=>0,'expired_time'=>null]);
        $poll->restore();
        \DB::table('poll_options')->where('poll_id',$pollId)->update(['deleted_at'=>null]);
        $poll = Polling::find($pollId);
        $poll->addToCache();
        $this->model = $poll;
        //add to feed
        if ($request->has('company_id')) {
            //event(new NewFeedable($poll, $company));
        }
        //add model subscriber
        event(new Create($poll,$request->user()->profile));
        $this->model = true;
        return $this->sendResponse();
    }

}
