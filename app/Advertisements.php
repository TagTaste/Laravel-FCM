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
    	'youtube_url',
    	'video',
        'created_at',
        'updated_at',
        'deleted_at',
    	'expired_at',
    	'is_expired',
    	'profile_id',
    	'company_id',
    	'link',
    	'image',
    	'cities'
    ];

    protected $visible = [
    	'id',
    	'title',
    	'description',
    	'youtube_url',
    	'video',
    	'created_at',
        'updated_at',
        'deleted_at',
    	'expired_at',
    	'is_expired',
    	'profile_id',
    	'company_id',
    	'link',
    	'image',
    	'cities'
    ];

}
