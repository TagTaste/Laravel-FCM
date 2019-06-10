<?php

namespace App\Shareable;

use App\Channel\Payload;
use App\Comment;
use App\Interfaces\CommentNotification;
use App\Privacy;
use App\Traits\CachedPayload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\GetTags;

class Share extends Model implements CommentNotification
{
    use SoftDeletes, CachedPayload, GetTags;
     
    protected $fillable = ['profile_id','privacy_id','content'];
    protected $visible = ['id','profile_id','created_at','content'];
    
    public function __construct($attributes = [])
    {
        $class = strtolower(class_basename($this));
        $this->table = $class . "_shares";
        $column = strtolower(class_basename($this)).'_id';
        $this->fillable[] = $column;
        parent::__construct($attributes);
       
    }
    
    public static function boot()
    {
        static::created(function($model){
            $model->addToCache();
        });
        static::updated(function($model){
            $model->addToCache();
        });
        static::deleted(function($model){
            $model->payload->delete();
        });
    }
    
    public function addToCache()
    {
        $model = class_basename($this);
        \Redis::set("shared:" . strtolower($model) . ":" . $this->id,$this->toJson());
    }
    
    public function payload()
    {
        return $this->belongsTo(Payload::class,'payload_id');
    }
    
    public function privacy()
    {
        return $this->belongsTo(Privacy::class);
    }
    
    public function getColumnName()
    {
        return strtolower(class_basename($this)) . "_id";
    }
    
    public static function getSharedAt(Model $model)
    {
        $columnName = strtolower(class_basename($model)) . "_id";
        $shareable = "\\App\\Shareable\\" . class_basename($model);
        $model = $shareable::where($columnName,$model->id)->first();
        return $model !== null && $model->payload !== null ? $model->payload->created_at->toDateTimeString() : null;
    }

    public function comments()
    {
        $tableName = 'comments_'.strtolower(class_basename($this)).'_shares';
        $columnName = strtolower(class_basename($this)).'_share_id';
        return $this->belongsToMany(Comment::class,$tableName,$columnName,'comment_id');
    }
    
    public function getCacheKey() : array
    {
        $name = strtolower(class_basename($this));
        $key  =  "shared:$name:" . $this->id;
        
        if(!\Redis::exists($key))
        {
            \Redis::set($key,$this->toJson());
        }
        return [$name => $key];
    }
    
    public function getRelatedKey()
    {
        if(empty($this->relatedKey)){
            throw new \Exception("Related key not specified for shareable.");
        }
        return $this->relatedKey;
    }
    
    public function getCommentNotificationMessage() : string
    {
        return "New comment on your share!";
    }

    public function getContentAttribute($value)
    {
        $profiles = $this->getTaggedProfiles($value);

        if($profiles){
            $value = ['text'=>$value,'profiles'=>$profiles];
        }
        return $value;
    }
}
