<?php

namespace App;

use App\Chat\Member;
use App\Chat\Message;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chat extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'profile_id','image','chat_type'];

    //protected $with = ['members'];

    protected $visible = ['id','name','imageUrl','profile_id','created_at','updated_at','latestMessages','profiles','unreadMessageCount','is_enabled','chat_type'];

    protected $appends = ['latestMessages','profiles','imageUrl','unreadMessageCount','is_enabled'];

    protected $isEnabled = true;

    public function members()
    {
        return $this->hasMany( Member::class);
    }

    public function getProfilesAttribute()
    {
        // $memberOfChat = Chat\Member::withTrashed()->where('chat_id',$this->id)->where('profile_id',request()->user()->profile->id)->first();
        // if(isset($memberOfChat->deleted_at))
        // {
        //     return $this->members()->where('profile_id','!=',request()->user()->profile->id)->where('created_at','<=',$memberOfChat->deleted_at)->whereNull('deleted_at')->get()->pluck('profile');
        // }
        // else
        // {
        //     if(isset($memberOfChat->deleted_at))
        //     {
        //         $memberOfChat->restore();
        //     }
        //     return $this->members()->withTrashed()->whereNull('deleted_at')->get()->pluck('profile');
        // }
        if($this->chat_type === 1)
        {
            return $this->members()->whereNull('deleted_at')->get()->pluck('profile');
        }

    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function profile()
    {
        return $this->belongsTo(\App\Recipe\Profile::class,'profile_id');
        
    }

    public function getLatestMessagesAttribute()
    {
        $memberOfChat = Chat\Member::withTrashed()->where('chat_id',$this->id)->where('profile_id',request()->user()->profile->id)->first();
        if(!$memberOfChat){
            return;
        }

        if(isset($memberOfChat->deleted_at))
        {
            $this->isEnabled = false;
            $message = $this->messages()->whereBetween('created_at',[$memberOfChat->created_at,$memberOfChat->deleted_at])->orderBy('created_at','desc')->take(1)->get();
            $msgArray = $message->toArray();
            $id = $msgArray[0]["id"];
            $isDeleted = \DB::table('message_recepients')->where('message_id',$id)->where('recepient_id',request()->user()->profile->id)->whereNull('deleted_on')->first();
            if($isDeleted)
            {
                return $message;
            }
            else{
                return null;
            }
        }
        else
        {
            $message = $this->messages()->where('created_at','>=',$memberOfChat->created_at)->orderBy('created_at','desc')->where('type',0)->take(1)->get();
            $msgArray = $message->toArray();
            if(count($msgArray)!=0)
            {
                $id = $msgArray[0]["id"];
                $isNotDeleted = \DB::table('message_recepients')->where('message_id',$id)->where('recepient_id',request()->user()->profile->id)->whereNull('deleted_on')->first();
                if($isNotDeleted)
                {
                return $message;
                }
            }
            else{
                return null;
            }
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
        return \DB::table('message_recepients')->where('recepient_id',request()->user()->profile->id)->where('chat_id',$this->id)->whereNull('read_on')->whereNull('deleted_on')->count();
    }

    public function getIsEnabledAttribute()
    {
        return $this->isEnabled;
    }
}

