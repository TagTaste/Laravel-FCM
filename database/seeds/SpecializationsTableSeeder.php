<?php

use Illuminate\Database\Seeder;

class SpecializationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];
        $data[] = ['name'=>'Animal Husbandry', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/animal_husbandry.png', 'order'=>19];
        $data[] = ['name'=>'Blogger', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/blogger.png', 'order'=>11];
        $data[] = ['name'=>'Operation', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/operation.png', 'order'=>10];
        $data[] = ['name'=>'Food Anthropologist', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/food_anthropologist.png', 'order'=>13];
        $data[] = ['name'=>'Food Production', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/food_production.png', 'order'=>9];
        $data[] = ['name'=>'Wine Sommelier', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/wine_sommelier.png', 'order'=>8];
        $data[] = ['name'=>'Trader', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/trader.png', 'order'=>17];
        $data[] = ['name'=>'Retailer', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/retailer.png', 'order'=>18];
        $data[] = ['name'=>'Food Photographer', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/photographer.png', 'order'=>12];

        \DB::table('specializations')->insert($data);
        \DB::table('specializations')->where('id',1)->update(['name'=>'Chef', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/chef.png', 'order'=>1]);
        \DB::table('specializations')->where('id',2)->update(['name'=>'Researcher', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/researcher.png', 'order'=>14]);
        \DB::table('specializations')->where('id',3)->update(['name'=>'Food Technologist', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/food_technologies.png', 'order'=>15]);
        \DB::table('specializations')->where('id',4)->update(['name'=>'Beverage Expert', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/beverage_expert.png', 'order'=>2]);
        \DB::table('specializations')->where('id',5)->update(['name'=>'Nutritionist', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/nutritionist.png', 'order'=>3]);
        \DB::table('specializations')->where('id',6)->update(['name'=>'Purchase Manager', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/purchase_manager.png', 'order'=>4]);
        \DB::table('specializations')->where('id',7)->update(['name'=>'F&B Taster', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/f%26b_taster.png', 'order'=>7]);
        \DB::table('specializations')->where('id',8)->update(['name'=>'Food Safety', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/food_safety.png', 'order'=>16]);
        \DB::table('specializations')->where('id',9)->update(['name'=>'Farmer', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/farmer.png', 'order'=>5]);
        \DB::table('specializations')->where('id',10)->update(['name'=>'Flavorist', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/flavorists.png', 'order'=>6]);
        \DB::table('specializations')->where('id',11)->update(['name'=>'Equipment', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/equipment.png', 'order'=>20]);
        \DB::table('specializations')->where('id',12)->update(['order'=>21]);
    }
}
