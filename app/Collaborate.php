<?php

namespace App;

use App\Channel\Payload;
use App\Collaborate\Applicant;
use App\Interfaces\Feedable;
use App\Traits\CachedPayload;
use App\Traits\IdentifiesOwner;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Redis;

class Collaborate extends Model implements Feedable
{
    use IdentifiesOwner, CachedPayload, SoftDeletes;

    protected $fillable = ['title', 'i_am', 'looking_for', 'expires_on','video','location',
        'description','project_commences','image1','image2','image3','image4','image5',
        'duration','financials','eligibility_criteria','occassion',
        'profile_id', 'company_id','template_fields','template_id',
        'notify','privacy_id','file1','deliverables','start_in','state','deleted_at',
        'created_at','updated_at','category_id','step','financial_min','financial_max',
        'type_id','images','collaborate_type','is_taster_residence','product_review_meta',
        'methodology_id','age_group','gender_ratio','no_of_expert','no_of_veterans','is_product_endorsement',
        'brand_name','brand_logo','no_of_batches','global_question_id','taster_instruction','images_meta'];

    protected $with = ['profile','company','fields','categories','addresses','collaborate_occupations',
        'collaborate_specializations','collaborate_allergens'];

    static public $state = [1,2,3,4,5]; //active =1 , delete =2 expired =3 draft as saved = 4 5 = close

    protected $visible = ['id','title', 'i_am', 'looking_for','owner',
        'expires_on','video','location','categories',
        'description','project_commences',
        'duration','financials','eligibility_criteria','occassion',
        'profile_id', 'company_id','template_fields','template_id','notify','privacy_id',
        'profile','company','created_at','deleted_at',
        'applicationCount','file1','deliverables','start_in','state','updated_at','images',
        'step','financial_min','financial_max','type','type_id','addresses','collaborate_type',
        'is_taster_residence','product_review_meta','methodology_id','age_group','gender_ratio',
        'no_of_expert','no_of_veterans','is_product_endorsement','tasting_methodology','collaborate_occupations','collaborate_specializations',
        'brand_name','brand_logo','no_of_batches','collaborate_allergens','global_question_id','taster_instruction','images_meta','owner'];

    protected $appends = ['applicationCount','type','product_review_meta','tasting_methodology','owner'];

    protected $casts = [
        'privacy_id' => 'integer',
        'profile_id' => 'integer',
        'company_id' => 'integer',
        'financial_min' => 'integer',
        'financial_max' => 'integer'
    ];

    private $interestedCount = 0;
    
    public static function boot()
    {
        self::created(function($model){
            $model->addToCache();
            $model->addToCacheV2();
            \App\Documents\Collaborate::create($model);
        });
        
        self::updated(function($model){
            $model->addToCache();
            $model->addToCacheV2();
            //update the search
            \App\Documents\Collaborate::create($model);
    
        });
    }
    
    public function addToCache()
    {
        Redis::set("collaborate:" . $this->id,$this->makeHidden(['privacy','profile','company','commentCount','likeCount','applicationCount','fields'])->toJson());
    }

    public function addToCacheV2()
    {
        $keyRequired = [
            "id",
            "title",
            "description",
            "profile_id",
            "company_id",
            "has_tags",
            "collaborate_type",
            "expires_on",
            "updated_at",
            "created_at",
            "deleted_at",
        ];
        $data = array_intersect_key(
            $this->makeHidden([
                'privacy',
                'profile',
                'company',
                'commentCount',
                'likeCount',
                'applicationCount',
                'fields'
            ])->toArray(), 
            array_flip($keyRequired)
        );
        foreach ($data as $key => $value) {
            if (is_null($value) || $value == '')
                unset($data[$key]);
        }
        Redis::connection('V2')->set("collaborate:".$this->id.":V2",json_encode($data));
    }

    public function getOwnerAttribute()
    {
        return $this->owner();
    }
    
    public function removeFromCache()
    {

        Redis::del("collaborate:".$this->id);
        Redis::connection('V2')->del("collaborate:".$this->id.":V2");
    }
    /**
     * Which profile created the collaboration project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function profile()
    {
        return $this->belongsTo(\App\Recipe\Profile::class);
    }
    
    /**
     * Which company created the collaboration project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(\App\Recipe\Company::class);
    }
    
    public function collaborateapplicants()
    {
        return \DB::table("collaborate_applicants")->where("collaborate_id",$this->id)->get();
    }
    
    /**
     * People Collaborators on the project
     */
    public function profiles()
    {
        return $this->belongsToMany(\App\Collaborate\Profile::class,'collaborate_applicants',
            'collaborate_id','profile_id')->withPivot('created_at','shortlisted_at','rejected_at');
    }
    
