<?php

namespace App\Collaborate;

use Illuminate\Database\Eloquent\Model;


class Applicant extends Model {

    protected $table = 'collaborate_applicants';

    protected $fillable = ['profile_id','collaborate_id','batch_id','is_invited','shortlisted_at','rejected_at'];

    protected $visible = ['id','profile_id','collaborate_id','is_invited','shortlisted_at','rejected_at','profile','batches'];

    protected $with = ['profile','batches'];

    protected $casts = [
        'collaborate_id' => 'integer',
        'profile_id' => 'integer',
        'batch_id' => 'integer'
    ];

    public function profile()
    {
        return $this->belongsTo(\App\Recipe\Profile::class);
    }

    public function batches()
    {
        return $this->belongsToMany('App\Collaborate\Batches','collaborate_batches_assign','profile_id','batch_id');
    }

}
