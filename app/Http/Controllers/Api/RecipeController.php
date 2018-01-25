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
        $loggedInProfileId = $request->user()->profile->id;
    
        //paginate
        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        
        if(!empty($filters)){
            $this->model = [];
            $recipeModelIds = \App\Filter\Recipe::getModelIds($filters,$skip,$take);
            
            if(count($recipeModelIds) == 0){
                return $this->sendResponse();
            }
            $this->model["data"]=[];

            $recipes = \App\Recipe::whereIn('id',$recipeModelIds)->get();
            foreach($recipes as $recipe){
                $this->model['data'][] = ['recipe'=>$recipe,'meta'=>$recipe->getMetaFor($loggedInProfileId)];
            }
            $this->model['count'] = count($this->model['data']);
            return $this->sendResponse();
        }

        $this->model = [];
        $this->model['count'] = $recipes->count();
        $this->model['data'] = [];
       
        $recipes=$recipes->skip($skip)->take($take)->get();

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
