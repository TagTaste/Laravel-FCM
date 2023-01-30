<?php

namespace App\BlockAccount;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlockAccount extends Model
{
    use SoftDeletes;
    protected $table = 'block_profiles_mapping';
    protected $guarded = ["id"];
    
    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';
}
