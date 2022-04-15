<?php

namespace App;

use App\Http\Controllers\Api\PublicReviewProduct\ReviewController;
use App\Payment\PaymentDetails;
use App\Payment\PaymentLinks;
use App\PublicReviewProduct\Review;
use App\PublicReviewProduct\ReviewHeader;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Redis;

class PublicReviewProduct extends Model
{
    use SoftDeletes;

    public $incrementing = false;

    protected $table = 'public_review_products';

    protected $dates = ['deleted_at'];

    public static $types = ['Vegetarian','Non-Vegetarian'];

    protected $fillable = ['id','name','is_vegetarian','product_category_id','product_sub_category_id','brand_name','brand_logo','company_name','company_logo','company_id','description','mark_featured','images_meta','video_link', 'global_question_id','is_active','created_at','updated_at','deleted_at','keywords','is_authenticity_check','brand_description','company_description','paired_best_with','portion_size','product_ingredients','nutritional_info','allergic_info_contains','brand_id','is_newly_launched'];

    protected $visible = ['id','name','is_vegetarian','product_category_id','product_sub_category_id','brand_name','brand_logo','company_name','company_logo','company_id','description','mark_featured','images_meta','video_link','global_question_id','is_active','product_category','product_sub_category','type','review_count','created_at','updated_at','deleted_at','keywords','is_authenticity_check','brand_description','company_description','paired_best_with','portion_size','product_ingredients','nutritional_info','allergic_info_contains','brand_id','is_newly_launched'];

    protected $appends = ['type','review_count'];

    protected $with = ['product_category','product_sub_category']; // remove category and sub category

    public static function boot()
    {
        self::created(function($model){
            $model->addToCache();
            $model->addToCacheV2();
            \App\Documents\PublicReviewProduct::create($model);
        });

        self::updated(function($model){
            $model->addToCache();
            $model->addToCacheV2();
            //update the search
            \App\Documents\PublicReviewProduct::create($model);

        });
    }

    public function addToCache()
    {
        Redis::set("public-review/product:" . $this->id,$this->makeHidden(['overall_rating','current_status'])->toJson());
    }

