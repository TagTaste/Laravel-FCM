<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\IdentifiesOwner;

class PublicReviewProductGetSample extends Model
{
	use IdentifiesOwner, SoftDeletes;

    protected $table = 'public_review_product_get_sample';

    protected $fillable = ['profile_id','product_id','count','created_at','updated_at','deleted_at'];

    protected $visible = ['id','profile_id','product_id','count','profile','publicReviewProduct','created_at','updated_at','deleted_at'];

    protected $append = ['profile', 'publicReviewProduct'];

    /**
     * Which profile created the collaboration project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function profile()
    {
        return $this->belongsTo(\App\Profile::class, 'profile_id', 'id');
    }

    /**
     * Which profile created the collaboration project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function publicReviewProduct()
    {
        return $this->belongsTo(\App\PublicReviewProduct::class, 'product_id', 'id');
    }

}
