<?php

namespace App;

use App\Payment\PaymentLinks;

class PaymentHelper
{
    public static function getDispatchedPaymentUserTypes($paymentDetails)
    {
        $getUsersExpert = PaymentLinks::where("payment_id", $paymentDetails->id)->whereNull("deleted_at")->where("status_id", "<>", config("constant.PAYMENT_STATUS.cancelled"))->where("is_expert", 1)->get();

        $getUsersNonExpert = PaymentLinks::where("payment_id", $paymentDetails->id)->whereNull("deleted_at")->where("status_id", "<>", config("constant.PAYMENT_STATUS.cancelled"))->where("is_expert", "=", 0)->get();

        $profileIds = ["expert" => (int)$getUsersExpert->count(), "consumer" => (int)$getUsersNonExpert->count()];

        return $profileIds;
    }

    public static function  getisPaidMetaFlag($paymentDetails)
    {
        if (!empty($paymentDetails)) {
            $isPaid = true;
            $exp = ((!empty($paymentDetails) && !empty($paymentDetails->excluded_profiles)) ? $paymentDetails->excluded_profiles : null);
            if ($exp != null) {
                $separate = explode(",", $exp);
                if (in_array(request()->user()->profile->id, $separate)) {
                    //excluded profile error to be updated
                    $isPaid = false;
                }
            }
            if ($isPaid == true) {

                $getCount = self::getDispatchedPaymentUserTypes($paymentDetails);
                if (request()->user()->profile->is_expert) {
                    $ukey = "expert";
                } else {
                    $ukey = "consumer";
                }

                if ($paymentDetails->review_type == config("payment.PAYMENT_REVIEW_TYPE.USER_TYPE")) {
                    $getAmount = json_decode($paymentDetails->amount_json, true);
                    if (($getCount[$ukey] + 1) > $getAmount["current"][$ukey][0]["user_count"]) {
                        $isPaid = false;
                    }
                } else {
                    $links = PaymentLinks::where("payment_id", $paymentDetails->id)->whereNull('deleted_at')->where("status_id", "<>", config("constant.PAYMENT_CANCELLED_STATUS_ID"))->get();
                    if ((int)$links->count() >=  (int)$paymentDetails->user_count) {
                        $isPaid = false;
                    }
                }
            }
        } else {
            $isPaid = false;
        }

        return  $isPaid;
    }
}
