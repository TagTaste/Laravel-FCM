<?php

namespace App\Http\Controllers\Api\V2;

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
use App\Http\Controllers\Api\ShareController as BaseController;

class ShareController extends BaseController
{
    use CheckTags;
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
            $class = "\\App\\V2\\" . ucfirst ($modelName);
            return $class::where('id',$id)->whereNull('deleted_at')->first();
        }
    }

    public function show(Request $request, $modelName, $id, $modelId)
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

        $sharedModel = $sharedModel->toArray();
        foreach ($sharedModel as $key => $value) {
            if (is_null($value) || $value == '')
                unset($sharedModel[$key]);
        }

        $this->model['shared'] = $exists;
        $this->model['sharedBy'] = json_decode(Redis::get('profile:small:' . $exists->profile_id.':V2'));
        $this->model['type'] = $modelName;
        
        if (isset($shoutout['company_id'])) {
            $this->model['company'] = json_decode(Redis::get('company:small:' . $sharedModel->company_id.':V2'));
        } else if (isset($shoutout['profile_id'])) {
            $this->model['profile'] = json_decode(Redis::get('profile:small:' . $sharedModel->profile_id.':V2'));
        }
        $this->model[$modelName] = $sharedModel;
        $this->model['meta']= $exists->getMetaForV2Shared($loggedInProfileId);
        return $this->sendResponse();
    }
}