<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Api\Controller;
use App\Recipe;
use App\RecipeLike;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($profileId)
    {
        $this->model = Recipe::where('profile_id',$profileId)->orderBy('created_at')->get();
        return $this->sendResponse();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $profileId = $request->user()->profile->id;
        $inputs = $request->all();
        $inputs['profile_id'] = $profileId;
        
        $this->model = Recipe::create($inputs);
        return $this->sendResponse();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($profileId,$id)
    {
        $this->model = Recipe::where('profile_id',$profileId)->where('id',$id)->first();
        
        return $this->sendResponse();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $profileId, $id)
    {
        $profileId = $request->user()->profile->id;
        
        $recipe = Recipe::where('profile_id',$profileId)->where('id',$id)->first();
        
        if($recipe === null){
            throw new \Exception("Recipe doesn't belong to the user.");
        }
        
        $this->model = $recipe->where('id',$id)->where('profile_id',$profileId)->update($request->except(['profile_id']));
        
        return $this->sendResponse();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $profileId = $request->user()->profile->id;
    
        $recipe = Recipe::where('profile_id',$profileId)->where('id',$id)->first();
    
        if($recipe){
            throw new \Exception("Recipe doesn't belong to the user.");
        }
    
        $this->model = $recipe->where('id',$id)->where('profile_id',$profileId)->delete();
    
        return $this->sendResponse();
    }

    public function recipeImages($id)
    {
        $recipe = Recipe::select('image')->findOrFail($id);
        $path = storage_path("app/" . Recipe::$fileInputs['image'] . "/" . $recipe->image);
        return response()->file($path);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function like(Request $request, $profileId, $recipeId)
    {
        $profileId = $request->user()->profile->id;
        $photoLike = RecipeLike::where('profile_id', $profileId)->where('recipe_id', $recipeId)->first();
        if($photoLike != null) {
            $this->model = RecipeLike::where('profile_id', $profileId)->where('recipe_id', $recipeId)->delete();
        } else {
            $this->model = RecipeLike::create(['profile_id' => $profileId, 'recipe_id' => $recipeId]);
        }
        return $this->sendResponse();
    }
}
