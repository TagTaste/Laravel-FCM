<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use stdClass;

use function GuzzleHttp\json_decode;

class MigrateProductBrands implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $brandList = \DB::table("public_review_products")->select('brand_name','brand_logo','brand_description')->whereNotNull('brand_name')->get()->groupBy('brand_name');
        $brandListToMigrate = [];
        foreach($brandList as $element){
            $brandListToMigrate[] = ['name'=>$element[0]->brand_name, 'image'=> $element[0]->brand_logo, 'description'=>$element[0]->brand_description, 'is_active'=>1, 'created_at'=>Carbon::now()];
        };
        
        //insert into brand table
        \DB::table("public_review_product_brands")->insert($brandListToMigrate);

        //update product table for brand_id
        $updatedBrands = \DB::table('public_review_product_brands')->get();
        foreach($updatedBrands as $element){
            \DB::table('public_review_products')->where('brand_name',$element->name)->update(['brand_id'=>$element->id]);
        };
        
        return true;
    }
}
