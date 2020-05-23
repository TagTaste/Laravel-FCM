<?php

namespace App\Collaborate;

use Illuminate\Database\Eloquent\Model;


class Applicant extends Model {
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
    //0 no action taken
    //1 accepted
    //2 rejected
    public static function getSubmissions($profileId, $collaborateId)
    {
        return \DB::table('collaborate_applicants')->where('profile_id',$profileId)
        ->where('collaborate_id',$collaborateId)
        ->join('contest_submissions','collaborate_applicants.id','=','contest_submissions.applicant_id')
        ->join('submissions','submissions.id','=','contest_submissions.submission_id')
        ->where('submissions.status','!=',2)
        ->select('submissions.*')
        ->get();
    }
    public static function countSubmissions($profileId,$collaborateId)
    {
       return \DB::table('collaborate_applicants')->where('profile_id',$profileId)
       ->where('collaborate_id',$collaborateId)
       ->join('contest_submissions','collaborate_applicants.id','=','contest_submissions.applicant_id')
       ->join('submissions','submissions.id','=','contest_submissions.submission_id')
       ->where('submissions.status','!=',2)
       ->count();
    }

}
