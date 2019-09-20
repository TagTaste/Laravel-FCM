<?php

namespace App\Shareable;

use App\Channel\Payload;
use App\PeopleLike;
use App\PublicReviewProduct;
use App\PublicReviewProduct\Review;
use App\Shareable\Share;
use Illuminate\Support\Facades\Redis;


class Product extends Share
{
    protected $fillable = ['profile_id','product_id','payload_id','privacy_id','content'];
    protected $visible = ['id','profile_id','product_id','created_at','content'];

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
        $key = "meta:productShare:likes:" . $this->id;

        $meta['hasLiked'] = Redis::sIsMember($key,request()->user()->profile->id) === 1;
        $meta['likeCount'] = Redis::sCard($key);

        $peopleLike = new PeopleLike();
        $meta['peopleLiked'] = $peopleLike->peopleLike($this->id, 'productShare' ,request()->user()->profile->id);

        $meta['commentCount'] = $this->comments()->count();
        $meta['original_post_meta'] = $product->getMetaFor(request()->user()->profile->id);
        return $meta;
    }

    public function getMetaForV2() : array
    {
        $product = PublicReviewProduct::where('id',$this->product_id)->whereNull('deleted_at')->first();
        $meta = [];
        $meta['overall_rating'] = $this->getOverallRatingAttribute($product);
        $meta['current_status'] = $this->getCurrentStatusAttribute($product,request()->user()->profile->id);
        $key = "meta:productShare:likes:" . $this->id;
        $meta['hasLiked'] = Redis::sIsMember($key,request()->user()->profile->id) === 1;
        $meta['likeCount'] = Redis::sCard($key);
        $meta['commentCount'] = $this->comments()->count();
        $meta['originalPostMeta'] = $product->getMetaFor(request()->user()->profile->id);
        return $meta;
    }

    public function getMetaForV2Shared() : array
    {
        $product = PublicReviewProduct::where('id',$this->product_id)->whereNull('deleted_at')->first();
        $meta = [];
        $meta['overall_rating'] = $this->getOverallRatingAttribute($product);
        $meta['current_status'] = $this->getCurrentStatusAttribute($product,request()->user()->profile->id);
        $key = "meta:productShare:likes:" . $this->id;
        $meta['hasLiked'] = Redis::sIsMember($key,request()->user()->profile->id) === 1;
        $meta['likeCount'] = Redis::sCard($key);
        $meta['commentCount'] = $this->comments()->count();
        $meta['originalPostMeta'] = $product->getMetaFor(request()->user()->profile->id);
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
            $meta['overall_rating'] = $userCount >= 1 ? $overallPreferances/$userCount : null;
            $meta['count'] = $userCount;
            $meta['color_code'] = $userCount >= 1 ? $this->getColorCode(floor($meta['overall_rating'])) : null;
            $product = \App\PublicReviewProduct::where('id',$this->product_id)->whereNull('deleted_at')->first();
            $meta['originalPostMeta'] = $product->getMetaFor(request()->user()->profile->id);
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
    public function like()
    {
        return $this->hasMany(\App\Shareable\Sharelikable\Product::class,'public_review_share_id');
    }

    public function getNotificationContent()
    {
        return [
            'name' => strtolower(class_basename(self::class)),
            'id' => $this->product->id,
            'share_id' => $this->id,
            'content' => null != $this->content ? $this->content : null,
            'image' => null,
            'shared' => true
        ];
    }
    public function getMetaForPublic(){
        $meta = [];
        $key = "meta:productShare:likes:" . $this->id;

        $meta['likeCount'] = Redis::sCard($key);

        $meta['commentCount'] = $this->comments()->count();

        return $meta;
    }
}
