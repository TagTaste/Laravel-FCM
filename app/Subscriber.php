<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Redis;

class Subscriber extends Model
{
    use SoftDeletes;
    protected $fillable = ['channel_name', 'profile_id','timestamp'];
    
    public function profile()
    {
        return $this->belongsTo(\App\Recipe\Profile::class,'profile_id');
    }
    
    public function channel()
    {
        return $this->belongsTo(Channel::class,'channel_name','name');
    }
    
    public static function getFollowers($profileId)
    {
        $profileIds = Redis::sMembers("followers:profile:$profileId");
        $keys = [];
        foreach($profileIds as $id){
            $keys[] = "profile:small:" . $id;
        }
        return Redis::mget($keys);
    }
    
    public static function countFollowers($profileId)
    {
        return Redis::sCard("followers:profile:" . $profileId);
    }
    
    public static function countFollowing($profileId)
    {
        return Redis::sCard("following:profile:" . $profileId);
    }
    
    public static function getFollowing($profileId)
    {
        $profileIds = Redis::sMembers("following:profile:$profileId");
        if(count($profileIds) === 0) {
            return;
        }
        $keys = [];
        foreach($profileIds as $id){
            if(str_contains($id,"company")){
                $keys[] = "company:small:" . last(explode(".",$id));
                continue;
            }
            $keys[] = "profile:small:" . $id;
        }
        return Redis::mget($keys);
    }

    public function followSuggestion()
    {
        if (isset($this->profile_id) && isset($this->channel_name)) {
            $user_id = (int)$this->profile_id;
            $channel = explode(".",$this->channel_name);
            $channel_owner_profile_id = last($channel);
            
            if (($channel[0] != "company") && ($this->profile_id != $channel_owner_profile_id)) {
                $following_id = (int)$channel_owner_profile_id;
                $this->followProfileSuggestion($user_id, $following_id);
            }
        }
    }

    public function followProfileSuggestion($user_id, $following_id)
    {
        $user_profile = \App\Neo4j\User::where('profile_id', $user_id)->first();
        $following_profile = \App\Neo4j\User::where('profile_id', $following_id)->first();

        if ($user_profile && $following_profile) {
            $is_user_following = $user_profile->follows->where('profile_id', $following_id)->first();
            if (!$is_user_following) {
                $relation = $user_profile->follows()->attach($following_profile);
                $relation->following = 1;
                $relation->save();
            } else {
                $relation = $user_profile->follows()->edge($following_profile);
                $relation->following = 1;
                $relation->save();
            }
        }
    }

    public function unfollowProfileSuggestion($user_id, $following_id)
    {
        $user_profile = \App\Neo4j\User::where('profile_id', $user_id)->first();
        $following_profile = \App\Neo4j\User::where('profile_id', $following_id)->first();
        if ($user_profile && $following_profile) {
            $is_user_following = $user_profile->follows->where('profile_id', $following_id)->first();
            if ($is_user_following) {
                $relation = $user_profile->follows()->edge($following_profile);
                $relation->following = 0;
                $relation->save();
            }
        }
    }

    public function followCompanySuggestion($user_id, $company_id)
    {
        $user_profile = \App\Neo4j\User::where('profile_id', $user_id)->first();
        $company_profile = \App\Neo4j\Company::where('company_id', $company_id)->first();
        if ($user_profile && $company_profile) {
            $is_user_following = $user_profile->follows_company->where('company_id', $company_id)->first();
            if (!$is_user_following) {
                $relation = $user_profile->follows_company()->attach($company_profile);
                $relation->following = 1;
                $relation->save();
            } else {
                $relation = $user_profile->follows_company()->edge($company_profile);
                $relation->following = 1;
                $relation->save();
            }
        }
    }

    public function unfollowCompanySuggestion($user_id, $company_id)
    {
        $user_profile = \App\Neo4j\User::where('profile_id', $user_id)->first();
        $company_profile = \App\Neo4j\Company::where('company_id', $company_id)->first();
        if ($user_profile && $company_profile) {
            $is_user_following = $user_profile->follows_company->where('company_id', $company_id)->first();
            if ($is_user_following) {
                $relation = $user_profile->follows_company()->edge($company_profile);
                $relation->following = 0;
                $relation->save();
            }
        }
    }
}
