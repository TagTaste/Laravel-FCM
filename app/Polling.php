<?php

namespace App;

use App\Channel\Payload;
use App\Interfaces\Feedable;
use App\Traits\CachedPayload;
use App\Traits\IdentifiesOwner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Polling extends Model implements Feedable
{
    use IdentifiesOwner, CachedPayload, SoftDeletes;

    protected $table = 'poll_questions';

    protected $fillable = ['title','profile_id','company_id','is_expired','expired_time','privacy_id','payload_id'];

    protected $with = ['profile','company','options'];


    protected $visible = ['id','title','profile_id','company_id','profile','company','options','created_at',
        'deleted_at','updated_at','is_expired','expired_time','privacy_id','payload_id'];

    public static function boot()
    {
        self::created(function($model){
            $model->addToCache();
            });

        self::updated(function($model){
            $model->addToCache();
            //update the search
        });
    }

    public function addToCache()
    {
        \Redis::set("polling:" . $this->id,$this->makeHidden([])->toJson());

    }

    public function removeFromCache()
    {
        \Redis::del("polling:" . $this->id);
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

        if($this->collaborate_type == 'product-review')
        {
            $key = "meta:collaborate:likes:" . $this->id;
            $meta['hasLiked'] = \Redis::sIsMember($key,$profileId) === 1;
            $meta['likeCount'] = \Redis::sCard($key);

            $meta['commentCount'] = $this->comments()->count();
            $peopleLike = new PeopleLike();
            $meta['peopleLiked'] = $peopleLike->peopleLike($this->id, 'collaborate' ,request()->user()->profile->id);
            $meta['shareCount']=\DB::table('collaborate_shares')->where('collaborate_id',$this->id)->whereNull('deleted_at')->count();
            $meta['sharedAt']= \App\Shareable\Share::getSharedAt($this);

            $this->interestedCount = \DB::table('collaborate_applicants')->where('collaborate_id',$this->id)->distinct()->get(['profile_id'])->count();
            $meta['interestedCount'] = $this->interestedCount;
            $meta['isAdmin'] = $this->company_id ? \DB::table('company_users')
                ->where('company_id',$this->company_id)->where('user_id',request()->user()->id)->exists() : false ;
            return $meta;
        }

        $this->setInterestedAsProfiles($meta,$profileId);

        $meta['isShortlisted'] = \DB::table('collaborate_shortlist')->where('collaborate_id',$this->id)->where('profile_id',$profileId)->exists();

        $key = "meta:collaborate:likes:" . $this->id;
        $meta['hasLiked'] = \Redis::sIsMember($key,$profileId) === 1;
        $meta['likeCount'] = \Redis::sCard($key);

        $meta['commentCount'] = $this->comments()->count();
        $peopleLike = new PeopleLike();
        $meta['peopleLiked'] = $peopleLike->peopleLike($this->id, 'collaborate' ,request()->user()->profile->id);
        $meta['shareCount']=\DB::table('collaborate_shares')->where('collaborate_id',$this->id)->whereNull('deleted_at')->count();
        $meta['sharedAt']= \App\Shareable\Share::getSharedAt($this);

        $meta['interestedCount'] = (int) \Redis::hGet("meta:collaborate:" . $this->id,"applicationCount") ?: 0;
        $meta['isAdmin'] = $this->company_id ? \DB::table('company_users')
            ->where('company_id',$this->company_id)->where('user_id',request()->user()->id)->exists() : false ;

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
            'image' => null,
            'collaborate_type' => $this->collaborate_type
        ];
    }

    public function getRelatedKey() : array
    {
        if(empty($this->relatedKey) && $this->company_id === null){
            return ['profile'=>'profile:small:' . $this->profile_id];
        }
        return ['company'=>'company:small:' . $this->company_id];
    }

}
