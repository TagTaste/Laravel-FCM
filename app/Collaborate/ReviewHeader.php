<?php

namespace App\Collaborate;

use Illuminate\Database\Eloquent\Model;


class ReviewHeader extends Model {

    protected $table = 'collaborate_tasting_header';

    protected $fillable = ['id','header_type','is_active','collaborate_id','created_at','updated_at','header_info'];

    protected $visible = ['id','header_type','is_active','collaborate_id','header_info'];

    public function getHeaderInfoAttribute($value)
    {
        if(isset($value))
            return json_decode($value,true);
    }

}
