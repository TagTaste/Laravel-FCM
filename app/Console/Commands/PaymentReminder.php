<?php

namespace App\Console\Commands;

use App\Collaborate;
use App\Deeplink;
use App\PublicReviewProduct;
use App\Surveys;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PaymentReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:PaymentLink';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $payments = Db::table('profiles')
            ->select('payment_links.status_json', 'users.email', 'users.name', 'payment_links.model_id','payment_links.transaction_id','payment_links.link',"payment_links.amount","payment_links.model_type")
            ->join("payment_links", 'payment_links.profile_id', 'profiles.id')
            ->join("users", 'users.id', 'profiles.user_id')
            ->where('payment_links.status_id', config("constant.PAYMENT_STATUS.pending"))
            ->whereDate('payment_links.expired_at', '>', date('Y-m-d'))
            ->whereRaw('DATEDIFF(payment_links.expired_at,Now())=' . config("constant.PAYMENT_REMINDER_BEFORE_DAYS"))
            ->where("payment_links.link","<>",null)
            ->get();


        foreach ($payments as $payment) {
            $link = json_decode($payment->status_json);
            if (isset($link->result->payoutLink)) {
                if ($payment->model_type == "Private Review") {
                    $getName = DB::table("collaborates")->where("id", $payment->model_id)->first();
                    $name = $getName->title ?? "";
                    $hyperlink = '<a style="
                    font-size: 14px;
                    font-weight: normal;
                    color: #4990e2;
                    margin: 0px;
                    line-height: 1.5;" href="' . Deeplink::getShortLink('collaborate', $payment->model_id) . '">' . $name . '</a>';
                    $type = "Collaboration";
                } else if ($payment->model_type == "Public Review") {
                    $getName = PublicReviewProduct::where("id", $payment->model_id)->first();
                    $name = $getName->name ?? "";
                    $hyperlink = '<a style="
                    font-size: 14px;
                    font-weight: normal;
                    color: #4990e2;
                    margin: 0px;
                    line-height: 1.5;"  href="' . Deeplink::getShortLink('product', $payment->model_id) . '">' . $name . '</a>';
                    $type = "Product Review";
                } else if ($payment->model_type == "Survey") {
                    $getName = Surveys::where("id", $payment->model_id)->first();
                    $name = $getName->title ?? "";
                    $hyperlink = '<a style="
                    font-size: 14px;
                    font-weight: normal;
                    color: #4990e2;
                    margin: 0px;
                    line-height: 1.5;"  href="' . Deeplink::getShortLink('surveys', $payment->model_id) . '">' . $name . '</a>';
                    $type = "Survey";
                }
                $str = [
                    "Name" => $payment->name,
                    "Link" =>  $link->result->payoutLink,
                    "expiry_time" => $link->result->expiryDate,
                    "type" => ($type ?? ""),
                    "hyperlink" => ($hyperlink ?? ""),
                    "Model" => $payment->model_id,
                    "Order" => $payment->transaction_id,
                    "Email" => $payment->email,
                    "amount" => $payment->amount
                ];
                $d = ["subject" => "Reminder for Payment Redemption - Payment Link expires in " . config("constant.PAYMENT_REMINDER_BEFORE_DAYS") . " Days", "content" => $str];
                Mail::send("emails.payment-reminder", ["data" => $d], function ($message) use ($str) {
                    $message->to($str['Email'], 'TagTaste')->subject(((config("app.env") != "production") ? 'TEST - ' : '') . 'Payment Link for order #' . $str['Order']);
                });
            }
        }
    }
}
