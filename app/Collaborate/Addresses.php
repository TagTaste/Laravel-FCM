<?php

namespace App\Collaborate;

use Illuminate\Database\Eloquent\Model;


class Addresses extends Model {

    protected $table = 'collaborate_addresses';

    protected $fillable = ['city','location','collaborate_id'];

    protected $visible = ['city','location','collaborate_id'];

}
