<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Profile::class, function (Faker\Generator $faker) {
    static $userId;
    
   return [ 'user_id' => $userId ];
});


$factory->define(App\Company::class,function(Faker\Generator $faker){
    $faker->addProvider(new Faker\Provider\en_US\Company($faker));
    
    static $userId;
    
    return [
        'name' => $faker->company,
        'about' =>  $faker->realText(),
        'registered_address' => $faker->address,
        'user_id' => $userId
    ];
});

$factory->define(App\Company\Portfolio::class, function (Faker\Generator $faker) {
    $faker->addProvider(new Faker\Provider\en_US\Company($faker));
    
    return ['worked_for'=>$faker->company,'description'=>$faker->realText()];
});

$factory->define(App\Album::class,function (Faker\Generator $faker) {
     $faker->addProvider(new Faker\Provider\en_US\Company($faker));

     return ['name'=>$faker->name,'description'=>$faker->realText()];
});