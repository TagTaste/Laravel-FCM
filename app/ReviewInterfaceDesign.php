<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\IdentifiesOwner;

class ReviewInterfaceDesign extends Model
{
	use IdentifiesOwner, SoftDeletes;

    protected $table = 'review_interface_design';

    protected $fillable = ['postion','ui_type','ui_style','collection_id','is_active','created_at','updated_at','deleted_at'];

    protected $visible = ['id','postion','ui_type','ui_style','collection_id','is_active','created_at','updated_at','deleted_at','collection'];

    protected $appends = ['collection'];

    /**
     * Which profile created the collaboration project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getCollectionAttribute()
    {
        return $this->belongsTo(\App\ReviewCollection::class, 'id', 'collection_id');
    }

}
