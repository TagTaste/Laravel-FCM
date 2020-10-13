<?php

namespace App\Http\Controllers\Api;

use App\Channel\Payload;
use App\Events\Actions\Share;
use App\Events\DeleteFeedable;
use App\Events\Model\Subscriber\Create;
use App\Events\NewFeedable;
use App\PublicReviewProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Traits\CheckTags;
use App\Events\Actions\Tag;
use App\Traits\HashtagFactory;

class ShareController extends Controller
{
    use CheckTags,HashtagFactory;
    private $column = "_id";
    
    private function setColumn(&$modelName)
    {
        if($modelName == 'polling')
            $this->column = 'poll_id';
        else
            $this->column = $modelName . $this->column;
    }
    
    private function getModel(&$modelName, &$id)
    {
        if(ucfirst($modelName) === 'Photo')
        {
            $class = "\\App\\V2\\" . ucfirst ($modelName);
            $photo = $class::where('id',$id)->whereNull('deleted_at')->first();
            $photo->images = json_decode($photo->images);
            return $photo;
        }
        else if (ucfirst($modelName)== 'Product') {
            $class = "\\App\\PublicReviewProduct";
            return $class::where('id',$id)->whereNull('deleted_at')->first();
        }
        else{
            $class = "\\App\\" . ucfirst ($modelName);
            if($modelName == 'collaborate')
                return $class::where('id',$id)->first();
            return $class::where('id',$id)->whereNull('deleted_at')->first();
        }
    }
    
    public function store(Request $request, $modelName, $id)
    {
        $modelName = strtolower($modelName);

        $this->setColumn($modelName);


        $sharedModel = $this->getModel($modelName, $id);
        
        if (!$sharedModel) {
            return $this->sendError("Nothing found for given Id.");
        }
        
        $loggedInProfileId = $request->user()->profile->id;
        
        //$sharedModel->additionalPayload = ['sharedBy'=>'profile:small:' . $loggedInProfileId,'shared'=>$modelName . ":" . $id];

        $class = "\\App\\Shareable\\" . ucwords($modelName);
        
        $share = new $class();
        $exists = $share->where('profile_id', $loggedInProfileId)
            ->where($this->column, $sharedModel->id)->whereNull('deleted_at')->first();
        
        if ($exists) {
            return $this->sendError("You have already shared this.");
        }

        $this->model = $share->create(['profile_id' => $loggedInProfileId, $this->column => $sharedModel->id,
            'privacy_id' => $request->input('privacy_id') ,'content' => $request->input('content')]);

        $this->model->additionalPayload = ['sharedBy' => 'profile:small:' . $loggedInProfileId,
            $modelName => $modelName . ":" . $id, 'shared' => "shared:$modelName:" . $this->model->id
        ];
        
        if($sharedModel->company_id){
            $this->model->relatedKey = ['company' => 'company:small:' . $sharedModel->company_id];
        } elseif($sharedModel->profile_id){
            $this->model->relatedKey = ['profile' => 'profile:small:' . $sharedModel->profile_id];
        }
        
        //push to feed
        event(new NewFeedable($this->model, $request->user()->profile));
        
        //add model subscriber
        event(new Create($this->model,$request->user()->profile));
    
        if($loggedInProfileId != $sharedModel->profile_id){
            $this->model->profile_id = $sharedModel->profile_id;
            event(new Share($this->model,$request->user()->profile));
        }
        //Tag profile and notify user
        $content = $request->input('content');
        $tags = $this->hasTags($content);

        if(isset($tags) && $tags != 0){
            event(new Tag($this->model, $request->user()->profile, $this->model->content));
        }
        
        return $this->sendResponse();
    }
    public function update(Request $request,$modelName, $postId){
        $content = $request->get('content');
        $tags = $this->hasTags($content);


        $modelName = strtolower($modelName);
        $this->setColumn($modelName);
        $sharedModel = $this->getModel($modelName, $postId);
        if (!$sharedModel) {
            return $this->sendError("Nothing found for given Id.");
        }
        $loggedInProfileId = $request->user()->profile->id;
        $class = "\\App\\Shareable\\" . ucwords($modelName);
        $share = new $class();
        $exists = $share->where('profile_id', $loggedInProfileId)
            ->where($this->column, $sharedModel->id)->whereNull('deleted_at')->first();
        if(!$exists){
            return $this->sendError("Model doesn't exist");
        }
        $this->model = $share->where('profile_id', $loggedInProfileId)
            ->where($this->column, $sharedModel->id)->whereNull('deleted_at')->update(['content' => $content]);
        $this->model = $share->where('profile_id', $loggedInProfileId)
            ->where($this->column, $sharedModel->id)->whereNull('deleted_at')->first();
        $this->model->additionalPayload = ['sharedBy' => 'profile:small:' . $loggedInProfileId,
            $modelName => $modelName . ":" . $postId, 'shared' => "shared:$modelName:" . $this->model->id
        ];
        if($sharedModel->company_id){
            $this->model->relatedKey = ['company' => 'company:small:' . $sharedModel->company_id];
        } elseif($sharedModel->profile_id){
            $this->model->relatedKey = ['profile' => 'profile:small:' . $sharedModel->profile_id];
        }
        $this->model->addToCacheV2();
        Redis::set("shared:" . strtolower($modelName) . ":" . $this->model->id,$this->model->toJson());
        if(isset($tags) && $tags != 0){
            event(new Tag($this->model, $request->user()->profile, $this->model->content));
        }
        return $this->sendResponse();
    }
    
