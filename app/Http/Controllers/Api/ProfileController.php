<?php

namespace App\Http\Controllers\Api;

use App\Events\Actions\Follow;
use App\Profile;
use App\Subscriber;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Jobs\PhoneVerify;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //DO NOT MODIFY THIS RESPONSE
        //DO NOT USE $this->model HERE
        //LIVES DEPEND ON THIS RESPONSE
        $userId = $request->user()->id;
        $response = \App\Profile\User::find($userId)->toArray();
        $response['profile']['isFollowing'] = false;
        $response['profile']['self'] = true;
        $response['companies'] = $this->getCompany($request);
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
    
        //id can either be id or handle
        //we can use both profile/{id} or handle in api call
        $profile = \App\Profile\User::whereHas("profile", function ($query) use ($id) {
            $query->where('id', $id);
        })->first();

        if ($profile === null) {
            return $this->sendError("Could not find profile.");
        }

        $this->model = $profile->toArray();
        if($this->model['profile']['email_private']!=1)
        {
            unset($this->model['email']);
        }
        $loggedInProfileId = $request->user()->profile->id;
        $self = $id == $loggedInProfileId;
        $this->model['profile']['self'] = $self;
        
        $this->model['profile']['isFollowing'] = $self ? false : Profile::isFollowing($loggedInProfileId,$id);
    
        return $this->sendResponse();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->except(["_method","_token",'hero_image','image','resume','remove','remove_image',
            'remove_hero_image']);
        //proper verified.
        if(isset($data['verified'])){
            $data['verified'] = empty($data['verified']) ? 0 : 1;
        }

        //delete heroimage or image
        if($request->has("remove_image") && $request->input('remove_image') == 1)
        {
            $data['profile']['image'] = null;
        }

        if($request->has("remove_hero_image") && $request->input('remove_hero_image') == 1)
        {
            $data['profile']['hero_image'] = null;
        }

        //update user name
        if(!empty($data['name'])){
            $name = array_pull($data, 'name');
            $request->user()->update(['name'=>trim($name)]);
        }
        
        //save profile image
        $path = \App\Profile::getImagePath($id);
        $this->saveFileToData("image",$path,$request,$data);
        
        //save hero image
        $path = \App\Profile::getHeroImagePath($id);
        $this->saveFileToData("hero_image",$path,$request,$data);

        //save profile resume

        if($request->has("remove") && $request->input('remove') == 1){
            $data['profile']['resume'] = null;
        }
        else if($request->hasFile('resume'))
        {
            $path = "profile/$id/resume";
            $status = \Storage::makeDirectory($path,0644,true);
            $ext = \File::extension($request->file('resume')->getClientOriginalName());
            $resumeName = str_random("32") .".". $ext;

            $response = $request->file('resume')->storeAs($path,$resumeName,['visibility'=>'public']);
            
            if(!$response)
            {
                throw new \Exception("Could not save resume " . $resumeName . " at " . $path);
            }
            else
            {
                $data['profile']['resume'] = $response;
            }
        }

        //phone verified for request otp

        if(isset($data['profile']['phone'])&&!empty($data['profile']['phone']))
        {
            dispatch((new PhoneVerify($data['profile']['phone'],$request->user()->profile))->onQueue('phone_verify'));
        }

        //save the model
        if(isset($data['profile']) && !empty($data['profile'])){
            $userId = $request->user()->id;
            try {
                $this->model = \App\Profile::where('user_id',$userId)->first();
                if(isset($data['profile']['handle']) && empty($data['profile']['handle'])){
                    unset($data['profile']['handle']);
                }
                $this->model->update($data['profile']);
                $this->model->refresh();
                //update filters
                \App\Filter\Profile::addModel($this->model);
                
                
            } catch(\Exception $e){
                \Log::error($e->getMessage() . " " . $e->getFile() . " " . $e->getLine());
                return $this->sendError("Could not update.");
            }
        }
        \App\Filter\Profile::addModel($this->model);
    
        return $this->sendResponse();
    }
    
    private function saveFileToData($key,$path,&$request,&$data)
    {
        if($request->hasFile($key)){
            $data['profile'][$key] = $this->saveFile($path,$request,$key);
        }
    }
    
    private function saveFile($path,&$request,$key)
    {
        
        $imageName = str_random("32") . ".jpg";
        $response = $request->file($key)->storeAs($path,$imageName,['visibility'=>'public']);
        if(!$response){
            throw new \Exception("Could not save image " . $imageName . " at " . $path);
        }
        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function image($id)
    {
        $profile = Profile::select('image')->findOrFail($id);
        $file = Profile::getImagePath($id,$profile->image);
        if(file_exists($file)){
            return response()->file($file);
        }
    }

    public function heroImage($id)
    {
        $profile = Profile::select('id','hero_image')->findOrFail($id);
        $file = Profile::getHeroImagePath($id,$profile->hero_image);
        if(file_exists($file)){
            return response()->file($file);
        }
    }

    public function dishImages($id)
    {
        $profile = Profile::select('hero_image')->findOrFail($id);
        return response()->file(storage_path("app/" . $profile->hero_image));
    }

    public function follow(Request $request)
    {
        $channelOwnerProfileId = $request->input('id');
        //$request->user()->profile->follow($id);
        $channelOwner = Profile::find($channelOwnerProfileId);
        if(!$channelOwner){
            throw new ModelNotFoundException();
        }
        
        $this->model = $request->user()->completeProfile->subscribeNetworkOf($channelOwner);
        $profileId = $request->user()->profile->id;
        
        //profiles the logged in user is following
        \Redis::sAdd("following:profile:" . $profileId, $channelOwnerProfileId);
        
        //profiles that are following $channelOwner
        \Redis::sAdd("followers:profile:" . $channelOwnerProfileId, $profileId);
        
        if(!$this->model){
            $this->sendError("You are already following this profile.");
        }
        event(new Follow($channelOwner, $request->user()->profile));

        return $this->sendResponse();
    }
    
    public function unfollow(Request $request)
    {
        $channelOwnerProfileId = $request->input('id');
        //$request->user()->profile->follow($id);
        $channelOwner = Profile::find($channelOwnerProfileId);
        if(!$channelOwner){
            throw new ModelNotFoundException();
        }
        
        $this->model = $request->user()->completeProfile->unsubscribeNetworkOf($channelOwner);
        
        if(!$this->model){
            $this->sendError("You are not following this profile.");
        }
    
        $profileId = $request->user()->profile->id;
        //profiles the logged in user is following
        \Redis::sRem("following:profile:" . $profileId, $channelOwnerProfileId);
    
        //profiles that are following $channelOwner
        \Redis::sRem("followers:profile:" . $channelOwnerProfileId, $profileId);
    
        return $this->sendResponse();
    }
    
    private function getFollowers($id, $loggedInProfileId)
    {
        $followers = Profile::getFollowers($id);
        if(!$followers){
            throw new ModelNotFoundException("Followers not found.");
        }
        
        $followerProfileIds = $followers->pluck('id')->toArray();
        //build network names
        $networks = [];
        foreach($followerProfileIds as $profileId){
            if($profileId != $loggedInProfileId){
                $networks[] = 'network.' . $profileId;
            }
        }
        $alreadySubscribed = Subscriber::where('profile_id',$loggedInProfileId)->whereIn('channel_name',$networks)
            ->whereNull('deleted_at')->get();
        $result = [];
        foreach($followers as &$profile){
            $temp = $profile->toArray();
            $temp['isFollowing'] = false;
            $temp['self'] = false;
            $result[] = $temp;
        }

        if($alreadySubscribed->count() > 0){
            $alreadySubscribed = $alreadySubscribed->keyBy('channel_name');
            foreach($result as &$profile){
                if($profile['id'] === $loggedInProfileId){
                    $profile['self'] = true;
                    continue;
                }
            
                $channel = $alreadySubscribed->get('network.' . $profile['id']);
                if($channel === null){
                    continue;
                }
            
                $profile['isFollowing'] = true;
            }
        }
    
        return $result;
    }
    
    public function followers(Request $request, $id)
    {
        $loggedInProfileId = $request->user()->profile->id ;

        $this->model = [];
        $profileIds = \Redis::SMEMBERS("followers:profile:".$id);
        $count = count($profileIds);
        if($count > 0 && \Redis::sIsMember("followers:profile:".$id,$id)){
                  $count = $count - 1;
        }
        $this->model['count'] = $count;
        $data = [];

        $page = $request->has('page') ? $request->input('page') : 1;
        $profileIds = array_slice($profileIds ,($page - 1)*20 ,20 );

        foreach ($profileIds as $key => $value)
        {
            if($id == $value)
            {
                unset($profileIds[$key]);
                continue;
            }
            $profileIds[$key] = "profile:small:".$value ;
        }

        if(count($profileIds)> 0)
        {
            $data = \Redis::mget($profileIds);

        }
        foreach($data as &$profile){
            if(is_null($profile)){
                continue;
            }
            $profile = json_decode($profile);
            $profile->isFollowing = \Redis::sIsMember("followers:profile:".$profile->id,$loggedInProfileId) === 1;
            $profile->self = false;
        }
        $this->model['profile'] = $data;
        return $this->sendResponse();
    }
    
    private function getFollowing($id, $loggedInProfileId, $page)
    {
        $profileIds = \Redis::sMembers("following:profile:$id");
    
        $count = count($profileIds);
        
        if($count > 0 && \Redis::sIsMember("following:profile:".$id,$id)){
              $count = $count - 1;
        }

        $profileIds = array_slice($profileIds ,($page - 1)*20 ,20 );
        \Log::info($profileIds);
        foreach ($profileIds as $key => $value)
        {
            if(str_contains($value,"company")){
                $profileIds[$key] = "company:small:" . last(explode(".",$value));
                continue;
            }
            if($id == $value)
            {
                unset($profileIds[$key]);
                continue;
            }
            $profileIds[$key] = "profile:small:".$value ;

        }
        $following = [];
        if(count($profileIds)> 0)
        {
            $following = \Redis::mget($profileIds);
        }
        foreach($following as &$profile){
            if(is_null($profile)){
                continue;
            }
            $profile = json_decode($profile);
            $key = "following:profile:$loggedInProfileId";
            $profile->type = isset($profile->profileId) ? "company" : "profile";
            $value = isset($profile->profileId) ? "company." : null;
            $value .= $profile->id;
            $profile->isFollowing =  \Redis::sIsMember($key,$value) === 1;
        }
        return ['count'=> $count,'profile'=>$following];
    }
    
    public function following(Request $request, $id)
    {
        $page = $request->has('page') ? $request->input('page') : 1;
        $this->model = $this->getFollowing($id, $request->user()->profile->id,$page);
        return $this->sendResponse();
    }
    
    public function all(Request $request)
    {
        
        $loggedInProfileId = $request->user()->profile->id;
        $filters = $request->input('filters');
        $models = \App\Recipe\Profile::whereNull('deleted_at')->where('id','!=',$loggedInProfileId)->orderBy('created_at','asc');
        $this->model = ['count' => $models->count()];
        $this->model['data'] = [];
        //paginate
        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        
        $models = $models->skip($skip)->take($take);
        
        if(empty($filters)){
            $profiles = $models->get();
    
            if($profiles->count()){
                foreach ($profiles as $profile){
                    $temp = $profile->toArray();
                    $temp['isFollowing'] =  Profile::isFollowing($loggedInProfileId,$profile->id);
                    $this->model['data'][] = $temp;
                }
            }
            
            return $this->sendResponse();
        }
        
        $profiles = \App\Filter\Profile::getModels($filters,$skip,$take);
        
        $this->model['count'] =  count($profiles);
    
        $loggedInProfileId = $request->user()->profile->id;
        foreach ($profiles as &$profile){
            $profile['isFollowing'] =  Profile::isFollowing($loggedInProfileId,$profile['id']);;
            $this->model['data'][] = $profile;
        }
        return $this->sendResponse();
    }

    public function filters()
    {
        $this->model = \App\Filter::getFilters("profile");
        return $this->sendResponse();
    }
    
    public function recentUploads(Request $request)
    {
        $userId = $request->user()->id;
        
        $recentModels = ['photos'=>"\App\Photo"];
        $keyPrefix = "recent:user:$userId:";
        
        $this->model = [];
        foreach($recentModels as $model => $class){
            $modelIds = \Redis::lRange($keyPrefix . $model,0,9);
            $this->model[$model] = $class::whereIn("id",$modelIds)->get();
        }
        return $this->sendResponse();
    }

    public function getCompany($request)
    {
        
        $companyIds = \DB::table('companies')->whereNull('deleted_at')->select('id')
            ->where('user_id',$request->user()->id)->get()->pluck('id');
        $adminCompanyIds = \DB::table('company_users')->select('company_id')
            ->where('user_id',$request->user()->id)
            ->whereNotIn('company_id',$companyIds)->get()->pluck('company_id');
        $companyIds = $companyIds->merge($adminCompanyIds)->toArray();

        if(count($companyIds) === 0){
            return [];
        }
        foreach($companyIds as &$companyId)
        {
            $companyId = "company:small:" . $companyId;
        }
        $data = \Redis::mget($companyIds);
        foreach($data as &$company){
            $company = json_decode($company);
        }
        return $data;
    }

    public function mutualFollowers(Request $request,$id)
    {
        $this->model = [];
        $loginProfileId = $request->user()->profile->id;
        $profileIds = \Redis::SINTER("followers:profile:".$id,"followers:profile:".$loginProfileId);
        $data = [];
        $this->model['count'] = count($profileIds);
        $page = $request->has('page') ? $request->input('page') : 1;
        $profileIds = array_slice($profileIds ,($page - 1)*20 ,20);
        foreach ($profileIds as &$profileId)
        {
            $profileId = "profile:small:".$profileId ;
        }
        if(count($profileIds))
        {
            $data = \Redis::mget($profileIds);
        }
        foreach($data as &$profile){
            $profile = json_decode($profile);
        }
        $this->model['profile'] = $data;
        return $this->sendResponse();
    }

    public function tagging(Request $request)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $profileIds = \Redis::SMEMBERS("followers:profile:".$loggedInProfileId);
        $query = $request->input('q');
        $this->model = User::select('profiles.id','users.name')->join('profiles','profiles.user_id','=','users.id')->whereIn('profiles.id',$profileIds)->where('name','like',"%$query%")->get();
        return $this->sendResponse();

    }

    public function allFollowers(Request $request)
    {
        $loggedInProfileId = $request->user()->profile->id ;

        $this->model = [];
        $profileIds = \Redis::SMEMBERS("followers:profile:".$loggedInProfileId);
        $this->model['count'] = count($profileIds) - \Redis::sIsMember("followers:profile:".$loggedInProfileId,$loggedInProfileId);
        $data = [];
        foreach ($profileIds as $key => $value)
        {
            if($loggedInProfileId == $value)
            {
                unset($profileIds[$key]);
                continue;
            }
            $profileIds[$key] = "profile:small:".$value ;
        }

        if(count($profileIds)> 0)
        {
            $data = \Redis::mget($profileIds);

        }
        foreach($data as &$profile){
            if(is_null($profile)){
                continue;
            }
            $profile = json_decode($profile);
            $profile->isFollowing = \Redis::sIsMember("followers:profile:".$profile->id,$loggedInProfileId) === 1;
        }
        $this->model['profile'] = $data;
        return $this->sendResponse();
    }

    public function oldtagging(Request $request)
    {
        $loggedInProfileId = $request->user()->profile->id ;

        $this->model = [];
        $profileIds = \Redis::SMEMBERS("followers:profile:".$loggedInProfileId);
        //$this->model['count'] = count($profileIds);
        $data = [];
        /*
        $page = $request->has('page') ? $request->input('page') : 1;
        $profileIds = array_slice($profileIds ,($page - 1)*20 ,20 );
        */

        foreach ($profileIds as $key => $value)
        {
            if($loggedInProfileId == $value)
            {
                unset($profileIds[$key]);
                continue;
            }
            $profileIds[$key] = "profile:small:".$value ;
        }

        if(count($profileIds)> 0)
        {
            $data = \Redis::mget($profileIds);

        }
        foreach($data as &$profile){
            if(is_null($profile)){
                continue;
            }
            $profile = json_decode($profile);
            $profile->isFollowing = \Redis::sIsMember("followers:profile:".$profile->id,$loggedInProfileId) === 1;
            $profile->self = false;
        }
        $this->model = $data;
        return $this->sendResponse();
    }

    public function requestOtp(Request $request)
    {
        $loggedInProfileId = $request->user()->profile->id;
        \Log::info($request->input('otp'));
        $this->model = Profile::where('id',$loggedInProfileId)->where('otp',$request->input('otp'))->update(['verified_phone'=>1]);
        return $this->sendResponse();
    }

}
