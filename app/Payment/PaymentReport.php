<?php

namespace App\Payment;

use App\Traits\IdentifiesOwner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentReport extends Model
{
    use SoftDeletes, IdentifiesOwner;
    protected $table = 'payment_report';
    protected $guarded = ["id"];

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    // protected $with = ['profile'];
    
    protected $appends = ['owner'];

    protected $visible = ["id", "transaction_id", "profile_id", "title", "description","complaint_id"];
}
