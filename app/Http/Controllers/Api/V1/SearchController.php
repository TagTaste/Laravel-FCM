<?php

namespace App\Http\Controllers\Api\V1;

use App\Company;
use App\SearchClient;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;

class SearchController extends Controller
{
    //aliases added for frontend
    private $models = [
        'collaborate'=> \App\Recipe\Collaborate::class,
        'collaborates'=> \App\Recipe\Collaborate::class,
        'recipe' => \App\Recipe::class,
        'recipes' => \App\Recipe::class,
        'profile' => \App\Recipe\Profile::class,
        'people' => \App\Recipe\Profile::class,
        'company' => \App\Recipe\Company::class,
        'companies' => \App\Recipe\Company::class,
        'job' => \App\Recipe\Job::class,
        'jobs' => \App\Recipe\Job::class
    ];
    
    private $filters = [
        'collaborate'=> \App\Filter\Collaborate::class,
        'recipe' => \App\Filter\Recipe::class,
        'recipes' => \App\Filter\Recipe::class,
        'profile' => \App\Filter\Profile::class,
        'people' => \App\Filter\Profile::class,
        'company' => \App\Filter\Company::class,
        'companies' => \App\Filter\Company::class,
        'job' => \App\Filter\Job::class,
        'jobs' => \App\Filter\Job::class
    ];


    public function discover(Request $request)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $this->model = [];

    }


    public function getAllProfileIdsFromNetwork($loggedInProfileId)
    {
        $profileIds = new Collection();
        // specialization
        $ids = \DB::table('profile_specializations')->where('profile_id',$loggedInProfileId)->get()->pluck('specialization_id');
        $ids = \DB::table('profile_specializations')->whereIn('specialization_id',$ids)->get()->pluck('profile_id');
        $profileIds = $profileIds->merge($ids);
        //job profile
        $ids = \DB::table('profile_occupations')->where('profile_id',$loggedInProfileId)->get()->pluck('occupation_id');
        $ids = \DB::table('profile_occupations')->whereIn('occupation_id',$ids)->get()->pluck('profile_id');
        $profileIds = $profileIds->merge($ids);
        //locations
        $profile = Profile::where('id',$loggedInProfileId)->first();
        $ids = \DB::table('profile_filters')->where('key','location')->where('value',$profile->city)->get()->pluck('profile_id');
        $profileIds = $profileIds->merge($ids);
        return $profileIds;
    }



}
