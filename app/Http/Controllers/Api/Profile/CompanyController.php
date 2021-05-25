<?php

namespace App\Http\Controllers\Api\Profile;

use App\Company;
use App\Profile;
use App\CompanyRating;
use App\CompanyUser;
use App\Http\Controllers\Api\Controller;
use App\Subscriber;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware("api.CheckCompanyAdmin")->only(['update','destroy']);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $profileId)
    {
        $userId = $request->user()->id;
        $companies = Company::where('user_id',$userId)->get();
        
        $profileId = $request->user()->profile->id;
        $this->model = [];
        foreach($companies as $company){
            //firing multiple queries for now.
            $temp = $company->toArray();
            $temp['isFollowing'] = $company->isFollowing($profileId);
            $this->model[] = $temp;
        }
        return $this->sendResponse();
    }
    
    
    /**
     * @param Request $request
     * @param $profileId Profile id
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $profileId)
    {
        $inputs = $request->except(['_method','_token']);
        if(empty($inputs)){
            throw new \Exception("Empty request received.");
        }

        $inputs['user_id'] = $request->user()->id;

        try {
            $company = Company::create($inputs);
        }
        catch (\Illuminate\Database\QueryException $e)
        {
            \Log::debug($e->getMessage());
            return $this->sendError("Could not create company.");
        }

        if($request->hasFile('logo')){
            $path = \App\Company::getLogoPath($profileId, $company->id);
            $this->saveFileToData("logo",$path,$request,$inputs,"logo_meta");
        }

        if($request->hasFile('hero_image')){
            $path = \App\Company::getHeroImagePath($profileId, $company->id);
            $this->saveFileToData("hero_image",$path,$request,$inputs,"hero_image_meta");

        }
        
        if($company->isDirty()){
            $company->update();
        }
    
        \App\Filter\Company::addModel($company);
        
        $this->model = $company;
        Redis::sAdd("following:profile:" . $request->user()->profile->id, "company.".$this->model->id);
    
        // add companies that are following $channel_owner
        Redis::sAdd("followers:company:".$this->model->id, $request->user()->profile->id);

        $subscriber = new Subscriber();
        $subscriber->followCompanySuggestion((int)$request->user()->profile->id, (int)$this->model->id);
        
        return $this->sendResponse();
    }

    private function saveFileToData($key,$path,&$request,&$data,$extraKey = null)
    {
        if($request->hasFile($key) && !is_null($extraKey)){

            $response = $this->saveFile($path,$request,$key);
            $data[$extraKey] = json_encode($response,true);
            $data[$key] = $response['original_photo'];
        }
    }

    private function saveFile($path,&$request,$key)
    {
        $imageName = str_random("32") . ".jpg";
        $response['original_photo'] = \Storage::url($request->file($key)->storeAs($path."/original",$imageName,['visibility'=>'public']));
        //create a tiny image
        $path = $path."/tiny/" . str_random(20) . ".jpg";
        $thumbnail = \Image::make($request->file($key))->resize(50, null,function ($constraint) {
            $constraint->aspectRatio();
        })->blur(1)->stream('jpg',70);
        \Storage::disk('s3')->put($path, (string) $thumbnail,['visibility'=>'public']);
        $response['tiny_photo'] = \Storage::url($path);
        $meta = getimagesize($request->input($key));
        $response['meta']['width'] = $meta[0];
        $response['meta']['height'] = $meta[1];
        $response['meta']['mime'] = $meta['mime'];
        $response['meta']['size'] = null;
        $response['meta']['tiny_photo'] = $response['tiny_photo'];
        if(!$response){
            throw new \Exception("Could not save image " . $imageName . " at " . $path);
        }
        return $response;
    }
    
    /**
     * @param Request $request
     * @param $profileId
     * @param $id Company Id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function show(Request $request, $profileId, $id)
    {
        
        $company = Company::whereHas('user.profile',function($query) use ($profileId){
            $query->where('id',$profileId);
        })->where('id',$id)->first();

        if(!$company){
            return $this->sendError("Company not found.");
        }
        $profileId = $request->user()->profile->id;
        $this->model = $company->toArray();
        $this->model['isFollowing'] = $company->isFollowing($profileId);
        $this->model['userRating'] = CompanyRating::where('company_id',$id)->where('profile_id',$profileId)->first();
        $this->model['isOwner'] = $company->user_id === $request->user()->id;
    
        return $this->sendResponse();
    }
    
    /**
     * @param Request $request
     * @param $profileId
     * @param $id Company Id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $profileId, $id)
    {
        $inputs = $request->except(['_method','_token','remove_logo','remove_hero_image']);

        if($request->hasFile('logo')){
            $path = \App\Company::getLogoPath($profileId, $id) ;
            $this->saveFileToData("logo",$path,$request,$inputs,"logo_meta");
        }

        if($request->hasFile('hero_image')){
            $path = \App\Company::getHeroImagePath($profileId, $id);
            $this->saveFileToData("hero_image",$path,$request,$inputs,"hero_image_meta");
        }

        //delete heroimage or image
        if($request->has("remove_logo") && $request->input('remove_logo') == 1)
        {
            $inputs['logo'] = null;
            $inputs['logo_meta'] = null;
        }

        if($request->has("remove_hero_image") && $request->input('remove_hero_image') == 1)
        {
            $inputs['hero_image'] = null;
            $inputs['hero_image_meta'] = null;
        }

        $status = \App\Company::where('id',$id)->update($inputs);
        if(!$status){
            return $this->sendError("Could not update company");
        }
        
        $company = \App\Company::find($id);
        $company->addToCache();
        $this->model = $company;
        $this->model->addToCache();
        $this->model->addToCacheV2();
        $this->model->addToGraph();
        //update the document
        \App\Documents\Company::create($company);
        \App\Filter\Company::addModel($company);
        return $this->sendResponse();
    }
    
    
    /**
     * @param Request $request
     * @param $profileId Profile Id
     * @param $id Company Id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $profileId, $id)
    {
        $userId = $request->user()->id;

        //remove subscribers
        Subscriber::where("channel_name","like","company%$id")->delete();
    
        //remove from following profiles
        $followers = Redis::smembers("followers:company:$id");
        if(count($followers)){
            foreach($followers as $profileId){
                Redis::sRem("following:profile:$profileId",$id);
            }
        }
        
        //remove company admins
        CompanyUser::where("company_id",$id)->delete();
        
        //remove filters
        \App\Filter\Company::removeModel($id);
    
        //delete company
        $this->model = Company::where('id',$id)->where('user_id',$userId)->delete();
        
        
        //remove from cache
        Redis::del("company:small:".$id);
        Redis::del("followers:company:$id");
        \App\Neo4j\Company::where('company_id', (int)$id)->delete();
    
        return $this->sendResponse();
    }
    
    /**
     * Returns company logo
     *
     * @param $profileId
     * @param $id Company id
     * @return mixed
     */
    public function logo($profileId, $id)
    {
        $company = \DB::table('companies')->select('logo')->find($id);
        $path = Company::getLogoPath($profileId, $id,$company->logo);
        return response()->file($path);
    }
    
    /**
     * Returns Company Hero Image
     *
     * @param $profileId
     * @param $id Company Id
     * @return mixed
     */
    public function heroImage($profileId, $id)
    {
        $company = \DB::table('companies')->select('hero_image')->find($id);
        $path = Company::getHeroImagePath($profileId, $id,$company->hero_image);
        return response()->file($path);
    }

    //This is to allow all the users to follow a company
    public function followCompanyAll(Request $request, $profileId, $id)
    {
        $profiled_IdList = \DB::table('profiles')->select('id')->whereNull('deleted_at')->get();
        foreach ($profiled_IdList as $key => $pid) {
            $profile_id = $pid->id;

            $channel_owner = Company::find($id);
            $profile_owner = Profile::find($profile_id);
            
            $this->model = $profile_owner->subscribeNetworkOf($channel_owner);

            if (!$this->model) {
                
            }else{
                
                // add companies the logged in user is following
                Redis::sAdd("following:profile:" . $profile_id, "company.$id");
            
                // add companies that are following $channel_owner
                Redis::sAdd("followers:company:".$id, $profile_id);
        
                Redis::sRem('suggested:company:'.$profile_id, $id);
                
                $subscriber = new Subscriber();
                $subscriber->followCompanySuggestion((int)$profile_id, (int)$id);
            }
        }

        return $this->sendResponse();
    }
    
    public function follow(Request $request, $profileId, $id)
    {
        $channel_owner = Company::find($id);
        if (!$channel_owner) {
            throw new ModelNotFoundException("Company not found.");
        }
        
        $this->model = $request->user()->completeProfile->subscribeNetworkOf($channel_owner);
        if (!$this->model) {
           return $this->sendError("You are already following this company.");
        }
        
        $profile_id = $request->user()->profile->id;
    
        // add companies the logged in user is following
        Redis::sAdd("following:profile:" . $profile_id, "company.$id");
    
        // add companies that are following $channel_owner
        Redis::sAdd("followers:company:".$id, $profile_id);

        Redis::sRem('suggested:company:'.$profile_id, $id);
        
        $subscriber = new Subscriber();
        $subscriber->followCompanySuggestion((int)$profile_id, (int)$id);
        
        return $this->sendResponse();
    }
    
    public function unfollow(Request $request, $profileId, $id)
    {
        $channel_owner = Company::find($id);
        if(!$channel_owner){
            throw new ModelNotFoundException("Company not found.");
        }

        $this->model = $request->user()->completeProfile->unsubscribeNetworkOf($channel_owner);
        if (!$this->model) {
            return $this->sendError("You are not following this company.");
        }

        $profile_id = $request->user()->profile->id;
        
        // remove companies the logged in user is following
        Redis::sRem("following:profile:".$profile_id, "company.$id");
    
        // remove profiles that are following $channelOwner
        Redis::sRem("followers:company:".$id, $profile_id);

        $subscriber = new Subscriber();
        $subscriber->unfollowCompanySuggestion((int)$profile_id, (int)$id);

        return $this->sendResponse();
    }

    private function getFollowers($id, $loggedInProfileId)
    {
        $followers = Company::getFollowers($id);
        if(!$followers){
            throw new ModelNotFoundException("Followers not found.");
        }

        $followerProfileIds = $followers->pluck('id')->toArray();
        //build network names
        $networks = [];

        foreach($followerProfileIds as $profileId){
                $networks[] = 'company.public.' . $profileId;
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

                $channel = $alreadySubscribed->get('company.public.' . $profile['id']);
                if($channel === null){
                    continue;
                }

                $profile['isFollowing'] = true;
            }
        }

        return $result;
    }

    public function followers(Request $request, $profileId, $id)
    {
        $this->model = [];
        $profileIds = Redis::SMEMBERS("followers:company:".$id);
        $this->model['count'] = count($profileIds);
        $data = [];
        $page = $request->has('page') ? $request->input('page') : 1;
        $profileIds = array_slice($profileIds ,($page - 1)*20 ,20 );
        foreach ($profileIds as &$profileId)
        {
            $profileId = "profile:small:".$profileId ;
        }

        $loggedInProfileId = $request->user()->profile->id ;
        if(count($profileIds)> 0)
        {
            $data = Redis::mget($profileIds);

        }
        foreach($data as &$profile){
            if(is_null($profile)){
                continue;
            }
            $profile = json_decode($profile);
            $profile->isFollowing = Redis::sIsMember("followers:profile:".$profile->id,$loggedInProfileId) === 1;
        }
        $this->model['profile'] = $data;
        return $this->sendResponse();
    }
}
