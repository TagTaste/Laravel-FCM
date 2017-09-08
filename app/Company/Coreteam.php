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

class Coreteam extends Model
{

    protected $table = 'core_teams';

    protected $fillable = ['name','email','image', 'designation' ,'about' ,'company_id','order','profile_id','invited'];

    protected $visible = ['id','name', 'designation' ,'about' ,'company_id','imageUrl','order','profile_id','invited','is_following'];

    protected $appends = ['imageUrl','is_following'];


    public static function getCoreteamImagePath($profileId,$companyId, $filename = null)
    {
        $relativePath = "images/ph/$companyId/c/coreteam";
        $status = \Storage::makeDirectory($relativePath,0644,true);
        return $filename === null ? $relativePath : $relativePath . "/" . $filename;
    }

    public function getImageUrlAttribute()
    {
        return !is_null($this->image) ? \Storage::url($this->image) : null;
    }

    public function getIsFollowingAttribute()
    {
        return $this->profile_id!=null ? Profile::isFollowing($this->profile_id, request()->user()->profile->id) : false;
    }

}
