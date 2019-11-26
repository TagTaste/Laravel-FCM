<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\IdentifiesOwner;

class CampusConnect extends Model
{
	use IdentifiesOwner, SoftDeletes;

    protected $table = 'campus_connect';

    protected $fillable = ['profile_id','campus_name','profile','created_at','updated_at','deleted_at'];

    protected $visible = ['id','profile_id','campus_name','profile','created_at','updated_at','deleted_at'];

    protected $append = ['profile'];

    /**
     * Which profile created the collaboration project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function profile()
    {
        return $this->belongsTo(\App\Profile::class, 'profile_id', 'id');
    }

}
