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
    
    protected $visible = ['id','name','imageUrl','profile_id','created_at','updated_at','latestMessages','profiles'];
    
    protected $appends = ['latestMessages','profiles','imageUrl'];
    
    public static function boot()
    {
        self::created(function($chat){
            $now = \Carbon\Carbon::now();
            $data = ['chat_id'=>$chat->id,'profile_id'=>$chat->profile_id, 'created_at'=>$now->toDateTimeString(),'is_admin'=>1];
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
                ->where('c1.profile_id','=',$profileIdOne)
                ->where('c2.profile_id','=',$profileIdTwo)
            ->groupBy('c1.chat_id')
            ->get();
        
        if($chatIds->count() === 0){
            return null;
        }
        
        $chatIds = \DB::table("chat_members")->selectRaw("chat_id,count(chat_id)")
            ->groupBy("chat_id")->whereIn('chat_id',$chatIds->pluck('id')->toArray())
            ->whereNull("deleted_at")
            ->havingRaw("count(chat_id) = 2")
            ->get();
        
        if($chatIds->count() === 0){
            return null;
        }
        return Chat::whereIn('id',$chatIds->pluck('chat_id')->toArray())->get();
        
    }
}
