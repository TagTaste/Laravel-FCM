<?php

use Illuminate\Database\Seeder;

use App\AttributeValue;
use App\ProfileAttribute;

class AttributeValueTableSeeder extends Seeder {

    public function run()
    {
        $cuisine = ProfileAttribute::where('name','like','cuisine')->first();
        $establishment = ProfileAttribute::where('name','like','establishment_types')->first();

        if($cuisine){
            $cuisines = [];
            $cuisines[] = ['name' => 'Afghani', 'value' => 'Afghani'];
            $cuisines[] = ['name' => 'African', 'value' => 'African'];
            $cuisines[] = ['name' => 'American', 'value' => 'American'];
            $cuisines[] = ['name' => 'Andhra', 'value' => 'Andhra'];
            $cuisines[] = ['name' => 'Arabian', 'value' => 'Arabian'];
            $cuisines[] = ['name' => 'Armenian', 'value' => 'Armenian'];
            $cuisines[] = ['name' => 'Asian', 'value' => 'Asian'];
            $cuisines[] = ['name' => 'Assamese', 'value' => 'Assamese'];
            $cuisines[] = ['name' => 'Australian', 'value' => 'Australian'];
            $cuisines[] = ['name' => 'Awadhi', 'value' => 'Awadhi'];
            $cuisines[] = ['name' => 'Bakery', 'value' => 'Bakery'];
            $cuisines[] = ['name' => 'Bangladeshi', 'value' => 'Bangladeshi'];
            $cuisines[] = ['name' => 'Belgian', 'value' => 'Belgian'];
            $cuisines[] = ['name' => 'Bengali', 'value' => 'Bengali'];
            $cuisines[] = ['name' => 'Bihari', 'value' => 'Bihari'];
            $cuisines[] = ['name' => 'Biryani', 'value' => 'Biryani'];
            $cuisines[] = ['name' => 'British', 'value' => 'British'];
            $cuisines[] = ['name' => 'Burger', 'value' => 'Burger'];
            $cuisines[] = ['name' => 'Burmese', 'value' => 'Burmese'];
            $cuisines[] = ['name' => 'Charcoal Grill', 'value' => 'CharcoalGrill'];
            $cuisines[] = ['name' => 'Chettinad', 'value' => 'Chettinad'];
            $cuisines[] = ['name' => 'Chinese', 'value' => 'Chinese'];
            $cuisines[] = ['name' => 'Continental', 'value' => 'Continental'];
            $cuisines[] = ['name' => 'Desserts', 'value' => 'Desserts'];
            $cuisines[] = ['name' => 'European', 'value' => 'European'];
            $cuisines[] = ['name' => 'Finger Food', 'value' => 'FingerFood'];
            $cuisines[] = ['name' => 'French', 'value' => 'French'];
            $cuisines[] = ['name' => 'German', 'value' => 'German'];
            $cuisines[] = ['name' => 'Goan', 'value' => 'Goan'];
            $cuisines[] = ['name' => 'Greek', 'value' => 'Greek'];
            $cuisines[] = ['name' => 'Gujarati', 'value' => 'Gujarati'];
            $cuisines[] = ['name' => 'Healthy Food', 'value' => 'HealthyFood'];
            $cuisines[] = ['name' => 'Hyderabadi', 'value' => 'Hyderabadi'];
            $cuisines[] = ['name' => 'Indian', 'value' => 'Indian'];
            $cuisines[] = ['name' => 'Indonesian', 'value' => 'Indonesian'];
            $cuisines[] = ['name' => 'Iranian', 'value' => 'Iranian'];
            $cuisines[] = ['name' => 'Italian', 'value' => 'Italian'];
            $cuisines[] = ['name' => 'Japanese', 'value' => 'Japanese'];
            $cuisines[] = ['name' => 'Juices', 'value' => 'Juices'];
            $cuisines[] = ['name' => 'Kashmiri', 'value' => 'Kashmiri'];
            $cuisines[] = ['name' => 'Kerala', 'value' => 'Kerala'];
            $cuisines[] = ['name' => 'Korean', 'value' => 'Korean'];
            $cuisines[] = ['name' => 'Lebanese', 'value' => 'Lebanese'];
            $cuisines[] = ['name' => 'Lucknowi', 'value' => 'Lucknowi'];
            $cuisines[] = ['name' => 'Maharashtrian', 'value' => 'Maharashtrian'];
            $cuisines[] = ['name' => 'Malaysian', 'value' => 'Malaysian'];
            $cuisines[] = ['name' => 'Mangalorean', 'value' => 'Mangalorean'];
            $cuisines[] = ['name' => 'Mediterranean', 'value' => 'Mediterranean'];
            $cuisines[] = ['name' => 'Mexican', 'value' => 'Mexican'];
            $cuisines[] = ['name' => 'Middle Eastern', 'value' => 'MiddleEastern'];
            $cuisines[] = ['name' => 'Modern Indian', 'value' => 'ModernIndian'];
            $cuisines[] = ['name' => 'Moroccan', 'value' => 'Moroccan'];
            $cuisines[] = ['name' => 'Mughlai', 'value' => 'Mughlai'];
            $cuisines[] = ['name' => 'Naga', 'value' => 'Naga'];
            $cuisines[] = ['name' => 'Nepalese', 'value' => 'Nepalese'];
            $cuisines[] = ['name' => 'North Eastern', 'value' => 'NorthEastern'];
            $cuisines[] = ['name' => 'North Indian', 'value' => 'NorthIndian'];
            $cuisines[] = ['name' => 'Oriya', 'value' => 'Oriya'];
            $cuisines[] = ['name' => 'Pakistani', 'value' => 'Pakistani'];
            $cuisines[] = ['name' => 'Panini', 'value' => 'Panini'];
            $cuisines[] = ['name' => 'Parsi', 'value' => 'Parsi'];
            $cuisines[] = ['name' => 'Persian', 'value' => 'Persian'];
            $cuisines[] = ['name' => 'Pizza', 'value' => 'Pizza'];
            $cuisines[] = ['name' => 'Portuguese', 'value' => 'Portuguese'];
            $cuisines[] = ['name' => 'Rajasthani', 'value' => 'Rajasthani'];
            $cuisines[] = ['name' => 'Russian', 'value' => 'Russian'];
            $cuisines[] = ['name' => 'Sandwich', 'value' => 'Sandwich'];
            $cuisines[] = ['name' => 'Seafood', 'value' => 'Seafood'];
            $cuisines[] = ['name' => 'Sindhi', 'value' => 'Sindhi'];
            $cuisines[] = ['name' => 'Singaporean', 'value' => 'Singaporean'];
            $cuisines[] = ['name' => 'South American', 'value' => 'SouthAmerican'];
            $cuisines[] = ['name' => 'South Indian', 'value' => 'SouthIndian'];
            $cuisines[] = ['name' => 'Spanish', 'value' => 'Spanish'];
            $cuisines[] = ['name' => 'Sri Lankan', 'value' => 'SriLankan'];
            $cuisines[] = ['name' => 'Steak', 'value' => 'Steak'];
            $cuisines[] = ['name' => 'Street Food', 'value' => 'StreetFood'];
            $cuisines[] = ['name' => 'Sushi', 'value' => 'Sushi'];
            $cuisines[] = ['name' => 'Tex-Max', 'value' => 'TexMax'];
            $cuisines[] = ['name' => 'Thai', 'value' => 'Thai'];
            $cuisines[] = ['name' => 'Tibetan', 'value' => 'Tibetan'];
            $cuisines[] = ['name' => 'Turkish', 'value' => 'Turkish'];
            $cuisines[] = ['name' => 'Vietnamese', 'value' => 'Vietnamese'];

            foreach($cuisines as &$value){
                $value['attribute_id'] = $cuisine->id;
                $value['default'] = 0;
            }
        }

        if($establishment){
            $establishments = [];
            $establishments[] = ['name' => 'Fine Dine', 'value' => 'FineDine'];
            $establishments[] = ['name' => 'Casual Dine', 'value' => 'CasualDine'];
            $establishments[] = ['name' => 'Quick Service Restaurant', 'value' => 'QSR'];
            $establishments[] = ['name' => 'Cafe & Bistro', 'value' => 'CafeBistro'];
            $establishments[] = ['name' => 'Beverage Shops', 'value' => 'BeverageShops'];
            $establishments[] = ['name' => 'Pub/Bars', 'value' => 'PubBars'];
            $establishments[] = ['name' => 'Lounge/Club', 'value' => 'LoungeClub'];
            $establishments[] = ['name' => 'Delivery Kitchens', 'value' => 'DeliveryKitchens'];
            $establishments[] = ['name' => 'Bakery/Desert Parlour', 'value' => 'BakeryDesert'];

            foreach($establishments as &$value){
                $value['attribute_id'] = $establishment->id;
                $value['default'] = 0;
            }
        }

        $chefAward = ProfileAttribute::where("name",'like','chef_awards')->first();

        if($chefAward){

            $awards[] = ['name'=>'JBF Award','value'=>'jbf'];
            $awards[] = ['name'=>'The Chefs\' Choice Award','value'=>'chefchoice'];

            foreach($awards as &$value){
                $value['attribute_id'] = $chefAward->id;
                $value['default'] = 0;
            }
        }

        $values = array_merge($cuisines, $awards, $establishments);

        if(count($values)){
            AttributeValue::insert($values);
        }
    }

}