    /**
     * Company Collaborators on the project8
     */
    public function companies()
    {
        return $this->belongsToMany(\App\Collaborate\Company::class,'collaborate_applicants',
            'collaborate_id','company_id')->withPivot('created_at','shortlisted_at','rejected_at');
    }
    
    public function applications()
    {
        return $this->profiles();
    }
    
    public function approved()
    {
        return $this->profiles()->wherePivot("approved_on",null);
    }
    
    public function approveProfile(Profile $profile)
    {
        $approvedOn = Carbon::now()->toDateTimeString();
        return Applicant::where('collaborate_id',$this->id)->where('profile_id',$profile->id)
            ->whereNull('company_id')->update(['shortlisted_at'=>$approvedOn,'rejected_at'=>null]);
    }
    
    public function approveCompany(Company $company)
    {
        $approvedOn = Carbon::now()->toDateTimeString();
        return Applicant::where('collaborate_id',$this->id)->where('company_id',$company->id)
            ->update(['shortlisted_at'=>$approvedOn,'rejected_at'=>null]);
    }
    
    public function rejected()
    {
        //if approved is null, then it is rejected.
        //should it be still shown to the creator?
    }
    
    public function rejectProfile(Profile $profile)
    {
        $approvedOn = Carbon::now()->toDateTimeString();
        return Applicant::where('collaborate_id',$this->id)->where('profile_id',$profile->id)
            ->whereNull('company_id')->update(['rejected_at'=>$approvedOn,'shortlisted_at'=>null]);
    }
    
    public function rejectCompany(Company $company)
    {
        $approvedOn = Carbon::now()->toDateTimeString();
        return Applicant::where('collaborate_id',$this->id)->where('company_id',$company->id)
            ->update(['rejected_at'=>$approvedOn,'shortlisted_at'=>null]);
        }
    
    public function comments()
    {
        return $this->belongsToMany(Comment::class,'comments_collaborates','collaborate_id','comment_id');
    }

    public function getLikeCountAttribute()
    {
        return \DB::table("collaboration_likes")->where("collaboration_id",$this->id)
            ->count();
    }
    
    public function template()
    {
        return $this->belongsTo(CollaborateTemplate::class,'template_id','id');
    }
    
    public function getAdditionalFieldsAttribute()
    {
        return $this->template !== null ? $this->template->fields : null;
    }

    public function getCommentCountAttribute()
    {
            return $this->comments->count();
    } 

    
    public function fields()
    {
        return $this->belongsToMany(Field::class,'collaboration_fields','collaboration_id','field_id');
    }
    
    public function addField(Field $field)
    {
        return $this->fields()->attach($field->id);
    }
    
    public function removeField(Field $field)
    {
        return $this->fields()->detach($field->id);
    }
    
    public function syncFields($fieldIds = [])
    {
        if(empty($fieldIds)){
            \Log::warning("Empty fields passed.");
            return false;
        }
        
        $fields = Field::select('id')->whereIn('id',$fieldIds)->get();
    
        if($fields->count()){
            return $this->fields()->sync($fields->pluck('id')->toArray());
        }
    }
    
    public function getTemplateValuesAttribute()
    {
        return !is_null($this->template_values) ? json_decode($this->template_values) : null;
    }
    
    public function getInterestedAttribute() : array
    {
        $count = \DB::table("collaborate_applicants")->where("collaborate_id",$this->id)->count();
        $profileIds = \DB::table("collaborate_applicants")->select('profile_id')->where("collaborate_id",$this->id)->get();
        if($profileIds){
            $profileIds = $profileIds->pluck('profile_id')->toArray();
        }
        $profiles = \App\Recipe\Profile::whereIn('id',$profileIds)->get();
        return ['count'=>$count,'profiles'=>$profiles];
    }
    
    private function getInterestedProfile($profileId)
    {
        $interestedProfile = json_decode(Redis::get("profile:small:" . $profileId),true);
        return is_array($interestedProfile) ? array_only($interestedProfile,['name','id']) : [];
    }
    
