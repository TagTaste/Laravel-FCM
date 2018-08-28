<?php

namespace App\Collaborate;

use Illuminate\Database\Eloquent\Model;


class Job extends Model {

    protected $table = 'collaborate_profiles_job';

    protected $fillable = ['collaborate_id','job_id'];

    protected $visible = ['name','description'];

    protected $appends = ['name','description'];

    protected $job = null;

    public function getNameAttribute()
    {
        $this->job = \DB::table('profiles_job')->where('id',$this->job_id)->first();

        return isset($this->job->name) ? $this->job->name : null;
    }

    public function getDescriptionAttribute()
    {
        return isset($this->job->description) ? $this->job->description : null;
    }
}
