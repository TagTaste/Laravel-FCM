<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy

class CuisineTableSeeder extends Seeder {

    public function run()
    {
        $names = ["Afghani","African","American","Andhra","Arabian","Armenian","Asian","Assamese",
            "Australian","Awadhi","Bakery","Bangladeshi","Belgian","Bengali","Bihari","Biryani",
            "British","Burger","Burmese","Cafe","Charcoal Grill","Chettinad","Chinese","Continental",
            "Desserts","European","Finger Food","French","German","Goan","Greek","Gujarati","Healthy Food",
            "Hyderabadi","Indian","Indonesian","Iranian","Italian","Japanese","Juices","Kashmiri","Kerala","Korean",
            "Lebanese","Lucknowi","Maharashtrian","Malaysian","Mangalorean","Mediterranean","Mexican","Middle Eastern",
            "Modern Indian","Moroccan","Mughlai","Naga","Nepalese","North Eastern","North Indian","Oriya","Pakistani",
            "Panini","Parsi","Persian","Pizza","Portuguese","Rajasthani","Russian","Sandwich","Seafood","Sindhi",
            "Singaporean","South American","South Indian","Spanish","Sri Lankan","Steak","Street Food","Sushi","Tex-Mex",
            "Thai","Tibetan","Turkish","Vietnamese"];

        foreach ($names as &$name)
        {
            $name = ['name'=>$name];
        }
        \App\Cuisine::insert($names);
    }

}