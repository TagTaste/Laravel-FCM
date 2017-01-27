<?php

namespace App\Api;

use App\Profile;

class Recommend
{
    private $recommendations = [];

    public function __construct()
    {
        $this->recommend();
    }

    /*
     * returns recommendations.
     *
     * */
    public function recommend()
    {
        //Add any recommendations
        $this->profiles();
        return;
    }

    public function profiles()
    {
        //a join is required with the users table; otherwise we would get stuck in an endless loop while fetching recommendations
        $profiles = Profile::select('name','tagline')
                ->join('users','users.id','=','profiles.user_id')
                ->without('experience','awards','certifications')->take(2)->get();

        $this->recommendations['profiles'] = $profiles;
        return;
    }

    public static function get()
    {
        $recommendations = new Recommend();

        return $recommendations->recommendations;
    }
}
