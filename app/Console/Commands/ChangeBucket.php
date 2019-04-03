<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ChangeBucket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'change:bucket';

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
        $products = \App\PublicReviewProduct::all();
        foreach($products as $product){
            if(strpos(json_encode($product->brand_logo), 'static4') !== false){
                $string = str_replace("static4","static3",json_encode($product->brand_logo));
                \App\PublicReviewProduct::where('id', $product->id)->update(['brand_logo'=>$string]);
            }
            if(strpos(json_encode($product->company_logo), 'static4') !== false){
                $string = str_replace("static4","static3",json_encode($product->company_logo));
                \App\PublicReviewProduct::where('id', $product->id)->update(['company_logo'=>$string]);
            }
            if(strpos(json_encode($product->images_meta), 'static4') !== false){
                $string = str_replace("static4","static3",json_encode($product->images_meta));
                \App\PublicReviewProduct::where('id', $product->id)->update(['images_meta'=>$string]);
            }
        }
    }
}
