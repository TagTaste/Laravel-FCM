<?php

namespace App\Http\Controllers\Api\Profile\Company;

use App\Http\Controllers\Api\Controller;
use App\Company\Coreteam;
use App\Jobs\SendInvitation;
use App\Profile;
use Illuminate\Http\Request;

class CoreteamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $profileId, $companyId)
    {
        $coreteams = Coreteam::where('company_id',$companyId)->orderBy('order','ASC')->get();
        $this->model = [];
        $loggedInProfileId = $request->user()->profile->id;
        foreach ($coreteams as $coreteam)
        {
                $temp = $coreteam->toArray();
                $temp['isFollowing'] =$temp['profile_id']!=null ? Profile::isFollowing($temp['profile_id'], $loggedInProfileId) : false;
                $this->model[] = $temp;
        }
        return $this->sendResponse();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$profileId, $companyId)
    {
        $userId = $request->user()->id;
        $company = \App\Company::where('id',$companyId)->where('user_id',$userId)->first();

        if(!$company){
            throw new \Exception("User does not belong to this company.");
        }
        $data = $request->except(['_method','_token','company_id']);
        $data['company_id'] = $companyId;
        if($request->hasFile('image')) {
            $imageName = str_random(32) . ".jpg";
            $path = Coreteam::getCoreteamImagePath($profileId, $companyId);
            $response = $request->file("image")->storeAs($path, $imageName, ['visibility' => 'public']);
            if (!$response) {
                throw new \Exception("Could not save resume " . $imageName . " at " . $path);
            }
            $data['image'] = $response;
        }
        
        if($request->has("profile_id"))
        {
            $profile = \App\Recipe\Profile::find($request->input('profile_id'));
            $data['image'] = $profile->image;
        }
        $data['invited'] = !$request->has("profile_id") && $request->has("email");

        $this->model = $company->coreteam()->create($data);
        
        if($request->has("email") && env('ENABLE_EMAILS',0) == 1)
        {
            $mail = (new SendInvitation($request->user(),$this->model,$request->input("email")))->onQueue('emails');
            \Log::info('Queueing send invitation...');

            dispatch($mail);
        }
            $this->model = $this->model->toArray();
            $this->model['isFollowing'] = $this->model['profile_id']!= null ? Profile::isFollowing($this->model['profile_id'], $request->user()->profile->id) : false;

        return $this->sendResponse();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$profileId,$companyId,$id)
    {
        $this->model = Coreteam::where('company_id',$companyId)->where('id',$id)->first();
        if(!$this->model){
            throw new \Exception("Core team not found.");
        }
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
    public function update(Request $request, $profileId,$companyId,$id)
    {
        $userId = $request->user()->id;
        $company = \App\Company::where('id',$companyId)->where('user_id',$userId)->first();

        if(!$company){
            throw new \Exception("User does not belong to this company.");
        }
        $data = $request->except(['_method','_token','company_id']);

        if($request->hasFile('image')){
            $imageName = str_random(32) . ".jpg";
            $path = Coreteam::getCoreteamImagePath($profileId, $companyId);
            $response = $request->file("image")->storeAs($path,$imageName,['visibility'=>'public']);
            if(!$response)
            {
                throw new \Exception("Could not save resume " . $imageName . " at " . $path);
            }
            $data['image'] = $response;
        }
        $this->model = $company->coreteam()->where('id',$id)->update($data);


        return $this->sendResponse();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $profileId, $companyId, $id)
    {
        $userId = $request->user()->id;
        $company = \App\Company::where('id',$companyId)->where('user_id',$userId)->first();

        if(!$company){
            throw new \Exception("User does not belong to this company.");
        }

        $this->model = $company->coreteam()->where('id',$id)->delete();

        return $this->sendResponse();
    }

    public function ordering(Request $request, $profileId, $companyId)
    {
        $userId = $request->user()->id;
        $company = \App\Company::where('id',$companyId)->where('user_id',$userId)->first();

        if(!$company){
            throw new \Exception("User does not belong to this company.");
        }
        $members =$request->input("member");
        if(count($members)>0){
            foreach ($members as $member){
                $this->model = $company->coreteam()->where('id',$member['id'])->update(['order'=>$member['order']]);
            }
        }
        $this->model = Coreteam::where('company_id',$companyId)->orderBy('order','ASC')->get();

        return $this->sendResponse();
    }
}
