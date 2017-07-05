<?php

namespace App;

use App\Chat\Member;
use App\Chat\Message;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chat extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['name', 'profile_id','image'];
    
    //protected $with = ['members'];
    
    protected $visible = ['id','name','imageUrl','profile_id','created_at','latestMessages','profiles'];
    
    protected $appends = ['latestMessages','profiles','imageUrl'];
    
    public static function boot()
    {
        self::created(function($chat){
            $now = \Carbon\Carbon::now();
            $data = ['chat_id'=>$chat->id,'profile_id'=>$chat->profile_id, 'created_at'=>$now->toDateTimeString()];
            Member::create($data);
        });
    }
    
    public function members()
    {
        return $this->hasMany( Member::class);
    }
    
    public function getProfilesAttribute()
    {
        return $this->members->pluck('profile');
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
        return $this->messages()->orderBy('created_at','desc')->take(5)->get();
    }
    
    public static function getImagePath($id, $filename = null)
    {
        //$relativePath = "profile/{$id}/images";
        $relativePath = "images/c/{$id}";
        
        Storage::makeDirectory($relativePath);
        return $filename === null ? $relativePath : storage_path("app/" . $relativePath) . "/" . $filename;
    }
    
    public function getImageUrlAttribute()
    {
        if(!is_null($this->image)){
            return "/images/c/" . $this->id . "/" . $this->image;
        }
        
    }
    
    public static function open($profileIdOne,$profileIdTwo)
    {
        $chatIds = Member::distinct('chat_id')->where(function($query) use ($profileIdOne,$profileIdTwo){
            $query->where('chat_members.profile_id','=',$profileIdOne)->orWhere('chat_members.profile_id','=',$profileIdTwo);
        })->get();
        
        return Chat::whereIn('id',$chatIds->toArray())->get();
        
    }
}
