<?php

namespace App\DeactivateAccount;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountDeactivateReasons extends Model
{
    use SoftDeletes;
    protected $table = 'account_deactivate_reasons';
    protected $guarded = ["id"];

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';
}
