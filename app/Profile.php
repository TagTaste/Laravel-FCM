<?php

namespace App;

use App\Channel\Payload;
use App\Events\SuggestionEngineEvent;
use App\Payment\PaymentLinks;
use App\Traits\PushesToChannel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Redis;

class Profile extends Model
{
    use PushesToChannel, Notifiable, SoftDeletes;

    protected $fillable = [
        'tagline', 'about', 'image', 'hero_image', 'phone', 'country_code', 'address', 'dob', 'interests', 'website_url',
        'blog_url', 'facebook_url', 'linkedin_url', 'instagram_link', 'pinterest_url', 'twitter_url', 'google_url', 'other_links',
        'ingredients', 'favourite_moments', 'verified', 'youtube_channel', 'followers', 'following', 'user_id', 'created_at',
        'pincode', 'handle', 'expertise', //a.k.a spokenLanguages
        'keywords', 'city', 'country', 'resume', 'email_private', 'address_private', 'phone_private', 'dob_private', 'affiliations',
        'style_image', 'style_hero_image', 'otp', 'verified_phone', 'onboarding_step', 'gender', 'foodie_type_id', 'onboarding_complete', "image_meta", "hero_image_meta", 'is_facebook_connected', 'is_linkedin_connected', 'is_google_connected', 'is_tasting_expert', 'is_ttfb_user',
        // palate data
        'palate_visibility', 'palate_iteration', 'palate_iteration_status', 'palate_test_status', 'tasting_instructions', 'is_premium', 'hometown', 'is_sensory_trained', 'is_paid_taster'
    ];

    // palate_visibility 1 visible to all, 0 hidden from everyone, 2 visible to people I follow
    // palate_iteration 1,2,3,4...n iteration of palate test
    // palate_iteration_status 0/1(incomplete/completed)
    // palate_test_status 0/1(inactive/active)

    //if you add a relation here, make sure you remove it from
    //App\Recommend to prevent any unwanted results like nested looping.
    protected $with = [
        'awards', 'certifications', 'tvshows', 'books', 'patents', 'projects', 'professional', 'training',
        'profile_occupations', 'profile_specializations'
    ];

    protected $visible = [
        'id', 'tagline', 'about', 'phone', 'country_code', 'address', 'dob', 'interests',
        'imageUrl', 'heroImageUrl', 'website_url', 'blog_url', 'facebook_url', 'linkedin_url', 'google_url', 'instagram_link',
        'pinterest_url', 'other_links', 'twitter_url', 'ingredients', 'favourite_moments', 'verified', 'youtube_channel',
        'interested_in_opportunities', 'followers', 'following', 'experience', 'awards', 'certifications', 'tvshows', 'books',
        'patents', 'followingProfiles', 'followerProfiles', 'mutualFollowers', 'name', 'photos', 'education', 'address', 'projects', 'professional',
        'created_at', 'pincode', 'isTagged', 'handle', 'expertise', 'keywords', 'city', 'country', 'resumeUrl', 'email_private',
        'address_private', 'phone_private', 'dob_private', 'training', 'affiliations', 'style_image', 'style_hero_image',
        'verified_phone', 'notificationCount', 'messageCount', 'addPassword', 'unreadNotificationCount', 'onboarding_step', 'isFollowedBy', 'profileCompletion', 'batchesCount', 'gender', 'user_id', 'newBatchesCount', 'shippingaddress',
        'profile_occupations', 'profile_specializations', 'is_veteran', 'is_expert', 'foodie_type_id', 'foodie_type', 'establishment_types', 'cuisines', 'interested_collections',
        'onboarding_complete', "image_meta", "hero_image_meta", 'fb_info', 'is_facebook_connected', 'is_linkedin_connected', 'is_google_connected', 'is_tasting_expert', 'reviewCount', 'allergens', 'totalPostCount', 'imagePostCount', 'document_meta', 'is_ttfb_user', 'palate_sensitivity', 'palate_visibility', 'palate_test_status', 'tasting_instructions', 'is_premium', 'hometown', 'is_paid_taster', 'is_sensory_trained', 'payment'
    ];


    protected $appends = [
        'imageUrl', 'shippingaddress', 'heroImageUrl', 'followingProfiles', 'followerProfiles', 'isTagged', 'name',
        'resumeUrl', 'experience', 'education', 'mutualFollowers', 'notificationCount', 'messageCount', 'addPassword', 'unreadNotificationCount',
        'remainingMessages', 'isFollowedBy', 'isMessageAble', 'profileCompletion', 'batchesCount', 'newBatchesCount', 'foodie_type', 'establishment_types',
        'cuisines', 'allergens', 'interested_collections', 'fb_info', 'reviewCount', 'privateReviewCount', 'surveyCount', 'totalPostCount', 'amount', 'imagePostCount', 'document_meta', 'palate_sensitivity', 'shoutoutPostCount', 'shoutoutSharePostCount', 'collaboratePostCount', 'collaborateSharePostCount', 'photoPostCount', 'photoSharePostCount', 'pollingPostCount', 'pollingSharePostCount', 'productSharePostCount', 'payment'
    ];

    /**
        profile completion mandatory field
        private $profileCompletionMandatoryField = ['name', 'handle', 'imageUrl', 'tagline', 'dob', 'phone', 'verified_phone', 'city', 'country','is_facebook_connected','is_linkedin_connected', 'keywords', 'expertise', 'experience', 'education'];
     **/
    private $profileCompletionMandatoryField = ['name', 'handle', 'tagline', 'dob', 'city', 'gender', 'foodie_type_id', 'profile_occupations', 'cuisines'];


    /**
        profile completion optional field
        private $profileCompletionOptionalField = ['address','website_url', 'heroImageUrl', 'pincode', 'resumeUrl', 'affiliations', 'tvshows',
        'awards','training','projects','patents','publications'];
     **/
    private $profileCompletionOptionalField = ['keywords', 'imageUrl', 'phone'];

    private $profileCompletionExtraOptionalField = ['heroImageUrl', 'website_url', 'about', 'profile_specializations', 'allergens', 'expertise', 'affiliations', 'experience', 'education', 'training'];

    private $profileCompletionMandatoryFieldForCollaborationApply = ['dob', 'name', 'gender', 'profile_occupations', 'phone', 'verified_phone'];


    private $profileCompletionMandatoryFieldForCampusConnect = ['phone'];

    private $profileCompletionMandatoryFieldForGetProductSample = ['shippingaddress', 'phone'];

    private $profileCompletionMandatoryFieldForCollaborationApplyV1 = ['phone', 'verified_phone'];


