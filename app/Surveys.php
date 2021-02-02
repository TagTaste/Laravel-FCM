<?php

namespace App;
use App\Channel\Payload;
use App\Interfaces\Feedable;
use App\Traits\CachedPayload;
use App\Traits\IdentifiesOwner;
use App\Traits\IdentifiesContentIsReported;
use App\Traits\HashtagFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Redis;

class Surveys extends Model implements Feedable
{

    use IdentifiesOwner, CachedPayload, SoftDeletes, IdentifiesContentIsReported,HashtagFactory;

    protected $table = "surveys";
    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    public $incrementing = false;


    protected $fillable = ["id","profile_id","company_id","privacy_id","title","description","image_meta","video_meta","form_json","profile_updated_by","invited_profile_ids","expired_at","is_active","state","deleted_at","published_at"];

    protected $appends = ['owner','meta'];

    protected $cast = [
        "form_json" => 'json'
    ];

    public function addToCache()
    {
        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            'profile_id'=>$this->profile_id,
        ];
        Redis::set("survey:" . $this->id,json_encode($data));
    }

    public function removeFromCache()
    {
        Redis::del("survey:" . $this->id);
    }

    public function profile()
    {
        return $this->belongsTo(\App\Recipe\Profile::class);
    }

    public function company()
    {
        return $this->belongsTo(\App\Recipe\Company::class);
    }

    public function privacy()
    {
        return $this->belongsTo(Privacy::class);
    }

    public function payload()
    {
        return $this->belongsTo(Payload::class,'payload_id');
    }

    public function getRelatedKey() : array
    {
        if(empty($this->relatedKey) && $this->company_id === null){
            return ['profile'=>'profile:small:' . $this->profile_id];
        }
        return ['company'=>'company:small:' . $this->company_id];
    }


    public function getOwnerAttribute()
    {
        return $this->owner();
    }

    public function getMetaAttribute()
    {
        $meta = [];
        $meta['expired_at'] = $this->expired_at;
        $meta['likeCount'] = 20;
        $meta['commentCount'] = 30;
        $meta['answerCount'] = 40;        
        // $key = "meta:polling:likes:" . $this->id;
        // $meta['likeCount'] = Redis::sCard($key);
        // $meta['commentCount'] = $this->comments()->count();
        // $meta['vote_count'] = \DB::table('poll_votes')->where('poll_id',$this->id)->count();
        return $meta;
    }
}
