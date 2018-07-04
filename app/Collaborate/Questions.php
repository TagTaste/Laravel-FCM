<?php

namespace App\Collaborate;

use Illuminate\Database\Eloquent\Model;


class Questions extends Model {

    protected $table = 'collaborate_tasting_questions';

    protected $fillable = ['key','value','aroma_id','aromatic_id','question_id','tasting_header_id','collaborate_id'];

    protected $visible = ['id','key','value','aroma_id','aromatic_id','question_id','tasting_header_id','collaborate_id'];

}
