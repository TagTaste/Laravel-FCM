<?php

namespace App\Shareable;

use App\Channel\Payload;
use App\PeopleLike;
use App\PublicReviewProduct;
use App\PublicReviewProduct\Review;
use App\Shareable\Share;


class Product extends Share
{
    protected $fillable = ['profile_id','product_id','payload_id','privacy_id'];
    protected $visible = ['id','profile_id','product_id','created_at'];

    protected $with = ['product'];

    public function __construct($attributes = [])
    {
        $this->table = "public_review_product_shares";
        $column = strtolower(class_basename($this)).'_id';
        $this->fillable[] = $column;
    }

    public static function boot()
    {
        static::deleted(function($model){
            $model->payload->delete();
        });
    }

    public function product()
    {
        return $this->belongsTo(\App\PublicReviewProduct::class,'product_id');
    }

    public function payload()
    {
        return $this->belongsTo(Payload::class,'payload_id');
    }

    public function getRelatedKey()
    {
        return [];
    }

    public function getMetaFor() : array
    {
        $product = PublicReviewProduct::where('id',$this->product_id)->whereNull('deleted_at')->first();
        $meta = [];
        $meta['overall_rating'] = $this->getOverallRatingAttribute($product);
        $meta['current_status'] = $this->getCurrentStatusAttribute($product,request()->user()->profile->id);
        return $meta;
    }

    public function getOverallRatingAttribute($product)
    {
        $header = PublicReviewProduct\ReviewHeader::where('global_question_id',$product->global_question_id)->where('header_selection_type',2)->first();
        if($header != null)
        {
            $overallPreferances = \DB::table('public_product_user_review')->where('current_status',2)->where('product_id',$product->id)->where('header_id',$header->id)->where('select_type',5)->sum('leaf_id');
            $userCount = \DB::table('public_product_user_review')->where('current_status',2)->where('product_id',$product->id)->where('header_id',$header->id)->where('select_type',5)->get()->count();
            $question = \DB::table('public_review_questions')->where('header_id',$header->id)->where('questions->select_type',5)->first();
            $question = json_decode($question->questions);
            $option = isset($question->option) ? $question->option : [];
            $meta = [];
            $meta['max_rating'] = count($option);
            $meta['overall_rating'] = $userCount >= 10 ? $overallPreferances/$userCount : null;
            $meta['count'] = $userCount;
            $meta['color_code'] = $userCount >= 10 ? $this->getColorCode(floor($meta['overall_rating'])) : null;
            return $meta;
        }

        return null;
    }

    public function getCurrentStatusAttribute($product,$profileId)
    {
        //bad me change krna h
        $currentStatus = \DB::table('public_product_user_review')->where('product_id',$product->id)->where('profile_id',$profileId)->where('current_status',2)->exists();
        if($currentStatus)
            return 2;
        $currentStatus = \DB::table('public_product_user_review')->where('product_id',$product->id)->where('profile_id',$profileId)->where('current_status',1)->exists();
        if($currentStatus)
            return 1;
        return 0;
    }

    protected function getColorCode($value)
    {
        if($value == 0 || is_null($value))
            return null;
        switch ($value) {
            case 1:
                return '#8C0008';
                break;
            case 2:
                return '#D0021B';
                break;
            case 3:
                return '#C92E41';
                break;
            case 4:
                return '#E27616';
                break;
            case 5:
                return '#AC9000';
                break;
            case 6:
                return '#7E9B42';
                break;
            case 7:
                return '#577B33';
                break;
            default:
                return '#305D03';
        }
    }

    public function getFeedMeta()
    {
        $product = PublicReviewProduct::where('id',$this->product_id)->whereNull('deleted_at')->first();
        $meta = [];
        $overRating = $this->getOverallRatingAttribute($product);
        $meta['current_status'] = $this->getCurrentStatusAttribute($product,request()->user()->profile->id);
        if(is_null($overRating))
            $metaString = null;
        else
        {
            $meta['overall_rating'] = "{";
            foreach($overRating as $key => $value){
                if($key == "color_code")
                    $meta['overall_rating'] .= "\"$key\":\"$value\"";
                else
                    $meta['overall_rating'] .= "\"$key\":\"$value\",";

            }
            $meta['overall_rating'] .= "}";
        }
        return $meta;
    }

}
