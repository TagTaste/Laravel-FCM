<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\IdentifiesOwner;

class ProfileCompiledInfo extends Model
{
	use IdentifiesOwner, SoftDeletes;

    protected $table = 'profile_compiled_info';

    // public $timestamps = true;
    
    protected $fillable = ['profile_id', 'shoutout_post', 'shoutout_shared_post', 'collaborate_post', 'collaborate_share_post', 'photo_post', 'photo_share_post', 'poll_post', 'poll_share_post', 'product_share_post', 'follower_count', 'private_review_count', 'public_review_count', 'created_at','updated_at','deleted_at'
    ];

    protected $visible = ['id', 'profile_id', 'shoutout_post', 'shoutout_shared_post', 'collaborate_post', 'collaborate_share_post', 'photo_post', 'photo_share_post', 'poll_post', 'poll_share_post', 'product_share_post', 'follower_count', 'private_review_count', 'public_review_count', 'created_at','updated_at','deleted_at'
    ];

}