    public function delete(Request $request, $modelName, $id)
    {
        $class = "\\App\\Shareable\\" . ucwords($modelName);
        $this->setColumn($modelName);
        $loggedInId = $request->user()->profile->id;
        $this->model = $class::where($this->column, $id)->where('profile_id', $loggedInId)->whereNull('deleted_at')->first();
        
        if (!$this->model) {
            return $this->sendError("Model not found.");
        }
        $this->model = $this->model->delete() ? true : false;
        return $this->sendResponse();
    }

    public function show(Request $request, $modelName, $id,$modelId)
    {
        //photo
        $this->model = [];
        $modelName = strtolower($modelName);
        $this->setColumn($modelName);

        $loggedInProfileId = $request->user()->profile->id;

        $class = "\\App\\Shareable\\" . ucwords($modelName);

        $share = new $class();
        $exists = $share->where('id', $id)->whereNull('deleted_at')->first();

        $sharedModel = $this->getModel($modelName, $modelId);

        if (!$sharedModel) {
            return $this->sendError("Nothing found for given Id.");
        }

        if (!$exists) {
            return $this->sendError("Nothing found for given shared model.");
        }
        $this->model['shared'] = $exists;
        $this->model['sharedBy'] = json_decode(Redis::get('profile:small:' . $exists->profile_id));
        $this->model['type'] = $modelName;
        if($sharedModel->company_id){
            $this->model['company'] = json_decode(Redis::get('company:small:' . $sharedModel->company_id));
        } elseif($sharedModel->profile_id){
            $this->model['profile'] = json_decode(Redis::get('profile:small:' . $sharedModel->profile_id));
        }
        $this->model[$modelName] = $sharedModel;
        $this->model['meta']= $exists->getMetaFor($loggedInProfileId);
        return $this->sendResponse();

    }
    public function showProduct(Request $request,$id, $modelId)
    {
        $modelName = "product";
        $this->setColumn($modelName);

        $loggedInProfileId = $request->user()->profile->id;

        $class = "\\App\\Shareable\\" . ucwords($modelName);

        $share = new $class();
        $exists = $share->where('id', $id)->whereNull('deleted_at')->first();
        $sharedModel = $this->getModel($modelName, $modelId);
        if (!$sharedModel) {
            return $this->sendError("Nothing found for given Id.");
        }

        if (!$exists) {
            return $this->sendError("Nothing found for given shared model.");
        }
        $this->model['shared'] = $exists;
        $this->model['sharedBy'] = json_decode(Redis::get('profile:small:' . $exists->profile_id));
        $this->model['type'] = $modelName;
        if($sharedModel->company_id){
            $this->model['company'] = json_decode(Redis::get('company:small:' . $sharedModel->company_id));
        } elseif($sharedModel->profile_id){
            $this->model['profile'] = json_decode(Redis::get('profile:small:' . $sharedModel->profile_id));
        }
        $this->model[$modelName] = $sharedModel;
        $this->model['meta']= $exists->getMetaFor($loggedInProfileId);
        return $this->sendResponse();
    }


