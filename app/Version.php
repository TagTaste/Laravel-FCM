<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    protected $table = 'app_versions';
//    protected $primaryKey = 'compatible_version';
    public $incrementing = false;

    public static $APP_IOS = 'ios';
    public static $APP_ANDROID = 'android';


    protected $fillable = ['compatible_version', 'latest_version', 'platform'];
    
    public function isCompatible($version)
    {
        return $this->compatible_version > $version;
    }
    
    public static function getVersion($platform)
    {
        return static::select("compatible_version","latest_version")->where('platform', $platform)->first();
    }
    
    public static function setVersion($compatibleVersion,$latestVersion = null, $platform)
    {
        $version = static::getVersion($platform);
        $version->compatible_version = $compatibleVersion;
        if(!is_null($latestVersion)){
            $version->latest_version = $latestVersion;
        }
        $version->update();
        
        return $version;
    }
    
    public function toHeaders()
    {
        return $this->platform == static::$APP_ANDROID ?
            ['X-Compatible-Version'=>$this->compatible_version,'X-Latest-Version'=>$this->latest_version] :
            ['X-Compatible-Version-Ios'=>$this->compatible_version,'X-Latest-Version-Ios'=>$this->latest_version];
    }
}
