<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    protected $primaryKey = null;
    
    protected $fillable = ['compatible_version', 'latest_version'];
    
    public function isCompatible($version)
    {
        return $this->compatible_version === (float) $version;
    }
    
    public static function getVersion()
    {
        return static::select("compatible_version","latest_version")->first();
    }
    
    public function toHeaders()
    {
        return ['X-Compatible-Version'=>$this->compatible_version,'X-Latest-Version'=>$this->latest_version];
    }
}
