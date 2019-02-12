<?php

namespace App\Jobs;

use App\Profile;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProductSuggestion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $productDeatils;

    public function __construct($productDeatils)
    {
        $this->productDeatils = $productDeatils;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Mail::send('emails.productSuggestion', ['productDeatils'=>$this->productDeatils], function($message)
        {
            // $path = "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/Taster's+Docket.pdf";
            $message->to('sarvada@tagtaste.com', "Product suggested is ". $this->productDetails->product_name.". Product Link is ".$this->productDeatils->product_link.". Image is ".$this->productDeatils->image)->subject('New Products Suggest!');
        });
    }
}
