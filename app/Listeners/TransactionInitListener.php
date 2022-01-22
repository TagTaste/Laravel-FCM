<?php

namespace App\Listeners;

use App\Events\Actions\PaymentTransactionCreate;
use App\Events\TransactionInit;
use App\Jobs\paymentInit;
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

        $channel = config("app.payment_channel");
        $event->data = (object)$event->data;

        if ($event->data->model_type == "Survey") {
            $initials = "TXN_SUR_" . date("dmy");
        } else if ($event->data->model_type == "Private Review") {
            $initials = "TXN_PRR_" . date("dmy");
        } else if ($event->data->model_type == "Public Review") {
            $initials = "TXN_PUR_" . date("dmy");
        }

        $getOldTxnId = PaymentLinks::where("transaction_id", "LIKE", '%' . $initials . "%")->orderBy("id", "desc")->first();

        $number = 0;
        if (!empty($getOldTxnId) && isset($getOldTxnId->transaction_id)) {
            $explode = explode("_", $getOldTxnId->transaction_id);
            $number = (int)array_pop($explode);
        }

        if (config("app.env") != "production") {
            $initials .= "_TEST";
        }
        $buildTxnId = $initials . "_" . ++$number;

        $data = PaymentLinks::create(["transaction_id" => $buildTxnId, "profile_id" => request()->user()->profile->id, "model_type" => $event->data->model_type, "model_id" => $event->data->model_id, "sub_model_id" => $event->data->sub_model_id ?? NULL, "amount" => $event->data->amount, "phone" => request()->user()->profile->phone, "status_id" => config("constant.PAYMENT_INITIATED_STATUS_ID"), "payment_id" => $event->data->payment_id, "payment_channel" => $channel,"is_expert"=>request()->user()->profile->is_expert]);


        if ($data) {
            if (!empty(request()->user()->profile->phone)) {
                $d = ["transaction_id" => $buildTxnId, "amount" => $event->data->amount, "phone" => request()->user()->profile->phone, "email" => request()->user()->email, "model_type" => $event->data->model_type, "title" => $event->data->model_id, "name" => request()->user()->name ?? "", "model" => $data, "model_id" => $event->data->model_id];
                if (isset($event->data->comment)) {
                    $d["comment"] = $event->data->comment;
                }
                $obj = new paymentInit($d);
                dispatch($obj);
                return ["status" => true];
            } else {
                return ["status" => false, "reason" => "phone"];
            }
        }
        return ["status" => false, "reason" => "txn failed"];
    }
}
