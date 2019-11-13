<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\IdentifiesOwner;

class ReviewCollectionElement extends Model
{
	use IdentifiesOwner, SoftDeletes;

    protected $table = 'review_collection_elements';

    protected $fillable = ['type','collection_id','data_type','data_id','filter_id','filter_name','filter_image','filter_on','filter','title','subtitle','description','image','created_at','updated_at','deleted_at'];

    protected $visible = ['id','type','collection_id','data_type','data_id','filter_id','filter_name','filter_image','filter_on','filter','data_model','title','subtitle','description','image','created_at','updated_at','deleted_at'];

    protected $appends = ['data_model', 'filter_model'];

    public function getDataModelAttribute()
    {
        switch (strtolower($this->data_type)) {
            case 'product':
                return "App\PublicReviewProduct";
                break;
            case 'collection':
                return "App\ReviewCollection";
                break;
            case 'profile':
                return "App\V2\Profile";
                break;
            default:
                return null;
                break;
        };
    }

    public function getFilterModelAttribute()
    {
        switch (strtolower($this->filter_on)) {
            case 'product':
                return "App\PublicReviewProduct";
                break;
            case 'collection':
                return "App\ReviewCollection";
                break;
            case 'profile':
                return "App\V2\Profile";
                break;
            default:
                return null;
                break;
        };
    }

}
