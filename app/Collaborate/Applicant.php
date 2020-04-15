<?php

namespace App\Collaborate;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Applicant extends Model {
    use SoftDeletes; 
    protected $table = 'collaborate_applicants';

    protected $fillable = ['profile_id','collaborate_id','is_invited','shortlisted_at','rejected_at','applier_address','message',
        'hut','created_at','updated_at','city','age_group','gender','company_id','document_meta','terms_verified', 'documents_verified'];

    protected $visible = ['id','profile_id','collaborate_id','is_invited','shortlisted_at','rejected_at','profile','applier_address',
        'message','hut','created_at','updated_at','city','age_group','gender','company','company_id','document_meta','terms_verified', 'documents_verified'];

    protected $with = ['profile','company'];

    protected $casts = [
        'collaborate_id' => 'integer',
        'profile_id' => 'integer',
        'batch_id' => 'integer'
    ];

    public function profile()
    {
        return $this->belongsTo(\App\Recipe\Profile::class);
    }

    public function company()
    {
        return $this->belongsTo(\App\Recipe\Company::class);
    }

    public function getApplierAddressAttribute($value)
    {
        return json_decode($value,true);
    }
    public function getDocumentMetaAttribute($value)
    {
        return json_decode($value);
    }

}
