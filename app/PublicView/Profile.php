<?php

namespace App\PublicView;

use App\Job;
use App\Profile as BaseProfile;
use App\Shoutout;
use App\Subscriber;
use Illuminate\Support\Facades\Redis;

class Profile extends BaseProfile
{
    protected $fillable = [];

    protected $with = [];

    protected $visible = ['id','name', 'designation','imageUrl','tagline','about','handle','city','expertise',
        'keywords','image','experience','education','followersCount', 'image_meta','hero_image_meta'];

    protected $appends = ['name','imageUrl','experience','education','followersCount'];

    public function photos()
    {
        return $this->belongsToMany('App\PublicView\Photos','profile_photos','profile_id','photo_id');
    }

    public function collaborate()
    {
        return $this->hasMany(\App\Collaborate::class);
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

    public function getExperienceAttribute(){
        $experiences = $this->experience()->get();
        $dates = $experiences->toArray();

        $experiences = $experiences->keyBy('id');
        $sortedExperience = collect([]);
        $endDates = [];
        foreach ($dates as $exp) {
            $id = $exp['id'];

            if (is_null($exp['end_date']) || $exp['current_company'] === 1) {
                $sortedExperience->push($experiences->get($id));
                continue;
            }
            $dateArray = explode("-", $exp['end_date']);
            $temp = array_fill(0, 3 - count($dateArray), '01');
            $tempdate = implode("-", array_merge($temp, $dateArray));
            $endDates[] = ['id' => $id, 'date' => $tempdate, 'time' => strtotime($tempdate)];
        }


        $currentCompanies = $sortedExperience->pluck('start_date','id')->toArray();
        $startDates = [];

        foreach($currentCompanies as $id=>$startDate){

            $dateArray = explode("-", $startDate);
            $temp = array_fill(0, 3 - count($dateArray), '01');
            $tempdate = implode("-", array_merge($temp, $dateArray));
            $startDates[] = ['id' => $id, 'date' => $tempdate, 'time' => strtotime($tempdate)];
        }
        $startDates = collect($startDates)->sortByDesc('time')->keyBy('id')->toArray();
        $sortedExperience = collect([]);

        foreach($startDates as $id=>$date){

            $sortedExperience->push($experiences->get($id));
        }


        $sorted = collect($endDates)->sortByDesc('time')->keyBy('id')->toArray();
        unset($endDates);

        foreach($sorted as $id=>$date){
            $sortedExperience->push($experiences->get($id));
        }

        unset($experiences);
        return $sortedExperience;

    }

    public function getEducationAttribute(){

        $educations = $this->education()->get();

        $dates = $educations->toArray();

        $educations = $educations->keyBy('id');
        $sortedEducation = collect([]);
        $endDates = [];
        foreach ($dates as $exp) {
            $id = $exp['id'];

            if (is_null($exp['end_date']) || $exp['ongoing'] === 1) {
                $sortedEducation->push($educations->get($id));
                continue;
            }
            $dateArray = explode("-", $exp['end_date']);
            $temp = array_fill(0, 3 - count($dateArray), '01');
            $tempdate = implode("-", array_merge($temp, $dateArray));
            $endDates[] = ['id' => $id, 'date' => $tempdate, 'time' => strtotime($tempdate)];
        }


        $currentColleges = $sortedEducation->pluck('start_date','id')->toArray();
        $startDates = [];

        foreach($currentColleges as $id=>$startDate){

            $dateArray = explode("-", $startDate);
            $temp = array_fill(0, 3 - count($dateArray), '01');
            $tempdate = implode("-", array_merge($temp, $dateArray));
            $startDates[] = ['id' => $id, 'date' => $tempdate, 'time' => strtotime($tempdate)];
        }
        $startDates = collect($startDates)->sortByDesc('time')->keyBy('id')->toArray();
        $sortedEducation = collect([]);

        foreach($startDates as $id=>$date){

            $sortedEducation->push($educations->get($id));
        }


        $sorted = collect($endDates)->sortByDesc('time')->keyBy('id')->toArray();
        unset($endDates);

        foreach($sorted as $id=>$date){
            $sortedEducation->push($educations->get($id));
        }

        unset($educations);
        return $sortedEducation;

    }

    public function experience()
    {
        return $this->hasMany('App\Profile\Experience');
    }

    public function education()
    {
        return $this->hasMany('App\Education');
    }

    public function getMetaForPublic()
    {
        $meta = [];

        return $meta;
    }

    public function getFollowersCountAttribute()
    {
        $profileIds = Redis::SMEMBERS("followers:profile:".$this->id);
        return count($profileIds) - Redis::sIsMember("followers:profile:".$this->id,$this->id);
    }

}
