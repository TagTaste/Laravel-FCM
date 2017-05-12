<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Collaborate extends Model
{
    protected $fillable = ['title', 'i_am', 'looking_for',
        'purpose', 'deliverables', 'who_can_help', 'expires_on',
        'profile_id', 'company_id','template_fields','template_id'];
    
    protected $with = ['profiles','companies'];
    
    protected $appends = ['additionalFields'];
    
    /**
     * Which profile created the collaboration project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function profile()
    {
        return $this->belongsTo(\App\Profile::class);
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
    
    
    public function template()
    {
        return $this->belongsTo(CollaborateTemplate::class,'template_id','id');
    }
    
    public function getAdditionalFieldsAttribute()
    {
        return $this->template->fields;
    }
}
