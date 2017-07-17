<?php

namespace App\Http\Controllers\Api;

use App\Recipe;
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
        $recipes = Recipe::orderBy('created_at')->paginate(10);
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
    
        $loggedInProfileId = $request->user()->profile->id;
        $this->model = ['recipe'=>$recipe,'meta'=>$recipe->getMetaFor($loggedInProfileId)];
        
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

        $filters['cuisine'] = \App\Filter\Recipe::select('cuisine_id')->groupBy('cuisine_id')->get();
        $filters['level'] = \App\Filter\Recipe::select('level')->groupBy('level')->get();
        $filters['type'] = \App\Filter\Recipe::select('type')->groupBy('type')->get();
        $this->model = $filters;
        return $this->sendResponse();
    }
}
