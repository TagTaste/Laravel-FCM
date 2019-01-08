<?php

namespace App\PublicReviewProduct;

use Illuminate\Database\Eloquent\Model;

class ReviewHeader extends Model
{

    //header_selection_type instruction = 0,OVERALL PRODUCT EXPERIENCE = 2 , remainig = 1

    protected $table = 'public_review_question_headers';

    protected $fillable = ['header_type','is_active','global_question_id','created_at','updated_at','header_info','header_selection_type'];

    protected $visible = ['id','header_type','is_active','global_question_id','header_info','header_selection_type'];

    public function getHeaderInfoAttribute($value)
    {
        if(isset($value))
        {
            $value = json_decode($value,true);
            $images = isset($value['images']) ? $value['images'] : [];
            $imageValues = [];
            foreach ($images as $image)
            {
                \Log::info($image);
                $imageValues[] = $image;
            }
            return ['text'=> isset($value['text']) ? $value['text'] : null,"images"=>$imageValues , "video_link"=>isset($value['video_link']) ? $value['video_link'] : null ];
        }
    }
}
