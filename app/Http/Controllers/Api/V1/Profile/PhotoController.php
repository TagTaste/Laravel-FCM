<?php

namespace App\Http\Controllers\Api\V1\Profile;

use App\Events\Actions\Tag;
use App\Events\DeleteFeedable;
use App\Events\Model\Subscriber\Create;
use App\Events\NewFeedable;
use App\Events\UpdateFeedable;
use App\Http\Controllers\Api\Controller;
use App\Http\Requests\API\Photo\StoreRequest;
use App\Http\Requests\API\Photo\UpdateRequest;
use App\Photo;
use App\Traits\CheckTags;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    use CheckTags;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request, $profileId)
    {
        $photos = Photo::forProfile($profileId)->orderBy('created_at','desc')->orderBy('updated_at','desc');
        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        $count = $photos->count();
        $photos = $photos->skip($skip)->take($take)->get();

        $this->model = [];
        $loggedInProfileId = $request->user()->profile->id;
        foreach($photos as $photo){
            $this->model[] = ['photo'=>$photo,'meta'=>$photo->getMetaFor($loggedInProfileId)];
        }
        $this->model = ['data'=>$this->model,'count'=>$count];
        return $this->sendResponse();
    }

    private function saveFileToData($key,$path,&$request,&$data,$extraKey = null)
    {
        if($request->hasFile($key)){
            $response = $this->saveFile($path,$request,$key);
            $data[$extraKey] = json_encode($response,true);
            $data[$key] = $response['original_photo'];
        }
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $profile = $request->user()->profile;
        $profileId = $profile->id;
        $data = $request->except(['_method','_token','profile_id']);
        if(!isset($data['privacy_id'])){
            $data['privacy_id'] = 1;
        }
        $path = Photo::getProfileImagePath($profileId);
        $data['image_info'] = null;
        if(isset($imageInfo))
        {
            $data['image_info'] = json_encode($imageInfo,true);
        }
        $this->saveFileToData("file",$path,$request,$data,"image_meta");
        $data['has_tags'] = $this->hasTags($data['caption']);
        $photo = Photo::create($data);
        if(!$photo){
            return $this->sendError("Could not create photo.");
        }
        
        $res = \DB::table("profile_photos")->insert(['profile_id'=>$profileId,'photo_id'=>$photo->id]);
        $data = ['id'=>$photo->id,'caption'=>$photo->caption,'photoUrl'=>$photo->photoUrl,'image_info'=>$data['image_info'],
            'created_at'=>$photo->created_at->toDateTimeString(), 'updated_at'=>$photo->updated_at->toDateTimeString()];
        
        \Redis::set("photo:" . $photo->id,json_encode($data));
        
        //add to feed
        event(new NewFeedable($photo, $request->user()->profile));
        
        //add model subscriber
        event(new Create($photo,$request->user()->profile));
        
        //recent uploads
        \Redis::lPush("recent:user:" . $request->user()->id . ":photos",$photo->id);
        \Redis::lTrim("recent:user:" . $request->user()->id . ":photos",0,9);
        
        $this->model = $photo;
    
        if(isset($data['has_tags'])){
            event(new Tag($this->model, $profile, $this->model->caption));
        }
        return $this->sendResponse();
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$profileId,$id)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $photo = Photo::where('id',$id)->with(['comments' => function($query){
            $query->orderBy('created_at','desc');
            }])
        ->with(['like'=>function($query) use ($loggedInProfileId){
                $query->where('profile_id',$loggedInProfileId);
            }])->first();

        if(!$photo){
            return $this->sendError("Photo not found");
        }
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
    public function update(UpdateRequest $request, $profileId,$id)
    {
        $data = $request->except(['_method','_token','profile_id']);
        if(!isset($data['privacy_id'])){
            $data['privacy_id'] = 1;
        }
        $path = Photo::getProfileImagePath($profileId);
        $this->saveFileToData("file",$path,$request,$data,"image_meta");
        $data['has_tags'] = $this->hasTags($data['caption']);
        $inputs = $data;
        unset($inputs['has_tags']);
        $this->model = $request->user()->profile->photos()->where('id',$id)->update($inputs);
        $this->model = \App\Photo::find($id);
        if(isset($data['has_tags']) && $data['has_tags']){
            event(new Tag($this->model, $request->user()->profile, $this->model->caption));
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
    public function destroy(Request $request, $profileId, $id)
    {
        $this->model =  $request->user()->profile->photos()->where('id',$id)->first();
        if(!$this->model){
            return $this->sendError("Photo not found.");
        }
        event(new DeleteFeedable($this->model));
        $this->model = $this->model->delete();
        //remove from recent photos
        \Redis::lRem("recent:user:" . $request->user()->id . ":photos",$id,1);
        return $this->sendResponse();
    }

    public function image($profileId, $id)
    {
        $photo = \App\Photo::select('file')->find($id);
        
        if(!$photo){
            throw new ModelNotFoundException("Could not find photo with id " . $id);
        }
        $file = Photo::getProfileImagePath($profileId, $photo->file);
        if(file_exists($file)){
            return response()->file($file);
    
        }
    }
}
