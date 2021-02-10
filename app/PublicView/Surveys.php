<?php

namespace App\PublicView;

use App\Traits\IdentifiesOwner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Surveys as BaseSurveys;
use Illuminate\Support\Facades\Redis;

class Surveys extends BaseSurveys
{
    use IdentifiesOwner, SoftDeletes;

    protected $with = ['profile','company'];

    protected $appends = ['owner'];
    protected $visible = ["id","profile_id","company_id","privacy_id","title","description","image_meta","video_meta","profile_updated_by","invited_profile_ids","expired_at","is_active","state","deleted_at","published_at","owner","profile","company"];

    // protected $visible = ['id','title','profile_id','company_id','profile','company','created_at',
        // 'deleted_at','updated_at','is_expired','expired_time','options','owner','image_meta','type','preview'];

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

    /**
     * @param int $profileId
     * @return array
     */
    public function getMetaForPublic()
    {
        $meta = [];
        $meta['expired_at'] = $this->expired_at;
        $meta['likeCount'] = 20;
        $meta['commentCount'] = 30;
        $meta['answerCount'] = 40;    

        // $meta['vote_count'] = \DB::table('poll_votes')->where('poll_id',$this->id)->count();
        // $key = "meta:surveys:likes:" . $this->id;
        // $meta['likeCount'] = Redis::sCard($key);
        // $meta['commentCount'] = $this->comments()->count();
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
        $images = $this->image_meta != null ? $this->image_meta : null;
        $data['cardType'] = isset($images) ? 'summary_large_image':'summary';
        $data['ogImage'] = 'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/share/poll_feed.png';
        $data['ogUrl'] = env('APP_URL').'/surveys/'.$this->id;
        $data['redirectUrl'] = env('APP_URL').'/surveys/'.$this->id;

        return $data;
    }

    public function getOwnerAttribute()
    {
        return $this->owner();
    }
}