    private function getInterestedCompany($companyId)
    {
        $company = json_decode(Redis::get("company:small:" . $companyId),true);
        return is_array($company) ? array_only($company,['name','id','profileId']) : [];
    }
    
    private function setInterestedAsProfiles(&$meta,&$profileId)
    {
        $interested = \DB::table('collaborate_applicants')->where('collaborate_id',$this->id);
    
        $companyIds = \DB::table("company_users")->select('company_id')->where('profile_id',$profileId)->get();
        
        if($companyIds->count()){
            $interested = $interested->where(function($query) use ($profileId,$companyIds){
                $query->where('profile_id',$profileId)->orWhereIn('company_id',$companyIds->pluck('company_id'));
            });
        } else {
            $interested = $interested->where('profile_id',$profileId);
        }
        
        $interested = $interested->first();
        //only one of his companies can apply;
        $meta['interested'] = !!$interested;
    
        if($meta['interested']){
            $meta['interested_as']['profile'] = $this->getInterestedProfile($interested->profile_id);
        
            if($interested->company_id){
                $meta['interested_as']['company'] = $this->getInterestedCompany($interested->company_id);
            }
        }
    }
    
    /**
     * @param int $profileId
     * @return array
     */
    public function getMetaFor(int $profileId) : array
    {
        $meta = [];

        if($this->collaborate_type == 'product-review')
        {
            $key = "meta:collaborate:likes:" . $this->id;
            $meta['hasLiked'] = Redis::sIsMember($key,$profileId) === 1;
            $meta['likeCount'] = Redis::sCard($key);

            $meta['commentCount'] = $this->comments()->count();
            $peopleLike = new PeopleLike();
            $meta['peopleLiked'] = $peopleLike->peopleLike($this->id, 'collaborate' ,request()->user()->profile->id);
            $meta['shareCount']=\DB::table('collaborate_shares')->where('collaborate_id',$this->id)->whereNull('deleted_at')->count();
            $meta['sharedAt']= \App\Shareable\Share::getSharedAt($this);

            $this->interestedCount = \DB::table('collaborate_applicants')->where('collaborate_id',$this->id)->distinct()->get(['profile_id'])->count();
            $meta['interestedCount'] = $this->interestedCount;
            $meta['isAdmin'] = $this->company_id ? \DB::table('company_users')
                ->where('company_id',$this->company_id)->where('user_id',request()->user()->id)->exists() : false ;
            return $meta;
        }

        $this->setInterestedAsProfiles($meta,$profileId);

        $meta['isShortlisted'] = \DB::table('collaborate_shortlist')->where('collaborate_id',$this->id)->where('profile_id',$profileId)->exists();

        $key = "meta:collaborate:likes:" . $this->id;
        $meta['hasLiked'] = Redis::sIsMember($key,$profileId) === 1;
        $meta['likeCount'] = Redis::sCard($key);

        $meta['commentCount'] = $this->comments()->count();
        $peopleLike = new PeopleLike();
        $meta['peopleLiked'] = $peopleLike->peopleLike($this->id, 'collaborate' ,request()->user()->profile->id);
        $meta['shareCount']=\DB::table('collaborate_shares')->where('collaborate_id',$this->id)->whereNull('deleted_at')->count();
        $meta['sharedAt']= \App\Shareable\Share::getSharedAt($this);

        $meta['interestedCount'] = (int) Redis::hGet("meta:collaborate:" . $this->id,"applicationCount") ?: 0;
        $meta['isAdmin'] = $this->company_id ? \DB::table('company_users')
            ->where('company_id',$this->company_id)->where('user_id',request()->user()->id)->exists() : false ;

        return $meta;
    }

