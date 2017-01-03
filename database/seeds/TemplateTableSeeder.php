<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;
use App\Template;
use App\TemplateType;

class TemplateTableSeeder extends Seeder {

    public function run()
    {
        $dishTemplateType = TemplateType::where('name','like','%dish%')->first();

        if(!$dishTemplateType){
            throw new \Exception("Could not find dish template type.");
        }

        Template::create(['name'=>'Dish Article','view'=>'templates.article_dish','template_type_id'=>$dishTemplateType->id]);

        $blogTemplateType = TemplateType::where('name','like','%blog%')->first();

        if(!$dishTemplateType){
            throw new \Exception("Could not find blog template type.");
        }

        Template::create(['name'=>'Blog Article','view'=>'templates.article_blog','template_type_id'=>$blogTemplateType->id]);
    }

}