<?php

namespace App\Http\Controllers\Api;

use App\Company;
use App\CompanyUser;
use App\Events\Actions\Follow;
use App\Events\SuggestionEngineEvent;
use App\Profile;
use App\Recipe\Collaborate;
use App\Subscriber;
use Carbon\Carbon;
use GuzzleHttp\Client;
use App\User;
use App\OTPMaster;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Jobs\PhoneVerify;
use App\Services\SMS;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Twilio\Rest\Client as TwilioClient;
use Twilio\Jwt\ClientToken;
use App\BlockAccount\BlockAccount;
use App\Services\UserService;
use App\DonationProfileMapping;
use App\Traits\ProfileData;

class ProfileController extends Controller
{
    use ProfileData;
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //DO NOT MODIFY THIS RESPONSE
        //DO NOT USE $this->model HERE
        //LIVES DEPEND ON THIS RESPONSE
        $userId = $request->user()->id;

        // Store jwt tokens for force-logout
        $token = $request->bearerToken();
        $this->userService->storeUserLoginInfo($userId, $request, $token);

        $response = \App\Profile\User::find($userId)->toArray();
        $response['profile']['isFollowing'] = false;
        $response['profile']['self'] = true;
        $response['companies'] = $this->getCompany($request);
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        //id can either be id or handle
        //we can use both profile/{id} or handle in api call
        // $profile = \App\Profile\User::where('account_deactivated', false)->whereHas("profile", function ($query) use ($id) {
        //     $query->where('id', $id);
        // })->first();
        $profile = \App\Profile\User::where('account_deactivated', false)->whereHas("profile", function ($query) use ($id) {
            $query->where('id', $id);
        })->first();

        if ($profile === null) {
            return $this->sendError("Could not find profile.");
        }
        $loggedInProfileId = $request->user()->profile->id;

        $this->model = $profile->toArray();
        if ($this->model['profile']['email_private'] == 3) {
            unset($this->model['email']);
        }
        if (!Redis::sIsMember("followers:profile:" . $loggedInProfileId, $id) && $this->model['profile']['email_private'] == 2) {
            unset($this->model['email']);
        }
        $self = $id == $loggedInProfileId;
        $this->model['profile']['self'] = $self;

        $this->model['profile']['isFollowing'] = $self ? false : Profile::isFollowing($loggedInProfileId, $id);

        $this->model['profile']['is_blocked'] = false;
        if(BlockAccount::where('profile_id', $loggedInProfileId)
        ->where('blocked_profile_id', $id)->whereNull('deleted_at')->exists()){
            $this->model['profile']['is_blocked'] = true;
        }
        
        return $this->sendResponse();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $data = $request->except([
            "_method", "_token", 'hero_image', 'image', 'resume', 'remove', 'remove_image',
            'remove_hero_image', 'verified_phone'
        ]);

        //proper verified.
        if (isset($data['verified'])) {
            $data['verified'] = empty($data['verified']) ? 0 : 1;
        }
        if (isset($data['profile']['handle']) && !empty($data['profile']['handle']) && ($data['profile']['handle'] != $request->user()->profile->handle)) {
            $handleExist = \DB::table('profiles')->where('handle', $data['profile']['handle'])->where('id', '!=', $request->user()->profile->id)->exists();
            if ($handleExist) {
                return $this->sendError("This handle is already in use");
            }
        } else {
            unset($data['profile']['handle']);
        }

        // When user deselect the selected eating habit
        if(isset($data['profile']['foodie_type_id']) && ($data['profile']['foodie_type_id'] == '0' || empty($data['profile']['foodie_type_id'])))
        {
            $data['profile']['foodie_type_id'] = null;
        }

        // pallate visibility
        if (isset($data['palate_visibility'])) {
            if (in_array($data['palate_visibility'], ["0", "1", "2"])) {
                $data['profile']['palate_visibility'] = (int)$data['palate_visibility'];
            }
        }

        //delete heroimage or image
        if ($request->has("remove_image") && $request->input('remove_image') == 1) {
            $data['profile']['image'] = null;
            $data['profile']['image_meta'] = null;
        }

        if ($request->has("remove_hero_image") && $request->input('remove_hero_image') == 1) {
            $data['profile']['hero_image'] = null;
            $data['profile']['hero_image_meta'] = null;
        }

        //update user name
        if (!empty($data['name'])) {
            $name = array_pull($data, 'name');
            $name = ucwords($name);
            $request->user()->update(['name' => trim($name)]);
        }

        //save profile image
        $path = \App\Profile::getImagePath($id);
        $this->saveFileToData("image", $path, $request, $data, "image_meta");

        //save hero image
        $path = \App\Profile::getHeroImagePath($id);
        $this->saveFileToData("hero_image", $path, $request, $data, "hero_image_meta");

        //save profile resume

        if ($request->has("remove") && $request->input('remove') == 1) {
            $data['profile']['resume'] = null;
        } else if ($request->hasFile('resume')) {
            $path = "profile/$id/resume";
            $status = \Storage::makeDirectory($path, 0644, true);
            $ext = \File::extension($request->file('resume')->getClientOriginalName());
            $resumeName = str_random("32") . "." . $ext;

            $response = $request->file('resume')->storeAs($path, $resumeName, ['visibility' => 'public']);

            if (!$response) {
                throw new \Exception("Could not save resume " . $resumeName . " at " . $path);
            } else {
                $data['profile']['resume'] = $response;
            }
        }

        //phone verified for request otp
        if (isset($data['profile']['phone']) && !empty($data['profile']['phone'])) {
            $profile = \DB::table('profiles')->where('id', $request->user()->profile->id)->first();
            if ($data['profile']['phone'] != $profile->phone) {
                $data['profile']['verified_phone'] = 0;
            }
        }

        //save the model
        $userId = $request->user()->id;
        $this->model = \App\Profile::where('user_id', $userId)->first();

        if (isset($data['profile']) && !empty($data['profile'])) {
            try {
                $this->model->update($data['profile']);
                $this->model->addToCache();
                $this->model->refresh();
                //update filters
                \App\Filter\Profile::addModel($this->model);
            } catch (\Exception $e) {
                \Log::error($e->getMessage() . " " . $e->getFile() . " " . $e->getLine());
                return $this->sendError("Could not update.");
            }
        }

        $loggedInProfileId = $request->user()->profile->id;

        if (isset($data['profile']['occupation_id']) && !is_null($data['profile']['occupation_id']) && $data['profile']['occupation_id'] != 0) {
            $jobs = ['profile_id' => $loggedInProfileId, 'occupation_id' => $data['profile']['occupation_id']];
            Profile\Occupation::where('profile_id', $loggedInProfileId)->delete();
            $this->model->profile_occupations()->insert($jobs);
            unset($data['profile']['occupation_id']);
        }

