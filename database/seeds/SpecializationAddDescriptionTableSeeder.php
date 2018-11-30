<?php

use Illuminate\Database\Seeder;

class SpecializationAddDescriptionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('specializations')->where('id',1)->update(['description'=>"A professional cook, typically the chief cook in a restaurant or hotel."]);
        \DB::table('specializations')->where('id',2)->update(['description'=>"Someone who carries out academic or scientific research in food & beverages."]);
        \DB::table('specializations')->where('id',3)->update(['description'=>"Someone responsible for the safe and efficient development, modification and manufacture of food products and processes."]);
        \DB::table('specializations')->where('id',4)->update(['description'=>"Someone with an experience in developing, modifying and / or manufacturing alcoholic or non-alcoholic beverages."]);
        \DB::table('specializations')->where('id',5)->update(['description'=>"Someone who studies or is an expert in nutrition."]);
        \DB::table('specializations')->where('id',6)->update(['description'=>"Someone who is responsible for buying or approving the acquisition of food & beverage products."]);
        \DB::table('specializations')->where('id',7)->update(['description'=>"Someone responsible for the process of preparing, presenting and serving of food and beverages to the customers."]);
        \DB::table('specializations')->where('id',8)->update(['description'=>"Someone responsible for handling, preparation, and storage of food in ways that prevent foodborne illness."]);
        \DB::table('specializations')->where('id',9)->update(['description'=>"Someone who owns or manages a farm."]);
        \DB::table('specializations')->where('id',10)->update(['description'=>"Someone who uses chemistry to engineer artificial and natural flavors."]);
        \DB::table('specializations')->where('id',11)->update(['description'=>"Someone who works to manufacture, distribute or sales of equipment used in the F&B industry."]);
        \DB::table('specializations')->where('id',12)->update(['description'=>"Anyone who is from the F&B industry but a relevant specialization is not mentioned in the above list."]);

    }
}
