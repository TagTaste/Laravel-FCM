<?php

namespace App\Payment;

use App\Traits\IdentifiesOwner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentLinks extends Model
{
    use SoftDeletes, IdentifiesOwner;
    protected $table = 'payment_links';
    protected $guarded = ["id"];

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    protected $with = ['profile'];

    protected $appends = ['owner'];


    protected $visible = ["id", "transaction_id", "amount", "payout_link_id", "link", "is_active", "status_id", "expired_at", "comments", "phone", "profile_id", "model_id", "sub_model_id", "model_type", "created_at", "updated_at","payment_id","is_expert","account_reconciliation_date"];
 
    public function profile()
    {
        return $this->belongsTo('App\Profile');
    }

    public function status()
    {
        return $this->belongsTo('App\Payment\PaymentStatus',"status_id");
    }

    
}
