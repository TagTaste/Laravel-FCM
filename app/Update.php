<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Update extends Model
{
    protected $fillable = ['content', 'model_id', 'model_name', 'profile_id'];

    public function storeData($id,$name,$profileId,$content){

        $this->create($id,$name,$profileId,$content);

    }
    public function create($id,$name,$profileId,$content)
    {
        $this->model_id=$id;
        $this->content=$content;
        $this->model_name=$name;
        $this->profile_id=$profileId;
        $this->is_read=0;
        $this->save();

        \Redis::publish('notification-channel',$this->toJson());
    }
}
