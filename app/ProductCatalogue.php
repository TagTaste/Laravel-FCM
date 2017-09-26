<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCatalogue extends Model
{
    protected $fillable = ['product', 'category', 'company_id','brand','measurement_unit','barcode','size','certified','delivery_cities',
    'price','moq','type','about','shelf_life'];

    public function getCertifiedAttribute($value)
    {
        if($value==null) {
            return 0;
        }
    }
}
