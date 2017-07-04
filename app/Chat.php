<?php

namespace App;

use App\Chat\Member;
use App\Chat\Message;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chat extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['name', 'profile_id'];
    
    protected $with = ['members'];
    
    protected $appends = ['latestMessages'];
    
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
        return $this->hasManyThrough( \App\Recipe\Profile::class, Member::class,'profile_id','id');
    }
    
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    
    public function profile()
    {
        return $this->belongsTo(\App\Recipe\Profile::class,'profile_id');
    }
    
    //set name of the chat as the second member of the chat, for a two person chat.
    public function getNameAttribute($value)
    {
        if($this->members->count() === 2){
            $to = $this->members->whereNotIn('id',[$this->profile_id]);
            if($to->count() === 0){
                //it would never come back here, but still.
                return $value;
            }
            return $to->first()->name;
        }
        return $value;
    }
    
    public function getLatestMessagesAttribute()
    {
        return $this->messages()->orderBy('created_at','desc')->take(5)->get();
    }
}
