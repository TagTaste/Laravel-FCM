<?php

namespace App\Http\Controllers\Api;

use App\Recipe;
use App\RecipeRating;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $recipes = Recipe::orderBy('created_at');

        $filters = $request->input('filters');
        if (!empty($filters['cuisine_id'])) {
            $recipes = $recipes->whereIn('cuisine_id', $filters['cuisine_id']);
        }

        if (!empty($filters['level'])) {
            $recipes = $recipes->whereIn('level', $filters['level']);
        }

        if (!empty($filters['type'])) {
            $recipes = $recipes->whereIn('type', $filters['type']);
        }
    
        //paginate
        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        $recipes=$recipes->skip($skip)->take($take)->get();

        $loggedInProfileId = $request->user()->profile->id;
        $this->model = [];
        foreach($recipes as $recipe){
            $this->model[] = ['recipe'=>$recipe,'meta'=>$recipe->getMetaFor($loggedInProfileId)];
        }
        return $this->sendResponse();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $recipe = Recipe::where('id',$id)->first();
        if(!$recipe){
            return $this->sendError("Could not find recipe.");
        }
        $loggedInProfileId = $request->user()->profile->id;
        $meta=$recipe->getMetaFor($loggedInProfileId);
        $recipe=$recipe->toArray();
        $recipe['userRating'] = RecipeRating::where('recipe_id',$id)->where('profile_id',$loggedInProfileId)->first();
        $this->model = ['recipe'=>$recipe,'meta'=>$meta];

        return $this->sendResponse();
    }
    
    /**
     * Return recipe image.
     * @param $id
     * @return mixed
     */
    public function recipeImages($id)
    {
        $recipe = Recipe::select('image')->findOrFail($id);
        $path = storage_path("app/" . Recipe::$fileInputs['image'] . "/" . $recipe->image);
        if(file_exists($path)){
            return response()->file($path);
        }
    }

    public function filters()
    {
        $filters = [];

//        $filters['cuisine'] = \App\Cuisine::select('id','name')->groupBy('name')->get();
        $filters['level'] = \App\Filter\Recipe::select('level as value')->groupBy('level')->get();
        $filters['type'] = \App\Filter\Recipe::select('type as value')->groupBy('type')->get();
        $filters['ingredients']=\App\Recipe\Ingredient::select('id as key','name as value')->get();
        $this->model = $filters;
        return $this->sendResponse();
    }
}