        if ($request->has('specialization_id')) {
            $specializationIds = $request->input('specialization_id');
            $specializations = [];
            if (count($specializationIds) > 0 && !empty($specializationIds) && is_array($specializationIds)) {
                foreach ($specializationIds as $specializationId) {
                    $specializations[] = ['profile_id' => $loggedInProfileId, 'specialization_id' => $specializationId];
                }
                if (count($specializations)) {
                    Profile\Specialization::where('profile_id', $loggedInProfileId)->delete();
                    $this->model->profile_specializations()->insert($specializations);
                }
            } else {
                Profile\Specialization::where('profile_id', $loggedInProfileId)->delete();
            }
        }

        if ($request->has('interested_collection_id')) {
            $interestedCollectionIds = $request->input('interested_collection_id');
            $interestedCollections = [];
            if (count($interestedCollectionIds) > 0 && !empty($interestedCollectionIds) && is_array($interestedCollectionIds)) {
                foreach ($interestedCollectionIds as $interestedCollectionId) {
                    $interestedCollections[] = ['profile_id' => $loggedInProfileId, 'interested_collection_id' => $interestedCollectionId];
                }
                if (count($interestedCollections)) {
                    \DB::table('profiles_interested_collections')->where('profile_id', $loggedInProfileId)->delete();
                    \DB::table('profiles_interested_collections')->insert($interestedCollections);
                }
            } else {
                \DB::table('profiles_interested_collections')->where('profile_id', $loggedInProfileId)->delete();
            }
        }

        if ($request->has('cuisine_id')) {
            $cuisineIds = $request->input('cuisine_id');
            $cuisines = [];
            if (count($cuisineIds) > 0 && !empty($cuisineIds) && is_array($cuisineIds)) {
                foreach ($cuisineIds as $cuisineId) {
                    $cuisines[] = ['profile_id' => $loggedInProfileId, 'cuisine_id' => $cuisineId];
                }
                if (count($cuisines)) {
                    \DB::table('profiles_cuisines')->where('profile_id', $loggedInProfileId)->delete();
                    \DB::table('profiles_cuisines')->insert($cuisines);
                }
            } else {
                \DB::table('profiles_cuisines')->where('profile_id', $loggedInProfileId)->delete();
            }
        }
        
