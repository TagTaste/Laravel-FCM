<?php

namespace App;

use App\PublicReviewProduct\Review;
use App\PublicReviewProduct\ReviewHeader;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class PublicReviewProductCompany extends Model
{

    use SoftDeletes;

    protected $table = 'public_review_product_companies';

    protected $dates = ['deleted_at'];

    protected $fillable = ['name','description','image','is_active','created_at','updated_at','deleted_at'];

    protected $visible = ['id','name','description','image','is_active','created_at','updated_at','deleted_at'];

}