    /**
     * @param int $profileId
     * @return array
     */
    public function getMetaForV2(int $profileId) : array
    {
        $meta = [];

        if ($this->collaborate_type == 'product-review') {
            $key = "meta:collaborate:likes:" . $this->id;
            $meta['hasLiked'] = Redis::sIsMember($key,$profileId) === 1;
            $meta['likeCount'] = Redis::sCard($key);
            $meta['commentCount'] = $this->comments()->count();
            $meta['shareCount'] = \DB::table('collaborate_shares')->where('collaborate_id',$this->id)->whereNull('deleted_at')->count();
            $meta['sharedAt']= \App\Shareable\Share::getSharedAt($this);
            $meta['isAdmin'] = $this->company_id ? \DB::table('company_users')
                ->where('company_id',$this->company_id)->where('user_id',request()->user()->id)->exists() : false ;
            return $meta;
        }

        $key = "meta:collaborate:likes:" . $this->id;
        $meta['hasLiked'] = Redis::sIsMember($key,$profileId) === 1;
        $meta['likeCount'] = Redis::sCard($key);
        $meta['commentCount'] = $this->comments()->count();
        $meta['shareCount']=\DB::table('collaborate_shares')->where('collaborate_id',$this->id)->whereNull('deleted_at')->count();
        $meta['sharedAt']= \App\Shareable\Share::getSharedAt($this);
        $meta['isAdmin'] = $this->company_id ? \DB::table('company_users')
            ->where('company_id',$this->company_id)->where('user_id',request()->user()->id)->exists() : false ;

        return $meta;
    }
    
    /**
     * @param int $companyId
     * @return array
     */
    public function getMetaForCompany(int $companyId) : array
    {
        $meta = [];
        $meta['interested'] = \DB::table('collaborate_applicants')->where('collaborate_id',$this->id)->where('company_id',$companyId)->exists();
        return $meta;
    }
    
    public function privacy()
    {
        return $this->belongsTo(Privacy::class);
    }
    
    public function payload()
    {
        return $this->belongsTo(Payload::class,'payload_id');
    }
    
    public function getCommentNotificationMessage() : string
    {
        return "New comment on " . $this->title . ".";
    }

    public function categories()
    {
        return $this->belongsTo(CollaborateCategory::class,'category_id');
    }
    
    public function getNotificationContent()
    {
        return [
            'name' => strtolower(class_basename(self::class)),
            'id' => $this->id,
            'content' => $this->title,
            'image' => null,
            'collaborate_type' => $this->collaborate_type
        ];
    }
    
    public function getRelatedKey() : array
    {
        if(empty($this->relatedKey) && $this->company_id === null){
            return ['profile'=>'profile:small:' . $this->profile_id];
        }
        return ['company'=>'company:small:' . $this->company_id];
    }

    public function getImagesAttribute ($value)
    {
        $imageArray = [];
        if(isset($value))
        {
            if(!is_array($value))
            {
                $images = json_decode($value, true);
                $i = 1;
                foreach ($images as $image) {
                    $imageArray[] = isset($image['image'.$i]) ? $image['image'.$i] : $image;
                    $i++;
                }
            }
            else
                return $value;
        }
        return $imageArray;

    }

    public function getImagesMetaAttribute($value)
    {
        $imageArray = [];
        if(isset($value))
        {
            if(!is_array($value))
            {
                $images = json_decode($value, true);
                foreach ($images as $image) {
                    $imageArray[] = $image;
                }
            }
            else
                return $value;
        }
        return $imageArray;
    }
    
    public function getApplicationCountAttribute()
    {
        if($this->collaborate_type != 'product-review')
        {
            $this->interestedCount = (int)Redis::hGet("meta:collaborate:" . $this->id,"applicationCount") ?? 0;
        }
        return $this->interestedCount;
    }
    
    public function getFile1Attribute($value)
    {
        return !is_null($value) && !(empty($value)) ? \Storage::url($value) : null;
    }

    public function getPreviewContent()
    {
        $profile = isset($this->company_id) ? Company::getFromCache($this->company_id) : Profile::getFromCache($this->profile_id);
        $profile = json_decode($profile);
        $data = [];
        $data['modelId'] = $this->id;
        $data['deeplinkCanonicalId'] = 'share_feed/'.$this->id;
        $data['owner'] = $profile;
        $data['title'] = substr($this->title,0,65);
        $data['description'] = $profile->name;
        $data['title'] = $profile->name. ' is looking for '.substr($this->title,0,65);
        $data['description'] = substr($this->description,0,155);
        $data['ogTitle'] = substr($this->title,0,65);
        $data['ogDescription'] = $profile->name;
        $images = $this->getImagesAttribute($this->images);
        $data['cardType'] = isset($images[0]) ? 'summary_large_image':'summary';
        $data['ogImage'] = isset($images[0]) ? $images[0]:
            'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/share/share-collaboration-big.png';
        $data['ogUrl'] = env('APP_URL').'/preview/collaborate/'.$this->id;
        $data['redirectUrl'] = env('APP_URL').'/collaborate/'.$this->id;
        $data['collaborate_type'] = $this->collaborate_type; 

        return $data;

    }

