<?php

namespace App\Collaborate;

use Illuminate\Database\Eloquent\Model;


class ReviewHeader extends Model {

    protected $table = 'collaborate_tasting_header';

    protected $fillable = ['header_type','is_active','collaborate_id','created_at','updated_at'];

    protected $visible = ['header_type','is_active','collaborate_id'];

}
