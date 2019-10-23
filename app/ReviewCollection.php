<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\IdentifiesOwner;

class ReviewCollection extends Model
{
	use IdentifiesOwner, SoftDeletes;

    protected $table='review_collections';

    protected $fillable = ['title','subtitle','description','image','type','is_active','created_at','updated_at','deleted_at'];

    protected $visible = ['id','title','subtitle','description','image','type','is_active','created_at','updated_at','deleted_at'];

}
