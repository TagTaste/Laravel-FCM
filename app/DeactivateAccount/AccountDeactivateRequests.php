<?php

namespace App\DeactivateAccount;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountDeactivateRequests extends Model
{
    use SoftDeletes;
    protected $table = 'account_deactivate_requests';
    protected $guarded = ["id"];

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';
}
