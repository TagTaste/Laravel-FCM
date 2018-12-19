<?php

namespace App\PublicReviewProduct;

use Illuminate\Database\Eloquent\Model;

class ReviewHeader extends Model
{
    protected $table = 'public_review_question_headers';

    protected $fillable = ['header_type','is_active','global_question_id','created_at','updated_at','header_info'];

    protected $visible = ['header_type','is_active','global_question_id','header_info'];
}
