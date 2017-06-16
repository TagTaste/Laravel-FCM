<?php

namespace App\Http\Controllers\Api\Profile;

use App\Company;
use App\Subscriber;
use App\Http\Controllers\Api\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;


class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $profileId)
    {
        $companies = $request->user()->companies;
        
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
        $inputs = $request->intersect(['name','about','phone',
            'email','registered_address','established_on', 'status_id',
            'type','employee_count','client_count','annual_revenue_start',
            'annual_revenue_end',
            'facebook_url','twitter_url','linkedin_url','instagram_url','youtube_url','pinterest_url','google_plus_url','websites',
            'milestones',
            'speciality'
        ]);
        if(empty($inputs)){
            throw new \Exception("Empty request received.");
        }
        $imageName = null;
        $heroImageName = null;
        if($request->hasFile('logo')){
            $imageName = str_random(32) . ".jpg";
            $inputs['logo'] = $imageName;
        }
        
        if($request->hasFile('heroImage')){
            $heroImageName = str_random(32) . ".jpg";
            $inputs['hero_image'] = $heroImageName;
        }
    
        $company = $request->user()->companies()->create($inputs);
        
        if($request->hasFile('logo') && $imageName !== null){
            $path = \App\Company::getLogoPath($profileId, $company->id);
            $response = $request->file('logo')->storeAs($path, $imageName);
        }
    
        if($request->hasFile('heroImage')){
            $request->file('heroImage')->storeAs(\App\Company::getHeroImagePath($profileId, $company->id),$heroImageName);
        }
        
        if($company->isDirty()){
            $company->update();
        }
    
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
        $inputs = $request->intersect(['name','about','logo','hero_image','phone',
            'email','registered_address','established_on', 'status_id',
            'type','employee_count','client_count','annual_revenue_start',
            'annual_revenue_end',
            'facebook_url','twitter_url','linkedin_url','instagram_url','youtube_url','pinterest_url','google_plus_url',
            'tagline','establishments','cuisines','websites','milestones',
            'speciality'
        ]);
    
        if($request->hasFile('logo')){
            $imageName = str_random(32) . ".jpg";
            $path = \App\Company::getLogoPath($profileId, $id);
            $response = $request->file('logo')->storeAs($path, $imageName);
            if($response !== false){
                $inputs['logo'] = $imageName;
            }
        }
    
        if($request->hasFile('hero_image')){
            $imageName = str_random(32) . ".jpg";
            $path = \App\Company::getHeroImagePath($profileId, $id);
            $response = $request->file('hero_image')->storeAs($path,$imageName);
            if($response !== false){
                $inputs['hero_image'] = $imageName;
            }
        }

        $this->model = $request->user()->companies()->where('id',$id)->update($inputs);
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
        $this->model = $request->user()->companies()->where('id',$id)->delete();
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
        
        $this->model = $request->user()->profile->subscribeNetworkOf($channelOwner);
        
        if(!$this->model){
            throw new \Exception("You are already following this company.");
        }
        
        return $this->sendResponse();
    }
    
    public function unfollow(Request $request, $profileId, $id)
    {
        $channelOwner = Company::find($id);
        if(!$channelOwner){
            throw new ModelNotFoundException();
        }
        
        $this->model = $request->user()->profile->unsubscribeNetworkOf($channelOwner);
        
        if(!$this->model){
            throw new \Exception("You are not following this company.");
        }
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
            if($profileId != $loggedInProfileId){
                $networks[] = 'company.public.' . $profileId;
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

                $channel = $alreadySubscribed->get('company.public.' . $profile['id']);
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
}
