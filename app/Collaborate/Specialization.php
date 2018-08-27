<?php

namespace App\Collaborate;

use Illuminate\Database\Eloquent\Model;


class Specialization extends Model {

    protected $table = 'collaborate_profiles_specialization';

    protected $fillable = ['collaborate_id','specialization_id'];

    protected $visible = ['collaborate_id','specialization_id'];

}
