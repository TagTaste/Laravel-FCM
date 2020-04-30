<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;

class MetaController extends Controller
{
    private $column = "_id";

    private $models = [
        'collaborate'=> \App\Recipe\Collaborate::class,
        'collaborates'=> \App\Recipe\Collaborate::class,
        'recipe' => \App\Recipe::class,
        'recipes' => \App\Recipe::class,
        'profile' => \App\Recipe\Profile::class,
        'people' => \App\Recipe\Profile::class,
        'company' => \App\Recipe\Company::class,
        'companies' => \App\Recipe\Company::class,
        'job' => \App\Recipe\Job::class,
        'jobs' => \App\Recipe\Job::class,
        'product' => \App\PublicReviewProduct::class
    ];

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

    public function getMeta(Request $request, $modelName, $modelId)
    {
        $this->model = [];
        $modelName = strtolower($modelName);
        $loggedInProfileId = $request->user()->profile->id;
        $model = $this->getModel($modelName, $modelId);
        if (!$model) {
            return $this->sendError("Nothing found for given Id.");
        }
        $this->model = $model->getMetaFor($loggedInProfileId);
        return $this->sendResponse();
    }

    public function getSharedMeta(Request $request, $modelName, $id,$modelId)
    {
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
        $this->model = $exists->getMetaFor($loggedInProfileId);
        return $this->sendResponse();
    }
}
