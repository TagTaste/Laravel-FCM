<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\IdentifiesOwner;

class Advertisements extends Model
{
	use IdentifiesOwner, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'video',
        'youtube_url',
        'link',
        'image',
        'cities',
        'payload',
        'model',
        'model_id',
        'profile_id',
        'company_id',
        'is_expired',
        'created_at',
        'updated_at',
        'deleted_at',
        'expired_at'
    ];

    protected $visible = [
    	'id',
    	'title',
        'description',
        // 'video',
        // 'youtube_url',
        'link',
        'image',
        // 'cities',
        'payload',
        'model',
        'model_id',
        // 'profile_id',
        // 'company_id',
        'is_expired',
        'created_at',
        'updated_at',
        'deleted_at',
        'expired_at',
        'profile',
        'company'
    ];

    protected $with = ['profile', 'company'];

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
}
