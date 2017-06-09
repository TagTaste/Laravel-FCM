<?php

namespace App\Filter;

use App\Collaborate as BaseCollaborate;

class Collaborate extends BaseCollaborate
{
	 protected $visible = ['location','keywords'];
}