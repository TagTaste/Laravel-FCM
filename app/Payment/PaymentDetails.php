<?php

namespace App\Payment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentDetails extends Model
{
    use SoftDeletes;
    protected $table = 'payment_details';
    protected $guarded = ["id"];

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';
}
