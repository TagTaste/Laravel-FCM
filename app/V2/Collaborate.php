<?php

namespace App\V2;

use App\Privacy;
use App\Traits\GetTags;
use App\Traits\HasPreviewContent;
use App\Traits\IdentifiesOwner;
use App\Collaborate as BaseCollaborate;
use Illuminate\Support\Facades\Redis;

class Collaborate extends BaseCollaborate
{
	use IdentifiesOwner, GetTags, HasPreviewContent;

	protected $visible = ["id", "title", "description", "profile_id", "company_id", "has_tags", "collaborate_type", "expires_on", "updated_at", "created_at", "deleted_at","financials","financial_min","financial_max","track_consistency","addresses","is_contest","max_submissions","show_interest_state","mandatory_fields", "closing_reason"];

}