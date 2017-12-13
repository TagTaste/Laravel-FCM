<?php

namespace App\Http\Controllers\Api\Profile\Company;

use App\Company\Coreteam;
use App\Http\Controllers\Api\Controller;
use App\Invitation;
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
        $this->model = Coreteam::where('company_id',$companyId)->orderBy('order','ASC')->get();
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
        if($request->has("profile_id"))
        {
            $profileId = Coreteam::where("company_id",$companyId)->where("profile_id",$request->input("profile_id"))->exists();
            if($profileId)
            {
                return $this->sendError("You have already added this user as a core team member in your company");
            }
        }
        $data = $request->except(['_method','_token','company_id']);
        $data['company_id'] = $companyId;
        if($request->hasFile('image')) {
            $imageName = str_random(32) . ".jpg";
            $path = Coreteam::getCoreteamImagePath($companyId);
            $response = $request->file("image")->storeAs($path, $imageName, ['visibility' => 'public']);
            if (!$response) {
                throw new \Exception("Could not save image " . $imageName . " at " . $path);
            }
            $data['image'] = $response;
        }
        
        if($request->has("profile_id"))
        {
            $profile = \App\Recipe\Profile::find($request->input('profile_id'));
            if($profile){
                $data['image'] = $profile->image;
            }
        }
        $data['invited'] = !$request->has("profile_id") && $request->has("email");

        $this->model = Coreteam::create($data);

            $this->model = $this->model->toArray();
            $this->model['isFollowing'] = isset($this->model['profile_id']) ? Profile::isFollowing($this->model['profile_id'], $request->user()->profile->id) : false;

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

        if(isset($data['email']) && empty($data['email'])){
            unset($data['email']);
        }
        
        $this->model = Coreteam::where('id',$id)->update($data);
        
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
        $this->model = Coreteam::where('id',$id)->delete();

        return $this->sendResponse();
    }

    public function ordering(Request $request, $profileId, $companyId)
    {
        $members =$request->input("member");
        if(count($members)>0){
            foreach ($members as $member){
                $this->model = Coreteam::where('id',$member['id'])->update(['order'=>$member['order']]);
            }
        }
        $this->model = Coreteam::where('company_id',$companyId)->orderBy('order','ASC')->get();

        return $this->sendResponse();
    }
}
