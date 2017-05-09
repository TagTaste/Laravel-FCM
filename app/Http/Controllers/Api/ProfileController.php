<?php

namespace App\Http\Controllers\Api;

use App\Profile;
use App\Subscriber;
use App\User;
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
        $requests = $request->user();
        
        return response()->json($requests);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        $profile = User::whereHas("profile",function($query) use ($id){
            $query->where('user_id',$id);
        })->first();
        
        if($profile === null){
            throw new ModelNotFoundException("Could not find profile.");
        }
//        $profileId = $profile->id;
//        foreach($profile->followerProfiles['profiles'] as &$follower){
//            $follower->isFollowing = Profile::isFollowing($profileId,$follower->id);
//        }
//
        
        return response()->json($profile);
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

        //save the model
        $this->model = $request->user()->profile->update($data);
        
        return $this->sendResponse();
    }
    
    private function saveFileToData($key,$path,&$request,&$data)
    {
        if($request->hasFile($key)){
            $data[$key] = $this->saveFile($path,$request,$key);
        }
    }
    
    private function saveFile($path,&$request,$key)
    {
        $imageName = str_random("32") . ".jpg";
        $response = $request->file($key)->storeAs($path,$imageName);
        if(!$response){
            throw new \Exception("Could not save image " . $imageName . " at " . $path);
        }
        return $imageName;
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
        return response()->file(Profile::getImagePath($id,$profile->image));
    }

    public function heroImage($id)
    {
        $profile = Profile::select('id','hero_image')->findOrFail($id);
        return response()->file(Profile::getHeroImagePath($id,$profile->hero_image));
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
        
        $this->model = $request->user()->profile->subscribeNetworkOf($channelOwner);
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
        
        $this->model = $request->user()->profile->unsubscribeNetworkOf($channelOwner);
        
        if(!$this->model){
            throw new \Exception("You are not following this profile.");
        }
        return $this->sendResponse();
    }
    
    public function followers(Request $request, $id)
    {
        $followers = Profile::getFollowers($id);
        if(!$followers){
            throw new ModelNotFoundException("Followers not found.");
        }
        
        $loggedInProfileId = $request->user()->profile->id;
        
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
    
        foreach($followers as $profile){
            $temp = $profile;
            $temp->isFollowing = false;
            $temp->self = false;
            $result[] = $temp;
        }
        
        if($alreadySubscribed->count() > 0){
            $alreadySubscribed = $alreadySubscribed->keyBy('channel_name');
            foreach($result as $profile){
                $channel = $alreadySubscribed->get('network.' , $profile->id);
    
                if($channel === null){
                    continue;
                }
                
                if($channel === $loggedInProfileId){
                    $profile->self = true;
                    continue;
                }
                $profile->isFollowing = true;
            }
        }
        $this->model = $result;
        return $this->sendResponse();
    }
    
    public function following(Request $request, $id)
    {
        $following = Profile::getFollowing($id);
        if(!$following){
            throw new ModelNotFoundException("Following profiles not found.");
        }
        $loggedInProfileId = $request->user()->profile->id;
    
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
    
        foreach($following as $profile){
            $temp = $profile;
            $temp->isFollowing = false;
            $temp->self = false;
            $result[] = $temp;
        }
    
        if($alreadySubscribed->count() > 0){
            $alreadySubscribed = $alreadySubscribed->keyBy('channel_name');
            foreach($result as $profile){
                $channel = $alreadySubscribed->get('network.' , $profile->id);
    
                if($channel === null){
                    continue;
                }
                
                if($channel === $loggedInProfileId){
                    $profile->self = true;
                    continue;
                }
                
                $profile->isFollowing = true;
            }
        }
        $this->model = $result;
        return $this->sendResponse();
        
        
    }

}
