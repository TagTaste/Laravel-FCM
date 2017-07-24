<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductCatalogue extends Model
{
    protected $fillable = ['product', 'catalogue', 'company_id'];
}
