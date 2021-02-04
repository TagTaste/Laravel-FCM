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

    protected $casts = [
        "form_json" => 'array',
        "image_meta" => 'booleans',
        'video_meta' => 'array'
    ];

    public function getImageMetaAttribute($value){
        return json_decode($value,true);
    }

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
    
    public function comments()
    {
        return $this->belongsToMany('App\Comment','comment_surveys','surveys_id','comment_id');
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
        $key = "meta:surveys:likes:" . $this->id;
        $meta['likeCount'] = Redis::sCard($key);
        $meta['commentCount'] = $this->comments()->count();
        $meta['answerCount'] = 40;        
        
        //NOTE NIKHIL : Add answer count in here like poll count 
        // $meta['vote_count'] = \DB::table('poll_votes')->where('poll_id',$this->id)->count();
        return $meta;
    }

    public function getPreviewContent()
    {
        $data = [];
        $data['modelId'] = $this->id;
        $data['deeplinkCanonicalId'] = 'share_feed/'.$this->id;
        $data['title'] = substr($this->title,0,65);
        $data['description'] = "by ".$this->owner->name;
        $data['ogTitle'] = "Survey: ".substr($this->title,0,65);
        $data['ogDescription'] = "by ".$this->owner->name;
        die;
        $images = $this->image_meta != null ? $this->image_meta : null;
        $data['cardType'] = isset($images) ? 'summary_large_image':'summary';
        $data['ogImage'] = 'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/share/poll_feed.png';
        $data['ogUrl'] = env('APP_URL').'/survey/'.$this->id;
        $data['redirectUrl'] = env('APP_URL').'/survey/'.$this->id;

        return $data;
    }

    public function getSeoTags() : array
    {
        $title = "TagTaste | ".$this->title." | Survey";
        $description = "";
        if (!is_null($this->description)) {
            $description = substr(htmlspecialchars_decode($this->description),0,160)."...";
        } else {
            $description = "World's first online community for food professionals to discover, network and collaborate with each other.";
        }

        $seo_tags = [
            "title" => $title,
            "meta" => array(
                array(
                    "name" => "description",
                    "content" => $description,
                ),
                array(
                    "name" => "keywords",
                    "content" => "survey, surveys, online survey, food survey, TagTaste survey",
                )
            ),
            "og" => array(
                array(
                    "property" => "og:title",
                    "content" => $title,
                ),
                array(
                    "property" => "og:description",
                    "content" => $description,
                ),
                array(
                    "property" => "og:image",
                    "content" => "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/share/poll_feed.png",
                )
            ),
        ];
        return $seo_tags;
    }
}
