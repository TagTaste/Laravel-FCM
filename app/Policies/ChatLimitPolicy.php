<?php

namespace App\Policies;

use App\ChatLimit;
use App\User;
use App\Chat;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChatLimitPolicy
{
    use HandlesAuthorization;
    
    private static $MAX = 5;
    private static $REMAINING = 5;

    /**
     * Determine whether the user can create chats.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        $profileId = $user->profile->id;
        
        return $this->checkLimit($profileId);
    }
    
    private function checkLimit($profileId)
    {
        $currentLimit = ChatLimit::where('profile_id',$profileId)->first();
    
        if(!$currentLimit){
            $currentLimit = $this->createLimit($profileId,self::$MAX,self::$REMAINING);
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
    
    private function createLimit(&$profileId,$max=null,$remaining=5)
    {
        return ChatLimit::create(['profile_id'=>$profileId,'max'=>$max,'remaining'=>$remaining]);
    }

    /**
     * Determine whether the user can delete the chat.
     *
     * @param  \App\User  $user
     * @param  \App\Chat  $chat
     * @return mixed
     */
    public function delete(User $user, Chat $chat)
    {
        $profileId = $user->profile->id;
    
        $currentLimit = ChatLimit::where('profile_id',$profileId)->first();
        
        if(!$currentLimit){
            $currentLimit = $this->createLimit($profileId,self::$MAX,self::$REMAINING);
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
