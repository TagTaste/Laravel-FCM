<?php

namespace App\Http\Controllers\Api\Profile\Company;

use App\Company;
use App\Events\Actions\Tag;
use App\Events\DeleteFeedable;
use App\Events\NewFeedable;
use App\Events\UpdateFeedable;
use App\Http\Controllers\Api\Controller;
use App\Photo;
use App\Traits\CheckTags;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    use CheckTags;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $profileId,$companyId)
    {
        $photos = Photo::forCompany($companyId)->orderBy('created_at','desc')->orderBy('updated_at','desc')->paginate(10);
        $this->model = [];
        $loggedInProfileId = $request->user()->profile->id;
        foreach($photos as $photo){
            $this->model[] = ['photo'=>$photo,'meta'=>$photo->getMetaFor($loggedInProfileId)];
        }
        return $this->sendResponse();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $profileId, $companyId)
    {
        //check if company exists
        $company = Company::find($companyId);
        
        if(!$company){
            throw new \Exception( "This company does not exist");
        }
        $profile = $request->user()->profile;
        $profileId = $profile->id;
        //check if user belongs to the company
        $userId = $request->user()->id;
        $userBelongsToCompany = $company->checkCompanyUser($userId);
        
        if(!$userBelongsToCompany){
            return $this->sendError("User does not belong to this company");
        }
        
        //create the photo
        $data = $request->except(['_method','_token','company_id']);
        
        if(!$request->hasFile('file') && empty($request->input('file)'))){
           return $this->sendError("Photo missing.");
        }
        
        $imageName = str_random(32) . ".jpg";
        $path = Photo::getCompanyImagePath($profileId, $companyId);
        $this->saveFileToData("image_meta",$path,$request,$data,"file");
        $data['image_info'] = null;
        if(!isset($data['privacy_id'])){
            $data['privacy_id'] = 1;
        }
        $data['has_tags'] = $this->hasTags($data['caption']);

        $this->model = $company->photos()->create($data);
        if($data['has_tags']){
            event(new Tag($this->model, $profile, $this->model->caption));
        }
        $data = ['id'=>$this->model->id,'caption'=>$this->model->caption,'photoUrl'=>$this->model->photoUrl,'image_info'=>$data['image_info'],
            'created_at'=>$this->model->created_at->toDateTimeString(),'updated_at'=>$this->model->updated_at->toDateTimeString()];
        \Redis::set("photo:" . $this->model->id,json_encode($data));
        event(new NewFeedable($this->model,$company));
    
        //add subscriber
        event(new \App\Events\Model\Subscriber\Create($this->model,$request->user()->profile));
        return $this->sendResponse();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $profileId, $companyId, $id)
    {
        $photo = Photo::where('id',$id)->forCompany($companyId)->with(['comments' => function($query){
            $query->orderBy('created_at','desc');
            }])->first();

        if(!$photo){
            throw new \Exception("Company does not have the photo.");
        }
        $loggedInProfileId = $request->user()->profile->id;
        $meta = $photo->getMetaFor($loggedInProfileId);
        $this->model = ['photo'=>$photo,'meta'=>$meta];

        return $this->sendResponse();
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
        $company = Company::find($companyId);
    
        if(!$company){
           throw new \Exception("This company does not belong to the user.");
        }
        $profile = $request->user()->profile;
        $profileId = $profile->id;

        
        $data = $request->except(['_method','_token','company_id']);
        $data['has_tags'] = $this->hasTags($data['caption']);

        if(!isset($data['privacy_id'])){
            $data['privacy_id'] = 1;
        }
        $inputs = $data;
        unset($inputs['has_tags']);
        $this->model = $company->photos()->where('id',$id)->update($inputs);
        
        $this->model = Photo::find($id);
        if(isset($data['has_tags']) && $data['has_tags']){
            event(new Tag($this->model, $profile, $this->model->caption));
        }
        $data = ['id'=>$this->model->id,'caption'=>$this->model->caption,'photoUrl'=>$this->model->photoUrl,'created_at'=>$this->model->created_at->toDateTimeString(),'updated_at'=>$this->model->updated_at->toDateTimeString()];
        \Redis::set("photo:" . $this->model->id,json_encode($data));
        event(new UpdateFeedable($this->model));

        $loggedInProfileId = $request->user()->profile->id;
        $meta = $this->model->getMetaFor($loggedInProfileId);
        $this->model = ['photo'=>$this->model,'meta'=>$meta];
    
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
        $company = Company::find($companyId);
    
        if(!$company){
            throw new \Exception("This company does not belong to the user.");
        }
    
        //check if user belongs to the company
        $userId = $request->user()->id;
        $userBelongsToCompany = $company->checkCompanyUser($userId);
    
        if(!$userBelongsToCompany){
            return $this->sendError("User does not belong to this company");
        }
        
        $this->model = $company->photos()->where('id',$id)->first();
        if(!$this->model){
            return $this->sendError("Photo not found.");
        }
        event(new DeleteFeedable($this->model));
        
        $this->model = $this->model->delete();
        return $this->sendResponse();
    }

    public function image($profileId, $companyId, $id)
    {
        $photo = \App\Photo::select('file')->find($id);
        $file = Photo::getProfileImagePath($profileId, $photo->file);
        if(file_exists($file)){
            return response()->file($file);
        
        }
    }

    private function saveFileToData($key,$path,&$request,&$data,$extraKey = null)
    {
        if($request->hasFile($key)){
            $response = $this->saveFile($path,$request,$key);
            $data[$key] = json_encode($response,true);
            $data[$extraKey] = $response['original_photo'];
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
        $response['meta'] = getimagesize($request->input($key));
        if(!$response){
            throw new \Exception("Could not save image " . $imageName . " at " . $path);
        }
        return $response;
    }
}
