<?php
/**
 * Created by PhpStorm.
 * User: ashok
 * Date: 19/08/17
 * Time: 4:23 PM
 */
namespace App\Company;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $table = 'company_galleries';

    protected $fillable = ['name','description','image' ,'company_id','image_meta'];

    protected $visible = ['id','name', 'description' ,'company_id','imageUrl','image_meta'];

    protected $appends = ['imageUrl'];


    public static function getGalleryImagePath($profileId,$companyId, $filename = null)
    {
        $relativePath = "images/c/$companyId/gallery";
        $status = \Storage::makeDirectory($relativePath,0644,true);
        return $filename === null ? $relativePath : $relativePath . "/" . $filename;
    }

    public function getImageUrlAttribute()
    {
        return !is_null($this->image) ? \Storage::url($this->image) : null;
    }

}