    public static function boot()
    {
        parent::boot();

        self::created(function (Profile $profile) {

            //add default handle when profile is created

            $name = $profile->name;
            $name = iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $name);
            $name = preg_replace("/[^ \w]+/", '', $name);
            $name = str_replace(' ', '_', $name);
            $name = str_replace('__', '_', $name);
            $name = rtrim($name, '_');
            $hanleExist = Profile::where('handle', $name)->exists();
            if ($hanleExist) {
                $name = $name . '_' . mt_rand(100, 999);
            }
            $profile->update(['handle' => $name]);


            //create profile's feed channel
            //$feed = Channel::create(['name'=>"feed." . $profile->id,'profile_id'=>$profile->id]);

            //subscribe to own feed channel
            $profile->subscribe("feed", $profile);

            //create profile's public channel
            //$public  = Channel::create(['name'=>"public." . $profile->id,'profile_id'=>$profile->id]);
            //subscribe to own public channel
            $profile->subscribe("public", $profile);

            // anything below this condition would not be executed
            // for the admin user.
            if ($profile->id === 1) {
                return;
            }
            //create the document for searching
            \App\Documents\Profile::create($profile);
            //bad call inside, would be fixed soon
            $profile->addToCache();
            $profile->addToCacheV2();
            $profile->addToGraph();
            $profile->addUserDob();
            $profile->addUserCuisine();
            $profile->addUserFoodieType();
            $profile->addUserSpecialization();
            // event(new SuggestionEngineEvent($profile, 'create'));

        });

        self::updated(function (Profile $profile) {
            //bad call inside, would be fixed soon
            $profile->addToCache();
            $profile->addToCacheV2();
            $profile->addToGraph();
            $profile->updateUserDob();
            $profile->updateUserCuisine();
            $profile->updateUserFoodieType();
            $profile->updateUserSpecialization();

            //this would delete the old document.
            \App\Documents\Profile::create($profile);
            //            event(new SuggestionEngineEvent($profile, 'update'));

        });

