<?php

namespace App\PublicReviewProduct;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'public_product_user_review';

    protected $fillable = ['key','value','leaf_id','question_id','header_id',
        'product_id','profile_id','intensity','current_status','created_at','updated_at'];

    protected $visible = ['id','key','value','leaf_id','question_id','header_id','product_id','profile_id',
        'intensity','current_status','created_at','updated_at','profile'];

    protected $with = ['profile'];

    //current_status 0 - to be notified, 1 - notified, 2 - in progress, 3 - completed

    public function profile()
    {
        return $this->belongsTo(\App\Recipe\Profile::class);
    }


}
