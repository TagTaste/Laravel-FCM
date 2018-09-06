<?php

namespace App\Collaborate;

use Illuminate\Database\Eloquent\Model;


class Occupation extends Model {

    protected $table = 'collaborate_occupations';

    protected $fillable = ['collaborate_id','occupation_id'];

    protected $visible = ['id','name','description'];

    protected $appends = ['id','name','description'];

    protected $occupations = null;

    public function getIdAttribute()
    {
        $this->occupations = \DB::table('occupations')->where('id',$this->occupation_id)->first();
        return isset($this->occupations->id) ? $this->occupations->id : null;
    }

    public function getNameAttribute()
    {
        return isset($this->occupations->name) ? $this->occupations->name : null;
    }

    public function getDescriptionAttribute()
    {
        return isset($this->occupations->description) ? $this->occupations->description : null;
    }
}
