<?php

namespace App\Profile;

use App\Scopes\Profile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use Profile;

    protected $fillable = ['company','designation','description','location',
    'start_date','end_date','current_company','profile_id'];

    protected $visible = ['id','company','designation','description','location',
        'start_date','end_date','current_company'];

    protected $touches = ['profile'];

    protected static function boot()
    {
        parent::boot();
        // Order by name ASC
        static::addGlobalScope('experiences', function (Builder $builder) {
            $builder->orderBy('current_company','desc')->orderBy('start_date', 'desc');
        });
        
        self::created(function($model){
           \App\Documents\Profile::create($model->profile);
        });
        
        self::updated(function($model){
           \App\Documents\Profile::create($model->profile);
        });
    }

    public function profile()
    {
        return $this->belongsTo('App\Profile');
    }

    public function setCurrentCompanyAttribute($value){
        $this->attributes['current_company'] = empty($value) ? 0 : 1;
    }

    public function getCurrentCompanyAttribute($value){
      if(is_null($value)){
        return false;
      }
      return $value;
    }

    public function seo_friendly_url($string)
    {
        $string = str_replace('.', '', $string);
        $string = str_replace('. ', '', $string);
        $string = str_replace("'", '', $string);
        $string = str_replace(array('[\', \']'), '', $string);
        $string = preg_replace('/\[.*\]/U', '', $string);
        $string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', '_AND_', $string);
        $string = htmlentities($string, ENT_COMPAT, 'utf-8');
        $string = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $string );
        $string = preg_replace(array('/[^a-z0-9]/i', '/[-]+/') , '_', $string);
        return strtoupper(trim($string, '_'));
    }

    public function remove_unwanted_info($designation)
    {
        $replacer = array(
            '?' => "",
            "'" => "",
            "." => "",
            "(" => "",
            ")" => "",
            "-" => "_",
            ":" => "_",
            " " => "_",
            "." => "_",
            "&AMP;" => "_AND_",
            "_RSQUO_" => ""
        );

        $string =  str_replace(
            array_keys($replacer),
            array_values($replacer),
            preg_replace('/\_+/', "_",$this->seo_friendly_url($designation))
        );

        return $string;
    }
   
    public function addUserExperience()
    {
        if ($this->designation) {
            $designation = $this->seo_friendly_url($this->designation);
            $designation = $this->remove_unwanted_info($this->designation);
            if (strlen($designation)) {
                $designation_exist = \App\Neo4j\Experiance::where('name',$designation)->first();
                if (!$designation_exist) {
                    $designation_exist = \App\Neo4j\Experiance::create([
                        "name" => $designation
                    ]);
                }
                if ($designation_exist) {
                    $user = \App\Neo4j\User::where('profile_id', $this->profile_id)->first();
                    if ($user) {
                        $designation_have_user = $designation_exist
                            ->have
                            ->where('profile_id', (int)$this->profile_id)
                            ->first();
                        if (!$designation_have_user) {
                            $relation = $designation_exist->have()->attach($user);
                            $relation->status = 1;
                            $relation->statusValue = "have";
                            $relation->save();
                        } else {
                            $relation = $designation_exist->have()->edge($user);
                            $relation->status = 1;
                            $relation->statusValue = "have";
                            $relation->save();
                        }
                    }
                }

            }
        }
    }

    public function updateUserExperience()
    {
        if ($this->designation) {
            $designation = $this->seo_friendly_url($this->designation);
            $designation = $this->remove_unwanted_info($this->designation);
            if (strlen($designation)) {
                $designation_exist = \App\Neo4j\Experiance::where('name',$designation)->first();
                if (!$designation_exist) {
                    $designation_exist = \App\Neo4j\Experiance::create([
                        "name" => $designation
                    ]);
                }
                if ($designation_exist) {
                    $user = \App\Neo4j\User::where('profile_id', $this->profile_id)->first();
                    if ($user) {
                        $designation_have_user = $designation_exist
                            ->have
                            ->where('profile_id', (int)$this->profile_id)
                            ->first();
                        if (!$designation_have_user) {
                            $relation = $designation_exist->have()->attach($user);
                            $relation->status = 1;
                            $relation->statusValue = "have";
                            $relation->save();
                        } else {
                            $relation = $designation_exist->have()->edge($user);
                            $relation->status = 1;
                            $relation->statusValue = "have";
                            $relation->save();
                        }
                    }
                }

            }
        }
    }

    public function detachUserExperience()
    {
        if ($this->designation) {
            $designation = $this->seo_friendly_url($this->designation);
            $designation = $this->remove_unwanted_info($this->designation);
            if (strlen($designation)) {
                $user = \App\Neo4j\User::where('profile_id', $this->profile_id)->first();
                $designation_data = \App\Neo4j\Experiance::where('name',$designation)->first();
                if (!is_null($designation_data)) {
                    $designation_data_have_user = $designation_data->have->where('profile_id', (int)$this->profile_id)->first();
                    if ($designation_data_have_user) {
                        $detach_result = $designation_data->have()->detach($user);
                    }
                }
            }
        }
    }

}
