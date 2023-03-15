<?php

namespace App\Profile;

use Illuminate\Database\Eloquent\Model;


class Badge extends Model {

    protected $table = 'profile_badges';

    protected $fillable = ['profile_id','badge_id'];

    protected $visible = ['id','title','description', 'image'];

    protected $appends = ['id','title','description', 'image'];

    protected $badge = null;

    public function getIdAttribute()
    {
        $this->badge = \DB::table('badges')->where('id',$this->badge_id)->first();
        return isset($this->badge->id) ? $this->badge->id : null;
    }

    public function getTitleAttribute()
    {
        return isset($this->badge->title) ? $this->badge->title : null;
    }

    public function getDescriptionAttribute()
    {
        return isset($this->badge->description) ? $this->badge->description : null;
    }

    public function getImageAttribute()
    {
        return isset($this->badge->image_meta) ? json_decode($this->badge->image_meta) : null;
    }
}
