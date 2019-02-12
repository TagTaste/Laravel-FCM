<?php

namespace App\Jobs;

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
    public $productDetails;

    public function __construct($productDetails)
    {
        $this->productDetails = $productDetails;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = ['product_name'=>$this->productDetails->product_name,'product_link'=>$this->productDetails->product_link,
            'brand_name'=>$this->productDetails->brand_name,'profile_id'=>$this->productDetails->profile_id];
        \Mail::send('emails.productSuggestion',$data , function($message)
        {
            $message->to('newproducts@tagtaste.com', 'Product Suggestion')->subject('New Products Suggest!');
        });
    }
}
