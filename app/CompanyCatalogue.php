<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;


class CompanyCatalogue extends Model
{
    protected $fillable = ['company_id', 'image'];

    public static function checkExists(&$catalogueDetails)
    {
        return self::where('image', $catalogueDetails['image'])->where('company_id',$catalogueDetails['company_id'])->exists();
    }

    public static function getCompanyImagePath($profileId,$companyId, $filename = null)
    {
        $relativePath = "images/ph/$profileId/c/$companyId/p";
        $status = Storage::makeDirectory($relativePath,0644,true);
        if($filename === null){
            return $relativePath;
        }
        return storage_path("app/".$relativePath) . "/" . $filename;
    }
}
