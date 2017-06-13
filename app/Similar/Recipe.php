<?php namespace App\Similar;

use App\Recipe as BaseRecipe;

class Recipe extends BaseRecipe
{
    //protected $visible = ['id','name','imageUrl'];
    
    public function similar($skip,$take)
    {
        return self::where('level','=',$this->level)
            ->skip($skip)
            ->take($take)
            ->get();
    }
}