        if ($request->has('establishment_type_id')) {
            $establishmentTypeIds = $request->input('establishment_type_id');
            $establishmentTypes = [];
            if (count($establishmentTypeIds) > 0 && !empty($establishmentTypeIds) && is_array($establishmentTypeIds)) {

                foreach ($establishmentTypeIds as $establishmentTypeId) {
                    $establishmentTypes[] = ['profile_id' => $loggedInProfileId, 'establishment_type_id' => $establishmentTypeId];
                }
                if (count($establishmentTypes)) {
                    \DB::table('profile_establishment_types')->where('profile_id', $loggedInProfileId)->delete();
                    \DB::table('profile_establishment_types')->insert($establishmentTypes);
                }
            } else {
                \DB::table('profile_establishment_types')->where('profile_id', $loggedInProfileId)->delete();
            }
        }
        $this->model = Profile::find($request->user()->profile->id);
        $this->model->addToCache();
        $this->model->addToCacheV2();
        $this->model->addToGraph();
        $this->model->updateUserDob();
        $this->model->updateUserCuisine();
        $this->model->updateUserFoodieType();
        $this->model->updateUserSpecialization();
        return $this->sendResponse();
    }

    private function saveFileToData($key, $path, &$request, &$data, $extraKey = null)
    {
        if ($request->hasFile($key) && !is_null($extraKey)) {

            $response = $this->saveFile($path, $request, $key);
            $data['profile'][$extraKey] = json_encode($response, true);
            $data['profile'][$key] = $response['original_photo'];
        }
    }

    private function saveFile($path, &$request, $key)
    {
        $imageName = str_random("32") . ".jpg";
        $response['original_photo'] = \Storage::url($request->file($key)->storeAs($path . "/original", $imageName, ['visibility' => 'public']));
        //create a tiny image
        $path = $path . "/tiny/" . str_random(20) . ".jpg";
        $thumbnail = \Image::make($request->file($key))->resize(50, null, function ($constraint) {
            $constraint->aspectRatio();
        })->blur(1)->stream('jpg', 70);
        \Storage::disk('s3')->put($path, (string) $thumbnail, ['visibility' => 'public']);
        $response['tiny_photo'] = \Storage::url($path);
        $meta = getimagesize($request->input($key));
        $response['meta']['width'] = $meta[0];
        $response['meta']['height'] = $meta[1];
        $response['meta']['mime'] = $meta['mime'];
        $response['meta']['size'] = null;
        $response['meta']['tiny_photo'] = $response['tiny_photo'];
        if (!$response) {
            throw new \Exception("Could not save image " . $imageName . " at " . $path);
        }
        return $response;
    }

    public function image($id)
    {
        $profile = Profile::select('image')->findOrFail($id);
        $file = Profile::getImagePath($id, $profile->image);
        if (file_exists($file)) {
            return response()->file($file);
        }
    }

    public function heroImage($id)
    {
        $profile = Profile::select('id', 'hero_image')->findOrFail($id);
        $file = Profile::getHeroImagePath($id, $profile->hero_image);
        if (file_exists($file)) {
            return response()->file($file);
        }
    }

    public function dishImages($id)
    {
        $profile = Profile::select('hero_image')->findOrFail($id);
        return response()->file(storage_path("app/" . $profile->hero_image));
    }

    public function follow(Request $request)
    {
        $channel_owner_profile_id = $request->input('id');
        //$request->user()->profile->follow($id);
        $channel_owner = \App\Recipe\Profile::find($channel_owner_profile_id);
        if (!$channel_owner) {
            throw new ModelNotFoundException();
        }

        if ($channel_owner_profile_id == $request->user()->profile->id) {
            return $this->sendError("You can not follow yourself.");
        }
        $profile_id = $request->user()->profile->id;

        $this->model = $request->user()->completeProfile->subscribeNetworkOf($channel_owner);

        // add profiles the logged in user is following
        Redis::sAdd("following:profile:" . $profile_id, $channel_owner_profile_id);

        // add profiles that are following $channel_owner in redis
        Redis::sAdd("followers:profile:" . $channel_owner_profile_id, $profile_id);

        if (!$this->model) {
            $this->sendError("You are already following this profile.");
        }

        event(new Follow($channel_owner, $request->user()->profile));

        Redis::sRem('suggested:profile:' . $profile_id, $channel_owner_profile_id);

        $subscriber = new Subscriber();
        $subscriber->followProfileSuggestion((int)$profile_id, (int)$channel_owner_profile_id);

        return $this->sendResponse();
    }

    public function unfollow(Request $request)
    {
        $channel_owner_profile_id = $request->input('id');
        //$request->user()->profile->follow($id);
        $channel_owner = Profile::find($channel_owner_profile_id);
        if (!$channel_owner) {
            throw new ModelNotFoundException();
        }

        $this->model = $request->user()->completeProfile->unsubscribeNetworkOf($channel_owner);

        if (!$this->model) {
            $this->sendError("You are not following this profile.");
        }

        $profile_id = $request->user()->profile->id;

        // remove profiles the logged in user is following
        Redis::sRem("following:profile:" . $profile_id, $channel_owner_profile_id);

        // remove profiles that are following $channel_owner in redis
        Redis::sRem("followers:profile:" . $channel_owner_profile_id, $profile_id);

        $subscriber = new Subscriber();
        $subscriber->unfollowProfileSuggestion((int)$profile_id, (int)$channel_owner_profile_id);

        return $this->sendResponse();
    }

    public function fbFriends(Request $request)
    {

        $loggedinProfileId = $request->user()->profile->id;
        $loggedinUserId = $request->user()->id;

        $fb = \DB::table('social_accounts')->where('user_id', $loggedinUserId)->where('provider', 'facebook')
            ->whereNull('deleted_at')->first();
        if ($fb == null) {
            $this->model = 0;
            return $this->sendError('Social account does not exists');
        }
        $client = new \GuzzleHttp\Client();
        try {
            $res = $client->request('GET', 'https://graph.facebook.com/v2.12/' . $fb->provider_user_id . '/friends?access_token=' . $fb->access_token);
        } catch (\Exception $e) {
            $res = null;
            \Log::error('Token Expired');
        }

        if (!$res) {
            $this->model = 0;
            return $this->sendError('Facebook access token expired');
        }

        $graphResponse = json_decode($res->getBody(), true);
        $friendsFbId = [];
        foreach ($graphResponse['data']  as $f) {
            $friendsFbId[] = $f['id'];
        }

        $friendsUserIds = \DB::table('social_accounts')->where('provider', 'facebook')->whereIn('provider_user_id', $friendsFbId)
            ->pluck('user_id');
        $profiles = \App\Recipe\Profile::whereIn('user_id', $friendsUserIds)->get();

        $this->model = $profiles;
        return $this->sendResponse();
    }

    private function getFollowers($id, $loggedInProfileId)
    {
        $followers = Profile::getFollowers($id);
        if (!$followers) {
            throw new ModelNotFoundException("Followers not found.");
        }

        $followerProfileIds = $followers->pluck('id')->toArray();
        //build network names
        $networks = [];
        foreach ($followerProfileIds as $profileId) {
            if ($profileId != $loggedInProfileId) {
                $networks[] = 'network.' . $profileId;
            }
        }
        $alreadySubscribed = Subscriber::where('profile_id', $loggedInProfileId)->whereIn('channel_name', $networks)
            ->whereNull('deleted_at')->get();
        $result = [];
        foreach ($followers as &$profile) {
            $temp = $profile->toArray();
            $temp['isFollowing'] = false;
            $temp['self'] = false;
            $result[] = $temp;
        }

        if ($alreadySubscribed->count() > 0) {
            $alreadySubscribed = $alreadySubscribed->keyBy('channel_name');
            foreach ($result as &$profile) {
                if ($profile['id'] === $loggedInProfileId) {
                    $profile['self'] = true;
                    continue;
                }

                $channel = $alreadySubscribed->get('network.' . $profile['id']);
                if ($channel === null) {
                    continue;
                }

                $profile['isFollowing'] = true;
            }
        }

        return $result;
    }

    public function followers(Request $request, $id)
    {
        $this->model = $this->getFollowerList($request, $id);
        return $this->sendResponse();
    }

    private function getFollowing($id, $loggedInProfileId, $page)
    {
        $profileIds = Redis::sMembers("following:profile:$id");

        $count = count($profileIds);

        if ($count > 0 && Redis::sIsMember("following:profile:" . $id, $id)) {
            $count = $count - 1;
        }

        $profileIds = array_slice($profileIds, ($page - 1) * 20, 20);
        foreach ($profileIds as $key => $value) {
            if (str_contains($value, "company")) {
                $profileIds[$key] = "company:small:" . last(explode(".", $value));
                continue;
            }
            if ($id == $value) {
                unset($profileIds[$key]);
                continue;
            }
            $profileIds[$key] = "profile:small:" . $value;
        }
        $following = [];
        if (count($profileIds) > 0) {
            $following = Redis::mget($profileIds);
        }
        foreach ($following as &$profile) {
            if (is_null($profile)) {
                continue;
            }
            $profile = json_decode($profile);
            $key = "following:profile:$loggedInProfileId";
            $profile->type = isset($profile->profileId) ? "company" : "profile";
            $value = isset($profile->profileId) ? "company." : null;
            $value .= $profile->id;
            $profile->isFollowing =  Redis::sIsMember($key, $value) === 1;
        }
        return ['count' => $count, 'profile' => $following];
    }

    public function following(Request $request, $id)
    {
        $page = $request->has('page') ? $request->input('page') : 1;
        $this->model = $this->getFollowing($id, $request->user()->profile->id, $page);
        return $this->sendResponse();
    }

    public function all(Request $request)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $filters = $request->input('filters');
        $models = \App\Recipe\Profile::whereNull('deleted_at')->where('id', '!=', $loggedInProfileId)->orderBy('created_at', 'asc');
        $this->model = ['count' => $models->count()];
        $this->model['data'] = [];
        //paginate
        $page = $request->input('page');
        list($skip, $take) = \App\Strategies\Paginator::paginate($page);

        $models = $models->skip($skip)->take($take);
        if (empty($filters)) {
            $profiles = $models->get();

            if ($profiles->count()) {
                foreach ($profiles as $profile) {
                    $temp = $profile->toArray();
                    $temp['isFollowing'] =  Profile::isFollowing($loggedInProfileId, $profile->id);
                    $this->model['data'][] = $temp;
                }
            }

            return $this->sendResponse();
        }

        $profiles = \App\Filter\Profile::getModelIds($filters);
        $this->model['data'] = [];
        $this->model = ['count' => count($profiles)];
        $profiles = Profile::whereNull('deleted_at')->whereIn('id', $profiles)->skip($skip)->take($take)->get()->toArray();
        $loggedInProfileId = $request->user()->profile->id;
        foreach ($profiles as &$profile) {
            $profile['isFollowing'] =  Profile::isFollowing($loggedInProfileId, $profile['id']);;
            $this->model['data'][] = $profile;
        }
        return $this->sendResponse();
    }

    public function filters()
    {
        $this->model = \App\Filter::getFilters("profile");
        return $this->sendResponse();
    }

    public function recentUploads(Request $request)
    {
        $userId = $request->user()->id;

        $recentModels = ['photos' => "\App\Photo"];
        $keyPrefix = "recent:user:$userId:";

        $this->model = [];
        foreach ($recentModels as $model => $class) {
            $modelIds = Redis::lRange($keyPrefix . $model, 0, 9);
            $this->model[$model] = $class::whereIn("id", $modelIds)->get();
        }
        return $this->sendResponse();
    }

    public function getCompany($request)
    {

        $companyIds = \DB::table('companies')->whereNull('deleted_at')->select('id')
            ->where('user_id', $request->user()->id)->get()->pluck('id');
        $adminCompanyIds = \DB::table('company_users')->select('company_id')
            ->where('user_id', $request->user()->id)
            ->whereNotIn('company_id', $companyIds)->get()->pluck('company_id');
        $companyIds = $companyIds->merge($adminCompanyIds)->toArray();

        if (count($companyIds) === 0) {
            return [];
        }
        foreach ($companyIds as &$companyId) {
            $companyId = "company:small:" . $companyId;
        }
        $data = Redis::mget($companyIds);
        foreach ($data as &$company) {
            $company = json_decode($company);
        }
        return $data;
    }

    public function mutualFollowers(Request $request, $id)
    {
        $this->model = [];
        $loginProfileId = $request->user()->profile->id;
        $profileIds = Redis::SINTER("followers:profile:" . $id, "followers:profile:" . $loginProfileId);
        $data = [];
        $this->model['count'] = count($profileIds);
        $page = $request->has('page') ? $request->input('page') : 1;
        $profileIds = array_slice($profileIds, ($page - 1) * 20, 20);
        foreach ($profileIds as &$profileId) {
            $profileId = "profile:small:" . $profileId;
        }
        if (count($profileIds)) {
            $data = Redis::mget($profileIds);
        }
        foreach ($data as &$profile) {
            $profile = json_decode($profile);
        }
        $this->model['profile'] = $data;
        return $this->sendResponse();
    }

    public function tagging(Request $request)
    {
        $this->model = $this->getSearchedProfiles($request);
        return $this->sendResponse();
    }

    public function allFollowers(Request $request)
    {
        $loggedInProfileId = $request->user()->profile->id;

        $this->model = [];
        $profileIds = Redis::SMEMBERS("followers:profile:" . $loggedInProfileId);
        $this->model['count'] = count($profileIds) - Redis::sIsMember("followers:profile:" . $loggedInProfileId, $loggedInProfileId);
        $data = [];
        foreach ($profileIds as $key => $value) {
            if ($loggedInProfileId == $value) {
                unset($profileIds[$key]);
                continue;
            }
            $profileIds[$key] = "profile:small:" . $value;
        }

        if (count($profileIds) > 0) {
            $data = Redis::mget($profileIds);
        }
        $followerData = [];
        foreach ($data as &$profile) {
            if (empty($profile)) {
                continue;
            }
            $profile = json_decode($profile);
            $profile->isFollowing = Redis::sIsMember("followers:profile:" . $profile->id, $loggedInProfileId) === 1;
            $followerData[] = $profile;
        }
        $this->model['profile'] = array_filter($followerData);
        return $this->sendResponse();
    }

    public function oldtagging(Request $request)
    {
        $loggedInProfileId = $request->user()->profile->id;

        $this->model = [];
        $profileIds = Redis::SMEMBERS("followers:profile:" . $loggedInProfileId);
        //$this->model['count'] = count($profileIds);
        $data = [];
        /*
        $page = $request->has('page') ? $request->input('page') : 1;
        $profileIds = array_slice($profileIds ,($page - 1)*20 ,20 );
        */

        foreach ($profileIds as $key => $value) {
            if ($loggedInProfileId == $value) {
                unset($profileIds[$key]);
                continue;
            }
            $profileIds[$key] = "profile:small:" . $value;
        }

        if (count($profileIds) > 0) {
            $data = Redis::mget($profileIds);
        }
        foreach ($data as &$profile) {
            if (empty($profile)) {
                continue;
            }
            $profile = json_decode($profile);
            $profile->isFollowing = Redis::sIsMember("followers:profile:" . $profile->id, $loggedInProfileId) === 1;
            $profile->self = false;
        }
        $this->model = array_filter($data);
        return $this->sendResponse();
    }

    public function onboarding(Request $request)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $fixProfileIds = collect([1, 2, 10, 44, 32, 165]);
        $fixCompaniesIds = collect([111, 137]);
        $filters = [];
        $companyFilter = [];
        $keywords = $request->user()->profile->keywords;
        $keywords = explode(',', $keywords);
        foreach ($keywords as $keyword) {
            $filters['skills'][] = $keyword;
            $companyFilter['speciality'][] = $keyword;
        }
        list($skip, $take) = \App\Strategies\Paginator::paginate(1);
        $profilesIds = \App\Filter\Profile::getModelIds($filters, $skip, 9);
        $companiesIds = \App\Filter\Company::getModelIds($companyFilter, $skip, 3);
        $this->model = [];

        $profilesIds = $fixProfileIds->merge($profilesIds);

        $companiesIds = $fixCompaniesIds->merge($companiesIds);

        $companies = Company::with([])->whereIn('id', $companiesIds)->get();
        $profiles = \App\Recipe\Profile::whereNull('deleted_at')->with([])->whereIn('id', $profilesIds)
            ->where('id', '!=', $request->user()->profile->id)->get();
        $this->model['profile'] = \App\Recipe\Profile::whereNull('deleted_at')->with([])->whereNotIn('id', $profilesIds)
            ->where('id', '!=', $request->user()->profile->id)->take(15 - $profilesIds->count())->get();

        $this->model['profile'] = $profiles->merge($this->model['profile']);

        $this->model['company'] = Company::whereNull('deleted_at')->with([])->whereNotIn('id', $companiesIds)->take(5 - $companiesIds->count())->get();

        $this->model['company'] = $companies->merge($this->model['company']);

        foreach ($this->model['profile'] as &$profile) {
            $profile->isFollowing = Redis::sIsMember("followers:profile:" . $profile->id, $loggedInProfileId) === 1;
        }

        foreach ($this->model['company'] as &$company) {
            $company->isFollowing = Redis::sIsMember("following:profile:" . $loggedInProfileId, "company." . $company->id) === 1;
        }

        return $this->sendResponse();
    }

    public function sendOtp(Request $request){
        $data = $request->except([
            "_method", "_token", 'hero_image', 'image', 'resume', 'remove', 'remove_image',
            'remove_hero_image', 'verified_phone'
        ]);
        $this->model = 0;
    
        if (isset($data['profile']['phone']) && !empty($data['profile']['phone'])) {
            $profile_id = $request->user()->profile->id;
            $profile = Profile::with([])->where('id', $profile_id)->first();
            $country_code = array(trim(str_replace("+", "", $data['profile']['country_code'])), $data['profile']['country_code']);
            $existForOther = Profile::where('phone', $data['profile']['phone'])
                ->whereIn("country_code", $country_code)
                ->where('verified_phone', 1)
                ->first();
            
            if (isset($existForOther)) {
                return $this->sendError(["This number is already verified. Please try with another number or contact tagtaste for any query."]);
            } else if (($data['profile']['phone'] != $profile->phone) || $profile->verified_phone == 0) {
                $profile->update(['verified_phone' => 0]);
                $number = $data['profile']['phone'];
                $source = config("constant.PHONE_VERIFICATION");
    
                $otpCheck = OTPMaster::where("profile_id", $profile_id)->where('mobile', "=", $number)->where("created_at", ">", date("Y-m-d H:i:s", strtotime("-" . config("constant.OTP_LOGIN_TIMEOUT_MINUTES") . " minutes")))
                ->where("source", $source)->orderBy("id", "desc")
                ->where("deleted_at", null)
                ->first();
    
                if ($otpCheck == null) 
                {
                    $versionKey = 'X-VERSION';
                    $versionKeyIos = 'X-VERSION-IOS';

                    if ($request->hasHeader($versionKey)) {
                        $platform = "android";
                    } else if ($request->hasHeader($versionKeyIos)){
                        $platform = "ios";
                    } else {
                        $platform = "web";
                    }
    
                    if (strlen($number) == 13) {
                        $number = substr($number, 3);
                    }
    
                    // check for server
                    $environment = env('APP_ENV');
                    if($environment == "test")
                    {
                        $otpNo = 123456;
                    } else {
                        $otpNo = mt_rand(100000, 999999);
                        $text = $otpNo . " is your OTP to verify your number with TagTaste.";
                        if ($request->profile["country_code"] == "+91" || $request->profile["country_code"] == "91") {
                            $service = "twilio";
                            $getResp = SMS::sendSMS($request->profile["country_code"] . $data['profile']["phone"], $text, $service);
                        } else {
                            $service = "twilio";
                            $getResp = SMS::sendSMS($request->profile["country_code"] . $data['profile']["phone"], $text, $service);
                        }
                    }
    
                    $insertOtp = OTPMaster::create(["profile_id" => $profile_id, "otp" => $otpNo, "mobile" => $number, "source" => $source, "platform" => $platform ?? null, "expired_at" => date("Y-m-d H:i:s", strtotime("+5 minutes"))]);
    
                    if(!$insertOtp)
                    {
                        return $this->sendNewError("Something went wrong!");
                    }
    
                    $this->model = $profile->update(['otp' => $otpNo]);
                    $job = ((new PhoneVerify($number, $request->user()->profile))->onQueue('phone_verify'))->delay(Carbon::now()->addMinutes(5));
                    dispatch($job);
                } else {
                    return $this->sendNewError("OTP sent already. Please try again in 1 minute.");
                }
            }
        }
    
        //save the model
        if (isset($data['profile']) && !empty($data['profile'])) {
            $userId = $request->user()->id;
            try {
                $this->model = \App\Profile::where('user_id', $userId)->first();
                $this->model->update($data['profile']);
                $this->model->refresh();
                //update filters
                \App\Filter\Profile::addModel($this->model);
            } catch (\Exception $e) {
                \Log::error($e->getMessage());
                return $this->sendNewError("Could not update.");
            }
        }
    
        \App\Filter\Profile::addModel(Profile::find($request->user()->profile->id));
    
        return $this->sendNewResponse();
    }

    public function verifyOtp(Request $request){
        $loggedInProfileId = $request->user()->profile->id;
        $otp = $request->input('otp');
        $phone = $request->input('phone');
        $source = config("constant.PHONE_VERIFICATION");
        $this->model = 0;

        $otpVerification = OTPMaster::where('mobile', $phone)
            ->where("source",$source)
            ->whereNull("deleted_at")
            ->orderBy("id", "desc")
            ->first();

        if(empty($otpVerification))
        {
            return $this->sendNewError("Something went wrong! Please regenerate OTP or try other login methods.");
        }

        // check for otp attempts
        if ($otpVerification && $otpVerification->attempts >= config("constant.OTP_LOGIN_VERIFY_MAX_ATTEMPT")) {
            OTPMaster::where('mobile', $phone)->where("source",$source)->update(["deleted_at" => date("Y-m-d H:i:s")]);
            return $this->sendNewError("OTP attempts exhausted. Please regenerate OTP or try other login methods.");
        }

        if ($otpVerification && $otpVerification->otp == $otp) {
            // check for otp expiration 
            if($otpVerification->expired_at < date("Y-m-d H:i:s"))
            {
                return $this->sendNewError("OTP has expired. Please try again!");
            }

            // Update attempts
            $otpVerification->update(["attempts" => $otpVerification->attempts + 1]);

            $profileUpdate = Profile::where('id', $loggedInProfileId)->where('otp', $otp)->update(['verified_phone' => 1]);
            if($profileUpdate)
            {
                OTPMaster::where('mobile', $phone)->where("source",$source)->update(["deleted_at" => date("Y-m-d H:i:s")]);
                $this->model = "Your phone number is verified!";
            } else {
                return $this->sendNewError("Something went wrong! Please regenerate OTP or try other login methods.");
            }
        } else {
            $otpVerification->update(["attempts" => $otpVerification->attempts + 1]);
            return $this->sendNewError("Incorrect OTP entered. Please try again.");
        }
            
        return $this->sendNewResponse();
    }

    public function requestOtp(Request $request)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $otp = $request->input('otp');
        $this->model = Profile::where('id', $loggedInProfileId)->where('otp', $otp)->whereNotNull('otp')->update(['verified_phone' => 1]);

        return $this->sendResponse();
    }

    public function sendVerifyMail(Request $request)
    {
        $user = $request->user();
        $alreadyVerified = \App\Profile\User::where('id', $user->id)->whereNull('verified_at')->first();
        $this->model = false;
        if ($alreadyVerified) {
            $alreadyVerified->update(['email_token' => str_random(15)]);

            $mail = (new \App\Jobs\EmailVerification($alreadyVerified))->onQueue('emails');
            \Log::info('Queueing Verified Email...');

            dispatch($mail);
            $this->model = true;
            return $this->sendResponse();
        } else {
            return $this->sendError("Already verified");
        }
    }

    public function sendVerifyEmail(Request $request)
    {
        $source = config("constant.EMAIL_VERIFICATION");
        $email = $request->email;
        $platform = $request->platform;

        // Service called to send verification email
        $result = $this->userService->sendVerificationEmail($email, $source, $platform);
        if($result['result'] == false)
        {
            return $this->sendError($result['error']);
        }
        else
        {
            $this->model = $result['result'];
            return $this->sendResponse();
        }
    }

    public function verifyEmailOtp(Request $request)
    {
        $source = config("constant.SIGNUP_EMAIL_VERIFICATION");
        $another_source = config("constant.EMAIL_VERIFICATION");
        $email = $request->email;
        $otp = $request->otp;

        $otpVerification = OTPMaster::where('email', $email)
            ->whereIn("source",[ $source, $another_source])
            ->where("deleted_at", null)
            ->orderBy("id", "desc")
            ->first();

        if(empty($otpVerification))
        {
            return $this->sendError("Something went wrong! Please regenerate OTP or try other login methods.");
        }

        // check for otp attempts
        if ($otpVerification && $otpVerification->attempts >= config("constant.OTP_LOGIN_VERIFY_MAX_ATTEMPT")) {
            OTPMaster::where('email', $email)->whereIn("source",[ $source, $another_source])->update(["deleted_at" => date("Y-m-d H:i:s")]);
            return $this->sendError("OTP attempts exhausted. Please regenerate OTP or try other login methods.");
        }

        if ($otpVerification && $otpVerification->otp == $otp) 
        {
            // check for otp expiration 
            if($otpVerification->expired_at < date("Y-m-d H:i:s"))
            {
                return $this->sendError("OTP has expired. Please try again!");
            }

            // Update attempts
            $otpVerification->update(["attempts" => $otpVerification->attempts + 1]);

            $user_id = Profile::find($otpVerification->profile_id)->user_id;
            
            $this->model = User::where('id', $user_id)->whereNull('verified_at')->where('email', $email)->update(['verified_at' => date("Y-m-d H:i:s")]);
            if($this->model == 1)
            {
                OTPMaster::where('email', $email)->whereIn("source",[ $source, $another_source])->update(["deleted_at" => date("Y-m-d H:i:s")]);
                $this->messages = "Your email is verified!";
            }
            
            return $this->sendResponse();

        }
        else
        {
            $otpVerification->update(["attempts" => $otpVerification->attempts + 1]);
            return $this->sendError("Incorrect OTP entered. Please try again.");
        }
    }

    public function phoneVerify(Request $request)
    {
        $data = $request->except([
            "_method", "_token", 'hero_image', 'image', 'resume', 'remove', 'remove_image',
            'remove_hero_image', 'verified_phone'
        ]);
        if (isset($data['profile']['phone']) && !empty($data['profile']['phone'])) {
            $profile = Profile::with([])->where('id', $request->user()->profile->id)->first();

            $existForOther = Profile::where('phone', $data['profile']['phone'])
                ->where('verified_phone', 1)
                ->where('id', '<>', $request->user()->profile->id)
                ->first();
            if (isset($existForOther)) {
                return $this->sendError(["This number is already verified. Please try with another number or contact tagtaste for any query."]);
            } else if (($data['profile']['phone'] != $profile->phone) || $profile->verified_phone == 0) {
                $profile->update(['verified_phone' => 0]);
                $number = $data['profile']['phone'];
                if (strlen($number) == 13) {
                    $number = substr($number, 3);
                }
                $otp = \DB::table('profiles')->where('id', $request->user()->profile->id)->first();

                // check for server
                $environment = env('APP_ENV');
                if($environment == "test")
                {
                    $otpNo = 123456;
                }
                else
                {
                    $otpNo = mt_rand(100000, 999999);

                    // $otpNo = isset($otp->otp) && !is_null($otp->otp) ? $otp->otp : mt_rand(100000, 999999);
                    $text = $otpNo . " is your OTP to verify your number with TagTaste.";

                    if ($request->profile["country_code"] == "+91" || $request->profile["country_code"] == "91") {
                        $service = "twilio";
                        $getResp = SMS::sendSMS($request->profile["country_code"] . $data['profile']["phone"], $text, $service);
                    } else {
                        $service = "twilio";
                        $getResp = SMS::sendSMS($request->profile["country_code"] . $data['profile']["phone"], $text, $service);
                    }
                }

                $this->model = $profile->update(['otp' => $otpNo]);
                $job = ((new PhoneVerify($number, $request->user()->profile))->onQueue('phone_verify'))->delay(Carbon::now()->addMinutes(5));
                dispatch($job);
            }
        }

        //save the model
        if (isset($data['profile']) && !empty($data['profile'])) {
            $userId = $request->user()->id;
            try {
                $this->model = \App\Profile::where('user_id', $userId)->first();
                $this->model->update($data['profile']);
                $this->model->refresh();
                //update filters
                \App\Filter\Profile::addModel($this->model);
            } catch (\Exception $e) {
                \Log::error($e->getMessage());
                return $this->sendError("Could not update.");
            }
        }

        \App\Filter\Profile::addModel(Profile::find($request->user()->profile->id));

        return $this->sendResponse();
    }

    public function handleAvailable(Request $request)
    {
        $this->model = 0;
        $data = $request->input('handle');
        if (isset($data)) {
            $this->model = !Profile::where('handle', $data['handle'])->where('id', '!=', $request->user()->profile->id)->exists();
        }
        return $this->sendResponse();
    }

    public function followFbFriends(Request $request)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $loggedInUserProviderId = $request->input('loggedin_provider_user_id');
        $userExist = \DB::table('social_accounts')->where('provider_user_id', $loggedInUserProviderId)
            ->where('user_id', $request->user()->id)->first();

        if (!isset($userExist)) {
            return $this->sendError("The Facebook account you are trying to connect seems to be a different one, 
            please make sure you are logged in with your own Facebook account.");
        }
        $usersProviderIds = $request->input('provider_user_id');
        $user_ids = \DB::table('social_accounts')->whereIn('provider_user_id', $usersProviderIds)->get()->pluck('user_id');
        //dd($profile_ids);
        $profiles = \App\Recipe\Profile::whereIn('user_id', $user_ids)->get();
        $fbFriends = "";
        foreach ($profiles as &$profile) {
            $fbFriends = $profile->id . "," . $fbFriends;
            $profile->isFollowing = Redis::sIsMember("followers:profile:" . $profile->id, $loggedInProfileId) === 1;
        }
        \DB::table('social_accounts')->where('provider_user_id', $loggedInUserProviderId)
            ->where('user_id', $request->user()->id)->update(['fb_friends' => $fbFriends]);
        $this->model = $profiles;
        return $this->sendResponse();
    }

    public function getPremium(Request $request)
    {
        $companyIds = \DB::table('companies')->whereNull('deleted_at')->select('id')->where('is_premium', 1)->get()->pluck('id');
        $companyIds = \DB::table('company_users')->select('company_id')
            ->where('user_id', $request->user()->id)
            ->whereIn('company_id', $companyIds)->get()->pluck('company_id');

        if (count($companyIds) === 0) {
            $this->model = [];
            return $this->sendResponse();
        }
        $premiumnCompanies = [];
        foreach ($companyIds as &$companyId) {
            $premiumnCompanies[] = "company:small:" . $companyId;
        }
        $data = Redis::mget($premiumnCompanies);
        foreach ($data as &$company) {
            $company = json_decode($company);
        }
        $this->model = $data;

        return $this->sendResponse();
    }

    public function tastingCategory(Request $request)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $collaborateIds = \DB::table('collaborate_batches_assign')->where('profile_id', $loggedInProfileId)->where('begin_tasting', 1)
            ->get()->pluck('collaborate_id');
        $totalTastingDoneCount = \DB::table('collaborate_tasting_user_review')->where('profile_id', $loggedInProfileId)->whereIn('collaborate_id', $collaborateIds)->where('current_status', 3)->groupBy('batch_id')->count();
        $categoryIds = Collaborate::whereIn('id', $collaborateIds)->get()->pluck('category_id');
        $categories = \DB::table('collaborate_categories')->whereIn('id', $categoryIds)->get();
        $this->model = [];
        $this->model['tastingDone'] = $totalTastingDoneCount;
        $this->model['category'] = $categories;

        return $this->sendResponse();
    }

    public function updateDonation(Request $request){
        $profileId = $request->user()->profile->id;
        $this->model = false;
        if($request->has("is_donation")){
            $this->model = true;
            $isDonation = $request->is_donation;
            if($isDonation){
                $organisationId = $request->donation_organisation['id'] ?? null;
                $this->model = true;
                if(is_null($organisationId)){
                    $this->model = false;
                    return $this->sendNewError("Organisation detail missing.");
                }
                $data = ["is_donation"=>true];
                \App\Profile::where('id', $profileId)->update($data);

                DonationProfileMapping::where('profile_id',$profileId)->whereNull('deleted_at')->update(["deleted_at"=>Carbon::now()]);

                $data = ["profile_id"=>$profileId, "donation_organisation_id"=>$organisationId, "created_at"=>Carbon::now(), "updated_at"=>Carbon::now()];
                DonationProfileMapping::updateOrInsert($data);
                
            }else{                
                $data = ["is_donation"=>false];
                \App\Profile::where('id', $profileId)->update($data);

                DonationProfileMapping::where('profile_id',$profileId)->whereNull('deleted_at')->update(["deleted_at"=>Carbon::now()]);
            }
        }
        return $this->sendNewResponse();
    }
    
    public function addAllergens(Request $request)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $this->model = false;
        if ($request->has('allergens_id')) {
            $allergensIds = $request->input('allergens_id');
            $allergens = [];
            if (count($allergensIds) > 0 && !empty($allergensIds) && is_array($allergensIds)) {

                foreach ($allergensIds as $allergensId) {
                    $allergens[] = ['profile_id' => $loggedInProfileId, 'allergens_id' => $allergensId];
                }
                if (count($allergens)) {
                    \DB::table('profiles_allergens')->where('profile_id', $loggedInProfileId)->delete();
                    \DB::table('profiles_allergens')->insert($allergens);
                }
            } else {
                \DB::table('profiles_allergens')->where('profile_id', $loggedInProfileId)->delete();
            }
        }
        $allergenIds = \DB::table('profiles_allergens')->where('profile_id', $loggedInProfileId)->get()->pluck('allergens_id');
        $this->model = \DB::table('allergens')->whereIn('id', $allergenIds)->get();
        return $this->sendResponse();
    }

    public function foodieType(Request $request)
    {
        $this->model = \DB::table('foodie_type')->orderBy('order')->get();
        return $this->sendResponse();
    }

    public function establishmentType()
    {
        $this->model = \DB::table('establishment_types')->get();
        return $this->sendResponse();
    }

    public function interestedCollections()
    {
        $this->model = \DB::table('interested_collections')->where('featured', 1)->get();
        return $this->sendResponse();
    }

    public function nestedFollow(Request $request)
    {
        $channelOwnerProfileIds = $request->input('id');
        $this->model = false;
        foreach ($channelOwnerProfileIds as $channelOwnerProfileId) {
            //$request->user()->profile->follow($id);
            $channelOwner = \App\Recipe\Profile::find($channelOwnerProfileId);
            if (!$channelOwner) {
                throw new ModelNotFoundException();
            }

            $data = $request->user()->completeProfile->subscribeNetworkOf($channelOwner);
            $profileId = $request->user()->profile->id;

            //profiles the logged in user is following
            Redis::sAdd("following:profile:" . $profileId, $channelOwnerProfileId);

            //profiles that are following $channelOwner
            Redis::sAdd("followers:profile:" . $channelOwnerProfileId, $profileId);

            if (!$data) {
                continue;
            }
            $this->model = $data;
            event(new Follow($channelOwner, $request->user()->profile));

            Redis::sRem('suggested:profile:' . $request->user()->profile->id, $channelOwnerProfileId);
        }
        if (isset($this->model) && $this->model != false)
            $this->model = true;
        return $this->sendResponse();
    }

    public function getAllergens(Request $request)
    {
        $loggedInProfileId = $request->user()->profile->id;

        $allergenIds = \DB::table('profiles_allergens')->where('profile_id', $loggedInProfileId)->get()->pluck('allergens_id');
        $this->model = \DB::table('allergens')->whereIn('id', $allergenIds)->get();
        return $this->sendResponse();
    }

    public function uploadDocument(Request $request)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $this->validatePhotos($request);
        $images = $this->changeInJson($request->images);
        \DB::table('profile_documents')->where('profile_id', $loggedInProfileId)->delete();
        $this->model  = \DB::table('profile_documents')->insert(['profile_id' => $loggedInProfileId, 'document_meta' => $images]);
        return $this->sendResponse();
    }
    public function deleteDocument(Request $request)
    {
        $loggedInProfile = $request->user()->profile->id;
        $this->model = \DB::table('profile_documents')->where('profile_id', $loggedInProfile)->delete();
        return $this->sendResponse();
    }
    public function validatePhotos($request)
    {
        if (!$request->has('images') || is_null($request->input('images')) || !is_array($request->input('images'))) {
            return $this->sendError("wrong format");
        }
    }
    public function changeInJson($images)
    {
        $data = [];
        foreach ($images as $image) {
            $image = json_decode($image);
            $data[] = ['original_photo' => $image->original_photo, 'tiny_photo' => $image->tiny_photo, 'meta' => $image->meta];
        }
        return json_encode($data);
    }

    public function updateDetails(Request $request, $id)
    {
        $data = $request->only(["verified", "is_tasting_expert", "is_premium"]);

        foreach ($data as $key => $value) {
            if (is_null($value)) {
                unset($data[$key]);
            } else {
                $data[$key] = (int)$value;
            }
        }

        if (is_null($data) || empty($data)) {
            return $this->sendError("Please provide valid params such as 'verified','is_tasting_expert', 'is_premium'");
        } else {
            $this->model = \App\Profile::where('id', $id)->first();
            if ($this->model) {
                $this->model->update($data);
                $this->model->addToCache();
                $this->model->addToCacheV2();
                $this->model->addToGraph();
                return $this->sendResponse();
            } else {
                return $this->sendError("Invalid profile id.");
            }
        }
    }

    public function reviewHelperText(Request $request)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $tasting_instructions = $request->tasting_instructions == 0 || $request->tasting_instructions == 1 ? $request->tasting_instructions : null;

        if ($tasting_instructions == null) {
            return $this->sendError('Invalid Input option received');
        }

        $this->model = Profile::where('id', $loggedInProfileId)
            ->update(['tasting_instructions' => $tasting_instructions]);
        return $this->sendResponse();
    }

    public function shortProfile(Request $request)
    {

        $getData = DB::table("users")->join("profiles", "profiles.user_id", "users.id")->selectRaw("users.name,users.email, users.verified_at, profiles.*, users.id as user_id,  COUNT(DISTINCT message_recepients.chat_id) as message_count")->leftJoin("message_recepients", function ($q) {
            $q->on("message_recepients.recepient_id", "=", "profiles.id");
            $q->whereNull("message_recepients.last_seen");
            $q->whereNull("read_on");
        })
            // ->groupBy("profiles.id")
            ->where("users.id", $request->user()->id)->get();

        foreach ($getData as $v) {
            $data["name"] = $v->name;
            $data["email"] = $v->email;
            $data["verified_at"] = $v->verified_at;
            $prf = [];
            $prf["id"] = $v->id;
            $prf["tagline"] = $v->tagline;
            $prf["about"] = $v->about;
            $prf["phone"] = $v->phone;
            $prf["dob"] = $v->dob;
            $prf["user_id"] = $v->user_id;
            $prf["created_at"] = $v->created_at;
            $prf["country"] = $v->country;
            $prf["verified"] = $v->verified;
            $prf["country_code"] = $v->country_code;
            $prf["pincode"] = $v->pincode;
            $prf["handle"] = $v->handle;
            $prf["city"] = $v->city;
            $prf["dob_private"] = $v->dob_private;
            $prf["verified_phone"] = $v->verified_phone;
            $prf["gender"] = $v->gender;
            $prf["image_meta"] = $v->image_meta;
            $prf["hero_image_meta"] = $v->hero_image_meta;
            $prf["onboarding_complete"] = $v->onboarding_complete;
            $prf["is_tasting_expert"] = $v->is_tasting_expert;
            $prf["is_ttfb_user"] = $v->is_ttfb_user;
            $prf["is_premium"] = $v->is_premium;
            $prf["hometown"] = $v->hometown;
            $prf["imageUrl"] = $v->image;
            $followingCount = Redis::SCARD("following:profile:" . $v->id);
            if ($followingCount > 0 && Redis::sIsMember("following:profile:" . $v->id, $v->id)) {
                $followingCount = $followingCount - 1;
            }
            $prf["followingProfiles"]["count"] = $followingCount;

            $followerCount = Redis::SCARD("followers:profile:" . $v->id);
            if (Redis::sIsMember("followers:profile:" . $v->id, $v->id)) {
                $followerCount = $followerCount - 1;
            }

            if ($followerCount === 0) {
                $prf["followerProfiles"] =  ['count' => 0, 'profiles' => null];
            } else {
                $prf["followerProfiles"] =  ['count' => $followerCount];
            }

            $prf["name"] = $v->name;
            $prf["notificationCount"] = 0;
            $prf["messageCount"] = $v->message_count;
            // $prf["profileCompletion"] = json_decode('{
            //     "complete_percentage": 100,
            //     "overall_percentage": 100,
            //     "mandatory_remaining_field": [],
            //     "optional_remaining_field": [],
            //     "additional_optional_field": [],
            //     "mandatory_field_for_collaboration_apply": [],
            //     "mandatory_field_for_collaboration_apply_v1": [],
            //     "mandatory_field_for_campus_connect": [],
            //     "mandatory_field_for_get_product_sample": []
            // }', true);
            $proCompletion = Profile::where("id", $request->user()->profile->id)->get()->map->append("profileCompletion");
            // //ProfileCompletion
            foreach ($proCompletion as $v) {
                $prf["profileCompletion"] = $v->profileCompletion;
            }
            $data["profile"] = $prf;
        }

        // $data['companies'] = $this->getCompany($request);
        return response()->json($data);
    }

    public function getMeta($profileId)
    {

        $productCount =  \DB::table('public_product_user_review')->where('profile_id', $profileId)->where('current_status', 2)->groupBy("product_id")->get()->count();
        $collabProductCount = \DB::table('collaborate_tasting_user_review')->where('profile_id', $profileId)->where('current_status', 3)->groupBy("batch_id")->get()->count();
        $total["total_review_count"] = ($productCount + $collabProductCount);
        $total["public_review"] = $productCount;
        $total["private_review"] = $collabProductCount;

        return $this->sendNewResponse($total);
    }
}
