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
        $data[] = ['name'=>'Animal Husbandry', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/animal_husbandry.png'];
        $data[] = ['name'=>'Beverage Expert', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/beverage_expert.png'];
        $data[] = ['name'=>'Blogger', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/blogger.png'];
        $data[] = ['name'=>'Operation', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/operation.png'];
        $data[] = ['name'=>'Chef', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/chef.png'];
        $data[] = ['name'=>'Researcher', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/researcher.png'];
        $data[] = ['name'=>'Food Technologist', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/food_technologies.png'];
        $data[] = ['name'=>'Food Anthropologist', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/food_anthropologist.png'];
        $data[] = ['name'=>'Food Production', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/food_production.png'];
        $data[] = ['name'=>'Nutritionist', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/nutritionist.png'];
        $data[] = ['name'=>'Purchase Manager', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/purchase_manager.png'];
        $data[] = ['name'=>'F&B Professional', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/f%26b_taster.png'];
        $data[] = ['name'=>'Food Safety', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/food_safety.png'];
        $data[] = ['name'=>'Farmer', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/farmer.png'];
        $data[] = ['name'=>'Flavorist', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/flavorists.png'];
        $data[] = ['name'=>'Equipment', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/equipment.png'];
        $data[] = ['name'=>'Wine Sommelier', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/wine_sommelier.png'];
        $data[] = ['name'=>'Trader', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/trader.png'];
        $data[] = ['name'=>'Retailer', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/retailer.png'];
        $data[] = ['name'=>'Photographer', 'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/photographer.png'];
        $data[] = ['name'=>'Any Other', 'image'=>''];

        \DB::table('specializations')->insert($data);

    }
}
