<?php

namespace App\V1\Chat;

use App\V1\Chat;
use App\V1\Chat\Profile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use SoftDeletes;
    
    protected $table = 'chat_members';
    
    protected $fillable = ['chat_id', 'profile_id','is_admin','is_single','exited_on','last_seen','deleted_at'];
    
    protected $visible = ['profile','is_admin','is_single','created_at','deleted_at','exited_on','last_seen','deleted_at'];
    
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
