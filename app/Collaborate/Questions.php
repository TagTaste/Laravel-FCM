<?php

namespace App\Collaborate;

use Illuminate\Database\Eloquent\Model;


class Questions extends Model {

    protected $table = 'collaborate_tasting_questions';

    protected $fillable = ['title','subtitle','is_nested_question','is_mandatory','is_active','parent_question_id','questions','header_type_id','collaborate_id','created_at','updated_at','track_consistency','max_rank'];

    protected $visible = ['id','is_mandatory','is_active','is_nested_question','parent_question_id','questions','header_type_id','collaborate_id','created_at','updated_at','track_consistency','max_rank'];

}
