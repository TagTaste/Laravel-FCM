<?php

use Illuminate\Database\Seeder;

class FoodieTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('profiles')->whereIn('foodie_type_id',[5,6])->update(['foodie_type_id'=>null]);
        //
        \DB::table('foodie_type')->where('id',1)->update(['order'=>8,'technical_name'=>'Pescatarian','description'=>'Has mostly vegetarian diet but eats fish and seafood as well.']);

        \DB::table('foodie_type')->where('id',2)->update(['name'=>'Eggetarian','order'=>2,'technical_name'=>'Ovo Lacto Vegetarian','description'=>'Eats vegetables, fruits, eggs and dairy products but excludes meat, fish and poultry.']);

        \DB::table('foodie_type')->where('id',3)->update(['name'=>'Vegetarian','order'=>1,'technical_name'=>'Lacto Vegetarian','description'=>'Eats vegetables, fruits and dairy products but excludes eggs, poultry, fish and meat.']);

        \DB::table('foodie_type')->where('id',4)->update(['name'=>'Vegan','order'=>4,'technical_name'=>'Vegan','description'=>'Does not eat animal by-products (eggs, honey, dairy), fish and meat.']);

        \DB::table('foodie_type')->where('id',5)->delete();
        \DB::table('foodie_type')->where('id',6)->delete();

        \DB::table('foodie_type')->where('id',7)->update(['order'=>7,'technical_name'=>'Flexitarian','description'=>'Has primarily vegetarian diet but occasionally eats meat or fish as well.']);


        $data = [];
        $data[] = ['name'=>'Ovo Vegetarian','technical_name'=>'Ovo Vegetarian','order'=>3,'description'=>'Eats vegetables, fruits and eggs but excludes dairy products.'];
        $data[] = ['name'=>'Fruitarian','technical_name'=>'Fruitarian','order'=>5,'description'=>'Eats only fruits and possibly nuts & seeds that are without animal products.'];
        $data[] = ['name'=>'Omnivorous','technical_name'=>'Omnivorous','order'=>6,'description'=>'Eats both plant and animal-based food products.'];
        $data[] = ['name'=>'Pollotarian','technical_name'=>'Pollotarian','order'=>9,'description'=>'Eats chicken or other poultry but excludes meat from mammals.'];
        $data[] = ['name'=>'Pollo Pescatarian','technical_name'=>'Pollo Pescatarian','order'=>10,'description'=>'Eats both poultry and fish or seafood but excludes meat from mammals.'];

        \DB::table('foodie_type')->insert($data);

    }
}
