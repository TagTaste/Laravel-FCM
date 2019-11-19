<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\IdentifiesOwner;

class ReviewInterfaceDesign extends Model
{
	use IdentifiesOwner, SoftDeletes;

    protected $table = 'review_interface_design';

    protected $fillable = ['position','ui_type','ui_style','collection_id','is_active','created_at','updated_at','deleted_at'];

    protected $visible = ['id','position','ui_type','ui_style','ui_style_meta','collection_id','is_active','created_at','updated_at','deleted_at','collections'];

    protected $with = ['collections'];

    protected $appends = ['ui_style_meta'];

    /**
     * Which profile created the collaboration project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function collections()
    {
        return $this->belongsTo(\App\ReviewCollection::class, 'collection_id', 'id');
    }

    public function getUiStyleMetaAttribute()
    {
        if (!is_null($this->ui_style) && "" != $this->ui_style) {
            return json_decode($this->ui_style);
        }
        return $this->ui_style;
    }
}
