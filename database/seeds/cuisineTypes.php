<?php

use Illuminate\Database\Seeder;

class cuisineTypes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('cuisines')->truncate();
        DB::table('cuisines')->insert([
            'name' => 'Afghani',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'African',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'American',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Andhra',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Arabian',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Armenian',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Asian',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Assamese',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Australian',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Awadhi',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Bakery',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Bangladeshi',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Belgian',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Bengali',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Bihari',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Biryani',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'British',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Burger',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Burmese',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Charcoal Grill',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Chettinad',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Chinese',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Continental',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Desserts',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'European',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Finger Food',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'French',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'German',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Goan',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Greek',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Gujarati',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Healthy Food',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Hyderabadi',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Indian',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Indonesian',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Iranian',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Italian',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Japanese',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Juices',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Kashmiri',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Kerala',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Korean',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Lebanese',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Lucknowi',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Maharashtrian',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Malaysian',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Mangalorean',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Mediterranean',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Mexican',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Middle Eastern',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Modern Indian',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Moroccan',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Mughlai',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Naga',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Nepalese',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'North Eastern',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'North Indian',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Oriya',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Pakistani',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Panini',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Parsi',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Persian',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Pizza',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Portuguese',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Rajasthani',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Russian',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Sandwich',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Seafood',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Sindhi',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Singaporean',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'South American',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'South Indian',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Spanish',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Sri Lankan',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Steak',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Street Food',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Sushi',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Tex-Max',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Thai',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Tibetan',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Turkish',
        ]);
        DB::table('cuisines')->insert([
            'name' => 'Vietnamese',
        ]);
    }
}
