<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    protected $table = 'apk_versions';
    protected $primaryKey = 'compatible_version';
    public $incrementing = false;
    
    protected $fillable = ['compatible_version', 'latest_version'];
    
    public function isCompatible($version)
    {
        return $this->compatible_version > $version;
    }
    
    public static function getVersion()
    {
        return static::select("compatible_version","latest_version")->first();
    }
    
    public static function setVersion($compatibleVersion,$latestVersion = null)
    {
        $version = static::getVersion();
        $version->compatible_version = $compatibleVersion;
        if(!is_null($latestVersion)){
            $version->latest_version = $latestVersion;
        }
        $version->update();
        
        return $version;
    }
    
    public function toHeaders()
    {
        return ['X-Compatible-Version'=>$this->compatible_version,'X-Latest-Version'=>$this->latest_version];
    }
}
