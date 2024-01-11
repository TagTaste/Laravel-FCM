<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PublicReviewUserTiming extends Model
{
    protected $table = 'public_review_user_timings';

    protected $fillable = ['profile_id','product_id','current_status','start_review','end_review','duration','is_flag','created_at','updated_at'];

}
