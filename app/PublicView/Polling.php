<?php

namespace App\PublicView;

use App\Traits\IdentifiesOwner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Polling as BasePolling;

class Polling extends BasePolling
{
    use IdentifiesOwner, SoftDeletes;

    protected $with = ['profile','company'];

    protected $appends = ['options','owner'];
    protected $visible = ['id','title','profile_id','company_id','profile','company','created_at',
        'deleted_at','updated_at','is_expired','expired_time','options','owner'];

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
    public function getMetaForPublic()
    {
        $meta = [];
        $meta['is_expired'] = $this->is_expired;
        $meta['vote_count'] = \DB::table('poll_votes')->where('poll_id',$this->id)->count();
        return $meta;
    }

    public function getOptionsAttribute(){
        $options = \App\PollingOption::where('poll_id',$this->id)->get();
        \Log::info($options);
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
        $data['description'] = "by ".$this->profile->name;
        $data['ogTitle'] = substr($this->title,0,65);
        $data['ogDescription'] = "by ".$this->profile->name;
        $images = isset($this->profile->image) ? $this->profile->image : null;
        $data['cardType'] = isset($images) ? 'summary_large_image':'summary';
        $data['ogImage'] = isset($images) ? $images:'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/share/share-collaboration-big.png';
        $data['ogUrl'] = env('APP_URL').'/polling/'.$this->id;
        $data['redirectUrl'] = env('APP_URL').'/polling/'.$this->id;

        return $data;

    }

    public function getOwnerAttribute()
    {
        return $this->owner();
    }
}
