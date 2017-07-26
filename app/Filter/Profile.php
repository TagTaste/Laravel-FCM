<?php

namespace App\Filter;

use App\Profile as BaseProfile;

class Profile extends BaseProfile {

    protected $with = [];

    protected $visible = ['city'];

    protected $appends = [];

}