        self::deleting(function ($profile) {
            \App\Filter\Profile::removeModel($profile->id);
            \App\Documents\Profile::delete($profile);
            $profile->removeFromCache();
        });
    }

    public function addToCache()
    {
        $smallProfile = \App\Recipe\Profile::find($this->id);
        Redis::set("profile:small:" . $this->id, $smallProfile->toJson());
    }

    public function addToCacheV2()
    {
        $smallProfile = \App\V2\Profile::find($this->id);
        Redis::set("profile:small:" . $this->id . ":V2", $smallProfile->toJson());
    }

    public function addToGraph()
    {
        $data = \App\V2\Profile::find($this->id)->toArray();

        foreach ($data as $key => $value) {
            if (in_array($key, ["verified", "is_tasting_expert", "is_premium"])) {
                continue;
            }

            if (is_null($value) || $value == '')
                unset($data[$key]);
        }

        if (isset($data['id'])) {
            $data['profile_id'] = $data['id'];
        }

        $user = \App\Neo4j\User::where('user_id', (int)$data['user_id'])->first();
        if (!$user) {
            \App\Neo4j\User::create($data);
        } else {
            unset($data['id']);
            \App\Neo4j\User::where('user_id', (int)$data['user_id'])->update($data);
        }
    }

    public function addUserDob()
    {
        if ($this->dob) {
            $time = strtotime($this->dob);
            $date = date('d-m', $time);
            $user = \App\Neo4j\User::where('user_id', (int)$this->user_id)->first();
            if ($user) {
                $date_type = \App\Neo4j\DateOfBirth::where('dob', $date)->first();
                $date_type_have_user = $date_type->have->where('user_id', (int)$this->user_id)->first();
                if (!$date_type_have_user) {
                    $relation = $date_type->have()->attach($user);
                    $relation->status = 1;
                    $relation->statusValue = "have";
                    $relation->save();
                } else {
                    $relation = $date_type->have()->edge($user);
                    $relation->status = 1;
                    $relation->statusValue = "have";
                    $relation->save();
                }
            }
        }
    }

    public function updateUserDob()
    {
        $user = \App\Neo4j\User::where('user_id', $this->user_id)->first();
        if (isset($user->dateOfBirth)) {
            foreach ($user->dateOfBirth as $key => $value) {
                $detach_result = $value->have()->detach($user);
            }
        }
        if ($this->dob) {
            $time = strtotime($this->dob);
            $date = date('d-m', $time);
            if ($user) {
                $date_type = \App\Neo4j\DateOfBirth::where('dob', $date)->first();
                $date_type_have_user = $date_type->have->where('user_id', $this->user_id)->first();
                if (!$date_type_have_user) {
                    $relation = $date_type->have()->attach($user);
                    $relation->status = 1;
                    $relation->statusValue = "have";
                    $relation->save();
                } else {
                    $relation = $date_type->have()->edge($user);
                    $relation->status = 1;
                    $relation->statusValue = "have";
                    $relation->save();
                }
            }
        }
    }

    public function addUserCuisine()
    {
        if ($this->cuisines->pluck('id') && $this->cuisines->pluck('id')->count()) {
            $user = \App\Neo4j\User::where('user_id', (int)$this->user_id)->first();
            foreach ($this->cuisines->pluck('id') as $key => $value) {
                $cuisine_type = \App\Neo4j\Cuisines::where('cuisine_id', $value)->first();
                $cuisine_type_have_user = $cuisine_type->have->where('user_id', (int)$this->user_id)->first();
                if (!$cuisine_type_have_user) {
                    $relation = $cuisine_type->have()->attach($user);
                    $relation->status = 1;
                    $relation->statusValue = "have";
                    $relation->save();
                } else {
                    $relation = $cuisine_type->have()->edge($user);
                    $relation->status = 1;
                    $relation->statusValue = "have";
                    $relation->save();
                }
            }
        }
    }

    public function updateUserCuisine()
    {
        $user = \App\Neo4j\User::where('user_id', (int)$this->user_id)->first();
        // if (isset($user->cuisines) && $user->cuisines->count()) {
        //     // $detach_result = $user->dateOfBirth->have()->detach($user);
        // }

        if ($this->cuisines->pluck('id') && $this->cuisines->pluck('id')->count()) {
            foreach ($this->cuisines->pluck('id') as $key => $value) {
                $cuisine_type = \App\Neo4j\Cuisines::where('cuisine_id', $value)->first();
                $cuisine_type_have_user = $cuisine_type->have->where('user_id', (int)$this->user_id)->first();
                if (!$cuisine_type_have_user) {
                    $relation = $cuisine_type->have()->attach($user);
                    $relation->status = 1;
                    $relation->statusValue = "have";
                    $relation->save();
                } else {
                    $relation = $cuisine_type->have()->edge($user);
                    $relation->status = 1;
                    $relation->statusValue = "have";
                    $relation->save();
                }
            }
        }
    }

    public function addUserFoodieType()
    {
        if ($this->foodieType && isset($this->foodieType->id)) {
            $foodie_type_id = $this->foodieType->id;
            $user = \App\Neo4j\User::where('user_id', (int)$this->user_id)->first();
            if ($user) {
                $foodie_type = \App\Neo4j\FoodieType::where('foodie_type_id', $foodie_type_id)->first();
                $foodie_type_have_user = $foodie_type->have->where('user_id', $this->user_id)->first();
                if (!$foodie_type_have_user) {
                    $relation = $foodie_type->have()->attach($user);
                    $relation->status = 1;
                    $relation->statusValue = "have";
                    $relation->save();
                } else {
                    $relation = $foodie_type->have()->edge($user);
                    $relation->status = 0;
                    $relation->statusValue = "haven't";
                    $relation->save();
                }
            }
        }
    }

    public function updateUserFoodieType()
    {
        $user = \App\Neo4j\User::where('user_id', $this->user_id)->first();
        if (isset($user->foodieType)) {
            $detach_result = $user->foodieType->have()->detach($user);
        }

        if ($this->foodieType && isset($this->foodieType->id)) {
            $foodie_type_id = $this->foodieType->id;
            if ($user) {
                $foodie_type = \App\Neo4j\FoodieType::where('foodie_type_id', $foodie_type_id)->first();
                $foodie_type_have_user = $foodie_type->have->where('user_id', $this->user_id)->first();
                if (!$foodie_type_have_user) {
                    $relation = $foodie_type->have()->attach($user);
                    $relation->status = 1;
                    $relation->statusValue = "have";
                    $relation->save();
                } else {
                    $relation = $foodie_type->have()->edge($user);
                    $relation->status = 1;
                    $relation->statusValue = "have";
                    $relation->save();
                }
            }
        }
    }

    public function addUserSpecialization()
    {
        if ($this->profile_specializations->pluck('id') && $this->profile_specializations->pluck('id')->count()) {
            $user = \App\Neo4j\User::where('user_id', (int)$this->user_id)->first();
            foreach ($this->profile_specializations->pluck('id') as $key => $value) {
                $specialization_type = \App\Neo4j\Specialization::where('specialization_id', $value)->first();
                $specialization_type_have_user = $specialization_type
                    ->have
                    ->where('user_id', (int)$this->user_id)
                    ->first();
                if (!$specialization_type_have_user) {
                    $relation = $specialization_type->have()->attach($user);
                    $relation->status = 1;
                    $relation->statusValue = "have";
                    $relation->save();
                } else {
                    $relation = $specialization_type->have()->edge($user);
                    $relation->status = 1;
                    $relation->statusValue = "have";
                    $relation->save();
                }
            }
        }
    }

    public function updateUserSpecialization()
    {
        $user = \App\Neo4j\User::where('user_id', (int)$this->user_id)->first();
        // if (isset($user->profile_specializations) && $user->profile_specializations->count()) {
        //     // $detach_result = $user->profile_specializations->have()->detach($user);
        // }

        if ($this->profile_specializations->pluck('id') && $this->profile_specializations->pluck('id')->count()) {
            foreach ($this->profile_specializations->pluck('id') as $key => $value) {
                $specialization_type = \App\Neo4j\Specialization::where('specialization_id', $value)->first();
                $specialization_type_have_user = $specialization_type
                    ->have
                    ->where('user_id', (int)$this->user_id)
                    ->first();
                if (!$specialization_type_have_user) {
                    $relation = $specialization_type->have()->attach($user);
                    $relation->status = 1;
                    $relation->statusValue = "have";
                    $relation->save();
                } else {
                    $relation = $specialization_type->have()->edge($user);
                    $relation->status = 1;
                    $relation->statusValue = "have";
                    $relation->save();
                }
            }
        }
    }

    public static function getFromCache($id)
    {
        return Redis::get('profile:small:' . $id);
    }

    public static function getFromCacheV2($id)
    {
        return Redis::connection('V2')->get('profile:small:' . $id);
    }

    public function removeFromCache()
    {
        Redis::connection('V2')->del('profile:small:' . $this->id . ":V2");
        return Redis::del('profile:small:' . $this->id);
    }

    public static function getMultipleFromCache($ids = [])
    {
        $keyPreifx = "profile:small:";
        foreach ($ids as &$id) {
            $id = $keyPreifx . $id;
        }
        $profiles = Redis::mget($ids);
        if (count(array_filter($profiles)) == 0) {
            return false;
        }
        foreach ($profiles as $index => &$profile) {
            $profile = json_decode($profile);
        }

        return $profiles;
    }

    public static function getMultipleFromCacheV2($ids = [])
    {
        $keyPreifx = "profile:small:";
        foreach ($ids as &$id) {
            $id = $keyPreifx . $id . ":V2";
        }
        $profiles = Redis::connection('V2')->mget($ids);
        if (count(array_filter($profiles)) == 0) {
            return false;
        }
        foreach ($profiles as $index => &$profile) {
            $data = json_decode($profile);
            if (!is_null($data)) {
                $profile = array(
                    "id" => $data->id,
                    "name" => $data->name,
                    "handle" => $data->handle
                );
            } else {
                $profile = array(
                    "id" => 0,
                    "name" => "",
                    "handle" => ""
                );
            }
        }

        return $profiles;
    }

    public static function getMultipleFromCacheFeed($ids = [])
    {
        $keyPreifx = "profile:small:";
        foreach ($ids as &$id) {
            $id = $keyPreifx . $id;
        }
        $profiles = Redis::mget($ids);

        if (count(array_filter($profiles)) == 0) {
            return false;
        }

        foreach ($profiles as $index => &$profile) {
            $data = json_decode($profile);
            $profile = array(
                "id" => $data->id,
                "name" => $data->name,
                "handle" => $data->handle
            );
        }
        return $profiles;
    }



    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getNameAttribute()
    {
        try {
            return $this->user->name;
        } catch (\Exception $e) {
            $message = "Accessing deleted profile " . $this->id;
            \Log::warning($message);
            $client =  new \GuzzleHttp\Client();
            $hook = 'https://hooks.slack.com/services/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';
            if ($hook) {
                $client->request(
                    'POST',
                    $hook,
                    [
                        'json' =>
                        [
                            "channel" => '@testerrors',
                            "username" => "ramukaka",
                            "icon_emoji" => ":older_man::skin-tone-3:",
                            "text" => $message
                        ]
                    ]
                );
            }
        }
        return "Inactive User";
    }

    public function setDobAttribute($value)
    {
        $this->attributes['dob'] = empty($value) ? null : date("Y-m-d", strtotime($value));
    }

    public function getDobAttribute($value)
    {
        if (!empty($value)) {
            return date("d-m-Y", strtotime($value));
            if (request()->user()->profile->id == $this->id) {
                return date("d-m-Y", strtotime($value));
            }

            if ($this->dob_private == 3) {
                return null;
            }
            if (!Redis::sIsMember("followers:profile:" . request()->user()->profile->id, $this->id) && $this->dob_private == 2) {
                return null;
            }
            return date("d-m-Y", strtotime($value));
        }
    }

    public function getExperienceAttribute()
    {
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


        $currentCompanies = $sortedExperience->pluck('start_date', 'id')->toArray();
        $startDates = [];

        foreach ($currentCompanies as $id => $startDate) {

            $dateArray = explode("-", $startDate);
            $temp = array_fill(0, 3 - count($dateArray), '01');
            $tempdate = implode("-", array_merge($temp, $dateArray));
            $startDates[] = ['id' => $id, 'date' => $tempdate, 'time' => strtotime($tempdate)];
        }
        $startDates = collect($startDates)->sortByDesc('time')->keyBy('id')->toArray();
        $sortedExperience = collect([]);

        foreach ($startDates as $id => $date) {

            $sortedExperience->push($experiences->get($id));
        }


        $sorted = collect($endDates)->sortByDesc('time')->keyBy('id')->toArray();
        unset($endDates);

        foreach ($sorted as $id => $date) {
            $sortedExperience->push($experiences->get($id));
        }

        unset($experiences);
        return $sortedExperience;
    }

    public function getEducationAttribute()
    {

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


        $currentColleges = $sortedEducation->pluck('start_date', 'id')->toArray();
        $startDates = [];

        foreach ($currentColleges as $id => $startDate) {

            $dateArray = explode("-", $startDate);
            $temp = array_fill(0, 3 - count($dateArray), '01');
            $tempdate = implode("-", array_merge($temp, $dateArray));
            $startDates[] = ['id' => $id, 'date' => $tempdate, 'time' => strtotime($tempdate)];
        }
        $startDates = collect($startDates)->sortByDesc('time')->keyBy('id')->toArray();
        $sortedEducation = collect([]);

        foreach ($startDates as $id => $date) {

            $sortedEducation->push($educations->get($id));
        }


        $sorted = collect($endDates)->sortByDesc('time')->keyBy('id')->toArray();
        unset($endDates);

        foreach ($sorted as $id => $date) {
            $sortedEducation->push($educations->get($id));
        }

        unset($educations);
        return $sortedEducation;
    }

    public function experience()
    {
        return $this->hasMany('App\Profile\Experience');
    }

    public function awards()
    {
        return $this->belongsToMany('App\Profile\Award', 'profile_awards', 'profile_id', 'award_id');
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

    //specific to API
    public function getImageUrlAttribute()
    {
        return $this->image;
    }

    //specific to API
    public function getHeroImageUrlAttribute()
    {
        return $this->hero_image;
    }

    //$followsId is following $this profile
    public function follow($followsId)
    {
        $this->iAmFollowing()->attach($followsId);
        $this->save();
        \DB::table('profiles')->whereId($this->id)->increment('following');
        \DB::table('profiles')->whereId($followsId)->increment('followers');
    }

    public function unfollow($followsId)
    {
        $this->iAmFollowing()->detach($followsId);
        $this->save();
        \DB::table('profiles')->whereId($this->id)->decrement('following');
        \DB::table('profiles')->whereId($followsId)->decrement('followers');
    }

    //profiles which are following this profile
    public function myFollowers()
    {
        return $this->belongsToMany('App\Profile', 'followers', 'follows_id', 'follower_id');
    }

    //profiles which this profile is following
    public function iAmFollowing()
    {
        return $this->belongsToMany('App\Profile', 'followers', 'follower_id', 'follows_id');
    }

    /**
     * Get people I am following.
     *
     * @return array
     */

    public static function getFollowing($id)
    {
        $channelOwnerProfileIds = \DB::table("subscribers")
            ->select('channels.profile_id')
            ->join('channels', 'subscribers.channel_name', '=', 'channels.name')
            ->where('subscribers.profile_id', '=', $id)
            ->where('subscribers.channel_name', 'like', 'network.%')
            ->where('subscribers.channel_name', 'not like', 'feed.' . $id)
            ->where('subscribers.channel_name', 'not like', 'network.' . $id)
            ->where('subscribers.channel_name', 'not like', 'public.' . $id)
            ->whereNull('subscribers.deleted_at')
            ->get();
        return \App\Recipe\Profile::whereIn("id", $channelOwnerProfileIds->pluck('profile_id')->toArray())->get();
    }

    public function getFollowingProfilesAttribute()
    {
        $count = Redis::SCARD("following:profile:" . $this->id);
        if ($count > 0 && Redis::sIsMember("following:profile:" . $this->id, $this->id)) {
            $count = $count - 1;
        }

        //        if ($count > 1000000) {
        //            $count = round($count / 1000000, 1) . "m";
        //        } elseif ($count > 1000) {
        //            $count = round($count / 1000, 1) . "k";
        //        }

        return ['count' => $count];
    }

    public static function getFollowers($id)
    {
        //just get the profile ids first
        //then fire another query to build the required things

        $profileIds = \DB::table('profiles')->select('profiles.id')
            ->join('subscribers', 'subscribers.profile_id', '=', 'profiles.id')
            ->where('subscribers.channel_name', 'like', 'network.' . $id)
            ->where('subscribers.profile_id', '!=', $id)
            ->whereNull('profiles.deleted_at')
            ->whereNull('subscribers.deleted_at')
            ->get();

        return \App\Recipe\Profile::whereIn('id', $profileIds->pluck('id')->toArray())->get();
    }

    /**
     * Get people following me.
     *
     * @return array
     */
    public function getFollowerProfilesAttribute()
    {
        $count = Redis::SCARD("followers:profile:" . $this->id);
        if (Redis::sIsMember("followers:profile:" . $this->id, $this->id)) {
            $count = $count - 1;
        }

        if ($count === 0) {
            return ['count' => 0, 'profiles' => null];
        }

        //        if ($count > 1000000) {
        //            $count = round($count / 1000000, 1) . "m";
        //        } elseif ($count > 1000) {
        //            $count = round($count / 1000, 1) . "k";
        //        }

        return ['count' => $count];
    }

    public function getMutualFollowersAttribute()
    {
        if ($this->id != request()->user()->profile->id) {
            $profileIds = Redis::SINTER("followers:profile:" . $this->id, "followers:profile:" . request()->user()->profile->id);
            if (!count($profileIds)) {
                return ['count' => 0, 'profiles' => []];
            }
            $i = 0;
            $profileInfo = [];
            foreach ($profileIds as $profileId) {
                if ($i == 5)
                    break;
                $profileInfo[] = "profile:small:" . $profileId;
                $i++;
            }
            $data = [];
            if (count($profileInfo))
                $data = Redis::mget($profileInfo);

            foreach ($data as &$profile) {
                $profile = json_decode($profile);
            }
            return ['count' => count($profileIds), 'profiles' => $data];
        }
    }

    public function photos()
    {
        return $this->belongsToMany('App\V2\Photo', 'profile_photos', 'profile_id', 'photo_id');
    }

    public function projects()
    {
        return $this->hasMany('App\Profile\Project');
    }

    public function training()
    {
        return $this->hasMany('App\Profile\Training');
    }

    public function affiliation()
    {
        return $this->hasMany('App\Profile\Affiliation');
    }

    public function education()
    {
        return $this->hasMany('App\Education');
    }

    public function companies()
    {
        return $this->hasManyThrough('\App\Company', 'App\User');
    }

    //there should be a better way to write the paths.
    public static function getImagePath($id, $filename = null)
    {
        //$relativePath = "profile/{$id}/images";
        $relativePath = "images/p/{$id}";

        Storage::makeDirectory($relativePath);
        return $filename === null ? $relativePath : storage_path("app/" . $relativePath) . "/" . $filename;
    }

    //there should be a better way to write the paths.
    public static function getHeroImagePath($id, $filename = null)
    {
        $relativePath = "images/p/{$id}/hi";
        Storage::makeDirectory($relativePath);
        return $filename === null ? $relativePath : storage_path("app/" . $relativePath) . "/" . $filename;
    }

    public function professional()
    {
        return $this->hasOne('\App\Professional');
    }

    /**
     * Just the pivot relationship. Ideabook of a user is defined in \App\User
     */

    public function ideabooks()
    {
        return $this->belongsToMany('\App\Ideabook', 'ideabook_profiles', 'profile_id', 'ideabook_id');
    }

    public function getIsTaggedAttribute()
    {
        return $this->ideabooks->count() === 1;
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

    public function payment()
    {
        return $this->hasMany('App\Payment\PaymentLinks');
    }
    private function getChannel(&$channelName, &$owner, $createIfNotExist = true)
    {
        $prefix = $owner instanceof Company ? "company." : null;
        $whereClause = $owner instanceof Company ? "company_id" : "profile_id";
        $channelName = $prefix . $channelName . "." . $owner->id;

        $channel = Channel::where($whereClause, $owner->id)->where('name', 'like', $channelName)->first();

        if ($channel === null) {
            if (!$createIfNotExist) {
                throw new ModelNotFoundException("Channel not found.");
            }

            $channel = Channel::create(['name' => $channelName, $whereClause => $owner->id]);
        }

        return $channel;
    }

    /**
     * Subscribe the owner's network
     * @param Profile|Company $owner
     * @return mixed
     */
    public function subscribeNetworkOf(&$owner)
    {
        //only individual can publish things in network.
        if ($owner instanceof Company === false) {
            $this->subscribe("network", $owner);
        }

        return $this->subscribe("public", $owner);
    }

    public function subscribe($channelName, &$owner)
    {
        return $this->getChannel($channelName, $owner, true)->subscribe($this->id);
    }

    public function unsubscribeNetworkOf(&$owner)
    {
        $this->unsubscribe("network", $owner);
        return $this->unsubscribe("public", $owner);
    }

    public function unsubscribe($channelName, &$owner)
    {
        return $this->getChannel($channelName, $owner, false)->unsubscribe($this->id);
    }

    //todo: remove this method if not used.
    private function addSubscriber(Profile $owner)
    {
        $channelName = 'network.' . $this->id;
        $channel = $this->channels()->where('name', 'like', $channelName)->first();

        if (!$channel) {
            //create channel
            $channel = $this->channels()->create(['name' => $channelName]);
        }
        return $channel->subscribe($owner->id);
    }


    /**
     * Feed for the logged in user's profile
     *
     * @return mixed
     */
    public function feed()
    {
        $profileId = $this->id;
        return Payload::select('payload')->whereHas('channel', function ($query) use ($profileId) {
            $query->where('channels.profile_id', $profileId)
                ->where('channels.name', 'not like', 'network.' . $profileId)
                ->where('channels.name', 'not like', 'public.' . $profileId);
        })->orderBy('created_at', 'desc')->get();
    }

    /**
     * Feed which a user would see if he visits his /profile page.
     */
    public function profileFeed()
    {
        $profileId = $this->id;
        return Payload::select('payload')->whereHas('channel', function ($query) use ($profileId) {
            $query->where('channel_name', 'profile.' . $profileId);
        })->orderBy('created_at', 'desc')->get();
    }

    /**
     * Feed of the subscribed network of the user.
     *
     * @return mixed
     */
    public function subscribedNetworksFeed()
    {
        $profileId = $this->id;
        $channels = Channel::select('name')->whereHas("subscribers", function ($query) use ($profileId) {
            $query->where('profile_id', $profileId)->where('name', 'like', 'network.%')
                ->where('name', 'not like', 'network.' . $profileId);
        })->get()->toArray();

        return Payload::select('payload')->whereHas('channel', function ($query) use ($channels) {
            $query->whereIn('channel_name', $channels);
        })->orderBy('created_at', 'desc')->get();
    }

    public function shoutouts()
    {
        return $this->hasMany(Shoutout::class);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    public static function isFollowing($profileId, $followerProfileId)
    {
        return Redis::sIsMember("following:profile:" . $profileId, $followerProfileId) === 1;
        //return Subscriber::where('profile_id', $followerProfileId)->where("channel_name", 'like', 'network.' . $profileId)->count() === 1;
    }

    public function getIsFollowedByAttribute()
    {
        return Redis::sIsMember("followers:profile:" . request()->user()->profile->id, $this->id) === 1;
    }

    //specific to API
    public function getResumeUrlAttribute()
    {
        return !is_null($this->resume) ? \Storage::url($this->resume) : null;
    }

    public function getAddressAttribute($value)
    {
        if (!isset(request()->user()->profile->id)) {
            return null;
        }
        if (!empty($value)) {
            if (request()->user()->profile->id == $this->id) {
                return $value;
            }

            if ($this->address_private == 3) {
                return null;
            }
            if (!Redis::sIsMember("followers:profile:" . request()->user()->profile->id, $this->id) && $this->address_private == 2) {
                return null;
            }
            return $value;
        }
    }

    public function getPhoneAttribute($value)
    {
        if (!empty($value) && isset(request()->user()->profile->id)) {
            if (request()->user()->profile->id == $this->id) {
                return $value;
            }

            if ($this->phone_private == 3) {
                return null;
            }
            if (!Redis::sIsMember("followers:profile:" . request()->user()->profile->id, $this->id) && $this->phone_private == 2) {
                return null;
            }
            return $value;
        }
    }

    public function getNotificationCountAttribute()
    {
        return \DB::table('notifications')->whereNull('last_seen')->where('notifiable_id', request()->user()->profile->id)->count();
    }

    public function getNotificationContent($action = null)
    {
        //        if($action && $action == 'follow') {
        return [
            'name' => strtolower(class_basename(self::class)),
            'id' => $this->id,
            'tagline' => $this->tagline,
            'image' => $this->imageUrl,
            'content' => null,
        ];
        //        }
    }

    public function getMessageCountAttribute()
    {
        return \DB::table('message_recepients')->whereNull('read_on')->whereNull('last_seen')->where('recepient_id', request()->user()->profile->id)->distinct('chat_id')->count();
    }

    public function getAddPasswordAttribute()
    {
        if (request()->user()->profile->id != $this->id) {
            return false;
        } else {
            return \DB::table('users')->whereNull('password')->where('id', request()->user()->id)->exists();
        }
    }

    public function routeNotificationForMail()
    {
        return $this->user->email;
    }

    public function getUnreadNotificationCountAttribute()
    {
        return \DB::table('notifications')->whereNull('read_at')->where('notifiable_id', request()->user()->profile->id)->count();
    }

    public function getPreviewContent()
    {
        $data = [];
        $data['modelId'] = $this->id;
        $data['deeplinkCanonicalId'] = 'share_profile/' . $this->id;
        $data['owner'] = $this->id;
        $data['title'] = 'Checkout ' . $this->name . '\'s profile on TagTaste';
        $data['description'] = substr($this->tagline, 0, 155);
        $data['ogTitle'] = 'Checkout ' . $this->name . '\'s profile on TagTaste';
        $data['ogDescription'] = null;
        $data['ogImage'] = $this->imageUrl;
        $data['cardType'] = 'summary_large_image';
        $data['ogUrl'] = env('APP_URL') . '/profile/' . $this->id;
        $data['redirectUrl'] = env('APP_URL') . '/profile/' . $this->id;
        if (empty($this->imageUrl)) {
            $data['cardType'] = 'summary';
        }

        return $data;
    }

    public function getremainingMessagesAttribute()
    {
        if (request()->user()->profile->id == $this->id) {
            $remaining = \DB::table('chat_limits')->where('profile_id', $this->id)->first();
            return isset($remaining) ? $remaining : null;
        }
    }

    public function getIsMessageAbleAttribute()
    {
        $chat = Chat::open($this->id, request()->user()->profile->id);
        //return is_null($chat) ? false : true;
        return true;
    }

    public function getProfileCompletionAttribute($fields = null)
    {
        if (!is_null(request()->user())) {
            if (request()->user()->profile->id == $this->id) {
                if ($fields == null) {
                    $remaningMandatoryItem = [];
                    $remaningOptionalItem = [];
                    $remaningAdditionalOptionalItem = [];
                    $profileCompletionMandatoryFieldForCollaborationApply = [];
                    $profileCompletionMandatoryFieldForCollaborationApplyV1 = [];
                    $profileCompletionMandatoryFieldForCampusConnect = [];
                    $profileCompletionMandatoryFieldForGetProductSample = [];
                    $index = 0;
                    if (!isset(request()->user()->verified_at) && is_null(request()->user()->verified_at)) {
                        $index++;
                        $remaningMandatoryItem = ['verified_email'];
                        $profileCompletionMandatoryFieldForCampusConnect[] = 'verified_email';
                        $profileCompletionMandatoryFieldForGetProductSample[] = 'verified_email';
                    }

                    if (!isset(request()->user()->email) && is_null(request()->user()->email)) {
                        $index++;
                        $remaningMandatoryItem = ['email'];
                    }


                    foreach ($this->profileCompletionMandatoryField as $item) {
                        if (is_null($this->{$item}) || empty($this->{$item}) || strlen($this->{$item}) == 0 || count([$this->{$item}]) == 0 || ($item == "cuisines" && $this->{$item}->count() == 0)) {
                            $index++;
                            $remaningMandatoryItem[] = $item;
                        }
                    }

                    foreach ($this->profileCompletionOptionalField as $item) {
                        if (is_null($this->{$item}) || empty($this->{$item}) || strlen($this->{$item}) == 0 || count([$this->{$item}]) == 0) {
                            $index++;
                            $remaningOptionalItem[] = $item;
                        }
                    }
                    $percentage = ((15 - $index) / 15) * 100;

                    foreach ($this->profileCompletionExtraOptionalField as $item) {
                        if (is_null($this->{$item}) || empty($this->{$item}) || strlen($this->{$item}) == 0 || count([$this->{$item}]) == 0 || (is_object($this->{$item}) && $this->{$item}->count() == 0) || (is_array($this->{$item}) && $this->{$item}->count() == 0)) {
                            $index++;
                            $remaningAdditionalOptionalItem[] = $item;
                        }
                    }

                    foreach ($this->profileCompletionMandatoryFieldForCollaborationApply as $item) {
                        if (is_null($this->{$item}) || empty($this->{$item}) || count([$this->{$item}]) == 0) {
                            $profileCompletionMandatoryFieldForCollaborationApply[] = $item;
                        }
                    }

                    foreach ($this->profileCompletionMandatoryFieldForCollaborationApplyV1 as $item) {
                        if (is_null($this->{$item}) || empty($this->{$item}) || count([$this->{$item}]) == 0) {
                            $profileCompletionMandatoryFieldForCollaborationApplyV1[] = $item;
                        }
                    }

                    foreach ($this->profileCompletionMandatoryFieldForCampusConnect as $item) {
                        if (is_null($this->{$item}) || empty($this->{$item}) || count([$this->{$item}]) == 0) {
                            $profileCompletionMandatoryFieldForCampusConnect[] = $item;
                        }
                    }

                    foreach ($this->profileCompletionMandatoryFieldForGetProductSample as $item) {
                        if (is_null($this->{$item}) || empty($this->{$item}) || count([$this->{$item}]) == 0 || (is_object($this->{$item}) && $this->{$item}->count() == 0) || (is_array($this->{$item}) && $this->{$item}->count() == 0)) {
                            $profileCompletionMandatoryFieldForGetProductSample[] = $item;
                        }
                    }

                    $percentage_total = ((25 - $index) / 25) * 100;
                    $profileCompletion = [
                        'complete_percentage' => (round($percentage) % 5 === 0) ? round($percentage) : round(($percentage + 5 / 2) / 5) * 5,
                        'overall_percentage' => (round($percentage_total) % 5 === 0) ? round($percentage_total) : round(($percentage_total + 5 / 2) / 5) * 5,
                        'mandatory_remaining_field' => $remaningMandatoryItem,
                        'optional_remaining_field' => $remaningOptionalItem,
                        'additional_optional_field' => $remaningAdditionalOptionalItem,
                        'mandatory_field_for_collaboration_apply' => $profileCompletionMandatoryFieldForCollaborationApply,
                        'mandatory_field_for_collaboration_apply_v1' => $profileCompletionMandatoryFieldForCollaborationApplyV1,
                        'mandatory_field_for_campus_connect' => $profileCompletionMandatoryFieldForCampusConnect,
                        'mandatory_field_for_get_product_sample' => $profileCompletionMandatoryFieldForGetProductSample
                    ];

                    return $profileCompletion;
                } else {
                    $remaningMandatoryItem = [];
                    foreach ($fields as $field) {
                        if ($field == 'verified_email') {
                            if (!isset(request()->user()->verified_at) && is_null(request()->user()->verified_at))
                                $remaningMandatoryItem[] = 'verified_email';
                        } else if ($field == 'email' && !isset(request()->user()->email) && is_null(request()->user()->email)) {
                            $remaningMandatoryItem[] = 'email';
                        } else if ($field == 'document_meta' || $field == 'address') {
                            $remaningMandatoryItem[] = $field;
                        } else if (is_null($this->{$field}) || empty($this->{$field}) || count($this->{$field}) == 0) {
                            $remaningMandatoryItem[] = $field;
                        }
                    }
                    return $remaningMandatoryItem;
                }
            }
        }
    }

    public function getBatchesCountAttribute()
    {
        return \DB::table('collaborate_batches_assign')->where('profile_id', request()->user()->profile->id)->where('begin_tasting', 1)->count();
    }

    public function getNewBatchesCountAttribute()
    {
        return \DB::table('collaborate_batches_assign')->where('profile_id', request()->user()->profile->id)
            ->where('begin_tasting', 1)->whereNull('last_seen')->count();
    }

    public function getReviewCountAttribute()
    {
        return \DB::table('public_product_user_review')->where('profile_id', $this->id)->where('current_status', 2)->get()->unique('product_id')->count();
    }

    public function getPrivateReviewCountAttribute()
    {
        return \DB::table('collaborate_tasting_user_review')->where('profile_id', $this->id)->where('current_status', 3)->get()->unique('batch_id')->count();
    }

    public function getSurveyCountAttribute()
    {
        return \DB::table('survey_answers')->where('profile_id', $this->id)->where('current_status', 2)->whereNull('deleted_at')->get()->unique('survey_id')->count();
    }

    public function getAmountAttribute()
    {
        $getPaymentDetails = PaymentLinks::where("profile_id", $this->id)->whereNull('deleted_at')->where("status_id", '<>', config("constant.PAYMENT_CANCELLED_STATUS_ID"))->select("amount")->get()->pluck('amount');
        $sum = array_sum($getPaymentDetails->toArray());
        return $sum;
    }

    public function getShippingaddressAttribute()
    {
        $request = request()->user();
        if ($request != null && $request->profile->id == $this->id)
            return \App\Profile\ShippingAddress::where('profile_id', $this->id)->get();
        else
            return null;
    }

    public function profile_specializations()
    {
        return $this->hasMany('App\Profile\Specialization');
    }

    public function profile_interested_collection()
    {
        return $this->hasMany('App\Profile\InterestedCollection');
    }

    public function profile_occupations()
    {
        return $this->hasMany('App\Profile\Occupation');
    }

    public function getFoodieTypeAttribute()
    {
        return isset($this->foodie_type_id) ? \DB::table('foodie_type')->where('id', $this->foodie_type_id)->first() : null;
    }

    public function getCuisinesAttribute()
    {
        $cuisineIds =  \DB::table('profiles_cuisines')->where('profile_id', $this->id)->get()->pluck('cuisine_id');
        return  \DB::table('cuisines')->whereIn('id', $cuisineIds)->get();
    }

    public function getEstablishmentTypesAttribute()
    {
        $establishmentTypeIds =  \DB::table('profile_establishment_types')->where('profile_id', request()->user()->profile->id)->get()->pluck('establishment_type_id');
        return  \DB::table('establishment_types')->whereIn('id', $establishmentTypeIds)->get();
    }

    public function getInterestedCollectionsAttribute()
    {
        $interestedCollectionIds =  \DB::table('profiles_interested_collections')->where('profile_id', request()->user()->profile->id)->get()->pluck('interested_collection_id');
        return  \DB::table('interested_collections')->whereIn('id', $interestedCollectionIds)->get();
    }

    public function getFbInfoAttribute()
    {
        return \DB::table('social_accounts')->where('provider', 'facebook')->where('user_id', request()->user()->id)->first();
    }

    public function getAllergensAttribute()
    {
        return \DB::table('allergens')->join('profiles_allergens', 'profiles_allergens.allergens_id', '=', 'allergens.id')->where('profiles_allergens.profile_id', $this->id)->get(['id', 'name', 'description', 'image']);
    }

    public function getTotalPostCountAttribute()
    {
        return \DB::table('channel_payloads')->where('channel_name', 'public.' . $this->id)->whereNull('deleted_at')->count();
    }

    // count calculation function start
    public function getShoutoutPostCountAttribute()
    {
        return \DB::table('channel_payloads')->where('channel_name', 'public.' . $this->id)->where('model', 'App\Shoutout')->whereNull('deleted_at')->count();
    }

    public function getShoutoutSharePostCountAttribute()
    {
        return \DB::table('channel_payloads')->where('channel_name', 'public.' . $this->id)->where('model', 'App\Shareable\Shoutout')->whereNull('deleted_at')->count();
    }

    public function getCollaboratePostCountAttribute()
    {
        return \DB::table('channel_payloads')->where('channel_name', 'public.' . $this->id)->where('model', 'App\Collaborate')->whereNull('deleted_at')->count();
    }

    public function getCollaborateSharePostCountAttribute()
    {
        return \DB::table('channel_payloads')->where('channel_name', 'public.' . $this->id)->where('model', 'App\Shareable\Collaborate')->whereNull('deleted_at')->count();
    }

    public function getPhotoPostCountAttribute()
    {
        return \DB::table('channel_payloads')->where('channel_name', 'public.' . $this->id)->whereIn('model', ['App\Photo', 'App\V2\Photo'])->whereNull('deleted_at')->count();
    }

    public function getPhotoSharePostCountAttribute()
    {
        return \DB::table('channel_payloads')->where('channel_name', 'public.' . $this->id)->where('model', 'App\Shareable\Photo')->whereNull('deleted_at')->count();
    }

    public function getPollingPostCountAttribute()
    {
        return \DB::table('channel_payloads')->where('channel_name', 'public.' . $this->id)->where('model', 'App\Polling')->whereNull('deleted_at')->count();
    }

    public function getPollingSharePostCountAttribute()
    {
        return \DB::table('channel_payloads')->where('channel_name', 'public.' . $this->id)->where('model', 'App\Shareable\Polling')->whereNull('deleted_at')->count();
    }

    public function getProductSharePostCountAttribute()
    {
        return \DB::table('channel_payloads')->where('channel_name', 'public.' . $this->id)->where('model', 'App\Shareable\Product')->whereNull('deleted_at')->count();
    }
    // count calculation function end 

    public function getImagePostCountAttribute()
    {
        return \DB::table('channel_payloads')
            ->where('channel_name', 'public.' . $this->id)
            ->whereNull('deleted_at')
            ->where('model', 'like', '%Photo')
            ->where('model', 'not like', '%Shareable%')
            ->count();
    }

    public function getDocumentMetaAttribute()
    {
        $docs = \DB::table('profile_documents')
            ->where('profile_id', $this->id)
            ->select('document_meta', 'is_verified')
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

    public function getPalateSensitivityAttribute()
    {
        $palate_tasting = null;

        if (request()->user()->profile->id == $this->id) {
            $palate_tasting = $this->getPalateSensitivityResult();
            return $palate_tasting;
        } else {
            if ($this->palate_visibility == 0) {
                return $palate_tasting;
            } else if ($this->palate_visibility == 2) {
                if (Redis::sIsMember("followers:profile:" . request()->user()->profile->id, $this->id)) {
                    $palate_tasting = $this->getPalateSensitivityResult();
                    return $palate_tasting;
                } else {
                    return $palate_tasting;
                }
            } else {
                $palate_tasting = $this->getPalateSensitivityResult();
                return $palate_tasting;
            }
        }
        return $palate_tasting;
    }

    public function getCurrentPalateIterationValue()
    {
        $current_palate_iteration = 0;
        if (0 == $this->palate_iteration) {
            return $current_palate_iteration;
        } else {
            if ($this->palate_iteration_status) {
                $current_palate_iteration = $this->palate_iteration;
                return $current_palate_iteration;
            } else {
                $current_palate_iteration = $this->palate_iteration - 1;
                return $current_palate_iteration;
            }
        }
        return $current_palate_iteration;
    }

    public function getPalateSensitivityResult()
    {
        $palate_result = null;
        $current_palate_iteration = $this->getCurrentPalateIterationValue();
        $palate_responses = \App\PalateResponses::where('profile_id', $this->id)
            ->where('iteration_id', $current_palate_iteration)
            ->whereNull('deleted_at')
            ->get();
        if (count($palate_responses)) {
            $palate_result = array();
            $palate_responses_grouped = $this->group_by('palate_type', $palate_responses->toArray());
            foreach ($palate_responses_grouped as $group_key => $palate_response_group) {
                $keys = array_column($palate_response_group, 'concentration_level');
                array_multisort($keys, SORT_ASC, $palate_response_group);
                if (in_array($group_key, array("Salt", "Sugar", "Sour"))) {
                    $palate_result[$group_key] = array(
                        'value' => $group_key,
                        'ui_style_meta' => array(
                            "border_color" => "#E5E5E5",
                            "background_color" => "#F5F5F5"
                        ),
                        'status' => "Very Low"
                    );

                    foreach ($palate_response_group as $key => $value) {
                        if ($value['result']) {
                            $palate_result[$group_key]['status'] = $value['status'];
                            $palate_result[$group_key]['ui_style_meta'] = $value['ui_style_meta'];
                            break;
                        }
                    }
                } else if ($group_key === "Bitter") {
                    $palate_result[$group_key] = array(
                        'value' => $group_key,
                        'ui_style_meta' => $palate_response_group[0]['ui_style_meta'],
                        'status' => $palate_response_group[0]['status']
                    );
                }
            };
            $palate_result = array_values($palate_result);
        }
        return $palate_result;
    }

    /**
     * Function that groups an array of associative arrays by some key.
     * 
     * @param {String} $key Property to sort by.
     * @param {Array} $data Array that stores multiple associative arrays.
     */
    function group_by($key, $data)
    {
        $result = array();

        foreach ($data as $val) {
            if (array_key_exists($key, $val)) {
                $result[$val[$key]][] = $val;
            } else {
                $result[""][] = $val;
            }
        }

        return $result;
    }


    /**
     * @param int $profileId
     * @return array
     */
    public function getSeoTags(): array
    {
        $follower_count = $this->getFollowerProfilesAttribute()['count'];
        $title = "TagTaste | " . htmlspecialchars_decode($this->name) . " | Profile";

        $description = "View " . htmlspecialchars_decode($this->name) . "'s profile on TagTaste. " . htmlspecialchars_decode($this->name) . " has " . $follower_count . " followers. TagTaste is the world's first ever online community for food professionals to discover, network & collaborate with each other.";

        $seo_tags = [
            "title" => $title,
            "meta" => array(
                array(
                    "name" => "description",
                    "content" => $description,
                ),
                array(
                    "name" => "keywords",
                    "content" => "user, profile, tagtaste, tagtaste profile, " . htmlspecialchars_decode($this->name),
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
                    "content" => $this->imageUrl,
                )
            ),
        ];
        return $seo_tags;
    }

    public function getPaymentAttribute()
    {
        $getPaymentDetails = PaymentLinks::where("profile_id", $this->id)->whereNull('deleted_at')->where("status_id", '<>', config("constant.PAYMENT_CANCELLED_STATUS_ID"))->select("amount")->get()->pluck('amount');
        $sum = array_sum($getPaymentDetails->toArray());
        return ["earning" => $sum,"formatted_earning" => utf8_encode("&#8377;") . number_format($sum,2)];
    }

}
