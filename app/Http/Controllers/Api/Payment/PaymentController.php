<?php

namespace App\Http\Controllers\Api\Payment;

use App\Collaborate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Payment\PaymentLinks as PaymentLinks;
use App\Payment\PaymentStatus;
use App\Product;
use App\Surveys;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
        $this->model = [];
        $page = $request->input('page');
        list($skip, $take) = \App\Strategies\Paginator::paginate($page);

        $getData = DB::table("payment_links")->where("profile_id", $request->user()->profile->id);

        if ($request->has("transaction_id") && !empty($request->transaction_id)) {
            $getData->where("transaction_id", $request->transaction_id);
        }

        if ($request->has("phone") && !empty($request->phone)) {
            $getData->where("phone", $request->phone);
        }

        if ($request->has("txn_id") && !empty($request->txn_id)) {
            $getData->where("transaction_id", $request->txn_id);
        }

        $this->model['count'] = $getData->count();

        $getData->join("payment_status", "payment_status.id", "=", "payment_links.status_id");
        $details = $getData->skip($skip)->take($take)->select(DB::raw("payment_links.id,transaction_id,model_type as model,amount,payment_links.created_at,payment_links.updated_at,  JSON_OBJECT
        (
          'id', payment_status.id, 
          'value', payment_status.value
        ) as status"))->get();


        // print_r
        $this->model["payments"] = $details;

        return $this->sendResponse();
    }

    public function getTxnsById($txn_id, Request $request)
    {
        $this->model = [];

        $getData = DB::table("payment_links")->where("profile_id", $request->user()->profile->id)->where("transaction_id", $txn_id)->join("payment_status", "payment_status.id", "=", "payment_links.status_id")->select(DB::raw("payment_links.id,transaction_id,model_id,sub_model_id,model_type as model,amount,payment_links.created_at,payment_links.updated_at,  JSON_OBJECT
        (
          'id', payment_status.id, 
          'value', payment_status.value
        ) as status"))->get();


        $data = [];
        foreach ($getData as $v) {
            $data = $v;

            if ($v->model == "survey") {
                $getTitile = Surveys::where("id", $v->model_id)->select("title")->first();
            } else if ($v->model == "collaboration") {
                $getTitile = Collaborate::where("id", $v->model_id)->select("title")->first();
            } else if ($v->model == "product") {
                $getTitile = Product::where("id", $v->model_id)->select("title")->first();
            }
            $data->title = $getTitile->title;
        }

        // print_r
        if (!empty($data)) {
            $data->pop_up = [
                "title" => "Earing",
                "sub_title" => "Claim your earning",
                "icon" => "static3.tagtaste.com/images/pending.png",
                "amount" => $data->amount
            ];
        }
        $this->model = $data;

        return $this->sendResponse();
    }

    public function getPaymentStatus()
    {
        $this->model = PaymentStatus::select(["id", "value", "description"])->where("deleted_at", "=", null)->get();
        return $this->sendResponse();
    }

    public function getFilters()
    {
        $this->model = [["key" => "total", "title" => "Total Transaction"], ["key" => "pending", "title" => "Pending Transactions"], ["key" => "redeemed", "title" => "Redeemed Transactions"], ["key" => "expired", "title" => "Expired Transactions"]];
        return $this->sendResponse();
    }

    public function paymentOverview(Request $request)
    {
        $earning = PaymentLinks::where(function ($q) {
            $q->orWhere("status_id", config("constant.PAYMENT_SUCCESS_STATUS_ID"));
            $q->orWhere("status_id", config("constant.PAYMENT_PENDING_STATUS_ID"));
        })->where("profile_id", $request->user()->profile->id)->select(DB::raw("SUM(amount) as total_earnings"))->first();

        $pending = PaymentLinks::where("status_id", config("constant.PAYMENT_PENDING_STATUS_ID"))->where("profile_id", $request->user()->profile->id)->select(DB::raw("SUM(amount) as pending"))->first();

        $redeemed = PaymentLinks::where("status_id", config("constant.PAYMENT_SUCCESS_STATUS_ID"))->where("profile_id", $request->user()->profile->id)->select(DB::raw("SUM(amount) as redeemed"))->first();


        $this->model = [
            ["title" => "Total Earning", "value" => (!empty($earning->total_earnings) ? $earning->total_earnings : 0), "color_code" => "#3f3f3f", "icon" => "https://static3.tagtaste.com/images/earning.png", "is_main" => true],
            ["title" => "To be reedemed", "value" => (!empty($pending->pending) ? $pending->pending : 0), "color_code" => "#3f3f3f", "icon" => "https://static3.tagtaste.com/images/pending.png"],
            ["title" => "Reedemed", "value" => (!empty($redeemed->redeemed) ? $redeemed->redeemed : 0), "color_code" => "#3f3f3f", "icon" => "https://static3.tagtaste.com/images/redeemed.png"]
        ];

        return $this->sendResponse();
    }
    public function verifyPassword(Request $request)
    {
        $get = User::where("id", $request->user()->id)->select("password")->first();
        if (Hash::check($request->password, $get->password)) {
             
            return response(["data"=>true,"errors"=>[],"messages"=>"Request Successfull"],200);
        }
        return response(["data"=>false,"errors"=>["Invalid Password"],"messages"=>"Request Failed"],400);
    }
}
