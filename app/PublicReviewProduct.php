<?php

namespace App;

use App\PublicReviewProduct\Review;
use App\PublicReviewProduct\ReviewHeader;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class PublicReviewProduct extends Model
{

    use SoftDeletes;

    public $incrementing = false;

    protected $table = 'public_review_products';

    protected $dates = ['deleted_at'];

    public static $types = ['Vegetarian','Non-Vegetarian'];

        protected $fillable = ['id','name','is_vegetarian','product_category_id','product_sub_category_id','brand_name','brand_logo',
        'company_name','company_logo','company_id','description','mark_featured','images_meta','video_link', 'global_question_id',
            'is_active','created_at','updated_at','deleted_at','keywords','is_authenticity_check','brand_description','company_description','paired_best_with','portion_size','product_ingredients','nutritional_info','allergic_info_contains'];

    protected $visible = ['id','name','is_vegetarian','product_category_id','product_sub_category_id','brand_name','brand_logo',
        'company_name','company_logo','company_id','description','mark_featured','images_meta','video_link','global_question_id','is_active',
        'product_category','product_sub_category','type','review_count','created_at','updated_at','deleted_at','keywords',
        'is_authenticity_check','brand_description','company_description','paired_best_with','portion_size',
        'product_ingredients','nutritional_info','allergic_info_contains'];

    protected $appends = ['type','review_count'];

    protected $with = ['product_category','product_sub_category']; // remove category and sub category

    public static function boot()
    {
        self::created(function($model){
            $model->addToCache();
            \App\Documents\PublicReviewProduct::create($model);
        });

        self::updated(function($model){
            $model->addToCache();
            //update the search
            \App\Documents\PublicReviewProduct::create($model);

        });
    }

    public function addToCache()
    {
        \Redis::set("public-review/product:" . $this->id,$this->makeHidden(['overall_rating','current_status'])->toJson());

    }

    public function getTypeAttribute()
    {
        if($this->is_vegetarian == 1)
        {
            return ['id'=>1,'value'=>'Vegetarian','image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/public-review/kind_vegeratian_icon.png'];
        }
        else
        {
            return ['id'=>2,'value'=>'Non-Vegetarian','image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/public-review/kind_non_vegeratian_icon.png'];
        }
    }

    public function product_category()
    {
        return $this->belongsTo(\App\PublicReviewProduct\ProductCategory::class);
    }

    public function product_sub_category()
    {
        return $this->belongsTo(\App\PublicReviewProduct\ProductSubCategory::class);
    }

    public function getImagesMetaAttribute($value)
    {
        if(isset($value))
        {
            return json_decode($value);
        }
        return [];
    }

    public function getBrandLogoAttribute($value)
    {
        if(isset($value))
            return json_decode($value);
    }

    public function getCompanyLogoAttribute($value)
    {
        if(isset($value))
            return json_decode($value);
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

    public function getOverallRatingAttribute()
    {
        $header = ReviewHeader::where('global_question_id',$this->global_question_id)->where('header_selection_type',2)->first();
        if($header != null)
        {
            $overallPreferances = \DB::table('public_product_user_review')->where('current_status',2)->where('product_id',$this->id)->where('header_id',$header->id)->where('select_type',5)->sum('leaf_id');
            $userCount = \DB::table('public_product_user_review')->where('current_status',2)->where('product_id',$this->id)->where('header_id',$header->id)->where('select_type',5)->get()->count();
            $question = \DB::table('public_review_questions')->where('header_id',$header->id)->where('questions->select_type',5)->first();
            $question = json_decode($question->questions);
            $option = isset($question->option) ? $question->option : [];
            $meta = [];
            $meta['max_rating'] = count($option);
            $meta['overall_rating'] = $userCount >= 1 ? $overallPreferances/$userCount : null;
            $meta['count'] = $userCount;
            $meta['color_code'] = $userCount >= 1 ? $this->getColorCode(floor($meta['overall_rating'])) : null;
            return $meta;
        }

        return null;
    }

    public function getReviewCountAttribute()
    {
        return \DB::table('public_product_user_review')->where('current_status',2)->where('product_id',$this->id)->count(\DB::raw('DISTINCT profile_id'));

    }

    public function getCurrentStatusAttribute()
    {
        //bad me change krna h
        $loggedInProfileId = request()->user()->profile->id;
        $currentStatus = \DB::table('public_product_user_review')->where('product_id',$this->id)->where('profile_id',$loggedInProfileId)->where('current_status',2)->exists();
        if($currentStatus)
            return 2;
        $currentStatus = \DB::table('public_product_user_review')->where('product_id',$this->id)->where('profile_id',$loggedInProfileId)->where('current_status',1)->exists();
        if($currentStatus)
            return 1;
        return 0;
    }

    public function getKeywordsAttribute($value)
    {
        $keywords = explode(",",$value);
        return \DB::table('public_review_keywords')->whereIn('id',$keywords)->get();
    }

    public function getMetaFor(int $profileId) : array
    {
        $meta = [];
        $meta['overall_rating'] = $this->getOverallRatingAttribute();
        $meta['current_status'] = $this->getCurrentStatusAttribute();
        return $meta;
    }

    public function getPreviewContent()
    {
        $data = [];
        $data['modelId'] = $this->id;
        $data['deeplinkCanonicalId'] = 'share_feed/'.$this->id;
        $data['title'] = substr($this->name,0,65);
        $data['description'] = substr($this->company_name,0,155);
        $data['ogTitle'] = substr($this->name,0,65);
        $data['ogDescription'] = $this->brand_name;
        $images = isset($this->images_meta[0]->original_photo) ? $this->images_meta[0]->original_photo : null;
        $data['cardType'] = isset($images) ? 'summary_large_image':'summary';
        $data['ogImage'] = isset($images) ? $images:'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/share/share-collaboration-big.png';
        $data['ogUrl'] = env('APP_URL').'/public-review/'.$this->id;
        $data['redirectUrl'] = env('APP_URL').'/public-review/products/'.$this->id;

        return $data;

    }

}
