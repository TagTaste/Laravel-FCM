<?php

namespace App\Http\Controllers\Api;

use App\Profile;
use App\Subscriber;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

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
    \Log::info($profile);
        if ($profile === null) {
            return $this->sendError("Could not find profile.");
        }
    
        $this->model = $profile->toArray();
    
        $loggedInProfileId = $request->user()->profile->id;
        $self = $id == $loggedInProfileId;
        $this->model['profile']['self'] = $self;
        
        $this->model['profile']['isFollowing'] = $self ? false : Profile::isFollowing($id, $loggedInProfileId);
    
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
        $data = $request->except(["_method","_token"]);
        //proper verified.
        if(isset($data['verified'])){
            $data['verified'] = empty($data['verified']) ? 0 : 1;
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

        if($request->has("remove")&&$data['remove'] == 1){
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
                $data['profile']['resume'] = $resumeName;
            }
        }
        //save the model
        if(isset($data['profile']) && !empty($data['profile'])){
            $userId = $request->user()->id;
            try {
                $this->model = \App\Profile::where('user_id',$userId)->first();
                $this->model->update($data['profile']);
                $this->model->refresh();
                new \App\Cached\Filter\Profile($this->model);
            } catch(\Exception $e){
                \Log::error($e->getMessage() . " " . $e->getFile() . " " . $e->getLine());
                return $this->sendError("Could not update.");
            }
        }
        
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
        if(!$this->model){
            throw new \Exception("You are already following this profile.");
        }
        
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
            throw new \Exception("You are not following this profile.");
        }
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
        $this->model = $this->getFollowers($id,$request->user()->profile->id);
        return $this->sendResponse();
    }
    
    private function getFollowing($id, $loggedInProfileId)
    {
        $following = Profile::getFollowing($id);
        if(!$following){
            throw new ModelNotFoundException("Following profiles not found.");
        }
        $followingProfileIds = $following->pluck('id')->toArray();
        //build network names
        $networks = [];
        foreach($followingProfileIds as $profileId){
            if($profileId != $loggedInProfileId){
                $networks[] = 'network.' . $profileId;
            }
        }
        $alreadySubscribed = Subscriber::where('profile_id',$loggedInProfileId)->whereIn('channel_name',$networks)
            ->whereNull('deleted_at')
            ->get();
        $result = [];
    
        foreach($following as &$profile){
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
    public function following(Request $request, $id)
    {
        $this->model = $this->getFollowing($id, $request->user()->profile->id);
        return $this->sendResponse();
    }
    
    public function all(Request $request)
    {
        $filters = $request->input('filters');
        $models = \App\Recipe\Profile::orderBy('created_at','asc');
        $this->model = ['count' => $models->count()];
        $this->model['data'] = [];
        //paginate
        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        
        $models = $models->skip($skip)->take($take);
        
        if(empty($filters)){
            $profiles = $models->get();
    
            $loggedInProfileId = $request->user()->profile->id;
            foreach ($profiles as $profile){
                $temp = $profile->toArray();
                $temp['isFollowing'] =  Profile::isFollowing($profile->id, $loggedInProfileId);;
                $this->model['data'][] = $temp;
            }
            
            return $this->sendResponse();
        }
        
        
        $properties = [];
        foreach($filters as $name => $values){
            if(is_string($values)){
                $properties[] = $values;
                continue;
            }
    
            foreach($values as $value){
                $properties[] = $value;
            }
        }
        $profileIds = \App\Cached\Filter\Profile::getModelIds($properties);
        $this->model['count'] = count($profileIds);
        $profiles = $models->whereIn('id',$profileIds)->get();
    
        $loggedInProfileId = $request->user()->profile->id;
        foreach ($profiles as $profile){
            $temp = $profile->toArray();
            $temp['isFollowing'] =  Profile::isFollowing($profile->id, $loggedInProfileId);;
            $this->model['data'][] = $temp;
        }
        
        return $this->sendResponse();
    }

    public function filters()
    {
        $this->model = \App\Cached\Filter\Profile::getFilters();

        foreach($this->model as &$filter){
            foreach($filter as &$value){
                $value = ['value'=>$value];
            }
        }
        return $this->sendResponse();
    }

}
