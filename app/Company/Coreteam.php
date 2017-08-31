<?php
/**
 * Created by PhpStorm.
 * User: ashok
 * Date: 19/08/17
 * Time: 4:23 PM
 */
namespace App\Company;

use Illuminate\Database\Eloquent\Model;

class Coreteam extends Model
{

    protected $table = 'core_teams';

    protected $fillable = ['name','email','image', 'designation' ,'about' ,'company_id','order','profile_id','invited'];

    protected $visible = ['id','name', 'designation' ,'about' ,'company_id','imageUrl','order','profile_id','invited'];

    protected $appends = ['imageUrl'];


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

}