    public function addToCacheV2()
    {
        $data = $this->makeHidden([
            "is_vegetarian",
            "product_category_id",
            "product_sub_category_id",
            "brand_logo",
            "company_logo",
            "company_id",
            "description",
            "mark_featured",
            "keywords",
            "is_authenticity_check",
            "global_question_id",
            "is_active",
            "brand_description",
            "company_description",
            "paired_best_with",
            "portion_size",
            "serves_count",
            "product_ingredients",
            "nutritional_info",
            "allergic_info_contains",
            "type",
            "product_category",
            "product_sub_category",
            "overall_rating",
            "current_status"
        ])->toArray();
        foreach ($data as $key => $value) {
            if (is_null($value) || $value == '')
                unset($data[$key]);
        }
        Redis::connection('V2')->set(
            "public-review/product:" . $this->id.":V2",
            json_encode($data)
        );
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

    public function getBrandNameAttribute($value)
    {
        if (isset($this->brand_id) && !is_null($this->brand_id)) {
            $public_review_product_brand = \App\PublicReviewProductBrand::where('id',$this->brand_id)->first();
            if ($public_review_product_brand 
                && isset($public_review_product_brand->name) 
                && !is_null($public_review_product_brand->name)) {
                return $public_review_product_brand->name;
            } else {
                return null;
            }
        } else {
            if (isset($value)) {
                return $value;
            } else {
                return null;
            }
        }
    }

    public function getBrandLogoAttribute($value)
    {
        if (isset($this->brand_id) && !is_null($this->brand_id)) {
            $public_review_product_brand = \App\PublicReviewProductBrand::where('id',$this->brand_id)->first();
            if ($public_review_product_brand 
                && isset($public_review_product_brand->image) 
                && !is_null($public_review_product_brand->image)) {
                return json_decode($public_review_product_brand->image);
            } else {
                return null;
            }
        } else {
            if (isset($value)) {
                return json_decode($value);
            } else {
                return null;
            }
        }
    }

    public function getCompanyNameAttribute($value)
    {
        if (isset($this->company_id) && !is_null($this->company_id)) {
            $public_review_product_company = \App\PublicReviewProductCompany::where('id',$this->company_id)->first();
            if ($public_review_product_company 
                && isset($public_review_product_company->name) 
                && !is_null($public_review_product_company->name)) {
                return $public_review_product_company->name;
            } else {
                return null;
            }
        } else {
            if (isset($value)) {
                return $value;
            } else {
                return null;
            }
        }
    }

    public function getCompanyLogoAttribute($value)
    {
        if (isset($this->company_id) && !is_null($this->company_id)) {
            $public_review_product_company = \App\PublicReviewProductCompany::where('id',$this->company_id)->first();
            if ($public_review_product_company 
                && isset($public_review_product_company->image) 
                && !is_null($public_review_product_company->image)) {
                return json_decode($public_review_product_company->image);
            } else {
                return null;
            }
        } else {
            if (isset($value)) {
                return json_decode($value);
            } else {
                return null;
            }
        }
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
        if (!is_null($header)) {
            $overallPreferances = \DB::table('public_product_user_review')->where('current_status',2)->where('product_id',$this->id)->where('header_id',$header->id)->where('select_type',5)->sum('leaf_id');
            // old code
            // $userCount = \DB::table('public_product_user_review')->where('current_status',2)->where('product_id',$this->id)->where('header_id',$header->id)->where('select_type',5)->get()->count();
            $userCount = \DB::table('public_product_user_review')->where('current_status',2)->where('product_id',$this->id)->where('header_id',$header->id)->count(\DB::raw('DISTINCT profile_id'));
            $question = \DB::table('public_review_questions')->where('header_id',$header->id)->whereRaw("JSON_CONTAINS(questions, '5', '$.select_type')")->first();
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
        $meta['seen_count'] = "1.2k";
        $meta['overall_rating'] = $this->getOverallRatingAttribute();
        $meta['current_status'] = $this->getCurrentStatusAttribute();
        $meta['is_sample_available'] = false;
        $meta['is_sample_requested'] = false;
        $payment = PaymentDetails::where("model_type","Public Review")
            ->where("model_id",$this->id)
            ->where("is_active",1)
            ->whereNull('deleted_at')
            ->first();
        if (!empty($payment)) {
            $ispaid = true;
            $exp = (!empty($payment) && !empty($payment->excluded_profiles) ? $payment->excluded_profiles : null);
            if ($exp != null) {
                $separate = explode(",", $exp);
                if (in_array(request()->user()->profile->id, $separate)) {
                    //excluded profile error to be updated
                    $ispaid = false;
                }
            }
            if ($ispaid == true) {
                $getCount = PaymentHelper::getDispatchedPaymentUserTypes($payment);
                if (request()->user()->profile->is_expert) {
                    $ukey = "expert";
                } else {
                    $ukey = "consumer";
                }
                
                if ($payment->review_type == config("payment.PAYMENT_REVIEW_TYPE.USER_TYPE")) {
                    $getAmount = json_decode($payment->amount_json, true);
                    if (($getCount[$ukey] + 1) > $getAmount["current"][$ukey][0]["user_count"]) {
                        $ispaid = false;
                    }
                } else {
                    $links = PaymentLinks::where("payment_id", $payment->id)->where("status_id", "<>", config("constant.PAYMENT_CANCELLED_STATUS_ID"))->get();
                    if ((int)$links->count() >=  (int)$payment->user_count) {
                        $ispaid = false;
                    }
                }
            }
        } else {
            $ispaid = false;
        }

        $meta['isPaid'] = $ispaid;

        if ($this->is_newly_launched) {
            $meta['is_sample_available'] = true;
            $loggedInProfileId = request()->user()->profile->id;
            $meta['is_sample_requested'] = PublicReviewProductGetSample::where('profile_id', (int)$loggedInProfileId)
                ->where('product_id', $this->id)
                ->exists();
        }
        
        return $meta;
    }

    /**
     * @param int $profileId
     * @return array
     */
    public function getSeoTags() : array
    {
        $title = "TagTaste | ".$this->name." | Product";
        
        $description = "";
        if (!is_null($this->description)) {
            $description = substr(htmlspecialchars_decode($this->description),0,160)."...";
        } else {
            $description = "World's first online community for food professionals to discover, network and collaborate with each other.";
        }

        $seo_tags = [
            "title" => $title,
            "meta" => array(
                array(
                    "name" => "description",
                    "content" => $description,
                ),
                array(
                    "name" => "keywords",
                    "content" => "product reviews, sensory reviews, sensory product reviews, food product reviews, beverage reviews, alcoholic beverage reviews, alcohol review, sensogram, product sensogram, appearance, aroma, aromatics, flavor, flavour, texture, product experience, TagTaste product reviews",
                )
            ),
            "og" => array(
                array(
                    "property" => "og:title",
                    "content" => $title,
                ),
                array(
                    "property" => "og:description",
                    "content" => $description,
                ),
                array(
                    "property" => "og:image",
                    "content" => $this->getPreviewContent()['ogImage'],
                )
            ),
        ];
        return $seo_tags;
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
        $data['ogUrl'] = env('APP_URL').'/reviews/products/'.$this->id;
        $data['redirectUrl'] = env('APP_URL').'/reviews/products/'.$this->id;

        return $data;

    }

    public function getMetaForPublicForCollection()
    {
        $meta = [];
        $meta['overall_rating'] = $this->getOverallRatingAttribute();
        return $meta;
    }
    
    public function addToGraph(){
        // $data = \App\PublicReviewProduct::find($this->id)->toArray();
        
        $data = ['id'=>$this->id, 
        'product_id'=>$this->id,
        'name'=>$this->name, 
        'is_active'=>$this->is_active,
        'created_at'=>$this->created_at];
        
        $product = \App\Neo4j\PublicReviewProduct::where('product_id', $data['id'])->first();
        if (!$product) {
            \App\Neo4j\PublicReviewProduct::create($data);
        } else {
            unset($data['id']);
            \App\Neo4j\PublicReviewProduct::where('product_id', $data['product_id'])->update($data);
        }
    }
    
    public function addReviewEdge($profileId){
        $userProfile = \App\Neo4j\User::where('profile_id', $profileId)->first();
        $product = \App\Neo4j\PublicReviewProduct::where('product_id', $this->id)->first();
        if ($userProfile && $product) {
            $isUserReviewed = $userProfile->reviewed->where('product_id',$this->id)->first();
            if (!$isUserReviewed) {
                $relation = $userProfile->reviewed()->attach($product);
                $relation->save();
            } else {
                $relation = $userProfile->reviewed()->edge($product);
                $relation->save();
            }
        }
    }

    public function removeFromGraph(){        
        $productCount = \App\Neo4j\PublicReviewProduct::where('product_id', $this->id)->count();
        if ($productCount > 0) {
            $client = config('database.neo4j_uri_client');
             $query = "MATCH (p:Product{product_id:'$this->id'})
                        DETACH DELETE p;";
            $result = $client->run($query);
        }
    }
}
