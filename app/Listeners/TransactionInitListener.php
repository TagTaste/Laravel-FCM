<?php

namespace App\Listeners;

use App\Events\TransactionInit;
use App\Payment\PaymentLinks;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TransactionInitListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  TransactionInit  $event
     * @return void
     */
    public function handle(TransactionInit $event)
    {

        if ($event->model == "Survey") {
            $initials = "TXN_SUR_" . date("dmy");
        } else if ($event->model == "Private Review") {

            $initials = "TXN_SUR_" . date("dmy");
        } else if ($event->model == "Public Review") {

            $initials = "TXN_SUR_" . date("dmy");
        }
        $getOldTxnId = PaymentLinks::where("tranaction_id", "LIKE", '%' . $initials)->orderBy("id", "desc")->first();
        $number = 1;
        if (!empty($getOldTxnId) && isset($getOldTxnId->transaction_id)) {
            $explode = explode("_", $getOldTxnId);
            $number = (int)array_pop($explode);
        }
        $buildTxnId = $initials . "_" . $number;

        $data = PaymentLinks::create(["transaction_id" => $buildTxnId, "profile_id" => request()->user()->profile->id, "model_type" => $event->model_type, "model_id" => $event->model_id, "sub_model_id" => $event->sub_model_id, "amount" => $event->amount, "phone" => request()->user()->profile->phone, "status_id" => config("constant.PAYMENT_PENDING_STATUS_ID")]);
        if ($data) {
            //dispach jon for paytm
            return true;
        }
        return false;
    }
}
