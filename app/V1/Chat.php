<?php

namespace App\V1;

use App\V1\Chat\Member;
use App\V1\Chat\Message;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Redis;

class Chat extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'profile_id','image','chat_type'];

    //protected $with = ['members'];

    protected $visible = ['id','name','image','profile_id','created_at','updated_at','latestMessages','profiles',
        'unreadMessageCount','is_enabled','chat_type','isAdmin','isOnline'];

    protected $appends = ['latestMessages','profiles','unreadMessageCount','is_enabled','isAdmin','isOnline'];

    //chat type = 1 is single chat, 0 is group chat

    protected $isEnabled = true;

    public function members()
    {
        return $this->hasMany( Member::class);
    }

    public function getProfilesAttribute()
    {
         if($this->chat_type == 1)
         {
             return $this->members()->whereNull('exited_on')->withTrashed()->get()->pluck('profile');
         }
         return null;
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function profile()
    {
        return $this->belongsTo(\App\V1\Chat\Profile::class,'profile_id');
        
    }

    public function getLatestMessagesAttribute()
    {   
        $memberOfChat = \DB::table('chat_members')->where('chat_id',$this->id)->where('profile_id',request()->user()->profile->id)->first();
        if(!$memberOfChat){
            return;
        }

        if(isset($memberOfChat->deleted_at))
        {
            return Message::join('message_recepients', function($query){
                $query->on('message_recepients.message_id','=','chat_messages.id');
            })->orderBy('message_recepients.sent_on','desc')->where('message_recepients.chat_id','=',$this->id)
                ->where('message_recepients.recepient_id',request()->user()->profile->id)
                ->whereNull('message_recepients.deleted_on')->first();
        }
        else
        {
            return Message::join('message_recepients', function($query){
                $query->on('message_recepients.message_id','=','chat_messages.id');
            })->orderBy('message_recepients.sent_on','desc')->where('message_recepients.recepient_id',request()->user()->profile->id)
                ->where('message_recepients.chat_id','=',$this->id)
                ->whereNull('message_recepients.deleted_on')->first();
        }
    }

    public static function getImagePath($id, $filename = null)
    {
        //$relativePath = "profile/{$id}/images";
        $relativePath = "images/c/{$id}";

        \Storage::makeDirectory($relativePath);

        return $filename === null ? $relativePath : storage_path("app/" . $relativePath) . "/" . $filename;
    }

    public function getImageUrlAttribute()
    {
        return !is_null($this->image) ? \Storage::url($this->image) : null;
    }

    public static function open($profileIdOne,$profileIdTwo)
    {
        $chatIds = \DB::table("chat_members as c1")->selectRaw(\DB::raw("c1.chat_id as id"))
            ->join('chat_members as c2','c2.chat_id','=','c1.chat_id')
            ->join("chats",'chats.id','=','c1.chat_id')
            ->where(function($query) use ($profileIdOne){
                $query->where('c1.profile_id','=',$profileIdOne)
                ;
            })->where(function($query) use ($profileIdTwo) {
                $query->where('c2.profile_id','=',$profileIdTwo)
                ;
            })
            ->where('chat_type',1)
            ->whereNull('chats.deleted_at')
            ->groupBy('c1.chat_id')
            ->orderBy('c1.chat_id')
            ->first();

        return $chatIds == null ? null : Chat::where('id',$chatIds->id)->first();
    }

    public function getUnreadMessageCountAttribute()
    {
    
        return \DB::table('message_recepients')->where('recepient_id',request()->user()->profile->id)
            ->where('chat_id',$this->id)->whereNull('read_on')->whereNull('deleted_on')->count();
    }

    public function getIsEnabledAttribute()
    {
       return Member::withTrashed()->where('profile_id',request()->user()->profile->id)->where('chat_id',$this->id)
           ->whereNull('exited_on')->exists();
    }

    public function getIsAdminAttribute()
    {
        $loggedInProfileId = request()->user()->profile->id;
        return \DB::table('chat_members')->where('chat_id',$this->id)->where('profile_id',$loggedInProfileId)->where('is_admin',1)->whereNull('exited_on')->exists();
    }

    public function getIsOnlineAttribute()
    {
        $loggedInProfileId = request()->user()->profile->id;
        return Redis::sIsMember("online:profile:",$loggedInProfileId);
    }
}

