<?php


namespace App\Traits;
use Illuminate\Support\Facades\Redis;
use App\Profile;
use App\Profile\User;
use App\Traits\CheckTTEmployee;

trait ProfileData
{
    use CheckTTEmployee;

    public function getFollowerList($request, $profile_id, $module = null){
        $loggedInProfileId = $request->user()->profile->id;
        $followersData = [];

        $profileIds = Redis::SMEMBERS("followers:profile:" . $profile_id);

        if(isset($module) && !empty($module) && $module == 'chat'){
            if($this->checkTTEmployee($profile_id)){
                $profileIds = Profile::whereNull('deleted_at')->pluck('id')->toArray();
            }
        }

        $deac_profiles = User::join('profiles', 'users.id', '=', 'profiles.user_id')->whereNull('users.deleted_at')->where('users.account_deactivated', 1)->pluck('profiles.id')->toArray();

        $profileIds = array_diff($profileIds, $deac_profiles);

        $count = count($profileIds);
        if ($count > 0 && Redis::sIsMember("followers:profile:" . $profile_id, $profile_id)) {
            $count = $count - 1;
        }
        $followersData['count'] = $count;
        $data = [];

        $page = $request->has('page') ? $request->input('page') : 1;
        $profileIds = array_slice($profileIds, ($page - 1) * 20, 20);

        foreach ($profileIds as $key => $value) {
            if ($profile_id == $value) {
                unset($profileIds[$key]);
                continue;
            }
            $profileIds[$key] = "profile:small:" . $value;
        }

        if (count($profileIds) > 0) {
            $data = Redis::mget($profileIds);
        }

        foreach ($data as &$profile) {
            if (is_null($profile)) {
                continue;
            }
            $profile = json_decode($profile);
            $profile->isFollowing = Redis::sIsMember("followers:profile:" . $profile->id, $loggedInProfileId) === 1;
            $profile->self = false;
        }

        $followersData['profile'] = $data;
        return $followersData;
    }

    public function getSearchedProfiles($request, $module = null){
        $loggedInProfileId = $request->user()->profile->id;
        $profileIds = Redis::SMEMBERS("followers:profile:" . $loggedInProfileId);

        if(isset($module) && !empty($module) && $module == 'chat'){
            if( $this->checkTTEmployee($loggedInProfileId)){
                $profileIds = Profile::whereNull('deleted_at')->pluck('id')->toArray();
            }
        }
        
        $query = $request->input('term');
        $page = $request->input('page');
        list($skip, $take) = \App\Strategies\Paginator::paginate($page);
        return \App\Recipe\Profile::select('profiles.*')->join('users', 'profiles.user_id', '=', 'users.id')
            ->where('users.account_deactivated', 0)->where('users.name', 'like', "%$query%")
            ->whereIn('profiles.id', $profileIds)->skip($skip)->take($take)->get();
    }
}