<?php

namespace App\Http\Controllers\Api\Payment;

use App\Collaborate;
use App\Deeplink;
use App\Events\Actions\PaymentComplain;
use App\Events\Actions\SensoryEnroll;
use App\Events\Actions\TasterEnroll;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Payment\PaymentLinks as PaymentLinks;
use App\Payment\PaymentStatus;
use App\Payment\PaymentDetails;
use App\Payment\PaymentReport;
use App\Product;
use App\Profile;
use App\PublicReviewProduct;
use App\Surveys;
use App\Traits\PaymentTransaction;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
        $getStatusId = config("constant.PAYMENT_STATUS");
        if ($request->has('q') && !empty($request->q) && $request->q != "total") {
            $getData->where("status_id", $request->q);
        }
        //Filters here for q=total and all statuses
        if ($request->has("status") && !empty($request->status)) {
            if (isset($getStatusId[$request->status])) {
                $getData->where("status_id", $getStatusId[$request->status]);
            }
        }


        $this->model['count'] = $getData->count();

        $getData->join("payment_status", "payment_status.id", "=", "payment_links.status_id");
        $details = $getData->skip($skip)->take($take)->select(DB::raw("payment_links.id,payment_links.model_id,transaction_id,model_type as model,amount,payment_links.created_at,payment_links.updated_at,  JSON_OBJECT
        (
          'id', payment_status.id, 
          'value', payment_status.value,
          'text_color', payment_status.text_color
        ) as status"))->orderBy("payment_links.created_at", "desc")->get();


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
                $deeplink = Deeplink::getShortLink('surveys', $v->model_id);
            } else if ($v->model == "Private Review") {
                $getTitile = Collaborate::where("id", $v->model_id)->select("title")->first();
                $title = (isset($getTitile->title) ? $getTitile->title : "");
                $deeplink = Deeplink::getShortLink('collaborate', $v->model_id);
            } else if ($v->model == "Public Review") {
                $getTitile = PublicReviewProduct::where("id", $v->model_id)->select("name")->first();
                $title = (isset($getTitile->name) ? $getTitile->name : "");
                $deeplink = Deeplink::getShortLink('product', $v->model_id);
            }
            $data->title = $title;
            $v->deeplink = $deeplink;
            $js = json_decode($v->status);
            $v->status = $js;
        }

        if (!empty($data)) {
            $title = '';
            $sub_title = '';
            $icon = '';
            if ($data->status->id == config("constant.PAYMENT_INITIATED_STATUS_ID")) {
                $title = 'Initiated';
                $sub_title = 'Your transaction has initiated';
                $icon = 'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/Payment/Static/Transaction-Detail/initiated.png';
            } else if ($data->status->id == config("constant.PAYMENT_PENDING_STATUS_ID")) {
                $title = 'To be Redeemed';
                $sub_title = 'Claim your earning';
                $icon = 'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/Payment/Static/Transaction-Detail/pending.png';
            } else if ($data->status->id == config("constant.PAYMENT_SUCCESS_STATUS_ID")) {
                $title = 'Redeemed';
                $sub_title = 'Money successfully transferred to your account';
                $icon = 'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/Payment/Static/Transaction-Detail/redeemed.png';
            } else if ($data->status->id == config("constant.PAYMENT_FAILURE_STATUS_ID")) {
                $title = 'Failed';
                $sub_title = 'Your transaction has failed';
                $icon = 'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/Payment/Static/Transaction-Detail/failed.png';
            } else if ($data->status->id == config("constant.PAYMENT_CANCELLED_STATUS_ID")) {
                $title = 'Cancelled';
                $sub_title = 'Your transaction has cancelled';
                $icon = 'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/Payment/Static/Transaction-Detail/cancelled.png';
            } else if ($data->status->id == config("constant.PAYMENT_EXPIRED_STATUS_ID")) {
                $title = 'Expired';
                $sub_title = 'Your transaction has cancelled';
                $icon = 'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/Payment/Static/Transaction-Detail/expired.png';
            }

            $data->pop_up = [
                "title" => $title,
                "sub_title" => $sub_title,
                "icon" => $icon,
                "amount" => "₹" . $data->amount
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
        $this->model = [
            ["key" => "total", "title" => "Total Transactions"],
            ["key" => "1", "title" => "Initiated Transactions"],
            ["key" => "2", "title" => "Pending Transactions"],
            ["key" => "3", "title" => "Redeemed Transactions"],
            ["key" => "6", "title" => "Expired Transactions"],
            ["key" => "5", "title" => "Cancelled Transactions"],
            ["key" => "4", "title" => "Failed Transactions"]
        ];
        return $this->sendResponse();
    }

    public function paymentOverview(Request $request)
    {
        //total - All - cancelled
        //to be redeemed = total - (cancelled + success)
        $totalEarning = PaymentLinks::where("status_id", "<>", config("constant.PAYMENT_CANCELLED_STATUS_ID"))->where("profile_id", $request->user()->profile->id)->select(DB::raw("SUM(amount) as amount"))->first();
        $redeemed = PaymentLinks::where("status_id", config("constant.PAYMENT_SUCCESS_STATUS_ID"))->where("profile_id", $request->user()->profile->id)->select(DB::raw("SUM(amount) as amount"))->first();
        $pending = PaymentLinks::where("status_id", config("constant.PAYMENT_PENDING_STATUS_ID"))->where("profile_id", $request->user()->profile->id)->select(DB::raw("SUM(amount) as amount"))->first();
        $cancelled = PaymentLinks::where("status_id", config("constant.PAYMENT_CANCELLED_STATUS_ID"))->where("profile_id", $request->user()->profile->id)->select(DB::raw("SUM(amount) as amount"))->first();
        $failure = PaymentLinks::where("status_id", config("constant.PAYMENT_FAILURE_STATUS_ID"))->where("profile_id", $request->user()->profile->id)->select(DB::raw("SUM(amount) as amount"))->first();
        $expired = PaymentLinks::where("status_id", config("constant.PAYMENT_EXPIRED_STATUS_ID"))->where("profile_id", $request->user()->profile->id)->select(DB::raw("SUM(amount) as amount"))->first();
        $initiated = PaymentLinks::where("status_id", config("constant.PAYMENT_INITIATED_STATUS_ID"))->where("profile_id", $request->user()->profile->id)->select(DB::raw("SUM(amount) as amount"))->first();

        $totalEarning = number_format(($totalEarning->amount ?? 0), 2, '.', "");
        $toBeRedeemed = number_format((($pending->amount ?? 0) + ($initiated->amount ?? 0)), 2, '.', "");
        $redeemed = number_format(($redeemed->amount ?? 0), 2, '.', "");
        $pending = number_format(($pending->amount ?? 0), 2, '.', "");
        $cancelled = number_format(($cancelled->amount ?? 0), 2, '.', "");
        $failure = number_format(($failure->amount ?? 0), 2, '.', "");
        $expired = number_format(($expired->amount ?? 0), 2, '.', "");
        $initiated = number_format(($initiated->amount ?? 0), 2, '.', "");

        $this->model = [
            [
                "title" => "Total Earning", "value" => "₹" . $totalEarning,
                "color_code" => "#FFFFFF", "text_color" => "#171717", "border_color" => "#f56262", "value_color" => "#DD2E1F",
                "icon" => "", "is_main" => true
            ],
            [
                "title" => "Earning Redeemed", "value" => "₹" . $redeemed,
                "color_code" => "#E5F5EC", "text_color" => "#171717", "border_color" => "#CCECDA", "value_color" => "#00A146",
                "icon" => "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/Payment/Static/Passbook/redeemed.png"
            ],
            [
                "title" => "To be Redeemed", "value" => "₹" . $toBeRedeemed,
                "color_code" => "#FDF1E7", "text_color" => "#171717", "border_color" => "#FDE4D0", "value_color" => "#F47816",
                "icon" => "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/Payment/Static/Passbook/toberedeemed.png"
            ],
            [
                "title" => "Earning Expired", "value" => "₹" . $expired,
                "color_code" => "#FBEAE8", "text_color" => "#DD2E1F", "border_color" => "#F8D5D2", "value_color" => "#171717",
                "icon" => "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/Payment/Static/Passbook/expired.png"
            ],
            // [
            //     "title" => "Cancelled TXN", "value" => "₹".$cancelled,
            //     "color_code" => "#E5E5E5", "text_color" => "#171717", "border_color" => "#CCCCCC","value_color"=>"#171717",
            //     "icon" => "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/Payment/Static/Passbook/cancelled.png"
            // ],
            [
                "title" => "Failed TXN", "value" => "₹" . $failure,
                "color_code" => "#FCF1D2", "text_color" => "#171717", "border_color" => "#EFB920", "value_color" => "#171717",
                "icon" => "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/Payment/Static/Passbook/failed.png"
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
        $expertButton = [
            "title" => "Enroll as an expert", "color_code" => "#efb920", "text_color" => "#000000",
            "url" => "payment/expert/enroll", "method" => "POST"
        ];

        $sensoryButton = [
            "title" => "Enroll for sensory workshop", "color_code" => "#4990e2", "text_color" => "#ffffff",
            "url" => "payment/sensory/enroll", "method" => "POST"
        ];

        $headers = [
            [
                "title" => "PROGRAM BENEFITS",
                "list_type" => 1, //With bullet
                "child" => [
                    ["title" => "Earn anywhere between Rs. 100 to Rs. 20,000 in a single assignment."],
                    ["title" => "Get to taste the products before they hit the market."],
                    ["title" => "Build your knowledge and network in the food industry."],
                    ["title" => "Get eligible for exclusive assignments from food startups and MNCs."]
                ]
            ],
            [
                "title" => "HOW TO FIND PAID TASTINGS?",
                "list_type" => 2, // Without bullet
                "child" => [
                    ["title" => "Regularly visit the collaboration section of our website and apps for all paid tasting assignments."]
                ]
            ],
            [
                "title" => "NOTE",
                "list_type" => 1, // With bullet
                "child" => [
                    [
                        "title" => "Expert tasters: Trained food professionals such as chefs, nutritionists, food technologists, etc. are eligible for product improvement and related assignments beyond product reviews. Click on the following button to initiate the process of registering yourself as an expert.",
                        "button_assets" => $expertButton
                    ],
                    [
                        "title" => "Sensory workshop: We conduct online and offline workshops for our community members from time to time. We've trained over 7000 tasters from 91 cities across India. Sensory workshop is mandatory for anyone (including experts) who wishes to become a paid taster. Click on the following button to register for upcoming workshops.",
                        "button_assets" => $sensoryButton
                    ],
                    ["title" => "Review 3 products: Click on the 'Review' link in the header (website) or the 'Star' icon in the bottom bar (Android/ iOS apps) to go to the list of all products available on TagTaste. Review any 3 products from this list."],
                    ["title" => "Read TagTaste terms & conditions for complete details."]
                ]
            ]
        ];

        // $pop_up = ["title" => "Uh-oh!", "sub_title" => "Not a paid taster", "icon" => "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/Payment/Static/Submit-Review/failed.png"];

        $data = [
            // "pop_up" => $pop_up,
            "title" => "",
            "sub_title" => "Do you know that you can earn by simply reviewing a product on TagTaste? Well, it's that simple. All you need to do is attend our sensory workshop and review 3 products on TagTaste to become eligible for paid tastings.",
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
        if ($model == "collaborate" && isset($subModelId)) {
            $pop_up = ["title" => "Paid Private Review", "icon" => "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/Payment/Static/Payment-Rules/private-review.png"];
            $title = "Read the questions carefully before answering. Remember, there are no right or wrong answers.";
            $paymentDetail = PaymentDetails::select("user_count")
                ->where("model_id", $modelId)
                ->where("sub_model_id", $subModelId)
                ->where("is_active", 1)
                ->get();
                $text = "review";
        } else if ($model == "survey") {
            $pop_up = ["title" => "Paid survey", "icon" => "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/Payment/Static/Payment-Rules/survey.png"];
            $title = "Read the questions carefully before answering. Remember, there are no right or wrong answers.";
            $paymentDetail = PaymentDetails::select("user_count")
                ->where("model_id", $modelId)
                ->where("is_active", 1)
                ->get();
                $text = "survey";
        } else if ($model == "product") {
            $pop_up = ["title" => "Paid Public Review", "icon" => "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/Payment/Static/Payment-Rules/public-review.png"];
            $title = "Read the questions carefully before answering. Remember, there are no right or wrong answers.";
            $paymentDetail = PaymentDetails::select("user_count")
                ->where("model_id", $modelId)
                ->where("is_active", 1)
                ->get();
                $text = "review";
        } else {
            return $this->sendError("Invalid request. Please check your request.");
        }

        if ($paymentDetail->count() == 0 ||  $paymentDetail == null || !isset($paymentDetail)) {
            return $this->sendError("This is not a paid model.");
        }
        // $userCount = $paymentDetail[0]["user_count"] ?? 0;
        // $headers = [
        //     [
        //         "title" => "Read the question carefully before answering. Remember there is no right or wrong answer.",
        //         "child" => []
        //     ]
        // ];

        $pop_up["sub_title"] = "You will get paid once you complete the ".$text;
        $data = ["title" => $title, "pop_up" => $pop_up];
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
        // \Mail::send('emails.payment-complain', ['transaction_id' => $txn_id, 'title' => $title, 'description' => $description], function($message) use($request,$txn_id)
        // {
        //     $message->to($request->user()->email, $request->user()->name)->subject('Transaction Complaint regarding '.$txn_id);
        // }    );
        $links = PaymentLinks::where("transaction_id", $txn_id)->first();


        $getOldComplaintId = PaymentReport::where("complaint_id", "LIKE", '%' . date('dmy') . "%")->orderBy("id", "desc")->select("complaint_id")->first();

        $number = 0;
        if (!empty($getOldComplaintId) && isset($getOldComplaintId->complaint_id)) {
            $explode = explode("_", $getOldComplaintId->complaint_id);

            $number = (int)array_pop($explode);
        }

        $buildComplaintId = date('dmy') . "_" . ++$number;

        PaymentReport::insert(['transaction_id' => $txn_id, 'profile_id' => $profileId, 'title' => $title, 'description' => $description, "complaint_id" => $buildComplaintId]);
        event(new PaymentComplain($links, null, ['transaction_id' => $txn_id, 'title' => $title, 'description' => $description, "complaint_id" => $buildComplaintId,"name" => $request->user()->name]));

        $link = "";
        if ($links->model_type == "Public Review") {
            $link = config("app.url") . "/collaborations/" . $links->model_id . "/product-review";
        } else if ($links->model_type == "Private Review") {
            $link = config("app.url") . "/reviews/products/" . $links->model_id;
        } else if ($links->model_type == "Survey") {
            $link = config("app.url") . "/surveys/" . $links->model_id;
        }
        $str = [
            "Name" => $request->user()->name,
            "Email Address" => $request->user()->email,
            "Contact No" => $request->user()->profile->phone,
            "Collaboration/Product Link" => $link,
            "Ticket/Complaint ID:" => $buildComplaintId,
            "Complaint" => $title,
            "Complaint Description" => $description
        ];
        $d = ["subject" => "You’ve received a new complaint regarding " . $links->model_type . " Payments", "content" => $str];
        Mail::send("emails.payment-staff-common", ["data" => $d], function ($message) {
            $message->to('payment@tagtaste.com', 'Tech Team')->subject(((config("app.env")!= "production") ? 'TEST - ' : '').'New Complain regarding Payments');
        });

        $this->model = true;
        return $this->sendResponse();
    }

    public function enrollSensoryProgram(Request $request)
    {
        //Send email to payment@tagtaste.com
        //Keep user email in copy 
        //Take mail template from tanvi or arun sir
        $data = ["status" => true, "title" => "", "sub_title" => "Your enrollment has been successfull. Our team will reach out to you with further details."];

        $str = [
            "Name" => $request->user()->name,
            "Email Address" => $request->user()->email,
            "Contact No" => $request->user()->profile->phone,
            "Onboarding Date" => date("Y-m-d")
        ];
        $d = ["subject" => "You’ve received a new registration for Sensory Workshop", "content" => $str];
        // Mail::send("emails.payment-staff-common", ["data" => $d], function ($message) {
        //     $message->to('workshop@tagtaste.com', 'Tech Team')->subject(((config("app.env")!= "production") ? 'TEST - ' : '').'New Registration for Sensory Workshop');
        // });
        $links = Profile::where("id", $request->user()->profile->id)->first();
        event(new SensoryEnroll($links, null, ["name" => $request->user()->name]));

        return $this->sendResponse($data);
    }

    public function enrollExpertProgram(Request $request)
    {

        //Send email to payment@tagtaste.com
        //Keep user email in copy 
        //Take mail template from tanvi or arun sir
        $data = ["status" => true, "title" => "Success", "sub_title" => "Your enrollment has been successfull. Our team will reach out to you with further details."];
        $userData = Profile::where("profiles.id", $request->user()->profile->id)->leftJoin("profile_occupations", "profile_occupations.profile_id", "=", 'profiles.id')->leftJoin("occupations", "profile_occupations.occupation_id", "=", "occupations.id")
            ->leftJoin("profile_specializations", "profile_specializations.profile_id", "=", 'profiles.id')->leftJoin("specializations", "profile_specializations.specialization_id", "=", "specializations.id")->select(["specializations.name as specialization", "occupations.name as job"])->first();
        $str = [
            "Name" => $request->user()->name,
            "Email Address" => $request->user()->email,
            "Contact No" => $request->user()->profile->phone,
            "Job Profile" => ($userData->job ?? "N.A"),
            "Specialisations" => ($userData->specialization ?? "N.A")
        ];
        $d = ["subject" => "You’ve received a new registration for enrolment as an Expert", "content" => $str];
        Mail::send("emails.payment-staff-common", ["data" => $d], function ($message) {
            $message->to('workshop@tagtaste.com', 'Tech Team')->subject(((config("app.env")!= "production") ? 'TEST - ' : '').'New Registration for Expert');
        });

        $links = Profile::where("id", $request->user()->profile->id)->first();
        event(new TasterEnroll($links, null, ["name" => $request->user()->name]));

        return $this->sendResponse($data);
    }

    public function paymentCallback(Request $request)
    {
        return $this->callback($request);
    }
}
