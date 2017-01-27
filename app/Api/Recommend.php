<?php

namespace App\Api;

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
        $profiles = \DB::table('users')->select('users.id','users.name','profiles.tagline')
            ->join('profiles','users.id','=','profiles.user_id')
            ->take(2)
            ->get();

        $this->recommendations['profiles'] = $profiles;
        return;
    }

    public static function get()
    {
        $recommendations = new Recommend();

        return $recommendations->recommendations;
    }
}
