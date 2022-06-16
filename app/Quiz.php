<?php

namespace App;

use App\Interfaces\Feedable;
use App\Traits\CachedPayload;
use App\Traits\IdentifiesOwner;
use App\Traits\IdentifiesContentIsReported;
use App\Traits\HashtagFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Redis;

class Quiz extends Model implements Feedable
{

    use IdentifiesOwner, CachedPayload, SoftDeletes, IdentifiesContentIsReported, HashtagFactory;

    protected $table = "quizes";
    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    public $incrementing = false;


    protected $fillable = ["id","profile_id","company_id","title","description","image_meta","form_json","payload_id","updated_by","expired_at","state","deleted_at","replay"];
    
    protected $with = ['profile','company'];
    

    protected $cast = [
        "form_json" => 'array',
    ];


    public function addToCache()
    {
        $data = [
            'id' => $this->id,
            'profile_id' => $this->profile_id,
            'company_id' => $this->company_id,
            'payload_id' => $this->payload_id,
            'title' => $this->title,
            'description' => $this->description,
            'image_meta' => json_decode($this->image_meta),
            'state' => $this->state,
            'expired_at' => $this->expired_at,
            'deleted_at' => $this->deleted_at->toDateTimeString(),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            'replay' => $this->replay,
        ];

        Redis::set("quizes:" . $this->id, json_encode($data));
    }

    public function removeFromCache()
    {
        Redis::del("quizes:" . $this->id);
    }

    public function profile()
    {
        return $this->belongsTo(\App\Recipe\Profile::class);
    }

    public function company()
    {
        return $this->belongsTo(\App\Recipe\Company::class);
    }

  
}
