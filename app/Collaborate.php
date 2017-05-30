<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Collaborate extends Model
{
    protected $fillable = ['title', 'i_am', 'looking_for',
        'purpose', 'deliverables', 'who_can_help', 'expires_on','keywords','video','interested','location',
        'profile_id', 'company_id','template_fields','template_id','notify','commentCount','likeCount'];
    
    protected $with = ['profile','company','fields'];
    

    protected $appends = ['interested','commentCount','likeCount'];
    
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
        return $this->profiles()->updateExistingPivot($profile->id,['approved_on'=>$approvedOn]);
    }
    
    public function approveCompany(Company $company)
    {
        $approvedOn = Carbon::now()->toDateTimeString();
        return $this->companies()->updateExistingPivot($company->id,['approved_on'=>$approvedOn]);
    }
    
    public function rejected()
    {
        //if approved is null, then it is rejected.
        //should it be still shown to the creator?
    }
    
    public function rejectProfile(Profile $profile)
    {
        $approvedOn = Carbon::now()->toDateTimeString();
        return $this->profiles()->updateExistingPivot($profile->id,['rejected_on'=>$approvedOn]);
    }
    
    public function rejectCompany(Company $company)
    {
        $approvedOn = Carbon::now()->toDateTimeString();
        return $this->companies()->updateExistingPivot($company->id,['rejected_on'=>$approvedOn]);
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
        if(empty($fields)){
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
    
    public function getInterestedAttribute()
    {
        $count = \DB::table("collaborators")->where("collaborate_id",$this->id)->count();
        $profileIds = \DB::table("collaborators")->select('profile_id')->where("collaborate_id",$this->id)->get();
        if($profileIds){
            $profileIds = $profileIds->pluck('profile_id')->toArray();
        }
        $profiles = \App\Recipe\Profile::whereIn('id',$profileIds)->get();
        return ['count'=>$count,'profiles'=>$profiles];
    }
    
    public function getMetaFor($profileId)
    {
        $meta = [];
        $meta['interested'] = \DB::table('collaborators')->where('collaborate_id',$this->id)->where('profile_id',$profileId)->exists();
        $meta['hasLiked'] = \DB::table('collaboration_likes')->where('collaboration_id',$this->id)->where('profile_id',$profileId)->exists();
        return $meta;
    }
    
    public function getMetaForCompany($companyId)
    {
        $meta = [];
        $meta['interested'] = \DB::table('collaborators')->where('collaborate_id',$this->id)->where('company_id',$companyId)->exists();
       
    
        return $meta;
    }

    public function similar()
    {
        return self::take(4)->get();
    }

   
}
