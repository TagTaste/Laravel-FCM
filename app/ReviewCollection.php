<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\IdentifiesOwner;

class ReviewCollection extends Model
{
	use IdentifiesOwner, SoftDeletes;

    protected $table='review_collections';

    protected $fillable = ['title','subtitle','description','image','type','category_type','is_active','created_at','updated_at','deleted_at'];

    protected $visible = ['id','title','subtitle','description','image','type','category_type','is_active','images_meta','created_at','updated_at','deleted_at','elements'];

    protected $with = ['elements'];

    protected $appends = ['images_meta'];

    /**
     * Which profile created the collaboration project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function elements()
    {
        return $this->hasMany(\App\ReviewCollectionElement::class, 'collection_id', 'id')->inRandomOrder();
    }

    public function getImagesMetaAttribute()
    {
        if (!is_null($this->image)) {
            return json_decode($this->image);
        }
        return $this->image;
    }

}
