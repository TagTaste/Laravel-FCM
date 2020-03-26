<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\IdentifiesOwner;

class CategorySelectorCollection extends Model
{
	use IdentifiesOwner, SoftDeletes;

    protected $table = 'category_selector_collection';

    protected $fillable = ['category_type','category_id','data_type','data_id','is_active','created_at','updated_at','deleted_at'];

    protected $visible = ['id','category_type','category_id','data_type','data_id','is_active','created_at','updated_at','deleted_at'];
}
