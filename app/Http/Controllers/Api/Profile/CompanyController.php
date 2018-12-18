<?php

namespace App\Http\Controllers\Api\Profile;

use App\Company;
use App\CompanyRating;
use App\CompanyUser;
use App\Http\Controllers\Api\Controller;
use App\Subscriber;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;


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
            //image
//            $imageName = str_random(32) . ".jpg";
//            $path = \App\Company::getLogoPath($profileId, $company->id);
//            $inputs['logo'] = $request->file('logo')->storeAs($path, $imageName,['visibility'=>'public']);
    
            //store thumbnail
            $path = \App\Company::getLogoPath($profileId, $company->id) . "/" . str_random(20) . ".jpg";
            $thumbnail = \Image::make($request->file('logo'))->resize(180, null,function ($constraint) {
                $constraint->aspectRatio();
            })->stream('jpg',70);
            \Storage::disk('s3')->put($path, (string) $thumbnail,['visibility'=>'public']);
            $inputs['logo'] = $path;
        }

        if($request->hasFile('hero_image')){
            $heroImageName = str_random(32) . ".jpg";
            $path = \App\Company::getHeroImagePath($profileId, $company->id);
            $inputs['hero_image'] = $request->file('hero_image')->storeAs($path,$heroImageName,['visibility'=>'public']);
        }
        
        if($company->isDirty()){
            $company->update();
        }
    
        \App\Filter\Company::addModel($company);
    
        $this->model = $company;
        return $this->sendResponse();
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
            $path = \App\Company::getLogoPath($profileId, $id) . "/" . str_random(20) . ".jpg";
            $thumbnail = \Image::make($request->file('logo'))->resize(180, null,function ($constraint) {
                $constraint->aspectRatio();
            })->stream('jpg',70);
            \Storage::disk('s3')->put($path, (string) $thumbnail,['visibility'=>'public']);
            $inputs['logo'] = $path;
        }

        if($request->hasFile('hero_image')){
            $heroImageName = str_random(32) . ".jpg";
            $path = \App\Company::getHeroImagePath($profileId, $id);
            $inputs['hero_image'] = $request->file('hero_image')->storeAs($path,$heroImageName,['visibility'=>'public']);
        }

        //delete heroimage or image
        if($request->has("remove_logo") && $request->input('remove_logo') == 1)
        {
            $inputs['logo'] = null;
        }

        if($request->has("remove_hero_image") && $request->input('remove_hero_image') == 1)
        {
            $inputs['hero_image'] = null;
        }

        $status = \App\Company::where('id',$id)->update($inputs);
        if(!$status){
            return $this->sendError("Could not update company");
        }
        
        $company = \App\Company::find($id);
        return $this->model;

        $company->addToCache();
        $this->model = $company;
        //update the document
        \App\Documents\Company::create($this->model);
        \App\Filter\Company::addModel($this->model);

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
        $followers = \Redis::smembers("followers:company:$id");
        if(count($followers)){
            foreach($followers as $profileId){
                \Redis::sRem("following:profile:$profileId",$id);
            }
        }
        
        //remove company admins
        CompanyUser::where("company_id",$id)->delete();
        
        //remove filters
        \App\Filter\Company::removeModel($id);
    
        //delete company
        $this->model = Company::where('id',$id)->where('user_id',$userId)->delete();
        
        
        //remove from cache
        \Redis::del("company:small:" . $id);
        \Redis::del("followers:company:$id");
    
    
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
    
    public function follow(Request $request, $profileId, $id)
    {
        $channelOwner = Company::find($id);
        if(!$channelOwner){
            throw new ModelNotFoundException("Company not found.");
        }
        
        $this->model = $request->user()->completeProfile->subscribeNetworkOf($channelOwner);
        
        if(!$this->model){
           return $this->sendError("You are already following this company.");
        }
        
        $profileId = $request->user()->profile->id;
    
        //companies the logged in user is following
        \Redis::sAdd("following:profile:" . $profileId, "company.$id");
    
        //profiles that are following $channelOwner
        \Redis::sAdd("followers:company:" . $id, $profileId);

        \Redis::sRem('suggested:company:'.$profileId,$id);
        
        return $this->sendResponse();
    }
    
    public function unfollow(Request $request, $profileId, $id)
    {
        $channelOwner = Company::find($id);
        if(!$channelOwner){
            throw new ModelNotFoundException();
        }

        $this->model = $request->user()->completeProfile->unsubscribeNetworkOf($channelOwner);
        
        if(!$this->model){
            return $this->sendError("You are not following this company.");
        }
        $profileId = $request->user()->profile->id;
        //companies the logged in user is following
        \Redis::sRem("following:profile:" . $profileId, "company.$id");
    
        //profiles that are following $channelOwner
        \Redis::sRem("followers:company:" . $id, $profileId);
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
        $profileIds = \Redis::SMEMBERS("followers:company:".$id);
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
}
