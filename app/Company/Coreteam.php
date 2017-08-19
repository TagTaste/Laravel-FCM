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

    protected $fillable = ['name','email','image', 'designation' ,'about' ,'company_id'];

    protected $visible = ['id','name','email','image', 'designation' ,'about' ,'company_id'];

    public static function getCoreteamImagePath($profileId,$companyId, $filename = null)
    {
        $relativePath = "images/ph/$companyId/c/coreteam";
        $status = \Storage::makeDirectory($relativePath,0644,true);
        return $filename === null ? $relativePath : $relativePath . "/" . $filename;
    }

}
