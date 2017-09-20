<?php

namespace App;

use App\Channel\Payload;
use App\Interfaces\CommentNotification;
use App\Interfaces\Feedable;
use App\Traits\CachedPayload;
use App\Traits\IdentifiesOwner;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collaborate extends Model implements Feedable
{
    use IdentifiesOwner, CachedPayload, SoftDeletes;
    
    protected $fillable = ['title', 'i_am', 'looking_for', 'expires_on','video','location',
        'description','project_commences','image1','image2','image3','image4','image5',
        'duration','financials','eligibility_criteria','occassion',
        'profile_id', 'company_id','template_fields','template_id','notify','privacy_id'];
    
    protected $with = ['profile','company','fields','categories'];
    
    protected $visible = ['id','title', 'i_am', 'looking_for',
        'expires_on','video','location','categories',
        'description','project_commences','images',
        'duration','financials','eligibility_criteria','occassion',
        'profile_id', 'company_id','template_fields','template_id','notify','privacy_id',
        'profile','company','created_at',
        'commentCount','likeCount','applicationCount'];
    
    protected $appends = ['commentCount','likeCount','images','applicationCount'];
    
    public static function boot()
    {
        self::created(function($model){
            $model->addToCache();
    
            \App\Documents\Collaborate::create($model);
        });
        
        self::updated(function($model){
            $model->addToCache();
            //update the search
            \App\Documents\Collaborate::create($model);
    
        });
    }
    
    public function addToCache()
    {
        \Redis::set("collaborate:" . $this->id,$this->makeHidden(['privacy','profile','company','commentCount','likeCount'])->toJson());
    
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
        return $this->belongsTo(\App\Company::class);
    }
    
    public function collaborators()
    {
        return \DB::table("collaborators")->where("collaborate_id",$this->id)->get();
    }
    
    /**
     * People Collaborators on the project
     */
    public function profiles()
    {
        return $this->belongsToMany(\App\Collaborate\Profile::class,'collaborators',
            'collaborate_id','profile_id')->withPivot('applied_on','approved_on','rejected_on','template_values');
    }
    
    /**
     * Company Collaborators on the project8
     */
    public function companies()
    {
        return $this->belongsToMany(\App\Collaborate\Company::class,'collaborators',
            'collaborate_id','company_id')->withPivot('applied_on','approved_on','rejected_on','template_values');
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
        return $this->profiles()->updateExistingPivot($profile->id,['approved_on'=>$approvedOn,'archived_at'=>null]);
    }
    
    public function approveCompany(Company $company)
    {
        $approvedOn = Carbon::now()->toDateTimeString();
        return $this->companies()->updateExistingPivot($company->id,['approved_on'=>$approvedOn ,'archived_at'=>null]);
    }
    
    public function rejected()
    {
        //if approved is null, then it is rejected.
        //should it be still shown to the creator?
    }
    
    public function rejectProfile(Profile $profile)
    {
        $approvedOn = Carbon::now()->toDateTimeString();
        return $this->profiles()->updateExistingPivot($profile->id,['rejected_on'=>$approvedOn,'archived_at'=>$approvedOn]);
    }
    
    public function rejectCompany(Company $company)
    {
        $approvedOn = Carbon::now()->toDateTimeString();
        return $this->companies()->updateExistingPivot($company->id,['rejected_on'=>$approvedOn,'archived_at'=>$approvedOn]);
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
        $count = \DB::table("collaborators")->where("collaborate_id",$this->id)->count();
        $profileIds = \DB::table("collaborators")->select('profile_id')->where("collaborate_id",$this->id)->get();
        if($profileIds){
            $profileIds = $profileIds->pluck('profile_id')->toArray();
        }
        $profiles = \App\Recipe\Profile::whereIn('id',$profileIds)->get();
        return ['count'=>$count,'profiles'=>$profiles];
    }
    
    /**
     * @param int $profileId
     * @return array
     */
    public function getMetaFor(int $profileId) : array
    {
        $meta = [];
        $meta['interested'] = \DB::table('collaborators')->where('collaborate_id',$this->id)->where('profile_id',$profileId)->exists();
        $meta['isShortlisted'] = \DB::table('collaborate_shortlist')->where('collaborate_id',$this->id)->where('profile_id',$profileId)->exists();

        $meta['hasLiked'] = \DB::table('collaboration_likes')->where('collaboration_id',$this->id)->where('profile_id',$profileId)->exists();
        $meta['commentCount'] = $this->comments()->count();
        $meta['likeCount'] = $this->likeCount;
        $meta['shareCount']=\DB::table('collaborate_shares')->where('collaborate_id',$this->id)->whereNull('deleted_at')->count();
        $meta['sharedAt']= \App\Shareable\Share::getSharedAt($this);
    
        return $meta;
    }
    
    /**
     * @param int $companyId
     * @return array
     */
    public function getMetaForCompany(int $companyId) : array
    {
        $meta = [];
        $meta['interested'] = \DB::table('collaborators')->where('collaborate_id',$this->id)->where('company_id',$companyId)->exists();
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
        return $this->belongsToMany('App\CollaborateCategory', 'collaborate_category_pivots','collaborate_id','category_id');
    }
    
    public function getNotificationContent()
    {
        return [
            'name' => strtolower(class_basename(self::class)),
            'id' => $this->id,
            'content' => $this->title,
            'image' => null
        ];
    }
    
    public function getRelatedKey() : array
    {
        if(empty($this->relatedKey) && $this->company_id === null){
            return ['profile'=>'profile:small:' . $this->profile_id];
        }
        return ['company'=>'company:small:' . $this->company_id];
    }

    public function getImagesAttribute ()
    {

        $images=[];
        if($this->company_id){
            for($i=1;$i<=5;$i++)
            {
                if($this->{"image".$i}!==null)
                {
                    $images[] = !is_null($this->{"image".$i}) ? \Storage::url($this->{"image".$i}) : null;

                }
            }
        }
        else
        {
            for($i=1;$i<=5;$i++)
            {
                if($this->{"image".$i}!==null)
                {
                    $images[] = !is_null($this->{"image".$i}) ? \Storage::url($this->{"image".$i}) : null;
                }
            }
        }

        return $images;
    }
    
    public function getApplicationCountAttribute()
    {
        if(request()->user()->profile->id == $this->profile_id)
        {
            return \Redis::hGet("meta:collaborate:" . $this->id,"applicationCount") ?: 0;
        }
    }


}
