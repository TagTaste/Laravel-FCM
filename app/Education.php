<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
//    use StartEndDate;

    protected $table = 'education';

    protected $fillable = ['degree','college','field','grade','percentage','description','start_date','end_date','ongoing','location','profile_id'];

    protected $visible = ['id','degree','college','field','grade','percentage','description','start_date','end_date','ongoing','location','profile_id'];

    protected static function boot()
    {
        parent::boot();
        // Order by name ASC
        static::addGlobalScope('education', function (Builder $builder) {
            $builder->orderBy('ongoing','desc')->orderBy('start_date', 'desc');
        });
    }

    public function profile()
    {
        return $this->belongsTo('App\Profile');
    }

    public function seo_friendly_url($string)
    {
        $string = str_replace('.', '', $string);
        $string = str_replace('. ', '', $string);
        $string = str_replace("'", '', $string);
        $string = str_replace(array('[\', \']'), '', $string);
        $string = preg_replace('/\[.*\]/U', '', $string);
        $string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', '_', $string);
        $string = htmlentities($string, ENT_COMPAT, 'utf-8');
        $string = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $string );
        $string = preg_replace(array('/[^a-z0-9]/i', '/[-]+/') , '_', $string);
        return strtoupper(trim($string, '_'));
    }

    public function remove_unwanted_info($degree)
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
            "B TECH" => "BTECH",
            "B.TECH" => "BTECH",
            "B_TECH" => "BTECH",
            "M TECH" => "MTECH",
            "M.TECH" => "MTECH",
            "M_TECH" => "MTECH",
            "_RSQUO_" => ""
        );

        $string =  str_replace(
            array_keys($replacer),
            array_values($replacer),
            preg_replace('/\_+/', "_",$this->seo_friendly_url($degree))
        );

        return $string;
    }
   
    public function addUserEducation()
    {
        if ($this->degree) {
            $degree = $this->seo_friendly_url($this->degree);
            $degree = $this->remove_unwanted_info($this->degree);
            if (strlen($degree)) {
                $degree_exist = \App\Neo4j\Degree::where('name',$degree)->first();
                if (!$degree_exist) {
                    $degree_exist = \App\Neo4j\Degree::create([
                        "name" => $degree
                    ]);
                }
                if ($degree_exist) {
                    $user = \App\Neo4j\User::where('profile_id', $this->profile_id)->first();
                    if ($user) {
                        $degree_have_user = $degree_exist->have->where('profile_id', (int)$this->profile_id)->first();
                        if (!$degree_have_user) {
                            $relation = $degree_exist->have()->attach($user);
                            $relation->status = 1;
                            $relation->statusValue = "have";
                            $relation->save();
                        } else {
                            $relation = $degree_exist->have()->edge($user);
                            $relation->status = 1;
                            $relation->statusValue = "have";
                            $relation->save();
                        }
                    }
                }

            }
        }
    }

    public function updateUserEducation()
    {
        if ($this->degree) {
            $degree = $this->seo_friendly_url($this->degree);
            $degree = $this->remove_unwanted_info($this->degree);
            if (strlen($degree)) {
                $degree_exist = \App\Neo4j\Degree::where('name',$degree)->first();
                if (!$degree_exist) {
                    $degree_exist = \App\Neo4j\Degree::create([
                        "name" => $degree
                    ]);
                }
                if ($degree_exist) {
                    $user = \App\Neo4j\User::where('profile_id', $this->profile_id)->first();
                    if ($user) {
                        $degree_have_user = $degree_exist->have->where('profile_id', (int)$this->profile_id)->first();
                        if (!$degree_have_user) {
                            $relation = $degree_exist->have()->attach($user);
                            $relation->status = 1;
                            $relation->statusValue = "have";
                            $relation->save();
                        } else {
                            $relation = $degree_exist->have()->edge($user);
                            $relation->status = 1;
                            $relation->statusValue = "have";
                            $relation->save();
                        }
                    }
                }

            }
        }
    }

    public function detachUserEducation()
    {
        if ($this->degree) {
            $degree = $this->seo_friendly_url($this->degree);
            $degree = $this->remove_unwanted_info($this->degree);
            if (strlen($degree)) {
                $user = \App\Neo4j\User::where('profile_id', $this->profile_id)->first();
                $degree_data = \App\Neo4j\Degree::where('name',$degree)->first();
                if (!is_null($degree_data)) {
                    $degree_data_have_user = $degree_data->have->where('profile_id', (int)$this->profile_id)->first();
                    if ($degree_data_have_user) {
                        $detach_result = $degree_data->have()->detach($user);
                    }
                }
            }
        }
    }
}
