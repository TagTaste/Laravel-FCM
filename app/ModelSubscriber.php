<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModelSubscriber extends Model
{
    use SoftDeletes;
    protected $fillable = ['model', 'model_id', 'profile_id', 'muted_on'];
    
    public static function updateSubscriberTimestamp(Model $model,$modelId,$profileId)
    {
        $model = get_class($model);
        $subscriber = self::where('model',$model)
            ->where('model_id',$modelId)
            ->where('profile_id',$profileId)
            ->first();
        if(!$subscriber){
            //add new subscriber
            $subscriber = \App\ModelSubscriber::create(
                ['model'=>$model,
                    'model_id'=>$modelId,
                    'profile_id'=>$profileId,
                ]);
        }
    
        $subscriber->touch();
    }
}
