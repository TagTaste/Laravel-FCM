<?php

use Illuminate\Database\Seeder;

class establishmentTypes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('establishment_types')->truncate();

        DB::table('establishment_types')->insert([
            'name' => 'Fine Dine',
            'description' => 'These are high end restaurants with a fancy atmosphere, serving high priced dishes. The menu has exotic dishes and the table service is impeccable with formally dressed waiters. They usually do serve alcohol. These places might have a dress code and services like valet parking  etc. Example: ITC - Bukhara, Indian Accent.',
        ]);
        DB::table('establishment_types')->insert([
            'name' => 'Casual Dine',
            'description' => 'These restaurants will serve moderately priced food in a casual atmosphere with table service. The ambiance my vary a little wrt the sophistication. May or may not serve alcohol. Example: Pind balluchi, Pirates of Grill, etc.',
        ]);
        DB::table('establishment_types')->insert([
            'name' => 'Quick Service Restaurant',
            'description' => 'These are Quick Service Restaurants, very casual seating, with minimal or no table service. The ambiance is very basic and serves mostly fast food or plates/thalis. Example: Dominos, KFC, Haldirams etc.',
        ]);
        DB::table('establishment_types')->insert([
            'name' => 'Cafe & Bistro',
            'description' => 'These are restaurants serving moderately priced food in a casual, cafe-like seating. The ambiance is relaxed and the food type mostly includes cafe cuisines(Burgers, Pizzas, Pastas, finger food etc). According to the new trends these places may also serve a variety of cocktails, mocktails, beer etc. Example: Another fine day, Elma\'s Cafe etc.',
        ]);
        DB::table('establishment_types')->insert([
            'name' => 'Beverage Shops',
            'description' => 'All the shake centres, Juice centres will be categorised under this. Example: Keventers, Amar juice centre etc.',
        ]);
        DB::table('establishment_types')->insert([
            'name' => 'Pub/Bars',
            'description' => 'These places are known mostly for their drinks. The seating will include bar stools, and relaxed seating. The food might not have much variety and would mostly include pub/bar food, finger food etc. Example: Soi 7 Pub, Manhattan etc. ',
        ]);
        DB::table('establishment_types')->insert([
            'name' => 'Lounge/Club',
            'description' => 'These place are different from bars/pubs in terms of lighting and ambiance. They usually have a dim lighting with relaxed seating in the form of sofas and couches. The music is usually loud in such places. Example: Social, BluO etc.',
        ]);
        DB::table('establishment_types')->insert([
            'name' => 'Delivery Kitchens',
            'description' => 'These are places with no seating, they only have a kitchen and deliver food to specific areas. May/maynot have their own delivery services and might also have an option of take-aways. Examples: Frsh.com, Chaayos delivery, Pizza Hut Delivery etc.',
        ]);
        DB::table('establishment_types')->insert([
            'name' => 'Bakery/Desert Parlour',
            'description' => 'These are outlets serving bakery products, desserts, ice creams etc. May or may not have a seating. Example: Bisque Bakery, BomBaykery, Red Mango etc.',
        ]);
    }
}
