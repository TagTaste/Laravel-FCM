<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PublicReviewEntryMapping extends Model
{
    protected $table = "public_review_entry_mapping";
    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    protected $guarded = ["id"];
    protected $fillable = ["profile_id","product_id","header_id","activity","created_at","updated_at"];

}
