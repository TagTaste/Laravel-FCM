<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\IdentifiesOwner;

class ReviewCollectionElement extends Model
{
	use IdentifiesOwner, SoftDeletes;

    protected $table = 'review_collection_elements';

    protected $fillable = ['type','collection_id','data_type','data_id','filter_id','filter_on','filter','created_at','updated_at','deleted_at'];

    protected $visible = ['id','type','collection_id','data_type','data_id','filter_id','filter_on','filter','created_at','updated_at','deleted_at'];

}
