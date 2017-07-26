<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\RecipeRating;
use App\Recipe;

class RecipeRatingController extends Controller
{
    /**
     * Variable to model
     *
     * @var recipe_rating
     */
    protected $model;

    /**
     * Create instance of controller with Model
     *
     * @return void
     */
    public function __construct(RecipeRating $model)
    {
        $this->model = $model;
    }


    public function rate(Request $request, $recipeId)
    {
        $recipe = Recipe::find($recipeId);

        if (!$recipe) {
            return $this->sendError("Recipe doesn't exist.");
        }

        $inputs = $request->all();

        $inputs['recipe_id'] = $recipeId;
        $inputs['profile_id'] = $request->user()->profile->id;

        $this->model->where('recipe_id', $recipeId)
            ->where('profile_id', $inputs['profile_id'])->delete();


        $this->model = $this->model->create($inputs);
        return $this->sendResponse();
    }
}
