<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CollaborateCategoryPivot extends Model
{
    protected $fillable = ['collaborate_id', 'category_id'];
}