    public function getApprovedAttribute() : array
    {
        $count = \DB::table("collaborate_applicants")->where("collaborate_id",$this->id)->count();
        $profileIds = \DB::table("collaborate_applicants")->select('profile_id')->whereNull('rejected_at')->where("collaborate_id",$this->id)->get();
        if($profileIds){
            $profileIds = $profileIds->pluck('profile_id')->toArray();
        }
        $profiles = \App\Recipe\Profile::whereIn('id',$profileIds)->get();
        return ['count'=>$count,'profiles'=>$profiles];
    }

    public function getStateAttribute($value)
    {
        switch ($value) {
            case 1:
                return 'Active';
                break;
            case 2:
                return 'Delete';
                break;
            case 3:
                return 'Expired';
                break;
            case 4:
                return 'Save';
                break;
            default:
                return 'Close';
        }
    }


    public function getTypeAttribute()
    {
        return isset($this->type_id) && !is_null($this->type_id) ? \DB::table('collaborate_types')->where('id',$this->type_id)->first() : null;
    }

    public function addresses()
    {
        return $this->hasMany('App\Collaborate\Addresses');
    }

    public function getProductReviewMetaAttribute()
    {
        $meta = [];
        if($this->collaborate_type == 'product-review' && isset(request()->user()->profile->id))
        {
            $meta['is_invited'] = \DB::table('collaborate_applicants')->where('collaborate_id',$this->id)->where('profile_id',request()->user()->profile->id)
                ->where('is_invited',1)->exists();
            $meta['has_batch_assign'] = \DB::table('collaborate_batches_assign')->where('collaborate_id',$this->id)
                ->where('profile_id',request()->user()->profile->id)->where('begin_tasting',1)->exists();
            $batchIds =  \DB::table('collaborate_batches_assign')->where('collaborate_id',$this->id)
                ->where('profile_id',request()->user()->profile->id)->get()->pluck('batch_id')->toArray();
            $completedBatchIds = \DB::table('collaborate_tasting_user_review')->where('profile_id',request()->user()->profile->id)
                ->where('collaborate_id',$this->id)->where('current_status',3)->get()->pluck('batch_id')->toArray();
            sort($batchIds);
            sort($completedBatchIds);
            $meta['is_completed_product_review'] = count($completedBatchIds) > 0 ? ($batchIds == $completedBatchIds) : false;
            $meta['is_interested'] = \DB::table('collaborate_applicants')->where('collaborate_id',$this->id)->where('profile_id',request()->user()->profile->id)
                ->where('is_invited',0)->whereNull('rejected_at')->exists();
            $applicants = \DB::table('collaborate_applicants')->where('collaborate_id',$this->id)->where('profile_id',request()->user()->profile->id)
                ->where('is_invited',1)->first();
            $meta['is_actioned'] = isset($applicants) ? isset($applicants->shortlisted_at) || isset($applicants->rejected_at) ? true : false : false;
            $meta['is_invitation_accepted'] = isset($applicants) ? isset($applicants->shortlisted_at) && !is_null($applicants->shortlisted_at) ? true : false : false;
            return $meta;
        }
        return null;

    }

    public function getTastingMethodologyAttribute()
    {
        return isset($this->methodology_id) && !is_null($this->methodology_id) ? \DB::table('collaborate_tasting_methodology')->where('id',$this->methodology_id)->first() : null;
    }

    public function collaborate_specializations()
    {
        return $this->hasMany('App\Collaborate\Specialization');
    }

    public function collaborate_occupations()
    {
        return $this->hasMany('App\Collaborate\Occupation');
    }

    public function collaborate_allergens()
    {
        return $this->hasMany('App\Collaborate\Allergens');
    }

    public function getAgeGroupAttribute($value)
    {
        return !is_null($value) ? json_decode($value) : null;
    }

    public function getGenderRatioAttribute($value)
    {
        return !is_null($value) ? json_decode($value) : null;
    }

//    public function getOwnerAttribute()
//    {
//        return $this->owner();
//    }

}
