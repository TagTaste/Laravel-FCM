<?php namespace App\Similar;

use App\Recipe as BaseRecipe;

class Recipe extends BaseRecipe
{
    //protected $visible = ['id','name','imageUrl'];
    
    public function similar($skip,$take)
    {
        //similar
        //tags
        //level
        //type
        //is_vegetarian
        return self::where('id','!=',$this->id)->whereNull('deleted_at')
            ->skip($skip)
            ->take($take)
            ->get();
    }
}