<?php

namespace App;

use App\Payment\PaymentLinks;

class PaymentHelper
{
    public static function getDispatchedPaymentUserTypes($paymentDetails)
    {
        $getUsersExpert = PaymentLinks::where("payment_id", $paymentDetails->id)->whereNull("deleted_at")->where("status_id","<>", config("constant.PAYMENT_STATUS.cancelled"))->where("is_expert", 1)->get();

        $getUsersNonExpert = PaymentLinks::where("payment_id", $paymentDetails->id)->whereNull("deleted_at")->where("status_id","<>", config("constant.PAYMENT_STATUS.cancelled"))->where("is_expert", "=", 0)->get();

        $profileIds = ["expert" => (int)$getUsersExpert->count(), "consumer" => (int)$getUsersNonExpert->count()];

        return $profileIds;
    }
}
