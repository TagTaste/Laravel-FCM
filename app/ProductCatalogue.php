<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCatalogue extends Model
{
    use SoftDeletes;
    protected $fillable = ['product', 'category', 'company_id',
    'price','moq','type','about'];
}
