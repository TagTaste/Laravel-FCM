<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CollaborateTastingEntryMapping extends Model
{
    protected $table = "collaborate_tasting_entry_mapping";
    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    protected $guarded = ["id"];
    protected $fillable = ["profile_id","collaborate_id","batch_id","header_id","activity","created_at","updated_at"];

}
