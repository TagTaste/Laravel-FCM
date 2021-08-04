<?php

namespace App\Http\Controllers\Api\Payment;

use App\Collaborate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Payment\PaymentLinks as PaymentLinks;
use App\Payment\PaymentStatus;
use App\Payment\PaymentReport;
use App\Product;
use App\PublicReviewProduct;
use App\Surveys;
use App\Traits\PaymentTransaction;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tagtaste\Api\SendsJsonResponse;



class PaymentController extends Controller
{
    use SendsJsonResponse, PaymentTransaction;

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

        //Filters here for q=total and all statuses
        if ($request->has("status") && !empty($request->status)) {
            $getStatusId = config("constant.PAYMENT_STATUS");
            if (isset($getStatusId[$request->status])) {
                $getData->where("status_id", $getStatusId[$request->status]);
            }
        }


        $this->model['count'] = $getData->count();

        $getData->join("payment_status", "payment_status.id", "=", "payment_links.status_id");
        $details = $getData->skip($skip)->take($take)->select(DB::raw("payment_links.id,transaction_id,model_type as model,amount,payment_links.created_at,payment_links.updated_at,  JSON_OBJECT
        (
          'id', payment_status.id, 
          'value', payment_status.value,
          'text_color', payment_status.text_color
        ) as status"))->get();

        
        foreach ($details as $value) {
            $js = json_decode($value->status);
            $value->status = $js;
        }
        // print_r
        $this->model["payments"] = $details;

        return $this->sendResponse();
    }

    public function getTxnsById($txn_id, Request $request)
    {
        $this->model = [];
        $this->getStatus($txn_id);
        $getData = DB::table("payment_links")->where("profile_id", $request->user()->profile->id)->where("transaction_id", $txn_id)->join("payment_status", "payment_status.id", "=", "payment_links.status_id")->select(DB::raw("payment_links.id,transaction_id,model_id,sub_model_id,model_type as model,link,amount,payment_links.created_at,payment_links.updated_at,  JSON_OBJECT
        (
          'id', payment_status.id, 
          'value', payment_status.value,
          'text_color', payment_status.text_color
        ) as status"))->get();


        $data = [];
        foreach ($getData as $v) {
            $data = $v;
            $title = "";
            if ($v->model == "Survey") {
                $getTitile = Surveys::where("id", $v->model_id)->select("title")->first();
                $title = (isset($getTitile->title) ? $getTitile->title : "");
            } else if ($v->model == "Private Review") {
                $getTitile = Collaborate::where("id", $v->model_id)->select("title")->first();
                $title = (isset($getTitile->title) ? $getTitile->title : "");
            } else if ($v->model == "Public Review") {
                $getTitile = PublicReviewProduct::where("id", $v->model_id)->select("name")->first();
                $title = (isset($getTitile->name) ? $getTitile->name : "");
            }
            $data->title = $title;
            $js = json_decode($v->status);
            $v->status = $js;
        }
        
        // print_r
        // if (!empty($data)) {
            // $data->pop_up = [
            //     "title" => "Earing",
            //     "sub_title" => "Claim your earning",
            //     "icon" => "static3.tagtaste.com/images/pending.png",
            //     "amount" => $data->amount
            // ];
        // }
                
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
        $this->model = [["key" => "total", "title" => "Total Transaction"], 
        ["key" => "2", "title" => "Pending Transactions"], 
        ["key" => "3", "title" => "Redeemed Transactions"], 
        ["key" => "6", "title" => "Expired Transactions"],
        ["key" => "5", "title" => "Cancelled Transactions"],
        ["key" => "4", "title" => "FAILED Transactions"]];
        return $this->sendResponse();
    }

    public function paymentOverview(Request $request)
    {
        //total - All - cancelled
        //to be reedemed = total - (cancelled + success)
        $earning = PaymentLinks::where(function ($q) {
            $q->orWhere("status_id", config("constant.PAYMENT_SUCCESS_STATUS_ID"));
            $q->orWhere("status_id", config("constant.PAYMENT_PENDING_STATUS_ID"));
        })->where("profile_id", $request->user()->profile->id)->select(DB::raw("SUM(amount) as total_earnings"))->first();

        $pending = PaymentLinks::where("status_id", config("constant.PAYMENT_PENDING_STATUS_ID"))->where("profile_id", $request->user()->profile->id)->select(DB::raw("SUM(amount) as pending"))->first();

        $redeemed = PaymentLinks::where("status_id", config("constant.PAYMENT_SUCCESS_STATUS_ID"))->where("profile_id", $request->user()->profile->id)->select(DB::raw("SUM(amount) as redeemed"))->first();

        $this->model = [
            [
                "title" => "Total Earning", "value" => (!empty($earning->total_earnings) ? $earning->total_earnings : 0),
                "color_code" => "#dbbdba", "text_color" => "#000000", "border_color" => "#f56262",
                "icon" => "https://s3.ap-south-1.amazonaws.com/static4.tagtaste.com/test/reddemed_card.png", "is_main" => true
            ],
            [
                "title" => "To be reedemed", "value" => (!empty($pending->pending) ? $pending->pending : 0),
                "color_code" => "#bbdba9", "text_color" => "#000000", "border_color" => "#97ed66",
                "icon" => "https://s3.ap-south-1.amazonaws.com/static4.tagtaste.com/test/reddemed_card.png"
            ],
            [
                "title" => "Reedemed", "value" => (!empty($redeemed->redeemed) ? $redeemed->redeemed : 0),
                "color_code" => "#cec5e3", "text_color" => "#000000", "border_color" => "#b199e8",
                "icon" => "https://s3.ap-south-1.amazonaws.com/static4.tagtaste.com/test/reddemed_card.png"
            ]
        ];

        return $this->sendResponse();
    }

    public function verifyPassword(Request $request)
    {
        $get = User::where("id", $request->user()->id)->select("password")->first();
        if (Hash::check($request->password, $get->password)) {

            return response(["data" => true, "errors" => "", "messages" => "Successfull"], 200);
        }
        return response(["data" => false, "errors" => "Invalid Password", "messages" => "Please enter correct password."], 200);
    }

    public function getTasterProgram(Request $request)
    {
        $headers = [
            [
                "title" => "Benifits of Paid Taster",
                "child" => [
                    ["title" => "You will get Priority to review our paid reviews and earn money."],
                    ["title" => "After reviewing 50 paid reviews you will achieve a badge of expert."],
                    ["title" => "You will get notified for our sensory sessions."]
                ]
            ],
            [
                "title" => "Eligibility Criteria for paid taster",
                "child" => [
                    ["title" => "User Should have attended the TagTaste Sensory Workshop - in person or virtual."],
                    ["title" => "Should have completed a minimum of 5 reviews on the TagTaste (Preferably different categories)."]
                ]
            ]
        ];

        $pop_up = ["title" => "Uh-oh!", "sub_title" => "Not a paid taster", "icon" => "https://s3.ap-south-1.amazonaws.com/static4.tagtaste.com/test/modela_image.png"];

        $data = [
            "pop_up" => $pop_up,
            "title" => "DON'T WORRY",
            "sub_title" => "We have introduced a program for you to certified as a paid taster. Get enroll yourself.",
            "headers" => $headers
        ];

        $this->model = $data;
        return $this->sendResponse();
    }

    public function getReviewConditions(Request $request, $model, $modelId, $subModelId = null)
    {
        //Check for module and submodule for active payment on this.
        //Then send rules according to this. 

        $pop_up = [];
        $title = "";
        if ($model == "collaborate") {
            $pop_up = ["title" => "Paid collboration", "icon" => "https://s3.ap-south-1.amazonaws.com/static4.tagtaste.com/test/modela_image.png"];
            $title = "Fill review carefully, correct data will lead you to earn money";
        } else if ($model == "survey") {
            $pop_up = ["title" => "Paid survey", "icon" => "https://s3.ap-south-1.amazonaws.com/static4.tagtaste.com/test/modela_image.png"];
            $title = "Fill survey carefully, correct data will lead you to earn money";
        } else if ($model == "product") {
            $pop_up = ["title" => "Paid product", "icon" => "https://s3.ap-south-1.amazonaws.com/static4.tagtaste.com/test/modela_image.png"];
            $title = "Fill review carefully, correct data will lead you to earn money";
        } else {
            $this->model = "This model is not allowed";
            return $this->sendResponse();
        }

        $headers = [
            [
                "title" => "Get paid rules",
                "child" => [
                    ["title" => "First come firts earn."],
                    ["title" => "First 150 people get paid T&C apply."]
                ]
            ]
        ];

        $pop_up["sub_title"] = "You will get paid once you complete.";
        $data = ["title" => $title, "pop_up" => $pop_up, "headers" => $headers];
        $this->model = $data;
        return $this->sendResponse();
    }

    public function transactionComplain(Request $request, $txn_id)
    {
        $title = $request->title;
        if (empty($title)) {
            return $this->sendError("Title is mandatory.");
        }
        $description = !empty($request->description) ? $request->description : NULL;

        $profileId = $request->user()->profile->id;
        PaymentReport::insert(['transaction_id' => $txn_id, 'profile_id' => $profileId, 'title' => $title, 'description' => $description]);
        $this->model = true;
        return $this->sendResponse();
    }

    public function enrollTasterProgram(Request $request)
    {
        //Send email to payment@tagtaste.com
        //Keep user email in copy 
        //Take mail template from tanvi or arun sir
        $data = ["status" => true, "title" => "Success", "sub_title" => "You have enrolled successfully. We will keep you posted for further updates."];
        return $this->sendResponse($data);
    }

    public function paymentCallback(Request $request)
    {
       return $this->callback($request);
    }
}