    public function productStore(Request $request, $id)
    {
        $modelName = 'product';
        $modelName = strtolower($modelName);

        $this->setColumn($modelName);


        $sharedModel = PublicReviewProduct::where('id',$id)->whereNull('deleted_at')->where('is_active',1)->first();

        if (!$sharedModel) {
            return $this->sendError("Nothing found for given Id.");
        }

        $loggedInProfileId = $request->user()->profile->id;

        //$sharedModel->additionalPayload = ['sharedBy'=>'profile:small:' . $loggedInProfileId,'shared'=>$modelName . ":" . $id];

        $class = "\\App\\Shareable\\" . ucwords($modelName);

        $share = new $class();
        $exists = $share->where('profile_id', $loggedInProfileId)
            ->where($this->column, $sharedModel->id)->whereNull('deleted_at')->first();

        if ($exists) {
            return $this->sendError("You have already shared this.");
        }
        $share->profile_id = $loggedInProfileId;
        $share->product_id = $sharedModel->id;
        $share->privacy_id = $request->input('privacy_id');
        $share->created_at = Carbon::now()->toDateTimeString();
        $share->updated_at = Carbon::now()->toDateTimeString();
        $share->content = $request->input('content');
//        $this->model = $share->insert(['profile_id' => $loggedInProfileId, $this->column => $sharedModel->id,
//            'privacy_id' => $request->input('privacy_id') ,'content' => $request->input('content')]);
        $share->save();
        $this->model = $share;
        $matches = $this->hasHashtags($this->model);
        if(count($matches)) {
            $this->createHashtag($matches,'App\Shareable\Product',$this->model->id);
        }
        $this->model->additionalPayload = ['sharedBy' => 'profile:small:' . $loggedInProfileId,
            $modelName => $modelName . ":" . $id, 'shared' => "shared:$modelName:" . $this->model->id
        ];
        $this->model->relatedKey = ['profile' => 'profile:small:' . $sharedModel->profile_id];
        $content = $request->input('content');
        $tags = $this->hasTags($content);

        if(isset($tags) && $tags != 0){
            event(new Tag($this->model, $request->user()->profile, $this->model->content));
        }
        $this->model->additionalPayload = ['sharedBy' => 'profile:small:' . $loggedInProfileId,
            $modelName => "public-review/product" . ":" . $id, 'shared' => "shared:$modelName:" . $this->model->id
        ];

        $this->model->relatedKey = [];

        //push to feed
        event(new NewFeedable($this->model, $request->user()->profile));

        //add model subscriber
        event(new Create($this->model,$request->user()->profile));

        return $this->sendResponse();
    }

    public function productShareDelete(Request $request,$id)
    {
        $modelName = 'product';
        $class = "\\App\\Shareable\\" . ucwords($modelName);
        $this->setColumn($modelName);
        $loggedInId = $request->user()->profile->id;
        $this->model = $class::where($this->column, $id)->where('profile_id', $loggedInId)->whereNull('deleted_at')->first();

        if (!$this->model) {
            return $this->sendError("Model not found.");
        }
        $this->model = $this->model->delete() ? true : false;
        $matches = $this->hasHashtags($this->model);
            if(count($matches)) {
                $this->deleteExistingHashtag('App\Shareable\Product',$this->model->id);
            }
        if($this->model)
            Payload::where("payload->product","public-review/product:".$id)->where("payload->sharedBy","profile:small:".$loggedInId)->update(['deleted_at'=>\Carbon\Carbon::now()->toDateTimeString()]);
        return $this->sendResponse();

    }

    public function productUpdate(Request $request, $modelId){
        $content = $request->input('content');
        $modelName = 'product';
        $class = "\\App\\Shareable\\".ucwords($modelName);
        $this->setColumn($modelName);
        $loggedInId = $request->user()->profile->id;
        $this->model = $class::where($this->column,$modelId)->where('profile_id',$loggedInId)->whereNull('deleted_at');
        if(!$this->model->exists()){
            return $this->sendError("Model not found.");
        }
        $this->model->update(['content'=>$content]);
        $matches = $this->hasHashtags($this->model);
            $this->deleteExistingHashtag('App\Shareable\Product',$this->model->id);
            if(count($matches)) {
                $this->createHashtag($matches,'App\Shareable\Product',$this->model->id);
            }
        $this->model = $class::where($this->column,$modelId)->where('profile_id',$loggedInId)->whereNull('deleted_at')->first();
        $this->model->addToCache();
        $this->model->addToCacheV2();
        $tags = $this->hasTags($content);

        if(isset($tags) && $tags != 0){
            event(new Tag($this->model, $request->user()->profile, $this->model->content));
        }
        return $this->sendResponse();
    }

    public function hasHashtags($data) 
    {

        $totalMatches = [];
        if(preg_match_all('/#[A-Za-z0-9_]{1,50}/i',$data->content,$matches)) {
            $totalMatches = array_merge($totalMatches,$matches[0]);
        }
        return $totalMatches;
    }

}