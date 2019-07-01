<?php

namespace App\Http\Controllers\Api\V2;

use App\Company;
use App\Events\Actions\Tag;
use App\Events\DeleteFeedable;
use App\Events\Model\Subscriber\Create;
use App\Events\NewFeedable;
use App\Events\UpdateFeedable;
use App\Http\Controllers\Api\Controller;
use App\Http\Requests\API\Photo\StoreRequest;
use App\Http\Requests\API\Photo\UpdateRequest;
use App\V2\Photo;
use App\Traits\CheckTags;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class PhotoController extends Controller
{
    use CheckTags;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $loggedInProfileId = $request->user()->profile->id;
        if($request->has('company_id') && !is_null($request->input('company_id')))
        {
            $companyId = $request->input('company_id');
            $photos = Photo::forCompany($companyId)->orderBy('created_at','desc')->orderBy('updated_at','desc');
        }
        else
        {
            $profileId = $request->has('profile_id') ? $request->input('profile_id') : $loggedInProfileId;
            $photos = Photo::forProfile($profileId)->orderBy('created_at','desc')->orderBy('updated_at','desc');
        }
        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        $count = $photos->count();
        $photos = $photos->skip($skip)->take($take)->get();

        $this->model = [];

        foreach($photos as $photo){
            $photo->images = json_decode($photo->images);
            $this->model[] = ['photo'=>$photo,'meta'=>$photo->getMetaFor($loggedInProfileId)];
        }
        $this->model = ['data'=>$this->model,'count'=>$count];
        return $this->sendResponse();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $profile = $request->user()->profile;
        $profileId = $profile->id;
        $data = [];
        if(!isset($data['privacy_id'])){
            $data['privacy_id'] = 1;
        }
        if(!$request->has('images') || is_null($request->input('images')) || !is_array($request->input('images')))
        {
            return $this->sendError("wrong format");
        }
        $data['caption'] = $request->input('caption');
        $data['images'] = $this->changeInJson($request->input('images'));
        $data['has_tags'] = $this->hasTags($data['caption']);
        if($request->has('company_id') && !is_null($request->input('company_id')))
        {
            $companyId = $request->input('company_id');
            $userId = $request->user()->id;
            $company = Company::find($companyId);
            $userBelongsToCompany = $company->checkCompanyUser($userId);
            if(!$userBelongsToCompany){
                return $this->sendError("User does not belong to this company");
            }

            $this->model = $company->photos()->create($data);
            $this->model->images = json_decode($this->model->images);
            if($data['has_tags']){
                event(new Tag($this->model, $profile, $this->model->caption));
            }
            $data = ['id'=>$this->model->id,'caption'=>$this->model->caption,'images'=>$this->model->images,
                'created_at'=>$this->model->created_at->toDateTimeString(),'updated_at'=>$this->model->updated_at->toDateTimeString(), 'image_meta'=>json_encode($this->model->images[0])];
            \Redis::set("photo:" . $this->model->id,json_encode($data));
            event(new NewFeedable($this->model,$company));

            //add subscriber
            event(new \App\Events\Model\Subscriber\Create($this->model,$request->user()->profile));
            return $this->sendResponse();

        }
        else
        {
            $photo = Photo::create($data);
            if(!$photo){
                return $this->sendError("Could not create photo.");
            }
            $photo->images = json_decode($photo->images);
            $res = \DB::table("profile_photos")->insert(['profile_id'=>$profileId,'photo_id'=>$photo->id]);
            $data = ['id'=>$photo->id,'caption'=>$photo->caption,'images'=>$photo->images,
                'created_at'=>$photo->created_at->toDateTimeString(), 'updated_at'=>$photo->updated_at->toDateTimeString(), 'image_meta'=>json_encode($photo->images[0])];

            Redis::set("photo:" . $photo->id,json_encode($data));


            //add to feed
            event(new NewFeedable($photo, $request->user()->profile));

            //add model subscriber
            event(new Create($photo,$request->user()->profile));

            //recent uploads
            Redis::lPush("recent:user:" . $request->user()->id . ":photos",$photo->id);
            Redis::lTrim("recent:user:" . $request->user()->id . ":photos",0,9);


            $this->model = $photo;

            if(isset($data['has_tags'])){
                event(new Tag($this->model, $profile, $this->model->caption));
            }
            return $this->sendResponse();
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
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
        $photo->images = json_decode($photo->images);
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

    public function update(UpdateRequest $request,$id)
    {
        $profile = $request->user()->profile;
        $profileId = $profile->id;
        $data = [];
        $data['caption'] = $request->input('caption');
        $datum['has_tags'] = $this->hasTags($data['caption']);
        if($request->has('company_id') && !is_null($request->input('company_id')))
        {
            $companyId = $request->input('company_id');
            $userId = $request->user()->id;
            $company = Company::find($companyId);
            $userBelongsToCompany = $company->checkCompanyUser($userId);
            if (!$userBelongsToCompany) {
                return $this->sendError("User does not belong to this company");
            }
            $this->model = $company->photos()->where('id',$id)->update($data);

            $this->model = \App\V2\Photo::find($id);
            if(isset($datum['has_tags']) && $datum['has_tags']){
                event(new Tag($this->model, $profile, $this->model->caption));
            }
            $data = ['id'=>$this->model->id,'caption'=>$this->model->caption,'images'=>json_decode($this->model->images),'created_at'=>$this->model->created_at->toDateTimeString(),'updated_at'=>$this->model->updated_at->toDateTimeString(),'image_meta'=>json_encode(json_decode($this->model->images)[0])];
            \Redis::set("photo:" . $this->model->id,json_encode($data));
            event(new UpdateFeedable($this->model));

            $loggedInProfileId = $request->user()->profile->id;
            $meta = $this->model->getMetaFor($loggedInProfileId);
            $this->model->images = json_decode($this->model->images);
            $this->model = ['photo'=>$this->model,'meta'=>$meta];
            return $this->sendResponse();

        } else {
            $data['has_tags'] = $this->hasTags($data['caption']);
            $inputs = $data;
            unset($inputs['has_tags']);
            \App\V2\Photo::where('id',$id)->update($inputs);
            $this->model = \App\V2\Photo::find($id);
            if(isset($data['has_tags']) && $data['has_tags']){
                event(new Tag($this->model, $request->user()->profile, $this->model->caption));
            }
            $data = ['id'=>$this->model->id,'caption'=>$this->model->caption,'images'=>json_decode($this->model->images),'updated_at'=>$this->model->updated_at->toDateTimeString(),'created_at'=>$this->model->created_at->toDateTimeString(),'image_meta'=>json_encode(json_decode($this->model->images)[0])];
            Redis::set("photo:" . $this->model->id,json_encode($data));
            event(new UpdateFeedable($this->model));

            $loggedInProfileId = $request->user()->profile->id;
            $meta = $this->model->getMetaFor($loggedInProfileId);
            $this->model->images = json_decode($this->model->images);
            $this->model = ['photo'=>$this->model,'meta'=>$meta];
            return $this->sendResponse();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if($request->has('company_id') && !is_null($request->input('company_id')))
        {
            $companyId = $request->input('company_id');
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
        else
        {
            $this->model =  $request->user()->profile->photos()->where('id',$id)->first();
            if(!$this->model){
                return $this->sendError("Photo not found.");
            }
            event(new DeleteFeedable($this->model));
            $this->model = $this->model->delete();
            //remove from recent photos
            Redis::lRem("recent:user:" . $request->user()->id . ":photos",$id,1);
            return $this->sendResponse();
        }
    }

    public function changeInJson($images)
    {
        $data = [];
        foreach ($images as $image)
        {
            $image = json_decode($image);
            $data[] = ['original_photo'=>$image->original_photo,'tiny_photo'=>$image->tiny_photo,'meta'=>$image->meta];
        }
        return json_encode($data);
    }
}
