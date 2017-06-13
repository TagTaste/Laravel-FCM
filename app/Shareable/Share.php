<?php

namespace App\Shareable;

use App\Channel\Payload;
use App\Comment;
use App\Privacy;
use App\Traits\CachedPayload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Share extends Model
{
    use SoftDeletes, CachedPayload;
     
    protected $fillable = ['profile_id','privacy_id'];
    protected $visible = ['id','profile_id','created_at'];
    
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
        static::deleted(function($model){
            $model->payload->delete();
        });
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
}
