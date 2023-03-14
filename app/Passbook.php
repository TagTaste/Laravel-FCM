<?php

namespace App;

use App\Chat\Member;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Payment\PaymentLinks;

class Passbook extends Model
{
    protected $table = "passbook_read_mapping";
    const UPDATED_AT = 'passbook_read_at';

    protected $visible = ['id', 'profile_id', 'unreadPassbookCount','passbook_read_at'];

    protected $appends = ['unreadPassbookCount'];


    public function getUnreadPassbookCountAttribute()
    {
        $count = 0;
        $paymentLinksTime = PaymentLinks::select('created_at')->where('profile_id', $this->id)->where('is_active', 1)->whereNull('deleted_at')->first();
        $passbookTime = Passbook::select('passbook_read_at')->where('profile_id', $this->id)->first();

        if ($paymentLinksTime < $passbookTime) {
            $count++;
        }
        return $count;
    }
}
