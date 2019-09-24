<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeedTracker extends Model
{
    protected $table = 'feed_tracker';
    
    protected $fillable = ["model_name", "model_id", "profile_id", "interaction_type", "interaction_type_id", "device", "device_id"
    ];

    protected $visible = ["id","model_name", "model_id", "profile_id", "interaction_type", "interaction_type_id", "device", "device_id"
    ];

    protected $with = ['profile'];

    /**
     * Which profile created the collaboration project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function profile()
    {
        return $this->belongsTo(\App\V2\Profile::class,'profile_id');
    }
}
