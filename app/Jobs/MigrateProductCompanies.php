<?php

namespace App\Jobs;

use Carbon\Carbon;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

class MigrateProductCompanies implements ShouldQueue
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
        //
        $companyList = \DB::table("public_review_products")->select('company_name','company_logo','company_description')->whereNotNull('company_name')->get()->groupBy('company_name');
        $companyListToMigrate = [];
        foreach($companyList as $element){
            $companyListToMigrate[] = ['name'=>$element[0]->company_name, 'image'=> $element[0]->company_logo, 'description'=>$element[0]->company_description, 'is_active'=>1, 'created_at'=>Carbon::now()];
        };
        
        //insert into company table
        \DB::table("public_review_product_companies")->insert($companyListToMigrate);

        //update product table for company_id
        $updatedCompanies = \DB::table('public_review_product_companies')->get();
        foreach($updatedCompanies as $element){
            \DB::table('public_review_products')->where('company_name',$element->name)->update(['company_id'=>$element->id]);
        };
        
        $data['data'] = $companyList;
        $data['count'] = $companyList->count();
        return $data;
    }
}
