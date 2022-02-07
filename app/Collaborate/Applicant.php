<?php

namespace App\Collaborate;

use Illuminate\Database\Eloquent\Model;


class Applicant extends Model {
    protected $table = 'collaborate_applicants';

    protected $fillable = ['profile_id','collaborate_id','is_invited','shortlisted_at','rejected_at','applier_address','message',
        'hut','created_at','updated_at','city','age_group','gender','company_id','document_meta','terms_verified', 'documents_verified','share_number','hometown','current_city'];

    protected $visible = ['id','profile_id','collaborate_id','is_invited','shortlisted_at','rejected_at','profile','applier_address',
        'message','hut','created_at','updated_at','city','age_group','gender','company','company_id','document_meta','terms_verified', 'documents_verified','phone','submission_count','hometown','current_city'];

    protected $with = ['profile','company'];

    protected $appends = ['phone','submission_count'];

    protected $casts = [
        'collaborate_id' => 'integer',
        'profile_id' => 'integer',
        'batch_id' => 'integer'
    ];

    public function profile()
    {
        $profs = $this->belongsTo(\App\Recipe\Profile::class);
        return $profs;
    }

    public function company()
    {
        return $this->belongsTo(\App\Recipe\Company::class);
    }

    public function getApplierAddressAttribute($value)
    {
        if($value == null || $value == '{}') {
            return (object)null;
        }
        // if($value == null || $value == '{}') {
        //     return [];
        // }
        return json_decode($value,true);
    }
    public function getDocumentMetaAttribute($value)
    {
        return json_decode($value);
    }
    //0 no action taken
    //1 accepted
    //2 rejected
    public static function getSubmissions($id, $collaborateId)
    {  
        return \DB::table('collaborate_applicants')->where('collaborate_applicants.id',$id)
        ->where('collaborate_id',$collaborateId)
        ->join('contest_submissions','collaborate_applicants.id','=','contest_submissions.applicant_id')
        ->join('submissions','submissions.id','=','contest_submissions.submission_id')
        //->where('submissions.status','!=',2)
        ->select('submissions.*')
        ->get();
    }
    public static function countSubmissions($id,$collaborateId)
    {
       return \DB::table('collaborate_applicants')->where('collaborate_applicants.id',$id)
       ->where('collaborate_id',$collaborateId)
       ->join('contest_submissions','collaborate_applicants.id','=','contest_submissions.applicant_id')
       ->join('submissions','submissions.id','=','contest_submissions.submission_id')
       ->where('submissions.status','!=',2)
       ->count();
    }

    public function getPhoneAttribute()
    {
        if($this->share_number) {
            return \DB::table('profiles')->where('id',$this->profile_id)->first()->phone;
        } else {
            return null;
        }
    }

    public function getSubmissionCountAttribute()
    {
        return $this->countSubmissions($this->id,$this->collaborate_id);
    }
}
