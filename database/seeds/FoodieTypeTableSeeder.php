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
        $data = [];
        $data[] = [
            'name'=>'Pescatarian',
            'technical_name'=>'Pescatarian',
            'order'=>8,
            'description'=>'Has mostly vegetarian diet but eats fish and seafood as well.'
        ];
        $data[] = [
            'name'=>'Eggetarian',
            'technical_name'=>'Ovo Lacto Vegetarian',
            'order'=>2,
            'description'=>'Eats vegetables, fruits, eggs and dairy products but excludes meat, fish and poultry.'
        ];
        $data[] = [
            'name'=>'Vegetarian',
            'technical_name'=>'Lacto Vegetarian',
            'order'=>1,
            'description'=>'Eats vegetables, fruits and dairy products but excludes eggs, poultry, fish and meat.'
        ];
        $data[] = [
            'name'=>'Vegan',
            'technical_name'=>'Vegan',
            'order'=>4,
            'description'=>'Does not eat animal by-products (eggs, honey, dairy), fish and meat.'
        ];
        $data[] = [
            'name'=>'Flexitarian',
            'technical_name'=>'Flexitarian',
            'order'=>7,
            'description'=>'Has primarily vegetarian diet but occasionally eats meat or fish as well.'
        ];
        $data[] = [
            'name'=>'Ovo Vegetarian',
            'technical_name'=>'Ovo Vegetarian',
            'order'=>3,
            'description'=>'Eats vegetables, fruits and eggs but excludes dairy products.'
        ];
        $data[] = [
            'name'=>'Fruitarian',
            'technical_name'=>'Fruitarian',
            'order'=>5,
            'description'=>'Eats only fruits and possibly nuts & seeds that are without animal products.'
        ];
        $data[] = [
            'name'=>'Omnivorous',
            'technical_name'=>'Omnivorous',
            'order'=>6,
            'description'=>'Eats both plant and animal-based food products.'
        ];
        $data[] = [
            'name'=>'Pollotarian',
            'technical_name'=>'Pollotarian',
            'order'=>9,
            'description'=>'Eats chicken or other poultry but excludes meat from mammals.'
        ];
        $data[] = [
            'name'=>'Pollo Pescatarian',
            'technical_name'=>'Pollo Pescatarian',
            'order'=>10,
            'description'=>'Eats both poultry and fish or seafood but excludes meat from mammals.'
        ];

        foreach ($data as $key => $value) {
            $found = \DB::table('foodie_type')->where('name',$value['name'])->count();
            if ($found) {
                $name = $value['name'];
                unset($value['name']);
                \DB::table('foodie_type')->where('name', $name)->update($value);
            } else {
                \DB::table('foodie_type')->insert($value);
            }
        }
    }
}
