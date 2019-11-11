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
        $data[] = [
            'name'=>'Chef',
            'description' => 'A professional cook, typically the chief cook in a restaurant or hotel.',
            'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/chef.png',
            'order'=>1
        ];
        $data[] = [
            'name'=>'Researcher',
            'description' => 'Someone who carries out academic or scientific research in food & beverages.',
            'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/researcher.png',
            'order'=>14
        ];
        $data[] = [
            'name'=>'Food Technologist',
            'description' => 'Someone responsible for the safe and efficient development, modification and manufacture of food products and processes.',
            'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/food_technologies.png',
            'order'=>15
        ];
        $data[] = [
            'name'=>'Beverage Expert',
            'description' => 'Someone with an experience in developing, modifying and / or manufacturing alcoholic or non-alcoholic beverages.',
            'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/beverage_expert.png',
            'order'=>2
        ];
        $data[] = [
            'name'=>'Nutritionist',
            'description' => 'Someone who studies or is an expert in nutrition.',
            'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/nutritionist.png',
            'order'=>3
        ];
        $data[] = [
            'name'=>'Purchase Manager',
            'description' => 'Someone who is responsible for buying or approving the acquisition of food & beverage products.',
            'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/purchase_manager.png',
            'order'=>4
        ];
        $data[] = [
            'name'=>'F&B Taster',
            'description' => 'Someone responsible for the process of preparing, presenting and serving of food and beverages to the customers.',
            'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/f%26b_taster.png',
            'order'=>7
        ];
        $data[] = [
            'name'=>'Food Safety',
            'description' => 'Someone responsible for handling, preparation, and storage of food in ways that prevent foodborne illness.',
            'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/food_safety.png',
            'order'=>16
        ];
        $data[] = [
            'name'=>'Farmer',
            'description' => 'Someone who owns or manages a farm.',
            'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/farmer.png',
            'order'=>5
        ];
        
        $data[] = [
            'name'=>'Flavorist',
            'description' => 'Someone who uses chemistry to engineer artificial and natural flavors.',
            'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/flavorists.png',
            'order'=>6
        ];
        $data[] = [
            'name'=>'Equipment',
            'description' => 'Someone who works to manufacture, distribute or sales of equipment used in the F&B industry.',
            'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/equipment.png',
            'order'=>20
        ];
        $data[] = [
            'name'=>'Food Enthusiast',
            'description' => 'Anyone who is from the F&B industry but a relevant specialization is not mentioned in the above list.',
            'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/food_enthusiast.png',
            'order'=>24
        ];
        $data[] = [
            'name'=>'Animal Husbandry',
            'description' => 'Someone who is concerned with day-to-day care, selective breeding and the raising of livestock for meat, fibre, milk, eggs, or other products.',
            'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/animal_husbandry.png',
            'order'=>19
        ];
        $data[] = [
            'name'=>'Blogger',
            'description' => 'Someone who writes about his or her experiences with food or cooking.',
            'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/blogger.png',
            'order'=>11
        ];
        $data[] = [
            'name'=>'Operation',
            'description' => 'Someone who is involved in running the day to day operations at hotel or restaurants.',
            'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/operation.png',
            'order'=>10
        ];
        $data[] = [
            'name'=>'Food Anthropologist',
            'description' => 'Someone who studies various aspects of food in past and present humans societies.',
            'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/food_anthropologist.png',
            'order'=>13
        ];
        $data[] = [
            'name'=>'Food Production',
            'description' => 'Someone who is involved in the process of producing food items but is not a farmer.',
            'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/food_production.png',
            'order'=>9
        ];
        $data[] = [
            'name'=>'Wine Sommelier',
            'description' => 'Someone who specializes in all aspects of wine service as well as wine and food pairing and is normally working in fine restaurants.',
            'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/wine_sommelier.png',
            'order'=>8
        ];
        $data[] = [
            'name'=>'Distributor',
            'description' => 'Someone who deals in distribution of food and beverage based products.',
            'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/trader.png',
            'order'=>17
        ];

        $data[] = [
            'name'=>'Retailer',
            'description' => 'Someone who sells food and beverage products directly to the end customers.',
            'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/retailer.png',
            'order'=>18
        ];
        $data[] = [
            'name'=>'Food Photographer',
            'description' => 'Someone who creates images (and videos) of food for use in advertising, menus, cookbooks and other media.',
            'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/photographer.png',
            'order'=>12
        ];
        $data[] = [
            'name'=>'Hotelier',
            'description' => 'Someone who owns and manages a hotel.',
            'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/hotelier.png',
            'order'=>21
        ];
        $data[] = [
            'name'=>'Restaurateur',
            'description' => 'Someone who owns and manages a restaurant.',
            'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/restaurateur.png',
            'order'=>22
        ];
        $data[] = [
            'name'=>'Caterer',
            'description' => 'Someone who provides food and drink at a social event or other gatherings.',
            'image'=>'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/specialization/caterer.png',
            'order'=>23
        ];

        foreach ($data as $key => $value) {
            $found = \DB::table('specializations')->where('name',$value['name'])->count();
            if ($found) {
                $name = $value['name'];
                unset($value['name']);
                \DB::table('specializations')->where('name', $name)->update($value);
            } else {
                \DB::table('specializations')->insert($value);
            }
        }
    }
}
