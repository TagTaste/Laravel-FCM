<?php
/**
 * Created by PhpStorm.
 * User: ashok
 * Date: 19/08/17
 * Time: 4:23 PM
 */
namespace App\Company;

use App\Profile;
use Illuminate\Database\Eloquent\Model;

class 
Coreteam extends Model
{

    protected $table = 'core_teams';

    protected $fillable = ['name','email','image', 'designation' ,'about' ,'company_id','order','profile_id','invited','company_id'];

    protected $visible = ['id','name', 'designation' ,'about' ,'company_id','imageUrl','order','profile_id','invited','is_following'];

    protected $appends = ['imageUrl','is_following'];


    public static function getCoreteamImagePath($companyId, $filename = null)
    {
        $relativePath = "images/ph/$companyId/c/coreteam";
        $status = \Storage::makeDirectory($relativePath,0644,true);
        return $filename === null ? $relativePath : $relativePath . "/" . $filename;
    }

    public function getImageUrlAttribute()
    {
        $image = null;
        if (!is_null($this->image)) {
            $pos = strpos($this->image, env('S3_BUCKET'));
            if ($pos === false) {
                $image = \Storage::url($this->image);
            } else {
                $image = $this->image;
            }
        }
        return $image;
    }

    public function getIsFollowingAttribute()
    {
        return $this->profile_id!=null ? Profile::isFollowing(request()->user()->profile->id, $this->profile_id) : false;
    }
    
    public function setEmailAttribute($value)
    {
        return empty($value) ? null : $value;
    }

}
