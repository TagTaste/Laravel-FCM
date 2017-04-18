<?php

namespace App\Filter;

use App\Job as BaseJob;

class Job extends BaseJob {
    protected $with = [];
    protected $visible = ['location'];
    protected $appends = [];
}