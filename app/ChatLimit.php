<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatLimit extends Model
{
    private static $MAX = 5;
    private static $REMAINING = 5;
    
    protected $fillable = ['profile_id', 'remaining', 'max'];
    
    public static function checkLimit(&$profileId)
    {
        $currentLimit = ChatLimit::where('profile_id',$profileId)->first();
        
        if(!$currentLimit){
            $currentLimit = static::createLimit($profileId,self::$MAX,self::$REMAINING);
        }
        
        //for admins, marketing people
        if($currentLimit->max == null){
            return true;
        }
        
        if($currentLimit->remaining > 0){
            $currentLimit->remaining--;
            $currentLimit->save();
            return true;
        }
        
        return false;
    }
    
    private static function createLimit(&$profileId,$max=null,$remaining=5)
    {
        return ChatLimit::create(['profile_id'=>$profileId,'max'=>$max,'remaining'=>$remaining]);
    }
    
    public static function increaseLimit(&$profileId)
    {
        $currentLimit = ChatLimit::where('profile_id',$profileId)->first();
    
        if(!$currentLimit){
            $currentLimit = self::createLimit($profileId,self::$MAX,self::$REMAINING);
        }
    
        if($currentLimit->max == null){
            return true;
        }
    
        if($currentLimit->max > $currentLimit->remaining){
            $currentLimit->remaining++;
            $currentLimit->save();
        }
        
        return true;
    }
    
}
