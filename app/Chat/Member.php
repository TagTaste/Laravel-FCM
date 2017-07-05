<?php

namespace App\Chat;

use App\Chat;
use App\Recipe\Profile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use SoftDeletes;
    
    protected $table = 'chat_members';
    
    protected $fillable = ['chat_id', 'profile_id'];
    
    protected $visible = ['profile'];
    
    protected $with = ['profile'];
    
    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }
    
    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}
