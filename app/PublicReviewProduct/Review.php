<?php

namespace App\PublicReviewProduct;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'public_product_user_review';

    protected $fillable = ['key','value','leaf_id','question_id','header_id','select_type',
        'product_id','profile_id','intensity','current_status','created_at','updated_at','meta'];

    protected $visible = ['id','key','value','leaf_id','question_id','header_id','select_type','product_id','profile_id',
        'intensity','current_status','created_at','updated_at','profile','review_meta','comment_count','review_comment','meta'];

    protected $with = ['profile'];

    protected $appends = ['review_meta','comment_count','review_comment'];

    public function profile()
    {
        return $this->belongsTo(\App\Recipe\Profile::class);
    }

    public function getReviewMetaAttribute()
    {
        $meta = [];
        $meta['max_rating'] = 8;
        $meta['user_rating'] = isset($this->leaf_id) ? $this->leaf_id : null;
        $meta['color_code'] = $this->getColorCode(floor($meta['user_rating']));
        return $meta;
    }

    public function getCommentCountAttribute()
    {
        return \DB::table('comments_public_review')->where('public_review_id',$this->id)->count();
    }

    public function getReviewCommentAttribute()
    {
        $comment = \DB::table('public_product_user_review')->where('product_id',$this->product_id)->where('profile_id',$this->profile_id)->where('select_type',3)->first();

        return isset($comment->value) ? $comment->value : null;
    }

    public function comments()
    {
        return $this->belongsToMany('App\Comment','comments_public_review','public_review_id','comment_id');
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

    public function getMetaAttribute($value)
    {
        $meta = \DB::table('public_product_user_review')->where('product_id',$this->product_id)->where('profile_id',$this->profile_id)->where('select_type',6)->first();

        return isset($meta->meta) ? json_decode($meta->meta,true) : null;
    }
}
