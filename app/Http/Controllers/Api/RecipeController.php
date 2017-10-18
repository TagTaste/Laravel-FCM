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
        $recipes = Recipe::orderBy('created_at')->whereNull('deleted_at');

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

        if (!empty($filters['vegetarian'])) {
            $recipes = $recipes->where('is_vegetarian', $filters['vegetarian']);
        }
        
        if(!empty($filters['preparation_time'])){
            $start = [
                0 => 1,
                1 => 1,
                2 => 4
            ];
    
            $end = [
                0 => 1,
                1 => 4,
                2 => 8
            ];
            $startAt = 999; //a very high number
            $endAt = 0;
            foreach($filters['preparation_time'] as $prepTime){
                if($start[$prepTime] < $startAt){
                    $startAt = $start[$prepTime];
                }
                
                if($end[$prepTime] > $endAt){
                    $endAt = $end[$prepTime];
                }
            }
    
            $recipes = $recipes->where('preparation_time','>=',$startAt)
                ->where('preparation_time','<=',$endAt);
        }
        
        if(!empty($filters['ingredients'])){
            $recipes = $recipes->whereHas('ingredients',function($query) use (&$filters){
                $query->whereIn('id',$filters['ingredients']);
            });
        }
    
        if(!empty($filters['cooking_time'])){
            $start = [
                0 => 1,
                1 => 1,
                2 => 4
            ];
        
            $end = [
                0 => 1,
                1 => 4,
                2 => 8
            ];
            $startAt = 999; //a very high number
            $endAt = 0;
            foreach($filters['cooking_time'] as $prepTime){
                if($start[$prepTime] < $startAt){
                    $startAt = $start[$prepTime];
                }
            
                if($end[$prepTime] > $endAt){
                    $endAt = $end[$prepTime];
                }
            }
        
            $recipes = $recipes->where('cooking_time','>=',$startAt)
                ->where('cooking_time','<=',$endAt);
        }

        $this->model = [];
        $this->model['count'] = $recipes->count();
        $this->model['data'] = [];
        //paginate
        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        $recipes=$recipes->skip($skip)->take($take)->get();

        $loggedInProfileId = $request->user()->profile->id;
        foreach($recipes as $recipe){
            $this->model['data'][] = ['recipe'=>$recipe,'meta'=>$recipe->getMetaFor($loggedInProfileId)];
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
        $recipe = Recipe::where('id',$id)->whereNull('deleted_at')->first();
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
        $this->model = \App\Filter::getFilters("recipe");
        return $this->sendResponse();
    }

    public function properties()
    {
        $this->model = [];
        foreach(\App\Recipe::$level as $key => $value){
            $this->model['level'][] = ['key'=>$key,'value'=>$value];
        }
        foreach(\App\Recipe::$type as $key => $value){
            $this->model['type'][] = ['key'=>$key,'value'=>$value];
        }
        foreach(\App\Recipe::$veg as $key => $value){
            $this->model['is_vegetarian'][] = ['key'=>$key,'value'=>$value];
        }
        return $this->sendResponse();
    }
}
