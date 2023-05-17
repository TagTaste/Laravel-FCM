<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helper;

class AddReconciliationDatePaymentLinks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:addReconciliationDateToPaymentLinks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'to sync account reconciliation date in payment links table. It will update to 1 level of nesting only';

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
        $paymentLinks = \DB::table("payment_links")->whereNull("account_reconciliation_date")->get();
        
        foreach ($paymentLinks as $paymentLink) {            
            $reconciliationDate = null;
            if(isset($paymentLink->parent_transaction_id)){
                if (!str_contains($paymentLink->parent_transaction_id,"_RN_")) {
                    $parentPaymentLink = \DB::table("payment_links")->where("transaction_id", $paymentLink->parent_transaction_id)->first();
                    if(isset($paymentLink->parent_transaction_id)){
                        $reconciliationDate = $parentPaymentLink->created_at;
                    } 
                }
            }else{
                $reconciliationDate = $paymentLink->created_at;
            }
            if(isset($reconciliationDate)){
                \DB::table("payment_links")->where('transaction_id', $paymentLink->transaction_id)->update(["account_reconciliation_date"=> $reconciliationDate]);    
            }
        }  
    }     
}
