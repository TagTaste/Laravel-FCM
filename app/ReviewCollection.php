<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\IdentifiesOwner;

class ReviewCollection extends Model
{
	use IdentifiesOwner, SoftDeletes;

    protected $fillable = ['title','subtitle','description','image','type','is_active','created_at','updated_at','deleted_at'];

    protected $visible = ['id','title','subtitle','description','image','type','is_active','created_at','updated_at','deleted_at'];

    protected $with = ['profile', 'company'];

    protected $appends = ['actual_model'];

    /**
     * Which profile created the collaboration project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function profile()
    {
        return $this->belongsTo(\App\V2\Profile::class,'profile_id');
    }

    /**
     * Which company created the collaboration project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(\App\V2\Company::class);
    }
}
