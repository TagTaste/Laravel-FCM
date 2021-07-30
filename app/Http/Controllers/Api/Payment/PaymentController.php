<?php

namespace App\Http\Controllers\Api\Payment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Payment\PaymentLinks as PaymentLinks;
use App\Payment\PaymentStatus;
use Tagtaste\Api\SendsJsonResponse;

class PaymentController extends Controller
{
    use SendsJsonResponse;

    protected $model;
    public function __construct(PaymentLinks $model)
    {
        $this->model = $model;
    }
    public function passbookTxns(Request $request)
    {
        $page = $request->input('page');
        list($skip, $take) = \App\Strategies\Paginator::paginate($page);

        $getData = PaymentLinks::where("profile_id", $request->user()->profile->id)->where("deleted_at", "=", null);

        if ($request->has("transaction_id") && !empty($request->transaction_id)) {
            $getData->where("transaction_id", $request->transaction_id);
        }

        if ($request->has("phone") && !empty($request->phone)) {
            $getData->where("phone", $request->phone);
        }
        $this->model['count'] = $getData->count();

        $details = $getData->skip($skip)->take($take);

        $this->model["payments"] = $details;

        return $this->sendResponse();
    }

    public function getTxnsById($txn_id, Request $request)
    {
        $this->model = PaymentLinks::where("transaction_id", $txn_id)->where("deleted_at", "=", null)->first();

        return $this->sendResponse();
    }

    public function getPaymentStatus()
    {
        $this->model = PaymentStatus::select(["id","value","description"])->where("deleted_at","=",null)->get();
        return $this->sendResponse();
    }
}
