<?php

namespace App\Collaborate;

use Illuminate\Database\Eloquent\Model;


class Job extends Model {

    protected $table = 'collaborate_profiles_job';

    protected $fillable = ['collaborate_id','job_id'];

    protected $visible = ['id','name','description'];

    protected $appends = ['id','name','description'];

    protected $job = null;

    public function getIdAttribute()
    {
        $this->job = \DB::table('profiles_job')->where('id',$this->job_id)->first();
        return isset($this->job->id) ? $this->job->id : null;
    }

    public function getNameAttribute()
    {
        return isset($this->job->name) ? $this->job->name : null;
    }

    public function getDescriptionAttribute()
    {
        return isset($this->job->description) ? $this->job->description : null;
    }
}
