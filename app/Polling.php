<?php

namespace App;

use App\Channel\Payload;
use App\Interfaces\Feedable;
use App\Traits\CachedPayload;
use App\Traits\IdentifiesOwner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Redis;

class Polling extends Model implements Feedable
{
    use IdentifiesOwner, CachedPayload, SoftDeletes;

    protected $table = 'poll_questions';

    protected $fillable = ['title','profile_id','company_id','is_expired','expired_time','privacy_id','payload_id','image_meta'];

    protected $with = ['profile','company'];

    protected $appends = ['options','owner','meta','type'];
    protected $visible = ['id','title','profile_id','company_id','profile','company','created_at',
        'deleted_at','updated_at','is_expired','expired_time','privacy_id','payload_id','options','owner','image_meta','type'];

    public static function boot()
    {
        self::created(function($model){
            $model->addToCache();
            });

        self::updated(function($model){
            $model->addToCache();
            //update the search
        });
        self::deleted(function($model){
            $model->removeFromCache();
            //update the search
        });
    }

    public function addToCache()
    {
        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'options' => $this->getOptionsAttribute(),
            'poll_meta' => $this->meta,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            'profile_id'=>$this->profile_id,
            'image_meta'=>$this->image_meta,
            'type'=>$this->type,
        ];
        Redis::set("polling:" . $this->id,json_encode($data));

    }

    public function removeFromCache()
    {
        Redis::del("polling:" . $this->id);
    }
    /**
     * Which profile created the collaboration project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function profile()
    {
        return $this->belongsTo(\App\Recipe\Profile::class);
    }

    /**
     * Which company created the collaboration project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(\App\Recipe\Company::class);
    }

    public function options()
    {
        return $this->hasMany('App\PollingOption','poll_id');
    }

    /**
     * @param int $profileId
     * @return array
     */
    public function getMetaFor(int $profileId) : array
    {
        $meta = [];
        $meta['self_vote'] = PollingVote::where('poll_id',$this->id)->where('profile_id',$profileId)->first();
        $meta['is_expired'] = $this->is_expired;
        $key = "meta:polling:likes:" . $this->id;
        $meta['hasLiked'] = Redis::sIsMember($key,$profileId) === 1;
        $meta['likeCount'] = Redis::sCard($key);
        $meta['commentCount'] = $this->comments()->count();
        $meta['isAdmin'] = $this->company_id ? \DB::table('company_users')
            ->where('company_id',$this->company_id)->where('user_id',request()->user()->id)->exists() : false ;
        $meta['vote_count'] = \DB::table('poll_votes')->where('poll_id',$this->id)->count();
        return $meta;
    }

    /**
     * @param int $profileId
     * @return array
     */
    public function getMetaForV2(int $profileId) : array
    {
        $meta = [];
//        $options = PollingOption::where('poll_id',$this->id)->get();
//        $count = $options->sum('count');
//        if($count)
//        {
//            foreach ($options as $option)
//                $option->count = ($option->count/$count) * 100;
//        }
//        $meta['options'] = $options;
        $meta['self_vote'] = PollingVote::where('poll_id',$this->id)->where('profile_id',$profileId)->first();
        $meta['is_expired'] = $this->is_expired;
        $key = "meta:polling:likes:" . $this->id;
        $meta['hasLiked'] = Redis::sIsMember($key,$profileId) === 1;
        $meta['likeCount'] = Redis::sCard($key);
        $meta['commentCount'] = $this->comments()->count();
        $meta['isAdmin'] = $this->company_id ? \DB::table('company_users')
            ->where('company_id',$this->company_id)->where('user_id',request()->user()->id)->exists() : false ;
        $meta['vote_count'] = \DB::table('poll_votes')->where('poll_id',$this->id)->count();
        return $meta;
    }

    public function privacy()
    {
        return $this->belongsTo(Privacy::class);
    }

    public function payload()
    {
        return $this->belongsTo(Payload::class,'payload_id');
    }

    public function getCommentNotificationMessage() : string
    {
        return "New comment on " . $this->title . ".";
    }

    public function getNotificationContent()
    {
        return [
            'name' => strtolower(class_basename(self::class)),
            'id' => $this->id,
            'content' => $this->title,
            'image' => $this->image_meta,
            //'collaborate_type' => $this->collaborate_type
        ];
    }

    public function getRelatedKey() : array
    {
        if(empty($this->relatedKey) && $this->company_id === null){
            return ['profile'=>'profile:small:' . $this->profile_id];
        }
        return ['company'=>'company:small:' . $this->company_id];
    }

    public function comments()
    {
        return $this->belongsToMany('App\Comment','comment_pollings','poll_id','comment_id');
    }

    public function getOptionsAttribute(){
        $options = PollingOption::where('poll_id',$this->id)->get();
        $count = $options->sum('count');
        if($count)
        {
            foreach ($options as $option)
                $option->count = ($option->count/$count) * 100;
        }
        return $options;
    }

    public function getPreviewContent()
    {
        $data = [];
        $data['modelId'] = $this->id;
        $data['deeplinkCanonicalId'] = 'share_feed/'.$this->id;
        $data['title'] = substr($this->title,0,65);
        $data['description'] = "by ".$this->owner->name;
        $data['ogTitle'] = "Poll: ".substr($this->title,0,65);
        $data['ogDescription'] = "by ".$this->owner->name;
        $images = $this->image_meta != null ? $this->image_meta : null;
        $data['cardType'] = isset($images) ? 'summary_large_image':'summary';
        $data['ogImage'] = isset($images) ? $images:'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/share/poll_feed.png';
        $data['ogUrl'] = env('APP_URL').'/polling/'.$this->id;
        $data['redirectUrl'] = env('APP_URL').'/polling/'.$this->id;

        return $data;

    }

    public function getOwnerAttribute()
    {
        return $this->owner();
    }

    public function getMetaAttribute()
    {
        $meta = [];
        $meta['is_expired'] = $this->is_expired;
        $key = "meta:polling:likes:" . $this->id;
        $meta['likeCount'] = Redis::sCard($key);
        $meta['commentCount'] = $this->comments()->count();
        $meta['vote_count'] = \DB::table('poll_votes')->where('poll_id',$this->id)->count();
        return $meta;
    }

    public function getPollMetaAttribute()
    {
        return $this->getMetaAttribute();
    }

    public function getImageMetaAttribute($value)
    {
        if($value != null) {
            return json_decode($value);
        }
        return null;
    }

    public function getTypeAttribute()
    {
        return $this->image != null ? 1 : ($this->options[0]['image'] != null ? 2 : 0);
    }
}
