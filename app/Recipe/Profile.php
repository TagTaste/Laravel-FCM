<?php

namespace App\Recipe;

use App\Channel;
use App\Job;
use App\Profile as BaseProfile;
use App\Shoutout;
use App\Subscriber;
use Illuminate\Notifications\Notifiable;

class Profile extends BaseProfile
{
    use Notifiable;
    protected $fillable = [];

    protected $with = ['allergens'];

    protected $visible = ['id','name', 'designation','imageUrl','tagline','about','handle','city','expertise','user_id',
        'keywords','image','isFollowing','ageRange','gender',"image_meta","hero_image_meta",'is_ttfb_user','verified','is_expert','is_tasting_expert','phone','tasting_instructions','is_premium','hometown',"is_sensory_trained","account_deactivated", 'foodie_type_id', 'foodie_type', 'allergens'];


    protected $appends = ['name','designation','imageUrl','ageRange', 'email','account_deactivated', 'foodie_type'];
    
    public function getDesignationAttribute()
    {
       return $this->professional !== null ? $this->professional->designation : null;
    }
    
    public function getAgeRangeAttribute()
    {
        $age = $this->getDobAttribute($this->dob);
        if(isset($age) && !is_null($age)) {
            $ageGroup = ['< 18', '18 - 35', '35 - 55', '55 - 70', '> 70'];
            $to = (int)$diff = (date('Y') - date('Y', strtotime($age)));
            switch ($to) {
                case $to <= 18:
                    return $ageGroup[0];
                    break;
                case $to > 18 && $to <= 35:
                    return $ageGroup[1];
                    break;
                case $to > 35 && $to <= 55:
                    return $ageGroup[2];
                    break;
                case $to > 55 && $to <= 70:
                    return $ageGroup[3];
                    break;
                case $to > 70:
                    return $ageGroup[4];
                    break;
                default:
                    return null;
            }
        }
        return null;
    }

    public function experience()
    {
        return $this->hasMany('App\Profile\Experience');
    }
    
    public function awards()
    {
        return $this->belongsToMany('App\Profile\Award','profile_awards','profile_id','award_id');
    }

    public function allergens()
    {
        return $this->belongsToMany('App\Profile\Allergen','profiles_allergens','profile_id','allergens_id');
    }
    
    public function certifications()
    {
        return $this->hasMany('App\Profile\Certification');
    }
    
    public function tvshows()
    {
        return $this->hasMany('App\Profile\Show');
    }
    
    public function books()
    {
        return $this->hasMany('App\Profile\Book');
    }
    
    public function patents()
    {
        return $this->hasMany('App\Profile\Patent');
    }
    
    public function recipes()
    {
        return $this->hasMany(\App\Recipe::class);
    }
    
    public function photos()
    {
        return $this->belongsToMany('App\Photo','profile_photos','profile_id','photo_id');
    }
    
    public function projects()
    {
        return $this->hasMany('App\Profile\Project');
    }
    
    public function education()
    {
        return $this->hasMany('App\Education');
    }
    
    public function companies()
    {
        return $this->hasManyThrough('\App\Company','App\User');
    }
    
    public function professional()
    {
        return $this->hasOne('\App\Professional');
    }
    
    public function ideabooks()
    {
        return $this->belongsToMany('\App\Ideabook','ideabook_profiles','profile_id','ideabook_id');
    }
    
    public function collaborate()
    {
        return $this->hasMany(\App\Collaborate::class);
    }
    
    public function channels()
    {
        return $this->hasMany(Channel::class);
    }
    
    public function subscribers()
    {
        return $this->hasMany(Subscriber::class);
    }
    
    public function shoutouts()
    {
        return $this->hasMany(Shoutout::class);
    }
    
    public function jobs()
    {
        return $this->hasMany(Job::class);
    }
    public function getDocumentMetaAttribute()
    {
        $docs = \DB::table('profile_documents')
            ->where('profile_id',$this->id)
            ->select('document_meta','is_verified')
            ->first();
        if ($docs) {
            $doc_meta = json_decode($docs->document_meta);
            $docs->images = $doc_meta;
            unset($docs->document_meta);
            return $docs;
        } else {
            return null;
        }
    }

    public function getEmailAttribute($value)
    {
        return $this->user->email  ?? "";
    }

    public function getContactDetail()
    {
        $phone_number = null;
        if (isset($this->id)) {
            $profile_data = \DB::table('profiles')->where('id',$this->id)->first();
            if (isset($profile_data->phone)) {
                $phone_number = $profile_data->phone;
            }
        }
        return $phone_number;
    }

}
