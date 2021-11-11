<?php

namespace App\Console\Commands;

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
        ->select('payment_links.status_json','users.email','users.name','payment_links.model_id')
        ->join("payment_links",'payment_links.profile_id','profiles.id')
        ->join("users",'users.id','profiles.user_id')
        ->where('payment_links.status_id',2)
        ->whereDate('payment_links.expired_at','>',date('Y-m-d'))
        ->whereRaw('DATEDIFF(payment_links.expired_at,Now())=2')
        ->get()->toArray();


        $data = ["status" => true];
        foreach($payments as $payment)
        {
            $link = json_decode($payment->status_json);
            if(isset($link->result->payoutLink)){
            $str = [
            "Name" => $payment->name,
            "Link" =>  $link->result->payoutLink,
            "Model" => $payment->model_id,
            "Order" => $link->result->orderId,
            "Email" => $payment->email
        ];
        $d = ["subject" => "Please make your payment before the link expires!", "content" => $str];
        Mail::send("emails.payment-reminder", ["data" => $d], function ($message) use ($str) {
            $message->to($str['Email'], 'TagTaste')->subject(((config("app.env")!= "production") ? 'TEST - ' : '').'Payment Link for order #'.$str['Order']);
        });

           }
       
       }
    }
}
