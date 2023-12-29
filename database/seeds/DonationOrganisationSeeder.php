<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DonationOrganisationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data = [];
        $data[] = ['title'=>'IFCA - The Indian Federation of Culinary Associations','image_url'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/donation-organisations/ifca.png','sort_order'=>1,'slug'=>'ifca', 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()];

        $data[] = ['title'=>'Akshay Patra Foundation','image_url'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/donation-organisations/akshay_patra.png','sort_order'=>2, 'slug'=>'akshay_patra','created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()];
        
        $data[] = ['title'=>'CRY - Child Rights and You','image_url'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/donation-organisations/cry.png','sort_order'=>3, 'slug'=>'cry','created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()];

        \DB::table('donation_organisations')->insert($data);
    }
}
