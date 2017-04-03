<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $fillable = ['title', 'description', 'type', 'location', 'annual_salary', 'functional_area', 'key_skills', 'xpected_role', 'experience_required', 'company_id'];
    
    public function company()
    {
        return $this->belongsTo(\App\Company::class);
    }
}
