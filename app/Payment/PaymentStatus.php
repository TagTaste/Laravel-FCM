<?php

namespace App\Payment;

use App\Traits\IdentifiesOwner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentStatus extends Model
{
    use SoftDeletes;
    protected $table = 'payment_status';
    protected $guarded = ["id"];

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';
}
