<?php

namespace App\Collaborate;

use Illuminate\Database\Eloquent\Model;


class Specialization extends Model {

    protected $table = 'collaborate_specializations';

    protected $fillable = ['collaborate_id','specialization_id'];

    protected $visible = ['id','name','description'];

    protected $appends = ['id','name','description'];

    protected $spcialzation = null;

    public function getIdAttribute()
    {
        $this->spcialzation = \DB::table('specializations')->where('id',$this->specialization_id)->first();
        return isset($this->spcialzation->id) ? $this->spcialzation->id : null;
    }

    public function getNameAttribute()
    {
        return isset($this->spcialzation->name) ? $this->spcialzation->name : null;
    }

    public function getDescriptionAttribute()
    {
        return isset($this->spcialzation->description) ? $this->spcialzation->description : null;
    }

}
