<?php

use Illuminate\Database\Seeder;

class companyNewStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Company\Status::where('id',1)->update(['name'=>'Operational']);
        \App\Company\Status::where('id',2)->update(['name'=>'Operational Subsidiary']);
        \App\Company\Status::where('id',3)->update(['name'=>'Out of Business']);
        \App\Company\Status::where('id',4)->update(['name'=>'Acquired']);
        \App\Company\Status::where('id',5)->update(['name'=>'Others']);
    }
}
