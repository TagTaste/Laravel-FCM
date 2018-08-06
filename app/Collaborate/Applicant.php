<?php

namespace App\Collaborate;

use Illuminate\Database\Eloquent\Model;


class Applicant extends Model {

    protected $table = 'collaborate_applicants';

    protected $fillable = ['profile_id','collaborate_id','is_invited','shortlisted_at','rejected_at','applier_address','message','hut'];

    protected $visible = ['id','profile_id','collaborate_id','is_invited','shortlisted_at','rejected_at','profile','applier_address','message','hut'];

    protected $with = ['profile'];

    protected $casts = [
        'collaborate_id' => 'integer',
        'profile_id' => 'integer',
        'batch_id' => 'integer'
    ];

    public function profile()
    {
        return $this->belongsTo(\App\Recipe\Profile::class);
    }

}
